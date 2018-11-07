<?php

class grocery_CRUD_States extends grocery_CRUD_Layout {

  const STATE_UNKNOWN = 0;
  const STATE_LIST = 1;
  const STATE_ADD = 2;
  const STATE_EDIT = 3;
  const STATE_DELETE = 4;
  const STATE_INSERT = 5;
  const STATE_READ = 18;
  const STATE_DELETE_MULTIPLE = '19';

  protected $states = array(
      0 => 'unknown',
      1 => 'list',
      2 => 'add',
      3 => 'edit',
      4 => 'delete',
      5 => 'insert',
      6 => 'update',
      7 => 'ajax_list',
      8 => 'ajax_list_info',
      9 => 'insert_validation',
      10 => 'update_validation',
      11 => 'upload_file',
      12 => 'delete_file',
      13 => 'ajax_relation',
      14 => 'ajax_relation_n_n',
      15 => 'success',
      16 => 'export',
      17 => 'print',
      18 => 'read',
      19 => 'delete_multiple'
  );

  public function getStateInfo() {
    $state_code = $this->getStateCode();
    $segment_object = $this->get_state_info_from_url();

    $first_parameter = $segment_object->first_parameter;
    $second_parameter = $segment_object->second_parameter;

    $state_info = (object) array();

    switch ($state_code) {
      case self::STATE_LIST:
      case self::STATE_ADD:
        //for now... do nothing! Keeping this switch here in case we need any information at the future.
        break;

      case self::STATE_EDIT:
      case self::STATE_READ:
        if ($first_parameter !== null) {
          $state_info = (object) array('primary_key' => $first_parameter);
        } else {
          throw new Exception('On the state "edit" the Primary key cannot be null', 6);
          die();
        }
        break;

      case self::STATE_DELETE:
        if ($first_parameter !== null) {
          $state_info = (object) array('primary_key' => $first_parameter);
        } else {
          throw new Exception('On the state "delete" the Primary key cannot be null', 7);
          die();
        }
        break;

      case self::STATE_DELETE_MULTIPLE:
        if (!empty($_POST) && !empty($_POST['ids']) && is_array($_POST['ids'])) {
          $state_info = (object) array('ids' => $_POST['ids']);
        } else {
          throw new Exception('On the state "Delete Multiple" you need send the ids as a post array.');
          die();
        }
        break;

      case self::STATE_INSERT:
        if (!empty($_POST)) {
          $state_info = (object) array('unwrapped_data' => $_POST);
        } else {
          throw new Exception('On the state "insert" you must have post data', 8);
          die();
        }
        break;

      case 6:
        if (!empty($_POST) && $first_parameter !== null) {
          $state_info = (object) array('primary_key' => $first_parameter, 'unwrapped_data' => $_POST);
        } elseif (empty($_POST)) {
          throw new Exception('On the state "update" you must have post data', 9);
          die();
        } else {
          throw new Exception('On the state "update" the Primary key cannot be null', 10);
          die();
        }
        break;

      case 7:
      case 8:
      case 16: //export to excel
      case 17: //print
        $state_info = (object) array();
        if (!empty($_POST['per_page'])) {
          $state_info->per_page = is_numeric($_POST['per_page']) ? $_POST['per_page'] : null;
        }
        if (!empty($_POST['page'])) {
          $state_info->page = is_numeric($_POST['page']) ? $_POST['page'] : null;
        }
        //If we request an export or a print we don't care about what page we are
        if ($state_code === 16 || $state_code === 17) {
          $state_info->page = 1;
          $state_info->per_page = 1000000; //a very big number!
        }
        if (!empty($_POST['order_by'][0])) {
          $state_info->order_by = $_POST['order_by'];
        }
        if (!empty($_POST['search_text'])) {
          if (empty($_POST['search_field'])) {
            $search_text = strip_tags($_POST['search_field']);
            $state_info->search = (object) array('field' => null, 'text' => $_POST['search_text']);
          } else {
            if (is_array($_POST['search_field'])) {
              $search_array = array();
              foreach ($_POST['search_field'] as $search_key => $search_field_name) {
                $search_array[$search_field_name] = !empty($_POST['search_text'][$search_key]) ? $_POST['search_text'][$search_key] : '';
              }
              $state_info->search = $search_array;
            } else {
              $state_info->search = (object) array(
                          'field' => strip_tags($_POST['search_field']),
                          'text' => $_POST['search_text']);
            }
          }
        }
        break;

      case 9:

        break;

      case 10:
        if ($first_parameter !== null) {
          $state_info = (object) array('primary_key' => $first_parameter);
        }
        break;

      case 11:
        $state_info->field_name = $first_parameter;
        break;

      case 12:
        $state_info->field_name = $first_parameter;
        $state_info->file_name = $second_parameter;
        break;

      case 13:
        $state_info->field_name = $_POST['field_name'];
        $state_info->search = $_POST['term'];
        break;

      case 14:
        $state_info->field_name = $_POST['field_name'];
        $state_info->search = $_POST['term'];
        break;

      case 15:
        $state_info = (object) array(
                    'primary_key' => $first_parameter,
                    'success_message' => true
        );
        break;
    }

    return $state_info;
  }

  protected function getStateCode() {
    $state_string = $this->get_state_info_from_url()->operation;

    if ($state_string != 'unknown' && in_array($state_string, $this->states))
      $state_code = array_search($state_string, $this->states);
    else
      $state_code = 0;

    return $state_code;
  }

  protected function state_url($url = '', $is_list_page = false) {
    //Easy scenario, we had set the crud_url_path
    if (!empty($this->crud_url_path)) {
      $state_url = !empty($this->list_url_path) && $is_list_page ?
              $this->list_url_path :
              $this->crud_url_path . '/' . $url;
    } else {
      //Complicated scenario. The crud_url_path is not specified so we are
      //trying to understand what is going on from the URL.
      $ci = &get_instance();

      $segment_object = $this->get_state_info_from_url();
      $method_name = $this->get_method_name();
      $segment_position = $segment_object->segment_position;

      $state_url_array = array();

      if (sizeof($ci->uri->segments) > 0) {
        foreach ($ci->uri->segments as $num => $value) {
          $state_url_array[$num] = $value;
          if ($num == ($segment_position - 1))
            break;
        }

        if ($method_name == 'index' && !in_array('index', $state_url_array)) //there is a scenario that you don't have the index to your url
          $state_url_array[$num + 1] = 'index';
      }

      $state_url = site_url(implode('/', $state_url_array) . '/' . $url);
    }

    return $state_url;
  }

  protected function get_state_info_from_url() {
    $ci = &get_instance();

    $segment_position = count($ci->uri->segments) + 1;
    $operation = 'list';

    $segements = $ci->uri->segments;
    foreach ($segements as $num => $value) {
      if ($value != 'unknown' && in_array($value, $this->states)) {
        $segment_position = (int) $num;
        $operation = $value; //I don't have a "break" here because I want to ensure that is the LAST segment with name that is in the array.
      }
    }

    $function_name = $this->get_method_name();

    if ($function_name == 'index' && !in_array('index', $ci->uri->segments))
      $segment_position++;

    $first_parameter = isset($segements[$segment_position + 1]) ? $segements[$segment_position + 1] : null;
    $second_parameter = isset($segements[$segment_position + 2]) ? $segements[$segment_position + 2] : null;

    return (object) array('segment_position' => $segment_position, 'operation' => $operation, 'first_parameter' => $first_parameter, 'second_parameter' => $second_parameter);
  }

  protected function get_method_hash() {
    $ci = &get_instance();

    $state_info = $this->get_state_info_from_url();
    $extra_values = $ci->uri->segment($state_info->segment_position - 1) != $this->get_method_name() ? $ci->uri->segment($state_info->segment_position - 1) : '';

    return $this->crud_url_path !== null ? md5($this->crud_url_path) : md5($this->get_controller_name() . $this->get_method_name() . $extra_values);
  }

  protected function get_method_name() {
    $ci = &get_instance();
    return $ci->router->method;
  }

  protected function get_controller_name() {
    $ci = &get_instance();
    return $ci->router->class;
  }

  public function getState() {
    return $this->states[$this->getStateCode()];
  }

  protected function getListUrl() {
    return $this->state_url('', true);
  }

  protected function getAjaxListUrl() {
    return $this->state_url('ajax_list');
  }

  protected function getExportToExcelUrl() {
    return $this->state_url('export');
  }

  protected function getPrintUrl() {
    return $this->state_url('print');
  }

  protected function getAjaxListInfoUrl() {
    return $this->state_url('ajax_list_info');
  }

  protected function getAddUrl() {
    return $this->state_url('add');
  }

  protected function getInsertUrl() {
    return $this->state_url('insert');
  }

  protected function getValidationInsertUrl() {
    return $this->state_url('insert_validation');
  }

  protected function getValidationUpdateUrl($primary_key = null) {
    if ($primary_key === null)
      return $this->state_url('update_validation');
    else
      return $this->state_url('update_validation/' . $primary_key);
  }

  protected function getEditUrl($primary_key = null) {
    if ($primary_key === null)
      return $this->state_url('edit');
    else
      return $this->state_url('edit/' . $primary_key);
  }

  protected function getReadUrl($primary_key = null) {
    if ($primary_key === null)
      return $this->state_url('read');
    else
      return $this->state_url('read/' . $primary_key);
  }

  protected function getUpdateUrl($state_info) {
    return $this->state_url('update/' . $state_info->primary_key);
  }

  protected function getDeleteUrl($state_info = null) {
    if (empty($state_info)) {
      return $this->state_url('delete');
    } else {
      return $this->state_url('delete/' . $state_info->primary_key);
    }
  }

  protected function getDeleteMultipleUrl() {
    return $this->state_url('delete_multiple');
  }

  protected function getListSuccessUrl($primary_key = null) {
    if (empty($primary_key))
      return $this->state_url('success', true);
    else
      return $this->state_url('success/' . $primary_key, true);
  }

  protected function getUploadUrl($field_name) {
    return $this->state_url('upload_file/' . $field_name);
  }

  protected function getFileDeleteUrl($field_name) {
    return $this->state_url('delete_file/' . $field_name);
  }

  protected function getAjaxRelationUrl() {
    return $this->state_url('ajax_relation');
  }

  protected function getAjaxRelationManytoManyUrl() {
    return $this->state_url('ajax_relation_n_n');
  }

}
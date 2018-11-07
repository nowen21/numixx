<?php


class grocery_CRUD_Layout extends grocery_CRUD_Model_Driver {

  private $theme_path = null;
  private $views_as_string = '';
  private $echo_and_die = false;
  protected $theme = null;
  protected $default_true_false_text = array('inactive', 'active');
  protected $css_files = array();
  protected $js_files = array();
  protected $js_lib_files = array();
  protected $js_config_files = array();

  protected function set_basic_Layout() {
    if (!file_exists($this->theme_path . $this->theme . '/views/list_template.php')) {
      throw new Exception('The template does not exist. Please check your files and try again.', 12);
      die();
    }
  }

  protected function showList($ajax = false, $state_info = null) {
    $data = $this->get_common_data();

    $data->order_by = $this->order_by;

    $data->types = $this->get_field_types();

    $data->list = $this->get_list();
    $data->list = $this->change_list($data->list, $data->types);
    $data->list = $this->change_list_add_actions($data->list);

    $data->total_results = $this->get_total_results();

    $data->columns = $this->get_columns();

    $data->success_message = $this->get_success_message_at_list($state_info);

    $data->primary_key = $this->get_primary_key();
    $data->add_url = $this->getAddUrl();
    $data->edit_url = $this->getEditUrl();
    $data->delete_url = $this->getDeleteUrl();
    $data->delete_multiple_url = $this->getDeleteMultipleUrl();
    $data->read_url = $this->getReadUrl();
    $data->ajax_list_url = $this->getAjaxListUrl();
    $data->ajax_list_info_url = $this->getAjaxListInfoUrl();
    $data->export_url = $this->getExportToExcelUrl();
    $data->print_url = $this->getPrintUrl();
    $data->actions = $this->actions;
    $data->unique_hash = $this->get_method_hash();
    $data->order_by = $this->order_by;

    $data->unset_add = $this->unset_add;
    $data->unset_edit = $this->unset_edit;
    $data->unset_read = $this->unset_read;
    $data->unset_delete = $this->unset_delete;
    $data->unset_export = $this->unset_export;
    $data->unset_print = $this->unset_print;

    $default_per_page = $this->config->default_per_page;
    $data->paging_options = $this->config->paging_options;
    $data->default_per_page = is_numeric($default_per_page) && $default_per_page > 1 && in_array($default_per_page, $data->paging_options) ? $default_per_page : 25;

    if ($data->list === false) {
      throw new Exception('It is impossible to get data. Please check your model and try again.', 13);
      $data->list = array();
    }

    foreach ($data->list as $num_row => $row) {
      $data->list[$num_row]->primary_key_value = $row->{$data->primary_key};
      $data->list[$num_row]->edit_url = $data->edit_url . '/' . $row->{$data->primary_key};
      $data->list[$num_row]->delete_url = $data->delete_url . '/' . $row->{$data->primary_key};
      $data->list[$num_row]->read_url = $data->read_url . '/' . $row->{$data->primary_key};
    }

    if (!$ajax) {
      $this->_add_js_vars(array('dialog_forms' => $this->config->dialog_forms));

      $data->list_view = $this->_theme_view('list.php', $data, true);
      $this->_theme_view('list_template.php', $data);
    } else {
      $this->set_echo_and_die();
      $this->_theme_view('list.php', $data);
    }
  }

  protected function exportToExcel($state_info = null) {
    $data = $this->get_common_data();

    $data->order_by = $this->order_by;
    $data->types = $this->get_field_types();

    $data->list = $this->get_list();
    $data->list = $this->change_list($data->list, $data->types);
    $data->list = $this->change_list_add_actions($data->list);

    $data->total_results = $this->get_total_results();

    $data->columns = $this->get_columns();
    $data->primary_key = $this->get_primary_key();

    @ob_end_clean();
    $this->_export_to_excel($data);
  }

  protected function _export_to_excel($data) {
    /**
     * No need to use an external library here. The only bad thing without using external library is that Microsoft Excel is complaining
     * that the file is in a different format than specified by the file extension. If you press "Yes" everything will be just fine.
     * */
    $string_to_export = "";
    foreach ($data->columns as $column) {
      $string_to_export .= $column->display_as . "\t";
    }
    $string_to_export .= "\n";

    foreach ($data->list as $num_row => $row) {
      foreach ($data->columns as $column) {
        $string_to_export .= $this->_trim_export_string($row->{$column->field_name}) . "\t";
      }
      $string_to_export .= "\n";
    }

    // Convert to UTF-16LE and Prepend BOM
    $string_to_export = "\xFF\xFE" . mb_convert_encoding($string_to_export, 'UTF-16LE', 'UTF-8');

    $filename = "export-" . date("Y-m-d_H:i:s") . ".xls";

    header('Content-type: application/vnd.ms-excel;charset=UTF-16LE');
    header('Content-Disposition: attachment; filename=' . $filename);
    header("Cache-Control: no-cache");
    echo $string_to_export;
    die();
  }

  protected function print_webpage($state_info = null) {
    $data = $this->get_common_data();

    $data->order_by = $this->order_by;
    $data->types = $this->get_field_types();

    $data->list = $this->get_list();
    $data->list = $this->change_list($data->list, $data->types);
    $data->list = $this->change_list_add_actions($data->list);

    $data->total_results = $this->get_total_results();

    $data->columns = $this->get_columns();
    $data->primary_key = $this->get_primary_key();

    @ob_end_clean();
    $this->_print_webpage($data);
  }

  protected function _print_webpage($data) {
    $string_to_print = "<meta charset=\"utf-8\" /><style type=\"text/css\" >
		#print-table{ color: #000; background: #fff; font-family: Verdana,Tahoma,Helvetica,sans-serif; font-size: 13px;}
		#print-table table tr td, #print-table table tr th{ border: 1px solid black; border-bottom: none; border-right: none; padding: 4px 8px 4px 4px}
		#print-table table{ border-bottom: 1px solid black; border-right: 1px solid black}
		#print-table table tr th{text-align: left;background: #ddd}
		#print-table table tr:nth-child(odd){background: #eee}
		</style>";
    $string_to_print .= "<div id='print-table'>";

    $string_to_print .= '<table width="100%" cellpadding="0" cellspacing="0" ><tr>';
    foreach ($data->columns as $column) {
      $string_to_print .= "<th>" . $column->display_as . "</th>";
    }
    $string_to_print .= "</tr>";

    foreach ($data->list as $num_row => $row) {
      $string_to_print .= "<tr>";
      foreach ($data->columns as $column) {
        $string_to_print .= "<td>" . $this->_trim_print_string($row->{$column->field_name}) . "</td>";
      }
      $string_to_print .= "</tr>";
    }

    $string_to_print .= "</table></div>";

    echo $string_to_print;
    die();
  }

  protected function _trim_export_string($value) {
    $value = str_replace(array("&nbsp;", "&amp;", "&gt;", "&lt;"), array(" ", "&", ">", "<"), $value);
    return strip_tags(str_replace(array("\t", "\n", "\r"), "", $value));
  }

  protected function _trim_print_string($value) {
    $value = str_replace(array("&nbsp;", "&amp;", "&gt;", "&lt;"), array(" ", "&", ">", "<"), $value);

    //If the value has only spaces and nothing more then add the whitespace html character
    if (str_replace(" ", "", $value) == "")
      $value = "&nbsp;";

    return strip_tags($value);
  }

  protected function set_echo_and_die() {
    $this->echo_and_die = true;
  }

  protected function unset_echo_and_die() {
    $this->echo_and_die = false;
  }

  protected function showListInfo() {
    $this->set_echo_and_die();

    $total_results = (int) $this->get_total_results();
    @ob_end_clean();
    echo json_encode(array('total_results' => $total_results));
    die();
  }

  protected function change_list_add_actions($list) {
    if (empty($this->actions))
      return $list;

    $primary_key = $this->get_primary_key();

    foreach ($list as $num_row => $row) {
      $actions_urls = array();
      foreach ($this->actions as $unique_id => $action) {
        if (!empty($action->url_callback)) {
          $actions_urls[$unique_id] = call_user_func($action->url_callback, $row->$primary_key, $row);
        } else {
          $actions_urls[$unique_id] = $action->url_has_http ?
                  $action->link_url . $row->$primary_key :
                  site_url($action->link_url . '/' . $row->$primary_key);
        }
      }
      $row->action_urls = $actions_urls;
    }

    return $list;
  }

  protected function change_list($list, $types) {
    $primary_key = $this->get_primary_key();
    $has_callbacks = !empty($this->callback_column) ? true : false;
    $output_columns = $this->get_columns();
    foreach ($list as $num_row => $row) {
      foreach ($output_columns as $column) {
        $field_name = $column->field_name;
        $field_value = isset($row->{$column->field_name}) ? $row->{$column->field_name} : null;
        if ($has_callbacks && isset($this->callback_column[$field_name]))
          $list[$num_row]->$field_name = call_user_func($this->callback_column[$field_name], $field_value, $row);
        elseif (isset($types[$field_name]))
          $list[$num_row]->$field_name = $this->change_list_value($types[$field_name], $field_value);
        else
          $list[$num_row]->$field_name = $field_value;
      }
    }

    return $list;
  }

  protected function showAddForm() {
    $this->set_js_lib($this->default_javascript_path . '/' . grocery_CRUD::JQUERY);

    $data = $this->get_common_data(); // crea un nuevo objeto
    $data->types = $this->get_field_types();
    // nuevo
$data->field_values = $this->get_add_values(null);

    $data->list_url = $this->getListUrl();
    $data->insert_url = $this->getInsertUrl();
    $data->validation_url = $this->getValidationInsertUrl();
    
    //$data->input_fields = $this->get_add_input_fields();
    // nuevo
    $data->input_fields = $this->get_add_input_fields($data->field_values);

    $data->fields = $this->get_add_fields();
    $data->hidden_fields = $this->get_add_hidden_fields();
    $data->unset_back_to_list = $this->unset_back_to_list;
    $data->unique_hash = $this->get_method_hash();
    $data->is_ajax = $this->_is_ajax();

    $this->_theme_view('add.php', $data);
    $this->_inline_js("var js_date_format = '" . $this->js_date_format . "';");

    $this->_get_ajax_results();
  }

  protected function showEditForm($state_info) {
    $this->set_js_lib($this->default_javascript_path . '/' . grocery_CRUD::JQUERY);

    $data = $this->get_common_data();
    $data->types = $this->get_field_types();

    $data->field_values = $this->get_edit_values($state_info->primary_key);

    $data->add_url = $this->getAddUrl();

    $data->list_url = $this->getListUrl();
    $data->update_url = $this->getUpdateUrl($state_info);
    $data->delete_url = $this->getDeleteUrl($state_info);
    $data->read_url = $this->getReadUrl($state_info->primary_key);
    $data->input_fields = $this->get_edit_input_fields($data->field_values);
    $data->unique_hash = $this->get_method_hash();

    $data->fields = $this->get_edit_fields();
    $data->hidden_fields = $this->get_edit_hidden_fields();
    $data->unset_back_to_list = $this->unset_back_to_list;

    $data->validation_url = $this->getValidationUpdateUrl($state_info->primary_key);
    $data->is_ajax = $this->_is_ajax();

    $this->_theme_view('edit.php', $data);
    $this->_inline_js("var js_date_format = '" . $this->js_date_format . "';");

    $this->_get_ajax_results();
  }

  protected function showReadForm($state_info) {
    $this->set_js_lib($this->default_javascript_path . '/' . grocery_CRUD::JQUERY);

    $data = $this->get_common_data();
    $data->types = $this->get_field_types();

    $data->field_values = $this->get_edit_values($state_info->primary_key);

    $data->add_url = $this->getAddUrl();

    $data->list_url = $this->getListUrl();
    $data->update_url = $this->getUpdateUrl($state_info);
    $data->delete_url = $this->getDeleteUrl($state_info);
    $data->read_url = $this->getReadUrl($state_info->primary_key);
    $data->input_fields = $this->get_read_input_fields($data->field_values);
    $data->unique_hash = $this->get_method_hash();

    $data->fields = $this->get_read_fields();
    $data->hidden_fields = $this->get_edit_hidden_fields();
    $data->unset_back_to_list = $this->unset_back_to_list;

    $data->validation_url = $this->getValidationUpdateUrl($state_info->primary_key);
    $data->is_ajax = $this->_is_ajax();

    $this->_theme_view('read.php', $data);
    $this->_inline_js("var js_date_format = '" . $this->js_date_format . "';");

    $this->_get_ajax_results();
  }

  protected function delete_layout($delete_result = true) {
    @ob_end_clean();
    if ($delete_result === false) {
      $error_message = '<p>' . $this->l('delete_error_message') . '</p>';

      echo json_encode(array('success' => $delete_result, 'error_message' => $error_message));
    } else {
      $success_message = '<p>' . $this->l('delete_success_message') . '</p>';

      echo json_encode(array('success' => true, 'success_message' => $success_message));
    }
    $this->set_echo_and_die();
  }

  protected function get_success_message_at_list($field_info = null) {
    if ($field_info !== null && isset($field_info->success_message) && $field_info->success_message) {
      if (!empty($field_info->primary_key) && !$this->unset_edit) {
        return $this->l('insert_success_message') . " <a class='go-to-edit-form' href='" . $this->getEditUrl($field_info->primary_key) . "'>" . $this->l('form_edit') . " {$this->subject}</a> ";
      } else {
        return $this->l('insert_success_message');
      }
    } else {
      return null;
    }
  }

  protected function insert_layout($insert_result = false) {
    @ob_end_clean();
    if ($insert_result === false) {
      echo json_encode(array('success' => false));
    } else {
      $success_message = '<p>' . $this->l('insert_success_message');

      if (!$this->unset_back_to_list && !empty($insert_result) && !$this->unset_edit) {
        $success_message .= " <a class='go-to-edit-form' href='" . $this->getEditUrl($insert_result) . "'>" . $this->l('form_edit') . " {$this->subject}</a> ";

        if (!$this->_is_ajax()) {
          $success_message .= $this->l('form_or');
        }
      }

      if (!$this->unset_back_to_list && !$this->_is_ajax()) {
        $success_message .= " <a href='" . $this->getListUrl() . "'>" . $this->l('form_go_back_to_list') . "</a>";
      }

      $success_message .= '</p>';

      echo json_encode(array(
          'success' => true,
          'insert_primary_key' => $insert_result,
          'success_message' => $success_message,
          'success_list_url' => $this->getListSuccessUrl($insert_result)
      ));
    }
    $this->set_echo_and_die();
  }

  protected function validation_layout($validation_result) {
    @ob_end_clean();
    echo json_encode($validation_result);
    $this->set_echo_and_die();
  }

  protected function upload_layout($upload_result, $field_name) {
    @ob_end_clean();
    if ($upload_result !== false && !is_string($upload_result) && empty($upload_result[0]->error)) {
      echo json_encode(
              (object) array(
                  'success' => true,
                  'files' => $upload_result
      ));
    } else {
      $result = (object) array('success' => false);
      if (is_string($upload_result))
        $result->message = $upload_result;
      if (!empty($upload_result[0]->error))
        $result->message = $upload_result[0]->error;

      echo json_encode($result);
    }

    $this->set_echo_and_die();
  }

  protected function delete_file_layout($upload_result) {
    @ob_end_clean();
    if ($upload_result !== false) {
      echo json_encode((object) array('success' => true));
    } else {
      echo json_encode((object) array('success' => false));
    }

    $this->set_echo_and_die();
  }

  public function set_css($css_file) {
    $this->css_files[sha1($css_file)] = base_url() . $css_file;
  }

  public function set_js($js_file) {
    $this->js_files[sha1($js_file)] = base_url() . $js_file;
  }

  public function set_js_lib($js_file) {
    $this->js_lib_files[sha1($js_file)] = base_url() . $js_file;
    $this->js_files[sha1($js_file)] = base_url() . $js_file;
  }

  public function set_js_config($js_file) {
    $this->js_config_files[sha1($js_file)] = base_url() . $js_file;
    $this->js_files[sha1($js_file)] = base_url() . $js_file;
  }

  public function is_IE7() {
    return isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') !== false) ? true : false;
  }

  public function get_css_files() {
    return $this->css_files;
  }

  public function get_js_files() {
    return $this->js_files;
  }

  public function get_js_lib_files() {
    return $this->js_lib_files;
  }

  public function get_js_config_files() {
    return $this->js_config_files;
  }

  /**
   * Load Javascripts
   * */
  protected function load_js_fancybox() {
    $this->set_css($this->default_css_path . '/jquery_plugins/fancybox/jquery.fancybox.css');

    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.fancybox-1.3.4.js');
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.easing-1.3.pack.js');
  }

  protected function load_js_chosen() {
    $this->set_css($this->default_css_path . '/jquery_plugins/chosen/chosen.css');
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.chosen.min.js');
  }

  protected function load_js_jqueryui() {
    $this->set_css($this->default_css_path . '/ui/simple/' . grocery_CRUD::JQUERY_UI_CSS);
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/ui/' . grocery_CRUD::JQUERY_UI_JS);
  }

  protected function load_js_uploader() {
    $this->set_css($this->default_css_path . '/ui/simple/' . grocery_CRUD::JQUERY_UI_CSS);
    $this->set_css($this->default_css_path . '/jquery_plugins/file_upload/file-uploader.css');
    $this->set_css($this->default_css_path . '/jquery_plugins/file_upload/jquery.fileupload-ui.css');

    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/ui/' . grocery_CRUD::JQUERY_UI_JS);
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/tmpl.min.js');
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/load-image.min.js');

    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.iframe-transport.js');
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.fileupload.js');
    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.fileupload.config.js');
  }

  protected function get_layout() {
    $js_files = $this->get_js_files();
    $css_files = $this->get_css_files();

    $js_lib_files = $this->get_js_lib_files();
    $js_config_files = $this->get_js_config_files();

    if ($this->unset_jquery) {
      unset($js_files[sha1($this->default_javascript_path . '/' . grocery_CRUD::JQUERY)]);
    }

    if ($this->unset_jquery_ui) {
      unset($css_files[sha1($this->default_css_path . '/ui/simple/' . grocery_CRUD::JQUERY_UI_CSS)]);
      unset($js_files[sha1($this->default_javascript_path . '/jquery_plugins/ui/' . grocery_CRUD::JQUERY_UI_JS)]);
    }

    if ($this->unset_bootstrap) {
      unset($js_files[sha1($this->default_theme_path . '/bootstrap/js/bootstrap/dropdown.js')]);
      unset($js_files[sha1($this->default_theme_path . '/bootstrap/js/bootstrap/modal.js')]);
      unset($js_files[sha1($this->default_theme_path . '/bootstrap/js/bootstrap/dropdown.min.js')]);
      unset($js_files[sha1($this->default_theme_path . '/bootstrap/js/bootstrap/modal.min.js')]);
      unset($css_files[sha1($this->default_theme_path . '/bootstrap/css/bootstrap/bootstrap.css')]);
      unset($css_files[sha1($this->default_theme_path . '/bootstrap/css/bootstrap/bootstrap.min.css')]);
    }

    if ($this->echo_and_die === false) {
      /** Initialize JavaScript variables */
      $js_vars = array(
          'default_javascript_path' => base_url() . $this->default_javascript_path,
          'default_css_path' => base_url() . $this->default_css_path,
          'default_texteditor_path' => base_url() . $this->default_texteditor_path,
          'default_theme_path' => base_url() . $this->default_theme_path,
          'base_url' => base_url()
      );
      $this->_add_js_vars($js_vars);

      return (object) array(
                  'js_files' => $js_files,
                  'js_lib_files' => $js_lib_files,
                  'js_config_files' => $js_config_files,
                  'css_files' => $css_files,
                  'output' => $this->views_as_string,
      );
    } elseif ($this->echo_and_die === true) {
      echo $this->views_as_string;
      die();
    }
  }

  protected function update_layout($update_result = false, $state_info = null) {
    @ob_end_clean();
    if ($update_result === false) {
      echo json_encode(array('success' => $update_result));
    } else {
      $success_message = '<p>' . $this->l('update_success_message');
      if (!$this->unset_back_to_list && !$this->_is_ajax()) {
        $success_message .= " <a href='" . $this->getListUrl() . "'>" . $this->l('form_go_back_to_list') . "</a>";
      }
      $success_message .= '</p>';

      echo json_encode(array(
          'success' => true,
          'insert_primary_key' => $update_result,
          'success_message' => $success_message,
          'success_list_url' => $this->getListSuccessUrl($state_info->primary_key)
      ));
    }
    $this->set_echo_and_die();
  }

  protected function get_integer_input($field_info, $value) {
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.numeric.min.js');
    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.numeric.config.js');
    $extra_attributes = '';
    if (!empty($field_info->db_max_length))
      $extra_attributes .= "maxlength='{$field_info->db_max_length}'";
    $input = "<input id='field-{$field_info->name}' name='{$field_info->name}' type='text' value='$value' class='numeric form-control' $extra_attributes />";
    return $input;
  }

  protected function get_true_false_input($field_info, $value) {
    $this->set_css($this->default_css_path . '/jquery_plugins/uniform/uniform.default.css');
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.uniform.min.js');
    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.uniform.config.js');

    $value_is_null = empty($value) && $value !== '0' && $value !== 0 ? true : false;

    $input = "<div class='pretty-radio-buttons'>";

    $true_string = is_array($field_info->extras) && array_key_exists(1, $field_info->extras) ? $field_info->extras[1] : $this->default_true_false_text[1];
    $checked = $value === '1' || ($value_is_null && $field_info->default === '1') ? "checked = 'checked'" : "";
    $input .= "<label><input id='field-{$field_info->name}-true' class='radio-uniform'  type='radio' name='{$field_info->name}' value='1' $checked /> " . $true_string . "</label> ";

    $false_string = is_array($field_info->extras) && array_key_exists(0, $field_info->extras) ? $field_info->extras[0] : $this->default_true_false_text[0];
    $checked = $value === '0' || ($value_is_null && $field_info->default === '0') ? "checked = 'checked'" : "";
    $input .= "<label><input id='field-{$field_info->name}-false' class='radio-uniform' type='radio' name='{$field_info->name}' value='0' $checked /> " . $false_string . "</label>";

    $input .= "</div>";

    return $input;
  }

  protected function get_string_input($field_info, $value) {
    $value = !is_string($value) ? '' : str_replace('"', "&quot;", $value);

    $extra_attributes = '';
    if (!empty($field_info->db_max_length)) {

      if (in_array($field_info->type, array("decimal", "float"))) {
        $decimal_lentgh = explode(",", $field_info->db_max_length);
        $decimal_lentgh = ((int) $decimal_lentgh[0]) + 1;

        $extra_attributes .= "maxlength='" . $decimal_lentgh . "'";
      } else {
        $extra_attributes .= "maxlength='{$field_info->db_max_length}'";
      }
    }
    $input = "<input id='field-{$field_info->name}' class='form-control' name='{$field_info->name}' type='text' value=\"$value\" $extra_attributes />";
    return $input;
  }

  protected function get_text_input($field_info, $value) {
    if ($field_info->extras == 'text_editor') {
      $editor = $this->config->default_text_editor;
      switch ($editor) {
        case 'ckeditor':
          $this->set_js_lib($this->default_texteditor_path . '/ckeditor/ckeditor.js');
          $this->set_js_lib($this->default_texteditor_path . '/ckeditor/adapters/jquery.js');
          $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.ckeditor.config.js');
          break;

        case 'tinymce':
          $this->set_js_lib($this->default_texteditor_path . '/tiny_mce/jquery.tinymce.js');
          $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.tine_mce.config.js');
          break;

        case 'markitup':
          $this->set_css($this->default_texteditor_path . '/markitup/skins/markitup/style.css');
          $this->set_css($this->default_texteditor_path . '/markitup/sets/default/style.css');

          $this->set_js_lib($this->default_texteditor_path . '/markitup/jquery.markitup.js');
          $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.markitup.config.js');
          break;
      }

      $class_name = $this->config->text_editor_type == 'minimal' ? 'mini-texteditor' : 'texteditor';

      $input = "<textarea id='field-{$field_info->name}' name='{$field_info->name}' class='$class_name' >$value</textarea>";
    } else {
      $input = "<textarea id='field-{$field_info->name}' name='{$field_info->name}'>$value</textarea>";
    }
    return $input;
  }

  protected function get_datetime_input($field_info, $value) {
    $this->set_css($this->default_css_path . '/ui/simple/' . grocery_CRUD::JQUERY_UI_CSS);
    $this->set_css($this->default_css_path . '/jquery_plugins/jquery.ui.datetime.css');
    $this->set_css($this->default_css_path . '/jquery_plugins/jquery-ui-timepicker-addon.css');
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/ui/' . grocery_CRUD::JQUERY_UI_JS);
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery-ui-timepicker-addon.js');

    if ($this->language !== 'english') {
      include($this->default_config_path . '/language_alias.php');
      if (array_key_exists($this->language, $language_alias)) {
        $i18n_date_js_file = $this->default_javascript_path . '/jquery_plugins/ui/i18n/datepicker/jquery.ui.datepicker-' . $language_alias[$this->language] . '.js';
        if (file_exists($i18n_date_js_file)) {
          $this->set_js_lib($i18n_date_js_file);
        }

        $i18n_datetime_js_file = $this->default_javascript_path . '/jquery_plugins/ui/i18n/timepicker/jquery-ui-timepicker-' . $language_alias[$this->language] . '.js';
        if (file_exists($i18n_datetime_js_file)) {
          $this->set_js_lib($i18n_datetime_js_file);
        }
      }
    }

    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery-ui-timepicker-addon.config.js');

    if (!empty($value) && $value != '0000-00-00 00:00:00' && $value != '1970-01-01 00:00:00') {
      list($year, $month, $day) = explode('-', substr($value, 0, 10));
      $date = date($this->php_date_format, mktime(0, 0, 0, $month, $day, $year));
      $datetime = $date . substr($value, 10);
    } else {
      $datetime = '';
    }
    $input = "<input id='field-{$field_info->name}' name='{$field_info->name}' type='text' value='$datetime' maxlength='19' class='datetime-input form-control' />
		<a class='datetime-input-clear' tabindex='-1'>" . $this->l('form_button_clear') . "</a>
		({$this->ui_date_format}) hh:mm:ss";
    return $input;
  }

  protected function get_hidden_input($field_info, $value) {
    if ($field_info->extras !== null && $field_info->extras != false)
      $value = $field_info->extras;
    $input = "<input id='field-{$field_info->name}' type='hidden' name='{$field_info->name}' value='$value' />";
    return $input;
  }

  protected function get_password_input($field_info, $value) {
    $value = !is_string($value) ? '' : $value;

    $extra_attributes = '';
    if (!empty($field_info->db_max_length))
      $extra_attributes .= "maxlength='{$field_info->db_max_length}'";
    $input = "<input id='field-{$field_info->name}' class='form-control' name='{$field_info->name}' type='password' value='$value' $extra_attributes />";
    return $input;
  }

  protected function get_date_input($field_info, $value) {
    $this->set_css($this->default_css_path . '/ui/simple/' . grocery_CRUD::JQUERY_UI_CSS);
    $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/ui/' . grocery_CRUD::JQUERY_UI_JS);

    if ($this->language !== 'english') {
      include($this->default_config_path . '/language_alias.php');
      if (array_key_exists($this->language, $language_alias)) {
        $i18n_date_js_file = $this->default_javascript_path . '/jquery_plugins/ui/i18n/datepicker/jquery.ui.datepicker-' . $language_alias[$this->language] . '.js';
        if (file_exists($i18n_date_js_file)) {
          $this->set_js_lib($i18n_date_js_file);
        }
      }
    }

    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.datepicker.config.js');

    if (!empty($value) && $value != '0000-00-00' && $value != '1970-01-01') {
      list($year, $month, $day) = explode('-', substr($value, 0, 10));
      $date = date($this->php_date_format, mktime(0, 0, 0, $month, $day, $year));
    } else {
      $date = '';
    }

    $input = "<input id='field-{$field_info->name}' name='{$field_info->name}' type='text' value='$date' maxlength='10' class='datepicker-input form-control' />
		<a class='datepicker-input-clear' tabindex='-1'>" . $this->l('form_button_clear') . "</a> (" . $this->ui_date_format . ")";
    return $input;
  }

  protected function get_dropdown_input($field_info, $value) {
    $this->load_js_chosen();
    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.chosen.config.js');

    $select_title = str_replace('{field_display_as}', $field_info->display_as, $this->l('set_relation_title'));

    $input = "<select id='field-{$field_info->name}' name='{$field_info->name}' class='chosen-select' data-placeholder='" . $select_title . "'>";
    $options = array('' => '') + $field_info->extras;
    foreach ($options as $option_value => $option_label) {
      $selected = !empty($value) && $value == $option_value ? "selected='selected'" : '';
      $input .= "<option value='$option_value' $selected >$option_label</option>";
    }

    $input .= "</select>";
    return $input;
  }

  protected function get_enum_input($field_info, $value) {
    $this->load_js_chosen();
    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.chosen.config.js');

    $select_title = str_replace('{field_display_as}', $field_info->display_as, $this->l('set_relation_title'));

    $input = "<select id='field-{$field_info->name}' name='{$field_info->name}' class='chosen-select' data-placeholder='" . $select_title . "'>";
    $options_array = $field_info->extras !== false && is_array($field_info->extras) ? $field_info->extras : explode("','", substr($field_info->db_max_length, 1, -1));
    $options_array = array('' => '') + $options_array;

    foreach ($options_array as $option) {
      $selected = !empty($value) && $value == $option ? "selected='selected'" : '';
      $input .= "<option value='$option' $selected >$option</option>";
    }

    $input .= "</select>";
    return $input;
  }

  protected function get_readonly_input($field_info, $value) {
    $read_only_value = "&nbsp;";

    if (!empty($value) && !is_array($value)) {
      $read_only_value = $value;
    } elseif (is_array($value)) {
      $all_values = array_values($value);
      $read_only_value = implode(", ", $all_values);
    }

    return '<div id="field-' . $field_info->name . '" class="readonly_label">' . $read_only_value . '</div>';
  }

  protected function get_set_input($field_info, $value) {
    $this->load_js_chosen();
    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.chosen.config.js');

    $options_array = $field_info->extras !== false && is_array($field_info->extras) ? $field_info->extras : explode("','", substr($field_info->db_max_length, 1, -1));
    $selected_values = !empty($value) ? explode(",", $value) : array();

    $select_title = str_replace('{field_display_as}', $field_info->display_as, $this->l('set_relation_title'));
    $input = "<select id='field-{$field_info->name}' name='{$field_info->name}[]' multiple='multiple' size='8' class='chosen-multiple-select' data-placeholder='$select_title' style='width:510px;' >";

    foreach ($options_array as $option) {
      $selected = !empty($value) && in_array($option, $selected_values) ? "selected='selected'" : '';
      $input .= "<option value='$option' $selected >$option</option>";
    }

    $input .= "</select>";

    return $input;
  }

  protected function get_multiselect_input($field_info, $value) {
    $this->load_js_chosen();
    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.chosen.config.js');

    $options_array = $field_info->extras;
    $selected_values = !empty($value) ? explode(",", $value) : array();

    $select_title = str_replace('{field_display_as}', $field_info->display_as, $this->l('set_relation_title'));
    $input = "<select id='field-{$field_info->name}' name='{$field_info->name}[]' multiple='multiple' size='8' class='chosen-multiple-select' data-placeholder='$select_title' style='width:510px;' >";

    foreach ($options_array as $option_value => $option_label) {
      $selected = !empty($value) && in_array($option_value, $selected_values) ? "selected='selected'" : '';
      $input .= "<option value='$option_value' $selected >$option_label</option>";
    }

    $input .= "</select>";

    return $input;
  }

  protected function get_relation_input($field_info, $value) {
    $this->load_js_chosen();
    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.chosen.config.js');

    $ajax_limitation = 10000;
    $total_rows = $this->get_relation_total_rows($field_info->extras);


    //Check if we will use ajax for our queries or just clien-side javascript
    $using_ajax = $total_rows > $ajax_limitation ? true : false;

    //We will not use it for now. It is not ready yet. Probably we will have this functionality at version 1.4
    $using_ajax = false;

    //If total rows are more than the limitation, use the ajax plugin
    $ajax_or_not_class = $using_ajax ? 'chosen-select' : 'chosen-select';

    $this->_inline_js("var ajax_relation_url = '" . $this->getAjaxRelationUrl() . "';\n");

    $select_title = str_replace('{field_display_as}', $field_info->display_as, $this->l('set_relation_title'));
    $input = "<select id='field-{$field_info->name}'  name='{$field_info->name}' class='$ajax_or_not_class' data-placeholder='$select_title' style='width:300px'>";
    $input .= "<option value=''></option>";

    if (!$using_ajax) {
      $options_array = $this->get_relation_array($field_info->extras);
      foreach ($options_array as $option_value => $option) {
        $selected = !empty($value) && $value == $option_value ? "selected='selected'" : '';
        $input .= "<option value='$option_value' $selected >$option</option>";
      }
    } elseif (!empty($value) || (is_numeric($value) && $value == '0')) { //If it's ajax then we only need the selected items and not all the items
      $selected_options_array = $this->get_relation_array($field_info->extras, $value);
      foreach ($selected_options_array as $option_value => $option) {
        $input .= "<option value='$option_value'selected='selected' >$option</option>";
      }
    }

    $input .= "</select>";
    return $input;
  }

  protected function get_relation_readonly_input($field_info, $value) {
    $options_array = $this->get_relation_array($field_info->extras);

    $value = isset($options_array[$value]) ? $options_array[$value] : '';

    return $this->get_readonly_input($field_info, $value);
  }

  protected function get_upload_file_readonly_input($field_info, $value) {
    $file = $file_url = base_url() . $field_info->extras->upload_path . '/' . $value;

    $value = !empty($value) ? '<a href="' . $file . '" target="_blank">' . $value . '</a>' : '';

    return $this->get_readonly_input($field_info, $value);
  }

  protected function get_relation_n_n_input($field_info_type, $selected_values) {
    $has_priority_field = !empty($field_info_type->extras->priority_field_relation_table) ? true : false;
    $is_ie_7 = isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') !== false) ? true : false;

    if ($has_priority_field || $is_ie_7) {
      $this->set_css($this->default_css_path . '/ui/simple/' . grocery_CRUD::JQUERY_UI_CSS);
      $this->set_css($this->default_css_path . '/jquery_plugins/ui.multiselect.css');
      $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/ui/' . grocery_CRUD::JQUERY_UI_JS);
      $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/ui.multiselect.min.js');
      $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.multiselect.js');

      if ($this->language !== 'english') {
        include($this->default_config_path . '/language_alias.php');
        if (array_key_exists($this->language, $language_alias)) {
          $i18n_date_js_file = $this->default_javascript_path . '/jquery_plugins/ui/i18n/multiselect/ui-multiselect-' . $language_alias[$this->language] . '.js';
          if (file_exists($i18n_date_js_file)) {
            $this->set_js_lib($i18n_date_js_file);
          }
        }
      }
    } else {
      $this->set_css($this->default_css_path . '/jquery_plugins/chosen/chosen.css');
      $this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.chosen.min.js');
      $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.chosen.config.js');
    }

    $this->_inline_js("var ajax_relation_url = '" . $this->getAjaxRelationUrl() . "';\n");

    $field_info = $this->relation_n_n[$field_info_type->name]; //As we use this function the relation_n_n exists, so don't need to check
    $unselected_values = $this->get_relation_n_n_unselected_array($field_info, $selected_values);

    if (empty($unselected_values) && empty($selected_values)) {
      $input = "Please add {$field_info_type->display_as} first";
    } else {
      $css_class = $has_priority_field || $is_ie_7 ? 'multiselect' : 'chosen-multiple-select';
      $width_style = $has_priority_field || $is_ie_7 ? '' : 'width:510px;';

      $select_title = str_replace('{field_display_as}', $field_info_type->display_as, $this->l('set_relation_title'));
      $input = "<select id='field-{$field_info_type->name}' name='{$field_info_type->name}[]' multiple='multiple' size='8' class='$css_class' data-placeholder='$select_title' style='$width_style' >";

      if (!empty($unselected_values))
        foreach ($unselected_values as $id => $name) {
          $input .= "<option value='$id'>$name</option>";
        }

      if (!empty($selected_values))
        foreach ($selected_values as $id => $name) {
          $input .= "<option value='$id' selected='selected'>$name</option>";
        }

      $input .= "</select>";
    }

    return $input;
  }

  protected function _convert_bytes_ui_to_bytes($bytes_ui) {
    $bytes_ui = str_replace(' ', '', $bytes_ui);
    if (strstr($bytes_ui, 'MB'))
      $bytes = (int) (str_replace('MB', '', $bytes_ui)) * 1024 * 1024;
    elseif (strstr($bytes_ui, 'KB'))
      $bytes = (int) (str_replace('KB', '', $bytes_ui)) * 1024;
    elseif (strstr($bytes_ui, 'B'))
      $bytes = (int) (str_replace('B', '', $bytes_ui));
    else
      $bytes = (int) ($bytes_ui);

    return $bytes;
  }

  protected function get_upload_file_input($field_info, $value) {
    $this->load_js_uploader();

    //Fancybox
    $this->load_js_fancybox();

    $this->set_js_config($this->default_javascript_path . '/jquery_plugins/config/jquery.fancybox.config.js');

    $unique = mt_rand();

    $allowed_files = $this->config->file_upload_allow_file_types;
    $allowed_files_ui = '.' . str_replace('|', ',.', $allowed_files);
    $max_file_size_ui = $this->config->file_upload_max_file_size;
    $max_file_size_bytes = $this->_convert_bytes_ui_to_bytes($max_file_size_ui);

    $this->_inline_js('
			var upload_info_' . $unique . ' = {
				accepted_file_types: /(\\.|\\/)(' . $allowed_files . ')$/i,
				accepted_file_types_ui : "' . $allowed_files_ui . '",
				max_file_size: ' . $max_file_size_bytes . ',
				max_file_size_ui: "' . $max_file_size_ui . '"
			};

			var string_upload_file 	= "' . $this->l('form_upload_a_file') . '";
			var string_delete_file 	= "' . $this->l('string_delete_file') . '";
			var string_progress 			= "' . $this->l('string_progress') . '";
			var error_on_uploading 			= "' . $this->l('error_on_uploading') . '";
			var message_prompt_delete_file 	= "' . $this->l('message_prompt_delete_file') . '";

			var error_max_number_of_files 	= "' . $this->l('error_max_number_of_files') . '";
			var error_accept_file_types 	= "' . $this->l('error_accept_file_types') . '";
			var error_max_file_size 		= "' . str_replace("{max_file_size}", $max_file_size_ui, $this->l('error_max_file_size')) . '";
			var error_min_file_size 		= "' . $this->l('error_min_file_size') . '";

			var base_url = "' . base_url() . '";
			var upload_a_file_string = "' . $this->l('form_upload_a_file') . '";
		');

    $uploader_display_none = empty($value) ? "" : "display:none;";
    $file_display_none = empty($value) ? "display:none;" : "";

    $is_image = !empty($value) &&
            ( substr($value, -4) == '.jpg' || substr($value, -4) == '.png' || substr($value, -5) == '.jpeg' || substr($value, -4) == '.gif' || substr($value, -5) == '.tiff') ? true : false;

    $image_class = $is_image ? 'image-thumbnail' : '';

    $input = '<span class="fileinput-button qq-upload-button" id="upload-button-' . $unique . '" style="' . $uploader_display_none . '">
			<span>' . $this->l('form_upload_a_file') . '</span>
			<input type="file" name="' . $this->_unique_field_name($field_info->name) . '" class="gc-file-upload" rel="' . $this->getUploadUrl($field_info->name) . '" id="' . $unique . '">
			<input class="hidden-upload-input" type="hidden" name="' . $field_info->name . '" value="' . $value . '" rel="' . $this->_unique_field_name($field_info->name) . '" />
		</span>';

    $this->set_css($this->default_css_path . '/jquery_plugins/file_upload/fileuploader.css');

    $file_url = base_url() . $field_info->extras->upload_path . '/' . $value;

    $input .= "<div id='uploader_$unique' rel='$unique' class='grocery-crud-uploader' style='$uploader_display_none'></div>";
    $input .= "<div id='success_$unique' class='upload-success-url' style='$file_display_none padding-top:7px;'>";
    $input .= "<a href='" . $file_url . "' id='file_$unique' class='open-file";
    $input .= $is_image ? " $image_class'><img src='" . $file_url . "' height='50px'>" : "' target='_blank'>$value";
    $input .= "</a> ";
    $input .= "<a href='javascript:void(0)' id='delete_$unique' class='delete-anchor'>" . $this->l('form_upload_delete') . "</a> ";
    $input .= "</div><div style='clear:both'></div>";
    $input .= "<div id='loading-$unique' style='display:none'><span id='upload-state-message-$unique'></span> <span class='qq-upload-spinner'></span> <span id='progress-$unique'></span></div>";
    $input .= "<div style='display:none'><a href='" . $this->getUploadUrl($field_info->name) . "' id='url_$unique'></a></div>";
    $input .= "<div style='display:none'><a href='" . $this->getFileDeleteUrl($field_info->name) . "' id='delete_url_$unique' rel='$value' ></a></div>";

    return $input;
  }

  protected function get_add_hidden_fields() {
    return $this->add_hidden_fields;
  }

  protected function get_edit_hidden_fields() {
    return $this->edit_hidden_fields;
  }

  protected function get_add_input_fields($field_values = null) {
    $fields = $this->get_add_fields();
    $types = $this->get_field_types();

    $input_fields = array();

    foreach ($fields as $field_num => $field) {
      $field_info = $types[$field->field_name];

      $field_value = !empty($field_values) && isset($field_values->{$field->field_name}) ? $field_values->{$field->field_name} : null;

      if (!isset($this->callback_add_field[$field->field_name])) {
        $field_input = $this->get_field_input($field_info, $field_value);
      } else {
        $field_input = $field_info;
        $field_input->input = call_user_func($this->callback_add_field[$field->field_name], $field_value, null, $field_info);
      // nuevo
        if ($field_input->input === False) {
					$field_input = $this->get_field_input($field_info, $field_value);
				}
      }

      switch ($field_info->crud_type) {
        case 'invisible':
          unset($this->add_fields[$field_num]);
          unset($fields[$field_num]);
          continue;
          break;
        case 'hidden':
          $this->add_hidden_fields[] = $field_input;
          unset($this->add_fields[$field_num]);
          unset($fields[$field_num]);
          continue;
          break;
      }

      $input_fields[$field->field_name] = $field_input;
    }

    return $input_fields;
  }

  protected function get_edit_input_fields($field_values = null) {
    $fields = $this->get_edit_fields();
    $types = $this->get_field_types();

    $input_fields = array();

    foreach ($fields as $field_num => $field) {
      $field_info = $types[$field->field_name];

      $field_value = !empty($field_values) && isset($field_values->{$field->field_name}) ? $field_values->{$field->field_name} : null;
      if (!isset($this->callback_edit_field[$field->field_name])) {
        $field_input = $this->get_field_input($field_info, $field_value);
      } else {
        $primary_key = $this->getStateInfo()->primary_key;
        $field_input = $field_info;
        $field_input->input = call_user_func($this->callback_edit_field[$field->field_name], $field_value, $primary_key, $field_info, $field_values);
      }

      switch ($field_info->crud_type) {
        case 'invisible':
          unset($this->edit_fields[$field_num]);
          unset($fields[$field_num]);
          continue;
          break;
        case 'hidden':
          $this->edit_hidden_fields[] = $field_input;
          unset($this->edit_fields[$field_num]);
          unset($fields[$field_num]);
          continue;
          break;
      }

      $input_fields[$field->field_name] = $field_input;
    }

    return $input_fields;
  }

  protected function get_read_input_fields($field_values = null) {
    $read_fields = $this->get_read_fields();

    $this->field_types = null;
    $this->required_fields = null;

    $read_inputs = array();
    foreach ($read_fields as $field) {
      if (!empty($this->change_field_type) && isset($this->change_field_type[$field->field_name]) && $this->change_field_type[$field->field_name]->type == 'hidden') {
        continue;
      }
      $this->field_type($field->field_name, 'readonly');
    }

    $fields = $this->get_read_fields();
    $types = $this->get_field_types();

    $input_fields = array();

    foreach ($fields as $field_num => $field) {
      $field_info = $types[$field->field_name];

      $field_value = !empty($field_values) && isset($field_values->{$field->field_name}) ? $field_values->{$field->field_name} : null;
      if (!isset($this->callback_read_field[$field->field_name])) {
        $field_input = $this->get_field_input($field_info, $field_value);
      } else {
        $primary_key = $this->getStateInfo()->primary_key;
        $field_input = $field_info;
        $field_input->input = call_user_func($this->callback_read_field[$field->field_name], $field_value, $primary_key, $field_info, $field_values);
      }

      switch ($field_info->crud_type) {
        case 'invisible':
          unset($this->read_fields[$field_num]);
          unset($fields[$field_num]);
          continue;
          break;
        case 'hidden':
          $this->read_hidden_fields[] = $field_input;
          unset($this->read_fields[$field_num]);
          unset($fields[$field_num]);
          continue;
          break;
      }

      $input_fields[$field->field_name] = $field_input;
    }

    return $input_fields;
  }

  protected function setThemeBasics() {
    $this->theme_path = $this->default_theme_path;
    if (substr($this->theme_path, -1) != '/')
      $this->theme_path = $this->theme_path . '/';

    include($this->theme_path . $this->theme . '/config.php');

    $this->theme_config = $config;
  }

  public function set_theme($theme = null) {
    $this->theme = $theme;

    return $this;
  }

  protected function _get_ajax_results() {
    //This is a $_POST request rather that $_GET request , because
    //Codeigniter doesn't like the $_GET requests so much!
    if ($this->_is_ajax()) {
      @ob_end_clean();
      $results = (object) array(
                  'output' => $this->views_as_string,
                  'js_files' => array_values($this->get_js_files()),
                  'js_lib_files' => array_values($this->get_js_lib_files()),
                  'js_config_files' => array_values($this->get_js_config_files()),
                  'css_files' => array_values($this->get_css_files())
      );

      echo json_encode($results);
      die;
    }
    //else just continue
  }

  protected function _is_ajax() {
    return array_key_exists('is_ajax', $_POST) && $_POST['is_ajax'] == 'true' ? true : false;
  }

  protected function _theme_view($view, $vars = array(), $return = FALSE) {
    $vars = (is_object($vars)) ? get_object_vars($vars) : $vars;

    $file_exists = FALSE;

    $ext = pathinfo($view, PATHINFO_EXTENSION);
    $file = ($ext == '') ? $view . '.php' : $view;

    $view_file = $this->theme_path . $this->theme . '/views/';

    if (file_exists($view_file . $file)) {
      $path = $view_file . $file;
      $file_exists = TRUE;
    }

    if (!$file_exists) {
      throw new Exception('Unable to load the requested file: ' . $file, 16);
    }

    extract($vars);

    #region buffering...
    ob_start();

    include($path);

    $buffer = ob_get_contents();
    @ob_end_clean();
    #endregion

    if ($return === TRUE) {
      return $buffer;
    }

    $this->views_as_string .= $buffer;
  }

  protected function _inline_js($inline_js = '') {
    $this->views_as_string .= "<script type=\"text/javascript\">\n{$inline_js}\n</script>\n";
  }

  protected function _add_js_vars($js_vars = array()) {
    $javascript_as_string = "<script type=\"text/javascript\">\n";
    foreach ($js_vars as $js_var => $js_value) {
      $javascript_as_string .= "\tvar $js_var = '$js_value';\n";
    }
    $javascript_as_string .= "\n</script>\n";
    $this->views_as_string .= $javascript_as_string;
  }

  protected function get_views_as_string() {
    if (!empty($this->views_as_string))
      return $this->views_as_string;
    else
      return null;
  }

}

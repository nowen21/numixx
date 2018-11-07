<?php


class Grocery_CRUD extends grocery_CRUD_States {

  /**
   * Grocery CRUD version
   *
   * @var	string
   */
  const VERSION = "1.5.4";
  const JQUERY = "jquery-1.11.1.min.js";
  const JQUERY_UI_JS = "jquery-ui-1.10.3.custom.min.js";
  const JQUERY_UI_CSS = "jquery-ui-1.10.1.custom.min.css";

  protected $state_code = null;
  protected $state_info = null;
  protected $columns = null;
  private $basic_db_table_checked = false;
  private $columns_checked = false;
  private $add_fields_checked = false;
  private $edit_fields_checked = false;
  private $read_fields_checked = false;
  protected $default_theme = 'flexigrid';
  protected $language = null;
  protected $lang_strings = array();
  protected $php_date_format = null;
  protected $js_date_format = null;
  protected $ui_date_format = null;
  protected $character_limiter = null;
  protected $config = null;
  protected $add_fields = null;
  protected $edit_fields = null;
  protected $read_fields = null;
  protected $add_hidden_fields = array();
  protected $edit_hidden_fields = array();
  protected $field_types = null;
  protected $basic_db_table = null;
  protected $theme_config = array();
  protected $subject = null;
  protected $subject_plural = null;
  protected $display_as = array();
  protected $order_by = null;
  protected $where = array();
  protected $like = array();
  protected $having = array();
  protected $or_having = array();
  protected $limit = null;
  protected $required_fields = array();
  protected $_unique_fields = array();
  protected $validation_rules = array();
  protected $relation = array();
  protected $relation_n_n = array();
  protected $upload_fields = array();
  protected $actions = array();
  protected $form_validation = null;
  protected $change_field_type = null;
  protected $primary_keys = array();
  protected $crud_url_path = null;
  protected $list_url_path = null;

  /* The unsetters */
  protected $unset_texteditor = array();
  protected $unset_add = false;
  protected $unset_edit = false;
  protected $unset_delete = false;
  protected $unset_read = false;
  protected $unset_jquery = false;
  protected $unset_jquery_ui = false;
  protected $unset_bootstrap = false;
  protected $unset_list = false;
  protected $unset_export = false;
  protected $unset_print = false;
  protected $unset_back_to_list = false;
  protected $unset_columns = null;
  protected $unset_add_fields = null;
  protected $unset_edit_fields = null;
  protected $unset_read_fields = null;

  /* Callbacks */
  protected $callback_before_insert = null;
  protected $callback_after_insert = null;
  protected $callback_insert = null;
  protected $callback_before_update = null;
  protected $callback_after_update = null;
  protected $callback_update = null;
  protected $callback_before_delete = null;
  protected $callback_after_delete = null;
  protected $callback_delete = null;
  protected $callback_column = array();
  protected $callback_add_field = array();
  protected $callback_edit_field = array();
  protected $callback_upload = null;
  protected $callback_before_upload = null;
  protected $callback_after_upload = null;
  protected $default_javascript_path = null; //autogenerate, please do not modify
  protected $default_css_path = null; //autogenerate, please do not modify
  protected $default_texteditor_path = null; //autogenerate, please do not modify
  protected $default_theme_path = null; //autogenerate, please do not modify
  protected $default_language_path = 'assets/grocery_crud/languages';
  protected $default_config_path = 'assets/grocery_crud/config';
  protected $default_assets_path = 'assets/grocery_crud';

  /**
   *
   * Constructor
   *
   * @access	public
   */
  public function __construct() {
    
  }

  // nuevo código
  public function getModel () {
		 if ($this-> basic_model === null)
	   		 $this-> set_default_Model();
	  	 return $this-> basic_model;
	 }
   protected function get_add_values() {
		$values = $this->basic_model->get_add_values();
	  	return $values;
	}
  // fin nuevo código
  
  
  /**
   * The displayed columns that user see
   *
   * @access	public
   * @param	string
   * @param	array
   * @return	void
   */
  public function columns() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->columns = $args;

    return $this;
  }

  /**
   * Set Validation Rules
   *
   * Important note: If the $field is an array then no automated crud fields will take apart
   *
   * @access	public
   * @param	mixed
   * @param	string
   * @return	void
   */
  function set_rules($field, $label = '', $rules = '') {
    if (is_string($field)) {
      $this->validation_rules[$field] = array('field' => $field, 'label' => $label, 'rules' => $rules);
    } elseif (is_array($field)) {
      foreach ($field as $num_field => $field_array) {
        $this->validation_rules[$field_array['field']] = $field_array;
      }
    }
    return $this;
  }

  /**
   *
   * Changes the default field type
   * @param string $field
   * @param string $type
   * @param array|string $extras
   */
  public function change_field_type($field, $type, $extras = null) {
    $field_type = (object) array('type' => $type);

    $field_type->extras = $extras;

    $this->change_field_type[$field] = $field_type;

    return $this;
  }

  /**
   *
   * Just an alias to the change_field_type method
   * @param string $field
   * @param string $type
   * @param array|string $extras
   */
  public function field_type($field, $type, $extras = null) {
    return $this->change_field_type($field, $type, $extras);
  }

  /**
   * Change the default primary key for a specific table.
   * If the $table_name is NULL then the primary key is for the default table name that we added at the set_table method
   *
   * @param string $primary_key_field
   * @param string $table_name
   */
  public function set_primary_key($primary_key_field, $table_name = null) {
    $this->primary_keys[] = array('field_name' => $primary_key_field, 'table_name' => $table_name);

    return $this;
  }

  /**
   * Unsets the texteditor of the selected fields
   *
   * @access	public
   * @param	string
   * @param	array
   * @return	void
   */
  public function unset_texteditor() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }
    foreach ($args as $arg) {
      $this->unset_texteditor[] = $arg;
    }

    return $this;
  }

  /**
   * Unsets just the jquery library from the js. This function can be used if there is already a jquery included
   * in the main template. This will avoid all jquery conflicts.
   *
   * @return	void
   */
  public function unset_jquery() {
    $this->unset_jquery = true;

    return $this;
  }

  /**
   * Unsets the jquery UI Javascript and CSS. This function is really useful
   * when the jquery UI JavaScript and CSS are already included in the main template.
   * This will avoid all jquery UI conflicts.
   *
   * @return	void
   */
  public function unset_jquery_ui() {
    $this->unset_jquery_ui = true;

    return $this;
  }

  /**
   * Unsets just the twitter bootstrap libraries from the js and css. This function can be used if there is already twitter bootstrap files included
   * in the main template. If you are already using a bootstrap template then it's not necessary to load the files again.
   *
   * @return	void
   */
  public function unset_bootstrap() {
    $this->unset_bootstrap = true;

    return $this;
  }

  /**
   * Unsets the add operation from the list
   *
   * @return	void
   */
  public function unset_add() {
    $this->unset_add = true;

    return $this;
  }

  /**
   * Unsets the edit operation from the list
   *
   * @return	void
   */
  public function unset_edit() {
    $this->unset_edit = true;

    return $this;
  }

  /**
   * Unsets the delete operation from the list
   *
   * @return	void
   */
  public function unset_delete() {
    $this->unset_delete = true;

    return $this;
  }

  /**
   * Unsets the read operation from the list
   *
   * @return	void
   */
  public function unset_read() {
    $this->unset_read = true;

    return $this;
  }

  /**
   * Just an alias to unset_read
   *
   * @return	void
   * */
  public function unset_view() {
    return unset_read();
  }

  /**
   * Unsets the export button and functionality from the list
   *
   * @return	void
   */
  public function unset_export() {
    $this->unset_export = true;

    return $this;
  }

  /**
   * Unsets the print button and functionality from the list
   *
   * @return	void
   */
  public function unset_print() {
    $this->unset_print = true;

    return $this;
  }

  /**
   * Unsets all the operations from the list
   *
   * @return	void
   */
  public function unset_operations() {
    $this->unset_add = true;
    $this->unset_edit = true;
    $this->unset_delete = true;
    $this->unset_read = true;
    $this->unset_export = true;
    $this->unset_print = true;

    return $this;
  }

  /**
   * Unsets a column from the list
   *
   * @return	void.
   */
  public function unset_columns() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->unset_columns = $args;

    return $this;
  }

  public function unset_list() {
    $this->unset_list = true;

    return $this;
  }

  public function unset_fields() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->unset_add_fields = $args;
    $this->unset_edit_fields = $args;
    $this->unset_read_fields = $args;

    return $this;
  }

  public function unset_add_fields() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->unset_add_fields = $args;

    return $this;
  }

  public function unset_edit_fields() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->unset_edit_fields = $args;

    return $this;
  }

  public function unset_read_fields() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->unset_read_fields = $args;

    return $this;
  }

  /**
   * Unsets everything that has to do with buttons or links with go back to list message
   * @access	public
   * @return	void
   */
  public function unset_back_to_list() {
    $this->unset_back_to_list = true;

    return $this;
  }

  /**
   *
   * The fields that user will see on add/edit
   *
   * @access	public
   * @param	string
   * @param	array
   * @return	void
   */
  public function fields() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->add_fields = $args;
    $this->edit_fields = $args;

    return $this;
  }

  /**
   *
   * The fields that user can see . It is only for the add form
   */
  public function add_fields() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->add_fields = $args;

    return $this;
  }

  /**
   *
   *  The fields that user can see . It is only for the edit form
   */
  public function edit_fields() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->edit_fields = $args;

    return $this;
  }

  public function set_read_fields() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->read_fields = $args;

    return $this;
  }

  /**
   *
   * Changes the displaying label of the field
   * @param $field_name
   * @param $display_as
   * @return void
   */
  public function display_as($field_name, $display_as = null) {
    if (is_array($field_name)) {
      foreach ($field_name as $field => $display_as) {
        $this->display_as[$display_as[0]] = $display_as[1];
      }
    } elseif ($display_as !== null) {
      $this->display_as[$field_name] = $display_as;
    }
    return $this;
  }

  /**
   *
   * Load the language strings array from the language file
   */
  protected function _load_language() {
    if ($this->language === null) {
      $this->language = strtolower($this->config->default_language);
    }
    include($this->default_language_path . '/' . $this->language . '.php');

    foreach ($lang as $handle => $lang_string)
      if (!isset($this->lang_strings[$handle]))
        $this->lang_strings[$handle] = $lang_string;

    $this->default_true_false_text = array($this->l('form_inactive'), $this->l('form_active'));
    $this->subject = $this->subject === null ? $this->l('list_record') : $this->subject;
  }

  protected function _load_date_format() {
    list($php_day, $php_month, $php_year) = array('d', 'm', 'Y');
    list($js_day, $js_month, $js_year) = array('dd', 'mm', 'yy');
    list($ui_day, $ui_month, $ui_year) = array($this->l('ui_day'), $this->l('ui_month'), $this->l('ui_year'));

    $date_format = $this->config->date_format;
    switch ($date_format) {
      case 'uk-date':
        $this->php_date_format = "$php_day/$php_month/$php_year";
        $this->js_date_format = "$js_day/$js_month/$js_year";
        $this->ui_date_format = "$ui_day/$ui_month/$ui_year";
        break;

      case 'us-date':
        $this->php_date_format = "$php_month/$php_day/$php_year";
        $this->js_date_format = "$js_month/$js_day/$js_year";
        $this->ui_date_format = "$ui_month/$ui_day/$ui_year";
        break;

      case 'sql-date':
      default:
        $this->php_date_format = "$php_year-$php_month-$php_day";
        $this->js_date_format = "$js_year-$js_month-$js_day";
        $this->ui_date_format = "$ui_year-$ui_month-$ui_day";
        break;
    }
  }

  /**
   *
   * Set a language string directly
   * @param string $handle
   * @param string $string
   */
  public function set_lang_string($handle, $lang_string) {
    $this->lang_strings[$handle] = $lang_string;

    return $this;
  }

  /**
   *
   * Just an alias to get_lang_string method
   * @param string $handle
   */
  public function l($handle) {
    return $this->get_lang_string($handle);
  }

  /**
   *
   * Get the language string of the inserted string handle
   * @param string $handle
   */
  public function get_lang_string($handle) {
    return $this->lang_strings[$handle];
  }

  /**
   *
   * Simply set the language
   * @example english
   * @param string $language
   */
  public function set_language($language) {
    $this->language = $language;

    return $this;
  }

  /**
   *
   * Enter description here ...
   */
  protected function get_columns() {
    if ($this->columns_checked === false) {
      $field_types = $this->get_field_types();
      if (empty($this->columns)) {
        $this->columns = array();
        foreach ($field_types as $field) {
          if (!isset($field->db_extra) || $field->db_extra != 'auto_increment')
            $this->columns[] = $field->name;
        }
      }

      foreach ($this->columns as $col_num => $column) {

        if (isset($this->relation[$column])) {

          $new_column = $this->_unique_field_name($this->relation[$column][0]);
          $this->columns[$col_num] = $new_column;

          if (isset($this->display_as[$column])) {
            $display_as = $this->display_as[$column];
            unset($this->display_as[$column]);
            $this->display_as[$new_column] = $display_as;
          } else {
            $this->display_as[$new_column] = ucfirst(str_replace('_', ' ', $column));
          }

          $column = $new_column;
          $this->columns[$col_num] = $new_column;
        } else {
          if (!empty($this->relation)) {
            $table_name = $this->get_table();
            foreach ($this->relation as $relation) {
              if ($relation[2] == $column) {
                $new_column = $table_name . '.' . $column;
                if (isset($this->display_as[$column])) {
                  $display_as = $this->display_as[$column];
                  unset($this->display_as[$column]);
                  $this->display_as[$new_column] = $display_as;
                } else {
                  $this->display_as[$new_column] = ucfirst(str_replace('_', ' ', $column));
                }

                $column = $new_column;
                $this->columns[$col_num] = $new_column;
              }
            }
          }
        }

        if (isset($this->display_as[$column]))
          $this->columns[$col_num] = (object) array('field_name' => $column, 'display_as' => $this->display_as[$column]);
        elseif (isset($field_types[$column]))
          $this->columns[$col_num] = (object) array('field_name' => $column, 'display_as' => $field_types[$column]->display_as);
        else
          $this->columns[$col_num] = (object) array('field_name' => $column, 'display_as' =>
                      ucfirst(str_replace('_', ' ', $column)));

        if (!empty($this->unset_columns) && in_array($column, $this->unset_columns)) {
          unset($this->columns[$col_num]);
        }
      }

      $this->columns_checked = true;
    }

    return $this->columns;
  }

  /**
   *
   * Enter description here ...
   */
  protected function get_add_fields() {
    if ($this->add_fields_checked === false) {
      $field_types = $this->get_field_types();
      if (!empty($this->add_fields)) {
        foreach ($this->add_fields as $field_num => $field) {
          if (isset($this->display_as[$field]))
            $this->add_fields[$field_num] = (object) array('field_name' => $field, 'display_as' => $this->display_as[$field]);
          elseif (isset($field_types[$field]->display_as))
            $this->add_fields[$field_num] = (object) array('field_name' => $field, 'display_as' => $field_types[$field]->display_as);
          else
            $this->add_fields[$field_num] = (object) array('field_name' => $field, 'display_as' => ucfirst(str_replace('_', ' ', $field)));
        }
      }
      else {
        $this->add_fields = array();
        foreach ($field_types as $field) {
          //Check if an unset_add_field is initialize for this field name
          if ($this->unset_add_fields !== null && is_array($this->unset_add_fields) && in_array($field->name, $this->unset_add_fields))
            continue;

          if ((!isset($field->db_extra) || $field->db_extra != 'auto_increment')) {
            if (isset($this->display_as[$field->name]))
              $this->add_fields[] = (object) array('field_name' => $field->name, 'display_as' => $this->display_as[$field->name]);
            else
              $this->add_fields[] = (object) array('field_name' => $field->name, 'display_as' => $field->display_as);
          }
        }
      }

      $this->add_fields_checked = true;
    }
    return $this->add_fields;
  }

  /**
   *
   * Enter description here ...
   */
  protected function get_edit_fields() {
    if ($this->edit_fields_checked === false) {
      $field_types = $this->get_field_types();
      if (!empty($this->edit_fields)) {
        foreach ($this->edit_fields as $field_num => $field) {
          if (isset($this->display_as[$field]))
            $this->edit_fields[$field_num] = (object) array('field_name' => $field, 'display_as' => $this->display_as[$field]);
          else
            $this->edit_fields[$field_num] = (object) array('field_name' => $field, 'display_as' => $field_types[$field]->display_as);
        }
      }
      else {
        $this->edit_fields = array();
        foreach ($field_types as $field) {
          //Check if an unset_edit_field is initialize for this field name
          if ($this->unset_edit_fields !== null && is_array($this->unset_edit_fields) && in_array($field->name, $this->unset_edit_fields))
            continue;

          if (!isset($field->db_extra) || $field->db_extra != 'auto_increment') {
            if (isset($this->display_as[$field->name]))
              $this->edit_fields[] = (object) array('field_name' => $field->name, 'display_as' => $this->display_as[$field->name]);
            else
              $this->edit_fields[] = (object) array('field_name' => $field->name, 'display_as' => $field->display_as);
          }
        }
      }

      $this->edit_fields_checked = true;
    }
    return $this->edit_fields;
  }

  /**
   *
   * Enter description here ...
   */
  protected function get_read_fields() {
    if ($this->read_fields_checked === false) {
      $field_types = $this->get_field_types();
      if (!empty($this->read_fields)) {
        foreach ($this->read_fields as $field_num => $field) {
          if (isset($this->display_as[$field]))
            $this->read_fields[$field_num] = (object) array('field_name' => $field, 'display_as' => $this->display_as[$field]);
          else
            $this->read_fields[$field_num] = (object) array('field_name' => $field, 'display_as' => $field_types[$field]->display_as);
        }
      }
      else {
        $this->read_fields = array();
        foreach ($field_types as $field) {
          //Check if an unset_read_field is initialize for this field name
          if ($this->unset_read_fields !== null && is_array($this->unset_read_fields) && in_array($field->name, $this->unset_read_fields))
            continue;

          if (!isset($field->db_extra) || $field->db_extra != 'auto_increment') {
            if (isset($this->display_as[$field->name]))
              $this->read_fields[] = (object) array('field_name' => $field->name, 'display_as' => $this->display_as[$field->name]);
            else
              $this->read_fields[] = (object) array('field_name' => $field->name, 'display_as' => $field->display_as);
          }
        }
      }

      $this->read_fields_checked = true;
    }
    return $this->read_fields;
  }

  public function order_by($order_by, $direction = 'asc') {
    $this->order_by = array($order_by, $direction);

    return $this;
  }

  public function where($key, $value = NULL, $escape = TRUE) {
    $this->where[] = array($key, $value, $escape);

    return $this;
  }

  public function or_where($key, $value = NULL, $escape = TRUE) {
    $this->or_where[] = array($key, $value, $escape);

    return $this;
  }

  public function like($field, $match = '', $side = 'both') {
    $this->like[] = array($field, $match, $side);

    return $this;
  }

  protected function having($key, $value = '', $escape = TRUE) {
    $this->having[] = array($key, $value, $escape);

    return $this;
  }

  protected function or_having($key, $value = '', $escape = TRUE) {
    $this->or_having[] = array($key, $value, $escape);

    return $this;
  }

  public function or_like($field, $match = '', $side = 'both') {
    $this->or_like[] = array($field, $match, $side);

    return $this;
  }

  public function limit($limit, $offset = '') {
    $this->limit = array($limit, $offset);

    return $this;
  }

  protected function _initialize_helpers() {
    $ci = &get_instance();

    $ci->load->helper('url');
    $ci->load->helper('form');
  }

  protected function _initialize_variables() {
    $ci = &get_instance();
    $ci->load->config('grocery_crud');

    $this->config = (object) array();

    /** Initialize all the config variables into this object */
    $this->config->default_language = $ci->config->item('grocery_crud_default_language');
    $this->config->date_format = $ci->config->item('grocery_crud_date_format');
    $this->config->default_per_page = $ci->config->item('grocery_crud_default_per_page');
    $this->config->file_upload_allow_file_types = $ci->config->item('grocery_crud_file_upload_allow_file_types');
    $this->config->file_upload_max_file_size = $ci->config->item('grocery_crud_file_upload_max_file_size');
    $this->config->default_text_editor = $ci->config->item('grocery_crud_default_text_editor');
    $this->config->text_editor_type = $ci->config->item('grocery_crud_text_editor_type');
    $this->config->character_limiter = $ci->config->item('grocery_crud_character_limiter');
    $this->config->dialog_forms = $ci->config->item('grocery_crud_dialog_forms');
    $this->config->paging_options = $ci->config->item('grocery_crud_paging_options');
    $this->config->default_theme = $ci->config->item('grocery_crud_default_theme');
    $this->config->environment = $ci->config->item('grocery_crud_environment');

    /** Initialize default paths */
    $this->default_javascript_path = $this->default_assets_path . '/js';
    $this->default_css_path = $this->default_assets_path . '/css';
    $this->default_texteditor_path = $this->default_assets_path . '/texteditor';
    $this->default_theme_path = $this->default_assets_path . '/themes';

    $this->character_limiter = $this->config->character_limiter;

    if ($this->character_limiter === 0 || $this->character_limiter === '0') {
      $this->character_limiter = 1000000; //a very big number
    } elseif ($this->character_limiter === null || $this->character_limiter === false) {
      $this->character_limiter = 30; //is better to have the number 30 rather than the 0 value
    }

    if ($this->theme === null && !empty($this->config->default_theme)) {
      $this->set_theme($this->config->default_theme);
    }
  }

  protected function _set_primary_keys_to_model() {
    if (!empty($this->primary_keys)) {
      foreach ($this->primary_keys as $primary_key) {
        $this->basic_model->set_primary_key($primary_key['field_name'], $primary_key['table_name']);
      }
    }
  }

  /**
   * Initialize all the required libraries and variables before rendering
   */
  protected function pre_render() {
    $this->_initialize_variables();
    $this->_initialize_helpers();
    $this->_load_language();
    $this->state_code = $this->getStateCode();

    if ($this->basic_model === null)
      $this->set_default_Model();

    $this->set_basic_db_table($this->get_table());

    $this->_load_date_format();

    $this->_set_primary_keys_to_model();
  }

  /**
   *
   * Or else ... make it work! The web application takes decision of what to do and show it to the final user.
   * Without this function nothing works. Here is the core of grocery CRUD project.
   *
   * @access	public
   */
  public function render() {
    $this->pre_render();

    if ($this->state_code != 0) {
      $this->state_info = $this->getStateInfo();
    } else {
      throw new Exception('The state is unknown , I don\'t know what I will do with your data!', 4);
      die();
    }

    switch ($this->state_code) {
      case 15://success
      case 1://list
        if ($this->unset_list) {
          throw new Exception('You don\'t have permissions for this operation', 14);
          die();
        }

        if ($this->theme === null)
          $this->set_theme($this->default_theme);
        $this->setThemeBasics();

        $this->set_basic_Layout();

        $state_info = $this->getStateInfo();

        $this->showList(false, $state_info);

        break;

      case 2://add
        if ($this->unset_add) {
          throw new Exception('You don\'t have permissions for this operation', 14);
          die();
        }

        if ($this->theme === null)
          $this->set_theme($this->default_theme);
        $this->setThemeBasics();

        $this->set_basic_Layout();

        $this->showAddForm();

        break;

      case 3://edit
        if ($this->unset_edit) {
          throw new Exception('You don\'t have permissions for this operation', 14);
          die();
        }

        if ($this->theme === null)
          $this->set_theme($this->default_theme);
        $this->setThemeBasics();

        $this->set_basic_Layout();

        $state_info = $this->getStateInfo();

        $this->showEditForm($state_info);

        break;

      case 4://delete
        if ($this->unset_delete) {
          throw new Exception('This user is not allowed to do this operation', 14);
          die();
        }

        $state_info = $this->getStateInfo();
        $delete_result = $this->db_delete($state_info);

        $this->delete_layout($delete_result);
        break;

      case 5://insert
        if ($this->unset_add) {
          throw new Exception('This user is not allowed to do this operation', 14);
          die();
        }

        $state_info = $this->getStateInfo();
        $insert_result = $this->db_insert($state_info);

        $this->insert_layout($insert_result);
        break;

      case 6://update
        if ($this->unset_edit) {
          throw new Exception('This user is not allowed to do this operation', 14);
          die();
        }

        $state_info = $this->getStateInfo();
        $update_result = $this->db_update($state_info);

        $this->update_layout($update_result, $state_info);
        break;

      case 7://ajax_list

        if ($this->unset_list) {
          throw new Exception('You don\'t have permissions for this operation', 14);
          die();
        }

        if ($this->theme === null)
          $this->set_theme($this->default_theme);
        $this->setThemeBasics();

        $this->set_basic_Layout();

        $state_info = $this->getStateInfo();
        $this->set_ajax_list_queries($state_info);

        $this->showList(true);

        break;

      case 8://ajax_list_info

        if ($this->theme === null)
          $this->set_theme($this->default_theme);
        $this->setThemeBasics();

        $this->set_basic_Layout();

        $state_info = $this->getStateInfo();
        $this->set_ajax_list_queries($state_info);

        $this->showListInfo();
        break;

      case 9://insert_validation

        $validation_result = $this->db_insert_validation();

        $this->validation_layout($validation_result);
        break;

      case 10://update_validation

        $validation_result = $this->db_update_validation();

        $this->validation_layout($validation_result);
        break;

      case 11://upload_file

        $state_info = $this->getStateInfo();

        $upload_result = $this->upload_file($state_info);

        $this->upload_layout($upload_result, $state_info->field_name);
        break;

      case 12://delete_file
        $state_info = $this->getStateInfo();

        $delete_file_result = $this->delete_file($state_info);

        $this->delete_file_layout($delete_file_result);
        break;
      /*
        case 13: //ajax_relation
        $state_info = $this->getStateInfo();

        $ajax_relation_result = $this->ajax_relation($state_info);

        $ajax_relation_result[""] = "";

        echo json_encode($ajax_relation_result);
        die();
        break;

        case 14: //ajax_relation_n_n
        echo json_encode(array("34" => 'Johnny' , "78" => "Test"));
        die();
        break;
       */
      case 16: //export to excel
        //a big number just to ensure that the table characters will not be cutted.
        $this->character_limiter = 1000000;

        if ($this->unset_export) {
          throw new Exception('You don\'t have permissions for this operation', 15);
          die();
        }

        if ($this->theme === null)
          $this->set_theme($this->default_theme);
        $this->setThemeBasics();

        $this->set_basic_Layout();

        $state_info = $this->getStateInfo();
        $this->set_ajax_list_queries($state_info);
        $this->exportToExcel($state_info);
        break;

      case 17: //print
        //a big number just to ensure that the table characters will not be cutted.
        $this->character_limiter = 1000000;

        if ($this->unset_print) {
          throw new Exception('You don\'t have permissions for this operation', 15);
          die();
        }

        if ($this->theme === null)
          $this->set_theme($this->default_theme);
        $this->setThemeBasics();

        $this->set_basic_Layout();

        $state_info = $this->getStateInfo();
        $this->set_ajax_list_queries($state_info);
        $this->print_webpage($state_info);
        break;

      case grocery_CRUD_States::STATE_READ:
        if ($this->unset_read) {
          throw new Exception('You don\'t have permissions for this operation', 14);
          die();
        }

        if ($this->theme === null)
          $this->set_theme($this->default_theme);
        $this->setThemeBasics();

        $this->set_basic_Layout();

        $state_info = $this->getStateInfo();

        $this->showReadForm($state_info);

        break;

      case grocery_CRUD_States::STATE_DELETE_MULTIPLE:

        if ($this->unset_delete) {
          throw new Exception('This user is not allowed to do this operation');
          die();
        }

        $state_info = $this->getStateInfo();
        $delete_result = $this->db_multiple_delete($state_info);

        $this->delete_layout($delete_result);

        break;
    }

    return $this->get_layout();
  }

  protected function get_common_data() {
    $data = (object) array();

    $data->subject = $this->subject;
    $data->subject_plural = $this->subject_plural;

    return $data;
  }

  /**
   *
   * Enter description here ...
   */
  public function callback_before_insert($callback = null) {
    $this->callback_before_insert = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   */
  public function callback_after_insert($callback = null) {
    $this->callback_after_insert = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   */
  public function callback_insert($callback = null) {
    $this->callback_insert = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   */
  public function callback_before_update($callback = null) {
    $this->callback_before_update = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   */
  public function callback_after_update($callback = null) {
    $this->callback_after_update = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   * @param mixed $callback
   */
  public function callback_update($callback = null) {
    $this->callback_update = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   */
  public function callback_before_delete($callback = null) {
    $this->callback_before_delete = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   */
  public function callback_after_delete($callback = null) {
    $this->callback_after_delete = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   */
  public function callback_delete($callback = null) {
    $this->callback_delete = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   * @param string $column
   * @param mixed $callback
   */
  public function callback_column($column, $callback = null) {
    $this->callback_column[$column] = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   * @param string $field
   * @param mixed $callback
   */
  public function callback_field($field, $callback = null) {
    $this->callback_add_field[$field] = $callback;
    $this->callback_edit_field[$field] = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   * @param string $field
   * @param mixed $callback
   */
  public function callback_add_field($field, $callback = null) {
    $this->callback_add_field[$field] = $callback;

    return $this;
  }

  /**
   *
   * Enter description here ...
   * @param string $field
   * @param mixed $callback
   */
  public function callback_edit_field($field, $callback = null) {
    $this->callback_edit_field[$field] = $callback;

    return $this;
  }

  /**
   *
   * Callback that replace the default auto uploader
   *
   * @param mixed $callback
   * @return grocery_CRUD
   */
  public function callback_upload($callback = null) {
    $this->callback_upload = $callback;

    return $this;
  }

  /**
   *
   * A callback that triggered before the upload functionality. This callback is suggested for validation checks
   * @param mixed $callback
   * @return grocery_CRUD
   */
  public function callback_before_upload($callback = null) {
    $this->callback_before_upload = $callback;

    return $this;
  }

  /**
   *
   * A callback that triggered after the upload functionality
   * @param mixed $callback
   * @return grocery_CRUD
   */
  public function callback_after_upload($callback = null) {
    $this->callback_after_upload = $callback;

    return $this;
  }

  /**
   *
   * Gets the basic database table of our crud.
   * @return string
   */
  public function get_table() {
    if ($this->basic_db_table_checked) {
      return $this->basic_db_table;
    } elseif ($this->basic_db_table !== null) {
      if (!$this->table_exists($this->basic_db_table)) {
        throw new Exception('The table name does not exist. Please check you database and try again.', 11);
        die();
      }
      $this->basic_db_table_checked = true;
      return $this->basic_db_table;
    } else {
      //Last try , try to find the table from your view / function name!!! Not suggested but it works .
      $last_chance_table_name = $this->get_method_name();
      if ($this->table_exists($last_chance_table_name)) {
        $this->set_table($last_chance_table_name);
      }
      $this->basic_db_table_checked = true;
      return $this->basic_db_table;
    }

    return false;
  }

  /**
   *
   * The field names of the required fields
   */
  public function required_fields() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->required_fields = $args;

    return $this;
  }

  /**
   * Add the fields that they are as UNIQUE in the database structure
   *
   * @return grocery_CRUD
   */
  public function unique_fields() {
    $args = func_get_args();

    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }

    $this->_unique_fields = $args;

    return $this;
  }

  /**
   *
   * Sets the basic database table that we will get our data.
   * @param string $table_name
   * @return grocery_CRUD
   */
  public function set_table($table_name) {
    if (!empty($table_name) && $this->basic_db_table === null) {
      $this->basic_db_table = $table_name;
    } elseif (!empty($table_name)) {
      throw new Exception('You have already insert a table name once...', 1);
    } else {
      throw new Exception('The table name cannot be empty.', 2);
      die();
    }

    return $this;
  }

  /**
   * Set a full URL path to this method.
   *
   * This method is useful when the path is not specified correctly.
   * Especially when we are using routes.
   * For example:
   * Let's say we have the path http://www.example.com/ however the original url path is
   * http://www.example.com/example/index . We have to specify the url so we can have
   * all the CRUD operations correctly.
   * The url path has to be set from this method like this:
   * <code>
   * 		$crud->set_crud_url_path(site_url('example/index'));
   * </code>
   *
   * @param string $crud_url_path
   * @param string $list_url_path
   * @return grocery_CRUD
   */
  public function set_crud_url_path($crud_url_path, $list_url_path = null) {
    $this->crud_url_path = $crud_url_path;

    //If the list_url_path is empty so we are guessing that the list_url_path
    //will be the same with crud_url_path
    $this->list_url_path = !empty($list_url_path) ? $list_url_path : $crud_url_path;

    return $this;
  }

  /**
   *
   * Set a subject to understand what type of CRUD you use.
   * ----------------------------------------------------------------------------------------------
   * Subject_plural: Sets the subject to its plural form. For example the plural
   * of "Customer" is "Customers", "Product" is "Products"... e.t.c.
   * @example In this CRUD we work with the table db_categories. The $subject will be the 'Category'
   * and the $subject_plural will be 'Categories'
   * @param string $subject
   * @param string $subject_plural
   * @return grocery_CRUD
   */
  public function set_subject($subject, $subject_plural = null) {
    $this->subject = $subject;
    $this->subject_plural = $subject_plural === null ? $subject : $subject_plural;

    return $this;
  }

  /**
   *
   * Enter description here ...
   * @param $title
   * @param $image_url
   * @param $url
   * @param $css_class
   * @param $url_callback
   */
  public function add_action($label, $image_url = '', $link_url = '', $css_class = '', $url_callback = null) {
    $unique_id = substr($label, 0, 1) . substr(md5($label . $link_url), -8); //The unique id is used for class name so it must begin with a string

    $this->actions[$unique_id] = (object) array(
                'label' => $label,
                'image_url' => $image_url,
                'link_url' => $link_url,
                'css_class' => $css_class,
                'url_callback' => $url_callback,
                'url_has_http' => substr($link_url, 0, 7) == 'http://' || substr($link_url, 0, 8) == 'https://' ? true : false
    );

    return $this;
  }

  /**
   *
   * Set a simple 1-n foreign key relation
   * @param string $field_name
   * @param string $related_table
   * @param string $related_title_field
   * @param mixed $where_clause
   * @param string $order_by
   * @return Grocery_CRUD
   */
  public function set_relation($field_name, // nombre del campo que se va a relacionar de la primera tabla
          $related_table, // tabla que se relaciona
          $related_title_field, // nombre del campo de la tambla que se va a relacionar con $field_name
          $where_clause = null, 
          $order_by = null) {
    $this->relation[$field_name] = array($field_name, $related_table, $related_title_field, $where_clause, $order_by);
    return $this;
  }

  /**
   *
   * Sets a relation with n-n relationship.
   * @param string $field_name
   * @param string $relation_table
   * @param string $selection_table
   * @param string $primary_key_alias_to_this_table
   * @param string $primary_key_alias_to_selection_table
   * @param string $title_field_selection_table
   * @param string $priority_field_relation_table
   * @param mixed $where_clause
   * @return Grocery_CRUD
   */
  public function set_relation_n_n($field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table, $primary_key_alias_to_selection_table, $title_field_selection_table, $priority_field_relation_table = null, $where_clause = null) {
    $this->relation_n_n[$field_name] = (object) array(
                'field_name' => $field_name,
                'relation_table' => $relation_table,
                'selection_table' => $selection_table,
                'primary_key_alias_to_this_table' => $primary_key_alias_to_this_table,
                'primary_key_alias_to_selection_table' => $primary_key_alias_to_selection_table,
                'title_field_selection_table' => $title_field_selection_table,
                'priority_field_relation_table' => $priority_field_relation_table,
                'where_clause' => $where_clause
    );

    return $this;
  }

  /**
   *
   * Transform a field to an upload field
   *
   * @param string $field_name
   * @param string $upload_path
   * @return Grocery_CRUD
   */
  public function set_field_upload($field_name, $upload_dir = '', $allowed_file_types = '') {
    $upload_dir = !empty($upload_dir) && substr($upload_dir, -1, 1) == '/' ? substr($upload_dir, 0, -1) : $upload_dir;
    $upload_dir = !empty($upload_dir) ? $upload_dir : 'assets/uploads/files';

    /** Check if the upload Url folder exists. If not then throw an exception * */
    if (!is_dir(FCPATH . $upload_dir)) {
      throw new Exception("It seems that the folder \"" . FCPATH . $upload_dir . "\" for the field name
					\"" . $field_name . "\" doesn't exists. Please create the folder and try again.");
    }

    $this->upload_fields[$field_name] = (object) array(
                'field_name' => $field_name,
                'upload_path' => $upload_dir,
                'allowed_file_types' => $allowed_file_types,
                'encrypted_field_name' => $this->_unique_field_name($field_name));
    return $this;
  }

}

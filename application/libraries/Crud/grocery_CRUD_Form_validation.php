<?php

if (defined('CI_VERSION')) {
  $ci = &get_instance();
  $ci->load->library('Form_validation');

  class grocery_CRUD_Form_validation extends CI_Form_validation {

    public $CI;
    public $_field_data = array();
    public $_config_rules = array();
    public $_error_array = array();
    public $_error_messages = array();
    public $_error_prefix = '<p>';
    public $_error_suffix = '</p>';
    public $error_string = '';
    public $_safe_form_data = FALSE;

  }

}
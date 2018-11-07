<?php

defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

/**
 * @autor Ing. Henry Ospina - hospinab@gmail.com
 * @nombre: Controller
 * @version: 1.0
 * @descripcion: 
 * */
class MY_Controller extends CI_Controller {
  public $_ips;
  public function __construct() {
    
    parent::__construct();
    $CI = & get_instance();
    $this->load->helper('html');
    $this->load->library('grocery_CRUD');

$this->_ips= $CI->session->userdata['u_ips'];
    $this->lang->load(array('Extras'));
    if (!$this->session->has_userdata('u_id')) {
      $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_su_sesion_ha_expirado_escriba_sus_datos_nuevamente'));
      $this->session->set_flashdata('msgTipo', 4); // Atención
      redirect('/ingreso');
    }
  }

  public function crudver($grocery = null, $vista, $titulo, $plantilla) {
    if ($plantilla == 'formulario') {
      $parametros['jquery'] = true;
    } else {
      $parametros['jquery'] = false;
    }
    $parametros['tituloPagina'] = '<section class="content-header"><h1>' . $titulo . '</h1></section>';
    $parametros['script'] = '';
    $parametros['estilo'] = '';
    $js = 'assets/admin/js/' . $vista . '/vista.js';
    $css = 'assets/admin/css/' . $vista . '/vista.css';
    if (file_exists($js))
      $parametros['script'] = '<script src="' . base_url() . $js . '"></script>';
    if (file_exists($css))
      $parametros['estilo'] .= "\n" . '  <link rel="stylesheet" href="' . base_url() . $css . '">';
    $cabeza = '/crudCabeza';
    $pie = '/crudPie';

    $this->load->view('plantilla/admin' . $cabeza, $parametros);
    $this->load->view('admin/' . $plantilla, $grocery);
    $this->load->view('plantilla/admin' . $pie, $parametros);
  }

}

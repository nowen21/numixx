<?php
defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');
/**
 * @autor Ing. Henry Ospina - hospinab@gmail.com
 * @nombre: Controller
 * @version: 1.0
 * @descripcion: 
 * */

class Servicios extends MY_Controller {

    var $parametros;

    public function __construct() {
        parent::__construct();
        $this->load->library('grocery_CRUD');

        if ( !$this->session->has_userdata('u_id') ) {
            $this->session->set_flashdata('msgTxt', $this->lang->line('er_am_su_sesion_ha_expirado_escriba_sus_datos_nuevamente'));
            $this->session->set_flashdata('msgTipo', 4); // Atención
            redirect('/ingreso');
        }
    }

    public function index() {
        $crud = new grocery_CRUD();
        $crud->set_table('servicios');
        $grocery = $crud->render();
        $this->crudver($grocery, 'admin/servicios', 'Servicios');
    }

}

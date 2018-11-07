<?php
defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');
/**
 * @autor Ing. Henry Ospina - hospinab@gmail.com
 * @nombre: Controller
 * @version: 1.0
 * @descripcion: 
 * */

class Clinicas extends MY_Controller {

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
        $crud->set_table('clinicas');
     // $crud->add_action('Medicamento', site_url('assets/adminlte/dist/img/credit/cirrus.png'), 'admin/Medicamentos/medicamento');
     // $crud->add_action('osmoralidad', site_url('assets/adminlte/dist/img/credit/cirrus.png'), 'admin/Medicamentos/osmoralidad');
      //$crud->add_action('peso_e', site_url('assets/adminlte/dist/img/credit/cirrus.png'), 'admin/Medicamentos/peso_e');
      //$crud->add_action('porcentaje', site_url('assets/adminlte/dist/img/credit/cirrus.png'), 'admin/Medicamentos/porcentaje');
      //$crud->add_action('unidad_medica', site_url('assets/adminlte/dist/img/credit/cirrus.png'), 'admin/Medicamentos/unidad_medica');
        //$crud->set_relation('genero','generos','nombre');
        
        $grocery = $crud->render();
        $this->crudver($grocery, 'admin/clinicas', 'clinicas', 'crud');
    }

}

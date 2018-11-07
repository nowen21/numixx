<?php

defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

/**
 * @autor Ing. Henry Ospina - hospinab@gmail.com
 * @nombre: Controller
 * @version: 1.0
 * @descripcion: 
 * */
class Pacientesh extends MY_Controller {

  var $parametros;

  public function __construct() {
    parent::__construct();
    $this->load->library('grocery_CRUD');

    if (!$this->session->has_userdata('u_id')) {
      $this->session->set_flashdata('msgTxt', $this->lang->line('er_am_su_sesion_ha_expirado_escriba_sus_datos_nuevamente'));
      $this->session->set_flashdata('msgTipo', 4); // Atención
      redirect('/ingreso');
    }
  }

  /*
    function _just_a_test($primary_key , $row)
    {
    return $row->id;
    }
   */

  public function index() {
    $crud = new grocery_CRUD();
    $crud->set_table('pacientes');
    //$crud->unset_add();
//        $crud->unset_delete();        
//        $crud->unset_edit();  

    $crud->add_action('Detalles', '', 'nucleos/personas_cargo');
//        $crud->add_action('Smileys', '','','', array($this,'_just_a_test'));
    $crud->add_action('Registro M', site_url('assets/adminlte/dist/img/credit/agregar.png'), 'admin/pacientesh/adulto');



    //$crud->add_fields('Registro M', site_url('assets/adminlte/dist/img/credit/agregar.png'), 'admin/pacientesh/adulto');
    // $crud->add_action('codigo', site_url('assets/adminlte/dist/img/credit/cirrus.png'), 'admin/pacientesh/codigo');
    //$crud->callback_add_field('PRECIO_COMPRA',array($this,'add_field_callback_1'), 'admin/pacientesh/adulto'));

    $crud->set_relation('genero', 'generos', 'nombre');
    $crud->set_relation('eps', 'eps', 'nombre');
    $crud->set_relation('servicio', 'servicios', 'nombre');
    $crud->set_relation('institucion', 'clinicas', 'clinicax');
    $crud->set_relation('departamento', 'departamentos', 'nombre');
    $crud->set_relation('municipio', 'municipios', 'nombre');
    //$crud->set_relation('npt', 'npts', 'nombrnpt');
//        $crud->set_relation('medicamento','medicamentos','nom_medic');
//        $crud->set_relation('osmoralidad','medicamentos','osmoralidad');
//        $crud->add_action('Personas a Cargo', '', 'nucleos/personas_cargo','ui-icon-plus');

    /*
      $crud->add_action('Photos', '', '','ui-icon-image',array($this,'just_a_test'));
      $crud->add_action('Smileys', 'http://www.grocerycrud.com/assets/uploads/general/smiley.png', 'nucleos/personas_cargo');
     */

//es para agregar los medicamentos del los pacientes 

    $grocery = $crud->render();
    $this->crudver($grocery, 'admin/pacientesh', 'Registro Pacientes', "crud");
  }

  public function adulto($id) {
//      
    $crud = new grocery_CRUD();
    $crud->where('idpaciente', $id);
    $crud->getModel()->set_add_value('idpaciente', $id);
    $crud->set_table('formulaciones');
    $crud->display_as(array(array('tieminfh', 'Tiempo de infusion H'),
        array('idpacien', 'Paciente')
    ));

    $crud->add_action('Impimir', site_url('assets/img/imprimir.jpg'), 'admin/imprimiretiqueta/imprimirpdf');
    $grocery = $crud->render();


    $this->crudver($grocery, 'admin/formulacion_adultos', 'Registro Medicamento Adultos', "formulario");
  }

  /*        public function adulto ($id) {
    //        echo "Codigo del nucleo: $id Personas a cargo";

    $crud = new grocery_CRUD();
    $crud->set_table('formulacion_adultos');
    //$crud->set_relation('nombre_paciente','formulacion_adultos','nombre_paciente');
    $crud->set_relation('nombre_paciente','pacientesh','nombres_apellidos');
    //$crud->set_relation('nucleo','nucleos','nombre_cabeza_grupo');


    $grocery = $crud->render();
    $this->crudver($grocery, 'admin/formulacion_adultos', 'Registro Medicamento');
   */
}

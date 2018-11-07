<?php
defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');
/**
 * @autor Ing. Henry Ospina - hospinab@gmail.com
 * @nombre: Controller
 * @version: 1.0
 * @descripcion: 
 * */

class Inventario_entradas extends MY_Controller {

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
       
       echo "prueba";

        $crud = new grocery_CRUD();
        $crud->set_table('inventario_entradas');

        

        $crud->set_relation('medicamento','medicamentos','medicamento_con_marca');
        $crud->set_relation('registro_invima','medicamentos','registro_invima');
        
         



        $grocery = $crud->render();
        $this->crudver($grocery, 'admin/inventario_entradas', 'Inventario Entradas');

}


    public function suma(){
        $crud = new grocery_CRUD();
        $crud->set_table('inventario_entradas');

        $crud->add_action('suma', site_url('assets/adminlte/dist/img/credit/agregar.png'),'admin/inventario_entradas/suma');

        $crud->unset_operations('cantidad_solitada' + 'cantidad_recibida');
        $crud->unset_print();



        $grocery = $crud->render();
        $this->crudver($grocery, 'admin/inventario_entradas', 'suma');


    }
            


    

}

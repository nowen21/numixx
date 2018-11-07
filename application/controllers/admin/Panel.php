<?php
defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

/**
 * @autor Ing. Henry Ospina - hospinab@gmail.com
 * @nombre: Panel
 * @version: 1.0
 * @descripcion: Controlador que presenta el resumen de la aplicacion
 * */
class Panel extends CI_Controller {

    var $parametros;

    public function __construct() {
        parent::__construct();

        if ( !$this->session->has_userdata('u_id') ) {
            $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_su_sesion_ha_expirado_escriba_sus_datos_nuevamente'));
            $this->session->set_flashdata('msgTipo', 4); // Atención
            redirect('/ingreso');
        }

    }

    public function index() {
        $this->parametros['plantilla'] = 'basico';
        $this->parametros['vista'] = 'admin/panel';
        $this->parametros['datos']['titulo'] = 'Panel de Control';
        $this->parametros['datos']['subtitulo'] = '';
        $this->load->view('plantillaAdmin', $this->parametros);
    }

}

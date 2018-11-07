<?php

defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

/**
 * @autor Ing. Henry Ospina - hospinab@gmail.com
 * @nombre: Ingreso
 * @version: 1.0
 * @descripcion: Controlador del formulario de ingreso
 * */
class Ingreso extends CI_Controller {

  var $parametros;
  var $regUsuario;

  public function __construct() {
    parent::__construct();
    $this->load->model('Permisos', 'p');
  }

  public function index() {
    $this->parametros['plantilla'] = 'ingreso';
    $this->parametros['vista'] = 'ingreso';
    $this->load->view('plantillaAdmin', $this->parametros);
  }

  public function validacion() {

    $post = $this->input->post();

    if (!empty($post)) {
      $this->form_validation->set_rules('correo', 'Correo', 'max_length[70]|valid_email|required|trim|xss_clean');
      $this->form_validation->set_rules('clave', 'Contraseña', 'min_length[5]|max_length[20]|required|trim|xss_clean');
      if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('msgTxt', validation_errors() . "<p>" . $this->lang->line('extras_am_por_favor_intentelo_nuevamente') . "</p>");
        $this->session->set_flashdata('msgTipo', 2); // Error
      } else {
        //$this->Modelo->tabla = 'usuarios';
        $this->Modelo->tabla = 'users';

        //$usuario = $this->Modelo->registros(NULL, array('correo' => $post['correo'], 'clave' => $post['clave']));
        $usuario = $this->Modelo->registros(NULL, array('correo' => $post['correo'], 'password' => $this->p->getLogin($post['correo'], $post['clave'])));
//        echo '<pre>';
//        print_r($usuario);

        if (count($usuario)) {
          $this->regUsuario = $usuario[0];
          $this->set_session();

          redirect('/admin/usuarios');
        } else {
          $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_correo_o_contrasena_invalidos') . ' ' . $this->lang->line('extras_am_por_favor_intentelo_nuevamente'));
          $this->session->set_flashdata('msgTipo', 2); // Error
        }
      }
    } else {
      $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_no_se_han_recibido_datos') . ' ' . $this->lang->line('extras_am_por_favor_intentelo_nuevamente'));
      $this->session->set_flashdata('msgTipo', 2); // Error
    }

    $this->parametros['plantilla'] = 'ingreso';
    $this->parametros['vista'] = 'ingreso';
    $this->load->view('plantillaAdmin', $this->parametros);
  }

  public function set_session() {
    //  . ' ' . $this->regUsuario['apellidos']
    // ,'u_raiz'=>$this->regUsuario['raiz'],
    $this->session->set_userdata(array(
        'u_id' => $this->regUsuario['idusuario'],
        'u_nombreCompleto' => $this->regUsuario['nombre'] . ' ' . $this->regUsuario['apellidos'],
        'u_correo' => $this->regUsuario['correo'],
        'u_perfil' => 0,
        'u_ips'=>$this->regUsuario['ips']
            )
    );
    $this->session->userdata['u_perfil'] = $this->p->getPermiso();
  }

  public function salir() {
    $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_no_salida_exitosa'));
    $this->session->set_flashdata('msgTipo', 3); // Información
    redirect('/ingreso');
  }

}

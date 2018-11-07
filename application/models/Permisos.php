<?php

defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

/**
 * Description of Permissions
 *
 * @author Nowen21
 */
class Permisos extends CI_Model {

  private $_table = 'roles_usuarios';

  private function getConspermiso($campoxxx, $permisox, $admin) {
    $respuesta = []; // no es administrador o no tiene el permiso asignado
    $this->db->select($campoxxx);
    $this->db->from("{$this->_table} as ru");
    $this->db->join('roles as r', 'r.id = ru.id');
    if (!$admin) { // sa
      $this->db->join('permisos_roles as pr', 'pr.role_id = r.id');
      $this->db->join('permisos as p', 'p.id = pr.id');
      $this->db->where(['ru.user_id' => $this->session->userdata['u_id']]);
    }else{
      $this->db->where([$campoxxx => $permisox, 'ru.user_id' => $this->session->userdata['u_id']]);
    }
    
    $query = $this->db->get();

    if ($admin) {
      $row = $query->row();
      if (isset($row)) {// es administrador
        $respuesta = ['all-access'];
      }
    } else {
      $row = $query->result();      
      if ($row) {
        foreach ($row as $value) {
          $respuesta[]= $value->slug;
        }
      }
    }
    return $respuesta;
  }

  public function getPermiso() {
    $respuesta = $this->getConspermiso('r.special', 'all-access', TRUE);
    if (count($respuesta) == 0) {// no es administrador
      $respuesta = $this->getConspermiso('p.slug', '', FALSE);
    }
    print_r($respuesta);
    return $respuesta;
  }

  public function getLogin($usuariox, $passwordx) {
    $respuest = '';
    $this->db->from("users");
    $this->db->where(['correo' => $usuariox]);
    $query = $this->db->get();
    foreach ($query->result() as $value) {
      if (password_verify($passwordx, $value->password)) {
        $respuest = $value->password;
      }
    }
    return $respuest;
  }

}

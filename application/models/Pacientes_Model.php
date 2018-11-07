<?php

defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

class Pacientes_Model extends CI_Model {
  private $_tabla='pacientes';
  public function listar_pacientes($ipsxxxxx) {
    $this->db->select(["idpaciente", 'npt', 'nombre_apellido']);
    $this->db->from($this->_tabla);
    $this->db->where(['ips'=>$ipsxxxxx]);
    $consulta = $this->db->get();
    $combo = [];
    foreach ($consulta->result() as $value) {
      $combo[] = ['value' => $value->idpaciente . '_' . $value->npt, 'option' => $value->nombre_apellido];
    }
    return $combo;
//    return $query->result_array();
  }

}

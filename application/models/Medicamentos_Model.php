<?php


defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

class Medicamentos_Model extends CI_Model{
  private $_tabla='medicamentos';
  public function listar_medicamentos($casa,$medicamento) {
    $this->db->select(["idmedicamento", 'nombre']);
    $this->db->from($this->_tabla);
    if($medicamento>0){
      $this->db->where(['idmedicamento'=>$medicamento]);
    }
    $this->db->where(['idcasa'=>$casa]);
    $consulta = $this->db->get();
    $combo = [];
    foreach ($consulta->result() as $value) {
      $combo[] = ['value' => $value->idmedicamento , 'option' => $value->nombre];
    }
    return $combo;
  }
  
}

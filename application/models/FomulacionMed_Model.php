<?php

defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

class FomulacionMed_Model extends CI_Model{
  private $_tabla='formulacion_med';
  public function insertar_formulacion_med($formulacion) {
    $this->db->insert('formulacion_med', $formulacion);    
    return $this->db->insert_id();
  }
  public function listar_formulacion_med($idformu) {
    $this->db->from($this->_tabla);
     $this->db->join('medicamentos', "medicamentos.idmedicamento = {$this->_tabla}.medicamento");
    $this->db->where(["{$this->_tabla}.idformulacion"=>$idformu]);    
    $consulta= $this->db->get();
    return $consulta->result(); 
  }
}

<?php

defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

class Formulaciones_Model extends CI_Model {

  private $_tabla = 'formulaciones';

  public function insertar_formulacion($formulacion) {
    $this->db->insert($this->_tabla, $formulacion);
    return $this->db->insert_id();
  }

  public function listar_formulacion() {
    $serch = $this->input->post('search[value]');
    $columna = $this->input->post('columns');
   
    $order = $this->input->post('order[0][dir]');
    $this->db->from($this->_tabla);
    $this->db->join('pacientes', "pacientes.idpaciente = {$this->_tabla}.idpaciente");

    $buscar = [
        "{$this->_tabla}.idformulacion" => $serch,
        "pacientes.nombre_apellido" => $serch,
        "{$this->_tabla}.tiempo" => $serch,
        "velocidad" => $serch,
        "{$this->_tabla}.volumen" => $serch,
        "{$this->_tabla}.purga" => $serch,
        "{$this->_tabla}.peso" => $serch,
        "{$this->_tabla}.total" => $serch
    ];
    
    $this->db->or_like($buscar);
    $this->db->order_by($columna[$this->input->post('order[0][column]')]['data'], $order);
    $consulta = $this->db->get();
    $combo = [];
    foreach ($consulta->result() as $key=> $value) {
      $inicio=$this->input->post('start');
      $hasta=$inicio+$this->input->post('length');
     
      if($key>=$inicio && $key<$hasta or $this->input->post('length')==-1){
        $combo[] = [
          "idformulacion" => $value->idformulacion,
          "nombre_apellido" => $value->nombre_apellido,
          "tiempo" => $value->tiempo,
          "velocidad" => $value->velocidad,
          "volumen" => $value->volumen,
          "purga" => $value->purga,
          "peso" => $value->peso,
          "total" => $value->total
      ];
      }
      
    }
    $data = [
        "draw" => $this->input->post('draw'),
        "recordsTotal" => $key+1,
        "recordsFiltered" => $key+1,
        'data' => $combo
    ];
    return $data;
  }
  public function mostrar_formulacion($idformu) {
    $this->db->from($this->_tabla);
    $this->db->where(['idformulacion'=>$idformu]);    
    $consulta= $this->db->get();
    return $consulta->result(); 
  }
}

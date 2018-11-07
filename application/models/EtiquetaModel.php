<?php

class EtiquetaModel extends CI_Model {

  public function formulacion($id) {
    $sql = "SELECT * FROM formulaciones AS fo JOIN pacientes AS pa ON fo.idpaciente=pa.idpaciente ";
    $sql.= "JOIN servicios AS se ON pa.servicio=se.idservicio ";
    $sql.= "JOIN clinicas AS cl ON pa.institucion=cl.idclinica ";
    $sql.= "JOIN npts AS np ON pa.npt=np.idnpt ";
    $sql.= "WHERE idformulacion=" . $id;
    return $this->db->query($sql);
  }

}

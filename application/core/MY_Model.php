<?php 
defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');
// http://www.tutorials.kode-blog.com/codeigniter-model

class MY_Model extends CI_Model {

    public $tabla = '';
    public $llave = 'id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
        if (!$this->tabla) {
            $this->tabla = 'TablaNoDefinida';
        }
    }

    public function registro($id, $llave = "") {
        if ( $llave == "" ) {
            $llave = $this->llave;            
        }

        return $this->db->get_where($this->tabla, array($llave => $id))->row();
    }

    public function registros($fields = '', $where = array(), $table = '', $limit = '', $order_by = '', $group_by = '') {
        $data = array();
        if ($fields != '') {
            $this->db->select($fields);
        }

        if (count($where)) {
            $this->db->where($where);
        }

        if ($table != '') {
            $this->tabla = $table;
        }

        if ($limit != '') {
            $this->db->limit($limit);
        }

        if ($order_by != '') {
            $this->db->order_by($order_by);
        }

        if ($group_by != '') {
            $this->db->group_by($group_by);
        }

        $Q = $this->db->get($this->tabla);

        if ($Q->num_rows() > 0) {
            foreach ($Q->result_array() as $row) {
                $data[] = $row;
            }
        }
        $Q->free_result();

        return $data;
    }

    public function insertar($data) {
        // $data['fecha_creado'] = $data['fecha_actualizado'] = date('Y-m-d H:i:s');
        // $data['creado_ip'] = $data['actualizado_ip'] = $this->input->ip_address();
        $success = $this->db->insert($this->tabla, $data);
        if ($success) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function actualizar($data, $id) {
        // $data['fecha_actualizado'] = date('Y-m-d H:i:s');
        // $data['actualizado_ip'] = $this->input->ip_address();
        $this->db->where($this->llave, $id);
        return $this->db->update($this->tabla, $data);
    }

    public function eliminar($id) {
        $this->db->where($this->llave, $id);
        return $this->db->delete($this->tabla);
    }

}


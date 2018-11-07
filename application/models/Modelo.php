<?php
defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

/**
 * @autor Ing. David Amador - solucionaticos@gmail.com
 * @nombre Modelo.php
 * @fecha 29 Junio 2016
 * @descripcio: Manejo de parametros.
 */
class Modelo extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function filasAnuncios($categoria) {
		$this->db->where('mi_anuncio_categoria', $categoria)
                 ->where("activo = '1'");
		return  $this->db->count_all_results('registros');
    }

    public function obtenerAnuncios($limit, $start, $categoria) {
        $this->db->limit($limit, $start);
        $query = $this->db->where("mi_anuncio_categoria = " . $categoria)
                          ->where("activo = '1'")
                          ->order_by("id","desc")
        				  ->get("registros");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }

   public function servicios($anuncio) {
        return $this->db->select('servicios.nombre')
                        ->join('registro_servicios', 'registro_servicios.registro = ' . $anuncio . ' AND registro_servicios.servicio = servicios.id AND registro_servicios.activo = "1"')
                        ->order_by('servicios.nombre', 'asc')
                        ->get('servicios')->result_array();
   }


   public function misanuncios($correo) {
        $sql = "
            SELECT 
                r.mi_anuncio_titulo, r.fecha, r.activo, r.codigo, c.nombre AS categoria
            FROM 
                registros r 
                    INNER JOIN categorias c
                        ON r.mi_anuncio_categoria = c.id
            WHERE 
                r.mis_datos_email = '".$correo."'";
        $query = $this->db->query($sql);
        return $query->result_array();
   }


}
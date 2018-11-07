     <?php

defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

class ImprimirEtiqueta extends MY_Controller {

  private $_etiqueta;

  public function __construct() {
    parent::__construct();
    $this->load->model('Formulaciones_Model', 'mm');
    $this->load->model('FomulacionMed_Model', 'fmm');
  }

  public function index($id) {
    $data= $this->getData($id);
//    $data["pdf"]='';
//    $this->load->view('admin/etiqueta',$data);
    $this->crudver($data , 'admin/pacientesh', 'Registro Pacientes', "etiqueta");
  }
  private function getData($id) {
    $tabla = $this->mm->mostrar_formulacion($id);
    $dataxxxx = array();
    foreach ($tabla as $row) {
      $dataxxxx = $row;
    }
    $data = array("etiqueta" => array(),
        "total" => '',
        "volutota" => '',
        "nombre" => '',
        "pesoxxxx" => '',
        "historia" => '',
        "camaxxxx" => '',
        "servicio" => '',
        "clinica" => '',
        "jquery" => '',
        "nombrnpt" => ''
    );
    if (count($dataxxxx) > 0) {
      $etiqueta=[];
      $total = 0;
      foreach ($etiqueta as $value) {
        $total = $total + $value["volumendia"];
      }
      $data["etiqueta"] = $tabla[0]; //$etiqueta;
      $data['medicamentos']= $this->fmm->listar_formulacion_med($id);
      $data["total"] = $total;
      $data["volutota"] = '';//$dataxxxx->volutota;
      $data["nombre"] = '';//$dataxxxx->nombres_apellidos;
      $data["pesoxxxx"] ='';// $dataxxxx->peso;
      $data["historia"] ='';// $dataxxxx->historia;
      $data["camaxxxx"] = '';//$dataxxxx->camaxxxx;
      $data["servicio"] = '';//$dataxxxx->nombre;
      $data["clinica"] = '';//$dataxxxx->clinicax;
      $data["nombrnpt"] = '';//$dataxxxx->nombrnpt;
      $data["idxxxxxx"] = '';//$dataxxxx->idxxxxxx;
    }
    return $data;
  }
  public function imprimirpdf($id) {
    $data= $this->getData($id);
    $data["pdf"]='';
    ob_start();
    $this->load->view('admin/pdf',$data);
    $content = ob_get_clean();
    $this->load->library('html2pdf_lib');

    /********
     * $content = the html content to be converted
     * you can use file_get_content() to get the html from other location
     *
     * $filename = filename of the pdf file, make sure you put the extension as .pdf
     * $save_to = location where you want to save the file,
     *            set it to null will not save the file but display the file directly after converted
     * ******/
     
    $filename = 'testing.pdf';
    $save_to = $this->config->item('upload_root');

    if ($this->html2pdf_lib->converHtml2pdf($content,$filename,$save_to)) {
      echo $save_to.'/'.$filename;
    } else {
      echo 'failed';
    }
  }

}

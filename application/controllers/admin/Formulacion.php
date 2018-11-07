<?php

defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

/**
 * @autor Ing. Henry Ospina - hospinab@gmail.com
 * @nombre: Controller
 * @version: 1.0
 * @descripcion: 
 * */
class Formulacion extends MY_Controller {

  var $parametros;

  public function __construct() {
    parent::__construct();
    $this->load->model('Pacientes_Model', 'pm');
    $this->load->model('Formulaciones_Model', 'fm');
    $this->load->model('FomulacionMed_Model', 'fmm');
    $this->load->model('Medicamentos_Model', 'mm');
    $this->load->model('Permisos');
    $this->load->library('grocery_CRUD');

    if (!$this->session->has_userdata('u_id')) {
      $this->session->set_flashdata('msgTxt', $this->lang->line('er_am_su_sesion_ha_expirado_escriba_sus_datos_nuevamente'));
      $this->session->set_flashdata('msgTipo', 4); // Atención
      redirect('/ingreso');
    }
  }

  public function index() { 
   
//    echo '<pre>';
//    print_r($this->session->userdata);
    $data = ['pacientes' => $this->pm->listar_pacientes($this->_ips),
        'aminoacido' => $this->mm->listar_medicamentos(1, 0),
        'fosfato' => $this->mm->listar_medicamentos(2, 0),
        'carbohidrato' => $this->mm->listar_medicamentos(3, 0),
        'sodio' => $this->mm->listar_medicamentos(4, 0),
        'potacio' => $this->mm->listar_medicamentos(5, 0),
        'calcio' => $this->mm->listar_medicamentos(6, 0),
        'magnesio' => $this->mm->listar_medicamentos(7, 0),
        'elementos' => $this->mm->listar_medicamentos(8, 0),
        'multivitaminas_1' => $this->mm->listar_medicamentos(9, 15),
        'multivitaminas_2' => $this->mm->listar_medicamentos(9, 16),
        'multivitaminas_3' => $this->mm->listar_medicamentos(9, 17),
        'glutamina' => $this->mm->listar_medicamentos(10, 0),
        'vitaminac' => $this->mm->listar_medicamentos(11, 0),
        'complejob' => $this->mm->listar_medicamentos(12, 0),
        'tiamina' => $this->mm->listar_medicamentos(13, 0),
        'acido' => $this->mm->listar_medicamentos(14, 0),
        'vitaminak' => $this->mm->listar_medicamentos(15, 0),
        'lipidos' => $this->mm->listar_medicamentos(16, 0),
    ];
    //echo json_encode($data);
    $crud = new grocery_CRUD();
    $crud->set_table('formulaciones');
    $crud->unset_columns('aminoacido', 'casas', 'nombre');




    $grocery = $crud->render();
    $grocery->output = $data;

    $this->crudver($grocery, 'admin/formulacion_adultos', 'Formulaciones', 'formulario');
  }

  private function calculosguardar() {
    $formulacion = $this->input->post('data');
    $formul = [
        'idpaciente' => $formulacion['idpaciente'],
        'tiempo' => $formulacion['tiempo'],
        'velocidad' => $formulacion['velocidad'],
        'volumen' => $formulacion['volumen'],
        'purga' => $formulacion['purga'],
        'peso' => $formulacion['peso'],
        'total' => $formulacion['total'],
        'ips'=>$this->_ips
    ];
    $idformu = $this->fm->insertar_formulacion($formul);

    foreach ($formulacion['medicamentos'] as $key => $value) {
//      if($value['medicamento']-1==23){
      $formu = $this->formulas($value['medicamento'] - 1, $value['cantidad'], $formulacion);
      //echo $formu['rtotal'] . ' => ' . $formu['volumen'] . ' => ' . $formu['purga'] . '<br>';
     // }
      $this->fmm->insertar_formulacion_med(['medicamento' => $value['medicamento'], 'cantidad' => $value['cantidad'], 'idformulacion' => $idformu, 'rtotal' => $formu['rtotal'],
          'volumen' => $formu['volumen'], 'purga' => $formu['purga']]);
    }
  }

  public function guardarFormulacion() {
    $this->calculosguardar();

    echo json_encode([]);
  }

  public function filtroajax() {



    echo json_encode($this->fm->listar_formulacion());
  }

  private function formulas($pos, $cant, $formulacion) {
    $tiempo = $formulacion['tiempo'];
    $velocidad = $formulacion['velocidad'];
    $purga = $formulacion['purga'];
    $peso = $formulacion['peso'];
    $cpurga = (($tiempo * $velocidad + $purga) / ($tiempo * $velocidad));
    $medi = $formulacion['medicamentos'];
    $formulas = [
        ['rtotal' => $peso * $cant, 'volumen' => ($peso * $cant * 10), 'purga' => $cpurga * $peso * $cant * 10], //f1 amino 10
        ['rtotal' => $peso * $cant, 'volumen' => ($peso * $cant * 6.7), 'purga' => $cpurga * $peso * $cant * 6.7], //f2 amino 15
        ['rtotal' => $cant, 'volumen' => $cant, 'purga' => $cant * $cpurga], //f3 fosfato glicerofosfato        
        ['rtotal' => $cant, 'volumen' => $cant / 2.6, 'purga' => $cant / 2.6 * $cpurga], //f4 fosfato corpaul        
        ['rtotal' => $cant, 'volumen' => $cant, 'purga' => $cant * $cpurga], //f5 fostao pisa        
        ['rtotal' => $cant * $peso * 1.44, 'volumen' => $cant * $peso * 1.44 * 2, 'purga' => $cant * $peso * 1.44 * 2 * $cpurga], //f6 carbohidrato 50        
        ['rtotal' => $cant * $peso * 1.44, 'volumen' => $cant * $peso * 1.44 * 10, 'purga' => $cant * $peso * 1.44 * 10 * $cpurga], //f7 carbohidrato 10        
        ['rtotal' => $peso * $cant, 'volumen' => ($peso * $cant) / 2, 'purga' => ($peso * $cant) / 2 * $cpurga], //f8 sodio
        [], //f9  potasio
        ['rtotal' => $cant, 'volumen' => $cant / 9.2, 'purga' => $cant / 9.2 * $cpurga], //f10 calcio        
        ['rtotal' => $cant, 'volumen' => $cant / 20, 'purga' => $cant / 20 * $cpurga], //f11 magnesio       
        ['rtotal' => $cant, 'volumen' => $cant / 0.33, 'purga' => $cant / 0.33 * $cpurga], //f12 traza tracutil
        ['rtotal' => $cant, 'volumen' => $cant / 0.654, 'purga' => $cant / 0.654 * $cpurga], //f13 traza addamel
        ['rtotal' => $cant, 'volumen' => $cant, 'purga' => $cant * $cpurga], //f14 traza corpaul        
        ['rtotal' => $cant, 'volumen' => $cant, 'purga' => $cant * $cpurga], //f15 multivataminas cernivit
        ['rtotal' => $cant / 0.099, 'volumen' => $cant / 0.099, 'purga' => $cant / 0.099 * $cpurga], //f16 multivataminas vitalipid        
        ['rtotal' => $cant / 0.04, 'volumen' => $cant / 0.04, 'purga' => $cant / 0.04 * $cpurga], //f17 multivataminas soluvit
        ['rtotal' => $cant * $peso, 'volumen' => $cant * $peso * 5, 'purga' => $cant * $peso * 5 * $cpurga], //f18 glutamina
        ['rtotal' => $cant, 'volumen' => $cant / 100, 'purga' => $cant / 100 * $cpurga], //f19 vitamina c      
        ['rtotal' => $cant, 'volumen' => $cant, 'purga' => $cant * $cpurga], //f20 complejo B
        ['rtotal' => $cant, 'volumen' => $cant / 100, 'purga' => $cant / 100 * $cpurga], //f21 tiamina
        ['rtotal' => $cant, 'volumen' => $cant, 'purga' => $cant * $cpurga], //f22 acido folico
        ['rtotal' => $cant, 'volumen' => $cant * 0.1, 'purga' => $cant * 0.1 * $cpurga], //f23 vitamina k   
        // lipidos
        [], //f24        
        [], //f25        
        [], //f26        
    ];
    // calcular las otras formulas de potasio
    if ($pos == 8) {
      $constante = 0;
      if ($medi[1]['medicamento'] == 4) {
        $constante = 3.6;
      }
      if ($medi[1]['medicamento'] == 5) {
        $constante = 2;
      }
      $formulas[$pos] = ['rtotal' => $peso * $cant - ($medi[1]['cantidad'] * $constante), 'volumen' => ($peso * $cant - ($medi[1]['cantidad'] * $constante )) / 2, 'purga' => ($peso * $cant - $medi[1]['cantidad'] * $constante) / 2 * $cpurga]; //f9  potasio
    }
    // realizar el calculo para los lipidos
    switch ($pos) {
      case 23:
      case 24:
      case 25:
        $resta = 0;
        $factor=5;
        if ($medi[9]['cantidad'] > 0) {
          $resta =0.2*$medi[9]['cantidad'] / 0.099;
        }
        if($pos==25){
          $factor=10;
        }
        $rtotal=$cant * $peso;
        $volumen=abs(($rtotal-$resta) * $factor);
        $ppurga=$volumen*$cpurga;
        $formulas[$pos] = ['rtotal' => $rtotal,'volumen' =>$volumen,'purga' => abs($ppurga)]; //f23 f24 f25  
        break;
    }
    return $formulas[$pos];
  }

  public function prueba() {
    $tiempo = 24;
    $velocidad = 125;
    $formulacion = [
        'idpaciente' => 1,
        'tiempo' => $tiempo,
        'velocidad' => $velocidad,
        'volumen' => $velocidad * $tiempo,
        'purga' => 30,
        'peso' => 80,
        'total' => 300,
        'medicamentos' =>
        [
            [
                'medicamento' => 1,
                'cantidad' => 1
            ],
            [
                'medicamento' => 5,
                'cantidad' => 10
            ],
            [
                'medicamento' => 6,
                'cantidad' => 4
            ],
            [
                'medicamento' => 8,
                'cantidad' => 1
            ],
            [
                'medicamento' => 9,
                'cantidad' => 1
            ],
            [
                'medicamento' => 10,
                'cantidad' => 500
            ],
            [
                'medicamento' => 11,
                'cantidad' => 500
            ],
            [
                'medicamento' => 13,
                'cantidad' => 5
            ],
            [
                'medicamento' => 15,
                'cantidad' => 0
            ],
            [
                'medicamento' => 16,
                'cantidad' => 0.99
            ],
            [
                'medicamento' => 17,
                'cantidad' => 0.4
            ],
            [
                'medicamento' => 18,
                'cantidad' => 0.5
            ],
            [
                'medicamento' => 19,
                'cantidad' => 500
            ],
            [
                'medicamento' => 20,
                'cantidad' => 5
            ],
            [
                'medicamento' => 21,
                'cantidad' => 500
            ],
            [
                'medicamento' => 22,
                'cantidad' => 1
            ],
            [
                'medicamento' => 23,
                'cantidad' => 10
            ],
            [
                'medicamento' => 24,
                'cantidad' => 1.2
            ]
        ]
    ];

    foreach ($formulacion['medicamentos'] as $key => $value) {
      //if($key==23){

      $formu = $this->formulas($value['medicamento'] - 1, $value['cantidad'], $formulacion);
      echo ($key + 1) . ' => ' . $formu['rtotal'] . ' => ' . $formu['volumen'] . ' => ' . $formu['purga'] . '<br>';
      //}
//      
//      echo $formu['rtotal'] . ' => ' .
//      $formu['volumen'] . ' => ' .
//      $formu['purga'] . '<br>';
//      $this->fmm->insertar_formulacion_med(['medicamento' => $value['medicamento'], 'cantidad' => $value['cantidad'], 'idformulacion' => $idformu, 'rtotal' => $formu['rtotal'],
//          'volumen' => $formu['volumen'], 'purga' => $formu['purga']]);
    }
  }

  public function imprime_etiqueta() {

    $data = $this->getData($this->input->post('formul'));
    $data['ancho'] = 700;
    //$data= $this->getData(15);
    $this->load->view('admin/etiqueta', $data);
  }

  private function getData($id) { echo $id;
    $tabla = $this->fm->mostrar_formulacion($id);
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
        "nombrnpt" => '',
        "medicamentos" => [],
        "idxxxxxx" => 0
    );
    print_r($tabla);
    if (count($tabla) > 0) {
      $etiqueta = [];
      $total = 0;
      foreach ($etiqueta as $value) {
        $total = $total + $value["volumendia"];
      }
      $data["etiqueta"] = $tabla[0]; //$etiqueta;
      $data['medicamentos'] = $this->fmm->listar_formulacion_med($id);
      $data["total"] = $total;
      $data["volutota"] = ''; //$dataxxxx->volutota;
      $data["nombre"] = ''; //$dataxxxx->nombres_apellidos;
      $data["pesoxxxx"] = ''; // $dataxxxx->peso;
      $data["historia"] = ''; // $dataxxxx->historia;
      $data["camaxxxx"] = ''; //$dataxxxx->camaxxxx;
      $data["servicio"] = ''; //$dataxxxx->nombre;
      $data["clinica"] = ''; //$dataxxxx->clinicax;
      $data["nombrnpt"] = ''; //$dataxxxx->nombrnpt;
      $data["idxxxxxx"] = ''; //$dataxxxx->idxxxxxx;
    }
    return $data;
  }

  public function imprimirpdf($id) {
    $data = $this->getData($id);
    $data['ancho'] = 400;
    $data["pdf"] = '';
    ob_start();
    $this->load->view('admin/pdf', $data);
    $content = ob_get_clean();
    $this->load->library('html2pdf_lib');

    /*     * ******
     * $content = the html content to be converted
     * you can use file_get_content() to get the html from other location
     *
     * $filename = filename of the pdf file, make sure you put the extension as .pdf
     * $save_to = location where you want to save the file,
     *            set it to null will not save the file but display the file directly after converted
     * ***** */

    $filename = 'testing.pdf';
    $save_to = $this->config->item('upload_root');

    if ($this->html2pdf_lib->converHtml2pdf($content, $filename, $save_to)) {
      echo $save_to . '/' . $filename;
    } else {
      echo 'failed';
    }
  }

}

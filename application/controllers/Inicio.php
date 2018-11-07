<?php
defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');

/**
 * @autor Ing. Henry Ospina - hospinab@gmail.com
 * @nombre: Inicio
 * @version: 1.0
 * @fecha: 22 Julio 2016 
 * @descripcion: Inicio
 * */
class Inicio extends CI_Controller {

    var $parametros;

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->parametros['plantilla'] = 'iniciox';
        $this->parametros['vista'] = 'iniciox';

//        $this->Modelo->tabla = 'categorias';
        $this->parametros['datos']['categorias'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'orden ASC');

        $this->load->view('plantillaFront', $this->parametros);

/*
        $this->parametros['plantilla'] = 'bloqueo';
        $this->parametros['vista'] = 'front/bloqueo';
        $this->parametros['title'] = 'EnlaceRosa';
        $this->load->view('plantillaFront', $this->parametros);
*/
    }

    public function cf($categoria="", $numPaginas=0) {
        $this->session->set_userdata('numPaginas', $numPaginas);
        redirect('/c/' . $categoria);
    }

    public function c($categoria="", $pagina=0) {
        $this->parametros['plantilla'] = 'categoriax';
        $this->parametros['vista'] = 'front/categoriax';
        $this->parametros['title'] = 'EnlaceRosa';

        $this->Modelo->tabla = 'categorias';
        $datos_categoria = $this->Modelo->registro($categoria, "carpeta");

        if ( count($datos_categoria) ) {
            $this->parametros['datos']['categoria'] = $datos_categoria;

            $this->load->library("pagination");
            $config["base_url"] = base_url() . "c/" . $categoria;
            $config["total_rows"] = $this->Modelo->filasAnuncios($datos_categoria->id);

            if ( !$this->session->has_userdata('numPaginas') ) {
                $this->session->set_userdata('numPaginas', 12);
            }

            $config["per_page"] = $this->session->userdata('numPaginas');

            $config["uri_segment"] = 3;
            $config['query_string_segment'] = 'page';
            $config['full_tag_open'] = '<div><ul class="pagination pagination-small pagination-centered">';
            $config['full_tag_close'] = '</ul></div>';
            $config['first_link'] = '&laquo; Primera';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = 'Última &raquo;';
            $config['last_tag_open'] = '<li class="next page">';
            $config['last_tag_close'] = '</li>';
            $config['next_link'] = 'Siguiente &rarr;';
            $config['next_tag_open'] = '<li class="next page">';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = '&larr; Anterior';
            $config['prev_tag_open'] = '<li class="prev page">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="">&nbsp;&nbsp;';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';
            $config['anchor_class'] = 'follow_link';
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = round($choice);
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
            $this->parametros['datos']["anunciosRegistros"] = $this->Modelo->obtenerAnuncios($config["per_page"], $page, $datos_categoria->id);
            $this->parametros['datos']["anunciosPaginacion"] = $this->pagination->create_links();

        } else {
            redirect('/');
        }

        $this->load->view('plantillaFront', $this->parametros);
    }


// ----------------------------------------------------------------------------------
    public function pagos() {
        $this->parametros['plantilla'] = 'pagosx';
        $this->parametros['vista'] = 'front/pagosx';
        $this->parametros['title'] = 'EnlaceRosa';
        $this->load->view('plantillaFront', $this->parametros);
    }
    public function pagoexitoso() {
        $this->parametros['plantilla'] = 'pagosx';
        $this->parametros['vista'] = 'front/pagoexitosox';
        $this->parametros['title'] = 'EnlaceRosa';
        $this->load->view('plantillaFront', $this->parametros);
    }
    public function pagonoexitoso() {
        $this->parametros['plantilla'] = 'pagosx';
        $this->parametros['vista'] = 'front/pagonoexitosox';
        $this->parametros['title'] = 'EnlaceRosa';
        $this->load->view('plantillaFront', $this->parametros);
    }
    public function pagopendiente() {
        $this->parametros['plantilla'] = 'pagosx';
        $this->parametros['vista'] = 'front/pagopendientex';
        $this->parametros['title'] = 'EnlaceRosa';
        $this->load->view('plantillaFront', $this->parametros);
    }

// ----------------------------------------------------------------------------------
    public function contactenos() {
        $this->parametros['plantilla'] = 'contactenosx';
        $this->parametros['vista'] = 'front/contactenosx';
        $this->parametros['title'] = 'EnlaceRosa';

        $this->load->view('plantillaFront', $this->parametros);
    }

    public function contactenosProcesar() {
        $post = $this->input->post();

        if (!empty($post)) {

// - Contacto ---------------- 

            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean');
            $this->form_validation->set_rules('correo', 'Correo', 'required|valid_email|trim|xss_clean');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'required|trim|xss_clean');
            $this->form_validation->set_rules('asunto', 'Asunto', 'required|trim|xss_clean');
            $this->form_validation->set_rules('mensaje', 'Mensaje', 'required|trim|xss_clean');

// - Condiciones ------------------

            if ($this->form_validation->run()) {
                $fecha_valor = $this->config->item('YmdHis');

                $ip = ($this->input->ip_address() == '0.0.0.0' ? $this->input->ip_address() : $_SERVER['REMOTE_ADDR']);

                $registro = array(
                    'nombre' => $post['nombre'],
                    'correo' => $post['correo'],
                    'telefono' => $post['telefono'],
                    'asunto' => $post['asunto'],
                    'mensaje' => $post['mensaje'],
                    'ip' => $ip,
                    'fecha' => $fecha_valor,
                );

                $this->Modelo->tabla = 'contactenos';
                $registro_id = $this->Modelo->insertar($registro);
                if ($registro_id) {
                    $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_se_inserto_1_registro'));
                    $this->session->set_flashdata('msgTipo', 1); // Exito
                } else {
                    $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_no_fue_posible_insertar_el_registro') . ' ' . $this->lang->line('extras_am_por_favor_intentelo_nuevamente'));
                    $this->session->set_flashdata('msgTipo', 2); // Error
                }
            } else {
                $this->session->set_flashdata('msgTxt', validation_errors() . "<p>" . $this->lang->line('extras_am_por_favor_intentelo_nuevamente') . "</p>");
                $this->session->set_flashdata('msgTipo', 2); // Error
            }
        } else {
            $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_no_hay_datos_para_insertar_el_registro') . ' ' . $this->lang->line('extras_am_por_favor_intentelo_nuevamente') );
            $this->session->set_flashdata('msgTipo', 2); // Error
        }

        redirect ('/contactenos');

    }

// ---------------------------------------------------------------------------------


// ----------------------------------------------------------------------------------
    public function denuncio($codigo="") {
        $this->parametros['plantilla'] = 'denunciarx';
        $this->parametros['vista'] = 'front/denunciarx';
        $this->parametros['title'] = 'EnlaceRosa';

        $this->parametros['datos']['codigo'] = $codigo;

        $this->Modelo->tabla = 'denuncios_tipos';
        $this->parametros['datos']['denuncios_tipos'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'orden ASC');
        $this->load->view('plantillaFront', $this->parametros);
    }

    public function denuncioProcesar() {
        $post = $this->input->post();
        if (!empty($post)) {

// - Contacto ---------------- 

            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean');
            $this->form_validation->set_rules('correo', 'Correo', 'required|valid_email|trim|xss_clean');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'required|trim|xss_clean');
            $this->form_validation->set_rules('asunto', 'Asunto', 'required|trim|xss_clean');
            $this->form_validation->set_rules('codigo', 'Codigo Anuncio', 'required|trim|xss_clean');
            $this->form_validation->set_rules('mensaje', 'Mensaje', 'required|trim|xss_clean');

// - Condiciones ------------------

            if ($this->form_validation->run()) {
                $fecha_valor = $this->config->item('YmdHis');

                $ip = ($this->input->ip_address() == '0.0.0.0' ? $this->input->ip_address() : $_SERVER['REMOTE_ADDR']);

                $registro = array(
                    'nombre' => $post['nombre'],
                    'correo' => $post['correo'],
                    'telefono' => $post['telefono'],
                    'asunto' => $post['asunto'],
                    'mensaje' => $post['mensaje'],
                    'anuncio' => $post['codigo'],
                    'ip' => $ip,
                    'fecha' => $fecha_valor,
                );

                $this->Modelo->tabla = 'denuncios';
                $registro_id = $this->Modelo->insertar($registro);
                if ($registro_id) {
                    $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_se_inserto_1_registro'));
                    $this->session->set_flashdata('msgTipo', 1); // Exito
                } else {
                    $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_no_fue_posible_insertar_el_registro') . ' ' . $this->lang->line('extras_am_por_favor_intentelo_nuevamente'));
                    $this->session->set_flashdata('msgTipo', 2); // Error
                }
            } else {
                $this->session->set_flashdata('msgTxt', validation_errors() . "<p>" . $this->lang->line('extras_am_por_favor_intentelo_nuevamente') . "</p>");
                $this->session->set_flashdata('msgTipo', 2); // Error
            }
        } else {
            $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_no_hay_datos_para_insertar_el_registro') . ' ' . $this->lang->line('extras_am_por_favor_intentelo_nuevamente') );
            $this->session->set_flashdata('msgTipo', 2); // Error
        }

        redirect ('/denuncio/realizado');

    }

// ---------------------------------------------------------------------------------

    public function generarClave() {
        $clave = "";
        //      012345678901234567890123456789012345
        $str = "0123456789abcdefghijklmnopqrstuvwxyz";
        $clave = "";
        for($i=0;$i<10;$i++) {
            $clave .= substr($str,rand(0,35),1);
        }
        return $clave;
    }

// ----------------------------------------------------------------------------------
    public function informacion($tipo, $mensaje) {
        $this->parametros['plantilla'] = 'informacionx';
        $this->parametros['vista'] = 'front/informacionx';
        $this->parametros['title'] = 'EnlaceRosa';

        $this->parametros['tipo'] = $tipo;
        $this->parametros['mensaje'] = $mensaje;

        $this->load->view('plantillaFront', $this->parametros);
    }

    public function activacion($codigo="", $activacion="") {

        // echo "Codigo del anuncio ($codigo) y su codigo de activacion ($activacion)";

        // Aqui debe buscar el codigo y la activacion par activar el registro

        $this->Modelo->tabla = 'registros';
        $anuncio = $this->Modelo->registro($codigo, $llave = "codigo");
        
        if ( count($anuncio) ) {
            if ($anuncio->activo == '0') {
                if ($anuncio->activacion == $activacion) {
                    //echo "Aqui se debe activar el anuncio y llevar al usuario al anuncio";
                    
                    // echo "<pre>";
                    // print_r($anuncio);
                    // echo "</pre>";

                    $registro = array(
                        'activo' => '1',
                        'activacion_fecha' => $this->config->item('YmdHis'),
                    );
                    $this->Modelo->tabla = 'registros';
                    $registro_id = $this->Modelo->actualizar($registro, $anuncio->id);

                    $this->session->set_flashdata('msgTxt', "Tu anuncio ha sido activado correctamente. Ahora todos podran verlo.");
                    $this->session->set_flashdata('msgTipo', 1); // Exito

                    redirect('/a/' . url_title($anuncio->mi_anuncio_titulo, 'dash', TRUE) . '/' . $codigo);

                } else {
                    // echo "El codigo de activacion no coincide, comuniquese con el administrador";
                    $this->informacion(2, "El codigo de activacion no coincide, comuniquese con el administrador");
                }
            } else {
                // echo "Este anuncio ya se encuentra activo";
                // $this->informacion(3, "Este anuncio ya se encuentra activo");

                $this->session->set_flashdata('msgTxt', "Este anuncio ya se encuentra activo");
                $this->session->set_flashdata('msgTipo', 3); // Exito

                redirect('/a/' . url_title($anuncio->mi_anuncio_titulo, 'dash', TRUE) . '/' . $codigo);

            }
        } else {
            // echo "No encontro el anuncio, comuniquese con el administrador";
            $this->informacion(2, "No encontro el anuncio, comuniquese con el administrador");
        }

//        redirect('/anuncio/sinactivar');
//        redirect('/anuncio/activado');
    }


    public function a($titulo="", $codigo="") {
        $this->parametros['plantilla'] = 'anunciox';
        $this->parametros['vista'] = 'front/anunciox';
        $this->parametros['title'] = 'EnlaceRosa';
        $this->Modelo->tabla = 'registros';
        $anuncio = $this->Modelo->registros('', array('codigo' => $codigo, 'activo' => '1'));
        if ( count($anuncio) == 0 ) {
            redirect('/');
        } else {
            $this->Modelo->tabla = 'categorias';
            $this->parametros['datos']['categoria'] = $this->Modelo->registro($anuncio[0]['mi_anuncio_categoria']);

            $this->Modelo->tabla = 'idiomas';
            $this->parametros['datos']['idioma'] = $this->Modelo->registro($anuncio[0]['acerca_de_mi_idioma']);

            $this->Modelo->tabla = 'idiomas';
            $this->parametros['datos']['idioma_otro'] = $this->Modelo->registro($anuncio[0]['acerca_de_mi_idioma_otro']);

            $this->parametros['datos']['servicios'] =  $this->Modelo->servicios($anuncio[0]['id']);

            $this->Modelo->tabla = 'paises';
            $this->parametros['datos']['pais'] = $this->Modelo->registro($anuncio[0]['mi_localizacion_pais']);

            $this->Modelo->tabla = 'departamentos';
            $this->parametros['datos']['departamento'] = $this->Modelo->registro($anuncio[0]['mi_localizacion_departamento']);

            $this->Modelo->tabla = 'municipios';
            $this->parametros['datos']['ciudad'] = $this->Modelo->registro($anuncio[0]['mi_localizacion_ciudad']);

            $this->Modelo->tabla = 'complexion_fisica';
            $this->parametros['datos']['complexion_fisica'] = $this->Modelo->registro($anuncio[0]['mi_apariencia_complexion']);

            $this->Modelo->tabla = 'color_ojos';
            $this->parametros['datos']['color_ojos'] = $this->Modelo->registro($anuncio[0]['mi_apariencia_color_ojos']);

            $this->Modelo->tabla = 'color_pelo';
            $this->parametros['datos']['color_pelo'] = $this->Modelo->registro($anuncio[0]['mi_apariencia_color_pelo']);

            $this->Modelo->tabla = 'color_piel';
            $this->parametros['datos']['color_piel'] = $this->Modelo->registro($anuncio[0]['mi_apariencia_color_piel']);

            $this->parametros['datos']['anuncio'] = $anuncio;
            $this->load->view('plantillaFront', $this->parametros);
        }
    }

    public function bloqueo() {
        $this->parametros['plantilla'] = 'bloqueo';
        $this->parametros['vista'] = 'front/bloqueo';
        $this->parametros['title'] = 'EnlaceRosa';

        $this->load->view('plantillaFront', $this->parametros);
    }

// - Actualizacion -----------------------------------------------------------------------------

    public function actualizacion($codigo) {

        $this->Modelo->tabla = 'registros';
        $anuncio = $this->Modelo->registro($codigo, $llave = "codigo");
        
        if ( count($anuncio) == 1 ) {
            $this->parametros['plantilla'] = 'registrox';
            $this->parametros['vista'] = 'front/registroy';
            $this->parametros['title'] = 'EnlaceRosa';

            $this->parametros['datos']['anuncio'] = $anuncio;

            $this->Modelo->tabla = 'registro_servicios';
            $this->parametros['datos']['anuncio_servicios'] = $this->Modelo->registros(NULL, array('activo' => '1', 'registro' => $anuncio->id), NULL, NULL, NULL);

            $this->Modelo->tabla = 'paises';
            $this->parametros['datos']['paises11'] = $this->Modelo->registros(NULL, array('activo' => '1', 'principal' => '1', ), NULL, NULL, 'nombre ASC');
            $this->parametros['datos']['paises10'] = $this->Modelo->registros(NULL, array('activo' => '1', 'principal' => '0', ), NULL, NULL, 'nombre ASC');

            $this->Modelo->tabla = 'departamentos';
            $this->parametros['datos']['departamentos'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

            $this->Modelo->tabla = 'estatura';
            $this->parametros['datos']['estatura'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'orden ASC');

            $this->Modelo->tabla = 'complexion_fisica';
            $this->parametros['datos']['complexion_fisica'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

            $this->Modelo->tabla = 'color_ojos';
            $this->parametros['datos']['color_ojos'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

            $this->Modelo->tabla = 'color_pelo';
            $this->parametros['datos']['color_pelo'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

            $this->Modelo->tabla = 'color_piel';
            $this->parametros['datos']['color_piel'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

            $this->Modelo->tabla = 'categorias';
            $this->parametros['datos']['categorias'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

            $this->Modelo->tabla = 'idiomas';
            $this->parametros['datos']['idiomas'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

            $this->Modelo->tabla = 'servicios';
            $this->parametros['datos']['servicios'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');


            $this->parametros['javascriptConstruido'] = '
<script type="text/javascript">
    $(function () {
        departamentos('.$anuncio->mi_localizacion_pais.');
        $("#mi_localizacion_departamento").val('.$anuncio->mi_localizacion_departamento.');
        municipios('.$anuncio->mi_localizacion_departamento.');
        $("#mi_localizacion_ciudad").val('.$anuncio->mi_localizacion_ciudad.');
        localidades('.$anuncio->mi_localizacion_ciudad.');
        $("#mi_localizacion_barrio").val("'.$anuncio->mi_localizacion_barrio.'");
    });
</script>
';

            $this->load->view('plantillaFront', $this->parametros);

        } else {

            redirect('/');

        }

    }

// ------------------------------------------------------------------------------

    public function registro() {
        $this->parametros['plantilla'] = 'registrox';
        $this->parametros['vista'] = 'front/registrox';
        $this->parametros['title'] = 'EnlaceRosa';

        $this->Modelo->tabla = 'paises';
        $this->parametros['datos']['paises11'] = $this->Modelo->registros(NULL, array('activo' => '1', 'principal' => '1', ), NULL, NULL, 'nombre ASC');
        $this->parametros['datos']['paises10'] = $this->Modelo->registros(NULL, array('activo' => '1', 'principal' => '0', ), NULL, NULL, 'nombre ASC');

        $this->Modelo->tabla = 'departamentos';
        $this->parametros['datos']['departamentos'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

        $this->Modelo->tabla = 'estatura';
        $this->parametros['datos']['estatura'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'orden ASC');

        $this->Modelo->tabla = 'complexion_fisica';
        $this->parametros['datos']['complexion_fisica'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

        $this->Modelo->tabla = 'color_ojos';
        $this->parametros['datos']['color_ojos'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

        $this->Modelo->tabla = 'color_pelo';
        $this->parametros['datos']['color_pelo'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

        $this->Modelo->tabla = 'color_piel';
        $this->parametros['datos']['color_piel'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

        $this->Modelo->tabla = 'categorias';
        $this->parametros['datos']['categorias'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

        $this->Modelo->tabla = 'idiomas';
        $this->parametros['datos']['idiomas'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

        $this->Modelo->tabla = 'servicios';
        $this->parametros['datos']['servicios'] = $this->Modelo->registros(NULL, array('activo' => '1', ), NULL, NULL, 'nombre ASC');

        $this->load->view('plantillaFront', $this->parametros);
    }


    public function registroProcesar() {
        $post = $this->input->post();

        if (!empty($post)) {

// - Mi datos ---------------- 

            $this->form_validation->set_rules('mis_datos_email', 'Mis Datos:Email', 'required|valid_email|trim|xss_clean');
            $this->form_validation->set_rules('mis_datos_telefono', 'Mis Datos:Teléfono', 'required|trim|xss_clean');
            $this->form_validation->set_rules('mis_datos_whatsapp', 'Mis Datos:Whatsapp', 'trim|xss_clean');
            $this->form_validation->set_rules('mis_datos_web', 'Mis Datos:Web', 'valid_url|trim|xss_clean');

// - Mi anuncio ----------------

            $this->form_validation->set_rules('mi_anuncio_categoria', 'Mi anuncio:Categoría', 'required|trim|xss_clean');
            $this->form_validation->set_rules('mi_anuncio_titulo', 'Mi anuncio:Título', 'required|trim|xss_clean');
            $this->form_validation->set_rules('mi_anuncio_descripcion', 'Mi anuncio:Descripción', 'required|trim|xss_clean');

// - Mi localización ----------------

            $this->form_validation->set_rules('mi_localizacion_pais', 'Mi localización:País', 'required|trim|xss_clean');
            $this->form_validation->set_rules('mi_localizacion_departamento', 'Mi localización:Departamento', 'required|trim|xss_clean');
            $this->form_validation->set_rules('mi_localizacion_ciudad', 'Mi localización:Ciudad', 'required|trim|xss_clean');
            $this->form_validation->set_rules('mi_localizacion_barrio', 'Mi localización:Barrio / Localidad', 'trim|xss_clean');

// - Acerca de mi ----------------

            $this->form_validation->set_rules('acerca_de_mi_edad', 'Acerca de mi:Edad', 'trim|xss_clean');
            $this->form_validation->set_rules('acerca_de_mi_idioma', 'Acerca de mi:Qué idioma hablas?', 'trim|xss_clean');
            $this->form_validation->set_rules('acerca_de_mi_idioma_otro', 'Acerca de mi:Qué otro idioma hablas?', 'trim|xss_clean');

// - Mi apariencia ----------------

            $this->form_validation->set_rules('mi_apariencia_estatura', 'Mi apariencia:Estatura', 'trim|xss_clean');
            $this->form_validation->set_rules('mi_apariencia_complexion', 'Mi apariencia:Complexión Física', 'trim|xss_clean');
            $this->form_validation->set_rules('mi_apariencia_color_ojos', 'Mi apariencia:Color Ojos', 'trim|xss_clean');
            $this->form_validation->set_rules('mi_apariencia_color_pelo', 'Mi apariencia:Color Pelo', 'trim|xss_clean');
            $this->form_validation->set_rules('mi_apariencia_color_piel', 'Mi apariencia:Color Piel', 'trim|xss_clean');

// - Youtube ----------------------

            $this->form_validation->set_rules('youtube_url', 'Youtube:URL', 'valid_url|trim|xss_clean');

// - Condiciones ------------------
// - Habeas Data ------------------

            if ($this->form_validation->run()) {
                $fecha_valor = $this->config->item('YmdHis');

                $config['upload_path']          = './imagen/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 2048;
                $this->load->library('upload', $config);

                $fotos_archivo_1 = $fotos_archivo_2 = $fotos_archivo_3 = $fotos_archivo_4 = $fotos_archivo_5 = "";

                if ($this->upload->do_upload('fotos_archivo_1')) {
                    $data1 = array('upload_data' => $this->upload->data());
                    $zfile = $data1['upload_data']['full_path']; // get file path
                    chmod($zfile,0777); // CHMOD file
                    $fotos_archivo_1 = sha1(date("YmdHis") . $data1['upload_data']['file_name']) . "." . $data1['upload_data']['image_type'];
                    rename($data1['upload_data']['full_path'],$data1['upload_data']['file_path'].$fotos_archivo_1);
                    $this->fotofix ($fotos_archivo_1, $data1['upload_data']['file_path']);
                }

                if ($this->upload->do_upload('fotos_archivo_2')) {
                    $data2 = array('upload_data' => $this->upload->data());
                    $fotos_archivo_2 = sha1(date("YmdHis") . $data2['upload_data']['file_name']) . "." . $data2['upload_data']['image_type'];
                    rename($data2['upload_data']['full_path'],$data2['upload_data']['file_path'].$fotos_archivo_2);
                    $this->fotofix ($fotos_archivo_2, $data2['upload_data']['file_path']);
                }

                if ($this->upload->do_upload('fotos_archivo_3')) {
                    $data3 = array('upload_data' => $this->upload->data());
                    $fotos_archivo_3 = sha1(date("YmdHis") . $data3['upload_data']['file_name']) . "." . $data3['upload_data']['image_type'];
                    rename($data3['upload_data']['full_path'],$data3['upload_data']['file_path'].$fotos_archivo_3);
                    $this->fotofix ($fotos_archivo_3, $data3['upload_data']['file_path']);
                }

                if ($this->upload->do_upload('fotos_archivo_4')) {
                    $data4 = array('upload_data' => $this->upload->data());
                    $fotos_archivo_4 = sha1(date("YmdHis") . $data4['upload_data']['file_name']) . "." . $data4['upload_data']['image_type'];
                    rename($data4['upload_data']['full_path'],$data4['upload_data']['file_path'].$fotos_archivo_4);
                    $this->fotofix ($fotos_archivo_4, $data4['upload_data']['file_path']);
                }

                if ($this->upload->do_upload('fotos_archivo_5')) {
                    $data5 = array('upload_data' => $this->upload->data());
                    $fotos_archivo_5 = sha1(date("YmdHis") . $data5['upload_data']['file_name']) . "." . $data5['upload_data']['image_type'];
                    rename($data5['upload_data']['full_path'],$data5['upload_data']['file_path'].$fotos_archivo_5);
                    $this->fotofix ($fotos_archivo_5, $data5['upload_data']['file_path']);
                }

                $ip = ($this->input->ip_address() == '0.0.0.0' ? $this->input->ip_address() : $_SERVER['REMOTE_ADDR']);

                $codigo = $this->generarClave();
                $activacion = $this->generarClave();
                $clave = $this->generarClave();

                $this->Modelo->tabla = 'registros';
                if ( isset($post['id']) ) {
                    // Actualiza
                    $registro = array(
                        'mis_datos_email' => $post['mis_datos_email'],
                        'mis_datos_web' => $post['mis_datos_web'],
                        'mis_datos_telefono' => $post['mis_datos_telefono'],
                        'mis_datos_whatsapp' => $post['mis_datos_whatsapp'],
                        'mi_localizacion_pais' => $post['mi_localizacion_pais'],
                        'mi_localizacion_departamento' => $post['mi_localizacion_departamento'],
                        'mi_localizacion_ciudad' => $post['mi_localizacion_ciudad'],
                        'mi_localizacion_barrio' => $post['mi_localizacion_barrio'],
                        'mi_apariencia_estatura' => $post['mi_apariencia_estatura'],
                        'mi_apariencia_complexion' => $post['mi_apariencia_complexion'],
                        'mi_apariencia_color_ojos' => $post['mi_apariencia_color_ojos'],
                        'mi_apariencia_color_pelo' => $post['mi_apariencia_color_pelo'],
                        'mi_apariencia_color_piel' => $post['mi_apariencia_color_piel'],
                        'youtube_url' => $post['youtube_url'],
                        'mi_anuncio_categoria' => $post['mi_anuncio_categoria'],
                        'mi_anuncio_titulo' => $post['mi_anuncio_titulo'],
                        'mi_anuncio_descripcion' => $post['mi_anuncio_descripcion'],
                        'acerca_de_mi_edad' => $post['acerca_de_mi_edad'],
                        'acerca_de_mi_idioma' => $post['acerca_de_mi_idioma'],
                        'acerca_de_mi_idioma_otro' => $post['acerca_de_mi_idioma_otro'],
                        'ip' => $ip,
                        'fecha' => $fecha_valor,
                    );

                    if ($fotos_archivo_1 != '') $registro['fotos_archivo_1'] = $fotos_archivo_1;
                    if ($fotos_archivo_2 != '') $registro['fotos_archivo_2'] = $fotos_archivo_2;
                    if ($fotos_archivo_3 != '') $registro['fotos_archivo_3'] = $fotos_archivo_3;
                    if ($fotos_archivo_4 != '') $registro['fotos_archivo_4'] = $fotos_archivo_4;
                    if ($fotos_archivo_5 != '') $registro['fotos_archivo_5'] = $fotos_archivo_5;

                    if ( $this->Modelo->actualizar($registro, $post['id']) ) {
                        $registro_id = $post['id'];
                    } else {
                        $registro_id = false;
                    }

                } else {
                    // Inserta
                    $registro = array(
                        'codigo' => $codigo,
                        'mis_datos_email' => $post['mis_datos_email'],
                        'mis_datos_web' => $post['mis_datos_web'],
                        'mis_datos_telefono' => $post['mis_datos_telefono'],
                        'mis_datos_whatsapp' => $post['mis_datos_whatsapp'],
                        'mi_localizacion_pais' => $post['mi_localizacion_pais'],
                        'mi_localizacion_departamento' => $post['mi_localizacion_departamento'],
                        'mi_localizacion_ciudad' => $post['mi_localizacion_ciudad'],
                        'mi_localizacion_barrio' => $post['mi_localizacion_barrio'],
                        'mi_apariencia_estatura' => $post['mi_apariencia_estatura'],
                        'mi_apariencia_complexion' => $post['mi_apariencia_complexion'],
                        'mi_apariencia_color_ojos' => $post['mi_apariencia_color_ojos'],
                        'mi_apariencia_color_pelo' => $post['mi_apariencia_color_pelo'],
                        'mi_apariencia_color_piel' => $post['mi_apariencia_color_piel'],
                        'youtube_url' => $post['youtube_url'],
                        'mi_anuncio_categoria' => $post['mi_anuncio_categoria'],
                        'mi_anuncio_titulo' => $post['mi_anuncio_titulo'],
                        'mi_anuncio_descripcion' => $post['mi_anuncio_descripcion'],
                        'acerca_de_mi_edad' => $post['acerca_de_mi_edad'],
                        'acerca_de_mi_idioma' => $post['acerca_de_mi_idioma'],
                        'acerca_de_mi_idioma_otro' => $post['acerca_de_mi_idioma_otro'],
                        'fotos_archivo_1' => $fotos_archivo_1,
                        'fotos_archivo_2' => $fotos_archivo_2,
                        'fotos_archivo_3' => $fotos_archivo_3,
                        'fotos_archivo_4' => $fotos_archivo_4,
                        'fotos_archivo_5' => $fotos_archivo_5,
                        'ip' => $ip,
                        'fecha' => $fecha_valor,
                        'activo' => '0',
                        'activacion' => $activacion,
                        'clave' => $clave,
                    );
                    $registro_id = $this->Modelo->insertar($registro);
                }


                if ($registro_id) {
                    // 1) debe borrar
                    $this->Modelo->tabla = 'registro_servicios';
                    $this->Modelo->llave = 'registro';
                    $this->Modelo->eliminar($registro_id);

                    // 2) debe insertar
                    $this->Modelo->tabla = 'registro_servicios';
                    if ( isset($post['servicio']) ) {
                        foreach ($post['servicio'] as $servicio) {
                            $id = $this->Modelo->insertar(array(
                                'registro' => $registro_id,
                                'servicio' => $servicio,
                                'activo' => '1',
                            ));
                        }
                    }

                    if ( isset($post['id']) ) {
                        // Actualizar
                        $this->session->set_flashdata('msgTxt', "Tu anuncio ha sido actulizado correctamente.");
                        $this->session->set_flashdata('msgTipo', 1); // Exito
                    } else {
                        // Insertar
                        $this->session->set_flashdata('msgTxt', "Tu anuncio ha sido almacenado correctamente, te hemos enviado un correo electronico con un enlace de activacion.<br>Si no encuentras tu correo en la bandeja de entrada no olvides buscar en correo no deseado o spam, o comunicate con el administrador del sistema.");
                        $this->session->set_flashdata('msgTipo', 1); // Exito

                        // Enviar correo de activacion
                        $datosactivacion['codigo'] = $codigo;
                        $datosactivacion['activacion'] = $activacion;
                        $datosactivacion['clave'] = $clave;
                        $datosactivacion['correo'] = $post['mis_datos_email'];

                        $mensajeActivacion = $this->load->view('correos/anuncioactivacion', $datosactivacion, true);
                        $this->load->library('email');
                        $this->email->set_mailtype("html"); 
                        $this->email->from('info@numixx.com', 'Numixx SAS');
                        $this->email->bcc('hospinab@gmail.com'); 
                        $this->email->to($post['mis_datos_email']); 
                        $this->email->subject('Activacion de Anuncio');
                        $this->email->message($mensajeActivacion);  
                        $this->email->send();

                    }
    
                } else {
                    $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_no_fue_posible_insertar_el_registro') . ' ' . $this->lang->line('extras_am_por_favor_intentelo_nuevamente'));
                    $this->session->set_flashdata('msgTipo', 2); // Error
                }
            } else {
                $this->session->set_flashdata('msgTxt', validation_errors() . "<p>" . $this->lang->line('extras_am_por_favor_intentelo_nuevamente') . "</p>");
                $this->session->set_flashdata('msgTipo', 2); // Error
            }
        } else {
            $this->session->set_flashdata('msgTxt', $this->lang->line('extras_am_no_hay_datos_para_insertar_el_registro') . ' ' . $this->lang->line('extras_am_por_favor_intentelo_nuevamente') );
            $this->session->set_flashdata('msgTipo', 2); // Error
        }

        if ( isset($post['id']) ) {

            $this->Modelo->tabla = 'registros';
            $anuncio = $this->Modelo->registros('', array('id' => $post['id']));
            if ( count($anuncio) == 0 ) {
                redirect('/');
            } else {
                redirect ('a/'.url_title($anuncio[0]['mi_anuncio_titulo'], 'dash', TRUE).'/'.$anuncio[0]['codigo']);
            }

        } else {
            redirect ('/registro');
        }


    }


    public function registroActualizar() {

        $post = $this->input->post();
        if (!empty($post)) {
            if ($post['retirar'] == '1') {
                echo "Aqui debe retirar el anuncio<hr>";
                $this->Modelo->tabla = 'registros';
                $anuncio = $this->Modelo->registro($post['id'], $llave = "id");
                if ( count($anuncio) == 1 ) {
                    echo "<pre>";
                    print_r($anuncio);
                    echo "</pre>";

                    $registro = array(
                        'activo' => '0'
                    );
                    $this->Modelo->tabla = 'registros';
                    $registro_id = $this->Modelo->actualizar($registro, $post['id']);

                    $this->session->set_flashdata('msgTxt', "Tu anuncio ha sido retirado correctamente.");
                    $this->session->set_flashdata('msgTipo', 1); // Exito

                } else {
                    $this->session->set_flashdata('msgTxt', "No se ha encontrador el anuncio. Intentalo nuevamente.");
                    $this->session->set_flashdata('msgTipo', 2); // Exito
                }
            }
        } else {
            $this->session->set_flashdata('msgTxt', "No llegaron loa datos del anuncio. Intentalo nuevamente.");
            $this->session->set_flashdata('msgTipo', 2); // Exito
        }

        redirect ('/registro');


    }



    public function ciudades() {
        $post = $this->input->post();
        $datos = array();
        if (!empty($post)) {
            $this->Modelo->tabla = 'ciudades';
            $datos = $this->Modelo->registros("*", array("departamento" => $post['departamento'], "activo" => "1") );
        }
        echo json_encode($datos);
    }

    public function fotofix ($imagen, $ruta) {
        $this->load->library('image_lib');

        $anchoCrop = 260;
        $altoCrop = 260;
        $partes_ruta = pathinfo($imagen);
        $filename = $partes_ruta['filename'];
        $extension = $partes_ruta['extension'];

        $config['image_library'] = 'gd2';
        $config['source_image'] = "imagen/" . $imagen;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['height']   = "600";
        $config['width'] = "600";
        $config['new_image'] = "imagen/" . $filename . "-enlacerosa-1." . $extension;
        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {
            //echo "<br>Error1: (" . $config['source_image'] . ")" . $this->image_lib->display_errors();
        } else {
            $config['source_image'] = "imagen/" . $filename . "-enlacerosa-1." . $extension;
            $config['new_image'] = "imagen/" . $filename . "-enlacerosa-1." . $extension;
            $config['wm_type'] = 'overlay';
            $config['wm_overlay_path'] = 'logos/logo.png';
            $config['quality'] = '90%';
            $config['wm_vrt_alignment'] = 'middle'; 
            $config['wm_hor_alignment'] = 'center';
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            if (!$this->image_lib->watermark()) {
                //echo "<br>Error2: (" . $config['source_image'] . ")" . $this->image_lib->display_errors();
            } else {
                $tamano = getimagesize( "imagen/" . $filename . "-enlacerosa-1." . $extension);
                $ancho = $tamano[0];
                $alto = $tamano[1];

                if ( $ancho == $alto ) {
                    $config['source_image'] = "imagen/" . $filename . "-enlacerosa-1." . $extension;
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = TRUE;
                    $config['height'] = $altoCrop;
                    $config['width'] = $anchoCrop;
                    $config['new_image'] = "imagen/" . $filename . "-enlacerosa-2." . $extension;
                    $this->image_lib->clear();
                    $this->image_lib->initialize($config);
                    if (!$this->image_lib->resize()) {
                        //echo "<br>Error3: (" . $config['source_image'] . ")" . $this->image_lib->display_errors();
                    } else {
                        //echo " ---> OK";
                    }
                } else {
                    if ($ancho > $alto) { // Horizontal
                        $anchoMin = $alto;
                        $altoMin = $alto;
                    } elseif ($alto > $ancho) { // Vertical
                        $anchoMin = $ancho;
                        $altoMin = $ancho;
                    }
                    $config['source_image'] = "imagen/" . $filename . "-enlacerosa-1." . $extension;
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = FALSE;
                    $config['height']   = $altoMin;
                    $config['width'] = $anchoMin;
                    $config['new_image'] = "imagen/" . $filename . "-enlacerosa-2." . $extension;
                    $this->image_lib->clear();
                    $this->image_lib->initialize($config);
                    if (!$this->image_lib->crop()) {
                        //echo "<br>Errorx: (" . $config['source_image'] . ")" . $this->image_lib->display_errors();
                    } else {
                        $config['source_image'] = "imagen/" . $filename . "-enlacerosa-2." . $extension;
                        $config['create_thumb'] = FALSE;
                        $config['maintain_ratio'] = FALSE;
                        $config['height'] = $altoCrop;
                        $config['width'] = $anchoCrop;
                        $config['new_image'] = "imagen/" . $filename . "-enlacerosa-2." . $extension;
                        $this->image_lib->clear();
                        $this->image_lib->initialize($config);
                        if (!$this->image_lib->resize()) {
                            //echo "<br>Error3: (" . $config['source_image'] . ")" . $this->image_lib->display_errors();
                        } else {
                            //echo " ---> OK";
                        }
                    }
                }
            }
        }

        if ( unlink($ruta . $imagen) ) {
             //echo 'Imagen ' . $ruta . $imagen . ' eliminada exitosamente <br>';
        } else {
             //echo 'Error al eliminar la imagen ' . $ruta . $imagen;
        }

    }

    public function departamentos() {
        $post = $this->input->post();
        $opciones = '';
        if (!empty($post)) {
            $this->Modelo->tabla = 'departamentos';
            $datos = $this->Modelo->registros(NULL, array('activo' => '1', 'pais' => $post['pais']), NULL, NULL, 'orden ASC, nombre ASC');
            foreach ($datos as $registro) {
                $opciones .= '<option value="'.$registro['id'].'">'.$registro['nombre'].'</option>';
            }
            if ($opciones != "") {
                $opciones = '<select class="inputbox" name="mi_localizacion_departamento" id="mi_localizacion_departamento"><option value="">Selecciona tu departamento</option>' . $opciones . '</select>';
            }

        }
        echo $opciones;
    }

    public function municipios() {
        $post = $this->input->post();
        $opciones = '';
        if (!empty($post)) {
            $this->Modelo->tabla = 'municipios';
            $datos = $this->Modelo->registros(NULL, array('activo' => '1', 'departamento' => $post['departamento']), NULL, NULL, 'orden ASC, nombre ASC');
            foreach ($datos as $registro) {
                $opciones .= '<option value="'.$registro['id'].'">'.$registro['nombre'].'</option>';
            }
            if ($opciones != "") {
                $opciones = '<select class="inputbox" name="mi_localizacion_ciudad" id="mi_localizacion_ciudad"><option value="">Selecciona tu ciudad</option>' . $opciones . '</select>';
            }

        }
        echo $opciones;
    }


    public function localidades() {
        $post = $this->input->post();
        $opciones = '';
        if (!empty($post)) {
            $this->Modelo->tabla = 'localidades';
            $datos = $this->Modelo->registros(NULL, array('activo' => '1', 'municipio' => $post['ciudad']), NULL, NULL, 'orden ASC, nombre ASC');
            foreach ($datos as $registro) {
                $opciones .= '<option value="'.$registro['nombre'].'">'.$registro['nombre'].'</option>';
            }
            if ($opciones != "") {
                $opciones = '<select class="inputbox" name="mi_localizacion_barrio" id="mi_localizacion_barrio"><option value="">Selecciona tu localidad</option>' . $opciones . '</select>';
            }
        }
        echo $opciones;
    }


// - Correo ------------------

    public function correo() {
        $this->load->library('email');

        $this->email->from('hospinab@gmail.com', 'HOB');
        $this->email->to('hospinab@gmail.com'); 
        $this->email->cc('hospinab@gmail.com'); 
        $this->email->bcc('hospinab@gmail.com'); 

        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');  

        $this->email->send();

        echo $this->email->print_debugger();        
    } 



}

<?php
defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');
/**
 * @autor Ing. Henry Ospina - hospinab@gmail.com
 * @nombre plantilla.php
 * @descripcion: 
 *              Manejo de footer (cabeza) y header (pie) en la pagina archivos correspondientes a la 
 *              pagina desplegada se crean el nombre de la carpeta igual al nombre de la 
 *              vista con el js o css dentro de la caperta segun corresponda con el 
 *              nombre "vista.css" o "vista.js" segun como se necesite.
 */
if (!isset($datos)) {
    $datos = null;
}
if (!isset($vista)) {
    $datos = null;
    die('falta Vista');
}


$parametros['menu'] = $this->session->userdata('menu');

/*
$parametros['title'] = $this->lang->line('extras_title');
$parametros['titulo_logo_mini'] = $this->lang->line('extras_titulo_logo_mini');
$parametros['titulo_logo_lg'] = $this->lang->line('extras_titulo_logo_lg');

if (isset($datos['titulo'])) {
	$parametros['titulo'] = $datos['titulo'];
} else {
	$parametros['titulo'] = '';
}
if (isset($datos['subtitulo'])) {
  $parametros['subtitulo'] = $datos['subtitulo'];
} else {
  $parametros['subtitulo'] = '';
}
if (isset($datos['breadcrumb'])) {
  $parametros['breadcrumb'] = $datos['breadcrumb'];
} else {
  $parametros['breadcrumb'] = '';
}

$parametros['tituloPagina'] = '';
if (trim($parametros['titulo']) != '' or trim($parametros['subtitulo']) != '') {
  $parametros['tituloPagina'] = '
    <section class="content-header">
      <h1>
        '.trim($parametros['titulo']).' <small>'.trim($parametros['subtitulo']).'</small>
      </h1>
      '.$parametros['breadcrumb'].'
    </section>';
}

$parametros['proyVar'] = '<script> var proyVar =' . json_encode($this->config->item('proyVar')) . '</script>';
$parametros['txtVar'] = '<script> var txtVar =' .  json_encode($this->lang->language) . '</script>';
*/

$parametros['script'] = '';

$parametros['estilo'] = '<link rel="stylesheet" href="' . base_url() . 'assets/admin/css/global.css">';
$js = 'assets/admin/js/' . $vista . '/vista.js';
$css = 'assets/admin/css/' . $vista . '/vista.css';
if (file_exists($js)) {
    $parametros['script'] = '<script src="' . base_url() . $js . '"></script>';
}
if (file_exists($css)) {
    $parametros['estilo'] .= "\n" . '  <link rel="stylesheet" href="' . base_url() . $css . '">';
}

if (!isset($plantilla)) {
  $cabeza = '/basicoCabeza';
  $pie = '/basicoPie';
} else {
  $cabeza = "/{$plantilla}Cabeza";
  $pie = "/{$plantilla}Pie";
}

/*
$parametros['sidebar_collapse'] = '';
if ($vista != 'admin/panel') {
  $parametros['sidebar_collapse'] = 'sidebar-collapse';
}
$parametros['footer'] = '';
*/

$this->load->view('plantilla/admin' . $cabeza, $parametros);
$this->load->view($vista, $datos);
$this->load->view('plantilla/admin' . $pie, $parametros);

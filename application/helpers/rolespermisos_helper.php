<?php

/**
 * Helper para gestionar los roles y los permisos de un unsuario
 *
 * @author Nowen21
 */
if (!function_exists('can')) {

  function can($slug) {
    $respuesta = FALSE;
    $CI = & get_instance();
    $CI->load->model('Permisos', 'p');
    $permisos = $CI->session->userdata['u_perfil'];
    if (count($permisos) == 1 & $permisos[0] == 'all-access') { // es administrador
      $respuesta = TRUE;
    } else {
      if (in_array($slug, $permisos)) {
        $respuesta = TRUE;
      }
    }
    return $respuesta;
  }

}

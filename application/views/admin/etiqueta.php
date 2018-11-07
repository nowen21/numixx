<?php ?>

<div class="container" style="width: <?=$ancho?>px;">
  
  <?php
  require_once 'etiquetapdf.php';
  ?> 
  <a href="<?= base_url("admin/formulacion/imprimirpdf/{$etiqueta->idformulacion}") ?>" target="_blank" class="btn btn-primary">Imprimir</a>
</div>


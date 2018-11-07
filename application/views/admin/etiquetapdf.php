<table style=" width: 90%;" >
  <tr>
    <td style="width: 40%; text-align: left; height: 75px; " rowspan="3" >
      <img src="<?= base_url("assets/img/logo.jpg") ?>"   style="width: 135px; height: 76px;" alt="logo">
    </td>
    <td style=" width: 40%; text-align: left;  " colspan="2" rowspan="2"></td>

    <td style=" width: 20%; text-align: left; ">CÓDIGO</td>
  </tr>
  <tr>     
    <td style=" width: 20%; text-align: left; ">PRO-FO-37-V0</td>
  </tr>
  <tr>          
    <td style=" width: 20%; text-align: left; "></td>      
    <td style=" width: 20%; text-align: left; "></td>      
    <td style=" width: 20%; text-align: left; "></td>      
  </tr>
</table>
<table style=" width: 100%; " >
  <tr> 
    <td style=" width: 80%; text-align: left; " >
      NUTRICIÓN PARENTERAL
    </td>
    <td style=" width: 20%; text-align: left; ">
      1
    </td>
  </tr>
</table>
<table style=" width: 100%; " >
  <tr> 
    <td style=" width: 25%; text-align: left; " >
      N° HISTORIA CLÍNICA
    </td>      
    <td style=" width: 25%; text-align: left; ">
      N° CAMA
    </td>
    <td style=" width: 25%; text-align: left; ">
      SERVICIO
    </td>
    <td style=" width: 25%; text-align: left; ">
      FECHA VENCIMIENTO
    </td>
  </tr>
  <tr> 
    <td style=" width: 25%; text-align: left; " >
      <?= $historia ?>
    </td>      
    <td style=" width: 25%; text-align: left; ">
      <?= $camaxxxx ?>
    </td>
    <td style=" width: 25%; text-align: left; ">
      <?= $servicio ?>
    </td>
    <td style=" width: 25%; text-align: left; ">
      pendiente
    </td>
  </tr>
</table>
<table style=" width: 100%; " >
  <tr> 
    <td style=" width: 50%; text-align: left;" >
      NOMBRE Y APELLIDO
    </td>      
    <td style=" width: 25%; text-align: left; ">
      PESO
    </td>
    <td style=" width: 25%; text-align: left; ">
      VIA
    </td>      
  </tr>
  <tr> 
    <td style=" width: 50%; text-align: left; " >
      <?= $nombre ?>
    </td>      
    <td style=" width: 25%; text-align: left; ">
      <?= $pesoxxxx ?>
    </td>
    <td style=" width: 25%; text-align: left; ">
      no se
    </td>      
  </tr>
</table>
<table style=" width: 100%; " >
  <tr> 
    <td style=" width: 25%; text-align: left; " >
      CLÍNICA:
    </td>      
    <td style=" width: 50%; text-align: left; ">
      <?= $clinica ?>
    </td>
    <td style=" width: 25%; text-align: left; ">
      <?= $nombrnpt ?>
    </td>      
  </tr>
</table>
<table style=" width: 100%;" >
  <thead >
    <tr> 
      <th style=" width: 60%; text-align: left; border-top: 2px #000 solid;border-bottom: 2px #000 solid;" >
        PRODUCTO:
      </th>      
      <th style=" width: 10%; text-align: left; border-top: 2px #000 solid;border-bottom: 2px #000 solid;">
        REQ
      </th>
      <th style=" width: 15%; text-align: left;border-top: 2px #000 solid;border-bottom: 2px #000 solid;">
        VOLUMEN
      </th>      
      <th style=" width: 15%; text-align: left;border-top: 2px #000 solid;border-bottom: 2px #000 solid;">
        VOLUMEN PURGA
      </th>      
    </tr>
  </thead>
  <tbody >
    <?php
    $total = 0;
    $osmo = 0;
    $fosfato = 0;

    $calcio = 0;
    foreach ($medicamentos as $value) {
      $total += $value->volumen;
      $osmo += $value->osmoralidad * $value->purga;
      switch ($value->medicamento) {
        case 3:
        case 4:
        case 5:
          $fosfato += $value->volumen;
          break;

        case 10:
          $calcio = $value->volumen;
          break;
      }
      if ($value->cantidad > 0) {
        ?>
        <tr> 
          <td style="  text-align: left; background: #d2d6dc" >
            <?= $value->nombre ?>
          </td>      
          <td style="  text-align: left;">
            <?= $value->cantidad ?>
          </td>
          <td style="  text-align: left;">
            <?= $value->volumen ?>
          </td>      
          <td style=" text-align: left;">
            <?= $value->purga ?>
          </td>      
        </tr>
        <?php
      }
    }
    ?>   
  </tbody>
</table>
<table style=" width: 100%;">
  <tr>
    <td style=" width:75%;">
      Agua
    </td>
    <td style=" width:25%;">
      <?php
      echo abs($etiqueta->volumen - $total);
//       print_r($etiqueta);
      ?>
    </td>
  </tr>
</table>
<table style=" width: 100%;">
  <tr>
    <td style=" width:30%;">
      VOLUMEN TOTAL
    </td>
    <td style=" width:20%;">
      <?php
      echo $etiqueta->volumen;
      ?>
    </td>
    <td style=" width:25%;">
      VOLUMEN PURGA
    </td>
    <td style=" width:25%;">
      <?= $etiqueta->volumen + $etiqueta->purga ?>
    </td>
  </tr>
  <tr>
    <td >
      OSMORALIDAD (mOsm / L)
    </td>
    <td >
      <?= number_format($osmo / ($etiqueta->volumen + $etiqueta->purga), 1) ?>
    </td>
    <td >
      RELACIÓN CALCIO/FOSFORO (<2)
    </td>
    <td >
      <?= number_format((($calcio * 9.3 / $etiqueta->volumen * 1000 / 40) * ($fosfato * 31 / $etiqueta->volumen * 1000 / 31)) / 100, 1) ?>
    </td>
  </tr>


</table>
<table style="width: 100%">
  <tr>
    <td style=" width:50%;">
      Preparado Por:
    </td>
    <td style=" width:50%;">
      Liberado Por:
    </td>
  </tr>
  <tr>
    <td >
      Q.F. DIEGO VÁSQUEZ
    </td>
    <td >
      Q.F. LIZZETH SÁNCHEZ
    </td>      
  </tr>
</table>


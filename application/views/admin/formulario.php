<?php
defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');
?>
<?php foreach ($css_files as $file): ?>
  <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>
  <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= $tituloPagina ?>
    </h1>
  </section>
  <style>
    .modal-header, h4, .close {
      background-color: #5cb85c;
      color:white !important;
      text-align: center;
      font-size: 30px;
    }
    .modal-footer {
      background-color: #f9f9f9;
    }
  </style>

  <?php
  $generos = [];
  $data = [
      ['idname' => 'idpaciente', 'nombre' => 'Paciente', 'select' => true, 'data' => $output['pacientes'], 'aplica' => false,'value'=>0],
      ['idname' => 'tiempo', 'nombre' => 'Tiempo Infusion', 'select' => false, 'data' => '', 'aplica' => false,'value'=>24],
      ['idname' => 'velocidad', 'nombre' => 'Velocidad Infusion', 'select' => false, 'data' => '', 'aplica' => false,'value'=>125],
      ['idname' => 'purga', 'nombre' => 'Purga', 'select' => false, 'data' => '', 'aplica' => false,'value'=>30],
      ['idname' => 'peso', 'nombre' => 'Peso Diario', 'select' => false, 'data' => '', 'aplica' => false,'value'=>80],
      ['idname' => 'aminoacidos', 'nombre' => 'Aminoacido', 'select' => true, 'data' => $output['aminoacido'], 'aplica' => true,'value'=>1],
      ['idname' => 'fosfato', 'nombre' => 'Fosfato Glicerofosfato ', 'select' => true, 'data' => $output['fosfato'], 'aplica' => true,'value'=>10],
      ['idname' => 'carbohidrato', 'nombre' => 'Carbohidrato', 'select' => true, 'data' => $output['carbohidrato'], 'aplica' => true,'value'=>4],
      ['idname' => 'sodio', 'nombre' => 'Sodio', 'select' => true, 'data' => $output['sodio'], 'aplica' => true,'value'=>1],
      ['idname' => 'potasio', 'nombre' => 'Potacio', 'select' => true, 'data' => $output['potacio'], 'aplica' => true,'value'=>1],
      ['idname' => 'calcio', 'nombre' => 'Calcio', 'select' => true, 'data' => $output['calcio'], 'aplica' => true,'value'=>500],
      ['idname' => 'magnesio', 'nombre' => 'Magnesio', 'select' => true, 'data' => $output['magnesio'], 'aplica' => true,'value'=>500],
      ['idname' => 'elementos', 'nombre' => 'Elementos Traza', 'select' => true, 'data' => $output['elementos'], 'aplica' => true,'value'=>5],
      ['idname' => 'multivitaminas_1', 'nombre' => 'Multivitaminas Cernevit', 'select' => true, 'data' => $output['multivitaminas_1'], 'aplica' => true,'value'=>0],
      ['idname' => 'multivitaminas_2', 'nombre' => 'Multivitaminas Vitalipid', 'select' => true, 'data' => $output['multivitaminas_2'], 'aplica' => true,'value'=>0.99],
      ['idname' => 'multivitaminas_3', 'nombre' => 'Multivitaminas Soluvit', 'select' => true, 'data' => $output['multivitaminas_3'], 'aplica' => true,'value'=>0.4],
      ['idname' => 'glutamina', 'nombre' => 'Glutamina', 'select' => true, 'data' => $output['glutamina'], 'aplica' => true,'value'=>0.5],
      ['idname' => 'vitaminac', 'nombre' => 'Vitamina C', 'select' => true, 'data' => $output['vitaminac'], 'aplica' => true,'value'=>500],
      ['idname' => 'complejob', 'nombre' => 'Complejo B', 'select' => true, 'data' => $output['complejob'], 'aplica' => true,'value'=>5],
      ['idname' => 'tiamina', 'nombre' => 'Tiamina', 'select' => true, 'data' => $output['tiamina'], 'aplica' => true,'value'=>500],
      ['idname' => 'acido', 'nombre' => 'Acido', 'select' => true, 'data' => $output['acido'], 'aplica' => true,'value'=>1],
      ['idname' => 'vitaminak', 'nombre' => 'Vitamina K', 'select' => true, 'data' => $output['vitaminak'], 'aplica' => true,'value'=>10],
      ['idname' => 'lipidos', 'nombre' => 'Lipidos', 'select' => true, 'data' => $output['lipidos'], 'aplica' => true,'value'=>1.2]
  ];
//  $data = [
//      ['idname' => 'idpaciente', 'nombre' => 'Paciente', 'select' => true, 'data' => $output['pacientes'], 'aplica' => false,'value'=>0],
//      ['idname' => 'tiempo', 'nombre' => 'Tiempo Infusion', 'select' => false, 'data' => '', 'aplica' => false,'value'=>24],
//      ['idname' => 'velocidad', 'nombre' => 'Velocidad Infusion', 'select' => false, 'data' => '', 'aplica' => false,'value'=>125],
//      ['idname' => 'purga', 'nombre' => 'Purga', 'select' => false, 'data' => '', 'aplica' => false,'value'=>30],
//      ['idname' => 'peso', 'nombre' => 'Peso Diario', 'select' => false, 'data' => '', 'aplica' => false,'value'=>80],
//      ['idname' => 'aminoacidos', 'nombre' => 'Aminoacido', 'select' => true, 'data' => $output['aminoacido'], 'aplica' => true],
//      ['idname' => 'fosfato', 'nombre' => 'Fosfato Glicerofosfato ', 'select' => true, 'data' => $output['fosfato'], 'aplica' => true],
//      ['idname' => 'carbohidrato', 'nombre' => 'Carbohidrato', 'select' => true, 'data' => $output['carbohidrato'], 'aplica' => true],
//      ['idname' => 'sodio', 'nombre' => 'Sodio', 'select' => true, 'data' => $output['sodio'], 'aplica' => true],
//      ['idname' => 'potasio', 'nombre' => 'Potacio', 'select' => true, 'data' => $output['potacio'], 'aplica' => true],
//      ['idname' => 'calcio', 'nombre' => 'Calcio', 'select' => true, 'data' => $output['calcio'], 'aplica' => true],
//      ['idname' => 'magnesio', 'nombre' => 'Magnesio', 'select' => true, 'data' => $output['magnesio'], 'aplica' => true],
//      ['idname' => 'elementos', 'nombre' => 'Elementos Traza', 'select' => true, 'data' => $output['elementos'], 'aplica' => true],
//      ['idname' => 'multivitaminas_1', 'nombre' => 'Multivitaminas Cernevit', 'select' => true, 'data' => $output['multivitaminas_1'], 'aplica' => true],
//      ['idname' => 'multivitaminas_2', 'nombre' => 'Multivitaminas Vitalipid', 'select' => true, 'data' => $output['multivitaminas_2'], 'aplica' => true],
//      ['idname' => 'multivitaminas_3', 'nombre' => 'Multivitaminas Soluvit', 'select' => true, 'data' => $output['multivitaminas_3'], 'aplica' => true],
//      ['idname' => 'glutamina', 'nombre' => 'Glutamina', 'select' => true, 'data' => $output['glutamina'], 'aplica' => true],
//      ['idname' => 'vitaminac', 'nombre' => 'Vitamina C', 'select' => true, 'data' => $output['vitaminac'], 'aplica' => true],
//      ['idname' => 'complejob', 'nombre' => 'Complejo B', 'select' => true, 'data' => $output['complejob'], 'aplica' => true],
//      ['idname' => 'tiamina', 'nombre' => 'Tiamina', 'select' => true, 'data' => $output['tiamina'], 'aplica' => true],
//      ['idname' => 'acido', 'nombre' => 'Acido', 'select' => true, 'data' => $output['acido'], 'aplica' => true],
//      ['idname' => 'vitaminak', 'nombre' => 'Vitamina K', 'select' => true, 'data' => $output['vitaminak'], 'aplica' => true],
//      ['idname' => 'lipidos', 'nombre' => 'Lipidos', 'select' => true, 'data' => $output['lipidos'], 'aplica' => true]
//  ];
  ?>
  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box">
      <div class="box-body">
        <div class="modal fade" id="moda_etiqueta" role="dialog">
          <div class="modal-dialog" style=" width: 60%">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class="glyphicon glyphicon-lock"></span>Vista Etiqueta</h4>
              </div>
              <div class="modal-body" id="body_etiqueta" style="padding:40px 50px;">
                
              </div>

            </div>

          </div>
        </div> 
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog" style=" width: 700px">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class="glyphicon glyphicon-lock"></span> Nueva Formulacion</h4>
              </div>
              <div class="modal-body" style="padding:40px 50px;">
                <form class="form-horizontal" id="formulario_ajax"  method="POST" action="<?= base_url('admin/Formulacion/guardarFormulacion') ?>" sy>
                  <?php foreach ($data as $value) { ?>
                    <div class="form-group">
                      <label class="control-label col-sm-4" for="<?= $value['idname'] ?>"><?= $value['nombre'] ?>:</label>
                      <div class="col-sm-4">
                        <?php if ($value['select']) { ?>

                          <select class="form-control" id="<?= $value['idname'] ?>" name="<?= $value['idname'] ?>" >
                            <option value="">..::Seleccione::..</option>
                            <?php foreach ($value['data'] as $pacien) { ?>
                              <option value="<?= $pacien['value'] ?>"><?= $pacien['option'] ?></option>
                            <?php } ?>
                          </select>
                        <?php } else { ?>
                        <input type="text"  class="form-control" id="<?= $value['idname'] ?>" name="<?= $value['idname'] ?>" placeholder="<?= $value['nombre'] ?>" value="<?= $value['value'] ?>">
                        <?php } ?>
                      </div>
                      <?php if ($value['aplica']) { ?>
                        <label class="control-label col-sm-2" for="<?= $value['idname'] ?>_cant">Requerimiento:</label>
                        <div class="col-sm-2">
                          <input type="number" class="form-control" id="<?= $value['idname'] ?>_cant" name="<?= $value['idname'] ?>_cant" placeholder="Dia" value="<?= $value['value'] ?>">
                        </div>
                      <?php } ?>
                    </div>
                  <?php } ?>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn-default pull-left" ><span class="glyphicon glyphicon-remove"></span> Guardar</button>  
                  </div>
                </form>
              </div>

            </div>

          </div>
        </div> 

      </div>


      <div style="width:83%; margin-left: 2%">
        <table id="example" class="display nowrap" style="width:100%">
          <thead>
            <tr>
              <th>Paciente</th>
              <th>Tiempo de Infucisón</th>
              <th>Velocidad de Infusión</th>
              <th>Volomen Total</th>
              <th>Purga</th>
              <th>Peso Diario</th>
              <th>Requerimiento Total</th>
            </tr>
          </thead>

          <tfoot>
            <tr>
              <th>Paciente</th>
              <th>Tiempo de Infucisón</th>
              <th>Velocidad de Infusión</th>
              <th>Volomen Total</th>
              <th>Purga</th>
              <th>Peso Diario</th>
              <th>Requerimiento Total</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->




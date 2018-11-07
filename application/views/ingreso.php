<!-- Alertas -------------------------------------------------------------------------------------- -->

<?php
if ($this->session->flashdata('msgTipo')) {
  switch ($this->session->flashdata('msgTipo')) {
    case 1: // Ok
      ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> <?= $this->lang->line('extras_at_proceso_exitoso') ?>:</h4>
        <?= $this->session->flashdata('msgTxt') ?>
      </div>
      <?php
      break;
    case 2: // Error
      ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> <?= $this->lang->line('extras_at_error') ?>:</h4>
        <?= $this->session->flashdata('msgTxt') ?>
      </div>
      <?php
      break;

    case 3: // Informativo
      ?>
      <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-info"></i> <?= $this->lang->line('extras_at_informacion') ?>:</h4>
        <?= $this->session->flashdata('msgTxt') ?>
      </div>
      <?php
      break;

    case 4: // Atención
      ?>
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-warning"></i> <?= $this->lang->line('extras_at_atencion') ?>:</h4>
        <?= $this->session->flashdata('msgTxt') ?>
      </div>
      <?php
      break;
  }
}
?>

<?php
$this->session->sess_destroy();
?>
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Numixx</b>Central de Bogota</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Ingrese sus datos para iniciar la sesión</p>

    <form action="<?= base_url() ?>ingreso/validacion" method="post" id="formIngreso">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="correo" placeholder="Correo">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="clave" placeholder="Clave">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <hr>
    <div class="text-center">
      NIT: 0000
      <br>
      <b>norte</b>
      <br>
      <b>E-MAIL:</b> info@numixx.com
      <br>
      <b>SITIO WEB:</b> www.numixx.com
    </div>




  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
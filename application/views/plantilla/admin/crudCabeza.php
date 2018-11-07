<?php
defined('BASEPATH') OR exit('No esta permitido el acceso directo a este script.');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Numixx SAS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/adminlte/bootstrap/css/bootstrap.min.css">
    <!--datatable-->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.jqueryui.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"> 
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/adminlte/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/adminlte/dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?= $estilo ?>

    <script>
      var base_url = '<?= base_url() ?>';
    </script>
  </head>
  <body class="hold-transition skin-green-light sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="<?= base_url() ?>assets/adminlte/index2.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>Numixx</b>SAS</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Numixx</b>SAS</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?= base_url() ?>assets/adminlte/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?= $this->session->userdata('u_nombreCompleto') ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?= base_url() ?>assets/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                    <p>
                      <?= $this->session->userdata('u_nombreCompleto') ?>
                    </p>
                  </li>
                  <li class="user-footer">
                    <div class="pull-left">

                    </div>
                    <div class="pull-right">
                      <a href="<?= base_url() ?>/ingreso/salir" class="btn btn-default btn-flat">Salir</a>
                    </div>
                  </li>
                </ul>
              </li>

            </ul>
          </div>
        </nav>
      </header>

      <!-- =============================================== -->

      <!-- Left side column. contains the sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?= base_url() ?>assets/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?= $this->session->userdata('u_nombreCompleto') ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> En lÃ­nea</a>
            </div>
          </div>

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MENU</li>

            <?php if (can('modulo.pacientes')): ?>
              <li>
                <a href="<?= base_url() ?>admin/usuarios">
                  <i class="fa fa-users"></i> <span>Nuevo paciente</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>

                <ul class="treeview-menu">

                  <?php if (can('admin.pacientes')): ?>
                    <li><a href="<?= base_url() ?>admin/Pacientes"><i class="fa fa-circle-o"></i>Nuevo Pacientes</a></li>
                  <?php endif ?>
                </ul>
              </li>
            <?php endif ?>
            <?php if (can('modulo.forumulacion')): ?>
              <li>
                <a href="<?= base_url() ?>admin/usuarios">
                  <i class="fa fa-users"></i> <span> FormulciÃ³n NPT</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                <!--  <li><a href="<?= base_url() ?>admin/Pacientesh"><i class="fa fa-circle-o"></i>Nuevo Pacientes</a></li> -->

                  <?php if (can('admin.formulaciona')): ?>
                    <li><a href='<?= base_url() ?>admin/formulacion'><i class='fa fa-circle-o'></i>Registro Med Adultos</a></li>
                  <?php endif ?>
                  <?php if (can('admin.formulacionn')): ?>
                    <li><a href='<?= base_url() ?>admin/Formulacionn'><i class='fa fa-circle-o'></i>Registro Med Neonatos</a></li>
                  <?php endif ?>
                  <?php if (can('admin.formulacion')): ?>
                    <li><a href='<?= base_url() ?>admin/Formulacion'><i class='fa fa-circle-o'></i>Registro Med Pediatrico</a></li>
                  <?php endif ?>
                </ul>
              </li>
            <?php endif ?>
            <?php if (can('modulo.datos')): ?>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-database"></i> <span>Config Datos</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <?php if (can('admin.clinicas')): ?>
                    <li><a href='<?= base_url() ?>admin/Clinicas'><i class='fa fa-circle-o'></i>Clinicas</a></li> 
                  <?php endif ?>
                  <?php if (can('admin.npts')): ?>
                    <li><a href='<?= base_url() ?>admin/npts'><i class='fa fa-circle-o'></i>NPT</a></li>  
                  <?php endif ?>
                  <?php if (can('admin.generos')): ?>
                    <li><a href='<?= base_url() ?>admin/Generos'><i class='fa fa-circle-o'></i>Generos</a></li>
                  <?php endif ?>
                  <?php if (can('admin.eps')): ?>
                    <li><a href='<?= base_url() ?>admin/Eps'><i class='fa fa-circle-o'></i>EPS</a></li>
                  <?php endif ?>
                  <?php if (can('admin.servicios')): ?>
                    <li><a href='<?= base_url() ?>admin/servicios'><i class='fa fa-circle-o'></i>Servicios</a></li>
                  <?php endif ?>
                  <?php if (can('admin.departamentos')): ?>
                    <li><a href='<?= base_url() ?>admin/Departamentos'><i class='fa fa-circle-o'></i>Departamentos</a></li>
                  <?php endif ?>
                  <?php if (can('admin.municipios')): ?>
                    <li><a href='<?= base_url() ?>admin/Municipios'><i class='fa fa-circle-o'></i>Municipios</a></li>
                  <?php endif ?>

                </ul>
              </li>
            <?php endif ?>
            <?php if (can('modulo.medicamentos')): ?>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-eyedropper"></i> <span>Config Medicamentos</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>

                <ul class="treeview-menu">

                  <?php if (can('admin.medicamentos')): ?>
                    <li><a href='<?= base_url() ?>admin/Medicamentos'><i class='fa fa-circle-o'></i>Agregar Medicamentos</a></li>
                  <?php endif ?>        
                </ul>
              </li>
            <?php endif ?> 
            <?php if (can('modulo.rinventarios')): ?>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-server"></i> <span> Registrar Invetarios</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">

                  <?php if (can('admin.inventarios_entradas')): ?>
                    <li><a href="<?= base_url() ?>admin/inventario_entradas"><i class="fa fa-file-o"></i>Inventarios Entradas</a></li>
                  <?php endif ?>
                  <?php if (can('admin.inventarios_salidas')): ?>
                    <li><a href="<?= base_url() ?>admin/inventario_salidas"><i class="fa fa-file-text"></i>Inventarios Salida</a></li>
                  <?php endif ?>
                </ul>
              </li>
            <?php endif ?>

            <!--   <li class="treeview">
                 <a href="#">
                   <i class="fa fa-print"></i> <span>Reportes</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
                 </a>
                 <ul class="treeview-menu">
                   <li><a href="<?= base_url() ?>admin/ "><i class="fa fa-circle-o"></i>Etiqueta 1</a></li>
                   <li><a href="<?= base_url() ?>admin/ "><i class="fa fa-circle-o"></i>Etiqueta 2</a></li>
                   <li><a href="<?= base_url() ?>admin/ "><i class="fa fa-circle-o"></i>Etiqueta 3</a></li>
                 </ul>
               </li> -->

            <?php if (can('modulo.usuarios')): ?>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-user"></i> <span>Seguridad</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">

                  <?php if (can('admin.usuarios')): ?>
                    <li><a href="<?= base_url() ?>admin/usuarios"><i class="fa fa-circle-o"></i>Usuarios</a></li>
                    <?php endif ?>
                </ul>
              </li>
            <?php endif ?>
          </ul>
        </section>

        <!-- /.sidebar -->
      </aside>

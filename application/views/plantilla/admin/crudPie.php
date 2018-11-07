</div>
<!-- ./wrapper -->
<?php
if(isset($jquery)){
  ?>
<!--<script src="http://localhost/npt/assets/grocery_crud/js/jquery-1.11.1.min.js"></script>-->
<?php
}
if($jquery){
  ?>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<?php
} else {
   ?>
<script src=" https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
}
?>




 
<!-- Bootstrap 3.3.6 -->
<script src="<?= base_url() ?>assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<!-- SlimScroll -->
<script src="<?= base_url() ?>assets/adminlte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= base_url() ?>assets/adminlte/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>assets/adminlte/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes 
<script src="<?= base_url() ?>assets/adminlte/dist/js/demo.js"></script> -->




<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.jqueryui.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.jqueryui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
<script src="<?= base_url() ?>assets/validation/dist/jquery.validate.js"></script>
<?= $script ?>
<script>
  


</script>
</body>
</html>
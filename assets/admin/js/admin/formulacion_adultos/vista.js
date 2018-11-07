$(function () {
  var tabla = function () {
    var formulacion = {};
    var table = $('#example').DataTable({
      dom: 'Bfrtip',
      lengthChange: false,
      select: true,
      buttons: [
        {
          text: '<span class="btn-success">Nueva</span>',
          action: function (e, dt, node, config) {
            $("#myModal").modal();
          },
          attr: {
            class: 'btn-success'
          }
        },

        {
          text: 'Editar',
          action: function (e, dt, node, config) {
            formulacion = dt.row({selected: true}).data();

          },

          attr: {
            id: 'btn_editar',
            class: 'btn-warning select deselect ui-state-disabled'
          }
        },
        {
          text: 'Eliminar',
          action: function (e, dt, node, config) {
            alert('Rows: ' + dt.rows({selected: true}).count());
          },

          attr: {
            id: 'btn_eliminar',
            class: 'btn-danger select deselect ui-state-disabled'
          }

        },
        {
          text: '<i class="fa fa-file-pdf-o"></i>Imprimir Etiqueta',
          attr: {
            id: 'btn_imprime_etiqueta',
            class: 'btn-danger select deselect ui-state-disabled'
          },
          action: function (e, dt, node, config) {
            formulacion = dt.row({selected: true}).data();
            $("#moda_etiqueta").modal();
            $.ajax({
              url: base_url + 'admin/Formulacion/imprime_etiqueta',
              type: "POST",
              data: {formul: formulacion.idformulacion},
              dataType: 'html',
              success: function (json) {
                $("#body_etiqueta").html(json);
              },
              error: function (xhr, status) {
                alert('Disculpe, existió un problema');
              },

            });

          },

        },
        {
          extend: 'pdfHtml5',
          titleAttr: 'PDF'
          , text: '<span class="btn-danger"><i class="fa fa-file-pdf-o"></i></span>',
          attr: {
            class: 'btn-danger'
          }

        },
        {
          extend: 'colvis'
          , text: '<span class="btn-info">Columnas Visibles</span>',
          attr: {
            class: 'btn-info'
          }
        },
        {
          extend: 'pageLength'
          , text: '<span class="btn-info">Filas a ver</span>',
          attr: {
            class: 'btn-info'
          }
        }
      ],
      language: {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json",
        buttons: {
          pageLength: 'Filas a ver',

        }
      },
      "processing": true,
      "serverSide": true,
      lengthMenu: [
        [10, 25, 50, -1],
        ['10 Filas', '25 Filas', '50 Filas', 'Ver todas']
      ],
      ajax: {
        url: base_url + 'admin/Formulacion/filtroajax',
        "type": "POST"
      },
      "columns": [
        {"data": "nombre_apellido"},
        {"data": "tiempo"},
        {"data": "velocidad"},
        {"data": "volumen"},
        {"data": "purga"},
        {"data": "peso"},
        {"data": "total"}
      ]
    });
    table.buttons()
            .container()
            .insertBefore('#example_filter');
    table.on('select deselect', function () {
      var selectedRows = table.rows({selected: true}).count();
      if (!selectedRows) {
        formulacion = {};
      }
      table.button(1).enable(selectedRows === 1);
      table.button(2).enable(selectedRows > 0);
      table.button(3).enable(selectedRows > 0);
    });
    table.on('order', function () {
      $("#btn_editar").prop('class', 'btn-warning select deselect ui-state-disabled');
      $("#btn_eliminar").prop('class', 'btn-danger select deselect ui-state-disabled');
      $("#btn_imprime_etiqueta").prop('class', 'btn-danger select deselect ui-state-disabled');
    });
  }
  tabla();
  var rule = {
    required: true,
    number: true
  };
  var message = {
    required: "Ingrese el tiempo de infusion",
    number: "El valor debe ser numerico"
  };
  $("#formulario_ajax").validate({
    event: "blur",
    rules: {
      'idpaciente': 'required',
      'tiempo': rule,
      'velocidad': rule,
      'purga': rule,
      'peso': rule,
      'aminoacidos': 'required',
      'aminoacidos_cant': rule,
      'fosfato': 'required',
      'fosfato_cant': rule,
      'carbohidrato': 'required',
      'carbohidrato_cant': rule,
      'sodio': 'required',
      'sodio_cant': rule,
      'potasio': 'required',
      'potasio_cant': rule,
      'calcio': 'required',
      'calcio_cant': rule,
      'magnesio': 'required',
      'magnesio_cant': rule,
      'elementos': 'required',
      'elementos_cant': rule,
      'multivitaminas_1': 'required',
      'multivitaminas_1_cant': rule,
      'multivitaminas_2': 'required',
      'multivitaminas_2_cant': rule,
      'multivitaminas_3': 'required',
      'multivitaminas_3_cant': rule,
      'glutamina': 'required',
      'glutamina_cant': rule,
      'vitaminac': 'required',
      'vitaminac_cant': rule,
      'complejob': 'required',
      'complejob_cant': rule,
      'tiamina': 'required',
      'tiamina_cant': rule,
      'vitaminak': 'required',
      'vitaminak_cant': rule,
      'lipidos': 'required',
      'lipidos_cant': rule,
    },
    messages: {

      'idpaciente': {
        required: "Seleccione un paciente",
      },

      'tiempo': {
        required: "Ingrese el tiempo de infusion",
        number: "El valor debe ser numerico"
      },
      'velocidad': {
        required: "Ingrese la velocidad de infusion",
        number: "El valor debe ser numerico"
      },

      'purga': {
        required: "Ingrese la purga",
        number: "El valor debe ser numerico"
      },

      'peso': {
        required: "Ingrese el peso",
        number: "El valor debe ser numerico"
      },
      'aminoacidos': {
        required: "Seleccione un aminoacido",
      },
      'aminoacidos_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },

      'fosfato': {
        required: "Seleccione un fofato",
      },
      'fosfato_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },

      'carbohidrato': {
        required: "Seleccione un carbohidrato",
        number: "El valor debe ser numerico"
      },
      'carbohidrato_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },

      'sodio': {
        required: "Seleccione un sodio",
        number: "El valor debe ser numerico"
      },
      'sodio_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'potasio': {
        required: "Seleccione un potasio",
        number: "El valor debe ser numerico"
      },
      'potasio_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'calcio': {
        required: "Seleccione un calcio",
        number: "El valor debe ser numerico"
      },
      'calcio_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'magnesio': {
        required: "Seleccione un magnesio",
        number: "El valor debe ser numerico"
      },
      'magnesio_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'elementos': {
        required: "Seleccione un elemento traza",
        number: "El valor debe ser numerico"
      },
      'elementos_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },

      'multivitaminas_1': {
        required: "Seleccione una multivitamina",
        number: "El valor debe ser numerico"
      },
      'multivitaminas_1_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'multivitaminas_2': {
        required: "Seleccione una multivitamina",
        number: "El valor debe ser numerico"
      },
      'multivitaminas_2_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'multivitaminas_3': {
        required: "Seleccione una multivitamina",
        number: "El valor debe ser numerico"
      },
      'multivitaminas_3_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'glutamina': {
        required: "Seleccione una glutamina",
        number: "El valor debe ser numerico"
      },
      'glutamina_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'vitaminac': {
        required: "Seleccione una vitamina C",
        number: "El valor debe ser numerico"
      },
      'vitaminac_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'complejob': {
        required: "Seleccione un complejo B",
        number: "El valor debe ser numerico"
      },
      'complejob_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'tiamina': {
        required: "Seleccione una tiamina",
        number: "El valor debe ser numerico"
      },
      'tiamina_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'vitaminak': {
        required: "Seleccione una vitamiana K",
        number: "El valor debe ser numerico"
      },
      'vitaminak_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
      'lipidos': {
        required: "Seleccione un lipido",
        number: "El valor debe ser numerico"
      },
      'lipidos_cant': {
        required: "Ingrese requerimiento diario",
        number: "El valor debe ser numerico"
      },
    },

    submitHandler: function (form) {
      var data = $("#formulario_ajax").serializeArray();
      var total = 0;
      for (var i = 6; i < data.length; i += 2) {
        total += eval(data[i].value);
      }
      formulacion = {
        'idpaciente': data[0].value.split('_')[0],
        'tiempo': data[1].value,
        'velocidad': data[2].value,
        'volumen': data[1].value * data[2].value,
        'purga': data[3].value,
        'peso': data[4].value,
        'total': total,
        'medicamentos': [
          {'medicamento': data[5].value, 'cantidad': data[6].value},
          {'medicamento': data[7].value, 'cantidad': data[8].value},
          {'medicamento': data[9].value, 'cantidad': data[10].value},
          {'medicamento': data[11].value, 'cantidad': data[12].value},
          {'medicamento': data[13].value, 'cantidad': data[14].value},
          {'medicamento': data[15].value, 'cantidad': data[16].value},
          {'medicamento': data[17].value, 'cantidad': data[18].value},
          {'medicamento': data[19].value, 'cantidad': data[20].value},
          {'medicamento': data[21].value, 'cantidad': data[22].value},
          {'medicamento': data[23].value, 'cantidad': data[24].value},
          {'medicamento': data[25].value, 'cantidad': data[26].value},
          {'medicamento': data[27].value, 'cantidad': data[28].value},
          {'medicamento': data[29].value, 'cantidad': data[30].value},
          {'medicamento': data[31].value, 'cantidad': data[32].value},
          {'medicamento': data[33].value, 'cantidad': data[34].value},
          {'medicamento': data[35].value, 'cantidad': data[36].value},
          {'medicamento': data[37].value, 'cantidad': data[38].value},
          {'medicamento': data[39].value, 'cantidad': data[40].value}
        ]
      }


      $.ajax({

        url: $("#formulario_ajax").attr("action"),
        type: $("#formulario_ajax").attr("method"),
        data: {data: formulacion},
        dataType: 'json',

        success: function (json) {
          //$("#formulario_ajax")[0].reset();
        },

        error: function (xhr, status) {
          alert('Disculpe, existió un problema');
        },

      });
    }
  });

});


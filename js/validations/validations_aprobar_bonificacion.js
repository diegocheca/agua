$(document).ready(function() {
      //validar los campos que tiene agregar cliente


        $('#form_bonificacion').submit(function(e){
          //e.preventDefault();
          var ok = '';
          var montoDeuda = $('#inputMonto').val();
          var montoBonificado = $('#inputMontoBonificado').val();
          if(!((montoBonificado < montoDeuda) && !(isNaN(montoBonificado)) && (montoBonificado >= 0) && (montoBonificado != "")))
          {
            ok += " * Error en el campo Monto a bonificar \n ";
          }
          var porcentajeBonificado = $('#inputPorcentajeBonificado').val();
          if(!(!(isNaN(porcentajeBonificado)) && (porcentajeBonificado >= 0) && (porcentajeBonificado != "") && (porcentajeBonificado <= 48)))
          {
            ok += " * Error en el campo Porcentaje de bonificacion \n ";
          }

          if(ok == null)
          {
            $( "#form_aprobar_bonificacion" ).submit();
            return;
          }
          else
          {
            //alert(ok);
            swal("Formulario con errores!", "Debe corregir:"+ok, "error");


            event.preventDefault();
            return;
          }
        });


  });
    
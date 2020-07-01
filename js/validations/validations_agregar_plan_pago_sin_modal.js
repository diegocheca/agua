    $(document).ready(function() {
      //validar los campos que tiene agregar cliente


        $('#form_agregar_plan_pago_sin_modal').submit(function(e){
          //e.preventDefault();
          var ok = '';
          var conexionId = $('#inputConexionId_planPago').val();
          if (!((conexionId.length>=1) && !(isNaN(conexionId)) && (conexionId != "")))
          {
            ok += "\n * Error en el campo Conexion Id \n";
          }
          // var nombre = $('#inputCliente_plan_pago').val();
          // if (!((nombre.length <50) && (nombre.length >5) && (nombre != "")))
          // {
          //   ok += " * Error en el campo Nombre Cliente \n";
          // }
          var fechaIni = $('#inputFechaInicio_agregarPlanPago').val();
          
          if(validarFecha(fechaIni))
          {
            ok += " * Error en el campo Año \n ";
          }
          var cuotas = $('#inputCantidadCuotas_agregarPlanPago').val();
          if(!
            (
              cuotas > 0
              &&
              cuotas <= 12
               && 
               cuotas != ""
              )
            )
          {
            ok += " * Error en el campo Cuotas \n ";
          }
          var interes = $('#inputInteres_agregarPlanPago').val();
          if(!(!(isNaN(interes)) && (interes >= 0) && (interes <= 50) && (interes != "")))
          {
            ok += " * Error en el campo interes \n ";
          }
          if(ok == '')
          {
            $( "#form_agregar_plan_pago_sin_modal" ).submit();
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

function validarFecha(date){
  var x=new Date();
  var fecha = date.split("/");
  x.setFullYear(fecha[2],fecha[1]-1,fecha[0]);
  var today = new Date();

  if (x >= today)
    return false;
  else
    return true;
}

$("#inputTipoPago").on("change",function(){
    var valor = $("#inputTipoPago").val();
    if(valor === "Contado" )
    {
        $("#div_cantidad_cuotas").hide('1800');
    }
    else
    {
       $("#div_cantidad_cuotas").show('1800');

    }


    });
  });
    
    
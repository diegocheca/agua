    $(document).ready(function() {
      //validar los campos que tiene agregar cliente


        $('#form_agregar_medicion').submit(function(e){
	//e.preventDefault();
	var ok = '';
	var conexionId = $('#inputConexionId').val();
	if (!((conexionId.length>=1) && !(isNaN(conexionId)) && (conexionId != "")))
		ok += "\n * Error en el campo Conexion Id \n";
	var mes = $('#inputMes').val();
	if(!(!(isNaN(mes)) && (mes < 12) && (mes > 1) && (mes != "")))
		ok += " * Error en el campo mes \n ";
	var anio = $('#inputAnio').val();
	if(!((anio.length == 4) && !(isNaN(anio))))
		ok += " * Error en el campo Año \n ";
	var medAnterior = $('#inputMAnterior').val();
	if(!(!(isNaN(medAnterior)) && (medAnterior.length < 8) && (medAnterior != "")))
		ok += " * Error en el campo Medicion Anterior \n ";
	var medActual = $('#inputMActual').val();
	if(!(!(isNaN(medActual)) && (medActual >= medAnterior) && (medActual.length < 8) && (medActual != "" )))
		ok += " * Error en el campo Medicion Actual \n ";
	if(ok == '')
	{
		$( "#form_agregar_medicion" ).submit();
		return;
	}
          else
	{
		swal("Formulario con errores!", "Debe corregir:"+ok, "error");
		event.preventDefault();
		return;
	}
        });
	$("#inputTipoPago").on("change",function(){
		var valor = $("#inputTipoPago").val();
		if(valor === "Contado" )
			$("#div_cantidad_cuotas").hide('1800');
		else
			$("#div_cantidad_cuotas").show('1800');
	});
});
    
    
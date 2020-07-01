$(document).ready(function() {
	$('#myform').submit(function(e){
		var ok = '';
		var nombre = $('#razon_social').val();
		if (!((nombre.length <50) && (nombre.length >5) && (nombre != "")))
			ok += "\n Error en el campo Nombre \n";
		var documento = $('#nro_documento').val();
		if( isNaN(documento) || (documento.length < 6) || (documento.length > 8))
			ok += "\n Error en el campo documento \n ";
		// var cuit = $('#inputCuit').val();
		// if(!((cuit.length > 12) && (cuit.length < 14) &&  (cuit != "")))
		// 	ok += "Error en el campo Cuit \n ";
		var telefono = $('#inputTelefono').val();
		if(!((telefono.length > 5) && (telefono.length < 20) && (telefono != "")))
			ok += "\n Error en el campo Telefono \n ";
		// var celular = $('#inputCelular').val();
		// if(!((celular.length > 7) && (celular.length < 18) && (celular != "")))
		// 	ok += "Error en el campo Celular \n ";
		var domPost = $('#inputDomPost').val();
		if (!((domPost.length <60) && (domPost.length >5) && (domPost != "")))
			ok += "\n Error en el campo Domicilio Postal \n ";
		var domSum = $('#inputDomSum').val();
		if (!((domSum.length <60) && (domSum.length >5) && (domSum != "")))
			ok += "\n Error en el campo Domicilio Suministro \n ";
		var sector = $('#inputsector').val();
		if((sector == "")) 
			ok += "\n Error en el campo Sector \n ";
		var precio = $('#inputPrecioMedidor').val();
		if(precio == "")
			ok += "\n Error en el campo Precio \n ";
		if(ok == '') //formulario son errores
	  	{
			// $.ajax({
			// 	data:   $( "#myform" ).serialize(),
			// 	url:   'http://localhost/codeigniter/clientes/agregar_nuevo',
			// 	type:  'post',
			// 	success:  
			// 	function (response) 
			// 	{
			// 		console.log(response);
			// 		if(response != "false")
			// 		{
			// 			swal("Cliente Borrado!", "Se ha eliminado el cliente.", "success");
			// 		}
			// 		else
			// 		{
			// 			swal("Cliente Borrado!", "Se ha eliminado el cliente.", "error"); 
			// 			//alert("todo mal") ;
			// 		}
			// 	}
			// });
			console.log("algo q se no sq escribir");
		}
		else
			swal("Se encontraron Errores!", "Se encontraron los siguientes errores."+ok, "info"); 
	});

	function redireccionarBoletadeIngreso (linkdddd) {
		var a = document.createElement("a");
		a.target = "_blank";
		a.href = 'http://localhost/codeigniter/imprimir/crear_recibo_de_pago'+"/"+linkdddd;
		a.click();
		window.location = 'http://localhost/codeigniter';
		//window.location = ;
	}
		$("#enviando_formulario").on("click",function(){
			var ok = '';
			var nombre = $('#razon_social').val();
			if (!((nombre.length <50) && (nombre.length >5) && (nombre != "")))
				ok += "\n * Error en el campo Nombre \n";
			var documento = $('#nro_documento').val();
			if( isNaN(documento) || (documento.length < 6) || (documento.length > 8))
				ok += "\n * Error en el campo documento \n ";
			// var cuit = $('#inputCuit').val();
			// if(!((cuit.length > 12) && (cuit.length < 14) &&  (cuit != "")))
			// 	ok += "Error en el campo Cuit \n ";
			var telefono = $('#inputTelefono').val();
			if(!((telefono.length > 5) && (telefono.length < 20) && (telefono != "")))
				ok += "\n * Error en el campo Telefono \n ";
			// var celular = $('#inputCelular').val();
			// if(!((celular.length > 7) && (celular.length < 18) && (celular != "")))
			// 	ok += "Error en el campo Celular \n ";
			var domPost = $('#inputDomPost').val();
			if (!((domPost.length <60) && (domPost.length >5) && (domPost != "")))
				ok += "\n * Error en el campo Domicilio Postal \n ";
			var domSum = $('#inputDomSum').val();
			if (!((domSum.length <60) && (domSum.length >5) && (domSum != "")))
				ok += "\n  * Error en el campo Domicilio Suministro \n ";
			var sector = $('#inputsector').val();
			if((sector == "")) 
				ok += "\n * Error en el campo Sector \n ";
			var precio = $('#inputPrecioMedidor').val();
			if(precio == "")
				ok += "\n * Error en el campo Precio \n ";
			if(ok == '') //formulario son errores
	  		{
				$.ajax({
					data:   $( "#myform" ).serialize(),
					url:   'http://localhost/codeigniter/clientes/agregar_nuevo',
					type:  'post',
					success:  
					function (response) 
					{
					  if(response != "false")
					  {
						swal("Cliente Creado!", "Se ha creado Correctamente el cliente.", "success");
						/*LLmando a otra super funcion */
						var valores_devueltos = response.split('-');
						/*
						0 id_movimiento
						1 $razon_social
						2 $todas_las_variables[15]->Configuracion_Valor
						3 $nro_documento
						4 $id_comexion
						5 $id_medidor
						6 $inputDomSum ;
						7 $id_cliente ;
						*/
						console.log(valores_devueltos);
						//alert("El primero :"+ valores_devueltos[0] +" - El segundo "+valores_devueltos[1]+ "- El tercero:"+valores_devueltos[2]);
						if($("#inputTipoPago").val() === "Contado" ) // pago contado
						{
							var a = document.createElement("a");
							a.target = "_blank";
							a.href = 'http://localhost/codeigniter/imprimir/crear_recibo_de_pago_medidor_nuevo'+"/"+valores_devueltos[0]+"/"+valores_devueltos[1]+"/"+valores_devueltos[2];
							a.click();
						}
						a.target = "_blank";
						a.href = 'http://localhost/codeigniter/imprimir/crear_contrato_conexion'+"/"+valores_devueltos[1]+"/"+valores_devueltos[3]+"/"+valores_devueltos[4]+"/"+valores_devueltos[5]+"/"+valores_devueltos[6]+"/"+valores_devueltos[7];
						a.click();
						//
						a.target = "_blank";
						a.href = 'http://localhost/codeigniter/orden_trabajo/guardar_desde_ajax'+"/"+valores_devueltos[7]+"/"+valores_devueltos[6]+"/"+valores_devueltos[4]+"/"+valores_devueltos[1];
						a.click();
						window.location = 'http://localhost/codeigniter';
						//"imprimir/crear_contrato_conexion")."/".$razon_social."/".$nro_documento."/".$id_comexion."/".$id_medidor."/".$inputDomSum,'refresh'); 
					}
					else
					{
						swal("Cliente No Creado!", "no se pudo crear el cliente correctamente.", "error"); 
					}
					}
				});
				console.log("algo q se no sq escribir");
			}
			else
				swal("Se encontraron Errores!", "Se encontraron los siguientes errores."+ok, "info"); 
	});

$("#inputTipoPago").on("change",function(){
	var valor = $("#inputTipoPago").val();
	if(valor === "Contado" )
		$("#div_cantidad_cuotas").hide('1800');
	else
	   $("#div_cantidad_cuotas").show('1800');
	});
});
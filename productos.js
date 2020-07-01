$(document).on("ready", function(){

	function sumarTotales(){
		// Suma los precios totales
		$("#valorSumado").val("0");
		var primerprecio=$("#inputPrecio").val();//obtiene el valor del primer campo de precio
		if (inputstotal > 0 && primerprecio!='') {
			$(".row #inputPrecio").each(function(index, value){
				monto=parseFloat($(this).val()) + parseFloat($("#valorSumado").val());
				$("#valorSumado").val(monto.toFixed(2));
			});
		}
		// ./ Suma los precio finales en Total
	}
	

	function crearIgv(){
		//se obtiene en valor de todos los campos sumados
		var valorSumado = $('#valorSumado').val();
		//definimos la variable subtotal y total
		var subtotal,igv,total;

		if($("#incluyeIGV").prop("checked")){
			subtotal = valorSumado/1.18;
			igv = parseFloat(valorSumado) - parseFloat(subtotal);
			$('#inputSubtotal').val(subtotal.toFixed(2));
			$('#inputIgv').val(igv.toFixed(2));
			$('#inputTotal').val(valorSumado);
		}else{
			igv = parseFloat(valorSumado) * 0.18;
			total = parseFloat(valorSumado) + parseFloat(igv);
			$('#inputSubtotal').val(valorSumado);
			$('#inputIgv').val(igv.toFixed(2));
			$('#inputTotal').val(total.toFixed(2));
		}
	}



 //graba ingresos modal
	$("#guadar_ingreso_nuevo_modal").on("click",function(){
		var tipo = $("#tipo_egreso").val();
		var monto = $("#monto_ingreso").val();
		var codigo = $("#codigo_ingreso").val();
		var descripcion = $("#descripcion_ingreso").val();
		var tipo_pago = $("#tipo_pago").val();
		var estado = $("#estado_evento_nuevo").val();
		var acuenta = $("#acuenta_actual").val();
		var aquien = $("#a_nombre").val();
		var aux = $("#datos_personales_para_acuenta").val();
		var saldo = $("#saldo_cuenta").val();
		if( (aux != -1) && (aux != null))
		{
			aux = aux.split("*");
			var coenxion_id_js = aux[0];
			var cli_id_js = aux[1];
		}
		else
		{
			var coenxion_id_js = -1;
			var cli_id_js = -1;
		}
		var fecha = $("#Fecha_de_Pago").val();
		//alert(fecha);
		//hacer ajax
		$.ajax({
			data: {"tipo" : tipo, "monto" : monto, "codigo" : codigo,
				"descripcion" : descripcion, "tipo_pago" : tipo_pago, "estado" : estado,
				"acuenta" : acuenta,"aquien" :aquien, "conexion_id" : coenxion_id_js, "cliente_id" : cli_id_js, "saldo": saldo, "fecha":fecha },
			url: 'movimientos/guardar_movimiento_desde_modal',
			type: 'post',
			success:
			function(response) 
			{
				console.log("seestoy en succes");
			//	alert(response);
				if(response != "false")
				{
					console.log("se grabo");
					$("#nuevo_ingreso_modal").modal("hide");
					swal("Movimiento Guardado!", "Se guardado correctaemnte el movimiento.", "success"); 
					var a = document.createElement("a");
					a.target = "_blank";
					a.href = 'http://localhost/codeigniter/imprimir/crear_recibo_de_pago'+"/"+response;
					a.click();
					//setTimeout("redireccionarIndex()", 800);

				}
				else
				{
					console.log("no se grabo");
				}
			},
		error: function (jqXHR, textStatus, errorThrown) {
		   console.log(jqXHR, textStatus, errorThrown);
		}

		});
	});

	//  Autocompletar campos

	//Campo de Cliente
	$("#inputCliente").devbridgeAutocomplete({
		showNoSuggestionNotice: true,
		serviceUrl: '/codeigniter/clientes/leer_clientes',
		noSuggestionNotice: 'No se encontraron datos',
		onSelect: function (suggestion) {
			$('#inputCliente_factura_nueva').val(suggestion.data)
			$('#inputSerie').val(suggestion.nro_documento);
			$('#inputDireccion').val(suggestion.direccion)
		}
	});


//Campo de Cliente de crear tarea
	$("#inputNombreUsuario").devbridgeAutocomplete({
		showNoSuggestionNotice: true,
		serviceUrl: '/codeigniter/clientes/leer_clientes_desde_tareas',
		noSuggestionNotice: 'No se encontraron datos',
		onSelect: function (suggestion) {
			$('#inputNombreUsuario').val(suggestion.value);
			$('#inputConexionId').val(suggestion.conexion);
			$('#aclaracion_evento_nuevo').val(suggestion.direccion);
			
		}
	});


	// //Autocompletar de factura nueva
	// //Campo de Cliente
	// $("#inputCliente").devbridgeAutocomplete({
	// 	showNoSuggestionNotice: true,
	// 	serviceUrl: '/codeigniter/clientes/leer_clientes',
	// 	noSuggestionNotice: 'No se encontraron datos',
	// 	onSelect: function (suggestion) {
	// 		$('#inputIdCliente').val(suggestion.data)
	// 		$('#inputSerie').val(suggestion.nro_documento);
	// 		$('#inputDireccion').val(suggestion.direccion)
	// 	}
	// });
	

	//Autocompletar de factura nueva
	//Campo de Cliente
	$("#inputCliente_crearfactura").devbridgeAutocomplete({
		showNoSuggestionNotice: true,
		serviceUrl: '/codeigniter/facturar/buscar_datos_crear_factura',
		noSuggestionNotice: 'No se encontraron datos',
		onSelect: function (suggestion) {
			$('#inputIdCliente').val(suggestion.data)
			$('#inputRuc').val(suggestion.nro_documento);
			//$('#inputDate').val(suggestion.sector);
			$('#inputSector').val(suggestion.sector);
			$('#inputConexionId').val(suggestion.id_conexion);
			$('#inputCategoria').val(suggestion.categoria);
			$('#inputDireccion').val(suggestion.direccion);
			$('#inputDeuda').val(suggestion.deuda);
			
			if( (suggestion.categoria == "Familiar") || (suggestion.categoria == 1) )
			{
				$('#imputTarifaBasica').val(suggestion.tarifa_familiar);
				$('#inputCuotaSocial').val(suggestion.tarifa_social);
			}
			else
			{
				$('#imputTarifaBasica').val(suggestion.tarifa_comercial);
				$('#inputCuotaSocial').val(suggestion.tarifa_social);
			}
			$('#inputDeudaAnterior').val(suggestion.deuda);
			if(suggestion.plan_pago_couta_acutal != null)
			{
				$('#inputPlanPagoCuotaActual').val(suggestion.plan_pago_couta_acutal +"/"+ suggestion.plan_pago_couta_total );
				$('#inputPlanPago').val(suggestion.plan_pago_monto_cuota);
			}
			else
			{
				$('#inputPlanPagoCuotaActual').val('');
				$('#inputPlanPago').val(0);
			}
			
			if(suggestion.plan_medidor_couta_acutal != null)
			{
				$('#inputPlanMedidorCuotaActual').val(suggestion.plan_medidor_couta_acutal +"/"+ suggestion.plan_medidor_couta_total );
				$('#inputPagoMedidor').val(suggestion.plan_medidor_monto_cuota);
			}
			else
			{
				$('#inputPlanMedidorCuotaActual').val('');
				$('#inputPagoMedidor').val(0);
			}

			$('#inputExcedente').val(suggestion.excedente)
			
			var subtotal = parseFloat($('#inputDeuda').val()) + parseFloat($('#inputExcedente').val()) + parseFloat($('#imputTarifaBasica').val()) + parseFloat($('#inputCuotaSocial').val()) + parseFloat($('#inputPlanPago').val()) + parseFloat($('#inputPagoMedidor').val());
			//var subtotal = parseFloat($('#inputDeuda').val());
			$('#inputSubtotal').val(subtotal);

			$('#inputPagosAcuenta').val(suggestion.pago_a_cuenta);

			$('#inputBonificacion').val(parseFloat(subtotal) * parseFloat(suggestion.bonificacion));
			$('#inputTotal').val(
				parseFloat($('#inputSubtotal').val()) 
				- 
				parseFloat($('#inputBonificacion').val())
				- 
				parseFloat($('#inputPagosAcuenta').val())
				);





			// $('#inputConexionId').val(suggestion.id_conexion);
			// $('#inputConexionId').val(suggestion.id_conexion);


			
			
		} 
	});

	//autocompletar conexion
	$("#inputConexionId").devbridgeAutocomplete({
		showNoSuggestionNotice: true,
		serviceUrl: '/codeigniter/mediciones/leer_conexiones',
		noSuggestionNotice: 'No se encontraron datos',
		onSelect: function (suggestion) {
			$('#inputConexionId').val(suggestion.data);

			$('#inputCliente').val(suggestion.value);

			$('#inputConexionDomicilio').val(suggestion.domicilio);

			$('#inputMes').val(suggestion.mes);
			$('#inputAnio').val(suggestion.anio);
			
			$('#inputTipo').val(suggestion.tipo);


			$('#inputMAnterior').val(suggestion.anterior);
			foco_mecion_actual();
		}
	});

	//autocompletar conexion
	$("#inputConexionId_planPago").devbridgeAutocomplete({
		showNoSuggestionNotice: true,
		serviceUrl: '/codeigniter/plan_pago/buscar_conexion',
		noSuggestionNotice: 'No se encontraron datos',
		onSelect: function (suggestion) {
			$('#inputConexionId_planPago').val(suggestion.id);

			$('#cliente_razon_social').val(suggestion.value);

			$('#cliente_direccion').val(suggestion.direccion);

			$('#categoria_conexion').val(suggestion.categoria);

			$('#inputMontoTotal_agregarPlanPago').val(suggestion.deuda);

			$('#inputMontoTotal_agregarPlanPago').val(suggestion.deuda);
			//foco_mecion_actual();
		}
	});

//Campo de Cliente
	$("#inputCliente_plan_pago").devbridgeAutocomplete({
		showNoSuggestionNotice: true,
		serviceUrl: '/codeigniter/clientes/leer_clientes',
		noSuggestionNotice: 'No se encontraron datos',
		onSelect: function (suggestion) {
			$('#inputCliente_factura_nueva').val(suggestion.data)
			$('#inputSerie').val(suggestion.nro_documento);
			$('#inputDireccion').val(suggestion.direccion)
		}
	});

function foco_mecion_actual(){
	$('#inputMActual').focus();

}
	//./ Autocompletar campos

		$("#inputFacturaAjax").devbridgeAutocomplete({
		showNoSuggestionNotice: true,
		serviceUrl: '/codeigniter/facturar/buscar_factura',
		noSuggestionNotice: 'No se encontraron datos',
		onSelect: function (suggestion) {
			$('#agregar_bonificacion').show("1500");
			$('#agregar_PlanMedidor').show("1500");
			$('#mas_datos_boleta').show("1500");
			
			
			$('#inputFacturaAjax').val(suggestion.data)


			$('#inputCliente').val(suggestion.value);
			$('#inputMes').val(suggestion.mes);
			$('#inputAnio').val(suggestion.anio);
			if(suggestion.categoria == 1)
				$('#inputCategoria').val("Familiar");
			if(suggestion.categoria == 2)
				$('#inputCategoria').val("Comercial");
			$('#inputConexionId').val(suggestion.conexion);
			$('#inputAnterior').val(suggestion.anterior);
			$('#inputActual').val(suggestion.actual);
			$('#inputBasico').val(suggestion.basico);
			$('#inputExcedente').val(suggestion.excedente);
			$('#inputDeudaAnterior').val(suggestion.conexion_deuda);
			$('#inputDeudaAnterior_tabla').val(suggestion.conexion_deuda);
			
			$('#inputTarifaSocial').val(suggestion.tarifa);
			$('#inputdeudaExcente').val(suggestion.excedente_calculado);
			$('#inputdeudaCuotaSocial').val(suggestion.cuota_social);
			$('#inputsubtotal').val(suggestion.subtotal_aPagar);
			


			
			if(suggestion.id_planpago>0)
			{
				$('#inputplanpago_id').val(suggestion.id_planpago);
				$('#inputplanpagoplanpagocuotaactual').val(suggestion.planpago_acutal + "/"+suggestion.planpago_total);
				$('#inputplanpago').val(suggestion.planpago_precio_cuota);
			}
			else
			{
				$('#inputplanpago_id').val(0);
				$('#inputplanpagoplanpagocuotaactual').val(0);
				$('#inputplanpago').val(0);
			}


			if(suggestion.id_planmedidor>0)
			{
				$('#inputplanmedidor_id').val(suggestion.id_planmedidor);
				$('#inputplanmedidorcuotaactual').val(suggestion.planmedidor_actual + "/"+suggestion.planmedidor_total);
				$('#inputplanmedidor').val(suggestion.planmedidor_precio_cuota);
			}
			else
			{
				$('#inputplanmedidor_id').val(0);
				$('#inputplanmedidorcuotaactual').val(0);
				$('#inputplanmedidor').val(0);
			}
			


			$('#monto_pagar_con_bonificacion').val(suggestion.total_aPagar);
			$('#inputtotal').val(suggestion.total_aPagar);
			$('#total_sin_cambios').val(suggestion.total_aPagar);

			$('#inputIdCliente').val(suggestion.id_cli);
			$('#inputRuc').val(suggestion.nro_documento);
			$('#inputDireccion').val(suggestion.direccion);
			$('#inputSerie').val(suggestion.serie);
			$('#inputCorrelativo').val(suggestion.correlativo);
			$('#inputDate').val(suggestion.fechaEmision); //inicio
			$('#inputDateC').val(suggestion.fechaVenci); //fin
			$('#inputMonto').val(suggestion.monto); //fin
			$('#inputFacturaOculta').val(suggestion.id);
			cagar_datos_iniciales(suggestion.total_aPagar);
		}
	});
	function cagar_datos_iniciales(total)
	{
		console.log("Hola....");
		console.log(total);
		$('#inputtotal').val(total);
		$('[name=total]').val(total);

	}
	

	$("#inputMontoModif").on("change",function(){   
		modificar_campos();
	});

	$("#bonificacion").on("change",function(){
		modificar_campos();
	});


	function modificar_campos()
	{
		// 1 campo : id="inputMontoModif" 
		// 2 campo : id="bonificaicon" 
		// 3 campo :  id="monto_pagar_con_bonificacion"
		// 4 campo : id="inputendeuda"
		// 5 campo : id="inputtotal"
		// 6 campo : id="total_sin_cambios"
		// 7 campo : id="riego"
		var riego = $("#riego").val();
		var monto_total_modificado =  $("#inputMontoModif").val();
		console.log(monto_total_modificado);
		if ((monto_total_modificado != null)&&(monto_total_modificado != "")&&(monto_total_modificado != 0)&&(monto_total_modificado != " "))
			monto_total_modificado = monto_total_modificado.replace(",", ".");
		else monto_total_modificado = 0;
		monto_total_modificado = parseFloat(monto_total_modificado);
		monto_total_modificado = monto_total_modificado.toFixed(2)


		var bonificacion =  $("#bonificacion").val();
		if ((bonificacion != null)&&(bonificacion != "")&&(bonificacion != 0)&&(bonificacion != " "))
			bonificacion = bonificacion.replace(",", ".");
		else bonificacion = 0;
		bonificacion = parseFloat(bonificacion);
		bonificacion = bonificacion.toFixed(2);

		var monto_pagar_con_bonificacion =  $("#monto_pagar_con_bonificacion").val();
		if ((monto_pagar_con_bonificacion != null)&&(monto_pagar_con_bonificacion != "")&&(monto_pagar_con_bonificacion != 0)&&(monto_pagar_con_bonificacion != " "))
			monto_pagar_con_bonificacion = monto_pagar_con_bonificacion.replace(",", ".");
		else monto_pagar_con_bonificacion = 0;
		monto_pagar_con_bonificacion = parseFloat(monto_pagar_con_bonificacion);
		monto_pagar_con_bonificacion = monto_pagar_con_bonificacion.toFixed(2);

		var deuda =  $("#inputendeuda").val();
		if ((deuda != null)&&(deuda != "")&&(deuda != 0)&&(deuda != " "))
			deuda = deuda.replace(",", ".");
		else deuda = 0;
		deuda = parseFloat(deuda);
		deuda = deuda.toFixed(2);
		//console.log ("La deuda calculada es:"+deuda);

		var total =  $("#inputtotal").val();
		if ((total != null)&&(total != "")&&(total != 0)&&(total != " "))
			total = total.replace(",", ".");
		else total = 0;
		total = parseFloat(total);
		total = total.toFixed(2);

		var total_sin_cambios =  $("[name=total_sin_cambios]").val();
		if ((total_sin_cambios != null)&&(total_sin_cambios != "")&&(total_sin_cambios != 0)&&(total_sin_cambios != " "))
			total_sin_cambios = total_sin_cambios.replace(",", ".");
		else total_sin_cambios = 0;
		total_sin_cambios = parseFloat(total_sin_cambios);
		total_sin_cambios = total_sin_cambios.toFixed(2);

		if( (bonificacion == 0) ||  (bonificacion== null) ||  (bonificacion == '')||  (bonificacion == NaN))
			bonificacion = parseFloat(0);
		if( (deuda == 0) ||  (deuda== null) ||  (deuda == '')||  (deuda == NaN))
			deuda = parseFloat(0);

		if( (monto_total_modificado == 0) ||  (monto_total_modificado== null) ||  (monto_total_modificado == '')||  (monto_total_modificado == NaN))
		{//pago el total de la boleta
			console.log("estoy por aca aca");
			console.log("Total:"+parseFloat(total_sin_cambios)+"__ bonificacion:"+parseFloat(bonificacion));
			total =  parseFloat(total_sin_cambios) - parseFloat(bonificacion);
			total = total.toFixed(2);
			console.log("Total:"+parseFloat(total));
		}
		else
		{//pago parcial de la boleta
			console.log("Antes de calcular::");
			console.log(
				"Modificado:" +parseFloat(monto_total_modificado)+
				"_ total:"+parseFloat(total)+
				"_ bonificacion: "+parseFloat(bonificacion)+ "_Deuda:"+parseFloat(deuda)
				);
			total =  parseFloat(monto_total_modificado);
			total = total.toFixed(2);
			deuda =  parseFloat(total_sin_cambios) - parseFloat(monto_total_modificado) - parseFloat(bonificacion);
			deuda = deuda.toFixed(2);
			console.log("Despues de calcular::");
			console.log(
				"Modificado:" +parseFloat(monto_total_modificado)
				+"_ total:"+parseFloat(total)
				+"_ bonificacion: "+parseFloat(bonificacion)
				+"_ sin cambios: "+parseFloat(total_sin_cambios)
				+ "_Deuda:"+parseFloat(deuda)  );
		}
		$("#inputMontoModif").val(monto_total_modificado);  
		$("#bonificacion").val(bonificacion);  
		$("#monto_pagar_con_bonificacion").val(total);  
		$("#inputendeuda").val(deuda); 
		if(
		 ((deuda != 0) || (deuda != '') ||  (deuda != null) || (deuda != false) )
			&& (deuda>0)
			 )
			$('#agregar_PlanMedidor').show("1500");
		 $("#inputbonificacion").val(bonificacion);  
		

		$("#inputtotal").val(total);  
		$('[name=total]').val(total);
	}

	//Bonificacion
	//monto bonificado
	$("#inputMontoBonificado").on("change",function(){ 
		var deuda = $("#inputMonto").val();
		var monto = $("#inputMontoBonificado").val();
		var porcentaje = (parseFloat(monto)*100)/(parseFloat(deuda));
		$("#inputPorcentajeBonificado").val(Math.round(parseFloat(porcentaje)));
	});
	//monto porcentaje
	$("#inputPorcentajeBonificado").on("change",function(){ 
		var deuda = $("#inputMonto").val();
		var porcentaje = $("#inputPorcentajeBonificado").val();
		var monto = (parseFloat(deuda)*(parseFloat(porcentaje)/100));
		var montoRedondeado = monto.toFixed(2);
		$("#inputMontoBonificado").val(montoRedondeado);
	});

	 




	// Repetidor de campos de Productos
		$('.repeater').repeater({
			hide: function (deleteElement) {
				var elemento=$(this);
				swal({   
					title: "Estas Seguro?",   
					text: "Se borrará toda la fila!",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#DD6B55",   
					confirmButtonText: "Si, bórralo!",
					cancelButtonText: 'Cancelar',   
					closeOnConfirm: false
				}, function(){   
					swal("Producto Borrado!", "Se ha eliminado el Producto.", "success"); 
					elemento.remove();
					sumarTotales();
					crearIgv();
				});
			},
			isFirstItemUndeletable: true
		});
	// ./ Repetidor de campos de Productos

	// Sumar Precios

	var precio,cantidad,precioUnidad;
	var monto, inputstotal=$(".row #inputPrecio").length;//obtiene el numero de campos de precio
		
	//sí, cambia los valores de Cantidad y Precio
	$(".producto-container").on("load keyup","#inputPrecioUnidad,#inputCantidad",function(){

		$("div[data-repeater-item]").each(function(index,valor){
			
			cantidad=$(this).find("#inputCantidad").val();
			precioUnidad=$(this).find("#inputPrecioUnidad").val();

			// Introduce el costo total del producto
			if(precioUnidad!=''){

				precio=cantidad*precioUnidad;
				$(this).find("#inputPrecio").val(precio.toFixed(2));
				// calcular_total();

				sumarTotales();
				crearIgv();
			}

		});

	});
	
	sumarTotales();
	crearIgv();

	//incluir IGV
	$("#incluyeIGV").on("click",function(){
		sumarTotales();
		crearIgv();
	});

	// //Comprobar campos antes de guardar
	// $("#enviarDatos,#actualizarDatos").click(function(e){
	//     var camposVacios=0;
	//     $('#content input:not([type="hidden"]):not([type="checkbox"]):not([class="checkbox"])').each(function(index,value){
	//     	// Si los campos son diferentes de vacios
	//     	// y diferentes de 0 y si tiene la clase "has-error"
	//     	// le qita el marco rojo de error
	//         if($(this).val()!="" && $(this).val() != "0" && $(this).parents(".form-group").hasClass("has-error")){
	//             $(this).parents(".form-group").removeClass("has-error");//elimina la clase "has-error"
	//         }else{// de lo contrario
	//         	// Si los campos estan vacios
	//         	// y sin son iguales a 0 se añade la clase has error
	//             if($(this).val() === "" || $(this).val() == "0"){
	//             	//agregar la clase has error al contenedor
	//                 $(this).parents(".form-group").addClass("has-error");
	//                 camposVacios++
	//             }
	//         }
	//     });
	// 	//Si hay campos vacios emite alerta
	//     if(camposVacios>0){
	//     	notify("Debes llenar todos los campos", "danger", 2500)// ./ Si hay campos vacios emite alert
	//     }else if($(this).attr("id")=="enviarDatos"){
	// 		// comprueba el correlativo
	//     	var serie=$("#inputSerie").val();
	//     	var correlativo=$("#inputCorrelativo").val();
 //    	    var datos = {
 //    	        "serie" : serie,
 //    	        "correlativo" : correlativo
 //    	    };

 //    	    $.ajax({
 //    	        data:  datos,
 //    	        url:   '../ajax/comprobar_correlativo',
 //    	        method:  'POST',
 //    	        success:  function (response) {
 //    	        		if(response === "true"){
 //    	        			$("#inputCorrelativo").val(" ");
 //    	        			$("#enviarDatos").attr("status","false")//agrega un atributo a el boton enviar
 //    	        			notify('Ya se registro este número de factura', 'danger');
 //    	        		}else if(response === "false"){
 //    	        			$("#formDocument").submit();
 //    	        		}
 //    	        }
 //    	    });
	// 	    // ./ comprueba el correlativo
			
	//     }else if($(this).attr("id")=="actualizarDatos"){
	//     	$("#formDocument").submit();
	//     }
		
	// });
	// //./ Comprobar campos antes de guardar


	$("#enviarDatos,#actualizarDatos").click(function(e){
		// comprueba el correlativo
		var id_cliente=$("#inputIdCliente").val();
		var id_conexion=$("#inputConexionId").val();
		var subtotal=$("#inputSubtotal").val();
		var total=$("#inputTotal").val();
		$.ajax({
			data:  {'id_cliente': id_cliente,
					'id_conexion': id_conexion,
					'subtotal': subtotal,
					'total': total,
					},
			url:   '../facturar/guardar_datos_factura',
			method:  'POST',
			success:  function (response) 
			{
				console.log("el resultado:"+response);
				if(response === "false")
					alert("algo malo paso");
				else 
					swal("Se cargo correctamente la boleta!", "Cargada correctamente", "success"); 
				 setTimeout("redireccionarCrearBoletaCOnexionId("+response+")", 1200);
					
			},
			error: function(){
				alert("Hubo un error enviando la petición al servidor, contactar al administrador")
			  }
		});
		// ./ comprueba el correlativo
	});




	//Comprobar campos antes de guardar
	$("#submit_form_plan_pago").click(function(e){
		var monto_plan_pago= $("#monto_plan_pago").val();
		var inputendeuda_modal= $("#inputendeuda_modal").val();
		var cant_coutas_plan_pago= $("#cant_coutas_plan_pago").val();
		var monto_cuota_plan_pago= $("#monto_cuota_plan_pago").val();
		var fecha_inicio_plan_pago= $("#fecha_inicio_plan_pago").val();
		var fecha_fin_plan_pago= $("#fecha_fin_plan_pago").val();
		var id_conexion= $("#inputConexionId").val();
		swal({   
			title: "Estas Seguro?",   
			text: "Se creara este plan de pago!",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Si, crear!",
			cancelButtonText: 'Cancelar',   
			closeOnConfirm: false
		}, function(){  
			$.ajax({
				data:  {'monto': monto_plan_pago, 'total_deuda':inputendeuda_modal, 'cant_coutas': cant_coutas_plan_pago, 'fecha_inicio': fecha_inicio_plan_pago, 'id_conexion':id_conexion },
				url:   '../pago/guardar_plan_pago',
				method:  'POST',
				success:  function (response) 
				{
					if(response === false)
						swal("Se prudujo un fallo!", "No se pudo guardar el plan de pago"+response, "danger"); 
					else 
						swal("Plan Creado!", "El plan de pago se guardo correctamente", "success"); 
				}
			});
		});
	});
});
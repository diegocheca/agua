$(document).on('ready',function(){
	$("#inputCantidadCuotas_agregarPlanPago").on("change",function(){
		calcular_precio_por_couta();
	});
	$("#inputInteres_agregarPlanPago").on("change",function(){
		calcular_precio_por_couta();
	});

	$("#submit_agregandoPlanPago").on("click",function(e){
		e.preventDefault();
		var inputConexionId_agregarPlanPago = $("#inputConexionId_agregarPlanPago").val();
		var id_agregarPlanPago = $("#id_agregarPlanPago").val();
		var ismodal_agregarPlanPago = $("#ismodal_agregarPlanPago").val();
		var inputMontoTotal_agregarPlanPago = $("#inputMontoTotal_agregarPlanPago").val();
		var inputFechaInicio_agregarPlanPago = $("#inputFechaInicio_agregarPlanPago").val();
		var inputMontoPagado_agregarPlanPago = $("#inputMontoPagado_agregarPlanPago").val();
		var inputCantidadCuotas_agregarPlanPago = $("#inputCantidadCuotas_agregarPlanPago").val();
		var inputMontoCuota_agregarPlanPago = $("#inputMontoCuota_agregarPlanPago").val();
		var inputInteres_agregarPlanPago = $("#inputInteres_agregarPlanPago").val();
		var inputCuotaActual_agregarPlanPago = $("#inputCuotaActual_agregarPlanPago").val();
		var inputObservacion_agregarPlanPago = $("#inputObservacion_agregarPlanPago").val();
		//var resultado = validar_formulario_agregar_PlanPago(inputConexionId_agregarPlanPago,id_agregarPlanPago,ismodal_agregarPlanPago,inputMontoTotal_agregarPlanPago,inputFechaInicio_agregarPlanPago,inputMontoPagado_agregarPlanPago,inputCantidadCuotas_agregarPlanPago,inputMontoCuota_agregarPlanPago,inputInteres_agregarPlanPago,inputCuotaActual_agregarPlanPago,inputObservacion_agregarPlanPago);
		var resultado = true;
			$.ajax({
				url: 'http://192.168.1.35/codeigniter/plan_pago/enviar_formulario_modal_plan_pago_nuevo',
				type: 'POST',
				data: {
					inputConexionId:inputConexionId_agregarPlanPago, 
					inputMontoTotal:inputMontoTotal_agregarPlanPago,
					inputMontoPagado:inputMontoPagado_agregarPlanPago,
					inputMontoCuota:inputMontoCuota_agregarPlanPago,
					inputCantidadCuotas:inputCantidadCuotas_agregarPlanPago,
					inputInteres:inputInteres_agregarPlanPago,
					inputCuotaActual:inputCuotaActual_agregarPlanPago,
					inputFechaInicio:inputFechaInicio_agregarPlanPago,
					inputObservacion:inputObservacion_agregarPlanPago,
					id_agregarPlanPago:id_agregarPlanPago,
					ismodal_agregarPlanPago:ismodal_agregarPlanPago
				},
				success: function(response){
					if (response === false) 
					{
						alert("Error");
					}
					else
					{
						//alert(response);
						//$("#myModalPlanMedidor").modal('hide');
						swal(
							"Plan Pago Creado!", "Se ha creado un nuevo plan de pago.", "success"); 
						$("#id_agregarPlanPago_ya_creado").val(response);
						//console.log("datos del response:"+response);
						//console.log("datos del response: dasdas");
						cerrar_modal_planpago_nuevo(inputConexionId_agregarPlanPago,inputMontoCuota_agregarPlanPago,inputFechaInicio_agregarPlanPago,inputCantidadCuotas_agregarPlanPago,inputInteres_agregarPlanPago,inputObservacion_agregarPlanPago);
					}
				},
				error:function (request, status, error) 
				{
					console.log("estoy en el error"+request.responseText);
					alert("estoy en el error"+request.responseText);
				}
			});
	});

		
	$("#submit_actualizarPlanPago").on("click",function(e){
				e.preventDefault();
				var inputConexionId_agregarPlanPago = $("#inputConexionId_agregarPlanPago").val();
				var id_agregarPlanPago = $("#id_agregarPlanPago").val();
				var ismodal_agregarPlanPago = $("#ismodal_agregarPlanPago").val();
				var inputMontoTotal_agregarPlanPago = $("#inputMontoTotal_agregarPlanPago").val();
				var inputFechaInicio_agregarPlanPago = $("#inputFechaInicio_agregarPlanPago").val();
				var inputMontoPagado_agregarPlanPago = $("#inputMontoPagado_agregarPlanPago").val();
				var inputCantidadCuotas_agregarPlanPago = $("#inputCantidadCuotas_agregarPlanPago").val();
				var inputMontoCuota_agregarPlanPago = $("#inputMontoCuota_agregarPlanPago").val();
				var inputInteres_agregarPlanPago = $("#inputInteres_agregarPlanPago").val();
				var inputCuotaActual_agregarPlanPago = $("#inputCuotaActual_agregarPlanPago").val();
				var inputObservacion_agregarPlanPago = $("#inputObservacion_agregarPlanPago").val();

				//var resultado = validar_formulario_agregar_PlanPago(inputConexionId_agregarPlanPago,id_agregarPlanPago,ismodal_agregarPlanPago,inputMontoTotal_agregarPlanPago,inputFechaInicio_agregarPlanPago,inputMontoPagado_agregarPlanPago,inputCantidadCuotas_agregarPlanPago,inputMontoCuota_agregarPlanPago,inputInteres_agregarPlanPago,inputCuotaActual_agregarPlanPago,inputObservacion_agregarPlanPago);
				var resultado = true;

					$.ajax({
						url: 'http://192.168.1.35/codeigniter/plan_pago/enviar_formulario_modal_plan_pago_nuevo',
						type: 'POST',
						data: {
							inputConexionId:inputConexionId_agregarPlanPago, 
							inputMontoTotal:inputMontoTotal_agregarPlanPago,
							inputMontoPagado:inputMontoPagado_agregarPlanPago,
							inputMontoCuota:inputMontoCuota_agregarPlanPago,
							inputCantidadCuotas:inputCantidadCuotas_agregarPlanPago,
							inputInteres:inputInteres_agregarPlanPago,
							inputCuotaActual:inputCuotaActual_agregarPlanPago,
							inputFechaInicio:inputFechaInicio_agregarPlanPago,
							inputObservacion:inputObservacion_agregarPlanPago,
							id_agregarPlanPago:id_agregarPlanPago,
							ismodal_agregarPlanPago:ismodal_agregarPlanPago
						},
						success: function(response){
							if (response === false) 
							{
								alert("Error");
							}
							else
							{
								cerrar_modal_planpago_actualizado(inputMontoCuota_agregarPlanPago,inputFechaInicio_agregarPlanPago,inputCantidadCuotas_agregarPlanPago,inputInteres_agregarPlanPago,inputObservacion_agregarPlanPago);
								
							}
						},
						error:function (request, status, error) 
						{
							console.log("estoy en el error"+request.responseText);
							alert("estoy en el error"+request.responseText);
						}
					});
	});

	 function calcular_precio_por_couta()
	{
		var cant_couta = $("#inputCantidadCuotas_agregarPlanPago").val();
		var monto_total = $("[name = endeuda]").val();
		var interes  = $("#inputInteres_agregarPlanPago").val();
		if( (parseInt(cant_couta)>0) && (parseInt(cant_couta)<13) && !(isNaN(cant_couta)) )
			if((parseInt(interes)>0) && (parseInt(interes)<45) && !(isNaN(interes)) )
			{
				var monto_por_cuota = ( parseFloat(monto_total) / parseFloat(cant_couta) ) * ( (parseFloat(interes)/100)+1);
				monto_por_cuota = monto_por_cuota.toFixed(2);
				$("#inputMontoCuota_agregarPlanPago").val(monto_por_cuota);
			}
			else
				if(interes == 0)
				{
					var monto_por_cuota = ( parseFloat(monto_total) / parseFloat(cant_couta) );
					monto_por_cuota = monto_por_cuota.toFixed(2);
					$("#inputMontoCuota_agregarPlanPago").val(monto_por_cuota);
				}
				else 
					alert("El valor del interes de las cuotas debe ser mayor a 0 y menor a 45");
		else
			alert("El valor de la cantidad de cuotas debe ser mayor a 0 y menor a 12");
	}
			
	$("#modificar_PlanMedidor").on("click",function(e){
				e.preventDefault();
				var inputConexionId_agregarPlanPago = $("#inputConexionId_agregarPlanPago").val();
				var id_agregarPlanPago = $("#id_agregarPlanPago_ya_creado").val();
				var ismodal_agregarPlanPago = true;
				//alert(id_agregarPlanPago);
				$.ajax({
						url: 'http://192.168.1.35/codeigniter/plan_pago/buscar_datos_modal_plan_pago_nuevo',
						type: 'POST',
						data: {
							inputConexionId:inputConexionId_agregarPlanPago, 
							id_agregarPlanPago:id_agregarPlanPago,
							ismodal_agregarPlanPago:ismodal_agregarPlanPago
						},
						success: function(response){

							/*console.log(datos_tres);
							console.log(datos_tres.conexion_id);
							console.log(datos_tres.id_plan_pago);
							console.log(datos_tres.is_modal);
							console.log(datos_tres.fecha_inicio_plan_pago);
							console.log(datos_tres.monto_pago);
							console.log(datos_tres.cantidadCuotas);
							console.log(datos_tres.monto_por_cuota);
							console.log(datos_tres.interes_por_cuotas);
							console.log(datos_tres.observaciones_plan_pago);*/


							if (response === false) 
							{
								alert("Error");
							}
							else
							{
								var obj = JSON.parse(response);
								var datos_tres = JSON.parse(obj);
								var observacion = null;
								if(datos_tres.observaciones_plan_pago != "null")
									observacion = datos_tres.observaciones_plan_pago ;

								$("#inputConexionId_agregarPlanPago").val(datos_tres.conexion_id);
								$("#id_agregarPlanPago").val(datos_tres.id_plan_pago);
								$("#ismodal_agregarPlanPago").val(datos_tres.is_modal);
								$("#inputFechaInicio_agregarPlanPago").val(datos_tres.fecha_inicio_plan_pago);
								$("#inputMontoPagado_agregarPlanPago").val(datos_tres.monto_pago);
								$("#inputCantidadCuotas_agregarPlanPago").val(datos_tres.cantidadCuotas);
								$("#inputMontoCuota_agregarPlanPago").val(datos_tres.monto_por_cuota);
								$("#inputInteres_agregarPlanPago").val(datos_tres.interes_por_cuotas);
								$("#inputCuotaActual_agregarPlanPago").val(datos_tres.cuota_actual);
								$("#inputObservacion_agregarPlanPago").val(observacion);


								$("#submit_agregandoPlanPago").hide('1500');
								$("#submit_actualizarPlanPago").show('1500');
								mostrar_modal_plan_pago();
								//swal("Plan Pago Creado!", "Se ha creado un nuevo plan de pago.", "success"); 
								//cerrar_modal_planpago_nuevo(inputConexionId_agregarPlanPago,inputMontoCuota_agregarPlanPago,inputFechaInicio_agregarPlanPago,inputCantidadCuotas_agregarPlanPago,inputInteres_agregarPlanPago,inputObservacion_agregarPlanPago);
							}
						},
						error:function (request, status, error) 
						{
							console.log("estoy en el error"+request.responseText);
							alert("estoy en el error"+request.responseText);
						}
					});
	});

	$("#quitar_PlanMedidor").on("click",function(e){
		e.preventDefault();
		var id_agregarPlanPago = $("#id_agregarPlanPago_ya_creado").val();
		$.ajax({
				url: 'http://192.168.1.35/codeigniter/plan_pago/borrar_usuarios',
				type: 'POST',
				data: {
					id_agregarPlanPago:id_agregarPlanPago
				},
				success: function(response){
					if (response === false) 
					{
						alert("Error");
					}
					else
					{
						ocultar_datos_plan_de_pago_creado();
					}
				},
				error:function (request, status, error) 
				{
					console.log("estoy en el error"+request.responseText);
					alert("estoy en el error"+request.responseText);
				}
			});
   });
	});

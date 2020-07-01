//Welcome Message (not for login page)
function redireccionarPagina() {
	window.location = 'http://localhost/codeigniter/clientes';
}
function redireccionarPaginaConexion() {
  window.location = 'http://localhost/codeigniter/conexion';
}
function redireccionarPaginaMedidor() {
  window.location = 'http://localhost/codeigniter/inventario';
}
function redireccionarPaginaPago() {
  window.location = 'http://localhost/codeigniter/pago';
}
function redireccionarOrdenTrabajo() {
  window.location = 'http://localhost/codeigniter/orden_trabajo';
}
function redireccionarPaginaTipoMedidor() {
  window.location = 'http://localhost/codeigniter/tipos_medidores';
}

function redireccionarPaginaUsuario () {
  window.location = 'http://localhost/codeigniter/usuarios';
}

function redireccionarPaginaMediciones () {
  window.location = 'http://localhost/codeigniter/mediciones';
}
function redireccionarPaginaPlanPago () {
  window.location = 'http://localhost/codeigniter/plan_pago';
}
function redireccionarPaginaBonificacion () {
  window.location = 'http://localhost/codeigniter/bonificacion';
}
function redireccionarPaginaBonificacionAprobada () {
  window.location = 'http://localhost/codeigniter/bonificacion/cargar_aprobadas';
}
function redireccionarPaginaDeuda () {
  window.location = 'http://localhost/codeigniter/deuda';
}
function redireccionarPaginaMovientoIngreso () {
  window.location = 'http://localhost/codeigniter/movimientos';
}
function redireccionarPaginaMateriales () {
  window.location = 'http://localhost/codeigniter/materiales';
}

function redireccionarPaginaOrdenTrabajo () {
  window.location = 'http://localhost/codeigniter/orden_trabajo';
}
function redireccionarBoletadeIngreso (linkdddd) {
		var a = document.createElement("a");
		a.target = "_blank";
		a.href = 'http://localhost/codeigniter/imprimir/crear_recibo_de_pago'+"/"+linkdddd;
		a.click();
		window.location = 'http://localhost/codeigniter';

  //window.location = ;
}
function redireccionarCrearBoletaCOnexionId (linkdddd) {
		var a = document.createElement("a");
		a.target = "_blank";
		a.href = 'http://localhost/codeigniter/imprimir/crear_factura_por_conexion_id'+"/"+linkdddd;
		a.click();
		window.location = 'http://localhost/codeigniter';

  //window.location = ;
}

function redireccionarIndex () {
  window.location = 'http://localhost/codeigniter';
}



function modal_borrar_medidor (id)
{
	var elemento=$(this);
	alert(id);
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
		$.ajax({
			data:  {"id" : id},
			url:   'inventario/borrar_medidor',
			type:  'post',
			// beforeSend: function () {
			//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			// },
			success:  
			function (response) {
				//alert (response);
				if(response == 1){
					console.log("entre al true");
					
					swal("Medidor Borrado!", "Se ha eliminado el medidor.", "success"); 
					
					setTimeout("redireccionarPaginaMedidor()", 1500);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaMedidor()", 1200);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}


function modal_borrar_pago (id)
{
	var elemento=$(this);
	swal({   
		title: "Estas Seguro?",   
		text: "Se borrará este pago!",   
		type: "warning",   
		showCancelButton: true,   
		confirmButtonColor: "#DD6B55",   
		confirmButtonText: "Si, bórralo!",
		cancelButtonText: 'Cancelar',   
		closeOnConfirm: false
	}, function(){  
		$.ajax({
			data:  {"id" : id},
			url:   'pago/borrar_pago_y_movimiento',
			type:  'post',
			success:  
			function (response) {
				if(response == 1){
					console.log("entre al true");
					swal("Pago y Movimiento Borrados!", "Se ha eliminado correctamente.", "success"); 
					setTimeout("redireccionarPaginaPago()", 1500);
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaPago()", 1200);
					}
				}
			}
		});
	});
}
function modal_borrar (id)
{
	var elemento=$(this);
	alert(id);
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
		$.ajax({
			data:  {"id" : id},
			url:   'clientes/borrar_cliente',
			type:  'post',
			// beforeSend: function () {
			//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			// },
			success:  
			function (response) {
				//alert (response);
				if(response == 1){
					console.log("entre al true");
					
					swal("Cliente Borrado!", "Se ha eliminado el cliente.", "success"); 
					
					setTimeout("redireccionarPaginaConexion()", 1500);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaConexion()", 1200);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}

function modal_borrar_plan_pago (id)
{
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
		$.ajax({
			data:  {"id" : id},
			url:   'plan_pago/borrar_usuarios',
			type:  'post',
			// beforeSend: function () {
			//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			// },
			success:  
			function (response) {
				//alert (response);
				if(response == 1){
					console.log("entre al true");
					
					swal("Plan Borrado!", "Se ha eliminado el plna de pago.", "success"); 
					
					setTimeout("redireccionarPaginaPlanPago()", 1500);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaPlanPago()", 1200);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}


function modal_borrar_orden_trabajo (id)
{
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
		$.ajax({
			data:  {"id" : id},
			url:   'orden_trabajo/borrar_orden_trabajo',
			type:  'post',
			success:  
			function (response) {
				if(response == 1){
					console.log("entre al true");
					swal("Orden Borrada!", "Se ha eliminado la orden de trabajo.", "success"); 
					setTimeout("redireccionarPaginaOrdenTrabajo()", 1500);
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaOrdenTrabajo()", 1200);
					}
				}
			}
		});


		
	});
}



function modal_modificar_medicion(id, anterior, actual, obs, excedente,tipo)
{
	$("#Lectura_Anterior").val(anterior);
	$("#Lectura_Actual").val(parseInt(actual));
	$("#input_excedente").val(parseInt(excedente));
	$("#id_medicion").val(id);
	$("#observacion_medicion").val(obs);
	$("#tipo_conexion_input").val(tipo);
	

	$("#modal_modificar_medicion").modal("show");
}

function modal_aprobar_medicion(id, anterior, actual, tipo)
{
	$("#Lectura_Anterior").val(anterior);
	$("#Lectura_Actual").val(parseInt(actual));
	$("#id_medicion").val(id);
	$("#tipo_conexion_input").val(tipo);
	$("#modal_modificacion_medicion").modal("show");
}

function modal_borrar_mov_ingreso (id)
{
	var elemento=$(this);
	//alert(id);
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
		$.ajax({
			data:  {"id" : id},
			url:   'http://localhost/codeigniter/movimientos/borrar_movimiento',
			type:  'post',
			success:  
			function (response) {
				if(response == 1){
					console.log("entre al true");
					swal("Movimiento Borrado!", "Se ha eliminado el movimiento de ingreso", "success"); 
					
					setTimeout("redireccionarPaginaMovientoIngreso()", 1500);
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaMovientoIngreso()", 1200);
					}
				}
			}
		});


		
	});
}

function modal_borrar_materiales (id)
{
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
		$.ajax({
			data:  {"id" : id},
			url:   'materiales/borrar_materiales',
			type:  'post',
			success:  
			function (response) {
				if(response == 1){
					console.log("entre al true");
					swal("Material Borrado!", "Se ha eliminado el material", "success"); 
					
					setTimeout("redireccionarPaginaMateriales()", 1500);
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaMateriales()", 1200);
					}
				}
			}
		});


		
	});
}

function modal_borrar_deuda (id)
{
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
		$.ajax({
			data:  {"id" : id},
			url:   'deuda/borrar_deuda',
			type:  'post',
			// beforeSend: function () {
			//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			// },
			success:  
			function (response) {
				//alert (response);
				if(response == 1){
					console.log("entre al true");
					
					swal("Deuda Borrada!", "Se ha eliminado la deuda.", "success"); 
					
					setTimeout("redireccionarPaginaDeuda()", 1500);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al false");
						setTimeout("redireccionarPaginaDeuda()", 1200);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}
/*
function aprobar_autorizacion(id, vto1, vto2, descuento, monto_pago, fecha_pago)
{
	$("#vencimiento_uno").val(parseFloat(vto1));
	$("#vencimiento_dos").val(parseFloat(vto2));
	$("#descuento").val(descuento);
	$("#id_medicion").val(id);
	$("#monto_pago").val(monto_pago);
	$("#fecha_pago").val(fecha_pago);

	$("#modal_aprobar_descuento").modal("show");
}*/



function cerrar_modal_planpago_nuevo(id_plan,monto_cuota, fecha,cant,inter,obs)
{
	//$('#id_agregarPlanPago_ya_creado').val(id_plan);  
	$('#inputFechaInicio_agregarPlanPago_ya_creado').val(fecha);  
	$('#inputCantidadCuotas_agregarPlanPago_ya_creado').val(cant);  
	$('#inputMontoCuota_agregarPlanPago_ya_creado').val(monto_cuota);  
	$('#inputInteres_agregarPlanPago_ya_creado').val(inter);  
	$('#inputObservacion_agregarPlanPago_ya_creado').val(obs);  
	$('#quitar_PlanMedidor').show("1500");
	$('#modificar_PlanMedidor').show("1500");
	
	$('#hab_medidor').attr('disabled',true);
	$('#datos_plan_pago_ya_creado').show('1500');  
	$('#plan_de_pago_quitar_modificar_div').show('1500');  
	
	$('#hab_medidor').prop('checked',true);
	$('#plan_de_pago_div').hide();
	$('#myModalPlanMedidor').parent().removeClass('open');
  //  $('#myModalPlanMedidor').modal('toogle');
	//$('#myModalPlanMedidor').modal('hide');
	console.log("despues de cerrar con funcion");
}

function cerrar_modal_planpago_actualizado(monto_cuota, fecha,cant,inter,obs)
{
	//$('#id_agregarPlanPago_ya_creado').val(id_plan);  
	$('#inputFechaInicio_agregarPlanPago_ya_creado').val(fecha);  
	$('#inputCantidadCuotas_agregarPlanPago_ya_creado').val(cant);  
	$('#inputMontoCuota_agregarPlanPago_ya_creado').val(monto_cuota);  
	$('#inputInteres_agregarPlanPago_ya_creado').val(inter);  
	$('#inputObservacion_agregarPlanPago_ya_creado').val(obs);  
	$('#quitar_PlanMedidor').show("1500");
	$('#modificar_PlanMedidor').show("1500");
	
	$('#hab_medidor').attr('disabled',true);
	$('#datos_plan_pago_ya_creado').show('1500');  
	$('#plan_de_pago_quitar_modificar_div').show('1500');  
	
	$('#hab_medidor').prop('checked',true);
	$('#plan_de_pago_div').hide();
	$('#myModalPlanMedidor').parent().removeClass('open');
	$('#myModalPlanMedidor').modal('hide');
	swal("Plan Pago Modificado!", "Se ha modificado correctamente el plan de pago.", "success"); 
}

function ocultar_datos_plan_de_pago_creado()
{
	$('#inputFechaInicio_agregarPlanPago_ya_creado').val(null);  
	$('#inputCantidadCuotas_agregarPlanPago_ya_creado').val(null);
	$('#inputMontoCuota_agregarPlanPago_ya_creado').val(null);  
	$('#inputInteres_agregarPlanPago_ya_creado').val(null);  
	$('#inputObservacion_agregarPlanPago_ya_creado').val(null);  
	$('#datos_plan_pago_ya_creado').hide('1500');
	$('#quitar_PlanMedidor').hide("1500");
	$('#modificar_PlanMedidor').hide("1500");
	$('#hab_medidor').prop('checked',false);
	$('#plan_de_pago_div').show();
	swal("Plan Pago Eliminado!", "Se ha eliminado correctamente el plan de pago.", "success");
}

 function mostrar_modal_plan_pago()
 {
	$('#myModalPlanMedidor').modal('show');
 }  


function modal_borrar_medidor (id)
{
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
		$.ajax({
			data:  {"id" : id},
			url:   'inventario/borrar_medidor',
			type:  'post',
			// beforeSend: function () {
			//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			// },
			success:  
			function (response) {
				//alert (response);
				if(response == 1){
					console.log("entre al true");
					
					swal("Medidor Borrado!", "Se ha eliminado el medidor.", "success"); 
					
					setTimeout("redireccionarPaginaMedidor()", 1500);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaMedidor()", 1200);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}



function modal_borrar_medicion (id)
{
   //alert(id);
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
		$.ajax({
			data:  {"id" : id},
			url:   'http://localhost/codeigniter/mediciones/borrar_mediciones',
			type:  'post',
			// beforeSend: function () {
			//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			// },
			success:  
			function (response) {
				if(response == 1){
					console.log("entre al true");
					
					swal("Medición Borrada!", "Se ha eliminado la medicion correctamente.", "success"); 
					
					setTimeout("redireccionarPaginaMediciones()", 1500);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaMediciones()", 1200);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}



function modal_borrar_conexion (id)
{
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
		$.ajax({
			data:  {"id" : id},
			url:   'conexion/borrar_conexion',
			type:  'post',
			// beforeSend: function () {
			//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			// },
			success:  
			function (response) {
				//alert (response);
				if(response == 1){
					console.log("entre al true");
					
					swal("Conexion Borrada!", "Se ha eliminado la conexion.", "success"); 
					
					setTimeout("redireccionarPagina()", 1500);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPagina()", 1200);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}


function modal_borrar_usuario (id)
{
	var elemento=$(this);
	swal({   
		title: "Estas Seguro?",   
		text: "Se borrará el usuario!",   
		type: "warning",   
		showCancelButton: true,   
		confirmButtonColor: "#DD6B55",   
		confirmButtonText: "Si, bórralo!",
		cancelButtonText: 'Cancelar',   
		closeOnConfirm: false
	}, function(){  
		$.ajax({
			data:  {"id" : id},
			url:   'usuarios/borrar_usuarios',
			type:  'post',
			// beforeSend: function () {
			//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			// },
			success:  
			function (response) {
				//alert (response);
				if(response == 1){
					console.log("entre al true");
					
					swal("Usuario Borrada!", "Se ha eliminado el usuario, correctamente.", "success"); 
					
					setTimeout("redireccionarPaginaUsuario()", 700);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPagina()", 700);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}
function modal_aprobar_medicion_llamado(id,des, vto, vto2,fecha, monto , con)
{
	//alert(fecha);
	$("#id_medicion").val(id);
	$("#vencimiento_uno").val(parseFloat(vto));
	$("#vencimiento_dos").val(parseFloat(vto2));
	$("#descuento").val(des);
	$("#id_medicion").val(id);
	$("#monto_pago").val(monto);
	$("#fecha_pago").val(fecha);
	$("#conexion_id_input").val(con);
	

	$("#modal_aprobar_descuento").modal("show");

	//aprobar_autorizacion($(this).data("row-id"), arre, vto2, des , mon, fecha);

}

function modal_borrar_generico (id, url, mensaje)
{
	//alert(id);
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
		$.ajax({
			data:  {"id" : id},
			url:   url,
			type:  'post',
			// beforeSend: function () {
			//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			// },
			success:  
			function (response) {
				//alert (response);
				if(response == 1){
					//console.log("entre al true");
					
					swal(mensaje+"Borrado!", "Se ha eliminado el "+mensaje+" .", "success"); 
					
				setTimeout("redireccionarPaginaUsuario()", 1500);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaUsuario()", 1200);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}




function cargar_datos_en_modal_movimiento (id)
{
	//alert(id);
	$.ajax({
		data:  {"id_movimiento" : id},
		url:   'http://localhost/codeigniter/nuevo/cargar_datos_en_modal_movimiento/'+id,
		type:  'post',
		// beforeSend: function () {
		//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
		// },
		success:  
		function (response) {
			var datos = jQuery.parseJSON(response);
			datos = datos[0];
			//datos = jQuery.parseJSON(datos);
			console.log (datos["Mov_Id"]);
			if(response != false){
				
				$("#id_moviemiento").val(datos["Mov_Id"]);
				$("#tipo_moviemiento").val(datos["Mov_Tipo"]);
				$("#monto_moviemiento").val(datos["Mov_Monto"]);
				$("#fecha_moviemiento").val(datos["Mov_Timestamp"]);
				$("#codigo_moviemiento").val(datos["Mov_Codigo"]);
				$("#factura_moviemiento").val(datos["Mov_Pago_Id"]);
				$("#quien_moviemiento").val(datos["Mov_Quien"]);

				



				// $("#id_moviemiento").val(datos["Mov_Id"]);
				// $("#id_moviemiento").val(datos["Mov_Id"]);

				//swal(mensaje+"Borrado!", "Se ha eliminado el "+mensaje+" .", "success"); 
				
			//setTimeout("redireccionarPaginaBonificacion()", 1500);
			   /* function exito(){
				$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
				}
				window.setTimeout(exito,600);
				function redireccion(){
					location.reload();
				}
				window.setTimeout(redireccion,1200);*/
			}else{
				if(response == false){
					alert ("volvio un false");
					console.log("entre al faslse");
					//setTimeout("redireccionarPaginaBonificacion()", 1200);
				   /* function error(){
						$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
					}
					window.setTimeout(error,600);*/
				}
			}
		}
		});
}


function modal_borrar_bonificacion (id, url, mensaje)
{
	//alert(id);
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
		$.ajax({
			data:  {"id" : id},
			url:   'http://localhost/codeigniter/bonificacion/borrar_bonificacion',
			type:  'post',
			// beforeSend: function () {
			//         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			// },
			success:  
			function (response) {

				if(response == 1){
					//console.log("entre al true");
					
					swal(mensaje+"Borrado!", "Se ha eliminado el "+mensaje+" .", "success"); 
					
				setTimeout("redireccionarPaginaBonificacion()", 1500);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaBonificacion()", 1200);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}

function modal_borrar_bonificacion_ap (id, url, mensaje)
{
	//alert(id);
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
		$.ajax({
			data:  {"id" : id},
			url:   'http://localhost/codeigniter/bonificacion/borrar_bonificacion',
			type:  'post',
			success:  
			function (response) {
				if(response == 1){
					swal(mensaje+"Borrado!", "Se ha eliminado el "+mensaje+" .", "success"); 
					setTimeout("redireccionarPaginaBonificacionAprobada()", 1500);
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaBonificacionAprobada()", 1200);
					}
				}
			}
		});
	});
}

if (!$('.login-content')[0]) {
 //   notify('Bienvenido de Nuevo', 'inverse',2500);
} 
//./ Welcome Message (not for login page)

$(document).on('ready',function(){

// function cargar_datos()
// {
//     var tiene_codigo = '<?php if(isset($codigo)) echo $codigo; else "false"; ?> ';
//     if(!(tiene_codigo === "false"))
//     {
		
//         $("#inputFacturaAjax").val(tiene_codigo);
//     }
// }

//$('[name = producto]').trigger('chosen:activate')
	 $('[name=telefono]').mask('(000) 000-0000')
	 $('[name=inputCuit]').mask('00-00000000-0')
	 $('[name=celular]').mask('(000) 0 000000')


	//Agrega las clases ACTIVE al Menu
	var enlaceActivo = $(".main-menu li a").hasClass('active');
	var pgurl = window.location.href;
	$(".main-menu li a").each(function(){
		if($(this).attr("href") == pgurl ){
			$(this).parent().parent().parent().addClass("active")
			$(this).addClass("active");
		}
	});
	//./ Agrega las clases ACTIVE al Menu

	

	//Command Buttons
var gridClientes = $("#data-table-clientes").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Cliente",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar('" + row.id + "')\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridClientes.find('.command-edit').on('click',function(){
			location.href="clientes/editar_cliente/"+$(this).data("row-id");
		})/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})
*/

	

	});

	//Command Buttons
var gridClientes = $("#data-table-pago").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Pago",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_pago('" + row.id + "')\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridClientes.find('.command-edit').on('click',function(){
			location.href="clientes/editar_cliente/"+$(this).data("row-id");
		})/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})
*/

	

	});

	//Command Buttons
var gridConexiones = $("#data-table-conexiones").bootgrid({
		//dom: 'Bfrtip',
		// buttons: [
		//     'copyHtml5',
		//     'excelHtml5',
		//     'csvHtml5',
		//     'pdfHtml5'
		// ],

		caseSensitive: false,
		labels: {
				search: "Buscar Conexion",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_conexion('" + row.id + "')\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridConexiones.find('.command-edit').on('click',function(){
			location.href="conexion/editar/"+$(this).data("row-id");
		})/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})
*/

	});



	//Command Buttons
var gridMediciones = $("#data-table-mediciones").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Mediciones",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.sku + "\" onclick=\"modal_modificar_medicion('" + row.sku + "', '" + row.anterior + "','" + row.actual + "','" + row.obs + "', '" + row.excedente + "', '" + row.tipo_conexion + "')\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_medicion('" + row.sku + "' )\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridMediciones.find('.command-edit').end().find(".command-create").on("click", function(){
		   modal_modificar_medicion($(this).data("row-id"), $(this).data("anterior"), $(this).data("actual"), $(this).data("obs"), $(this).data("excedente"));
		})/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})
*/

	});



	//Command Buttons
var gridMediciones = $("#data-table-mediciones-raras").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Mediciones",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return  "<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\" data-anterior=\"" + row.anterior + "\" data-actual=\"" + row.actual + "\" data-tipo_conexion=\"" + row.tipo_conexion + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridMediciones.find('.command-edit').end().find(".command-create").on("click", function(){
		//	alert($(this).data("tipo_conexion"));
		   modal_aprobar_medicion($(this).data("row-id"), $(this).data("anterior"), $(this).data("actual"), $(this).data("tipo_conexion") );
		})


	});



	//Command Buttons
var gridMediciones = $("#data-table-autoriazar-bonificacion").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Autorizacion",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return  "<button type=\"button\" onclick=\"modal_aprobar_medicion_llamado('" + row.sku + "','" + row.Descuento + "','" + row.vencimineto + "','"+row.Vencimiento2 + "','"+row.Pago_Fecha+ "','"+row.Pago_Monto +"','"+row.conexion_id +"')\" class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\" data-todo=\"" + row.vencimineto+ "\" data-Vencimiento2=\"" + row.Vencimiento2 + "\" data-Descuento=\"" + row.Descuento + "\" data-PagoFecha=\"" + row.Pago_Fecha + "\" data-Pago_Monto=\"" + row.Pago_Monto+"\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridMediciones.find('.command-edit').end().find(".command-create").on("click", function(){
			//alert($(this).data("todo"));
			//var arre = [];
			

		})


	});



	//Command Buttons
var gridmedidores = $("#data-table-command").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Medidor",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_medidor('" + row.sku + "')\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridmedidores.find('.command-edit').on('click',function(){
			location.href="inventario/editar_medidor/"+$(this).data("row-id");
		})/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})*/


	

	});


var gridplanpago = $("#data-table-plan_pago").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar plan pago",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_plan_pago('" + row.sku + "')\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridplanpago.find('.command-edit').on('click',function(){
			location.href="plan_pago/editar_plan_pago/"+$(this).data("row-id");
		})/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})*/


	

	});


	//Command Buttons
var gridsuusarios = $("#data-table-usuarios").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Usuarios",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_usuario('" + row.sku + "')\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridsuusarios.find('.command-edit').on('click',function(){
			location.href="usuarios/editar_usuarios/"+$(this).data("row-id");
		})/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})*/


	

	});


	//Command Buttons
var gridtiposmedidores = $("#data-table-tipos_medidores").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Tipo de Medidor",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_generico('" + row.sku + "','tipos_medidores/borrar_tipos_medidores','Tipo de Medidor' )\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridtiposmedidores.find('.command-edit').on('click',function(){
			location.href="tipos_medidores/editar_tipo_medidor/"+$(this).data("row-id");
		})/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})*/


	

	});

var gridbonificacion = $("#data-table-bonificacion").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Usuarios",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_bonificacion('" + row.sku + "','http://localhost/codeigniter/bonificacion/borrar_bonificacion','Bonificacion ' )\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridbonificacion.find('.command-edit').on('click',function(){
			location.href="bonificacion/modificar_bonificacion/"+$(this).data("row-id");
		});/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})*/

	});


var gridbonificacionap = $("#data-table-bonificacion-ap").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Usuarios",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_bonificacion_ap('" + row.sku + "','bonificacion/borrar_bonificacion','Bonificacion ' )\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridbonificacionap.find('.command-edit').on('click',function(){
			location.href="/bonificacion/modificar_bonificacion/"+$(this).data("row-id");
		});/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})*/

	});


var gridmateriales = $("#data-table-materiales").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Materiales",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_materiales('" + row.id + "','bonificacion/borrar_bonificacion','Bonificacion ' )\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridmateriales.find('.command-edit').on('click',function(){
			location.href="materiales/editar_material/"+$(this).data("row-id");
		});/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})*/

	});

var griddeuda = $("#data-table-deuda").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar deuda",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_deuda('" + row.sku + "')\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		griddeuda.find('.command-edit').on('click',function(){
			location.href="deuda/editar_deuda/"+$(this).data("row-id");
		})/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})*/


	

	});

	var gridorden = $("#data-table-orden").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Orden de trabajo",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_orden_trabajo('" + row.id + "')\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				 "<button class=\"btn btn-icon command-print\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-print\"></span></button> "+
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.id + "\" data-row-new=\"" + row.nuevoMedidor + "\" data-row-nconex=\"" + row.nConexion +"\"><span class=\"zmdi zmdi-check\"></span></button>"; 
				//"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridorden.find('.command-edit').on('click',function(){
			location.href="orden_trabajo/editar_orden_trabajo/"+$(this).data("row-id");
		}).end().find(".command-print").on("click", function(){//boton para imprimir
			//  alert("Hola desde imprmir");
			//location.href="imprimir/re_imprimir_orden_trabajo/"+$(this).data("row-id");
			var a = document.createElement("a");
			a.target = "_blank";
			a.href = 'http://localhost/codeigniter/imprimir/re_imprimir_orden_trabajo'+"/"+$(this).data("row-id");
			a.click();
		}).end().find(".command-create").on("click", function(){
			var id = $(this).data("row-id");
			var newMedidor = $(this).data("row-new");
			var nConexion = $(this).data("row-nconex");
			swal({   
				title: "La tarea esta completa?",   
				text: "Se marcará como terminada!",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Si, Terminar!",
				cancelButtonText: 'Cancelar',   
				closeOnConfirm: false
				//*****************************************************************************/
				//********aca se debe preguntar por el newMedidor y habilitar la conexion *****/
				/******mejor hacerlo en orden_trabajo/terminar_tarea************************* */
				//*****************************************************************************/
				}, function(){  
					$.ajax({
						data:  {"id" : id, "nuevo" : newMedidor, "nConexion" : nConexion},
						url:   'orden_trabajo/terminar_tarea',
						type:  'post',
						success:  
						function (response) {
							if(response == 1){
								console.log("entre al true");
								swal("Tarea Terminada!", "Se ha eliminado la tarea.", "success"); 
								setTimeout("redireccionarOrdenTrabajo()", 1500);
							}else{
								if(response == false){
									alert ("volvio un false");
									console.log("entre al faslse");
									setTimeout("redireccionarOrdenTrabajo()", 1200);
										}
									}
								}
							});
						});
			 })


	

	});

var gridMovimientoIngreso = $("#data-table-movimientos").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar movimiento",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\" onclick=\"modal_borrar_mov_ingreso('" + row.sku + "')\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.ruc + "\"><span class=\"zmdi zmdi-delete\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\" ><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridMovimientoIngreso.find('.command-edit').on('click',function(){
			alert("hola");
		}).end().find(".command-create").on("click", function(){//boton para imprimir
			//alert("Hola");
		  $("#detalles_movimiento_modal").modal("show");
			cargar_datos_en_modal_movimiento($(this).data("row-id"));
		});
	});




	  //Command Buttons
var gridsconfig = $("#data-table-configuracion").bootgrid({
		caseSensitive: false,
		labels: {
				search: "Buscar Variable",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button type=\"button\"  class=\"btn btn-icon command-create\" data-row-id=\"" + row.sku + "\"><span class=\"zmdi zmdi-check\"></span></button>";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		gridsconfig.find('.command-edit').on('click',function(){
			$("#configuracion_model").modal("show");
			$("#id_variable_modificando").val($(this).data("row-id"));
			
			//location.href="configuracion/editar_variable/"+$(this).data("row-id");
		})/*.end().find(".command-delete").on("click", function(){//boton para imprimir
			alert("Hola");
		})*/


	

	});
   

   

	//Comandos para Tabla Documentos
	//Command Buttons
var grid = $("#data-table-command-docs").bootgrid({
		caseSensitive: false,
		rowCount: 15,
		labels: {
				search: "Buscar",
				infos: "Mostrando {{ctx.start}} a {{ctx.end}} de {{ctx.total}} elementos",
				all: "Todos",
				noResults: "No se encontraron resultados"
		},
		css: {
			icon: 'zmdi icon',
			iconColumns: 'zmdi-view-module',
			iconDown: 'zmdi-caret-down',
			iconRefresh: 'zmdi-refresh',
			iconUp: 'zmdi-caret-up'
		},
		formatters: {
			"comandos": function(column, row) {
				return "<button class=\"btn btn-icon command-edit\" data-toggle=\"modal\" data-target=\"#modalWider\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
				"<button class=\"btn btn-icon command-delete"+row.comandos+"\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-block\"></span></button> " + 
				"<button class=\"btn btn-icon command-view\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-eye\"></span></button> " +
				"<button class=\"btn btn-icon command-download\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-case-download \"></span></button> " +
				"<button class=\"btn btn-icon command-print\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-print\"></span></button> ";
			  }
		}
	}).on("loaded.rs.jquery.bootgrid", function(){
		grid.find(".command-edit").on("click", function(){//Boton para editar Documento
			location.href="editar/documento/"+$(this).data("row-id");
		}).end().find(".command-print").on("click", function(){//boton para imprimir
			location.href="imprimir/factura/"+$(this).data("row-id");
		}).end().find(".command-download").on("click",function(){//Boton de Vista Previa
		   //alert("se descarga el archivo, de la boleta:"+$(this).data("row-id"));
			 window.location = 'http://localhost/codeigniter/pago/ver_codigo/'+$(this).data("row-id");
		}).end().find(".command-view").on("click",function(){//Boton de Vista Previa
			var url = "preview/documento/"+$(this).data("row-id");

			$('#modalWider').on('show.bs.modal', function () {
				$('#modalWider iframe').attr("src", url);
				$('#modalWider a').attr("href",url);
			});
			$('#modalWider').modal({show:true});
		}).end().find(".command-delete").on("click", function(){
			var btn=$(this);
			$.ajax({
			  url: 'facturar/anular_doc'
	,		  type: 'POST',
			  async: true,
			  data: 'id=' + $(this).attr("data-row-id")+'&estado=0',
			  success: function(){
				//si la clase command-delete existe
				//la elimina y agrega la nueva
				if (btn.hasClass("command-delete")==true) {
					btn.removeClass("command-delete");
					btn.addClass("command-delete-active");
				};
			  },
			  error: function(){
				alert("Hubo un error enviando la petición al servidor, contactar al administrador")
			  }
			});
		}).end().find(".command-delete-active").on("click",function(){//Boton de Anular Documento
			var btn=$(this);
			$.ajax({
			  url: 'facturar/anular_doc',
			  type: 'POST',
			  async: true,
			  data: 'id=' + $(this).attr("data-row-id")+'&estado=1',
			  success: function(){
				//si la clase command-delete-active existe
				//la elimina y agrega la nueva
				if (btn.hasClass("command-delete-active")==true) {
					btn.removeClass("command-delete-active");
					btn.addClass("command-delete");
				};
			  },
			  error: function(){
				alert("Hubo un error enviando la petición al servidor, contactar al administrador")
			  }
			});
		});
	});

	$("#data-table-command-docs tbody").on("click","button.command-delete",function(){//anular documentoss
			var btn=$(this);
			$.ajax({
			  url: 'facturar/anular_doc',
			  type: 'POST',
			  async: true,
			  data: 'id=' + $(this).attr("data-row-id")+'&estado=0',
			  success: function(){
				//si la clase command-delete existe
				//la elimina y agrega la nueva
				if (btn.hasClass("command-delete")==true) {
					btn.removeClass("command-delete");
					btn.addClass("command-delete-active");
				};
			  },
			  error: function(){
				alert("Hubo un error enviando la petición al servidor, contactar al administrador")
			  }
			});
		});
		$("#data-table-clientes tbody").on("click","button.command-delete-active",function(){
			var btn=$(this);
			$.ajax({
			  url: 'facturar/anular_doc',
			  type: 'POST',
			  async: true,
			  data: 'id=' + $(this).attr("data-row-id")+'&estado=1',
			  success: function(){
				//si la clase command-delete-active existe
				//la elimina y agrega la nueva
				if (btn.hasClass("command-delete-active")==true) {
					btn.removeClass("command-delete-active");
					btn.addClass("command-delete");
				};
			  },
			  error: function(){
				alert("Hubo un error enviando la petición al servidor, contactar al administrador")
			  }
			});
		});
	// ./ Comandos para Tabla Documentos
	$("#inputProducto").on("change",function(){
		
		var datos = ($("#inputProducto").val()).split('-');
		$("#inputPrecioUnidad").val(datos[1]);
		$("#inputCantidad").attr("autofocus","autofocus");

		$("[name = inputCantidadname]").attr("autofocus","autofocus");
		$("[name = inputCantidadname]").focus();
		});
	$("#enreparacion").on("change",function(){
		if(document.getElementById('enreparacion').checked)
			$("#rep_oculto").val("true");
		else $("#rep_oculto").val("false");
		});
	$("#hab_medidor").on("change",function(){
		if(document.getElementById('hab_medidor').checked)
			$("#hab_oculto").val("true");
		else $("#hab_oculto").val("false");
		});
	$("#inputTipoPago").on("change",function(){
		var valor = $("#inputTipoPago").val();
		if(valor == 2) //pago parcial
			$("#div_Montodiferente").show("1000");
		else  //pago toal
		{
			$("#div_Montodiferente").hide("1000");
			$("#inputendeuda").val('');
			$("#inputMontoModif").val('');
			$("#monto_pagar_con_bonificacion").val(0);
			var algo  = $("#inputMonto").val();
			$("[name=total]").val(algo);

		}
		});

//habilitaciones de habilitacion
$("#input_hab_1").on("change",function(){
	if(document.getElementById('input_hab_1').checked)
		$("#rep_oculto_1").val("1");
	else $("#rep_oculto_1").val("0");
});
$("#input_hab_2").on("change",function(){
	if(document.getElementById('input_hab_2').checked)
		$("#rep_oculto_2").val("1");
	else $("#rep_oculto_2").val("0");
});
$("#input_hab_3").on("change",function(){
	if(document.getElementById('input_hab_3').checked)
		$("#rep_oculto_3").val("1");
	else $("#rep_oculto_3").val("0");
});
$("#input_hab_4").on("change",function(){
	if(document.getElementById('input_hab_4').checked)
		$("#rep_oculto_4").val("1");
	else $("#rep_oculto_4").val("0");
});
$("#input_hab_5").on("change",function(){
	if(document.getElementById('input_hab_5').checked)
		$("#rep_oculto_5").val("1");
	else $("#rep_oculto_5").val("0");
});
$("#input_hab_6").on("change",function(){
	if(document.getElementById('input_hab_6').checked)
		$("#rep_oculto_6").val("1");
	else $("#rep_oculto_6").val("0");
});
$("#input_hab_7").on("change",function(){
	if(document.getElementById('input_hab_7').checked)
		$("#rep_oculto_7").val("1");
	else $("#rep_oculto_7").val("0");
});
$("#input_hab_8").on("change",function(){
	if(document.getElementById('input_hab_8').checked)
		$("#rep_oculto_8").val("1");
	else $("#rep_oculto_8").val("0");
});
$("#input_hab_9").on("change",function(){
	if(document.getElementById('input_hab_9').checked)
		$("#rep_oculto_9").val("1");
	else $("#rep_oculto_9").val("0");
});


$("#agregar_bonificacion").on("click",function(){
		$("#myModal_Bonificacion").modal('show');
		 $.ajax({
			  url: 'http://localhost/codeigniter/pago/llenar_modal_bonificacion_nuevo',
			  type: 'POST',
			  async: true,
			  success: function( response){
				if (response != null) 
					{
						$("#nueva_bonificacion_div").html(response);
						$("#monto_descontado_por_bonificacion").val(0);
						$("#monto_pagar_con_bonificacion").val($('[name=inputtotal]').val());
						$("#monto_despues_bonificacion").val($('[name=inputtotal]').val());
						$("#hay_bonificacion_form").val(0);
					}
				else
					alert("Error");
			  },
			  error: function(){
				alert("Hubo un error enviando la petición al servidor, contactar al administrador")
			  }
			});

	});


$("[name = optionsRadios]").on("change",function(){
	var valor_tarjeta =  $("#pago_tarjeta").prop('checked');
	var pago_contado =  $("#pago_contado").prop('checked');
	var pago_mixto =  $("#pago_mixto").prop('checked');
	//alert("Tarjeta: "+valor_tarjeta+" - Contado: "+pago_contado+ " - Mixto:"+pago_mixto );
	if(pago_mixto)
	{
		$("#mixto_div").show('1500');
		$("#optionsRadios_hiden").val('2');
		
	}
	else 
	{
		$("#mixto_div").hide('1500');
		if(pago_contado)
			$("#optionsRadios_hiden").val('1');
		else $("#optionsRadios_hiden").val('2');
	}

});
$("#Modificar_bonificacion_btn").on("click",function(){
	var porcen = $("#procentaje_bonificacion_agregar").val();
	$("#myModal_Bonificacion").modal('show');
	if((porcen!=0 ) && (porcen!=null ) && (porcen!='')) //significa que hay un porcentaje
	{
		$("#monto_a_bonificacion_agregar").val('');
	}
	else //monto numerico
	{
	   $('#procentaje_bonificacion_agregar option:eq(0)').prop('selected', true);

	}
		

/*
		$("#porcentaje_bonificacion_form_div").hide('1000');
		$("#monto_a_descontar_form_div").hide('1000');
		$("#monto_descontado_form_div").hide('1000');

		$("#monto_descontado_por_bonificacion").val('');
		$("#monto_pagar_con_bonificacion").val('');
		$("#monto_despues_bonificacion").val('');
		$("#hay_bonificacion_form").val(0);

		$("#datos_bonificacion").hide('800');
		$("#Borrar_bonificacion_btn").hide('800'); 
		$("#Modificar_bonificacion_btn").hide('800');   
		$('#agregar_bonificacion').show("1500");
*/

	});

$("#Borrar_bonificacion_btn").on("click",function(){
		$("#monto_despues_bonificacion").val('');
		$("#procentaje_bonificacion_agregar").val('');
		$("#inputbonificacion").val('');
		
		if(  ( $("#inputMontoModif").val() == 0 ) || ( $("#inputMontoModif").val() == null ) )
			$("#inputtotal").val($("#total_sin_cambios").val());
		else $("#inputtotal").val($("#inputMontoModif").val());
		//volver los valores como estaban antes
		
		$("#monto_descontado_form").val('');
		$("#porcentaje_bonificacion_form").val('');
		$("#monto_a_descontar_form").val('');


		$("#porcentaje_bonificacion_form_div").hide('1000');
		$("#monto_a_descontar_form_div").hide('1000');
		$("#monto_descontado_form_div").hide('1000');

		$("#monto_descontado_por_bonificacion").val('');
		$("#monto_pagar_con_bonificacion").val('');
		$("#monto_despues_bonificacion").val('');
		$("#hay_bonificacion_form").val(0);

		$("#datos_bonificacion").hide('800');
		$("#Borrar_bonificacion_btn").hide('800'); 
		$("#Modificar_bonificacion_btn").hide('800');   
		$('#agregar_bonificacion').show("1500");

	});

	$("#inputMActual").on("change",function(){
		var actual = $("#inputMActual").val();
		var anteriror = $("#inputMAnterior").val();
		if( parseInt(actual) >=  parseInt(anteriror))
		{
			var total = 0;
			if($('#inputTipo').val() == "1")
				total = parseInt(actual) - parseInt(anteriror) - 10;
			else
				total = parseInt(actual) - parseInt(anteriror) - 15;
			if (total<0) total = 0;
			$("#inputExcedente").val(total);
		}
		else
		{
			alert("Valor Actuales inferior al anteriror, corrigalo por favor");
			$("#inputMActual").focus();  
		} 
	});

	 $("#mas_datos_boleta").on("click",function(){
		$("#mas_datos").show('1500');
		$("#menos_datos_boleta").show('1500');
		$("#mas_datos_boleta").hide('1500');
	});

	  $("#menos_datos_boleta").on("click",function(){
		$("#mas_datos").hide('1500');
		$("#menos_datos_boleta").hide('1500');
		$("#mas_datos_boleta").show('1500');
	});

	$("#pagar_boleta").on("click",function(){
		//llamo a la funcion que se encarga de validar los campos
		//var datos_validos = validar_datos();
		/*LEO las varlarbles para poder pasar a su formato correcto*/
		var inputMontoModifjs= $("#inputMontoModif").val();
		//alert("monto:"+inputMontoModifjs);
		if ((inputMontoModifjs != null)&&(inputMontoModifjs != "")&&(inputMontoModifjs != 0)&&(inputMontoModifjs != " "))
			inputMontoModifjs = inputMontoModifjs.replace(",", ".");
		else inputMontoModifjs = 0;
		inputMontoModifjs = parseFloat(inputMontoModifjs);
		//alert(inputMontoModifjs );

		var total = $('[name=total]').val();
		//alert(total);
		if ((total != null)&&(total != "")&&(total != 0)&&(total != " "))
			total = total.replace(",", ".");
		else total = parseFloat(0);
		total = parseFloat(total);
	//	alert(total);

		var total_sin_cambiosjs = $("#total_sin_cambios").val();
		if ((total_sin_cambiosjs != null)&&(total_sin_cambiosjs != "")&&(total_sin_cambiosjs != 0)&&(total_sin_cambiosjs != " "))
			total_sin_cambiosjs = total_sin_cambiosjs.replace(",", ".");
		else total_sin_cambiosjs = parseFloat(0);
		total_sin_cambiosjs = parseFloat(total_sin_cambiosjs);

		
		var monto_a_bonificacion_agregarjs =  $("#monto_a_bonificacion_agregar").val();
		if ((monto_a_bonificacion_agregarjs != null)&&(monto_a_bonificacion_agregarjs != "")&&(monto_a_bonificacion_agregarjs != 0)&&(monto_a_bonificacion_agregarjs != " "))
			monto_a_bonificacion_agregarjs = monto_a_bonificacion_agregarjs.replace(",", ".");
		else monto_a_bonificacion_agregarjs = parseFloat(0);
		monto_a_bonificacion_agregarjs = parseFloat(monto_a_bonificacion_agregarjs);

		var endeudajs =  $("#inputendeuda").val();
		if ((endeudajs != null)&&(endeudajs != "")&&(endeudajs != 0)&&(endeudajs != " "))
			endeudajs = endeudajs.replace(",", ".");
		else endeudajs = parseFloat(0);
		endeudajs = parseFloat(endeudajs);

		var bonificacion_hecha =  $("#inputbonificacion").val();



		 var  solicitud_bonif = $('[name=rep_oculto]').val();
		 var  solicitud_planp = $('[name=hab__oculto]').val();


		 var cant_cuota_pp = $("#inputCantidadCuotas_agregarPlanPago").val();
		 var fecha_js_nueva = $("#fechaalgo").val();

	//	 alert(fecha_js_nueva);
		 // alert("los valores son: id factura:"+$("#inputFacturaOculta").val()+"  /id factura ajax:"+$("#inputFacturaAjax").val()+
		 //     "  /inputMontoModif:"+$("#inputMontoModif").val()+
		 //     "  /inputTipoPago:"+$("#inputTipoPago").val()+
		 //     "  /enreparacion:"+$("#enreparacion").val()+
		 //     "  /monto_a_bonificacion_agregar:"+$("#monto_a_bonificacion_agregar").val()+
		 //     "  /procentaje_bonificacion_agregar:"+$("#procentaje_bonificacion_agregar").val()+
		 //     "  /inputendeuda:"+$("#inputendeuda").val()+
		 //     "  /inputIdCliente:"+$("#inputIdCliente").val()+
		 //     "  /total_sin_cambios:"+$("#total_sin_cambios").val()+
		 //     "  /id_agregarPlanPago_ya_creado:"+$("#id_agregarPlanPago_ya_creado").val()+
		 //     "  /planpago:"+$("#inputplanpago_id").val()+
		 //     "  /planmedidorasdasdasd:"+$("#inputplanmedidor_id").val()+
		 //     "  /solicitudBonificacion:"+solicitud_bonif+
		 //     "  /solicitudPlanPago:"+solicitud_planp
		 //  );

		$.ajax({
			//url: '../pago/datos_envios',
			url: 'http://localhost/codeigniter/pago/guardar_pago_nuevo',
			type: 'POST',
			async: true,
			data:{
			inputFacturaOculta: $("#inputFacturaOculta").val(),
			inputFacturaAjax: $("#inputFacturaAjax").val(),
			inputMontoModif: inputMontoModifjs,
			inputtotal:total,
			total_sin_cambios: total_sin_cambiosjs,
			inputTipoPago: $("#inputTipoPago").val(),
			solicitud_bonificacion: $("#enreparacion").val(),
			monto_a_bonificacion_agregar: $("#monto_a_bonificacion_agregar").val(),
			procentaje_bonificacion_agregar: $("#procentaje_bonificacion_agregar").val(),
			endeuda: endeudajs,
			pago_fecha_: fecha_js_nueva,
			bonificacion_hecha : bonificacion_hecha,
			cliente: $("#inputIdCliente").val(),
			
			id_agregarPlanPago_ya_creado: $("#id_agregarPlanPago_ya_creado").val(),
			plan_pago: $("#inputplanpago_id").val(),
			plan_medidor: $("#inputplanmedidor_id").val(),
			cant_cuota_pp:cant_cuota_pp,

			rep_oculto: solicitud_bonif,

			//cantidad_cuotas_planpago: $("#inputplanmedidor_id").val(),
			interes_plapago: $("#inputInteres_agregarPlanPago").val(),
			fecha_inicio_planpago: $("#inputFechaInicio_agregarPlanPago_ya_creado").val(),
			monot_por_cuota_plan_pago: $("#inputMontoCuota_agregarPlanPago").val(),
			observaciones_planpago: $("#inputObservacion_agregarPlanPago").val(),
			hab__oculto: solicitud_planp
			},
			success: function( response){
				$.trim(response);
				response = parseInt(response);
			console.log(response);
	//	alert(response);
			if (response != "false") 
			{
				swal("Pago Agregado!", "Se ha cargado correctamente el pago.", "success");
				setTimeout("redireccionarBoletadeIngreso("+response+")", 1200);
			}
			else
				alert("Error");
			},
			error: function(){
				alert("Hubo un error enviando la petición al servidor, contactar al administrador")
			}
		});
	});
	


	//Comienzo d plan pgo modal



	$("#agregar_PlanMedidor").on("click",function(){
		// $("#myModalPlanMedidor").modal("show");
		// $("#submit_actualizarPlanPago").hide("1500");
		// var deuda =  $("#inputendeuda").val();
		// if(deuda =='' || deuda == null || deuda == 0)
		//     deuda = 0;
		// var conexion =  $("#inputConexionId").val();

		// $.ajax({
		//       url: '../plan_pago/llenar_modal_plan_pago_nuevo',
		//       type: 'POST',
		//       async: true,
		//       data: {id:1, conexion_id:conexion,deuda:deuda},
		//       success: function( response){
		//         if (response != null) 
		//             {
		//                 $("#PlanPagoNuevo").html(response);
		//             }
		//         else
		//             alert("Error");
		//       },
		//       error: function(){
		//         alert("Hubo un error enviando la petición al servidor, contactar al administrador")
		//       }
		//     });
		//     var mi_path = 'http://localhost/codeigniter/plan_pago/cargando'+"/"+deuda+"/"+conexion ;
		//     $("#PlanPagoNuevo").load(mi_path);


		$("#modificar_PlanMedidor").show("1500");
		$("#quitar_PlanMedidor").show("1500");
		$("#datos_plan_pago_ya_creado").show("1500");

		

	});

	  $("#agregar_PlanMedidor_desde_lista").on("click",function(){
		$("#myModalPlanMedidor").modal("show");
		var deuda =  333;
		var conexion =  1;
/*
		$.ajax({
			  url: '../plan_pago/llenar_modal_plan_pago_nuevo',
			  type: 'POST',
			  async: true,
			  data: {id:1, conexion_id:conexion,deuda:deuda},
			  success: function( response){
				if (response != null) 
					{
						$("#PlanPagoNuevo").html(response);
					}
				else
					alert("Error");
			  },
			  error: function(){
				alert("Hubo un error enviando la petición al servidor, contactar al administrador")
			  }
			});*/
			var mi_path = 'http://localhost/codeigniter/plan_pago/cargando_desde_lista'+"/"+0+"/"+0 ;
			$("#PlanPagoNuevo").load(mi_path);
	});
	
$('body').on('click','img',function(){
	
	$.ajax({
		url: 'http://localhost/codeigniter/home/mostrar_picker',
		type: 'POST',
		async: true,
		success: function( response){
			console.log(response);
			if (response != "false") 
			{
				$("#picker_contenido").html(response);
				$("#image_pick").modal("show");
			}
			else
				   alert("Error");
			},
		error: function(){
			 alert("Hubo un error enviando la petición al servidor, contactar al administrador")
		}
	});
})

  



 });//fin document on ready
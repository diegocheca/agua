<!-- <button id="nuevo_ingreso" name="nuevo_ingreso" type="button">Movimientos</button> -->
<!-- <br>
<br>
<div class="row">
	<button type="button" class="btn btn-float waves-effect waves-effect waves-circle waves-float" style="background-color:#3F6022" id="nuevo_reporte" name="nuevo_reporte"><i class="zmdi zmdi-minus"></i></button>	
</div>
<br><br>
 -->
 <div class="modal fade" id="reporte_ingreso_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">        <div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title" id="myModalLabel">Creando Reporte</h4>
		</div>
		<div class="modal-body">
			<div class="row"  >
				<div class="row" id="div_acuenta">
					<div class="col-md-6">
						<label>Inicio :</label>
						<input type="date" name="myDate" id="inicio_reporte_pagos" min="2018-02-02">
					</div>
					<div class="col-md-6">
						<label>Fin:</label>
						<input type="date" name="myDate" id="fin_reporte_pagos" max="<?php echo date("Y-m-d"); ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button"  class="btn btn-warning" data-dismiss="modal">Cancelar</button>
			<button type="button"  class="btn btn-success" id="crear_reporte_fechas" name="crear_reporte_fechas"> Buscar</button>
			<button type="button"  class="btn btn-primary" id="crear_reporte_fechas_dos" name="crear_reporte_fechas_dos"> Buscar</button>
		</div>

	</div>
</div>
	</div>
<script type="text/javascript">
	$(document).on('ready',function(){
		$("#nuevo_reporte").on("click",function(){
			$("#reporte_ingreso_modal").modal('toggle');
		});
		$("#crear_reporte_fechas").on("click",function(){
			var inicio =  $("#inicio_reporte_pagos").val();
			var fin =  $("#fin_reporte_pagos").val();
			var a = document.createElement("a");
			a.target = "_blank";
			a.href = 'http://localhost/codeigniter/reportes/reporte_pagos_fechas'+"/"+inicio+"/"+fin;
			a.click();
			//alert("Inicio:"+inicio+" - fin:"+fin);
			// $.ajax({
			// 	url: 'http://localhost/codeigniter/reportes/reporte_pagos_fechas',
			// 	type: 'POST',
			// 	async: true,
			// 	data:{
			// 		"fin": fin,
			// 		"inicio": inicio
			// 	},
			// 	success: function( response){
			// 		// $.trim(response);
			// 		// if(response != "")
			// 		// 	response = parseInt(response);
			// 		// else response=0;
			// 		console.log(response);
			// 			// $("#acuenta_actual").val(response);
			// 			// //$("#monto_ingreso").val(0);
			// 			// $("#saldo_cuenta").val($("#saldo_cuenta").val() - $("#acuenta_actual").val() );
						
			// 		},
			// 	error: function(){
			// 		alert("Hubo un error enviando la petición al servidor, contactar al administrador")
			// 	}
			// });
		});
		$("#crear_reporte_fechas_dos").on("click",function(){
			var inicio =  $("#inicio_reporte_pagos").val();
			var fin =  $("#fin_reporte_pagos").val();
			var a = document.createElement("a");
			a.target = "_blank";
			a.href = 'http://localhost/codeigniter/imprimir/movimientos_diarios'+"/"+inicio+"/"+fin;
			a.click();
		});
	});
</script>
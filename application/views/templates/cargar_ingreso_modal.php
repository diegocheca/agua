<!-- <button id="nuevo_ingreso" name="nuevo_ingreso" type="button">Movimientos</button> -->
 <div class="modal fade" id="nuevo_ingreso_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">        <div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title" id="myModalLabel">Agregando un Movimiento de Dinero</h4>
		</div>
		<div class="modal-body">
			<div class="row"  id="nueva_tarea_div">
				<div class="row">
					<div class="col-md-8">
						<label for="tipo_ingreso">Tipo de movimiento:</label>
						<select id="tipo_egreso" type="text" name="tipo_egreso"  class="chosen" >
							<option value="-1" selected disabled="" >Seleccione</option>
							<option value="1" > Ingreso </option>
							<option value="2" > Egreso</option>
							<option value="4" > Pago a cuenta o atrasado</option>
						</select>
					</div>
				</div>
				<br></br>
				<input type="hidden" name="id_evento_nuevo" id="id_evento_nuevo" value="-1" >
				<div class="row">
					<div class="col-md-6">
						<label for="monto_ingreso">Monto</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
							<div class="fg-line">
								<input id="monto_ingreso" type="number" min="0" max="5000" value="0" name="monto_ingreso" class="form-control input-sm"  >
							</div>
						</div>
					</div>
					<div class="col-md-6"  id="div_descripcion">
						<label for="codigo_ingreso">Codigo:</label>
						<select id="codigo_ingreso" type="text" name="codigo_ingreso"  class="chosen" >
							<option value="0" selected disabled>Seleccione</option>
							<?php
							foreach ($codigos as $key) {
								echo  '<option value="'.$key->Codigo_Id.'" > N: '.$key->Codigo_Numero."  D:". $key->Codigo_Descripcion.'</option>';
							}
							 ?>
							<option value="3" > Egreso</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<label for="descripcion_ingreso">Descripcion</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-code-setting"></i></span>
							<div class="fg-line">
								<input id="descripcion_ingreso" type="text" name="descripcion_ingreso" class="form-control input-sm" maxlength="90"  >
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<label for="a_nombre">A Nombre de:</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-code-setting"></i></span>
							<div class="fg-line">
								<input id="a_nombre" type="text" name="a_nombre" class="form-control input-sm" maxlength="25"  >
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="div_acuenta" style="display:none">
					<label for="select_material">Datos de la persona:</label>
					<select name="datos_personales_para_acuenta"  id="datos_personales_para_acuenta" class="chosen" data-placeholder="Seleecinar Datos" >
						<option value="-1" selected disabled>Seleccionar</option>
						<?php
						foreach ($conexiones_a_imprimir_conexion as $key ) 
						{
							echo '<option value="'.$key->Conexion_Id.'*'.$key->Cli_Id.'">Nombre: '.$key->Cli_RazonSocial.' DNI: '.$key->Cli_NroDocumento.' N°Conexion: '.$key->Conexion_Id.'</option>';
						} 
						?>
					</select>
					<div class="col-md-8">
						<label for="acuenta_actual">Deuda actual</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-code-setting"></i></span>
							<div class="fg-line">
								<input id="acuenta_actual" type="number" name="acuenta_actual" class="form-control input-sm" readonly=""  >
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<label for="saldo_cuenta">Saldo a favor cliente</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-code-setting"></i></span>
							<div class="fg-line">
								<input id="saldo_cuenta" type="number" name="saldo_cuenta" class="form-control input-sm" value="0" readonly=""  >
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xs-6">
					<label for="Fecha_de_Pago">Fecha de Pago</label>
					<div class="input-group form-group">
						<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
						<div class="dtp-container dropdown fg-line open">
							<input id="Fecha_de_Pago" type="text" name="Fecha_de_Pago" class="form-control input-sm date-picker" data-toggle="dropdown" required>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button"  class="btn btn-warning" data-dismiss="modal">Cancelar</button>
			<button type="button"  class="btn btn-success" id="guadar_ingreso_nuevo_modal" name="guadar_ingreso_nuevo_modal"> Guardar</button>
		</div>

	</div>
</div>
	</div>
<script type="text/javascript">
	$(document).on('ready',function(){
		$("#tipo_egreso").on("change",function(){
			var valor_elegido =  $("#tipo_egreso").val();
			if(valor_elegido == 4) // tipo de pago acuenta
			{
				$('#datos_personales_para_acuenta').prop('disabled', false);
				$("#div_acuenta").show("1500");
				$("#div_descripcion").hide("1500");
				
				
				//alert("valor: "+valor_elegido);
			}
			else
			{
				$("#div_acuenta").hide("1500");
				$("#div_descripcion").show("1500");
			}
				

		});
		$("#datos_personales_para_acuenta").on("change",function(){
			var conexion_elegida =  $("#datos_personales_para_acuenta").val();
			var arreglo = conexion_elegida.split("*");
		//	alert(arreglo[0]);
			$.ajax({
				url: 'http://localhost/codeigniter/conexion/buscar_deuda_conexion',
				type: 'POST',
				async: true,
				data:{
					id_conexion: arreglo[0],
				},
				success: function( response){
					alert(response);
					if(response != "")
						response = parseInt(response);
					else response=0;
					console.log(response);
						$("#acuenta_actual").val(response);
						$("#saldo_cuenta").val($("#saldo_cuenta").val() - $("#acuenta_actual").val() );
						
					},
				error: function(){
					alert("Hubo un error enviando la petición al servidor, contactar al administrador")
				}
			});
			
		});
		$("#monto_ingreso").on("change",function(){
			if( (parseInt($("#monto_ingreso").val()) == 0) || (parseInt($("#monto_ingreso").val()) == NaN )  || ($("#monto_ingreso").val() == ''))
			{//cereo todo
				$("#saldo_cuenta").val("0");
			}
			else
				$("#saldo_cuenta").val($("#monto_ingreso").val() - $("#acuenta_actual").val() );
		});




				

	});
</script>
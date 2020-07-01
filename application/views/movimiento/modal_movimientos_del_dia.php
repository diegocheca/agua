<div class="modal fade" id="nueva_tadasdasrea_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Agregando una Tarea</h4>
			</div>
			<div class="modal-body">
				<div class="row"  id="nueva_tarea_div">
					<div class="row">
						<div class="col-md-6">
							<label for="bonificacion">Persona que la realizara:</label>
							<div class="fg-line select">
								<select id="select_persona_raliza_tarea" type="text" name="select_persona_raliza_tarea" class="form-control input-sm" required>
									<option value="1" selected>Juancito</option>
									<option value="2">Marisa</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<label for="monto_pagar_con_bonificacion">Comienzo:</label>
							<div class="dtp-container dropdown fg-line open">
								<input id="inputDate" type="text" name="fechaEmision" class="form-control input-sm date-picker" value="<?php
								$fecha = date('Y-m-j');
								$nuevafecha = strtotime ( '+10 day' , strtotime ( $fecha ) ) ;
								$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
								 
								echo $nuevafecha;
								 ?>"  data-toggle="dropdown" >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="inputConexionId">Materiales</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<div class="fg-line">
									<input id="materiales_tarea" type="text" name="materiales_tarea" class="form-control input-sm"  >    
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputConexionId">Aclaracion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<div class="fg-line">
									<input id="materiales_tarea" type="text" name="materiales_tarea" class="form-control input-sm"  >    
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="bonificacion">Duracion estimada:</label>
							<div class="fg-line select">
								<select id="select_persona_raliza_tarea" type="text" name="select_persona_raliza_tarea" class="form-control input-sm" required>
									<option value="1" selected>5 dias</option>
									<option value="1" selected>3 dias</option>
									<option value="1" selected>1 dias</option>
									<option value="1" selected>5 hora</option>
									<option value="1" selected>3 hora</option>
									<option value="1" selected>1 hora</option>
									
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<!-- selección múltiple -->
							<select name="miselect[]" id="select_material" class="chosen" data-placeholder="Elige tus colores favoritos" style="width:90%;display:block" multiple[] >
								<option value="azul">Azul</option>
								<option value="amarillo">Amarillo</option>
								<option value="blanco">Blanco</option>
								<option value="gris">Gris</option>
								<option value="marron">Marrón</option>
								<option value="naranja">Naranja</option>
								<option value="negro">Negro</option>
								<option value="rojo">Rojo</option>
								<option value="verde">Verde</option>
								<option value="violeta">Violeta</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button"  class="btn btn-primary" data-dismiss="modal">Agregar</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$("#bucardor_de_balance").click(function(){
	var a = document.createElement("a");
	a.target = "_blank";
	a.href = 'imprimir/movimientos_diarios';
	a.click();
	window.location = '';
});
</script>
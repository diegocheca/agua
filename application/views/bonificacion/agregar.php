	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-6">
			<div class="card-header">
				<h2>Aprobar Bonificacion</h2>
			</div>
			<div class="card-body card-padding">
						
				<form  id ="form_bonificacion" action="<?php 
				echo base_url();
				if(isset($bonificacion))
					echo "bonificacion/guardar_agregar";
				else echo "bonificacion/guardar_agregar";
				?>" method="post" class="">
					<div class="row">
						<div class="col-md-6">
							<label for="inputNombreUsuario">Nombre Usuario</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputNombreUsuario" placeholder="Nombre Usuario" type="text" maxlength="200" name="inputNombreUsuario" class="form-control input-sm" 
									<?php 
									if(isset($bonificacion))
										echo  'value= "'.$bonificacion->Cli_RazonSocial.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputConexionId">Conexion ID</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputConexionId" placeholder="Conexion id" type="text"  name="inputConexionId" class="form-control input-sm" required
									<?php 
									if(isset($bonificacion))
										echo  'value= "'.$bonificacion->Conexion_Id.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputMonto">Monto Deuda</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputMonto" type="text"  name="inputMonto"  class="form-control input-sm" 
								 	<?php 
									if(isset($bonificacion))
										echo  'value= "'.$bonificacion->Conexion_Deuda.'"';
									 ?> 
									 >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="inputMontoBonificado">Monto a bonificar</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
								<div class="fg-line">
									<input id="inputMontoBonificado" name="inputMontoBonificado" type="number" min="1" max="10000" step=any class="form-control input-sm input-mask"
									<?php  
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Monto.'"';
									 ?>
									 requierd>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputPorcentajeBonificado">Porcentaje de bonificacion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputPorcentajeBonificado" name="inputPorcentajeBonificado" class="form-control input-sm" type="number" min="1" max="48" step="1"
									<?php 
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Porcentaje.'"';
									 ?>
									>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="inputObservacion">Observacion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<input id="inputObservacion" type="text" maxlength="200" name="inputObservacion" name="inputObservacion" class="form-control input-sm"
									<?php  
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Observacion.'"';
									 ?>
									 >
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="id" id="id" value="<?php if(isset($bonificacion)) echo $bonificacion->Bonificacion_Id; else echo "-1";  ?>" style="display: none">
					<div class="row">
						<div class="col-md-4">
							<button type="submit" name="enviar" style="width:100%" class="btn btn-success">Guardar</button>
						</div>
						<div class="col-md-4">
							<button type="reset"  style="width:100%" class="btn btn-warning">Cancelar</button>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("bonificacion");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<script src="<?php echo base_url();?>js/validations/validations_agregar_bonificacion.js"></script>

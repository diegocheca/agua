	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-6">
			<div class="card-header">
				<h2>Agregar Orden de Trabajo</h2>
			</div>
			<div class="card-body card-padding">
						
				<form action="<?php 
				echo base_url();
				if(isset($orden))
					echo "orden_trabajo/guardar_agregar";
				else echo "orden_trabajo/guardar_agregar";
				 ?>" method="post" class="">
					<div class="row">
						<div class="col-md-12">
							<label for="inputTarea">Tarea</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputTarea" placeholder="Tarea" type="text" maxlength="200" name="inputTarea" class="form-control input-sm" required
									<?php 
									if(isset($bonificacion))
										echo  'value= "'.$bonificacion->Bonificacion_Factura_Id.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputNombreUsuario">Nombre Usuario</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputNombreUsuario" placeholder="Nombre Usuario" type="text" maxlength="200" name="inputNombreUsuario" class="form-control input-sm" required
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
								<input id="inputConexionId" placeholder="Conexion id" type="text" name="inputConexionId" class="form-control input-sm" required
									<?php 
									if(isset($bonificacion))
										echo  'value= "'.$bonificacion->Conexion_Id.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-12">
							<label for="inputDireccion">Direccion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputDireccion" type="text"  name="inputDireccion" class="form-control input-sm" 
								 	<?php 
									if(isset($bonificacion))
										echo  'value= "'.$bonificacion->Factura_Total.'"';
									 ?> 
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputTecnico">Tecnico asigando</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputTecnico" type="text"  name="inputTecnico" class="form-control input-sm" 
								 	<?php 
									if(isset($bonificacion))
										echo  'value= "'.$bonificacion->Factura_Total.'"';
									 ?> 
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputFecha">Fecha</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputFecha" type="text"  name="inputFecha" class="form-control input-sm" 
								 	<?php 
									if(isset($bonificacion))
										echo  'value= "'.$bonificacion->Factura_Total.'"';
									 ?> 
									 >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label for="inputCodigoMaterial1">Codigo 1</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
								<div class="fg-line">
									<input id="inputCodigoMaterial1" type="text" maxlength="200" name="inputCodigoMaterial1" class="form-control input-sm"
									<?php  
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Monto.'"';
									 ?>
									 requierd>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputDescricionMaterial1">Material 1</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputDescricionMaterial1" type="text" maxlength="200" name="inputDescricionMaterial1" class="form-control input-sm"
									<?php 
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Porcentaje.'"';
									 ?>
									>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputCantMaterial1">Cantidad</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputCantMaterial1" name="inputCantMaterial1" type="number" min="1" max="6000" step=any class="form-control input-sm input-mask">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label for="inputCodigoMaterial2">Codigo 2</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
								<div class="fg-line">
									<input id="inputCodigoMaterial2" type="text" maxlength="200" name="inputCodigoMaterial2" class="form-control input-sm"
									<?php  
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Monto.'"';
									 ?>
									 requierd>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputDescricionMaterial2">Material 2</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputDescricionMaterial2" type="text" maxlength="200" name="inputDescricionMaterial2" class="form-control input-sm"
									<?php 
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Porcentaje.'"';
									 ?>
									>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputCantMaterial2">Cantidad</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputCantMaterial2" name="inputCantMaterial2" type="number" min="1" max="6000" step=any class="form-control input-sm input-mask">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label for="inputCodigoMaterial3">Codigo 3</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
								<div class="fg-line">
									<input id="inputCodigoMaterial3" type="text" maxlength="200" name="inputCodigoMaterial3" class="form-control input-sm"
									<?php  
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Monto.'"';
									 ?>
									 requierd>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputDescricionMaterial3">Material 3</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputDescricionMaterial3" type="text" maxlength="200" name="inputDescricionMaterial3" class="form-control input-sm"
									<?php 
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Porcentaje.'"';
									 ?>
									>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputCantMaterial3">Cantidad</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputCantMaterial3" name="inputCantMaterial3" type="number" min="1" max="6000" step=any class="form-control input-sm input-mask">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label for="inputCodigoMaterial4">Codigo 4</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
								<div class="fg-line">
									<input id="inputCodigoMaterial4" type="text" maxlength="200" name="inputCodigoMaterial4" class="form-control input-sm"
									<?php  
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Monto.'"';
									 ?>
									 requierd>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputDescricionMaterial4">Material 4</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputDescricionMaterial4" type="text" maxlength="200" name="inputDescricionMaterial4" class="form-control input-sm"
									<?php 
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Porcentaje.'"';
									 ?>
									>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputCantMaterial4">Cantidad</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputCantMaterial4" name="inputCantMaterial4" type="number" min="1" max="6000" step=any class="form-control input-sm input-mask">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label for="inputCodigoMaterial5">Codigo 5</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
								<div class="fg-line">
									<input id="inputCodigoMaterial5" type="text" maxlength="200" name="inputCodigoMaterial5" class="form-control input-sm"
									<?php  
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Monto.'"';
									 ?>
									 requierd>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputDescricionMaterial5">Material 5</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputDescricionMaterial5" type="text" maxlength="200" name="inputDescricionMaterial5" class="form-control input-sm"
									<?php 
									if(isset($bonificacion))
										echo 'value= "'.$bonificacion->Bonificacion_Porcentaje.'"';
									 ?>
									>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputCantMaterial5">Cantidad</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputCantMaterial5" name="inputCantMaterial5" type="number" min="1" max="6000" step=any class="form-control input-sm input-mask">
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
					<input type="hidden" name="id" id="id" value="<?php if(isset($usuario)) echo $usuario->id; else echo "-1";  ?>" style="display: none">
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


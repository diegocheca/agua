	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-12">
			<div class="card-header">
				<h2>Agregar Nuevo Plan de pago</h2>
			</div>
			<div class="card-body card-padding">
					<div class="row">
						<div class="col-md-6">
							<label for="inputConexionId_agregarPlanPago">Conexion Id</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<div class="fg-line">
									<input id="inputConexionId_agregarPlanPago" type="text" maxlength="10" name="conexionId" class="form-control input-sm" required
									<?php 
									if( isset($conexion_id))
										echo 'value="'.$conexion_id.'" readonly';
									?>
									>
								</div>
							</div>
						</div>

						<input id="id_agregarPlanPago" type='hidden' value="<?php if(isset($id_plan_pago)) echo $id_plan_pago; else echo "-1"; ?>">
						<input id="ismodal_agregarPlanPago" type='hidden' value="<?php if(isset($is_modal)) echo "1"; else "0"; ?>">

						
						<div class="col-md-6">
							<label for="inputMontoTotal_agregarPlanPago">Monto total</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line">
									<input id="inputMontoTotal_agregarPlanPago" type="text" maxlength="10" name="montoTotal" class="form-control input-sm" required
									<?php 
									if(isset($deuda))
										echo 'value= "'.$deuda.'"';
									?>
									>
								</select>
								</div>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-6">
							<label for="inputFechaInicio_agregarPlanPago">Fecha de inicio</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
								<div class="dtp-container dropdown fg-line open">
									<input id="inputFechaInicio_agregarPlanPago" type="text" name="fechaInicio" class="form-control input-sm date-picker" data-toggle="dropdown"
									value="<?php 
									if( isset($fecha_inicio_plan_pago))
									echo $fecha_inicio_plan_pago;
									else 
									{
										$fecha = date('Y-m-d');
										$nuevafecha = strtotime ( '+1 month' , strtotime ( $fecha ) ) ;
										$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
										echo $nuevafecha;
									}

									
									?>">

								</div>
							</div>
						</div>	
						<div class="col-md-6">
							<label for="inputMontoPagado_agregarPlanPago">Monto pagado</label> 
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
								<div class="fg-line">
									<input id="inputMontoPagado_agregarPlanPago" type="text" maxlength="10" name="montoPagado" class="form-control input-sm" required
									value= <?php 
									if(isset($monto_pago))
										echo '"'.$monto_pago.'" readonly>';
										else echo '"0">';  ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="inputCantidadCuotas_agregarPlanPago">Cantidad de cuotas</label> 
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
								<div class="fg-line">
									<input id="inputCantidadCuotas_agregarPlanPago" type="text" maxlength="10" name="cantidadCuotas" class="form-control input-sm" required
									value= <?php 
									if(isset($cantidadCuotas))
										echo '"'.$cantidadCuotas.'">';
									else echo '"">' ?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputMontoCuota_agregarPlanPago">Monto de cuota</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
								<div class="fg-line">
									<input id="inputMontoCuota_agregarPlanPago" readonly type="text" maxlength="10" name="montoCuota" class="form-control input-sm" required
									<?php 
									if(isset($monto_por_cuota))
										echo 'value="'.$monto_por_cuota.'"';
									?>
									>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputInteres_agregarPlanPago">Interes</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
								<div class="fg-line">
									<input id="inputInteres_agregarPlanPago" type="text" maxlength="10" name="interes" class="form-control input-sm" required
									<?php 
									if(isset($interes_por_cuotas))
										echo 'value="'.$interes_por_cuotas.'"';
									else echo 'value="0"';
									?>
									>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label for="inputCuotaActual_agregarPlanPago">Cuota actual</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment-return"></i></span>
								<div class="fg-line">
									<input id="inputCuotaActual_agregarPlanPago" type="text" maxlength="10" name="cuotaActual" class="form-control input-sm" required
									value= <?php 
									if(isset($cuota_actual))
										echo '"'.$cuota_actual.'" readonly>';
									 else echo '"" >';
									 	?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="inputObservacion_agregarPlanPago">Observaciones</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<div class="fg-line">
									<input id="inputObservacion_agregarPlanPago"  type="text" name="inputObservacion" class="form-control input-sm" 
									value=
									<?php 
									if(isset($observaciones_plan_pago))
										echo '"'.$observaciones_plan_pago.'"';
									else echo '""'; ?>
									>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<button style="width:100%" id="submit_agregandoPlanPago" class="btn btn-success">Guardar</button>
							<button style="width:100%" id="submit_actualizarPlanPago" class="btn btn-success" style="display:none">Actualizar</button>
						</div>
						<div class="col-md-4">
						<?php 
						if($is_modal)
							echo '<button type="reset"  data-dismiss="modal" style="width:100%" class="btn btn-danger">Cancelar</button>';
						else 
							{
								echo '<button type="reset"  style="width:100%" class="btn btn-danger">Cancelar</button>
								</div>
								<div class="col-md-4">	
									<a href="<?php echo base_url("inventario");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>';
							}
						?>
						</div>
					</div>
			</div>
		</div>
	
	</div>

	
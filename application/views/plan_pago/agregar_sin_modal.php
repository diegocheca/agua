	<div class="row">
		<div class="card col-md-12">
			<div class="card-header">
				<h2>Agregar Nuevo Plan de pago</h2>
			</div>
			<form id="form_agregar_plan_pago_sin_modal" method="POST" action="<?php
			if(isset($plan_pago))
				 echo base_url("plan_pago/modificar_plan_pago");
			else  echo base_url("plan_pago/insertar_plan_desde_vista");?>" id="formulario">
			<div class="card-body card-padding">
					<div class="row">
						<div class="col-md-6">
							<label for="inputConexionId_planPago">Conexion Id</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<div class="fg-line">
									<input id="inputConexionId_planPago" type="text" maxlength="10" name="inputConexionId_planPago" class="form-control input-sm" required
									<?php 
									if(isset( $plan_pago) )
										echo 'value="'.$plan_pago->PlanPago_Id.'" readonly';
										?>
									>
								</div>
							</div>
						</div>

						<input  id="id_agregarPlanPago" name="id_agregarPlanPago" type='hidden' value="<?php if(isset($plan_pago)) echo $plan_pago->PlanPago_Id; else echo "-1"; ?>">

						
						<div class="col-md-6">
							<label for="inputMontoTotal_agregarPlanPago">Monto total</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line">
									<input id="inputMontoTotal_agregarPlanPago" type="text" maxlength="10" name="montoTotal" class="form-control input-sm"
									<?php 
									if(isset( $plan_pago) )
										echo 'value="'.$plan_pago->PlanPago_MontoTotal.'"';
									else echo 'readonly';
										?>
										>
								</select>
								</div>
							</div>
						</div>


						<div class="col-md-6">
							<label for="inputCliente_plan_pago">Nombre cliente</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputCliente_plan_pago" placeholder="Nombre..." type="text" maxlength="200" name="inputCliente_plan_pago" class="form-control input-sm" 
								<?php 
									if(isset($medicion))
										echo  'value= "'.$medicion->Cli_RazonSocial.'" readonly';
									 ?>
									 >
							</div>
						</div>


						<div class="col-md-6">
							<label for="cliente_razon_social">Cliente total</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line">
									<input id="cliente_razon_social" type="text" name="cliente_razon_social" class="form-control input-sm" readonly>
								</select>
								</div>
							</div>
						</div>


						<div class="col-md-6">
							<label for="cliente_direccion">Direccion total</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line">
									<input id="cliente_direccion" type="text" name="cliente_direccion" class="form-control input-sm" readonly>
								</select>
								</div>
							</div>
						</div>


						<div class="col-md-6">
							<label for="categoria_conexion">Categoria total</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line">
									<input id="categoria_conexion" type="text" name="categoria_conexion" class="form-control input-sm" readonly>
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
									if( isset($plan_pago))
									echo date("d/m/Y", strtotime($plan_pago->PlanPago_FechaInicio));

									else 
									{
										$fecha = date('Y-m-d');
										$nuevafecha = strtotime ( '+1 month' , strtotime ( $fecha ) ) ;
										$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
										echo $nuevafecha;
									}

									
									?>">

								</div>
							</div
						</div>	
						<div class="col-md-6">
							<label for="inputMontoPagado_agregarPlanPago">Monto pagado</label> 
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
								<div class="fg-line">
									<input id="inputMontoPagado_agregarPlanPago" type="text" maxlength="10" name="montoPagado" class="form-control input-sm" 
									<?php 
									if(isset($plan_pago))
										echo 'value= "'.$plan_pago->PlanPago_MontoPagado.'"';
									else echo 'value="0" readonly'; ?>
									>
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
									if(isset($plan_pago))
										echo '"'.$plan_pago->PlanPago_Coutas.'">';
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
									if(isset($plan_pago))
										echo 'value="'.$plan_pago->PlanPago_MontoCuota.'"';
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
									if(isset($plan_pago))
										echo 'value="'.$plan_pago->PlanPago_Interes.'"';
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
									<input id="inputCuotaActual_agregarPlanPago" type="text" maxlength="10" name="cuotaActual" class="form-control input-sm"  
									<?php 
									if(isset($plan_pago))
										echo 'value= "'.$plan_pago->PlanPago_CoutaActual.'"';
									else echo 'value="0" readonly'; ?>
									>
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
									if(isset($plan_pago))
										echo '"'.$plan_pago->PlanPago_Observacion.'"';
									else echo '""'; ?>
									>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<button style="width:100%" type="submit" id="guardarPlanPago" class="btn btn-success">Guardar</button>
						</div>
						<div class="col-md-4">
							<button type="reset" style="width:100%" class="btn btn-warning">Cancelar</button>
						</div>
						<div class="col-md-4">	
							<a href="<?php echo base_url("plan_pago");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
			</div>
			</div>
		</div>
	
	</div>

	<script src="<?php echo base_url();?>js/validations/validations_agregar_plan_pago_sin_modal.js"></script>
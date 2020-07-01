<div class="row" ng-app="formApp"  ng-controller="MainCtrl" >
	<!-- <form id="formulario_chiquitito" method="post" action="<?php echo base_url("pago/guardar_pago_nuevo"); ?>" name="exampleForm"> -->
	<div class="card">
		<div class="card-body card-padding">
			<div class="row">
				<div class="col-md-3 col-xs-12">
					<label for="inputFacturaAjax">Codigo de barra</label>
					<div class="input-group form-group">
						<span class="input-group-addon"><i class="zmdi zmdi-tab"></i></span>
						<div class="fg-line">
							<input id="inputFacturaAjax" type="text" name="inputFacturaAjax" class="form-control input-sm" required>
							<input id="inputFacturaOculta" type="text" name="inputFacturaOculta" style="display:none">
						</div>
					</div>
				</div>
				<div class="col-md-3 col-xs-12">
					<label for="inputDate">Fecha de Emision</label> - <label for="inputDateC">Cancelación</label>
					<div class="input-group form-group">
						<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
						<div class="col-md-6 col-xs-6">
							<div class="dtp-container dropdown fg-line open">
								<input id="inputDate" type="text" name="fechaEmision" class="form-control input-sm date-picker" data-toggle="dropdown" readonly>
							</div>
						</div>
						<div class="col-md-6 col-xs-6">
							<div class="dtp-container dropdown fg-line open">
								<input id="inputDateC" type="text" name="fechaCancelacion" class="form-control input-sm date-picker" data-toggle="dropdown" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2 col-xs-12">
					<label for="inputMoneda">Monto Total</label>
					<div class="input-group form-group">
						<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
						<div class="fg-line">
							<input id="inputMonto" type="text" name="monto" class="form-control input-sm" ng-model="formData.montototal" readonly>
						</div>
					</div>
				</div>
				<div class="col-md-2 col-xs-12">
					<label for="inputConexionId">Conexion</label>
					<div class="input-group form-group">
						<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
						<div class="fg-line">
							<input id="inputConexionId" type="text" name="conexion" class="form-control input-sm"  ng-model="conexion_angular" readonly> 	
						</div>
					</div>
				</div>
			</div>
			<div class="row">
					<div class="col-md-2 col-xs-12">
						<label for="inputTipoPago">Tipo de Pago</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line select">
								<select id="inputTipoPago" type="text" name="inputTipoPago" class="form-control input-sm" required>
									<option value="1" selected>Total</option>
									<option value="2">Parcial</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12">
						<label for="inputDeuda">Deuda de la Conexion</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line">
								<input id="inputDeudaAnterior" type="text" name="deuda" class="form-control input-sm" readonly>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12">
						<label for="inputMes">Mes Factura</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line">
								<input id="inputMes" type="text" name="mes" class="form-control input-sm" readonly>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12">
						<label for="inputAnio">Año Factura</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line">
								<input id="inputAnio" type="text" name="anio" class="form-control input-sm" readonly>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12">
						<label for="inputCategoria">Categoria</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line">
								<input id="inputCategoria" type="text" name="categoria" class="form-control input-sm" readonly>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12"  style="display:none" id="div_Montodiferente">
						<label for="inputMontoModif">Monto A Pagar</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
							<div class="fg-line">
								<input id="inputMontoModif" type="text" name="inputMontoModif" class="form-control input-sm" ng-model="monto_total">
							</div>
						</div>
					</div>
					<button style="display: none" type="button" id="mas_datos_boleta" name="mas_datos_boleta" class="btn btn-success waves-effect">
					<i class="zmdi zmdi-plus"></i>
					</button>
					<button style="display: none" type="button" id="menos_datos_boleta" name="menos_datos_boleta" class="btn btn-danger waves-effect">
					<i class="zmdi zmdi-minus"></i>
					</button>
			</div>
			<div id="mas_datos" style="display:none">
			<hr class="style5">
				<div class="row">
					<div class="col-md-4">
						<label class="control-label" for="inputCliente">Cliente</label>
						<div class=" input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
							<div class="fg-line">
								<input id="inputCliente" type="text" name="cliente" class="form-control input-sm" readonly>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<label class="control-label" for="inputRuc">Id</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
							<div class="fg-line">
								<input id="inputIdCliente" type="text" name="inputIdCliente" class="form-control input-sm" readonly >
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<label class="control-label" for="inputRuc">DNI</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
							<div class="fg-line">
								<input id="inputRuc" type="text" name="ruc" class="form-control input-sm" disabled>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<label class="control-label" for="inputDireccion">Dirección</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
							<div class="fg-line">
								<input id="inputDireccion" type="text" name="direccion" class="form-control input-sm" disabled>
							</div>
						</div>
					</div>
				</div>
				<hr class="style5">
				<div class="row">
							<div class="col-md-2">
								<label class="control-label" for="inputAnterior">Anterior</label>
								<div class=" input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
									<div class="fg-line">
										<input id="inputAnterior" type="text" name="anterior" class="form-control input-sm" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<label class="control-label" for="inputActual">Actual</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
									<div class="fg-line">
										<input id="inputActual" type="text" name="actual" class="form-control input-sm" disabled>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<label class="control-label" for="inputtotal">Total</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
									<div class="fg-line">
										<input id="inputtotal" type="text" name="inputtotal" class="form-control input-sm" disabled>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<label class="control-label" for="inputBasico">Basico</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
									<div class="fg-line">
										<input id="inputBasico" type="text" name="basico" class="form-control input-sm" disabled>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<label class="control-label" for="inputExcedente">Excedente</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
									<div class="fg-line">
										<input id="inputExcedente" type="text" name="excedente" class="form-control input-sm" disabled>
									</div>
								</div>
							</div>
				</div>
			</div>


		</div>
	</div>
	
	<div class="card" id="div_deuda" style="display:none">
		<div class="card-header bgm-bluegray m-b-20">
			<h2>Duda del Cliente</h2>
		</div>
		<div class="card-body card-padding">
			<div class="row">
				<div class="col-md-5">
					<label class="control-label" for="inputCliente">Cliente</label>
					
				</div>
				<div class="col-md-2">
					<label class="control-label" for="inputRuc">DNI</label>
					
				</div>
				<div class="col-md-5">
					<label class="control-label" for="inputDireccion">Dirección</label>
					
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-9">
			<div class="card repeater">
				<div class="card-body card-padding">
					<div class="row">
						<table class="table table-responsive">
						<thead>
							<tr>
						    	<th>#</th>
						      	<th>Descripcion</th>
						      	<th>Cuotas</th>
						      	<th>Valor</th>
						    </tr>
						</thead>
						<tbody>
						    <tr>
						    	<th scope="row">1</th>
						    	<td>Deuda Anterior</td>
						    	<td></td>
						    	<td>
						    	<div class="col-md-6">
									<div class="input-group form-group">
										<div class="fg-line">
											<input id="inputDeudaAnterior_tabla" type="text" name="deudaTotal" class="form-control input-sm" readonly>
										</div>
									</div>
								</div>
						    	</td>
						    </tr>
						    <tr>
						      <th scope="row">2</th>
						      <td>Tarifa Basica</td>
						      <td></td>
						      <td><input type="text" name="deudaTarifa" id="inputTarifaSocial"  readonly></td>
						    </tr>
						    <tr>
						      <th scope="row">3</th>
						      <td> Excedente</td>
						      <td></td>
						      <td><input type="text" name="deudaExcente" id="inputdeudaExcente" readonly></td>
						    </tr>
						    <tr>
						      <th scope="row">4</th>
						      <td> Couta Social</td>
						      <td></td>
						      <td><input type="text" name="cuotaSocial" id="inputdeudaCuotaSocial" readonly ></td>
						    </tr>
						    <tr  class="table-warning">
						      <th scope="row">5</th>
						      <td> Medidor</td>
						      <td><input type="text" name="planmedidorcuotaactual" id="inputplanmedidorcuotaactual" readonly ></td>
						      <td><input type="text" name="planmedidor" id="inputplanmedidor" readonly >
						      <input type="hidden" name="planmedidor_id" id="inputplanmedidor_id" ></td>
						      <!--
						      <td><input type="text" name="pagomedidor" id="inputpagomedidor" readonly ></td>
						      -->
						    </tr>
						    <tr>
						      <th scope="row">6</th>
						      <td> Plan Pago</td>
						      <td><input type="text" name="planpagocuotaactual" id="inputplanpagoplanpagocuotaactual" readonly ></td>
						      <td><input type="text" name="planpago" id="inputplanpago" readonly >
						      <input type="hidden" name="planpago_id" id="inputplanpago_id" ></td>
						    </tr>
						    <tr>
						    	<td></td>
						    	<td></td>
						    	<td></td>
						    	<td></td>
						    </tr>
						    <tr class="table-info">
						      <th scope="row">7</th>
						      <td> SubTotal </td>
						      <td></td>
						      <td><input type="text" name="subtotal" id="inputsubtotal" readonly ></td>
						    </tr>
						    <tr class="table-info">
						      <th scope="row">8</th>
						      <td> Pagos A Cuenta </td>
						      <td></td>
						      <td><input type="text" name="pagosacuenta" id="inputpagosacuenta" readonly ></td>
						    </tr>
						    <tr class="table-info">
						      <th scope="row">9</th>
						      <td> Bonificacion </td>
						      <td>
						      <?php 
						      //if(soy admin)
						      echo '<button style="display: none" type="button" id="agregar_bonificacion" name="agregar_bonificacion" class="btn btn-primary waves-effect">Agregar</button>';
						      echo '<button style="display: none" type="button" id="Borrar_bonificacion_btn" name="Borrar_bonificacion_btn" class="btn btn-danger waves-effect">Borrar</button>';
						      echo '<button style="display: none" type="button" id="Modificar_bonificacion_btn" name="Modificar_bonificacion_btn" class="btn btn-warning waves-effect">Modificar</button>';
						      //else
						      ?>
						      <div id="datos_bonificacion_seleccione" style="display:none">
							    <?php
							     echo '<label for="enreparacion">Solicitar Bonificacion?</label>
									<div class="input-group form-group">
										NO&nbsp&nbsp&nbsp
										<div class="toggle-switch">';
										if (isset($medidor))
											if($medidor->Medidor_Habilitacion == 1 )
												echo '<input id="enreparacion" type="checkbox" hidden="hidden" checked>';
											else echo '<input id="enreparacion" type="checkbox" hidden="hidden">';
										else echo '<input id="enreparacion" type="checkbox" hidden="hidden" >';
					                    echo '<label for="enreparacion" class="ts-helper"></label>
					                    </div>&nbsp&nbsp&nbspSI
									</div>
									<input type="text" name="rep_oculto" id="rep_oculto" style="display:none">';
								
						      ?>
						      </div>
						      <div id="datos_bonificacion" style="display:none">
							    <div class="row" id="porcentaje_bonificacion_form_div">
	   						    	<input type="hidden" name="hay_bonificacion_form" id="hay_bonificacion_form"  >
							    	<label for="porcentaje_bonificacion_form"> Porcentaje Descontado</label>
							    	<input type="text" name="porcentaje_bonificacion_form" id="porcentaje_bonificacion_form" readonly >
							    </div>
							    <div class="row" id="monto_descontado_form_div">
							      	<label for="monto_descontado_form">Monto descontado</label>
							      	<input type="text" name="monto_descontado_form" id="monto_descontado_form" readonly >
							    </div>
							    <div class="row" id="monto_a_descontar_form_div">
							      	<label for="monto_a_descontar_form">Monto a descontar</label>
							      	<input type="text" name="monto_a_descontar_form" id="monto_a_descontar_form" readonly >
						      	</div>
						      </div>
						      </td>
						      <td><input type="text" name="bonificacion" id="inputbonificacion" ></td>
						    </tr>
						    <tr>
						    	<td></td>
						    	<td></td>
						    	<td></td>
						    	<td></td>
						    </tr>
						    <tr class="table-info">
						      <th scope="row">10</th>
						      <td> Endeuda </td>
						      <td> <i class="zmdi zmdi-flight"></i> </i></td>
						      <td><input type="text" name="endeuda" id="inputendeuda" ></td>
						    </tr>
						    <tr>
						    	<td></td>
						    	<td></td>
						    	<td></td>
						    	<td></td>
						    </tr>
						    <tr class="table-success">
						      <th scope="row">11</th>
						      <td> Total </td>
						      <td></td>
						      <td><input type="text" name="total" id="inputtotal" ng-model="monto_total" ></td>
						    </tr>
						    <input type="text"  name="total_sin_cambios" id="total_sin_cambios"   style="display: none" >
						  </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div ng-app="">
			<p>Mi primera expresion: {{ 5 + 5 }}</p>
		+</div>

		<div class="col-md-3">
			<div class="card">
				<div class="card-header bgm-gray">
					<h2>Total: <strong>{{monto_total}}</strong> </h2>
				</div>
				<div class="card-body card-padding">
							<div class="row form-group">
								<div class="input-group">
								  <span class="input-group-addon">Entregado</span>
								  <input id="inputEntregado" type="text" class="form-control" >
								</div>
								<br/>
								<div class="input-group">
								  <span class="input-group-addon">Vuelto</span>
								  <input id="inputVuelto" type="text" class="form-control">
								</div>
								<br/>
								<div class="input-group">
								  <span class="input-group-addon">Total</span>
								  <input id="inputtotal" type="text" name="inputtotal" class="form-control">
								</div>
							</div>
							<div class="row form-group">
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label>
                                            <input id="incluyeIGV" name="igv" type="checkbox" value="0">
                                            <i class="input-helper"></i>
                                            Precio Incluye IGV
                                        </label>
                                    </div>
                                </div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label for="hab_medidor">Solicitar Plan de Pago?</label>
									<div class="input-group form-group">
										NO&nbsp&nbsp&nbsp
										<div class="toggle-switch">
										<?php 
										if (isset($medidor))
											if($medidor->Medidor_Habilitacion == 1 )
												echo '<input id="hab_medidor" type="checkbox" hidden="hidden" checked>';
											else echo '<input id="hab_medidor" type="checkbox" hidden="hidden">';
										else echo '<input id="hab_medidor" type="checkbox" hidden="hidden" >';
										?>  
					                        <label for="hab_medidor" class="ts-helper"></label>
					                    </div>&nbsp&nbsp&nbspSI
									</div>
									<input type="text" name="hab__oculto" id="hab_oculto" style="display:none">
								</div>
								<?php 
								//if($this->session->userdata('item'))
								echo '
								<div class="col-md-12" id="plan_de_pago_div">
									<label for="hab_medidor">Crear Plan de Pago para medidor?</label>
									<div class="input-group form-group">';
								 		echo '<button style="display: none" type="button"  id="agregar_PlanMedidor" name="agregar_PlanMedidor" class="btn btn-primary waves-effect">

								 		<i class="zmdi zmdi-plus"></i>
								 		</button>
									</div>
								</div>';

								echo '
								<div class="col-md-12" id="plan_de_pago_quitar_modificar_div">
									<div class="input-group form-group">
								 		<button style="display: none" type="button"  id="modificar_PlanMedidor" name="modificar_PlanMedidor" class="btn btn-warning waves-effect">
								 		modificar
								 		</button>
								 		<button style="display: none" type="button"  id="quitar_PlanMedidor" name="quitar_PlanMedidor" class="btn btn-danger waves-effect">
								 		<i class="zmdi zmdi-minus"></i>
								 		</button>

									</div>
								</div>';

								?>
								<div class="col-md-12" id="datos_plan_pago_ya_creado" style="display:none">

									<input id="id_agregarPlanPago_ya_creado" type='hidden' value="">
									<input id="ismodal_agregarPlanPago_ya_creado" type='hidden' value="false">
									<div class="row">
										<div class="col-md-6">
											<label for="inputFechaInicio_agregarPlanPago_ya_creado">Fecha de inicio</label>
											<div class="input-group form-group">
												<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
												<div class="dtp-container dropdown fg-line open">
													<input id="inputFechaInicio_agregarPlanPago_ya_creado" type="text" name="inputFechaInicio_agregarPlanPago_ya_creado" class="form-control" readonly value="">
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<label for="inputCantidadCuotas_agregarPlanPago_ya_creado">Cantidad de cuotas</label> 
											<div class="input-group form-group">
												<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
												<div class="fg-line">
													<input id="inputCantidadCuotas_agregarPlanPago_ya_creado" type="text" name="inputCantidadCuotas_agregarPlanPago_ya_creado" class="form-control" readonly value= "">
												</div>
											</div>
										</div>	
									</div>
									<div class="row">
										<div class="col-md-6">
											<label for="inputMontoCuota_agregarPlanPago_ya_creado">Monto de cuota</label>
											<div class="input-group form-group">
												<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
												<div class="fg-line">
													<input id="inputMontoCuota_agregarPlanPago_ya_creado" readonly type="text" name="inputMontoCuota_agregarPlanPago_ya_creado" class="form-control" required value="">
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<label for="inputInteres_agregarPlanPago_ya_creado">Interes</label>
											<div class="input-group form-group">
												<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
												<div class="fg-line">
													<input id="inputInteres_agregarPlanPago_ya_creado" type="text" name="inputInteres_agregarPlanPago_ya_creado" class="form-control" readonly>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label for="inputObservacion_agregarPlanPago_ya_creado">Observaciones</label>
											<div class="input-group form-group">
												<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
												<div class="fg-line">
													<input id="inputObservacion_agregarPlanPago_ya_creado"  type="text" name="inputObservacion_agregarPlanPago_ya_creado" class="form-control input-sm" readonly>
												</div>
											</div>
										</div>
									</div>
								</div> 
						<button type="button"  id="pagar_boleta" name="pagar_boleta" class="btn btn-success waves-effect">PAGAR</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModalPlanMedidor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Creando Plan de Pago</h4>
                </div>
                <div class="modal-body">
	                <div id="PlanPagoNuevo" class="col-md-12">

	                	
	                </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="myModal_Bonificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Agregando una Bonificacion</h4>
                </div>
                <div class="modal-body">
                    <div id="nueva_bonificacion_div">
                    	
                    </div>
                </div>
                <div class="modal-footer">
                	<button type="button"  class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button"  class="btn btn-success" data-dismiss="modal">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>


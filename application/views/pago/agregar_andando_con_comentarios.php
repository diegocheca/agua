	<!-- contents -->
<div class="row">
	<!-- <div class="block-header">
		<h2>Cargar nuevo pago</h2>
	</div> -->
	<div class="card">
		<form id="formulario_chiquitito" name="formulario_chiquitito" method="post" action="<?php echo base_url("pago/guardar_pago_nuevo"); ?>">
			<!-- <div class="card-header bgm-bluegray m-b-20">
				<h2>Datos de Facturación</h2>
			</div> -->
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
					<!-- <div class="col-md-3 col-xs-12">
						<label for="inputSerie">Serie</label> - <label for="inputCorrelativo">Correlativo</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-tab"></i></span>
							<div class="col-md-3 col-xs-3">
								<div class="fg-line">
									<input id="inputSerie" type="text" name="serie" class="form-control input-sm" value="001" required>
								</div>
							</div>
							<div class="col-md-9 col-xs-9">
								<div class="fg-line">
									<input id="inputCorrelativo" type="text" name="correlativo" class="form-control input-sm" required>
								</div>
							</div>
						</div>
					</div> -->
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
								<input id="inputMonto" type="text" name="monto" class="form-control input-sm" readonly>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12">
						<label for="inputConexionId">Conexion</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line">
								<input id="inputConexionId" type="text" name="conexion" class="form-control input-sm" readonly>
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
									<select id="inputTipoPago" type="text" name="tipopago" class="form-control input-sm" required>
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
									<input id="inputDeuda" type="text" name="deuda" class="form-control input-sm" readonly>
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
									<input id="inputMontoModif" type="text" name="montodif" class="form-control input-sm">
								</div>
							</div>
						</div>
				</div>
				</div>
			</div>
			<div class="card">
				<!-- <div class="card-header bgm-bluegray m-b-20">
					<h2>Datos de Cliente</h2>
				</div> -->
				<div class="card-body card-padding">
					<div class="row">
						<div class="col-md-4">
							<label class="control-label" for="inputCliente">Cliente</label>
							<div class=" input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<input id="inputCliente" type="text" name="cliente" class="form-control input-sm" readonly>
									<!-- <input type="hidden" id="inputIdCliente" name="idcliente"> -->
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<label class="control-label" for="inputRuc">Id</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<div class="fg-line">
									<input id="inputIdCliente" type="text" name="idcliente" class="form-control input-sm" disabled>
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
				</div>
			</div>
			<div class="card">
				<!-- <div class="card-header bgm-bluegray m-b-20">
					<h2>Datos de Medicion</h2>
				</div> -->
				<div class="card-body card-padding">
					<div class="row">
						<div class="col-md-2">
							<label class="control-label" for="inputAnterior">Anterior</label>
							<div class=" input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<input id="inputAnterior" type="text" name="anterior" class="form-control input-sm" readonly>
									<!-- <input type="hidden" id="inputIdCliente" name="idcliente"> -->
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
							<label class="control-label" for="inputTotal">Total</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<div class="fg-line">
									<input id="inputTotal" type="text" name="total" class="form-control input-sm" disabled>
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
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Agregando una Bonificacion</h4>
                        </div>
                        <div class="modal-body">
                        	<div clas="row">
                        		<div class="col-md-6">
									<label for="bonificacion">Monto a bonificar:</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
										<div class="fg-line">
											<input id="bonificacion" type="text" name="bonificacion" class="form-control input-sm">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<label for="monto_pagar_con_bonificacion">Monto a pagar:</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
										<div class="fg-line">
											<input id="monto_pagar_con_bonificacion" type="text" name="monto_pagar_con_bonificacion" class="form-control input-sm" required>
										</div>
									</div>
								</div>

							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary">Agregar bonificacion</button>
                        </div>
                    </div>
                </div>
            </div>

			<div class="row">
				<div class="col-md-9">
					<div class="card repeater">
						<!-- <div class="card-header bgm-bluegray m-b-20">
							<h2> Datos de Venta </h2>
							<button data-repeater-create type="button" class="btn bgm-red btn-float waves-effect"><i class="zmdi zmdi-plus"></i></button>
						</div> -->
						<div class="card-body card-padding">
							<div class="row">
								<!-- <div class="col-md-2">
									<label class="control-label" for="inputAnterior">Deuda</label>
									<div class=" input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
										<div class="fg-line">
											<input id="inputAnterior" type="text" name="anterior" class="form-control input-sm" required>
											<!-- <input type="hidden" id="inputIdCliente" name="idcliente"> ->
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label" for="inputActual">Tarifa basica</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
										<div class="fg-line">
											<input id="inputActual" type="text" name="actual" class="form-control input-sm" disabled>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label" for="inputTotal">Total</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
										<div class="fg-line">
											<input id="inputTotal" type="text" name="total" class="form-control input-sm" disabled>
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
								</div> -->

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
												<label for="inputDeudaAnterior">Monto a pagar:</label>
												<div class="input-group form-group">
													<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
												<div class="fg-line">
													<input id="inputDeudaAnterior" type="text" name="deudaTotal" class="form-control input-sm" readonly>
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
									      <td></td>
									      <td><input type="text" name="pagomedidor" id="inputpagomedidor" readonly ></td>
									    </tr>
									    <tr>
									      <th scope="row">6</th>
									      <td> Plan Pago</td>
									      <td><input type="text" name="planpagocuotaactual" id="inputplanpagoplanpagocuotaactual" readonly ></td>
									      <td><input type="text" name="planpago" id="inputplanpago" readonly ></td>
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
									      <td> <button style="display: none" type="button" data-toggle="modal" data-target="#myModal" id="agregar_bonificacion" name="agregar_bonificacion" class="btn btn-primary waves-effect"><i class="zmdi zmdi-plus"></i></button></td>
									      <td><input type="text" name="bonificacion" id="inputbonificacion" ></td>
									    </tr>
							<!-- 		    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                                Launch Demo Modal
                            </button>

                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                        </div>
                                        <div class="modal-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                             -->
									    <tr>
									    	<td></td>
									    	<td></td>
									    	<td></td>
									    	<td></td>
									    </tr>
									    <tr class="table-info">
									      <th scope="row">10</th>
									      <td> Endeuda </td>
									      <td></td>
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
									      <td><input type="text" name="total" id="inputtotal" ></td>
									    </tr>

									    <input type="text" name="total_sin_cambios" id="total_sin_cambios" style="display: none" >




									  </tbody>
									</table>
							</div>
							<div class="row" style="display: none">
								<!-- Campo Productos -->
								<div data-repeater-list="productos" class="col-md-12 producto-container">
									<div data-repeater-item class="row">
										<!-- <div class="col-md-2">
											<div class="form-group fg-line">
												<label class="control-label" for="inputCantidad">Cantidad</label>
												<input type="text" id="inputCantidad" name="cantidad" class="form-control input-sm" placeholder="Ingrese la cantidad" autocomplete="off" required>
											</div>
										</div> -->
										<div class="col-md-5">
											<div class="form-group fg-line select">
												<label class="control-label" for="inputProducto">Producto</label>
												<select id="inputProducto" name="producto" class="form-control input-sm" required >
												<option value="-1">Seleccionar ...</option>
												<?php 
												foreach ($productos as $key) {
													echo '<option value="'.$key->Prod_Id.'">'.$key->Prod_Nombre.'</option>';
												}?>
												</select>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group fg-line">
												<label class="control-label" for="inputPrecioUnidad">Precio Unidad</label>
												<input type="text" id="inputPrecioUnidad" name="precio_unit" class="form-control input-sm" placeholder="Solo Números" autocomplete="off" required>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group fg-line">
												<label class="control-label" for="inputPrecio">Precio</label>
												<input type="text" id="inputPrecio" name="precio" class="form-control input-sm" placeholder="Solo Números" readonly>
											</div>
										</div>
										<div id="botones" class="form-group col-md-1">
											<button data-repeater-delete type="button" id="delete-producto" class="btn btn-primary"><i class="zmdi zmdi-minus"></i></button>
										</div>
									</div>
								</div>
								<!-- Fin Campo Productos -->
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-header bgm-gray">
							<h2>Totales</h2>
						</div>
						<div class="card-body card-padding">
							<div class="row form-group">
								<div class="input-group">
								  <span class="input-group-addon">Subtotal</span>
								  <input id="valorSumado" type="hidden">
								  <input id="inputSubtotal" type="text" class="form-control" aria-describedby="subtotal">
								</div>
								<br/>
								<div class="input-group">
								  <span class="input-group-addon">I.G.V</span>
								  <input id="inputIgv" type="text" class="form-control" aria-describedby="igv">
								</div>
								<br/>
								<div class="input-group">
								  <span class="input-group-addon">Total</span>
								  <input id="inputTotal" type="text" name="total" class="form-control" aria-describedby="total">
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
								
								<!--id="enviarDatos_" -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<button type="submit" >Crear Pago</button>
		</form>
	</div>
	<!-- <div id="resultado"></div> -->

<div class="row">
		<form id="formDocument" method="post" action="<?php echo base_url("/");?>">
			<div class="card">
				<div class="card-header bgm-bluegray m-b-20">
					<h2>Datos de Cliente</h2>
				</div>
				<div class="card-body card-padding">
					<div class="row">
						<div class="col-md-5">
							<label class="control-label" for="inputCliente_crearfactura">Cliente</label>
							<div class=" input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<input id="inputCliente_crearfactura" type="text" name="cliente" class="form-control input-sm" required>
									<input type="hidden" id="inputIdCliente" name="idcliente">
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
						<div class="col-md-5">
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
				<div class="card-body card-padding">
					<div class="row">
						<div class="col-md-3 col-xs-12">
							<label for="inputDate">Vencimiento 1</label> - <label for="inputDateC">Vencimiento 2</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
								<div class="col-md-6 col-xs-6">
									<div class="dtp-container dropdown fg-line open">
										<input id="inputDate" type="text" name="fechaEmision" class="form-control input-sm date-picker" data-toggle="dropdown" value="<?php echo date('14/m/Y'); ?>" >
									</div>
								</div>
								<div class="col-md-6 col-xs-6">
									<div class="dtp-container dropdown fg-line open">
										<input id="inputDateC" type="text" name="fechaCancelacion" class="form-control input-sm date-picker" data-toggle="dropdown" value="<?php echo date('21/m/Y'); ?>" >
									</div>
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
							<label for="inputDeuda"> <a data-target="#myModal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal"> Deuda de la Conexion</a> </label>
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
									<input id="inputMes" type="text" name="mes" class="form-control input-sm" value="<?php echo date("m");?>" readonly>
								</div>
							</div>
						</div>
						<div class="col-md-2 col-xs-12">
							<label for="inputAnio">Año Factura</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<div class="fg-line">
									<input id="inputAnio" type="text" name="anio" class="form-control input-sm" value="<?php echo date("Y");?>" readonly>
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
						<div class="col-md-2 col-xs-12">
							<label for="inputSector">Sector</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<div class="fg-line">
									<input id="inputSector" type="text" name="inputSector" class="form-control input-sm" readonly>
								</div>
							</div>
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
	                            		<div class="table-responsive">
			                               <table class="table">
			                                    <thead>
			                                        <tr>
			                                            <th>#</th>
			                                            <th>First Name</th>
			                                            <th>Last Name</th>
			                                            <th>Username</th>
			                                        </tr>
			                                    </thead>
			                                    <tbody>
			                                        <tr class="success">
			                                            <td>1</td>
			                                            <td>Mark</td>
			                                            <td>Otto</td>
			                                            <td>@mdo</td>
			                                        </tr>
			                                        <tr class="info">
			                                            <td>2</td>
			                                            <td>Jacob</td>
			                                            <td>Thornton</td>
			                                            <td>@fat</td>
			                                        </tr>
			                                        <tr class="warning">
			                                            <td>3</td>
			                                            <td>Larry</td>
			                                            <td>the Bird</td>
			                                            <td>@twitter</td>
			                                        </tr>
			                                        <tr class="danger">
			                                            <td>4</td>
			                                            <td>John</td>
			                                            <td>Smith</td>
			                                            <td>@jsmith</td>
			                                        </tr>
			                                    </tbody>
						 </table>
	                            		</div>
			                	</div>
			                </div>
			                <div class="modal-footer">
			                    <button type="button"  class="btn btn-primary" data-dismiss="modal">Salir</button>
			                </div>
			            </div>
			        </div>
    			</div>
			<div class="row">
				<div class="col-md-9">
					<div class="card repeater">
						<div class="card-header bgm-bluegray m-b-20">
							<h2>Datos de Factura</h2>
							<!-- <button data-repeater-create type="button" class="btn bgm-red btn-float waves-effect"><i class="zmdi zmdi-plus"></i></button> -->
						</div>
						<div class="card-body card-padding">
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
								    	<td><input type="text" name="deudaAnterior" id="inputDeudaAnterior" readonly></td>
							    	</tr>
								    <tr>
								      <th scope="row">2</th>
								      <td> Excedente</td>
								      <td></td>
								      <td><input type="text" name="excente" id="inputExcedente" readonly></td>
								    </tr>
								    <tr>
								      <th scope="row">3</th>
								      <td> Tarifa básica</td>
								      <td></td>
								      <td><input type="text" name="tarifaBasica" id="imputTarifaBasica" readonly></td>
								    </tr>
								    <tr>
								      <th scope="row">4</th>
								      <td> Couta Social</td>
								      <td></td>
								      <td><input type="text" name="cuotaSocial" id="inputCuotaSocial" readonly ></td>
								    </tr>
								    <tr  class="table-warning">
								      <th scope="row">5</th>
								      <td> Medidor</td>
								      <td><input type="text" name="planMedidorCuotaActual" id="inputPlanMedidorCuotaActual" readonly ></td>
								      <td><input type="text" name="pagoMedidor" id="inputPagoMedidor" readonly ></td>
								    </tr>
								    <tr>
								      <th scope="row">6</th>
								      <td> Plan Pago</td>
								      <td><input type="text" name="planPagoCuotaActual" id="inputPlanPagoCuotaActual" readonly ></td>
								      <td><input type="text" name="planPago" id="inputPlanPago" readonly ></td>
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
								      <td><input type="text" name="subtotal" id="inputSubtotal" readonly ></td>
								    </tr>
								    <tr class="table-info">
								      <th scope="row">8</th>
								      <td> Pagos A Cuenta </td>
								      <td></td>
								      <td><input type="text" name="pagosacuenta" id="inputPagosAcuenta" readonly ></td>
								    </tr>
								    <tr class="table-info">
								      <th scope="row">9</th>
								      <td> Bonificacion </td>
								      <td> <button style="display: none" type="button" data-toggle="modal" data-target="#myModal" id="agregar_bonificacion" name="agregar_bonificacion" class="btn btn-primary waves-effect"><i class="zmdi zmdi-plus"></i></button></td>
								      <td><input type="text" name="bonificacion" id="inputBonificacion" ></td>
								    </tr>
								    <tr>
								    	<td></td>
								    	<td></td>
								    	<td></td>
								    	<td></td>
								    </tr>
								    	<td></td>
								    	<td></td>
								    	<td></td>
								    	<td></td>
								    </tr>

								    <tr class="table-success">
								      <th scope="row">11</th>
								      <td> Total </td>
								      <td></td>
								      <td><input type="text" name="total" id="inputTotal" ></td>
								    </tr>

								    <input type="text" name="total_sin_cambios" id="total_sin_cambios" style="display: none" >

							  </tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-header bgm-gray">
							<h2>Totales <small>Importe total</small></h2>
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
								<button type="button" id="enviarDatos" class="btn btn-lg btn-block btn-success">Crear Documento</button>
							</div>
							<!-- <div class="row">
								<button type="button" id="dasdasd" onclick="alertas()" class="btn btn-lg btn-block btn-success">Aviso </button>
							</div> -->

						</div>
					</div>
				</div>
			</div>
		</form>
</div>
<div id="resultado"></div>


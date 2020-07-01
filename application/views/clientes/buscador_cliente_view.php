<div class="card">
				<div class="card-header bgm-bluegray m-b-20">
					<h2>Datos de Cliente <small>Ingresa los Datos del Cliente</small></h2>
				</div>
				<div class="card-body card-padding">
					<div class="row">
						<div class="col-md-5">
							<label class="control-label" for="inputCliente">Cliente</label>
							<div class=" input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<input id="inputCliente" type="text" name="cliente" class="form-control input-sm" required>
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
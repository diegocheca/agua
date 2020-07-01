	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-6">
			<div class="card-header">
				<h2>Detalles movimiento</h2>
			</div>
			<div class="card-body card-padding">
						
				<form>
					<div class="row">
						<div class="col-md-6">
							<label for="inputPrendaCodigo">Tipo de movimiento</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputPrendaCodigo" type="text" maxlength="200" name="inputPrendaCodigo" class="form-control input-sm" readonly
									<?php 
									if(isset($consulta))
										if($consulta->Mov_Tipo == 1){
											echo  'value= Ingreso';
										}
										else{
											echo  'value= Egreso';
										}
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputCodigo">Codigo</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputCodigo" type="text" maxlength="200" name="inputCodigo" class="form-control input-sm" readonly
									<?php 
									if(isset($consulta))
										echo  'value= "'.$consulta->Mov_Codigo.'"';
									 ?>
									 >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="inputMonto">Monto</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputMonto" type="text" maxlength="200" name="inputMonto" class="form-control input-sm" readonly
									<?php 
									if(isset($consulta))
										echo  'value= "'.$consulta->Mov_Monto.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputMonto">Fecha</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputMonto" type="text" maxlength="200" name="inputMonto" class="form-control input-sm" readonly
									<?php 
									if(isset($consulta))
										echo  'value= "'.$consulta->Mov_Timestamp.'"';
									 ?>
									 >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="inputQuien">Realizado por:</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputQuien" type="text" maxlength="200" name="inputQuien" class="form-control input-sm" readonly
									<?php 
									if(isset($consulta))
										echo  'value= "'.$consulta->usuario.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputFacturacionId">Nro. factura</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputFacturacionId" type="text" maxlength="200" name="inputFacturacionId" class="form-control input-sm" readonly
									<?php 
									if(isset($consulta))
										echo  'value= "'.$consulta->Pago_Facturacion_Id.'"';
									 ?>
									 >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="inputObservacion">Observacion del pago</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<input id="inputObservacion" type="text" maxlength="200" name="inputObservacion" name="inputObservacion" class="form-control input-sm" readonly
									<?php  
									if(isset($consulta))
										echo 'value= "'.$consulta->Pago_Observacion.'"';
									 ?>
									 >
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="id" id="id" value="<?php if(isset($consulta)) echo $consulta->Mov_Id; else echo "-1";  ?>" style="display: none">
					<div class="row">
						<div class="col-md-6">
							<button type="submit" name="enviar" style="width:100%" class="btn btn-warning">Editar</button>
						</div>
						<div class="col-md-6">
							<a href="<?php echo base_url("movimientos/ingresos");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
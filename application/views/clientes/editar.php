	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card">
			<div class="card-header">
				<h2>Editar Cliente</h2>
			</div>
			<div class="card-body card-padding">
						
				<form  id ="form_bonificacion" action="<?php 
				echo base_url();
				if(isset($cliente))
					echo "clientes/modificar_cliente";
				else echo "clientes/modificar_cliente";
				?>" method="post" class="">
					<div class="row">
						<div class="col-md-3">
							<label for="inputTipoPersona">Tipo Persona</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<select id="inputTipoPersona" type="text" name="inputTipoPersona" class="form-control input-sm" required
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_TipoPersona.'"';
									 ?>
								>
										<option value="0" selected>Natural</option>
										<option value="1">Júridica</option>
									</select>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputTipoDocumento">Tipo Documento</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-file"></i></span>
								<select id="inputTipoDocumento" type="text" name="inputTipoDocumento" class="form-control input-sm" required
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_TipoDoc.'"';
									 ?>
								>
										<option value="dni" selected>DNI</option>
										<option value="Libreta Civica">Libreta Cívica</option>
										<option value="Libreta Enrolamiento">Libreta Enrolamiento</option>
										<option value="Entre Privado">Ente Privado</option>
										<option value="Entre Publico">Ente Público</option>
									</select>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputNroDocumento">Nro. Documento</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<input id="inputNroDocumento" type="text" maxlength="12" name="inputNroDocumento" class="form-control input-sm"
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_NroDocumento.'"';
									 ?>
								>
							</div>
						</div>
						<div class="col-md-3" style="display:none">
							<label for="inputNroCliente">Nro Cliente</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<input id="inputNroCliente" type="text" maxlength="8" name="inputNroCliente" class="form-control input-sm"
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_Id.'"';
									 ?>
								>
							</div>
						</div> 
					</div>
					<div class="row">
						<div class="col-md-8">
							<label for="inputRazonSocial">Razón Social</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-receipt"></i></span>
								<input id="inputRazonSocial" type="text" maxlength="200" name="inputRazonSocial" class="form-control input-sm"
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_RazonSocial.'"';
									 ?>
								>
							</div>
						</div>
						<div class="col-md-4">
							<label for="inputCuit">Cuit</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputCuit" type="text" maxlength="14" name="inputCuit" class="form-control input-sm"
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_RazonSocial.'"';
									 ?>
								>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<label for="inputEmail">Email</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
								<input id="inputEmail" type="text" maxlength="200" name="inputEmail" class="form-control input-sm"
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_Email.'"';
									 ?>
								>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputTelefono">Teléfono</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-phone"></i></span>
								<input id="inputTelefono" type="text" maxlength="20" name="inputTelefono" class="form-control input-sm"
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_Telefono.'"';
									 ?>
								>
							</div>
						</div>
						<div class="col-md-4">
							<label for="inputCelular">Celular</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-smartphone-android"></i></span>
								<input id="inputCelular" type="text" maxlength="20" name="inputCelular" class="form-control input-sm"
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_Celular.'"';
									 ?>
								>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<label for="inputDomPost">Domicilio postal</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
								<input id="inputDomPost" type="text" maxlength="200" name="inputDomPost" class="form-control input-sm"
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_DomicilioPostal.'"';
									 ?>
								>
							</div>
						</div>
					</div>	
					<div class="row">
						<div class="col-md-3">
							<label for="inputTipoIVA">Tipo de IVA</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<select id="inputTipoIVA" type="text" name="inputTipoIVA" class="form-control input-sm"
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_TipoIVAId.'"';
									 ?>
								>
										<option value="1" selected>Excento</option>
										<option value="2">IVA general</option>
										<option value="3">IVA reducida</option>
										<option value="4">VA superreducido</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputObservacion">Observacion del cliente</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<input id="inputObservacion" type="text" maxlength="200" name="inputObservacion" class="form-control input-sm"
									<?php 
									if(isset($cliente))
										echo  'value= "'.$cliente->Cli_Observacion.'"';
									 ?>
								>
							</div>
						</div>
					</div>
					
					<input type="hidden" name="id" id="id" value="<?php if(isset($cliente)) echo $cliente->Cli_Id; else echo "-1";  ?>" style="display: none">
					<div class="row">
						<div class="col-md-4">
							<button type="submit" name="enviar" style="width:100%" class="btn btn-success">Guardar</button>
						</div>
						<div class="col-md-4">
							<button type="reset"  style="width:100%" class="btn btn-warning">Cancelar</button>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("clientes");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

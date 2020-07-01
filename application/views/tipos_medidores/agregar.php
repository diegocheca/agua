	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-6">
			<div class="card-header">
				<h2>Agregar Nuevo Tipo de Medidor</h2>
			</div>
			<div class="card-body card-padding">
						
				<form action="<?php 
				echo base_url();
				if(isset($tipos))
					echo "tipos_medidores/modificar_tmedidor";
				else echo "tipos_medidores/agregar";
				 ?>" method="post" class="">
					<div class="row">
						<div class="col-md-6">
							<label for="inputCodigoProducto">Codigo</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<div class="fg-line">
									<input id="inputCodigoProducto" type="text" maxlength="10" name="inputCodigoProducto" class="form-control input-sm" 
									<?php 
									if(isset($tipos))
										echo 'value= "'.$tipos->TMedidor_Id.' "  readonly';
									else echo 'placeholder ="0003004021 " disabled';
										?>
									>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputMarca">Marca</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputMarca" placeholder="Marca...." type="text" maxlength="50" name="inputMarca" class="form-control input-sm" required
									<?php 
									if(isset($tipos))
										echo  'value= "'.$tipos->TMedidor_Marca.'"';
									 ?>
									 >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="inputModelo">Modelo</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputModelo" placeholder="Modelo...." type="text" maxlength="50" name="inputModelo" class="form-control input-sm" required
									<?php 
									if(isset($tipos))
										echo 'value= "'.$tipos->TMedidor_Modelo.'"';
									 ?>
									>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputPrecioMayo">Precio Mayorista</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
								<div class="fg-line">
									<input id="inputPrecioMayo" placeholder="150.00" data-mask="000 000.00" data-mask-reverse="true" type="text" name="inputPrecioMayo" class="form-control input-sm input-mask"
									<?php  
									if(isset($tipos))
										echo 'value= "'.$tipos->TMedidor_PrecioMayorista.'"';
									 ?>
									 >
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="inputPrecioUni">Precio Minorista</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
								<div class="fg-line">
									<input id="inputPrecioUni" placeholder="150.00" data-mask="000 000.00" data-mask-reverse="true" type="text" name="inputPrecioUni" class="form-control input-sm input-mask"
									
									<?php  
									if(isset($tipos))
										echo 'value= "'.$tipos->TMedidor_PrecioUnitario.'"';
									 ?>
									 >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="hab_medidor">Habilitacion</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
								<?php 
								if (isset($medidor))
									if($medidor->Medidor_Habilitacion == 1 )
										echo '<input id="hab_medidor" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="hab_medidor" type="checkbox" hidden="hidden">';
								else echo '<input id="hab_medidor" type="checkbox" hidden="hidden" checked>';
								?>  
			                        <label for="hab_medidor" class="ts-helper"></label>
			                    </div>&nbsp&nbsp&nbspSI
							</div>
							<input type="text" name="hab__oculto" id="hab_oculto" style="display:none">
						</div>
					</div>
					<input type="text" name="id" id="id" value="<?php if(isset($tipos)) echo $tipos->TMedidor_Id; else echo "-1";  ?>" style="display: none">
					<div class="row">
						<div class="col-md-4">
							<label for="inputCantidad">Cantidad</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment-return"></i></span>
								<div class="fg-line">
									<input id="inputCantidad" placeholder="600"  data-mask="000 000" data-mask-reverse="true" type="text" name="inputCantidad" class="form-control input-sm input-mask"
									<?php  
									if(isset($tipos))
										echo 'value= "'.$tipos->TMedidor_Cantidad.'"';
									 ?>
									 >
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label for="inputInstalados">Instalados</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
								<div class="fg-line">
									<input id="inputInstalados" placeholder="150" data-mask="000 000" data-mask-reverse="true" type="text" name="inputInstalados" class="form-control input-sm input-mask"
									<?php  
									if(isset($tipos))
										echo 'value= "'.$tipos->TMedidor_Instalados.'"';
									 ?> >
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label for="inputSinInstalar">Sin Instalar</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
								<div class="fg-line">
									<input id="inputSinInstalar" placeholder="150" data-mask="000 000" data-mask-reverse="true" type="text" name="inputSinInstalar" class="form-control input-sm input-mask"
									<?php  
									if(isset($tipos))
										echo 'value= "'.$tipos->TMedidor_SinInstalar.'"';
									?>
									>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<label for="inputBaja">De baja </label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment-return"></i></span>
								<div class="fg-line">
									<input id="inputBaja"  data-mask="000 000" placeholder="150" data-mask-reverse="true" type="text" name="inputBaja" class="form-control input-sm input-mask"
									<?php  
									if(isset($tipos))
										echo 'value= "'.$tipos->TMedidor_Baja.'"';
									?>
									>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label for="inputReparados">Reparados</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
								<div class="fg-line">
									<input id="inputReparados" placeholder="150" data-mask="000 000" data-mask-reverse="true" type="text" name="inputReparados" class="form-control input-sm input-mask"
									<?php  
										if(isset($tipos))
											echo 'value= "'.$tipos->TMedidor_Reparados.'"';
									?>
										 >
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="inputObservacion">Observaciones</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<div class="fg-line">
									<input id="inputObservacion"  type="text" name="inputObservacion" class="form-control input-sm" 
									<?php  
										if(isset($tipos))
											echo 'value= "'.$tipos->TMedidor_Observacion.'"';
									?>
									>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<button type="submit" name="enviar" style="width:100%" class="btn btn-success">Guardar</button>
						</div>
						<div class="col-md-4">
							<button type="reset"  style="width:100%" class="btn btn-danger">Cancelar</button>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("tipos_medidores");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
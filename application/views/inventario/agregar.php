		
	<!-- contents -->
	
	<div class="row">
		<!-- Agregar clientes -->
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-6">
			<div class="card-header">
				<h2>Agregar Nuevo Producto</h2>
			</div>
			<div class="card-body card-padding">
						
				<form action="<?php 
				echo base_url();
				if(isset($medidor))
					echo "inventario/modificar_medidor";
				else echo "inventario/agregar";
				 ?>" method="post" class="">
					<div class="row">
						<div class="col-md-6">
							<label for="inputCodigoProducto">Codigo</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<div class="fg-line">
									<input id="inputCodigoProducto" type="text" maxlength="10" name="codigo_producto" class="form-control input-sm" required
									value= "<?php 
									if(isset($medidor))
										echo $medidor->Medidor_Codigo;
									else echo "0003004021" ?>"
									>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<label for="inputTipoMedidor">Tipo de medidor</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line select">
									<select id="tipo_medidor" name="tipo_medidor"  class="form-control input-sm">
									<option value="0">Seleccione</option>
									<?php
									foreach ($tipos as $key ) {
										 echo '<option value="'.$key->TMedidor_Id.'">'.$key->TMedidor_Marca." - ".$key->TMedidor_Modelo.'</option>';
									}
									 ?>
								</select>
								</div>
							</div>

						</div>

					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="finstalacion">Fecha de Instalacion</label> 
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
								<div class="dtp-container dropdown fg-line open">
									<input id="finstalacion" type="text" name="finstalacion" class="form-control input-sm date-picker" data-toggle="dropdown" 
									value="
									<?php 
									if(isset($medidor)) 
										if($medidor->Medidor_FechaInstalacion != null)
											echo date( "d/m/Y", strtotime( $medidor->Medidor_FechaInstalacion) ) ;?>">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputPrecio">Precio</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
								<div class="fg-line">
									<input id="inputPrecio" placeholder="150.00" data-mask="000 000.00" data-mask-reverse="true" type="text" name="precio" class="form-control input-sm input-mask"
									value="750.00" >
								</div>
							</div>
						</div>

					</div>
					<div class="row">
						<input type="text" name="id" id="id" value="<?php if(isset($medidor)) echo $medidor->Medidor_Id; else echo "-1";  ?>" style="display: none">

						<div class="col-md-4">
							<label for="enreparacion">En Reparacion</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
		                          
								<?php 
								if (isset($medidor))
									if($medidor->Medidor_EnReparacion == 1 )
										echo '<input id="enreparacion" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="enreparacion" type="checkbox" hidden="hidden">';
								else echo '<input id="enreparacion" type="checkbox" hidden="hidden">';
								?>  
		                            <label for="enreparacion" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
		                        <input type="text" name="rep_oculto" id="rep_oculto" style="display:none">
							</div>
						</div>
						<div class="col-md-4">
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
						<div class="col-md-4">
							<label for="inputIntervenciones">Intervenciones</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment-return"></i></span>
								<div class="fg-line">
									<input id="inputIntervenciones"  data-mask="000 000.00" data-mask-reverse="true" type="text" name="inputIntervenciones" class="form-control input-sm input-mask">
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
									<input id="inputObservacion"  type="text" name="inputObservacion" class="form-control input-sm" >
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
							<a href="<?php echo base_url("inventario");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- fin de agregar clientes -->
	</div>
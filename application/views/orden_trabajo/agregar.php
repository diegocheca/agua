	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-6">
			<div class="card-header">
				<h2><?php
					if(isset($orden))
						echo "Editar orden de trabajo";
					else
						echo "Agregar orden de trabajo";
				?></h2>
			</div>
			<div class="card-body card-padding">
						
				<form id="form_agregar_orden_trabajo" action="<?php 
				echo base_url();
				if(isset($orden))
					echo "orden_trabajo/guardar_agregar";
				else echo "orden_trabajo/guardar_agregar";
				 ?>" method="post" class="">
					<div class="row">
						<div class="col-md-12">
							<label for="inputTarea">Tarea</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputTarea" placeholder="Tarea" type="text" maxlength="200" name="inputTarea" class="form-control input-sm" required
									<?php 
									if(isset($orden)){
										echo  'value= "'.$orden->OrdenTrabajo_Tarea.'"';
										if($orden->OrdenTrabajo_Tarea == "Colocacion de nuevo Medidor")
											echo  'readonly';
									 }
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputNombreUsuario">Nombre Usuario</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputNombreUsuario" placeholder="Nombre Usuario" type="text" maxlength="200" name="inputNombreUsuario" class="form-control input-sm" 
									<?php 
									if(isset($orden))
										echo  'value= "'.$orden->OrdenTrabajo_Cliente.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputConexionId">Conexion ID</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputConexionId" placeholder="Conexion id" type="text" name="inputConexionId" class="form-control input-sm" 
									<?php 
									if(isset($orden))
										echo  'value= "'.$orden->OrdenTrabajo_NConexion.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-12">
							<label for="inputDireccion">Direccion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputDireccion" type="text"  name="inputDireccion" class="form-control input-sm" 
								<?php 
								if(isset($orden))
									echo  'value= "'.$orden->OrdenTrabajo_Direccion.'"';
								?> 
								>
							</div>
						</div>
						<div class="col-md-12">
							<label for="inputTecnico">Tecniiiiiiiiico asigando</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputTecnico" type="text"  name="inputTecnico" class="form-control input-sm" 
								 	<?php 
									if(isset($orden))
										echo  'value= "'.$orden->OrdenTrabajo_Tecnico.'"';
									 ?> 
									 >
							</div>
						</div>
						<!-- 	<div class="col-md-6">
							<label for="inputFecha">Fecha</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputFecha" type="text"  name="inputFecha" class="form-control input-sm" 
								 	<?php 
									if(isset($orden))
										echo  'value= "'.date ( 'd/m/Y' , $orden->OrdenTrabajo_FechaInicio).'"';
									 ?> 
									 >
							</div>
						</div> -->
						
					</div>
					<!-- <div class="row">
						<div class="col-md-6">
							<label for="select_material">Materiales a utilizar:</label>
							<div class="fg-line select">
							<select name="materiales[]" multiple id="select_material" class="chosen" data-placeholder="Elige los materiales" >
							<?php
							foreach ($materiales as $key) {
								echo "<option value=".$key->Materiales_Id.">".$key->Materiales_Descripcion."</option>";
								# code...
							} ?>
							</select>
							</div>
						</div>
					</div>
 -->
				 	<!-- <div class="row">
				 		<div class="col-md-6">
							<label for="inputNroCliente">Finalizado</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
									<?php if( (isset($orden)) && ($orden->OrdenTrabajo_Porcentaje == 100 )  && ($orden->OrdenTrabajo_Estado == 1))
									echo '<input id="hab_medidor" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="hab_medidor" type="checkbox" hidden="hidden">';
									?>  
									<label for="hab_medidor" class="ts-helper"></label>
								</div>&nbsp&nbsp&nbspSI
								<input type="text" name="hab__oculto" id="hab_oculto" style="display:none">
							</div>
						</div>
					</div> -->
					<div class="row">
							<div class="col-md-6">
								<label for="fecha_inicio_evento_nuevo">Comienzo:</label>
								<div class="dtp-container dropdown fg-line open">
									<input id="fecha_inicio_evento_nuevo" type="text" name="fecha_inicio_evento_nuevo" class="form-control input-sm date-picker" value="<?php
									$fecha = date('Y-m-j');
									$nuevafecha = strtotime ( '+2 day' , strtotime ( $fecha ) ) ;
									$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
									 
									echo $nuevafecha;
									 ?>"  data-toggle="dropdown" >
								</div>
							</div>
							<div class="col-md-6">
								<label for="fecha_fin_evento_nuevo">Fin:</label>
								<div class="dtp-container dropdown fg-line open">
									<input id="fecha_fin_evento_nuevo" type="text" name="fecha_fin_evento_nuevo" class="form-control input-sm date-picker" value="<?php
									$fecha = date('Y-m-j');
									$nuevafecha = strtotime ( '+10 day' , strtotime ( $fecha ) ) ;
									$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
									 
									echo $nuevafecha;
									?>"  data-toggle="dropdown" >
								</div>
							</div>
						</div>
<br>
					<div class="row">
						<div class="col-md-6">
							<label for="estado_evento_nuevo">Estado de evento:</label>
							<select id="estado_evento_nuevo" type="text" name="estado_evento_nuevo"  class="chosen" >
							<?php 
							if( (isset($orden)) && ($orden->OrdenTrabajo_Estado != null ) )
								if($orden->OrdenTrabajo_Estado == 1 )
									echo '<option value="-1" selected disabled>Sin Comenzar</option>';
								elseif($orden->OrdenTrabajo_Estado== 2 )
									echo '<option value="-1" selected disabled>Comenzo</option>';
								elseif($orden->OrdenTrabajo_Estado == 3 )
									echo '<option value="-1" selected disabled>Suspndida</option>';
								elseif($orden->OrdenTrabajo_Estado == 4 )
									echo '<option value="-1" selected disabled>Termino</option>';
								else
									echo '<option value="0" selected disabled>Seleccione</option>';
							?>
								<!-- <option value="0" selected>Seleccione</option> -->
								<option value="1" >Sin Comenzar</option>
								<option value="2" >Comenzo</option>
								<option value="3" >Suspndida</option>
								<option value="4" >Termino</option>
								
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-6">
							<label for="observaciones_tareas">Observación</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="observaciones_tareas" type="text"  name="observaciones_tareas" class="form-control input-sm" 
								<?php 
								if(isset($orden))
									echo  'value= "'.$orden->OrdenTrabajo_Observacion.'"';
								?> 
								>
							</div>
						</div>
					</div>

					<br><br>
					<input type="hidden" name="id" id="id" value="<?php if(isset($orden)) echo $orden->OrdenTrabajo_Id; else echo "-1";  ?>" style="display: none">
					<div class="row">
						<div class="col-md-4">
							<button type="submit" name="enviar" style="width:100%" class="btn btn-success">Guardar</button>
						</div>
						<div class="col-md-4">
							<button type="reset"  style="width:100%" class="btn btn-warning">Cancelar</button>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("orden_trabajo");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<script src="<?php echo base_url();?>js/validations/validations_agregar_orden_trabajo.js"></script>


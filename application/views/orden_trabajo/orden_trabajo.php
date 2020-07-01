		<div class="block-header">
			<h2>Lista de Ordenes de Trabajo</h2>
		</div>
		<div class="row">
			<div class="card">
				<div class="card-header">
					<h2>Lista de Tareas</h2>
					<div class="col-md-9">
					</div>

					<div class="col-md-1">
						<a href="<?php echo base_url('orden_trabajo/agregar_orden_trabajo'); ?>">
							<button  type="button" class="btn btn-float bgm-green waves-effect"  ><i class="zmdi zmdi-plus"></i></button>
						</a>
					</div>
					<div class="col-md-1">
						<a href="" data-toggle="dropdown">
							<button  type="button" class="btn btn-float bgm-blue waves-effect" ><i class="zmdi zmdi-more-vert"></i></button>
						</a>
						<ul class="dropdown-menu dropdown-menu-right">
							<li>
								<a href="javascript:void(0)" class="aaff" id="nuevo_evento">Nueva tarea</a>
							</li>
							<li>
								<a href="javascript:void(0)" class="listar_tareas" id="lista_de_tareas">Informe en Pantalla</a>
							</li>
							<li>
								<a href="javascript:void(0)" class="mostrador_filtros_informe_tareas" id="fltros_de_tareas">Descargar Informe</a>
							</li>
						</ul>
					</div>

				</div>
				<!-- al cambiar el id de la tabla se rompe el buscar-->
				<table id="data-table-orden" class="table table-striped table-vmiddle">
					<thead>
						<tr>
							<th data-column-id="id">ID</th>
							<th data-column-id="tarea">Tarea</th>
							<th data-column-id="cliente" data-visible="false">Cliente</th>
							<th data-column-id="cantidad">Direccion</th>
							<th data-column-id="nConexion" data-visible="false">N° Conexion</th>
							<th data-column-id="tecnico">Técnico</th>
							<th data-column-id="codMaterial1" data-visible="false">Cod. Material 1</th>
							<th data-column-id="codMaterial2" data-visible="false">Cod. Material 2</th>
							<th data-column-id="codMaterial3" data-visible="false">Cod. Material 3</th>
							<th data-column-id="codMaterial4" data-visible="false">Cod. Material 4</th>
							<th data-column-id="codMaterial5" data-visible="false">Cod. Material 5</th>
							<th data-column-id="estado">Estado</th>
							<th data-column-id="nuevoMedidor" data-visible="false">Nuevo medidor</th>
							<th data-column-id="observacion" data-visible="false">Obs</th>
							<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($consulta as $columna):
					if($columna->OrdenTrabajo_Estado != 1)
						{?>
						<tr>
							<td><?php echo $columna->OrdenTrabajo_Id ?></td>
							<td><?php echo $columna->OrdenTrabajo_Tarea ?></td>
							<td><?php echo $columna->OrdenTrabajo_Cliente ?></td>
							<td><?php echo str_replace( "%20", " ", $columna->OrdenTrabajo_Direccion); ?></td>
							<td><?php echo $columna->OrdenTrabajo_NConexion ?></td>
							<td><?php echo $columna->OrdenTrabajo_Tecnico ?></td>
							<td><?php echo $columna->OrdenTrabajo_CodigoMaterial1 ?></td>
							<td><?php echo $columna->OrdenTrabajo_CodigoMaterial2 ?></td>
							<td><?php echo $columna->OrdenTrabajo_CodigoMaterial3 ?></td>
							<td><?php echo $columna->OrdenTrabajo_CodigoMaterial4 ?></td>
							<td><?php echo $columna->OrdenTrabajo_CodigoMaterial5 ?></td>
							<td><?php if($columna->OrdenTrabajo_Estado == 1)
										echo "Sin Empezar";
									  elseif($columna->OrdenTrabajo_Estado == 2)
									  	echo "Comenzo";	
									elseif($columna->OrdenTrabajo_Estado == 3)
									  	echo "Suspendida";	
									  elseif($columna->OrdenTrabajo_Estado == 4)
									  	echo "Terminado";	
									  else echo "Sin Estado";	
							?></td>
							<td><?php echo $columna->OrdenTrabajo_NewConexion ?></td>
							<td><?php echo $columna->OrdenTrabajo_Observacion ?></td>
							<td></td>
						</tr>
					<?php
					}
					endforeach; ?>
					</tbody>
				</table>
			</div>
	</div>
			<div class="modal fade" id="modal_listado_tareas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
								<div class="modal-dialog" style="width:90%">
									<div class="modal-content" style="width:90%">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title" id="myModalLabel">Listado de Tareas</h4>
										</div>
										<div class="modal-body">
											<div id="resutado_lista_tareas">

											</div>
										</div>
										<div class="modal-footer">
											<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
											<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
										</div>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>

							 <div class="modal fade" id="modal_informe_tareas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
								<div class="modal-dialog">
									<form id="formulario_tareas_filtradas" method="POST" action="<?php echo base_url("reportes/crear_pdf_tareas_filtradas"); ?>">
									<div class="modal-content" >
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title" id="myModalLabel">Listado de Tareas</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-md-6">
													<label for="inputDate">Fecha de Inicio</label>
													<div class="input-group form-group">
														<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
														<div class="col-md-6 col-xs-6">
															<div class="dtp-container dropdown fg-line open">
																<input id="inputDate" type="text" name="fechaInicio" class="form-control input-sm date-picker" data-toggle="dropdown" >
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<label for="inputDate">Fecha de Fin</label>
													<div class="input-group form-group">
														<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
														<div class="col-md-6 col-xs-6">
															<div class="dtp-container dropdown fg-line open">
																<input id="inputDate" type="text" name="fechafin" class="form-control input-sm date-picker" data-toggle="dropdown" >
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label for="select_persona_raliza_evento_nuevo">Realizador:</label>
													<div class="fg-line select">
														<select id="realizador_tareas" type="text" name="realizador"  class="chosen">
															<option value="0" selected>Seleccione</option>
															<option value="David" >David</option>
															<option value="Claudio">Claudio</option>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<label for="select_persona_raliza_evento_nuevo">Estado:</label>
													<div class="fg-line select">
														<select id="estado_tareas" type="text" name="estado_tareas"  class="chosen">
															<option value="0" selected disabled>Seleccione</option>
															<option value="1" >Sin Comenzar</option>
															<option value="2" >Comenzo</option>
															<option value="3" >Suspndida</option>
															<option value="4" >Termino</option>

														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
											<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
											<button type="submit" class="btn btn-success" id="crear_pdf_tareas_filtradas">Crear PDF</button>
										</div>
									</div>
									<!-- /.modal-content -->
									</form>
								</div>
								<!-- /.modal-dialog -->
							</div>


	 <div class="modal fade" id="nuevo_evento_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">        <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Nueva Orden de Trabajo</h4>
			</div>
			<div class="modal-body">
				<div class="row"  id="nueva_tarea_div">
					<div class="row">
						<div class="col-md-6">
							<label for="titulo_evento_nuevo">Tarea</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<div class="fg-line">
									<input id="titulo_evento_nuevo" type="text" name="titulo_evento_nuevo" class="form-control input-sm" placeholder="Tarea" >
								</div>
							</div>
						</div>
					</div>
					<div class="row">
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
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="aclaracion_evento_nuevo">Direccion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<div class="fg-line">
									<input id="aclaracion_evento_nuevo" type="text" name="aclaracion_evento_nuevo" class="form-control input-sm"  >    
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="inputTecnico">Tecnico asigando</label>
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
						
					</div>

						<!-- <div class="row">
							<div class="col-md-6">
								<label for="select_persona_raliza_evento_nuevo">Persona que la realizara:</label>
								<div class="fg-line select">
									<select id="select_persona_raliza_evento_nuevo" type="text" name="select_persona_raliza_evento_nuevo"  class="chosen">
										<option value="0" selected>Seleccione</option>
										<option value="1" >Juancito</option>
										<option value="2">Marisa</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<input type="color" id="html5colorpicker" value="#ff0000" style="width:85%;">
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
						
						<input type="hidden" name="id_evento_nuevo" id="id_evento_nuevo" value="-1" >

						
						<div class="row">
							<!-- <div class="col-md-6">
								<label for="porcentaje_evento_nuevo">Porcentaje</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
									<div class="fg-line">
										<input id="porcentaje_evento_nuevo" type="number" min="0" max="100" name="porcentaje_evento_nuevo" class="form-control input-sm"  >    
									</div>
								</div>
							</div> -->
							<div class="col-md-6">
								<label for="estado_evento_nuevo">Estado de evento:</label>
									<select id="estado_evento_nuevo" type="text" name="estado_evento_nuevo"  class="chosen" >
										<option value="0" selected>Seleccione</option>
										<option value="1" >Sin Comenzar</option>
										<option value="2" >Comenzo</option>
										<option value="3" >Suspndida</option>
										<option value="4" >Termino</option>
										
									</select>
							</div>
						</div>
						<!-- <div class="row">
							<div class="col-md-6">
								<label for="duracion_evento_nuevo">Duracion estimada:</label>
								<div class="fg-line select">
									<select id="duracion_evento_nuevo" type="text" name="duracion_evento_nuevo"  class="chosen" required>
										<option value="0" selected>Seleccione</option>
										<option value="1" >5 dias</option>
										<option value="2" >3 dias</option>
										<option value="3" >1 dias</option>
										<option value="4" >5 hora</option>
										<option value="5" >3 hora</option>
										<option value="6" >1 hora</option>
										
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<!-- selección múltiple ->
								<label for="select_material">Materiales a utilizar:</label>
									<div class="fg-line select">
									<select name="miselect[]" multiple id="select_material" class="chosen" data-placeholder="Elige los materiales" >
										<option value="azul">Azul</option>
										<option value="amarillo">Amarillo</option>
										<option value="blanco">Blanco</option>
										<option value="gris">Gris</option>
										<option value="marron">Marrón</option>
										<option value="naranja">Naranja</option>
										<option value="negro">Negro</option>
										<option value="rojo">Rojo</option>
										<option value="verde">Verde</option>
										<option value="violeta">Violeta</option>
									</select>
									</div>
							</div>
						</div> -->
					</div>
			</div>
				<div class="modal-footer">
					<button type="button"  class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="button"  class="btn btn-success" id="guadar_evento_nuevo_modal" name="guadar_evento_nuevo_modal"> Guardar</button>
				</div>
			</div>
		</div>
	</div>
			
	<!-- ./ contents -->				

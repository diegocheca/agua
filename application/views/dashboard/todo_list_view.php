<div class="col-sm-6" >
		<!-- Todo Lists -->
		<div id="todo-lists" style="height: 520px; overflow-y: scroll;">
			<div class="tl-header">
				<h2>Todo Lists</h2>
				<small>Lista de Tareas</small>
				<ul class="actions actions-alt">
					<li class="dropdown">
						<a href="" data-toggle="dropdown" aria-expanded="false">
							<i class="zmdi zmdi-more-vert"></i>
						</a>
						
						<ul class="dropdown-menu dropdown-menu-right">
							<li>
								<a href="">Refresh</a>
							</li>
							<li>
								<a href="javascript:void(0)" class="aaf" id="users_id">Nueva tarea</a>
								<a data-target="#nueva_tarea_modal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#nueva_tarea_modal">HELP</a>
								
							</li>
							<li>
								<a href="">Widgets Settings</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="clearfix"></div>
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">Graficos de clientes</h4>
						</div>
						<div class="modal-body">
						   <div class="card z-depth-2">
								<div class="card-header bgm-lightgreen">
									<h2>Clientes dados de Baja</h2>
								</div>
								<div class="card-body card-padding text-center">
									<div class="easy-pie sec-pie-2 m-b-15" data-percent="<?php echo $bajas;?>">
										<div class="percent"><?php echo $bajas;?></div>
										<div class="pie-title">Clientes dados de Baja</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
	
			<div class="tl-body">
				<div id="add-tl-item" class="">
					<i class="add-new-item md md-add" style="background-color:black; color:white">+</i>
					<div class="add-tl-body">
						<textarea placeholder="Escribir la tarea..." id="cuerpo_de_tarea"></textarea>
						<div class="add-tl-actions">
							<a href="" data-tl-action="dismiss"><i class="zmdi zmdi-minus"></i></a>
							<a href="" data-tl-action="save"><i class="zmdi zmdi-plus" id="agregar_tarea_nueva"></i></a>
						</div>
					</div>
				</div>
				<?php
				if(isset($tareas) && ($tareas!= false))
				{
					foreach ($tareas as $key) {
						echo '<div class="checkbox media">
								<div class="pull-right">
									<ul class="actions actions-alt">
										<li class="dropdown">
											<a href="" data-toggle="dropdown">
												<i class="zmdi zmdi-more-vert"></i>
											</a>
											<ul class="dropdown-menu dropdown-menu-right">
												<li><a data-target="#myModal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal">Borrar</a></li>
												<li><a href="">Terminar</a></li>
											</ul>
										</li>
									</ul>
								</div>
								<div class="media-body">
									<label>
										<input type="checkbox">
										<i class="input-helper"></i>
										<span>'.$key->Tarea_Cuerpo.'</span>
									</label>
								</div>
							</div>';
					}
				}
				else echo '<div class="alert alert-danger">
								Sin tareas guardadas
							</div>';
				?>
				
				<div class="checkbox media">
					<div class="pull-right">
						<ul class="actions actions-alt">
							<li class="dropdown">
								<a href="" data-toggle="dropdown">
									<i class="zmdi zmdi-more-vert"></i>
								</a>
								
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="">Delete</a></li>
									<li><a href="">Archive</a></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="media-body">
						<label>
							<input type="checkbox">
							<i class="input-helper"></i>
							<span>Duis vitae nibh molestie pharetra augue vitae</span>
						</label>
					</div>
				</div>
				<div class="checkbox media">
					<div class="pull-right">
						<ul class="actions actions-alt">
							<li class="dropdown">
								<a href="" data-toggle="dropdown">
									<i class="zmdi zmdi-more-vert"></i>
								</a>
								
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="">Delete</a></li>
									<li><a href="">Archive</a></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="media-body">
						<label>
							<input type="checkbox">
							<i class="input-helper"></i>
							<span>Suspendisse potenti. Cras dolor augue, tincidunt sit amet lorem id, blandit rutrum libero</span>
						</label>
					</div>
				</div>
				
				<div class="checkbox media">
					<div class="pull-right">
						<ul class="actions actions-alt">
							<li class="dropdown">
								<a href="" data-toggle="dropdown">
									<i class="zmdi zmdi-more-vert"></i>
								</a>
								
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="">Delete</a></li>
									<li><a href="">Archive</a></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="media-body">
						<label>
							<input type="checkbox">
							<i class="input-helper"></i>
							<span>Proin luctus dictum nisl id auctor. Nullam lobortis condimentum arcu sit amet gravida</span>
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="nueva_tarea_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Agregando una Tarea</h4>
				</div>
				<div class="modal-body">
					<div class="row"  id="nueva_tarea_div">
						<div class="row">
							<div class="col-md-6">
								<label for="bonificacion">Persona que la realizara:</label>
								<div class="fg-line select">
									<select id="select_persona_raliza_tarea" type="text" name="select_persona_raliza_tarea" class="form-control input-sm" required>
										<option value="1" selected>Juancito</option>
										<option value="2">Marisa</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<label for="monto_pagar_con_bonificacion">Comienzo:</label>
								<div class="dtp-container dropdown fg-line open">
									<input id="inputDate" type="text" name="fechaEmision" class="form-control input-sm date-picker" value="<?php
									$fecha = date('Y-m-j');
									$nuevafecha = strtotime ( '+10 day' , strtotime ( $fecha ) ) ;
									$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
									 
									echo $nuevafecha;
									 ?>"  data-toggle="dropdown" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label for="inputConexionId">Materiales</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
									<div class="fg-line">
										<input id="materiales_tarea" type="text" name="materiales_tarea" class="form-control input-sm"  >    
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<label for="inputConexionId">Aclaracion</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
									<div class="fg-line">
										<input id="materiales_tarea" type="text" name="materiales_tarea" class="form-control input-sm"  >    
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label for="bonificacion">Duracion estimada:</label>
								<div class="fg-line select">
									<select id="select_persona_raliza_tarea" type="text" name="select_persona_raliza_tarea" class="form-control input-sm" required>
										<option value="1" selected>5 dias</option>
										<option value="1" selected>3 dias</option>
										<option value="1" selected>1 dias</option>
										<option value="1" selected>5 hora</option>
										<option value="1" selected>3 hora</option>
										<option value="1" selected>1 hora</option>
										
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<!-- selección múltiple ->
								<select name="miselect[]" id="select_material" class="chosen" data-placeholder="Elige tus colores favoritos" style="width:90%;display:block" multiple[] >
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
					</div>
				</div>
				<div class="modal-footer">
					<button type="button"  class="btn btn-primary" data-dismiss="modal">Agregar</button>
				</div>
			</div>
		</div>
	</div> 
<script type="text/javascript">
	 function borrando_tareas(id){
		if(confirm('¿Estas seguro que desea borra esta tarea?'))
			return true;
		else
			return false;
	}
</script>
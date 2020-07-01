	<!-- contents -->
		<!-- Titulos y botones superiores -->
		<div class="block-header">
			<h2>Lista de Ordenes de Trabajo</h2>
	                        
				<ul class="actions">
					<li>
						+<?php echo anchor('orden_trabajo/agregar_orden_trabajo','<i class="md md-add"></i>'); ?>
					</li><li>
						<a href="">
							-<i class="md md-trending-up"></i>
						</a>
					</li>
					<li>
					   <a href="">
							*<i class="md md-done-all"></i>                    
						</a>
					</li>
					<li class="dropdown">
						<a href="" data-toggle="dropdown">
							/<i class="md md-more-vert"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-right">
							<li>
								<a href="">Refresh</a>
							</li>
							<li>
								<a href="">Manage Widgets</a>
							</li>
							<li>
								<a href="">Widgets Settings</a>
							</li>
						</ul>
					</li>
				</ul>
		</div>
		<!-- ./ Titulo y botones superiores -->
	<div class="row">
		<!-- fin de agregar clientes -->
		<div class="card">
			<div class="card-header">
				<h2>Lista de bonificaciones</h2>
			</div>
			<!-- al cambiar el id de la tabla se rompe el buscar-->
			<table id="data-table-orden" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<th data-column-id="id" data-visible="false">ID</th>
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
						<th data-column-id="observacion" data-visible="false">Obs</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($consulta as $columna):?>
					<tr>
						<td><?php echo $columna->OrdenTrabajo_Id ?></td>
						<td><?php echo $columna->OrdenTrabajo_Tarea ?></td>
						<td><?php echo $columna->OrdenTrabajo_Cliente ?></td>
						<td><?php echo $columna->OrdenTrabajo_Direccion ?></td>
						<td><?php echo $columna->OrdenTrabajo_NConexion ?></td>
						<td><?php echo $columna->OrdenTrabajo_Tecnico ?></td>
						<td><?php echo $columna->OrdenTrabajo_CodigoMaterial1 ?></td>
						<td><?php echo $columna->OrdenTrabajo_CodigoMaterial2 ?></td>
						<td><?php echo $columna->OrdenTrabajo_CodigoMaterial3 ?></td>
						<td><?php echo $columna->OrdenTrabajo_CodigoMaterial4 ?></td>
						<td><?php echo $columna->OrdenTrabajo_CodigoMaterial5 ?></td>
						<td><?php if($columna->OrdenTrabajo_Estado === 1)
									echo "Terminado";
								  else
								  	echo "Incompleto";	
						?></td>
						<td><?php echo $columna->OrdenTrabajo_Observacion ?></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
			
	<!-- ./ contents -->				

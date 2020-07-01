	<!-- contents -->
		<!-- Titulos y botones superiores -->
		<div class="block-header">
			<h2>Lista de mediciones</h2>
	                        
				<ul class="actions">
					<li>
						+<?php echo anchor('mediciones/agregar_medicion','<i class="md md-add"></i>'); ?>
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
				<h2>Lista de Mediciones</h2>
			</div>
			<!-- al cambiar el id de la tabla se rompe el buscar-->
			<table id="data-table-mediciones-a-aprovar" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<!--hay que cambiar los id???-->
						<th data-column-id="sku" data-visible="false">ID</th>
						<th data-column-id="conexion_id">Conexion Id</th>
						<th data-column-id="mes">Mes</th>
						<th data-column-id="año">Año</th>
						<th data-column-id="anterior" data-visible="false">Anterior</th>
						<th data-column-id="actual">Actual</th>
						<th data-column-id="metros" data-visible="false">Metros</th>
						<th data-column-id="excedente" data-visible="false">Excedente</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($consulta as $columna):?>
					<tr>
						<td><?php echo $columna->Medicion_Id; ?></td>
						<td><?php echo $columna->Medicion_Conexion_Id; ?> </td>
						<td><?php echo $columna->Medicion_Mes; ?> </td>
						<td><?php echo $columna->Medicion_Anio; ?> </td>
						<td><?php echo $columna->Medicion_Anterior; ?> </td>
						<td><?php echo $columna->Medicion_Actual; ?> </td>
						<td><?php echo $columna->Medicion_Mts; ?> </td>
						<td><?php echo $columna->Medicion_Excedente; ?> </td>
						<td></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
			
	<!-- ./ contents -->				

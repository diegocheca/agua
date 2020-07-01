	<!-- contents -->
		<!-- Titulos y botones superiores -->
		<div class="block-header">
			<h2>Inventario de Medidores</h2>
	                        
				<ul class="actions">
					<li>
						+<?php echo anchor('tipos_medidores/agregar_tipo','<i class="md md-add"></i>'); ?>
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
		<?php
		 if (isset($mensaje))
		echo $mensaje;
	?>
			<div class="card-header">
				<h2>Lista de Productos</h2>
			</div>
			<table id="data-table-tipos_medidores" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<th data-column-id="sku" data-visible="false">ID</th>
						<th data-column-id="Codigo">Marca</th>
						<th data-column-id="producto">Modelo</th>
						<th data-column-id="cantidad">Cantidad</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($consulta as $columna):?>
					<tr>
						<td><?php echo $columna->TMedidor_Id ?></td>
						<td><?php echo $columna->TMedidor_Marca ?></td>
						<td><?php echo $columna->TMedidor_Modelo ?></td>
						<td><?php echo  $columna->TMedidor_Cantidad ?></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
			
	<!-- ./ contents -->				

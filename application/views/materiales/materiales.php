	<!-- contents -->
		<!-- Titulos y botones superiores -->
		<div class="block-header">
			<h2>Lista de Materiales</h2>
			<!-- 	<ul class="actions">
					<li>
						+<?php echo anchor('materiales/agregar_materiales','<i class="md md-add"></i>'); ?>
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
				</ul> -->
		</div>
		<!-- ./ Titulo y botones superiores -->
	<div class="row">
		<!-- fin de agregar clientes -->
		<div class="card">
			<div class="card-header">
				<h2>Lista de Materiales</h2>
				<div class="col-md-9">
				</div>

				<div class="col-md-1">
					<a href="<?php echo base_url('materiales/agregar_materiales'); ?>">
						<button  type="button" class="btn btn-float bgm-green waves-effect"  ><i class="zmdi zmdi-plus"></i></button>
					</a>
				</div>
				<div class="col-md-1">
					<a href="" data-toggle="dropdown">
						<button  type="button" class="btn btn-float bgm-blue waves-effect" ><i class="zmdi zmdi-more-vert"></i></button>
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
				</div>

			</div>
			<!-- al cambiar el id de la tabla se rompe el buscar-->
			<table id="data-table-materiales" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<!--hay que cambiar los id???-->
						<th data-column-id="id" data-visible="false">ID</th>
						<th data-column-id="codigo">Codigo</th>
						<th data-column-id="descripcion">Descripcion</th>
						<th data-column-id="cantidad">Cantidad</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($consulta as $columna):?>
					<tr>
						<td><?php echo $columna->Materiales_Id ?></td>
						<td><?php echo $columna->Materiales_Codigo ?></td>
						<td><?php echo $columna->Materiales_Descripcion ?></td>
						<td><?php echo $columna->Materiales_Cantidad ?></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
			
	<!-- ./ contents -->				

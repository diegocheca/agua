	<!-- contents -->
		<!-- Titulos y botones superiores -->
		<div class="block-header">
			<h2>Lista de usuarios</h2>
				<!-- <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4"> -->
				
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
				<h2>Lista de usuarios</h2>
				<div class="col-md-9">
				</div>

				<div class="col-md-1">
					<a href="<?php echo base_url('Usuarios/agregar_usuario'); ?>">
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
			<table id="data-table-usuarios" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<!--hay que cambiar los id???-->
						<th data-column-id="sku">ID</th>
						<th data-column-id="Codigo">Usuario</th>
						<th data-column-id="producto" data-visible="false">Password</th>
						<th data-column-id="email" data-visible="false">Email</th>
						<th data-column-id="rol">Rol</th>
						<th data-column-id="nombre">Nombre</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($consulta as $columna):?>
					<tr>
						<td><?php echo $columna->id ?></td>
						<td><?php echo $columna->usuario ?></td>
						<td><?php echo $columna->password ?></td>
						<td><?php echo $columna->email ?></td>
						<td><?php echo $columna->rol ?></td>
						<td><?php echo $columna->nombre ?></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
			
	<!-- ./ contents -->				

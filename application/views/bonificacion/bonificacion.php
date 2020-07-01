	<!-- contents -->
		<!-- Titulos y botones superiores -->
		<div class="block-header">
			<h2>Lista de bonificaciones pendientes de aprobacion</h2>
	          <!--               
				<ul class="actions">
					<li>
						<!-- +<?php echo anchor('bonificacion/agregar_bonificacion','<i class="md md-add"></i>'); ?> ->
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
				<h2>Lista de Bonificaciones</h2>
				<div class="col-md-9">
				</div>

				<div class="col-md-1">
					<a href="<?php echo base_url('bonificacion/agregar_bonificacion'); ?>">
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
			<table id="data-table-bonificacion" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<!--hay que cambiar los id???-->
						<th data-column-id="sku" data-visible="false">ID</th>
						<th data-column-id="factura_id">Facutura ID</th>
						<th data-column-id="monto">Monto</th>
						<th data-column-id="porcentaje">Porcentaje</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($consulta as $columna):?>
					<tr>
						<td><?php echo $columna->Bonificacion_Id ?></td>
						<td><?php echo $columna->Bonificacion_Factura_Id ?></td>
						<td><?php echo $columna->Bonificacion_Monto ?></td>
						<td><?php echo $columna->Bonificacion_Porcentaje ?></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
			
	<!-- ./ contents -->				

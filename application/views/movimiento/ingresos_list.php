		<div class="block-header">
			<h2>Listado de Egreso</h2>
				<!-- <ul>
					<li>
						<?php echo anchor('inventario/agregar_producto','<button type="button" class="btn btn-primary" >AGREGAR</button>'); ?>
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

		<!--MODALE DE GRAFICOS-->
		


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
							<div class="easy-pie sec-pie-2 m-b-15" data-percent="<?php echo "10";?>">
								<div class="percent"><?php echo "50";?></div>
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
        <!-- /.modal-dialog -->
    </div>
	<!-- FIN de MODAL-->


	<div class="row">
		<!-- fin de agregar clientes -->
		<div class="card">
				<div class="card-header">
				<h2>Lista de Egresos</h2>
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
			<table id="data-table-movimientos" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<th data-column-id="sku" data-visible="false">ID</th>
						<th data-column-id="tipo">Tipo</th>
						<th data-column-id="monto">Monto </th>
						<th data-column-id="pago_id">N° de pago</th>
						<th data-column-id="fecha">Fecha</th>
						<th data-column-id="quien">Cobrado por</th>
						<th data-column-id="revisado">Revisado</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($consulta as $columna):?>
					<tr>
						<td><?php echo $columna->Mov_Id ?></td>
						<td><?php  if($columna->Mov_Tipo == 1)
						echo "<font color='green'>Ingreso</font>";
						elseif ($columna->Mov_Tipo == 2) {
							echo "Egreso";
						  }  ?></td>
						<td><?php echo "$" . round($columna->Mov_Monto,3);?></td>
						<td><?php echo  $columna->Mov_Pago_Id ?></td>
						<td><?php echo date( "d/m/Y H:i:s", strtotime( $columna->Mov_Timestamp) ) ?></td>
						<td><?php echo $columna->Mov_Quien;?></td>
						<td><?php echo $columna->Mov_Revisado?></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal fade" id="ver_ropa_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Detalles de la prenda</h4>
				</div>
				<div class="modal-body">
				<form method="POST" action="<?php echo base_url("inventario/editar_prenda_desde_modal"); ?>">
					<div clas="row">
					<div id="detalles_de_prenda_modal">
					</div>
						
					<br>
					</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-primary">Modificar Prenda</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<!-- ./ contents -->

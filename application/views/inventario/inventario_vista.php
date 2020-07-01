	<!-- contents -->
		<!-- Titulos y botones superiores -->
		<div class="block-header">
			<h2>Inventario de Medidores</h2>
	                        
				<ul class="actions">
					<li>
						+<?php echo anchor('inventario/agregar_producto','<i class="md md-add"></i>'); ?>
					</li><li>
						<a href="">
							<a data-target="#myModal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal"><i class="zmdi zmdi-trending-up"></i></a>
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
		<?php
		 if (isset($mensaje))
		echo $mensaje;
	?>
			<div class="card-header">
				<h2>Lista de Productos</h2>
			</div>
			<table id="data-table-command" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<th data-column-id="sku" data-visible="false">ID</th>
						<th data-column-id="Codigo">ID</th>
						<th data-column-id="producto">Producto</th>
						<th data-column-id="cantidad">Fecha Instalacion</th>
						<th data-column-id="vendidos">Estado</th>
						<th data-column-id="stok">Persona</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($consulta as $columna):?>
					<tr>
						<td><?php echo $columna->Medidor_Id ?></td>
						<td><?php echo $columna->Medidor_Codigo ?></td>
						<td><?php echo $columna->TMedidor_Marca." - ".$columna->TMedidor_Modelo ?></td>
						<td><?php echo date( "d/m/Y", strtotime( $columna->Medidor_FechaInstalacion) ) ?></td>
						<td><?php if ($columna->Medidor_EnReparacion== 1) 
						echo '<font color="red">En reparacion</font>';
						else echo '<font color="green">Disponible</font>';?>
						</td>
						<td><?php echo $columna->Medidor_Observacion?></td>
						<td>Sin Datos</td>
						<td>S/. <?php echo $columna->Medidor_CantIntervenido ?></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
			
	<!-- ./ contents -->				

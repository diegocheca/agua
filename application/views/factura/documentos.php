	<!-- ./ breadcrumb -->

	<!-- Titulos y botones superiores -->
	<div class="block-header">
		<h2>Lista de Facturas Emitidas</h2>
		
        <div class="modal fade" id="modalWider" tabindex="-1" role="dialog" aria-hidden="true">
        	<div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                    	<iframe src="" width="100%" height="670" frameborder="0"></iframe>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-success waves-effect waves-button waves-float">Imprimir</a>
                        <button type="button" class="btn btn-link waves-effect waves-button waves-float" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
        	</div>
        </div>                
			<ul class="actions">
				<li>
					<?php echo anchor('facturar/crear','<i class="zmdi zmdi-plus"></i>'); ?>
				</li><li>
					<a href="">
						<i class="zmdi zmdi-trending-up"></i>
					</a>
				</li>
                <li class="dropdown">
					<a href="" data-toggle="dropdown">
						<i class="zmdi zmdi-more-vert"></i>
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

	
	<!-- contents -->
	
		<!-- fin de agregar clientes -->
		<div class="card">
			<div class="card-header">
				<h2>Lista de Facturas Emitidas</h2>
			</div>
			<div class="table-responsive">
				<table id="data-table-command-docs" class="table table-striped table-vmiddle">
					<thead>
						<tr>
							<th data-column-id="id" data-visible="false">ID</th>
							<th data-column-id="idunico">Codigo Barra</th>
							<th data-column-id="conexion">Id Conexion</th>
							<th data-column-id="idcliente" data-visible="false">Id Cliente</th>
							<th data-column-id="razon-social"> Cliente</th>
							<th data-column-id="monto">Monto</th>
							<th data-column-id="fecha">Fecha</th>
							<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($documentos as $columna): ?>
						<tr>
							<td><?php echo $columna->id; ?></td>
							<td><?php echo $columna->id_factura; ?></td>
							<td><?php echo $columna->Factura_Conexion_Id; ?></td>
							<td><?php echo $columna->id_cliente; ?></td>
							<td><?php echo $columna->Cli_RazonSocial; ?></td>
							<td><?php echo $columna->monto; ?></td>
							<td><?php echo  date( "d/m/Y", strtotime( $columna->fecha_emision) ); ?></td>
							<td><?php echo ($columna->estado == 0) ? "-active":"";  ?></td>
						</tr>
					<?php endforeach?>
					</tbody>
				</table>
			</div>
		</div>

	<!-- ./ contents -->				
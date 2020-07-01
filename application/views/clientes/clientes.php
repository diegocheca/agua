	<!-- ./ breadcrumb -->

	<!-- Titulos y botones superiores -->
	<div class="block-header">
		<h2>Lista de Clientes</h2>
			<ul class="actions">
				<li>
					<?php echo anchor('clientes/agregar/','+<i class="zmdi zmdi-add"></i>'); ?>
				</li>
				<li>
					<a data-target="#myModal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal"><i class="zmdi zmdi-trending-up"></i></a>
				</li>
                <li class="dropdown">
					<a href="" data-toggle="dropdown">
						<i class="zmdi zmdi-more-vert"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li>
							<a href="">Refrescar</a>
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
        <!-- /.modal-dialog -->
    </div>
	<!-- FIN de MODAL-->
	<!-- contents -->
	
		<!-- fin de agregar clientes -->
		<div class="card">
			<div class="card-header">
				<h2>Lista de Clientes</h2>
			</div>
			<div class="table-responsive">
				<table id="data-table-clientes" class="table table-striped table-vmiddle">
					<thead>
						<tr>
							<th data-column-id="id">N Socio</th>
							<th data-column-id="tpersona" data-visible="false">Tipo Persona</th>
							<th data-column-id="ruc">DNI</th>
							<th data-column-id="rsocial">Razon Social</th>
							
							<th data-column-id="email">Email</th>
							<th data-column-id="telefono">Teléfono</th>
							<th data-column-id="tienda">N CLiente</th>
							<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Accion</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($clientes as $columna): ?>
						<tr>
							<td><?php echo $columna->Cli_Id; ?></td>
							<td><?php echo $columna->Cli_TipoPersona; ?></td>
							<td><?php echo $columna->Cli_NroDocumento; ?></td>
							<td><?php echo $columna->Cli_RazonSocial; ?></td>
							
							<td><?php echo $columna->Cli_Email; ?></td>
							<td><?php echo $columna->Cli_Telefono; ?></td>
							<td><?php echo $columna->Cli_Id; ?></td>
							<td></td>
						</tr>
					<?php endforeach?>
					</tbody>
				</table>
			</div>
		</div>
			
	<!-- ./ contents -->				
	<!-- ./ breadcrumb -->

	<!-- Titulos y botones superiores -->
	<div class="block-header">
		<h2>Lista de Conexiones</h2>
                        
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
    <!-- </div> -->
	<!-- FIN de MODAL-->
	<!-- contents -->
	
		<!-- fin de agregar clientes -->
		<div class="card">
			<div class="card-header">
				<h2>Lista de Conexiones</h2>
			</div>
			<div class="table-responsive">
				<table id="data-table-conexiones" class="table table-striped table-vmiddle">
					<thead>
						<tr>
							<th data-column-id="id" >N Conexion</th>
							<th data-column-id="orden" >Orden</th>
							<th data-column-id="tpersona">Persona</th>
							<th data-column-id="ruc">Domicilio</th>
							<th data-column-id="rsocial">Categoria</th>
							<th data-column-id="email">Sector</th>
							<th data-column-id="deuda">Deuda</th>
							<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Accion</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($conexiones as $columna): ?>
						<tr>
							<td><?php echo $columna->Conexion_Id; ?></td>

							<td><?php echo $columna->Conexion_UnionVecinal; ?></td>
							<td><?php echo $columna->Cli_RazonSocial; ?></td>
							<td><?php echo $columna->Conexion_DomicilioSuministro; ?></td>
							<td><?php if( ($columna->Conexion_Categoria==1) || ($columna->Conexion_Categoria=="Familiar")) echo "Falimiliar"; else echo "Comercial"; ?></td>
							<td><?php echo $columna->Conexion_Sector; ?></td>
							<td><?php echo "$ ".$columna->Conexion_Deuda; ?></td>
							<td></td>
						</tr>
					<?php endforeach?>
					</tbody>
				</table>
			</div>
		</div>
	
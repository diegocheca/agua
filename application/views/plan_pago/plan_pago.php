	<!-- contents -->
		<!-- Titulos y botones superiores -->
		<div class="block-header">
			<h2>Lista de planes de pago</h2>
			<!-- 	<ul class="actions">
					<li>
						<a href="<?php echo base_url("plan_pago/cargar_vista_agregar");?>"><?php echo '<button type="button"  id="agregar_PlanMedidor_desde_lista" name="agregar_PlanMedidor" class="btn btn-primary waves-effect"> agregar </button>'; ?></a>
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
				</ul>              -->
		</div>
		<!-- ./ Titulo y botones superiores -->
<div class="modal fade" id="myModalPlanMedidor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Creando Plan de Pago</h4>
                </div>
                <div class="modal-body">
	                <div id="PlanPagoNuevo" class="col-md-12">

	                	
	                </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
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
				<h2>Lista de Planes de Pago</h2>
				<div class="col-md-9">
				</div>

				<div class="col-md-1">
					<a href="<?php echo base_url('plan_pago/cargar_vista_agregar'); ?>">
						<button  type="button" class="btn btn-float bgm-green waves-effect"  ><i class="zmdi zmdi-plus"></i></button>
					</a>
				</div>
				<div class="col-md-1">
					<a data-target="#myModal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal">
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
			<table id="data-table-plan_pago" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<th data-column-id="sku" data-visible="false">ID</th>
						<th data-column-id="ConexionId">Conexion Id</th>
						<th data-column-id="MontoTotal">Monto total</th>
						<th data-column-id="MontoPagado">Monto pagado</th>
						<th data-column-id="MontoCuota" data-visible="false">Monto cuota</th>
						<th data-column-id="TotalCuotas">Total de cuotas</th>
						<th data-column-id="Interes" data-visible="false">Interes</th>
						<th data-column-id="CuotaAcutal">Couta actual</th>
						<th data-column-id="Tipo" data-visible="false">Tipo</th>
						<th data-column-id="FechaInicio" data-visible="false">Fecha Inicio</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($consulta as $columna):?>
					<tr>
						<td><?php echo $columna->PlanPago_Id ?></td>
						<td><?php echo $columna->PlanPago_Conexion_Id ?></td>
						<td><?php echo $columna->PlanPago_MontoTotal ?></td>
						<td><?php echo $columna->PlanPago_MontoPagado ?></td>
						<td><?php echo $columna->PlanPago_MontoCuota ?></td>
						<td><?php echo $columna->PlanPago_Coutas ?></td>
						<td><?php echo $columna->PlanPago_Interes ?></td>
						<td><?php echo $columna->PlanPago_CoutaActual ?></td>
						<td><?php echo $columna->PlanPago_Tipo ?></td>
						<td><?php echo $columna->PlanPago_FechaInicio ?></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>


	<!-- ./ contents -->				

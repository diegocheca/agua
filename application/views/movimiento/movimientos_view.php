<div class="block-header">
	<h2>Listado de Movimientos</h2>	
</div>
	<!--MODALE DE GRAFICOS-->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Filtros </h4>
				</div>
				<div class="modal-body">
					<form id="formulario_filtro_sector" method="POST" action="<?php echo base_url('movimientos'); ?>">
						<div class="card-body card-padding text-center">
							<br><br>
							<div class="row">
								<label for="select_tipo_movimiento">Seleccione el tipo:</label>
								<select name="select_tipo_movimiento"  id="select_tipo_movimiento" class="chosen" data-placeholder="Elige el tipo" >
									<option value="-1" selected disabled >Sin Filtro</option>
									<option value="2" >Egreso</option>
									<option value="1" >Ingreso</option>
								</select>
							</div>
							<br><br>
							<div class="row">
								<div class="col-md-6">
									<label>Inicio :</label>
									<input type="date" name="inicio_reporte_pagos" id="inicio_reporte_pagos" min="2018-02-02">
								</div>
								<div class="col-md-6">
									<label>Fin:</label>
									<input type="date" name="fin_reporte_pagos" id="fin_reporte_pagos" max="<?php echo date("Y-m-d"); ?>">
								</div>
							</div>
							<br>
							<br>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6">
							<button class="btn bgm-lime btn-float waves-effect waves-button waves-float waves-circle " id="filtrar_mes_de_medicion" name="filtrar_mes_de_medicion" type="submit"><i class="zmdi zmdi-search"></i></button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="detalles_movimiento_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Detalles del Movimiento </h4>
				</div>
				<div class="modal-body">
					<form id="formulario_filtro_sector" method="POST" action="<?php echo base_url('movimientos'); ?>">
						<div class="card-body card-padding text-center">
							<br><br>
							<div class="row">
								<div class="col-md-6">
									<label>ID:</label>
									<input type="text" name="id_moviemiento" id="id_moviemiento" readonly="">
								</div>
								<div class="col-md-6">
									<label>Tipo:</label>
									<input type="text" name="tipo_moviemiento" id="tipo_moviemiento" readonly="">
								</div>
							</div>
							<br><br>

							<div class="row">
								<div class="col-md-6">
									<label>Monto:</label>
									<input type="text" name="monto_moviemiento" id="monto_moviemiento" readonly="">
								</div>
								<div class="col-md-6">
									<label>Fecha:</label>
									<input type="text" name="fecha_moviemiento" id="fecha_moviemiento" readonly="">
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Codigo:</label>
									<input type="text" name="codigo_moviemiento" id="codigo_moviemiento" readonly="">
								</div>
								<div class="col-md-6">
									<label>Factura:</label>
									<input type="text" name="factura_moviemiento" id="factura_moviemiento" readonly="">
								</div>
							</div>
							<br>
							<br>
							<hr>
							<br>
							<br>
							<div class="row">
								<button class="btn bgm-lime btn-float waves-effect waves-button waves-float waves-circle " id="filtrar_mes_de_medicion" name="filtrar_mes_de_medicion" type="submit"><i class="zmdi zmdi-search"></i></button>
							</div>
						</div>

					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>

<div class="row">
	<div class="card">
		<div class="card-header">
			<h2>Lista de Movimientos</h2>
				<div class="col-md-9">
				</div>
				<div class="col-md-1">
					<a href="" data-toggle="dropdown">
						<button  type="button" class="btn btn-float bgm-blue waves-effect" ><i class="zmdi zmdi-more-vert"></i></button>
					</a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li>
								<a data-target="#myModal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal"> Filtrar <i class="zmdi zmdi-search"></i></a>
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
					<th data-column-id="pago_id">N° de movimiento</th>
					<th data-column-id="fecha">Fecha</th>
					<th data-column-id="quien" data-visible="false">Cobrado por</th>
					<th data-column-id="revisado" data-visible="false">Revisado</th>
					<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
				</tr>
		</thead>
		<tbody>
		<?php 
		//var_dump($consulta[0]["Mov_Id"]);die();
		foreach ($consulta as $columna):?>
			<tr>
				<td><?php echo $columna["Mov_Id"] ?></td>
				<td><?php  if($columna["Mov_Tipo"] == 1)
				echo "<font color='green'>Ingreso</font>";
				elseif ($columna["Mov_Tipo"] == 2) {
					echo "Egreso";
				  }  ?></td>
				<td><?php echo "$" .  number_format(floatval($columna["Mov_Monto"]), 2, '.', '');?></td>
				<td><?php echo  $columna["Mov_Pago_Id"] ?></td>
				<td><?php echo date( "d/m/Y H:i:s", strtotime( $columna["Mov_Timestamp"]) ) ?></td>
				<td><?php echo $columna["Mov_Quien"];?></td>
				<td><?php echo $columna["Mov_Revisado"]?></td>
				<td></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		</table>
	</div>
</div>


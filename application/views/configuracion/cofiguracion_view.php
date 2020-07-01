<!-- contents -->
	<!-- Titulos y botones superiores -->
	<div class="block-header">
		<h2>Configuracion del sistema</h2>
	</div>
	<div class="modal fade" id="configuracion_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Modificando Variable</h4>
				</div>
				<div class="modal-body">
					<div id="datos_variable_modficando">
						<form method="POST" action="<?php echo base_url('configuracion/configuracion_guardar_modificado');?>">
							<div class="row">
								<div class="col-md-6">
									<label for="valor_variable_modificando">Valor</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
										<div class="fg-line">
											<input id="valor_variable_modificando" type="text" maxlength="10" name="valor_variable_modificando" class="form-control input-sm">
										</div>
									</div>
								</div>
								<input id="id_variable_modificando" type="text" name="id_variable_modificando" readonly>
							</div>
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-success" >Guardar</button>
						</form>
					</div>
				</div>
				<div class="modal-footer">
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
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
				<h2>Configuracion de Variables</h2>
			</div>
			<table id="data-table-configuracion" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<th data-column-id="id" data-visible="false">ID</th>
						<th data-column-id="Codigo">Nombre</th>
						<th data-column-id="producto">Valor</th>
						<th data-column-id="nombre">Quien cambio</th>
						<th data-column-id="cantidad">Cuando</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					foreach ($todas_las_variables as $key ) {
						$date = new DateTime($key->Configuracion_Modifcado);
						$date = $date->format('d/m/Y H:i:s');
						echo '<tr>';
								echo '<td>'.$key->Configuracion_Id.'</td>
								<td>'.$key->Configuracion_Nombre.'</td>
								<td>'.$key->Configuracion_Valor.'</td>
								<td>'.$key->nombre.'</td>
								<td>'.$date.'</td>
								<td></td>';
								echo '</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<!-- ./ contents -->
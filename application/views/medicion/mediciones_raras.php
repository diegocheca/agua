			<!-- 	<ul class="actions">
					<li>
						+<?php echo anchor('mediciones/agregar_medicion','<i class="md md-add"></i>'); ?>
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
				<div class="block-header">
			<h2>Lista de mediciones</h2>
		</div>
		<div class="row">
			<div class="card">
				<div class="card-header">
					<h2>Lista de mediciones para el mes: <?php echo $mes_buscado?></h2>
					<div class="col-md-9">
					</div>
					<div class="col-md-1">
						<a href="<?php echo base_url('mediciones/agregar_medicion'); ?>">
							<button  type="button" class="btn btn-float bgm-green waves-effect"  ><i class="zmdi zmdi-plus"></i></button>
						</a>
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
					<!--MODALE DE GRAFICOS-->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 class="modal-title" id="myModalLabel">Filtros </h4>
							</div>
							<div class="modal-body">
								<form id="formulario_filtro_sector" method="POST" action="<?php echo base_url('mediciones/mediciones_a_aprobar'); ?>">
									<div class="card-body card-padding text-center">
										<br><br>
										<div class="row">
											<label for="select_sector_imprimir">Seleccion Sectores:</label>
											<select name="miselect" id="select_sector_imprimir" class="chosen" data-placeholder="Elige los sectores" >
												<option value="-1" selected disabled >Sin Filtro</option>
												<?php 
												foreach ($conexiones_a_imprimir as $key) {
													echo '<option value="'.$key->Conexion_Sector.'" >'.$key->Conexion_Sector.'</option>';
												}
												?>
											</select>
										</div>
										<br><br>
										<div class="row">
											<label for="select_sector_imprimir">Seleccione el mes:</label>
											<select name="mes_select"  id="select_mes_filtrar" class="chosen" data-placeholder="Elige el mes" >
												<option value="-1" selected disabled >Sin Filtro</option>
												<option value="01" >Enero</option>
												<option value="02" >Febrero</option>
												<option value="03" >Marzo</option>
												<option value="04" >Abril</option>
												<option value="05" >Mayo</option>
												<option value="06" >Junio</option>
												<option value="07" >Julio</option>
												<option value="08" >Agosto</option>
												<option value="09" >Semptiembre</option>
												<option value="10" >Octubre</option>
												<option value="11" >Noviembre</option>
												<option value="12" >Diciembre</option>
											</select>
										</div>
										<br><br>
										<div class="row">
											<label for="anio_select_mediciones_raras">Seleccione el año:</label>
											<select name="anio_select_mediciones_raras"  id="anio_select_mediciones_raras" class="chosen" data-placeholder="Elige el año" >
											<option value="-1" selected disabled >Sin Filtro</option>
												<option value="2017" >2017</option>
												<option value="2018" >2018</option>
											</select>
										</div>
										<br><br>
										<div class="row">
											<label for="limite_raro">Seleccione el limite:</label>
											<input name="limite_raro" id="limite_raro" type="number" step="1" value="25"/>
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

				<div class="modal fade" id="modal_modificacion_medicion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 class="modal-title" id="myModalLabel">Aprobando Medicion </h4>
							</div>
							<div class="modal-body">
								<form id="formulario_aprobando_medicion" method="POST" action="<?php echo base_url('nuevo/aprobar_medicion'); ?>">
									<div class="card-body card-padding text-center">
										<input id="id_medicion" type="hidden" name="id_medicion" class="form-control input-sm" >
										<input id="tipo_conexion_input" type="hidden" name="tipo_conexion_input" class="form-control input-sm" >
										<div class="input-group form-group">
											<span class="input-group-addon"><i style="color:orange" class="zmdi zmdi-assignment"></i> Anterior</span>
											<input id="Lectura_Anterior" type="text" name="Lectura_Anterior" class="form-control input-sm">
										</div>
										<br>
										<!-- <div class="input-group form-group">
											<span class="input-group-addon"><i style="color:red" class="zmdi zmdi-help"></i> Observacion</span>
											<input id="observacion_medicion" type="text" name="observacion_medicion" class="form-control input-sm" readonly>
										</div>
										<br> -->

										<div class="input-group form-group">
											<span class="input-group-addon"><i style="color:green" class="zmdi zmdi-assignment"></i> Actual</span>
											<input id="Lectura_Actual" type="number" name="Lectura_Actual" class="form-control input-sm" >
										</div>
										<button class="btn bgm-green btn-float waves-effect waves-button waves-float waves-circle " id="aprobar_medicion" name="aprobar_medicion" type="submit"><i class="zmdi zmdi-save"></i></button>
									</div>
								</form>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
<br>
<br>
			<div class="col-lg-8 col-md-offset-2">
                    <div class="well">
                        <h4>Filtro Actual</h4>
                        <p>Sector:  <?php if($sector != -1)
                        					echo $sector;
                        					else echo "sin filtro";?></p>
                        
                        <p>Mes: <?php if($mes_buscado != -1) 
                        				 echo  $mes_buscado;
                        				 else echo "sin filtro";?></p>


						<p>Año: <?php if($anio != -1) 
										 echo  $anio;
										 else echo "sin filtro";?></p>

						<p>Limite:  <?php  if($limite != -1) 
										echo $limite;
										else echo "sin filtro";?></p>

                    </div>
                </div>

				<table id="data-table-mediciones-raras" class="table table-striped table-vmiddle">
					<thead>
						<tr>
							<!--hay que cambiar los id???-->
							<th data-column-id="sku" >ID</th>
							<th data-column-id="conexion_id">Conexion Id</th>
							<th data-column-id="mes">Mes</th>
							<th data-column-id="año">Año</th>
							<th data-column-id="anterior">Anterior</th>
							<th data-column-id="actual">Actual</th>
							<th data-column-id="metros" >Excedente m3</th>
							<th data-column-id="excedente">Excedente $</th>
							<th data-column-id="tipo_conexion">Categoria $</th>
							
							<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($consulta as $columna):?>
						<tr>
							<td><?php echo $columna->Factura_Id; ?></td>
							<td><?php echo $columna->Factura_Conexion_Id; ?> </td>
							<td><?php echo $columna->Factura_Mes; ?> </td>
							<td><?php echo $columna->Factura_Año; ?> </td>
							<td><?php echo $columna->Factura_MedicionAnterior; ?> </td>
							<td><?php echo $columna->Factura_MedicionActual; ?> </td>
							<td><?php echo $columna->Factura_Excedentem3; ?> </td>
							<td><?php echo $columna->Factura_ExcedentePrecio; ?> </td>
							<td><?php echo $columna->Conexion_Categoria; ?> </td>
							<td></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
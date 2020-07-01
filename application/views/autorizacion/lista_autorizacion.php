
		<div class="block-header">
			<h2>Lista de Auditoria</h2>
		</div>
		<div class="row">
			<div class="card">
				<div class="card-header">
					<h2>Lista de mediciones para el mes: <?php echo $mes_buscado?></h2>
					<div class="col-md-9">
					</div>
					<!-- <div class="col-md-1">
						<a href="<?php echo base_url('mediciones/agregar_medicion'); ?>">
							<button  type="button" class="btn btn-float bgm-green waves-effect"  ><i class="zmdi zmdi-plus"></i></button>
						</a>
					</div> -->
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
												foreach ($sectores as $key) {
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

				<div class="modal fade" id="modal_aprobar_descuento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 class="modal-title" id="myModalLabel">Aprobando Descuento </h4>
							</div>
							<div class="modal-body">
								<form id="formulario_aprobando_medicion" method="POST" action="<?php echo base_url('nuevo/aprobar_descuento'); ?>">
									<div class="card-body card-padding text-center">
										<input id="id_medicion" type="hidden" name="id_medicion" class="form-control input-sm" >
										<input id="conexion_id_input" type="text" name="conexion_id_input" class="form-control input-sm" >
										<div class="input-group form-group">
											<span class="input-group-addon"><i style="color:orange" class="zmdi zmdi-assignment"></i> Vencimiento 1</span>
											<input id="vencimiento_uno" type="text" name="vencimiento_uno" class="form-control input-sm">
										</div>
										<br>
										<div class="input-group form-group">
											<span class="input-group-addon"><i style="color:orange" class="zmdi zmdi-assignment"></i> Vencimiento 2 </span>
											<input id="vencimiento_dos" type="text" name="vencimiento_dos" class="form-control input-sm">
										</div>
										<br>
										<div class="input-group form-group">
											<span class="input-group-addon"><i style="color:orange" class="zmdi zmdi-assignment"></i> Descuento Aplicado</span>
											<input id="descuento" type="text" name="descuento" class="form-control input-sm">
										</div>
										<br>
										<div class="input-group form-group">
											<span class="input-group-addon"><i style="color:orange" class="zmdi zmdi-assignment"></i> Nuevo Descuento</span>
											<input id="nuevo_descuento" type="number" name="nuevo_descuento" class="form-control input-sm">
										</div>
										<br>
										<div class="input-group form-group">
											<span class="input-group-addon"><i style="color:orange" class="zmdi zmdi-assignment"></i> Monto Pago</span>
											<input id="monto_pago" type="text" name="monto_pago" class="form-control input-sm">
										</div>
										<br>

										<div class="input-group form-group">
											<span class="input-group-addon"><i style="color:green" class="zmdi zmdi-assignment"></i> Fecha Pago</span>
											<input id="fecha_pago" type="text" name="fecha_pago" class="form-control input-sm" >
										</div>

										<div class="input-group form-group">
											<span class="input-group-addon"><i style="color:green" class="zmdi zmdi-assignment"></i> Fecha Pago</span>
											<select name="decision_aprobar" id="decision_aprobar">
												<option value="1" selected>Si, aprobarla</option>
												<option value="2">No, Reprobarla y pasar a deuda</option>
											</select>
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

				<table id="data-table-autoriazar-bonificacion" class="table table-striped table-vmiddle">
					<thead>
						<tr>
							<!--hay que cambiar los id???-->
							<th data-column-id="sku" >ID </th>
							<th data-column-id="conexion_id" >Con Id</th>
							<th data-column-id="Factura_ID" >Factura id</th>
							<th data-column-id="Tarifa_social" data-visible="false">Tarifa social</th>
							<th data-column-id="Excedente_precio">Excedente</th>
							<th data-column-id="PM_Cant_Cuotas" data-visible="false">Cuotas plan medidor</th>
							<th data-column-id="PM_Cuota_Actual" data-visible="false">Numero de cuota plan medidor</th>
							<th data-column-id="PM_Precio_Cuota" >Precio cuota plan medidor</th>
							<th data-column-id="PP_Cant_Cuotas" data-visible="false">Cuotas plan de pago</th>
							<th data-column-id="PP_Cuota_Actual"data-visible="false" >Couta PP Actual</th>
							<th data-column-id="PP_Precio_Cuota">precio plan pago </th>
							<th data-column-id="Riego" data-visible="false">Riego</th>
							<th data-column-id="Subtotal">Subtotal</th>
							<th data-column-id="A_Cuenta" >A Cuenta</th>
							<th data-column-id="Bonificacion">Bonificación$</th>
							<th data-column-id="Total" data-visible="false">Total</th>	
							<th data-column-id="Descuento">Descuento</th>
							<th data-column-id="vencimineto" >Primer vencimiento</th>
							<th data-column-id="Vencimiento2">Segundo vencimiento</th>
							<th data-column-id="Medicion_Anterior">Medición anterior</th>
							<th data-column-id="Medicion_Actual">Medición actual</th>
							<th data-column-id="Excendente_m3">M3 excentes</th>
							<th data-column-id="Pago_Atrasado">Pago atrasado</th>
							<th data-column-id="Pago_Monto">Monto</th>
							<th data-column-id="Pago_Contado" data-visible="false">Contado</th>
							<th data-column-id="Pago_Cheque" data-visible="false">Cheque</th>
							<th data-column-id="Pago_Fecha" data-visible="false">Fecha Pago</th>
							<th data-column-id="Mes">Mes</th>
							<th data-column-id="Año">Año</th>
							<th data-column-id="Quien" data-visible="false">Quien</th>
							<th data-column-id="Fecha_Edicion" data-visible="false">Fecha</th>

							<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($autorizacion as $columna):?>
						<tr>
							<td><?php echo $columna->Aut_Factura_Id; ?></td>
							<td><?php echo $columna->Aut_Factura_Conexion_Id; ?> </td>
							<td><?php echo $columna->Aut_Factura_TarifaSocial; ?> </td>
							<td><?php echo $columna->Aut_Factura_ExcedentePrecio; ?> </td>
							<td><?php echo $columna->Aut_Factura_CuotaSocial; ?> </td>
							<td><?php echo $columna->Aut_Factura_PM_Cant_Cuotas; ?> </td>
							<td><?php echo $columna->Aut_Factura_PM_Cuota_Actual; ?> </td>
							<td><?php echo $columna->Aut_Factura_PM_Cuota_Precio; ?> </td>
							<td><?php echo $columna->Aut_Factura_PP_Cant_Cuotas; ?> </td>
							<td><?php echo $columna->Aut_Factura_PP_Cuota_Actual; ?> </td>
							<td><?php echo $columna->Aut_Factura_PPC_Precio; ?> </td>
							<td><?php echo $columna->Aut_Factura_Riego; ?> </td>
							<td><?php echo $columna->Aut_Factura_SubTotal; ?> </td>
							<td><?php echo $columna->Aut_Factura_Acuenta; ?> </td>
							<td><?php echo $columna->Aut_Factura_Bonificacion; ?> </td>
							<td><?php echo $columna->Aut_Factura_Total; ?> </td>
							<td><?php echo $columna->Aut_Factura_Descuento; ?> </td>
							<td><?php echo $columna->Aut_Factura_Vencimiento1_Precio; ?> </td>
							<td><?php echo $columna->Aut_Factura_Vencimiento2_Precio; ?> </td>
							<td><?php echo $columna->Aut_Factura_MedicionAnterior; ?> </td>
							<td><?php echo $columna->Aut_Factura_MedicionActual; ?> </td>
							<td><?php echo $columna->Aut_Factura_Excedentem3; ?> </td>
							<td><?php echo $columna->Aut_Factura_PagoAtrasado; ?> </td>
							<td><?php echo $columna->Aut_Factura_PagoMonto; ?> </td>
							<td><?php echo $columna->Aut_Factura_PagoContado; ?> </td>
							<td><?php echo $columna->Aut_Factura_PagoCheque; ?> </td>
							<td><?php echo $columna->Aut_Factura_PagoTimestamp; ?> </td>
							<td><?php echo $columna->Aut_Mes; ?> </td>
							<td><?php echo $columna->Aut_Año; ?> </td>
							<td><?php echo $columna->Aut_Quien; ?> </td>
							<td><?php echo $columna->Aut_FechaHora; ?> </td>
							<td><?php echo $columna->Aut_Factura_PagoTimestamp; ?> </td>
							<td></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
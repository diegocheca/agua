<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Material Bootstrap Wizard by Creative Tim</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="viewport" content="width=device-width" />

	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('js/wizard/assets/img/apple-icon.png'); ?> " />
	<link rel="icon" type="image/png" href="<?php echo base_url('js/wizard/assets/img/favicon.png'); ?> " />

	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

	<!-- CSS Files -->
	<link href="<?php echo base_url('js/wizard/assets/css/bootstrap.min.css'); ?> " rel="stylesheet" />
	<link href="<?php echo base_url('js/wizard/assets/css/material-bootstrap-wizard.css'); ?> " rel="stylesheet" />

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link href="<?php echo base_url('js/wizard/assets/css/demo.css'); ?>" rel="stylesheet" />
</head>

<body>
	<div class="image-container set-full-height" style="background-image: url('<?php echo base_url('js/wizard').'/'; ?>assets/img/wizard-city.jpg')">
		<!--   Creative Tim Branding   -->
		<!-- <a href="http://creative-tim.com">
			 <div class="logo-container">
				<div class="logo">
					<img src="<?php echo base_url('js/wizard/assets/img/new_logo.png'); ?>">
				</div>
				<div class="brand">
					Creative Tim
				</div>
			</div>
		</a>
 -->
		<!--  Made With Material Kit  -->
	<!-- 	<a href="http://demos.creative-tim.com/material-kit/index.html?ref=material-bootstrap-wizard" class="made-with-mk">
			<div class="brand">MK</div>
			<div class="made-with">Made with <strong>Material Kit</strong></div>
		</a> -->

		<!--   Big container   -->
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<!--      Wizard container        -->
					<div class="wizard-container">
						<div class="card wizard-card" data-color="purple" id="wizard">
							<form action="" method="">
							<!--        You can switch " data-color="rose" "  with one of the next bright colors: "blue", "green", "orange", "purple"        -->
								<div class="wizard-header">
									<h3 class="wizard-title">
										Inicio de mes
									</h3>
									<h5>Creando boletas de mes mayo.</h5>
								</div>
	<!-- 								*****************************************************************/
	/*
	Pasos de inicio:
	1 - Configuracion de valores
	2 - Crear los registros del nuevo mes (nuevo/crear_nuevo_es_facturas)
	3 - Subir los datos a la tablet (con admin traer los nuevos registros del mes)
	4 - Cargar las mediciones con las tabler casa x casa
	5 - Descargar los datos de las tablet a la pc
	6 - Calcular los valores de Excm3 y Exc Precio (nuevo/corregir_mediciones)
	7 - Pasar las facturas impagas a Deuda conexion (nuevo/pasar_mes_impago_a_deuda_conexion)
	8 - Pasar las Deuda conexion a Factura_actual (nuevo/calcular_deudas_y_multas_a_facturacion_mes_nuevo)
	9 - Habilitar las bonificaciones realizadas (nuevo/)
	10 - Validar mediciones raras (nuevo/aprobar_medicion)
	11 - Recalcular Valores de las facturas actuales (nuevo/corregir_boletas)
	12 - Imprimir
	*/
	 -->
								<div class="wizard-navigation">
									<ul>
										<!-- 1 - Configuracion de parametros (nuevo/crear_nuevo_es_facturas) -->
										<li><a href="#valores" data-toggle="tab">1 </a></li>
										<!-- 2 - Crear los registros del nuevo mes (nuevo/crear_nuevo_es_facturas) -->
										<li><a href="#crear_filas" data-toggle="tab">2</a></li>
										<!-- 3 - Subir los datos a la tablet (con admin traer los nuevos registros del mes) -->
										<li><a href="#cargar_tablet" data-toggle="tab">3</a></li>
										<!-- 4 - Cargar las mediciones con las tabler casa x casa -->
										<li><a href="#cargar_mediciones" data-toggle="tab">4</a></li>
										<!-- 5 - Descargar los datos de las tablet a la pc -->
										<li><a href="#descargar_tablet" data-toggle="tab">5</a></li>
										<!-- 6 - Calcular los valores de Excm3 y Exc Precio (nuevo/corregir_mediciones) -->
										<li><a href="#calular_exm3" data-toggle="tab">6</a></li>
										<!--7 - Pasar las facturas impagas a Deuda conexion (nuevo/pasar_mes_impago_a_deuda_conexion) -->
										<li><a href="#facturas_impagas_a_deuda" data-toggle="tab">7</a></li>

										<!--	8 - Pasar las Deuda conexion a Factura_actual (nuevo/calcular_deudas_y_multas_a_facturacion_mes_nuevo) -->
										<li><a href="#deuda_a_factura_nueva" data-toggle="tab">8</a></li>


										<!--	9 - Habilitar las bonificaciones realizadas (nuevo/) -->
										<li><a href="#habilitar_bonificaciones" data-toggle="tab">9</a></li>

										<!--	10 - Validar mediciones raras (nuevo/aprobar_medicion)-->
										<li><a href="#aprobar_mediciones" data-toggle="tab">10</a></li>

										<!--	11 - Recalcular Valores de las facturas actuales (nuevo/corregir_boletas) -->
										<li><a href="#recalcular_valores" data-toggle="tab">11</a></li>



										<li><a href="#mostrar_imprimir" data-toggle="tab">Imprimir</a></li>
									</ul>
								</div>


































								<!--
								INICIO
								PASO 1
								<h4 class="info-text"> Actualizando Valores de Configuracion</h4>
								 -->
								<div class="tab-content">
									<div class="tab-pane" id="valores">
										<div class="row">
											<div class="col-sm-12">
												<h3 class="info-text"> Actualizando Valores de Configuracion</h3>
												<p class="info-text"> Configura los valores que serán usandos para calcular las boletas</p>
											</div>
											<div class="col-sm-12">
												  <div class="checkbox">
													  <label>
														  <input type="checkbox" id="terminado_1" name="optionsCheckboxes" 
														  <?php 
														  $pasos_hecho = false;
														  foreach ($pasos as $key) {
														  	if( ($key->IM_Paso == 1)&&($key->IM_Hecho == 1))
														  		$pasos_hecho = true;
														  } 
														  if($pasos_hecho)
														  	echo "checked";
														  ?>
														  ><span class="checkbox-material"></span>
													  </label>
													  Terminado?
												  </div>
											</div>
											<?php 
											if($pasos != false)
											{
												$paso2_hecho = false;
												foreach ($pasos as $key) {
													if( ($key->IM_Paso == 1)&&($key->IM_Hecho == 1))
														$paso2_hecho = true;
												} 
												if($paso2_hecho)
													echo "<div class='alert alert-success'>
															Paso 1 TERMINADO
														</div>";
													else
														echo "<div class='alert alert-warning'>
															Paso 1 SIN HACER
														</div>";
											}
											?>
										  	<div class"row" id="status_terminado_1"></div>
										  	<div class"row" id="status_paso_1"></div>
											<div class="col-sm-5 ">
		                                    	<div class="form-group label-floating is-empty">
		                                        	<label class="control-label">Mts Basicos Familiar Actual: <?php echo $variables[5]->Configuracion_Valor; ?> mts</label>
		                                        	<input type="text" class="form-control" id="mts_bascios_familiar">
		                                    	<span class="material-input"></span></div>
		                                	</div>
		                                	<div class="col-sm-5 ">
		                                    	<div class="form-group label-floating is-empty">
		                                        	<label class="control-label">Couta Social Actual: $ <?php echo $variables[9]->Configuracion_Valor; ?></label>
		                                        	<input type="text" class="form-control" id="couta_social">
		                                    	<span class="material-input"></span></div>
		                                	</div>
		                                	<div class="col-sm-5 ">
		                                    	<div class="form-group label-floating is-empty">
		                                        	<label class="control-label">Mts Basicos Comercial Actual: <?php echo $variables[8]->Configuracion_Valor; ?> mts</label>
		                                        	<input type="text" class="form-control" id="mts_basicos_comercial">
		                                    	<span class="material-input"></span></div>
		                                	</div>
		                                	<div class="col-sm-5 ">
		                                    	<div class="form-group label-floating is-empty">
		                                        	<label class="control-label">Precio Metro Familiar  Actual: $ <?php echo $variables[3]->Configuracion_Valor; ?></label>
		                                        	<input type="text" class="form-control" id="precio_mt_familiar">
		                                    	<span class="material-input"></span></div>
		                                	</div>
		                                	<div class="col-sm-5 ">
		                                    	<div class="form-group label-floating is-empty">
		                                        	<label class="control-label">Precio Metro Comercial Actual: $ <?php echo $variables[6]->Configuracion_Valor; ?></label>
		                                        	<input type="text" class="form-control" id="precio_mt_comercial">
		                                    	<span class="material-input"></span></div>
		                                	</div>
		                                	<div class="col-sm-5 ">
		                                    	<div class="form-group label-floating is-empty">
		                                        	<label class="control-label">Precio Riego Actual: $ <?php echo $variables[17]->Configuracion_Valor; ?></label>
		                                        	<input type="text" class="form-control" id="precio_riego">
		                                    	<span class="material-input"></span></div>
		                                	</div>
		                                	<div class="col-sm-5 ">
		                                    	<div class="form-group label-floating is-empty">
		                                        	<label class="control-label">Fecha Vencimiento 1 Actual: <?php echo $variables[20]->Configuracion_Valor; ?></label>
		                                        	<input type="date" class="form-control" id="vencimiento_1">
		                                    	<span class="material-input"></span></div>
		                                	</div>
		                                	<div class="col-sm-5 ">
		                                    	<div class="form-group label-floating is-empty">
		                                        	<label class="control-label">Fecha Vencimiento 2 Actual: <?php echo $variables[21]->Configuracion_Valor; ?></label>
		                                        	<input type="date" class="form-control" id="vencimiento_2">
		                                    	<span class="material-input"></span></div>
		                                	</div>
		                                	<div class="col-sm-5 ">
		                                    	<div class="form-group label-floating is-empty">
		                                        	<label class="control-label">Validez de Boleta Actual: <?php echo $variables[19]->Configuracion_Valor; ?></label>
		                                        	<input type="date" class="form-control" id="validez_boleta">
		                                    	<span class="material-input"></span></div>
		                                	</div>
		                                	<input type='button' id="btn_paso_1" name='paso_1' value='Guardar Valores' />

										</div>
									</div>
									<!--
									FIN
									PASO 1
								 	-->




































								 	<!--
									INICIO
									PASO 2
									<h4 class="info-text"> Creando registros en Base de datos</h4>
								 	-->
									<div class="tab-pane" id="crear_filas">
										<div class="row">
											<div class="col-sm-12">
												<h4 class="info-text"> Creando registros en Base de datos</h4>
												<p class="info-text">Crea registros en la base de datos</p>
												<div id="status"></div>
												<div id="statusB"></div>
												<div id="statusC"></div>
												<div id="statusAbe"></div>
												<div id="statusVE"></div>
												<div id="statusOlmos"></div>
												<div id="statusSa"></div>
												<div id="statusZal"></div>
												<div id="statusJS"></div>
												<div id="statusSB"></div>
											</div>
											<div class="col-sm-12">
												<div class="col-sm-12">
												  <!-- <div class="checkbox">
													  <label>
														  <input type="checkbox" id="terminado_2" name="optionsCheckboxes"  -->
														  <?php 
														  $paso2_hecho = false;
														  foreach ($pasos as $key) {
														  	if( ($key->IM_Paso == 2)&&($key->IM_Hecho == 1))
														  		$paso2_hecho = true;
														  } 
														  if($paso2_hecho)
														  	echo "<div class='alert alert-success'>
																	Paso 2 TERMINADO
																</div>";
														  ?>
														 <!--  ><span class="checkbox-material"></span>
													  </label>
													  Terminado
												  </div> -->
										  	</div>
												<div class="col-sm-5">
													<div class="form-group label-floating">
														<label class="control-label"> Mes Anterior</label>
														<select id="crear_boleta_mes" name="crear_boleta_mes" class="form-control">
															<option disabled="" selected=""></option>
															<option value="1"> Enero</option>
															<option value="2"> Febrero</option>
															<option value="3"> Marzo</option>
															<option value="4"> Abril</option>
															<option value="5"> Mayo</option>
															<option value="6"> Junio</option>
															<option value="7"> Julio</option>
															<option value="8"> Agosto</option>
															<option value="9"> Septiembre</option>
															<option value="10"> Octubre</option>
															<option value="11"> Noviembre</option>
															<option value="12"> Diciembre</option>
														</select>
													</div>
												</div>
												<div class="col-sm-5">
													<div class="form-group label-floating">
														<label class="control-label"> Año</label>
														<select id="crear_boleta_anio" name="crear_boleta_anio" class="form-control">
															<option disabled="" selected=""></option>
															<option value="2018"> 2018</option>
															<option value="2019"> 2019</option>
															<option value="2020"> 2020</option>
														</select>
													</div>
												</div>

												<input type='button' id="btn_crear_filas" name='next123' value='Crear Registros' />
											</div>
										</div>
										<div class="row">
											<hr>
											<p>Revisión:</p>
											<p>Se puede comprobar el estado de este paso, revisando que se pueden cargar las mediciones del mes para cada conexion, como si se escribieran a mano,
											click <a target="_blank" href="http://localhost/codeigniter/nuevo/cargar_mediciones_por_lote" >aqui</a> </p>
										</div>
									</div>
									<!--
									FIN
									PASO 2
								 	-->




























								 	<!--
									INICIO
									PASO 3
									<h4 class="info-text"> Cargando registros en Tablet</h4>
								 	-->
									<div class="tab-pane" id="cargar_tablet">
										<div class="row">
											<div class="col-sm-12">
												<h3 class="info-text"> Cargando registros en Tablet</h3>
												<p class="info-text">Pasando las mediciones recien creadas y vacias, desde la pc a las tablet</p>
											</div>
											<div class="col-sm-12">
												
												<div class="checkbox">
													<label>
													<input type="checkbox" id="terminado_3" name="optionsCheckboxes" 
													<?php 
													if($pasos != false)
													{
														$pasos_hecho = false;
														foreach ($pasos as $key) {
															if( ($key->IM_Paso == 1)&&($key->IM_Hecho == 1))
																$pasos_hecho = true;
														} 
														if($pasos_hecho)
															echo "checked";
													}
													?>
													><span class="checkbox-material"></span>
													</label>
													Terminado?
												</div>
										  	<div class="row">
												<?php 
													if($pasos != false)
													$paso3_hecho = false;
													foreach ($pasos as $key) {
														if( ($key->IM_Paso == 3)&&($key->IM_Hecho == 1))
															$paso3_hecho = true;
													} 
													if($paso3_hecho)
														echo "<div class='alert alert-success'>
																Paso 3 TERMINADO
															</div>";
														else
															echo "<div class='alert alert-warning'>
																Paso 3 SIN HACER
															</div>";
												?>
										  	</div>
										  	<div class"row" id="status_terminado_3"></div>
										  	<div class"row" id="status_paso_3"></div>
												<h2>Pasos a seguir:</h2>
												<p>1: Abrir aplicacion de la tablet</p>
												<p>2: Iniciar sesion usario: admin   y contraseña: admin</p>
												<p>3: Revisar el numero de la direccion: debe ser 192.168.1.43</p>
												<p>4: Iniciar sesion usario: claudio   y contraseña: claudio    o   usario: david   y contraseña: david </p>
												<p>5: Hacer click en traer registros</p>
												<p>6: Esperar a que diga "sectores actualizados"</p>
												<hr>
												<p>Revisión:</p>
												<p>7: Revisar que esten las conexiones en los sectores</p>
											</div>
										</div>
									</div>
									<!--
									FIN
									PASO 3
								 	-->
				




















								 	<!--
									INICIO
									PASO 4
									<h4 class="info-text">Cargar mediciones conexion x conexion</h4>
								 	-->
									<div class="tab-pane" id="cargar_mediciones">
										<div class="row">
											<div class="col-sm-12">
												<h3 class="info-text">Cargar mediciones conexion x conexion</h3>
												<p class="info-text">Este paso lo hacen los tecnicos casa por casa</p>
											</div>
											<div class="col-sm-12">
											  	<?php 
													$paso4_hecho = false;
													foreach ($pasos as $key) {
														if( ($key->IM_Paso == 4)&&($key->IM_Hecho == 1))
															$paso4_hecho = true;
													} 
													if($paso4_hecho)
														echo "<div class='alert alert-success'>
																Paso 4 TERMINADO
															</div>";
														else
															echo "<div class='alert alert-warning'>
																Paso 4 SIN HACER
															</div>";
												?>
												<div class="checkbox">
													<label>
														<input type="checkbox" id="terminado_4" name="optionsCheckboxes" 
														<?php 
														$pasos_hecho = false;
														foreach ($pasos as $key) {
															if( ($key->IM_Paso == 4)&&($key->IM_Hecho == 1))
																$pasos_hecho = true;
														} 
														if($pasos_hecho)
															echo "checked";
														?>
														><span class="checkbox-material"></span>
													</label>
													Terminado?
												</div>
										  	</div>
											<div class"row" id="status_terminado_4"></div>
										</div>
									</div>
									<!--
									FIN
									PASO 4
								 	-->
























								 	<!--
									INICIO
									PASO 5
									<h4 class="info-text">Descargar los datos de la tablet a la pc</h4>
								 	-->
									<div class="tab-pane" id="descargar_tablet">
										<div class="row">
											<div class="col-sm-12">
												<h3 class="info-text">Descargar los datos de la tablet a la pc</h3>
												<p class="info-text">Pasar los datos desde las tablet hacia la pc</p>
												<p class="info-text">Este paso se hace en las tablets, no en la pc</p>
											</div>
											<div class="col-sm-12">
											  	<?php 
													$paso5_hecho = false;
													foreach ($pasos as $key) {
														if( ($key->IM_Paso == 5)&&($key->IM_Hecho == 1))
															$paso5_hecho = true;
													} 
													if($paso5_hecho)
														echo "<div class='alert alert-success'>
																Paso 5 TERMINADO
															</div>";
														else
															echo "<div class='alert alert-warning'>
																Paso 5 SIN HACER
															</div>";
												?>
												<div class="checkbox">
													  <label>
														  <input type="checkbox" id="terminado_5" name="optionsCheckboxes" 
														  <?php 
														  $pasos_hecho = false;
														  foreach ($pasos as $key) {
														  	if( ($key->IM_Paso == 5)&&($key->IM_Hecho == 1))
														  		$pasos_hecho = true;
														  } 
														  if($pasos_hecho)
														  	echo "checked";
														  ?>
														  ><span class="checkbox-material"></span>
													  </label>
													  Terminado?
												  </div>
												  
										  	 	<div class"row" id="status_terminado_5"></div>
											</div>
											<h2>Pasos a seguir:</h2>
											<p>1: Abrir aplicacion de la tablet</p>
											<p>2: Iniciar sesion usario: david   y contraseña: david       y/o  usario: claudio   y contraseña: claudio </p>
											<p>3: Hacer click en subir sector</p>
											<p>4: Esperar a que diga sector subido</p>
											<p>5: Repetir con la otra tablet</p>
										</div>
									</div>
									<!--
									FIN
									PASO 5
								 	-->

									













									<!--
									INICIO
									PASO 6
									<h4 class="info-text">Calcular los valores de Excm3 y Exc Precio</h4>
								 	-->
									<div class="tab-pane" id="calular_exm3">
										<h3 class="info-text">Calcular los valores de Excm3 y Exc Precio</h3>
										<p class="info-text">El sistema calculará el exc en mts y precio para cada una de las mediciones tomadas</p>
										<div class="row">
										<?php 
											$paso6_hecho = false;
											foreach ($pasos as $key) {
												if( ($key->IM_Paso == 6)&&($key->IM_Hecho == 1))
													$paso6_hecho = true;
											} 
											if($paso6_hecho)
												echo "<div class='alert alert-success'>
														Paso 6 TERMINADO
													</div>";
												else
													echo "<div class='alert alert-warning'>
														Paso 6 SIN HACER
													</div>";
										?>
										<div class="checkbox">
											  <label>
												  <input type="checkbox" id="terminado_6" name="optionsCheckboxes" 
												  <?php 
												  $pasos_hecho = false;
												  foreach ($pasos as $key) {
												  	if( ($key->IM_Paso == 6)&&($key->IM_Hecho == 1))
												  		$pasos_hecho = true;
												  } 
												  if($pasos_hecho)
												  	echo "checked";
												  ?>
												  ><span class="checkbox-material"></span>
											  </label>
											  Terminado?
										  </div>

											<!-- <div class="col-sm-10 col-sm-offset-1">
												<div class="col-sm-4 col-sm-offset-4">
													<input type="radio" name="type" id="calcular_excedentes" value="House">
													<div class="icon">
														<i class="material-icons">cached</i>
													</div>
													<h6>Calcular</h6>
												</div>
											</div> -->
											<div class="col-sm-5">
													<div class="form-group label-floating">
														<label class="control-label"> Mes a Calcular</label>
														<select id="calcular_excedentes_mes" name="calcular_excedentes_mes" class="form-control">
															<option disabled="" selected=""></option>
															<option value="1"> Enero</option>
															<option value="2"> Febrero</option>
															<option value="3"> Marzo</option>
															<option value="4"> Abril</option>
															<option value="5"> Mayo</option>
															<option value="6"> Junio</option>
															<option value="7"> Julio</option>
															<option value="8"> Agosto</option>
															<option value="9"> Septiembre</option>
															<option value="10"> Octubre</option>
															<option value="11"> Noviembre</option>
															<option value="12"> Diciembre</option>
														</select>
													</div>
												</div>
												<div class="col-sm-5">
													<div class="form-group label-floating">
														<label class="control-label"> Año</label>
														<select id="calcular_excedentes_anio" name="calcular_excedentes_anio" class="form-control">
															<option disabled="" selected=""></option>
															<option value="2018"> 2018</option>
															<option value="2019"> 2019</option>
															<option value="2020"> 2020</option>
														</select>
													</div>
												</div>

													<button id="calcular_excedentes">Calcular</button>
											<div class"row" id="status_terminado_6"></div>
										</div>
										<div class="row">
											<hr>
											<p>Revisión:</p>
											<p>Se puede comprobar el estado de este paso, revisando si en las mediciones de las conexiones ya figura el valor del exdecente para las mediciones tomadas,
											click <a target="_blank" href="http://localhost/codeigniter/nuevo/cargar_mediciones_por_lote" >aqui</a> </p>
										</div>
										<div class"row" id="status_terminado_6"></div>
									</div>
									<!--
									FIN
									PASO 6
								 	-->







































									<!--
									INICIO
									PASO 7
									<h4 class="info-text">Pasar las facturas impagas a Deuda conexion</h4>
								 	-->
									<div class="tab-pane" id="facturas_impagas_a_deuda">
										<h4 class="info-text">7 - Pasar las facturas impagas a Deuda conexion</h4>
										<div class="row">
										<?php 
											$paso7_hecho = false;
											foreach ($pasos as $key) {
												if( ($key->IM_Paso == 7)&&($key->IM_Hecho == 1))
													$paso7_hecho = true;
											} 
											if($paso7_hecho)
												echo "<div class='alert alert-success'>
														Paso 7 TERMINADO
													</div>";
												else
													echo "<div class='alert alert-warning'>
														Paso 7 SIN HACER
													</div>";
										?>
										<div class="checkbox">
											  <label>
												  <input type="checkbox" id="terminado_7" name="optionsCheckboxes" 
												  <?php 
												  $pasos_hecho = false;
												  foreach ($pasos as $key) {
												  	if( ($key->IM_Paso == 7)&&($key->IM_Hecho == 1))
												  		$pasos_hecho = true;
												  } 
												  if($pasos_hecho)
												  	echo "checked";
												  ?>
												  ><span class="checkbox-material"></span>
											  </label>
											  Terminado?
										  </div>



										  <div class="col-sm-5">
											<div class="form-group label-floating">
												<label class="control-label"> Mes Anterior</label>
												<select id="calcular_deudas_mes" name="calcular_deudas_mes" class="form-control">
													<option disabled="" selected=""></option>
													<option value="1"> Enero</option>
													<option value="2"> Febrero</option>
													<option value="3"> Marzo</option>
													<option value="4"> Abril</option>
													<option value="5"> Mayo</option>
													<option value="6"> Junio</option>
													<option value="7"> Julio</option>
													<option value="8"> Agosto</option>
													<option value="9"> Septiembre</option>
													<option value="10"> Octubre</option>
													<option value="11"> Noviembre</option>
													<option value="12"> Diciembre</option>
												</select>
											</div>
										</div>
										<div class="col-sm-5">
											<div class="form-group label-floating">
												<label class="control-label"> Año</label>
												<select id="calcular_deudas_anio" name="calcular_deudas_anio" class="form-control">
													<option disabled="" selected=""></option>
													<option value="2018"> 2018</option>
													<option value="2019"> 2019</option>
													<option value="2020"> 2020</option>
												</select>
											</div>
										</div>

										<button id="pasar_facturas_impagas_a_deuda">Calcular</button>


											<!-- <div class="col-sm-10 col-sm-offset-1">
												<div class="col-sm-4 col-sm-offset-4">
													<div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Select this option if you have a house.">
														<input type="radio" name="type" value="House">
														<div class="icon">
															<i class="material-icons">swap_horiz</i>
														</div>
														<h6>Pasar</h6>
													</div>
												</div>
											</div> -->
											<div class"row" id="status_terminado_7"></div>
										</div>
										<div class="row">
											<hr>
											<p>Revisión:</p>
											<p>Se puede comprobar el estado de este paso, 
											click <a target="_blank" href="http://localhost/codeigniter/nuevo/cargar_mediciones_por_lote" >aqui</a> </p>
										</div>
									</div>
									<!--
									FIN
									PASO 7
								 	-->







































									<!--
									INICIO
									PASO 8
									8 - Pasar las Deuda conexion a Factura_actual (nuevo/calcular_deudas_y_multas_a_facturacion_mes_nuevo)
								 	-->
									<div class="tab-pane" id="deuda_a_factura_nueva">
										<h3 class="info-text">Pasar las Deuda conexion a Factura_actual</h3>
										<div class="row">
										<?php 
											$paso8_hecho = false;
											foreach ($pasos as $key) {
												if( ($key->IM_Paso == 8)&&($key->IM_Hecho == 1))
													$paso8_hecho = true;
											} 
											if($paso8_hecho)
												echo "<div class='alert alert-success'>
														Paso 8 TERMINADO
													</div>";
												else
													echo "<div class='alert alert-warning'>
														Paso 8 SIN HACER
													</div>";
										?>
										<div class="checkbox">
											  <label>
												  <input type="checkbox" id="terminado_8" name="optionsCheckboxes" 
												  <?php 
												  $pasos_hecho = false;
												  foreach ($pasos as $key) {
												  	if( ($key->IM_Paso == 8)&&($key->IM_Hecho == 1))
												  		$pasos_hecho = true;
												  } 
												  if($pasos_hecho)
												  	echo "checked";
												  ?>
												  ><span class="checkbox-material"></span>
											  </label>
											  Terminado?
										  </div>

											<div class="col-sm-5">
												<div class="form-group label-floating">
													<label class="control-label"> Mes Anterior</label>
													<select id="calcular_deudas_mes" name="calcular_deudas_mes" class="form-control">
														<option disabled="" selected=""></option>
														<option value="1"> Enero</option>
														<option value="2"> Febrero</option>
														<option value="3"> Marzo</option>
														<option value="4"> Abril</option>
														<option value="5"> Mayo</option>
														<option value="6"> Junio</option>
														<option value="7"> Julio</option>
														<option value="8"> Agosto</option>
														<option value="9"> Septiembre</option>
														<option value="10"> Octubre</option>
														<option value="11"> Noviembre</option>
														<option value="12"> Diciembre</option>
													</select>
												</div>
											</div>
											<div class="col-sm-5">
												<div class="form-group label-floating">
													<label class="control-label"> Año</label>
													<select id="calcular_deudas_anio" name="calcular_deudas_anio" class="form-control">
														<option disabled="" selected=""></option>
														<option value="2018"> 2018</option>
														<option value="2019"> 2019</option>
														<option value="2020"> 2020</option>
													</select>
												</div>
											</div>
											<button id="pasar_deuda_a_facturas_nuevas">Calcular</button>
											<div class"row" id="status_terminado_8"></div>
										</div>
									</div>
									<!--
									FIN
									PASO 8
								 	-->


































									<!--
									INICIO
									PASO 9
									9- Habilitar las bonificaciones realizadas (nuevo/)
								 	-->
									<div class="tab-pane" id="habilitar_bonificaciones">
										<div class="row">
											<h3 class="info-text"> Habilitar las bonificaciones realizadas. </h3>
											<p class="info-text"> Este paso lo debe hacer el juan</p>
											<?php 
												$paso9_hecho = false;
												foreach ($pasos as $key) {
													if( ($key->IM_Paso == 9)&&($key->IM_Hecho == 1))
														$paso9_hecho = true;
												} 
												if($paso9_hecho)
													echo "<div class='alert alert-success'>
															Paso 9 TERMINADO
														</div>";
													else
														echo "<div class='alert alert-warning'>
															Paso 9 SIN HACER
														</div>";
											?>
											<div class="checkbox">
												  <label>
													  <input type="checkbox" id="terminado_9" name="optionsCheckboxes" 
													  <?php 
													  $pasos_hecho = false;
													  foreach ($pasos as $key) {
													  	if( ($key->IM_Paso == 9)&&($key->IM_Hecho == 1))
													  		$pasos_hecho = true;
													  } 
													  if($pasos_hecho)
													  	echo "checked";
													  ?>
													  ><span class="checkbox-material"></span>
												  </label>
												  Terminado?
											</div>
											<div class="col-sm-5">
												<div class="form-group label-floating">
													<label class="control-label"> Mes Anterior</label>
													<select id="recalcular_deudas_mes" name="calcular_deudas_mes" class="form-control">
														<option disabled="" selected=""></option>
														<option value="1"> Enero</option>
														<option value="2"> Febrero</option>
														<option value="3"> Marzo</option>
														<option value="4"> Abril</option>
														<option value="5"> Mayo</option>
														<option value="6"> Junio</option>
														<option value="7"> Julio</option>
														<option value="8"> Agosto</option>
														<option value="9"> Septiembre</option>
														<option value="10"> Octubre</option>
														<option value="11"> Noviembre</option>
														<option value="12"> Diciembre</option>
													</select>
												</div>
											</div>
											<div class="col-sm-5">
												<div class="form-group label-floating">
													<label class="control-label"> Año</label>
													<select id="recalcular_deudas_anio" name="calcular_deudas_anio" class="form-control">
														<option disabled="" selected=""></option>
														<option value="2018"> 2018</option>
														<option value="2019"> 2019</option>
														<option value="2020"> 2020</option>
													</select>
												</div>
											</div>
											<button id="recaulcular_boletas">Calcular</button>

											<div class"row" id="status_terminado_9"></div>
										</div>
									</div>
									<!--
									FIN
									PASO 9
								 	-->






































									<!--
									INICIO
									PASO 10
									Validar mediciones raras (nuevo/aprobar_medicion)
								 	-->
									<div class="tab-pane" id="aprobar_mediciones">
										<div class="row">
											<h3 class="info-text"> Validar mediciones raras. </h3>
											<p class="info-text"> Este paso lo puede hacer Juan o Noelia</p>
											<?php 
											$paso10_hecho = false;
											foreach ($pasos as $key) {
												if( ($key->IM_Paso == 10)&&($key->IM_Hecho == 1))
													$paso10_hecho = true;
											} 
											if($paso10_hecho)
												echo "<div class='alert alert-success'>
														Paso 10 TERMINADO
													</div>";
												else
													echo "<div class='alert alert-warning'>
														Paso 10 SIN HACER
														</div>";
											?>
											<div class="checkbox">
												  <label>
													  <input type="checkbox" id="terminado_10" name="optionsCheckboxes" 
													  <?php 
													  $pasos_hecho = false;
													  foreach ($pasos as $key) {
													  	if( ($key->IM_Paso == 10)&&($key->IM_Hecho == 1))
													  		$pasos_hecho = true;
													  } 
													  if($pasos_hecho)
													  	echo "checked";
													  ?>
													  ><span class="checkbox-material"></span>
												  </label>
												  Terminado?
											  </div>

											<div class="col-sm-10 col-sm-offset-1">
												<div class="col-sm-4 col-sm-offset-4">
													<div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Select this option if you have a house.">
														<input type="radio" name="type" value="House">
														<div class="icon">
															<i class="material-icons">thumb_up</i>
														</div>
														<h6>Pasar</h6>
													</div>
												</div>
											</div>
											<div class"row" id="status_terminado_10"></div>
										</div>
									</div>
									<!--
									FIN
									PASO 10
								 	-->






































									
									<!--
									INICIO
									PASO 11
									Recalcular Valores de las facturas actuales (nuevo/corregir_boletas)
								 	-->
									<div class="tab-pane" id="recalcular_valores">
										<div class="row">
											<h3 class="info-text"> Recalcular Valores de las facturas actuales. </h3>
											<p class="info-text"> Este paso lo hace Noelia</p>
											<?php 
											$paso11_hecho = false;
											foreach ($pasos as $key) {
												if( ($key->IM_Paso == 11)&&($key->IM_Hecho == 1))
													$paso11_hecho = true;
											} 
											if($paso11_hecho)
												echo "<div class='alert alert-success'>
														Paso11 TERMINADO
													</div>";
												else
													echo "<div class='alert alert-warning'>
														Paso 11 SIN HACER
														</div>";
											?>
											<div class="checkbox">
												  <label>
													  <input type="checkbox" id="terminado_11" name="optionsCheckboxes" 
													  <?php 
													  $pasos_hecho = false;
													  foreach ($pasos as $key) {
													  	if( ($key->IM_Paso == 11)&&($key->IM_Hecho == 1))
													  		$pasos_hecho = true;
													  } 
													  if($pasos_hecho)
													  	echo "checked";
													  ?>
													  ><span class="checkbox-material"></span>
												  </label>
												  Terminado?
											  </div>

											<div class="col-sm-10 col-sm-offset-1">
												<div class="col-sm-4 col-sm-offset-4">
													<div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Select this option if you have a house.">
														<input type="radio" name="type" value="House">
														<div class="icon">
															<i class="material-icons">attach_money</i>
														</div>
														<h6>Pasar</h6>
													</div>
												</div>
											</div>
											<div class"row" id="status_terminado_11"></div>
										</div>
									</div>
									<!--
									FIN
									PASO 11
								 	-->



































									
									<!--
									INICIO
									PASO 12
									Imprimir
								 	-->
									<div class="tab-pane" id="mostrar_imprimir">
										<div class="row">
											<h3 class="info-text"> Imprimir. </h3>
											<p class="info-text"> Este paso lo hace Noelia</p>
											<?php 
											$paso12_hecho = false;
											foreach ($pasos as $key) {
												if( ($key->IM_Paso == 12)&&($key->IM_Hecho == 1))
													$paso12_hecho = true;
											} 
											if($paso12_hecho)
												echo "<div class='alert alert-success'>
														Paso 12 TERMINADO
													</div>";
												else
													echo "<div class='alert alert-warning'>
														Paso 12 SIN HACER
														</div>";
											?>
											<div class="checkbox">
												  <label>
													  <input type="checkbox" id="terminado_12" name="optionsCheckboxes" 
													  <?php 
													  $pasos_hecho = false;
													  foreach ($pasos as $key) {
													  	if( ($key->IM_Paso == 12)&&($key->IM_Hecho == 1))
													  		$pasos_hecho = true;
													  } 
													  if($pasos_hecho)
													  	echo "checked";
													  ?>
													  ><span class="checkbox-material"></span>
												  </label>
												  Terminado?
											  </div>

											<div class="col-sm-10 col-sm-offset-1">
												<div class="col-sm-4 col-sm-offset-4">
													<div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Select this option if you have a house.">
														<input type="radio" name="type" value="House">
														<div class="icon">
															<i class="material-icons">print</i>
														</div>
														<h6>Pasar</h6>
													</div>
												</div>
											</div>
											<div class"row" id="status_terminado_12"></div>
										</div>
									</div>
									<!--
									FIN
									PASO 15


								 	-->
								</div>
								<div class="wizard-footer">
									<div class="pull-right">
										<input type='button' class='btn btn-next btn-fill btn-primary btn-wd' name='next' value='Siguiente' />
										<input type='button' class='btn btn-finish btn-fill btn-primary btn-wd' name='finish' value='Terminar' />
									</div>
									<div class="pull-left">
										<input type='button' class='btn btn-previous btn-fill btn-default btn-wd' name='previous' value='Volver' />
									</div>
									<div class="clearfix"></div>
								</div>
							</form>
							<div id="resultado_2" class="row">
								<!-- <p>Resultado de operacion:</p> -->
							</div>
						</div>
					</div> <!-- wizard container -->
				</div>
			</div> <!-- row -->
		</div> <!--  big container -->

		<div class="footer">
			<!-- <div class="container text-center">
				 Made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com">Creative Tim</a>. Free download <a href="http://www.creative-tim.com/product/bootstrap-wizard">here.</a>
			</div> -->
		</div>
	</div>

</body>
	<!--   Core JS Files   -->
	<script src="<?php echo base_url('js/wizard/assets/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/wizard/assets/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/wizard/assets/js/jquery.bootstrap.js'); ?>" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="<?php echo base_url('js/wizard/assets/js/material-bootstrap-wizard.js'); ?>"></script>

	<!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
	<script src="<?php echo base_url('js/wizard/assets/js/jquery.validate.min.js'); ?>"></script>
	<script type="text/javascript">
	
$(document).on('ready',function(){
// function cargar_datos()
// {
//     var tiene_codigo = '<?php if(isset($codigo)) echo $codigo; else "false"; ?> ';
//     if(!(tiene_codigo === "false"))
//     {
		
//         $("#inputFacturaAjax").val(tiene_codigo);
//     }
// }


	$("#btn_paso_1").on("click",function(){
		var mts_basicos_familiar = $("#mts_bascios_familiar").val();
		var couta_social = $("#couta_social").val();
		var mts_basicos_comercial = $("#mts_basicos_comercial").val();
		var precio_mt_familiar = $("#precio_mt_familiar").val();
		var precio_mt_comercial = $("#precio_mt_comercial").val();
		var precio_riego = $("#precio_riego").val();
		var vencimiento_1 = $("#vencimiento_1").val();
		var vencimiento_2 = $("#vencimiento_2").val();
		var validez_boleta = $("#validez_boleta").val();
			var url = 'http://localhost/codeigniter/nuevo/modificar_valores_configuracion';
			$.ajax({
				data:  {
					"mts_basicos_familiar" : mts_basicos_familiar,
					"couta_social" : couta_social,
					"mts_basicos_comercial" : mts_basicos_comercial,
					"precio_mt_familiar" : precio_mt_familiar,
					"precio_mt_comercial" : precio_mt_comercial,
					"precio_riego" : precio_riego,
					"vencimiento_1" : vencimiento_1,
					"vencimiento_2" : vencimiento_2,
					"validez_boleta" : validez_boleta
					},
				url:   url,
				type:  'post',
				beforeSend: function () {
					$("#status_paso_1").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Escribiendo</div>');
				},
				success:  
				function (response) {
					if(response != false){ // success
						$("#status_paso_1").html('<div class="alert alert-success"><i class="md md-check"></i> escrito</div>');
					}else{
						alert("error");
						}
					}
			});
	});
	$("#terminado_1").on("click",function(){
		var mostrar = '';
		if( $('#terminado_1').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/1/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_1").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_1").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});

	$("#terminado_3").on("click",function(){
		var mostrar = '';
		if( $('#terminado_3').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/3/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_3").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_3").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});

	$("#terminado_4").on("click",function(){
		var mostrar = '';
		if( $('#terminado_4').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/4/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_4").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_4").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});
	$("#terminado_5").on("click",function(){
		var mostrar = '';
		if( $('#terminado_5').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/5/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_5").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_5").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});
	$("#terminado_6").on("click",function(){
		var mostrar = '';
		if( $('#terminado_6').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/6/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_6").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_6").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});
	$("#terminado_7").on("click",function(){
		var mostrar = '';
		if( $('#terminado_7').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/7/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_7").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_7").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});
	$("#terminado_8").on("click",function(){
		var mostrar = '';
		if( $('#terminado_8').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/8/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_8").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_8").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});
	$("#terminado_9").on("click",function(){
		var mostrar = '';
		if( $('#terminado_9').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/9/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_9").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_9").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});
	$("#terminado_10").on("click",function(){
		var mostrar = '';
		if( $('#terminado_10').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/10/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_10").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_10").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});

	$("#terminado_11").on("click",function(){
		var mostrar = '';
		if( $('#terminado_11').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/11/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_11").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_11").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});
	$("#terminado_12").on("click",function(){
		var mostrar = '';
		if( $('#terminado_12').prop('checked'))
			mostrar = 1;
		else mostrar=0;
		var url = 'http://localhost/codeigniter/nuevo/terminar_paso/12/'+mostrar+"/5/2018";
		$.ajax({
			data:  {"vuelta" : 1},
			url:   url,
			type:  'post',
			beforeSend: function () {
				$("#status_terminado_12").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Haciendo</div>');
			},
			success:  
			function (response) {
				if(response != false){ // success
					$("#status_terminado_12").html('<div class="alert alert-success"><i class="md md-check"></i>Terminado</div>');
				}else{
					if(response == false){
						alert ("volvio un false");
					}
				}
			}
		});
	});






	



	$("#btn_crear_filas").on("click",function(){
		var mes = $("#crear_boleta_mes").val();
		var anio = $("#crear_boleta_anio").val();
		var sectores = ["A","B","C","medina","Aberanstain","JardinesdelSur","Salas","SantaBarbara","VElisa","David","ASENTAMIENTOOLMOS","Zaldivar"];
		var sector = '';
 		var cartel_sector = '';
 		var log_paso_dos = '';
 		var mensaje = '';
		for (var y = 0; y < sectores.length; y++) {
			sector = sectores[y];
			var url = 'http://localhost/codeigniter/nuevo/crear_nuevo_es_facturas'+'/'+mes+'/'+anio+'/'+sector;
			cartel_sector += ".   Haciendo: "+sector+"      .";
			var array_resultado = '';
			$.ajax({
				data:  {"vuelta" : y},
				url:   url,
				type:  'post',
				beforeSend: function () {
					//mensaje += '             *                 Haciendo el sector:'+sector+'*                        *                    *';
					$("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Cartel'+cartel_sector+'</div>');
				},
				success:  
				function (response) {
					if(response != false){ // success
						var copia_resultado = JSON.parse(JSON.stringify(response)) ;
						copia_resultado = JSON.parse(response) ;
						array_resultado  = copia_resultado;
						for(var i  = 0 ; i<copia_resultado.length;i++)
						{
							log_paso_dos += "_ Con:"+array_resultado[i]+" _";
							console.log("Sub for: vuelta"+i);
						}
						console.log("response");
					    function exito(){
					    	cartel_sector += '             *                 Termine con el sector                        *                    *';
							$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> '+cartel_sector+'</div>');
						}
						window.setTimeout(exito,600);
						console.log("log de conexiones:"+log_paso_dos);
						$("#resultado_2").html('<div class="alert alert-success"><i class="md md-check"></i> '+log_paso_dos +'</div>');
					}else{
						if(response == false){
							alert ("volvio un false");
							console.log("entre al faslse");
							mensaje += '             __                 Error con el sector:'+sector+'__                        ___                   __';
						   function error(){
								$("#status").html('<div class="alert alert-danger">'+mensaje+'</div>');
							}
							window.setTimeout(error,600);
						}
					}
				}
			});
        }
	});

	$("#btn_tarear_filas").on("click",function(){
			var url = 'http://localhost/codeigniter/nuevo/traer_filas_creadas_inicio_mes/5/2018';
			$.ajax({
				data:  {"vuelta" : 1},
				url:   url,
				type:  'post',
				beforeSend: function () {
					$("#filas").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i>Buscando</div>');
				},
				success:  
				function (response) {
					alert(response);
					if(response != "false"){ // success
						var copia_resultado = JSON.parse(JSON.stringify(response)) ;
					//	copia_resultado = JSON.parse(response) ;
						console.log(copia_resultado);
						alert(copia_resultado);
						// for(var i  = 0 ; i<copia_resultado.length;i++)
						// {
						// 	log_paso_dos += "_ Con:"+array_resultado[i]+" _";
						// }
						$("#filas").html('<div class="alert alert-success"><i class="md md-check"></i></div>');
					}else
						alert("false");
							//$("#revisar_filas").html('<div class="alert alert-danger"><i class="md md-check"></i>No se creo nada aun</div>');
				}
			});
		});


		$("#calcular_excedentes").on("click",function(){
			var mes_e = $("#calcular_excedentes_mes").val();
			var anio_e = $("#calcular_excedentes_anio").val();
			var sectores = ["A","B","C","medina","Aberanstain","JardinesdelSur","Salas","SantaBarbara","VElisa","David","ASENTAMIENTOOLMOS","Zaldivar"];
			var sector = '';
			for (var y = 0; y < sectores.length; y++) {
				sector = sectores[y];

				var a = document.createElement("a");
				a.target = "_blank";
				a.href = 'http://localhost/codeigniter/nuevo/corregir_mediciones'+'/'+sector+'/'+mes_e+'/'+anio_e;
				a.click();
	        }
	        window.location = 'http://localhost/codeigniter/nuevo/inicio';

		});

		$("#pasar_facturas_impagas_a_deuda").on("click",function(){
			var mes_e = $("#calcular_deudas_mes").val();
			var anio_e = $("#calcular_deudas_anio").val();
			var sectores = ["A","B","C","medina","Aberanstain","JardinesdelSur","Salas","SantaBarbara","VElisa","David","ASENTAMIENTOOLMOS","Zaldivar"];
			var sector = '';
			for (var y = 0; y < sectores.length; y++) {
				sector = sectores[y];
				var a = document.createElement("a");
				a.target = "_blank";
				a.href = 'http://localhost/codeigniter/nuevo/pasar_mes_impago_a_deuda_conexion'+'/'+sector+'/'+mes_e+'/'+anio_e;
				a.click();
	        }
	        window.location = 'http://localhost/codeigniter/nuevo/inicio';

		});


		$("#pasar_deuda_a_facturas_nuevas").on("click",function(){
			var mes_e = 5;
			var anio_e = 2018;
			var sectores = ["A","B","C","medina","Aberanstain","JardinesdelSur","Salas","SantaBarbara","VElisa","David","ASENTAMIENTOOLMOS","Zaldivar"];
			var sector = '';
			for (var y = 0; y < sectores.length; y++) {
				sector = sectores[y];

				var a = document.createElement("a");
				a.target = "_blank";
				a.href = 'http://localhost/codeigniter/nuevo/calcular_deudas_y_multas_a_facturacion_mes_nuevo'+'/'+sector+'/'+mes_e+'/'+anio_e;
				a.click();
	        }
	        window.location = 'http://localhost/codeigniter/nuevo/inicio';

		});

		$("#recaulcular_boletas").on("click",function(){
			var mes_e = $("#recalcular_deudas_mes").val();
			var anio_e = $("#recalcular_deudas_anio").val();
			var sectores = ["A","B","C","medina","Aberanstain","JardinesdelSur","Salas","SantaBarbara","VElisa","David","ASENTAMIENTOOLMOS","Zaldivar"];
			var sector = '';
			for (var y = 0; y < sectores.length; y++) {
				sector = sectores[y];

				var a = document.createElement("a");
				a.target = "_blank";
				a.href = 'http://localhost/codeigniter/nuevo/corregir_boletas'+'/'+sector+'/'+mes_e+'/'+anio_e;
				a.click();
	        }
	        window.location = 'http://localhost/codeigniter/nuevo/inicio';

		});



		


});

</script>
</html>

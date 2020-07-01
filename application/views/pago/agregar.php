<!-- <h4><?php var_dump($factura); ?></h4> -->
<div class="row" ng-app="formApp"  ng-controller="MainCtrl" >
	<!-- <form id="formulario_chiquitito" method="post" action="<?php echo base_url("pago/guardar_pago_nuevo"); ?>" name="exampleForm"> -->
	<?php

	//var_dump($factura);die();
	function arreglar_numero($numero)
	{
			$inicio_coma = strpos($numero, '.');
			if( is_numeric( $inicio_coma) &&  ($inicio_coma >= 1) &&  ($inicio_coma < strlen($numero) ) )
				$numero =  substr($numero, 0,  ($inicio_coma+3)); 
			return $numero;
	}
	
	if ( ($factura[0]->Conexion_Categoria == 1 ) || ($factura[0]->Conexion_Categoria == "Familiar" ) )
	{
		$tarifa_basica = $configuracion[4]->Configuracion_Valor;
		$multiplicador = $configuracion[3]->Configuracion_Valor;
	}
	else
	{
		$tarifa_basica = $configuracion[7]->Configuracion_Valor;
		$multiplicador = $configuracion[6]->Configuracion_Valor;
	}
	$monto_total = arreglar_numero($factura[0]->monto);
	if($monto_total  == null)
		$monto_total = 0; 
	$monto_sin_modificar = arreglar_numero($factura[0]->monto);
	if($monto_sin_modificar  == null)
		$monto_sin_modificar = 0; 
	$deuda_anterior = $factura[0]->Conexion_Deuda;
	if($deuda_anterior  == null)
		$deuda_anterior = 0; 
	$categoria = $factura[0]->Conexion_Categoria;
	if($categoria  == null)
		$categoria = 0; 
	$excedente = $factura[0]->Medicion_Importe;
	if($excedente  == null)
		$excedente = 0; 
	$cuota_social = arreglar_numero($configuracion[2]->Configuracion_Valor);
	if($cuota_social  == null)
		$cuota_social = 0; 
	$cuota_actual_medidor = $factura[0]->PlanMedidor_CoutaActual;
	if($cuota_actual_medidor  == null)
		$cuota_actual_medidor = 0; 
	$cuotas_total_medidor = $factura[0]->PlanMedidor_Coutas;
	if($cuotas_total_medidor  == null)
		$cuotas_total_medidor = 0; 
	$monto_cueta_medidor = arreglar_numero($factura[0]->PlanMedidor_MontoCuota);
	if($monto_cueta_medidor  == null)
		$monto_cueta_medidor = 0; 
	$cuota_actual_pp = $factura[0]->PlanPago_CoutaActual;
	if($cuota_actual_pp  == null)
		$cuota_actual_pp = 0; 
	$cuotas_total_pp =  $factura[0]->PlanPago_Coutas;
	if($cuotas_total_pp  == null)
		$cuotas_total_pp = 0; 
	$monto_cueta_pp = arreglar_numero($factura[0]->PlanPago_MontoCuota);
	if($monto_cueta_pp  == null)
		$monto_cueta_pp = 0; 

	

	
	$a_cuenta = arreglar_numero($factura[0]->Conexion_Acuenta);
	if($a_cuenta  == null)
		$a_cuenta = 0; 
	if($factura[0]->Conexion_Latitud == "1" )
		$riego = floatval ($configuracion[17]->Configuracion_Valor);
	else  $riego  =  0;
	if($riego  == null)
		$riego  =0;
	if($deuda_anterior == 0)  // lleva pagando al dia las boletas
		$bonificacion_pago_puntual =  (floatval ($excedente) + floatval($tarifa_basica)) * floatval (5) / floatval(100) ;//con bonificacion
	else
		$bonificacion_pago_puntual = 0;
	
	$subtotal = floatval($deuda_anterior) + 
			floatval ($tarifa_basica)  + 
			floatval ($excedente)  +   
			floatval ($cuota_social) +
			floatval ($monto_cueta_medidor)  +
			floatval ($monto_cueta_pp) +
			$riego;
	//$subtotal = arreglar_numero($factura[0]->Factura_SubTotal);
	if($subtotal  == null)
		$subtotal = 0; 
	$total = $subtotal - $bonificacion_pago_puntual - $a_cuenta;
	$monto_total  = $total ;
	$monto_vencido = arreglar_numero(floatval($monto_total ) + floatval($monto_total )  * floatval($configuracion[18]->Configuracion_Valor) );
	//$factura[0]->monto 

	$primer_vencimiento = false;
	$segundo_vencimiento = false;
	if ((intval(date("d"))>=14) && (intval(date("d"))<=30) )
	{
		$segundo_vencimiento = true;
		//actualizo el monto toal de la boleta, por pagar fuera de termino
		$monto_total  = arreglar_numero(floatval($monto_total ) + floatval($monto_total )  * floatval($configuracion[18]->Configuracion_Valor) );
		//echo $monto_total;die();
		//echo $factura[0]->monto; die();

		$pago_atrasado = 1;
		echo '
		<div class="row">
			<div class="alert alert-danger">
				Se encuentra en el vencimiento, 1° Vto
			</div>
		</div>';
	}
	else	$pago_atrasado = 0;
	$pago_atrasado = 0; //voy a poner siempre si atraso
	?>
	<div class="card">
		<div class="card-body card-padding">
			<div class="row">
			<input type="hidden" ng-init="inicio_de_variables(<?php 

			echo 
				$monto_total.",". //monto toal
				$deuda_anterior.", '". //dueda anterior 
				$categoria."',". // categoria
				$tarifa_basica.",". //tarifa basica
				$multiplicador.",". //multiplicador
				$excedente.",". // excedente
				$cuota_social.",". // cuota social
				$cuota_actual_medidor.",". // cuota actual de plan medidor
				$cuotas_total_medidor.",". // cantidad de cuotas de plan medidor
				$monto_cueta_medidor.",". // monto de cuota de plan medidor
				$cuota_actual_pp." ,". // cuota actual de plan de pago
				$cuotas_total_pp.",". //cantidad de cuotas de plan de pago
				$monto_cueta_pp.",". //monto cuota de plan medidor
				$subtotal." ,".
				$a_cuenta." ,".
				$riego." ,".
				$bonificacion_pago_puntual.",".
				$pago_atrasado
				; 
			?>)" >
				<div class="col-md-3 col-xs-12">
					<label for="inputFacturaAjax">Codigo de barra</label>
					<div class="input-group form-group">
						<span class="input-group-addon"><i class="zmdi zmdi-tab"></i></span>
						<div class="fg-line">
							<input id="inputFacturaAjax" <?php 
							if(isset($codigo))
							{
								echo 'value="'.$codigo.'"';
								echo 'readonly';
							}
							?>
							type="text" name="inputFacturaAjax" class="form-control input-sm" required>
							<input id="inputFacturaOculta" type="text" name="inputFacturaOculta" style="display:none"
							<?php
								if(isset($factura))
									echo 'value="'.$factura[0]->id.'"';
							?>
							>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-xs-12">
					<label for="inputDate">Fecha de Emision</label> - <label for="inputDateC">Cancelación</label>
					<div class="input-group form-group">
						<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
						<div class="col-md-6 col-xs-6">
							<div class="dtp-container dropdown fg-line open">
								<input id="inputDate" type="text" name="fechaEmision" class="form-control input-sm date-picker" data-toggle="dropdown" readonly 
								<?php 
									if(isset($factura))
									{
										$aux =  str_replace('-', '/', $factura[0]->fecha_emision);
										$fecha_inicio = date("d/m/Y", strtotime($aux));
										echo 'value="'.$fecha_inicio.'"';
									}
								?>
								>
							</div>
						</div>
						<div class="col-md-6 col-xs-6">
							<div class="dtp-container dropdown fg-line open">
								<input id="inputDateC" type="text" name="fechaCancelacion" class="form-control input-sm date-picker" data-toggle="dropdown" readonly
								<?php 
									if(isset($factura))
									{
										$aux =  str_replace('-', '/', $factura[0]->Factura_Vencimiento1);
										$fecha_fin = date("d/m/Y", strtotime($aux));
										echo 'value="'.$fecha_fin.'"';
									}
								?>
								>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2 col-xs-12">
					<label for="inputMoneda">Monto Total</label>
					<div class="input-group form-group">
						<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
						<div class="fg-line">
							<input id="inputMonto" type="text" name="monto" ng-model="monto_total_a" class="form-control input-sm" readonly
							<?php 
								if(isset($factura))
									echo 'value="'.arreglar_numero($factura[0]->monto).'"';
							?>
							>
						</div>
					</div>
				</div>
				<div class="col-md-2 col-xs-12">
					<label for="inputConexionId">Conexion</label>
					<div class="input-group form-group">
						<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
						<div class="fg-line">
							<input id="inputConexionId" type="text" name="conexion" class="form-control input-sm"  readonly
							<?php 
								if(isset($factura))
									echo 'value="'.$factura[0]->Conexion_Id.'"';
								else echo 'ng-model="conexion_angular"';
							?>
							> 	
						</div>
					</div>
				</div>
				<div class="col-md-2 col-xs-12">
					<label for="inputMoneda">Fecha del Pago</label>
					<div class="input-group form-group">
						<div class="fg-line">
							<input id="fechaPago" type="date" name="fechaPago" ng-model="fecha_elegida_angular" ng-change="calcular_aumento_monto_por_fecha()" >
						</div>
					</div>
				</div>
			</div>
			<input type="text" name="fechaalgo" id="fechaalgo" ng-model="fechacorregida" >
			<div class="row">
					<div class="col-md-2 col-xs-12">
						<label for="inputTipoPago">Tipo de Pago</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line select">
								<select id="inputTipoPago" type="text" name="inputTipoPago"  ng-model="tipo_de_pago" ng-change="cambiar_parcial_o_total()" class="form-control input-sm" required>
									<option value="1" selected>Total</option>
									<option value="2">Parcial</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12">
						<label for="inputDeuda">Deuda de la Conexion</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line">
								<input id="inputDeudaAnterior" type="text" name="deuda" class="form-control input-sm" ng-model="deuda_anterior_a" readonly
								<?php 
									if(isset($factura))
									{
										echo 'value="'.$factura[0]->Conexion_Deuda.'"';
									}
							  ?>
							  >
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12">
						<label for="inputMes">Mes Factura</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line">
								<input id="inputMes" type="text" name="mes" class="form-control input-sm" readonly
								<?php 
									if(isset($factura))
									{
										echo 'value="'.$factura[0]->Medicion_Mes.'"';
									}
							  ?>>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12">
						<label for="inputAnio">Año Factura</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line">
								<input id="inputAnio" type="text" name="anio" class="form-control input-sm" readonly
								<?php 
									if(isset($factura))
									{
										echo 'value="'.$factura[0]->Medicion_Anio.'"';
									}
							  ?>>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12">
						<label for="inputCategoria">Categoria</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
							<div class="fg-line">
								<input id="inputCategoria" type="text" name="categoria" ng-model="categoria_a" class="form-control input-sm" readonly
								<?php 
									if(isset($factura))
										echo 'value="'.$factura[0]->Conexion_Categoria.'"';
								?>
								>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-12"  style="display:none" id="div_Montodiferente">
						<label for="inputMontoModif">Monto A Pagar</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
							<div class="fg-line">
								<input id="inputMontoModif" type="text" name="inputMontoModif" class="form-control input-sm" ng-model="pago_parcial_a" ng-change="pago_parcial()" >
							</div>
						</div>
					</div>
					<button 
					<?php 
						if(isset($factura))
							echo 'style="display: block"';
						else echo 'style="display: none"';
					 ?>
					  type="button" id="mas_datos_boleta" name="mas_datos_boleta" class="btn btn-success waves-effect">
					<i class="zmdi zmdi-plus"></i>
					</button>
					<button style="display: none" type="button" id="menos_datos_boleta" name="menos_datos_boleta" class="btn btn-danger waves-effect">
					<i class="zmdi zmdi-minus"></i>
					</button>
				</div>
			<div id="mas_datos" style="display:none">
			<hr class="style5">
				<div class="row">
					<div class="col-md-4">
						<label class="control-label" for="inputCliente">Cliente</label>
						<div class=" input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
							<div class="fg-line">
								<input id="inputCliente" type="text" name="cliente" class="form-control input-sm" readonly
								<?php 
								if(isset($factura))
									echo 'value="'.$factura[0]->Cli_RazonSocial.'"';
							  ?>
							  >
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<label class="control-label" for="inputRuc">Id</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
							<div class="fg-line">
								<input id="inputIdCliente" type="text" name="inputIdCliente" class="form-control input-sm" readonly
								<?php 
								if(isset($factura))
									echo 'value="'.$factura[0]->id_cliente.'"';
								?> 
								>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<label class="control-label" for="inputRuc">DNI</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
							<div class="fg-line">
								<input id="inputRuc" type="text" name="ruc" class="form-control input-sm" disabled
								<?php 
								if(isset($factura))
									echo 'value="'.$factura[0]->Cli_NroDocumento.'"';
							?>
							>

							</div>
						</div>
					</div>
					<div class="col-md-4">
						<label class="control-label" for="inputDireccion">Dirección</label>
						<div class="input-group form-group">
							<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
							<div class="fg-line">
								<input id="inputDireccion" type="text" name="direccion" class="form-control input-sm" disabled
								<?php 
								if(isset($factura))
									echo 'value="'.$factura[0]->Cli_DomicilioSuministro.'"';
								?>
								>
							</div>
						</div>
					</div>
				</div>
				<hr class="style5">
				<div class="row">
							<div class="col-md-2">
								<label class="control-label" for="inputAnterior">Anterior</label>
								<div class=" input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
									<div class="fg-line">
										<input id="inputAnterior" type="text" name="anterior" class="form-control input-sm" readonly
										<?php 
											if(isset($factura))
											{
												echo 'value="'.$factura[0]->Medicion_Anterior.'"';
											}
										?>>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<label class="control-label" for="inputActual">Actual</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
									<div class="fg-line">
										<input id="inputActual" type="text" name="actual" class="form-control input-sm" disabled
										<?php 
											if(isset($factura))
											{
												echo 'value="'.$factura[0]->Medicion_Actual.'"';
											}
										?>
										>
									</div>
								</div>
							</div>
							<!-- <div class="col-md-2">
								<label class="control-label" for="inputtotal">Total</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
									<div class="fg-line">
										<input id="inputtotal" type="text" name="inputtotal" class="form-control input-sm" disabled
										<?php 
											if(isset($factura))
											{
												echo 'value="'.arreglar_numero($factura[0]->Factura_Total).'"';
											}
										?>
										>
									</div>
								</div>
							</div> -->
							<div class="col-md-2">
								<label class="control-label" for="inputBasico">Basico</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
									<div class="fg-line">
										<input id="inputBasico" type="text" name="basico" class="form-control input-sm" disabled
										<?php 
											if(isset($factura))
												echo 'value="'.$factura[0]->Medicion_Basico.'"';
										?>
										>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<label class="control-label" for="inputExcedente">Excedente</label>
								<div class="input-group form-group">
									<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
									<div class="fg-line">
										<input id="inputExcedente" type="text" name="excedente" class="form-control input-sm" disabled
										<?php 
											if(isset($factura))
											{
												echo 'value="'.$factura[0]->Medicion_Excedente.'"';
											}
										?>
										>
									</div>
								</div>
							</div>
				</div>
			</div>


		</div>
	</div>
	
	<div class="card" id="div_deuda" style="display:none">
		<div class="card-header bgm-bluegray m-b-20">
			<h2>Duda del Cliente</h2>
		</div>
		<div class="card-body card-padding">
			<div class="row">
				<div class="col-md-5">
					<label class="control-label" for="inputCliente">Cliente</label>
					
				</div>
				<div class="col-md-2">
					<label class="control-label" for="inputRuc">DNI</label>
					
				</div>
				<div class="col-md-5">
					<label class="control-label" for="inputDireccion">Dirección</label>
					
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-9">
			<div class="card repeater">
				<div class="card-body card-padding">
					<div class="row">
						<table class="table table-responsive">
						<thead>
							<tr>
								<th>#</th>
								<th>Descripcion</th>
								<th>Cuotas</th>
								<th>Valor</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">1</th>
								<td>Deuda Anterior</td>
								<td></td>
								<td>
									<div class="fg-line">
										<input id="inputDeudaAnterior_tabla" type="text" name="deudaTotal" ng-model="deuda_anterior_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)" 
										<?php 
										if( isset($factura))
										{
												echo 'value="'.$deuda_anterior.'"';
										}
										?>
										>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row">2</th>
								<td>Tarifa Basica</td>
								<td></td>
								<td><input type="text" name="deudaTarifa" id="inputTarifaSocial"  ng-model="tarifa_social_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"   
								<?php 
									if( isset($configuracion))
										echo 'value="'.$tarifa_basica.'"';
									?>
								>
								</td>
							</tr>
							<tr>
								<th scope="row">3</th>
								<td> Excedente</td>
								<td></td>
								<td><input type="text" name="deudaExcente" id="inputdeudaExcente" ng-model="exdenete_a"  ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"
								<?php 
									if( isset($factura))
									{
										echo 'value="'.$excedente.'"';
									}
									?>>
								</td>
							</tr>
							<tr>
								<th scope="row">4</th>
								<td> Couta Social</td>
								<td></td>
								<td><input type="text" name="cuotaSocial" id="inputdeudaCuotaSocial" ng-model="cuota_social_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"
								<?php 
									if( isset($factura)  )
									{
											echo 'value=" '.$cuota_social.'"';
									}
									?>
									>
								</td>
							</tr>
							<tr  class="table-warning">
								<th scope="row">5</th>
								<td> Medidor</td>
								<td><input type="text" name="planmedidorcuotaactual" id="inputplanmedidorcuotaactual" readonly 
								<?php 
									if( isset($factura) &&  ( $factura[0]->PlanMedidor_CoutaActual != null ) && ( $factura[0]->PlanMedidor_CoutaActual != 0 ) &&  ( $factura[0]->PlanMedidor_Coutas != null ) &&  ( $factura[0]->PlanMedidor_Coutas != 0 ) )
									{
											echo 'value="'.$factura[0]->PlanMedidor_CoutaActual.'/'.$factura[0]->PlanMedidor_Coutas.'"';
									}
									?>>
								</td>
								<td>
									<input type="text" name="planmedidor" id="inputplanmedidor"  ng-model="monto_cuota_medidor_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"
									<?php 
										if( isset($factura) &&  (is_numeric( $factura[0]->PlanMedidor_MontoCuota) ) )
										{
												echo 'value="'.$monto_cueta_medidor.'"';
										}
										?>>
									<input type="hidden" name="planmedidor_id" id="inputplanmedidor_id" 
									<?php 
										if( isset($factura) &&  (is_numeric( $factura[0]->PlanMedidor_Conexion_Id) ) )
										{
												echo 'value="'.arreglar_numero($factura[0]->PlanMedidor_Conexion_Id).'"';
										}
										?>
										>
								</td>
							</tr>
							<tr>
								<th scope="row">6</th>
								<td> Plan Pago</td>
								<td><input type="text" name="planpagocuotaactual" id="inputplanpagoplanpagocuotaactual" readonly 
								<?php 
									if( isset($factura) &&  (is_numeric( $factura[0]->PlanPago_Id) ) )
									{
										echo 'value="'.$factura[0]->PlanPago_CoutaActual.' / '.$factura[0]->PlanPago_Coutas.' " ';
									}
								?>>
								</td>
								<td>
								<input type="text" name="planpago" id="inputplanpago" ng-model="monto_cuota_plan_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"
									<?php 
									if( isset($factura) &&  (is_numeric( $factura[0]->PlanPago_Id) ) )
									{
										echo 'value="'.$monto_cueta_pp.'" ';
									}
									?> 
								>
								<input type="hidden" name="planpago_id" id="inputplanpago_id"
								<?php 
									if( isset($factura) &&  (is_numeric( $factura[0]->PlanPago_Id) ) )
									{
										echo 'value="'.arreglar_numero($factura[0]->PlanPago_Id).'" ';
									}
								?> 
								>
								</td>
							</tr>
							<tr class="table-info">
								<th scope="row">7</th>
								<td> RIego </td>
								<td></td>
								<td><input type="text" name="riego" id="riego" ng-model="riego_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"
								<?php 
									if( isset($factura) )
									{
										echo 'value="'.$riego.'" ';
									}
								?> 
								></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr class="table-info">
								<th scope="row">7</th>
								<td> SubTotal </td>
								<td></td>
								<td><input type="text" name="subtotal" id="inputsubtotal" ng-model="subtotal_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"
								<?php 
									if( isset($factura) )
									{
										echo 'value="'.$subtotal.'" ';
									}
								?> 
								></td>
							</tr>
							<tr class="table-info">
								<th scope="row">8</th>
								<td> Pagos A Cuenta </td>
								<td></td>
								<td><input type="text" name="pagosacuenta" id="inputpagosacuenta" ng-model="pago_a_cuenta_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"
								<?php 
									if( isset($factura) &&  (is_numeric( $factura[0]->Conexion_Acuenta) ) )
									{
											echo 'value="'.$a_cuenta.'" ';
									}
									?> 
								></td>
							</tr>
							<tr class="table-info">
							  <th scope="row">9</th>
							  <td> Bonificacion </td>
							  <td>
								<?php 
								if($this->session->userdata('rol') == "administrador")
								{
									if(isset($factura))
										echo '<button type="button" id="agregar_bonificacion" name="agregar_bonificacion" class="btn btn-primary waves-effect">Agregar</button>';
									else echo '<button style="display: none" type="button" id="agregar_bonificacion" name="agregar_bonificacion" class="btn btn-primary waves-effect">Agregar</button>';
									echo '<button style="display: none" type="button" id="Borrar_bonificacion_btn" name="Borrar_bonificacion_btn" class="btn btn-danger waves-effect">Borrar</button>';
									echo '<button style="display: none" type="button" id="Modificar_bonificacion_btn" name="Modificar_bonificacion_btn" class="btn btn-warning waves-effect">Modificar</button>';
								}
									echo 
										'<div id="datos_bonificacion_seleccione" >
										<label for="enreparacion">Solicitar Bonificacion?</label>
											<div class="input-group form-group">
												NO&nbsp&nbsp&nbsp
												<div class="toggle-switch">';
												if (isset($medidor))
													if($medidor->Medidor_Habilitacion == 1 )
														echo '<input id="enreparacion" type="checkbox" hidden="hidden" checked>';
													else echo '<input id="enreparacion" type="checkbox" hidden="hidden">';
												else echo '<input id="enreparacion" type="checkbox" hidden="hidden" >';
										echo '<label for="enreparacion" class="ts-helper"></label>
										</div>&nbsp&nbsp&nbspSI
										</div>
										<input type="text" name="rep_oculto" id="rep_oculto" style="display:none">';
								?>
								
							  </div>
							  <div id="datos_bonificacion" style="display:none">
								<div class="row" id="porcentaje_bonificacion_form_div">
									<input type="hidden" name="hay_bonificacion_form" id="hay_bonificacion_form"  >
									<label for="porcentaje_bonificacion_form"> Porcentaje Descontado</label>
									<input type="text" name="porcentaje_bonificacion_form" id="porcentaje_bonificacion_form" readonly >
								</div>
								<div class="row" id="monto_descontado_form_div">
									<label for="monto_descontado_form">Monto descontado</label>
									<input type="text" name="monto_descontado_form" id="monto_descontado_form" readonly >
								</div>
								<div class="row" id="monto_a_descontar_form_div">
									<label for="monto_a_descontar_form">Monto a descontar</label>
									<input type="text" name="monto_a_descontar_form" id="monto_a_descontar_form" readonly >
								</div>
							  </div>
							  </td>
							  <td><input type="text" name="bonificacion" id="inputbonificacion" value="<?php echo $bonificacion_pago_puntual; ?>"></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr class="table-info">
							  <th scope="row">10</th>
							  <td> Endeuda </td>
							  <td> <i class="zmdi zmdi-flight"></i> </i></td>
							  <td><input type="text" name="endeuda" id="inputendeuda" ng-model="endeuda_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr class="table-success">
								<th scope="row">11</th>
								<td> Total </td>
								<td></td>
								<td><input type="text" name="total" id="inputtotal" ng-model="monto_total_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"
								<?php 
								if(isset($factura))
										echo 'value="'.$monto_total.'"';
								?>
								>
							</td>
							</tr>
								<input type="text"  name="total_sin_cambios" id="total_sin_cambios" ng-model="monto_total_sin_modificar_a"  style="display: none"
								<?php 
									if(isset($factura))
										echo 'value="'.$monto_total.'"';
								?>
							>
						<tr class="table-success">
							  <th scope="row">12</th>
							  <td> Descuento a aplicar </td>
							  <td></td>
							  <td><input type="text" name="descuento_a_aplicar" id="descuento_a_aplicar" ng-model="descuento_a_aplicar_a" ng-change="calcular_montos($datos['configuracion'][18]->Configuracion_Valor)"></td>
							</tr>
						   
						  </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div ng-app="">
			<button type="button"  id="pagar_boleta" name="pagar_boleta" class="btn btn-success waves-effect " style="width:24%; height: 60px"> <h3><strong>PAGAR</strong></h3></button>
			<br>
			<p></p>
		</div>

		<div class="col-md-3">
			<div class="card">
				<div class="card-header bgm-gray">
					<h2>Total: <strong>{{monto_total_a}}</strong> </h2>
					
				</div>
				<div class="card-body card-padding">
							<div class="row form-group">
								<div class="input-group">
								  <span class="input-group-addon">Entregado</span>
								  <input id="inputEntregado" type="text" class="form-control" >
								</div>
								<br/>
								<div class="input-group">
								  <span class="input-group-addon">Vuelto</span>
								  <input id="inputVuelto" type="text" class="form-control">
								</div>
								<br/>
								<div class="input-group">
								  <span class="input-group-addon">Total</span>
								  <input id="inputtotal" type="text" name="inputtotal" class="form-control">
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-12">
									<div class="checkbox">
										<label>
											<input id="incluyeIGV" name="igv" type="checkbox" value="0">
											<i class="input-helper"></i>
											Precio Incluye IGV
										</label>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="panel panel-warning">
													<div class="panel-heading">
														Tipo de Pago
													</div>
													<div class="panel-body">
														<div class="col-md-12">
															<div class="radio">
																<label>
																	<input id="pago_contado" name="optionsRadios" type="radio" value="1" checked>
																	<i class="input-helper"></i>
																	Contado
																</label>
															</div>
														</div>
										<div class="col-md-12">
											<div class="radio">
												<label>
													<input id="pago_tarjeta" name="optionsRadios" type="radio" value="0">
													<i class="input-helper"></i>
													Cheque
												</label>
											</div>
										</div>
										<div class="col-md-12">
											<div class="radio">
												<label>
													<input id="pago_mixto" name="optionsRadios" type="radio" value="2">
													<i class="input-helper"></i>
													Mixto
												</label>
											</div>
										</div>
										<input id="optionsRadios_hiden" name="optionsRadios_hiden" value="1" type="hidden">
										<div class="row" id="mixto_div" style="display:none">
											<div class="form-group fg-line">
												<label class="control-label" for="input_mixto_contado">Cantidad de Contado</label>
												<input type="text" id="input_mixto_contado" name="input_mixto_contado" class="form-control input-sm" ng-change="mixto_calculo_desde_contado()" ng-model="cantidad_contado">
											</div>
											<div class="form-group fg-line">
												<label class="control-label" for="input_mixto_tarjeta">Cantidad de Cheque</label>
												<input type="text" id="input_mixto_tarjeta" name="input_mixto_tarjeta" class="form-control input-sm" ng-change="mixto_calculo_desde_tarjeta()" ng-model="cantidad_tarjeta">
											</div>
										</div>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-md-12">
									<label for="hab_medidor">Solicitar Plan de Pago?</label>
									<div class="input-group form-group">
										NO&nbsp&nbsp&nbsp
										<div class="toggle-switch">
										<?php 
										if (isset($medidor))
											if($medidor->Medidor_Habilitacion == 1 )
												echo '<input id="hab_medidor" type="checkbox" hidden="hidden" checked>';
											else echo '<input id="hab_medidor" type="checkbox" hidden="hidden">';
										else echo '<input id="hab_medidor" type="checkbox" hidden="hidden" >';
										?>  
											<label for="hab_medidor" class="ts-helper"></label>
										</div>&nbsp&nbsp&nbspSI
									</div>
									<input type="text" name="hab__oculto" id="hab_oculto" style="display:none">
								</div>
								<?php 
								//if($this->session->userdata('item'))
								echo '
								<div class="col-md-12" id="plan_de_pago_div">
									<label for="hab_medidor">Crear Plan de Pago para medidor?</label>
									<div class="input-group form-group">';
									if(isset($factura))
										echo '<button style="display: block"';
									else echo '<button style="display: none"';
										echo ' type="button"  id="agregar_PlanMedidor" name="agregar_PlanMedidor" class="btn btn-primary waves-effect">

										<i class="zmdi zmdi-plus"></i>
										</button>
									</div>
								</div>';

								echo '
								<div class="col-md-12" id="plan_de_pago_quitar_modificar_div">
									<div class="input-group form-group">
										<button style="display: none" type="button"  id="modificar_PlanMedidor" name="modificar_PlanMedidor" class="btn btn-warning waves-effect">
										modificar
										</button>
										<button style="display: none" type="button"  id="quitar_PlanMedidor" name="quitar_PlanMedidor" class="btn btn-danger waves-effect">
										<i class="zmdi zmdi-minus"></i>
										</button>

									</div>
								</div>';

								?>
								<div class="col-md-12" id="datos_plan_pago_ya_creado" style="display:none">

									<input id="id_agregarPlanPago_ya_creado" type='hidden' value="">
									<input id="ismodal_agregarPlanPago_ya_creado" type='hidden' value="false">
									<div class="row">
										<div class="col-md-6">
											<label for="inputFechaInicio_agregarPlanPago_ya_creado">Fecha de inicio</label>
											<div class="input-group form-group">
												<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
												<div class="dtp-container dropdown fg-line open">
													<input id="inputFechaInicio_agregarPlanPago_ya_creado" type="text" name="inputFechaInicio_agregarPlanPago_ya_creado" class="form-control input-sm date-picker" data-toggle="dropdown">
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<label for="inputCantidadCuotas_agregarPlanPago_ya_creado">Cantidad de cuotas</label> 
											<div class="input-group form-group">
												<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
												<div class="fg-line">
													<input id="inputCantidadCuotas_agregarPlanPago" type="text" name="cantidadCuotas" class="form-control"  value= "">
												</div>
											</div>
										</div>	
									</div>
									<div class="row">
										<div class="col-md-6">
											<label for="inputMontoCuota_agregarPlanPago_ya_creado">Monto de cuota</label>
											<div class="input-group form-group">
												<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
												<div class="fg-line">
													<input id="inputMontoCuota_agregarPlanPago"  type="text" name="montoCuota" class="form-control" required value="">
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<label for="inputInteres_agregarPlanPago_ya_creado">Interes</label>
											<div class="input-group form-group">
												<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
												<div class="fg-line">
													<input id="inputInteres_agregarPlanPago" type="text" name="interes" class="form-control" >
												</div>
											</div>
										</div>
									<input id="inputMontoTotal_agregarPlanPago" type="hidden" maxlength="10" name="montoTotal" class="form-control input-sm" required>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label for="inputObservacion_agregarPlanPago_ya_creado">Observaciones</label>
											<div class="input-group form-group">
												<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
												<div class="fg-line">
													<input id="inputObservacion_agregarPlanPago"  type="text" name="inputObservacion" class="form-control input-sm" >
												</div>
											</div>
										</div>
									</div>
								</div> 
						
				</div>
			</div>
		</div>
	</div>
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


	<div class="modal fade" id="myModal_Bonificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Agregando una Bonificacion</h4>
				</div>
				<div class="modal-body">
					<div id="nueva_bonificacion_div">
						
					</div>
				</div>
				<div class="modal-footer">
					<button type="button"  class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					<button type="button"  class="btn btn-success" data-dismiss="modal">Guardar</button>
				</div>
			</div>
		</div>
	</div>
</div>


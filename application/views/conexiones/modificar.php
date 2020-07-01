<?php 

//Info devulve FALSO si no hay datos 
if (!isset($info)) { $info = FALSE; }


$tipo_conexion = array(
		'Familiar' 		=> 'Familiar',
		'Comercial'		=> 'Comercial'
);
$tipo_conexion_atributos = 'id="tipo_conexion" name="tipo_conexion" class="form-control input-sm"';



// $sector = array(
// 		'id'			=> 'sector',
// 		'name'			=> 'sector',
// 		'class'			=> 'form-control input-sm',
// 		'value'			=> ($info) ? $info->Conexion_Sector:"",
// 		'required'		=> 'required'
// );


$enviar = array(
		'name'			=> 'enviar',
		'class'			=> 'btn-success btn',
		'type'			=> 'submit',
		'style'			=> 'width:100%',
		'content'		=> 'Guardar'
);

$reset = array(
		'class'			=> 'btn btn-danger',
		'type'			=> 'reset',
		'style'			=> 'width:100%',
		'content'		=> 'Borrar'
);


$volver = array(
		'class'			=> 'btn btn-primary',
		'type'			=> 'button',
		'style'			=> 'width:100%',
		'content'		=> 'Volver'
);

$dom_suministro = array(
		'id'			=> 'inputDomSum',
		'name'			=> 'inputDomSum',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Conexion_DomicilioSuministro:"",
		'required'		=> 'required',
		'placeholder'	=> 'Distrito - Ciudad - Provincia',
);

$nom_cliente = array(

		'id'			=> 'inputCliente',
		'name'			=> 'cliente',
		'type'			=> "text",
		'readonly' => 'readonly',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_RazonSocial:"",
		'required'		=> 'required'
);
$deuda = array(

		'id'			=> 'inputDeuda',
		'name'			=> 'deuda',
		'type'			=> "number",
		'step'			=> "0.01",
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Conexion_Deuda:""
);
$multa = array(

		'id'			=> 'inputMulta',
		'name'			=> 'multa',
		'type'			=> "number",
		'step'			=> "0.01",
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Conexion_Multa:""
);
// $multa_motivo = array(

// 		'id'			=> 'textMulta',
// 		'name'			=> 'multa_motivo',
// 		'type'			=> "textarea",
// 		'maxlength'			=> "250",
// 		'value'			=> ($info) ? $info->Conexion_Multa_Motivo:""
// );

$acuenta = array(

		'id'			=> 'inputAcuetna',
		'name'			=> 'acuenta',
		'type'			=> "number",
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Conexion_Acuenta:""
);

$habilitacion = array(
		'id'			=> 'inputHabilitacion',
		'name'			=> 'inputHabilitacion',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Conexion_Habilitacion:""


);

if(isset($info))
{
	$id = array(
			'id'			=> 'id_oculto',
			'name'			=> 'id_oculto',
			'style'     => 'display:none',
			'value'			=> ($info) ? $info->Conexion_Cliente_Id:""
			);


	$id_conexion = array(
			'id'			=> 'id_oculto_conexion',
			'name'			=> 'id_oculto_conexion',
			'style'     => 'display:none',
			'value'			=> ($info) ? $info->Conexion_Id:""


			
	);
}





?>
	<!-- contents -->
	
	<div class="row">
		<!-- Agregar clientes -->
		<?php echo $this->session->flashdata('msje_datos_guardados'); ?>
		<div class="card">
			<div class="card-header">
				<h3>Modificando Conexion</h3>
			</div>
			<div class="card-body card-padding">
						
				<?php 
					echo form_open("conexion/modificar");?>
					
					<?php echo form_input($id_conexion);  ?>
					
					<div class="row">
						<div class="col-md-6">
							<label for="inputDomSum">Dirección suminsitro</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
								<div class="fg-line">
									<?php echo form_input($dom_suministro); ?>
								</div>
							</div>
						</div>
								

						<div class="col-md-4">
							<label for="select_sector_imprimir">Seleccion Sectores:</label>
							<select name="sector" id="sector" class="chosen"  >
								<?php 
								if( isset($info))
									echo '<option value="-9" selected>Actual:'.$info->Conexion_Sector.'</option>';
								foreach ($conexiones_a_imprimir as $key) {
									echo '<option value="'.$key->Conexion_Sector.'" >'.$key->Conexion_Sector.'</option>';
								}
								?>
							</select>
							<!-- <label for="inputLocalidad">Sector</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-map"></i></span>
								<div class="fg-line">
									<?php //echo form_input($sector); ?>
								</div>
							</div> -->
						</div>
					</div>
					<?php echo form_input($id);  ?>
					<?php echo form_input($id_conexion);  ?>
					
					<div class="row">
						<div class="col-md-6">
							<label for="inputTipoPersona">Tipo de Conexion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line select">
									<?php echo form_dropdown('tipo_conexion', $tipo_conexion, ($info)?$info->Conexion_Categoria:"", $tipo_conexion_atributos);?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label class="control-label" for="inputCliente">Cliente</label>
							<div class=" input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<!-- <input id="inputCliente" type="text" name="cliente" class="form-control input-sm" required>
									<input type="hidden" id="inputIdCliente" name="idcliente"> -->
									<?php echo form_input($nom_cliente);  ?>

								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label class="control-label" for="inputDeuda">Deuda</label>
							<div class=" input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<?php echo form_input($deuda);  ?>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<label class="control-label" for="inputMulta">multa</label>
							<div class=" input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<?php echo form_input($multa);  ?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label class="control-label" for="multa_motivo">motivo multa </label>
								<textarea maxlength="250" id="multa_motivo" name="multa_motivo" cols="55" rows="3"><?php if(isset($info->Conexion_Multa_Motivo)&& ($info->Conexion_Multa_Motivo != null) ) echo $info->Conexion_Multa_Motivo;?></textarea>
						</div>


						
						<div class="col-md-6">
							<label class="control-label" for="inputAcuetna">Aceunta</label>
							<div class=" input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<?php echo form_input($acuenta);  ?>
								</div>
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="select_riego">Riego:</label>
							<select name="select_riego" id="select_riego" class="chosen"  >
								<?php 
								if (isset($info))
									if ($info->Conexion_Latitud == 1)
									{

										echo '<option value="1" selected>Si, ya tiene</option>';
										echo '<option value="0" >No</option>';
									}
									else
									{
										echo '<option value="0" selected>No, aun no tiene</option>';	
										echo '<option value="1" >Si</option>';
									} 
								
								?>
							</select>
						</div>
					</div>
					<br>
					
					<div class="row">
						<div class="col-md-4">
							<?php echo form_button($enviar); ?>
						</div>
						<div class="col-md-4">
							<?php echo form_button($reset); ?>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("conexion"); ?>"><?php echo form_button($volver); ?></a> 
						</div>
					</div>
			<?php echo form_close(); ?>
		</div>
		</div>

		
		<!-- fin de agregar clientes -->
	</div>
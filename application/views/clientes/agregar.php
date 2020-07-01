<?php 

//Info devulve FALSO si no hay datos 
if (!isset($info)) { $info = FALSE; }

$tipo_persona_atributos = 'id="inputTipoPersona" name="inputTipoPersona" class="form-control input-sm"';
$tipo_persona = array(
		'Natural'		=> 'Natural',
		'Juridico' 		=> 'Juridico'
);

$tipo_documento = array(
		'dni' 		=> 'DNI',
		'Libreta Civica'		=> 'Libreta Civica',
		'Libreta Enrolamiento'		=> 'Libreta Enrolamiento',
		'Entre Privado'		=> 'Entre Privado',
		'Entre Publico'		=> 'Entre Público'
);
$tipo_documento_atributos = 'id="tipo_documento" class="form-control input-sm"';


$tipo_iva = array(
		'1' 		=> 'Excento',
		'2'		=> 'IVA general',
		'3'		=> 'IVA reducida',
		'4'		=> 'IVA superreducido'
);
$tipo_iva_atributos = 'id="tipo_iva" class="form-control input-sm"';


$tipo_conexion = array(
		'1' 		=> 'Familiar',
		'2'		=> 'Comercial',
		'3'		=> 'Profesional',
		
);
$tipo_conexion_atributos = 'id="tipo_conexion" name="tipo_conexion" class="form-control input-sm"';



/*

$tipo_documento = array(
		'id' 			=> 'tipo_documento',
		'name'			=> 'tipo_documento',
		'placeholder' 	=> 'RUC',
		'value' 		=> 'RUC',
		'class' 		=> 'form-control input-sm'
);*/

$nro_documento = array(
		'id' 			=> 'nro_documento',
		'name' 			=> 'nro_documento',
		'max' 	=> '99999999',
		'min' 	=> '1000000',
		'type' 	=> 'number',
		'class' 		=> 'form-control input-sm',
		//'value'			=> ($info) ? $info->Cli_NroDocumento : "88888888",
		'value'			=> ($info) ? $info->Cli_NroDocumento : "",
		'required' 		=> 'required'
);

$tienda_atributos = 'id="inputTienda" class="form-control input-sm"';
$tienda = array(
		'tienda1'		=> 'Tienda 1',
		'tienda2'		=> 'Tienda 2',
		'global'		=> 'Global'
);


$pago_medidor_atributos = 'id="inputTipoPago" name="inputTipoPago" class="form-control input-sm"';
$pago_medidor= array(
		'Contado'		=> 'Contado',
		'Tarjeta'		=> 'Coutas'
);

$cantidad_cuotas_medidor_atributos = 'id="cantidadCuotas" name="cantidadCuotas" class="form-control input-sm"';
$cantidad_cuotas_medidor= array(
		'1'		=> '1',
		'2'		=> '2',
		'3'		=> '3',
		'4'		=> '4',
		'5'		=> '5'
);



/*
$Observacion = array(
		'id'			=> 'inputObservacion',
		'name'			=> 'inputObservacion',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_Observacion:"",
		'required'		=> 'required'
);*/

$razon_social = array(
		'id'			=> 'razon_social',
		'name'			=> 'razon_social',
		'type' 	=> 'text',
		'maxlength' 	=> '50',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_RazonSocial:"",
		//'value'			=> "Juancho Aguirre",
		'required'		=> 'required'
);

$representante = array(
		'id'			=> 'representante',
		'name'			=> 'representante',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_NroCliente:""
);

$email = array(
		'id'			=> 'email',
		'name'			=> 'email',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_Email:"",
		//'value'			=> ($info) ? $info->Cli_Email:"maildepr444a@gmail.com",
		//'required'		=> 'required',
		'type'			=> 'email'
);

$telefono = array(
		'id'			=> 'inputTelefono',
		'name'			=> 'telefono',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_Telefono:"",
		'required'		=> 'required',
		'placeholder'	=> '01 123 4567',
		'type'	 		=> 'text',
		'maxlength'		=> '20'
);

$celular = array(
		'id'			=> 'inputCelular',
		'name'			=> 'celular',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_Celular:"",
		'type'	 		=> 'text',
		'placeholder'	=> '987 654 321',
		'maxlength'		=> '18',
);

$direccion = array(
		'id'			=> 'inputDireccion',
		'name'			=> 'direccion',
		'type' 	=> 'text',
		'maxlength' 	=> '60',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_DomicilioPostal:""
);

// $localidad = array(
// 		'id'			=> 'inputLocalidad',
// 		'name'			=> 'localidad',
// 		'class'			=> 'form-control input-sm',
// 		'type' 	=> 'text',
// 		'maxlength' 	=> '50',
// 		'value'			=> ($info) ? $info->Cli_Localidad:"",
// 		'required'		=> 'required',
// 		'placeholder'	=> 'Barrio - Ciudad',
// );

$sector = array(
		'id'			=> 'inputsector',
		'name'			=> 'inputsector',
		'class'			=> 'form-control input-sm',
		//'value'			=> ($info) ? $info->Conexion_Sector:"1",
		'value'			=> ($info) ? $info->Conexion_Sector:"",
		'required'		=> 'required',
		'placeholder'	=> 'Barrio - Ciudad',
);

$enviar = array(
		'name'			=> 'enviar',
		'class'			=> 'btn-success btn',
		'type'			=> 'submit',
		'type'			=> 'button',
		'id'            => 'enviando_formulario',
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


$cuit = array(
		'id'			=> 'inputCuit',
		'name'			=> 'inputCuit',
		'type' 	=> 'text',
		'maxlength' 	=> '15',
		'class'			=> 'form-control input-sm',
		//'value'			=> ($info) ? $info->Cli_Cuit:"98123698527",
		'value'			=> ($info) ? $info->Cli_Cuit:"",
		'placeholder'	=> 'xx-xxxxxxxx-x',
);

$nro_cliente = array(
		'id'			=> 'inputNroCliente',
		'name'			=> 'inputNroCliente',
		'value'			=> '0',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_NroCliente:""
);

$num_medidor = array(
		'id'			=> 'inputNroMedidor',
		'name'			=> 'inputNroMedidor',
		'text'			=> 'number',
		'class'			=> 'form-control input-sm',
		//'value'			=> (isset($Medidor)) ? $Medidor->Medidor_Id:"87545"
		'value'			=> (isset($Medidor)) ? $Medidor->Medidor_Id:""
);


$precio_medidor = array(
		'id'			=> 'inputPrecioMedidor',
		'name'			=> 'inputPrecioMedidor',
		'text'			=> 'number',
		'disable'			=> 'true',
		'class'			=> 'form-control input-sm',
		'required'			=> 'required',
		//'value'			=> (isset($Precio)) ? $Precio:"200"
		'value'			=> (isset($precio)) ?  $precio: ""
);


$dom_postal = array(
		'id'			=> 'inputDomPost',
		'name'			=> 'inputDomPost',
		'class'			=> 'form-control input-sm',
		'type' 	=> 'text',
		'maxlength' 	=> '60',
		//'value'			=> ($info) ? $info->Cli_DomicilioPostal:"Calle 15 sin numero",
		'value'			=> ($info) ? $info->Cli_DomicilioPostal:"",
		'required'		=> 'required',
		'placeholder'	=> 'Distrito - Ciudad - Provincia',
);


$dom_suministro = array(
		'id'			=> 'inputDomSum',
		'name'			=> 'inputDomSum',
		'class'			=> 'form-control input-sm',
		//'value'			=> ($info) ? $info->Cli_DomicilioPostal:"ruta 41 a 501 de calle 12",
		'value'			=> ($info) ? $info->Cli_DomicilioPostal:"",
		'required'		=> 'required',
		'type' 	=> 'text',
		'maxlength' 	=> '60',
		'placeholder'	=> 'Distrito - Ciudad - Provincia',
);


$deudor = array(
		'id'			=> 'inputDeudor',
		'name'			=> 'inputDeudor',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_Deudor:""
);

$iva = array(
		'id'			=> 'inputIVA',
		'name'			=> 'inputIVA',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_TipoIVAId:""
);

$observacion = array(
		'id'			=> 'inputObservacion',
		'name'			=> 'inputObservacion',
		'class'			=> 'form-control text-area-sm',
		'type' 	=> 'text',
		'maxlength' 	=> '200',
		'value'			=> ($info) ? $info->Cli_Observacion:""
		//'value'			=> ($info) ? $info->Cli_Observacion:"esta son las obevaciones de pruebas"
);

$habilitacion = array(
		'id'			=> 'inputHabilitacion',
		'name'			=> 'inputHabilitacion',
		'class'			=> 'form-control input-sm',
		'value'			=> ($info) ? $info->Cli_Habilitacion:""


);

if(isset($info))
{
	$id = array(
			'id'			=> 'id_oculto',
			'name'			=> 'id_oculto',
			'style'     => 'display:none',
			'value'			=> ($info) ? $info->Cli_Id:""
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
				<?php echo heading($titulo, 2); ?>
			</div>
			<div class="card-body card-padding">
				<?php 
				$attributes = array('id' => 'myform');
				//echo form_open('email/send', $attributes);
				if( isset($url) )
					echo form_open($url,$attributes);
				else  echo form_open("",$attributes);?>
					<div class="row">
						<div class="col-md-3">
							<label for="inputTipoPersona">Tipo Persona</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line select">
									<?php echo form_dropdown('tipo_persona', $tipo_persona, ($info)?$info->Cli_TipoPersona:"", $tipo_persona_atributos);?>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputTipoDocumento">Tipo Documento </label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-file"></i></span>
								
								<div class="fg-line select">
									<?php echo form_dropdown('tipo_documento', $tipo_documento, ($info)?$info->Cli_TipoDoc:"", $tipo_documento_atributos);?>
								
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputNroDocumento">Nro. Documento <font color= "red">*</font></label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<div class="fg-line">
									<?php echo form_input($nro_documento);?>
								</div>
							</div>
						</div>
						<div class="col-md-3" style="display:none">
							<label for="inputNroCliente">Nro Cliente</label>
							<div class="input-group form-group">
								<!-- span class="input-group-addon"><i class="zmdi zmdi-local-store"></i></span>
								<div class="fg-line select">
									<?php echo form_dropdown('tienda', $tienda,($info) ? $info->Cli_Id:"", $tienda_atributos); ?>
								</div> -->
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<div class="fg-line">
									<?php echo form_input($nro_cliente);?>
								</div>
							</div>
						</div> 
					</div>
					<?php echo form_input($id);  ?>
					<?php echo form_input($id_conexion);  ?>
					
					<div class="row">
						<div class="col-md-8">
							<label for="inputRazonSocial">Razón Social <font color= "red">*</font></label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-receipt"></i></span>
								<div class="fg-line">
									<?php echo form_input($razon_social); ?>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label for="inputCuit">Cuit</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<?php echo form_input($cuit); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<label for="inputEmail">Email</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
								<div class="fg-line">
									<?php echo form_input($email);?>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<label for="inputTelefono">Teléfono <font color= "red">*</font></label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-phone"></i></span>
								<div class="fg-line">
									<?php echo form_input($telefono); ?>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label for="inputCelular">Celular</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-smartphone-android"></i></span>
								<div class="fg-line">
									<?php echo form_input($celular); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<label for="inputDomPost">Domicilio postal <font color= "red">*</font></label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
								<div class="fg-line">
									<?php echo form_input($dom_postal); ?>
								</div>
							</div>
						</div>
						<!-- <div class="col-md-4">
							<label for="inputLocalidad">Localidad</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-map"></i></span>
								<div class="fg-line">
									<?php echo form_input($localidad); ?>
								</div>
							</div>
						</div> -->
					</div>	
					
					<div class="row">
						<div class="col-md-3">
							<label for="inputTipoPersona">Tipo de IVA</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line select">
									<?php echo form_dropdown('tipo_iva', $tipo_iva, ($info)?$info->Cli_TipoIVAId:"", $tipo_iva_atributos);?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputNroDocumento">Observacion del cliente</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
								<div class="fg-line">
									<?php echo form_input($observacion);?>
								</div>
							</div>
						</div>
						<!-- <div class="col-md-3">
							<label for="inputNroCliente">Habilitacion</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
		                          
								<?php if( ($info == false) || isset($info->Cli_Habilitacion )  && $info->Cli_Habilitacion == 1)
								echo '<input id="hab" type="checkbox" hidden="hidden" checked>';
								else echo '<input id="hab" type="checkbox" hidden="hidden">';
								?>  
		                            <label for="hab" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
							</div>
						</div> -->
					</div>
					<hr>
					<h4>Agregando Conexión</h4>
					<br>
					<div class="row">
						<div class="col-md-6">
							<label for="inputDomSum">Dirección suminsitro <font color= "red">*</font></label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
								<div class="fg-line">
									<?php echo form_input($dom_suministro); ?>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label for="select_sector_imprimir">Seleccion Sector: <font color= "red">*</font></label>
								<select name="inputsector" id="inputsector" class="chosen" data-placeholder="Elige los sectores" >
									<?php 
									foreach ($conexiones_a_imprimir as $key) {
										echo '<option value="'.$key->Conexion_Sector.'" >'.$key->Conexion_Sector.'</option>';
									}
									?>
								</select>
						</div>
					</div>
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
					</div>
					<hr>
					<h4>Agregando Medidor</h4>
					<br>
					<div class="row">
						<div class="col-md-6">
							<label for="inputDomSum">Ingrese el numero del medidor</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
								<div class="fg-line">
									<?php echo form_input($num_medidor); ?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputTipoPersona">Tipo de Pago</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line select">
									<?php echo form_dropdown('pago_medidor', $pago_medidor, (isset($medidor))?$medidor->Medidor_Id:"", $pago_medidor_atributos);?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6" id="div_cantidad_cuotas" style="display:none">
							<label for="inputTipoPersona">Cantidad de Cuotas</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<div class="fg-line select">
									<?php echo form_dropdown('pago_cantidad', $cantidad_cuotas_medidor, $cantidad_cuotas_medidor_atributos);?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputDomSum">Precio Actual del Medidor <font color= "red">*</font></label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
								<div class="fg-line">
									<?php echo form_input($precio_medidor); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<?php echo form_button($enviar); ?>
						</div>
						<div class="col-md-4">
							<?php echo form_button($reset); ?>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("clientes"); ?>"><?php echo form_button($volver); ?></a> 
						</div>
					</div>
				<?php echo form_close(); ?>
		</div>
		</div>

		
		<!-- fin de agregar clientes -->
	</div>

	<script src="<?php echo base_url();?>js/validations/validations_agregar_cliente.js"></script>
        

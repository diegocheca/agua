<?php 

//Info devulve FALSO si no hay datos 
if (!isset($info)) { $info = FALSE; }


$tipo_conexion = array(
		'1' 		=> 'Familiar',
		'2'		=> 'Comercial'
);
$tipo_conexion_atributos = 'id="tipo_conexion" name="tipo_conexion" class="form-control input-sm"';



$sector = array(
		'id'			=> 'sector',
		'name'			=> 'sector',
		'class'			=> 'form-control input-sm',
		'value'			=>	"",
		'required'		=> 'required'
);


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




	$id_cliente = array(
			'id'			=> 'id_cliente',
			'name'			=> 'id_cliente',
			'type'			=> 'text',
			'value'			=> ""
			);
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
					echo form_open("conexion/recibir_datos_agregar_solo_conexion");?>
					
					
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
							<label for="inputLocalidad">Sector</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-map"></i></span>
								<div class="fg-line">
									<?php echo form_input($sector); ?>
								</div>
							</div>
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
						<div class="col-md-4">
							<label for="inputLocalidad">Id de Cliente</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-map"></i></span>
								<div class="fg-line">
									<?php echo form_input($id_cliente); ?>
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
							<a href="<?php echo base_url("conexion"); ?>"><?php echo form_button($volver); ?></a> 
						</div>
					</div>
				<?php echo form_close(); ?>
					
		</div>
		</div>

		
		<!-- fin de agregar clientes -->
	</div>
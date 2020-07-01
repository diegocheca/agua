<?php 
$form_atributos	= array(
	'class'			=> 'form-horizontal'
);

$fecha = array(
	'id'			=> 'inputFecha',
	'name'			=> 'fecha',
	'class'			=> 'form-control',
	'placeholder'	=> 'Seleccione la Fecha'
);

$cliente = array(
	'id'			=> 'inputCliente',
	'name'			=> 'cliente',
	'class'			=> 'form-control',
	'placeholder'	=> 'Seleccione el cliente'
);

//Genera un campo TYPE = HIDDEN
$idcliente	= array(
	'id'			=> 'inputIdCliente',
	'name'			=> 'idcliente',
	'type'			=> 'hidden'
);

$vendedor 	= array(
	'id'			=> 'inputVendedor',
	'name'			=> 'vendedor',
	'class'			=> 'form-control',
	'placeholder'	=> 'Nombre de vendedor'
);

$empresa = array(
	'id'			=> 'inputEmpresa',
	'name'			=> 'empresa',
	'class'			=> 'form-control',
	'placeholder'	=> 'Escriba el Nombre de la Empresa'
);

$contenido = array(
	'id'		=> 'textContenido',
	'name'		=> 'contenido',
	'class'		=> 'form-control'
);

$itemtitulo	= array(
	'id'		=> 'inputTitulo',
	'name'		=> 'itemtitulo',
	'class'		=> 'form-control'
);

$itemdescripcion = array(
	'id' 		=> 'inputDescripcion',
	'name'		=> 'itemdescripcion',
	'class' 	=> 'form-control'
);

$itempunitario = array(
	'id'		=> 'inputPUnitario',
	'name'		=> 'itempunitario',
	'class'		=> 'form-control'
);

$itemcantidad = array(
	'id'		=> 'inputCantidad',
	'name'		=> 'itemcantidad',
	'class'		=> 'form-control'
);

$itemptotal = array(
	'id'		=> 'inputPTotal',
	'name'		=> 'itemptotal',
	'class'		=> 'form-control'
);

$preciofinal = array(
	'id'		=> 'inputPrecioFinal',
	'name'		=> 'preciofinal',
	'class'		=> 'form-control'
);

$btn_guardar = array(
	'name'		=> 'guardar_proforma',
	'class'		=> 'btn-success');

?>

<div class="card">
	<div class="card-header">
		<?php echo heading('Realizar Cotización', 2); ?>
	</div>
	<div class="card-body card-padding">
		<?php echo form_open(base_url('proformas/guardar_proforma'), $form_atributos); ?>
		<div class="form-group">
			<label for="<?php echo $fecha['id'];?>" class="col-md-3 control-label">Seleccione la fecha:</label>
			<div class="col-md-9">
				<div class="fg-line">
					<?php echo form_input($fecha);?>	
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="<?php echo $cliente['id'];?>" class="col-md-3 control-label">Busque el cliente:</label>
			<div class="col-md-9">
				<div class="fg-line">
					<?php echo form_input($cliente);?>
					<?php echo form_input($idcliente); ?>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="<?php echo $vendedor['id'];?>" class="col-md-3 control-label">Nombre de Vendedor:</label>
			<div class="col-md-9">
				<div class="fg-line">
					<?php echo form_input($vendedor); ?>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label for="<?php echo $empresa['id'];?>" class="col-md-3 control-label">Nombre de Empresa</label>
			<div class="col-md-9">
				<div class="fg-line">
					<?php echo form_input($empresa);?>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="<?php echo $contenido['id'];?>" class="col-md-3 control-label">Ingrese el Cotenido:</label>
			<div class="col-md-9">
			<div class="html-editor"></div>
				<div class="fg-line">
					<?php echo form_textarea($contenido); ?>
				</div>
			</div>
		</div>

		<div class="form-group">
			<button id="algo" name="algo" type="button">Algo</button>
		</div>
		
		
		<div class="form-group">
			<?php echo form_input($itemtitulo); ?>
		</div>
		<div class="form-group">
			<?php echo form_input($itemdescripcion); ?>
		</div>
		<div class="form-group">
			<?php echo form_input($itempunitario); ?>
		</div>
		<div class="form-group">
			<?php echo form_input($itemcantidad); ?>
		</div>
		<div class="form-group">
			<?php echo form_input($itemptotal); ?>
		</div>
		<div class="form-group">
			<?php echo form_input($preciofinal); ?>
		</div>		
		
		<?php echo form_submit($btn_guardar, 'Crear Cotización'); ?>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	
      $("#algo").on("click",function(){
        var algo = $("#textContenido").val();
        alert(textContenido);
        console.log(textContenido);
    });
</script>
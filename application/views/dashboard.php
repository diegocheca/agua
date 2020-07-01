
<!-- contents -->
<?php 
if ($this->session->userdata('rol') == 'administrador')
{

	?>
<!-- <div class="row">

	<!-- Card de Facturas ->
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-cyan">
				<h2>Facturas</h2>
			</div>
			<div class="card-body card-padding text-center">
				<div class="easy-pie sec-pie-1 m-b-15" data-percent="63">
					<div class="percent">63</div>
					<div class="pie-title">Facturas Pagadas </div>
				</div>
				<?php // echo anchor('facturar/crear_nueva','Crear Facturas',array('class'=>'btn btn-block btn-lg bgm-cyan')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Facturas ->

	<!-- Card de Clientes ->
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-lightgreen">
				<h2>Ordenes de trabajo</h2>
			</div>
			<div class="card-body card-padding text-center">
				<div class="easy-pie sec-pie-2 m-b-15" data-percent="95">
					<div class="percent">95</div>
					<div class="pie-title">Ordenes Completas</div>
				</div>
				<?php echo anchor('orden_trabajo/','Detalles',array('class'=>'btn btn-block btn-lg bgm-lightgreen')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Clientes ->

	<!-- Card de Inventario ->

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-pink">
				<h2>Mediciones Tomadas</h2>
			</div>
			<div class="card-body card-padding text-center">
				<div class="easy-pie sec-pie-3 m-b-15" data-percent="80">
					<div class="percent">80</div>
					<div class="pie-title">Mediciones Agregadas</div>
				</div>
				<?php echo anchor('mediciones/cargar_mediciones_por_lote/','Nuevas Mediciones',array('class'=>'btn btn-block btn-lg bgm-pink')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Inventario ->

</div> --><!--Fin Row Panels-->
<?php 
}
?>

	
<!-- contents -->

<div class="row">

	<!-- Card de Facturas -->
<!-- 	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-indigo">
				<h2>Pagos</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('pago/agregar/','Crear Pago',array('class'=>'btn btn-block btn-lg bgm-indigo')); ?>
			</div>
		</div>
	</div> -->

	<!-- ./ Card de Facturas -->

	<!-- Card de Clientes -->
<!-- 
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-blue">
				<h2>Medidores</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('clientes/','Clientes',array('class'=>'btn btn-block btn-lg bgm-blue')); ?>
			</div>
		</div>
	</div>
 -->
	<!-- ./ Card de Clientes ->

	<!-- Card de Inventario -->

<!-- 	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-red">
				<h2>Medidores</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('inventario/','Medidores',array('class'=>'btn btn-block btn-lg bgm-red')); ?>
			</div>
		</div>
	</div> -->

	<!-- ./ Card de Inventario -->

</div><!--Fin Row Panels-->



<!-- contents ->
<div class="row">

	<!-- Card de Facturas --
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-purple">
				<h2>Plan Trabajo</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('trabajo/agregar/','Crear Orden de Trabajo',array('class'=>'btn btn-block btn-lg bgm-purple')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Facturas ->

	<!-- Card de Clientes ->

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-deeppurple">
				<h2>Medidores</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('clientes/','Clientes',array('class'=>'btn btn-block btn-lg bgm-deeppurple')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Clientes ->

	<!-- Card de Inventario ->

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-teal">
				<h2>Medidores</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('inventario/','Medidores',array('class'=>'btn btn-block btn-lg bgm-teal')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Inventario ->

</div><!--Fin Row Panels-->



<!-- contents -->
<!-- <div class="row">

	<!-- Card de Facturas ->
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-green">
				<h2>Plan Trabajo</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('trabajo/agregar/','Crear Orden de Trabajo',array('class'=>'btn btn-block btn-lg bgm-green')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Facturas ->

	<!-- Card de Clientes ->

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-lightgreen">
				<h2>Medidores</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('clientes/','Clientes',array('class'=>'btn btn-block btn-lg bgm-lightgreen')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Clientes ->

	<!-- Card de Inventario ->

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-lime">
				<h2>Medidores</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('inventario/','Medidores',array('class'=>'btn btn-block btn-lg bgm-lime')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Inventario ->

</div><!--Fin Row Panels->

	
<!-- contents ->
<div class="row">

	<!-- Card de Facturas ->
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-deeporange">
				<h2>Plan Trabajo</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('trabajo/agregar/','Crear Orden de Trabajo',array('class'=>'btn btn-block btn-lg bgm-deeporange')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Facturas ->

	<!-- Card de Clientes ->

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-bluegray">
				<h2>Medidores</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('clientes/','Clientes',array('class'=>'btn btn-block btn-lg bgm-bluegray')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Clientes ->

	<!-- Card de Inventario ->

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-black">
				<h2>Medidores</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('inventario/','Medidores',array('class'=>'btn btn-block btn-lg bgm-black')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Inventario->

</div><!--Fin Row Panels->
	
<!-- contents ->
<div class="row">

	<!-- Card de Facturas ->
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-gray">
				<h2>Plan Trabajo</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('trabajo/agregar/','Crear Orden de Trabajo',array('class'=>'btn btn-block btn-lg bgm-gray')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Facturas ->

	<!-- Card de Clientes ->

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-amber">
				<h2>Medidores</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('clientes/','Clientes',array('class'=>'btn btn-block btn-lg bgm-amber')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Clientes ->

	<!-- Card de Inventario ->

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-brown">
				<h2>Medidores</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('inventario/','Medidores',array('class'=>'btn btn-block btn-lg bgm-brown')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Inventario ->

</div><!--Fin Row Panels->
	
	
<!-- contents ->
<div class="row">

	<!-- Card de Facturas ->
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="card z-depth-2">
			<div class="card-header bgm-orange">
				<h2>Plan Trabajo</h2>
			</div>
			<div class="card-body card-padding text-center">
				<?php echo anchor('trabajo/agregar/','Crear Orden de Trabajo',array('class'=>'btn btn-block btn-lg bgm-orange')); ?>
			</div>
		</div>
	</div>

	<!-- ./ Card de Facturas ->

</div><!--Fin Row Panels->
	 -->

	
	
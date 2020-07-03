<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title><?php echo $titulo; ?></title>

		<!-- Vendor CSS -->
		<link href="<?php echo base_url();?>vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>vendors/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
		<link href="<?php echo base_url();?>vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>vendors/bower_components/summernote/dist/summernote.css" rel="stylesheet">


		<link href="<?php echo base_url();?>vendors/chosen_v1.4.2/chosen.min.css" rel="stylesheet">


		<!-- CSS -->
		<link href="<?php echo base_url();?>css/app.min.1.css" rel="stylesheet">
		<link href="<?php echo base_url();?>css/app.min.2.css" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo base_url("css/autocomplete-styles.css"); ?>">


	   <script src="<?php echo base_url();?>js/jquery-2.1.1.min.js"></script>
	     		<script src="<?php echo base_url();?>js/angular.min.js"></script>
        <script src="<?php echo base_url();?>js/angular/agragar_nuevo.js"></script>
		
	</head>
	<body>
		<header id="header">
			<ul class="header-inner">
				<li id="menu-trigger" data-trigger="#sidebar">
					<div class="line-wrap">
						<div class="line top"></div>
						<div class="line center"></div>
						<div class="line bottom"></div>
					</div>
				</li>
			
				<li class="logo hidden-xs">
					<a href="<?php echo base_url(); ?>">Sistema de Facturación</a>
				</li>
				
				<li class="pull-right">
				<ul class="top-menu">
					<li id="toggle-width">
						<div class="toggle-switch">
							<input id="tw-switch" type="checkbox" hidden="hidden">
							<label for="tw-switch" class="ts-helper"></label>
						</div>
					</li>
<!-- 					<li id="top-search">
						<a class="tm-search" href=""></a>
					</li> -->
					<li class="dropdown">
						<a data-toggle="dropdown" class="tm-settings" href=""></a>
						<ul class="dropdown-menu dm-icon pull-right">
							<li>
								<a data-action="fullscreen" href="">Poner Pantalla Completa</a>
							</li>
							<!-- <li>
								<a data-action="clear-localstorage" href="">Borrar Memoria Local</a>
							</li> -->
							<!-- <li>
								<a href="">Ver Perfil</a>
							</li> -->
							<li>
								<li><?php echo anchor('configuracion','Configucion'); ?></li>
							</li>
							<li>
								<a href="<?php echo base_url("home/logout")?>">Cerrar Sesión</a>
							</li>
						</ul>
					</li>

					</ul>
				</li>
			</ul>
			
			<!-- Top Search Content -->
			<div id="top-search-wrap">
				<input type="text">
				<i id="top-search-close">&times;</i>
			</div>
		</header>

		<!-- breadcrumb -->
	   <!--  <?php if($segmentos > 0):
			echo $bread; ?>
		<?php endif; ?> -->
		<!-- ./ breadcrumb -->



		<section id="main">
			<aside id="sidebar">
				<div class="sidebar-inner">
					<div class="si-inner">
						<div class="profile-menu">
							<a href="" style="">
								<div class="profile-pic">
									<!-- <a data-target="#image_pick" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#image_pick"></a> -->
									<img src="<?php echo base_url(user_data('avatar'));?>" alt="">
								</div>
								
								<div class="profile-info">
									<?php echo user_data('usuario'); ?>
								</div>
							</a>
						</div>
						
						<ul class="main-menu">
							<li>
								<a href="<?php echo base_url()?>"><i  style="color:indigo"  class="zmdi zmdi-home"></i> Dashboard</a>
							</li>
							<!-- <li class="sub-menu">
								<a href=""><i class="zmdi zmdi-search-in-page"></i> Facturas</a>
								<ul>
									<li><?php echo anchor('facturar','Facturas Creadas'); ?></li>
									<!-- <li><?php echo anchor('facturar/crear','Crear Factura'); ?></li> ->
								</ul>
							</li> -->
							<li class="sub-menu">
								<a href=""><i style="color:green"  class="zmdi zmdi-money"></i> Pagos</a>

								<ul>
									<li><?php echo anchor('pago','Lista de pagos'); ?></li>
									<!-- <li><?php echo anchor('pago/agregar','Agregar Pago'); ?></li> -->
								</ul>
							</li>
							<li class="sub-menu">
								<a href=""><i style="color:orange"  class="zmdi zmdi-assignment-check"></i> Conexiones</a>
								<ul>
									<li><?php echo anchor('conexion','Lista de Conexiones'); ?></li>
								</ul>
							</li>
							<li class="sub-menu">
								<a href=""><i  style="color:blue" class="zmdi zmdi-folder-person"></i> Clientes</a>
				
								<ul>
									<li><?php echo anchor('clientes','Lista de Clientes'); ?></li>
									<li><?php echo anchor('clientes/agregar','Cliente Nuevo'); ?></li>
								</ul>
							</li>
							
<!-- 
							<li class="sub-menu">
								<a href=""><i  style="color:orange" class="zmdi zmdi-time"></i> Medidores</a>
				
								<ul>
									<li><?php echo anchor('inventario','Lista de Medidores'); ?></li>
									<li><?php echo anchor('inventario/agregar_producto','Cargar Medidor'); ?></li>
								</ul>
							</li> -->
							<!--  <li class="sub-menu">
								<a href=""><i class="zmdi zmdi-accounts"></i> Tipos de Medidores</a>
								<ul>
									<li><?php echo anchor('tipos_medidores','Lista de Tipos de Medidores'); ?></li>
									<li><?php echo anchor('tipos_medidores/agregar_tipo','Cargar Tipo Medidor'); ?></li>
								</ul>
							</li>
						  -->
							
							<li class="sub-menu">
								<a href=""><i style="color:red" class="zmdi zmdi-network"></i>Orden de trabajo</a>
								<ul>
									<li><?php echo anchor('orden_trabajo','Lista de ordenes de trabajo'); ?></li>
								</ul>
							</li>
							<li class="sub-menu">
								<a href=""><i style="color:cyan"  class="zmdi zmdi-trending-up"></i>Reportes</a>
								<ul>
									<li><?php echo anchor('imprimir/tareas_terminadas','Ordenes de trabajo Terminadas', array('target' => '_blank', 'class' => 'new_window')); ?></li>
									<li><?php echo anchor('imprimir/deudas_conexiones','Deuda de conexiones', array('target' => '_blank', 'class' => 'new_window')); ?></li>
									<li><?php echo anchor('imprimir/pp_de_conexiones','Planes de Pagos conexiones', array('target' => '_blank', 'class' => 'new_window')); ?></li>
								</ul>
							</li>
						  <!--   <li class="sub-menu">
								<a href=""><i class="zmdi zmdi-equalizer"></i>Proformas</a>
								<ul>
									<li><a href="#">Proformas</a></li>
									<li><?php echo anchor('proformas/nuevo', 'Crear Proforma'); ?></li>
								</ul>
							</li> -->
							<li class="sub-menu">
								<a href=""><i  style="color:gray"  class="zmdi zmdi-accounts"></i>Usuarios</a>
								<ul>
									<li><?php echo anchor('usuarios','Lista de usuarios'); ?></li>
								</ul>
							</li>
							<li class="sub-menu">
								<a href=""><i class="zmdi zmdi-equalizer"></i>Mediciones</a>
								<ul>
									<!-- <li><?php echo anchor('mediciones','Lista de mediciones'); ?></li> -->
									<li><?php echo anchor('mediciones/cargar_mediciones_por_lote','Cargar Mediciones Por lote'); ?></li>
									<?php 
									if($this->session->userdata('rol') == "administrador")
									echo '<li>  '.anchor('mediciones/mediciones_a_aprobar','Mediciones Raras').'</li>';
									?>
									<li><?php echo anchor('conexion/probando_lista','Ordenar Lote'); ?></li>
								</ul>
							</li>
							<!-- <li class="sub-menu">
								<a href=""><i style="color:green" class="zmdi zmdi-laptop-mac"></i>Plan de pago</a>
								<ul>
									<li><?php echo anchor('plan_pago','Lista de plan de pago'); ?></li>
								</ul>
							</li> -->
							<?php
							if($this->session->userdata('rol') == 'administrador') 
								{
									?>
							<li class="sub-menu">
								<a href=""><i style="color:indigo" class="zmdi zmdi-money-box"></i>Movimientos</a>
								<ul>
									<li><?php echo anchor('movimientos/','Lista de movimientos'); ?></li>
									<!-- <li><?php echo anchor('movimientos/ingresos','Lista de ingresos'); ?></li>
									<li><?php echo anchor('movimientos/egresos','Lista de egresos'); ?></li> -->
								</ul>
							</li>
							<?php }?>
							<!-- <li class="sub-menu">
								<a href=""><i style="color:pink" class="zmdi zmdi-truck"></i>Materiales</a>
								<ul>
									<li><?php echo anchor('materiales','Lista de materiales'); ?></li>
								</ul>
							</li> -->
							<!-- <li class="sub-menu">
								<a href=""><i style="color:red" class="zmdi zmdi-money-off"></i>Bonificaciones</a>
								<ul>
									<li><?php echo anchor('bonificacion','Pendientes'); ?></li>
									<li><?php echo anchor('bonificacion/cargar_aprobadas','Aprobadas'); ?></li>
									<li><?php echo anchor('bonificacion/cargar_otorgadas','Otorgadas'); ?></li>
								</ul>
							</li> -->
							<?php
							if($this->session->userdata('rol') == 'administrador') 
								{
									?>

							<li class="sub-menu">
								<a href=""><i style="color:blue" class="zmdi zmdi-settings-square"></i>Configuracion</a>
								<ul>
									<li><?php echo anchor('configuracion','Precios a Configurar'); ?></li>
								</ul>
							</li>
							<?php }?>
							
							<!-- <li class="sub-menu">
								<a href=""><i style="color:orange" class="zmdi zmdi-money-box"></i>Deudas</a>
								<ul>
									<li><?php echo anchor('deuda','Lista de deudas'); ?></li>
								</ul>
							</li> -->
							<?php
							if($this->session->userdata('id_user')== 1)
							{ ?>
							<li class="sub-menu">
								<a href=""><i style="color:red" class="zmdi zmdi-daydream-setting"></i>SUDO</a>
								<ul>
									<li><?php echo anchor('usuarios/join_zarpado','Super join'); ?></li>
									<li><?php echo anchor('facturar/ultra_automatica','Crear Boletas'); ?></li>
									<li><?php echo anchor('Automatico/crear_tabla_mediciones','Mediciones Vacias'); ?></li>
									
								</ul>
							</li>
							<li class="sub-menu">
								<a href=""><i style="color:red" class="zmdi zmdi-daydream-setting"></i>Auditoria</a>
								<ul>
									<li><?php echo anchor('autorizacion','Auditoria'); ?></li>
									
								</ul>
							</li>
<?php
} ?>

						</ul>
					</div>
				</div>
			</aside>
			


	<div class="modal fade" id="image_pick" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" id="picker_contenido">
				<!-- <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Graficos de clientes</h4>
				</div>
				<div class="modal-body">
					<div >
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div> -->
			</div>
		</div>
	</div>
			<section id="content">
				<div class="container" style="width:95%">
				<?php echo $this->session->flashdata("document_status"); ?>


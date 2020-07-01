<!DOCTYPE html>
	<!--[if IE 9 ]><html class="ie9"><![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title><?php echo $titulo; ?></title>
		
		<!-- Vendor CSS -->
		<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"
  />
		<link href="http://localhost/codeigniter/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
		<link href="http://localhost/codeigniter/vendors/socicon/socicon.min.css" rel="stylesheet">
			
		<!-- CSS -->
		<link href="http://localhost/codeigniter/css/app.min.1.css" rel="stylesheet">


<style type="text/css">
	body {
  margin:0px;
  height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  font-family:"Open Sans",sans-serif;
  background:#222;
}
.lc-block {
  width:35%;
  height:50%;
  border:10px solid #ccc;
  border-radius:10px;
  line-height:200px;
  text-align:center;
  color:#ddd;
  font-size:25px;
  font-weight:600;
  text-transform:uppercase;
  position:relative;
  overflow:hidden;
}
.lc-block:before {
  content:"";
  position:absolute;
  width:2400px;
  height:2700px;
  background:#00acee;

  transform:translateX(-50%);
  border-radius:40%;
  animation:fill 15s ease-in-out infinite;
  z-index:-1;
}
@keyframes fill {
  from {
    top:350px;
    transform:translateX(-50%) rotate(0deg);
  }
  to {
    top:-450px;
    transform:translateX(-50%) rotate(360deg);
  }
}



</style>
	</head>
	
	<body class="login-content" style="background: #0066ff;">
		<!-- Login -->
		 
			<div class="lc-block toggled" id="l-login" >
				<div id="status"></div>
				<form id="loginform" class="form-horizontal" role="form">
				<?php echo $this->session->flashdata('mensaje'); ?>
				<div class="input-group m-b-20">
					<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
					<div class="fg-line">
						<input id="inputUser" type="text" class="form-control" name="user" placeholder="Nombre de Usuario">
					</div>
				</div>
				
				<div class="input-group m-b-20">
					<span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
					<div class="fg-line">
						<input id="inputPassword" type="password" class="form-control" name="password" placeholder="Contraseña">
					</div>
				</div>
				
				<div class="clearfix"></div>
				
				<div class="checkbox">
					<label>
						<input type="checkbox" value="">
						<i class="input-helper"></i>
						No cerrar sesion
					</label>
				</div>
				
				<a class="btn btn-login btn-danger btn-float" href=""><i class="zmdi zmdi-arrow-forward"></i></a>
				
				<ul class="login-navigation">
					<li data-block="#l-register" class="bgm-red">Registrate</li>
					<li data-block="#l-forget-password" class="bgm-orange">Olvidaste la Contraseña?</li>
				</ul>
			</form>
			</div>
		<!-- Register -->
		<div class="lc-block" id="l-register">
			<div class="input-group m-b-20">
				<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
				<div class="fg-line">
					<input type="text" class="form-control" placeholder="Nombre de Usuario">
				</div>
			</div>
			
			<div class="input-group m-b-20">
				<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
				<div class="fg-line">
					<input type="text" class="form-control" placeholder="Dirección de Email">
				</div>
			</div>
			
			<div class="input-group m-b-20">
				<span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
				<div class="fg-line">
					<input type="password" class="form-control" placeholder="Password">
				</div>
			</div>
			
			<div class="clearfix"></div>
			
			<div class="checkbox">
				<label>
					<input type="checkbox" value="">
					<i class="input-helper"></i>
					Accept the license agreement
				</label>
			</div>
			
			<a href="" class="btn btn-login btn-danger btn-float"><i class="md md-arrow-forward"></i></a>
			
			<ul class="login-navigation">
				<li data-block="#l-login" class="bgm-green">Login</li>
				<li data-block="#l-forget-password" class="bgm-orange">Forgot Password?</li>
			</ul>
		</div>
		
		<!-- Forgot Password -->
		<div class="lc-block" id="l-forget-password">
			<p class="text-left">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu risus. Curabitur commodo lorem fringilla enim feugiat commodo sed ac lacus.</p>
			
			<div class="input-group m-b-20">
				<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
				<div class="fg-line">
					<input type="text" class="form-control" placeholder="Email Address">
				</div>
			</div>
			
			<a href="" class="btn btn-login btn-danger btn-float"><i class="md md-arrow-forward"></i></a>
			
			<ul class="login-navigation">
				<li data-block="#l-login" class="bgm-green">Login</li>
				<li data-block="#l-register" class="bgm-red">Register</li>
			</ul>
		</div>

<!-- 
		<div class="box">
		  Fill Effect
		</div>
		 -->
		<!-- Older IE warning message -->
		<!--[if lt IE 9]>
			<div class="ie-warning">
				<h1 class="c-white">NAVEGADOR NO COMPATIBLE!</h1>
				<p>Estas usando una version obsoleta de Internet Explorer, actualiza a cualquiera de los siguientes navegadores para aprovechar todas las funciones de esta aplicación web. </p>
				<ul class="iew-download">
					<li>
						<a href="http://www.google.com/chrome/">
							<img src="img/browsers/chrome.png" alt="">
							<div>Chrome</div>
						</a>
					</li>
					<li>
						<a href="https://www.mozilla.org/en-US/firefox/new/">
							<img src="img/browsers/firefox.png" alt="">
							<div>Firefox</div>
						</a>
					</li>
					<li>
						<a href="http://www.opera.com">
							<img src="img/browsers/opera.png" alt="">
							<div>Opera</div>
						</a>
					</li>
					<li>
						<a href="https://www.apple.com/safari/">
							<img src="img/browsers/safari.png" alt="">
							<div>Safari</div>
						</a>
					</li>
					<li>
						<a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
							<img src="img/browsers/ie.png" alt="">
							<div>IE (New)</div>
						</a>
					</li>
				</ul>
				<p>Upgrade your browser for a Safer and Faster web experience. <br/>Gracias por su paciencia...</p>
			</div>   
		<![endif]-->
		
		<!-- Javascript Libraries -->
		<script src="http://localhost/codeigniter/js/jquery-2.1.1.min.js"></script>
		<script src="http://localhost/codeigniter/js/bootstrap.min.js"></script>
		
		<script src="http://localhost/codeigniter/vendors/bower_components/Waves/dist/waves.min.js"></script>
		
		<script src="http://localhost/codeigniter/js/functions.js"></script>
		<script>


$(document).keypress(function(e) {
  if(e.which == 13) {
	e.preventDefault();
				var usuario=$("#inputUser").val();
				var password=$("#inputPassword").val();

				var parametros = {
						"user" : usuario,
						"password" : password
				};
				$.ajax({
						data:  parametros,
						url:   'ajax/login',
						type:  'post',
						beforeSend: function () {
								$("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
						},
						success:  function (response) {
								if(response === "TRUE"){
									function exito(){
									$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
									}
									window.setTimeout(exito,600);
									function redireccion(){
										location.reload();
									}
									window.setTimeout(redireccion,1200);
								}else{
									if(response === "FALSE"){
										function error(){
											$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
										}
										window.setTimeout(error,600);
									}
								}
						}
				});
  }
});


		$(document).on("ready", function(){
			$(".btn-login").click(function(e){
				e.preventDefault();
				var usuario=$("#inputUser").val();
				var password=$("#inputPassword").val();

				var parametros = {
						"user" : usuario,
						"password" : password
				};
				$.ajax({
						data:  parametros,
						url:   'ajax/login',
						type:  'post',
						beforeSend: function () {
								$("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
						},
						success:  function (response) {
								if(response === "TRUE"){
									function exito(){
									$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
									}
									window.setTimeout(exito,600);
									function redireccion(){
										location.reload();
									}
									window.setTimeout(redireccion,1200);
								}else{
									if(response === "FALSE"){
										function error(){
											$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
										}
										window.setTimeout(error,600);
									}
								}
						}
				});

			});
		});
		</script>
		
	</body>

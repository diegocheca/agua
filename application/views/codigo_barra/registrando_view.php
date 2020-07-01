<!DOCTYPE HTML>
<html>
	<head>
		<title>Control Ingreso</title>
		<meta charset="utf-8" />
		<!--icono-->
		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url("icon/apple-icon-57x57.png"); ?>">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url("icon/apple-icon-60x60.png"); ?>">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url("icon/apple-icon-72x72.png"); ?>">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url("icon/apple-icon-76x76.png"); ?>">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url("icon/apple-icon-114x114.png"); ?>">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url("icon/apple-icon-120x120.png"); ?>">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url("icon/apple-icon-144x144.png"); ?>">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url("icon/apple-icon-152x152.png"); ?>">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url("icon/apple-icon-180x180.png"); ?>">
		<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url("icon/android-icon-192x192.png"); ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url("icon/favicon-32x32.png"); ?>">
		<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url("icon/favicon-96x96.png"); ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url("icon/favicon-16x16.png"); ?>">
		<link rel="manifest" href="<?php echo base_url("icon/manifest.json"); ?>">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<style>
			#myImg {
			    border-radius: 5px;
			    cursor: pointer;
			    transition: 0.3s;
			}
			#myImg:hover {opacity: 0.7;}
			/* The Modal (background) */
			.modal {
			    display: none; /* Hidden by default */
			    position: fixed; /* Stay in place */
			    z-index: 1; /* Sit on top */
			    padding-top: 100px; /* Location of the box */
			    left: 0;
			    top: 0;
			    width: 100%; /* Full width */
			    height: 100%; /* Full height */
			    overflow: auto; /* Enable scroll if needed */
			    background-color: rgb(0,0,0); /* Fallback color */
			    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
			}

			/* Modal Content (image) */
			.modal-content {
			    margin: auto;
			    display: block;
			    width: 80%;
			    max-width: 700px;
			}

			/* Caption of Modal Image */
			#caption {
			    margin: auto;
			    display: block;
			    width: 80%;
			    max-width: 700px;
			    text-align: center;
			    color: #ccc;
			    padding: 10px 0;
			    height: 150px;
			}

			/* Add Animation */
			.modal-content, #caption {    
			    -webkit-animation-name: zoom;
			    -webkit-animation-duration: 0.6s;
			    animation-name: zoom;
			    animation-duration: 0.6s;
			}

			@-webkit-keyframes zoom {
			    from {-webkit-transform:scale(0)} 
			    to {-webkit-transform:scale(1)}
			}

			@keyframes zoom {
			    from {transform:scale(0)} 
			    to {transform:scale(1)}
			}

			/* The Close Button */
			.close_form {
			    position: absolute;
			    top: 45px;
			    right: 22%;
			    color: #ffffff;
			    font-size: 100px;
			    font-weight: bold;
			    transition: 0.3s;
			}

			.close_form:hover,
			.close_form:focus {
			    color: #fff;
			    text-decoration: none;
			    cursor: pointer;
			}

			/* 100% Image Width on Smaller Screens */
			@media only screen and (max-width: 700px){
			    .modal-content {
			        width: 100%;
			    }
			}
		</style>
		<style>
			        SELECT, INPUT[type="text"] {
			            width: 160px;
			            box-sizing: border-box;
			        }
			        SECTION {
			            padding: 8px;
			            background-color: #f0f0f0;
			            overflow: auto;
			        }
			        SECTION > DIV {
			            float: left;
			            padding: 4px;
			        }
			        SECTION > DIV + DIV {
			            width: 40px;
			            text-align: center;
			        }
		</style>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="<?php echo base_url("assets/css/main.css"); ?>" />
		<link rel="stylesheet" href="<?php echo base_url("bootstrap/css/bootstrap.min.css"); ?>" />
	</head>
	<body id="top">
			<header id="header" style="width:20%">
				<div class="inner" >
				<img src="<?php echo base_url("logo/logo-expo-feria.png"); ?>">
					
				</div>
			</header>
			<div id="main" style="padding: 2em 2em 2em 2em; margin-left: 20%;width: 80%; max-width:75%">
				<section id="three">
						<h1>Registrar Foto del Acreditado</h1>
						<div class="row">
							<div class="11u 12u$(small)">
								<form method="post" action="<?php echo base_url("index.php/buscador/buscar_codigo_foto");?>" autocomplete="off">
									<div class="row uniform 50%">
										<div class="8u 12u$(xsmall)"><input type="text" name="codigo" id="codigo" placeholder="Codigo" autofocus /></div>
									
										<div class="4u$ 12u$(xsmall)">
											<input type="submit" class="icon fa-home" id="buscar" name="buscar" value="Buscar" />
										</div>

										
									</div>
								</form>
							</div>
						</div>
					</section>
					<section id="one"  style="width:100%;margin: 0em 0em 0em 0em; padding: 0em 0em 0em 0em;">
						<?php 
						if(isset($error))
							echo '<div class="alert alert-danger">Se produjo el siguiente error:
                                <strong>'.$error.'</strong>
                            </div>';
						if(isset($dni))
						{
							echo '<h2>Datos personales</h2>';
						echo '
						<div class="row">
							<div style="width:50%">
								<div class="11u 12u$(xsmall)">
									<div class="row">
										<div class="4u 12u$(xsmall)" >
											ID:
											<input type="text" name="id_persona" id="id_persona" value="'.$id.'" disabled></input>
										</div>
										<div class="7u$ 12u$(xsmall)" >
											DNI:
											<input type="text" name="dni_traido" id="dni_traido" value="'.$dni.'" disabled></input>
										</div>
									</div>	
								</div>

								<div class="11u 12u$(xsmall)">
									<div class="row">
										<div class="11u$ 12u$(xsmall)">
											Nombre:
											<input type="text" name="nombre_traido" id="nombre_traido" value="'.$nombre." ".$apellido.'" disabled></input>
										</div>
									</div>	
								</div>

								<div class="11u 12u$(xsmall)">
									<div class="row">
										<div class="6u 12u$(xsmall)" >
											Tipo
										<input type="text" name="fecha" id="fecha" value="'.$tipo.'" disabled></input>
										</div>
										<div class="5u$ 12u$(xsmall)" >
											Stand
										<input type="text" name="sexo" id="sexo" value="'.$stand.'" disabled></input>
										</div>
									</div>	
								</div>
								<div class="11u 12u$(xsmall)">
									<div class="row">
										<div class="7u 12u$(xsmall)" >
											Nacimiento
										<input type="text" name="fecha" id="fecha" value="'.date("d/m/Y", strtotime($fecha)).'" disabled></input>
										</div>
										<div class="4u$ 12u$(xsmall)" >
											Sexo
										<input type="text" name="sexo" id="sexo" value="'.$sexo.'" disabled></input>
										</div>
									</div>	
								</div>
								<div class="11u 12u$(xsmall)">
									<div class="row">
										<ul>';
										$i=1;
										if(  (isset($movimientos) )&& ($movimientos!=false))
										{
											foreach ($movimientos as $key) {
												if($key->AMo_Estado=="E")
												{
													//<span class="label label-default">Default</span>
													//andando
															//<span class="label label-primary">'.date("d/m/Y", strtotime($key->AMo_Fecha))." ".$key->AMo_Hora. ' Puesto: '.$key->AMo_Pun_ID.'</span>
													
													echo '<div class="alert alert-success">
							                                <span class="glyphicon glyphicon-time"></span> &nbsp;&nbsp;  '.date("d/m/Y", strtotime($key->AMo_Fecha))." &nbsp;&nbsp;".$key->AMo_Hora. ' -  Puesto: '.$key->AMo_Pun_ID.'
							                            </div>';
												}
												else
												{
													 //<font color="red">Mov'.$i." ".date("d/m/Y", strtotime($key->AMo_Fecha))." ".$key->AMo_Hora. ' Puesto: '.$key->AMo_Pun_ID.'</font>
													 //andando
													//echo '<span class="label label-warning">'.date("d/m/Y", strtotime($key->AMo_Fecha))." ".$key->AMo_Hora. ' Puesto: '.$key->AMo_Pun_ID.'</span>
													echo '<div class="alert alert-danger">
							                                 <span class="glyphicon glyphicon-time"></span>&nbsp;&nbsp; '.date("d/m/Y", strtotime($key->AMo_Fecha))."&nbsp;&nbsp; ".$key->AMo_Hora. ' -  Puesto: '.$key->AMo_Pun_ID.'
							                            </div>';
												}
												$i++;
											}
										}
										else echo '
											<div class="alert alert-warning">
							                                 <span class="fa fa-times"></span>&nbsp;&nbspSin movimientos en el dia de hoy
							                            </div>';
							                            
										echo '
										</ul>
									</div>	
								</div>



							</div>
							<div style="width:50%">
								<article class="11u 12u$(xsmall) work-item">

								<img id="myImg" src="'.$foto.'?'.md5(date("Y-m-d H:i:s")).'"  alt="'.$apellido.', '.$nombre.'" width="300" height="200">
									<br>
									<div id="myModal" class="modal">
									  <span class="close_form">&times;</span>
									  <img class="modal-content" id="img01">
									  <div id="caption"></div>
									</div>
									<br>


									<div class="alert alert-success" style="width:45%">
                                 <span class="glyphicon glyphicon-time"></span>Entradas
                            </div>
												<div class="alert alert-danger" style="width:45%">
                                 <span class="glyphicon glyphicon-time"></span>Salidas
                            </div>
								</article>
							</div>
						</div>';

						}
							
						?>
			<?php
				if(isset($dni))
				{
					//form_open_multipart( base_url()."index.php/buscador/prueba/".$id, array('id' => 'my_id') );

					//form_open_multipart( base_url()."index.php/buscador/prueba/").$id;
					echo '
					<form method="post" id="my_id" name="my_id" action="'.base_url("index.php/buscador/guardar_foto/").$id.'">';
					echo '
					<div class="row">

		                <div class="col-xs-3">
		                </div>
		                <div class="col-xs-6">
		                    <button type="button" id="botonIniciar" class="btn btn-primary btn-circle btn-lg"><i class="fa fa-play"></i>
		                    </button>
		                     <button type="button" id="nosirve" class="btn btn-warning btn-circle btn-lg" style="display: none"><i class="fa fa-stop" ></i>
		                    </button>
		                   <button type="button" id="botonFoto" name="otonFoto" class="btn btn-warning btn-circle btn-lg" style="display: none"><i class="fa fa-camera"></i>
		                    </button>
		                    <button type="button" class="btn btn-success" name="submit_piola" id="submit_piola" value="Enviar" title="Enviar foto" onclick="" style="display: none"><i class="fa fa-send"></i>&nbsp; Enviar</button>
		                </div>
		             </div>
					<div class="row">
					<div style="width:40%">
						<div class="contenedor">
					        <div class="titulo">Cámara</div>
					            <video id="camara" autoplay controls style="width:100%"></video>
					    </div>
					 </div>
					 <div style="width:40%">
						<div class="contenedor">
					        <div class="titulo">Foto</div>
					            <canvas id="foto"  style="width:100%" ></canvas>
					        </div>   
					 </div>   
					    <input type="hidden" id="ultima" name="ultima" value=""> 
					    <input type="hidden" id="id_persona" name="id_persona"> 
						</div>
					  </div>
					</div>
					</form>';
			}
?>

</section>
</div>
	<script src="<?php echo base_url("assets/js/jquery.min.js"); ?>"></script>
	<script src="<?php echo base_url("assets/js/jquery.poptrox.min.js"); ?>"></script>
	<script src="<?php echo base_url("assets/js/skel.min.js"); ?>"></script>
	<script src="<?php echo base_url("assets/js/util.js"); ?>"></script>
	<script src="<?php echo base_url("assets/js/main.js"); ?>"></script>
	<script src="<?php echo base_url("bootstrap/js/bootstrap.min.js"); ?>"></script>
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

	<script type="text/javascript">
			 function myFunction () { 
			   var data_piola=foto.toDataURL("image/png");
			   $("#ultima").val(data_piola);
	}
	</script>

	<script type="text/javascript">
		//mi funcion bien piola para guardar
	    function guardarImagenFichero (img) { 
	    if (typeof img == 'object') 
	            img = img.src; 
	    window.newW = open (img); 
	    newW.document.execCommand("SaveAs"); 
	  
	     var oCamara, 
	        oFoto,
	        oContexto,
	        w, h;
	         
	    oCamara = jQuery('#camara');
	    oFoto = jQuery('#foto_dos');
	    w = oCamara.width();
	    h = oCamara.height();
	    oFoto.attr({'width': w, 'height': h});
	    oContexto = oFoto[0].getContext('2d');
	    oContexto.drawImage(oCamara[0], 0, 0, w, h);
	    }
    </script>

	<script type="text/javascript">
		$('#submit_piola').click(function(){
		      myFunction ();
		     document.getElementById("my_id").submit();
		});
	</script>

	<script type="text/javascript">
		function iniciar(){
			$( "#id_persona" ).value('<?php
			if(isset($id)) echo $id;else echo""; ?>');

		  var submitBtn = document.getElementById('botonIniciar');
		    if(submitBtn){
		      submitBtn.click();
		    }

		    $( "#botonFoto" ).click(function() {
		      $( "#botonFoto" ).click();
		    });
			
		    
		    $("input[id=botonFoto]").click();
		    $(".botonFoto").click();
		     $("#botonFoto").click();
		}
	</script>

	<script type="text/javascript">
		window.URL = window.URL || window.webkitURL;
		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || function(){alert('Su navegador no soporta navigator.getUserMedia().');};
		 
		jQuery(document).ready(function(){
		    window.datosVideo = {
		        'StreamVideo': null,
		        'url' : null
		    };


		   
		    jQuery('#botonIniciar').on('click', function(e){
		    	$( "#botonFoto" ).toggle();
		        navigator.getUserMedia({'audio':false, 'video':true}, function(streamVideo){
		            datosVideo.StreamVideo = streamVideo;
		            datosVideo.url = window.URL.createObjectURL(streamVideo);
		            jQuery('#camara').attr('src', datosVideo.url);
		            $( "#botonIniciar" ).toggle();
		        }, function(){
		            alert('No fue posible obtener acceso a la cámara.');
		        });
		 
		    });
		 
		    jQuery('#botonDetener').on('click', function(e){
		        if(datosVideo.StreamVideo){
		            datosVideo.StreamVideo.stop();
		            window.URL.revokeObjectURL(datosVideo.url);
		        };
		    });
		    jQuery('#botonFoto').on('click', function(e){
		    	$('#submit_piola').show();
		    var oCamara, 
		        oFoto,
		        oContexto,
		        w, h;
		         
		    oCamara = jQuery('#camara');
		    oFoto = jQuery('#foto');
		    w = oCamara.width();
		    h = oCamara.height();
		    oFoto.attr({'width': w, 'height': h});
		    oContexto = oFoto[0].getContext('2d');
		    oContexto.drawImage(oCamara[0], 0, 0, w, h);
		    });
		});
	</script>

   <script>
	    function selectIngredient(select)
	    {
	      var option = select.options[select.selectedIndex];
	      var ul = select.parentNode.getElementsByTagName('ul')[0];
	         
	      var choices = ul.getElementsByTagName('input');
	      for (var i = 0; i < choices.length; i++)
	        if (choices[i].value == option.value)
	          return;
	         
	      var li = document.createElement('li');
	      var input = document.createElement('input');
	      var text = document.createTextNode(option.firstChild.data);
	         
	      input.type = 'hidden';
	      input.name = 'ingredientes[]';
	      input.value = option.value;

	      li.appendChild(input);
	      li.appendChild(text);
	      li.setAttribute('onclick', 'this.parentNode.removeChild(this);');     
	        
	      ul.appendChild(li);
	    }
    </script>

	<script>
		var modal = document.getElementById('myModal');
		var img = document.getElementById('myImg');
		var modalImg = document.getElementById("img01");
		var captionText = document.getElementById("caption");
		img.onclick = function(){
		    modal.style.display = "block";
		    modalImg.src = this.src;
		    captionText.innerHTML = this.alt;
		}
		var span = document.getElementsByClassName("close_form")[0];
		span.onclick = function() { 
		    modal.style.display = "none";
		}
	</script>
	</body>
</html>
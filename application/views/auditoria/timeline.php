
<style type="text/css">



body {
  font-family: 'Roboto';
  font-size: 17px;
  font-weight: 400;
  background-color: #eee;
}

h1 {
  font-size: 200%;
  text-transform: uppercase;
  letter-spacing: 3px;
  font-weight: 400;
}

header p {
  font-family: 'Allura';
  color: rgba(255, 255, 255, 0.2);
  margin-bottom: 0;
  font-size: 60px;
  margin-top: -30px;
}

.timeline {
  position: relative;
}
.timeline::before {
  content: '';
  background: #C5CAE9;
  width: 5px;
  height: 95%;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}

.timeline-item {
  width: 100%;
  margin-bottom: 70px;
}
.timeline-item:nth-child(even) .timeline-content {
  float: right;
  padding: 40px 30px 10px 30px;
}
.timeline-item:nth-child(even) .timeline-content .date {
  right: auto;
  left: 0;
}
.timeline-item:nth-child(even) .timeline-content::after {
  content: '';
  position: absolute;
  border-style: solid;
  width: 0;
  height: 0;
  top: 30px;
  left: -15px;
  border-width: 10px 15px 10px 0;
  border-color: transparent #f5f5f5 transparent transparent;
}
.timeline-item::after {
  content: '';
  display: block;
  clear: both;
}

.timeline-content {
  position: relative;
  width: 45%;
  padding: 10px 30px;
  border-radius: 4px;
  background: #f5f5f5;
  box-shadow: 0 20px 25px -15px rgba(0, 0, 0, 0.3);
}
.timeline-content::after {
  content: '';
  position: absolute;
  border-style: solid;
  width: 0;
  height: 0;
  top: 30px;
  right: -15px;
  border-width: 10px 0 10px 15px;
  border-color: transparent transparent transparent #f5f5f5;
}

.timeline-img {
  width: 30px;
  height: 30px;
  background: #3F51B5;
  border-radius: 50%;
  position: absolute;
  left: 50%;
  margin-top: 25px;
  margin-left: -15px;
}

/*a {
  background: #3F51B5;
  color: #FFFFFF;
  padding: 8px 20px;
  text-transform: uppercase;
  font-size: 14px;
  margin-bottom: 20px;
  margin-top: 10px;
  display: inline-block;
  border-radius: 2px;
  box-shadow: 0 1px 3px -1px rgba(0, 0, 0, 0.6);
}
a:hover, a:active, a:focus {
  background: #32408f;
  color: #FFFFFF;
  text-decoration: none;
}
*/
.timeline-card {
  padding: 0 !important;
}
.timeline-card p {
  padding: 0 20px;
}
.timeline-card a {
  margin-left: 20px;
}

.timeline-item .timeline-img-header {
  background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.4)), url("https://picsum.photos/1000/800/?random") center center no-repeat;
  background-size: cover;
}

.timeline-img-header {
  height: 200px;
  position: relative;
  margin-bottom: 20px;
}
.timeline-img-header h2 {
  color: #FFFFFF;
  position: absolute;
  bottom: 5px;
  left: 20px;
}

blockquote {
  margin-top: 30px;
  color: #757575;
  border-left-color: #3F51B5;
  padding: 0 20px;
}

.date {
  background: #FF4081;
  display: inline-block;
  color: #FFFFFF;
  padding: 10px;
  position: absolute;
  top: 0;
  right: 0;
}

@media screen and (max-width: 768px) {
  .timeline::before {
    left: 50px;
  }
  .timeline .timeline-img {
    left: 50px;
  }
  .timeline .timeline-content {
    max-width: 100%;
    width: auto;
    margin-left: 70px;
  }
  .timeline .timeline-item:nth-child(even) .timeline-content {
    float: none;
  }
  .timeline .timeline-item:nth-child(odd) .timeline-content::after {
    content: '';
    position: absolute;
    border-style: solid;
    width: 0;
    height: 0;
    top: 30px;
    left: -15px;
    border-width: 10px 15px 10px 0;
    border-color: transparent #f5f5f5 transparent transparent;
  }
}


</style>
<div ng-app="infinitScrollApp">


	<header>
	  <div class="container text-center">
	    <h1>Logs del sistema</h1>
	  </div>
	</header>

	<!--MODALE DE GRAFICOS-->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
		<div class="modal-dialog" style="margin-right: 50px, margin-left: 50px; width:85%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Más Datos </h4>
				</div>
				<div class="modal-body">
					<form id="formulario_filtro_sector" method="POST" action="<?php echo base_url('movimientos'); ?>">
						<div class="card-body card-padding text-center">
							<br><br>
							
							<div class="row">
								<div class="col-md-4">
									<label for="inputTipoPersona">Registro Id</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
										<div class="fg-line select">
											<input type="number" name="registro_id_modal" id="registro_id_modal" class='form-control input-sm'> <!--log_deuda_multa_Id-->
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label for="inputTipoPersona">Conexion Id</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
										<div class="fg-line select">
											<input type="number" name="registro_id_modal" id="registro_id_modal" class='form-control input-sm'><!--log_deuda_multa_Conexion_Id-->
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label for="inputTipoPersona">Valor Anterior</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-undo"></i></span>
										<div class="fg-line select">
											<input type="number" name="registro_id_modal" id="registro_id_modal" class='form-control input-sm'><!-- log_deuda_multa_Valor_Anterior -->
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label for="inputTipoPersona">Valor actual</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-redo"></i></span>
										<div class="fg-line select">
											<input type="number" name="registro_id_modal" id="registro_id_modal" class='form-control input-sm'><!-- log_deuda_multa_Valor_Actual -->
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label for="inputTipoPersona">Campo</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-space-bar"></i></span>
										<div class="fg-line select">
											<input type="number" name="registro_id_modal" id="registro_id_modal" class='form-control input-sm'><!-- log_deuda_multa_Valor_Campo-->
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label for="inputTipoPersona">Quien</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-account-box"></i></span>
										<div class="fg-line select">
											<input type="text" name="registro_id_modal" id="registro_quien_modal" class='form-control input-sm'><!-- log_deuda_multa_Quien-->
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<label for="inputTipoPersona">Cuando</label>
										<div class="input-group form-group">
											<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
											<div class="fg-line select">
												<input type="date" name="registro_cuando_modal" id="registro_id_modal" class='form-control input-sm'><!-- log_deuda_multa_Timestamp :  -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
			</div>
		</div>
	</div>


	<section class="timeline" style="width:100%; padding-left: 0px"  ng-controller="infinitScrollController">
	  <div class="container" style="width:100%">


	  	<?php 
	  	foreach ($logs as $key) {
	  		$direfencia =floatval($key->log_deuda_multa_Valor_Actual) - floatval($key->log_deuda_multa_Valor_Anterior);
	  		echo '
	  		<div class="timeline-item">
		      <div class="timeline-img"></div>

		      <div class="timeline-content js--fadeInLeft">
		        <h2>';
		        if($key->log_deuda_multa_Revisado == "No")
		        	echo ' <font color="red"> Registro:'.$key->log_deuda_multa_Id.' </font>  <button type="button" alt="aprobar registro" class="btn btn-icon command-create" id="aprobar_registro" onclick="aprobar_log('.$key->log_deuda_multa_Id.')"><span class="zmdi zmdi-check"></span></button> </h2>' ;
		        else 
		        	echo ' <font color="green"> Registro:'.$key->log_deuda_multa_Id.'</font> </h2>';
		        echo '<div class="date">'.date( "d/m/Y H:i:s", strtotime( $key->log_deuda_multa_Timestamp  )).'</div>
		        <p>La conexion utilizada es: '.$key->log_deuda_multa_Conexion_Id.'<br>
		        	el de la deuda era de: '.$key->log_deuda_multa_Valor_Anterior.' y ahora es de: '.$key->log_deuda_multa_Valor_Actual.' --> diferencia: <strong><font color="red"> '.floatval($direfencia).' </font> </strong> </p>
		        <a data-target="#myModal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal"> Mas datos <i class="zmdi zmdi-search"></i></a>

		      </div>
		    </div> 
		    ';
		  	}
		  	?>
	    

	





	    <div class="timeline-item" ng-repeat="item in registros | limitTo: limit" in-view="loadMore($last, $inview)" >
	      <div class="timeline-img"></div>

	      <div class="timeline-content js--fadeInLeft">
	        <h2>Title del angular</h2>
	        <div class="date">1 MAY 2016</div>
	        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime ipsa ratione omnis alias cupiditate saepe atque totam aperiam sed nulla voluptatem recusandae dolor, nostrum excepturi amet in dolores. Alias, ullam.</p>
	        <a class="bnt-more" href="javascript:void(0)">More</a>
	      </div>
	    </div>
	    <div class="timeline-item">
	      <div class="timeline-img"></div>

	      <div class="timeline-content js--fadeInLeft">
	        <h2>Title</h2>
	        <div class="date">1 MAY 2016</div>
	        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime ipsa ratione omnis alias cupiditate saepe atque totam aperiam sed nulla voluptatem recusandae dolor, nostrum excepturi amet in dolores. Alias, ullam.</p>
	        <a class="bnt-more" href="javascript:void(0)">More</a>
	      </div>
	    </div>
	    <div class="timeline-item">
	      <div class="timeline-img"></div>

	      <div class="timeline-content js--fadeInLeft">
	        <h2>Title</h2>
	        <div class="date">1 MAY 2016</div>
	        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime ipsa ratione omnis alias cupiditate saepe atque totam aperiam sed nulla voluptatem recusandae dolor, nostrum excepturi amet in dolores. Alias, ullam.</p>
	        <a class="bnt-more" href="javascript:void(0)">More</a>
	      </div>
	    </div>


	    <div class="timeline-item">

	      <div class="timeline-img"></div>

	      <div class="timeline-content timeline-card js--fadeInRight">
	        <div class="timeline-img-header">
	          <h2>Card Title</h2>
	        </div>
	        <div class="date">25 MAY 2016</div>
	        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime ipsa ratione omnis alias cupiditate saepe atque totam aperiam sed nulla voluptatem recusandae dolor, nostrum excepturi amet in dolores. Alias, ullam.</p>
	        <a class="bnt-more" href="javascript:void(0)">More</a>
	      </div>

	    </div>   

	    <div class="timeline-item">

	      <div class="timeline-img"></div>

	      <div class="timeline-content js--fadeInLeft">
	        <div class="date">3 JUN 2016</div>
	        <h2>Quote</h2>
	        <blockquote>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta explicabo debitis omnis dolor iste fugit totam quasi inventore!</blockquote>
	      </div>
	    </div>   

	    <div class="timeline-item">

	      <div class="timeline-img"></div>

	      <div class="timeline-content js--fadeInRight">
	        <h2>Title</h2>
	        <div class="date">22 JUN 2016</div>
	        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime ipsa ratione omnis alias cupiditate saepe atque totam aperiam sed nulla voluptatem recusandae dolor, nostrum excepturi amet in dolores. Alias, ullam.</p>
	        <a class="bnt-more" href="javascript:void(0)">More</a>
	      </div>
	    </div>   

	    <div class="timeline-item">

	      <div class="timeline-img"></div>

	      <div class="timeline-content timeline-card js--fadeInLeft">
	        <div class="timeline-img-header">
	          <h2>Card Title</h2>
	        </div>
	        <div class="date">10 JULY 2016</div>
	        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime ipsa ratione omnis alias cupiditate saepe atque totam aperiam sed nulla voluptatem recusandae dolor, nostrum excepturi amet in dolores. Alias, ullam.</p>
	        <a class="bnt-more" href="javascript:void(0)">More</a>
	      </div>
	    </div>   

	    <div class="timeline-item">

	      <div class="timeline-img"></div>

	      <div class="timeline-content timeline-card js--fadeInRight">
	        <div class="timeline-img-header">
	          <h2>Card Title</h2>
	        </div>
	        <div class="date">30 JULY 2016</div>
	        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime ipsa ratione omnis alias cupiditate saepe atque totam aperiam sed nulla voluptatem recusandae dolor, nostrum excepturi amet in dolores. Alias, ullam.</p>
	        <a class="bnt-more" href="javascript:void(0)">More</a>
	      </div>
	    </div>  

	    <div class="timeline-item">

	      <div class="timeline-img"></div>

	      <div class="timeline-content js--fadeInLeft">
	        <div class="date">5 AUG 2016</div>
	        <h2>Quote</h2>
	        <blockquote>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta explicabo debitis omnis dolor iste fugit totam quasi inventore!</blockquote>
	      </div>
	    </div>   

	    <div class="timeline-item">

	      <div class="timeline-img"></div>

	      <div class="timeline-content timeline-card js--fadeInRight">
	        <div class="timeline-img-header">
	          <h2>Card Title</h2>
	        </div>
	        <div class="date">19 AUG 2016</div>
	        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime ipsa ratione omnis alias cupiditate saepe atque totam aperiam sed nulla voluptatem recusandae dolor, nostrum excepturi amet in dolores. Alias, ullam.</p>
	        <a class="bnt-more" href="javascript:void(0)">More</a>
	      </div>
	    </div>  

	    <div class="timeline-item">

	      <div class="timeline-img"></div>

	      <div class="timeline-content js--fadeInLeft">
	        <div class="date">1 SEP 2016</div>
	        <h2>Title</h2>
	        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime ipsa ratione omnis alias cupiditate saepe atque totam aperiam sed nulla voluptatem recusandae dolor, nostrum excepturi amet in dolores. Alias, ullam.</p>
	        <a class="bnt-more" href="javascript:void(0)">More</a>
	      </div>
	    </div>   



	  </div>
	</section>

</div>

<script src="https://cdn.jsdelivr.net/scrollreveal.js/3.3.1/scrollreveal.min.js"></script>

<script type="text/javascript">
	$(function(){

  window.sr = ScrollReveal();

  if ($(window).width() < 768) {

  	if ($('.timeline-content').hasClass('js--fadeInLeft')) {
  		$('.timeline-content').removeClass('js--fadeInLeft').addClass('js--fadeInRight');
  	}

  	sr.reveal('.js--fadeInRight', {
	    origin: 'right',
	    distance: '300px',
	    easing: 'ease-in-out',
	    duration: 800,
	  });

  } else {
  	
  	sr.reveal('.js--fadeInLeft', {
	    origin: 'left',
	    distance: '300px',
		  easing: 'ease-in-out',
	    duration: 800,
	  });

	  sr.reveal('.js--fadeInRight', {
	    origin: 'right',
	    distance: '300px',
	    easing: 'ease-in-out',
	    duration: 800,
	  });

  }
  
  sr.reveal('.js--fadeInLeft', {
	    origin: 'left',
	    distance: '300px',
		  easing: 'ease-in-out',
	    duration: 800,
	  });

	  sr.reveal('.js--fadeInRight', {
	    origin: 'right',
	    distance: '300px',
	    easing: 'ease-in-out',
	    duration: 800,
	  });


});

</script>

<script type="text/javascript">
	

function aprobar_log (id)
{
	//alert("hola");
	var elemento=$(this);
	//alert(id);
	swal({   
		title: "Estas Seguro?",   
		text: "Se aprobara el resgistro N° "+id+" !",   
		type: "warning",   
		showCancelButton: true,   
		confirmButtonColor: "#DD6B55",   
		confirmButtonText: "Si, aprobar!",
		cancelButtonText: 'Cancelar',   
		closeOnConfirm: false
	}, function(){  
		$.ajax({
			data:  {"id" : id},
			url:   'auditoria/aprobar_registro',
			type:  'post',
			beforeSend: function () {
			        $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
			},
			success:  
			function (response) {
				//alert (response);
				if(response != false){
					swal("Registyro aprobado!", "Se ha aprobado el registro N° "+id+" !", "success");
					//setTimeout("redireccionarPaginaMedidor()", 1500);
				   /* function exito(){
					$("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
					}
					window.setTimeout(exito,600);
					function redireccion(){
						location.reload();
					}
					window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
						setTimeout("redireccionarPaginaMedidor()", 1200);

					   /* function error(){
							$("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
						}
						window.setTimeout(error,600);*/
					}
				}
			}
		});


		
	});
}


</script>






	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-inview/3.0.0/angular-inview.min.js">
    </script>
<script type="text/javascript">
	angular.module('infinitScrollApp', ['angular-inview'])
       .controller('infinitScrollController', infinitScrollController);
       $scope.registros = [],
       /*

       	log_deuda_multa_Id
		log_deuda_multa_Conexion_Id
		log_deuda_multa_Valor_Anterior
		log_deuda_multa_Valor_Actual
		log_deuda_multa_Valor_Campo
		log_deuda_multa_Quien
		log_deuda_multa_Timestamp : 
		*/

		function infinitScrollController($scope) {
		  $scope.resgistros = [
		  {
	       	log_deuda_multa_Id : 1,
			log_deuda_multa_Conexion_Id : 81,
			log_deuda_multa_Valor_Anterior : 159,
			log_deuda_multa_Valor_Actual : 951,
			log_deuda_multa_Valor_Actual : "tabla",
			log_deuda_multa_Quien : "TU HERMANA",	
			log_deuda_multa_Timestamp :  "2020-07-25 15:15:15"
		},
		{
	       	log_deuda_multa_Id : 2,
			log_deuda_multa_Conexion_Id : 82,
			log_deuda_multa_Valor_Anterior : 159,
			log_deuda_multa_Valor_Actual : 990,
			log_deuda_multa_Valor_Actual : "tabla",
			log_deuda_multa_Quien : "TU HERMANA2",	
			log_deuda_multa_Timestamp :  "2020-07-25 15:15:20"
		},
		{
	       	log_deuda_multa_Id : 3,
			log_deuda_multa_Conexion_Id : 83,
			log_deuda_multa_Valor_Anterior : 159,
			log_deuda_multa_Valor_Actual : 991,
			log_deuda_multa_Valor_Actual : "tabla",
			log_deuda_multa_Quien : "TU HERMANA3",	
			log_deuda_multa_Timestamp :  "2020-07-25 15:15:25"
		},
		{
	       	log_deuda_multa_Id : 4,
			log_deuda_multa_Conexion_Id : 84,
			log_deuda_multa_Valor_Anterior : 159,
			log_deuda_multa_Valor_Actual : 992,
			log_deuda_multa_Valor_Actual : "tabla",
			log_deuda_multa_Quien : "TU HERMANA4",	
			log_deuda_multa_Timestamp :  "2020-07-25 15:15:30"
		}
		];


		  //$scope.items = Array.from(Array(1000).keys());
		  
		  $scope.loadMore = function (last, inview) {
		    if (last && inview) {
		    	$scope.limit += 5;
		    }
		    nuevos_registros = [
			{
		       	log_deuda_multa_Id : 7,
				log_deuda_multa_Conexion_Id : 77,
				log_deuda_multa_Valor_Anterior : 159,
				log_deuda_multa_Valor_Actual : 5005,
				log_deuda_multa_Valor_Actual : "tabla",
				log_deuda_multa_Quien : "TU HERMANA",	
				log_deuda_multa_Timestamp :  "2020-07-25 15:15:15"
			},
			{
		       	log_deuda_multa_Id : 8,
				log_deuda_multa_Conexion_Id : 78,
				log_deuda_multa_Valor_Anterior : 159,
				log_deuda_multa_Valor_Actual : 5501,
				log_deuda_multa_Valor_Actual : "tabla",
				log_deuda_multa_Quien : "TU HERMANA2",	
				log_deuda_multa_Timestamp :  "2020-07-25 15:15:20"
			},
			{
		       	log_deuda_multa_Id : 9,
				log_deuda_multa_Conexion_Id : 79,
				log_deuda_multa_Valor_Anterior : 159,
				log_deuda_multa_Valor_Actual : 5540,
				log_deuda_multa_Valor_Actual : "tabla",
				log_deuda_multa_Quien : "TU HERMANA3",	
				log_deuda_multa_Timestamp :  "2020-07-25 15:15:25"
			},
			{
		       	log_deuda_multa_Id : 45,
				log_deuda_multa_Conexion_Id : 878,
				log_deuda_multa_Valor_Anterior : 159,
				log_deuda_multa_Valor_Actual : 7878,
				log_deuda_multa_Valor_Actual : "tabl5a",
				log_deuda_multa_Quien : "TU HERMANA3",	
				log_deuda_multa_Timestamp :  "2020-07-25 15:15:25"
			},
			{
		       	log_deuda_multa_Id : 15,
				log_deuda_multa_Conexion_Id : 79,
				log_deuda_multa_Valor_Anterior : 159,
				log_deuda_multa_Valor_Actual : 5577,
				log_deuda_multa_Valor_Actual : "tabla",
				log_deuda_multa_Quien : "TU HERMANA4",	
				log_deuda_multa_Timestamp :  "2020-07-25 15:15:30"
			}
			];
		    $scope.resgistros.push(nuevos_registros);

		    console.log($scope.resgistros);
		  }
		}

</script>
</body>
</html>
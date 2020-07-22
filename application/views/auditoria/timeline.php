
<div ng-app="infinitScrollApp">


	<header>
	  <div class="container text-center">
	    <h1>Logs del sistema</h1> <a data-target="#buscarmodal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#buscarmodal"> <i class="zmdi zmdi-search"></i>Buscar Registros</a>
	  </div>
	</header>

	<!--MODAL DE GRAFICOS-->
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
											<input type="number" name="conexion_id_modal" id="conexion_id_modal" class='form-control input-sm'><!--log_deuda_multa_Conexion_Id-->
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label for="inputTipoPersona">Valor Anterior</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-undo"></i></span>
										<div class="fg-line select">
											<input type="number" name="valor_anterior_modal" id="valor_anterior_modal" class='form-control input-sm'><!-- log_deuda_multa_Valor_Anterior -->
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
											<input type="number" name="valor_actual_modal" id="valor_actual_modal" class='form-control input-sm'><!-- log_deuda_multa_Valor_Actual -->
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label for="inputTipoPersona">Campo</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-space-bar"></i></span>
										<div class="fg-line select">
											<input type="number" name="campo_modal" id="campo_modal" class='form-control input-sm'><!-- log_deuda_multa_Valor_Campo-->
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label for="inputTipoPersona">Quien</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-account-box"></i></span>
										<div class="fg-line select">
											<input type="text" name="quien_id_modal" id="quien_id_modal" class='form-control input-sm'><!-- log_deuda_multa_Quien-->
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<label for="inputTipoPersona">Cuando</label>
										<div class="input-group form-group">
											<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
											<div class="fg-line select">
												<input type="date" name="cuando_modal" id="cuando_modal" class='form-control input-sm'><!-- log_deuda_multa_Timestamp :  -->
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

	<!--MODAL DE Buscador-->
	<div class="modal fade" id="buscarmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
		<div class="modal-dialog" style="margin-right: 50px, margin-left: 50px; width:85%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Buscador de Registros </h4>
				</div>
				<div class="modal-body">
					<form id="formulario_filtro_sector" method="POST" action="<?php echo base_url('auditoria'); ?>">
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
											<input type="number" name="conexion_id_modal" id="conexion_id_modal" class='form-control input-sm'><!--log_deuda_multa_Conexion_Id-->
										</div>
									</div>
								</div>
								
							</div>
							<div class="row">
								
								
								<div class="col-md-4">
									<label for="inputTipoPersona">Quien</label>
									<div class="input-group form-group">
										<span class="input-group-addon"><i class="zmdi zmdi-account-box"></i></span>
										<div class="fg-line select">
											<input type="text" name="quien_id_modal" id="quien_id_modal" class='form-control input-sm'><!-- log_deuda_multa_Quien-->
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<label for="inputTipoPersona">Cuando</label>
										<div class="input-group form-group">
											<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
											<div class="fg-line select">
												<input type="date" name="cuando_modal" id="cuando_modal" class='form-control input-sm'><!-- log_deuda_multa_Timestamp :  -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<label for="select_tipo_movimiento">Seleccione el tipo:</label>
								<select name="select_tipo_movimiento"  id="select_tipo_movimiento" class="chosen" data-placeholder="Elige el tipo" >
									<option value="-1" selected disabled >Sin Filtro</option>
									<option value="2" >Egreso</option>
									<option value="1" >Ingreso</option>
								</select>
							</div>
							<br><br>
							<div class="row">
								<div class="col-md-6">
									<label>Inicio :</label>
									<input type="date" name="inicio_reporte_pagos" id="inicio_reporte_pagos" min="2018-02-02">
								</div>
								<div class="col-md-6">
									<label>Fin:</label>
									<input type="date" name="fin_reporte_pagos" id="fin_reporte_pagos" max="<?php echo date("Y-m-d"); ?>">
								</div>
							</div>
							<br>
							<br>

						</div>
					</form>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-8">
							<button class="btn bgm-lime btn-float waves-effect waves-button waves-float waves-circle " id="buscar_registros_modal" name="buscar_registros_modal" type="submit"><i class="zmdi zmdi-search"></i></button>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
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
	    

	





	    <div class="timeline-item" ng-repeat="item in resgistros | limitTo: limit" in-view="loadMore($last, $inview)" >
	      <div class="timeline-img"></div>

	      <div class="timeline-content js--fadeInLeft">
	        <h2>{{item.log_deuda_multa_Quien}}</h2>
	        <div class="date">1 MAY 2016</div>
	        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime ipsa ratione omnis alias cupiditate saepe atque totam aperiam sed nulla voluptatem recusandae dolor, nostrum excepturi amet in dolores. Alias, ullam.</p>
	        <a class="bnt-more" href="javascript:void(0)">More</a>
	      </div>
	    </div>
	<!--     <div class="timeline-item">
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
	    </div>    -->



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
       //$scope.registros = [],
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
		$scope.resgistros = [],
		console.log('Emepzamos con los registros vacios: '+$scope.resgistros);
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

		console.log('Registros cargados: \n ');
		console.log($scope.resgistros);



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
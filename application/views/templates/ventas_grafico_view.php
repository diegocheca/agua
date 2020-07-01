<div class="row">
	<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
		<div class="card z-depth-2">
			<div class="card-header " style="background-color:#EA80FC">
				<h2>Flujo de Ingreso</h2>
				<div class ="row">
					<button  type="button" class="btn btn-float waves-effect" style="background-color:#F06292" id="nuevo_ingreso" name="nuevo_ingreso"><i class="zmdi zmdi-plus"></i></button>
					<br>
				</div>
			</div>
			<div class="card-body card-padding text-center" style="padding:0px">
				<div id="chart_div" style="width: 850px; height: 500px;"></div>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
						<button type="button" class="btn btn-float waves-effect" style="background-color:#F06292" id="bucardor_de_balance" name="bucardor_de_balance"><i class="zmdi zmdi-download"></i></button>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
						<button type="button" class="btn btn-float waves-effect waves-effect waves-circle waves-float" style="background-color:indigo" id="nuevo_reporte" name="nuevo_reporte"><i class="zmdi zmdi-calendar-alt"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div id="pie-charts" class="dash-widget-item">
			<div >
				<div class="dash-widget-header">
						<div class="dash-widget-title">Caja diaria</div>
				</div>
				<div class="clearfix"></div>

				<div class="text-center p-20 m-t-25">
					 
						<?php echo anchor('inventario/',' <i class="zmdi zmdi-money-box"></i>  Cierre de Caja',array('class'=>'btn btn-block btn-lg bgm-pink')); ?>
						 <h1>Total: E<strong><?php echo $total_plata; ?></strong></h1>
				</div>
			</div>
			<div class="p-t-20 p-b-10 text-center">
				<div class="easy-pie sec-pie-2 m-b-2" data-percent="<?php echo $ingreso_porcentaje; ?>">
					<div class="percent"><?php echo $ingreso_porcentaje; ?></div>
					<div class="pie-title">Ingreso</div>
				</div>
				 <div class="easy-pie sec-pie-1 m-b-7" data-percent="<?php echo $egreso_porcentaje?>">
					<div class="percent"><?php echo $egreso_porcentaje?></div>
					<div class="pie-title">Egreso</div>
				</div>
			</div>
		</div>
	</div>
	
</div>


<div class="row" >
	<div class="col-sm-12 col-md-3">
		
	</div>
</div>
<br>




 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawVisualization);

			function drawVisualization() {
				// Some raw data (not necessarily accurate)
				var data = google.visualization.arrayToDataTable([<?php echo $grafico; ?>
				 
			]);

		var options = {
			title : 'Flujo de Dinero',
			vAxis: {title: 'Pesos'},
			hAxis: {title: 'Días'},
			seriesType: 'bars',
			series: {5: {type: 'line'}}
		};

		var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
		</script>


<script type="text/javascript">
				$("#nuevo_ingreso").on("click",function(){
				$("#nuevo_ingreso_modal").modal('toggle');
		});

</script>
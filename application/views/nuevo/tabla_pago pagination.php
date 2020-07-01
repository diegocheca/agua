<!DOCTYPE html>
<html>
<head>
	<title>Super tabla</title>
 <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
 <style type="text/css">
	.boton_ocultar_columna {
		background-color: red;
	}

	.boton_mostrar_columna {
		background-color: green;
	}

 </style>
</head>
<body>
<div class="container" ng-app="sortApp" ng-controller="mainController">
  
  <div class="alert alert-info">
	<p>Sort Type: {{ sortType }}</p>
	<p>Sort Reverse: {{ sortReverse }}</p>
	<p>Search Query: {{ searchFish }}</p>
  </div>
  
	<div class="col-lg-4">
		<div class="well">
			<div style="width:33%">
				<label>Elegir sector: </label>
				<select ng-options="option for option in listOfOptions" 
					ng-model="selectedItem"
					ng-init="selectedItem =  'Sin Elegir'"
					>
				</select>
				<!-- ng-change="selectedItemChanged()" -->

			</div>
			<div style="width:33%">
				<label>Elegir Conexion: </label>
				<!-- <input type="number" name="conexion_a_buscar" id="conexion_a_buscar"> -->
				<select ng-options="option for option in listConexion" 
					ng-model="selectedConexion"
					ng-init="selectedConexion =  'Sin Elegir'"
					>
				</select>
			</div>
			<div style="width:33%">
				<label>Elegir Mes: </label>
				<select ng-options="option for option in listMes" 
					ng-model="selectedMes"
					ng-init="selectedMes =  'Sin Elegir'"
					>
				</select>
			</div>
			<label>Elegir Año: </label>
			<select ng-options="option for option in listAno" 
				ng-model="selectedAno"
				ng-init="selectedAno =  'Sin Elegir'"
				>
			</select>

			<label>Pagado: </label>
			<select ng-options="option for option in listPago" 
				ng-model="selectedPago"
				ng-init="selectedPago =  'Sin Elegir'"
				>
			</select>
			<button id="buscar_filas" name="buscar_filas" ng-model="boton_buscador" ng-click="filtrar_filas()" class="btn btn-primary">Buscar</button>
		</div>
	</div>

  	

  <form>
	<div class="form-group">
	  <div class="input-group">
		<div class="input-group-addon"><i class="fa fa-search"></i></div>
		<input type="text" class="form-control" placeholder="Buscador" ng-model="searchFish">
	  </div>     
	  <!-- <div class="input-group-addon"><i class="fa fa-search"></i></div>
		<input type="text" class="form-control" placeholder="Buscador" ng-model="buscador_nombre">
	  </div>      
	</div> -->
  </form>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading">
				Ocultar Botones
			</div>
			<div class="panel-body">
				<button ng-click="ocultar_columna(1)" ng-style="mostrar_columna_1 == true ? { color:'green' } : { color:'#FE0013' }"}" > {{lable[0]}}</button>
				<button ng-click="ocultar_columna(2)" ng-style="mostrar_columna_2 == true ? { color:'green' } : { color:'#FE0013' }"}"> {{lable[1]}}</button>
				<button ng-click="ocultar_columna(3)" ng-style="mostrar_columna_3 == true ? { color:'green' } : { color:'#FE0013' }"}" > {{lable[2]}}</button>
				<button ng-click="ocultar_columna(4)" ng-style="mostrar_columna_4 == true ? { color:'green' } : { color:'#FE0013' }"}"> {{lable[3]}}</button>
				<button ng-click="ocultar_columna(5)" ng-style="mostrar_columna_5 == true ? { color:'green' } : { color:'#FE0013' }"}"> {{lable[4]}} </button>
				<button ng-click="ocultar_columna(6)" ng-style="mostrar_columna_6 == true ? { color:'green' } : { color:'#FE0013' }"}"> {{lable[5]}} </button>
				<button ng-click="ocultar_columna(7)" ng-style="mostrar_columna_7 == true ? { color:'green' } : { color:'#FE0013' }"}"> {{lable[6]}} </button>
				<button ng-click="ocultar_columna(8)" ng-style="mostrar_columna_8 == true ? { color:'green' } : { color:'#FE0013' }"}"> {{lable[7]}} </button>
				<button ng-click="ocultar_columna(9)" ng-style="mostrar_columna_9 == true ? { color:'green' } : { color:'#FE0013' }"}"> {{lable[8]}} </button>  
				<button ng-click="ocultar_columna(10)" ng-style="mostrar_columna_10 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[9]}}  </button>
				<button ng-click="ocultar_columna(11)" ng-style="mostrar_columna_11 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[10]}}  </button>
				<button ng-click="ocultar_columna(12)" ng-style="mostrar_columna_12 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[11]}}  </button>
				<button ng-click="ocultar_columna(13)" ng-style="mostrar_columna_13 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[12]}}  </button>
				<button ng-click="ocultar_columna(14)" ng-style="mostrar_columna_14 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[13]}}  </button>
				<button ng-click="ocultar_columna(15)" ng-style="mostrar_columna_15 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[14]}}  </button>
				<button ng-click="ocultar_columna(16)" ng-style="mostrar_columna_16 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[15]}}  </button>
				<button ng-click="ocultar_columna(17)" ng-style="mostrar_columna_17 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[16]}}  </button>
				<button ng-click="ocultar_columna(18)" ng-style="mostrar_columna_18 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[17]}}  </button>
				<button ng-click="ocultar_columna(19)" ng-style="mostrar_columna_19 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[18]}}  </button>
				<button ng-click="ocultar_columna(20)" ng-style="mostrar_columna_20 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[19]}}  </button>
				<button ng-click="ocultar_columna(21)" ng-style="mostrar_columna_21 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[20]}}  </button>
				<button ng-click="ocultar_columna(22)" ng-style="mostrar_columna_22 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[21]}}  </button>
				<button ng-click="ocultar_columna(23)" ng-style="mostrar_columna_23 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[22]}}  </button>
				<button ng-click="ocultar_columna(24)" ng-style="mostrar_columna_24 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[23]}}  </button>
				<button ng-click="ocultar_columna(25)" ng-style="mostrar_columna_25 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[24]}}  </button>
				<button ng-click="ocultar_columna(26)" ng-style="mostrar_columna_26 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[25]}}  </button>
				<button ng-click="ocultar_columna(27)" ng-style="mostrar_columna_27 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[26]}}  </button>
				<button ng-click="ocultar_columna(28)" ng-style="mostrar_columna_28 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[27]}}  </button>
				<button ng-click="ocultar_columna(29)" ng-style="mostrar_columna_29 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[28]}}  </button>
				<button ng-click="ocultar_columna(30)" ng-style="mostrar_columna_30 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[29]}}  </button>
				<button ng-click="ocultar_columna(31)" ng-style="mostrar_columna_31 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[30]}}  </button>
				<button ng-click="ocultar_columna(32)" ng-style="mostrar_columna_32 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[31]}}  </button>
				<button ng-click="ocultar_columna(33)" ng-style="mostrar_columna_33 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[32]}}  </button>
				<button ng-click="ocultar_columna(34)" ng-style="mostrar_columna_34 == true ? { color:'green' } : { color:'#FE0013' }"}">{{lable[33]}}  </button>
				<button ng-click="ocultar_columna(35)" ng-style="mostrar_columna_35 == true ? { color:'green' } : { color:'#FE0013' }"}>{{lable[34]}}  </button>
				<button ng-click="ocultar_columna(36)" ng-style="mostrar_columna_36 == true ? { color:'green' } : { color:'#FE0013' }"}>{{lable[35]}}  </button>
			</div>
			<div class="panel-footer">
				Panel Footer
			</div>
		</div>
	</div>
</div>


  
  <table class="table table-bordered table-striped" style="width:2300px">
	
	<thead>
	  <tr>

		<td ng-show="mostrar_columna_botones">
			botones
		</td>
		<td ng-show="mostrar_columna_0">
			<a href="#" ng-click="sortType = 'id'; sortReverse = !sortReverse">
				ID 
				<span ng-show="sortType == 'id' && !sortReverse" class="fa fa-caret-down"></span>
				<span ng-show="sortType == 'id' && sortReverse" class="fa fa-caret-up"></span>
			</a>
		</td>
		<td ng-show="mostrar_columna_1" style=" width:180px">
			<a href="#" ng-click="sortType = 'name'; sortReverse = !sortReverse">
				Nombre
				<span ng-show="sortType == 'name' && !sortReverse" class="fa fa-caret-down"></span>
				<span ng-show="sortType == 'name' && sortReverse" class="fa fa-caret-up"></span>
			</a>
		</td>
		<td ng-show="mostrar_columna_35">
			<a href="#" ng-click="sortType = 'codigo_barra'; sortReverse = !sortReverse">
				Cod barra
				<span ng-show="sortType == 'codigo_barra' && !sortReverse" class="fa fa-caret-down"></span>
				<span ng-show="sortType == 'codigo_barra' && sortReverse" class="fa fa-caret-up"></span>
			</a>
		</td>
		<td ng-show="mostrar_columna_2">
			<a href="#" ng-click="sortType = 'id_conexion'; sortReverse = !sortReverse">
				Num Con 
				<span ng-show="sortType == 'id_conexion' && !sortReverse" class="fa fa-caret-down"></span>
				<span ng-show="sortType == 'id_conexion' && sortReverse" class="fa fa-caret-up"></span>
			</a>
		</td>
		<td ng-show="mostrar_columna_3">
			<a href="#" ng-click="sortType = 'id_cliente'; sortReverse = !sortReverse">
			Num Usu
			<span ng-show="sortType == 'id_cliente' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'id_cliente' && sortReverse" class="fa fa-caret-up"></span>
			</a>
		</td>
		<td ng-show="mostrar_columna_4">
		  <a href="#" ng-click="sortType = 'mes'; sortReverse = !sortReverse">
			Mes 
			<span ng-show="sortType == 'mes' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'mes' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_5">
		  <a href="#" ng-click="sortType = 'anio'; sortReverse = !sortReverse">
			Año 
			<span ng-show="sortType == 'anio' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'anio' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_6">
		  <a href="#" ng-click="sortType = 'actual'; sortReverse = !sortReverse">
			Actual
			<span ng-show="sortType == 'actual' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'actual' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_7">
		  <a href="#" ng-click="sortType = 'anterior'; sortReverse = !sortReverse">
			Anterior
			<span ng-show="sortType == 'anterior' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'anterior' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_8">
		  <a href="#" ng-click="sortType = 'exc_mts'; sortReverse = !sortReverse">
			Exc m3
			<span ng-show="sortType == 'exc_mts' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'exc_mts' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_9">
		  <a href="#" ng-click="sortType = 'exc_precio'; sortReverse = !sortReverse">
			Exc $
			<span ng-show="sortType == 'exc_precio' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'exc_precio' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_10">
		  <a href="#" ng-click="sortType = 'categoria'; sortReverse = !sortReverse">
			Categoria
			<span ng-show="sortType == 'categoria' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'categoria' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_11" style="width:120">
		  <a href="#" ng-click="sortType = 'vto_1_fecha'; sortReverse = !sortReverse">
			Vto 1
			<span ng-show="sortType == 'vto_1_fecha' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'vto_1_fecha' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_12">
		  <a href="#" ng-click="sortType = 'vto_1_Precio'; sortReverse = !sortReverse">
			Vto 1 $
			<span ng-show="sortType == 'vto_1_Precio' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'vto_1_Precio' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_13" style="width:120">
		  <a href="#" ng-click="sortType = 'vto_2_fecha'; sortReverse = !sortReverse">
			Vto 2
			<span ng-show="sortType == 'vto_2_fecha' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'vto_2_fecha' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_14">
		  <a href="#" ng-click="sortType = 'vto_2_precio'; sortReverse = !sortReverse">
			Vto 2 $
			<span ng-show="sortType == 'vto_2_precio' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'vto_2_precio' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_15">
		  <a href="#" ng-click="sortType = 'deuda_anterior'; sortReverse = !sortReverse">
			Deuda Ant
			<span ng-show="sortType == 'deuda_anterior' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'deuda_anterior' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_16">
		  <a href="#" ng-click="sortType = 'tarifa_basica'; sortReverse = !sortReverse">
			Tar Basi
			<span ng-show="sortType == 'tarifa_basica' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'tarifa_basica' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_17">
		  <a href="#" ng-click="sortType = 'couta_social'; sortReverse = !sortReverse">
			Cuota S
			<span ng-show="sortType == 'couta_social' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'couta_social' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_18">
		  <a href="#" ng-click="sortType = 'medidor_canti_cuota'; sortReverse = !sortReverse">
			M Cant Cout
			<span ng-show="sortType == 'medidor_canti_cuota' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'medidor_canti_cuota' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_19">
		  <a href="#" ng-click="sortType = 'medidor_couta_actual'; sortReverse = !sortReverse">
			M Cout Actua
			<span ng-show="sortType == 'medidor_couta_actual' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'medidor_couta_actual' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_20">
		  <a href="#" ng-click="sortType = 'medidor_couta_precio'; sortReverse = !sortReverse">
			M Cuen Prec
			<span ng-show="sortType == 'medidor_couta_precio' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'medidor_couta_precio' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_21">
		  <a href="#" ng-click="sortType = 'plan_pago_canti_cuota'; sortReverse = !sortReverse">
			PP Can Cuo
			<span ng-show="sortType == 'plan_pago_canti_cuota' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'plan_pago_canti_cuota' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_22">
		  <a href="#" ng-click="sortType = 'plan_pago_cuota_actual'; sortReverse = !sortReverse">
			PP cout Act
			<span ng-show="sortType == 'plan_pago_cuota_actual' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'plan_pago_cuota_actual' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_23">
		  <a href="#" ng-click="sortType = 'plan_pago_cuota_precio'; sortReverse = !sortReverse">
			PP Cou $
			<span ng-show="sortType == 'plan_pago_cuota_precio' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'plan_pago_cuota_precio' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_24">
		  <a href="#" ng-click="sortType = 'riego'; sortReverse = !sortReverse">
			Riego
			<span ng-show="sortType == 'riego' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'riego' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_36" style="width:280px">
		  <a href="#" ng-click="sortType = 'domicilio'; sortReverse = !sortReverse">
			Multa
			<span ng-show="sortType == 'domicilio' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'domicilio' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>

		<td ng-show="mostrar_columna_25">
		  <a href="#" ng-click="sortType = 'sub_total'; sortReverse = !sortReverse">
			sub_total
			<span ng-show="sortType == 'sub_total' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'sub_total' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_26">
		  <a href="#" ng-click="sortType = 'pago_acuenta'; sortReverse = !sortReverse">
			Acuenta
			<span ng-show="sortType == 'pago_acuenta' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'pago_acuenta' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_27">
		  <a href="#" ng-click="sortType = 'bonificacion'; sortReverse = !sortReverse">
			bonific
			<span ng-show="sortType == 'bonificacion' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'bonificacion' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_28">
		  <a href="#" ng-click="sortType = 'total'; sortReverse = !sortReverse">
			total
			<span ng-show="sortType == 'total' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'total' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_29">
		  <a href="#" ng-click="sortType = 'pago_id'; sortReverse = !sortReverse">
			Pago Monto
			<span ng-show="sortType == 'pago_id' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'pago_id' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_30">
		  <a href="#" ng-click="sortType = 'pago_monto'; sortReverse = !sortReverse">
			Pago Fecha
			<span ng-show="sortType == 'pago_monto' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'pago_monto' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_31">
		  <a href="#" ng-click="sortType = 'sector'; sortReverse = !sortReverse">
			Sector
			<span ng-show="sortType == 'sector' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'sector' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_32">
		  <a href="#" ng-click="sortType = 'orden_sector'; sortReverse = !sortReverse">
			Orden dentro del sector
			<span ng-show="sortType == 'orden_sector' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'orden_sector' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_33">
		  <a href="#" ng-click="sortType = 'mts_categoria'; sortReverse = !sortReverse">
			Mts Cat
			<span ng-show="sortType == 'mts_categoria' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'mts_categoria' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		<td ng-show="mostrar_columna_34" style="width:280px">
		  <a href="#" ng-click="sortType = 'domicilio'; sortReverse = !sortReverse">
			Domicilio
			<span ng-show="sortType == 'domicilio' && !sortReverse" class="fa fa-caret-down"></span>
			<span ng-show="sortType == 'domicilio' && sortReverse" class="fa fa-caret-up"></span>
		  </a>
		</td>
		
		
	  </tr>
	</thead>
	<tbody>
		<tr ng-repeat="factura in facturas | orderBy:sortType:sortReverse | filter:searchFish | startFrom:currentPage*pageSize | limitTo:pageSize"  ng-include="getTemplate(factura)">
		</tr>
	</tbody>
	
  </table>

  <button ng-disabled="currentPage == 0" ng-click="currentPage=currentPage-1">
        Previous
    </button>
    {{currentPage+1}}/{{numberOfPages()}}
    <button ng-disabled="currentPage >= facturas.length/pageSize - 1" ng-click="currentPage=currentPage+1">
        Next
    </button>


		<script type="text/ng-template" id="display">
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_botones"><button id="boton_mas" ng-show="isNaN(factura.pago_monto)" ng-click="pagar_boleta($index, factura)" class="btn btn-outline btn-success">$</button><button id="boton_mas" ng-click="crear_pdf(factura)" class="btn btn-outline btn-danger">PDF</button> <button ng-click="editContact(factura)" class="btn btn-outline btn-warning" ng-show="isNaN(factura.pago_monto)">Edit</button><button ng-click="recalcular_valores($index, factura)" class="btn btn-outline btn-primary" ng-show="isNaN(factura.pago_monto)">Calcu</button> <button id="boton_recibo" ng-click="crear_recibo(factura)" class="btn btn-outline btn-success" ng-show="isNaN(factura.pago_monto) === false">Recibo</button> </td>
			<td  ng-if="$even" ng-show="mostrar_columna_botones"><button id="boton_mas" ng-click="pagar_boleta($index, factura)" ng-show="isNaN(factura.pago_monto)" class="btn btn-outline btn-success">$</button><button id="boton_mas" ng-click="crear_pdf(factura)" class="btn btn-outline btn-danger">PDF</button> <button ng-click="editContact(factura)" class="btn btn-outline btn-warning" ng-show="isNaN(factura.pago_monto)">Edit</button> <button ng-click="recalcular_valores($index, factura)" class="btn btn-outline btn-primary" ng-show="isNaN(factura.pago_monto)">Calcu</button><button id="boton_recibo" ng-click="crear_recibo(factura)" class="btn btn-outline btn-success" ng-show="isNaN(factura.pago_monto) === false">Recibo</button></td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_0">{{ factura.id }}</td>
			<td  ng-if="$even" ng-show="mostrar_columna_0">{{ factura.id }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_1" readonly >{{ factura.name }}</td>
			<td  ng-if="$even" ng-show="mostrar_columna_1" readonly >{{ factura.name }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1"  ng-show="mostrar_columna_35">{{ factura.codigo_barra }}</td>
			<td  ng-if="$even" ng-show="mostrar_columna_35">{{ factura.codigo_barra }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1"  ng-show="mostrar_columna_2">{{ factura.id_conexion }}</td>
			<td  ng-if="$even" ng-show="mostrar_columna_2">{{ factura.id_conexion }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_3">{{ factura.id_cliente }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_3">{{ factura.id_cliente }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_4">{{ factura.mes }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_4">{{ factura.mes }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_5">{{ factura.anio }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_5">{{ factura.anio }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_6">{{ factura.actual }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_6">{{ factura.actual }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_7">{{ factura.anterior }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_7">{{ factura.anterior }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_8">{{ factura.exc_mts}}</td>
			<td ng-if="$even" ng-show="mostrar_columna_8">{{ factura.exc_mts }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_9">{{ factura.exc_precio }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_9">{{ factura.exc_precio }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_10">{{ factura.categoria }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_10">{{ factura.categoria }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1; width:100px" ng-show="mostrar_columna_11">{{ factura.vto_1_fecha }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_11" style="width:100px">{{ factura.vto_1_fecha }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_12">{{ factura.vto_1_Precio }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_12">{{ factura.vto_1_Precio }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1; width:100px"  ng-show="mostrar_columna_13">{{ factura.vto_2_fecha }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_13" style="width:100px">{{ factura.vto_2_fecha }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_14">{{ factura.vto_2_precio }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_14">{{ factura.vto_2_precio }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_15">{{ factura.deuda_anterior }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_15">{{ factura.deuda_anterior }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_16">{{ factura.tarifa_basica }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_16">{{ factura.tarifa_basica }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_17">{{ factura.couta_social }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_17">{{ factura.couta_social }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_18">{{ factura.medidor_canti_cuota }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_18">{{ factura.medidor_canti_cuota }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_19">{{ factura.medidor_couta_actual }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_19">{{ factura.medidor_couta_actual }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_20">{{ factura.medidor_couta_precio }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_20">{{ factura.medidor_couta_precio }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_21">{{ factura.plan_pago_canti_cuota }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_21">{{ factura.plan_pago_canti_cuota }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_22">{{ factura.plan_pago_cuota_actual }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_22">{{ factura.plan_pago_cuota_actual }}</td>

			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_23">{{ factura.plan_pago_cuota_precio }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_23">{{ factura.plan_pago_cuota_precio }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_24">{{ factura.riego }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_24">{{ factura.riego }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1"  ng-show="mostrar_columna_36">{{ factura.multa}}</td>
			<td  ng-if="$even" ng-show="mostrar_columna_36">{{ factura.multa }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_25">{{ factura.sub_total }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_25">{{ factura.sub_total }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_26">{{ factura.pago_acuenta }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_26">{{ factura.pago_acuenta }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_27">{{ factura.bonificacion }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_27">{{ factura.bonificacion }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_28">{{ factura.total }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_28">{{ factura.total }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_29">{{ factura.pago_monto }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_29">{{ factura.pago_monto }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_30">{{ factura.pago_id }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_30">{{ factura.pago_id }}</td>

			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_31">{{ factura.sector }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_31">{{ factura.sector }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_32">{{ factura.orden_sector }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_32">{{ factura.orden_sector }}</td>

			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_33">{{ factura.mts_categoria }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_33">{{ factura.mts_categoria }}</td>
			<td ng-if="$odd" style="background-color:#f1f1f1" ng-show="mostrar_columna_34">{{ factura.domicilio }}</td>
			<td ng-if="$even" ng-show="mostrar_columna_34">{{ factura.domicilio }}</td>





			

			
		</script>
		<script type="text/ng-template" id="edit">
			<td style="width: 50px ;    border-width: 0px; background-color:#d3d3d9"ng-show="mostrar_columna_botones"><button ng-click="guardar_cambios_en_factura($index, selected.id)"  style="background-color:#9ff781" >Gua</button><button style="background-color:#fe2e64" ng-click="reset()">Volv</button></td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_0"><input   style="width:55px" type="text" readonly ng-model="selected.id" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_1"><input   style="width:55px" type="text" ng-model="selected.name" readonly /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_35"><input   style="width:55px" type="text" readonly ng-model="selected.codigo_barra" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_2"><input   style="width:55px" type="text" readonly ng-model="selected.id_conexion" /> </td>
			<td style="background-color:#d3d3d9" ng-show="mostrar_columna_3"><input  style="width:55px" type="text" readonly ng-model="selected.id_cliente" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_4"><input  style="width:55px" type="text" ng-model="selected.mes" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_5"><input  style="width:55px" type="text" ng-model="selected.anio" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_6"><input   style="width:55px" type="text" ng-model="selected.actual" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_7"><input   style="width:55px" type="text" ng-model="selected.anterior" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_8"><input   style="width:55px" type="text" ng-model="selected.exc_mts" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_9"><input   style="width:55px" type="text" ng-model="selected.exc_precio" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_10"><input  style="width:55px" type="text" ng-model="selected.categoria" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_11"><input  style="width:100px" type="text" ng-model="selected.vto_1_fecha" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_12"><input  style="width:55px" type="text" ng-model="selected.vto_1_Precio" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_13"><input  style="width:100px" type="text" ng-model="selected.vto_2_fecha" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_14"><input  style="width:55px" type="text" ng-model="selected.vto_2_precio" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_15"><input  style="width:55px" type="text" ng-model="selected.deuda_anterior" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_16"><input  style="width:55px" type="text" ng-model="selected.tarifa_basica" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_17"><input  style="width:55px" type="text" ng-model="selected.couta_social" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_18"><input  style="width:55px" type="text" ng-model="selected.medidor_canti_cuota" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_19"><input  style="width:55px" type="text" ng-model="selected.medidor_couta_actual" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_20"><input  style="width:55px" type="text" ng-model="selected.medidor_couta_precio" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_21"><input  style="width:55px" type="text" ng-model="selected.plan_pago_canti_cuota" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_22"><input  style="width:55px" type="text" ng-model="selected.plan_pago_cuota_actual" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_23"><input  style="width:55px" type="text" ng-model="selected.plan_pago_cuota_precio" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_24"><input  style="width:55px" type="text" ng-model="selected.riego" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_36"><input   style="width:55px" type="text" ng-model="selected.multa" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_25"><input  style="width:55px" type="text" ng-model="selected.sub_total" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_26"><input  style="width:55px" type="text" ng-model="selected.pago_acuenta" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_27"><input  style="width:55px" type="text" ng-model="selected.bonificacion" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_28"><input  style="width:55px" type="text" ng-model="selected.total" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_29"><input  style="width:55px" type="text" ng-model="selected.pago_monto" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_30"><input  style="width:55px" type="text" ng-model="selected.pago_id" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_31"><input  style="width:55px" type="text" ng-model="selected.sector" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_32"><input  style="width:55px" type="text" ng-model="selected.orden_sector" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_33"><input  style="width:55px" type="text" ng-model="selected.mts_categoria" /> </td>
			<td style=" background-color:#d3d3d9" ng-show="mostrar_columna_34"><input  style="width:55px" type="text" ng-model="selected.domicilio" /> </td>
			
			<td>
			</td>
		</script>
	<div modal="showModal" close="cancel()">
		<div class="modal-header">
			<h4>Pagando Boleta {{Titutlo_Factura_Id}} de {{Titulo_Conexion_Id}} de {{Titulo_Nombre}} </h4>
		</div>
		<div class="modal-body">
				<div class="col-md-6 col-xs-6">
					<label for="inputMoneda">Fecha del Pago</label>
					<div class="input-group form-group">
						<div class="fg-line">
							<input id="fechaPago" type="date" name="fechaPago" ng-model="fecha_elegida_angular" ng-change="calcular_aumento_monto_por_fecha()" >
						</div>
					</div>
					<input type="text" name="fechaalgo" id="fechaalgo" ng-model="fechacorregida" ng-bind="ddddate | date:'dd/MM/yyyy'">
				</div>
				<div class="col-md-3 col-xs-3">
					<div class="alert alert-success" ng-show="pago_atrasado == 0 ? true : false">
						paga sin recargo.
					</div>
					<div class="alert alert-warning" ng-show="pago_atrasado == 1 ? true : false">
						Paga Con Recargo.
					</div>
				</div>
				<hr style="background-color: #fff; border-top: 2px dashed #8c8b8b">
				<div class="col-md-4 col-xs-12">
					<label for="inputTipoPago">Tipo de Pago</label>
					<select id="inputTipoPago" type="text" name="inputTipoPago"  ng-model="tipo_de_pago" ng-change="cambiar_parcial_o_total()" >
						<option value="1" selected>Total</option>
						<option value="2">Parcial</option>
					</select>
				</div>
				<div class="col-md-4 col-xs-12" ng-show="tipo_de_pago == 2 ? true : false">
					<label for="monto_parcial_a_pagar">Monto Parcial a Pagar</label>
					<input type="number"  ng-model="pago_parcial_a" name="monto_parcial_a_pagar" id="monto_parcial_a_pagar" ng-change="pago_parcial()">
				</div>
				<div class="col-md-4 col-xs-12">
					<label for="selectFormaPago">Forma de Pago</label>
					<select id="selectFormaPago" type="text" name="selectFormaPago"  ng-model="forma_de_pago" ng-change="cambiar_forma_de_pago()" >
						<option value="1" selected>Efectivo</option>
						<option value="2">Cheque</option>
						<option value="3">Mixto</option>
					</select>
				</div>
				<div class="col-md-4 col-xs-12" ng-show="forma_de_pago == 3 ? true : false">
					<label for="monto_efectivo_a_pagar">Monto Contado</label>
					<input type="text" ng-model="monto_efectivo_a_pagar" name="monto_efectivo_a_pagar" id="monto_efectivo_a_pagar" ng-change="cambio_parte_contado()">
				</div>

				<div class="col-md-4 col-xs-12" ng-show="forma_de_pago == 3 ? true : false">
					<label for="monto_cheque_a_pagar">Monto Cheque</label>
					<input type="text" ng-model="monto_cheque_a_pagar" name="monto_cheque_a_pagar" id="monto_cheque_a_pagar" ng-change="cambio_parte_cheque()">
				</div>
				<hr style="background-color: #fff; border-top: 2px dashed #8c8b8b">
				<p>Deuda a pagar: <input name="deuda_modal" id="deuda_modal" ng-model="selected_dos.deuda_anterior"  ng-change="recalcular_boleta()" type="text"  /> </p> <!-- min="0" max="9999999" step="0.01"-->
				<p>Tarifa Básica: <input name="tarifa_bascia_modal" id="tarifa_bascia_modal" ng-model="selected_dos.tarifa_basica" ng-change="recalcular_boleta()" type="text"  /></p> <!-- min="0" max="9999999" step="0.01"-->
				<p>Excedente: <input name="excedente_modal" id="excedente_modal" ng-model="selected_dos.exc_precio" ng-change="recalcular_boleta()" type="text"  /></p> <!-- min="0" max="9999999" step="0.01"-->
				<p>Cuota Social: <input name="cuota_social_modal" id="cuota_social_modal" ng-model="selected_dos.couta_social" ng-change="recalcular_boleta()" type="text"  /></p> <!-- min="0" max="9999999" step="0.01"-->
				<p>Plan Medidor: <input name="medidor_cant_cuota_modal" id="medidor_cant_cuota_modal" ng-model="selected_dos.medidor_canti_cuota" style="width:75px"  type="number" min="0" max="12"/> <input name="medidor_cuota_actual" id="medidor_cuota_actual" ng-model="selected_dos.medidor_couta_actual" style="width:75px" type="number" min="0" max="12"/> <input name="medidor_precio_cuota" id="medidor_precio_cuota" ng-model="selected_dos.medidor_couta_precio" style="width:75px" ng-change="recalcular_boleta()" type="text"  /></p> <!-- min="0" max="9999999" step="0.01" -->
				<p>Plan Pago: <input name="pp_cant_cuota_modal" id="pp_cant_cuota_modal" ng-model="selected_dos.plan_pago_canti_cuota" style="width:75px"  type="number" min="0" max="12"/> <input name="pp_cuota_actual" id="pp_cuota_actual" ng-model="selected_dos.plan_pago_cuota_actual" style="width:75px" type="number" min="0" max="12"/> <input name="pp_precio_cuota" id="pp_precio_cuota" ng-model="selected_dos.plan_pago_cuota_precio" style="width:75px"  ng-change="recalcular_boleta()" type="text"/></p> <!--  min="0" max="9999999" step="0.01"  -->
				<p>Riego: <input name="riego_modal" id="riego_modal" ng-model="selected_dos.riego" ng-change="recalcular_boleta()"  /></p> <!--type="number" min="0" max="9999999" step="0.01" -->
				<p>Multa: <input name="multa_modal" id="multa_modal" ng-model="selected_dos.multa" ng-change="recalcular_boleta()"  /></p> <!--type="number" min="0" max="9999999" step="0.01" -->
				<p>Subtotal a pagar: <input name="deuda_modal" id="deuda_modal" ng-model="selected_dos.sub_total" readonly="" /></p>
				<hr>
				<p>Pago A Cuenta: <input name="acuenta_modal" id="acuenta_modal" ng-model="selected_dos.pago_acuenta" ng-change="recalcular_boleta()" type="text"/></p> <!--  type="number" min="0" max="9999999" step="0.01"  -->
				<p>Bonificacion: <input name="bonificacion_modal" id="bonificacion_modal" ng-model="selected_dos.bonificacion" ng-change="recalcular_boleta()" type="text" readonly="" /></p> <!-- type="number" min="0" max="9999999" step="0.01"  -->
				<p>Total a pagar: <input name="total_modal" id="total_modal" ng-model="selected_dos.total" readonly/></p>
				<hr>
				<p>Endeuda: <input name="endeuda" id="endeuda" ng-model="endeuda" readonly/></p>
				<hr>
				<p>Descuento a aplicar: <input name="descuento" id="descuento" ng-model="descuento"  ng-change="aplicar_desceunto_en_boleta()"/></p> <!-- Este campo hace el descuento maximo y le resta a la deuda o al total-->
				<hr>
				<p>Total sin modif: <input name="total_sin_modif_modal" id="total_sin_modif_modal" ng-model="monto_total_sin_modificar_a" readonly/></p>
		</div>
		<div class="modal-footer">
			<button class="btn btn-success" ng-click="ok()">Confirmar Pago</button>
			<button class="btn btn-warning" ng-click="cancel()">Cancelar</button>
		</div>
	</div>





</div>
	<script src="<?php echo base_url();?>js/jquery-2.1.1.min.js"></script>
	<script src="<?php echo base_url();?>js/angular1-2-18.min.js"></script>
	<script src="<?php echo base_url();?>js/angular1-2-18.animate.min.js"></script>
	<!--<script src="<?php echo base_url();?>js/dirPagination.js"></script>-->

	<script src="<?php echo base_url();?>js/ui-bootstrap-2.5.0.min.js"></script>

	 <script src="http://angular-ui.github.com/bootstrap/ui-bootstrap-tpls-0.2.0.js"></script>
	<!--	 <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.13/angular-sanitize.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.13/angular-animate.min.js"></script> -->
	<script type="text/javascript">
	var sortApp = angular.module('sortApp', ['ui.bootstrap']);



		sortApp.controller('mainController',  ['$scope', '$http', '$dialog',function ($scope, $http, $dialog) {

			$scope.currentPage = 0;
		    $scope.pageSize = 10;
		    $scope.numberOfPages=function(){
		        return Math.ceil($scope.facturas.length/$scope.pageSize);                
	   		 }


			$scope.selected = {};
			$scope.Titutlo_Factura_Id= 0;
			$scope.Titulo_Conexion_Id= 0;
			$scope.Titulo_Nombre = 0;
			$scope.mostrar_columna_botones = true;
			$scope.mostrar_columna_0 = true;
			$scope.mostrar_columna_1 = true;
			$scope.mostrar_columna_2 = true;
			$scope.mostrar_columna_3 = true;
			$scope.mostrar_columna_4= true;
			$scope.mostrar_columna_5= true;
			$scope.mostrar_columna_6= true;
			$scope.mostrar_columna_7= true;
			$scope.mostrar_columna_8= true;
			$scope.mostrar_columna_9= true;
			$scope.mostrar_columna_10= true;
			$scope.mostrar_columna_11= true;
			$scope.mostrar_columna_12= true;
			$scope.mostrar_columna_13= true;
			$scope.mostrar_columna_14= true;
			$scope.mostrar_columna_15= true;
			$scope.mostrar_columna_16= true;
			$scope.mostrar_columna_17= true;
			$scope.mostrar_columna_18= true;
			$scope.mostrar_columna_19= true;
			$scope.mostrar_columna_20= true;
			$scope.mostrar_columna_21= true;
			$scope.mostrar_columna_22= true;
			$scope.mostrar_columna_23= true;
			$scope.mostrar_columna_24= true;
			$scope.mostrar_columna_25= true;
			$scope.mostrar_columna_26= true;
			$scope.mostrar_columna_27= true;
			$scope.mostrar_columna_28= true;
			$scope.mostrar_columna_29= true;
			$scope.mostrar_columna_30= true;
			$scope.mostrar_columna_31= true;
			$scope.mostrar_columna_32= true;
			$scope.mostrar_columna_33= true;
			$scope.mostrar_columna_34= true;
			$scope.mostrar_columna_35= true;
			$scope.mostrar_columna_36= true;

			$scope.listOfOptions = ["Sin Elegir",'A', 'B', 'C', "Santa Barbara", "Aberanstain", "VIP", "ASENTAMIENTO OLMOS", "V Elisa", "medina", "Jardinesss", "David","Zaldivar","Salas","0"];
			$scope.listConexion = ["Sin Elegir",'1', '2', '3', "4", "5", "6", "7", "8", "9", "2681"];
			$scope.listMes = ["Sin Elegir",'1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
			$scope.listAno = ["Sin Elegir",'2017', '2018'];
			$scope.listPago = ["Sin Elegir",'Si', 'No'];

			var tmpList = [];

			selected: {};
			selected_dos: {};
			$scope.lable= [];
			for (var i = 0; i < 40; i++) {
				$scope.lable[i]= "Ocultar "+(i+1) ;
			};	
			$scope.lable[0]= "Nombre";
			$scope.lable[1]= "Conex";
			$scope.lable[2]= "Usua";
			$scope.lable[3]= "Mes";
			$scope.lable[4]= "Año" ;
			$scope.lable[5]= "Actual";
			$scope.lable[6]= "Anterior";
			$scope.lable[7]= "Exc m3";
			$scope.lable[8]= "Exc $";
			$scope.lable[9]= "Categoria";
			$scope.lable[10]= "Vto1 F";
			$scope.lable[11]= "Vto1 $";
			$scope.lable[12]= "Vto2 F";
			$scope.lable[13]= "Vto2 $";
			$scope.lable[14]= "Deuda";
			$scope.lable[15]= "Tar B";
			$scope.lable[16]= "C Social";
			$scope.lable[17]= "M cant C";
			$scope.lable[18]= "M C Actual";
			$scope.lable[19]= "PP Cant";
			$scope.lable[20]= "PPC Act";
			$scope.lable[21]= "PPC $";
			$scope.lable[22]= "PPC $";
			$scope.lable[23]= "Riego";
			$scope.lable[24]= "SubTotal";
			$scope.lable[25]= "Acuenta";
			$scope.lable[26]= "Bonific";
			$scope.lable[27]= "Total";
			$scope.lable[28]= "NumPago";
			$scope.lable[29]= "Pago Monto";
			$scope.lable[30]= "Sector";
			$scope.lable[31]= "Orden";
			$scope.lable[32]= "MtsCant";
			$scope.lable[33]= "Domicilio";
			$scope.lable[34]= "Barra";
			$scope.lable[35]= "Cosa";
			$scope.lable[36]= "Multa";

			$scope.cosas = '';
			//$http.get('<?php //echo site_url('nuevo/traer_super_datos'); ?>').success(function($data){ $scope.cosas=$data; });
			// angular.forEach($scope.cosas, function(value, key){
			// 	$scope.facturas.push(
			// 		{ id: parseInt(value.id), name: value.Cli_RazonSocial, id_conexion: parseInt(value.Conexion_Id), id_cliente: parseInt(value.Cli_Id) , mes: parseInt(value.Medicion_Mes), anio : parseInt(value.Medicion_Anio) , actual: parseInt(value.Medicion_Actual), anterior: parseInt(value.Medicion_Anterior), exc_mts: parseInt(value.Medicion_Mts), exc_precio: parseFloat(value.Medicion_Importe), categoria : value.Conexion_Categoria, vto_1_fecha : value.Factura_Vencimiento1, vto_1_Precio : parseFloat(12), vto_2_fecha : value.Factura_Vencimiento2, vto_2_precio : parseFloat(12), deuda_anterior : parseFloat(value.Conexion_Deuda), tarifa_basica: parseFloat(100), couta_social: parseFloat(25), medidor_canti_cuota: parseInt(12),  medidor_couta_actual: parseInt(12),    medidor_couta_precio: parseInt(12),  plan_pago_canti_cuota: parseInt(12), plan_pago_cuota_actual: parseInt(12),  plan_pago_cuota_precio: parseInt(12) ,  riego: parseFloat(value.Conexion_Latitud) , sub_total: parseFloat(value.Factura_SubTotal),  pago_acuenta: parseFloat(value.Conexion_Acuenta) , bonificacion: parseFloat(0), total: parseFloat(value.Factura_Total), pago_monto: parseFloat(value.Pago_Monto), sector: value.Conexion_Sector, orden_sector: value.Conexion_UnionVecinal, mts_categoria: parseInt(value.Medicion_Mts), domicilio: value.Conexion_DomicilioSuministro }
			// 		);
			// });
			console.log($scope.cosas);
			$scope.sortType     = 'name'; // set the default sort type
			$scope.sortReverse  = false;  // set the default sort order
			$scope.searchFish   = '';     // set the default search/filter term
			$scope.facturas = [
				{ id: 1, name: 'Cali Roll', codigo_barra: 1231231231, id_conexion: 1, id_cliente: 2, mes: 99, anio : 1 , actual : 1, anterior : 1, exc_mts : 1, exc_precio : 65, categoria : 1, vto_1_fecha : 15-03-2018, vto_1_Precio : 120.00, vto_2_fecha : 22-03-2018, vto_2_precio : 121.80, deuda_anterior : 121.80, tarifa_basica: 100, couta_social: 25, medidor_canti_cuota: 0,  medidor_couta_actual: 0,   medidor_couta_precio: 0,  plan_pago_canti_cuota: 12,  plan_pago_cuota_actual: 2,  plan_pago_cuota_precio: 100.50 ,  riego: 63.00, sub_total: 112 , pago_acuenta: 0 , bonificacion: 100 , total: 121.80, pago_monto: 151, sector: 'A',orden_sector: 2,mts_categoria: 10, domicilio:'Libertador y mendoza'},
				{ id: 22, name: 'Philly', codigo_barra: 1231231231, id_conexion: 1, id_cliente: 4 , mes: 98, anio : 12 , actual: 12, anterior: 12, exc_mts: 12, exc_precio: 12, categoria : 12, vto_1_fecha : 12, vto_1_Precio : 12, vto_2_fecha : 12, vto_2_precio : 12, deuda_anterior : 0, tarifa_basica: 32, couta_social: 22, medidor_canti_cuota: 12,  medidor_couta_actual: 12,    medidor_couta_precio: 12,  plan_pago_canti_cuota: 12, plan_pago_cuota_actual: 12,  plan_pago_cuota_precio: 12 ,  riego: 12 , sub_total: 1992,  pago_acuenta: 13 , bonificacion: 13, total: 189, pago_monto: 151, sector: 'B',orden_sector: 2,mts_categoria: 10, domicilio:'Libertador y mendoza'}
			];
			$scope.datosss = function(){
				angular.forEach($scope.cosas, function(value, key){
					$scope.facturas.push(
						{ id: parseInt(value.id), 
							name: value.Cli_RazonSocial,
							id_conexion: parseInt(value.Conexion_Id),
							id_cliente: parseInt(value.Cli_Id) , 
							mes: parseInt(value.Medicion_Mes), 
							anio : parseInt(value.Medicion_Anio) , 
							actual: parseInt(value.Medicion_Actual), 
							anterior: parseInt(value.Medicion_Anterior), 
							exc_mts: parseInt(value.Medicion_Mts), 
							exc_precio: parseFloat(value.Medicion_Importe), 
							categoria : value.Conexion_Categoria, 
							vto_1_fecha : value.Factura_Vencimiento1, 
							vto_1_Precio : parseFloat(12), 
							vto_2_fecha : value.Factura_Vencimiento2, 
							vto_2_precio : parseFloat(12), 
							deuda_anterior : parseFloat(value.Factura_Deuda), 
							tarifa_basica: parseFloat(100), 
							couta_social: parseFloat(25), 
							medidor_canti_cuota: parseInt(12),  
							medidor_couta_actual: parseInt(12),    
							medidor_couta_precio: parseInt(12),  
							plan_pago_canti_cuota: parseInt(12), 
							plan_pago_cuota_actual: parseInt(12), 
							plan_pago_cuota_precio: parseInt(12) ,  
							riego: parseFloat(value.Conexion_Latitud) , 
							sub_total: parseFloat(value.Factura_SubTotal),  
							pago_acuenta: parseFloat(value.Conexion_Acuenta) , 
							bonificacion: parseFloat(0), 
							total: parseFloat(value.Factura_Total), 
							pago_monto: parseFloat(value.Pago_Monto),
							sector: value.Conexion_Sector, 
							orden_sector: parseInt(value.Conexion_UnionVecinal),
							mts_categoria: parseInt(value.Medicion_Mts),
							domicilio: value.Conexion_DomicilioSuministro }
						);
					});
				console.log($scope.cosas[0]["Cli_Id"]);
			}
			$scope.isNaN = function(value){
			return isNaN(value);
			}
			$scope.ocultar_columna = function(numero){
				if(numero == 1)
				{
					if( $scope.mostrar_columna_1)
					{
						$scope.lable[0] = "Nombre";
						$scope.mostrar_columna_1  = false;
					}
					else 
					{
						$scope.mostrar_columna_1 = true;
						$scope.lable[0]= "Nombre";
					}
				}
				if(numero == 2)
				{
					if( $scope.mostrar_columna_2)
					{
						$scope.lable[1] = "Conex";
						$scope.mostrar_columna_2  = false;
					}
					else 
					{
						$scope.mostrar_columna_2 = true;
						$scope.lable[1]= "Conex";
					}
				}
				if(numero == 3)
				{
					if( $scope.mostrar_columna_3)
					{
						$scope.lable[2] = "Usua";
						$scope.mostrar_columna_3  = false;
					}
					else 
					{
						$scope.mostrar_columna_3 = true;
						$scope.lable[2]= "Usua";
					}
				}
				if(numero == 4)
				{
					if( $scope.mostrar_columna_4)
					{
						$scope.lable[3] = "Mes";
						$scope.mostrar_columna_4  = false;
					}
					else 
					{
						$scope.mostrar_columna_4 = true;
						$scope.lable[3]= "Mes";
					}
				}
				if(numero == 5)
				{
					if( $scope.mostrar_columna_5)
					{
						$scope.lable[4] = "Año";
						$scope.mostrar_columna_5  = false;
					}
					else 
					{
						$scope.mostrar_columna_5 = true;
						$scope.lable[4]= "Año" ;
					}
				}
				if(numero == 6)
				{
					if( $scope.mostrar_columna_6)
					{
						$scope.lable[5] = "Actual";
						$scope.mostrar_columna_6  = false;
					}
					else 
					{
						$scope.mostrar_columna_6 = true;
						$scope.lable[5]= "Actual";
					}
				}
				if(numero == 7)
				{
					if( $scope.mostrar_columna_7)
					{
						$scope.lable[6] = "Anterior ";
						$scope.mostrar_columna_7  = false;
					}
					else 
					{
						$scope.mostrar_columna_7 = true;
						$scope.lable[6]= "Anterior";
					}
				}
				if(numero == 8)
				{
					if( $scope.mostrar_columna_8)
					{
						$scope.lable[7] = "Exc m3";
						$scope.mostrar_columna_8  = false;
					}
					else 
					{
						$scope.mostrar_columna_8 = true;
						$scope.lable[7]= "Exc m3";
					}
				}
				if(numero == 9)
				{
					if( $scope.mostrar_columna_9)
					{
						$scope.lable[8] = "Exc $";
						$scope.mostrar_columna_9 = false;
					}
					else 
					{
						$scope.mostrar_columna_9 = true;
						$scope.lable[8]= "Exc $";
					}
				}
				if(numero == 10)
				{
					if( $scope.mostrar_columna_10)
					{
						$scope.lable[9] = "Categoria";
						$scope.mostrar_columna_10  = false;
					}
					else
					{
					 $scope.mostrar_columna_10 = true;
					 $scope.lable[9]= "Categoria";
					}
				}
				if(numero == 11)
				{
					if( $scope.mostrar_columna_11)
					{
						$scope.lable[10] = "Vto1 F";
						$scope.mostrar_columna_11 = false;
					}
					else
					{
					 $scope.mostrar_columna_11 = true;
					 $scope.lable[10]= "Vto1 F";
					}
				}
				if(numero == 12)
				{
					if( $scope.mostrar_columna_12)
					{
						$scope.lable[11] = "Vto1 $";
						$scope.mostrar_columna_12  = false;
					}
					else
					{
					 $scope.mostrar_columna_12 = true;
					 $scope.lable[11]= "Vto1 $";
					}
				}
				if(numero == 13)
				{
					if( $scope.mostrar_columna_13)
					{
						$scope.lable[12] = "Vto2 F";
						$scope.mostrar_columna_13 = false;
					}
					else
					{
					 $scope.mostrar_columna_13 = true;
					 $scope.lable[12]= "Vto2 F";
					}
				}
				if(numero == 14)
				{
					if( $scope.mostrar_columna_14)
					{
						$scope.lable[13] = "Vto2 $";
						$scope.mostrar_columna_14  = false;
					}
					else
					{
					 $scope.mostrar_columna_14 = true;
					 $scope.lable[13]= "Vto2 $";
					}
				}
				if(numero == 15)
				{
					if( $scope.mostrar_columna_15)
					{
						$scope.lable[14] = "Deuda";
						$scope.mostrar_columna_15  = false;
					}
					else
					{
					 $scope.mostrar_columna_15 = true;
					 $scope.lable[14]= "Deuda";
					}
				}
				if(numero == 16)
				{
					if( $scope.mostrar_columna_16)
					{
						$scope.lable[15] = "Tar B";
						$scope.mostrar_columna_16  = false;
					}
					else
					{
					 $scope.mostrar_columna_16 = true;
					 $scope.lable[15]= "Tar B";
					}
				}
				if(numero == 17)
				{
					if( $scope.mostrar_columna_17)
					{
						$scope.lable[16] = "C Social";
						$scope.mostrar_columna_17  = false;
					}
					else
					{
					 $scope.mostrar_columna_17 = true;
					 $scope.lable[16]= "C Social";
					}
				}
				if(numero == 18)
				{
					if( $scope.mostrar_columna_18)
					{
						$scope.lable[17] = "M cant C";
						$scope.mostrar_columna_18  = false;
					}
					else
					{
					 $scope.mostrar_columna_18 = true;
					 $scope.lable[17]= "M cant C";
					}
				}
				if(numero == 19)
				{
					if( $scope.mostrar_columna_19)
					{
						$scope.lable[18] = "M C Actual";
						$scope.mostrar_columna_19  = false;
					}
					else
					{
					 $scope.mostrar_columna_19 = true;
					 $scope.lable[18]= "M C Actual";
					}
				}
				if(numero == 20)
				{
					if( $scope.mostrar_columna_20)
					{
						$scope.lable[19] = "M C $";
						$scope.mostrar_columna_20  = false;
					}
					else
					{
					 $scope.mostrar_columna_20 = true;
					 $scope.lable[19]= "M C $";
					}
				}
				if(numero == 21)
				{
					if( $scope.mostrar_columna_21)
					{
						$scope.lable[20] = "PP Cant";
						$scope.mostrar_columna_21  = false;
					}
					else
					{
					 $scope.mostrar_columna_21 = true;
					 $scope.lable[20]= "PP Cant";
					}
				}
				if(numero == 22)
				{
					if( $scope.mostrar_columna_22)
					{
						$scope.lable[21] = "PPC Act";
						$scope.mostrar_columna_22  = false;
					}
					else
					{
					 $scope.mostrar_columna_22 = true;
					 $scope.lable[21]= "PPC Act";
					}
				}
				if(numero == 23)
				{
					if( $scope.mostrar_columna_23)
					{
						$scope.lable[22] = "PPC $";
						$scope.mostrar_columna_23  = false;
					}
					else
					{
					 $scope.mostrar_columna_23 = true;
					 $scope.lable[22]= "PPC $";
					}
				}
				if(numero == 24)
				{
					if( $scope.mostrar_columna_24)
					{
						$scope.lable[23] = "Riego";
						$scope.mostrar_columna_24  = false;
					}
					else
					{
					 $scope.mostrar_columna_24 = true;
					 $scope.lable[23]= "Riego";
					}
				}
				
				if(numero == 25)
				{
					 

					if( $scope.mostrar_columna_25)
					{
						$scope.lable[24] = "SubTotal";
						$scope.mostrar_columna_25  = false;
					}
					else
					{
					 $scope.mostrar_columna_25 = true;
					 $scope.lable[24]= "SubTotal";
					}
				}
				if(numero == 26)
				{
					if( $scope.mostrar_columna_26)
					{
						$scope.lable[25] = "Acuenta";
						$scope.mostrar_columna_26  = false;
					}
					else
					{
					 $scope.mostrar_columna_26 = true;
					 $scope.lable[25]= "Acuenta";
					}
				}
				if(numero == 27)
				{
					if( $scope.mostrar_columna_27)
					{
						$scope.lable[26] = "Bonific";
						$scope.mostrar_columna_27  = false;
					}
					else
					{
					 $scope.mostrar_columna_27 = true;
					 $scope.lable[26]= "Bonific";
					}
				}
				if(numero == 28)
				{
					if( $scope.mostrar_columna_28)
					{
						$scope.lable[27] = "Total";
						$scope.mostrar_columna_28  = false;
					}
					else
					{
					 $scope.mostrar_columna_28 = true;
					 $scope.lable[27]= "Total";
					}
				}
				if(numero == 29)
				{
					if( $scope.mostrar_columna_29)
					{
						$scope.lable[28] = "NumPago";
						$scope.mostrar_columna_29  = false;
					}
					else
					{
					 $scope.mostrar_columna_29 = true;
					 $scope.lable[28]= "NumPago";
					}
				}
				if(numero == 30)
				{
					if( $scope.mostrar_columna_30)
					{
						$scope.lable[29] = "Pago Monto";
						$scope.mostrar_columna_30  = false;
					}
					else
					{
					 $scope.mostrar_columna_30 = true;
					 $scope.lable[29]= "Pago Monto";
					}
				}
				if(numero == 31)
				{
					if( $scope.mostrar_columna_31)
					{
						$scope.lable[30] = "Sector";
						$scope.mostrar_columna_31  = false;
					}
					else
					{
					 $scope.mostrar_columna_31 = true;
					 $scope.lable[30]= "Sector";
					}
				}
				if(numero == 32)
				{
					if( $scope.mostrar_columna_32)
					{
						$scope.lable[31] = "Orden";
						$scope.mostrar_columna_32  = false;
					}
					else
					{
					 $scope.mostrar_columna_32 = true;
					 $scope.lable[31]= "Orden";
					}
				}
				if(numero == 33)
				{
					if( $scope.mostrar_columna_33)
					{
						$scope.lable[32] = "MtsCant";
						$scope.mostrar_columna_33  = false;
					}
					else
					{
					 $scope.mostrar_columna_33 = true;
					 $scope.lable[32]= "MtsCant";
					}
				}
				if(numero == 34)
				{
					if( $scope.mostrar_columna_34)
					{
						$scope.lable[33] = "Domicilio";
						$scope.mostrar_columna_34  = false;
					}
					else
					{
					 $scope.mostrar_columna_34 = true;
					 $scope.lable[33]= "Domicilio";
					}
				}
				if(numero == 35)
				{
					if( $scope.mostrar_columna_35)
					{
						$scope.lable[34] = "Barra";
						$scope.mostrar_columna_35  = false;
					}
					else
					{
					 $scope.mostrar_columna_35 = true;
					 $scope.lable[34]= "Barra";
					}
				}

				if(numero == 36)
				{
					if( $scope.mostrar_columna_36)
					{
						$scope.lable[35] = "Barra";
						$scope.mostrar_columna_36  = false;
					}
					else
					{
					 $scope.mostrar_columna_36 = true;
					 $scope.lable[35]= "Barra";
					}
				}

			}
			$scope.getTemplate = function (facturas) {
				if (facturas.id === $scope.selected.id) return 'edit'; // si factura-> es igual al seleccionado entonces muestro el edit
				else return 'display'; //sino muestro las celdas solamente
			};
			$scope.editContact = function (facturas) {
				$scope.selected = angular.copy(facturas);
				// console.log("Toca");
				// console.log(facturas);
			};
			$scope.guardar_cambios_en_factura = function (idx, factura_id) {
				console.log("Saving contact");
				console.log($scope.selected);
				for (var i = 0; i < $scope.facturas.length && ($scope.facturas[i]["id"] != factura_id); i++) {
				};
				console.log("indece:"+i+"id index:"+$scope.facturas[i]["id"]+ "id  copia:"+$scope.selected["id"]);
				idx = i;
				var cambios_en_factura = false;
				//reviso los cambios
				//veo si algo de  la conexion cambio para la conexion
				if( ($scope.facturas[idx]["categoria"] != $scope.selected["categoria"]) || ($scope.facturas[idx]["deuda_anterior"] != $scope.selected["deuda_anterior"]) || ($scope.facturas[idx]["sector"] != $scope.selected["sector"]) || ($scope.facturas[idx]["orden_sector"] != $scope.selected["orden_sector"]) || ($scope.facturas[idx]["domicilio"] != $scope.selected["domicilio"]))
				{
					var datos = {
						categoria: $scope.selected["categoria"], 
						deuda_anterior: $scope.selected["deuda_anterior"],
						orden_sector:  $scope.selected["orden_sector"],
						domicilio:  $scope.selected["domicilio"]
					};
					var url_mia = 'http://localhost/codeigniter/nuevo/guardar_cambios_conexion/'+$scope.selected["id_conexion"]+'/'+$scope.selected["categoria"]+'/'+$scope.selected["deuda_anterior"]+'/'+$scope.selected["sector"]+'/'+$scope.selected["orden_sector"]+'/'+$scope.selected["domicilio"];
					$http.post(url_mia, {'email':$scope.selected["categoria"]}).
						success(function(response, status) {
							console.log(response.data+ "    - estado: "+status);
					});
				}
				else 
					console.log("No hya cambios para la conexion");
				//para la factura
				if( ($scope.facturas[idx]["tarifa_basica"] != $scope.selected["tarifa_basica"]) || ($scope.facturas[idx]["exc_precio"] != $scope.selected["exc_precio"]) ||  ($scope.facturas[idx]["couta_social"] != $scope.selected["couta_social"]) || 
					($scope.facturas[idx]["riego"] != $scope.selected["riego"]) || ($scope.facturas[idx]["medidor_couta_precio"] != $scope.selected["medidor_couta_precio"]) || ($scope.facturas[idx]["plan_pago_cuota_precio"] != $scope.selected["plan_pago_cuota_precio"]) || 
						($scope.facturas[idx]["vto_1_fecha"] != $scope.selected["vto_1_fecha"]) || ($scope.facturas[idx]["vto_1_Precio"] != $scope.selected["vto_1_Precio"]) || ($scope.facturas[idx]["vto_2_fecha"] != $scope.selected["vto_2_fecha"]) || 
						($scope.facturas[idx]["vto_2_precio"] != $scope.selected["vto_2_precio"]) || ($scope.facturas[idx]["medidor_canti_cuota"] != $scope.selected["medidor_canti_cuota"]) || ($scope.facturas[idx]["medidor_couta_actual"] != $scope.selected["medidor_couta_actual"]) || 
						($scope.facturas[idx]["plan_pago_canti_cuota"] != $scope.selected["plan_pago_canti_cuota"]) || ($scope.facturas[idx]["plan_pago_cuota_actual"] != $scope.selected["plan_pago_cuota_actual"]) || ($scope.facturas[idx]["plan_pago_cuota_precio"] != $scope.selected["plan_pago_cuota_precio"]) 
						|| ($scope.facturas[idx]["bonificacion"] != $scope.selected["bonificacion"])
						|| ($scope.facturas[idx]["multa"] != $scope.selected["multa"])
						|| ($scope.facturas[idx]["deuda_anterior"] != $scope.selected["deuda_anterior"])

						)
				{
						cambios_en_factura = true;
						$http.post('http://localhost/codeigniter/nuevo/guardar_cambios_factura/'+$scope.selected["id"],  {'tarifa_basica': $scope.selected["tarifa_basica"], 'exc_precio': $scope.selected["exc_precio"], 'couta_social': $scope.selected["couta_social"], 'riego': $scope.selected["riego"] , 'medidor_couta_precio' : $scope.selected["medidor_couta_precio"] , 'plan_pago_cuota_precio': $scope.selected["plan_pago_cuota_precio"],  'vto_1_fecha': $scope.selected["vto_1_fecha"], 'vto_1_Precio': $scope.selected["vto_1_Precio"], 'vto_2_fecha': $scope.selected["vto_2_fecha"],
										'vto_2_precio': $scope.selected["vto_2_precio"], 'medidor_canti_cuota': $scope.selected["medidor_canti_cuota"] ,  'medidor_couta_actual': $scope.selected["medidor_couta_actual"], 'plan_pago_canti_cuota': $scope.selected["plan_pago_canti_cuota"], 'plan_pago_cuota_actual': $scope.selected["plan_pago_cuota_actual"], 'bonificacion' : $scope.selected["bonificacion"] , 'deuda_anterior' : $scope.selected["deuda_anterior"], 'pago_acuenta' : $scope.selected["pago_acuenta"], 'deuda' : $scope.selected["deuda_anterior"], 'multa' : $scope.selected["multa"] }).
											success(function(response) {
												console.log( "    - hecho: "+response);
					});
				}
					//datos de la medicion
					if( ($scope.facturas[idx]["actual"] != $scope.selected["actual"]) 
						|| ($scope.facturas[idx]["anterior"] != $scope.selected["anterior"])
						||  ($scope.facturas[idx]["exc_mts"] != $scope.selected["exc_mts"]) 
						||  ($scope.facturas[idx]["mts_categoria"] != $scope.selected["mts_categoria"]) 
							) 
					{
						cambios_en_factura = true;
						$http.post('http://localhost/codeigniter/nuevo/guardar_cambios_medicion/'+$scope.selected["id"],  
							{'actual': $scope.selected["actual"], 'anterior': $scope.selected["anterior"], 
							'exc_mts': $scope.selected["exc_mts"], 'mts_categoria': $scope.selected["mts_categoria"]	}).
							success(function(response) {
								console.log( "    - hecho: "+response);
						});
					}
						//para el backup
					if(cambios_en_factura)
						$http.post('http://localhost/codeigniter/nuevo/guardar_back_cambios/'+$scope.selected["id"],  
							{
							'deuda': $scope.selected["deuda"], 
							'tarifa_basica': $scope.selected["tarifa_basica"], 
							'exc_precio': $scope.selected["exc_precio"], 
							'couta_social': $scope.selected["couta_social"], 
							'riego': $scope.selected["riego"] , 
							'medidor_couta_precio' : $scope.selected["medidor_couta_precio"] ,
							'plan_pago_cuota_precio': $scope.selected["plan_pago_cuota_precio"],
							'vto_1_fecha': $scope.selected["vto_1_fecha"],
							'vto_1_Precio': $scope.selected["vto_1_Precio"], 
							'vto_2_fecha': $scope.selected["vto_2_fecha"],
							'vto_2_precio': $scope.selected["vto_2_precio"], 
							'medidor_canti_cuota': $scope.selected["medidor_canti_cuota"] , 
							'medidor_couta_actual': $scope.selected["medidor_couta_actual"],
							'plan_pago_canti_cuota': $scope.selected["plan_pago_canti_cuota"], 
							'plan_pago_cuota_actual': $scope.selected["plan_pago_cuota_actual"], 
							'bonificacion' : $scope.selected["bonificacion"] , 
							'deuda_anterior' : $scope.selected["deuda_anterior"],
							'pago_acuenta' : $scope.selected["pago_acuenta"],
							'actual': $scope.selected["actual"],
							'anterior': $scope.selected["anterior"], 
							'multa': $scope.selected["multa"], 
							'exc_mts': $scope.selected["exc_mts"], 
							'mes': $scope.selected["mes"],
							'anio': $scope.selected["anio"],
							'mts_categoria': $scope.selected["mts_categoria"]
							}).
							success(function(response) {
								console.log( "    - hecho: "+response);
						});
     				//       original          el q tiene el cambio
						$scope.facturas[idx] = angular.copy($scope.selected);
						//$scope.selected = '';
						$scope.reset();
			};
		
			$scope.crear_recibo = function (factura_enviada) { // ventana de confirmacion de pago
				var a = document.createElement("a");
				a.target = "_blank";
				a.href = 'http://localhost/codeigniter/nuevo/crear_recibo_de_pago'+"/"+factura_enviada.id,  
				a.click();
			};

			$scope.crear_pdf = function (factura_enviada) { // ventana de confirmacion de pago
				var a = document.createElement("a");
				a.target = "_blank";
				a.href = 'http://localhost/codeigniter/nuevo/crear_factura_por_id'+"/"+factura_enviada.id_conexion+"/"+factura_enviada.sector+"/"+factura_enviada.mes+"/"+factura_enviada.anio;
				a.click();
			};
			
			$scope.recalcular_valores = function (idx, factura_enviada) { 
			//recalculo el exc m3 y ek exc $, ademas de el subtotal y total, vto1$ y vto2$ lueo guardo eso
				console.log("Saving contact");
				$scope.selected_dos = angular.copy(factura_enviada);
				console.log($scope.selected_dos);
				for (var i = 0; i < $scope.facturas.length && ($scope.facturas[i]["id"] != factura_enviada["id"]); i++) {
				};
				console.log("indece:"+i+"id index:"+$scope.facturas[i]["id"]+ "id  copia:"+$scope.selected["id"]);
				idx = i;
				//calculo excedente
				var  precio_metros = parseFloat(5);
				if($scope.selected_dos.mts_categoria != 10)
					precio_metros = parseFloat(7.5);

				$scope.selected_dos.exc_mts =  parseInt($scope.selected_dos.actual) -  parseInt($scope.selected_dos.anterior) - parseInt($scope.selected_dos.mts_categoria);
				if($scope.selected_dos.exc_mts < 0 )
					$scope.selected_dos.exc_mts = 0;
				$scope.selected_dos.exc_precio = 0;
				if($scope.selected_dos.exc_mts != 0)
					$scope.selected_dos.exc_precio = parseFloat(precio_metros) * parseFloat($scope.selected_dos.exc_mts);
				//calculo el subtotal y total
				$scope.selected_dos.sub_total = parseFloat($scope.selected_dos.tarifa_basica) 
										+ parseFloat($scope.selected_dos.deuda_anterior)
										+ parseFloat($scope.selected_dos.exc_precio )
										+ parseFloat($scope.selected_dos.couta_social )
										+ parseFloat($scope.selected_dos.riego )
										+ parseFloat($scope.selected_dos.medidor_couta_precio)
										+ parseFloat($scope.selected_dos.plan_pago_cuota_precio)
										+ parseFloat($scope.selected_dos.multa )
										;

				//calcular bonificacion
				$scope.selected_dos.bonificacion=0;
				if($scope.selected_dos.deuda_anterior == 0)
					$scope.selected_dos.bonificacion= (parseFloat($scope.selected_dos.exc_precio) + parseFloat($scope.selected_dos.tarifa_basica)) * parseFloat(5) / parseFloat(100) ;//con bonificacion
			
				$scope.selected_dos.total  = $scope.selected_dos.sub_total;
				$scope.selected_dos.total =	parseFloat($scope.selected_dos.total)
										- parseFloat($scope.selected_dos.pago_acuenta)
										- parseFloat($scope.selected_dos.bonificacion);

				$scope.selected_dos.vto_2_precio = parseFloat($scope.selected_dos.total) + parseFloat($scope.selected_dos.total) * parseFloat(valor_multiplicacdor);
				$scope.selected_dos.sub_total = $scope.selected_dos.sub_total.toFixed(2);
				$scope.selected_dos.total  = $scope.selected_dos.total.toFixed(2);
				//vtos
				$scope.selected_dos.vto_1_precio = $scope.selected_dos.total;
				$scope.selected_dos.vto_2_precio = $scope.selected_dos.vto_2_precio.toFixed(2);
				//$scope.pago_parcial(); esto no va xq no estoy pagando
				$scope.monto_total_sin_modificar_a = $scope.selected_dos.total;


				//guardo en la base de datos
				$http.post('http://localhost/codeigniter/nuevo/recalcular_boleta/'+$scope.selected_dos["id"],  
					{

					'exc_precio': $scope.selected_dos.exc_precio,
					'exc_mts': $scope.selected_dos.exc_mts,
					'sub_total': $scope.selected_dos.sub_total,
					'total': $scope.selected_dos.total, 
					'bonificacion': $scope.selected_dos.bonificacion, 
					'vto_1_precio': $scope.selected_dos.vto_1_precio, 
					'vto_2_precio': $scope.selected_dos.vto_2_precio, 
					'monto_total_sin_modificar_a': $scope.monto_total_sin_modificar_a,
					'id_conexion': $scope.selected_dos["id_conexion"]
						}).
					success(function(response) {
						if(response !=  true)
							alert("No se pudo re calcular");
						else alert("Recalculado Completo");
					});
				//ahora guardo en la row de la tabla
				$scope.facturas[idx] = angular.copy($scope.selected_dos);
				$scope.reset();
			};


			$scope.pagar_boleta = function (idx, factura_enviada) { // ventana de confirmacion de pago
				console.log("Saving contact");
				$scope.selected_dos = angular.copy(factura_enviada);
				console.log($scope.selected_dos);
				$scope.showModal = true;
				$scope.Titutlo_Factura_Id= $scope.selected_dos.id;
				$scope.Titulo_Conexion_Id= $scope.selected_dos.id_conexion;
				$scope.Titulo_Nombre =  $scope.selected_dos.name;
				//aca reinici las variables

				$scope.fecha_elegida_angular = '';
				$scope.fechacorregida = '';


			//	$scope.tipo_de_pago = '';
			//	$scope.pago_parcial = '';
				$scope.forma_de_pago = '';				
				$scope.monto_efectivo_a_pagar = '';
				$scope.monto_cheque_a_pagar = '';
				$scope.descuento = '';



				$scope.monto_total_sin_modificar_a = $scope.selected_dos.total;
				//$dialog.dialog({}).open('http://localhost/codeigniter/anugular_template/modal_pago.html');  
				console.log($scope.selected_dos.id);
				//var copia =  angular.copy($scope.selected);
				//$scope.reset();
			};
			$scope.reset = function () {
				$scope.selected = {};
			};

			$scope.modalShown = false;
			$scope.open = function() {
			  $scope.showModal = true;
			};

			$scope.ok = function() { // realemnte pagar la boleta
				$scope.fechacorregida = $scope.fechacorregida.replace(" ", "/");
				$scope.fechacorregida = $scope.fechacorregida.replace(" ", "/");

				console.log($scope.monto_cheque_a_pagar,$scope.monto_efectivo_a_pagar,$scope.forma_de_pago);

				//alert ($scope.pago_atrasado);

				console.log($scope.selected_dos["id_conexion"]);

				$http.post('http://localhost/codeigniter/nuevo/pagar_boleta/'+$scope.selected_dos["id"],  
					{'fechacorregida': $scope.fechacorregida, 'tipo_de_pago': $scope.tipo_de_pago, 
					'deuda': $scope.selected_dos.deuda_anterior, 'tarifa_basica': $scope.selected_dos.tarifa_basica,
					'exc_precio': $scope.selected_dos.exc_precio, 'couta_social': $scope.selected_dos.couta_social,
					'medidor_canti_cuota': $scope.selected_dos.medidor_canti_cuota, 'plan_pago_canti_cuota': $scope.selected_dos.plan_pago_canti_cuota,
					'medidor_couta_actual': $scope.selected_dos.medidor_couta_actual, 'plan_pago_cuota_actual': $scope.selected_dos.plan_pago_cuota_actual,
					'medidor_couta_precio': $scope.selected_dos.medidor_couta_precio, 'plan_pago_cuota_precio': $scope.selected_dos.plan_pago_cuota_precio,

					'cheque': $scope.monto_cheque_a_pagar, 'sub_total': $scope.selected_dos.sub_total,
					'multa': $scope.selected_dos.multa,
					'contado': $scope.monto_efectivo_a_pagar, 
					'riego': $scope.selected_dos.riego, 
					'total': $scope.selected_dos.total, 
					'endeuda': $scope.endeuda, 'descuento': $scope.descuento,
					'forma_de_pago': $scope.forma_de_pago,
					'pago_atrasado' : $scope.pago_atrasado,
					'monto_total_sin_modificar_a': $scope.monto_total_sin_modificar_a, 'pago_parcial_a': $scope.pago_parcial_a,
					'id_conexion': $scope.selected_dos["id_conexion"]

					}).
					success(function(response) {
						alert(response.trim() );
						var resultados  = response.split("*");
						alert("Res Back up"+ parseInt(resultados[0].trim()));
						alert("Res Boleta"+resultados[1]);
						alert("Res Conexion"+resultados[2]);
						alert("Res Movimiento"+resultados[3]);

						if(response !=  true)
						{
							alert($scope.facturas.length);
							console.log("Cantidad:"+$scope.facturas.length+" - Pivote"+$scope.facturas[3]["id"]+"  - Buscado:"+$scope.selected_dos["id"]);
														//ahora se tiene que modificar los valores de pago_monto, pago_fecha y lños botons de accion en la row
							for (var i = 0; i < $scope.facturas.length && ($scope.facturas[i]["id"] != $scope.selected_dos["id"]); i++) { //busco la factura q acabo de pagar
								console.log("indece:"+i+"id index:"+$scope.facturas[i]["id"]+ "id  copia:"+$scope.selected_dos["id"]);
							};
							idx = i;
							$scope.facturas[idx]["pago_monto"]  = $scope.selected_dos.total;
							$scope.facturas[idx]["pago_id"]  = "2018-05-24";
						}
						else 
						{
							alert("la boleta Siaa se pago");
							
						}
				var a = document.createElement("a");
				a.target = "_blank";
				a.href = 'http://localhost/codeigniter/nuevo/crear_recibo_de_pago'+"/"+$scope.selected_dos["id"],  
				a.click();
				$scope.showModal = false;
			});
			}

			$scope.cancel = function() {
			  $scope.showModal = false;
			};
			$scope.saveUser = function(usr) {
				$scope.userMod = usr;
				$window.alert('Desde metodo SALVAR del controller fuera de la ventana: ' + $scope.userMod.shortKey);
			}
			//FUnciones viejas
			var valor_multiplicacdor = 0.015;
			//var valor_multiplicacdor = 0.0573;
			//var valor_multiplicacdor = 0.0434;

			$scope.pago_atrasado = 0;
			$scope.recalcular_boleta = function() {
				$scope.selected_dos.sub_total = parseFloat($scope.selected_dos.tarifa_basica) 
										+ parseFloat($scope.selected_dos.deuda_anterior)
										+ parseFloat($scope.selected_dos.exc_precio )
										+ parseFloat($scope.selected_dos.couta_social )
										+ parseFloat($scope.selected_dos.riego )
										+ parseFloat($scope.selected_dos.medidor_couta_precio)
										+ parseFloat($scope.selected_dos.plan_pago_cuota_precio)
										+ parseFloat($scope.selected_dos.multa );
				$scope.selected_dos.total  = $scope.selected_dos.sub_total;
				$scope.selected_dos.total =	parseFloat($scope.selected_dos.total)
										- parseFloat($scope.selected_dos.pago_acuenta)
										- parseFloat($scope.selected_dos.bonificacion);
				if($scope.pago_atrasado === 1) // veo si esta atrasado, entonces le sumo el aumento
				{
					//if($scope.selected_dos.total == 120)
					//$scope.selected_dos.total += parseFloat($scope.selected_dos.total) * parseFloat(valor_multiplicacdor);
					$scope.selected_dos.total = parseFloat($scope.selected_dos.vto_2_precio);
				}
					$scope.monto_total_sin_modificar_a = $scope.selected_dos.total;
				$scope.selected_dos.sub_total = $scope.selected_dos.sub_total.toFixed(2);
				$scope.selected_dos.total  = $scope.selected_dos.total.toFixed(2);
				$scope.selected_dos.monto_total_sin_modificar_a  = $scope.selected_dos.monto_total_sin_modificar_a;
				$scope.pago_parcial();
			}
			function sumarDias(fecha, dias){
			  fecha.setDate(fecha.getDate() + dias);
			  return fecha;
			}

			$scope.calcular_aumento_monto_por_fecha = function() {
				var ddddate = new Date($scope.fecha_elegida_angular);
				ddddate = sumarDias(ddddate, 1);
				console.log(ddddate);
				var aux = $scope.selected_dos.vto_1_fecha.split('-');  // 0=>Y  1=>m 2=>d 
				$scope.fechacorregida = $scope.formatDate(ddddate);
				var day = ddddate.getDate();
				var mes = ddddate.getMonth();
				mes++;
				//alert("mes vto:"+parseInt(aux[1]) + "    * mes puesto: "+parseInt(mes) + "dia elegido"+ parseInt(day)+ "  dia vto"+parseInt(aux[2]) ) ;
				if( 
				(parseInt(mes) > parseInt(aux[1]) )
				 || ( parseInt(day) > parseInt(aux[2]) ) 
				 ) // solamente comparo el dia, no comparo el mes
				 	$scope.pago_atrasado = 1;
				else 
				$scope.pago_atrasado = 0;
				$scope.recalcular_boleta();
			}
			$scope.pago_parcial_a = 0; //seteado
			$scope.monto_total_sin_modificar_a = 0; // ya calculado
			$scope.tipo_de_pago = 1; //select
			$scope.endeuda = 0; //seteado

			$scope.pago_parcial = function() {
				//alert("gola");
				if($scope.tipo_de_pago == 2)
				{
					//alert($scope.monto_total_sin_modificar_a);
					if($scope.pago_parcial_a < $scope.monto_total_sin_modificar_a)
					{
						// if($scope.selected_dos.bonificacion != 0) //compruebo si hay descuento 
						// {// voy a descontar la bonificacion de la deuda que le quedara a esa conexion
						// 	$scope.endeuda =$scope.monto_total_sin_modificar_a - $scope.pago_parcial_a - $scope.selected_dos.bonificacion;
						// }
						// else 
						$scope.endeuda =$scope.monto_total_sin_modificar_a - $scope.pago_parcial_a;
						$scope.endeuda = $scope.endeuda.toFixed(2);
						$scope.selected_dos.total = $scope.pago_parcial_a;
						
					}
					else 
					{
						alert("Intenta pagar más que el monto");
						$scope.pago_parcial_a = 0;
						$scope.endeuda = 0;
					}
				}
			}
			$scope.aplicar_desceunto_en_boleta = function() {
				if( ($scope.descuento != 0) && ($scope.descuento != null) ) //compruebo si hay descuento 
				{// voy a descontar la bonificacion de la deuda que le quedara a esa conexion
					if( ($scope.endeuda != 0) && ($scope.endeuda != null) )
					{
						$scope.endeuda = $scope.monto_total_sin_modificar_a - $scope.pago_parcial_a - $scope.descuento;
						if($scope.endeuda < 0) //descuento mas de lo q endeudo
						{
							$scope.descuento = $scope.descuento + $scope.endeuda; //es + xq es un numero negativo
							$scope.endeuda = 0;
						}
					}
					else // significa q no tiene eneduda y descuento al total
						$scope.selected_dos.total = $scope.monto_total_sin_modificar_a  - $scope.descuento;
						if($scope.total < 0) //descuento mas de lo q endeudo
						{
							$scope.selected_dos.total = $scope.monto_total_sin_modificar_a; //es + xq es un numero negativo
							$scope.endeuda = 0;
							$scope.descuento = 0;
						}

				}
			}
			//pago 100
			//descuento 150
			//endeudo 296.5
			//deuda anterior 0
			


			/*
			TIPO de PAGO => PARCIAL O TOTAL
			FORMA DE PAOG => CONTADO , CHEQUE MIXTO*/
			$scope.cambiar_parcial_o_total = function() {
				if($scope.tipo_de_pago == 1) // reseteo el form   es total
				{
					$scope.endeuda = 0;
					$scope.pago_parcial_a  = 0;
					$scope.selected_dos.bonificacion = 0;
					$scope.selected_dos.total = $scope.monto_total_sin_modificar_a;
				}
				else   // es parcial
				{
					$scope.pago_parcial_a = 0;
				}
				//else alert("Intenta pagar más que el monto");
			}
			
			// $scope.cambiar_forma_de_pago = function() {
			// 	//var forma_de_pago = $scope.forma_de_pago;
			// 	if($scope.forma_de_pago == 1) // significa que paga contado
			// 	{
			// 		$scope.endeuda = 0;
			// 		$scope.pago_parcial_a  = 0;
			// 		$scope.selected_dos.bonificacion = 0;
			// 		$scope.selected_dos.total = $scope.monto_total_sin_modificar_a;
			// 	}
			// }
			$scope.cambio_parte_contado = function() {
				if($scope.tipo_de_pago == 1) // reseteo el form   es total
				{
					if( parseFloat($scope.monto_efectivo_a_pagar) > parseFloat($scope.selected_dos.total))
					{
						$scope.monto_efectivo_a_pagar = 0;
						$scope.monto_cheque_a_pagar = 0;
					}
					else // todo bien
					{
						$scope.monto_cheque_a_pagar = parseFloat($scope.selected_dos.total) - parseFloat($scope.monto_efectivo_a_pagar); 
						//$scope.monto_cheque_a_pagar=  parseFloat($scope.monto_cheque_a_pagar).toFixed(2);
					}	
				}
				else
				{// significa q voy pagar una parte nada mas y a esa parte la divido en cheque y contado
					if( parseFloat($scope.monto_efectivo_a_pagar) > parseFloat($scope.pago_parcial_a))
					{
						$scope.monto_efectivo_a_pagar = 0;
						$scope.monto_cheque_a_pagar = 0;
					}
					else // todo bien
					{
						$scope.monto_cheque_a_pagar = parseFloat($scope.pago_parcial_a) - parseFloat($scope.monto_efectivo_a_pagar); 
						//$scope.monto_cheque_a_pagar=  parseFloat($scope.monto_cheque_a_pagar).toFixed(2);
					}
				}
				$scope.recalcular_boleta();
					
			}
			
			$scope.cambio_parte_cheque = function() {
				if( parseFloat($scope.monto_cheque_a_pagar) > parseFloat($scope.selected_dos.total))
				{
					$scope.monto_efectivo_a_pagar = 0;
					$scope.monto_cheque_a_pagar = 0;
				}
				else // todo bien
				{
					$scope.monto_efectivo_a_pagar  = parseFloat($scope.selected_dos.total) - parseFloat($scope.monto_cheque_a_pagar); 
					//$scope.monto_efectivo_a_pagar  = parseFloat($scope.monto_efectivo_a_pagar).toFixed(2);
				}
			}
			$scope.formatDate = function(date) {
				// var monthNames = [
				//   "Enero", "Feebruary", "March",
				//   "April", "May", "June", "July",
				//   "August", "September", "October",
				//   "November", "December"
				// ];

				var day = date.getDate();
				var monthIndex = date.getMonth();
				var year = date.getFullYear();

				return day + ' ' + (monthIndex+1) + ' ' + year;
			}
			$scope.selectedItemChanged = function(){
				$scope.valor_inicial = $scope.selectedItem;
				$scope.tmpList = [];
				//alert("legi el barrio:" +$scope.selectedItem );
				$http({
					method: 'GET',
					url: 'http://localhost/codeigniter/nuevo/traer_datos_por_sector/'+$scope.selectedItem
					}).then(function successCallback(response) {
						var mi_lista=  angular.fromJson(response.data);
						console.log(mi_lista[0]);
						$scope.facturas = [];
						angular.forEach(mi_lista, function(value, key){
							$scope.facturas.push(
								{ 
									id: parseInt(value.id), 
									name: value.Cli_RazonSocial,
									codigo_barra : value.Factura_CodigoBarra,
									id_conexion: parseInt(value.Conexion_Id),
									id_cliente: parseInt(value.Cli_Id) , 
									mes: parseInt(value.Medicion_Mes), 
									anio : parseInt(value.Medicion_Anio) , 
									actual: parseInt(value.Medicion_Actual), 
									anterior: parseInt(value.Medicion_Anterior), 
									exc_mts: parseInt(value.Medicion_Excedente), 
									exc_precio: parseFloat(value.Medicion_Importe).toFixed(2), 
									categoria : value.Conexion_Categoria, 
									vto_1_fecha : value.Factura_Vencimiento1, 
									vto_1_Precio : parseFloat(value.Factura_Vencimiento1_Precio).toFixed(2),
									vto_2_fecha : value.Factura_Vencimiento2, 
									vto_2_precio : parseFloat(value.Factura_Vencimiento2_Precio).toFixed(2),
									deuda_anterior : parseFloat(value.Conexion_Deuda).toFixed(2),
									tarifa_basica: parseFloat(value.Factura_TarifaSocial).toFixed(2),
									couta_social: parseFloat(value.Factura_CuotaSocial).toFixed(2),
									medidor_canti_cuota: parseInt(value.Factura_PM_Cant_Cuotas),  
									medidor_couta_actual: parseInt(value.Factura_PM_Cuota_Actual),    
									medidor_couta_precio: parseFloat(value.Factura_PM_Cuota_Precio).toFixed(2),
									plan_pago_canti_cuota: parseInt(value.Factura_PP_Cant_Cuotas), 
									plan_pago_cuota_actual: parseInt(value.Factura_PP_Cuota_Actual), 
									plan_pago_cuota_precio: parseFloat(value.Factura_PPC_Precio).toFixed(2),
									riego: parseFloat(value.Factura_Riego).toFixed(2),
									sub_total: parseFloat(value.Factura_SubTotal).toFixed(2),
									pago_acuenta: parseFloat(value.Factura_Acuenta).toFixed(2),
									bonificacion: parseFloat(value.Factura_Bonificacion).toFixed(2),
									total: parseFloat(value.Factura_Total).toFixed(2), 
									pago_monto: parseFloat(value.Factura_PagoMonto).toFixed(2),
									pago_id: value.Factura_PagoTimestamp,
									sector: value.Conexion_Sector, 
									orden_sector: parseInt(value.Conexion_UnionVecinal),
									mts_categoria: parseInt(value.Medicion_Basico),
									domicilio: value.Conexion_DomicilioSuministro }
								);
							});

						}, function errorCallback(response) {
							alert(response);
				});
			}

			
			$scope.buscar_conexion = function(){
				$scope.valor_inicial = $scope.selectedItem;
				$scope.tmpList = [];
				//alert("legi el barrio:" +$scope.selectedItem );
				$http({
					method: 'GET',
					url: 'http://localhost/codeigniter/nuevo/traer_datos_por_sector/'+$scope.selectedItem
					}).then(function successCallback(response) {
						var mi_lista=  angular.fromJson(response.data);
						console.log(mi_lista[0]);
						$scope.facturas = [];
						angular.forEach(mi_lista, function(value, key){
							$scope.facturas.push(
								{ 
									id: parseInt(value.id), 
									name: value.Cli_RazonSocial,
									codigo_barra : value.Factura_CodigoBarra,
									id_conexion: parseInt(value.Conexion_Id),
									id_cliente: parseInt(value.Cli_Id) , 
									mes: parseInt(value.Medicion_Mes), 
									anio : parseInt(value.Medicion_Anio) , 
									actual: parseInt(value.Medicion_Actual), 
									anterior: parseInt(value.Medicion_Anterior), 
									exc_mts: parseInt(value.Medicion_Mts), 
									exc_precio: parseFloat(value.Medicion_Importe).toFixed(2), 
									categoria : value.Conexion_Categoria, 
									vto_1_fecha : value.Factura_Vencimiento1, 
									vto_1_Precio : parseFloat(value.Factura_Vencimiento1_Precio), 
									vto_2_fecha : value.Factura_Vencimiento2, 
									vto_2_precio : parseFloat(value.Factura_Vencimiento2_Precio), 
									deuda_anterior : parseFloat(value.Conexion_Deuda), 
									tarifa_basica: parseFloat(value.Factura_TarifaSocial), 
									couta_social: parseFloat(value.Factura_CuotaSocial), 
									medidor_canti_cuota: parseInt(value.Factura_PM_Cant_Cuotas),  
									medidor_couta_actual: parseInt(value.Factura_PM_Cuota_Actual),    
									medidor_couta_precio: parseFloat(value.Factura_PM_Cuota_Precio).toFixed(2),  
									plan_pago_canti_cuota: parseInt(value.Factura_PP_Cant_Cuotas), 
									plan_pago_cuota_actual: parseInt(value.Factura_PP_Cuota_Actual), 
									plan_pago_cuota_precio: parseFloat(value.Factura_PPC_Precio).toFixed(2),
									riego: parseFloat(value.Factura_Riego) , 
									sub_total: parseFloat(value.Factura_SubTotal),  
									pago_acuenta: parseFloat(value.Conexion_Acuenta) , 
									bonificacion: parseFloat(value.Facturacion_Bonificacion), 
									total: parseFloat(value.Factura_Total), 
									pago_monto: parseFloat(value.Pago_Monto),
									sector: value.Conexion_Sector, 
									orden_sector: parseInt(value.Conexion_UnionVecinal),
									mts_categoria: parseInt(value.Medicion_Mts),
									domicilio: value.Conexion_DomicilioSuministro }
								);
							});

						}, function errorCallback(response) {
							alert(response);
				});
			}

			
			$scope.filtrar_filas = function(){
				 var sectorBuscado = $scope.selectedItem;
				if(sectorBuscado == "Sin Elegir")
					sectorBuscado = -1;
				 var mesBuscado = $scope.selectedMes;
				if(mesBuscado == "Sin Elegir")
					mesBuscado = -1;
				 var anoBuscado = $scope.selectedAno;
				if(anoBuscado == "Sin Elegir")
					anoBuscado = -1;
				var idBuscado = $scope.selectedConexion;
				if(idBuscado == "Sin Elegir")
					idBuscado = -1;
				var pagoBuscado = $scope.selectedPago;
				if(pagoBuscado == "Sin Elegir")
					pagoBuscado = -1;

				$http({
					method: 'GET',
					url: 'http://localhost/codeigniter/nuevo/traer_datos_por_query/'+sectorBuscado+'/'+mesBuscado+'/'+anoBuscado+'/'+idBuscado+'/'+pagoBuscado,
					}).then(function successCallback(response) {
						var mi_lista=  angular.fromJson(response.data);
						console.log(mi_lista[0]);
						$scope.facturas = [];
						angular.forEach(mi_lista, function(value, key){
							$scope.facturas.push(
								{ 
									id: parseInt(value.id), 
									name: value.Cli_RazonSocial,
									codigo_barra : value.Factura_CodigoBarra,
									id_conexion: parseInt(value.Conexion_Id),
									id_cliente: parseInt(value.Cli_Id) , 
									mes: parseInt(value.Medicion_Mes), 
									anio : parseInt(value.Medicion_Anio) , 
									actual: parseInt(value.Medicion_Actual), 
									anterior: parseInt(value.Medicion_Anterior), 
									exc_mts: parseInt(value.Medicion_Excedente), 
									exc_precio: parseFloat(value.Medicion_Importe).toFixed(2), 
									categoria : value.Conexion_Categoria, 
									vto_1_fecha : value.Factura_Vencimiento1, 
									vto_1_Precio : parseFloat(value.Factura_Vencimiento1_Precio).toFixed(2),
									vto_2_fecha : value.Factura_Vencimiento2, 
									vto_2_precio : parseFloat(value.Factura_Vencimiento2_Precio).toFixed(2),
									deuda_anterior : parseFloat(value.Factura_Deuda).toFixed(2),
									tarifa_basica: parseFloat(value.Factura_TarifaSocial).toFixed(2),
									couta_social: parseFloat(value.Factura_CuotaSocial).toFixed(2),
									medidor_canti_cuota: parseInt(value.Factura_PM_Cant_Cuotas),  
									medidor_couta_actual: parseInt(value.Factura_PM_Cuota_Actual),    
									medidor_couta_precio: parseFloat(value.Factura_PM_Cuota_Precio).toFixed(2),
									plan_pago_canti_cuota: parseInt(value.Factura_PP_Cant_Cuotas), 
									plan_pago_cuota_actual: parseInt(value.Factura_PP_Cuota_Actual), 
									plan_pago_cuota_precio: parseFloat(value.Factura_PPC_Precio).toFixed(2),
									riego: parseFloat(value.Factura_Riego).toFixed(2),
									sub_total: parseFloat(value.Factura_SubTotal).toFixed(2),
									pago_acuenta: parseFloat(value.Factura_Acuenta).toFixed(2),
									bonificacion: parseFloat(value.Factura_Bonificacion).toFixed(2),
									total: parseFloat(value.Factura_Total).toFixed(2), 
									pago_monto: parseFloat(value.Pago_Monto).toFixed(2),
									sector: value.Conexion_Sector, 
									orden_sector: parseInt(value.Conexion_UnionVecinal),
									mts_categoria: parseInt(value.Medicion_Basico),
									domicilio: value.Conexion_DomicilioSuministro,
									multa: parseFloat(value.Factura_Multa)
									 }
								);
							});

						}, function errorCallback(response) {
							alert(response);
				});
			}
		}]);

		sortApp.filter('startFrom', function() {
		    return function(input, start) {
		        start = +start; //parse to int
		        return input.slice(start);
		    }
		});
	</script>
</body>
</html>
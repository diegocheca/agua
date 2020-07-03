<style>
.list {
	list-style: none outside none;
	margin: 10px 0 30px;
}

.item {
	width: 350px;
	padding: 5px 10px;
	margin: 5px 0;
	border: 2px solid #444;
	border-radius: 5px;
	background-color: #00bfff;

	font-size: 1.1em;
	font-weight: bold;
	text-align: center;
	cursor: pointer;
}

.ui-sortable-helper {
  cursor: move;
}


/***  Extra ***/

body {
	font-family: Verdana, 'Trebuchet ms', Tahoma;
}

.logList {
	margin-top: 20px;
	width: 250px;
	min-height: 200px;
	padding: 5px 15px;
	border: 5px solid #FFF;
	border-radius: 15px;
}

.logList:before {
	content: 'log';
	padding: 0 5px;
	position: relative;
	top: -1.1em;
	background-color: #FFF;
}

.container {
	width: 800px;
	margin: auto;
}

h2 {
	text-align: center;
}

.floatleft {
  float: left;
}

.clear {
  clear: both;
}

</style>
<div ng-app="sortableApp" ng-controller="sortableController" class="container">
	<h2>Lista de Conexion para Ordenar </h2>
	<hr>
	<label>Elegir sector: </label>
	<select ng-options="option for option in listOfOptions" 
		ng-model="selectedItem"
		ng-change="selectedItemChanged()"
		ng-init="selectedItem =  'A'"
		>
	</select>
	<p>Estado: <b>{{calculatedValue}}</b></p>
	<hr>
	<div class="floatleft">
		<ul ui-sortable="sortableOptions" ng-model="list" class="list">
			<li ng-repeat="item in list" class="item">
				{{$index+1}}°   {{item.text}}
				<br>
			</li>
		<br>
		<hr>
		</ul>
	</div>

	<div class="floatleft" style="margin-left: 20px;">
		<button ng-click="guardar_orden()" class="btn bgm-indigo btn-float waves-button waves-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-save"></i></button>
		<ul class="list logList">
			<li ng-repeat="entry in sortingLog track by $index" class="logItem">
				{{entry}}
			</li>
		</ul>
	</div>
  <div class="clear"></div>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.4/angular.min.js"></script>
  <script src="https://rawgithub.com/angular-ui/ui-sortable/master/src/sortable.js"></script>
  <script type="text/javascript">
	var myapp = angular.module('sortableApp', ['ui.sortable']);
	myapp.controller('sortableController', function ($scope, $http) {
		$scope.listOfOptions = ['A', 'B', 'C', "Sta Barbara", "Aberastain", "VIP", "ASENTAMIENTO OLMOS", "V Elisa", "medina", "Jardin", "David","Salas","0"];
		var tmpList = [];
		var numero = 0;
		var valor_inicial = 'A';
		$http({
			method: 'GET',
			url: 'http://localhost/codeigniter/conexion/datos/'+valor_inicial
		}).then(function successCallback(response) {
			var mi_lista=  angular.fromJson(response.data);
			angular.forEach(mi_lista, function(salu) {
				tmpList.push({
						text: 'Conexion N: ' + salu.id_conexion,
						conexion: salu.id_conexion,
						value:  salu.orden_acutal
					});
				});
		}, function errorCallback(response) {
			alert(response);
	});

 $scope.selectedItemChanged = function(){
    $scope.calculatedValue = 'Ordenando el Barrio/Sector : ' + $scope.selectedItem;
    $scope.valor_inicial = $scope.selectedItem;
    $scope.tmpList = [];
	$scope.numero = 0;
	//alert("legi el barrio:" +$scope.selectedItem );
    $http({
		method: 'GET',
		url: 'http://localhost/codeigniter/conexion/datos/'+$scope.selectedItem
		}).then(function successCallback(response) {
			var mi_lista=  angular.fromJson(response.data);
			console.log(mi_lista[0].id_conexion);
			$scope.tmpList = [];
			angular.forEach(mi_lista, function(salu) {
				 $scope.tmpList.push({
						text: 'Conexion N: ' + salu.id_conexion,
						conexion: salu.id_conexion,
						value:  salu.orden_acutal
					});
				console.log("Conexion: "+ salu.id_conexion + " _ Orden : "+salu.orden_acutal);
				});
			console.log($scope.tmpList);
			$scope.list = $scope.tmpList;
			$scope.sortingLog = [];

			}, function errorCallback(response) {
				alert(response);
	});
  }

  
 $scope.guardar_orden = function(){
 	var indice = 0;
 	var logEntry = $scope.tmpList.map(function(i){
 		indice ++;
 		//console.log(indice);
		return indice+"-"+i.conexion;
	}).join(', ');
	//alert(logEntry);
	var url_mia = 'http://localhost/codeigniter/conexion/guardar_orden_controller';
	$http.post(url_mia, {"name": logEntry}).
						success(function(data, status) {
							console.log(data+ "    - estado: "+status);
							
						})
	location.reload(true);		
  };

  $scope.list = tmpList;
  $scope.sortingLog = [];
  $scope.sortableOptions = {
    update: function(e, ui) {
      var logEntry = tmpList.map(function(i){
        return i.value;
      }).join(', ');
      $scope.sortingLog.push('Update: ' + logEntry);
    },
    stop: function(e, ui) {
      // this callback has the changed model
      var logEntry = tmpList.map(function(i){
        return i.value;
      }).join(', ');
      $scope.sortingLog.push('Stop: ' + logEntry);
    }
  };
});
  </script>
</div>

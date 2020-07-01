var app = angular.module('formApp', []);
 
app.controller('MainCtrl', function($scope) {
 // $scope.formData = {};
 
 $scope.saludo ="dfasfasf";
 //inicio variables
$scope.monto_total_a = '';
$scope.monto_total_sin_modificar_a = '';
$scope.deuda_anterior_a = '';
$scope.tarifa_social_a = '';
$scope.categoria_a = '';
$scope.exdenete_a = '';
$scope.cuota_social_a = '';
$scope.total_coutas_medidor_a = '';
$scope.cuota_actual_medidor_a = '';
$scope.monto_cuota_medidor_a = '';
$scope.total_coutas_plan_a = '';
$scope.cuota_actual_plan_a = '';
$scope.monto_cuota_plan_a = '';
$scope.subtotal_a = '' 
$scope.pago_a_cuenta_a = '' 
$scope.riego_a = '';
$scope.bonificacion_a = '';
$scope.atrasado_a = '';
$scope.pago_parcial_a = '';
$scope.endeuda_a = '';
$scope.descuento_a_aplicar_a = 0;
//$scope.fecha_elegida_angular = '';


   $scope.mixto_calculo_desde_contado = function() {
      // $scope.$apply(function () 
      // {
        $scope.cantidad_tarjeta = $scope.total - $scope.cantidad_contado;
      // });
   }
    $scope.mixto_calculo_desde_tarjeta = function() {
      // $scope.$apply(function () 
      // {
        $scope.cantidad_contado = $scope.total - $scope.cantidad_tarjeta;
      // });
   }





// alert($scope.saludo);
$scope.inicio_de_variables = function(monto_total, deuda_anterior,categoria, tarifa_social, multiplicador, exdenete, cuota_social, cuota_actual_medidor, total_coutas_medidor, monto_cuota_medidor, total_coutas_plan_a, cuota_actual_plan_a, monto_cuota_plan_a, subtotal, pago_a_cuenta, riego , bonificacion, atrasado) {
		//$scope.$apply(function () {
		//	alert(monto_total);
			$scope.monto_total_a = monto_total;
			$scope.monto_total_sin_modificar_a = monto_total;
			$scope.categoria_a = categoria;
			$scope.tarifa_social_a = tarifa_social;

			$scope.deuda_anterior_a = deuda_anterior;
			$scope.exdenete_a = exdenete;
			$scope.cuota_social_a = cuota_social;
			$scope.total_coutas_medidor_a = total_coutas_medidor;
			$scope.cuota_actual_medidor_a = cuota_actual_medidor;
			$scope.monto_cuota_medidor_a = monto_cuota_medidor;
			$scope.total_coutas_plan_a = total_coutas_plan_a;
			$scope.cuota_actual_plan_a = cuota_actual_plan_a;
			$scope.monto_cuota_plan_a = monto_cuota_plan_a;
			$scope.subtotal_a = subtotal;
			$scope.pago_a_cuenta_a = pago_a_cuenta;

			$scope.riego_a = pago_a_cuenta;
			$scope.bonificacion_a = pago_a_cuenta;
			$scope.atrasado_a = atrasado;
			//alert($scope.atrasado_a);
}
 /*
  $scope.submitForm = function (formData) {
	alert('Form submitted with' + JSON.stringify(formData));
  };*/
	$scope.calcular_montos = function(valor_multiplicacdor) {
  			$scope.monto_total_a = $scope.tarifa_social_a 
  									+ $scope.deuda_anterior_a 
  									+ $scope.exdenete_a 
  									+ $scope.cuota_social_a 
  									+ $scope.monto_cuota_medidor_a 
  									+ $scope.monto_cuota_plan_a;
  			if($scope.atrasado_a === 1) // veo si esta atrasado, entonces le sumo el aumento
				$scope.monto_total_a += $scope.monto_total_a * parseFloat(valor_multiplicacdor);
			//$datos["configuracion"][18]->Configuracion_Valor
			//else

  			$scope.monto_total_a =	$scope.monto_total_a
  									- $scope.pago_a_cuenta_a 
  									- $scope.bonificacion_a
  									- $scope.descuento_a_aplicar_a;
			$scope.monto_total_a  = $scope.monto_total_a.toFixed(2);
	}

  $scope.calcular_aumento_monto_por_fecha = function() {
        var ddddate = new Date($scope.fecha_elegida_angular);
        $scope.fechacorregida = moment(ddddate).format('DD/MM/YYYY');
        var aux =$scope.fechacorregida.toString();
        if( (aux [0] == "2") || (aux [0] == "3") )
        {
        	//significa q paga atrasado
        	$scope.atrasado_a = 1;
        }
        else 
        	$scope.atrasado_a = 0;
        if(aux [0] == "1")
        {
        	if ( (aux [1] == "0") || (aux [1] == "1") || (aux [1] == "2") || (aux [1] == "3") || (aux [1] == "4") || (aux [1] == "5") ) 
        		$scope.atrasado_a = 0;
        	else 	$scope.atrasado_a = 1;
        } 
        $scope.calcular_montos();
  }

	$scope.pago_parcial = function() {
		//alert("gola");
		if($scope.pago_parcial_a < $scope.monto_total_sin_modificar_a)
		{
			$scope.endeuda_a = $scope.monto_total_sin_modificar_a - $scope.pago_parcial_a;
			$scope.monto_total_a = $scope.pago_parcial_a;
		}
		else 
		{
			alert("Intenta pagar más que el monto");
			$scope.pago_parcial_a = 0;
			$scope.endeuda_a = 0;
		}
  }
	$scope.cambiar_parcial_o_total = function() {
		if($scope.tipo_de_pago == 1) // reseteo el form
		{
			$scope.endeuda_a = 0;
			$scope.pago_parcial_a  = 0;
			$scope.descuento_a_aplicar_a = 0;
			$scope.monto_total_a = $scope.monto_total_sin_modificar_a;
		}
		//else alert("Intenta pagar más que el monto");
  }






});

<?php 

//$valor : cadena a cortar
//$delimitador : caracter que servirá de cortador
//$indice : valor que devolverá la funcion
//$array : 

//Usado en View : Template Factura
//Usado en View : Preview Documentos
function cutString($valor, $delimitador, $indice, $array=0){


	$process=explode($delimitador, $valor);
	if(is_array($array)){
		return $array[(int)$process[$indice]];
	}else{
		return $process[$indice];
	}
}

//$mensaje : cadena de texto (mensaje)
//$tipo : tipo de mensaje (success, info, danger, warning)

//Usado en Controller: facturar, editar
function mensaje($mensaje,$tipo){
	return '<div class="alert alert-'.$tipo.' alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
               '.$mensaje.'
            </div>';
}

function user_data($valor){

	$CI =& get_instance();
	$user=$CI->session->userdata('login');

	switch ($valor) {
		case 'usuario':
			$CI->db->where('usuario',$user);
			$consulta= $CI->db->get('usuarios');
			return $consulta->row('nombre');
			break;

		case 'avatar':
			$CI->db->where('usuario',$user);
			$consulta= $CI->db->get('usuarios');
			return $consulta->row('avatar_uri');
			break;
	}

}

/*
	Funcion que obtiene el IGV
	$igv : variable que se obtiene del documento: 0 no incluye igv, 1 si incluye igv
	$monto: variable que se obtiene del documento: es el total
	$indice: indica el valor a devolver: 0 subtotal, 1 igv, 2 total


*/
	function get_totals($igv, $monto, $indice){

		if ($igv == 0) {

			$monto = $monto / 1.18;
			$valIgv = $monto * 0.18;
			$valSubtotal = $monto;
			$valTotal= $monto + $valIgv;

			if ($indice == 0) {
				return number_format(round($valSubtotal,2),2);
			}elseif ($indice == 1) {
				return number_format(round($valIgv, 2),2);
			}elseif ($indice == 2) {
				return number_format(round($valTotal, 2),2);
			}

		}elseif ($igv == 1) {

			$valSubtotal = $monto / 1.18;
			$valTotal = $monto;
			$valIgv = $valTotal - $valSubtotal;

			if ($indice == 0) {
				return number_format(round($valSubtotal,2),2);
			}elseif ($indice == 1) {
				return number_format(round($valIgv, 2),2);
			}elseif ($indice == 2) {
				return number_format(round($valTotal, 2),2);
			}

		}

	}
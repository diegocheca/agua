<?php
/**
 *  File: /application/core/MY_Controller.php
 */
class MY_Controller extends CI_Controller {

	public $inicio_mes;
	public $fin_mes;
	public $incio_mes_personal;
	public $unidad_tributaria;
	public $tarifa_social;
	public $precio_mt_fam;
	public $tarifa_familiar;
	public $mts_basicos_familiar;
	public $precio_mt_com;
	public $tarifa_comercial;
	public $mts_basicos_comercio;
	public $cuota_social;
	public $color_pago;
	public $puesto;
	public $hoy;
	public $hora;
	public $cantidad_disponible;
	public $cantidad_digitos;
	public $mes_buscado;
	public $anio_buscado;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Crud_model');
		$this->unidad_tributaria = 1.6;
		$this->inicio_mes="2016/04/23";
		$this->fin_mes="2016/05/24";
		$this->tarifa_social="24.00";
		$this->precio_mt_fam="4.30";
		$this->tarifa_familiar="80.00";
		$this->mts_basicos_familiar="10";
		$this->precio_mt_com="7.30";
		$this->tarifa_comercial = '150.00';
		$this->mts_basicos_comercio = '15';
		$this->cuota_social = '24.00';
		$this->color_pago = 'style="background-color:#3F51B5;"';
		$this->puesto = '1';
		date_default_timezone_set('America/Argentina/San_Juan');
		$this->hoy = date("Y-m-d");
		$this->hora = date("H:i:s");
		$this->cantidad_disponible = '5';
		$this->cantidad_digitos = '6';


		$this->anio_buscado = date("Y");
		$this->mes_buscado = intval( date("m") )-1;
		if($this->mes_buscado<10)
			$this->mes_buscado = "0".$this->mes_buscado;
		if($this->mes_buscado == 1)
		{
			$this->mes_buscado = 11;
			$this->anio_buscado = intval(date("Y")) -1;
		}
		elseif ($this->mes_buscado == 2) {
			$this->mes_buscado = 12;
			$this->anio_buscado = intval(date("Y")) -1;
		}



	}
	//get
		public function get_inicio_mes() 
		{
			return $this->inicio_mes;
		}
		public function get_fin_mes() 
		{
			return $this->fin_mes;
		}
		public function get_tarifa_social() 
		{
			return $this->tarifa_social;
		}
		public function get_precio_mt_fam() 
		{
			return $this->precio_mt_fam;
		}
		public function get_tarifa_familiar() 
		{
			return $this->tarifa_familiar;
		}
		public function get_mts_basicos_familiar() 
		{
			return $this->mts_basicos_familiar;
		}
		public function get_precio_mt_com() 
		{
			return $this->precio_mt_com;
		}
		public function get_tarifa_comercial() 
		{
			return $this->tarifa_comercial;
		}
		public function get_mts_basicos_comercio() 
		{
			return $this->mts_basicos_comercio;
		}
		public function get_cuota_social() 
		{
			return $this->cuota_social;
		}
		public function get_color_pago() 
		{
			return $this->color_pago;
		}
		public function get_puesto() 
		{
			return $this->puesto;
		}
		public function get_hoy()
		{
			return $this->hoy;
		}
		public function get_hora()
		{
			return $this->hora;
		}
		public function get_cantidad_disponible()
		{
			return $this->cantidad_disponible;
		}
		public function get_cantidad_digitos()
		{
			return $this->cantidad_digitos;
		}
//set
		public function set_inicio_mes($nuevo_valor) 
		{
			$this->inicio_mes = $nuevo_valor;
			return $this->inicio_mes;
		}
		public function set_fin_mes($nuevo_valor) 
		{
			$this->fin_mes = $nuevo_valor;
			return $this->fin_mes;
		}
		public function set_tarifa_social($nuevo_valor) 
		{
			$this->tarifa_social = $nuevo_valor;
			return $this->tarifa_social;
		}
		public function set_precio_mt_fam($nuevo_valor) 
		{
			$this->precio_mt_fam = $nuevo_valor;
			return $this->precio_mt_fam;
		}
		public function set_tarifa_familiar($nuevo_valor) 
		{
			$this->tarifa_familiar = $nuevo_valor;
			return $this->tarifa_familiar;
		}
		public function set_mts_basicos_familiar($nuevo_valor) 
		{
			 $this->mts_basicos_familiar = $nuevo_valor;
			return  $this->mts_basicos_familiar;
		}
		public function set_precio_mt_com($nuevo_valor) 
		{
			$this->precio_mt_com = $nuevo_valor;
		}
		public function set_tarifa_comercial($nuevo_valor) 
		{
			 $this->tarifa_comercial = $nuevo_valor;
			return  $this->tarifa_comercial;
		}
		public function set_mts_basicos_comercio($nuevo_valor) 
		{
			 $this->mts_basicos_comercio = $nuevo_valor;
			return  $this->mts_basicos_comercio;
		}
		public function set_cuota_social($nuevo_valor) 
		{
			 $this->cuota_social = $nuevo_valor;
			return  $this->cuota_social;
		}
		public function set_color_pago($nuevo_valor) 
		{
			 $this->color_pago = $nuevo_valor;
			return  $this->color_pago;
		}
		public function set_puesto($nuevo_valor) 
		{
			$this->puesto =nuevo_valor;
			return $this->puesto;
		}
		public function set_hoy($nuevo_valor)
		{
			 $this->hoy = $nuevo_valor;
			return  $this->hoy;
		}
		public function set_hora($nuevo_valor)
		{
			 $this->hora = $nuevo_valor;
			return  $this->hora;
		}
		public function set_cantidad_disponible($nuevo_valor)
		{
			 $this->cantidad_disponible = $nuevo_valor;
			return  $this->cantidad_disponible;
		}
		public function set_cantidad_digitos($nuevo_valor)
		{
			 $this->cantidad_digitos = $nuevo_valor;
			return  $this->cantidad_digitos;
		}
		public function calcular_codigo_barra_agua($conexion_id, $factura_id )
		{
			$code = '1 8 3 9 5 6 0 8 9 4 1 5'; // barcode, of course ;)
			$aux_id_conexion = $conexion_id;
			$aux_id_conexion = str_pad($aux_id_conexion, 4, "0", STR_PAD_LEFT);
			$aux_factura = $factura_id;
			$aux_factura = str_pad($aux_factura, 6, "0", STR_PAD_LEFT);
			$codigo_barra = "1".$aux_id_conexion.$aux_factura;
			$control= 0;
			for ($i=0; $i < strlen($codigo_barra) ; $i++) { 
				$control  += $codigo_barra[$i];
			}
			$control_dos=$control %7;
			$codigo_barra = "1".$aux_id_conexion.$aux_factura.$control_dos."5"; //boleta de servicio de agua
			return $codigo_barra;
		}
		function arreglar_numero($numero)
		{
			// if( intval($numero) >= 1000)
			// 	$numero = str_replace(".", "",$numero);
			$inicio_coma = strpos($numero, '.');
			//var_dump($inicio_coma);die();
			// if ( is_float($numero)  &&  ($inicio_coma != false))
			// 	$numero .= "00";
			// else $numero .= ",00";
			if( is_numeric( $inicio_coma) &&  ($inicio_coma >= 1) &&  ($inicio_coma < strlen($numero) ) )
				$numero =  substr($numero, 0,  ($inicio_coma+3)); 
			//return str_replace(".", ",",$numero);
			return $numero;
		}
		public function calcular_valores_a_facturar($PlanMedidor_MontoCuota, $PlanPago_MontoCuota,$Conexion_Deuda,$Conexion_Acuenta,$datos, $Conexion_Bonificacion, $Bonificacion_Monto, $excdenete,$tipo, $cone, $riego)
		{
			$data["monto_cuota_medidor"] = $PlanMedidor_MontoCuota ;
			if($data["monto_cuota_medidor"] == null)
				$data["monto_cuota_medidor"] = 0;
			$data["monto_cuota_plan_pago"] = $PlanPago_MontoCuota ;
			if($data["monto_cuota_plan_pago"] == null)
				$data["monto_cuota_plan_pago"] = 0;
			$precio_riego = 0;
			if($tipo == "Familiar")
				$tarifa_social = $datos[4]->Configuracion_Valor ;
			else $tarifa_social = $datos[7]->Configuracion_Valor;
			if($riego == "1")
				$precio_riego = floatval ($datos[17]->Configuracion_Valor);
			// if($tipo == "Familiar")
			// {
			// 	$data["subtotal_sin_bonificacion"] = $Conexion_Deuda + 
			// 			$tarifa_social + 
			// 			floatval ($excdenete)  +   
			// 			floatval ($datos[2]->Configuracion_Valor) +
			// 			$data["monto_cuota_medidor"]  +
			// 			$data["monto_cuota_plan_pago"] +$precio_riego;
			// }
			// else
			// {
				$data["subtotal_sin_bonificacion"] = $Conexion_Deuda + 
						$tarifa_social  + 
						floatval ($excdenete)  +   
						floatval ($datos[2]->Configuracion_Valor) +
						$data["monto_cuota_medidor"]  +
						$data["monto_cuota_plan_pago"] +$precio_riego;
			//}
			if($Conexion_Deuda == 0)//aplico descuento en el subtotal
				//$data["subtotal_con_bonificacion"]= floatval($data["subtotal_sin_bonificacion"]) * floatval(0.995) ;//con bonificacion
				$data["subtotal_con_bonificacion"] = floatval($data["subtotal_sin_bonificacion"]) - ( (floatval ($excdenete) + floatval($tarifa_social)) * floatval(0.995) ) ;//con bonificacion
			else $data["subtotal_con_bonificacion"] = floatval($data["subtotal_sin_bonificacion"]); // sin bonificacion
				$data["total"] = floatval( $data["subtotal_con_bonificacion"]) - floatval($Conexion_Acuenta) ;
			/*
			AL TOTAL NO LE RESTO LA BONIFICACION; XQ ESTA AFECTA DIRECTAMENTE A LA DEUDA
			ASIQUE CUANDO CREE UNA BONIFICACION NUEVA, VOY A DESCONTAR LA DEUDA DE LA CONEXION DIRECTAMENTE
			ASIQ AHORA ESTARIA CALCULANDO EL TOTAL CON EL DESCUENTO YA HECHO EN LA DEUDA, Y SIMPLEMENTE LO ESCRIBO EN LA BOLETA*/
			// if(isset( $key->Bonificacion_Monto) && (is_numeric( $key->Bonificacion_Monto)) &&  $key->Bonificacion_Monto> 0)
			//       $data["total"] = floatval($data["total"]) - floatval( $key_boleta->Bonificacion_Monto);
			//$data["total"] = $this->arreglar_numero($data["total"]);
			//var_dump($data["total"]);die();
			$data["subtotal_con_bonificacion"] = $this->arreglar_numero($data["subtotal_con_bonificacion"]);
			$data["subtotal_sin_bonificacion"] = $this->arreglar_numero($data["subtotal_sin_bonificacion"]);  
			$data["monto_cuota_medidor"] = $this->arreglar_numero($data["monto_cuota_medidor"]);  
			$data["monto_cuota_plan_pago"] = $this->arreglar_numero($data["monto_cuota_plan_pago"]);  
			return $data;
		}





		//get todo
		public function get_todo() 
		{
			$arrayName [0] = array("nombre" => "inicio_mes", "valor" => $this->inicio_mes);
			$arrayName [1] = array("nombre" => 'fin_mes', "valor" =>  $this->fin_mes);
			$arrayName [2] = array("nombre" => 'tarifa_social', "valor" =>  $this->tarifa_social);
			$arrayName [3] = array("nombre" => 'precio_mt_fam', "valor" =>  $this->precio_mt_fam);
			$arrayName [4] = array("nombre" => 'tarifa_familiar', "valor" =>  $this->tarifa_familiar);
			$arrayName [5] = array("nombre" => 'mts_basicos_familiar', "valor" =>  $this->mts_basicos_familiar);
			$arrayName [6] = array("nombre" => 'precio_mt_com', "valor" =>  $this->precio_mt_com);
			$arrayName [7] = array("nombre" => 'tarifa_comercial', "valor" =>  $this->tarifa_comercial);
			$arrayName [8] = array("nombre" => 'mts_basicos_comercio', "valor" =>  $this->mts_basicos_comercio);
			$arrayName [9] = array("nombre" => 'cuota_social', "valor" =>  $this->cuota_social);
			$arrayName [10] = array("nombre" => 'color_pago', "valor" =>  $this->color_pago);
			$arrayName [11] = array("nombre" => 'puesto', "valor" =>  $this->puesto);
			$arrayName [12] = array("nombre" => 'hoy', "valor" =>  $this->hoy);
			$arrayName [13] = array("nombre" => 'hora', "valor" =>  $this->hora);
			$arrayName [14] = array("nombre" => 'cantidad_disponible', "valor" =>  $this->cantidad_disponible);
			$arrayName [15] = array("nombre" => 'cantidad_digitos', "valor" =>  $this->cantidad_digitos);
			return $arrayName;
		}


		public function corregir_boleta_por_id($id)
		{
			$this->load->model('Nuevo_model');
			$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
			$mediciones_desde_query = $this->Nuevo_model->get_data_dos_campos_con_conexion("facturacion_nueva","Factura_Id", $id,"Factura_Borrado",0);
			//$mediciones_desde_query tiene solamente [0], osea q se ejecuta el foreach una sola vez
			$indice_actual = 0;
			foreach ($mediciones_desde_query as $key ) 
			{
				if( ($key->Conexion_Categoria == 1) || ($key->Conexion_Categoria == "Familiar") || ($key->Conexion_Categoria =="Familiar ") )
				{
					$precio_metros = $todas_las_variables[3]->Configuracion_Valor;
					$metros_basicos = $todas_las_variables[5]->Configuracion_Valor;
					$precio_bsico = $todas_las_variables[4]->Configuracion_Valor;
				}
				else 
				{
					$precio_metros = $todas_las_variables[6]->Configuracion_Valor;
					$metros_basicos = $todas_las_variables[8]->Configuracion_Valor;
					$precio_bsico = $todas_las_variables[7]->Configuracion_Valor;
				}
				$anterior = $key->Factura_MedicionAnterior;
				$actual = $key->Factura_MedicionActual;
				$inputExcedente = intval($actual) - intval($anterior) - intval($metros_basicos);
				if($inputExcedente < 0 )
					$inputExcedente = 0;
				$importe_medicion = 0;
				if($inputExcedente == 0)
					$importe_medicion = 0;
				else $importe_medicion = floatval($precio_metros) * floatval($inputExcedente);
				//calculo el subtotal y total
				$sub_total = floatval($key->Factura_TarifaSocial) 
										+ floatval($key->Factura_Deuda)
										+ floatval($key->Factura_ExcedentePrecio )
										+ floatval($key->Factura_CuotaSocial )
										+ floatval($key->Factura_Riego )
										+ floatval($key->Factura_PM_Cuota_Precio)
										+ floatval($key->Factura_PPC_Precio)
										+ floatval($key->Factura_Multa)
										;
				$total  = $sub_total;
				$bonificacion = 0;
				if($key->Factura_Deuda == 0)
						//$bonificacion_pago_puntual =  (floatval ($excedente) + floatval($tarifa_basica)) * floatval (5) / floatval(100) ;//con bonificacion
						$bonificacion = (floatval ($inputExcedente) + floatval($key->Factura_TarifaSocial)) * floatval (5) / floatval(100) ;//con bonificacion
				$total =	floatval($total)
										- floatval($key->Factura_Acuenta)
										- floatval($bonificacion);
				//vtos
				$vto_2_precio = floatval($total) + floatval($total) * floatval($todas_las_variables[18]->Configuracion_Valor);
				$vto_1_precio = $total;
				$indice_actual++;
				$datos_factura_nueva = array(
					'Factura_SubTotal' => floatval($sub_total),
					'Factura_Bonificacion' => floatval($bonificacion),
					'Factura_Total' => floatval($total),
					'Factura_Vencimiento1_Precio' => floatval($vto_1_precio),
					'Factura_Vencimiento2_Precio' => floatval($vto_2_precio),
					'Factura_ExcedentePrecio' => floatval($importe_medicion),
					'Factura_Excedentem3' => $inputExcedente
					 );
				$resultado[$indice_actual] =  $this->Nuevo_model->update_data($datos_factura_nueva, $key->Factura_Id, "facturacion_nueva","Factura_Id");
				//var_dump($datos_factura_nueva,$key->Conexion_Id);
				return true;
			}
		}



	/**
	 * Prefix with an underscore if you don't want it
	 * publicly available through URI-routing
	 */
	// public function _some_shared_method()
	// {
	//     // some common operation here
	// }

}

/*

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Controller extends CI_Controller
{
public $unidad_tributaria;
public $clima;
public $clima_actualizacion;
public $inicio_mes;
public $fin_mes;
public $incio_mes_personal;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('mensaje_model');
		$this->load->model('clima_model');
		$this->load->model('notificaciones_model');
		$this->unidad_tributaria = 1.6;
		$this->inicio_mes="2016/04/23";
		$this->fin_mes="2016/05/24";
		//$this->fin_mes="2016/05/01";
		$this->clima="Nublado";
		$this->clima_actualizacion="07:52:00";
	}
	public function get_disp()
	{ 
		$disposicion_final_activa=true;
		return $disposicion_final_activa;
	}
	public function get_clima()
	{ 
		$clima="Nub";
		return $disposicion_final_activa;
	}
	public function set_clima($valor)
	{ 
		switch ($valor) {
			case '1':
				$clima = "nublado";
				break;
			case '2':
				$clima = "llovisna";
				break;
			case '3':
				$clima = "lluvia";
				break;
			case '4':
				$clima = "nublado_s_s";
				break;
			case '5':
				$clima = "soleado";
				break;
			case '6':
				$clima = "tornado";
				break;
			case '7':
				$clima = "viento";
				break;
			default:
				$clima = "viento";
				break;
		}
		return $clima;
	}
	public function set_clima_actualizacion()
	{ 
		$this->clima_actualizacion = date("d/m/Y H:m:s");
		return $this->clima_actualizacion;
	}
	public function ultimos_mensajes()
	{
		return $this->mensaje_model->ultimos_mensajes();
		
	}
	public function num_mensajes()
	{
		return $this->mensaje_model->num_mensajes();
		
	}
	public function notificaciones_g()
	{
		return $this->notificaciones_model->buscar_novedades_para_generador($this->session->userdata('id_ajeno'));
	}
	public function notificaciones_t()
	{
		return $this->notificaciones_model->buscar_novedades_para_transportista($this->session->userdata('id_ajeno'));	
	}
	public function notificaciones_generadoes()
	{
		return $this->notificaciones_model->buscar_generadores();
	}
	public function solicitud_cancelada_generador()
	{
		
		return $this->notificaciones_model->buscar_generadores();
	}

	public function notificaciones_transportista()
	{
		return $this->notificaciones_model->buscar_transportistas();
	}
	public function todo_generador()
	{
		$data["mensajes"] = $this->ultimos_mensajes();
		$data['num_mens'] = $this->num_mensajes();
		$data ['novedades'] = $this->notificaciones_g();
		$data ['novedades_generadores'] = $this->notificaciones_generadoes();
		$data ['novedades_transportistas'] = $this->notificaciones_transportista();
		$data ['clima'] = $this->clima_model->traer_clima();
		$this->removeCache();
		return $data;
	}
	public function todo_bascula()
	{
		$data["mensajes"] = $this->ultimos_mensajes();
		$data['num_mens'] = $this->mensaje_model->num_mensajes();
		/*Faltan notificaciones en bascula
		$data ['clima'] = $this->clima_model->traer_clima();
		$this->removeCache();
		return $data;
	}
	public function todo_admin()
	{
		$data["mensajes"] = $this->ultimos_mensajes();
		$data['num_mens'] = $this->num_mensajes();
		
		$data ['novedades'] = $this->notificaciones_model->buscar_novedades_para_admi();
		$data ['novedades_generadores'] = $this->notificaciones_generadoes();
		$data ['novedades_transportistas'] = $this->notificaciones_transportista();
		$data ['clima'] = $this->clima_model->traer_clima();
		return $data;
	}
	public function todo_transportista()
	{
		$data["mensajes"] = $this->ultimos_mensajes();
		$data['num_mens'] = $this->num_mensajes();
		$data ['novedades'] = $this->notificaciones_t();
		$data ['novedades_generadores'] = $this->notificaciones_generadoes();
		$data ['novedades_transportistas'] = $this->notificaciones_transportista();
		$data ['clima'] = $this->clima_model->traer_clima();
		$this->removeCache();
		return $data;
	}
	public function todo_administrativo()
	{
		if($this->session->userdata('perfil') == 'administrador')
		{
			$data = $this->todo_admin();
			$this->load->view("admin/links_administrador.php",$data);
		}
		else
		{
			$data["mensajes"] = $this->ultimos_mensajes();
			$data['num_mens'] = $this->num_mensajes();
			/*$data ['novedades'] = $this->notificaciones_administrativo();
			$data ['novedades_generadores'] = $this->notificaciones_generadoes();
			$data ['novedades_transportistas'] = $this->notificaciones_transportista();
			$this->removeCache();
			$this->load->view("administrativo/links_administrativo.php",$data);
		}
	}

	public function cargar_menu()
	{
		if($this->session->userdata('perfil') == 'administrador')
		{
			$data = $this->todo_admin();
			$this->load->view("admin/links_administrador.php",$data);
		}
		elseif($this->session->userdata('perfil') == 'administrativo')
		{
			$data = $this->todo_administrativo();
			$this->load->view("administrativo/links_administrativo.php",$data);
		}
		elseif($this->session->userdata('perfil') == 'generador')
		{
			$data = $this->todo_generador();
			$this->load->view("generador/links_generador.php",$data);
		}
		elseif($this->session->userdata('perfil') == 'transportista')
		{
			$data = $this->todo_transportista();
			$this->load->view("transportista/links_transportista_view.php",$data);
		}
		elseif($this->session->userdata('perfil') == 'bascula')
		{
			$data = $this->todo_bascula();
			$this->load->view("bascula/links_bascula.php",$data);
		}



	}

	public function error($id,$tipo)
	{
		$data["id"]= $id;
		$data["tipo"] = $tipo;
		$this->load->view("error_val_view",$data);

	}

	public function calcular_duedua($ordenes)
	{
		$cantidad =0;
		$deuda = 0;
		$peso =0;
		$arrayName = array('0' => 0,
		'1' => 0,
		'2' => 0,
		'3' => 0,
		'4' => 0
		 );
		foreach ($ordenes as $key) {
			$cantidad++;
			//saco el peso neto
			//$peso_aux = $key->toneladas_observacion;

			//funcion para sacar el peso neto de las toneladas con formato asdaisodaos*asdasdasd*xxxx,uyyy
			$fin=(strlen($key->toneladas_observacion))-1;
			while($key->toneladas_observacion[$fin]!='*')
				$fin--;
			$aux_t=substr($key->toneladas_observacion,($fin+1));
			$peso += $aux_t;
			$key->toneladas_observacion = $aux_t;
			//fin peso

			//dispsiciones
			$arrayName [$key->disposicion]++;

		}

		$dueda = $peso *1.64;
		//var_dump($peso,$arrayName, $dueda);die();

return
 array( 'disp' => $arrayName,
		'deuda' => $deuda,
		'cantidad'=>$cantidad,
		'peso' => $peso
		 ); 
	   
	}



	


	public function removeCache()
	{
		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
	}
}

class MY_funciones extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		// check if logged_in
   }

   public function removeCache()
	{
		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
	}
	
}*/
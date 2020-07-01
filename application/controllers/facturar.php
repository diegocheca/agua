<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facturar extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Facturar_model');
		$this->load->model('Crud_model');
	}

	//Pagina Muestra Facturas Realizadas
	public function index(){

		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Documentos', '/documentos');
			$datos['bread']=$this->breadcrumbs->show();// salida

			$segmentos_totales=$this->uri->total_segments();

			$datos['segmentos']=$segmentos_totales;
			$datos['titulo'] = "Documentos Creados";
			$datos['documentos'] = $this->Facturar_model->listar_documentos();
			$this->load->view('templates/header', $datos);

			$data ['mensaje'] = $this->session->flashdata('aviso');
			if($data ['mensaje'] != null)// hay aviso
			{
				$data ['tipo'] = $this->session->flashdata('tipo_aviso');
				if($data ['tipo'] == "success")
				{
					$this->load->view("templates/notificacion_correcta_success", $data);
				}
				else
					$this->load->view("templates/notificacion_incorrecta_success", $data);
			}

			$this->load->view('factura/documentos');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');

			$data ['aviso'] = $this->session->flashdata('aviso');
			//var_dump($data ['aviso']);
			if($data ['aviso'] != null)
			{
				//echo "estoy llamando";
				$this->load->view("templates/notificacion_view", $data);
			}
		endif;
	}

	// Pagina de Facturación
	public function crear(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Documentos', '/documentos');
			$this->breadcrumbs->push('Agregar Factura', '/facturar');
			$datos['bread']=$this->breadcrumbs->show();// salida


			$segmentos_totales=$this->uri->total_segments();

			$datos['segmentos']=$segmentos_totales;
			$datos['productos']=$this->Facturar_model->get_productos('productos');
			$datos['titulo']= "Crear Documento";
			$this->load->view('templates/header',$datos);
			$this->load->view('factura/crear_factura');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}

	public function crear_nueva(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Documentos', '/documentos');
			$this->breadcrumbs->push('Agregar Factura', '/facturar');
			$datos['bread']=$this->breadcrumbs->show();// salida
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			$datos['productos']=$this->Facturar_model->get_productos('productos');
			$datos['titulo']= "Crear Documento";
			$this->load->view('templates/header',$datos);
			$this->load->view('factura/crear_factura_nueva');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}

	public function crear_factor_mes_actual(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Documentos', '/documentos');
			$this->breadcrumbs->push('Agregar Factura', '/facturar');
			$datos['bread']=$this->breadcrumbs->show();// salida
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			$datos['productos']=$this->Facturar_model->get_productos('productos');
			$datos['titulo']= "Crear Documento";
			$this->load->view('templates/header',$datos);
			$this->load->view('factura/crear_factura_nueva');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}

	public function editar_factura($id_doc){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtenemos el numero de id del documento que se quiere editar
			$datosfactura	=	$this->Crud_model->get_data_row('facturacion','id',$id_doc);
			$datoscliente	=	$this->Crud_model->get_data_row('clientes','id',$datosfactura->id_cliente);
			$val['items']	=	$this->Crud_model->get_data_result('items','id_factura',$datosfactura->id_factura);
			$val['valores']	=	$datosfactura;
			$val['cliente']	=	$datoscliente;
			
			$datos['titulo']= "Editar Documento";
			$this->load->view('templates/header',$datos);
			$this->load->view('editar/editar_documento',$val);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}

	//anular facturas con ajax
	public function anular_doc(){
		$id=$this->input->post('id');
		$estado=$this->input->post('estado');
		$datos = array(
			'estado' => $estado
		);
		$this->Facturar_model->actualizar_documento($id,$datos);
	}
	//anular facturas con ajax
	public function hola($valor){
		$data = $this->Facturar_model->buscar_mediciones_para_una_conexion($valor);
		
		var_dump($data );
	}

	public function buscar_datos_crear_factura(){

		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:

			header('Content-Type: application/json');
			$valor=$_GET['query'];  //captura la variable que pasa el autocomplete
			//var_dump($valor);
			$data = $this->Facturar_model->buscar_datos_crear_factura($valor);
			// for ($i=0; $i < sizeof($data) ; $i++) { 
			// 	$medicion = $this->Facturar_model->buscar_mediciones_para_una_conexion($data[$i]->Conexion_Id);
			// 	if($medicion == false)
			// 	{
			// 		$data[$i]->medicion_id = false;
			// 		$data[$i]->medicion_mes = false;
			// 		$data[$i]->medicion_anio = false;
			// 		$data[$i]->medicion_anterior = false;
			// 		$data[$i]->medicion_actual = false;
					
			// 		$data[$i]->medicion_excedente = false;
			// 		$data[$i]->medicion_importe = false;
			// 		$data[$i]->medicion_bascio = false;
			// 		$data[$i]->medicion_mts = false;

			// 	}
			// 	else
			// 	{
			// 		$data[$i]->medicion_id = $medicion["Medicion_Id"];
			// 		$data[$i]->medicion_mes = $medicion["Medicion_Mes"];
			// 		$data[$i]->medicion_anio = $medicion["Medicion_Anio"];
			// 		$data[$i]->medicion_anterior = $medicion["Medicion_Anterior"];
			// 		$data[$i]->medicion_actual = $medicion["Medicion_Actual"];
					
			// 		$data[$i]->medicion_excedente = $medicion["Medicion_Excedente"];
			// 		$data[$i]->medicion_importe = $medicion["Medicion_Importe"];
			// 		$data[$i]->medicion_bascio = $medicion["Medicion_Basico"];
			// 		$data[$i]->medicion_mts = $medicion["Medicion_Mts"];
			// 	}
			// }
			$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
			$clientes = array(); //creamos un array
			foreach($data as $columna) 
			{
				//$medicion = NULL;
				$medicion = $this->Facturar_model->buscar_mediciones_para_una_conexion($columna->Conexion_Id);
				if($medicion == false)
				{
					$medicion =  new \stdClass();
					$medicion->Medicion_Id= false;
					$medicion->Medicion_Mes= false;
					$medicion->Medicion_Anio= false;
					$medicion->Medicion_Anterior= false;
					$medicion->Medicion_Actual= false;
					$medicion->Medicion_Excedente= false;
					$medicion->Medicion_Importe= false;
					$medicion->Medicion_Basico= false;
					$medicion->Medicion_Mts= false;
				}
				//var_dump($medicion->Medicion_Id);
				$id	=	$columna->Cli_Id;
				$razon=$columna->Cli_RazonSocial;
				$documento=$columna->Cli_NroDocumento;
				$direccion=$columna->Cli_DomicilioPostal;
				$direccion_conexion=$columna->Conexion_DomicilioSuministro;

				$sector=$columna->Conexion_UnionVecinal;
				$id_conexion=$columna->Conexion_Id;
				$categoria =$columna->Conexion_Categoria;
				$deuda=$columna->Conexion_Deuda;
				$tarifa_familiar = $datos["configuracion"][4]->Configuracion_Valor; //tarifa basica tarifa_familiar;
				$tarifa_social = $datos["configuracion"][2]->Configuracion_Valor;
				$tarifa_comercial =  $datos["configuracion"][7]->Configuracion_Valor;
				$datos_aux = $this->Crud_model->buscar_medicion_anterior($columna->Conexion_Id);
				if($datos_aux != false)
					$excedente = $datos_aux->Medicion_Importe;
				else// =false
					$excedente = null;
				$plan_pago_id=$columna->PlanPago_Id;
				if($plan_pago_id>0)
				{
				   	if($columna->PlanPago_CoutaActual< $columna->PlanPago_Coutas)
				   	{
				   		$plan_pago_couta_acutal = $columna->PlanPago_CoutaActual;
				   		$plan_pago_couta_total = $columna->PlanPago_Coutas;
				   		$plan_pago_monto_cuota=$columna->PlanPago_MontoCuota;
				   	}
				   	else
				   	{
				   		$plan_pago_couta_acutal = null;
				   		$plan_pago_couta_total = null;
				   		$plan_pago_monto_cuota= null;
				   	}
				}
				else
				{
					$plan_pago_couta_acutal = null;
					$plan_pago_couta_total = null;
					$plan_pago_monto_cuota= null;
				}
				$plan_medidor_id=$columna->PlanMedidor_Id;
				if($plan_medidor_id>0)
				{
				   	if($columna->PlanMedidor_CoutaActual< $columna->PlanMedidor_Coutas)
				   	{
				   		$plan_medidor_couta_acutal = $columna->PlanMedidor_CoutaActual;
				   		$plan_medidor_couta_total = $columna->PlanMedidor_Coutas;
				   		$plan_medidor_monto_cuota=$columna->PlanMedidor_MontoCuota;
				   	}
				   	else
				   	{
				   		$plan_medidor_couta_acutal = null;
				   		$plan_medidor_couta_total = null;
				   		$plan_medidor_monto_cuota= null;
				   	}
				}
				else
				{
					$plan_medidor_couta_acutal = null;
					$plan_medidor_couta_total = null;
					$plan_medidor_monto_cuota= null;
				}
				if($columna->Conexion_Bonificacion ==  floatval(1))
					$bonificacion = 0.05;
				elseif($columna->Conexion_Bonificacion == floatval(2))
					$bonificacion = 0.1;
				else
					$bonificacion = 0;
				//PAGO A CUENTA
				$pago_a_cuenta = $columna->Conexion_Acuenta;
				if($pago_a_cuenta == null)
					$pago_a_cuenta =0;
				$clientes[] = array(
					'value'=> $razon." / ".$direccion_conexion, 
					'data' => $id, 
					'nro_documento' => $documento,
					'sector' => $sector, 
					'id_conexion' => $id_conexion,
					'deuda' => $deuda,
					'categoria' => $categoria,
					'tarifa_familiar' => $tarifa_familiar,
					'tarifa_social' => $tarifa_social,
					'tarifa_comercial' => $tarifa_comercial,
					'excedente' => $excedente,
					'plan_pago_couta_acutal' => $plan_pago_couta_acutal,
					'plan_pago_couta_total' =>  $plan_pago_couta_total,
					'plan_pago_monto_cuota' =>  $plan_pago_monto_cuota,

					'plan_medidor_couta_acutal' => $plan_medidor_couta_acutal,
					'plan_medidor_couta_total' =>  $plan_medidor_couta_total,
					'plan_medidor_monto_cuota' =>  $plan_medidor_monto_cuota,

					'medicion_id' => $medicion->Medicion_Id,
					'medicion_mes' =>  $medicion->Medicion_Mes,
					'medicion_anio' =>  $medicion->Medicion_Anio,
					'medicion_anteriorl' => $medicion->Medicion_Anterior,
					'medicion_actual' =>  $medicion->Medicion_Actual,
					'medicion_excedente' =>  $medicion->Medicion_Excedente,
					'medicion_importel' => $medicion->Medicion_Importe,
					'medicion_bascio' =>  $medicion->Medicion_Basico,
					'medicion_mts' =>  $medicion->Medicion_Mts,

					'direccion' => $direccion,
					'pago_a_cuenta' => $pago_a_cuenta,
					'bonificacion' => $bonificacion
				);
			}

			$array = array("query" => "Unit", "suggestions" => $clientes);
			echo json_encode($array);
		endif;

	}





	public function buscar_factura(){

		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			header('Content-Type: application/json');
			$valor=$_GET['query'];  //captura la variable que pasa el autocomplete
			//var_dump($valor);
			$data=$this->Facturar_model->get_factura_id($valor);
			$facturas = array(); //creamos un array
			//var_dump($data);die();

			if(($data != false)&&($data != null) && ($data != 0))
			{
				foreach($data as $columna) { 
					$mes = substr($columna->Factura_Periodo,5,2);
					$anio = substr($columna->Factura_Periodo, 0,4);
					$id	=	$columna->id;
					$id_factura	=	$columna->id_factura;
					$id_cliente	=	$columna->Cli_Id;
					$cate	=	$columna->Conexion_Categoria;
					$con_id	=	$columna->Conexion_Id;
					$actual	=	$columna->Medicion_Actual;
					$anterior	=	$columna->Medicion_Anterior;
					$basico	=	$columna->Medicion_Basico;
					$excedente	=	$columna->Medicion_Excedente;
					$total	=	$columna->Medicion_Mts;

					if($cate == 1) //familiar
						$excedente_calculado	=	($columna->Medicion_Excedente) * intval(precio_mt_fam);
					elseif($cate == 2)
						$excedente_calculado	=	($columna->Medicion_Excedente) * intval(precio_mt_com);


				   	$razon=$columna->Cli_RazonSocial;
				   	$serie=$columna->serie;
				   	$correlativo=$columna->correlativo;
				   	$documento=$columna->Cli_NroDocumento;
				   	$fechaEmision=$columna->fecha_emision;
				   	$facturavencimiento1=$columna->Factura_Vencimiento1;
				   	$facturavencimiento2=$columna->Factura_Vencimiento2;
				   	$monto_fac=$columna->monto;
				   	$conexion_deuda=$columna->Conexion_Deuda;


				   	$subtotal=$columna->Factura_SubTotal;
				   	$total =$columna->Factura_Total;

				   		
				   		
				   	$plan_pago_id=$columna->PlanPago_Id;
				   	if($plan_pago_id>0)
				   	{
					   	if($columna->PlanPago_CoutaActual< $columna->PlanPago_Coutas)
					   	{
					   		$plan_pago_couta_acutal = $columna->PlanPago_CoutaActual;
					   		$plan_pago_couta_total = $columna->PlanPago_Coutas;
					   		$plan_pago_monto_cuota=$columna->PlanPago_MontoCuota;
					   	}
					   	else
					   	{
					   		$plan_pago_couta_acutal = null;
					   		$plan_pago_couta_total = null;
					   		$plan_pago_monto_cuota= null;

					   	}
				   	}
				   	else
					{
						$plan_pago_couta_acutal = null;
						$plan_pago_couta_total = null;
						$plan_pago_monto_cuota= null;
					}

					$plan_medidor_id=$columna->PlanMedidor_Id;
				   	if($plan_medidor_id>0)
				   	{
					   	if($columna->PlanMedidor_CoutaActual< $columna->PlanMedidor_Coutas)
					   	{
					   		$plan_medidor_couta_acutal = $columna->PlanMedidor_CoutaActual;
					   		$plan_medidor_couta_total = $columna->PlanMedidor_Coutas;
					   		$plan_medidor_monto_cuota=$columna->PlanMedidor_MontoCuota;
					   	}
					   	else
					   	{
					   		$plan_medidor_couta_acutal = null;
					   		$plan_medidor_couta_total = null;
					   		$plan_medidor_monto_cuota= null;

					   	}
				   	}
				   	else
					{
						$plan_medidor_couta_acutal = null;
						$plan_medidor_couta_total = null;
						$plan_medidor_monto_cuota= null;
					}

				   	$direccion=$columna->Cli_DomicilioPostal;
					$facturas[] = array(
					   	'value'=> $razon, 
					   	'data' => $id_factura, 
					   	'id' => $id, 
					   	'id_cli' => $id_cliente, 
					   	'serie' => $serie, 
					   	'correlativo' => $correlativo, 
					   	'nro_documento' => $documento, 
					   	'fechaEmision' => $fechaEmision, 
					   	'fechaVenci' => $facturavencimiento1,
					   	'fechaVencidos' => $facturavencimiento2,
					   	'monto' => $monto_fac,
					   	'mes' => $mes,
					   	'anio' => $anio,
					   	'categoria' => $cate,
					   	'conexion' => $con_id,
					   	'conexion_deuda' => $conexion_deuda,
					   	'actual' => $actual,
					   	'anterior' => $anterior,
					   	'basico' => $basico,
					   	'excedente' => $excedente,
					   	//'total' => $total,
					   	'tarifa' => tarifa_social,
					   	'excedente_calculado' => $excedente_calculado,
					   	'cuota_social' => cuota_social,
					   	'planpago_acutal' => $plan_pago_couta_acutal,
					   	'planpago_total' => $plan_pago_couta_total,
					   	'planpago_precio_cuota' => $plan_pago_monto_cuota,
					   	'cuota_social' => cuota_social,
					   	'total_aPagar' => $total,
					   	'subtotal_aPagar' => $subtotal,

					   	'id_planpago' => $plan_pago_id,
					   	'id_planmedidor' => $plan_medidor_id,

					   	'planmedidor_actual' => $plan_medidor_couta_acutal,
					   	'planmedidor_total' => $plan_medidor_couta_total,
					   	'planmedidor_precio_cuota' => $plan_medidor_monto_cuota,

					   	'direccion' => $direccion
					   );
				}
			}

			$array = array("query" => "Unit", "suggestions" => $facturas);
			echo json_encode($array);
		endif;
	}


	public function buscar_datos_factura($codigo){

		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$data=$this->Facturar_model->get_datos_factura_con_codigo($codigo);
			$facturas = array(); //creamos un array

			if(($data != false)&&($data != null) && ($data != 0))
			{
				foreach($data as $columna) 
				{ 
					$mes = substr($columna->Factura_Periodo,5,2);
					$anio = substr($columna->Factura_Periodo, 0,4);
					$id	=	$columna->id;
					$id_factura	=	$columna->id_factura;
					$id_cliente	=	$columna->Cli_Id;
					$dni	=	$columna->Cli_Id;
					$cate	=	$columna->Conexion_Categoria;
					$con_id	=	$columna->Conexion_Id;
					$actual	=	$columna->Medicion_Actual;
					$anterior	=	$columna->Medicion_Anterior;
					$basico	=	$columna->Medicion_Basico;
					$excedente	=	$columna->Medicion_Excedente;
					$total	=	$columna->Medicion_Mts;

					if($cate == 1) //familiar
						$excedente_calculado	=	($columna->Medicion_Excedente) * intval(precio_mt_fam);
					elseif($cate == 2)
						$excedente_calculado	=	($columna->Medicion_Excedente) * intval(precio_mt_com);


				   	$razon=$columna->Cli_RazonSocial;
				   	$serie=$columna->serie;
				   	$correlativo=$columna->correlativo;
				   	$documento=$columna->Cli_NroDocumento;
				   	$fechaEmision=$columna->fecha_emision;
				   	$facturavencimiento1=$columna->Factura_Vencimiento1;
				   	$facturavencimiento2=$columna->Factura_Vencimiento2;
				   	$monto_fac=$columna->monto;
				   	$conexion_deuda=$columna->Conexion_Deuda;


				   	$subtotal=$columna->Factura_SubTotal;
				   	$total =$columna->Factura_Total;

				   		
				   		
				   	$plan_pago_id=$columna->PlanPago_Id;
				   	if($plan_pago_id>0)
				   	{
					   	if($columna->PlanPago_CoutaActual< $columna->PlanPago_Coutas)
					   	{
					   		$plan_pago_couta_acutal = $columna->PlanPago_CoutaActual;
					   		$plan_pago_couta_total = $columna->PlanPago_Coutas;
					   		$plan_pago_monto_cuota=$columna->PlanPago_MontoCuota;
					   	}
					   	else
					   	{
					   		$plan_pago_couta_acutal = null;
					   		$plan_pago_couta_total = null;
					   		$plan_pago_monto_cuota= null;

					   	}
				   	}
				   	else
					{
						$plan_pago_couta_acutal = null;
						$plan_pago_couta_total = null;
						$plan_pago_monto_cuota= null;
					}

					$plan_medidor_id=$columna->PlanMedidor_Id;
				   	if($plan_medidor_id>0)
				   	{
					   	if($columna->PlanMedidor_CoutaActual< $columna->PlanMedidor_Coutas)
					   	{
					   		$plan_medidor_couta_acutal = $columna->PlanMedidor_CoutaActual;
					   		$plan_medidor_couta_total = $columna->PlanMedidor_Coutas;
					   		$plan_medidor_monto_cuota=$columna->PlanMedidor_MontoCuota;
					   	}
					   	else
					   	{
					   		$plan_medidor_couta_acutal = null;
					   		$plan_medidor_couta_total = null;
					   		$plan_medidor_monto_cuota= null;

					   	}
				   	}
				   	else
					{
						$plan_medidor_couta_acutal = null;
						$plan_medidor_couta_total = null;
						$plan_medidor_monto_cuota= null;
					}

				   	$direccion=$columna->Cli_DomicilioPostal;
					$facturas[] = array(
					   	'value'=> $razon, 
					   	'data' => $id_factura, 
					   	'id' => $id, 
					   	'id_cli' => $id_cliente, 
					   	'dni' => $id_cliente, 
					   	'serie' => $serie, 
					   	'correlativo' => $correlativo, 
					   	'nro_documento' => $documento, 
					   	'fechaEmision' => $fechaEmision, 
					   	'fechaVenci' => $facturavencimiento1,
					   	'fechaVencidos' => $facturavencimiento2,
					   	'monto' => $monto_fac,
					   	'mes' => $mes,
					   	'anio' => $anio,
					   	'categoria' => $cate,
					   	'conexion' => $con_id,
					   	'conexion_deuda' => $conexion_deuda,
					   	'actual' => $actual,
					   	'anterior' => $anterior,
					   	'basico' => $basico,
					   	'excedente' => $excedente,
					   	//'total' => $total,
					   	'tarifa' => tarifa_social,
					   	'excedente_calculado' => $excedente_calculado,
					   	'cuota_social' => cuota_social,
					   	'planpago_acutal' => $plan_pago_couta_acutal,
					   	'planpago_total' => $plan_pago_couta_total,
					   	'planpago_precio_cuota' => $plan_pago_monto_cuota,
					   	'cuota_social' => cuota_social,
					   	'total_aPagar' => $total,
					   	'subtotal_aPagar' => $subtotal,

					   	'id_planpago' => $plan_pago_id,
					   	'id_planmedidor' => $plan_medidor_id,

					   	'planmedidor_actual' => $plan_medidor_couta_acutal,
					   	'planmedidor_total' => $plan_medidor_couta_total,
					   	'planmedidor_precio_cuota' => $plan_medidor_monto_cuota,

					   	'direccion' => $direccion
					   );
				}
				return $facturas;
			}
			return false;

		endif;
	}

	//Actualiza un documento pasando el id
	public function actualizar_documento($id){

		// Campos para tabla de Facturacion
		// Recibe el Valor de los Campos por post, asignadas a una varibale
		$tipodoc=$this->security->xss_clean(strip_tags($this->input->post('tipodoc')));
		$serie=$this->security->xss_clean(strip_tags($this->input->post('serie')));
		$correlativo=$this->security->xss_clean(strip_tags($this->input->post('correlativo')));
		$fechaEmision=$this->security->xss_clean(strip_tags($this->input->post('fechaEmision')));
		$fechaCancelacion=$this->security->xss_clean(strip_tags($this->input->post('fechaCancelacion')));
		$tipopago=$this->security->xss_clean(strip_tags($this->input->post('tipopago')));
		$moneda=$this->security->xss_clean(strip_tags($this->input->post('moneda')));
		$idcliente=$this->security->xss_clean(strip_tags($this->input->post('idcliente')));
		$cliente=$this->security->xss_clean(strip_tags($this->input->post('cliente')));
		$precio_total=$this->security->xss_clean(strip_tags($this->input->post('total')));
		$direccion=$this->security->xss_clean(strip_tags($this->input->post('direccion')));
		$ruc=$this->security->xss_clean(strip_tags($this->input->post('ruc')));
		$igv=$this->security->xss_clean(strip_tags($this->input->post('igv')));

		//codigo unico
		$codigounico=$serie.$correlativo;

		//Campos para tabla producto
		$array=$this->security->xss_clean($this->input->post('productos'));

		//Comprobamos que los campos necesarios para Factura esten llenos
		if( isset($tipodoc) && !empty($tipodoc) && isset($idcliente) && !empty($idcliente) && isset($cliente) && !empty($cliente) && isset($fechaEmision) && !empty($fechaEmision) 
			&& isset($fechaCancelacion) && !empty($fechaCancelacion) && isset($tipopago) && !empty($tipopago) && isset($moneda) && !empty($moneda) 
			&& isset($serie) && !empty($serie) && isset($correlativo) && !empty($correlativo) && isset($precio_total) && !empty($precio_total) && isset($igv) ):

				$datos = array(
					'id_factura'		=> $codigounico,
					'id_cliente'		=> $idcliente,
					'razon_social' 		=> $cliente,
					'tipo_documento'	=> $tipodoc,
					'serie'				=> $serie,
					'correlativo' 		=> $correlativo,
					'fecha_emision'		=> $fechaEmision,
					'fecha_cancelacion'	=> $fechaCancelacion,
					'tipo_pago'			=> $tipopago,
					'moneda'			=> $moneda,
					'monto'				=> $precio_total,
					'estado'			=> "1",
					'igv'				=> $igv
					 );

				$this->Facturar_model->actualizar_documento($id, $datos);
				echo "Listo, la factura se guardo<br>";
				// $this->Facturar_model->actualizar_productos($codigounico,$array);
				// echo "Se guardo el Producto";

				$this->session->set_flashdata('document_status', mensaje('Se Actualizó el Documento','success'));
				redirect(base_url("facturar"));

		else:
			$this->session->set_flashdata('document_status', mensaje('Hubo un error al actualizar el Documento. Intenta Nuevamente','danger'));
			redirect(base_url("facturar"));
		endif;
	}

	//Pagina invisible, guardara los datos que se envio del formulario
	//CODIGO DE BARRA => 
			// 1 .para tipo de factura -> (1) para boleta de agua
			// 2 |
			// 3 |
			// 4 |
			// 5 | para id_conexion
			// 6 - 
			// 7 -
			// 8 -
			// 9 -
			// 10 -
			// 11 -id_facturacion
			// 12 *control
	public function guardar_datos_factura()
	{
		$id_cliente =$this->security->xss_clean(strip_tags($this->input->post('id_cliente')));
		$conexion_id=$this->security->xss_clean(strip_tags($this->input->post('id_conexion')));
		$subtotal=$this->security->xss_clean(strip_tags($this->input->post('subtotal')));
		$total=$this->security->xss_clean(strip_tags($this->input->post('total')));
		//buscar plan_pago_id
		$existe_planpago = $this->Crud_model->get_data_row_sin_borrado_en_array("planpago","PlanPago_Conexion_Id","PlanPago_Borrado",$conexion_id);
		if($existe_planpago == false )
		{
			$existe_planpago["PlanPago_Id"] = 0;
			$existe_planpago["PlanPago_MontoCuota"] = 0;
			$existe_planpago["PlanPago_MontoTotal"] = 0;
			$existe_planpago["PlanPago_MontoPagado"] = 0;
			$existe_planpago["PlanPago_Coutas"] = 0;
			$existe_planpago["PlanPago_CoutaActual"] = 0;
		}
		$existe_medicion = $this->Crud_model->get_data_row_sin_borrado_en_array("medicion","Medicion_Conexion_Id","Medicion_Borrado",$conexion_id);
		if($existe_planpago == false )
		{
			$existe_medicion["Medicion_Id"] = 0;
			$existe_medicion["Medicion_Importe"] = 0;
		}
		$existe_planmedidor = $this->Crud_model->get_data_row_sin_borrado_en_array("planmedidor","PlanMedidor_Conexion_Id","PlanMedidor_Borrado",$conexion_id);
		if($existe_planmedidor == false )
		{
			$existe_planmedidor["PlanMedidor_Id"] = 0;
			$existe_planmedidor["PlanMedidor_MontoCuota"] = 0;
			$existe_planmedidor["PlanMedidor_MontoTotal"] = 0;
			$existe_planmedidor["PlanMedidor_MontoPagado"] = 0;
			$existe_planmedidor["PlanMedidor_Coutas"] = 0;
			$existe_planmedidor["PlanMedidor_CoutaActual"] = 0;
			
		}
		$data_para_insertar_en_factura = array(
			'id'=> null,	
			'id_factura'=> "111111111111",
			'id_cliente'=> $id_cliente,
			'Factura_PlanPago_Id'=>$existe_planpago["PlanPago_Id"],
			'Factura_Conexion_Id'=> $conexion_id,
			'Factura_Medicion_Id'=> $existe_medicion["Medicion_Id"],
			'Factura_PlanMedidor_Id	'=> $existe_planmedidor["PlanMedidor_Id"],
			'tipo_documento'=> 1,
			'serie'	=> 1,
			'correlativo'=> 1,
			'moneda'=> "pesos",
			'monto'	=> $total,
			'fecha_emision'	=> date("Y-m-d H:i:s"),
			'fecha_cancelacion'	=> null,
			'tipo_pago'	=> "contado",
			'estado'=> "1",
			'igv'=> 0,
			'Factura_Periodo' => date("Y-m-d"),
			'Factura_SubTotal'=> $subtotal,
			'Factura_Total'	=> $total,
			'Factura_Codigo'=> 1, 
			'Factura_Vigencia'=> date("Y-m-21"), 
			'Factura_Vencimiento1'=> date("Y-m-14"),
			'Factura_Vencimiento2'=> date("Y-m-21"),
			'Factura_Observacion'=> null,
			'Factura_Habilitacion'=> 1,
			'Factura_Borrado'=> 0,
			'Factura_Timestamp'	=> null
		);
		$id_factura_recien_insertado =$this->Facturar_model->insertar_factura_tres("facturacion",$data_para_insertar_en_factura);
		//var_dump($id_factura_recien_insertado);die();
		if(is_numeric( $id_factura_recien_insertado ) ) // se inserto correctamente
		{
			$conexion_en_codigo_barra = null;
			$conexion_en_codigo_barra .= $conexion_id;
			for ($i= strlen($conexion_id); $i < 4 ; $i++) { 
				$conexion_en_codigo_barra  = "0".$conexion_en_codigo_barra;
			}
			$facturacion_en_codigo_barra = null;
			$facturacion_en_codigo_barra .= $id_factura_recien_insertado;
			for ($i= strlen($facturacion_en_codigo_barra); $i < 6 ; $i++) { 
				$facturacion_en_codigo_barra  = "0".$facturacion_en_codigo_barra;
			}
			$codigo_aux = "1".$conexion_en_codigo_barra.$facturacion_en_codigo_barra;
			$i=0;
			$acumulado = 0;
			for ($j=0; $j < cantidad_digitos-1 ; $j++)
				$acumulado +=$codigo_aux[$j] ;
			$codigo_aux[cantidad_digitos-1] = $acumulado % 7 ; 
			$codigo = array('id_factura' => $codigo_aux);
			$resultado_modificacion = $this->Facturar_model->modficar_factura($codigo, $id_factura_recien_insertado);
			if($resultado_modificacion == true)
				echo $id_factura_recien_insertado;
			else 
				echo "false";
		}
		else 
		{
			$this->session->set_flashdata('aviso','La factura NO se genero correctamente');
			echo "false";
		}
	}



public function ultra_automatica()
{
	$log_master = '';
	$datos["conexiones_a_imprimir"] = $this->Crud_model->buscar_conexion_a_imprmir_nuevo();
	//var_dump(sizeof($datos["conexiones_a_imprimir"]));die();	
	$resultado = '';	
	for ($i=0; $i < sizeof($datos["conexiones_a_imprimir"]); $i++) { 
		//var_dump($datos["conexiones_a_imprimir"][$i]->Conexion_Sector);
		$resultado .= $this->guardar_datos_factura_automatico($datos["conexiones_a_imprimir"][$i]->Conexion_Sector);
		$log_master = "    -  El resultado para el sector es:  ".$resultado;
	}
	var_dump($log_master);
}



	public function guardar_datos_factura_automatico($sector)
	{
		//$id_cliente =$this->security->xss_clean(strip_tags($this->input->post('id_cliente')));
		//$conexion_id=$this->security->xss_clean(strip_tags($this->input->post('id_conexion')));
		// $subtotal=$this->security->xss_clean(strip_tags($this->input->post('subtotal')));
		// $total=$this->security->xss_clean(strip_tags($this->input->post('total')));
		//buscar plan_pago_id
		$log = '';
		$conexiones_en_el_sector = $this->Crud_model->conexiones_por_sector($sector); // solo trae las conexiones
		//var_dump($conexiones_en_el_sector);die();
		$configuracion =  $this->Crud_model->get_data("configuracion");
		$errores = array();
		$indice_errores = 0;
		foreach ($conexiones_en_el_sector as $key) {
			// por cada uno de las conexion del sector voy a crear una factura, en caso de no tener medicion, entonces lo voy a reportar
			//Para cada factura, voy a necesitar los datos de los clientes , su conexion, plan de pago, deuda, bonificacion, plan medidor, y  su medicion actual
			$medicion = $this->Facturar_model->buscar_mediciones_para_una_conexion($key->Conexion_Id);
			$conexion_id = $key->Conexion_Id;
			$repetido = $this->Crud_model->get_data_row_dos_campos("facturacion","Factura_Conexion_Id",$key->Conexion_Id, "Factura_Periodo",intval (date("m")-1)) ;//busco si ya existe
			if(  ($medicion  == false) || ($repetido != false) )
			{
				//echo "error";die();
				//esta conexion no tiene cargada la medicion para este mes, por tanto no puedo crear factura y reporte esta falta
				$errores[$indice_errores] = $key->Conexion_Id;
				//poner el motivo del error
				$indice_errores++;
				//echo "    _    No se creo:  ".$key->Conexion_Id."     _     \n\r"  ;
				$log .= "    _    No se creo:  ".$key->Conexion_Id."     _     \n\r"  ;
				continue;
			}
			else
			{
				//echo "Sin error";die();
				//significa que si tiene una medicion cargada. hasta el momento solamente tengo la conexion y la medicion
				$data= $this->Facturar_model->buscar_datos_crear_factura_conexion_id($key->Conexion_Id); // aca tengo cliente, plan pago, plan medidor, deuda, y todo conexion
				if($data->Conexion_Bonificacion ==  floatval(1))
					$bonificacion_por_pago_a_tiempo = 0.05;
				elseif($data->Conexion_Bonificacion == floatval(2))
					$bonificacion_por_pago_a_tiempo = 0.1;
				else
					$bonificacion_por_pago_a_tiempo = 0;
				$pago_a_cuenta = $data->Conexion_Acuenta;
				if($pago_a_cuenta == null)
					$pago_a_cuenta =0;
				$existe_planpago = $this->Crud_model->get_data_row_sin_borrado_en_array("planpago","PlanPago_Conexion_Id","PlanPago_Borrado",$conexion_id);
				if($existe_planpago == false )
				{
					$existe_planpago["PlanPago_Id"] = 0;
					$existe_planpago["PlanPago_MontoCuota"] = 0;
					$existe_planpago["PlanPago_MontoTotal"] = 0;
					$existe_planpago["PlanPago_MontoPagado"] = 0;
					$existe_planpago["PlanPago_Coutas"] = 0;
					$existe_planpago["PlanPago_CoutaActual"] = 0;
				}
				//$existe_medicion = $this->Crud_model->get_data_row_sin_borrado_en_array("medicion","Medicion_Conexion_Id","Medicion_Borrado",$conexion_id);
				// if($existe_planpago == false )
				// {
				// 	$existe_medicion["Medicion_Id"] = 0;
				// 	$existe_medicion["Medicion_Importe"] = 0;
				// }
				$existe_planmedidor = $this->Crud_model->get_data_row_sin_borrado_en_array("planmedidor","PlanMedidor_Conexion_Id","PlanMedidor_Borrado",$conexion_id);
				if($existe_planmedidor == false )
				{
					$existe_planmedidor["PlanMedidor_Id"] = 0;
					$existe_planmedidor["PlanMedidor_MontoCuota"] = 0;
					$existe_planmedidor["PlanMedidor_MontoTotal"] = 0;
					$existe_planmedidor["PlanMedidor_MontoPagado"] = 0;
					$existe_planmedidor["PlanMedidor_Coutas"] = 0;
					$existe_planmedidor["PlanMedidor_CoutaActual"] = 0;
					
				}
				$datos = array(
					'id'=> null,	
					'id_factura'		=> "11111111",
					'id_cliente'		=> $data->Cli_Id,
					'Factura_PlanPago_Id'=> $existe_planpago["PlanPago_Id"],
					'Factura_Conexion_Id'=> $conexion_id,
					'Factura_Medicion_Id'=> $medicion->Medicion_Id,
					'Factura_PlanMedidor_Id'=> $existe_planmedidor["PlanMedidor_Id"],
					'tipo_documento'	=> 1,
					'serie'			=> 0,
					'correlativo' 		=> 1,
					'moneda'		=> "pesos",
					'monto'			=> 99.99,
					'fecha_emision'	=> date("Y-m-d H:i:s"),
					'fecha_cancelacion'	=> null,
					'tipo_pago'		=> "Contado",
					'estado'		=> "1",
					'igv'			=> 0,
					//'Factura_Periodo'      => intval (date("m")),
					'Factura_Periodo'      => 1,
					'Factura_SubTotal'     => 0,
					'Factura_Total'	=> 0,
					'Factura_Codigo'      => 1, 
					'Factura_Vigencia'    => date("Y-m-21"), 
					'Factura_Vencimiento1'=> date("Y-m-14"),
					'Factura_Vencimiento2'=> date("Y-m-21"),
					'Factura_Observacion'=> null,
					'Factura_Habilitacion'  => 1,
					'Factura_Borrado'      => 0,
					'Factura_Timestamp'	=> null
				);
				$id_factura_recien_creada = $this->Crud_model->insert_data("facturacion",$datos);
				$codigo_de_barras  = $this->calcular_codigo_barra_agua($conexion_id, $id_factura_recien_creada );
				if($medicion->Medicion_Mts == 15)
					$tipo ="Comercial";
				else $tipo ="Familiar";
				//if($key->Conexion_Id == 325)
				$datos_calculados = $this->calcular_valores_a_facturar($existe_planmedidor["PlanMedidor_MontoCuota"],$existe_planpago["PlanPago_MontoCuota"] ,$data->Conexion_Deuda,$data->Conexion_Acuenta,$configuracion,$data->Conexion_Bonificacion, 0, $medicion->Medicion_Excedente,$tipo, $key->Conexion_Id, $key->Conexion_Latitud);
				// if($conexion_id == 963)
				// {
				// 	var_dump($datos_calculados, $existe_planmedidor["PlanMedidor_MontoCuota"],$existe_planpago["PlanPago_MontoCuota"] ,$data->Conexion_Deuda,$data->Conexion_Acuenta,$configuracion,$data->Conexion_Bonificacion, 0, $medicion->Medicion_Excedente,$tipo, $key->Conexion_Id);
				// 	die();
				// }
				$arrayName = array(
					'id_factura' => substr($codigo_de_barras, 0,-1),
					'monto' => $datos_calculados["total"],
					'Factura_SubTotal' => str_replace(",", ".",$datos_calculados["subtotal_sin_bonificacion"]),
					'Factura_Total' => str_replace(",", ".",$datos_calculados["total"])
					 );
				$resultado  = $this->Crud_model->update_data($arrayName, $id_factura_recien_creada, "facturacion" ,"id");
				//echo "   _    Agrege la conexion : ".$conexion_id."   /\ \n\r   _ ";
				$log .= "   _    Agrege la conexion : ".$conexion_id."   /\ \n\r   _ ";
			}
		}
		return $log;
	}
}
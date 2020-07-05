<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nuevo extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('Nuevo_model');
		$this->load->helper('PDF_helper');
		$this->load->helper('eFPDF_helper');
		$this->load->library('zend');
		require_once 'vendor\fzaninotto\faker\src\autoload.php';
	}





	/***************************************************************
	****************************************************************
							INICIO	TABLA
	****************************************************************
	*****************************************************************/
	public function index(){
		//no hace nada, solo llama la view
		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//estoy adentro
			$this->load->view("nuevo/tabla_pago");
		endif;
	}
	// public function traer_super_datos(){

	// 	//SIN USO ; - OBSOLETO
		
	// 		$data ["usuarios"] = $this->Nuevo_model->join_nivel_dios_dos();
	// 		$this->load->helper(array('url'));
	// 		//$data ["usuarios"] = $this->Crud_model->get_data("configuracion"); 
	// 		$this->output->set_content_type('application/json')->set_output(json_encode($data ["usuarios"]));
	// 		// header('Content-Type: application/json');
	// 		// echo  json_encode($data ["usuarios"]);
	// }
	public function traer_datos_por_query($sector = -1, $mes = -1, $ano= -1, $id_conexion = -1, $pagado = -1 ){
		//creo los string con los nombre de los sectores, es por una cuestion de como se guardo en la bd
		$aux = "Jardines del Sur";
		if(strlen($sector)  == 9)
			$sector = "V Elisa";
		if(strlen($sector)  == 20)
			$sector = "ASENTAMIENTO OLMOS";
		if(strlen($sector)  == 15)
			$sector = "Santa Barbara";
		if(strlen($sector)  == 10)
		 	$sector = "Jardines del Sur";
		//var_dump($sector, strlen($sector),$aux, strlen($aux));die();
		$data ["usuarios"] = $this->Nuevo_model->traer_facturas_por_barrio_nuevo($sector, $mes , $ano, $id_conexion, $pagado);
		//var_dump($data ["usuarios"]);die();
		$this->load->helper(array('url'));
		$this->output->set_content_type('application/json')->set_output(json_encode($data ["usuarios"]));
		// header('Content-Type: application/json');
		// echo  json_encode($data ["usuarios"]);
	}
	public function traer_datos_por_sector($sector){
		if($sector == 'V%20Elisa')
			$sector ="V Elisa";
		if($sector == 'Santa%20Barbara')
			$sector ="Santa Barbara";
		if($sector == "ASENTAMIENTO%20OLMOS")
			$sector = "ASENTAMIENTO OLMOS";
		if($sector == "Jardin")
			$sector = "Jardines del Sur";
		if($sector == "medina")
			$sector = "Medina";
		$data ["usuarios"] = $this->Nuevo_model->traer_facturas_por_barrio_nuevo($sector);
		$this->load->helper(array('url'));
		$this->output->set_content_type('application/json')->set_output(json_encode($data ["usuarios"]));
	}
	public function guardar_cambios_conexion($id_conexion,$categoria = null, $deuda_anterior = null,$sector = null, $orden_sector = null, $domicilio = null){
		$datos = array();
		$domicilio = str_replace( "%20", " ", $domicilio);
		if( ($id_conexion != null) && (is_numeric($id_conexion) ) && ($id_conexion > 0) ) //id valido
		{
			$datos = array(
				"Conexion_Categoria" => $categoria,
				"Conexion_Deuda" =>  $deuda_anterior ,
				//"Conexion_Sector" =>  $sector ,
				"Conexion_UnionVecinal" =>  $orden_sector,
			//	"Conexion_DomicilioSuministro" =>  $domicilio
			);
			$resultado = $this->Nuevo_model->update_data($datos, $id_conexion, "Conexion","Conexion_Id");
		}
		
		$this->output->set_content_type('application/json')->set_output(json_encode($datos));
	}
	public function guardar_cambios_factura($id_factura){
		$request = json_decode(file_get_contents("php://input"), TRUE);
		if( ($id_factura != null) && (is_numeric($id_factura) ) && ($id_factura > 0) ) //id valido
		{
				//voy a caluclar los subtotal, total
				$subtotal = 
					floatval($request["deuda_anterior"]) +
					floatval($request["tarifa_basica"]) +
					floatval($request["exc_precio"]) +
					floatval($request["couta_social"]) +
					floatval($request["medidor_couta_precio"]) +
					floatval($request["plan_pago_cuota_precio"]) +
					floatval($request["riego"]) ;

				$total = $subtotal;
				if(intval($request["deuda_anterior"])  != 0) 
					$total += floatval($subtotal) * floatval(0.015);
				$total = floatval($total)
										- floatval($request["pago_acuenta"])
										- floatval($request["bonificacion"]);
			$datos = array(
				"Factura_Deuda" => $request["deuda"],
				"Factura_TarifaSocial" => $request["tarifa_basica"],
				"Factura_ExcedentePrecio" => $request["exc_precio"],
				"Factura_CuotaSocial" => $request["couta_social"],

				"Factura_PM_Cant_Cuotas" => $request["medidor_canti_cuota"],
				"Factura_PM_Cuota_Actual" => $request["medidor_couta_actual"],
				"Factura_PM_Cuota_Precio" => $request["medidor_couta_precio"],

				"Factura_PP_Cant_Cuotas" => $request["plan_pago_canti_cuota"],
				"Factura_PP_Cuota_Actual" => $request["plan_pago_cuota_actual"],
				"Factura_PPC_Precio" => $request["plan_pago_cuota_precio"],
				"Factura_Riego" => $request["riego"],
				"Factura_Multa" => $request["multa"],
				"Factura_SubTotal" => $subtotal,
				"Factura_Bonificacion" => $request["bonificacion"],
				"Factura_Total" => $total,
				"Factura_Vencimiento1" => $request["vto_1_fecha"],
				"Factura_Vencimiento1_Precio" => $request["vto_1_Precio"],
				"Factura_Vencimiento2" => $request["vto_2_fecha"],
				"Factura_Vencimiento2_Precio" => $request["vto_2_precio"],
			);
			$resultado = $this->Nuevo_model->update_data($datos, $id_factura, "facturacion_nueva","Factura_Id");
		}
		echo  $resultado;
		//$this->output->set_content_type('application/json')->set_output(json_encode($id));
	}
	public function guardar_cambios_medicion($id_factura){
		$request = json_decode(file_get_contents("php://input"), TRUE);
		if( ($id_factura != null) && (is_numeric($id_factura) ) && ($id_factura > 0) ) //id valido
		{
			$datos = array(
				"Factura_MedicionActual" => $request["actual"],
				"Factura_MedicionAnterior" => $request["anterior"],
				"Factura_Basico" => $request["mts_categoria"],
				"Factura_Excedentem3" => $request["exc_mts"],
			);
			$resultado = $this->Nuevo_model->update_data($datos, $id_factura, "facturacion_nueva","Factura_Id");
		}
		echo  $resultado;
		//$this->output->set_content_type('application/json')->set_output(json_encode($id));
	}
	public function guardar_back_cambios($id_factura){
		$request = json_decode(file_get_contents("php://input"), TRUE);
		if( ($id_factura != null) && (is_numeric($id_factura) ) && ($id_factura > 0) ) //id valido
		{
			$resultado_copia = $this->Nuevo_model->get_data_dos_campos("facturacion_nueva","Factura_Id", $id_factura,"Factura_Borrado",0);
			$datos_a_backup = array(
				'Aut_Id' => null,
				'Aut_Factura_Id' => $resultado_copia[0]->Factura_Id,
				'Aut_Factura_Deuda' => $resultado_copia[0]->Factura_Deuda,
				'Aut_Factura_TarifaSocial' => $resultado_copia[0]->Factura_TarifaSocial,
				'Aut_Factura_ExcedentePrecio' => $resultado_copia[0]->Factura_ExcedentePrecio,
				'Aut_Factura_CuotaSocial' => $resultado_copia[0]->Factura_CuotaSocial,
				'Aut_Factura_PM_Cant_Cuotas' => $resultado_copia[0]->Factura_PM_Cant_Cuotas,
				'Aut_Factura_PM_Cuota_Actual' => $resultado_copia[0]->Factura_PM_Cuota_Actual,
				'Aut_Factura_PM_Cuota_Precio' => $resultado_copia[0]->Factura_PM_Cuota_Precio,
				'Aut_Factura_PP_Cant_Cuotas' => $resultado_copia[0]->Factura_PP_Cant_Cuotas,
				'Aut_Factura_PP_Cuota_Actual' => $resultado_copia[0]->Factura_PP_Cuota_Actual,
				'Aut_Factura_PPC_Precio' => $resultado_copia[0]->Factura_PPC_Precio,
				'Aut_Factura_Riego' => $resultado_copia[0]->Factura_Riego,
				'Aut_Factura_Multa' => $resultado_copia[0]->Factura_Multa,
				'Aut_Factura_SubTotal' => $resultado_copia[0]->Factura_SubTotal,
				'Aut_Factura_Acuenta' => $resultado_copia[0]->Factura_Acuenta,
				'Aut_Factura_Bonificacion' => $resultado_copia[0]->Factura_Bonificacion,
				'Aut_Factura_Descuento' => $resultado_copia[0]->Factura_Descuento,
				'Aut_Factura_Total' => $resultado_copia[0]->Factura_Total,
				'Aut_Factura_Vencimiento1_Precio' => $resultado_copia[0]->Factura_Vencimiento1_Precio,
				'Aut_Factura_Vencimiento2_Precio' => $resultado_copia[0]->Factura_Vencimiento2_Precio,
				'Aut_Factura_MedicionAnterior' => $resultado_copia[0]->Factura_MedicionAnterior,
				'Aut_Factura_MedicionActual' => $resultado_copia[0]->Factura_MedicionActual,
				'Aut_Factura_Excedentem3' => $resultado_copia[0]->Factura_Excedentem3,
				'Aut_Factura_PagoAtrasado' => null,
				'Aut_Factura_PagoMonto' => null,
				'Aut_Factura_PagoContado' => null,
				'Aut_Factura_PagoCheque' => null,
				'Aut_Factura_PagoTimestamp' => null,
				'Aut_Revisado' => "No",
				'Aut_Mes' => $resultado_copia[0]->Factura_Mes,
				'Aut_Año' => $resultado_copia[0]->Factura_Año,
				'Aut_Quien' => $this->session->userdata('id_user'),
				'Aut_FechaHora' => date("Y-m-d H:i:s"),
				'Aut_Timestamp' => null
			 );
			$id_back_up = $this->Nuevo_model->insert_data("autorizacion",$datos_a_backup);
		}
		echo  $resultado;
		//$this->output->set_content_type('application/json')->set_output(json_encode($id));
	}
	public function recalcular_boleta($id_factura){
		$request = json_decode(file_get_contents("php://input"), TRUE);
			if( ($id_factura != null) && (is_numeric($id_factura) ) && ($id_factura > 0) ) //id valido
		{
			$datos = array(
				

				"Factura_Bonificacion" => $request["bonificacion"],

				"Factura_ExcedentePrecio" => $request["exc_precio"],
				"Factura_Excedentem3" => $request["exc_mts"],

				"Factura_SubTotal" => $request["sub_total"],
				"Factura_Total" => $request["total"],
				"Factura_Vencimiento1_Precio" => $request["total"], // es el mismo
				"Factura_Vencimiento2_Precio" => $request["vto_2_precio"],
				
			);
			$resultado_factura = $this->Nuevo_model->update_data($datos, $id_factura, "facturacion_nueva","Factura_Id");
			//voy a buscar la deuda anterior
		}
		if($resultado_factura)
			echo  true;
		else echo  false;
	}
	
	public function pagar_boleta($id_factura){
		$request = json_decode(file_get_contents("php://input"), TRUE);
		//var_dump($request);
		$inputFechaaux=$request["fechacorregida"];
			$aux =  str_replace('/', '-', $inputFechaaux);
			$inputFecha = date("Y-m-d H:i:s", strtotime($aux));
			$horas = date("H:i:s");
			$inputFecha = $inputFecha." ".$horas;
			if($request["tipo_de_pago"] == 1) // pago el total de la boleta
				$pago_Monto =  $request["total"];
			else // pago parcial de boleta
				$pago_Monto =   $request["pago_parcial_a"];

		if(isset($request["contado"]))
			$contado = $request["contado"]; //caso mixto
		else $contado =null;
		if(isset($request["cheque"]))
			$cheques = $request["cheque"]; //caso mixto
		else $cheques = null;
		if( $request["forma_de_pago"] == 1 ) //efectivo
		{
			$contado = $pago_Monto;
			$cheques = 0;
		}
		elseif( $request["forma_de_pago"] == 2 ) //cheque
		{
			$cheques = $pago_Monto;
			$contado =0;
		}
		$pago_atrasado = "Sin_Retraso";
		if($request["pago_atrasado"] == 1)
			$pago_atrasado = "Con_Retraso";
		if( ($id_factura != null) && (is_numeric($id_factura) ) && ($id_factura > 0) ) //id valido
		{
			//creo la copia de la factura q se pretende pagar
			$resultado_copia = $this->Nuevo_model->get_data_dos_campos("facturacion_nueva","Factura_Id", $id_factura,"Factura_Borrado",0);
			$datos_a_backup = array(
				'Aut_Id' => null,
				'Aut_Factura_Id' => $resultado_copia[0]->Factura_Id,
				'Aut_Factura_Deuda' => $resultado_copia[0]->Factura_Deuda,
				'Aut_Factura_TarifaSocial' => $resultado_copia[0]->Factura_TarifaSocial,
				'Aut_Factura_ExcedentePrecio' => $resultado_copia[0]->Factura_ExcedentePrecio,
				'Aut_Factura_CuotaSocial' => $resultado_copia[0]->Factura_CuotaSocial,
				'Aut_Factura_PM_Cant_Cuotas' => $resultado_copia[0]->Factura_PM_Cant_Cuotas,
				'Aut_Factura_PM_Cuota_Actual' => $resultado_copia[0]->Factura_PM_Cuota_Actual,
				'Aut_Factura_PM_Cuota_Precio' => $resultado_copia[0]->Factura_PM_Cuota_Precio,
				'Aut_Factura_PP_Cant_Cuotas' => $resultado_copia[0]->Factura_PP_Cant_Cuotas,
				'Aut_Factura_PP_Cuota_Actual' => $resultado_copia[0]->Factura_PP_Cuota_Actual,
				'Aut_Factura_PPC_Precio' => $resultado_copia[0]->Factura_PPC_Precio,
				'Aut_Factura_Riego' => $resultado_copia[0]->Factura_Riego,
				'Aut_Factura_Multa' => $resultado_copia[0]->Factura_Multa,
				'Aut_Factura_SubTotal' => $resultado_copia[0]->Factura_SubTotal,
				'Aut_Factura_Acuenta' => $resultado_copia[0]->Factura_Acuenta,
				'Aut_Factura_Bonificacion' => $resultado_copia[0]->Factura_Bonificacion,
				'Aut_Factura_Descuento' => $resultado_copia[0]->Factura_Descuento,
				'Aut_Factura_Total' => $resultado_copia[0]->Factura_Total,
				'Aut_Factura_Vencimiento1_Precio' => $resultado_copia[0]->Factura_Vencimiento1_Precio,
				'Aut_Factura_Vencimiento2_Precio' => $resultado_copia[0]->Factura_Vencimiento2_Precio,
				'Aut_Factura_MedicionAnterior' => $resultado_copia[0]->Factura_MedicionAnterior,
				'Aut_Factura_MedicionActual' => $resultado_copia[0]->Factura_MedicionActual,
				'Aut_Factura_Excedentem3' => $resultado_copia[0]->Factura_Excedentem3,
				'Aut_Factura_PagoAtrasado' => $pago_atrasado,
				'Aut_Factura_PagoMonto' => $pago_Monto,
				'Aut_Factura_PagoContado' => $contado,
				'Aut_Factura_PagoCheque' => $cheques,
				'Aut_Factura_PagoTimestamp' => $inputFecha,
				'Aut_Revisado' => "No",
				'Aut_Mes' => $resultado_copia[0]->Factura_Mes,
				'Aut_Año' => $resultado_copia[0]->Factura_Año,
				'Aut_Quien' => $this->session->userdata('id_user'),
				'Aut_FechaHora' => date("Y-m-d H:i:s"),
				'Aut_Timestamp' => null
			 );

			$id_back_up = $this->Nuevo_model->insert_data("autorizacion",$datos_a_backup);
			
			//var_dump($datos_a_backup);
			/*/*/
			//monto_total_sin_modificar_a
			$datos = array(
				"Factura_Deuda" => $request["deuda"],
				"Factura_TarifaSocial" => $request["tarifa_basica"],

				"Factura_ExcedentePrecio" => $request["exc_precio"],
				"Factura_CuotaSocial" => $request["couta_social"],

				"Factura_PM_Cant_Cuotas" => $request["medidor_canti_cuota"],
				"Factura_PM_Cuota_Actual" => $request["medidor_couta_actual"],
				"Factura_PM_Cuota_Precio" => $request["medidor_couta_precio"],

				"Factura_PP_Cant_Cuotas" => $request["plan_pago_canti_cuota"],
				"Factura_PP_Cuota_Actual" => $request["plan_pago_cuota_actual"],
				"Factura_PPC_Precio" => $request["plan_pago_cuota_precio"],

				"Factura_Riego" => $request["riego"],
				
				"Factura_Multa" => $request["multa"],
				"Factura_SubTotal" => $request["sub_total"],
				"Factura_Descuento" => $request["descuento"],
				"Factura_Total" => $request["total"],
					
				"Factura_PagoLugar" => 1,
				"Factura_PagoAtrasado" => $pago_atrasado,
				"Factura_PagoMonto" => $pago_Monto,
				"Factura_PagoContado" => $contado,
				"Factura_PagoCheque" => $cheques,
				"Factura_PagoTimestamp" => $inputFecha,
				
			);
			$resultado_factura = $this->Nuevo_model->update_data($datos, $id_factura, "facturacion_nueva","Factura_Id");
			//var_dump($datos,$resultado_factura);
			/**********************
			ACTUALIZO LA DEUDA PARA EL MES MAS NUEVO QUE EXISTA
			***********************/
			//voy a buscar la deuda anterior
			$deuda_a_grabar = 0;
			if($request["tipo_de_pago"] != 1)//pago parte de la boleta, entonces genero deuda
			{
				$datos_conexion = $this->Nuevo_model->get_data_dos_campos("conexion","Conexion_Id",$request["id_conexion"], "Conexion_Borrado",0);
				$deuda_a_grabar =  floatval($request["endeuda"]);
			}
			$datos = array(
				"Conexion_Deuda" => $deuda_a_grabar,
			);
			$resultado_conexion = $this->Nuevo_model->update_data($datos, $request["id_conexion"], "Conexion","Conexion_Id");
			
			//actualizo la deuda del proximo
			// 1° pregunto si esxisten una factura mas nueva q la q estoy pagando
			// 2° si existe le cambio el valor de Factura_deuda
			// 3° Recalcula el valor de la boleta
			$pxo_Mes = $resultado_copia[0]->Factura_Mes +1;
			$pxo_Año = $resultado_copia[0]->Factura_Año;
			if($pxo_Mes == 13)
			{
				$pxo_Mes = 1;
				$pxo_Año++;
			}
			$proxima_boleta = $this->Nuevo_model->get_data_tres_campos("facturacion_nueva", "Factura_Mes" ,$pxo_Mes,"Factura_Año",$pxo_Año, "Factura_Conexion_Id",$resultado_copia[0]->Factura_Conexion_Id);
			// var_dump("La proixima boleta es:",$proxima_boleta);
			// die();
			if( $proxima_boleta != false ) // si existe una boleta mas actual, entonces la acutalizo
			{
				//2° PASO
				$datos = array(
					"Factura_Deuda" => $deuda_a_grabar,
				);
				$resultado_actualizacion_de_factura_mas_nueva = $this->Nuevo_model->update_data($datos, $proxima_boleta[0]->Factura_Id, "facturacion_nueva","Factura_Id");
				//3° PASO
				$resultado_recalcular_factura_mas_actual =  $this->corregir_boleta_por_id($proxima_boleta[0]->Factura_Id);
			}
			//var_dump($resultado_conexion,$datos);
			//ingreo el movimiento de dinero
			$datos_movimiento = array(
				 	'Mov_Id' => null,
				 	'Mov_Tipo' => 1, //ingreso
				 	'Mov_Monto' => $this->arreglar_numero($pago_Monto),
				 	'Mov_Codigo' =>	"3", //poner codigo cuando lo tengamos
				 	'Mov_Pago_Id' =>	0, 
				 	'Mov_Revisado' =>	0,
				 	'Mov_Quien' =>	$this->session->userdata('id_user'),
				 	'Mov_Observacion' => "Pago Boleta Con:".$id_factura,
				 	'Mov_Habilitacion' =>	1,
				 	'Mov_Borrado' =>	0,
				 	'Mov_Timestamp' =>	null,
				 	'Mov_FechaInsert' =>	$inputFecha,
				 	'Mov_Conexion_Id' =>	$resultado_copia[0]->Factura_Conexion_Id,
				 	'Mov_Factura_Id' =>	$resultado_copia[0]->Factura_Id,
				 	'Mov_a_quien' =>	"pago boleta"
			);
			//var_dump($datos_movimiento);
			$id_movimiento = $this->Nuevo_model->insert_data("movimiento",$datos_movimiento);
		}
		// resultados: $id_back_up, $resultado_factura, $resultado_conexion , $id_movimiento
		$resultado = $id_back_up."*".$resultado_factura."*".$resultado_conexion."*".$id_movimiento;
		if($resultado_factura && $resultado_conexion)
			echo  $resultado;
		else echo  false;
		//$this->output->set_content_type('application/json')->set_output(json_encode($id));
	}
	public function anular_pago($id_factura)
	{
		$request = json_decode(file_get_contents("php://input"), TRUE);
		//$=$request["fechacorregida"];
		$motivo=$request["motivo"];
		//var_dump($motivo);

		//hacer,pregunto si la boleta esta paga, deberia venir siempre paga
		/*
		First Step -> set on null facturacion_nueva's fields
		Second step -> set on null movimiento's flieds
		Third Step -> insert a row into pago_eliminados
		fourth -> change deuda values*/
		//First
		$datos = array(
			"Factura_PagoLugar" => null,
			"Factura_PagoAtrasado" => null,
			"Factura_PagoMonto" => null,
			"Factura_PagoContado" => null,
			"Factura_PagoCheque" => null,
			"Factura_PagoTimestamp" => null,
		);
		$resultado_factura = $this->Nuevo_model->update_data($datos, $id_factura, "facturacion_nueva","Factura_Id");
		//var_dump($resultado_factura);
		//second step
		$datos_movimiento = array(
			'Mov_Habilitacion' =>	0,
			'Mov_Borrado' =>	1,
			'Mov_Timestamp' =>	null,
			'Mov_a_quien' =>	"movimiento eliminado"
		);
		$resultado_movimiento = $this->Nuevo_model->update_data($datos_movimiento, $id_factura, "movimiento","Mov_Factura_Id");
		//var_dump($datos_movimiento);
		//third
		$datos_pagos_eliminados = array(
			'PagoE_Id' => null,
			'PagoE_Factura_Id' => $id_factura,
			'PagoE_Motivo' => $motivo,
			'PagoE_Revisado' => 0, //poner codigo cuando lo tengamos
			'PagoE_Borrado' =>	0, 
			'PagoE_Tiemstamp' =>null
		);
		$id_movimiento = $this->Nuevo_model->insert_data("PagoEliminado",$datos_pagos_eliminados);
		//var_dump($datos_pagos_eliminados);
		//fourth
		//the debt remains the same
		//I'll search the factura_nueva's values
		$datos_factura_nueva = $this->Nuevo_model->get_data_dos_campos("facturacion_nueva","Factura_Id", $id_factura,"Factura_Borrado",0);
		$datos_conexion = array(
			"Conexion_Deuda" => $datos_factura_nueva[0]->Factura_Deuda
		);
		$resultado_factura = $this->Nuevo_model->update_data($datos, $id_factura, "facturacion_nueva","Factura_Id");
		var_dump($datos_conexion);	
	}
	/***************************************************************
	****************************************************************
							FIN	TABLA
	****************************************************************
	*****************************************************************/










































	/***************************************************************
	****************************************************************
							INICIO	MEDICIONES 
	****************************************************************
	*****************************************************************/
	public function cargar_mediciones_por_lote(){
		$datos['sectores'] = $this->Nuevo_model->get_data_sectores();
		if ($datos['sectores']) {
			$datos['titulo'] = "Cargar Lote Mediciones";
			$this->load->view('templates/header', $datos);
			$this->load->view('nuevo/agregar_lote_view', $datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		}
		else
		{
			$this->session->set_flashdata("document_status",mensaje("La Medicion No existe","danger"));
			redirect('mediciones');
		}
	}
	
	public function ejecutar_query()
	{
		$sectores=  $this->input->post("sectores");
		$fecha_aux = $this->input->post("mes");

		$inputFechaaux=$this->input->post('mes',true);
		//$aux =  str_replace('/', '-', $inputFechaaux);
		$anio = date("Y", strtotime($inputFechaaux));
		$mes = date("m", strtotime($inputFechaaux));
		//var_dump($anio, $mes);die();

		//	$anio_actual = date("Y");
		$mediciones_desde_query =  $this->Nuevo_model->get_sectores_query_nuevo($sectores, $mes, $anio );
		//var_dump($mediciones_desde_query);die();
		$indice_actual = -1;
		$resultado = array( );
		if(sizeof($mediciones_desde_query)==1)
			$resultado =$mediciones_desde_query ;
		else 
			foreach ($mediciones_desde_query as $key ) {
				if (($indice_actual  == -1) || ($indice_actual != $key->Conexion_Id)){
					//cargo la primer medicion con el indice como se declaro
					$indice_actual = $key->Conexion_Id ;
					array_push($resultado,$key);
				}
			}

		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		// var_dump($resultado);die();
		$cadena = null;
		$cantidad = null;
		$i = 0;
		if($resultado == false)
		{
			$cadena.= 
			'<div class="alert alert-danger">
				Sin conexion en este sector.
			</div>';
		}
		elseif( sizeof($resultado)  == 1){
			//var_dump($resultado);
			if(($resultado->Conexion_Categoria == 1) || ($resultado->Conexion_Categoria == "Familiar"))

			{
				$resultado->Conexion_Categoria = "Familiar";
				$metros_basicos = $todas_las_variables[5]->Configuracion_Valor;
			}
			else
			{
				$resultado->Conexion_Categoria = "Comercial";
				$metros_basicos = $todas_las_variables[8]->Configuracion_Valor;
			}
			$cadena.= 
			'<div data-repeater-list="productos" class="col-md-12 producto-container">
				<div data-repeater-item class="row">
					<div class="col-md-2">
						<div class="form-group fg-line">
							<label class="control-label" for="inputConexionId_'.$i.'">Conexion</label>
							<input type="text" id="inputConexionId_'.$i.'" name="inputConexionId_'.$i.'" value ="'.$resultado->Conexion_Id.'" class="form-control input-sm" readonly>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group fg-line">
							<label class="control-label" for="inputMedicionAnterior_'.$i.'">Anterior </label>
							<input type="text" id="inputMedicionAnterior_'.$i.'" name="inputMedicionAnterior_'.$i.'" value ="'.$resultado->Medicion_Anterior.'" class="form-control input-sm" readonly autocomplete="off">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group fg-line">
							<label class="control-label" for="inputMedicionActual_'.$i.'">Actual </label>
							<input type="text" id="inputMedicionActual_'.$i.'" name="inputMedicionActual_'.$i.'" class="form-control input-sm" placeholder="Solo Números" autocomplete="off" >
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group fg-line">
							<label class="control-label" for="inputExcedente_'.$i.'">Excedente </label>
							<input type="text" id="inputExcedente_'.$i.'" name="inputExcedente_'.$i.'" class="form-control input-sm" readonly autocomplete="off" value="0" >
							<input type="hidden" id="inputMetrosbasicos_'.$i.'" name="inputMetrosbasicos_'.$i.'" class="form-control input-sm"  value="'.$metros_basicos.'" autocomplete="off" >
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group fg-line">
							<label class="control-label" for="inputTipo_'.$i.'">Tipo Conexion </label>
							<input type="text" id="inputTipo_'.$i.'" name="inputTipo_'.$i.'" class="form-control input-sm"  value="'.$resultado->Conexion_Categoria.'" autocomplete="off"  readonly>
						</div>
					</div>
				</div>
			</div>';
			$i++;
		}
		else
		{
			//$cadena .= '<input type="text" id = "cantidad_errores_en_medicion_actual" value="0">';
			foreach ($resultado as $key) 
			{
				if( ($key->Conexion_Categoria == 1) || ($key->Conexion_Categoria == "Familiar") )
				{
					$key->Conexion_Categoria = "Familiar";
					$metros_basicos = $todas_las_variables[5]->Configuracion_Valor;
				}
				else
				{
					$key->Conexion_Categoria = "Comercial";
					$metros_basicos = $todas_las_variables[8]->Configuracion_Valor;
				}
				$cadena.= 
				' 
				<div data-repeater-list="productos" class="col-md-12 producto-container">
					<div data-repeater-item class="row">
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputConexionId_'.$i.'">Conexion</label>
								<input type="hidden" id="inputConexionId_'.$i.'" name="inputConexionId_'.$i.'" value ="'.$key->Conexion_Id.'" class="form-control input-sm" readonly>
								<input type="hidden" id="inputMedicionId_'.$i.'" name="inputMedicionId_'.$i.'" value ="'.$key->Medicion_Id.'" class="form-control input-sm" readonly>
								<p>'.$key->Conexion_Id.'</p>
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputMedicionAnterior_'.$i.'">Anterior</label>
								<input type="text" id="inputMedicionAnterior_'.$i.'" name="inputMedicionAnterior_'.$i.'" value =" '.$key->Medicion_Anterior.' " class="form-control input-sm">
								
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputMedicionActual_'.$i.'">Actual </label>
								<input type="text" id="inputMedicionActual_'.$i.'" name="inputMedicionActual_'.$i.'" class="form-control input-sm" placeholder="Solo Números" autocomplete="off"';
								if ( ($key->Medicion_Actual != null)  &&  ($key->Medicion_Actual != 0)  )
									$cadena.=  ' value=" '.$key->Medicion_Actual. ' " ';
								$cadena.=  '>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputExcedente_'.$i.'">Excedente </label>
								<input type="hidden" id="inputExcedente_'.$i.'" name="inputExcedente_'.$i.'" class="form-control input-sm" readonly autocomplete="off"';
								if ( ($key->Medicion_Excedente != null)  &&  ($key->Medicion_Excedente != 0)  )
									$cadena.=  ' value=" '.$key->Medicion_Excedente. ' " ';
								else $cadena.=  'value= "" ';
								$cadena.=  '>
								<p id="paragraphExcedente_'.$i.'">';
								if ( ($key->Medicion_Excedente != null)  &&  ($key->Medicion_Excedente != 0)  )
									$cadena.=  $key->Medicion_Excedente;
								$cadena.=  '
								 </p>
								<input type="hidden" id="inputTipo_'.$i.'" name="inputTipo_'.$i.'" class="form-control input-sm"  value="'.$key->Conexion_Categoria.'" autocomplete="off" >
								<input type="hidden" id="inputMetrosbasicos_'.$i.'" name="inputMetrosbasicos_'.$i.'" class="form-control input-sm"  value="'.$metros_basicos.'" autocomplete="off" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputTipo_'.$i.'">Tipo Conexion </label>
								<p>'.$key->Conexion_Categoria.'</p>
							</div>
						</div>
					</div>
					<hr style="background-color: #fff;border-top: 2px dashed #8c8b8b;">
				</div>';
				$i++;
			}
		}
		
		$cantidad.= 
			'<div class="col-md-2">
				<input type="hidden" id="inputCantidad" name="inputCantidad" value ="'.$i.'" class="form-control input-sm" readonly>
			</div>
			<script  type="text/javascript">
				$(document).ready(function(){
					$("[id^=\'inputMedicionActual_\']").change(function(e){
						e.preventDefault();
						var id_actual_actual = $(this).attr("id");
						var carga_actual = $(id_actual_actual).val();
						var id_valor = "#"+id_actual_actual;
						var valor_acutal = $(id_valor).val();  //medicion actual q estoy cargando
						var indice_id = id_actual_actual.split("_");

						var id_valor_anterior =  "#inputMedicionAnterior_"+indice_id[1]; 
						var valor_anterior = $(id_valor_anterior).val(); //medicion anterior

						var mtrs = "#inputMetrosbasicos_"+indice_id[1];
						var metros = $(mtrs).val(); 
						var id_excedente_actual = "#inputExcedente_"+indice_id[1]; 
						var id_p_excedente_actual = "#paragraphExcedente_"+indice_id[1]; 
						
						var valor =  parseFloat(valor_acutal) - parseFloat(valor_anterior);
						if( (valor >= 0) && (valor <= metros) )// entre 0 y metros basicos
							valor= 0;
						if(valor > metros)
							valor = parseFloat(valor) - parseFloat(metros);
						if(valor < 0){
							$(id_excedente_actual).css("background", "#E84F08");
							$(id_excedente_actual).css("border-color", "#E84F08");
							$(id_p_excedente_actual).css("background", "#FF4500");
							$(id_p_excedente_actual).css("border-color", "red");
							//$("#cantidad_errores_en_medicion_actual").val(parseInt($("#cantidad_errores_en_medicion_actual").val())+1);
						}
						else {
							//puede se q sea correcto pero excedido
							if( parseInt(valor) > parseInt(25) )
							{
								$(id_excedente_actual).css("background", "#F4A460");
								$(id_excedente_actual).css("border-color", "#F4A460");
								$(id_p_excedente_actual).css("background", "#FFAB05");
								$(id_p_excedente_actual).css("border-color", "#FFFA00");
							}
							else //sin exceso, es correcto
							{
								$(id_excedente_actual).css("background", "#F0F0F0");
								$(id_excedente_actual).css("border-color", "green");
								$(id_p_excedente_actual).css("background", "#00FA9A");
								$(id_p_excedente_actual).css("border-color", "green");
							}
							
						}
						$(id_excedente_actual).val(valor);
						$(id_p_excedente_actual).text(valor);
						//alert("actual:"+parseFloat(valor_acutal)+" metros:"+parseFloat(mtrs));
						});
				
				});
			</script>
			 ';
		$cadena .=$cantidad;
		echo $cadena;
	}
	public function guardar_mediciones_por_lotes_con_ajax(){
		$todas_las_variables = $this->Crud_model->get_data("configuracion");
		$cantidad = $this->input->post("cantidad_de_input", true);
		$mes = $this->input->post("mes_de_input");
		$anio = $this->input->post("anio_de_input");
		//var_dump($mes,$anio);die();


		for($i = 0; $i < $cantidad; $i++)
		{
			$imputConexionId = $this->input->post("inputConexionId_".$i, true);
			//$inputMedicionId = $this->input->post("inputMedicionId_".$i, true);
			
			$anterior = $this->input->post("inputMedicionAnterior_".$i, true);
			$actual = $this->input->post("inputMedicionActual_".$i, true);
			if($actual == null ) // si el valor no fue cargado
			{
				echo "        macutal con nada       --";
				continue;
			}	
			$inputExcedente = $this->input->post("inputExcedente_".$i, true); 
			$inputTipo = $this->input->post("inputTipo_".$i, true);
			if( ($inputTipo == 1) || ($inputTipo == "Familiar") || ($inputTipo =="Familiar ") )
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

			//$anterior = $key->Factura_MedicionAnterior;
			//$actual = $key->Factura_MedicionActual;
			$inputExcedente = intval($actual) - intval($anterior) - intval($metros_basicos);
			if($inputExcedente < 0 )
				$inputExcedente = 0;
			$importe_medicion = 0;
			if($inputExcedente == 0)
				$importe_medicion = 0;
			else $importe_medicion = floatval($precio_metros) * floatval($inputExcedente);

			$datos_medicion = array(
				'Factura_MedicionAnterior' => intval($anterior),
				'Factura_MedicionActual' => intval($actual),
				'Factura_ExcedentePrecio' => floatval($importe_medicion),
				'Factura_Excedentem3' => $inputExcedente,
				'Factura_MedicionTimestamp' => date("Y-m-d H:i:s")
				);
			//$resultado = $this->Crud_model->update_data($datos_medicion, $inputMedicionId, "medicion", "Medicion_Id");
			$resultado = $this->Nuevo_model->update_data_tres_campos($datos_medicion, $imputConexionId, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
			echo $resultado;
		}
	}
	public function corregir_boletas_por_sector($sectores = 0, $mes = 0, $anio = 0)
	{
		//viene si o si po parametro
		// if($sectores == 0 )
		// 	$sectores = $this->input->post('select_tablet');
		// if($mes == 0 )
		// 	$mes = $this->input->post('mes');
		// if($anio == 0 )
		// 	$anio = $this->input->post('anio');
		// if($sectores === 0 )
		// 	{
		// 		echo "Error";die();
		// 	}
		// elseif($sectores == "A")
		// 	$sectores = [ "A", "Jardines del Sur", "Aberanstain", "Medina", "Salas", "Santa Barbara" , "V Elisa"];
		// else $sectores = [ "B", "C", "David", "ASENTAMIENTO OLMOS", "Zaldivar" ];
		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		$mediciones_desde_query =  $this->Nuevo_model->get_sectores_query_corregir($sectores, $mes, $anio );
		//var_dump($mediciones_desde_query);die();
		if($mediciones_desde_query != false)
		{
			$indice_actual = 0;
			foreach ($mediciones_desde_query as $key ) {
				if( ($key->Factura_MedicionAnterior == 0) && ($key->Factura_MedicionActual == 1) ) // bandera de tablet
					continue;
				if( ( floatval($key->Factura_PagoMonto) != floatval(0)) && ($key->Factura_PagoContado != NULL) && ($key->Factura_PagoContado != NULL) ) //si esta pagada no se re calcula
					continue;
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
										+ floatval($key->Factura_Multa);
				$total  = $sub_total;
				$bonificacion = 0;
				if($key->Conexion_Deuda == 0)
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
					'Factura_Total' => floatval($total),
					'Factura_Vencimiento1_Precio' => floatval($vto_1_precio),
					'Factura_Vencimiento2_Precio' => floatval($vto_2_precio),
					'Factura_ExcedentePrecio' => floatval($importe_medicion),
					'Factura_Excedentem3' => $inputExcedente
					 );
				$resultado[$indice_actual] = $this->Nuevo_model->update_data_tres_campos($datos_factura_nueva, $key->Conexion_Id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
				var_dump($datos_factura_nueva,$key->Conexion_Id);
			}
			var_dump($datos_factura_nueva);
		}
		else
			var_dump("Error. no hay medciones para las variables");	
	}
	/***************************************************************
	****************************************************************
							FIN MEDICIONES
	****************************************************************
	*****************************************************************/





















	/***************************************************************
	****************************************************************
							INICIO	UNA VEZ
	****************************************************************
	*****************************************************************/

	public function pasar_mediciones_a_facturacion_nueva($mes = -1, $ano= -1, $sector = -1){
		//$data ["usuarios"] = $this->Nuevo_model->traer_facturas_por_barrio_nuevo($sector, $mes , $ano, $id_conexion, $pagado);
		$mediciones_desde_query =  $this->Nuevo_model->get_sectores_query($sector, $mes, $ano );
		//var_dump($mediciones_desde_query);die();
		$indice_actual = -1;
		$resultado = array( );
		foreach ($mediciones_desde_query as $key) {
			$indice_actual++;
			$datos_factura_nueva = array(
				"Factura_Mes" => $key->Medicion_Mes,
				"Factura_Año" => $key->Medicion_Anio,
				'Factura_ExcedentePrecio' => $key->Medicion_Importe,
				'Factura_MedicionAnterior' => $key->Medicion_Anterior,
				'Factura_MedicionActual' => $key->Medicion_Actual,
				'Factura_Basico' => $key->Medicion_Mts,
				'Factura_Excedentem3' => $key->Medicion_Excedente,
				'Factura_MedicionTimestamp' => $key->Medicion_Timestamp
				 );
			$resultado[$indice_actual] = $this->Nuevo_model->update_data($datos_factura_nueva, $key->Medicion_Conexion_Id, "facturacion_nueva","Factura_Conexion_Id");
		}
		var_dump($resultado);die();
	}
	public function migrar_factura_a_facturacion_nuevo($sector=0)
	{
		//$this->load->model('Facturar_model');
		//var_dump(date("m"));
		$log = '';
		$facturas_mes_anterior_tabla_anterior =  $this->Nuevo_model->traer_mediciones_tabla_vieja($sector);
		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		
		//var_dump($facturas_mes_anterior_tabla_anterior);die();
		/*COasa que debo hacer

		1 - Traer las mediciones del mes anterior para el sector solicitado
		2 - Crear las variables necesarias

		3 - Definir los vencimientos por configuracion

		4 - Creo e inserto una row de facturacion_nueva

		5 - Recalcular valores como_ codigo de barra,

		*/
		$indice_actual = -1;
		$resultado = array( );
		foreach ($facturas_mes_anterior_tabla_anterior as $key) {
			//veo si la factura ya existe, si es asi entonces salto
			//$aux = $this->Nuevo_model->get_data_tres_campos("facturacion_nueva", 'Factura_Mes', 3,'Factura_Año', 2018, 'Factura_Conexion_Id', $key["Conexion_Id"]);
			//var_dump($key["Conexion_Id"],$aux);die();
			if($this->Nuevo_model->get_data_tres_campos("facturacion_nueva", 'Factura_Mes', 3,'Factura_Año', 2018, 'Factura_Conexion_Id', $key["Conexion_Id"]) != false )
				continue;//significa que encontre la factura en facturacion nueva
		//calculo de las variables q voy a usar en para crear la factura
		if ( ($key["Conexion_Categoria"] ==  "Familiar") || ($key["Conexion_Categoria"] ==  1) || ($key["Conexion_Categoria"] =="Familiar ") )
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
		if($key["Conexion_Latitud"] == 1)
			$riego = floatval($todas_las_variables[17]->Configuracion_Valor);
		else $riego = floatval(0);

		$plan_medidor_couta_actual = 0; //recinicio el plan xq se acabo
		$cantidad_de_cuotas_medidor = 0;//recinicio el plan xq se acabo
		$pracio_de_cuota_medidor = 0;//recinicio el plan xq se acabo

		$plan_pag_couta_actual = 0; //recinicio el plan xq se acabo
		$cantidad_de_cuotas_planpago = 0;//recinicio el plan xq se acabo
		$precio_de_planpago = 0;//recinicio el plan xq se acabo

		/*
		$plan_medidor_couta_actual = 0;
		$cantidad_de_cuotas_medidor = $key["Factura_PM_Cant_Cuotas"];
		$pracio_de_cuota_medidor = $key["Factura_PM_Cuota_Precio"];
		if( is_numeric($key["Factura_PM_Cant_Cuotas"]) && ($key["Factura_PM_Cant_Cuotas"]  > 0) )
			if($key["Factura_PM_Cant_Cuotas"] < $key["Factura_PM_Cuota_Actual"]) // significa que ya termine de pagar
				{
					$plan_medidor_couta_actual = 0; //recinicio el plan xq se acabo
					$cantidad_de_cuotas_medidor = 0;//recinicio el plan xq se acabo
					$pracio_de_cuota_medidor = 0;//recinicio el plan xq se acabo
				}
			else //sigifica que tengo q seguir pagando la cuota
				$plan_medidor_couta_actual = intval($key["Factura_PM_Cuota_Actual"])+1;
		$plan_pag_couta_actual = 0;
		$cantidad_de_cuotas_planpago = $key["Factura_PP_Cant_Cuotas"];
		$precio_de_planpago = $key["Factura_PPC_Precio"];
		if( is_numeric($key["Factura_PP_Cant_Cuotas"]) && ($key["Factura_PP_Cant_Cuotas"]  > 0) ) //  de ser verdad existe el plan de pago
			if($key["Factura_PP_Cant_Cuotas"] < $key["Factura_PP_Cuota_Actual"]) // significa que ya termine de pagar
				{
					$plan_pag_couta_actual = 0; //recinicio el plan xq se acabo
					$cantidad_de_cuotas_planpago = 0;//recinicio el plan xq se acabo
					$precio_de_planpago = 0;//recinicio el plan xq se acabo
				}
			else //sigifica que tengo q seguir pagando la cuota
				$plan_pag_couta_actual = intval($key["Factura_PP_Cuota_Actual"])+1;
		*/
		$subtotal = floatval($key["Conexion_Deuda"]) + floatval($precio_bsico) +floatval($todas_las_variables[9]->Configuracion_Valor) +floatval($pracio_de_cuota_medidor) +floatval($precio_de_planpago) +floatval($riego) ;
		$acuenta = $key["Conexion_Acuenta"];
		if($acuenta == NULL)
			$acuenta = floatval(0);
		$bonificacion = 0; // despues de haber caqrgado la medicion se re calcucla la bonificacion
		$acutal = $key["Medicion_Actual"];
		if($acutal == 0)
			$acutal = $key["Medicion_Anterior"];
		//PASO 2 CREAR NUEVA FACTURA
		$indice_actual++;
		$datos = array(
			"Factura_Id" => null,
			"Factura_CodigoBarra" => "111111",// se calcula despues
			"Factura_Cli_Id" => $key["Cli_Id"],
			"Factura_Conexion_Id" => $key["Factura_Conexion_Id"],

			"Factura_FechaEmision" => date("Y-m-d H:i:s"),
			"Factura_Mes" => 3,
			"Factura_Año" => date("Y"),

			"Factura_TarifaSocial" => $precio_bsico, //valor ed tarifa social
			"Factura_ExcedentePrecio" => null , //no lo tengo aun
			"Factura_CuotaSocial" => $todas_las_variables[9]->Configuracion_Valor,

			"Factura_PM_Cant_Cuotas" => $cantidad_de_cuotas_medidor, //calcula arriba
			"Factura_PM_Cuota_Actual" => $plan_medidor_couta_actual, //calcula arriba
			"Factura_PM_Cuota_Precio" => $pracio_de_cuota_medidor, //calculado arriba

			"Factura_PP_Cant_Cuotas" => $plan_pag_couta_actual,
			"Factura_PP_Cuota_Actual" => $cantidad_de_cuotas_planpago,
			"Factura_PPC_Precio" => $precio_de_planpago,

			"Factura_Riego" => $riego,

			"Factura_SubTotal" => $subtotal,

			"Factura_Acuenta" => $acuenta, // lo  traigo desde la conexion
			"Factura_Bonificacion" => $bonificacion, // luego se recalcula
			
			"Factura_Total" => floatval(0),

			"Factura_Vigencia" => $todas_las_variables[19]->Configuracion_Valor,
			"Factura_Vencimiento1" => $todas_las_variables[20]->Configuracion_Valor,
			"Factura_Vencimiento1_Precio" => floatval(0),
			"Factura_Vencimiento2" => $todas_las_variables[21]->Configuracion_Valor,
			"Factura_Vencimiento2_Precio" => floatval(0),

			'Factura_MedicionAnterior' => $acutal, // el del mes anterior
			'Factura_MedicionActual' => 0, //bandera de null
			'Factura_Basico' => $metros_basicos, // calculado arriba
			'Factura_Excedentem3' =>  0, //bandera de null
			'Factura_MedicionTimestamp' => null, //toavia no acrgo la medicion

			'Factura_PagoLugar' => null,  //toavia no acrgo la pago
			'Factura_PagoMonto' => null,//toavia no acrgo la pago
			'Factura_PagoContado' => null,//toavia no acrgo la pago
			'Factura_PagoCheque' => null,//toavia no acrgo la pago
			'Factura_PagoTimestamp' => null,//toavia no acrgo la pago

			'Factura_Habilitacion' => 1,
			'Factura_Borrado' => 0,
			'Factura_Timestamp' => null

		);
		//var_dump($datos);die();
		$resultado[$indice_actual] = $this->Nuevo_model->insert_data("facturacion_nueva",$datos); 
		//Paso 3 
		//recalcular codigo de barra
		$codigo_barra = $this->calcular_codigo_barra_agua($key["Conexion_Id"], $resultado[$indice_actual] );
		$arrayName = array(
			'Factura_CodigoBarra' => substr($codigo_barra, 0,-1),
			 );
		$actualizacion_codigo_barra  = $this->Nuevo_model->update_data($arrayName, $resultado[$indice_actual], "facturacion_nueva" ,"Factura_Id");

		//paso 4 veo lo de la deuda
		// $deuda_arrastrada = 0;
		// if( ($key["Factura_PagoMonto"] == $key["Factura_Total"]) && ($key["Factura_PagoMonto"] !=  0) &&  ($key["Factura_PagoMonto"] !=  null) )//significa que pague toda la deuda
		// 	$deuda_arrastrada = 0;
		// else // no pague todo
		// 	$deuda_arrastrada = floatval($key["Factura_Total"]) - floatval($key["Factura_PagoMonto"]);
		// $arrayName = array(
		// 	'Conexion_Deuda' => $deuda_arrastrada,
		// 	 );
		// $actualizacion_deuda = $this->Nuevo_model->update_data($arrayName, $key["Conexion_Id"], "conexion" ,"Conexion_Id");
		$log .= "   _    Agrege la conexion : ".$key["Conexion_Id"]."   /\ \n\r   _ ";
		//	var_dump($actualizacion_codigo_barra);die();
		}
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
		else $numero .= ".00";
		//return str_replace(".", ",",$numero);
		return $numero;
	}
	public function calcular_valores_a_facturar_nuevo($key,$datos, $key_boleta)
	{
		//var_dump($key);die();
		$data["monto_cuota_medidor"] = 0;
		if(isset($key->PlanMedidor_MontoCuota) && ($data["monto_cuota_medidor"] == null))
			$data["monto_cuota_medidor"] = $key->PlanMedidor_MontoCuota ;
		$data["monto_cuota_plan_pago"] = 0;
		if(isset($key->PlanPago_MontoCuota) && ($data["monto_cuota_plan_pago"] == null) )
			$data["monto_cuota_plan_pago"] = $key->PlanPago_MontoCuota ;
		$precio_riego = 0;
		if(isset($key->Conexion_Latitud) && $key->Conexion_Latitud == "1")
			$precio_riego = floatval ($datos[17]->Configuracion_Valor);
		$data["precio_riego"] = $precio_riego;
		if( isset($key->Conexion_Categoria) && ($key->Conexion_Categoria == 1) || ($key->Conexion_Categoria == "Familiar") || ($key->Conexion_Categoria =="Familiar ") )
			$tarifa_social = $datos[4]->Configuracion_Valor ;
		else $tarifa_social = $datos[7]->Configuracion_Valor;
		$data["tarifa_social"] = $tarifa_social;
		$data["Medicion_Importe"] = $key->Medicion_Importe;
		$data["cuota_social"] = $datos[2]->Configuracion_Valor;
		$data["deuda"] = $key->Conexion_Deuda;
		$data["subtotal_sin_bonificacion"] = floatval($key->Conexion_Deuda) + 
			floatval ($tarifa_social)  + 
			floatval ($key->Medicion_Importe)  +   
			floatval ($datos[2]->Configuracion_Valor) +
			floatval ($data["monto_cuota_medidor"])  +
			floatval ($data["monto_cuota_plan_pago"]) +
			$precio_riego;
			//var_dump( floatval($key->Conexion_Deuda), floatval($tarifa_social), floatval($key->Medicion_Importe), floatval($datos[2]->Configuracion_Valor), floatval($data["monto_cuota_medidor"]), floatval($data["monto_cuota_plan_pago"]), $precio_riego);die();
		if( $key->Conexion_Deuda == 0)//aplico descuento en el subtotal
			$data["subtotal_con_bonificacion"] = floatval($data["subtotal_sin_bonificacion"]) - ( (floatval ($key->Medicion_Importe) + floatval($tarifa_social)) * floatval(0.05) ) ;//con bonificacion
		else $data["subtotal_con_bonificacion"] = floatval($data["subtotal_sin_bonificacion"]); // sin bonificacion
		$data["total"] = floatval( $data["subtotal_con_bonificacion"]) - floatval($key->Conexion_Acuenta);
		
		$data["total"] = $this->arreglar_numero($data["total"]);
		$data["subtotal_con_bonificacion"] = $this->arreglar_numero($data["subtotal_con_bonificacion"]);
		$data["subtotal_sin_bonificacion"] = $this->arreglar_numero($data["subtotal_sin_bonificacion"]);  
		$data["monto_cuota_medidor"] = $this->arreglar_numero($data["monto_cuota_medidor"]);  
		$data["monto_cuota_plan_pago"] = $this->arreglar_numero($data["monto_cuota_plan_pago"]);  
		return $data;
	}

	public function calcular_deudas_a_facturacion_nueva($sector)
	{
		/* Cuidado no correr varias veces*/
		$log = '';
		$conexiones_del_sector =  $this->Nuevo_model->get_data_dos_campos("conexion","Conexion_Borrado",0, "Conexion_Sector",$sector);
		//var_dump($conexiones_del_sector[0]->Conexion_Id);die();
		$i=0;
		foreach ($conexiones_del_sector as $key) {
			echo "Vuelta:".$i;
			//voy a buscar la factura para el mes anterior de esta medicion
			$factura_mes_anterior_tabla_anterior =  $this->Nuevo_model->traer_factura_tabla_vieja($key->Conexion_Id);
			//$factura_mes_anterior_tabla_anterior =  $this->Nuevo_model->traer_todas_facturas_tabla_vieja();
			if($factura_mes_anterior_tabla_anterior == false)
			{
				echo "-                    Sin boleta. Conexion:".$key->Conexion_Id."                  -";
				continue;
			}
				
				//{var_dump($key->Conexion_Id, $factura_mes_anterior_tabla_anterior);die();}
			$i++;
			// if($factura_mes_anterior_tabla_anterior[0]["Pago_Monto"] == null)
			// {
				//var_dump($i, $factura_mes_anterior_tabla_anterior);die();
				//calcular el monto de la boleta.
				//ver como lo hace el ePDF o en base a eso ver si pago o no el mismo monto o  algo parecido, 
				$datos_viejos = $this->Nuevo_model->buscar_lote_por_sectores($key->Conexion_Id, 2, 2018); // voy a crear la factura de marzo (3) por eso busco febrero (2)
				$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
				$deuda = $this->Nuevo_model->buscar_deuda_conexion($key->Conexion_Id);
				$valores = $this->calcular_valores_a_facturar_nuevo($datos_viejos[0],$datos["configuracion"], $datos_viejos[0]);
				//var_dump($valores["total"], $datos_viejos[0]->Pago_Monto);die();
				//compuebo si existe el pago, si existe, entonces no agrego deuda xq eso se hizo cuando se calculo el pago de la boleta
				if( ($datos_viejos[0]->Pago_Monto != null) && ($datos_viejos[0]->Pago_Monto != 0) )
				{	// entonces si pago y no se calcula la deuda, xq ya se hizo antes

					// no hago nada x ya se pago
					echo "-              boleta paga                  -  ";
					$resultado_actualizacion  = "-              pago bien              -";
				}
				else // significa q no pago nada y tiene q pasar el saldo de la boleta a deuda 
				{//voy a buscar la factura_nueva 
					//$factura_nueva = $this->Nuevo_model->get_data_tres_campos("facturacion_nueva","Factura_Conexion_Id",$key->Conexion_Id, "Factura_Mes",3, "Factura_Año",2018);
					//calculo la deuda de la conexion	$deuda_a_grabar =  floatval($datos_conexion[0]->Conexion_Deuda) + floatval($request["endeuda"]);
					$deuda_a_grabar = floatval($valores["total"]);
					//voy a guardar la deuda para la conexion q no pago su boleta
					$datos_a_actualizar = array(
						'Conexion_Deuda' => floatval($deuda_a_grabar),
						 );
					$resultado_actualizacion = $this->Nuevo_model->update_data($datos_a_actualizar, $key->Conexion_Id, "Conexion","Conexion_Id");
					echo "-              Debe:".$valores["total"]."  +  Deuda Anterior: ".$key->Conexion_Deuda."  . Conexion:".$key->Conexion_Id." Resultado:".$resultado_actualizacion."                -";
				}
				
			}
	}

	public function comparar_deudas_factura_conexion()
	{
		$log = '';
		$sectores = [ "A", "Jardines del Sur", "Aberanstain", "Medina", "Salas", "Santa Barbara" , "V Elisa", "B", "C", "David", "ASENTAMIENTO OLMOS", "Zaldivar" ];
		foreach ($sectores as $sector) {
			echo "<br><br><hr><hr><br><br> <font color='orange'>SECTOR BUSCADO ".$sector."</font>     <br><br>                  ..";
			$conexiones_del_sector =  $this->Nuevo_model->traer_facturas_por_barrio_nuevo($sector,4, 2018,-1 ,-1);
			$i=0;
			foreach ($conexiones_del_sector as $key) {
				echo "     -         ".$key["Conexion_Id"].". Valores: ConDeuda". floatval($key["Conexion_Deuda"])." y FacDeu:". floatval($key["Factura_Deuda"])." / -";
				if( floatval($key["Conexion_Deuda"]) == floatval($key["Factura_Deuda"]))
					echo " <font color='green'>Iguales</font>                       <br>";
				else
				{
					echo " <font color='red'>DIferentes  </font>";
					if($key["Factura_PagoMonto"]!= null)
						echo " <font color='green'>PAGO</font>                     <br>";
					else 
						{
							echo " <font color='red'> NO PAGO </font> ";
							//ARREGLANDO
							$arrayName = array('Factura_Deuda' => floatval($key["Conexion_Deuda"]), );
							$resultado = $this->Nuevo_model->update_data_tres_campos($arrayName, $key["Conexion_Id"] , "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", 4, "Factura_Año", 2018);
							echo " Resultado".$resultado."          <br>";
						}
				}
			}
		}
	}
	public function recaulcular_deudas_mayo()
	{
		$boletas_de_mayo = $this->Nuevo_model->get_data_tres_campos("facturacion_nuevaa","Factura_Borrado",0, "Factura_Mes", 5, "Factura_Año",2018) ;
		//var_dump($boletas_de_mayo);
		$i=0;
		foreach ($boletas_de_mayo as $key) {
			echo "\$datos_boletas [".$i."] [0]=".$key->Factura_Id.";<br>";
			echo "\$datos_boletas [".$i."] [1]=".$key->Factura_Deuda.";<br>";
			// echo "(<br>";
			// echo "&emsp;array( <br>";
			// echo "&emsp;\"Factura_Id\" => ".$key->Factura_Id.", <br>";
			// echo "&emsp;\"Factura_Deuda\" => ".$key->Factura_Deuda." <br>";
			// echo "&emsp;) <br>";
			// echo "); <br>";
			$i++;
		}
		//$datos_planes [2] =
	// 	( 
	// 		array(
	// 		"cliente_id" => 62,
	// 		"conexion_id" => 2,
	// 		"monto_cuota" =>0
	// 		)
	// 	);
	}
	
	public function cargar_mediciones_fake_inicio_sistema($sectores=0)
	{
		//HECHO EL 26-6-2020
		//Corregido el 29-6-20
		/*COasa que debo hacer

		1 -  Preparo las variables q voy a usar y Traer las facturacion_nuevo para todo un ssector entero

		2 - itero sobre cada una de las facturas

		3 - Creo las variables fake

		4 - creo la boleta actualizada

		5- actualizo en la bd
		*/
		/*
		Como se usa:
		Se debe acceder a la direccion: localhost/codeigniter/nuevo/cargar_mediciones_fake_inicio_sistema/$sector
		y se correra para el sector que se le pase por parametro o el q este descomenado en las proximas lineas (de esta manera lo corri
		por ultima vez , es decir correrlo tantas veces como sectores e ir cambiando el sector por codigo)

		MEJORA: hacer un for q englobe todo y corra tantas veces como sectores haya y se tenga q correr una sola vez
		
		*/

		//PASO 1 -  Preparo las variables q voy a usar y Traer las facturacion_nuevo para todo un ssector entero
		$faker = Faker\Factory::create();
		//esta variable debe cambiarse para cada sector, se debe ir cambian el valor por codigo
		$sectores = "Santa Barbara";
		//$sectores = [ "A", "Jardines del Sur", "Aberanstain", "Medina", "Salas", "Santa Barbara" , "V Elisa"];
		//$sectores = [ "B", "C", "David", "ASENTAMIENTO OLMOS", "Zaldivar" ];
		$facturas =  $this->Nuevo_model->get_sectores_query_corregir($sectores, 6, 2020);  // traigo las  facturas q cree en el paso 2 del wizard
		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		$indice_actual = -1;
		$resultado = array( );
		//Paso 2 - itero sobre cada una de las facturas
		foreach ($facturas as $key) {
			//PASO 3 - Creo las variables fake
			//creo las variables fakers
			if ( ($key->Conexion_Categoria ==  "Familiar") || ($key->Conexion_Categoria ==  1) || ($key->Conexion_Categoria =="Familiar ") )
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
			if($key->Conexion_Latitud == 1)
				$riego = floatval($todas_las_variables[17]->Configuracion_Valor);
			else $riego = floatval(0);
			//creo planes fakes
			$Factura_PM_Cant_Cuotas = intval($faker->numberBetween($min = 0, $max = 12));
			if($Factura_PM_Cant_Cuotas == 0 )
			{
				$Factura_PM_Cuota_Actual = 0;
				$Factura_PM_Cuota_Precio = 0;
			}
			else
			{
				$Factura_PM_Cuota_Actual = intval($faker->numberBetween($min = 0, $max = $Factura_PM_Cant_Cuotas));
				$Factura_PM_Cuota_Precio = intval($faker->numberBetween($min = 0, $max = 2587));
			}
			//creo planes fakes
			$Factura_PP_Cant_Cuotas = intval($faker->numberBetween($min = 0, $max = 12));
			if($Factura_PP_Cant_Cuotas == 0 )
			{
				$Factura_PP_Cuota_Actual = 0;
				$Factura_PPC_Precio = 0;
			}
			else
			{
				$Factura_PP_Cuota_Actual = intval($faker->numberBetween($min = 0, $max = $Factura_PP_Cant_Cuotas));
				$Factura_PPC_Precio = intval($faker->numberBetween($min = 0, $max = 2587));
			}
			$subtotal = floatval($key->Conexion_Deuda) + floatval($precio_bsico) +floatval($todas_las_variables[9]->Configuracion_Valor) +floatval($Factura_PM_Cuota_Precio) +floatval($Factura_PPC_Precio) +floatval($riego) ;
			$acuenta = $key->Conexion_Acuenta;
			if($acuenta == NULL)
				$acuenta = floatval(0);
			$bonificacion = 0; // despues de haber caqrgado la medicion se re calcucla la bonificacion
			$medicion_anterior = intval($faker->numberBetween($min = 0, $max = 10000));// no existe asiq creo cualquiera
			$medicion_actual = $medicion_anterior + intval($faker->numberBetween($min = 0, $max = 2000));// no existe asiq creo cualquiera
			if($medicion_actual == 0 )
				$medicion_actual = $medicion_anterior * 2;
			//PASO 4 - creo la boleta actualizada
			$indice_actual++;
			$datos = array(
				"Factura_TarifaSocial" => $precio_bsico, //valor ed tarifa social
				"Factura_CuotaSocial" => $todas_las_variables[9]->Configuracion_Valor,
				"Factura_PM_Cant_Cuotas" => $Factura_PM_Cant_Cuotas, //calcula arriba
				"Factura_PM_Cuota_Actual" => $Factura_PM_Cuota_Actual, //calcula arriba
				"Factura_PM_Cuota_Precio" => $Factura_PM_Cuota_Precio, //calculado arriba
				"Factura_PP_Cant_Cuotas" => $Factura_PP_Cant_Cuotas,
				"Factura_PP_Cuota_Actual" => $Factura_PP_Cuota_Actual,
				"Factura_PPC_Precio" => $Factura_PPC_Precio,
				"Factura_Riego" => $riego,
				"Factura_SubTotal" => $subtotal,
				"Factura_Acuenta" => $acuenta, // lo  traigo desde la conexion
				"Factura_Bonificacion" => $bonificacion, // luego se recalcula
				"Factura_Total" => floatval(0),
				"Factura_Vigencia" => $todas_las_variables[19]->Configuracion_Valor,
				"Factura_Vencimiento1" => $todas_las_variables[20]->Configuracion_Valor,
				"Factura_Vencimiento1_Precio" => floatval(0),
				"Factura_Vencimiento2" => $todas_las_variables[21]->Configuracion_Valor,
				"Factura_Vencimiento2_Precio" => floatval(0),
				'Factura_MedicionAnterior' => $medicion_anterior, // el del mes anterior
				'Factura_MedicionActual' => $medicion_actual, //bandera de null
				'Factura_Basico' => $metros_basicos, // calculado arriba
				'Factura_Excedentem3' =>  0, //bandera de null
				'Factura_MedicionTimestamp' => null, //toavia no acrgo la medicion
				'Factura_PagoLugar' => null,  //toavia no acrgo la pago
				'Factura_PagoMonto' => null,//toavia no acrgo la pago
				'Factura_PagoContado' => null,//toavia no acrgo la pago
				'Factura_PagoCheque' => null,//toavia no acrgo la pago
				'Factura_PagoTimestamp' => null,//toavia no acrgo la pago
				'Factura_Habilitacion' => 1,
				'Factura_Borrado' => 0,
				'Factura_Timestamp' => null
			);
			// PASO 5- actualizo en la bd
			$resultado[$indice_actual] = $this->Nuevo_model->update_data($datos, $key->Conexion_Id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", 6, "Factura_Año", 2020);
		}
		function update_data($datos, $id, $tabla,$campo){

			//Paso 3 
			//recalcular codigo de barra
			$codigo_barra = $this->calcular_codigo_barra_agua($key->Conexion_Id, $resultado[$indice_actual] );
			$arrayName = array(
				'Factura_CodigoBarra' => substr($codigo_barra, 0,-1),
				 );
			$actualizacion_codigo_barra  = $this->Nuevo_model->update_data($arrayName, $resultado[$indice_actual], "facturacion_nueva" ,"Factura_Id");

			//paso 4 veo lo de la deuda
			// $deuda_arrastrada = 0;
			// if( ($key["Factura_PagoMonto"] == $key["Factura_Total"]) && ($key["Factura_PagoMonto"] !=  0) &&  ($key["Factura_PagoMonto"] !=  null) )//significa que pague toda la deuda
			// 	$deuda_arrastrada = 0;
			// else // no pague todo
			// 	$deuda_arrastrada = floatval($key["Factura_Total"]) - floatval($key["Factura_PagoMonto"]);
			// $arrayName = array(
			// 	'Conexion_Deuda' => $deuda_arrastrada,
			// 	 );
			// $actualizacion_deuda = $this->Nuevo_model->update_data($arrayName, $key["Conexion_Id"], "conexion" ,"Conexion_Id");
			$log .= "   _    Agrege la conexion : ".$key["Conexion_Id"]."   /\ \n\r   _ ";
			//	var_dump($actualizacion_codigo_barra);die();
		}
	}
	/***************************************************************
	****************************************************************
							FIN	UNA VEZ
	****************************************************************
	*****************************************************************/

























	/***************************************************************
	****************************************************************
							INICIO	 INICIO DE MES
	****************************************************************
	*****************************************************************/
	/*
	Pasos de inicio:
	1 - Crear los registros del nuevo mes (nuevo/crear_nuevo_es_facturas)
	2 - Subir los datos a la tablet (con admin traer los nuevos registros del mes)
	3 - Cargar las mediciones con las tabler casa x casa
	4 - Descargar los datos de las tablet a la pc
	5 - Calcular los valores de Excm3 y Exc Precio (nuevo/corregir_mediciones)
	6 - Pasar las facturas impagas a Deuda conexion (nuevo/pasar_mes_impago_a_deuda_conexion)
	7 - Pasar las Deuda conexion a Factura_actual (nuevo/calcular_deudas_y_multas_a_facturacion_mes_nuevo)
	8 - Habilitar las bonificaciones realizadas (nuevo/)
	9 - Validar mediciones raras (nuevo/aprobar_medicion)
	10 - Recalcular Valores de las facturas actuales (nuevo/corregir_boletas)
	11 - Imprimir
	*/

	/*
	debira ser 1
	Esta funcion crea los registros de Factura_nueva para comenzar el inicio de mes
	*/




	/*
	back antes de cargar las mediciones de abril
	public function crear_nuevo_es_facturas($mes = -1, $ano= -1, $sector = -1){
		//$this->load->model('Facturar_model');
		//var_dump(date("m"));
		//$sector ="V Elisa";
		//$sector ="Santa Barbara";
		//$sector = "ASENTAMIENTO OLMOS";
		//$sector = "Jardines del Sur";
		$sector = "David";
		/*
		//$sectores = [ "A", "Jardines del Sur", "Aberanstain", "Medina", "Salas", "Santa Barbara" , "V Elisa"];
		//	else $sectores = [ "B", "C", "David", "ASENTAMIENTO OLMOS", "Zaldivar" ];
		$facturas_mess_anterior =  $this->Nuevo_model->traer_facturas_por_barrio_nuevo_todocampo($sector, $mes, $ano);
		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		// var_dump($facturas_mess_anterior);
		// die();
		/*COasa que debo hacer
		1 - Crear las variables necesarias
		2 - CREAR NUEVA FACTURA
		3 - Recalcular valores como_ codigo de barra,
		4 - VER SI PAGO LA BOLETA O SI LA CARGA COMO DEUDA
		5 - PASAR LAS MEDICIONES ACTUALES DEL MES ANTERIOR A LAS ACTUALES DEL MES NUEVO
		*

		$log = null;
		$indice_actual = -1;
		$resultado = array( );
		foreach ($facturas_mess_anterior as $key) {
			//calculo de las variables q voy a usar en para crear la factura
			if ( ($key["Conexion_Categoria"] ==  "Familiar") || ($key["Conexion_Categoria"] ==  1) || ($key["Conexion_Categoria"] =="Familiar ") )
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
			if($key["Conexion_Latitud"] == 1)
				$riego = floatval($todas_las_variables[17]->Configuracion_Valor);
			else $riego = floatval(0);
			$plan_medidor_couta_actual = 0;
			$cantidad_de_cuotas_medidor = $key["Factura_PM_Cant_Cuotas"];
			$pracio_de_cuota_medidor = $key["Factura_PM_Cuota_Precio"];
			if( is_numeric($key["Factura_PM_Cant_Cuotas"]) && ($key["Factura_PM_Cant_Cuotas"]  > 0) )
				if($key["Factura_PM_Cant_Cuotas"] < $key["Factura_PM_Cuota_Actual"]) // significa que ya termine de pagar
					{
						$plan_medidor_couta_actual = 0; //recinicio el plan xq se acabo
						$cantidad_de_cuotas_medidor = 0;//recinicio el plan xq se acabo
						$pracio_de_cuota_medidor = 0;//recinicio el plan xq se acabo
					}
				else //sigifica que tengo q seguir pagando la cuota
					$plan_medidor_couta_actual = intval($key["Factura_PM_Cuota_Actual"])+1;
			$plan_pag_couta_actual = 0;
			$cantidad_de_cuotas_planpago = $key["Factura_PP_Cant_Cuotas"];
			$precio_de_planpago = $key["Factura_PPC_Precio"];
			if( is_numeric($key["Factura_PP_Cant_Cuotas"]) && ($key["Factura_PP_Cant_Cuotas"]  > 0) ) //  de ser verdad existe el plan de pago
				if($key["Factura_PP_Cant_Cuotas"] < $key["Factura_PP_Cuota_Actual"]) // significa que ya termine de pagar
					{
						$plan_pag_couta_actual = 0; //recinicio el plan xq se acabo
						$cantidad_de_cuotas_planpago = 0;//recinicio el plan xq se acabo
						$precio_de_planpago = 0;//recinicio el plan xq se acabo
					}
				else //sigifica que tengo q seguir pagando la cuota
					$plan_pag_couta_actual = intval($key["Factura_PP_Cuota_Actual"])+1;
				
				/*Calculo la multa y le deuda*
				$multa = 0 ;
				if( (floatval($key["Conexion_Multa"]) == null) || (floatval($key["Conexion_Multa"]) <= 0))
					$multa = floatval($key["Conexion_Multa"] );
				$deuda_calculada = 0 ;
				if( (floatval($key["Conexion_Deuda"]) == null) || (floatval($key["Conexion_Deuda"]) <= 0))
					$deuda_calculada = floatval($key["Conexion_Deuda"]);

			$subtotal = floatval($deuda_calculada) + floatval($precio_bsico) +floatval($todas_las_variables[9]->Configuracion_Valor) +floatval($pracio_de_cuota_medidor) +floatval($precio_de_planpago) +floatval($riego) + floatval($multa);
			$acuenta = $key["Conexion_Acuenta"];
			if($acuenta == NULL)
				$acuenta = floatval(0);
			$bonificacion = 0; // despues de haber caqrgado la medicion se re calcucla la bonificacion
			
			//PASO 2 CREAR NUEVA FACTURA
			$indice_actual++;
			$datos = array(
				"Factura_Id" => null,
				"Factura_CodigoBarra" => "111111",// se calcula despues
				"Factura_Cli_Id" => $key["Factura_Cli_Id"],
				"Factura_Conexion_Id" => $key["Factura_Conexion_Id"],
				"Factura_FechaEmision" => date("Y-m-d H:i:s"),
				"Factura_Mes" => date("m"),
				"Factura_Año" => date("Y"),
				"Factura_Deuda" => $deuda_calculada, //valor ed tarifa social
				"Factura_TarifaSocial" => $precio_bsico, //valor ed tarifa social
				"Factura_ExcedentePrecio" => null , //no lo tengo aun
				"Factura_CuotaSocial" => $todas_las_variables[9]->Configuracion_Valor,
				"Factura_PM_Cant_Cuotas" => $cantidad_de_cuotas_medidor, //calcula arriba
				"Factura_PM_Cuota_Actual" => $plan_medidor_couta_actual, //calcula arriba
				"Factura_PM_Cuota_Precio" => $pracio_de_cuota_medidor, //calculado arriba
				"Factura_PP_Cant_Cuotas" => $plan_pag_couta_actual,
				"Factura_PP_Cuota_Actual" => $cantidad_de_cuotas_planpago,
				"Factura_PPC_Precio" => $precio_de_planpago,
				"Factura_Riego" => $riego,
				"Factura_Multa" => $multa,
				"Factura_SubTotal" => $subtotal,
				"Factura_Acuenta" => $acuenta, // lo  traigo desde la conexion
				"Factura_Bonificacion" => $bonificacion, // luego se recalcula
				"Factura_Descuento" => floatval(0),
				"Factura_Total" => floatval(0),
				"Factura_Vigencia" => $todas_las_variables[19]->Configuracion_Valor,
				"Factura_Vencimiento1" => $todas_las_variables[20]->Configuracion_Valor,
				"Factura_Vencimiento1_Precio" => floatval(0),
				"Factura_Vencimiento2" => $todas_las_variables[21]->Configuracion_Valor,
				"Factura_Vencimiento2_Precio" => floatval(0),
				'Factura_MedicionAnterior' => $key["Factura_MedicionActual"], // el del mes anterior
				'Factura_MedicionActual' => 0, //bandera de null
				'Factura_Basico' => $metros_basicos, // calculado arriba
				'Factura_Excedentem3' =>  0, //bandera de null
				'Factura_MedicionTimestamp' => null, //toavia no acrgo la medicion
				'Factura_PagoLugar' => null,  //toavia no acrgo la pago
				'Factura_PagoMonto' => null,//toavia no acrgo la pago
				'Factura_PagoContado' => null,//toavia no acrgo la pago
				'Factura_PagoCheque' => null,//toavia no acrgo la pago
				'Factura_PagoTimestamp' => null,//toavia no acrgo la pago
				'Factura_Habilitacion' => 1,
				'Factura_Borrado' => 0,
				'Factura_Timestamp' => null
			);
			//var_dump($datos);die();
			$resultado[$indice_actual] = $this->Nuevo_model->insert_data("facturacion_nueva",$datos); 
			//Paso 3 
			//recalcular codigo de barra
			$codigo_barra = $this->calcular_codigo_barra_agua($key["Conexion_Id"], $resultado[$indice_actual] );
			$arrayName = array(
				'Factura_CodigoBarra' => substr($codigo_barra, 0,-1),
				 );
			$actualizacion_codigo_barra  = $this->Nuevo_model->update_data($arrayName, $resultado[$indice_actual], "facturacion_nueva" ,"Factura_Id");
			//paso 4 veo lo de la deuda
			$deuda_arrastrada = 0;
			if( ($key["Factura_PagoMonto"] == $key["Factura_Total"]) && ($key["Factura_PagoMonto"] !=  0) &&  ($key["Factura_PagoMonto"] !=  null) )//significa que pague toda la deuda
				$deuda_arrastrada = 0;
			else // no pague todo
				$deuda_arrastrada = floatval($key["Factura_Total"]) - floatval($key["Factura_PagoMonto"]);
			$arrayName = array(
				'Conexion_Deuda' => $deuda_arrastrada,
				 );
			$actualizacion_deuda = $this->Nuevo_model->update_data($arrayName, $key["Conexion_Id"], "conexion" ,"Conexion_Id");
			//ahora actualizo la deuda de la boleta q estoy creando
			$arrayName = array(
				'Factura_Deuda' => $deuda_arrastrada,
				 );
			$actualizacion_deuda = $this->Nuevo_model->update_data($arrayName, $resultado[$indice_actual], "facturacion_nueva" ,"Factura_Id");
			$log .= "   _    Agrege la conexion : ".$key["Conexion_Id"]."   /\ \n\r   _ ";
		}
		var_dump($resultado);die();
	}*/
	public function crear_nuevo_es_facturas($mes = -1, $ano= -1, $sector = -1){
		//$this->load->model('Facturar_model');
		//var_dump(date("m"));
		if($sector == "JardinesdelSur")
			$sector = "Jardines del Sur";
		if($sector == "SantaBarbara")
			$sector = "Santa Barbara";
		if($sector == "VElisa")
			$sector = "V Elisa";
		if($sector == "ASENTAMIENTOOLMOS")
			$sector = "ASENTAMIENTO OLMOS";

		$facturas_mess_anterior =  $this->Nuevo_model->traer_facturas_por_barrio_nuevo_todocampo($sector, $mes, $ano);
		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		/*var_dump($facturas_mess_anterior);
		die();*/
		/*COasa que debo hacer
		1 - Crear las variables necesarias
		2 - CREAR NUEVA FACTURA
		3 - Recalcular valores como_ codigo de barra,
		4 - VER SI PAGO LA BOLETA O SI LA CARGA COMO DEUDA
		5 - PASAR LAS MEDICIONES ACTUALES DEL MES ANTERIOR A LAS ACTUALES DEL MES NUEVO
		*/
		$log = null;
		$indice_actual = -1;
		$resultado = array( );
		$hechos = array( );
		foreach ($facturas_mess_anterior as $key) {
			//calculo de las variables q voy a usar en para crear la factura
			if(
				$this->Nuevo_model->get_data_tres_campos("facturacion_nueva","Factura_Conexion_Id",$key["Factura_Conexion_Id"], "Factura_Mes", intval($mes) +1, "Factura_Año",$ano) 
				!= false
				)
			{

				continue;
				$log .= " -           Factura repetida      -";
			}
			if ( ($key["Conexion_Categoria"] ==  "Familiar") || ($key["Conexion_Categoria"] ==  1) || ($key["Conexion_Categoria"] =="Familiar ") )
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
			if($key["Conexion_Latitud"] == 1)
				$riego = floatval($todas_las_variables[17]->Configuracion_Valor);
			else $riego = floatval(0);
			$plan_medidor_couta_actual = 0;
			$cantidad_de_cuotas_medidor = $key["Factura_PM_Cant_Cuotas"];
			$pracio_de_cuota_medidor = $key["Factura_PM_Cuota_Precio"];
			if( is_numeric($key["Factura_PM_Cant_Cuotas"]) && ($key["Factura_PM_Cant_Cuotas"]  > 0) )
				if($key["Factura_PM_Cant_Cuotas"] < $key["Factura_PM_Cuota_Actual"]) // significa que ya termine de pagar
					{
						$plan_medidor_couta_actual = 0; //recinicio el plan xq se acabo
						$cantidad_de_cuotas_medidor = 0;//recinicio el plan xq se acabo
						$pracio_de_cuota_medidor = 0;//recinicio el plan xq se acabo
					}
				else //sigifica que tengo q seguir pagando la cuota
					$plan_medidor_couta_actual = intval($key["Factura_PM_Cuota_Actual"])+1;
			$plan_pag_couta_actual = 0;
			$cantidad_de_cuotas_planpago = $key["Factura_PP_Cant_Cuotas"];
			$precio_de_planpago = $key["Factura_PPC_Precio"];
			if( is_numeric($key["Factura_PP_Cant_Cuotas"]) && ($key["Factura_PP_Cant_Cuotas"]  > 0) ) //  de ser verdad existe el plan de pago
				if($key["Factura_PP_Cant_Cuotas"] < $key["Factura_PP_Cuota_Actual"]) // significa que ya termine de pagar
					{
						$plan_pag_couta_actual = 0; //recinicio el plan xq se acabo
						$cantidad_de_cuotas_planpago = 0;//recinicio el plan xq se acabo
						$precio_de_planpago = 0;//recinicio el plan xq se acabo
					}
				else //sigifica que tengo q seguir pagando la cuota
					$plan_pag_couta_actual = intval($key["Factura_PP_Cuota_Actual"])+1;
				
				/*Calculo la multa y le deuda*/
				$multa = 0 ;
				if( (floatval($key["Conexion_Multa"]) == null) || (floatval($key["Conexion_Multa"]) <= 0))
					$multa = floatval($key["Conexion_Multa"] );
				$deuda_calculada = 0 ;
				if( (floatval($key["Conexion_Deuda"]) == null) || (floatval($key["Conexion_Deuda"]) <= 0))
					$deuda_calculada = floatval($key["Conexion_Deuda"]);

			$subtotal = floatval($deuda_calculada) + floatval($precio_bsico) +floatval($todas_las_variables[9]->Configuracion_Valor) +floatval($pracio_de_cuota_medidor) +floatval($precio_de_planpago) +floatval($riego) + floatval($multa);
			$acuenta = $key["Conexion_Acuenta"];
			if($acuenta == NULL)
				$acuenta = floatval(0);
			$bonificacion = 0; // despues de haber caqrgado la medicion se re calcucla la bonificacion
			
			//PASO 2 CREAR NUEVA FACTURA
			$indice_actual++;
			$datos = array(
				"Factura_Id" => null,
				"Factura_CodigoBarra" => "111111",// se calcula despues
				"Factura_Cli_Id" => $key["Factura_Cli_Id"],
				"Factura_Conexion_Id" => $key["Factura_Conexion_Id"],
				"Factura_FechaEmision" => date("Y-m-d H:i:s"),
				"Factura_Mes" => date("m"),
				"Factura_Año" => date("Y"),
				"Factura_Deuda" => $deuda_calculada, //valor ed tarifa social
				"Factura_TarifaSocial" => $precio_bsico, //valor ed tarifa social
				"Factura_ExcedentePrecio" => null , //no lo tengo aun
				"Factura_CuotaSocial" => $todas_las_variables[9]->Configuracion_Valor,
				"Factura_PM_Cant_Cuotas" => $cantidad_de_cuotas_medidor, //calcula arriba
				"Factura_PM_Cuota_Actual" => $plan_medidor_couta_actual, //calcula arriba
				"Factura_PM_Cuota_Precio" => $pracio_de_cuota_medidor, //calculado arriba
				"Factura_PP_Cant_Cuotas" => $plan_pag_couta_actual,
				"Factura_PP_Cuota_Actual" => $cantidad_de_cuotas_planpago,
				"Factura_PPC_Precio" => $precio_de_planpago,
				"Factura_Riego" => $riego,
				"Factura_Multa" => $multa,
				"Factura_SubTotal" => $subtotal,
				"Factura_Acuenta" => $acuenta, // lo  traigo desde la conexion
				"Factura_Bonificacion" => $bonificacion, // luego se recalcula
				"Factura_Descuento" => floatval(0),
				"Factura_Total" => floatval(0),
				"Factura_Vigencia" => $todas_las_variables[19]->Configuracion_Valor,
				"Factura_Vencimiento1" => $todas_las_variables[20]->Configuracion_Valor,
				"Factura_Vencimiento1_Precio" => floatval(0),
				"Factura_Vencimiento2" => $todas_las_variables[21]->Configuracion_Valor,
				"Factura_Vencimiento2_Precio" => floatval(0),
				'Factura_MedicionAnterior' => $key["Factura_MedicionActual"], // el del mes anterior
				'Factura_MedicionActual' => 0, //bandera de null
				'Factura_Basico' => $metros_basicos, // calculado arriba
				'Factura_Excedentem3' =>  0, //bandera de null
				'Factura_MedicionTimestamp' => null, //toavia no acrgo la medicion
				'Factura_PagoLugar' => null,  //toavia no acrgo la pago
				'Factura_PagoMonto' => null,//toavia no acrgo la pago
				'Factura_PagoContado' => null,//toavia no acrgo la pago
				'Factura_PagoCheque' => null,//toavia no acrgo la pago
				'Factura_PagoTimestamp' => null,//toavia no acrgo la pago
				'Factura_Habilitacion' => 1,
				'Factura_Borrado' => 0,
				'Factura_Timestamp' => null
			);
			//var_dump($datos);die();
			$resultado[$indice_actual] = $this->Nuevo_model->insert_data("facturacion_nueva",$datos); 
			$hechos [$indice_actual] = $key["Factura_Conexion_Id"];
			//Paso 3 
			//recalcular codigo de barra
			$codigo_barra = $this->calcular_codigo_barra_agua($key["Conexion_Id"], $resultado[$indice_actual] );
			$arrayName = array(
				'Factura_CodigoBarra' => substr($codigo_barra, 0,-1),
				 );
			$actualizacion_codigo_barra  = $this->Nuevo_model->update_data($arrayName, $resultado[$indice_actual], "facturacion_nueva" ,"Factura_Id");
			//paso 4 veo lo de la deuda
			$deuda_arrastrada = 0;
			if(   

				 ($key["Factura_PagoMonto"] >= $key["Factura_Vencimiento1_Precio"]) //signifa q pago el vto1 o vto2

			      && 
			      ($key["Factura_PagoMonto"] !=  0)
			       &&  ($key["Factura_PagoMonto"] !=  null)
			       )//significa que pague toda la deuda
				$deuda_arrastrada = 0;
			else // no pague todo
			{

				$deuda_arrastrada = floatval($key["Factura_Vencimiento2_Precio"]) - floatval($key["Factura_PagoMonto"]);
			}
			$arrayName = array(
				'Conexion_Deuda' => $deuda_arrastrada,
				 );
			$actualizacion_deuda = $this->Nuevo_model->update_data($arrayName, $key["Conexion_Id"], "conexion" ,"Conexion_Id");
			//ahora actualizo la deuda de la boleta q estoy creando
			// $arrayName = array(
			// 	'Factura_Deuda' => $deuda_arrastrada,
			// 	 );
			// $actualizacion_deuda = $this->Nuevo_model->update_data($arrayName, $resultado[$indice_actual], "facturacion_nueva" ,"Factura_Id");
			$log .= "   _    Agrege la conexion : ".$key["Conexion_Id"]."   /\ \n\r   _ ";
		}


		/*escribo en wizard*/
		$mes = $mes++;//le sumo para q de el mes actual
		$paso_2 = $this->Nuevo_model->get_data_tres_campos("inicio_mes","IM_Mes",$mes, "IM_Anio",$ano,"IM_Paso",2);
		if( $paso_2 == false)
		{//creo el paso
			$datos = array(
				'IM_Id' => null,
				'IM_Mes' => $mes,
				'IM_Anio' => $ano,
				'IM_Paso' => 2,
				'IM_Hecho' => 1,
				'IM_SA' => null,
				'IM_SB' => null,
				'IM_SC' => null,
				'IM_SAberanstain' => null,
				'IM_SJardines' => null,
				'IM_SMedina' => null,
				'IM_SSalas' => null,
				'IM_SSanta' => null,
				'IM_SElisa' => null,
				'IM_SDavid' => null,
				'IM_SOlmos' => null,
				'IM_Timestamp' => null,
				'IM_Borrado' => 0,
				'IM_Quien' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->insert_data("inicio_mes", $datos);
		}
		else//modifico
		{
			$datos = array(
				'IM_Hecho' => 1,
				'IM_SA' => null,
				'IM_SB' => null,
				'IM_SC' => null,
				'IM_SAberanstain' => null,
				'IM_SJardines' => null,
				'IM_SMedina' => null,
				'IM_SSalas' => null,
				'IM_SSanta' => null,
				'IM_SElisa' => null,
				'IM_SDavid' => null,
				'IM_SOlmos' => null,
				'IM_Timestamp' => null,
				'IM_Borrado' => 0,
				'IM_Quien' => 1,
				);
			$resultado = $this->Crud_model->update_data_tres_campos($datos, $mes, "inicio_mes","IM_Mes", "IM_Anio", $ano, "IM_Paso",2);
		}

		echo json_encode($hechos);
		//die();
	}
	/*
	Esta funcion sirve para calcular los valores de Excm3, ExcPrecio Y el ExcTimestamp
	*/
	public function corregir_mediciones($sectores = 0, $mes = 0, $anio = 0)
	{
		//Usado: 26-6-20  - Diego
		//Corregido : 26-6-20 - Diego
		//Como se usa: se accede por url o por varias pag (p.e. wizard) y acceder definiendo los parametros , no afecta si se corre varias veces
		//Que es lo que hace: esta funcion se corrio desde url , pero por el wizard deberia andar bie, lo que hace es comprar las mediciones anterior y actual y calcula el excedente y el precio del mismo, luego actualiza en la bd.-
		/*Pasos que hace
		Paso 1 - re escribe el nombre de los sectores para que coincidan con los guardades en la bd
		Paso 2 - Busco los registros para los parametros (secotr, mes , año)
		Paso 3 - itero por cada factura para calcular
		Paso 4 - Calculo las variables necesarias para las facturas
		Paso 5 - creo object a guardar
		Paso 6 - actualizo en la bd
		*/
		//Paso 1 - re escribe el nombre de los sectores para que coincidan con los guardades en la bd
		//$sectores = "Aberanstain";
		//$sectores = "A";
		//$sectores = "B";
		//$sectores = "C";
		 //$sectores = "Medina";
		// $sectores = "Aberanstain";
		// $sectores = "Jardines del Sur";
		// $sectores = "Salas";
		 //$sectores = "Santa Barbara";
		// $sectores = "V Elisa";
		// $sectores = "David";
		// $sectores = "ASENTAMIENTO OLMOS";
		$sectores = "Zaldivar";
		if($sectores == "JardinesdelSur")
			$sectores = "Jardines del Sur";
		if($sectores == "SantaBarbara")
			$sectores = "Santa Barbara";
		if($sectores == "VElisa")
			$sectores = "V Elisa";
		if($sectores == "ASENTAMIENTOOLMOS")
			$sectores = "ASENTAMIENTO OLMOS";
		//$mes = $this->input->post('mes');
		//$anio = $this->input->post('anio');
		//var_dump($sectores, $mes, $anio);die();
		//Paso 2 - Busco los registros para los parametros (secotr, mes , año)
		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		$mediciones_desde_query =  $this->Nuevo_model->get_sectores_query_corregir($sectores, $mes, $anio );
		//var_dump($mediciones_desde_query);die();
		if($mediciones_desde_query != false)//reviso q hayan facturas
		{
			$indice_actual = 0;
			//Paso 3 - itero por cada factura para calcular
			foreach ($mediciones_desde_query as $key ) {
				if( ($key->Factura_MedicionAnterior == 0) && ($key->Factura_MedicionActual == 1) ) //significa q todavia no se carga la medicion y se salta
					continue;
				//Paso 4 - Calculo las variables necesarias para las facturas
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
				if($actual< $anterior) // caso de habe cambiado de medidor
					$inputExcedente = intval($actual) - intval($metros_basicos);
				else //caso mas comun
					$inputExcedente = intval($actual) - intval($anterior) - intval($metros_basicos);
				if($inputExcedente < 0 )
					$inputExcedente = 0;
				$importe_medicion = 0;
				if($inputExcedente == 0)
					$importe_medicion = 0;
				else $importe_medicion = floatval($precio_metros) * floatval($inputExcedente); // lo q se paso * el valor de cada mtr
				$indice_actual++; //´para decir cuantos se hicieron
				//Paso 5 - creo object a guardar
				$datos_factura_nueva = array(
					'Factura_ExcedentePrecio' => floatval($importe_medicion),
					'Factura_Excedentem3' => $inputExcedente,
					'Factura_MedicionTimestamp' => date("Y-m-d H:i:s")
					 );
				//Paso 6 - actualizo en la bd
				$resultado[$indice_actual] = $this->Nuevo_model->update_data_tres_campos($datos_factura_nueva, $key->Conexion_Id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
			}
			echo "true";
		}
		else
			//var_dump("Error. no hay medciones para las variables");	
			echo "true";
		
	}
	/*
	debira ser 2
	Esta funcion pasa del mes impago a la Conexion_Deuda
	*/
	public function pasar_mes_impago_a_deuda_conexion($sectores = 0, $mes = 0, $anio = 0)
	{
		//Usado: 
		//Corregido : 
		//Como se usa: se accede a la url con los parametros, desde wizard la llama en "paralelo"
		//Que es lo que hace: la funcion pasa el valor de la factura anteior, que no se pago o se pago en parte, y pasa a formar parte de la nueva factura,
		/*Pasos que hace
		Paso 1 - re escribe el nombre de los sectores para que coincidan con los guardades en la bd
		Paso 2 - Busco los registros para los parametros (secotr, mes , año)
		Paso 3 - itero por cada factura para calcular
		Paso 4 - veo si pago todo o le quedo un resto a pagar - lo cual sera deuda de la factura actual q es la q estoy actualizando
		Paso 5 - creo object a guardar
		Paso 6 - actualizo en la bd
		*/

		//	Paso 1 - re escribe el nombre de los sectores para que coincidan con los guardades en la bd//
		if($sectores == "JardinesdelSur")
			$sectores = "Jardines del Sur";
		if($sectores == "SantaBarbara")
			$sectores = "Santa Barbara";
		if($sectores == "VElisa")
			$sectores = "V Elisa";
		if($sectores == "ASENTAMIENTOOLMOS")
			$sectores = "ASENTAMIENTO OLMOS";
		if($sectores == null)
			$sectores = $this->input->post('select_tablet');
		if($mes == null)
			$mes = $this->input->post('mes');
		if($anio == null)
			$anio = $this->input->post('anio');
		if($sectores === 0 )
		{
			echo "Error";die();
		}
		//$sectores = "A";
		 //$sectores = "B";
		//$sectores = "C";
		 //$sectores = "Medina";
		// $sectores = "Aberanstain";
		//$sectores = "Jardines del Sur";
		//$sectores = "Salas";
		// $sectores = "Santa Barbara";
		// $sectores = "V Elisa";
		//$sectores = "David";
		 //$sectores = "ASENTAMIENTO OLMOS";
		// $sectores = "Zaldivar";
		//Paso 2 - Busco los registros para los parametros (secotr, mes , año)
		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		$mediciones_desde_query =  $this->Nuevo_model->traer_facturas_por_barrio_nuevo($sectores, $mes, $anio,null,null );
		//var_dump($mediciones_desde_query);die();
		$log = ''; // variable q guarda los resultados dentro de la iteracion para despues mostrarlos por pantalla
		//Paso 3 - itero por cada factura para calcular
		foreach ($mediciones_desde_query as $key) {
			$deuda_arrastrada = 0;
			//Paso 4 - veo si pago todo o le quedo un resto a pagar - lo cual sera deuda de la factura actual q es la q estoy actualizando
			if(   
				 ($key["Factura_PagoMonto"] >= $key["Factura_Total"]) //signifa q pago el vto1 o vto2
			      && 
			      ($key["Factura_PagoMonto"] !=  0)
			       &&  ($key["Factura_PagoMonto"] !=  null)
			       )//significa que pague toda la deuda
				{
					$deuda_arrastrada = 0;
					$log .= " -             SIn Deuda :".$key["Conexion_Id"]."            -    ";
				}
			else // no pague todo
			{
				/*if( $key["Conexion_Id"] == 66)
				{
					var_dump($key);
					die();
				}*/

				if( ($key["Factura_PagoMonto"] == NULL) || (intval($key["Factura_PagoMonto"]) == intval(0)) )
					$deuda_arrastrada = floatval($key["Factura_Vencimiento2_Precio"]);
				else
					$deuda_arrastrada = floatval($key["Factura_Vencimiento2_Precio"]) - floatval($key["Factura_PagoMonto"]);
				$log .= " -             Con Deuda :".$key["Conexion_Id"]."            -    ";
			}
			//Paso 5 - creo object a guardar

			$arrayName = array(
				'Conexion_Deuda' => $deuda_arrastrada,
				 );
			//Paso 6 - actualizo en la bd
			$actualizacion_deuda = $this->Nuevo_model->update_data($arrayName, $key["Conexion_Id"], "conexion" ,"Conexion_Id");
			}
		var_dump($log);
	}
	/*
	debira ser 3
	Esta funcion pasa del Conexion_Deuda a la Factura_Deuda, Factura_Multa y Factura_Acuenta
	*/
	public function calcular_deudas_y_multas_a_facturacion_mes_nuevo($sector= 0 , $mes_b = 0, $anio_b = 0)
	{
		//Usado: 29-6-20 - Diego
		//Corregido : 29-6-20 - Diego
		//Como se usa: se accede por url o por varias pag (p.e. wizard) y acceder definiendo los parametros , no afecta si se corre varias veces , ahora hardcodeado
		//Que es lo que hace: Esta funcion pasa del Conexion_Deuda a la Factura_Deuda, Factura_Multa y Factura_Acuenta  del mes actual, ahor hardcodeado
		// MEJORA : sacar hardcodeo  y utilizar los valores pasados por parametros
		//Mejora: Harcodeado  , tomar los valores de parametros
		/*Pasos que hace
		Paso 1 - re escribe el nombre de los sectores para que coincidan con los guardades en la bd
		Paso 2 - Busco los registros para los parametros (secotr, mes , año) - se buscan las conexiones na
		Paso 3 - itero por cada conexion
		--Deuda
		Paso 4 - Busco la deuda para la conexion q estoy viendo 
		Paso 5 - creo object a guardar
		Paso 6 - actualizo en la bd
		--MULTA
		Paso 7 - Busco la multa para la conexion q estoy viendo 
		Paso 8 - creo object a guardar
		Paso 9 - actualizo en la bd
		--ACUENTA
		Paso 10 - Busco la acuenta para la conexion q estoy viendo 
		Paso 11 - creo object a guardar
		Paso 12 - actualizo en la bd

		*/
		//Paso 1 - re escribe el nombre de los sectores para que coincidan con los guardades en la bd
		//$sector = "A";
		//$sectores = "B";
		//$sectores = "C";
		//$sectores = "Medina";
		//$sectores = "Aberanstain";
		//$sectores = "Jardines del Sur";
		//$sectores = "Salas";
		// $sectores = "Santa Barbara";
		//$sectores = "V Elisa";
		//$sector = "David";
		//$sectores = "ASENTAMIENTO OLMOS";
		// $sectores = "Zaldivar";
		if($sector == "JardinesdelSur")
			$sector = "Jardines del Sur";
		if($sector == "SantaBarbara")
			$sector = "Santa Barbara";
		if($sector == "VElisa")
			$sector = "V Elisa";
		if($sector == "ASENTAMIENTOOLMOS")
			$sector = "ASENTAMIENTO OLMOS";
		$mes= 6;
		$anio = 2020;
		$log = '';
		//Paso 2 - Busco los registros para los parametros (secotr, mes , año) - se buscan las conexiones na
		$conexiones_del_sector =  $this->Nuevo_model->get_data_dos_campos("conexion","Conexion_Borrado",0, "Conexion_Sector",$sector); // busco las conexiones no borradas y solo para el sector
		$i=0;
		//Paso 3 - itero por cada conexion
		foreach ($conexiones_del_sector as $key) {
			echo "Vuelta:".$i;
			// $i++;
			// if($i== 1 || $i== 2)
			// 	continue;
			// if($key->Conexion_Id == 13)
			// {
			// 	var_dump($conexiones_del_sector);
			// 	die();
			// }
			/* INICIO DEUDA*/
			//Paso 4 - Busco la deuda para la conexion q estoy viendo 
			$deuda_de_la_conexion =  $this->Nuevo_model->buscar_deuda_conexion($key->Conexion_Id);
			/*var_dump($key->Conexion_Id, $deuda_de_la_conexion[0]->Conexion_Deuda);
			die();*/
			$deuda_a_poner = 0;
			if( ($deuda_de_la_conexion == false) ||  ( intval($deuda_de_la_conexion[0]->Conexion_Deuda) == 0) ) 
			{
				echo "-                    Sin boleta. Conexion:".$key->Conexion_Id."                  -";
				//continue;
				$deuda_a_poner = 0;
			}
			else
			{
				$deuda_a_poner = floatval($deuda_de_la_conexion[0]->Conexion_Deuda);
			}
			//Paso 5 - creo object a guardar
			$datos_a_actualizar = array(
				'Factura_Deuda' => floatval($deuda_a_poner),
			);
			//var_dump($key->Conexion_Id,$datos_a_actualizar);die();
			// if($key->Conexion_Deuda == 329)
			// {
			// 	var_dump($key);die();
			// }
			//Paso 6 - actualizo en la bd
			$resultado_actualizacion = $this->Nuevo_model->update_data_tres_campos($datos_a_actualizar, $key->Conexion_Id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
			echo "-              Deuda Acutalizada: ".$key->Conexion_Deuda."                     -";
			/* FIN DEUDA*/

			/* INICIO MULTA*/
			//Paso 7 - Busco la multa para la conexion q estoy viendo 
			$multa_a_poner = 0;
			$multa_de_la_conexion =  $this->Nuevo_model->buscar_multa_conexion($key->Conexion_Id);
			if( ($multa_de_la_conexion == false) ||  ( intval($multa_de_la_conexion[0]->Conexion_Multa) == 0) ) 
			{
				echo "-                    Sin Multa. Conexion:".$key->Conexion_Id."                         -";
				$multa_a_poner = 0;
			}
			else
			{
				$multa_a_poner =  floatval($multa_de_la_conexion[0]->Conexion_Multa);
			}
			//Paso 8 - creo object a guardar
			$datos_a_actualizar = array(
				'Factura_Multa' => $multa_a_poner,
			);
			//Paso 9 - actualizo en la bd
			$resultado_actualizacion = $this->Nuevo_model->update_data_tres_campos($datos_a_actualizar, $key->Conexion_Id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
				echo "-             Multa Cambiada: ".$key->Conexion_Multa."                      -";
			/* FIN MULTA*/

			/* INICIO ACUENTA*/
			//Paso 10 - Busco la acuenta para la conexion q estoy viendo 

			$acuenta_de_la_conexion =  $this->Nuevo_model->buscar_acuenta_conexion($key->Conexion_Id);
			//if($acuenta_de_la_conexion == false)
			$acuenta_a_poner = 0;
			if( ($acuenta_de_la_conexion == false) ||  ( intval($acuenta_de_la_conexion[0]->Conexion_Acuenta) == 0) ) 
			{
				echo "-                    Sin Multa. Conexion:".$key->Conexion_Id."                         -";
				$acuenta_a_poner = 0;
			}
			else
			{
				$acuenta_a_poner =  floatval($acuenta_de_la_conexion[0]->Conexion_Acuenta);
			}
			//Paso 11 - creo object a guardar
			$datos_a_actualizar = array(
				'Factura_Acuenta' => $acuenta_a_poner ,
			);
			//Paso 12 - actualizo en la bd
			$resultado_actualizacion = $this->Nuevo_model->update_data_tres_campos($datos_a_actualizar, $key->Conexion_Id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
			echo "-             Multa Cambiada: ".$key->Conexion_Acuenta."                      -";
			/* FIN ACUENTA*/
		}
	}
	/*
	Actualizar deuda
	*/
	public function actualizar_deuda($id = null, $mes = null, $anio = null){
			/* INICIO DEUDA*/
			$deuda_de_la_conexion =  $this->Nuevo_model->buscar_deuda_conexion($id);
			var_dump($deuda_de_la_conexion[0]->Conexion_Deuda);die();
			$deuda_a_poner = 0;
			if( ($deuda_de_la_conexion == false) ||  ( intval($deuda_de_la_conexion[0]->Conexion_Deuda) == 0) ) 
			{
				echo "-                    Sin boleta. Conexion:".$id."                  -";
				//continue;
				$deuda_a_poner = 0;
			}
			else
			{
				$deuda_a_poner = floatval($deuda_de_la_conexion[0]->Conexion_Deuda);
			}
			$datos_a_actualizar = array(
				'Factura_Deuda' => floatval($deuda_a_poner),
			);
			$resultado_actualizacion = $this->Nuevo_model->update_data_tres_campos($datos_a_actualizar, $id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
			echo "-              Deuda Acutalizada: ".$id."                     -";
			/* FIN DEUDA*/

			/* INICIO MULTA*/
			$multa_a_poner = 0;
			$multa_de_la_conexion =  $this->Nuevo_model->buscar_multa_conexion($id);
			if( ($multa_de_la_conexion == false) ||  ( intval($multa_de_la_conexion[0]->Conexion_Multa) == 0) ) 
			{
				echo "-                    Sin Multa. Conexion:".$id."                         -";
				$multa_a_poner = 0;
			}
			else
			{
				$multa_a_poner =  floatval($multa_de_la_conexion[0]->Conexion_Multa);
			}
				$datos_a_actualizar = array(
					'Factura_Multa' => $multa_a_poner,
				);
			$resultado_actualizacion = $this->Nuevo_model->update_data_tres_campos($datos_a_actualizar, $id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
				echo "-             Multa Cambiada: ".$id."                      -";
			/* FIN MULTA*/

					/* INICIO ACUENTA*/

			$acuenta_de_la_conexion =  $this->Nuevo_model->buscar_acuenta_conexion($id);
			//if($acuenta_de_la_conexion == false)
			$acuenta_a_poner = 0;
			if( ($acuenta_de_la_conexion == false) ||  ( intval($acuenta_de_la_conexion[0]->Conexion_Acuenta) == 0) ) 
			{
				echo "-                    Sin Multa. Conexion:".$id."                         -";
				$acuenta_a_poner = 0;
			}
			else
			{
				$acuenta_a_poner =  floatval($acuenta_de_la_conexion[0]->Conexion_Acuenta);
			}
			$datos_a_actualizar = array(
				'Factura_Acuenta' => $acuenta_a_poner ,
			);
			$resultado_actualizacion = $this->Nuevo_model->update_data_tres_campos($datos_a_actualizar, $id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
			echo "-             Multa Cambiada: ".$id."                      -";
				/* FIN ACUENTA*/
	}
	/*
	Esta funcion have el recalculo de los valores que aparecen en las boletas
	*/
	/* back antes de generar boeltas de abril
	public function corregir_boletas($sectores = 0, $mes = 0, $anio = 0)
	{
		if($sectores == 0 )
			$sectores = $this->input->post('select_tablet');
		if($mes == 0 )
			$mes = $this->input->post('mes');
		if($anio == 0 )
			$anio = $this->input->post('anio');
		if($sectores === 0 )
			{
				echo "Error";die();
			}
		elseif($sectores == "A")
			$sectores = [ "A", "Jardines del Sur", "Aberanstain", "Medina", "Salas", "Santa Barbara" , "V Elisa"];
		else $sectores = [ "B", "C", "David", "ASENTAMIENTO OLMOS", "Zaldivar" ];
		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		$mediciones_desde_query =  $this->Nuevo_model->get_sectores_query_corregir($sectores, $mes, $anio );
		//var_dump($mediciones_desde_query);die();
		if($mediciones_desde_query != false)
		{
			$indice_actual = 0;
			foreach ($mediciones_desde_query as $key ) {
				if( ($key->Factura_MedicionAnterior == 0) && ($key->Factura_MedicionActual == 1) ) // bandera de tablet
					continue;
				if( ( floatval($key->Factura_PagoMonto) != floatval(0)) && ($key->Factura_PagoContado != NULL) && ($key->Factura_PagoContado != NULL) ) //si esta pagada no se re calcula
					continue;
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
										+ floatval($key->Conexion_Deuda)
										+ floatval($key->Factura_ExcedentePrecio )
										+ floatval($key->Factura_CuotaSocial )
										+ floatval($key->Factura_Riego )
										+ floatval($key->Factura_PM_Cuota_Precio)
										+ floatval($key->Factura_PPC_Precio);
				$total  = $sub_total;
				
				$bonificacion = 0;
				if($key->Conexion_Deuda == 0)
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
					'Factura_Total' => floatval($total),
					'Factura_Vencimiento1_Precio' => floatval($vto_1_precio),
					'Factura_Vencimiento2_Precio' => floatval($vto_2_precio),
					'Factura_ExcedentePrecio' => floatval($importe_medicion),
					'Factura_Excedentem3' => $inputExcedente
					 );
				$resultado[$indice_actual] = $this->Nuevo_model->update_data_tres_campos($datos_factura_nueva, $key->Conexion_Id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
				var_dump($datos_factura_nueva,$key->Conexion_Id);
				// if($indice_actual == 19)
				// 	die();
			}
			var_dump($datos_factura_nueva);
		}
		else
			var_dump("Error. no hay medciones para las variables");	
	}
*/
	public function corregir_boletas($sectores = 0, $mes = 0, $anio = 0)
	{
		//Usado: 29-6-2020 - Diego
		//Corregido : 29-6-2020 - Diego
		//Como se usa: se puede correr varias veces, se accede por url o por varias pag (p.e. wizard) y acceder definiendo los parametros , no afecta si se corre varias veces , ahora hardcodeado
		//Que es lo que hace: IMPORTANTE!! Esta funcion es la q actualiza definitivamente el total sub total , etc
		//Mejora: Harcodeado  , tomar los valores de parametros
		/*Pasos que hace
		Paso 1 - re escribe el nombre de los sectores para que coincidan con los guardades en la bd
		Paso 2 - Busco los registros para los parametros (secotr, mes , año) - se buscan facturas y conexiones
		Paso 3 - itero por cada factura
		Paso 4 - Salto en algunas condiciones
		Paso 5 - creo variables para hacer calculos
		Paso 6 -  Creo los vencimientos
		Paso 7 - creo object a guardar
		Paso 8 - creo actualizo en la bd
		 
		*/
		$vuelta = 0;
		//Paso 1 - re escribe el nombre de los sectores para que coincidan con los guardades en la bd
		//$sectoress = ["A", "Jardines del Sur", "Aberanstain", "Medina", "Salas", "Santa Barbara" , "V Elisa","B", "C", "David", "ASENTAMIENTO OLMOS", "Zaldivar" ];
		//$sectoress = ["A"];
		//$sectoress = ["B"];
		//$sectoress = ["C"];
		//$sectoress = ["Medina"];
		//$sectoress = ["Aberanstain"];
		//$sectoress = ["Jardines del Sur"];
		//$sectoress = ["Salas"];
		//$sectoress  = ["Santa Barbara"];
		//$sectoress = ["V Elisa"];
		//$sectoress = ["David"];
		//$sectoress = ["ASENTAMIENTO OLMOS"];
		$sectoress = ["Zaldivar"];
		foreach ($sectoress as $sectores) {

			/*

			SACAR ESTO DESPUES

			if($vuelta ==2)
				break;
			else $vuelta++;
			*/
			//Paso 2 - Busco los registros para los parametros (secotr, mes , año) - se buscan facturas y conexiones
			$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
			$mediciones_desde_query =  $this->Nuevo_model->get_sectores_query_corregir($sectores, $mes, $anio );
			//var_dump($sectores,$mediciones_desde_query);die();
			//Paso 3 - itero por cada factura
			if($mediciones_desde_query != false)
			{
				//Paso 4 - Salto en algunas condiciones
				$indice_actual = 0;
				foreach ($mediciones_desde_query as $key ) {
					if( ($key->Factura_MedicionAnterior == 0) && ($key->Factura_MedicionActual == 1) ) // bandera de tablet
						continue;
					if( ( floatval($key->Factura_PagoMonto) != floatval(0)) && ($key->Factura_PagoContado != NULL) && ($key->Factura_PagoContado != NULL) ) //si esta pagada no se re calcula
						continue;
					if( intval($key->Factura_MedicionActual) == intval(0) ) // si no tiene medicion se saltea
						continue;
					//Paso 5 - creo variables para hacer calculos
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
					//Paso 6 -  Creo los vencimientos
					$vto_2_precio = floatval($total) + floatval($total) * floatval($todas_las_variables[18]->Configuracion_Valor);
					$vto_1_precio = $total;
					$indice_actual++;
					//Paso 7 - creo object a guardar
					$datos_factura_nueva = array(
						'Factura_SubTotal' => floatval($sub_total),
						'Factura_Bonificacion' => floatval($bonificacion),
						'Factura_Total' => floatval($total),
						'Factura_Vencimiento1_Precio' => floatval($vto_1_precio),
						'Factura_Vencimiento2_Precio' => floatval($vto_2_precio),
						'Factura_ExcedentePrecio' => floatval($importe_medicion),
						'Factura_Excedentem3' => $inputExcedente
						 );
					//Paso 8 - creo actualizo en la bd
					$resultado[$indice_actual] = $this->Nuevo_model->update_data_tres_campos($datos_factura_nueva, $key->Conexion_Id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
					var_dump($datos_factura_nueva,$key->Conexion_Id);
				}
				var_dump($datos_factura_nueva);
			}
			else
			var_dump("Error. no hay medciones para las variables");	
		}
	}

	/*
	ACTUALIZAR ACTUALIZAR ACTUALIZAR ACTUALIZAR ACTUALIZAR ACTUALIZARACTUALIZAR
	Esta funcion sirva para validar o modificar las mediciones "raras", para aquellas negativas o
	q exceden el promedio de consumo
	*/
	public function aprobar_medicion(){
		$id_medicion = $this->input->post("id_medicion");
		$actual = $this->input->post("Lectura_Actual");
		$tipo_conexion = $this->input->post('tipo_conexion_input');
		//var_dump($tipo_conexion);die();
		$todas_las_variables = $this->Crud_model->get_data("configuracion");
		//var_dump($tipo_conexion);die();
		if( ($tipo_conexion == 1) || ($tipo_conexion == "Familiar") || ($tipo_conexion =="Familiar ") )
		{
			//echo "entrre familiar";die();
			$precio_metros = $todas_las_variables[3]->Configuracion_Valor;
			$metros_basicos = $todas_las_variables[5]->Configuracion_Valor;
			$precio_bsico = $todas_las_variables[4]->Configuracion_Valor;
		}
		else 
		{
		//	echo "entrre coemrcial";die();
			$precio_metros = $todas_las_variables[6]->Configuracion_Valor;
			$metros_basicos = $todas_las_variables[8]->Configuracion_Valor;
			$precio_bsico = $todas_las_variables[7]->Configuracion_Valor;
		}
		$anterior = $this->input->post("Lectura_Anterior");

		$inputExcedente = intval($actual) - intval($anterior) - intval($metros_basicos);
		if($inputExcedente < 0 )
			$inputExcedente = 0;
		//$actual = $this->input->post("Lectura_Actual");
		$importe_medicion = 0;
		if($inputExcedente == 0)
			$importe_medicion = 0;
		else $importe_medicion = floatval($precio_metros) * floatval($inputExcedente);


		$datos_factura_nueva = array(
				'Factura_MedicionActual' => $actual,
				'Factura_MedicionAnterior' => $anterior,
				'Factura_ExcedentePrecio' => floatval($importe_medicion),
				'Factura_Excedentem3' => $inputExcedente,
				'Factura_MedicionTimestamp' => date("Y-m-d H:i:s")
				 );
		//var_dump($datos_factura_nueva, $id_medicion);die();
			$resultado= $this->Nuevo_model->update_data($datos_factura_nueva, $id_medicion, "facturacion_nueva","Factura_Id");

		if($resultado)
		{
			$this->session->set_flashdata('aviso','Se modificó correctamente la medicion');
			$this->session->set_flashdata('tipo_aviso','success');
		}
		else 
		{
			$this->session->set_flashdata('aviso','NO se modificó la medicion');
			$this->session->set_flashdata('tipo_aviso','danger');
		}
		redirect(base_url('mediciones/mediciones_a_aprobar'), "refresh");
	}
	//esta funcion muestra el listado de facturas q han sido pagadas y se le aplico un descuento
	public function mostar_descuentos($revisado = null,$mes = null,$anio = null,$id_conexion = null, $sector = null, $inicio = null , $fin = null){
		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			if($revisado == null)
				$revisado = $this->input->post('revisado_autorizacion');
			if($revisado == false) $revisado = 0;
			//var_dump($revisado);die();
			if($mes == null)
				$mes = $this->input->post('mes_select');
			if($mes == false)
				//$mes = date("m");
				$mes = 4;
			if($anio == null)
				$anio = $this->input->post('anio_select_descuentos');
			if($anio == false)
				$anio = date("Y");
			if($sector == null)
				$sector = $this->input->post('sector');
			if($id_conexion == null)
				$id_conexion = $this->input->post('id_conexion_autorizacion');
			if($sector == null)
				$sector = $this->input->post('miselect');
			if($inicio == null)
				$inicio = $this->input->post('inicio_autorizacion');
			if($fin == null)
				$fin = $this->input->post('fin_autorizacion');

			//limite_raro

			$datos['facturas'] = $this->Nuevo_model->get_data_facturas_descuentos_a_aprobar($revisado,$mes,$anio,$id_conexion,$sector,$inicio,$fin);
		//var_dump($datos['facturas']);die();
			$datos["mes_buscado"] = $mes;
			$datos["anio"] = $anio;
			$datos["limite"] = "S/L";
			$datos["sector"] = "S/S";
			$datos["sectores"] = $this->Nuevo_model->get_data_sectores();

			$datos['titulo'] = 'Lista de Auditoria';
			$this->load->view('templates/header',$datos);
			$data ['mensaje'] = $this->session->flashdata('aviso');
			if($data ['mensaje'] != null)// hay aviso
			{
				$data ['tipo'] = $this->session->flashdata('tipo_aviso');
				if($data ['tipo'] == "success")
					$this->load->view("templates/notificacion_correcta_success", $data);
				else
					$this->load->view("templates/notificacion_incorrecta_success", $data);
			}
			$this->load->view('nuevo/lista_descuentos', $datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$data ['aviso'] = $this->session->flashdata('aviso');
			if($data ['aviso'] != null)
				$this->load->view("templates/notificacion_view", $data);
		endif;
	}
	/*
	Este funcion sirve para guardar los cambios luego de q se decida si es necesario pasar el descuento a deuda o no*/
	public function aprobar_descuento(){
		$id_factura = $this->input->post("id_medicion");
		$id_conexion = $this->input->post("conexion_id_input");
		$descuento_a_aplicar = $this->input->post("nuevo_descuento");
		$descuento_hecho = $this->input->post("descuento");
		$decision_aprobar = $this->input->post("decision_aprobar"); // 0: sin ver || 1: aprobada || 2: reprobada
		//var_dump($descuento_a_aplicar,$decision_aprobar);die();
		if($decision_aprobar == 2)// significa q se le agrega la deuda a la conexion
		{
			if( ($descuento_a_aplicar != 0 ) && ($descuento_a_aplicar != null ) )
				$descuento  = $descuento_a_aplicar;
			else $descuento  =$descuento_hecho;
			$datos_factura_nueva = array(
				'Conexion_Deuda' => $descuento,
				 );
			//var_dump($datos_factura_nueva, $id_medicion);die();
			$resultado= $this->Nuevo_model->update_data($datos_factura_nueva, $id_conexion, "conexion","Conexion_Id");
		}
		$datos_factura_nueva = array(
			'Factura_DescuentoRevisado' => $decision_aprobar,
			);
		$resultado= $this->Nuevo_model->update_data($datos_factura_nueva, $id_factura, "facturacion_nueva","Factura_Id");
		if($resultado)
		{
			$this->session->set_flashdata('aviso','Se modificó correctamente la medicion');
			$this->session->set_flashdata('tipo_aviso','success');
		}
		else 
		{
			$this->session->set_flashdata('aviso','NO se modificó la medicion');
			$this->session->set_flashdata('tipo_aviso','danger');
		}
		redirect(base_url('nuevo/mostar_descuentos'), "refresh");
	}
	public function buscar_acuenta($mes, $anio){
			$resultado= $this->Nuevo_model->get_data_facturas_con_acuenta($mes, $anio);
			var_dump($resultado);die();
	}
	/***************************************************************
	****************************************************************
							FIN INICIO DE MES
	****************************************************************
	*****************************************************************/


































	/***************************************************************
	****************************************************************
							INICIO	IMPRIMIR
	****************************************************************
	*****************************************************************/


	public function crear_factura_por_id($id = -1, $sector = -1, $mes  = -1, $año = -1)
	{
		$sector = str_replace( "%20", " ", $sector);
		$datos["resultado"] = $this->Nuevo_model->buscar_lote_por_sectores_nuevo($id,  $sector, $mes , $año);
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		if($datos["resultado"] == false)
			echo "Sin resultados por el momento" ;
		else
		{
			$pdf = new eFPDF();
			//var_dump($datos["resultado"]);die();
			$pdf->probando_tabla_por_lote_nueva($datos);
		}
	}


	public function crear_factura_por_sector($sectores =  -1 , $mes = -1, $anio = -1)
	{
		//Usado: 29-6-20 - Diego
		//Corregido : 29-6-20 - Diego
		//Como se usa: se accede via url , por lo general desde una card negra q esta en el dashboard, 
		//Que es lo que hace: busca todas las facturas para un sector dado, y luego las pasa a pdf para poder imprimirlas masivamente
		//Mejora: hacer consulta mas especifica, agregar filtros mas pro
		/*Pasos que hace
		Paso 1 - Leer variables. esto es por si llamaron la funcion por url o por form
		Paso 2 - Busco los registros de facturacion
		Paso 3 - llamo a epdf
		*/
		//Paso 1 - Leer variables. esto es por si llamaron la funcion por url o por form
		if($sectores == -1)
			$sectores = $this->input->post("miselect");
		if($mes == -1)
			$mes = $this->input->post("mes_a_imprimir");
		if($anio == -1)
			$anio = $this->input->post("anio_a_imprimir");
		$datos["resultado"] = $this->Nuevo_model->buscar_lote_por_sectores_anterior($sectores,$mes, $anio);
		//var_dump($datos["resultado"]);die();
		//Paso 2 - Busco los registros de facturacion
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		if($datos["resultado"] == false)
			echo "Sin resultados por el momento" ;
		else
		{
			//Paso 3 - llamo a epdf
			$pdf = new eFPDF();
			$pdf->probando_tabla_por_lote_nueva($datos);
		}
	}

	public function crear_factura_por_conexion($conexion = -1,$mes = -1, $anio = -1 )
	{
		if($conexion == -1)
			$conexion = $this->input->post("coenxion_a_imprimir_por_conexion");
		if($mes == -1)
			$mes = $this->input->post("mes_a_imprimir_por_conexion");
		if($anio == -1)
			$anio = $this->input->post("anio_a_imprimir_por_conexion");
		if( ($anio == -1) || ($anio == false) || ($anio == null) 
			|| ($mes == -1)|| ($mes == false)|| ($mes == null)
			|| ($conexion == -1)|| ($conexion == false)|| ($conexion == null)
			)
			echo "error en los parametros";
		else
		{
			$datos["resultado"] = $this->Nuevo_model->buscar_lote_por_conexion($conexion, $mes, $anio);
			//var_dump($datos["resultado"] );die();
			$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
			$pdf = new eFPDF();
			$pdf->probando_tabla_por_lote_nueva($datos);
		}
	}
	public function crear_recibo_de_pago($id_factura)
	{
		$datos["resultado"] = $this->Nuevo_model->buscar_pago_para_recibo($id_factura);
		//var_dump($datos["resultado"]);die();
		$datos["nombre"] ="Admin";
		$pdf = new eFPDF();
		$pdf->crear_recibo_ingreso_nuevo($datos,1);
	}
	/***************************************************************
	****************************************************************
							FIN IMPRIMIR
	****************************************************************
	*****************************************************************/




















	












	


	/***************************************************************
	****************************************************************
							INICIO	TABLET
	****************************************************************
	*****************************************************************/
	public function pasar_tabla_prueba_mediciones()
	{
		$mes = 4;
		$anio =  2018;
		$mediciones_de_tablet =  $this->Nuevo_model->get_data("prueba");
		$log = '';
		foreach ($mediciones_de_tablet as $key ) {
			if($key->pasado == 0) // significa q ya se cargo
			{
				$log .= "              -                                   -                ya pago                        -";
				continue;
			}
			else // hay q cargar
			{
				$datos_a_guardar = array(
					'Factura_MedicionActual' => $key->actual,
				);
				$resultado = $this->Nuevo_model->update_data_tres_campos($datos_a_guardar, 4, "facturacion_nueva","Factura_Mes", "Factura_Año", 2018, "Factura_Conexion_Id", $key->conexion);
				$log .= "              -                                   -                actualizando:".$key->conexion."                       -";
				$datos_a_guardar = array(
					'pasado' => 0,
				);
				$resultado = $this->Nuevo_model->update_data($datos_a_guardar, $key->id, "prueba","id");
			}
			// var_dump($log);
			// die();
		}
		var_dump($log);
	}
	public function invertir_orden_sector($sector)
	{
		$mediciones_de_tablet =  $this->Nuevo_model->get_data_dos_campos_B("conexion", "Conexion_Sector", $sector,"Conexion_Borrado", 0);
		//var_dump($mediciones_de_tablet);die();
		$indice = 1;
		foreach ($mediciones_de_tablet as $key ) {
			$datos_a_guardar = array(
				'Conexion_UnionVecinal' =>$indice,
			);
			$resultado = $this->Nuevo_model->update_data($datos_a_guardar, $key->Conexion_Id, "conexion","Conexion_Id");
			$indice++;
		}
	}

	public function arreglar_orden_sector_medina()
	{
		$mediciones_de_tablet =  $this->Nuevo_model->get_data_dos_campos_B("conexion", "Conexion_Sector", "medina","Conexion_Borrado", 0);
		//var_dump($mediciones_de_tablet);die();
		$indice = 1;
		foreach ($mediciones_de_tablet as $key ) {
			//inicio harcodeadisimo
			if($key->Conexion_Id == 538)
	
			$datos_a_guardar = array(
				'Conexion_UnionVecinal' =>1,
			);
				if($key->Conexion_Id == 539)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>2,
							);
				if($key->Conexion_Id == 540)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>3,
							);
				if($key->Conexion_Id == 541)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>4,
							);
				if($key->Conexion_Id == 542)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>5,
							);
				if($key->Conexion_Id == 543)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>6,
							);
				if($key->Conexion_Id == 544)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>7,
							);
				if($key->Conexion_Id == 545)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>8,
							);
				if($key->Conexion_Id == 546)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>9,
							);
				if($key->Conexion_Id == 547)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>10,
							);
				if($key->Conexion_Id == 549)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>11,
							);
				if($key->Conexion_Id == 550)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>12,
							);
				if($key->Conexion_Id == 551)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>13,
							);
				if($key->Conexion_Id == 552)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>14,
							);
				if($key->Conexion_Id == 553)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>15,
							);
				if($key->Conexion_Id == 554)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>16,
							);
				if($key->Conexion_Id == 555)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>17,
							);
				if($key->Conexion_Id == 556)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>18,
							);
				if($key->Conexion_Id == 557)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>19,
							);
				if($key->Conexion_Id == 558)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>20,
							);
				if($key->Conexion_Id == 559)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>21,
							);
				if($key->Conexion_Id == 560)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>22,
							);
				if($key->Conexion_Id == 561)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>23,
							);
				if($key->Conexion_Id == 562)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>24,
							);
				if($key->Conexion_Id == 563)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>25,
							);
				if($key->Conexion_Id == 564)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>26,
							);
				if($key->Conexion_Id == 565)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>27,
							);
				if($key->Conexion_Id == 566)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>28,
							);
				if($key->Conexion_Id == 567)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>29,
							);
				if($key->Conexion_Id == 568)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>30,
							);
				if($key->Conexion_Id == 569)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>31,
							);
				if($key->Conexion_Id == 570)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>32,
							);
				if($key->Conexion_Id == 571)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>33,
							);
				if($key->Conexion_Id == 572)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>34,
							);
				if($key->Conexion_Id == 573)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>35,
							);
				if($key->Conexion_Id == 574)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>36,
							);
				if($key->Conexion_Id == 575)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>37,
							);
				if($key->Conexion_Id == 576)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>38,
							);
				if($key->Conexion_Id == 577)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>39,
							);
				if($key->Conexion_Id == 578)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>40,
							);
				if($key->Conexion_Id == 579)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>41,
							);
				if($key->Conexion_Id == 580)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>42,
							);
				if($key->Conexion_Id == 581)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>43,
							);
				if($key->Conexion_Id == 582)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>44,
							);
				if($key->Conexion_Id == 583)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>45,
							);
				if($key->Conexion_Id == 584)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>46,
							);
				if($key->Conexion_Id == 585)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>47,
							);
				if($key->Conexion_Id == 586)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>48,
							);
				if($key->Conexion_Id == 587)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>49,
							);
				if($key->Conexion_Id == 588)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>50,
							);
				if($key->Conexion_Id == 589)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>51,
							);
				if($key->Conexion_Id == 590)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>52,
							);
				if($key->Conexion_Id == 591)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>53,
							);
				if($key->Conexion_Id == 592)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>54,
							);
				if($key->Conexion_Id == 593)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>55,
							);
				if($key->Conexion_Id == 594)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>56,
							);
				if($key->Conexion_Id == 595)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>57,
							);
				if($key->Conexion_Id == 596)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>58,
							);
				if($key->Conexion_Id == 597)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>59,
							);
				if($key->Conexion_Id == 598)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>60,
							);
				if($key->Conexion_Id == 599)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>61,
							);
				if($key->Conexion_Id == 600)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>62,
							);
				if($key->Conexion_Id == 601)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>63,
							);
				if($key->Conexion_Id == 602)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>64,
							);
				if($key->Conexion_Id == 603)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>65,
							);
				if($key->Conexion_Id == 604)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>66,
							);
				if($key->Conexion_Id == 605)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>67,
							);
				if($key->Conexion_Id == 606)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>68,
							);
				if($key->Conexion_Id == 524)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>69,
							);
				if($key->Conexion_Id == 525)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>70,
							);
				if($key->Conexion_Id == 526)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>71,
							);
				if($key->Conexion_Id == 527)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>72,
							);
				if($key->Conexion_Id == 528)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>73,
							);
				if($key->Conexion_Id == 801)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>74,
							);
				if($key->Conexion_Id == 530)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>75,
							);
				if($key->Conexion_Id == 531)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>76,
							);
				if($key->Conexion_Id == 532)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>77,
							);
				if($key->Conexion_Id == 533)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>78,
							);
				if($key->Conexion_Id == 534)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>79,
							);
				if($key->Conexion_Id == 535)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>80,
							);
				if($key->Conexion_Id == 536)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>81,
							);
				if($key->Conexion_Id == 537)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>82,
							);
				if($key->Conexion_Id == 500)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>83,
							);
				if($key->Conexion_Id == 501)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>84,
							);
				if($key->Conexion_Id == 502)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>85,
							);
				if($key->Conexion_Id == 503)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>86,
							);
				if($key->Conexion_Id == 504)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>87,
							);
				if($key->Conexion_Id == 505)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>88,
							);
				if($key->Conexion_Id == 506)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>89,
							);
				if($key->Conexion_Id == 507)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>90,
							);
				if($key->Conexion_Id == 508)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>91,
							);
				if($key->Conexion_Id == 509)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>92,
							);
				if($key->Conexion_Id == 510)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>93,
							);
				if($key->Conexion_Id == 511)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>94,
							);
				if($key->Conexion_Id == 512)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>95,
							);
				if($key->Conexion_Id == 513)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>96,
							);
				if($key->Conexion_Id == 514)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>97,
							);
				if($key->Conexion_Id == 515)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>98,
							);
				if($key->Conexion_Id == 516)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>99,
							);
				if($key->Conexion_Id == 517)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>100,
							);
				if($key->Conexion_Id == 518)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>101,
							);
				if($key->Conexion_Id == 519)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>102,
							);
				if($key->Conexion_Id == 520)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>103,
							);
				if($key->Conexion_Id == 521)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>104,
							);
				if($key->Conexion_Id == 522)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>105,
							);
				if($key->Conexion_Id == 523)
					
							$datos_a_guardar = array(
								'Conexion_UnionVecinal' =>106,
							);
				if($key->Conexion_Id == 283)
	
			$datos_a_guardar = array(
				'Conexion_UnionVecinal' =>107,
			);
			//fin hardcodeadisimo
			$resultado = $this->Nuevo_model->update_data($datos_a_guardar, $key->Conexion_Id, "conexion","Conexion_Id");
			$indice++;
		}
	}
	public function arreglar_orden_sector_olmos()
	{
		$mediciones_de_tablet =  $this->Nuevo_model->get_data_dos_campos_B("conexion", "Conexion_Sector", "ASENTAMIENTO OLMOS","Conexion_Borrado", 0);
		//var_dump($mediciones_de_tablet);die();
		//$indice = 1;
		foreach ($mediciones_de_tablet as $key ) {
			//inicio harcodeadisimo
			if($key->Conexion_Id == 1) continue;
			if($key->Conexion_Id == 789)
	
				$datos_a_guardar = array(
					'Conexion_UnionVecinal' =>1,
				);
			if($key->Conexion_Id == 960)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>2,
						);
			if($key->Conexion_Id == 792)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>3,
						);
			if($key->Conexion_Id == 788)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>4,
						);
			if($key->Conexion_Id == 794)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>5,
						);
			if($key->Conexion_Id == 798)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>6,
						);
			if($key->Conexion_Id == 797)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>7,
						);
			if($key->Conexion_Id == 613)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>8,
						);
			if($key->Conexion_Id == 614)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>9,
						);
			if($key->Conexion_Id == 778)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>10,
						);
			if($key->Conexion_Id == 632)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>11,
						);
			if($key->Conexion_Id == 615)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>12,
						);
			if($key->Conexion_Id == 784)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>13,
						);
			if($key->Conexion_Id == 616)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>14,
						);
			if($key->Conexion_Id == 779)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>15,
						);
			if($key->Conexion_Id == 639)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>16,
						);
			if($key->Conexion_Id == 617)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>17,
						);
			if($key->Conexion_Id == 803)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>18,
						);
			if($key->Conexion_Id == 623)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>19,
						);
			if($key->Conexion_Id == 799)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>20,
						);
			if($key->Conexion_Id == 780)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>21,
						);
			if($key->Conexion_Id == 842)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>22,
						);
			if($key->Conexion_Id == 785)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>23,
						);
			if($key->Conexion_Id == 800)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>24,
						);
			if($key->Conexion_Id == 806)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>25,
						);
			if($key->Conexion_Id == 791)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>26,
						);
			if($key->Conexion_Id == 782)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>27,
						);
			if($key->Conexion_Id == 783)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>28,
						);
			if($key->Conexion_Id == 793)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>29,
						);
			if($key->Conexion_Id == 786)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>30,
						);
			if($key->Conexion_Id == 790)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>31,
						);
			if($key->Conexion_Id == 848)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>32,
						);
			if($key->Conexion_Id == 840)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>33,
						);
			if($key->Conexion_Id == 841)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>34,
						);
			if($key->Conexion_Id == 817)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>35,
						);
			if($key->Conexion_Id == 777)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>36,
						);
			if($key->Conexion_Id == 955)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>37,
						);
			if($key->Conexion_Id == 795)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>38,
						);
			if($key->Conexion_Id == 787)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>39,
						);
			if($key->Conexion_Id == 796)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>40,
						);
			if($key->Conexion_Id == 958)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>41,
						);
			if($key->Conexion_Id == 781)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>42,
						);
			if($key->Conexion_Id == 776)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>43,
						);
			if($key->Conexion_Id == 952)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>44,
						);
			if($key->Conexion_Id == 812)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>45,
						);
			if($key->Conexion_Id == 845)
				
						$datos_a_guardar = array(
							'Conexion_UnionVecinal' =>46,
						);
			if($key->Conexion_Id == 807)
				$datos_a_guardar = array(
				'Conexion_UnionVecinal' =>47,
				);
			//fin hardcodeadisimo
			$resultado = $this->Nuevo_model->update_data($datos_a_guardar, $key->Conexion_Id, "conexion","Conexion_Id");
			// $indice++;
		}
	}
	/***************************************************************
	****************************************************************
							FIN	TABLET
	****************************************************************
	*****************************************************************/





	/***************************************************************
	****************************************************************
							INICIO	WIZARD
	****************************************************************
	*****************************************************************/
	public function inicio($mes=5, $anio=2018)
	{
		$data["variables"] = $this->Nuevo_model->get_data("configuracion");
		$data["pasos"] = $this->Nuevo_model->get_data_tres_campos("inicio_mes","IM_Borrado",0,"IM_Mes",$mes, "IM_Anio",$anio);
		//var_dump($data["pasos"]);die();

		$this->load->view('wizard/wizard_inicio_mes',$data);
	}
	public function modificar_valores_configuracion()
	{
		$mts_basicos_familiar = $this->input->post("mts_basicos_familiar");
		if( ($mts_basicos_familiar != null) && ($mts_basicos_familiar>1))//id = 6 
		{
			$datos = array(
				'Configuracion_Valor' => $mts_basicos_familiar,
				'Configuracion_Modifcado' => null,
				'Configuracion_QuienCambio	' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->update_data($datos, 6,"configuracion", "Configuracion_Id");
		}

		$couta_social = $this->input->post("couta_social");
		if( ($couta_social != null) && ($couta_social>1))//id =10
		{
			$datos = array(
				'Configuracion_Valor' => $couta_social,
				'Configuracion_Modifcado' => null,
				'Configuracion_QuienCambio	' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->update_data($datos, 10,"configuracion", "Configuracion_Id");
		}


		$mts_basicos_comercial = $this->input->post("mts_basicos_comercial");
		if( ($mts_basicos_comercial != null) && ($mts_basicos_comercial>1))//id =9
		{
			$datos = array(
				'Configuracion_Valor' => $mts_basicos_comercial,
				'Configuracion_Modifcado' => null,
				'Configuracion_QuienCambio	' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->update_data($datos, 9,"configuracion", "Configuracion_Id");
		}

		$precio_mt_familiar = $this->input->post("precio_mt_familiar");
		if( ($precio_mt_familiar != null) && ($precio_mt_familiar>1))//id =4
		{
			$datos = array(
				'Configuracion_Valor' => $precio_mt_familiar,
				'Configuracion_Modifcado' => null,
				'Configuracion_QuienCambio	' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->update_data($datos, 4,"configuracion", "Configuracion_Id");
		}

		$precio_mt_comercial = $this->input->post("precio_mt_comercial");
		if( ($precio_mt_comercial != null) && ($precio_mt_comercial>1))//id =7
		{
			$datos = array(
				'Configuracion_Valor' => $precio_mt_comercial,
				'Configuracion_Modifcado' => null,
				'Configuracion_QuienCambio	' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->update_data($datos, 7,"configuracion", "Configuracion_Id");
		}


		$precio_riego = $this->input->post("precio_riego");
		if( ($precio_riego != null) && ($precio_riego>1))//id =18
		{
			$datos = array(
				'Configuracion_Valor' => $precio_riego,
				'Configuracion_Modifcado' => null,
				'Configuracion_QuienCambio	' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->update_data($datos, 18,"configuracion", "Configuracion_Id");
		}

		$vencimiento_1 = $this->input->post("vencimiento_1");
		if( $vencimiento_1 != null)//id =21
		{
			$datos = array(
				'Configuracion_Valor' => $vencimiento_1,
				'Configuracion_Modifcado' => null,
				'Configuracion_QuienCambio	' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->update_data($datos, 21,"configuracion", "Configuracion_Id");
		}


		$vencimiento_2 = $this->input->post("vencimiento_2");
		if( $vencimiento_2 != null)//id =22
		{
			$datos = array(
				'Configuracion_Valor' => $vencimiento_2,
				'Configuracion_Modifcado' => null,
				'Configuracion_QuienCambio	' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->update_data($datos, 22,"configuracion", "Configuracion_Id");
		}

		$validez_boleta = $this->input->post("validez_boleta");
		if( $validez_boleta != null)//id =20
		{
			$datos = array(
				'Configuracion_Valor' => $validez_boleta,
				'Configuracion_Modifcado' => null,
				'Configuracion_QuienCambio	' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->update_data($datos, 20,"configuracion", "Configuracion_Id");
		}
		echo true;

	}
	
	public function terminar_paso($paso = 1 ,$valor = 1, $mes=5, $anio=2018)
	{
		///var_dump($valor);die();
		$paso_1 = $this->Nuevo_model->get_data_tres_campos("inicio_mes","IM_Mes",$mes, "IM_Anio",$anio,"IM_Paso",$paso);
		if( $paso_1 == false)
		{//creo el paso
			$datos = array(
				'IM_Id' => null,
				'IM_Mes' => $mes,
				'IM_Anio' => $anio,
				'IM_Paso' => $paso,
				'IM_Hecho' => $valor,
				'IM_SA' => null,
				'IM_SB' => null,
				'IM_SC' => null,
				'IM_SAberanstain' => null,
				'IM_SJardines' => null,
				'IM_SMedina' => null,
				'IM_SSalas' => null,
				'IM_SSanta' => null,
				'IM_SElisa' => null,
				'IM_SDavid' => null,
				'IM_SOlmos' => null,
				'IM_Timestamp' => null,
				'IM_Borrado' => 0,
				'IM_Quien' => 1, // arreglar esto
				);
			$resultado = $this->Crud_model->insert_data("inicio_mes", $datos);
		}
		else//modifico
		{
			$datos = array(
				'IM_Hecho' => $valor,
				'IM_SA' => null,
				'IM_SB' => null,
				'IM_SC' => null,
				'IM_SAberanstain' => null,
				'IM_SJardines' => null,
				'IM_SMedina' => null,
				'IM_SSalas' => null,
				'IM_SSanta' => null,
				'IM_SElisa' => null,
				'IM_SDavid' => null,
				'IM_SOlmos' => null,
				'IM_Timestamp' => null,
				'IM_Borrado' => 0,
				'IM_Quien' => 1,
				);
			$resultado = $this->Crud_model->update_data_tres_campos($datos, $mes, "inicio_mes","IM_Mes", "IM_Anio", $anio, "IM_Paso",$paso);
		}
		echo true;
	}
	
	public function traer_filas_creadas_inicio_mes($mes = 5, $anio=2018)
	{
		$filas_creadas = $this->Nuevo_model->traer_facturas_por_barrio_nuevo(-1, $mes, $anio, -1,-1 );
		//var_dump($filas_creadas[0]["Conexion_Id"]);die();
		$arre_nuevo = null;
		$i=0;
		if($filas_creadas != false)
		{
			foreach ($filas_creadas as $key) {
				$arre_nuevo[$i]= $key["Conexion_Id"];
				$i++;
			}
			echo json_encode($arre_nuevo);
		}
		else
			echo "false";
	}

	
	/***************************************************************
	****************************************************************
							FIN	WIZARD
	****************************************************************
	*****************************************************************/






	/***************************************************************
	****************************************************************
							INICIO	MOVIMIENTOS
	****************************************************************
	*****************************************************************/


	public function cargar_datos_en_modal_movimiento($id_movimiento = -1)
	{
		if($id_movimiento == -1)
			$id_movimiento = $this->input->post("id_movimiento");
		else
		{
			$datos["resultado"] = $this->Nuevo_model->buscar_datos_movimientos_modal($id_movimiento);
			echo json_encode($datos["resultado"]);
		}
	}

		/***************************************************************
	****************************************************************
							FIN	MOVIMIENTOS
	****************************************************************
	*****************************************************************/





/*	public function migrar_factura_a_facturacion_nuevo($sector=0)
	{
		//$this->load->model('Facturar_model');
		//var_dump(date("m"));
		$log = '';
		$facturas_mes_anterior_tabla_anterior =  $this->Nuevo_model->traer_mediciones_tabla_vieja($sector);
		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		
		//var_dump($facturas_mes_anterior_tabla_anterior);die();
		/*COasa que debo hacer

		1 - Traer las mediciones del mes anterior para el sector solicitado
		2 - Crear las variables necesarias

		3 - Definir los vencimientos por configuracion

		4 - Creo e inserto una row de facturacion_nueva

		5 - Recalcular valores como_ codigo de barra,

		*
		$indice_actual = -1;
		$resultado = array( );
		foreach ($facturas_mes_anterior_tabla_anterior as $key) {
			//veo si la factura ya existe, si es asi entonces salto
			//$aux = $this->Nuevo_model->get_data_tres_campos("facturacion_nueva", 'Factura_Mes', 3,'Factura_Año', 2018, 'Factura_Conexion_Id', $key["Conexion_Id"]);
			//var_dump($key["Conexion_Id"],$aux);die();
			if($this->Nuevo_model->get_data_tres_campos("facturacion_nueva", 'Factura_Mes', 3,'Factura_Año', 2018, 'Factura_Conexion_Id', $key["Conexion_Id"]) != false )
				continue;//significa que encontre la factura en facturacion nueva
		//calculo de las variables q voy a usar en para crear la factura
		if ( ($key["Conexion_Categoria"] ==  "Familiar") || ($key["Conexion_Categoria"] ==  1) || ($key["Conexion_Categoria"] =="Familiar ") )
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
		if($key["Conexion_Latitud"] == 1)
			$riego = floatval($todas_las_variables[17]->Configuracion_Valor);
		else $riego = floatval(0);

		$plan_medidor_couta_actual = 0; //recinicio el plan xq se acabo
		$cantidad_de_cuotas_medidor = 0;//recinicio el plan xq se acabo
		$pracio_de_cuota_medidor = 0;//recinicio el plan xq se acabo

		$plan_pag_couta_actual = 0; //recinicio el plan xq se acabo
		$cantidad_de_cuotas_planpago = 0;//recinicio el plan xq se acabo
		$precio_de_planpago = 0;//recinicio el plan xq se acabo

		/*
		$plan_medidor_couta_actual = 0;
		$cantidad_de_cuotas_medidor = $key["Factura_PM_Cant_Cuotas"];
		$pracio_de_cuota_medidor = $key["Factura_PM_Cuota_Precio"];
		if( is_numeric($key["Factura_PM_Cant_Cuotas"]) && ($key["Factura_PM_Cant_Cuotas"]  > 0) )
			if($key["Factura_PM_Cant_Cuotas"] < $key["Factura_PM_Cuota_Actual"]) // significa que ya termine de pagar
				{
					$plan_medidor_couta_actual = 0; //recinicio el plan xq se acabo
					$cantidad_de_cuotas_medidor = 0;//recinicio el plan xq se acabo
					$pracio_de_cuota_medidor = 0;//recinicio el plan xq se acabo
				}
			else //sigifica que tengo q seguir pagando la cuota
				$plan_medidor_couta_actual = intval($key["Factura_PM_Cuota_Actual"])+1;
		$plan_pag_couta_actual = 0;
		$cantidad_de_cuotas_planpago = $key["Factura_PP_Cant_Cuotas"];
		$precio_de_planpago = $key["Factura_PPC_Precio"];
		if( is_numeric($key["Factura_PP_Cant_Cuotas"]) && ($key["Factura_PP_Cant_Cuotas"]  > 0) ) //  de ser verdad existe el plan de pago
			if($key["Factura_PP_Cant_Cuotas"] < $key["Factura_PP_Cuota_Actual"]) // significa que ya termine de pagar
				{
					$plan_pag_couta_actual = 0; //recinicio el plan xq se acabo
					$cantidad_de_cuotas_planpago = 0;//recinicio el plan xq se acabo
					$precio_de_planpago = 0;//recinicio el plan xq se acabo
				}
			else //sigifica que tengo q seguir pagando la cuota
				$plan_pag_couta_actual = intval($key["Factura_PP_Cuota_Actual"])+1;
		*
		$subtotal = floatval($key["Conexion_Deuda"]) + floatval($precio_bsico) +floatval($todas_las_variables[9]->Configuracion_Valor) +floatval($pracio_de_cuota_medidor) +floatval($precio_de_planpago) +floatval($riego) ;
		$acuenta = $key["Conexion_Acuenta"];
		if($acuenta == NULL)
			$acuenta = floatval(0);
		$bonificacion = 0; // despues de haber caqrgado la medicion se re calcucla la bonificacion
		$acutal = $key["Medicion_Actual"];
		if($acutal == 0)
			$acutal = $key["Medicion_Anterior"];
		//PASO 2 CREAR NUEVA FACTURA
		$indice_actual++;
		$datos = array(
			"Factura_Id" => null,
			"Factura_CodigoBarra" => "111111",// se calcula despues
			"Factura_Cli_Id" => $key["Cli_Id"],
			"Factura_Conexion_Id" => $key["Factura_Conexion_Id"],

			"Factura_FechaEmision" => date("Y-m-d H:i:s"),
			"Factura_Mes" => 3,
			"Factura_Año" => date("Y"),

			"Factura_TarifaSocial" => $precio_bsico, //valor ed tarifa social
			"Factura_ExcedentePrecio" => null , //no lo tengo aun
			"Factura_CuotaSocial" => $todas_las_variables[9]->Configuracion_Valor,

			"Factura_PM_Cant_Cuotas" => $cantidad_de_cuotas_medidor, //calcula arriba
			"Factura_PM_Cuota_Actual" => $plan_medidor_couta_actual, //calcula arriba
			"Factura_PM_Cuota_Precio" => $pracio_de_cuota_medidor, //calculado arriba

			"Factura_PP_Cant_Cuotas" => $plan_pag_couta_actual,
			"Factura_PP_Cuota_Actual" => $cantidad_de_cuotas_planpago,
			"Factura_PPC_Precio" => $precio_de_planpago,

			"Factura_Riego" => $riego,

			"Factura_SubTotal" => $subtotal,

			"Factura_Acuenta" => $acuenta, // lo  traigo desde la conexion
			"Factura_Bonificacion" => $bonificacion, // luego se recalcula
			
			"Factura_Total" => floatval(0),

			"Factura_Vigencia" => $todas_las_variables[19]->Configuracion_Valor,
			"Factura_Vencimiento1" => $todas_las_variables[20]->Configuracion_Valor,
			"Factura_Vencimiento1_Precio" => floatval(0),
			"Factura_Vencimiento2" => $todas_las_variables[21]->Configuracion_Valor,
			"Factura_Vencimiento2_Precio" => floatval(0),

			'Factura_MedicionAnterior' => $acutal, // el del mes anterior
			'Factura_MedicionActual' => 0, //bandera de null
			'Factura_Basico' => $metros_basicos, // calculado arriba
			'Factura_Excedentem3' =>  0, //bandera de null
			'Factura_MedicionTimestamp' => null, //toavia no acrgo la medicion

			'Factura_PagoLugar' => null,  //toavia no acrgo la pago
			'Factura_PagoMonto' => null,//toavia no acrgo la pago
			'Factura_PagoContado' => null,//toavia no acrgo la pago
			'Factura_PagoCheque' => null,//toavia no acrgo la pago
			'Factura_PagoTimestamp' => null,//toavia no acrgo la pago

			'Factura_Habilitacion' => 1,
			'Factura_Borrado' => 0,
			'Factura_Timestamp' => null

		);
		//var_dump($datos);die();
		$resultado[$indice_actual] = $this->Nuevo_model->insert_data("facturacion_nueva",$datos); 
		//Paso 3 
		//recalcular codigo de barra
		$codigo_barra = $this->calcular_codigo_barra_agua($key["Conexion_Id"], $resultado[$indice_actual] );
		$arrayName = array(
			'Factura_CodigoBarra' => substr($codigo_barra, 0,-1),
			 );
		$actualizacion_codigo_barra  = $this->Nuevo_model->update_data($arrayName, $resultado[$indice_actual], "facturacion_nueva" ,"Factura_Id");

		//paso 4 veo lo de la deuda
		// $deuda_arrastrada = 0;
		// if( ($key["Factura_PagoMonto"] == $key["Factura_Total"]) && ($key["Factura_PagoMonto"] !=  0) &&  ($key["Factura_PagoMonto"] !=  null) )//significa que pague toda la deuda
		// 	$deuda_arrastrada = 0;
		// else // no pague todo
		// 	$deuda_arrastrada = floatval($key["Factura_Total"]) - floatval($key["Factura_PagoMonto"]);
		// $arrayName = array(
		// 	'Conexion_Deuda' => $deuda_arrastrada,
		// 	 );
		// $actualizacion_deuda = $this->Nuevo_model->update_data($arrayName, $key["Conexion_Id"], "conexion" ,"Conexion_Id");
		$log .= "   _    Agrege la conexion : ".$key["Conexion_Id"]."   /\ \n\r   _ ";
		//	var_dump($actualizacion_codigo_barra);die();
		}
	}
	*/
	/*public function pasar_Conexion_Deuda_a_Facturacion_Deuda($val = 0, $mes = 0, $anio = 0)
	{
		// if($val == 1)
		// 	$sectores = [ "", " Sur", "", "", "", "a" , ""];
		// else $sectores = [ "", "", "", "", "" ];
		$sectores = "V Elisa";
		//$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		$facturas_y_conexiones =  $this->Nuevo_model->get_sectores_conexiones_facturas($sectores, $mes, $anio );
		//var_dump($facturas_y_conexiones);die();
		$log = '';
		foreach ($facturas_y_conexiones as $key ) {
			if($key->Factura_PagoMonto == null) // significa q no pago
			{
				
			 			# code...
				if($key->Conexion_Deuda == null)
					$key->Conexion_Deuda = 0;
				if($key->Conexion_Multa == null)
					$key->Conexion_Multa = 0;
				
						$datos_a_guardar = array(
							'Factura_Deuda' => $key->Conexion_Deuda,
							'Factura_Multa' => $key->Conexion_Multa
							 );
						$resultado = $this->Nuevo_model->update_data($datos_a_guardar, $key->Factura_Id, "facturacion_nueva","Factura_Id");
						$log .= "              -                                   -                actualizando:".$resultado."                       -";
			}
			else $log .= "              -                                   -                ya pago                        -";
		}
		echo $log;
	}*/
}
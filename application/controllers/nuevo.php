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
	public function corregir_deudas_mayo_hardcodeado()
	{
		$datos_boletas [0] [0]=4303;
		$datos_boletas [0] [1]=0.00;
		$datos_boletas [1] [0]=4304;
		$datos_boletas [1] [1]=0.00;
		$datos_boletas [2] [0]=4305;
		$datos_boletas [2] [1]=0.00;
		$datos_boletas [3] [0]=4306;
		$datos_boletas [3] [1]=0.00;
		$datos_boletas [4] [0]=4307;
		$datos_boletas [4] [1]=0.00;
		$datos_boletas [5] [0]=4308;
		$datos_boletas [5] [1]=0.00;
		$datos_boletas [6] [0]=4309;
		$datos_boletas [6] [1]=0.00;
		$datos_boletas [7] [0]=4310;
		$datos_boletas [7] [1]=0.00;
		$datos_boletas [8] [0]=4311;
		$datos_boletas [8] [1]=0.00;
		$datos_boletas [9] [0]=4312;
		$datos_boletas [9] [1]=0.00;
		$datos_boletas [10] [0]=4313;
		$datos_boletas [10] [1]=0.00;
		$datos_boletas [11] [0]=4314;
		$datos_boletas [11] [1]=0.00;
		$datos_boletas [12] [0]=4315;
		$datos_boletas [12] [1]=121.80;
		$datos_boletas [13] [0]=4316;
		$datos_boletas [13] [1]=0.00;
		$datos_boletas [14] [0]=4317;
		$datos_boletas [14] [1]=0.00;
		$datos_boletas [15] [0]=4318;
		$datos_boletas [15] [1]=379.29;
		$datos_boletas [16] [0]=4319;
		$datos_boletas [16] [1]=0.00;
		$datos_boletas [17] [0]=4320;
		$datos_boletas [17] [1]=4135.32;
		$datos_boletas [18] [0]=4321;
		$datos_boletas [18] [1]=121.80;
		$datos_boletas [19] [0]=4322;
		$datos_boletas [19] [1]=0.00;
		$datos_boletas [20] [0]=4323;
		$datos_boletas [20] [1]=0.00;
		$datos_boletas [21] [0]=4324;
		$datos_boletas [21] [1]=53764.55;
		$datos_boletas [22] [0]=4325;
		$datos_boletas [22] [1]=667.62;
		$datos_boletas [23] [0]=4326;
		$datos_boletas [23] [1]=0.00;
		$datos_boletas [24] [0]=4327;
		$datos_boletas [24] [1]=0.00;
		$datos_boletas [25] [0]=4328;
		$datos_boletas [25] [1]=509.92;
		$datos_boletas [26] [0]=4329;
		$datos_boletas [26] [1]=51622.03;
		$datos_boletas [27] [0]=4330;
		$datos_boletas [27] [1]=121.80;
		$datos_boletas [28] [0]=4331;
		$datos_boletas [28] [1]=0.00;
		$datos_boletas [29] [0]=4332;
		$datos_boletas [29] [1]=381.13;
		$datos_boletas [30] [0]=4333;
		$datos_boletas [30] [1]=0.00;
		$datos_boletas [31] [0]=4334;
		$datos_boletas [31] [1]=0.00;
		$datos_boletas [32] [0]=4335;
		$datos_boletas [32] [1]=0.00;
		$datos_boletas [33] [0]=4336;
		$datos_boletas [33] [1]=379.29;
		$datos_boletas [34] [0]=4337;
		$datos_boletas [34] [1]=0.00;
		$datos_boletas [35] [0]=4338;
		$datos_boletas [35] [1]=0.00;
		$datos_boletas [36] [0]=4339;
		$datos_boletas [36] [1]=121.80;
		$datos_boletas [37] [0]=4340;
		$datos_boletas [37] [1]=5496.94;
		$datos_boletas [38] [0]=4341;
		$datos_boletas [38] [1]=0.00;
		$datos_boletas [39] [0]=4342;
		$datos_boletas [39] [1]=0.00;
		$datos_boletas [40] [0]=4343;
		$datos_boletas [40] [1]=0.00;
		$datos_boletas [41] [0]=4344;
		$datos_boletas [41] [1]=1405.40;
		$datos_boletas [42] [0]=4345;
		$datos_boletas [42] [1]=3127.48;
		$datos_boletas [43] [0]=4346;
		$datos_boletas [43] [1]=3596.20;
		$datos_boletas [44] [0]=4347;
		$datos_boletas [44] [1]=0.00;
		$datos_boletas [45] [0]=4348;
		$datos_boletas [45] [1]=121.80;
		$datos_boletas [46] [0]=4349;
		$datos_boletas [46] [1]=0.00;
		$datos_boletas [47] [0]=4350;
		$datos_boletas [47] [1]=0.00;
		$datos_boletas [48] [0]=4351;
		$datos_boletas [48] [1]=0.00;
		$datos_boletas [49] [0]=4352;
		$datos_boletas [49] [1]=485.78;
		$datos_boletas [50] [0]=4353;
		$datos_boletas [50] [1]=1029.75;
		$datos_boletas [51] [0]=4354;
		$datos_boletas [51] [1]=121.80;
		$datos_boletas [52] [0]=4355;
		$datos_boletas [52] [1]=126.82;
		$datos_boletas [53] [0]=4356;
		$datos_boletas [53] [1]=0.00;
		$datos_boletas [54] [0]=4357;
		$datos_boletas [54] [1]=0.00;
		$datos_boletas [55] [0]=4358;
		$datos_boletas [55] [1]=0.00;
		$datos_boletas [56] [0]=4359;
		$datos_boletas [56] [1]=0.00;
		$datos_boletas [57] [0]=4360;
		$datos_boletas [57] [1]=1363.57;
		$datos_boletas [58] [0]=4361;
		$datos_boletas [58] [1]=7780.11;
		$datos_boletas [59] [0]=4362;
		$datos_boletas [59] [1]=141.90;
		$datos_boletas [60] [0]=4363;
		$datos_boletas [60] [1]=0.00;
		$datos_boletas [61] [0]=4364;
		$datos_boletas [61] [1]=0.00;
		$datos_boletas [62] [0]=4365;
		$datos_boletas [62] [1]=2325.07;
		$datos_boletas [63] [0]=4366;
		$datos_boletas [63] [1]=1541.92;
		$datos_boletas [64] [0]=4367;
		$datos_boletas [64] [1]=0.00;
		$datos_boletas [65] [0]=4368;
		$datos_boletas [65] [1]=508.06;
		$datos_boletas [66] [0]=4369;
		$datos_boletas [66] [1]=0.00;
		$datos_boletas [67] [0]=4370;
		$datos_boletas [67] [1]=121.80;
		$datos_boletas [68] [0]=4371;
		$datos_boletas [68] [1]=0.00;
		$datos_boletas [69] [0]=4372;
		$datos_boletas [69] [1]=2506.51;
		$datos_boletas [70] [0]=4373;
		$datos_boletas [70] [1]=0.00;
		$datos_boletas [71] [0]=4374;
		$datos_boletas [71] [1]=121.80;
		$datos_boletas [72] [0]=4375;
		$datos_boletas [72] [1]=0.00;
		$datos_boletas [73] [0]=4376;
		$datos_boletas [73] [1]=136.87;
		$datos_boletas [74] [0]=4377;
		$datos_boletas [74] [1]=0.00;
		$datos_boletas [75] [0]=4378;
		$datos_boletas [75] [1]=0.00;
		$datos_boletas [76] [0]=4379;
		$datos_boletas [76] [1]=914.58;
		$datos_boletas [77] [0]=4380;
		$datos_boletas [77] [1]=0.00;
		$datos_boletas [78] [0]=4381;
		$datos_boletas [78] [1]=19411.59;
		$datos_boletas [79] [0]=4382;
		$datos_boletas [79] [1]=379.29;
		$datos_boletas [80] [0]=4383;
		$datos_boletas [80] [1]=2275.54;
		$datos_boletas [81] [0]=4384;
		$datos_boletas [81] [1]=632.99;
		$datos_boletas [82] [0]=4385;
		$datos_boletas [82] [1]=648.81;
		$datos_boletas [83] [0]=4386;
		$datos_boletas [83] [1]=0.00;
		$datos_boletas [84] [0]=4387;
		$datos_boletas [84] [1]=0.00;
		$datos_boletas [85] [0]=4388;
		$datos_boletas [85] [1]=255.66;
		$datos_boletas [86] [0]=4389;
		$datos_boletas [86] [1]=0.00;
		$datos_boletas [87] [0]=4390;
		$datos_boletas [87] [1]=404.11;
		$datos_boletas [88] [0]=4391;
		$datos_boletas [88] [1]=384.18;
		$datos_boletas [89] [0]=4392;
		$datos_boletas [89] [1]=0.00;
		$datos_boletas [90] [0]=4393;
		$datos_boletas [90] [1]=3204.05;
		$datos_boletas [91] [0]=4394;
		$datos_boletas [91] [1]=43942.72;
		$datos_boletas [92] [0]=4395;
		$datos_boletas [92] [1]=0.00;
		$datos_boletas [93] [0]=4396;
		$datos_boletas [93] [1]=379.29;
		$datos_boletas [94] [0]=4397;
		$datos_boletas [94] [1]=993.44;
		$datos_boletas [95] [0]=4398;
		$datos_boletas [95] [1]=5989.32;
		$datos_boletas [96] [0]=4399;
		$datos_boletas [96] [1]=0.00;
		$datos_boletas [97] [0]=4400;
		$datos_boletas [97] [1]=0.00;
		$datos_boletas [98] [0]=4401;
		$datos_boletas [98] [1]=1041.87;
		$datos_boletas [99] [0]=4402;
		$datos_boletas [99] [1]=2633.55;
		$datos_boletas [100] [0]=4403;
		$datos_boletas [100] [1]=2314.19;
		$datos_boletas [101] [0]=4404;
		$datos_boletas [101] [1]=0.00;
		$datos_boletas [102] [0]=4405;
		$datos_boletas [102] [1]=1149.66;
		$datos_boletas [103] [0]=4406;
		$datos_boletas [103] [1]=0.00;
		$datos_boletas [104] [0]=4407;
		$datos_boletas [104] [1]=0.00;
		$datos_boletas [105] [0]=4408;
		$datos_boletas [105] [1]=121.80;
		$datos_boletas [106] [0]=4409;
		$datos_boletas [106] [1]=-974.40;
		$datos_boletas [107] [0]=4410;
		$datos_boletas [107] [1]=1051.13;
		$datos_boletas [108] [0]=4411;
		$datos_boletas [108] [1]=679.65;
		$datos_boletas [109] [0]=4412;
		$datos_boletas [109] [1]=7446.46;
		$datos_boletas [110] [0]=4413;
		$datos_boletas [110] [1]=0.00;
		$datos_boletas [111] [0]=4414;
		$datos_boletas [111] [1]=848.82;
		$datos_boletas [112] [0]=4415;
		$datos_boletas [112] [1]=126.82;
		$datos_boletas [113] [0]=4416;
		$datos_boletas [113] [1]=0.00;
		$datos_boletas [114] [0]=4417;
		$datos_boletas [114] [1]=0.00;
		$datos_boletas [115] [0]=4418;
		$datos_boletas [115] [1]=0.00;
		$datos_boletas [116] [0]=4419;
		$datos_boletas [116] [1]=508.06;
		$datos_boletas [117] [0]=4420;
		$datos_boletas [117] [1]=0.00;
		$datos_boletas [118] [0]=4421;
		$datos_boletas [118] [1]=513.72;
		$datos_boletas [119] [0]=4422;
		$datos_boletas [119] [1]=7094.03;
		$datos_boletas [120] [0]=4423;
		$datos_boletas [120] [1]=0.00;
		$datos_boletas [121] [0]=4424;
		$datos_boletas [121] [1]=0.00;
		$datos_boletas [122] [0]=4425;
		$datos_boletas [122] [1]=9062.08;
		$datos_boletas [123] [0]=4426;
		$datos_boletas [123] [1]=0.00;
		$datos_boletas [124] [0]=4427;
		$datos_boletas [124] [1]=0.00;
		$datos_boletas [125] [0]=4428;
		$datos_boletas [125] [1]=704.61;
		$datos_boletas [126] [0]=4429;
		$datos_boletas [126] [1]=0.00;
		$datos_boletas [127] [0]=4430;
		$datos_boletas [127] [1]=0.00;
		$datos_boletas [128] [0]=4431;
		$datos_boletas [128] [1]=0.00;
		$datos_boletas [129] [0]=4432;
		$datos_boletas [129] [1]=0.00;
		$datos_boletas [130] [0]=4433;
		$datos_boletas [130] [1]=0.00;
		$datos_boletas [131] [0]=4434;
		$datos_boletas [131] [1]=0.00;
		$datos_boletas [132] [0]=4435;
		$datos_boletas [132] [1]=187.12;
		$datos_boletas [133] [0]=4436;
		$datos_boletas [133] [1]=0.00;
		$datos_boletas [134] [0]=4437;
		$datos_boletas [134] [1]=0.00;
		$datos_boletas [135] [0]=4438;
		$datos_boletas [135] [1]=362.46;
		$datos_boletas [136] [0]=4439;
		$datos_boletas [136] [1]=2516.55;
		$datos_boletas [137] [0]=4440;
		$datos_boletas [137] [1]=0.00;
		$datos_boletas [138] [0]=4441;
		$datos_boletas [138] [1]=0.00;
		$datos_boletas [139] [0]=4442;
		$datos_boletas [139] [1]=3360.82;
		$datos_boletas [140] [0]=4443;
		$datos_boletas [140] [1]=406.74;
		$datos_boletas [141] [0]=4444;
		$datos_boletas [141] [1]=257.45;
		$datos_boletas [142] [0]=4445;
		$datos_boletas [142] [1]=136.87;
		$datos_boletas [143] [0]=4446;
		$datos_boletas [143] [1]=0.00;
		$datos_boletas [144] [0]=4447;
		$datos_boletas [144] [1]=0.00;
		$datos_boletas [145] [0]=4448;
		$datos_boletas [145] [1]=0.00;
		$datos_boletas [146] [0]=4449;
		$datos_boletas [146] [1]=528.62;
		$datos_boletas [147] [0]=4450;
		$datos_boletas [147] [1]=3045.10;
		$datos_boletas [148] [0]=4451;
		$datos_boletas [148] [1]=337.84;
		$datos_boletas [149] [0]=4452;
		$datos_boletas [149] [1]=0.00;
		$datos_boletas [150] [0]=4453;
		$datos_boletas [150] [1]=0.00;
		$datos_boletas [151] [0]=4454;
		$datos_boletas [151] [1]=0.00;
		$datos_boletas [152] [0]=4455;
		$datos_boletas [152] [1]=0.00;
		$datos_boletas [153] [0]=4456;
		$datos_boletas [153] [1]=0.00;
		$datos_boletas [154] [0]=4457;
		$datos_boletas [154] [1]=0.00;
		$datos_boletas [155] [0]=4458;
		$datos_boletas [155] [1]=0.00;
		$datos_boletas [156] [0]=4459;
		$datos_boletas [156] [1]=1286.75;
		$datos_boletas [157] [0]=4460;
		$datos_boletas [157] [1]=764.44;
		$datos_boletas [158] [0]=4461;
		$datos_boletas [158] [1]=3415.42;
		$datos_boletas [159] [0]=4462;
		$datos_boletas [159] [1]=0.00;
		$datos_boletas [160] [0]=4463;
		$datos_boletas [160] [1]=0.00;
		$datos_boletas [161] [0]=4464;
		$datos_boletas [161] [1]=0.00;
		$datos_boletas [162] [0]=4465;
		$datos_boletas [162] [1]=3066.52;
		$datos_boletas [163] [0]=4466;
		$datos_boletas [163] [1]=0.00;
		$datos_boletas [164] [0]=4467;
		$datos_boletas [164] [1]=0.00;
		$datos_boletas [165] [0]=4468;
		$datos_boletas [165] [1]=146.92;
		$datos_boletas [166] [0]=4469;
		$datos_boletas [166] [1]=0.00;
		$datos_boletas [167] [0]=4470;
		$datos_boletas [167] [1]=0.00;
		$datos_boletas [168] [0]=4471;
		$datos_boletas [168] [1]=0.00;
		$datos_boletas [169] [0]=4472;
		$datos_boletas [169] [1]=121.80;
		$datos_boletas [170] [0]=4473;
		$datos_boletas [170] [1]=511.82;
		$datos_boletas [171] [0]=4474;
		$datos_boletas [171] [1]=172.04;
		$datos_boletas [172] [0]=4475;
		$datos_boletas [172] [1]=182.09;
		$datos_boletas [173] [0]=4476;
		$datos_boletas [173] [1]=0.00;
		$datos_boletas [174] [0]=4477;
		$datos_boletas [174] [1]=662.17;
		$datos_boletas [175] [0]=4478;
		$datos_boletas [175] [1]=0.00;
		$datos_boletas [176] [0]=4479;
		$datos_boletas [176] [1]=13993.50;
		$datos_boletas [177] [0]=4480;
		$datos_boletas [177] [1]=121.80;
		$datos_boletas [178] [0]=4481;
		$datos_boletas [178] [1]=3455.77;
		$datos_boletas [179] [0]=4482;
		$datos_boletas [179] [1]=0.00;
		$datos_boletas [180] [0]=4483;
		$datos_boletas [180] [1]=2091.19;
		$datos_boletas [181] [0]=4484;
		$datos_boletas [181] [1]=0.00;
		$datos_boletas [182] [0]=4485;
		$datos_boletas [182] [1]=697.22;
		$datos_boletas [183] [0]=4486;
		$datos_boletas [183] [1]=172.04;
		$datos_boletas [184] [0]=4487;
		$datos_boletas [184] [1]=25660.95;
		$datos_boletas [185] [0]=4488;
		$datos_boletas [185] [1]=0.00;
		$datos_boletas [186] [0]=4489;
		$datos_boletas [186] [1]=0.00;
		$datos_boletas [187] [0]=4490;
		$datos_boletas [187] [1]=1437.24;
		$datos_boletas [188] [0]=4491;
		$datos_boletas [188] [1]=0.00;
		$datos_boletas [189] [0]=4492;
		$datos_boletas [189] [1]=126.82;
		$datos_boletas [190] [0]=4493;
		$datos_boletas [190] [1]=0.00;
		$datos_boletas [191] [0]=4494;
		$datos_boletas [191] [1]=757.32;
		$datos_boletas [192] [0]=4495;
		$datos_boletas [192] [1]=0.00;
		$datos_boletas [193] [0]=4496;
		$datos_boletas [193] [1]=8244.78;
		$datos_boletas [194] [0]=4497;
		$datos_boletas [194] [1]=0.00;
		$datos_boletas [195] [0]=4498;
		$datos_boletas [195] [1]=0.00;
		$datos_boletas [196] [0]=4499;
		$datos_boletas [196] [1]=0.00;
		$datos_boletas [197] [0]=4500;
		$datos_boletas [197] [1]=227.31;
		$datos_boletas [198] [0]=4501;
		$datos_boletas [198] [1]=0.00;
		$datos_boletas [199] [0]=4502;
		$datos_boletas [199] [1]=0.00;
		$datos_boletas [200] [0]=4503;
		$datos_boletas [200] [1]=1584.72;
		$datos_boletas [201] [0]=4504;
		$datos_boletas [201] [1]=0.00;
		$datos_boletas [202] [0]=4505;
		$datos_boletas [202] [1]=522.46;
		$datos_boletas [203] [0]=4506;
		$datos_boletas [203] [1]=121.80;
		$datos_boletas [204] [0]=4507;
		$datos_boletas [204] [1]=0.00;
		$datos_boletas [205] [0]=4508;
		$datos_boletas [205] [1]=0.00;
		$datos_boletas [206] [0]=4509;
		$datos_boletas [206] [1]=0.00;
		$datos_boletas [207] [0]=4510;
		$datos_boletas [207] [1]=0.00;
		$datos_boletas [208] [0]=4511;
		$datos_boletas [208] [1]=0.00;
		$datos_boletas [209] [0]=4512;
		$datos_boletas [209] [1]=0.00;
		$datos_boletas [210] [0]=4513;
		$datos_boletas [210] [1]=632.86;
		$datos_boletas [211] [0]=4514;
		$datos_boletas [211] [1]=0.00;
		$datos_boletas [212] [0]=4515;
		$datos_boletas [212] [1]=0.00;
		$datos_boletas [213] [0]=4516;
		$datos_boletas [213] [1]=0.00;
		$datos_boletas [214] [0]=4517;
		$datos_boletas [214] [1]=0.00;
		$datos_boletas [215] [0]=4518;
		$datos_boletas [215] [1]=0.00;
		$datos_boletas [216] [0]=4519;
		$datos_boletas [216] [1]=0.00;
		$datos_boletas [217] [0]=4520;
		$datos_boletas [217] [1]=13426.24;
		$datos_boletas [218] [0]=4521;
		$datos_boletas [218] [1]=121.80;
		$datos_boletas [219] [0]=4522;
		$datos_boletas [219] [1]=217.26;
		$datos_boletas [220] [0]=4523;
		$datos_boletas [220] [1]=0.00;
		$datos_boletas [221] [0]=4524;
		$datos_boletas [221] [1]=4085.03;
		$datos_boletas [222] [0]=4525;
		$datos_boletas [222] [1]=207.21;
		$datos_boletas [223] [0]=4526;
		$datos_boletas [223] [1]=1165.07;
		$datos_boletas [224] [0]=4527;
		$datos_boletas [224] [1]=121.80;
		$datos_boletas [225] [0]=4528;
		$datos_boletas [225] [1]=0.00;
		$datos_boletas [226] [0]=4529;
		$datos_boletas [226] [1]=0.00;
		$datos_boletas [227] [0]=4530;
		$datos_boletas [227] [1]=0.00;
		$datos_boletas [228] [0]=4531;
		$datos_boletas [228] [1]=4658.45;
		$datos_boletas [229] [0]=4532;
		$datos_boletas [229] [1]=0.00;
		$datos_boletas [230] [0]=4533;
		$datos_boletas [230] [1]=41187.23;
		$datos_boletas [231] [0]=4534;
		$datos_boletas [231] [1]=0.00;
		$datos_boletas [232] [0]=4535;
		$datos_boletas [232] [1]=0.00;
		$datos_boletas [233] [0]=4536;
		$datos_boletas [233] [1]=682.97;
		$datos_boletas [234] [0]=4537;
		$datos_boletas [234] [1]=0.00;
		$datos_boletas [235] [0]=4538;
		$datos_boletas [235] [1]=0.00;
		$datos_boletas [236] [0]=4539;
		$datos_boletas [236] [1]=517.03;
		$datos_boletas [237] [0]=4540;
		$datos_boletas [237] [1]=1783.11;
		$datos_boletas [238] [0]=4541;
		$datos_boletas [238] [1]=1538.55;
		$datos_boletas [239] [0]=4542;
		$datos_boletas [239] [1]=657.67;
		$datos_boletas [240] [0]=4543;
		$datos_boletas [240] [1]=0.00;
		$datos_boletas [241] [0]=4544;
		$datos_boletas [241] [1]=0.00;
		$datos_boletas [242] [0]=4545;
		$datos_boletas [242] [1]=0.00;
		$datos_boletas [243] [0]=4546;
		$datos_boletas [243] [1]=6797.65;
		$datos_boletas [244] [0]=4547;
		$datos_boletas [244] [1]=0.00;
		$datos_boletas [245] [0]=4548;
		$datos_boletas [245] [1]=0.00;
		$datos_boletas [246] [0]=4549;
		$datos_boletas [246] [1]=0.00;
		$datos_boletas [247] [0]=4550;
		$datos_boletas [247] [1]=0.00;
		$datos_boletas [248] [0]=4551;
		$datos_boletas [248] [1]=0.00;
		$datos_boletas [249] [0]=4552;
		$datos_boletas [249] [1]=638.69;
		$datos_boletas [250] [0]=4553;
		$datos_boletas [250] [1]=0.00;
		$datos_boletas [251] [0]=4554;
		$datos_boletas [251] [1]=0.00;
		$datos_boletas [252] [0]=4555;
		$datos_boletas [252] [1]=0.00;
		$datos_boletas [253] [0]=4556;
		$datos_boletas [253] [1]=0.00;
		$datos_boletas [254] [0]=4557;
		$datos_boletas [254] [1]=0.00;
		$datos_boletas [255] [0]=4558;
		$datos_boletas [255] [1]=1222.80;
		$datos_boletas [256] [0]=4559;
		$datos_boletas [256] [1]=0.00;
		$datos_boletas [257] [0]=4560;
		$datos_boletas [257] [1]=9011.81;
		$datos_boletas [258] [0]=4561;
		$datos_boletas [258] [1]=0.00;
		$datos_boletas [259] [0]=4562;
		$datos_boletas [259] [1]=648.30;
		$datos_boletas [260] [0]=4563;
		$datos_boletas [260] [1]=0.00;
		$datos_boletas [261] [0]=4564;
		$datos_boletas [261] [1]=0.00;
		$datos_boletas [262] [0]=4565;
		$datos_boletas [262] [1]=0.00;
		$datos_boletas [263] [0]=4566;
		$datos_boletas [263] [1]=0.00;
		$datos_boletas [264] [0]=4567;
		$datos_boletas [264] [1]=0.00;
		$datos_boletas [265] [0]=4568;
		$datos_boletas [265] [1]=2015.33;
		$datos_boletas [266] [0]=4569;
		$datos_boletas [266] [1]=842.00;
		$datos_boletas [267] [0]=4570;
		$datos_boletas [267] [1]=0.00;
		$datos_boletas [268] [0]=4571;
		$datos_boletas [268] [1]=17343.20;
		$datos_boletas [269] [0]=4572;
		$datos_boletas [269] [1]=0.00;
		$datos_boletas [270] [0]=4573;
		$datos_boletas [270] [1]=0.00;
		$datos_boletas [271] [0]=4574;
		$datos_boletas [271] [1]=1158.52;
		$datos_boletas [272] [0]=4575;
		$datos_boletas [272] [1]=0.00;
		$datos_boletas [273] [0]=4576;
		$datos_boletas [273] [1]=0.00;
		$datos_boletas [274] [0]=4577;
		$datos_boletas [274] [1]=996.24;
		$datos_boletas [275] [0]=4578;
		$datos_boletas [275] [1]=0.00;
		$datos_boletas [276] [0]=4579;
		$datos_boletas [276] [1]=7989.81;
		$datos_boletas [277] [0]=4580;
		$datos_boletas [277] [1]=285.88;
		$datos_boletas [278] [0]=4581;
		$datos_boletas [278] [1]=121.80;
		$datos_boletas [279] [0]=4582;
		$datos_boletas [279] [1]=4759.23;
		$datos_boletas [280] [0]=4583;
		$datos_boletas [280] [1]=0.00;
		$datos_boletas [281] [0]=4584;
		$datos_boletas [281] [1]=0.00;
		$datos_boletas [282] [0]=4585;
		$datos_boletas [282] [1]=0.00;
		$datos_boletas [283] [0]=4586;
		$datos_boletas [283] [1]=0.00;
		$datos_boletas [284] [0]=4587;
		$datos_boletas [284] [1]=509.92;
		$datos_boletas [285] [0]=4588;
		$datos_boletas [285] [1]=0.00;
		$datos_boletas [286] [0]=4589;
		$datos_boletas [286] [1]=1135.64;
		$datos_boletas [287] [0]=4590;
		$datos_boletas [287] [1]=121.80;
		$datos_boletas [288] [0]=4591;
		$datos_boletas [288] [1]=815.62;
		$datos_boletas [289] [0]=4592;
		$datos_boletas [289] [1]=0.00;
		$datos_boletas [290] [0]=4593;
		$datos_boletas [290] [1]=0.00;
		$datos_boletas [291] [0]=4594;
		$datos_boletas [291] [1]=0.00;
		$datos_boletas [292] [0]=4595;
		$datos_boletas [292] [1]=990.66;
		$datos_boletas [293] [0]=4596;
		$datos_boletas [293] [1]=0.00;
		$datos_boletas [294] [0]=4597;
		$datos_boletas [294] [1]=0.00;
		$datos_boletas [295] [0]=4598;
		$datos_boletas [295] [1]=1553.76;
		$datos_boletas [296] [0]=4599;
		$datos_boletas [296] [1]=0.00;
		$datos_boletas [297] [0]=4600;
		$datos_boletas [297] [1]=1534.94;
		$datos_boletas [298] [0]=4601;
		$datos_boletas [298] [1]=0.00;
		$datos_boletas [299] [0]=4602;
		$datos_boletas [299] [1]=0.00;
		$datos_boletas [300] [0]=4603;
		$datos_boletas [300] [1]=3393.53;
		$datos_boletas [301] [0]=4604;
		$datos_boletas [301] [1]=0.00;
		$datos_boletas [302] [0]=4605;
		$datos_boletas [302] [1]=0.00;
		$datos_boletas [303] [0]=4606;
		$datos_boletas [303] [1]=182.09;
		$datos_boletas [304] [0]=4607;
		$datos_boletas [304] [1]=0.00;
		$datos_boletas [305] [0]=4608;
		$datos_boletas [305] [1]=151.95;
		$datos_boletas [306] [0]=4609;
		$datos_boletas [306] [1]=255.66;
		$datos_boletas [307] [0]=4610;
		$datos_boletas [307] [1]=0.00;
		$datos_boletas [308] [0]=4611;
		$datos_boletas [308] [1]=8958.17;
		$datos_boletas [309] [0]=4612;
		$datos_boletas [309] [1]=262.48;
		$datos_boletas [310] [0]=4613;
		$datos_boletas [310] [1]=0.00;
		$datos_boletas [311] [0]=4614;
		$datos_boletas [311] [1]=0.00;
		$datos_boletas [312] [0]=4615;
		$datos_boletas [312] [1]=0.00;
		$datos_boletas [313] [0]=4616;
		$datos_boletas [313] [1]=0.00;
		$datos_boletas [314] [0]=4617;
		$datos_boletas [314] [1]=0.00;
		$datos_boletas [315] [0]=4618;
		$datos_boletas [315] [1]=0.00;
		$datos_boletas [316] [0]=4619;
		$datos_boletas [316] [1]=0.00;
		$datos_boletas [317] [0]=4620;
		$datos_boletas [317] [1]=0.00;
		$datos_boletas [318] [0]=4621;
		$datos_boletas [318] [1]=0.00;
		$datos_boletas [319] [0]=4622;
		$datos_boletas [319] [1]=0.00;
		$datos_boletas [320] [0]=4623;
		$datos_boletas [320] [1]=5856.82;
		$datos_boletas [321] [0]=4624;
		$datos_boletas [321] [1]=0.00;
		$datos_boletas [322] [0]=4625;
		$datos_boletas [322] [1]=0.00;
		$datos_boletas [323] [0]=4626;
		$datos_boletas [323] [1]=0.00;
		$datos_boletas [324] [0]=4627;
		$datos_boletas [324] [1]=1888.91;
		$datos_boletas [325] [0]=4628;
		$datos_boletas [325] [1]=0.00;
		$datos_boletas [326] [0]=4629;
		$datos_boletas [326] [1]=523.61;
		$datos_boletas [327] [0]=4630;
		$datos_boletas [327] [1]=0.00;
		$datos_boletas [328] [0]=4631;
		$datos_boletas [328] [1]=1283.14;
		$datos_boletas [329] [0]=4632;
		$datos_boletas [329] [1]=121.80;
		$datos_boletas [330] [0]=4633;
		$datos_boletas [330] [1]=0.00;
		$datos_boletas [331] [0]=4634;
		$datos_boletas [331] [1]=0.00;
		$datos_boletas [332] [0]=4635;
		$datos_boletas [332] [1]=621.55;
		$datos_boletas [333] [0]=4636;
		$datos_boletas [333] [1]=0.00;
		$datos_boletas [334] [0]=4637;
		$datos_boletas [334] [1]=0.00;
		$datos_boletas [335] [0]=4638;
		$datos_boletas [335] [1]=379.29;
		$datos_boletas [336] [0]=4639;
		$datos_boletas [336] [1]=0.00;
		$datos_boletas [337] [0]=4640;
		$datos_boletas [337] [1]=247.15;
		$datos_boletas [338] [0]=4641;
		$datos_boletas [338] [1]=0.00;
		$datos_boletas [339] [0]=4642;
		$datos_boletas [339] [1]=525.17;
		$datos_boletas [340] [0]=4643;
		$datos_boletas [340] [1]=3799.16;
		$datos_boletas [341] [0]=4644;
		$datos_boletas [341] [1]=151.95;
		$datos_boletas [342] [0]=4645;
		$datos_boletas [342] [1]=172.04;
		$datos_boletas [343] [0]=4646;
		$datos_boletas [343] [1]=0.00;
		$datos_boletas [344] [0]=4647;
		$datos_boletas [344] [1]=0.00;
		$datos_boletas [345] [0]=4648;
		$datos_boletas [345] [1]=9858.24;
		$datos_boletas [346] [0]=4649;
		$datos_boletas [346] [1]=0.00;
		$datos_boletas [347] [0]=4650;
		$datos_boletas [347] [1]=342.68;
		$datos_boletas [348] [0]=4651;
		$datos_boletas [348] [1]=2698.34;
		$datos_boletas [349] [0]=4652;
		$datos_boletas [349] [1]=2008.48;
		$datos_boletas [350] [0]=4653;
		$datos_boletas [350] [1]=0.00;
		$datos_boletas [351] [0]=4654;
		$datos_boletas [351] [1]=0.00;
		$datos_boletas [352] [0]=4655;
		$datos_boletas [352] [1]=0.00;
		$datos_boletas [353] [0]=4656;
		$datos_boletas [353] [1]=0.00;
		$datos_boletas [354] [0]=4657;
		$datos_boletas [354] [1]=686.65;
		$datos_boletas [355] [0]=4658;
		$datos_boletas [355] [1]=1826.00;
		$datos_boletas [356] [0]=4659;
		$datos_boletas [356] [1]=0.00;
		$datos_boletas [357] [0]=4660;
		$datos_boletas [357] [1]=515.86;
		$datos_boletas [358] [0]=4661;
		$datos_boletas [358] [1]=0.00;
		$datos_boletas [359] [0]=4662;
		$datos_boletas [359] [1]=0.00;
		$datos_boletas [360] [0]=4663;
		$datos_boletas [360] [1]=5506.20;
		$datos_boletas [361] [0]=4664;
		$datos_boletas [361] [1]=0.00;
		$datos_boletas [362] [0]=4665;
		$datos_boletas [362] [1]=0.00;
		$datos_boletas [363] [0]=4666;
		$datos_boletas [363] [1]=0.00;
		$datos_boletas [364] [0]=4667;
		$datos_boletas [364] [1]=0.00;
		$datos_boletas [365] [0]=4668;
		$datos_boletas [365] [1]=0.00;
		$datos_boletas [366] [0]=4669;
		$datos_boletas [366] [1]=0.00;
		$datos_boletas [367] [0]=4670;
		$datos_boletas [367] [1]=0.00;
		$datos_boletas [368] [0]=4671;
		$datos_boletas [368] [1]=0.00;
		$datos_boletas [369] [0]=4672;
		$datos_boletas [369] [1]=0.00;
		$datos_boletas [370] [0]=4673;
		$datos_boletas [370] [1]=7230.23;
		$datos_boletas [371] [0]=4674;
		$datos_boletas [371] [1]=212.24;
		$datos_boletas [372] [0]=4675;
		$datos_boletas [372] [1]=769.42;
		$datos_boletas [373] [0]=4676;
		$datos_boletas [373] [1]=2543.11;
		$datos_boletas [374] [0]=4677;
		$datos_boletas [374] [1]=807.16;
		$datos_boletas [375] [0]=4678;
		$datos_boletas [375] [1]=804.80;
		$datos_boletas [376] [0]=4679;
		$datos_boletas [376] [1]=0.00;
		$datos_boletas [377] [0]=4680;
		$datos_boletas [377] [1]=2312.94;
		$datos_boletas [378] [0]=4681;
		$datos_boletas [378] [1]=913.33;
		$datos_boletas [379] [0]=4682;
		$datos_boletas [379] [1]=379.29;
		$datos_boletas [380] [0]=4683;
		$datos_boletas [380] [1]=708.24;
		$datos_boletas [381] [0]=4684;
		$datos_boletas [381] [1]=11148.49;
		$datos_boletas [382] [0]=4685;
		$datos_boletas [382] [1]=0.00;
		$datos_boletas [383] [0]=4686;
		$datos_boletas [383] [1]=379.29;
		$datos_boletas [384] [0]=4687;
		$datos_boletas [384] [1]=0.00;
		$datos_boletas [385] [0]=4688;
		$datos_boletas [385] [1]=0.00;
		$datos_boletas [386] [0]=4689;
		$datos_boletas [386] [1]=0.00;
		$datos_boletas [387] [0]=4690;
		$datos_boletas [387] [1]=0.00;
		$datos_boletas [388] [0]=4691;
		$datos_boletas [388] [1]=1041.82;
		$datos_boletas [389] [0]=4692;
		$datos_boletas [389] [1]=0.00;
		$datos_boletas [390] [0]=4693;
		$datos_boletas [390] [1]=0.00;
		$datos_boletas [391] [0]=4694;
		$datos_boletas [391] [1]=322.77;
		$datos_boletas [392] [0]=4695;
		$datos_boletas [392] [1]=524.50;
		$datos_boletas [393] [0]=4696;
		$datos_boletas [393] [1]=711.58;
		$datos_boletas [394] [0]=4697;
		$datos_boletas [394] [1]=0.00;
		$datos_boletas [395] [0]=4698;
		$datos_boletas [395] [1]=197.16;
		$datos_boletas [396] [0]=4699;
		$datos_boletas [396] [1]=1155.17;
		$datos_boletas [397] [0]=4700;
		$datos_boletas [397] [1]=8923.13;
		$datos_boletas [398] [0]=4701;
		$datos_boletas [398] [1]=0.00;
		$datos_boletas [399] [0]=4702;
		$datos_boletas [399] [1]=255.66;
		$datos_boletas [400] [0]=4703;
		$datos_boletas [400] [1]=0.00;
		$datos_boletas [401] [0]=4704;
		$datos_boletas [401] [1]=217.26;
		$datos_boletas [402] [0]=4705;
		$datos_boletas [402] [1]=3125.70;
		$datos_boletas [403] [0]=4706;
		$datos_boletas [403] [1]=282.58;
		$datos_boletas [404] [0]=4707;
		$datos_boletas [404] [1]=1566.79;
		$datos_boletas [405] [0]=4708;
		$datos_boletas [405] [1]=0.00;
		$datos_boletas [406] [0]=4709;
		$datos_boletas [406] [1]=0.00;
		$datos_boletas [407] [0]=4710;
		$datos_boletas [407] [1]=1664.37;
		$datos_boletas [408] [0]=4711;
		$datos_boletas [408] [1]=0.00;
		$datos_boletas [409] [0]=4712;
		$datos_boletas [409] [1]=1312.10;
		$datos_boletas [410] [0]=4713;
		$datos_boletas [410] [1]=4729.87;
		$datos_boletas [411] [0]=4714;
		$datos_boletas [411] [1]=0.00;
		$datos_boletas [412] [0]=4715;
		$datos_boletas [412] [1]=0.00;
		$datos_boletas [413] [0]=4716;
		$datos_boletas [413] [1]=192.14;
		$datos_boletas [414] [0]=4717;
		$datos_boletas [414] [1]=0.00;
		$datos_boletas [415] [0]=4718;
		$datos_boletas [415] [1]=151.95;
		$datos_boletas [416] [0]=4719;
		$datos_boletas [416] [1]=612.81;
		$datos_boletas [417] [0]=4720;
		$datos_boletas [417] [1]=0.00;
		$datos_boletas [418] [0]=4721;
		$datos_boletas [418] [1]=126.82;
		$datos_boletas [419] [0]=4722;
		$datos_boletas [419] [1]=0.00;
		$datos_boletas [420] [0]=4723;
		$datos_boletas [420] [1]=1159.05;
		$datos_boletas [421] [0]=4724;
		$datos_boletas [421] [1]=0.00;
		$datos_boletas [422] [0]=4725;
		$datos_boletas [422] [1]=0.00;
		$datos_boletas [423] [0]=4726;
		$datos_boletas [423] [1]=1996.79;
		$datos_boletas [424] [0]=4727;
		$datos_boletas [424] [1]=0.00;
		$datos_boletas [425] [0]=4728;
		$datos_boletas [425] [1]=0.00;
		$datos_boletas [426] [0]=4729;
		$datos_boletas [426] [1]=4839.62;
		$datos_boletas [427] [0]=4730;
		$datos_boletas [427] [1]=987.24;
		$datos_boletas [428] [0]=4731;
		$datos_boletas [428] [1]=3134.77;
		$datos_boletas [429] [0]=4732;
		$datos_boletas [429] [1]=276.00;
		$datos_boletas [430] [0]=4733;
		$datos_boletas [430] [1]=1148.19;
		$datos_boletas [431] [0]=4734;
		$datos_boletas [431] [1]=2341.52;
		$datos_boletas [432] [0]=4735;
		$datos_boletas [432] [1]=257.45;
		$datos_boletas [433] [0]=4736;
		$datos_boletas [433] [1]=457.57;
		$datos_boletas [434] [0]=4737;
		$datos_boletas [434] [1]=0.00;
		$datos_boletas [435] [0]=4738;
		$datos_boletas [435] [1]=3626.59;
		$datos_boletas [436] [0]=4739;
		$datos_boletas [436] [1]=0.00;
		$datos_boletas [437] [0]=4740;
		$datos_boletas [437] [1]=379.29;
		$datos_boletas [438] [0]=4741;
		$datos_boletas [438] [1]=0.00;
		$datos_boletas [439] [0]=4742;
		$datos_boletas [439] [1]=255.66;
		$datos_boletas [440] [0]=4743;
		$datos_boletas [440] [1]=0.00;
		$datos_boletas [441] [0]=4744;
		$datos_boletas [441] [1]=0.00;
		$datos_boletas [442] [0]=4745;
		$datos_boletas [442] [1]=6984.94;
		$datos_boletas [443] [0]=4746;
		$datos_boletas [443] [1]=2298.59;
		$datos_boletas [444] [0]=4747;
		$datos_boletas [444] [1]=0.00;
		$datos_boletas [445] [0]=4748;
		$datos_boletas [445] [1]=0.00;
		$datos_boletas [446] [0]=4749;
		$datos_boletas [446] [1]=0.00;
		$datos_boletas [447] [0]=4750;
		$datos_boletas [447] [1]=0.00;
		$datos_boletas [448] [0]=4751;
		$datos_boletas [448] [1]=0.00;
		$datos_boletas [449] [0]=4752;
		$datos_boletas [449] [1]=0.00;
		$datos_boletas [450] [0]=4753;
		$datos_boletas [450] [1]=0.00;
		$datos_boletas [451] [0]=4754;
		$datos_boletas [451] [1]=0.00;
		$datos_boletas [452] [0]=4755;
		$datos_boletas [452] [1]=0.00;
		$datos_boletas [453] [0]=4756;
		$datos_boletas [453] [1]=0.00;
		$datos_boletas [454] [0]=4757;
		$datos_boletas [454] [1]=0.00;
		$datos_boletas [455] [0]=4758;
		$datos_boletas [455] [1]=765.07;
		$datos_boletas [456] [0]=4759;
		$datos_boletas [456] [1]=2364.42;
		$datos_boletas [457] [0]=4760;
		$datos_boletas [457] [1]=0.00;
		$datos_boletas [458] [0]=4761;
		$datos_boletas [458] [1]=0.00;
		$datos_boletas [459] [0]=4762;
		$datos_boletas [459] [1]=396.76;
		$datos_boletas [460] [0]=4763;
		$datos_boletas [460] [1]=0.00;
		$datos_boletas [461] [0]=4764;
		$datos_boletas [461] [1]=0.00;
		$datos_boletas [462] [0]=4765;
		$datos_boletas [462] [1]=327.79;
		$datos_boletas [463] [0]=4766;
		$datos_boletas [463] [1]=1617.35;
		$datos_boletas [464] [0]=4767;
		$datos_boletas [464] [1]=0.00;
		$datos_boletas [465] [0]=4768;
		$datos_boletas [465] [1]=255.66;
		$datos_boletas [466] [0]=4769;
		$datos_boletas [466] [1]=0.00;
		$datos_boletas [467] [0]=4770;
		$datos_boletas [467] [1]=660.08;
		$datos_boletas [468] [0]=4771;
		$datos_boletas [468] [1]=332.82;
		$datos_boletas [469] [0]=4772;
		$datos_boletas [469] [1]=0.00;
		$datos_boletas [470] [0]=4773;
		$datos_boletas [470] [1]=121.80;
		$datos_boletas [471] [0]=4774;
		$datos_boletas [471] [1]=0.00;
		$datos_boletas [472] [0]=4775;
		$datos_boletas [472] [1]=1515.14;
		$datos_boletas [473] [0]=4776;
		$datos_boletas [473] [1]=0.00;
		$datos_boletas [474] [0]=4777;
		$datos_boletas [474] [1]=0.00;
		$datos_boletas [475] [0]=4778;
		$datos_boletas [475] [1]=121.80;
		$datos_boletas [476] [0]=4779;
		$datos_boletas [476] [1]=5328.75;
		$datos_boletas [477] [0]=4780;
		$datos_boletas [477] [1]=1201.74;
		$datos_boletas [478] [0]=4781;
		$datos_boletas [478] [1]=0.00;
		$datos_boletas [479] [0]=4782;
		$datos_boletas [479] [1]=0.00;
		$datos_boletas [480] [0]=4783;
		$datos_boletas [480] [1]=6444.26;
		$datos_boletas [481] [0]=4784;
		$datos_boletas [481] [1]=9132.72;
		$datos_boletas [482] [0]=4785;
		$datos_boletas [482] [1]=0.00;
		$datos_boletas [483] [0]=4786;
		$datos_boletas [483] [1]=0.00;
		$datos_boletas [484] [0]=4787;
		$datos_boletas [484] [1]=0.00;
		$datos_boletas [485] [0]=4788;
		$datos_boletas [485] [1]=2536.26;
		$datos_boletas [486] [0]=4789;
		$datos_boletas [486] [1]=0.00;
		$datos_boletas [487] [0]=4790;
		$datos_boletas [487] [1]=0.00;
		$datos_boletas [488] [0]=4791;
		$datos_boletas [488] [1]=4698.62;
		$datos_boletas [489] [0]=4792;
		$datos_boletas [489] [1]=192.14;
		$datos_boletas [490] [0]=4793;
		$datos_boletas [490] [1]=121.80;
		$datos_boletas [491] [0]=4794;
		$datos_boletas [491] [1]=0.00;
		$datos_boletas [492] [0]=4795;
		$datos_boletas [492] [1]=3566.32;
		$datos_boletas [493] [0]=4796;
		$datos_boletas [493] [1]=146.92;
		$datos_boletas [494] [0]=4797;
		$datos_boletas [494] [1]=0.00;
		$datos_boletas [495] [0]=4798;
		$datos_boletas [495] [1]=0.00;
		$datos_boletas [496] [0]=4799;
		$datos_boletas [496] [1]=767.47;
		$datos_boletas [497] [0]=4800;
		$datos_boletas [497] [1]=0.00;
		$datos_boletas [498] [0]=4801;
		$datos_boletas [498] [1]=121.80;
		$datos_boletas [499] [0]=4802;
		$datos_boletas [499] [1]=357.16;
		$datos_boletas [500] [0]=4803;
		$datos_boletas [500] [1]=1450.81;
		$datos_boletas [501] [0]=4804;
		$datos_boletas [501] [1]=605.27;
		$datos_boletas [502] [0]=4805;
		$datos_boletas [502] [1]=780.13;
		$datos_boletas [503] [0]=4806;
		$datos_boletas [503] [1]=1326.81;
		$datos_boletas [504] [0]=4807;
		$datos_boletas [504] [1]=250.50;
		$datos_boletas [505] [0]=4808;
		$datos_boletas [505] [1]=121.80;
		$datos_boletas [506] [0]=4809;
		$datos_boletas [506] [1]=1605.22;
		$datos_boletas [507] [0]=4810;
		$datos_boletas [507] [1]=0.00;
		$datos_boletas [508] [0]=4811;
		$datos_boletas [508] [1]=1160.50;
		$datos_boletas [509] [0]=4812;
		$datos_boletas [509] [1]=796.36;
		$datos_boletas [510] [0]=4813;
		$datos_boletas [510] [1]=121.80;
		$datos_boletas [511] [0]=4814;
		$datos_boletas [511] [1]=0.00;
		$datos_boletas [512] [0]=4815;
		$datos_boletas [512] [1]=0.00;
		$datos_boletas [513] [0]=4816;
		$datos_boletas [513] [1]=379.29;
		$datos_boletas [514] [0]=4817;
		$datos_boletas [514] [1]=0.00;
		$datos_boletas [515] [0]=4818;
		$datos_boletas [515] [1]=0.00;
		$datos_boletas [516] [0]=4819;
		$datos_boletas [516] [1]=0.00;
		$datos_boletas [517] [0]=4820;
		$datos_boletas [517] [1]=659.35;
		$datos_boletas [518] [0]=4821;
		$datos_boletas [518] [1]=0.00;
		$datos_boletas [519] [0]=4822;
		$datos_boletas [519] [1]=445.34;
		$datos_boletas [520] [0]=4823;
		$datos_boletas [520] [1]=0.00;
		$datos_boletas [521] [0]=4824;
		$datos_boletas [521] [1]=2475.40;
		$datos_boletas [522] [0]=4825;
		$datos_boletas [522] [1]=0.00;
		$datos_boletas [523] [0]=4826;
		$datos_boletas [523] [1]=161.99;
		$datos_boletas [524] [0]=4827;
		$datos_boletas [524] [1]=635.66;
		$datos_boletas [525] [0]=4828;
		$datos_boletas [525] [1]=0.00;
		$datos_boletas [526] [0]=4829;
		$datos_boletas [526] [1]=2384.70;
		$datos_boletas [527] [0]=4830;
		$datos_boletas [527] [1]=503.07;
		$datos_boletas [528] [0]=4831;
		$datos_boletas [528] [1]=0.00;
		$datos_boletas [529] [0]=4832;
		$datos_boletas [529] [1]=0.00;
		$datos_boletas [530] [0]=4833;
		$datos_boletas [530] [1]=177.07;
		$datos_boletas [531] [0]=4834;
		$datos_boletas [531] [1]=0.00;
		$datos_boletas [532] [0]=4835;
		$datos_boletas [532] [1]=0.00;
		$datos_boletas [533] [0]=4836;
		$datos_boletas [533] [1]=0.00;
		$datos_boletas [534] [0]=4837;
		$datos_boletas [534] [1]=247.41;
		$datos_boletas [535] [0]=4838;
		$datos_boletas [535] [1]=599.02;
		$datos_boletas [536] [0]=4839;
		$datos_boletas [536] [1]=1141.54;
		$datos_boletas [537] [0]=4840;
		$datos_boletas [537] [1]=121.80;
		$datos_boletas [538] [0]=4841;
		$datos_boletas [538] [1]=0.00;
		$datos_boletas [539] [0]=4842;
		$datos_boletas [539] [1]=0.00;
		$datos_boletas [540] [0]=4843;
		$datos_boletas [540] [1]=0.00;
		$datos_boletas [541] [0]=4844;
		$datos_boletas [541] [1]=292.62;
		$datos_boletas [542] [0]=4845;
		$datos_boletas [542] [1]=0.00;
		$datos_boletas [543] [0]=4846;
		$datos_boletas [543] [1]=582.66;
		$datos_boletas [544] [0]=4847;
		$datos_boletas [544] [1]=121.80;
		$datos_boletas [545] [0]=4848;
		$datos_boletas [545] [1]=0.00;
		$datos_boletas [546] [0]=4849;
		$datos_boletas [546] [1]=0.00;
		$datos_boletas [547] [0]=4850;
		$datos_boletas [547] [1]=0.00;
		$datos_boletas [548] [0]=4851;
		$datos_boletas [548] [1]=1530.32;
		$datos_boletas [549] [0]=4852;
		$datos_boletas [549] [1]=282.58;
		$datos_boletas [550] [0]=4853;
		$datos_boletas [550] [1]=418.23;
		$datos_boletas [551] [0]=4854;
		$datos_boletas [551] [1]=0.00;
		$datos_boletas [552] [0]=4855;
		$datos_boletas [552] [1]=0.00;
		$datos_boletas [553] [0]=4856;
		$datos_boletas [553] [1]=1046.58;
		$datos_boletas [554] [0]=4857;
		$datos_boletas [554] [1]=7616.98;
		$datos_boletas [555] [0]=4858;
		$datos_boletas [555] [1]=751.10;
		$datos_boletas [556] [0]=4859;
		$datos_boletas [556] [1]=160.37;
		$datos_boletas [557] [0]=4860;
		$datos_boletas [557] [1]=495.07;
		$datos_boletas [558] [0]=4861;
		$datos_boletas [558] [1]=182.09;
		$datos_boletas [559] [0]=4862;
		$datos_boletas [559] [1]=4729.70;
		$datos_boletas [560] [0]=4863;
		$datos_boletas [560] [1]=0.00;
		$datos_boletas [561] [0]=4864;
		$datos_boletas [561] [1]=0.00;
		$datos_boletas [562] [0]=4865;
		$datos_boletas [562] [1]=0.00;
		$datos_boletas [563] [0]=4866;
		$datos_boletas [563] [1]=5856.72;
		$datos_boletas [564] [0]=4867;
		$datos_boletas [564] [1]=0.00;
		$datos_boletas [565] [0]=4868;
		$datos_boletas [565] [1]=0.00;
		$datos_boletas [566] [0]=4869;
		$datos_boletas [566] [1]=1654.21;
		$datos_boletas [567] [0]=4870;
		$datos_boletas [567] [1]=0.00;
		$datos_boletas [568] [0]=4871;
		$datos_boletas [568] [1]=0.00;
		$datos_boletas [569] [0]=4872;
		$datos_boletas [569] [1]=121.80;
		$datos_boletas [570] [0]=4873;
		$datos_boletas [570] [1]=1750.80;
		$datos_boletas [571] [0]=4874;
		$datos_boletas [571] [1]=1702.50;
		$datos_boletas [572] [0]=4875;
		$datos_boletas [572] [1]=635.66;
		$datos_boletas [573] [0]=4876;
		$datos_boletas [573] [1]=0.00;
		$datos_boletas [574] [0]=4877;
		$datos_boletas [574] [1]=0.00;
		$datos_boletas [575] [0]=4878;
		$datos_boletas [575] [1]=121.80;
		$datos_boletas [576] [0]=4879;
		$datos_boletas [576] [1]=0.00;
		$datos_boletas [577] [0]=4880;
		$datos_boletas [577] [1]=0.00;
		$datos_boletas [578] [0]=4881;
		$datos_boletas [578] [1]=25942.13;
		$datos_boletas [579] [0]=4882;
		$datos_boletas [579] [1]=0.00;
		$datos_boletas [580] [0]=4883;
		$datos_boletas [580] [1]=0.00;
		$datos_boletas [581] [0]=4884;
		$datos_boletas [581] [1]=0.00;
		$datos_boletas [582] [0]=4885;
		$datos_boletas [582] [1]=0.00;
		$datos_boletas [583] [0]=4886;
		$datos_boletas [583] [1]=365.30;
		$datos_boletas [584] [0]=4887;
		$datos_boletas [584] [1]=0.00;
		$datos_boletas [585] [0]=4888;
		$datos_boletas [585] [1]=0.00;
		$datos_boletas [586] [0]=4889;
		$datos_boletas [586] [1]=868.92;
		$datos_boletas [587] [0]=4890;
		$datos_boletas [587] [1]=265.81;
		$datos_boletas [588] [0]=4891;
		$datos_boletas [588] [1]=252.43;
		$datos_boletas [589] [0]=4892;
		$datos_boletas [589] [1]=187.12;
		$datos_boletas [590] [0]=4893;
		$datos_boletas [590] [1]=13674.06;
		$datos_boletas [591] [0]=4894;
		$datos_boletas [591] [1]=126.82;
		$datos_boletas [592] [0]=4895;
		$datos_boletas [592] [1]=0.00;
		$datos_boletas [593] [0]=4896;
		$datos_boletas [593] [1]=121.80;
		$datos_boletas [594] [0]=4897;
		$datos_boletas [594] [1]=0.00;
		$datos_boletas [595] [0]=4898;
		$datos_boletas [595] [1]=403.90;
		$datos_boletas [596] [0]=4899;
		$datos_boletas [596] [1]=0.00;
		$datos_boletas [597] [0]=4900;
		$datos_boletas [597] [1]=256.17;
		$datos_boletas [598] [0]=4901;
		$datos_boletas [598] [1]=0.00;
		$datos_boletas [599] [0]=4902;
		$datos_boletas [599] [1]=1862.58;
		$datos_boletas [600] [0]=4903;
		$datos_boletas [600] [1]=473.73;
		$datos_boletas [601] [0]=4904;
		$datos_boletas [601] [1]=3746.15;
		$datos_boletas [602] [0]=4905;
		$datos_boletas [602] [1]=121.80;
		$datos_boletas [603] [0]=4906;
		$datos_boletas [603] [1]=200.82;
		$datos_boletas [604] [0]=4907;
		$datos_boletas [604] [1]=0.00;
		$datos_boletas [605] [0]=4908;
		$datos_boletas [605] [1]=1118.90;
		$datos_boletas [606] [0]=4909;
		$datos_boletas [606] [1]=0.00;
		$datos_boletas [607] [0]=4910;
		$datos_boletas [607] [1]=0.00;
		$datos_boletas [608] [0]=4911;
		$datos_boletas [608] [1]=0.00;
		$datos_boletas [609] [0]=4912;
		$datos_boletas [609] [1]=0.00;
		$datos_boletas [610] [0]=4913;
		$datos_boletas [610] [1]=1047.67;
		$datos_boletas [611] [0]=4914;
		$datos_boletas [611] [1]=6812.04;
		$datos_boletas [612] [0]=4915;
		$datos_boletas [612] [1]=1299.93;
		$datos_boletas [613] [0]=4916;
		$datos_boletas [613] [1]=0.00;
		$datos_boletas [614] [0]=4917;
		$datos_boletas [614] [1]=167.02;
		$datos_boletas [615] [0]=4918;
		$datos_boletas [615] [1]=0.00;
		$datos_boletas [616] [0]=4919;
		$datos_boletas [616] [1]=1781.38;
		$datos_boletas [617] [0]=4920;
		$datos_boletas [617] [1]=0.00;
		$datos_boletas [618] [0]=4921;
		$datos_boletas [618] [1]=0.00;
		$datos_boletas [619] [0]=4922;
		$datos_boletas [619] [1]=1325.08;
		$datos_boletas [620] [0]=4923;
		$datos_boletas [620] [1]=0.00;
		$datos_boletas [621] [0]=4924;
		$datos_boletas [621] [1]=146.92;
		$datos_boletas [622] [0]=4925;
		$datos_boletas [622] [1]=0.00;
		$datos_boletas [623] [0]=4926;
		$datos_boletas [623] [1]=0.00;
		$datos_boletas [624] [0]=4927;
		$datos_boletas [624] [1]=0.00;
		$datos_boletas [625] [0]=4928;
		$datos_boletas [625] [1]=3542.49;
		$datos_boletas [626] [0]=4929;
		$datos_boletas [626] [1]=0.00;
		$datos_boletas [627] [0]=4930;
		$datos_boletas [627] [1]=0.00;
		$datos_boletas [628] [0]=4931;
		$datos_boletas [628] [1]=316.38;
		$datos_boletas [629] [0]=4932;
		$datos_boletas [629] [1]=0.00;
		$datos_boletas [630] [0]=4933;
		$datos_boletas [630] [1]=0.00;
		$datos_boletas [631] [0]=4934;
		$datos_boletas [631] [1]=376.67;
		$datos_boletas [632] [0]=4935;
		$datos_boletas [632] [1]=1459.44;
		$datos_boletas [633] [0]=4936;
		$datos_boletas [633] [1]=0.00;
		$datos_boletas [634] [0]=4937;
		$datos_boletas [634] [1]=0.00;
		$datos_boletas [635] [0]=4938;
		$datos_boletas [635] [1]=0.00;
		$datos_boletas [636] [0]=4939;
		$datos_boletas [636] [1]=7766.49;
		$datos_boletas [637] [0]=4940;
		$datos_boletas [637] [1]=167.02;
		$datos_boletas [638] [0]=4941;
		$datos_boletas [638] [1]=0.00;
		$datos_boletas [639] [0]=4942;
		$datos_boletas [639] [1]=0.00;
		$datos_boletas [640] [0]=4943;
		$datos_boletas [640] [1]=0.00;
		$datos_boletas [641] [0]=4944;
		$datos_boletas [641] [1]=121.80;
		$datos_boletas [642] [0]=4945;
		$datos_boletas [642] [1]=0.00;
		$datos_boletas [643] [0]=4946;
		$datos_boletas [643] [1]=0.00;
		$datos_boletas [644] [0]=4947;
		$datos_boletas [644] [1]=1321.44;
		$datos_boletas [645] [0]=4948;
		$datos_boletas [645] [1]=0.00;
		$datos_boletas [646] [0]=4949;
		$datos_boletas [646] [1]=0.00;
		$datos_boletas [647] [0]=4950;
		$datos_boletas [647] [1]=10576.91;
		$datos_boletas [648] [0]=4951;
		$datos_boletas [648] [1]=0.00;
		$datos_boletas [649] [0]=4952;
		$datos_boletas [649] [1]=0.00;
		$datos_boletas [650] [0]=4953;
		$datos_boletas [650] [1]=0.00;
		$datos_boletas [651] [0]=4954;
		$datos_boletas [651] [1]=1341.13;
		$datos_boletas [652] [0]=4955;
		$datos_boletas [652] [1]=0.00;
		$datos_boletas [653] [0]=4956;
		$datos_boletas [653] [1]=0.00;
		$datos_boletas [654] [0]=4957;
		$datos_boletas [654] [1]=0.00;
		$datos_boletas [655] [0]=4958;
		$datos_boletas [655] [1]=0.00;
		$datos_boletas [656] [0]=4959;
		$datos_boletas [656] [1]=5554.10;
		$datos_boletas [657] [0]=4960;
		$datos_boletas [657] [1]=594.50;
		$datos_boletas [658] [0]=4961;
		$datos_boletas [658] [1]=0.00;
		$datos_boletas [659] [0]=4962;
		$datos_boletas [659] [1]=3478.66;
		$datos_boletas [660] [0]=4963;
		$datos_boletas [660] [1]=388.09;
		$datos_boletas [661] [0]=4964;
		$datos_boletas [661] [1]=0.00;
		$datos_boletas [662] [0]=4965;
		$datos_boletas [662] [1]=1800.96;
		$datos_boletas [663] [0]=4966;
		$datos_boletas [663] [1]=0.00;
		$datos_boletas [664] [0]=4967;
		$datos_boletas [664] [1]=1411.81;
		$datos_boletas [665] [0]=4968;
		$datos_boletas [665] [1]=393.96;
		$datos_boletas [666] [0]=4969;
		$datos_boletas [666] [1]=0.00;
		$datos_boletas [667] [0]=4970;
		$datos_boletas [667] [1]=0.00;
		$datos_boletas [668] [0]=4971;
		$datos_boletas [668] [1]=2490.15;
		$datos_boletas [669] [0]=4972;
		$datos_boletas [669] [1]=7856.43;
		$datos_boletas [670] [0]=4973;
		$datos_boletas [670] [1]=727.79;
		$datos_boletas [671] [0]=4974;
		$datos_boletas [671] [1]=0.00;
		$datos_boletas [672] [0]=4975;
		$datos_boletas [672] [1]=0.00;
		$datos_boletas [673] [0]=4976;
		$datos_boletas [673] [1]=0.00;
		$datos_boletas [674] [0]=4977;
		$datos_boletas [674] [1]=167.02;
		$datos_boletas [675] [0]=4978;
		$datos_boletas [675] [1]=1170.05;
		$datos_boletas [676] [0]=4979;
		$datos_boletas [676] [1]=664.07;
		$datos_boletas [677] [0]=4980;
		$datos_boletas [677] [1]=395.15;
		$datos_boletas [678] [0]=4981;
		$datos_boletas [678] [1]=2511.98;
		$datos_boletas [679] [0]=4982;
		$datos_boletas [679] [1]=0.00;
		$datos_boletas [680] [0]=4983;
		$datos_boletas [680] [1]=0.00;
		$datos_boletas [681] [0]=4984;
		$datos_boletas [681] [1]=2103.10;
		$datos_boletas [682] [0]=4985;
		$datos_boletas [682] [1]=1856.14;
		$datos_boletas [683] [0]=4986;
		$datos_boletas [683] [1]=0.00;
		$datos_boletas [684] [0]=4987;
		$datos_boletas [684] [1]=0.00;
		$datos_boletas [685] [0]=4988;
		$datos_boletas [685] [1]=0.00;
		$datos_boletas [686] [0]=4989;
		$datos_boletas [686] [1]=22334.98;
		$datos_boletas [687] [0]=4990;
		$datos_boletas [687] [1]=121.80;
		$datos_boletas [688] [0]=4991;
		$datos_boletas [688] [1]=1560.00;
		$datos_boletas [689] [0]=4992;
		$datos_boletas [689] [1]=0.00;
		$datos_boletas [690] [0]=4993;
		$datos_boletas [690] [1]=6053.54;
		$datos_boletas [691] [0]=4994;
		$datos_boletas [691] [1]=0.00;
		$datos_boletas [692] [0]=4995;
		$datos_boletas [692] [1]=0.00;
		$datos_boletas [693] [0]=4996;
		$datos_boletas [693] [1]=580.97;
		$datos_boletas [694] [0]=4997;
		$datos_boletas [694] [1]=15992.37;
		$datos_boletas [695] [0]=4998;
		$datos_boletas [695] [1]=1560.07;
		$datos_boletas [696] [0]=4999;
		$datos_boletas [696] [1]=0.00;
		$datos_boletas [697] [0]=5000;
		$datos_boletas [697] [1]=5136.76;
		$datos_boletas [698] [0]=5001;
		$datos_boletas [698] [1]=6079.71;
		$datos_boletas [699] [0]=5002;
		$datos_boletas [699] [1]=0.00;
		$datos_boletas [700] [0]=5003;
		$datos_boletas [700] [1]=928.64;
		$datos_boletas [701] [0]=5004;
		$datos_boletas [701] [1]=426.96;
		$datos_boletas [702] [0]=5005;
		$datos_boletas [702] [1]=125.66;
		$datos_boletas [703] [0]=5006;
		$datos_boletas [703] [1]=0.00;
		$datos_boletas [704] [0]=5007;
		$datos_boletas [704] [1]=0.00;
		$datos_boletas [705] [0]=5008;
		$datos_boletas [705] [1]=1712.18;
		$datos_boletas [706] [0]=5009;
		$datos_boletas [706] [1]=0.00;
		$datos_boletas [707] [0]=5010;
		$datos_boletas [707] [1]=4619.32;
		$datos_boletas [708] [0]=5011;
		$datos_boletas [708] [1]=0.00;
		$datos_boletas [709] [0]=5012;
		$datos_boletas [709] [1]=0.00;
		$datos_boletas [710] [0]=5013;
		$datos_boletas [710] [1]=0.00;
		$datos_boletas [711] [0]=5014;
		$datos_boletas [711] [1]=0.00;
		$datos_boletas [712] [0]=5015;
		$datos_boletas [712] [1]=0.00;
		$datos_boletas [713] [0]=5016;
		$datos_boletas [713] [1]=0.00;
		$datos_boletas [714] [0]=5017;
		$datos_boletas [714] [1]=332.16;
		$datos_boletas [715] [0]=5018;
		$datos_boletas [715] [1]=0.00;
		$datos_boletas [716] [0]=5019;
		$datos_boletas [716] [1]=0.00;
		$datos_boletas [717] [0]=5020;
		$datos_boletas [717] [1]=508.05;
		$datos_boletas [718] [0]=5021;
		$datos_boletas [718] [1]=0.00;
		$datos_boletas [719] [0]=5022;
		$datos_boletas [719] [1]=0.00;
		$datos_boletas [720] [0]=5023;
		$datos_boletas [720] [1]=0.00;
		$datos_boletas [721] [0]=5024;
		$datos_boletas [721] [1]=3064.19;
		$datos_boletas [722] [0]=5025;
		$datos_boletas [722] [1]=481.79;
		$datos_boletas [723] [0]=5026;
		$datos_boletas [723] [1]=798.42;
		$datos_boletas [724] [0]=5027;
		$datos_boletas [724] [1]=0.00;
		$datos_boletas [725] [0]=5028;
		$datos_boletas [725] [1]=0.00;
		$datos_boletas [726] [0]=5029;
		$datos_boletas [726] [1]=1713.57;
		$datos_boletas [727] [0]=5030;
		$datos_boletas [727] [1]=4407.07;
		$datos_boletas [728] [0]=5031;
		$datos_boletas [728] [1]=648.48;
		$datos_boletas [729] [0]=5032;
		$datos_boletas [729] [1]=0.00;
		$datos_boletas [730] [0]=5033;
		$datos_boletas [730] [1]=18161.27;
		$datos_boletas [731] [0]=5034;
		$datos_boletas [731] [1]=0.00;
		$datos_boletas [732] [0]=5035;
		$datos_boletas [732] [1]=0.00;
		$datos_boletas [733] [0]=5036;
		$datos_boletas [733] [1]=0.00;
		$datos_boletas [734] [0]=5037;
		$datos_boletas [734] [1]=0.00;
		$datos_boletas [735] [0]=5038;
		$datos_boletas [735] [1]=0.00;
		$datos_boletas [736] [0]=5039;
		$datos_boletas [736] [1]=715.20;
		$datos_boletas [737] [0]=5040;
		$datos_boletas [737] [1]=491.38;
		$datos_boletas [738] [0]=5041;
		$datos_boletas [738] [1]=0.00;
		$datos_boletas [739] [0]=5042;
		$datos_boletas [739] [1]=0.00;
		$datos_boletas [740] [0]=5043;
		$datos_boletas [740] [1]=0.00;
		$datos_boletas [741] [0]=5044;
		$datos_boletas [741] [1]=0.00;
		$datos_boletas [742] [0]=5045;
		$datos_boletas [742] [1]=1679.72;
		$datos_boletas [743] [0]=5046;
		$datos_boletas [743] [1]=3374.81;
		$datos_boletas [744] [0]=5047;
		$datos_boletas [744] [1]=0.00;
		$datos_boletas [745] [0]=5048;
		$datos_boletas [745] [1]=192.14;
		$datos_boletas [746] [0]=5049;
		$datos_boletas [746] [1]=983.37;
		$datos_boletas [747] [0]=5050;
		$datos_boletas [747] [1]=3963.79;
		$datos_boletas [748] [0]=5051;
		$datos_boletas [748] [1]=227.31;
		$datos_boletas [749] [0]=5052;
		$datos_boletas [749] [1]=0.00;
		$datos_boletas [750] [0]=5053;
		$datos_boletas [750] [1]=0.00;
		$datos_boletas [751] [0]=5054;
		$datos_boletas [751] [1]=0.00;
		$datos_boletas [752] [0]=5055;
		$datos_boletas [752] [1]=0.00;
		$datos_boletas [753] [0]=5056;
		$datos_boletas [753] [1]=8693.43;
		$datos_boletas [754] [0]=5057;
		$datos_boletas [754] [1]=1223.36;
		$datos_boletas [755] [0]=5058;
		$datos_boletas [755] [1]=121.80;
		$datos_boletas [756] [0]=5059;
		$datos_boletas [756] [1]=486.46;
		$datos_boletas [757] [0]=5060;
		$datos_boletas [757] [1]=0.00;
		$datos_boletas [758] [0]=5061;
		$datos_boletas [758] [1]=832.13;
		$datos_boletas [759] [0]=5062;
		$datos_boletas [759] [1]=0.00;
		$datos_boletas [760] [0]=5063;
		$datos_boletas [760] [1]=501.68;
		$datos_boletas [761] [0]=5064;
		$datos_boletas [761] [1]=4865.93;
		$datos_boletas [762] [0]=5065;
		$datos_boletas [762] [1]=0.00;
		$datos_boletas [763] [0]=5066;
		$datos_boletas [763] [1]=433.30;
		$datos_boletas [764] [0]=5067;
		$datos_boletas [764] [1]=2526.03;
		$datos_boletas [765] [0]=5068;
		$datos_boletas [765] [1]=1130.86;
		$datos_boletas [766] [0]=5069;
		$datos_boletas [766] [1]=0.00;
		$datos_boletas [767] [0]=5070;
		$datos_boletas [767] [1]=312.72;
		$datos_boletas [768] [0]=5071;
		$datos_boletas [768] [1]=0.00;
		$datos_boletas [769] [0]=5072;
		$datos_boletas [769] [1]=2749.22;
		$datos_boletas [770] [0]=5073;
		$datos_boletas [770] [1]=0.00;
		$datos_boletas [771] [0]=5074;
		$datos_boletas [771] [1]=0.00;
		$datos_boletas [772] [0]=5075;
		$datos_boletas [772] [1]=430.00;
		$datos_boletas [773] [0]=5076;
		$datos_boletas [773] [1]=0.00;
		$datos_boletas [774] [0]=5077;
		$datos_boletas [774] [1]=0.00;
		$datos_boletas [775] [0]=5078;
		$datos_boletas [775] [1]=8144.58;
		$datos_boletas [776] [0]=5079;
		$datos_boletas [776] [1]=5043.06;
		$datos_boletas [777] [0]=5080;
		$datos_boletas [777] [1]=0.00;
		$datos_boletas [778] [0]=5081;
		$datos_boletas [778] [1]=121.80;
		$datos_boletas [779] [0]=5082;
		$datos_boletas [779] [1]=2493.52;
		$datos_boletas [780] [0]=5083;
		$datos_boletas [780] [1]=18329.20;
		$datos_boletas [781] [0]=5084;
		$datos_boletas [781] [1]=795.24;
		$datos_boletas [782] [0]=5085;
		$datos_boletas [782] [1]=2145.43;
		$datos_boletas [783] [0]=5086;
		$datos_boletas [783] [1]=0.00;
		$datos_boletas [784] [0]=5087;
		$datos_boletas [784] [1]=321.63;
		$datos_boletas [785] [0]=5088;
		$datos_boletas [785] [1]=232.33;
		$datos_boletas [786] [0]=5089;
		$datos_boletas [786] [1]=2938.62;
		$datos_boletas [787] [0]=5090;
		$datos_boletas [787] [1]=0.00;
		$datos_boletas [788] [0]=5091;
		$datos_boletas [788] [1]=1355.17;
		$datos_boletas [789] [0]=5092;
		$datos_boletas [789] [1]=4813.53;
		$datos_boletas [790] [0]=5093;
		$datos_boletas [790] [1]=403.75;
		$datos_boletas [791] [0]=5094;
		$datos_boletas [791] [1]=876.60;
		$datos_boletas [792] [0]=5095;
		$datos_boletas [792] [1]=121.80;
		$datos_boletas [793] [0]=5096;
		$datos_boletas [793] [1]=0.00;
		$datos_boletas [794] [0]=5097;
		$datos_boletas [794] [1]=0.00;
		$datos_boletas [795] [0]=5098;
		$datos_boletas [795] [1]=3289.42;
		$datos_boletas [796] [0]=5099;
		$datos_boletas [796] [1]=121.80;
		$datos_boletas [797] [0]=5100;
		$datos_boletas [797] [1]=261.11;
		$datos_boletas [798] [0]=5101;
		$datos_boletas [798] [1]=0.00;
		$datos_boletas [799] [0]=5102;
		$datos_boletas [799] [1]=0.00;
		$datos_boletas [800] [0]=5103;
		$datos_boletas [800] [1]=2435.47;
		$datos_boletas [801] [0]=5104;
		$datos_boletas [801] [1]=172.04;
		$datos_boletas [802] [0]=5105;
		$datos_boletas [802] [1]=0.00;
		$datos_boletas [803] [0]=5106;
		$datos_boletas [803] [1]=0.00;
		$datos_boletas [804] [0]=5107;
		$datos_boletas [804] [1]=5288.59;
		$datos_boletas [805] [0]=5108;
		$datos_boletas [805] [1]=7171.62;
		$datos_boletas [806] [0]=5109;
		$datos_boletas [806] [1]=9518.38;
		$datos_boletas [807] [0]=5110;
		$datos_boletas [807] [1]=754.25;
		$datos_boletas [808] [0]=5111;
		$datos_boletas [808] [1]=3724.38;
		$datos_boletas [809] [0]=5112;
		$datos_boletas [809] [1]=0.00;
		$datos_boletas [810] [0]=5113;
		$datos_boletas [810] [1]=0.00;
		$datos_boletas [811] [0]=5114;
		$datos_boletas [811] [1]=7068.08;
		$datos_boletas [812] [0]=5115;
		$datos_boletas [812] [1]=190.77;
		$datos_boletas [813] [0]=5116;
		$datos_boletas [813] [1]=1114.93;
		$datos_boletas [814] [0]=5117;
		$datos_boletas [814] [1]=460.00;
		$datos_boletas [815] [0]=5118;
		$datos_boletas [815] [1]=683.75;
		$datos_boletas [816] [0]=5119;
		$datos_boletas [816] [1]=296.28;
		$datos_boletas [817] [0]=5120;
		$datos_boletas [817] [1]=267.50;
		$datos_boletas [818] [0]=5121;
		$datos_boletas [818] [1]=0.00;
		$datos_boletas [819] [0]=5122;
		$datos_boletas [819] [1]=0.00;
		$datos_boletas [820] [0]=5123;
		$datos_boletas [820] [1]=1225.32;
		$datos_boletas [821] [0]=5124;
		$datos_boletas [821] [1]=1435.97;
		$datos_boletas [822] [0]=5125;
		$datos_boletas [822] [1]=1027.32;
		$datos_boletas [823] [0]=5126;
		$datos_boletas [823] [1]=2545.06;
		$datos_boletas [824] [0]=5127;
		$datos_boletas [824] [1]=146.92;
		$datos_boletas [825] [0]=5128;
		$datos_boletas [825] [1]=801.28;
		$datos_boletas [826] [0]=5129;
		$datos_boletas [826] [1]=0.00;
		$datos_boletas [827] [0]=5130;
		$datos_boletas [827] [1]=983.13;
		$datos_boletas [828] [0]=5131;
		$datos_boletas [828] [1]=0.00;
		$datos_boletas [829] [0]=5132;
		$datos_boletas [829] [1]=9792.73;
		$datos_boletas [830] [0]=5133;
		$datos_boletas [830] [1]=1845.60;
		$datos_boletas [831] [0]=5134;
		$datos_boletas [831] [1]=0.00;
		$datos_boletas [832] [0]=5135;
		$datos_boletas [832] [1]=0.00;
		$datos_boletas [833] [0]=5136;
		$datos_boletas [833] [1]=121.80;
		$datos_boletas [834] [0]=5137;
		$datos_boletas [834] [1]=121.80;
		$datos_boletas [835] [0]=5138;
		$datos_boletas [835] [1]=42299.14;
		$datos_boletas [836] [0]=5139;
		$datos_boletas [836] [1]=-2040.99;
		$datos_boletas [837] [0]=5140;
		$datos_boletas [837] [1]=121.80;
		$datos_boletas [838] [0]=5143;
		$datos_boletas [838] [1]=0.00;
		for ($i=0; $i < 839 ; $i++) { 
			$arrayName = array(
					'Factura_Deuda' => $datos_boletas [$i] [1],
					 );
		$this->Nuevo_model->update_data($arrayName, $datos_boletas [$i] [0], "facturacion_nueva" ,"Factura_Id");
		}
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
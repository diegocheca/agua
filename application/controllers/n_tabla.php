<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class N_tabla extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('Nuevo_model');
		$this->load->helper('PDF_helper');
		$this->load->helper('eFPDF_helper');
		$this->load->library('zend');
	}
	public function index(){
		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//estoy adentro
			$this->load->view("nuevo/tabla_pago");
		endif;
	}
	public function traer_super_datos(){
		
			$data ["usuarios"] = $this->Nuevo_model->join_nivel_dios_dos();
			$this->load->helper(array('url'));
			//$data ["usuarios"] = $this->Crud_model->get_data("configuracion"); 
			$this->output->set_content_type('application/json')->set_output(json_encode($data ["usuarios"]));
			// header('Content-Type: application/json');
			// echo  json_encode($data ["usuarios"]);
	}
	public function traer_datos_por_query($sector = -1, $mes = -1, $ano= -1, $id_conexion = -1, $pagado = -1 ){
		$aux = "Jardines del Sur";
		
		if(strlen($sector)  == 9)
			$sector = "V Elisa";
		if(strlen($sector)  == 20)
			$sector = "ASENTAMIENTO OLMOS";
		if(strlen($sector)  == 15)
			$sector = "Santa Barbara";
		if(strlen($sector)  == 6)
		 	$sector = "Jardines del Sur";
		//var_dump($sector, strlen($sector),$aux, strlen($aux));die();
		$data ["usuarios"] = $this->Nuevo_model->traer_facturas_por_barrio_nuevo($sector, $mes , $ano, $id_conexion, $pagado);
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
				"Conexion_Sector" =>  $sector ,
				"Conexion_UnionVecinal" =>  $orden_sector,
				"Conexion_DomicilioSuministro" =>  $domicilio
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
			//var_dump($resultado_copia);die();
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

			//actualizar los datos de la conexion
			//voy a buscar la deuda anterior
			$deuda_a_grabar = 0;
			if($request["tipo_de_pago"] != 1)//pago parte de la boleta, entonces genero deuda
			{
				$datos_conexion = $this->Nuevo_model->get_data_dos_campos("conexion","Conexion_Id",$request["id_conexion"], "Conexion_Borrado",0);
				$deuda_a_grabar =  floatval($request["endeuda"]);
			}
			$datos = array(
				"Conexion_Deuda" => $deuda_a_grabar,
				//"Conexion_Deuda" => $deuda_a_grabar,
			);
			//var_dump($request["tipo_de_pago"],$request["endeuda"],$datos);die();
			$resultado_conexion = $this->Nuevo_model->update_data($datos, $request["id_conexion"], "Conexion","Conexion_Id");
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
				 	'Mov_Timestamp' =>	$inputFecha
			);

			//var_dump($datos_movimiento);die();
			$id_movimiento = $this->Nuevo_model->insert_data("movimiento",$datos_movimiento);

		}
		if($resultado_factura && $resultado_conexion)
			echo  true;
		else echo  false;
		//$this->output->set_content_type('application/json')->set_output(json_encode($id));
	}

	
}
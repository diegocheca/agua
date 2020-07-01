<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pago extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->helper('PDF_helper');
//		include('Barcode.php'); 
		$this->load->helper('eFPDF_helper');
		// $this->load->helper('salida_rapida_helper');
		// $this->load->helper('pdf_autoprint_helper');
		$this->load->library('zend');
   	
		
	}

	public function index(){
		//echo "estoy aca en clientes index"; die();

		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:

			//$datos['clientes'] = $this->Crud_model->get_data("clientes");
			$datos['pagos'] = $this->Crud_model->get_data_join_sin_borrados("pago","clientes", "Pago_Borrado", "Pago_Cli_Id", "Cli_Id");
			$datos['titulo'] = 'Lista de Pagos';
			$datos['color'] = color_pago;
			$this->load->view('templates/header',$datos);
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
			$this->load->view('pago/pago', $datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$data ['aviso'] = $this->session->flashdata('aviso');
			if($data ['aviso'] != null)
			{
				//echo "estoy llamando";
				$this->load->view("templates/notificacion_view", $data);
			}
		endif;

	}
	//x aca
	public function nuevo($nuevo, $viejo){
		echo "estoy en nuevo:".$nuevo." - mas:".$viejo;die();
	}

	public function datos_personales_para_pago(){
				$this->load->model('Impresiones_model');
		$mostrar = $this->input->post("datos_personales_para_pagar", true);
		$datos = explode("*", $mostrar );
		//var_dump($datos);die();
		$ultima_factura = $this->Impresiones_model->buscar_ultima_boleta($datos[1],$datos[0]);
		//var_dump($ultima_factura);die();
		if($ultima_factura == false)
			echo "el cliente no tiene conexion";
		elseif($ultima_factura->Factura_Habilitacion == 0)
		{
			//var_dump($ultima_factura);die();
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			$this->load->view('templates/header',$datos);
			$data["mensaje"] = "Esta boleta ya ha sido pagada anteriormente";
			$this->load->view("notificaciones/sin_permiso_view", $data);
			$this->load->view('templates/footer');
		}

		else $this->agregar_por_codigo_barra($ultima_factura->id_factura);
		//var_dump($ultima_factura);
	}



	
	function crear_factura_por_lote()
	{
		//Se crea un objeto de PDF
		//Para hacer uso de los métodos
		$this->load->model('Facturar_model');
		$datos = $this->Facturar_model->buscar_prueba();
		var_dump($datos);
		$names = array('Frank', 'Todd', 'James');
		echo "cambio:";
		var_dump($names);
		die();

		$pdf = new eFPDF();
		$pdf->crear_factura_por_lote($datos );
	
	}


	public function boleta()
	{
	 	//Se crea un objeto de PDF
		//Para hacer uso de los métodos
		$pdf = new PDF();
		$pdf->AddPage('P', 'Letter'); //Vertical, Carta
		$pdf->SetFont('Arial','B',12); //Arial, negrita, 12 puntos
		date_default_timezone_set("America/Argentina/Mendoza");
		$fecha= date("d-m-Y H:i:s");
		//Imprime la fecha
		$pdf->Cell(0,6,$fecha,0,1,'R');
		//Imprime un texto
		$pdf->Ln();$pdf->Ln();
		$pdf->SetTextColor(0,0,0); //Arial, negrita, 12 puntos
		$pdf->Cell(0,10,"NUMERO DE ORDEN DE TRASNPORTE: QUE SE YO ESTOY RE LOKO",0, 2 ,'C',false);
		$pdf->Cell(0,10,'G  E  N  E  R  A  D  O  R',0, 2 ,'C',false);
		$pdf->Ln(4);
		/*
		------------------------------
		FIN DEL TOCKET A IMRIMIR
		------------------------------
		*/
		    $nombre="Orden Transporte.pdf";
		    //$pdf->Output($nombre,'I'); //Salida al navegador del pdf'PAMY.pdf','D'
		    $pdf->Output($nombre,'D');
		    //$pdf->Output($nombre,'F');
		    $pdf->Output(); //Salida al navegador del pdf'PAMY.pdf','D'
	}

	function ver_codigo()
	{
		//Se crea un objeto de PDF
		//Para hacer uso de los métodos
		$pdf = new eFPDF();
		$pdf->crear_boleta();
	
	}

	public function crear_factura($id)
	{
		//Se crea un objeto de PDF
		//Para hacer uso de los métodos
		$pdf = new eFPDF();
		$pdf->crear_factura($id);
	
	}


	public function crear_factura_por_sector()
	{
		//Se crea un objeto de PDF
		//Para hacer uso de los métodos
		$this->load->model('Impresiones_model');
		$sectores = $this->input->post("miselect");
		$datos["resultado"] = $this->Impresiones_model->buscar_lote_por_sectores($sectores);
$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
//var_dump($datos["resultado"]);die();
		
        //var_dump($nuevafecha,$fecha );die();
		$pdf = new eFPDF();
		$pdf->crear_factura_por_lote($datos);
	}

	public function crear_factura_por_conexion()
	{
		//Se crea un obdeto de PDF
		//Para hacer uso de los métodos
		$conexiones = $this->input->post("miselect");
		var_dump($conexiones);die();
	
	}



	public function barcode()
	{
		$this->load->library('zend');
   		$this->zend->load('Zend/Barcode');

   		$temp = rand(10000, 99999);
 
    	$barcodeOptions = array('text' => 'ZEND-FRAMEWORK');
        $rendererOptions = array('imageType'          =>'png', 
                                 'horizontalPosition' => 'center', 
                                 'verticalPosition'   => 'middle');
        Zend_Barcode::render('code39', 'image',  array('text'=>$temp), array() );

        /*
		//I'm just using rand() function for data example
		$temp = rand(10000, 99999);
		$this->set_barcode($temp);*/
	}
	
	private function set_barcode($code)
	{
		//load library
		$this->load->library('zend');
		//load in folder Zend
		$this->zend->load('Zend/Barcode');
		//generate barcode
		Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
	}


	//Genera un Json para la seccion de Autocomplete en Crear Factura
	public function leer_clientes(){

		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:

			header('Content-Type: application/json');
			$valor=$_GET['query'];  //captura la variable que pasa el autocomplete
			//var_dump($valor);
			$data=$this->Clientes_model->buscar_clientes($valor);
			$clientes = array(); //creamos un array

			foreach($data as $columna) { 
				$id	=	$columna->Cli_Id;
			   $razon=$columna->Cli_RazonSocial;
			   $documento=$columna->Cli_NroDocumento;
			   $direccion=$columna->Cli_DomicilioPostal;
			    
			   $clientes[] = array(
			   	'value'=> $razon, 
			   	'data' => $id, 
			   	'nro_documento' => $documento, 
			   	'direccion' => $direccion
			   );
			 
			}

			$array = array("query" => "Unit", "suggestions" => $clientes);
			echo json_encode($array);
		endif;

	}

	public function guardar_cambios_cliente()
	{
		if($this->input->post())
		{
			$tipodoc_ingresado = $this->input->post("tipo_documento", true);
			$documento_ingresado = $this->input->post("nro_documento", true);
			$razon_social = $this->input->post("razon_social", true);
			$representante = $this->input->post("representante", true);
			$email = $this->input->post("email", true);
			$telefono = $this->input->post("telefono", true);
			$celular = $this->input->post("celular", true);
			$direccion = $this->input->post("direccion", true);
			$localidad = $this->input->post("localidad", true);
			//$inputDomSum = $this->input->post("inputDomSum", true);
			
			$inputCuit = $this->input->post("inputCuit", true);
			$inputNroCliente = $this->input->post("inputNroCliente", true);
			$inputDomPost = $this->input->post("inputDomPost", true);
			$inputDeudor = $this->input->post("inputDeudor", true);
			$inputIVA = $this->input->post("inputIVA", true);
			$inputHabilitacion = $this->input->post("inputHabilitacion", true);
			$inputObservacion = $this->input->post("inputObservacion", true);
			$id = $this->input->post("id_oculto", true);
			$tipo_persona = $this->input->post("inputTipoPersona", true);


			//validation 



			$datos_modificados = array(
				'Cli_TipoPersona'=> $tipo_persona,
				'Cli_TipoDoc' => $tipodoc_ingresado,
				'Cli_NroDocumento' => $documento_ingresado,
				'Cli_NroCliente' =>$inputNroCliente,
				'Cli_RazonSocial' =>  $razon_social,
				//'Cli_DomicilioSuministro' => 	$inputDomSum,
				'Cli_DomicilioPostal' => 		$inputDomPost,
				'Cli_Email' => 			$email,
				'Cli_Telefono' => 		$telefono,
				'Cli_Celular' => 	$celular,
				'Cli_Representante' => 	$representante,
				'Cli_Localidad' =>		$localidad,
				'Cli_Tienda' =>		$id,
				'Cli_Cuit' =>	$inputCuit,			
				'Cli_Deudor' =>	$inputDeudor,
				'Cli_TipoIVAId' => 		$inputIVA,
				'Cli_Observacion' =>	$inputObservacion,
				'Cli_Habilitacion' => 		$inputHabilitacion
				);
			//var_dump($id); die();
			$resultado = $this->Crud_model->modificar_datos_clientes($datos_modificados,$id, "clientes", "Cli_Id");
			//var_dump($resultado);die();

			if($resultado)
				$this->session->set_flashdata('aviso','El usuario fue modificado correctamente');
			else $this->session->set_flashdata('aviso','El usuario NO fue modificado');
	
			redirect(base_url("clientes"));
		//	$this->load->view("templates/notificacion_view",$resultado);
		}
		else 
		{
			$this->session->set_flashdata('mensaje','Debes seleccionar el cliente a editar');
			redirect(base_url());
		}

		
	}

public function llenar_modal_bonificacion_nuevo()
	{
		$data['is_modal'] = true;
	//	$data['conexion_id'] = $this->input->post('conexion_id',true);
		//$data['deuda'] = $this->input->post('deuda',true);
		//$data['monto_pago'] = 0;
		//$data['cuota_actual'] = 1;

		$vista = $this->load->view('pago/modal_agregar_bonificacio_view', $data, true);
		echo $vista;
	}


	public function guardar_plan_pago()
	{
		if($this->input->post())
		{
			$monto = $this->input->post("monto", true);
			$total_deuda = $this->input->post("total_deuda", true);
			$cant_coutas = $this->input->post("cant_coutas", true);
			$fecha_inicio = $this->input->post("fecha_inicio", true);
			$id_conexion = $this->input->post("id_conexion", true);

			$monto_cuota =  (float)$monto /  (float)$cant_coutas;

			$datos_modificados = array(
				'PlanPago_Id'=> null,
				'PlanPago_Conexion_Id' => $id_conexion,
				'PlanPago_MontoTotal' => $monto,
				'PlanPago_MontoPagado' => 0,
				'PlanPago_MontoCuota' => $monto_cuota,
				'PlanPago_Coutas' =>	$cant_coutas,			
				'PlanPago_Interes' =>	0,
				'PlanPago_CoutaActual' => 1,
				'PlanPago_Tipo' => 0,
				'PlanPago_FechaInicio' => $fecha_inicio,
				'PlanPago_Observacion' =>	null,
				'PlanPago_Habilitacion' => 1,
				'PlanPago_Borrado' =>	0,
				'PlanPago_Timestamp' =>	null
				);
			$resultado = $this->Crud_model->insert_data("planpago",$datos_modificados);
			echo $resultado;
		}
		else 
			echo false;
	}


	public function borrar_pago_y_movimiento()
	{
		$id=  $this->input->post("id");
		$resultado =  $this->Crud_model->borrar("Pago", "Pago_Id", $id);
		$resultado =  $this->Crud_model->borrar("movimiento", "Mov_Pago_Id", $id);
		echo true;
	}


	public function datos_envios()
	{
		echo "true";
	}


	public function guardar_pago_nuevo(){
		$this->load->helper('form');
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//$btn_enviar =$this->input->post('enviar');
			if ($this->input->post()) 
			{
				/*
				Cuando se crea un nuevo pago se debe:
				1 calculo de variables y valores
				2 creo el pago y lo guardo en la base de datos
				3 deshabilito la boleta para que pueda ser pagada nuevaemente
				4 si es posible,
					4.1 creo el array deuda a guardar
					4.2 deshabilito todas las deudas anteriores para esa conexion
					4.3 guardo una nueva deuda en la tabla deuda
				5 actualizo la deuda de la conexion , en la tabla de conexiones
				6 Crear el array del Bonificaciones  y guardarlo en la base ed datoas 
					Si es una solicitud de bonificacion, de todos los modos la creo y la marco como tal
				7
					7.1 Creo el array de plan de pago
					7.2  if() plan nuevo  -  o estoy pagando una cuota  -   o no tengo plan de pago
						7.2.1 pla nuevo,: Creo el array y lo inserto en la tabla
						7.2.2 No tengo pp , seteo una variable nada mas
						7.2.3 Estoy pagando cuota:
							7.2.3.1 VOy a buscar en la base de datos el registro del pp
							7.2.3.2 Veo si pago la ultima cuota o no
								7.2.3.2.1 Pagando la ultima cuota del pp
								7.2.3.2.2 Creo el array y deshabilito ese plan, xq se ha terminado de pagar
							7.2.3.3 Veo si es una cuota mas del plan
								7.2.3.3.1 Creo el array del pp y actualizo la nueva cuota pagada.
				8  PLAN MEDIDOR  Busco el plan de medidor   estoy pagando una cuota  -   o no tengo plan de pago
					8.1 No tengo pp , seteo una variable nada mas
					8.2 Estoy pagando cuota:
						8.2.1 VOy a buscar en la base de datos el registro del pp
						8.2.2 Veo si pago la ultima cuota o no
							8.2.2.1 Pagando la ultima cuota del pp
							8.2.2.2 Creo el array y deshabilito ese plan, xq se ha terminado de pagar
						8.2.3 Veo si es una cuota mas del plan
							8.2.3.1 Creo el array del pp y actualizo la nueva cuota pagada.	
				9 Moviemientos
					9.1 Crear el array del movmientos y guardarlo en la bd
				10 dar avisos y redireccionar
				*/
				//ASIGNAMOS UNA VARIABLE A CADA CAMPO RECIBIDO
				$id_factura=$this->security->xss_clean(strip_tags($this->input->post('inputFacturaOculta')));
				$codigo_factura=$this->security->xss_clean(strip_tags($this->input->post('inputFacturaAjax')));
				$MontoModificado=$this->security->xss_clean(strip_tags($this->input->post('inputMontoModif')));
				$inputtotal=$this->security->xss_clean(strip_tags($this->input->post('inputtotal')));
				$total_sin_cambios=$this->security->xss_clean(strip_tags($this->input->post('total_sin_cambios')));
				$tipo_pago=$this->security->xss_clean(strip_tags($this->input->post('inputTipoPago')));
				$procentaje_bonificacion_agregar=$this->security->xss_clean(strip_tags($this->input->post('procentaje_bonificacion_agregar')));
				$monto_a_bonificacion_agregar=$this->security->xss_clean(strip_tags($this->input->post('monto_a_bonificacion_agregar')));
				$solicitud_bonificacion=$this->security->xss_clean(strip_tags($this->input->post('rep_oculto')));
					//$fecha_nueva=$this->security->xss_clean(strip_tags($this->input->post('pago_fecha_')));
			$inputFechaaux=$this->security->xss_clean(strip_tags($this->input->post('pago_fecha_',true)));
			$aux =  str_replace('/', '-', $inputFechaaux);
			$inputFecha = date("Y-m-d H:i:s", strtotime($aux));
			//var_dump($inputFecha);die();
			



				if($solicitud_bonificacion === "true")
					$solicitud_bonificacion=1;
				else $solicitud_bonificacion=0;
				//
				if($tipo_pago == 2) // 2 ->parcial
				{
					//es monto modifiaco y con bonificacion
					if (
					 ($procentaje_bonificacion_agregar != "")  || ($procentaje_bonificacion_agregar != null)|| ($solicitud_bonificacion != 0)
					||
					($monto_a_bonificacion_agregar != "")  || ($monto_a_bonificacion_agregar != null)|| ($monto_a_bonificacion_agregar != 0)
					)
						$inputtotal = $inputtotal;
					else //total queda con el monto modificado
						$inputtotal = $MontoModificado;
				}
				$solicitud_planpago = $this->security->xss_clean(strip_tags($this->input->post('hab__oculto')));
				if($solicitud_planpago === "true")
					$solicitud_planpago=1;
				else $solicitud_planpago=0;
				//$bonificacion=$this->security->xss_clean(strip_tags($this->input->post('bonificacion')));
				
				$cantidad_cuotas_planpago = $this->security->xss_clean(strip_tags($this->input->post('cant_cuota_pp')));
			//	var_dump($cantidad_cuotas_planpago);die();
				$interes_plapago = $this->security->xss_clean(strip_tags($this->input->post('interes_plapago')));
				$fecha_inicio_planpago = $this->security->xss_clean(strip_tags($this->input->post('fecha_inicio_planpago')));
				$monot_por_cuota_plan_pago = $this->security->xss_clean(strip_tags($this->input->post('monot_por_cuota_plan_pago')));
				$observaciones_planpago = $this->security->xss_clean(strip_tags($this->input->post('observaciones_planpago')));
				//calculo el monto cobrado
				if(
					($procentaje_bonificacion_agregar != "")  && ($procentaje_bonificacion_agregar != null) && ($solicitud_bonificacion != 0)
					||
					($monto_a_bonificacion_agregar != "")  && ($monto_a_bonificacion_agregar != null) && ($monto_a_bonificacion_agregar != 0)
					) // se lleno alguno de los datos para crear la bonificacion
				{
					if ( ($procentaje_bonificacion_agregar != 0) || ($procentaje_bonificacion_agregar != '') || ($procentaje_bonificacion_agregar != null) || ($procentaje_bonificacion_agregar != -1))
						$bofinificacion_calculada = ($inputtotal * $procentaje_bonificacion_agregar)/100;
					else $bofinificacion_calculada = $inputtotal - $monto_a_bonificacion_agregar;

				}
				else
				$bofinificacion_calculada =0;
				$endeuda = $this->security->xss_clean(strip_tags($this->input->post('endeuda'))); // anda bien 1/11/17
				
				$bonificacion_hecha = $this->security->xss_clean(strip_tags($this->input->post('bonificacion_hecha')));
				//
				$id_cliente =  $this->security->xss_clean(strip_tags($this->input->post('cliente'))); 
				$id_plan_pago =  $this->security->xss_clean(strip_tags($this->input->post('id_agregarPlanPago_ya_creado')));
				$id_planpago_medidor =  $this->security->xss_clean(strip_tags($this->input->post('planmedidor_id'))); 
				$datos_factura = array(
					'Pago_Id' => null,
					'Pago_Cli_Id' => $id_cliente,
					'Pago_Facturacion_Id' => $id_factura,
					'Pago_Boleta' => $codigo_factura,
					'Pago_Lugar' => 1, //ver esto
					'Pago_Monto' =>  $this->arreglar_numero($inputtotal),
					'Pago_Ultima' => date('Y-m-d'),
					'Pago_SolicitudBonificacion' => $solicitud_bonificacion,
					'Pago_SolicitudPlanPago' => $solicitud_planpago,
					'Pago_Total' => $this->arreglar_numero($total_sin_cambios),
					'Pago_Bonificacion' => $this->arreglar_numero($bofinificacion_calculada),
					'Pago_Observacion' => 	null,
					'Pago_Habilitacion' => 	1,
					'Pago_Borrado' =>		0,
					'Pago_Timestamp' =>	$inputFecha
				);
				//var_dump($datos_factura);die();
				 $id_pago_recien_insertado = $this->Crud_model->insert_data("pago",$datos_factura);
				 //marco la boleta como pagada y no se debe volver a pagar esta factura
				 $datos_factura = array( 
				 	'Factura_Habilitacion' => 0,
				 	'Factura_Borrado' => 0 );
				 $resultado_actualizar_deuda = $this->Crud_model->update_data($datos_factura, $id_factura, "facturacion", "id");
				
				 $this->load->model('Facturar_model');
				 $id_conexion =  $this->Facturar_model->buscar_coenxion_id_en_factura($id_factura); 
				 if( ($endeuda>0) &&  ($cantidad_cuotas_planpago == null && $monot_por_cuota_plan_pago == null ))
				 {
				 	if(is_numeric( $id_conexion->Factura_Conexion_Id )) // si cumplo esto significa que obtube el id correcto
				 	{
				 		//echo "entrre al if";
				 		//4.1 creo el array
				 		$datos_deuda = array(
				 			'Deuda_Id' => null,
				 			'Deuda_Conexion_Id' => $id_conexion->Factura_Conexion_Id ,
				 			'Deuda_Concepto' => "no paga totalidad de factura",
				 			'Deuda_Monto' => $this->arreglar_numero($endeuda),
				 			'Deuda_Habilitacion' =>	1,
				 			'Deuda_Borrado' =>	0,
				 			'Deuda_Timestamp' => $inputFecha
				 		);

				 		//4.2  deshabilito todas las deudas anteriores
				 		$this->Crud_model->deshabilitar_todas_las_deudas_anteriores_conexion_id($id_conexion->Factura_Conexion_Id);
						
				 		//4.3 inserto la nueva deuda
				 		$id_deuda = $this->Crud_model->insert_data("deuda",$datos_deuda);

				 		/* creo q esto no va, xq siempre va a haber una sola deuda habilitada
				 		$total_deuda = $this->Crud_model->suma_data("deuda","Deuda_Monto","Deuda_Borrado","Deuda_Conexion_Id",$id_conexion->Factura_Conexion_Id );
				 		*/
				 		//coneXion- actualiza el total de la deuda
				 		$datos_conexion = array(
				 			'Conexion_Deuda' => $this->arreglar_numero($endeuda),
				 		);
				 		$actualizar_conexion = $this->Crud_model->update_data($datos_conexion,$id_conexion->Factura_Conexion_Id ,"conexion","Conexion_Id");
				 	}
				 }
				 if( $monto_a_bonificacion_agregar != null || $procentaje_bonificacion_agregar != null )  // se han cargado los datos de una bonificacion nueva, y se van a agregar a la bd
				 {
				 	//ver la posibilidad de descontarle solamente con el porcentaje y sacarle el monto_bonificado
				 	$datos_bonificacion = array(
				 		'Bonificacion_Id' => null,
				 		'Bonificacion_Factura_Id' => $id_factura,
				 		'Bonificacion_Monto' => $monto_a_bonificacion_agregar,
				 		'Bonificacion_Porcentaje' =>	$procentaje_bonificacion_agregar,
				 		'Bonificacion_Aprobada' => 1,
				 		'Bonificacion_Pendiente' => 0,
				 		'Bonificacion_Observacion' =>	$bonificacion_hecha,
				 		'Bonificacion_Habilitacion' =>	1,
				 		'Bonificacion_Borrado' =>	0,
				 		'Bonificacion_Timestamp' =>	$inputFecha
				 	);
				 	$id_bonificacion = $this->Crud_model->insert_data("bonificacion",$datos_bonificacion);
				 }
				 if($solicitud_bonificacion == 1) //se ha solicitado una bonificacion, la creo y depues se llenaran los datos de la misma
				 {
				 	$datos_bonificacion = array(
				 		'Bonificacion_Id' => null,
				 		'Bonificacion_Factura_Id' => $id_factura,
				 		'Bonificacion_Monto' => 0,
				 		'Bonificacion_Porcentaje' => 0,
				 		'Bonificacion_Aprobada' => 0,
				 		'Bonificacion_Pendiente' => 1,
				 		'Bonificacion_Observacion' => "a la espera de aprobacion",
				 		'Bonificacion_Habilitacion' =>	1,
				 		'Bonificacion_Borrado' =>	0,
				 		'Bonificacion_Timestamp' =>	$inputFecha
				 	);
				 	$id_bonificacion = $this->Crud_model->insert_data("bonificacion",$datos_bonificacion);
				 }
				 //plan pago empieza
				 //creo el nuevo plan de pago
				 if($cantidad_cuotas_planpago != null && $monot_por_cuota_plan_pago != null )
				 	$id_plan_pago = $this->agregar_plan_pago_por_parametros( $id_conexion->Factura_Conexion_Id,$endeuda,0,$monot_por_cuota_plan_pago,$cantidad_cuotas_planpago,$interes_plapago,1,$fecha_inicio_planpago,$observaciones_planpago);
				 else // modificar un plan pago ya creado anteriormente y voy a pagar una cuota mas de ese plan
				 {
				 	if( (is_numeric($id_plan_pago)) && ($id_plan_pago>0) ) // existe un plan pago y lo tengo q buscar
				 		$planPago =  $this->Crud_model->get_data_row_sin_borrado("PlanPago","PlanPago_Id","PlanPago_Borrado",$id_plan_pago);
				 	else $planPago = false; // no tengo ningun plan
				 	if($planPago!= false && $planPago!= 0 && $planPago!= null && $planPago!= '') // todos los datos validos
				 	{	
				 		if($planPago->PlanPago_CoutaActual == $planPago->PlanPago_Coutas) //significa que estoy pagando la ultima de las cuotas
				 			$datos_pago = array(
				 				'PlanPago_MontoPagado' => $planPago->PlanPago_MontoPagado + $planPago->PlanPago_MontoCuota,
				 				'PlanPago_CoutaActual' => $planPago->PlanPago_CoutaActual+1,
				 				'PlanPago_Habilitacion' => 0
				 				);
				 		else //significa q estoy pagando una cuota cualqueira
				 			$datos_pago = array(
				 			'PlanPago_MontoPagado' => $planPago->PlanPago_MontoPagado+ $planPago->PlanPago_MontoCuota,
				 			'PlanPago_CoutaActual' => $planPago->PlanPago_CoutaActual+1
				 			);
				 		$planPagoactulizado = $this->Crud_model->update_data($datos_pago, $id_planpago, 'PlanPago','PlanPago_Id');
				 	}
				 }	
				 // plan pago final*/
				 //Comienza Plan Medidor
				 if( (is_numeric($id_planpago_medidor)) && ($id_planpago_medidor>0) )  // veo si hay datos del plan medidor en la factura
				 	$planmedidor =  $this->Crud_model->get_data_row_sin_borrado("planmedidor","PlanMedidor_Id","PlanMedidor_Borrado",$id_planpago_medidor); // busco el row en la bd
				 else $planmedidor = false; // no tengo ningun plan medidor
				 if($planmedidor!= false && $planmedidor!= 0 && $planmedidor!= null && $planmedidor!= '') // pregunto plan medidor si existe
				 {
				 	if($planmedidor->PlanMedidor_CoutaActual== $planmedidor->PlanMedidor_Coutas) //ultima cuora del plan medidor
				 		$datos_pago_m = array(
				 			'PlanPago_MontoPagado' => $planmedidor->PlanMedidor_MontoPagado+ $planmedidor->PlanMedidor_MontoCuota,
				 			'PlanPago_CoutaActual' => $planmedidor->PlanMedidor_CoutaActual+1,
				 			'PlanPago_Habilitacion' => 0
				 			);
				 	else // una cuota mas del plan medidor
				 		$datos_pago_m = array(
				 		'PlanPago_MontoPagado' => $planmedidor->PlanMedidor_MontoPagado+ $planmedidor->PlanMedidor_MontoCuota,
				 		'PlanPago_CoutaActual' => $planmedidor->PlanMedidor_CoutaActual+1
				 		);
				 	$planPagoactulizado = $this->Crud_model->update_data($datos_pago_m, $id_planpago_medidor, 'planmedidor','PlanMedidor_Id');
				 }
 			// 	ingreso
 			 	$datos_movimiento = array(
				 	'Mov_Id' => null,
				 	'Mov_Tipo' => 1, //ingreso
				 	'Mov_Monto' => $this->arreglar_numero($inputtotal),
				 	'Mov_Codigo' =>	"3", //poner codigo cuando lo tengamos
				 	'Mov_Pago_Id' =>	$id_pago_recien_insertado, 
				 	'Mov_Revisado' =>	0,
				 	'Mov_Quien' =>	$this->session->userdata('id_user'),
				 	'Mov_Observacion' => "Pago Boleta Con:".$id_conexion->Factura_Conexion_Id,
				 	'Mov_Habilitacion' =>	1,
				 	'Mov_Borrado' =>	0,
				 	'Mov_Timestamp' =>	$inputFecha
				 );
				 $id_movimiento = $this->Crud_model->insert_data("movimiento",$datos_movimiento);
				 $resultado = $id_pago_recien_insertado;
				 if(is_numeric($resultado))
				 {
				 	$this->session->set_flashdata('aviso','Se guardo crrectamente el cambio');
				 	$this->session->set_flashdata('tipo_aviso','success');
				 }
				 else 
				 {
				 	$this->session->set_flashdata('aviso','NO se guardo crrectamente el cambio');
				 	$this->session->set_flashdata('tipo_aviso','danger');
				 }
				 echo intval($resultado);
			}
		endif;
	}

	//Pagina para Ingresar Clientes
	public function agregar(){
		$this->load->helper('form');
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$datos['color'] = color_pago;
			$datos['titulo'] = 'Agregar Pago';
			$this->load->view('templates/header',$datos);
			$this->load->view('pago/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}

	public function agregar_por_codigo_barra($codigo){
		$this->load->helper('form');
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$this->load->model('Facturar_model');
			$datos['factura'] = $this->Facturar_model->get_factura_con_codigo($codigo);
			//var_dump($datos['factura']);die();
			if( (sizeof($datos['factura'])  >= 1) &&   ($datos['factura'][0]->Factura_Habilitacion  == 1) )
			{
				$datos['color'] = color_pago;
				$datos['codigo'] = $codigo;
				$datos['titulo'] = 'Agregar Pago';
				$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
				$this->load->view('templates/header',$datos);
				$this->load->view('pago/agregar',$datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
			}
			else
			{
				$segmentos_totales=$this->uri->total_segments();
				$datos['segmentos']=$segmentos_totales;
				$this->load->view('templates/header',$datos);
				$data["mensaje"] = "Esta boleta ya ha sido pagada anteriormente";
				$this->load->view("notificaciones/sin_permiso_view", $data);
				$this->load->view('templates/footer');
			}
		endif;
	}

	public function modificar_pago(){
		$this->load->helper('form');
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$datos['color'] = color_pago;
			$datos['titulo'] = 'Agregar Pago';
			$this->load->view('templates/header',$datos);
			$this->load->view('pago/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}


	public function agregar_plan_pago_por_parametros($inputConexionId,$inputMontoTotal,$inputMontoPagado,$inputMonotCuota,$inputCuotas,$inputInteres,$inputCuotaActual,$inputFechaInicio,$inputObservacion)
	{
		$datos_plan_pago = array(
			'PlanPago_Id' => null, 
			'PlanPago_Conexion_Id' => $inputConexionId, 
			'PlanPago_MontoTotal' => $this->arreglar_numero($inputMontoTotal), 
			'PlanPago_MontoPagado' => $this->arreglar_numero($inputMontoPagado), 
			'PlanPago_MontoCuota' => $this->arreglar_numero($inputMonotCuota),
			'PlanPago_Coutas' => $inputCuotas,
			'PlanPago_Interes' => $this->arreglar_numero($inputInteres),
			'PlanPago_CoutaActual' => $inputCuotaActual,
			'PlanPago_Tipo' => 0,
			'PlanPago_FechaInicio' => $inputFechaInicio,
			'PlanPago_Observacion' => $inputObservacion,
			'PlanPago_Habilitacion' => 1,
			'PlanPago_Borrado' => 0,
			'PlanPago_Timestamp' => null
			);
		$resultado = $this->Crud_model->insert_data("planpago",$datos_plan_pago);
		return $resultado; 
	}
}
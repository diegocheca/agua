<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imprimir extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		//$this->load->helper("numeros");
		$this->load->helper('PDF_helper');
//		include('Barcode.php'); 
		$this->load->helper('eFPDF_helper');

	}

	public function index(){
	}
	public function factura(){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$nro_documento=$this->uri->segment(3);
			$id_factura=$this->Crud_model->get_data_row('facturacion','id',$nro_documento);
			$val['valores']=$id_factura;
			$val['items']=$this->Crud_model->get_data_result('items','id_factura',$id_factura->id_factura);
			$this->load->view("imprimir/template_factura",$val);

		endif;
	}

	
	public function movimientos_diarios($fecha = 0)
	{
		$this->load->model('Facturar_model');
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		if($fecha == 0)
			$movimientos = $this->Facturar_model->buscar_ingresos_y_egresos(date("Y-m-d"));
		
		$total_ingreso = $this->Facturar_model->buscar_ingresos(date("Y-m-d"));
		$total_ingreso = $total_ingreso[0]["Mov_Monto"];
		$total_egreso = $this->Facturar_model->buscar_egresos(date("Y-m-d"));
		$total_egreso = $total_egreso[0]["Mov_Monto"];
		//var_dump ($total_ingreso[0] );die();
		if($movimientos == null)
		{
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			$this->load->view('templates/header',$datos);
			$data["mensaje"] = "Sin movimientos registrado para el día de hoy";
			$this->load->view("templates/notificacion_incorrecta_success", $data);
			$this->load->view('templates/footer');

		} 
		else
		{
			$pdf = new eFPDF();
			$pdf->crear_balance_diario($movimientos, $total_ingreso,$total_egreso );
		}
		
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


	public function crear_contrato_conexion($nombre,$dni,$id_conexion,$id_medidor,$domicilio)
	{
		$nombre = str_replace( "%20", " ", $nombre);
		$domicilio = str_replace( "%20", " ", $domicilio);

	//	var_dump($nombre,$dni,$id_conexion,$id_medidor,$domicilio);die();
		$pdf = new eFPDF();
		$pdf->crear_contratro_nueva_conexion($nombre,$dni,$id_conexion,$id_medidor,$domicilio);
	}


	public function crear_orden_de_trabajo_automatica($id_cliente, $domicilio_sum,$id_conexion,$id_orden_recien_insertado,$razon_social)
	{
		$domicilio_sum = str_replace( "%20", " ", $domicilio_sum);
		$razon_social = str_replace( "%20", " ", $razon_social);
		$pdf = new eFPDF();
		$pdf->crear_ot_nueva_conexion($id_cliente, $domicilio_sum,$id_conexion,$id_orden_recien_insertado,$razon_social);
	}



	public function crear_factura_por_conexion()
	{
		//Se crea un obdeto de PDF
		//Para hacer uso de los métodos
//		var_dump($conexiones);die();

		$this->load->model('Impresiones_model');
		$conexiones = $this->input->post("miselect");
		$datos["resultado"] = $this->Impresiones_model->buscar_lote_por_conexion($conexiones);
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		//var_dump($datos["resultado"]);die();
		
        //var_dump($nuevafecha,$fecha );die();
		$pdf = new eFPDF();
		$pdf->crear_factura_por_lote_conexiones($datos);
	
	}


public function crear_factura_por_conexion_id($id_factura)
	{
		//Se crea un obdeto de PDF
		//Para hacer uso de los métodos
//		var_dump($conexiones);die();
		$this->load->model('Impresiones_model');
		$conexion = $this->Impresiones_model->buscar_conexion($id_factura);
		$datos["resultado"] = $this->Impresiones_model->buscar_por_conexion($conexion->Factura_Conexion_Id);
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		//var_dump($datos["resultado"]);die();
		
       // var_dump($datos["resultado"] );die();
		$pdf = new eFPDF();
		$pdf->crear_factura_por_conexion($datos);
	
	}
	public function crear_recibo_de_pago($id_pago)
	{
		$this->load->model('Impresiones_model');
		$datos["resultado"] = $this->Impresiones_model->buscar_pago_para_recibo($id_pago);
		//var_dump($datos["resultado"]);die();
		$datos["nombre"] =$this->session->userdata('nombre');
        //var_dump($nuevafecha,$fecha );die();
		$pdf = new eFPDF();
		$pdf->crear_recibo_ingreso($datos);
	
	}

	public function crear_recibo_de_pago_medidor_nuevo($id_recibo, $razon_social, $precio)
	{
		$razon_social = str_replace( "%20", " ", $razon_social);
	//	var_dump($id_recibo,$razon_social,$precio); die();
		$this->load->model('Impresiones_model');
		//$datos["resultado"] = $this->Impresiones_model->buscar_pago_para_recibo($id_pago);
		//var_dump($datos["resultado"]);die();
		$datos["nombre"] =$this->session->userdata('nombre');
		$datos["id_recibo"] =$id_recibo;
		$datos["razon_social"] =$razon_social;
		$datos["precio"] =$precio;
		
        //var_dump($nuevafecha,$fecha );die();
		$pdf = new eFPDF();
		$pdf->crear_recibo_ingreso_por_medidor_nuevo($datos);
	
	}







}
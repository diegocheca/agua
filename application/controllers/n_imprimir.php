<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class N_imprimir extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('Nuevo_model');
		$this->load->helper('PDF_helper');
		$this->load->helper('eFPDF_helper');
		$this->load->library('zend');
	}
	public function crear_factura_por_id($id = -1, $sector = -1, $mes  = -1, $año = -1)
	{
		$datos["resultado"] = $this->Nuevo_model->buscar_lote_por_sectores_nuevo($id,  $sector, $mes , $año);
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		if($datos["resultado"] == false)
			echo "Sin resultados por el momento" ;
		else
		{
			$pdf = new eFPDF();
			$pdf->probando_tabla_por_lote_nueva($datos);
		}
	}
	public function crear_factura_por_sector($sectores =  -1 , $mes = -1, $anio = -1)
	{
		if($sectores == -1)
			$sectores = $this->input->post("miselect");
		if($mes == -1)
			$mes = $this->input->post("mes_a_imprimir");
		if($anio == -1)
			$anio = $this->input->post("anio_a_imprimir");
		$datos["resultado"] = $this->Nuevo_model->buscar_lote_por_sectores_anterior($sectores,$mes, $anio);
		//	var_dump($datos["resultado"]);die();
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		if($datos["resultado"] == false)
			echo "Sin resultados por el momento" ;
		else
		{
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
		$datos["nombre"] =$this->session->userdata('nombre');
		$pdf = new eFPDF();
		$pdf->crear_recibo_ingreso_nuevo($datos,1);
	}
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
}
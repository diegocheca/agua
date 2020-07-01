<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportes extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Reporte_model');
		//$this->load->helper('eFPDF_helper');
		$this->load->helper('rFPDF_helper');
		$this->load->helper('PDF_helper');

	}

	public function index(){
		$this->load->view("reportes/excel.php");
	}
	public function informe_de_clientes(){
		$data["filas"]  = $this->Reporte_model->get_data_result_array("clientes", "Cli_Borrado", "0");
		$data["header"] = array('Num', 'Documento', 'Apellido y Nombre', 'Domicilio','Celular');
		$data["ancho_columna"] = array(45, 100, 260, 285,100);
		$data["titulo"] = "Listado de Usuarios";
		$pdf = new rFPDF();
		$pdf->creando_informe_tabla($data);
	}

	public function informe_de_conexiones(){
		$data["filas"]  = $this->Reporte_model->buscar_conexiones_para_informe();
		$data["header"] = array('N Con', 'N Cli', 'Apellido y Nombre', 'Domicilio Conexion', 'Deuda', 'Conexion');
		$data["ancho_columna"] = array(45, 45, 260, 260,100, 80);
		$data["titulo"] = "Listado de Conexiones";
		$pdf = new rFPDF();
		$pdf->creando_informe_conexion($data);
	}

	public function informe_de_pagos(){
		$data["filas"]  = $this->Reporte_model->buscar_conexiones_para_informe();
		$data["header"] = array('N Con', 'N Cli', 'Apellido y Nombre', 'Domicilio Conexion', 'Deuda', 'Conexion');
		$data["ancho_columna"] = array(45, 45, 260, 260,100, 80);
		$data["titulo"] = "Listado de Conexiones";
		$pdf = new rFPDF();
		$pdf->creando_informe_conexion($data);
	}

	public function historial_de_pago_cliente($id){

		$data["filas"]  = $this->Reporte_model->buscar_historial_de_pago_cliente($id);
		if($data["filas"] != false)
		{
			//var_dump($data["filas"]);die();
			$data["header"] = array('N Cli', 'N Con', 'Apellido y Nombre', 'Factura', 'Emision', 'Monto', 'Pago', 'Deuda', 'Anterior', 'Actual', 'Tipo');
			$data["ancho_columna"] = array(40,40,          220,                   50,    80,         50,      50,      50,       70,         50     ,60);
			$data["titulo"] = "Listado de Todos";
			$pdf = new rFPDF();
			$pdf->creando_informe_historial($data);
		}
		else echo "no tiene nada q mostrar";
		
	}

	public function historial_de_pago_por_mes($fecha){
		$data["filas"]  = $this->Reporte_model->buscar_pagos_por_mes($fecha);
		if($data["filas"] != false)
		{
			//var_dump($data["filas"]);die();
			$data["header"] = array('N Pago', 'N Cli', 'Apellido y Nombre', 'Factura', 'Emision', 'Monto', 'Cuando');
			$data["ancho_columna"] = array(40,40,          220,                   80,    100,         90,      190);
			$data["titulo"] = "Listado de Todos";
			$pdf = new rFPDF();
			$pdf->creando_informe_pagos_por_mes($data);
		}
		else echo "no tiene nada q mostrar";
	}

	public function reporte_pagos_fechas($inicio,$fin){
		// $inicio = $this->security->xss_clean(strip_tags($this->input->post('inicio')));
		// $fin = $this->security->xss_clean(strip_tags($this->input->post('fin')));
		 $data["filas"]  = $this->Reporte_model->buscar_pagos_entre_fechas($inicio, $fin);
		if($data["filas"] != false)
		{
			//echo "si hay algo q mostrar";
		// 	//var_dump($data["filas"]);die();
		 	$data["header"] = array('Pago', 'N Cli', 'Apellido y Nombre', 'Factura', 'Periodo','Excedente', 'Monto', 'Cuando');
			$data["ancho_columna"] = array(40,40,          220,                   70,    80,         90,  90,    140);
			$data["titulo"] = "Listado de Todos";
			$pdf = new rFPDF();
			$pdf->creando_informe_pagos_por_mes($data);
		}
		else echo "no tiene nada q mostrar";
	}

	
	public function html ()
	{
		$pdf = new rFPDF();
		$pdf->tablas_html();
	}
}
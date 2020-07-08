<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preview extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->helper("numeros");
	}

	//Pagina Muestra Facturas Realizadas
	public function index(){
			
		$this->session->set_flashdata("document_status",mensaje("Debes Visualizar un documento","warning"));
		redirect(base_url("facturar"));
	}
	public function documento($id_doc){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtenemos el numero de id del documento que se quiere editar
			$datosfactura=$this->Crud_model->get_data_row('facturacion','id',$id_doc);//obtiene los datos del documento
			$datoscliente=$this->Crud_model->get_data_row('clientes','Cli_Id',$datosfactura->id_cliente);//obtiene los datos del cliente
			$val['items']=$this->Crud_model->get_data_result_array('items','id_factura',$datosfactura->id_factura);//obtiene los items del documento
		//	var_dump($val['items']);
			$val['valores']=$datosfactura;
			$val['cliente']=$datoscliente;

			$this->load->view('preview/documentos',$val);
		endif;
	}
}
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Proformas extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
	}

	public function index(){
		
	}

	public function nuevo(){
		$datos['titulo']	= 'Realizar Cotización';
		$this->load->view('templates/header', $datos);
		$this->load->view('cotizar/nueva-cotizacion');
		$this->load->view('templates/footer');
	}

	public function guardar_proforma(){
		$input	=	$this->input->post(NULL,TRUE);
		$max 	= 	$this->Crud_model->get_max_id('cotizacion', 'idproforma');// devuleve el valor mayor de un campo en la BD
		$contenido	=	$this->input->post('contenido');
		var_dump($input);
		echo $contenido;

		$data = array(
			'id' 			=> NULL,
			'idproforma'	=> $max['idproforma']+1,
			'idcliente'		=> $input['idcliente'],//obtiene el valor de un input cliente
			'fecha'			=> $input['fecha'],
			'vendedor'		=> $input['vendedor'],
			'contenido'		=> $contenido,
			'total'			=> $input['preciofinal']
		);
		$this->Crud_model->insert_data('cotizacion',$data);
	}

}

/* End of file cotizar */
/* Location: ./application/controllers/cotizar */
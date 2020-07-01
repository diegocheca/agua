<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function login(){
		$this->load->model('Usuarios_model');

		$usuario=$this->security->xss_clean(strip_tags($this->input->post('user')));
		$password=md5($this->security->xss_clean(strip_tags($this->input->post('password'))));

		$this->Usuarios_model->login($usuario,$password);
	}

	//comprobador de correlativo
	public function comprobar_correlativo(){
		$this->load->model('Facturar_model');
		
		//recibimos los valores de serie y correltivo
		$serie=$this->security->xss_clean(strip_tags($this->input->post('serie')));
		$correlativo=$this->security->xss_clean(strip_tags($this->input->post('correlativo')));

		$valor=$serie.$correlativo;
		//comprobamos que no se haya registrado otra factura con el mismo numero de correlativo
		$this->Facturar_model->consultar_factura($valor);
	}

}
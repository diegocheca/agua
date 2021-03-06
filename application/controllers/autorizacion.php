<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Autorizacion extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Nuevo_model');
	}
	public function index($revisado = null,$mes = null,$anio = null,$id_conexion = null, $sector = null, $inicio = null , $fin = null){
		//Usado: 2-7-20 - Diego
		//Corregido : 2-7-20 - Diego
		//Que es lo que hace: busca todas las autorizaciones pendientes y las muestra por pantalla
		//Como se usa: se accede mediante url y se debe difinir todos los paramettros de la url
		//Mejora: nada de momento (la cosnutla es eficiente)
		/*Pasos que hace
		Paso 1 - compruebo si esta logueado
		Paso 2 - reviso los parametros
		Paso 3 - busco las autorizaciones relacionadas con las facturas
		Paso 4 - REVISAR: FALTA TERMINAR
		*/
		//Paso 1 - compruebo si esta logueado
		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//Paso 2 - reviso los parametros
			if($revisado == null)
				$revisado = $this->input->post('revisado_autorizacion');
			if($mes == null)
				$mes = $this->input->post('mes_autorizacion');
			if($mes == false)
				//$mes = date("m");
				$mes = 4;
			if($anio == null)
				$anio = $this->input->post('anio_autorizacion');
			if($anio == false)
				$anio = date("Y");
			if($sector == null)
				$sector = $this->input->post('sector');
			if($id_conexion == null)
				$id_conexion = $this->input->post('id_conexion_autorizacion');
			if($sector == null)
				$sector = $this->input->post('sector_autorizacion');
			if($inicio == null)
				$inicio = $this->input->post('inicio_autorizacion');
			if($fin == null)
				$fin = $this->input->post('fin_autorizacion');
			//Paso 3 - busco las autorizaciones relacionadas con las facturas
			$datos['autorizacion'] = $this->Nuevo_model->get_data_autorizacion($revisado,$mes,$anio,$id_conexion,$sector,$inicio,$fin);
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
			$this->load->view('autorizacion/lista_autorizacion', $datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$data ['aviso'] = $this->session->flashdata('aviso');
			if($data ['aviso'] != null)
				$this->load->view("templates/notificacion_view", $data);
		endif;
	}


}
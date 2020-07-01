<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Notificacion extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Crud_model");
	}
	public function index(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		// agregar breadcrumbs
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Orden de trabajo', '/Lista de ordenes de trabajo');

		// salida
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs

		$datos['titulo']= "Orden de trabajo";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados("ordenTrabajo", "OrdenTrabajo_Borrado");
		//var_dump($datos['consulta']);die();
		$datos['mensaje'] = $this->session->flashdata('aviso');
		
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

		$this->load->view('orden_trabajo/orden_trabajo',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');

		endif;
		
	}

public function sin_permiso()
	{
		$data["mensaje"] = "Usted no posee permisos para acceder a esta seccion";
		$this->load->view("notificaciones/sin_permiso_view", $data);
	}

public function sin_loguear()
	{
		$data["mensaje"] = "Usted debe iniciar sesion en el sistema para acceder aqui";
		$this->load->view("notificaciones/sin_permiso_view", $data);
		
	}


}
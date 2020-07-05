<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
No tengo ni idea para que es este controller
cuando se accede te mnanda a una pagina donde hay 9 checkbox para habilitar
hay una tabla en la bd llamada habilitaciones
anda bien
puede utilizarse en algunos abm , habria q verlo bien pero anda
*/
//class Tipos_medidores extends CI_Controller {
class habilitaciones extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Crud_model");
	}


	// Pagina de Inventario
	public function index(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		// agregar breadcrumbs
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Habilitaciones', 'Habilitaciones');

		// salida
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs

		$datos['titulo']= "Habilitaciones";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados("habilitacion","Habilitacion_Borrado");
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

		$this->load->view('habilitaciones/habilitaciones',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');
		$this->load->view('plan_pago/cargar_js');

		endif;
		
	}


	public function guardar()
	{
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			if($this->input->post())
			{
			
				$input1 = $this->input->post("rep_oculto_1", true);
				$input2 = $this->input->post("rep_oculto_2", true);
				$input3 = $this->input->post("rep_oculto_3", true);
				$input4 = $this->input->post("rep_oculto_4", true);
				$input5 = $this->input->post("rep_oculto_5", true);
				$input6 = $this->input->post("rep_oculto_6", true);
				$input7 = $this->input->post("rep_oculto_7", true);
				$input8 = $this->input->post("rep_oculto_8", true);
				$input9 = $this->input->post("rep_oculto_9", true);

				$datos1 = array(
					'Habilitacion_Estado' => $input1 
				);
				$datos2 = array(
					'Habilitacion_Estado' => $input2
				);
				$datos3 = array(
					'Habilitacion_Estado' => $input3
				);
				$datos4 = array(
					'Habilitacion_Estado' => $input4 
				);
				$datos5 = array(
					'Habilitacion_Estado' => $input5 
				);
				$datos6 = array(
					'Habilitacion_Estado' => $input6 
				);
				$datos7 = array(
					'Habilitacion_Estado' => $input7 
				);
				$datos8 = array(
					'Habilitacion_Estado' => $input8 
				);
				$datos9 = array(
					'Habilitacion_Estado' => $input9 
				);

				$resultado1 = $this->Crud_model->modificar_habilitacion($datos1, 1);
				$resultado2 = $this->Crud_model->modificar_habilitacion($datos2, 2);
				$resultado3 = $this->Crud_model->modificar_habilitacion($datos3, 3);
				$resultado4 = $this->Crud_model->modificar_habilitacion($datos4, 4);
				$resultado5 = $this->Crud_model->modificar_habilitacion($datos5, 5);
				$resultado6 = $this->Crud_model->modificar_habilitacion($datos6, 6);
				$resultado7 = $this->Crud_model->modificar_habilitacion($datos7, 7);
				$resultado8 = $this->Crud_model->modificar_habilitacion($datos8, 8);
				$resultado9 = $this->Crud_model->modificar_habilitacion($datos9, 9);
			}
			redirect(base_url(''));
		endif;
	}

}
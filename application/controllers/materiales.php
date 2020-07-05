<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*/ 
Este controller se encarga de realizar el abm de los materiales
que luego son usados en las ordenes de trabajo


*/

class materiales extends CI_Controller {

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
		$this->breadcrumbs->push('Materiales', '/Lista de materiales');

		// salida
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs

		$datos['titulo']= "Materiales";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados("materiales", "Materiales_Borrado");
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



		$this->load->view('materiales/materiales',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');

		endif;
		
	}

public function borrar_materiales()
	{
		$id=  $this->input->post("id");
		$data = array('Materiales_Borrado' => 1 );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "materiales", "Materiales_Id");
		echo true;
	}


	public function editar_material($id_material){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			$datos['material'] = $this->Crud_model->get_data_row('materiales','Materiales_Id',$id_material);
			//$datos['tipos'] = $this->Crud_model->get_data('tmedidor');
			$datos['url'] =base_url()."materiales/guardar_agregar";
			if ($datos['material']) {
				$datos['titulo'] = "Editar Materiales";
				$this->load->view('templates/header', $datos);
				$this->load->view('materiales/agregar', $datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
			}else{
				$this->session->set_flashdata("document_status",mensaje("El Material No existe","danger"));
				redirect('materiales');
			}
		endif;
	}

	public function modificar_bonificacion($id){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			$datos['bonificacion'] = $this->Crud_model->get_bonificacion_id_sin_borrados($id);
		//var_dump($datos['bonificacion']) ;die();
			//$datos['tipos'] = $this->Crud_model->get_data('tmedidor');
			$datos['url'] =base_url()."bonificacion/agregar_bonificacion";
			if ($datos['bonificacion']) {
				$datos['titulo'] = "Editar Usuarios";
				$this->load->view('templates/header', $datos);
				$this->load->view('bonificacion/agregar', $datos);

				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
				$this->load->view('bonificacion/cargar_js_bonificacion');
				
			}else{
				$this->session->set_flashdata("document_status",mensaje("La Bonificacion No existe","danger"));
				redirect('bonificacion');
			}
		endif;
	}


	public function guardar_agregar()
	{
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			if($this->input->post())
			{
				$inputCodigo = $this->input->post("inputCodigo", true);
				$inputCantidad = $this->input->post("inputCantidad", true);
				$inputDescripcion = $this->input->post("inputDescripcion", true);
				$inputObservacion = $this->input->post("inputObservacion", true);
				
				$id = $this->input->post("id", true);

				if($id == -1) // agregar nueva bonificacion
				{
					$datos_material = array(
						'Materiales_Id' => null, 
						'Materiales_Codigo' => $inputCodigo, 
						'Materiales_Descripcion' => $inputDescripcion, 
						'Materiales_Cantidad' => $inputCantidad, 
						'Materiales_Observacion' => $inputObservacion,
						'Materiales_Habilitado' => 1,
						'Materiales_Borrado' => 0,
						'Materliaes_Timestamp' => null
						);
					$id_material_recien_insertado = $this->Crud_model->insert_data("materiales",$datos_material);
					if(is_numeric( $id_material_recien_insertado) )
					{
						$this->session->set_flashdata('aviso','Se guardo crrectamente el material');
						$this->session->set_flashdata('tipo_aviso','success');
					}
					else 
						{
							$this->session->set_flashdata('aviso','NO se guardo correctamente el material');
							$this->session->set_flashdata('tipo_aviso','danger');
						}
					redirect(base_url("materiales"), "refresh");

				}
				else  //modificar bonificacion existente
				{
					$datos_material = array(
						'Materiales_Codigo' => $inputCodigo, 
						'Materiales_Descripcion' => $inputDescripcion, 
						'Materiales_Cantidad' => $inputCantidad, 
						'Materiales_Observacion' => $inputObservacion,
						'Materiales_Habilitado' => 1,
						'Materiales_Borrado' => 0,
						'Materliaes_Timestamp' => null
						);
					$this->Crud_model->update_data($datos_material,$id,"materiales","Materiales_Id");
					$this->session->set_flashdata('aviso', mensaje('Se modificó el material correctamente', 'success'));
				}
			redirect(base_url('materiales'));
			}
		endif;
	}


	public function agregar_materiales(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Usuarios', '/usuarios');
			$this->breadcrumbs->push('Agregar Usuarios', '/usuarios/agregar_usuario');

			// salida
			$datos['bread']=$this->breadcrumbs->show();

			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;

			//$datos['tipos'] = $this->Crud_model->get_data_row('tmedidor',"TMedidor_Id",);

			$datos['titulo']="Agregar Nuevo Usuarios";
			$this->load->view('templates/header',$datos);
			$this->load->view('materiales/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}
}
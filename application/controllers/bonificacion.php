<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class bonificacion extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Crud_model");
	}
	// Tabla de bonificaciones pendientes
	public function index(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		// agregar breadcrumbs
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Bonificaciones', '/Pendientes');
		// salida
		$datos['bread']=$this->breadcrumbs->show();
		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs
		$datos['titulo']= "Bonificaciones";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados_bonif_pendiente("bonificacion", "Bonificacion_Borrado");
		$datos['mensaje'] = $this->session->flashdata('aviso');
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
		$this->load->view('bonificacion/bonificacion',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');
		endif;
	}
	public function cargar_aprobadas(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		// agregar breadcrumbs
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Bonificaciones', '/Aprobadas');
		// salida
		$datos['bread']=$this->breadcrumbs->show();
		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs
		$datos['titulo']= "Bonificaciones";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados_bonif_aprobadas("bonificacion", "Bonificacion_Borrado");
		$datos['mensaje'] = $this->session->flashdata('aviso');
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
		$this->load->view('bonificacion/bonificacion_aprobada',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');
		endif;
	}
	public function cargar_otorgadas(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		// agregar breadcrumbs
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Bonificaciones', '/Otorgadas');
		// salida
		$datos['bread']=$this->breadcrumbs->show();
		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs
		$datos['titulo']= "Bonificaciones";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados_bonif_otorgadas("bonificacion", "Bonificacion_Borrado");
		$datos['mensaje'] = $this->session->flashdata('aviso');
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
		$this->load->view('bonificacion/bonificacion_otorgada',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');
		endif;
	}
	public function borrar_bonificacion()
	{
		$id=  $this->input->post("id");
		$data = array('Bonificacion_Borrado' => 1 );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "bonificacion", "Bonificacion_Id");
		echo true;
	}
	public function modificar_bonificacion($id){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion de la bonificacion
			$datos['bonificacion'] = $this->Crud_model->get_bonificacion_id_sin_borrados($id);
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
				$inputFacturaId = $this->input->post("inputFacturaId", true);
				$inputMontoBonificado = $this->input->post("inputMontoBonificado", true);
				$inputPorcentajeBonificado = $this->input->post("inputPorcentajeBonificado", true);
				$inputObservacion = $this->input->post("inputObservacion", true);
				$id = $this->input->post("id", true);
				if($id == -1) // agregar nueva bonificacion
				{
					$datos_bonificacion = array(
						'Bonificacion_Id' => null, 
						'Bonificacion_Factura_Id' => $inputFacturaId, 
						'Bonificacion_Monto' => $inputMontoBonificado, 
						'Bonificacion_Porcentaje' => $inputPorcentajeBonificado, 
						'Bonificacion_Aprobada' => 1,
						'Bonificacion_Pendiente' => 1,
						'Bonificacion_Observacion' => $inputObservacion,
						'Bonificacion_Habilitacion' => 1,
						'Bonificacion_Borrado' => 0,
						'Bonificacion_Timestamp' => null
						);
					$id_bonificacion_recien_insertado = $this->Crud_model->insert_data("bonificacion",$datos_bonificacion);
					if(is_numeric( $id_bonificacion_recien_insertado) )
					{
						$this->session->set_flashdata('aviso','Se guardó correctamente la bonificacion');
						$this->session->set_flashdata('tipo_aviso','success');
					}
					else 
					{
						$this->session->set_flashdata('aviso','NO se guardo correctamente la bonificacion');
						$this->session->set_flashdata('tipo_aviso','danger');
					}
					redirect(base_url("bonificacion"), "refresh");
				}
				else  //modificar bonificacion existente
				{
					$datos_bonificacion = array(
						'Bonificacion_Factura_Id' => $inputFacturaId, 
						'Bonificacion_Monto' => $inputMontoBonificado, 
						'Bonificacion_Porcentaje' => $inputPorcentajeBonificado, 
						'Bonificacion_Aprobada' => 1,
						'Bonificacion_Pendiente' => 1,
						'Bonificacion_Observacion' => $inputObservacion,
						'Bonificacion_Habilitacion' => 1,
						'Bonificacion_Borrado' => 0,
						'Bonificacion_Timestamp' => null
						);
					$this->Crud_model->update_data($datos_bonificacion,$id,"bonificacion","Bonificacion_Id");
					$this->session->set_flashdata('aviso', mensaje('Se aprobó la bonificacion correctamente', 'success'));
				}
			redirect(base_url('bonificacion'));
			}
		endif;
	}
	public function agregar_bonificacion(){
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
			$this->load->view('bonificacion/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}
}
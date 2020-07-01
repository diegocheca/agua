<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class deuda extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('Clientes_model');
	}

	public function index(){
		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//$datos['clientes'] = $this->Crud_model->get_data("clientes");
			$datos['deudas'] = $this->Crud_model->get_data_dos_campos("deuda", "Deuda_Borrado", 0, "Deuda_Habilitacion", 1);
			$datos['titulo'] = 'Lista de Deudas';
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
			if($datos['deudas']  != false)
				$this->load->view('deuda/deuda', $datos);
			else 
				{
					$data ["mensaje"] = "No hay deuda en este momento";
					$this->load->view("templates/notificacion_incorrecta_success", $data);
				}
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$data ['aviso'] = $this->session->flashdata('aviso');
			if($data ['aviso'] != null)
				$this->load->view("templates/notificacion_view", $data);
		endif;
	}

	public function nuevo($nuevo, $viejo){
		echo "estoy en nuevo:".$nuevo." - mas:".$viejo;die();
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
				$inputConexionId = $this->input->post("inputConexionId", true);
				$inputMonto = $this->input->post("inputMonto", true);
				$inputMonto = str_replace(",", ".", $inputMonto);
				$inputConcepto = $this->input->post("inputConcepto", true);
				//var_dump($inputMonto );die();
				$id = $this->input->post("id", true);
				if($id == -1) // agregar nueva deuda
				{
					$datos_deuda = array(
						'Deuda_Id' => null, 
						'Deuda_Conexion_Id' => $inputConexionId, 
						'Deuda_Monto' => $inputMonto, 
						'Deuda_Concepto' => $inputConcepto, 
						'Deuda_Habilitacion' => 1,
						'Deuda_Borrado' => 0,
						'Deuda_Timestamp' => null
						);
					$resultado =$this->Crud_model->insert_data("deuda",$datos_deuda);
					$this->session->set_flashdata('aviso', mensaje('Se creó la deuda correctamente', 'success'));
					$this->session->set_flashdata('tipo_aviso','success');
				}
				else  //modificar deuda existente
				{
					$datos_deuda = array(
						'Deuda_Id' => $id, 
						'Deuda_Conexion_Id' => $inputConexionId, 
						'Deuda_Monto' => $inputMonto, 
						'Deuda_Concepto' => $inputConcepto, 
						'Deuda_Habilitacion' => 1,
						'Deuda_Borrado' => 0,
						'Deuda_Timestamp' => null
						);
					$resultado = $this->Crud_model->update_data($datos_deuda,$id,"deuda","Deuda_Id");
					//se debe modificar tmb la deuda en la conexion
					$this->session->set_flashdata('aviso', mensaje('Se modificó la deuda correctamente', 'success'));
					$this->session->set_flashdata('tipo_aviso','success');
				}
				$datos_conexion = array( 'Conexion_Deuda' => $inputMonto, );
				$resultado_conexion = $this->Crud_model->update_data($datos_conexion,$inputConexionId,"conexion","Conexion_Id");
			}
			else
			{
				$this->session->set_flashdata('aviso', mensaje('NO Se modificó la deuda correctamente', 'success'));
				$this->session->set_flashdata('tipo_aviso','danger');
			}
			redirect(base_url("deuda"), "refresh");
		endif;
	}
	
	public function borrar_deuda()
	{
		$id=  $this->input->post("id");
		$data = array(
			'Deuda_Habilitacion' => 1,
			'Deuda_Borrado' => 1
			 );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "deuda", "Deuda_Id");
		//borro la deuda de la conexion
		$id_conexion = $this->Crud_model->get_data_row("deuda","Deuda_Id",$id);
		$data = array(
			'Conexion_Deuda' => 0,
			 );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id_conexion->Deuda_Conexion_Id, "conexion", "Conexion_Id");

		echo true;
	}
	public function editar_deuda($id)
	{
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion la deuda en la base de datos
			$datos['deuda'] = $this->Crud_model->get_data_row('deuda','Deuda_Id',$id);
			if ($datos['deuda']) {
				$datos['titulo'] = "Editar Deuda";
				$this->load->view('templates/header', $datos);
				$this->load->view('deuda/agregar', $datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
			}else{
				$this->session->set_flashdata("document_status",mensaje("La deuda no existe","danger"));
				redirect('deuda');
			}
		endif;
	}
	//Pagina para Ingresar Clientes
	public function agregar_deuda(){
		$this->load->helper('form');
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Deuda', '/Lista de deudas');
			$this->breadcrumbs->push('Agregar deuda', '/deuda/agregar');

			// salida
			$datos['bread']=$this->breadcrumbs->show();

			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;

			$datos['titulo']="Agregar Nueva Deuda";
			$this->load->view('templates/header',$datos);
			$this->load->view('deuda/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			
		endif;
	}
}
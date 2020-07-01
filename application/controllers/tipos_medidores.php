<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// CONTROLADOR INVENTARIO
//////////////////////////

class Tipos_medidores extends CI_Controller {


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
		$this->breadcrumbs->push('Productos', '/Tipos_medidores');

		// salida
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs

		$datos['titulo']= "Inventario";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados("tmedidor","TMedidor_Borrado" );
		$datos['mensaje'] = $this->session->flashdata('aviso');
		
		$this->load->view('templates/header',$datos);
		$this->load->view('tipos_medidores/tipos_medidores',$datos);
		$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');

		

		//if($datos ['aviso'] != null)
		//	$this->load->view("templates/notificacion_view", $datos);
		endif;

		
		
	}

public function borrar_tipos_medidores()
	{
		$id=  $this->input->post("id");
		$data = array('TMedidor_Borrado' => 1 );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "tmedidor", "TMedidor_Id");
		echo true;
	}
public function editar_tipo_medidor($id_medidor){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			$datos['tipos'] = $this->Crud_model->get_data_row('tmedidor','TMedidor_Id',$id_medidor);
			//$datos['tipos'] = $this->Crud_model->get_data('tmedidor');
			//var_dump($datos['tipos'] );die();
			$datos['url'] =base_url()."tipos_medidores/guardar_cambios_tmedidor";
			if ($datos['tipos']) {
				$datos['titulo'] = "Editar Tipo Medidor";
				$this->load->view('templates/header', $datos);
				$this->load->view('tipos_medidores/agregar', $datos);
				$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');
			}else{
				$this->session->set_flashdata("document_status",mensaje("El Tipo de Meididor no existe","danger"));
				redirect('tipos_medidores');
			}
		endif;
	}

	public function modificar_tmedidor(){
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			if($this->input->post())
			{
				$inputMarca = $this->input->post("inputMarca", true);
				$inputModelo = $this->input->post("inputModelo", true);
				$inputPrecioMayo = $this->input->post("inputPrecioMayo", true);
				$inputPrecioUni = $this->input->post("inputPrecioUni", true);
				$hab_medidor = $this->input->post("hab_oculto", true);
				if($hab_medidor === "true")
					$hab_medidor =1;
					else $hab_medidor =0;
				$id_tmedidor = $this->input->post("id", true);
				$inputCantidad = $this->input->post("inputCantidad", true);
				$inputInstalados = $this->input->post("inputInstalados", true); 
				$inputSinInstalar = $this->input->post("inputSinInstalar", true);
				$inputBaja = $this->input->post("inputBaja", true);
				$inputReparados = $this->input->post("inputReparados", true);
				$inputObservacion = $this->input->post("inputObservacion", true);

				$datos_viejos  = $this->Crud_model->get_data_row("tmedidor", "TMedidor_Id", $id_tmedidor);
				$datos_tmedidor = array(
					//'TMedidor_Id' => $codigo_producto, 
					'TMedidor_Marca' => $inputMarca, 
					'TMedidor_Modelo' => $inputModelo, 
					'TMedidor_PrecioUnitario' => $inputPrecioUni, 
					'TMedidor_PrecioMayorista' => $inputPrecioMayo, 
					'TMedidor_Cantidad' => $inputCantidad,
					'TMedidor_Instalados' => $inputInstalados,
					'TMedidor_SinInstalar' => $inputSinInstalar,
					'TMedidor_Baja' => $inputBaja,
					'TMedidor_Reparados' => $inputReparados,
					'TMedidor_Observacion' => $inputObservacion,
					'TMedidor_Habilitacion' => $hab_medidor,
					//'TMedidor_Borrado' => $hab,
					//'TMedidor_Timestamp' => $hab,
					);
				//var_dump($id_tmedidor, $datos_tmedidor);die();
				$this->Crud_model->update_data($datos_tmedidor,$id_tmedidor, "tmedidor", "TMedidor_Id");
				$this->session->set_flashdata('aviso', mensaje('Se modifico el medidor correctamente', 'success'));
				redirect(base_url('tipos_medidores'));
			}
		endif;
	}


	public function agregar()
	{
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			if($this->input->post())
			{
				$inputMarca = $this->input->post("inputMarca", true);
				$inputModelo = $this->input->post("inputModelo", true);
				$inputPrecioMayo = $this->input->post("inputPrecioMayo", true);
				$inputPrecioUni = $this->input->post("inputPrecioUni", true);
				$hab_medidor = $this->input->post("hab_oculto", true);
				if($hab_medidor === "true")
					$hab_medidor =1;
					else $hab_medidor =0;
				//$id_tmedidor = $this->input->post("id", true);
				$inputCantidad = $this->input->post("inputCantidad", true);
				$inputInstalados = $this->input->post("inputInstalados", true); 
				$inputSinInstalar = $this->input->post("inputSinInstalar", true);
				$inputBaja = $this->input->post("inputBaja", true);
				$inputReparados = $this->input->post("inputReparados", true);
				$inputObservacion = $this->input->post("inputObservacion", true);

				//$datos_viejos  = $this->Crud_model->get_data_row("tmedidor", "TMedidor_Id", $id_tmedidor);
				$datos_tmedidor = array(
					'TMedidor_Id' => null, 
					'TMedidor_Marca' => $inputMarca, 
					'TMedidor_Modelo' => $inputModelo, 
					'TMedidor_PrecioUnitario' => $inputPrecioUni, 
					'TMedidor_PrecioMayorista' => $inputPrecioMayo, 
					'TMedidor_Cantidad' => $inputCantidad,
					'TMedidor_Instalados' => $inputInstalados,
					'TMedidor_SinInstalar' => $inputSinInstalar,
					'TMedidor_Baja' => $inputBaja,
					'TMedidor_Reparados' => $inputReparados,
					'TMedidor_Observacion' => $inputObservacion,
					'TMedidor_Habilitacion' => 1,
					'TMedidor_Borrado' => 0,
					'TMedidor_Timestamp' => null,
					);
				//var_dump($id_tmedidor, $datos_tmedidor);die();
				$this->Crud_model->insert_data("tmedidor",$datos_tmedidor);
				$this->session->set_flashdata('aviso', mensaje('Se creó el tipo de medidor correctamente', 'success'));
				redirect(base_url('tipos_medidores'));
			}
		endif;
	}




	public function agregar_tipo(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Tipos Medidores', '/tipos_medidores');
			$this->breadcrumbs->push('Agregar Producto', '/tipos_medidores/agregar_tipo');

			// salida
			$datos['bread']=$this->breadcrumbs->show();

			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;

			//$datos['tipos'] = $this->Crud_model->get_data_row('tmedidor',"TMedidor_Id",);

			$datos['titulo']="Agregar Nuevo Tipo de Medidor";
			$this->load->view('templates/header',$datos);
			$this->load->view('tipos_medidores/agregar');
			$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');
		endif;
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Editar extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Facturar_model');
		$this->load->model('Crud_model');
		$this->load->helper('form');
	}

	public function index(){
			
		$this->session->set_flashdata("document_status",mensaje("Debes editar un documento","warning"));
		redirect(base_url("facturar"));
	}
	public function documento($id_doc){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtenemos el numero de id del documento que se quiere editar
			$datosfactura	=	$this->Crud_model->get_data_row('facturacion','id',$id_doc);
			$datoscliente	=	$this->Crud_model->get_data_row('clientes','Cli_Id',$datosfactura->id_cliente);
			$val['items']	=	$this->Crud_model->get_data_result('items','id_factura',$datosfactura->id_factura);
			$val['valores']	=	$datosfactura;
			$val['cliente']	=	$datoscliente;
			$datos['productos']=$this->Facturar_model->get_productos('productos');
			$datos['titulo']= "Editar Documento";
			$this->load->view('templates/header',$datos);
			$this->load->view('editar/editar_documento',$val,$datos);
			$this->load->view('templates/footer');
		endif;
	}

	public function cliente($id_cliente){

		//echo "llegue del formulario";die();
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			$datos['info'] 		=	$this->Crud_model->get_data_row('clientes','Cli_Id',$id_cliente);
		$datos['url'] 		=base_url()."clientes/guardar_cambios_cliente";
			if ($datos['info']) {
				$datos['titulo']	=	"Editar Cliente";
				$this->load->view('templates/header', $datos);
				$this->load->view('clientes/agregar', $datos);
				$this->load->view('templates/footer');
			}else{
				$this->session->set_flashdata("document_status",mensaje("El Cliente no existe","danger"));
				redirect('clientes');
			}
		endif;
	}

	// public function guardar_cambios_cliente()
	// {
	// 	if($this->input->post())
	// 	{
	// 		$tipodoc_ingresado = $this->input->post("tipo_documento", true);
	// 		$documento_ingresado = $this->input->post("nro_documento", true);
	// 		$razon_social = $this->input->post("razon_social", true);
	// 		$representante = $this->input->post("representante", true);
	// 		$email = $this->input->post("email", true);
	// 		$telefono = $this->input->post("telefono", true);
	// 		$celular = $this->input->post("celular", true);
	// 		$direccion = $this->input->post("direccion", true);
	// 		$localidad = $this->input->post("localidad", true);
	// 		$inputDomSum = $this->input->post("inputDomSum", true);
			
	// 		$inputCuit = $this->input->post("inputCuit", true);
	// 		$inputNroCliente = $this->input->post("inputNroCliente", true);
	// 		$inputDomPost = $this->input->post("inputDomPost", true);
	// 		$inputDeudor = $this->input->post("inputDeudor", true);
	// 		$inputIVA = $this->input->post("inputIVA", true);
	// 		$inputHabilitacion = $this->input->post("inputHabilitacion", true);
	// 		$inputObservacion = $this->input->post("inputObservacion", true);
	// 		$id = $this->input->post("id_oculto", true);

	// 		//validation 



	// 		$datos_modificados = array(
	// 			'Cli_TipoDoc' => $tipodoc_ingresado,
	// 			'Cli_NroDocumento' => $documento_ingresado,
	// 			'Cli_NroCliente' =>$inputNroCliente,
	// 			'Cli_RazonSocial' =>  $razon_social,
	// 			'Cli_DomicilioSuministro' => 	$inputDomSum,
	// 			'Cli_DomicilioPostal' => 		$inputDomPost,
	// 			'Cli_Email' => 			$email,
	// 			'Cli_Telefono' => 		$telefono,
	// 			'Cli_Celular' => 	$celular,
	// 			'Cli_Representante' => 	$representante,
	// 			'Cli_Localidad' =>		$localidad,
	// 			'Cli_Tienda' =>		$id,
	// 			'Cli_Cuit' =>	$inputCuit,			
	// 			'Cli_Deudor' =>	$inputDeudor,
	// 			'Cli_TipoIVAId' => 		$inputIVA,
	// 			'Cli_Observacion' =>	$inputObservacion,
	// 			'Cli_Habilitacion' => 		$inputHabilitacion
	// 			);
	// 		//var_dump($id); die();
	// 		$resultado = $this->Crud_model->modificar_datos_clientes($datos_modificados,$id, "clientes", "Cli_Id");
	// 		//var_dump($resultado);die();
	// 		if($resultado)
	// 			redirect(base_url("clientes/"), refresh);
	// 	}
	// 	else 
	// 	{
	// 		$this->session->set_flashdata('mensaje','Debes seleccionar el cliente a editar');
	// 		redirect(base_url());
	// 	}

		
	// }

}
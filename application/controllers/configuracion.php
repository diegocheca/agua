<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracion extends MY_Controller {
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
			$datos['titulo'] = 'Lista de Variables';
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
			//$datos['todas_las_variables'] = $this->Crud_model->get_data("configuracion");
			$datos['todas_las_variables'] = $this->Crud_model->get_data_join_sin_borrados("configuracion", "usuarios", null, "Configuracion_QuienCambio", "id");
			$this->load->view('configuracion/cofiguracion_view', $datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}
	public function editar_variable($id){
		echo "el id es :".$id;die();
	}

	public function configuracion_guardar_modificado(){
		$id = $this->input->post("id_variable_modificando");
		$valor = $this->input->post("valor_variable_modificando");
		$datos = array(
			'Configuracion_Valor' => $valor,
			'Configuracion_Modifcado' => null,
			'Configuracion_QuienCambio	' => 1, // arreglar esto
			 );
		$resultado = $this->Crud_model->update_data($datos, $id,"configuracion", "Configuracion_Id");
		if($resultado)
		{
			$this->session->set_flashdata('aviso','Se guardo crrectamente el cambio');
			$this->session->set_flashdata('tipo_aviso','success');
		}
		else 
		{
			$this->session->set_flashdata('aviso','NO se guardo crrectamente el cambio');
			$this->session->set_flashdata('tipo_aviso','danger');
		}
		redirect(base_url("configuracion"), "refresh");
	}
	public function ver_variable($id=0)
	{
		//$this->set_precio_mt_com("8.50");
		$aux = $this->get_precio_mt_com();
		var_dump($aux);
		$datos['todas_las_variables'] = $this->get_todo();
		var_dump($datos['todas_las_variables']);
	}

	//Genera un Json para la seccion de Autocomplete en Crear Factura
	public function leer_clientes(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:

			header('Content-Type: application/json');
			$valor=$_GET['query'];  //captura la variable que pasa el autocomplete
			$data=$this->Clientes_model->buscar_clientes($valor);
			$clientes = array(); //creamos un array
			foreach($data as $columna) { 
				$id	=	$columna->Cli_Id;
				$razon=$columna->Cli_RazonSocial;
				$documento=$columna->Cli_NroDocumento;
				$direccion=$columna->Cli_DomicilioPostal;
				$clientes[] = array(
					'value'=> $razon, 
					'data' => $id, 
					'nro_documento' => $documento, 
					'direccion' => $direccion
				);
			}
			$array = array("query" => "Unit", "suggestions" => $clientes);
			echo json_encode($array);
		endif;
	}

	public function guardar_cambios_cliente()
	{
		if($this->input->post())
		{
			$tipodoc_ingresado = $this->input->post("tipo_documento", true);
			$documento_ingresado = $this->input->post("nro_documento", true);
			$razon_social = $this->input->post("razon_social", true);
			$representante = $this->input->post("representante", true);
			$email = $this->input->post("email", true);
			$telefono = $this->input->post("telefono", true);
			$celular = $this->input->post("celular", true);
			$direccion = $this->input->post("direccion", true);
			$localidad = $this->input->post("localidad", true);
			//$inputDomSum = $this->input->post("inputDomSum", true);
			$inputCuit = $this->input->post("inputCuit", true);
			$inputNroCliente = $this->input->post("inputNroCliente", true);
			$inputDomPost = $this->input->post("inputDomPost", true);
			$inputDeudor = $this->input->post("inputDeudor", true);
			$inputIVA = $this->input->post("inputIVA", true);
			$inputHabilitacion = $this->input->post("inputHabilitacion", true);
			$inputObservacion = $this->input->post("inputObservacion", true);
			$id = $this->input->post("id_oculto", true);
			$tipo_persona = $this->input->post("inputTipoPersona", true);
			//validation 
			$datos_modificados = array(
				'Cli_TipoPersona'=> $tipo_persona,
				'Cli_TipoDoc' => $tipodoc_ingresado,
				'Cli_NroDocumento' => $documento_ingresado,
				'Cli_NroCliente' =>$inputNroCliente,
				'Cli_RazonSocial' =>  $razon_social,
				//'Cli_DomicilioSuministro' => 	$inputDomSum,
				'Cli_DomicilioPostal' => 		$inputDomPost,
				'Cli_Email' => 			$email,
				'Cli_Telefono' => 		$telefono,
				'Cli_Celular' => 	$celular,
				'Cli_Representante' => 	$representante,
				'Cli_Localidad' =>		$localidad,
				'Cli_Tienda' =>		$id,
				'Cli_Cuit' =>	$inputCuit,			
				'Cli_Deudor' =>	$inputDeudor,
				'Cli_TipoIVAId' => 		$inputIVA,
				'Cli_Observacion' =>	$inputObservacion,
				'Cli_Habilitacion' => 		$inputHabilitacion
				);
			//var_dump($id); die();
			$resultado = $this->Crud_model->modificar_datos_clientes($datos_modificados,$id, "clientes", "Cli_Id");
			//var_dump($resultado);die();
			if($resultado)
				$this->session->set_flashdata('aviso','El usuario fue modificado correctamente');
			else $this->session->set_flashdata('aviso','El usuario NO fue modificado');
			redirect(base_url("clientes"));
		//	$this->load->view("templates/notificacion_view",$resultado);
		}
		else 
		{
			$this->session->set_flashdata('mensaje','Debes seleccionar el cliente a editar');
			redirect(base_url());
		}
	}

	public function borrar_cliente()
	{
		$id=  $this->input->post("id");
		$data = array('Cli_Borrado' => 1, );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "clientes", "Cli_Id");
		echo true;
	}

	//Pagina para Ingresar Clientes
	public function agregar(){
		$this->load->helper('form');
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:

			$datos['titulo'] = 'Agregar Clientes';
			$this->load->view('templates/header',$datos);
			$this->load->view('clientes/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$btn_enviar =$this->input->post('enviar');
			if (isset($btn_enviar)) {
				//ASIGNAMOS UNA VARIABLE A CADA CAMPO RECIBIDO
				$tipo_persona=$this->security->xss_clean(strip_tags($this->input->post('tipo_persona')));
				$tipo_doc=$this->security->xss_clean(strip_tags($this->input->post('tipo_documento')));
				$nro_documento=$this->security->xss_clean(strip_tags($this->input->post('nro_documento')));
				$razon_social=$this->security->xss_clean(strip_tags($this->input->post('razon_social')));
				//$direccion=$this->security->xss_clean(strip_tags($this->input->post('direccion')));
				$email=$this->security->xss_clean(strip_tags($this->input->post('email')));
				$telefono=$this->security->xss_clean(strip_tags($this->input->post('telefono')));
				$celular=$this->security->xss_clean(strip_tags($this->input->post('celular')));
				$representante=$this->security->xss_clean(strip_tags($this->input->post('representante')));
				$localidad=$this->security->xss_clean(strip_tags($this->input->post('localidad')));
				$tienda=$this->security->xss_clean(strip_tags($this->input->post('tienda')));

				$inputNroCliente=$this->security->xss_clean(strip_tags($this->input->post('inputNroCliente')));
				$inputDomPost=$this->security->xss_clean(strip_tags($this->input->post('inputDomPost')));
				$id = $this->input->post("id_oculto", true);

				$inputCuit=$this->security->xss_clean(strip_tags($this->input->post('inputCuit')));
				$inputIVA=$this->security->xss_clean(strip_tags($this->input->post('inputIVA')));
				$inputObservacion=$this->security->xss_clean(strip_tags($this->input->post('inputObservacion')));
				$tipo_persona = $this->input->post("inputTipoPersona", true);
				//coenxion
				$inputDomSum=$this->security->xss_clean(strip_tags($this->input->post('inputDomSum')));
				$inputSector=$this->security->xss_clean(strip_tags($this->input->post('inputsector')));
				$inputtipo_conexion=$this->security->xss_clean(strip_tags($this->input->post('tipo_conexion')));
				$datos_modificados = array(
						'Cli_Id' => null,
						'Cli_TipoPersona' => $tipo_persona,
						'Cli_TipoDoc' => $tipo_doc,
						'Cli_NroDocumento' => $nro_documento,
						'Cli_NroCliente' =>$inputNroCliente,
						'Cli_RazonSocial' =>  $razon_social,
						//'Cli_DomicilioSuministro' => 	$direccion,
						'Cli_DomicilioPostal' => 		$inputDomPost,
						'Cli_Email' => 			$email,
						'Cli_Telefono' => 		$telefono,
						'Cli_Celular' => 	$celular,
						'Cli_Representante' => 	$representante,
						'Cli_Localidad' =>		$localidad,
						'Cli_Tienda' =>		$id,
						'Cli_Cuit' =>	$inputCuit,			
						'Cli_Deudor' =>	0,
						'Cli_TipoIVAId' => 		$inputIVA,
						'Cli_Observacion' =>	$inputObservacion,
						'Cli_Habilitacion' =>1,
						'Cli_Borrado' =>0,
						'Cli_Timestamp' =>null
					);
				//COMPROBAMOS QUE TODOS LOS CAMPOS TENGAN DATOS
				if(isset($razon_social) && !empty($razon_social) && isset($nro_documento) && !empty($nro_documento)){
					//SI LOS CAMPOS ESTAN CORRECTOS LOS INSERTAMOS EN LA BASE DE DATOS
					//LLAMAMOS AL MODELO Clientes_model QUE SE ENCARGARÁ DE INGRESAR LOS DATOS
					$id_cliente = $this->Crud_model->insert_data("clientes",$datos_modificados);
					$datos_conexion = array(
						'Conexion_Id' => null,
						'Conexion_Cliente_Id' => $id_cliente,
						'Conexion_DomicilioSuministro' => $inputDomSum,
						'Conexion_UnionVecinal' => 1,
						'Conexion_Categoria' =>$inputtipo_conexion,
						'Conexion_Sector' =>  $inputSector,
						'Conexion_Latitud' => null,
						'Conexion_Longuitud' => null,
						'Conexion_Observacion' => null,
						'Conexion_Habilitacion' =>1,
						'Conexion_Borrado' =>0,
						'Conexion_Timestamp' =>null
					);
					$id_comexion = $this->Crud_model->insert_data("conexion",$datos_conexion);
					$this->session->set_flashdata('document_status', mensaje('Se Guardó el Cliente','success'));
					redirect(base_url("clientes"));
				}
			}
		endif;
	}

}
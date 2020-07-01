<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Clientes extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('Clientes_model');
	}
	public function index()
	{
		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$datos['clientes'] = $this->Crud_model->get_data_sin_borrados("clientes", "Cli_Borrado");
			$datos['bajas'] = $this->Crud_model->get_cantidad_bajas("clientes");
			$datos['titulo'] = 'Lista de Clientes';
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
			$this->load->view('clientes/clientes', $datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}
	//Genera un Json para la seccion de Autocomplete en Crear Factura
	public function leer_clientes()
	{
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			header('Content-Type: application/json');
			$valor=$_GET['query'];  //captura la variable que pasa el autocomplete
			//var_dump($valor);
			$data=$this->Clientes_model->buscar_clientes($valor);
			$clientes = array(); //creamos un array
			foreach($data as $columna) 
			{ 
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
	public function leer_clientes_desde_tareas()
	{
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			header('Content-Type: application/json');
			$valor=$_GET['query'];  //captura la variable que pasa el autocomplete
			$data=$this->Clientes_model->buscar_clientes_desde_tarea($valor);
			$clientes = array(); //creamos un array
			foreach($data as $columna) 
			{ 
				$id	= $columna->Cli_Id;
				$conexion=$columna->Conexion_Id;
				$razon=$columna->Cli_RazonSocial;
				$direccion=$columna->Conexion_DomicilioSuministro;
				$clientes[] = array(
					'value'=> $razon, 
					'data' => $id, 
					'conexion' => $conexion,
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
				'Cli_Habilitacion' => 	1
				);
			$resultado = $this->Crud_model->modificar_datos_clientes($datos_modificados,$id, "clientes", "Cli_Id");
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
			redirect(base_url("clientes"), "refresh");
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
		$data = array('Conexion_Borrado' => 1, );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "conexion", "Conexion_Cliente_Id");
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
		$todas_las_variables = $this->Crud_model->get_data("configuracion");
		$datos['precio'] =  $todas_las_variables[15]->Configuracion_Valor;
			$this->load->view('templates/header',$datos);
			$datos["conexiones_a_imprimir"] = $this->Crud_model->buscar_conexion_a_imprmir_nuevo();
			//var_dump($datos["conexiones_a_imprimir"]);die();
			$this->load->view('clientes/agregar',$datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$btn_enviar =$this->input->post('enviar');
			if (isset($btn_enviar)) {
				//ASIGNAMOS UNA VARIABLE A CADA CAMPO RECIBIDO
				//$tipo_persona=$this->security->xss_clean(strip_tags($this->input->post('tipo_persona')));
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

				$inputNroMedidor=$this->security->xss_clean(strip_tags($this->input->post('inputNroMedidor')));
				$inputTipoPago=$this->security->xss_clean(strip_tags($this->input->post('pago_medidor')));
				$cantidadCuotas=$this->security->xss_clean(strip_tags($this->input->post('pago_cantidad')));
			//	var_dump($cantidadCuotas,$inputTipoPago,$inputNroMedidor);
			//	die();
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
					$datos_medidor = array(
						'Medidor_Id' => null,
						'Medidor_Codigo' => $inputNroMedidor,
						'Medidor_Conexion_Id' => $id_comexion,
						'Medidor_TMedidor_Id' => 1,
						'Medidor_FechaInstalacion' => null,
						'Medidor_EnReparacion' => null,
						'Medidor_CantIntervenido' => 0,
						'Medidor_Observacion' => null,
						'Medidor_Habilitacion' => 1,
						'Medidor_Borrado' => 0,
						'Medidor_Timestamp' => null
					);
					$id_medidor = $this->Crud_model->insert_data("medidor",$datos_medidor);
					$todas_las_variables = $this->Crud_model->get_data("configuracion");
					if($inputTipoPago === "Tarjeta")
					{
						$datos_p_medidor = array(
							'PlanMedidor_Id' => null,
							'PlanMedidor_Conexion_Id' => $id_comexion,
							'PlanMedidor_MontoTotal' => $todas_las_variables[15]->Configuracion_Valor,
							'PlanMedidor_MontoPagado' => 0,
							'PlanMedidor_MontoCuota' => $todas_las_variables[15]->Configuracion_Valor / $cantidadCuotas,
							'PlanMedidor_Coutas' => $cantidadCuotas,
							'PlanMedidor_Interes' => 0,
							'PlanMedidor_CoutaActual' => 1,
							'PlanMedidor_Observacion' => null,
							'PlanMedidor_Habilitacion' => 1,
							'PlanMedidor_Borrado' => 0,
							'PlanMedidor_Timestamp' => null
						);
						$id_p_medidor = $this->Crud_model->insert_data("planmedidor",$datos_p_medidor);
					}
					else $id_p_medidor = 0;
					//GENERAR RECIBO DE DINERO
					//ingreso
					if($inputTipoPago != "Tarjeta")
					{
						$datos_movimiento = array(
							'Mov_Id' => null,
							'Mov_Tipo' => 2, //ingreso por concepto medidor
							'Mov_Monto' => $todas_las_variables[15]->Configuracion_Valor,
							'Mov_Codigo' =>	"3", //poner codigo cuando lo tengamos
							'Mov_Pago_Id' =>	null, 
							'Mov_Revisado' =>	0,
							'Mov_Quien' =>	$this->session->userdata('id_user'),
							'Mov_Observacion' => "en concepto de pago por instalacion de medidor, conexion_id:".$id_comexion,
							'Mov_Habilitacion' =>	1,
							'Mov_Borrado' =>	0,
							'Mov_Timestamp' =>	null
						);
						$id_movimiento = $this->Crud_model->insert_data("movimiento",$datos_movimiento);
					}
					else  $id_movimiento = 0;
					echo $id_movimiento."-".$razon_social."-".$todas_las_variables[15]->Configuracion_Valor."-".$nro_documento."-".$id_comexion."-".$id_medidor."-".$inputDomSum ;
				}
			}
		endif;
	}
	public function editar_cliente($id){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion de la bonificacion
			$datos['cliente'] = $this->Crud_model->get_data_row_sin_borrado("clientes","Cli_Id","Cli_Borrado",$id);
			$datos['url'] =base_url()."clientes/editar";
			if ($datos['cliente']) {
				$datos['titulo'] = "Editar Cliente";
				$this->load->view('templates/header', $datos);
				$this->load->view('clientes/editar', $datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
			}else{
				$this->session->set_flashdata("document_status",mensaje("El cliente No existe","danger"));
				redirect('clientes');
			}
		endif;
	}
	public function modificar_cliente()
	{
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			if($this->input->post())
			{
				$inputTipoPersona = $this->input->post("inputTipoPersona", true);
				$inputTipoDocumento = $this->input->post("inputTipoDocumento", true);
				$inputNroDocumento = $this->input->post("inputNroDocumento", true);
				$inputNroCliente = $this->input->post("inputNroCliente", true);
				$inputRazonSocial = $this->input->post("inputRazonSocial", true);
				$inputDomPost = $this->input->post("inputDomPost", true);
				$inputEmail = $this->input->post("inputEmail", true);
				$inputTelefono = $this->input->post("inputTelefono", true);
				$inputCelular = $this->input->post("inputCelular", true);
				$inputCuit = $this->input->post("inputCuit", true);
				$inputTipoIVA = $this->input->post("inputTipoIVA", true);
				$inputObservacion = $this->input->post("inputObservacion", true);
				
				$id = $this->input->post("id", true);
				$datos_cliente = array(
					'Cli_TipoPersona' => $inputFacturaId, 
					'Cli_TipoDoc' => $inputTipoDocumento, 
					'Cli_NroDocumento' => $inputNroDocumento, 
					'Cli_RazonSocial' => $inputRazonSocial, 
					'Cli_DomicilioPostal' => $inputDomPost, 
					'Cli_Email' => $inputEmail, 
					'Cli_Telefono' => $inputTelefono, 
					'Cli_Celular' => $inputCelular, 
					'Cli_Cuit' => $inputCuit, 
					'Cli_TipoIVAId' => $inputTipoIVA, 
					'Cli_Deudor' => 0,
					'Cli_Observacion' => $inputObservacion,
					'Cli_Habilitacion' => 1,
					'Cli_Borrado' => 0,
					'Cli_Timestamp' => null
					);
				$this->Crud_model->update_data($datos_cliente,$id,"clientes","Cli_Id");
				$this->session->set_flashdata('aviso', mensaje('Se modificó el cliente correctamente', 'success'));
				}
			redirect(base_url('clientes'));
		endif;
	}

	public function agregar_nuevo()
	{
		//echo "no cargue nada";die();
		$tipo_doc=$this->security->xss_clean(strip_tags($this->input->post('tipo_documento')));
		$nro_documento=$this->security->xss_clean(strip_tags($this->input->post('nro_documento')));
		$razon_social=$this->security->xss_clean(strip_tags($this->input->post('razon_social')));
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
		$inputCuit = str_replace("-", "", $inputCuit);
		$inputIVA=$this->security->xss_clean(strip_tags($this->input->post('inputIVA')));
		$inputObservacion=$this->security->xss_clean(strip_tags($this->input->post('inputObservacion')));
		$tipo_persona = $this->input->post("inputTipoPersona", true);
		$inputDomSum=$this->security->xss_clean(strip_tags($this->input->post('inputDomSum')));
		$inputSector=$this->security->xss_clean(strip_tags($this->input->post('inputsector')));
		$inputtipo_conexion=$this->security->xss_clean(strip_tags($this->input->post('tipo_conexion')));
		$inputNroMedidor=$this->security->xss_clean(strip_tags($this->input->post('inputNroMedidor')));
		$inputTipoPago=$this->security->xss_clean(strip_tags($this->input->post('pago_medidor')));
		$cantidadCuotas=$this->security->xss_clean(strip_tags($this->input->post('pago_cantidad')));
		// var_dump($tipo_doc,$nro_documento,$razon_social,$email,$telefono,$celular,$tienda,$inputDomPost);
		// die();
		$datos_modificados = array(
				'Cli_Id' => null,
				'Cli_TipoPersona' => $tipo_persona,
				'Cli_TipoDoc' => $tipo_doc,
				'Cli_NroDocumento' => $nro_documento,
				'Cli_NroCliente' =>$inputNroCliente,
				'Cli_RazonSocial' =>  $razon_social,
				'Cli_DomicilioSuministro' => 		$inputDomPost,
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
		if(isset($razon_social) && !empty($razon_social) && isset($nro_documento) && !empty($nro_documento))
		{
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
				'Conexion_Habilitacion' => 0,
				'Conexion_Borrado' =>0,
				'Conexion_Timestamp' =>null
			);
			$id_comexion = $this->Crud_model->insert_data("conexion",$datos_conexion);
			$datos_medidor = array(
				'Medidor_Id' => null,
				'Medidor_Codigo' => $inputNroMedidor,
				'Medidor_Conexion_Id' => $id_comexion,
				'Medidor_TMedidor_Id' => 1,
				'Medidor_FechaInstalacion' => null,
				'Medidor_EnReparacion' => null,
				'Medidor_CantIntervenido' => 0,
				'Medidor_Observacion' => null,
				'Medidor_Habilitacion' => 1,
				'Medidor_Borrado' => 0,
				'Medidor_Timestamp' => null
			);
			$id_medidor = $this->Crud_model->insert_data("medidor",$datos_medidor);
			$todas_las_variables = $this->Crud_model->get_data("configuracion");
			if($inputTipoPago === "Tarjeta")
			{
				$datos_p_medidor = array(
					'PlanMedidor_Id' => null,
					'PlanMedidor_Conexion_Id' => $id_comexion,
					'PlanMedidor_MontoTotal' => $todas_las_variables[15]->Configuracion_Valor,
					'PlanMedidor_MontoPagado' => 0,
					'PlanMedidor_MontoCuota' => $todas_las_variables[15]->Configuracion_Valor / $cantidadCuotas,
					'PlanMedidor_Coutas' => $cantidadCuotas,
					'PlanMedidor_Interes' => 0,
					'PlanMedidor_CoutaActual' => 1,
					'PlanMedidor_Observacion' => null,
					'PlanMedidor_Habilitacion' => 1,
					'PlanMedidor_Borrado' => 0,
					'PlanMedidor_Timestamp' => null
				);
			$id_p_medidor = $this->Crud_model->insert_data("planmedidor",$datos_p_medidor);
			}
			else $id_p_medidor = 0;
			//GENERAR RECIBO DE DINERO
			//ingreso
			if($inputTipoPago != "Tarjeta")
			{
				$datos_movimiento = array(
					'Mov_Id' => null,
					'Mov_Tipo' => 2, //ingreso por concepto medidor
					'Mov_Monto' => $todas_las_variables[15]->Configuracion_Valor,
					'Mov_Codigo' =>	"3", //poner codigo cuando lo tengamos
					'Mov_Pago_Id' =>	null, 
					'Mov_Revisado' =>	0,
					'Mov_Quien' =>	$this->session->userdata('id_user'),
					'Mov_Observacion' => "en concepto de pago por instalacion de medidor, conexion_id:".$id_comexion,
					'Mov_Habilitacion' =>	1,
					'Mov_Borrado' =>	0,
					'Mov_Timestamp' =>	null
				);
				$id_movimiento = $this->Crud_model->insert_data("movimiento",$datos_movimiento);
			}
			else  $id_movimiento = 0;
			echo $id_movimiento."-".$razon_social."-".$todas_las_variables[15]->Configuracion_Valor."-".$nro_documento."-".$id_comexion."-".$id_medidor."-".$inputDomSum."-".$id_cliente;
		}
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// CONTROLADOR INVENTARIO
//////////////////////////

//class Tipos_medidores extends CI_Controller {
class plan_pago extends CI_Controller {

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
		$this->breadcrumbs->push('Plan de Pago', '/Plan de pago');

		// salida
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs

		$datos['titulo']= "Plan pago";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados("planpago","PlanPago_Borrado");
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



		$this->load->view('plan_pago/plan_pago',$datos);
		$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');
		$this->load->view('plan_pago/cargar_js');

		

		endif;

		
		
	}

	public function buscar_conexiones()
	{
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			header('Content-Type: application/json');
			$valor=$_GET['query'];
			var_dump($valor);die();
			$data=$this->Crud_model->buscar_conexiones_desde_plan_pago($valor);
			$clientes = array(); 
			foreach($data as $columna) 
			{ 
				//$data2=$this->Crud_model->buscar_medicion_anterior($valor);
				$id	=	$columna->Conexion_Id;
				$razon=$columna->Cli_RazonSocial;
				$documento=$columna->Cli_NroDocumento;
				$direccion=$columna->Cli_DomicilioPostal;
				$domicilio=$columna->Conexion_DomicilioSuministro;
				$tipo_c=$columna->Conexion_Categoria;
				$deuda=$columna->Conexion_Deuda;
				$clientes[] = array(
					'value'=> $razon, 
					'data' => $id, 
					'nro_documento' => $documento, 
					'direccion' => $direccion,
					'domicilio' => $domicilio,
					'deuda' => $deuda,
					'tipo' => $tipo_c
				);
			}
			$array = array("query" => "Unit", "suggestions" => $clientes);
			echo json_encode($array);
		endif;
	}


	public function buscar_conexion()
	{
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			header('Content-Type: application/json');
			$valor=$_GET['query'];  //captura la variable que pasa el autocomplete
			$data=$this->Crud_model->buscar_conexion($valor);
			$clientes = array(); //creamos un array
			foreach($data as $columna) 
			{ 
				$id	=$columna->Conexion_Id;
				$cliente_id	=$columna->Conexion_Cliente_Id;
				$categoria=$columna->Conexion_Categoria;
				$deuda=$columna->Conexion_Deuda;
				$direccion=$columna->Conexion_DomicilioSuministro;
				 
				$conexion[] = array(
					'id'=> $id, 
					'cliente_id' => $cliente_id, 
					'categoria' => $categoria, 
					'deuda' => $deuda, 
					'direccion' => $direccion
				);
			}
			$array = array("query" => "Unit", "suggestions" => $conexion);
			echo json_encode($array);
		endif;
	}

	public function llenar_modal_plan_pago_nuevo()
	{
		$data['is_modal'] = true;
		$data['conexion_id'] = $this->input->post('conexion_id',true);
		//$data['estoy_en_lista'] = $this->input->post('mostrar_div_buscador',true);
		$data['deuda'] = $this->input->post('deuda',true);
		$data['monto_pago'] = 0;
		$data['cuota_actual'] = 1;

		$vista = $this->load->view('plan_pago/agregar', $data, true);
		echo $vista;
	}



public function insertar_plan_desde_vista()
	{
		if($this->input->post() == true) //es una peticion
		{
			$id_conexion=$this->security->xss_clean(strip_tags($this->input->post('inputConexionId_planPago')));

			$montoTotal=$this->security->xss_clean(strip_tags($this->input->post('montoTotal')));

			$cantidad_cuotas=$this->security->xss_clean(strip_tags($this->input->post('cantidadCuotas')));


			$interes=$this->security->xss_clean(strip_tags($this->input->post('interes')));
			$monto_por_cuota=$this->security->xss_clean(strip_tags($this->input->post('montoCuota')));
			$inputFechaaux=$this->security->xss_clean(strip_tags($this->input->post('fechaInicio')));


			$aux =  str_replace('/', '-', $inputFechaaux);
			$fecha_inicio = date("Y-m-d", strtotime($aux));


			$observacion=$this->security->xss_clean(strip_tags($this->input->post('inputObservacion')));

			$datos_plan_pago = array(
				'PlanPago_Id' => null,
				'PlanPago_Conexion_Id' => $id_conexion,
				'PlanPago_MontoTotal' => $montoTotal,
				'PlanPago_MontoPagado' => 0,
				'PlanPago_MontoCuota' => $monto_por_cuota,
				'PlanPago_Coutas' => $cantidad_cuotas,
				'PlanPago_Interes' => $interes,
				'PlanPago_CoutaActual' => 1,
				'PlanPago_Tipo' => 0,
				'PlanPago_FechaInicio' => $fecha_inicio,
				'PlanPago_Observacion' => $observacion,
				'PlanPago_Habilitacion' => 1,
				'PlanPago_Borrado' => 0,
				'PlanPago_Timestamp' => null
			 );

			$id_plan_pago_recien_agregado = $this->Crud_model->insert_data("planpago",$datos_plan_pago);
					if($id_plan_pago_recien_agregado)
					{
						$this->session->set_flashdata('aviso','Se agrego plan de pago');
						$this->session->set_flashdata('tipo_aviso','success');
					}
					else 
					{
						$this->session->set_flashdata('aviso','No se guardo el plan de pago');
						$this->session->set_flashdata('tipo_aviso','danger');
					}
			redirect(base_url('plan_pago'), "refresh");

		}

	}

	public function cargando ( $deuda, $conexion)
	{
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Plan de Pago', '/Plan de pago');

		// salida
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs
		$datos['is_modal'] = true;

		$datos['deuda'] = $deuda;
		$datos['conexion_id'] = $conexion;


		//$this->load->view('templates/header',$datos);
		$this->load->view('plan_pago/agregar',$datos);
		$this->load->view('templates/footer_dos');
		$this->load->view('plan_pago/cargar_js');

	}
	
	public function cargando_desde_lista ( $deuda, $conexion)
	{
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Plan de Pago', '/Plan de pago');

		// salida
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs
		$datos['is_modal'] = true;

		$datos['deuda'] = $deuda;
		$datos['conexion_id'] = $conexion;


		//$this->load->view('templates/header',$datos);
		//$this->load->view('clientes/buscador_cliente_view',$datos);
		$this->load->view('plan_pago/agregar',$datos);
		$this->load->view('templates/footer_dos');
		$this->load->view('plan_pago/cargar_js');

	}


	public function cargar_vista_agregar ()
	{
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Plan de Pago', '/Plan de pago');

		// salida
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;

$datos['titulo']="Agregar Plan Pago";
		// ./ agregar breadcrumbs
		$datos['is_modal'] = true;

		$datos['deuda'] = 0;


		$this->load->view('templates/header',$datos);
		//$this->load->view('clientes/buscador_cliente_view',$datos);
		$this->load->view('plan_pago/agregar_sin_modal',$datos);
		$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');
		$this->load->view('plan_pago/cargar_js');
	}
	

	

public function borrar_usuarios()
	{
		$id=  $this->input->post("id");
		$data = array('PlanPago_Borrado' => 1 );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "planpago", "PlanPago_Id");
		echo true;
	}
public function editar_plan_pago($id){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			$datos['plan_pago'] = $this->Crud_model->get_data_row_sin_borrado('planpago','PlanPago_Id','PlanPago_Borrado',$id);
			//$datos['tipos'] = $this->Crud_model->get_data('tmedidor');
			if ($datos['plan_pago']) {
				$datos['titulo'] = "Editar PlanPago";
				$this->load->view('templates/header', $datos);
				$this->load->view('plan_pago/agregar_sin_modal', $datos);
				$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');
			}else{
				$this->session->set_flashdata("document_status",mensaje("El Usuario No existe","danger"));
				redirect('usuarios');
			}
		endif;
	}

	public function modificar_plan_pago(){
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			if($this->input->post())
			{
				$inputConexionId = $this->input->post("inputConexionId", true);
				$PP_Id= $this->input->post("id_agregarPlanPago", true);
				$id_conexion=$this->security->xss_clean(strip_tags($this->input->post('inputConexionId_planPago')));
				$montoTotal=$this->security->xss_clean(strip_tags($this->input->post('montoTotal')));
				$montopago=$this->security->xss_clean(strip_tags($this->input->post('montoPagado')));
				$cactual=$this->security->xss_clean(strip_tags($this->input->post('cuotaActual')));
				$cantidad_cuotas=$this->security->xss_clean(strip_tags($this->input->post('cantidadCuotas')));
				$interes=$this->security->xss_clean(strip_tags($this->input->post('interes')));
				$monto_por_cuota=$this->security->xss_clean(strip_tags($this->input->post('montoCuota')));
				$inputFechaaux=$this->security->xss_clean(strip_tags($this->input->post('fechaInicio')));
				$aux =  str_replace('/', '-', $inputFechaaux);
				$fecha_inicio = date("Y-m-d", strtotime($aux));
				$observacion=$this->security->xss_clean(strip_tags($this->input->post('inputObservacion')));
				$datos_plan_pago = array(
					'PlanPago_MontoTotal' => $montoTotal,
					'PlanPago_MontoPagado' => $montopago,
					'PlanPago_MontoCuota' => $monto_por_cuota,
					'PlanPago_Coutas' => $cantidad_cuotas,
					'PlanPago_Interes' => $interes,
					'PlanPago_CoutaActual' => $cactual,
					'PlanPago_Tipo' => 0,
					'PlanPago_FechaInicio' => $fecha_inicio,
					'PlanPago_Observacion' => $observacion,
					'PlanPago_Habilitacion' => 1,
					'PlanPago_Borrado' => 0,
					'PlanPago_Timestamp' => null
				 );
				//var_dump($datos_plan_pago,$PP_Id);die();
				$id_plan_pago_recien_agregado = $this->Crud_model->update_data($datos_plan_pago, $PP_Id, "planpago", "PlanPago_Id");
				if($id_plan_pago_recien_agregado)
				{
					$this->session->set_flashdata('aviso','Se modficó correctamente plan de pago');
					$this->session->set_flashdata('tipo_aviso','success');
				}
				else 
				{
					$this->session->set_flashdata('aviso','No se modificó el plan de pago');
					$this->session->set_flashdata('tipo_aviso','danger');
				}
				redirect(base_url('plan_pago'), "refresh");
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
				$inputConexionId = $this->input->post("inputConexionId_agregarPlanPago", true);
				$inputMontoTotal = $this->input->post("inputMontoTotal_agregarPlanPago", true);
				$inputMontoPagado = $this->input->post("inputMontoPagado_agregarPlanPago", true);
				$inputMonotCuota = $this->input->post("inputMontoCuota_agregarPlanPago", true);
				$inputCuotas = $this->input->post("inputCantidadCuotas_agregarPlanPago", true); 
				$inputInteres = $this->input->post("inputInteres_agregarPlanPago", true); 
				$inputCuotaActual = $this->input->post("inputCuotaActual_agregarPlanPago", true); 
				$inputFechaInicio = $this->input->post("inputFechaInicio_agregarPlanPago", true); 
				$inputObservacion = $this->input->post("inputObservacion_agregarPlanPago", true); 


				$id = $this->input->post("id_agregarPlanPago", true);
				$ismodal = $this->input->post("ismodal_agregarPlanPago", true);

				$resultado = 0;
				if($id == -1) // agregar nuevo usuario
				{
					$datos_plan_pago = array(
						'PlanPago_Id' => null, 
						'PlanPago_Conexion_Id' => $inputConexionId, 
						'PlanPago_MontoTotal' => $inputMontoTotal, 
						'PlanPago_MontoPagado' => $inputMontoPagado, 
						'PlanPago_MontoCuota' => $inputMonotCuota, 
						'PlanPago_Coutas' => $inputCuotas,
						'PlanPago_Interes' => $inputInteres,
						'PlanPago_CoutaActual' => $inputCuotaActual,
						'PlanPago_Tipo' => 0,
						'PlanPago_FechaInicio' => $inputFechaInicio,
						'PlanPago_Observacion' => $inputObservacion,
						'PlanPago_Habilitacion' => 1,
						'PlanPago_Borrado' => 0,
						'PlanPago_Timestamp' => null
						);
					//var_dump($id_tmedidor, $datos_tmedidor);die();
					$this->Crud_model->insert_data("planpago",$datos_plan_pago);
					$resultado = $this->session->set_flashdata('aviso', mensaje('Se creó el plan de pago correctamente correctamente', 'success'));
				}
				else  //modificar usuario existente
				{
					$datos_usuarios = array(
						'usuario' => $inputUsuario, 
						'password' => md5($inputPass) , 
						'email' => $inputMail, 
						'rol' => $rol, 
						'nombre' => $inputNombre,
						'avatar_uri' => "img/avatar_default.png",
						'Usuarios_Borrado' => 0
						);
					$this->Crud_model->update_data($datos_usuarios,$id,"usuarios","id");
					$this->session->set_flashdata('aviso', mensaje('Se modificó el usuario correctamente', 'success'));
				}
			if($ismodal == 0)
				redirect(base_url('usuarios'));
			else echo $resultado; 
			}
		endif;
	}

	public function enviar_formulario_modal_plan_pago_nuevo()
	{
		//obtiene la informacion del cliente en la base de datos
		$inputConexionId = $this->input->post("inputConexionId", true);
		$inputMontoTotal = $this->input->post("inputMontoTotal", true);
		$inputMontoPagado = $this->input->post("inputMontoPagado", true);
		$inputMonotCuota = $this->input->post("inputMontoCuota", true);
		$inputCuotas = $this->input->post("inputCantidadCuotas", true); 
		$inputInteres = $this->input->post("inputInteres", true); 
		$inputCuotaActual = $this->input->post("inputCuotaActual", true); 
		$inputFechaInicio = $this->input->post("inputFechaInicio", true); 
		$inputFechaInicio = date("Y-m-d", strtotime($inputFechaInicio));


		$inputObservacion = $this->input->post("inputObservacion", true); 
		$id = $this->input->post("id_agregarPlanPago", true);
		$ismodal = $this->input->post("ismodal_agregarPlanPago", true);
		$resultado = false;
		if($id == -1) // agregar nuevo plan de pago
		{
			$datos_plan_pago = array(
				'PlanPago_Id' => null, 
				'PlanPago_Conexion_Id' => $inputConexionId, 
				'PlanPago_MontoTotal' => $inputMontoTotal, 
				'PlanPago_MontoPagado' => $inputMontoPagado, 
				'PlanPago_MontoCuota' => $inputMonotCuota, 
				'PlanPago_Coutas' => $inputCuotas,
				'PlanPago_Interes' => $inputInteres,
				'PlanPago_CoutaActual' => $inputCuotaActual,
				'PlanPago_Tipo' => 0,
				'PlanPago_FechaInicio' => $inputFechaInicio,
				'PlanPago_Observacion' => $inputObservacion,
				'PlanPago_Habilitacion' => 1,
				'PlanPago_Borrado' => 0,
				'PlanPago_Timestamp' => null
				);
			$resultado = $this->Crud_model->insert_data("planpago",$datos_plan_pago);
			echo $resultado;
		}
		else //actualizar plan de pago
		{
			$datos_plan_pago_actualizando = array(
				'PlanPago_MontoTotal' => $inputMontoTotal, 
				'PlanPago_MontoPagado' => $inputMontoPagado, 
				'PlanPago_MontoCuota' => $inputMonotCuota, 
				'PlanPago_Coutas' => $inputCuotas,
				'PlanPago_Interes' => $inputInteres,
				'PlanPago_CoutaActual' => $inputCuotaActual,
				'PlanPago_Tipo' => 0,
				'PlanPago_FechaInicio' => $inputFechaInicio,
				'PlanPago_Observacion' => $inputObservacion,
				);
			$resultado = $this->Crud_model->update_data($datos_plan_pago_actualizando,$id,"planpago","PlanPago_Id" );
			echo $resultado;
		}
		return $resultado; 
	}
public function buscar_datos_modal_plan_pago_nuevo()
{
	$id_conexion = $this->input->post("inputConexionId");
	$id_planpago = $this->input->post("id_agregarPlanPago");
	$ismodal = $this->input->post("ismodal_agregarPlanPago");

	$data["planpago"] =  $this->Crud_model->get_data_row_sin_borrado("planpago","PlanPago_Id","PlanPago_Borrado",$id_planpago);
	if($data["planpago"] == false)
		echo "error:".$id_planpago."*";
	else 
	{
		$datos['conexion_id'] = $id_conexion;
		$datos['id_plan_pago'] = $id_planpago;
		$datos['is_modal'] = true;
		$datos['fecha_inicio_plan_pago'] = $data["planpago"]->PlanPago_FechaInicio;
		$datos['monto_pago'] = $data["planpago"]->PlanPago_MontoPagado;
		$datos['cantidadCuotas'] = $data["planpago"]->PlanPago_Coutas;
		$datos['monto_por_cuota'] = $data["planpago"]->PlanPago_MontoCuota;
		$datos['interes_por_cuotas'] = $data["planpago"]->PlanPago_Interes;
		$datos['cuota_actual'] = $data["planpago"]->PlanPago_CoutaActual;
		if( $data["planpago"]->PlanPago_Observacion == null)
			 $data["planpago"]->PlanPago_Observacion = "null";
		$datos['observaciones_plan_pago'] = $data["planpago"]->PlanPago_Observacion;
		$json_a_enviar = '{"conexion_id":'.$id_conexion.',"id_plan_pago":'.$id_planpago.',"is_modal":'.true.',"fecha_inicio_plan_pago":"'.$data["planpago"]->PlanPago_FechaInicio.'","monto_pago":'.$data["planpago"]->PlanPago_MontoPagado.',"cantidadCuotas":'.$data["planpago"]->PlanPago_Coutas.',"monto_por_cuota":'.$data["planpago"]->PlanPago_MontoCuota.',"interes_por_cuotas":'.$data["planpago"]->PlanPago_Interes.',"cuota_actual":'.$data["planpago"]->PlanPago_CoutaActual.',"observaciones_plan_pago":"'.$data["planpago"]->PlanPago_Observacion.'"}';
		echo json_encode($json_a_enviar);
	}
}


	public function agregar_plan_pago(){
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

			$datos['titulo']="Agregar Nuevo Plan de pago";
			$this->load->view('templates/header',$datos);
			$this->load->view('plan_pago/agregar');
			$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');
		endif;
	}
}
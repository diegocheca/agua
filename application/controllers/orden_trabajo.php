<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*

este controller se encarga de c*/
class Orden_trabajo extends CI_Controller {

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

public function orden_pendiente(){
	if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		// agregar breadcrumbs
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Orden de trabajo', '/Lista de ordenes pendientes');

		// salida
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs

		$datos['titulo']= "Ordenes de trabajo pendientes";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_ordenes_pendientes();
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

		$this->load->view('orden_trabajo/orden_trabajo_pendiente',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');

		endif;
}

public function borrar_orden_trabajo()
	{
		$id=  $this->input->post("id");
		$data = array('OrdenTrabajo_Borrado' => 1 );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "ordentrabajo", "OrdenTrabajo_Id");
		echo true;
	}
	//**** */
	public function terminar_tarea()
	{
		$id=  $this->input->post("id");
		$nuevo = $this->input->post("nuevo");
		$nConexion = $this->input->post("nConexion");
		$data = array('OrdenTrabajo_Estado' => 4);
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "ordentrabajo", "OrdenTrabajo_Id");
		if($nuevo == 1):
			$conexion = array('Conexion_Habilitacion' => 1);
			$resultadoHabConex = $this->Crud_model->update_data($conexion, $nConexion, "conexion", "Conexion_Id");
			$this->marcar_primer_medicion($nConexion);
		endif;
		echo true;
	}


public function editar_orden_trabajo($id){
	if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			$datos['orden'] = $this->Crud_model->get_data_row('ordentrabajo','OrdenTrabajo_Id',$id);
			//$datos['tipos'] = $this->Crud_model->get_data('tmedidor');
			$datos['materiales']=$this->Crud_model->get_data_sin_borrados("materiales", "Materiales_Borrado");
			$datos['url'] =base_url()."orden_trabajo/";
			if ($datos['orden']) {
				$datos['titulo'] = "Editar Orden de trabajo";
				$this->load->view('templates/header', $datos);
				$this->load->view('orden_trabajo/agregar', $datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
			}else{
				$this->session->set_flashdata("document_status",mensaje("La Orden de trabajo No existe","danger"));
				redirect('orden_trabajo');
			}
		endif;
}

public function actulizar_estado_orden($id_orden){
	if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			$datos['orden'] = $this->Crud_model->get_data_row('ordentrabajo','OrdenTrabajo_Id',$id_orden);
			//$datos['tipos'] = $this->Crud_model->get_data('tmedidor');
			$datos['url'] =base_url()."orden_trabajo/";
			if ($datos['orden']) {
				$datos['titulo'] = "Actualizar orden de trabajo";
				$this->load->view('templates/header', $datos);
				$this->load->view('orden_trabajo/modificar_pendiente', $datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
			}else{
				$this->session->set_flashdata("document_status",mensaje("La Orden No existe","danger"));
				redirect('orden_trabajo/orden_pendiente');
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
			// if($this->input->post())
			// {
			date_default_timezone_set("America/Argentina/Mendoza");
			$inputTarea = $this->input->post("inputTarea", true);
			$inputNombreUsuario = $this->input->post("inputNombreUsuario", true);
			$inputConexionId = $this->input->post("inputConexionId", true);
			$inputDireccion = $this->input->post("inputDireccion", true);
			$inputTecnico = $this->input->post("inputTecnico", true);
			$estado_evento_nuevo = $this->input->post("estado_evento_nuevo", true);
			// $hab = $this->input->post("hab__oculto", true);
    //  			if($hab === "true")
    // 					{
    // 						$hab =1;
    // 						if($inputTarea == "Colocacion de nuevo Medidor")
    // 						$this->marcar_primer_medicion($inputConexionId);
    // 					}
    // 				else $hab =0;
			//var_dump($hab);die();
			//$inputFecha = $this->input->post("inputFecha", true);
			$inputFechaaux=$this->security->xss_clean(strip_tags($this->input->post('fecha_inicio_evento_nuevo',true)));
			$aux =  str_replace('/', '-', $inputFechaaux);
			$inputFecha = date("Y-m-d", strtotime($aux));
			$inputFechaaux=$this->security->xss_clean(strip_tags($this->input->post('fecha_fin_evento_nuevo',true)));
			$aux =  str_replace('/', '-', $inputFechaaux);
			$fechafin = date("Y-m-d", strtotime($aux));
			$materiales = $this->input->post("materiales", true);
			// $inputCantMaterial1 = $this->input->post("inputCantMaterial1", true);
			// $inputCodigoMaterial2 = $this->input->post("inputCodigoMaterial2", true);
			// $inputCantMaterial2 = $this->input->post("inputCantMaterial2", true);
			// $inputCodigoMaterial3 = $this->input->post("inputCodigoMaterial3", true);
			// $inputCantMaterial3 = $this->input->post("inputCantMaterial3", true);
			// $inputCodigoMaterial4 = $this->input->post("inputCodigoMaterial4", true);
			// $inputCantMaterial4 = $this->input->post("inputCantMaterial4", true);
			// $inputCodigoMaterial5 = $this->input->post("inputCodigoMaterial5", true);
			// $inputCantMaterial5 = $this->input->post("inputCantMaterial5", true);
			//var_dump($materiales);die();
			$aux_materiales = array();
			for ($i=0; $i <= 5; $i++) { 
						$aux_materiales [$i] = 0;
						$aux_materiales_codigo [$i] = 0;
						$aux_materiales_cantidad [$i] = 0;
			}
			$i=0;
			if($materiales != null)
			{
						$i=1;
						foreach ($materiales as $key) {
							$aux_materiales [$i] = $key;
							$aux_materiales_codigo [$i] = $key;
							$aux_materiales_cantidad [$i] = 1;
							$i++;
						}
			}
			$inputObservacion = $this->input->post("inputObservacion", true);
			// $nuevafecha = strtotime ( '+7 day' , strtotime ( $inputFecha ) ) ;
			// $fechafin = date ( 'Y-m-j' , $nuevafecha );
			$id = $this->input->post("id", true);
			if($id == -1) // agregar nueva bonificacion
			{
						$datos_orden = array(
							'OrdenTrabajo_Id' => null, 
							'OrdenTrabajo_Tarea' => $inputTarea, 
							'OrdenTrabajo_Cliente' => $inputNombreUsuario, 
							'OrdenTrabajo_Direccion' => $inputDireccion, 
							'OrdenTrabajo_NConexion' => $inputConexionId,
							'OrdenTrabajo_Tecnico' => $inputTecnico,
							'OrdenTrabajo_FechaInicio' => $inputFecha,
							'OrdenTrabajo_FechaFin' => $fechafin,
							'OrdenTrabajo_Porcentaje' => 0,
							'OrdenTrabajo_Color' => null,
							'OrdenTrabajo_CodigoMaterial1' => $aux_materiales[0],
							'OrdenTrabajo_CodigoMaterial2' => $aux_materiales[1],
							'OrdenTrabajo_CodigoMaterial3' => $aux_materiales[2],
							'OrdenTrabajo_CodigoMaterial4' => $aux_materiales[3],
							'OrdenTrabajo_CodigoMaterial5' => $aux_materiales[4],
							'OrdenTrabajo_CantidadMaterial1' => 1,
							'OrdenTrabajo_CantidadMaterial2' => 1,
							'OrdenTrabajo_CantidadMaterial3' => 1,
							'OrdenTrabajo_CantidadMaterial4' => 1,
							'OrdenTrabajo_CantidadMaterial5' => 1,
							'OrdenTrabajo_Creador' => 1,
							'OrdenTrabajo_Estado' => $estado_evento_nuevo,
							'OrdenTrabajo_Observacion' => $inputObservacion,
							'OrdenTrabajo_Habilitacion' => 1,
							'OrdenTrabajo_Borrado' => 0,
							'OrdenTrabajo_Timestamp' => null
							);
						$id_orden_recien_insertado = $this->Crud_model->insert_data("ordenTrabajo",$datos_orden);
						if(is_numeric( $id_orden_recien_insertado) )
						{
							$this->session->set_flashdata('aviso','Se guardo crrectamente la orden de trabajo');
							$this->session->set_flashdata('tipo_aviso','success');
						}
						else 
						{
							$this->session->set_flashdata('aviso','NO se guardo correctamente la orden de trabajo');
							$this->session->set_flashdata('tipo_aviso','danger');
						}
						//var_dump($datos_orden);die();
						if($this->input->is_ajax_request())
							echo $id_orden_recien_insertado;
						else
							redirect(base_url("orden_trabajo"), "refresh");
			}
			else  //modificar bonificacion existente
			{
						if($hab == 1)
							$porcentaje = 100; 
						else $porcentaje = 0; 
						$datos_orden = array(
							'OrdenTrabajo_Tarea' => $inputTarea, 
							'OrdenTrabajo_NConexion' => $inputConexionId,
							'OrdenTrabajo_Tecnico' => $inputTecnico,
							'OrdenTrabajo_FechaInicio' => $inputFecha,
							'OrdenTrabajo_FechaFin' => $fechafin,
							'OrdenTrabajo_Porcentaje' => $porcentaje,
							'OrdenTrabajo_Color' => null,
							'OrdenTrabajo_CodigoMaterial1' => $aux_materiales_codigo[1],
							'OrdenTrabajo_CodigoMaterial2' => $aux_materiales_codigo[2],
							'OrdenTrabajo_CodigoMaterial3' => $aux_materiales_codigo[3],
							'OrdenTrabajo_CodigoMaterial4' => $aux_materiales_codigo[4],
							'OrdenTrabajo_CodigoMaterial5' => $aux_materiales_codigo[5],
							'OrdenTrabajo_CantidadMaterial1' => $aux_materiales_cantidad[1],
							'OrdenTrabajo_CantidadMaterial2' => $aux_materiales_cantidad[2],
							'OrdenTrabajo_CantidadMaterial3' => $aux_materiales_cantidad[3],
							'OrdenTrabajo_CantidadMaterial4' => $aux_materiales_cantidad[4],
							'OrdenTrabajo_CantidadMaterial5' => $aux_materiales_cantidad[5],
							'OrdenTrabajo_Creador' => 1,
							'OrdenTrabajo_Estado' => $estado_evento_nuevo,
							'OrdenTrabajo_Observacion' => $inputObservacion,
							'OrdenTrabajo_Habilitacion' => 1,
							'OrdenTrabajo_Borrado' => 0,
							'OrdenTrabajo_Timestamp' => null
							);
						$this->Crud_model->update_data($datos_orden,$id,"ordenTrabajo","OrdenTrabajo_Id");
						$this->session->set_flashdata('aviso', mensaje('Se modificó la orden correctamente', 'success'));
			}
			redirect(base_url('orden_trabajo'));
		endif;
	}


	public function guardar_desde_ajax($id_cliente, $domicilio_sum,$id_conexion,$razon_social)
	{
		$domicilio_sum = str_replace( "%20", " ", $domicilio_sum);
		$razon_social = str_replace( "%20", " ", $razon_social);
		date_default_timezone_set("America/Argentina/Mendoza");
		$inputFecha = date("Y-m-d");
		$nuevafecha = strtotime ( '+7 day' , strtotime ( $inputFecha ) ) ;
		$fechafin = date ( 'Y-m-j' , $nuevafecha );
		$datos_orden = array(
					'OrdenTrabajo_Id' => null, 
					'OrdenTrabajo_Tarea' => "Colocacion de nuevo Medidor", 
					'OrdenTrabajo_Cliente' => $id_cliente, 
					'OrdenTrabajo_Direccion' => $domicilio_sum, 
					'OrdenTrabajo_NConexion' => $id_conexion,
					'OrdenTrabajo_Tecnico' => "David",
					'OrdenTrabajo_FechaInicio' => $inputFecha,
					'OrdenTrabajo_FechaFin' => $fechafin,
					'OrdenTrabajo_Porcentaje' => 0,
					'OrdenTrabajo_Color' => null,
					'OrdenTrabajo_CodigoMaterial1' => 1,
					'OrdenTrabajo_CodigoMaterial2' => 2,
					'OrdenTrabajo_CodigoMaterial3' => 0,
					'OrdenTrabajo_CodigoMaterial4' => 0,
					'OrdenTrabajo_CodigoMaterial5' => 0,
					'OrdenTrabajo_CantidadMaterial1' => 1,
					'OrdenTrabajo_CantidadMaterial2' => 1,
					'OrdenTrabajo_CantidadMaterial3' => 1,
					'OrdenTrabajo_CantidadMaterial4' => 1,
					'OrdenTrabajo_CantidadMaterial5' => 1,
					'OrdenTrabajo_Creador' => 1,
					'OrdenTrabajo_Estado' => 0,
					'OrdenTrabajo_NewConexion' => 1,
					'OrdenTrabajo_Observacion' => "Generado automaticamente por el sistema al crear nueva conexion",
					'OrdenTrabajo_Habilitacion' => 1,
					'OrdenTrabajo_Borrado' => 0,
					'OrdenTrabajo_Timestamp' => null
					);
		$id_orden_recien_insertado = $this->Crud_model->insert_data("ordenTrabajo",$datos_orden);
		//echo $id_orden_recien_insertado;
		redirect(base_url().'imprimir/crear_orden_de_trabajo_automatica'."/".$id_cliente."/".$domicilio_sum."/".$id_conexion."/".$id_orden_recien_insertado."/".$razon_social,'refresh');
	}





	public function agregar_orden_trabajo(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Orden de trabajo', '/orden_trabajo');
			$this->breadcrumbs->push('Agregar Orden de trabajo', '/orden_trabajo/agregar_orden_trabajo');

			// salida
			$datos['bread']=$this->breadcrumbs->show();

			$datos['materiales']=$this->Crud_model->get_data_sin_borrados("materiales", "Materiales_Borrado");

			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;

			//$datos['tipos'] = $this->Crud_model->get_data_row('tmedidor',"TMedidor_Id",);

			$datos['titulo']="Agregar Nuevo Orden de trabajo";
			$this->load->view('templates/header',$datos);
			$this->load->view('orden_trabajo/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}

	
	public function marcar_primer_medicion($id_conexion){
		$resultado_medicion = $this->Crud_model->get_data_row("medicion","Medicion_Conexion_Id",$id_conexion);
		$todas_las_variables = $this->Crud_model->get_data("configuracion");
		$conexion =  $this->Crud_model->get_data_row("conexion","Conexion_Id",$id_conexion);
		if($conexion->Conexion_Categoria == 1)
		{
			$indice_mts_basicos = $todas_las_variables[5]->Configuracion_Valor;
			 $inputTipo =1;
		}
		else
		{
			$indice_mts_basicos = $todas_las_variables[8]->Configuracion_Valor;
			 $inputTipo = 0;
		}
		if($resultado_medicion == false)
		{
			$datos_medicion = array(
				'Medicion_Id' => null, 
				'Medicion_Conexion_Id' => $id_conexion, 
				'Medicion_Mes' => date("m"), 
				'Medicion_Anio' => date("Y"), 
				'Medicion_Anterior' => 0, 
				'Medicion_Actual' => 0,
				'Medicion_Basico' => $indice_mts_basicos ,
				'Medicion_Excedente' => 0,
				'Medicion_Importe' => 0,
				'Medicion_Mts' => 0,
				'Medicion_IVA' => 0,
				'Medicion_Porcentaje' => 0,
				'Medicion_Tipo' => $inputTipo,
				'Medicion_Recargo' => 0,
				'Medicion_Observacion' => null,
				'Medicion_Habilitacion' => 1,
				'Medicion_Borrado' => 0,
				'Medicion_Timestamp' => null
				);
			$id_medicion_recien_agregada = $this->Crud_model->insert_data("medicion",$datos_medicion);
			//var_dump($id_medicion_recien_agregada);die();
		}
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Este controller se encarga de hacer el abm de las ordenes de trabajo
en la tabla ordentrabajo
pero tiene que estar bien sincronizado con los hooks del sistema

ORDEN DE TRABAJO

OrdenTrabajo_Estado = 0 = sin comenzar 
OrdenTrabajo_Estado = 1 = comienzo
OrdenTrabajo_Estado = 2 = supendida
OrdenTrabajo_Estado = 4 = terminada
*/
class Orden_trabajo extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Crud_model");
	}
	public function index(){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede por url o por  el boton del menu de la izquierda
		//Que es lo que hace: Esta funcion sirva para cargar la lista de usuario y los muestra en pantalla 
		//Mejora:
		//Tabla: usuarios
		/*Pasos que hace
		Paso 1 - compruebo que el usuario este logueado
		Paso 2 - creo variables de pagina
		Paso 3 - busca las ordenes de trabajo creadas
		Paso 4 - Carga pagina (views)
		*/
		//Paso 1 - compruebo que el usuario este logueado
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//Paso 2 - creo variables de pagina
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Orden de trabajo', '/Lista de ordenes de trabajo');
			// salida
			$datos['bread']=$this->breadcrumbs->show();
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			// ./ agregar breadcrumbs
			$datos['titulo']= "Orden de trabajo";//Titulo de la página
			//Paso 3 - busca las ordenes de trabajo creadas
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
			//Paso 4 - Carga pagina (views)
			$this->load->view('orden_trabajo/orden_trabajo',$datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			endif;
	}
	public function orden_pendiente(){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede por url o por  el boton del menu de la izquierda
		//Que es lo que hace: Esta funcion sirva para cargar la lista de ordendes de trabajo pendientes y los muestra en pantalla 
		//Mejora: mejorar la visual
		//Tabla: ordentrabajo
		/*Pasos que hace
		Paso 1 - compruebo que el usuario este logueado
		Paso 2 - creo variables de pagina
		Paso 3 - busca las ordenes de trabajo con estado pendiente (ordentrabajo_estado = 0) creadas
		Paso 4 - Carga pagina (views)
		*/
		//Paso 1 - compruebo que el usuario este logueado
		if (!$this->session->userdata('login')):
				$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
				redirect(base_url());
			else:
			//Paso 2 - creo variables de pagina
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Orden de trabajo', '/Lista de ordenes pendientes');
			// salida
			$datos['bread']=$this->breadcrumbs->show();
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			// ./ agregar breadcrumbs
			$datos['titulo']= "Ordenes de trabajo pendientes";//Titulo de la página
			//Paso 3 - busca las ordenes de trabajo con estado pendiente (ordentrabajo_estado = 0) creadas
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
			//Paso 4 - Carga pagina (views)
			$this->load->view('orden_trabajo/orden_trabajo_pendiente',$datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');

			endif;
	}

	public function borrar_orden_trabajo()
	{
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: form de la pagina principal de ordendes de trabajos (tabla donde salen todos)
		//Que es lo que hace: Esta funcion sirva para eliminar las ordendes de trabajo del sistema, lo hace por softdelete
		//Mejora: 
		//Tabla: ordentrabajo
		/*Pasos que hace
		Paso 1 - leo id a borrar
		//Paso 2 - creo un objeto q luego actualizo en la bd
		Paso 3 - guardo en bd
		Paso 4 - doy aviso
		*/
		//Paso 1 - leo id a borrar
		$id=  $this->input->post("id");
		//Paso 2 - creo un objeto q luego actualizo en la bd
		$data = array('OrdenTrabajo_Borrado' => 1 );
		//Paso 3 - guardo en bd
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "ordentrabajo", "OrdenTrabajo_Id");
		//Paso 4 - doy aviso
		echo true;
	}
	public function terminar_tarea()
	{
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se usa desde el boton de la pagina ppal de orden de trabajo, el boton de la tilde
		//Que es lo que hace: Esta funcion sirva para completar una tarea, es decir la marca como completa (ordentrabajo_estado = 4)
		//Mejora: 
		//Tabla: ordentrabajo
		/*Pasos que hace
		Paso 1 - leo id a borrar
		Paso 2 - creo un objeto q luego actualizo en la bd
		Paso 3 - guardo en bd
		Paso 4 - si es una conexion nueva, entonces la doy como habilitada, xq el medidor comienza a funcionar
		Paso 5 - doy aviso
		*/
		//Paso 1 - leo id a borrar
		$id=  $this->input->post("id");
		$nuevo = $this->input->post("nuevo");
		$nConexion = $this->input->post("nConexion");
		//Paso 2 - creo un objeto q luego actualizo en la bd
		$data = array('OrdenTrabajo_Estado' => 4);//al comienzo del archivo tiene una aclaracion respecto a esto
		//Paso 3 - guardo en bd
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "ordentrabajo", "OrdenTrabajo_Id");
		//Paso 4 - si es una conexion nueva, entonces la doy como habilitada, xq el medidor comienza a funcionar
		if($nuevo == 1):
			$conexion = array('Conexion_Habilitacion' => 1);
			$resultadoHabConex = $this->Crud_model->update_data($conexion, $nConexion, "conexion", "Conexion_Id");
			$this->marcar_primer_medicion($nConexion);
		endif;
		//Paso 5 - doy aviso
		echo true;
	}
	public function editar_orden_trabajo($id){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se usa desde el boton de la pagina ppal de orden de trabajo, el boton del lapiz
		//Que es lo que hace: Esta funcion sirva para abriri la pagina para editar ordenes de trabajos, solo muestra la pagina
		//Mejora: 
		//Tabla: ordentrabajo
		/*Pasos que hace
		Paso 1 - leo id a editar
		Paso 2 - creo un objeto de OT para mostrar
		Paso 3 - creo un objeto de Materiales para mostrar
		Paso 4 - creo la vista y nuestro
		*/
		//Paso 1 - leo id a editar
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//Paso 2 - creo un objeto q luego actualizo en la bd
			$datos['orden'] = $this->Crud_model->get_data_row('ordentrabajo','OrdenTrabajo_Id',$id);
			//Paso 3 - creo un objeto de Materiales q luego actualizo en la bd
			$datos['materiales']=$this->Crud_model->get_data_sin_borrados("materiales", "Materiales_Borrado");
			$datos['url'] =base_url()."orden_trabajo/";
			if ($datos['orden']) {
				//Paso 4 - creo la vista y nuestro
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
	/*
	Parece que sin uso hasta el momento
	public function actulizar_estado_orden($id_orden){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se usa desde el boton de la pagina ppal de orden de trabajo, el boton del lapiz
		//Que es lo que hace: Esta funcion sirva para abriri la pagina para editar ordenes de trabajos, solo muestra la pagina
		//Mejora: 
		//Tabla: ordentrabajo
		/*Pasos que hace
		Paso 1 - leo id a editar
		Paso 2 - creo un objeto de OT para mostrar
		Paso 3 - creo un objeto de Materiales para mostrar
		Paso 4 - creo la vista y nuestro
		*
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
	}*/
	public function guardar_agregar()
	{
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: esta funcion se usa por medio de un form que se encuentra en la view de editar o crear
		//Que es lo que hace: Esta funcion sirva para guardar los datos que se pusieron el form de crear o editar orden de trabajo
		//Mejora: 
		//Tabla: ordentrabajo
		/*Pasos que hace
		Paso 1 - leo id a borrar
		Paso 2 - leo las variables del form
		Paso 3 - compruebo si es alta o edicion de orden de trabajo
		Paso 4 - creo un objeto para guardar en la bd
		Paso 5 - guardo en bd
		Paso 6 - doy aviso
		*/
		//Paso 1 - leo id a borrar
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			// if($this->input->post())
			// {
			//Paso 2 - leo las variables del form
			date_default_timezone_set("America/Argentina/Mendoza");
			$inputTarea = $this->input->post("inputTarea", true);
			$inputNombreUsuario = $this->input->post("inputNombreUsuario", true);
			$inputConexionId = $this->input->post("inputConexionId", true);
			$inputDireccion = $this->input->post("inputDireccion", true);
			$inputTecnico = $this->input->post("inputTecnico", true);
			$estado_evento_nuevo = $this->input->post("estado_evento_nuevo", true);
			//$hab = $this->input->post("hab__oculto", true);
			//if($hab === "true")
			//	{
			//		$hab =1;
			//		if($inputTarea == "Colocacion de nuevo Medidor")
			//		$this->marcar_primer_medicion($inputConexionId);
			//	}
			//else $hab =0;
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
			//Paso 3 - compruebo si es alta o edicion de orden de trabajo
			$id = $this->input->post("id", true);
			if($id == -1) // agregar nueva bonificacion
			{
				//Paso 4 - creo un objeto para guardar en la bd
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
				//Paso 5 - guardo en bd
				$id_orden_recien_insertado = $this->Crud_model->insert_data("ordenTrabajo",$datos_orden);
				//Paso 6 - doy aviso
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
				//Paso 4 - creo un objeto para guardar en la bd
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
				//Paso 5 - guardo en bd
				$this->Crud_model->update_data($datos_orden,$id,"ordenTrabajo","OrdenTrabajo_Id");
				//Paso 6 - doy aviso
				$this->session->set_flashdata('aviso', mensaje('Se modificó la orden correctamente', 'success'));
			}
			redirect(base_url('orden_trabajo'));
		endif;
	}
	public function guardar_desde_ajax($id_cliente, $domicilio_sum,$id_conexion,$razon_social)
	{
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: esta funcion se usa por medio de una llamada por ajax 
		//Que es lo que hace: Esta funcion sirva para guardar los datos que se pusieron el form de crear o editar orden de trabajo
		//Mejora: 
		//Tabla: ordentrabajo
		/*Pasos que hace
		Paso 1 - leo las variables del form
		Paso 2 - creo un objeto para guardar en la bd
		Paso 3 - guardo en bd
		Paso 4 - doy aviso
		*/
		//Paso 1 - leo las variables del form
		$domicilio_sum = str_replace( "%20", " ", $domicilio_sum);
		$razon_social = str_replace( "%20", " ", $razon_social);
		date_default_timezone_set("America/Argentina/Mendoza");
		$inputFecha = date("Y-m-d");
		$nuevafecha = strtotime ( '+7 day' , strtotime ( $inputFecha ) ) ;
		$fechafin = date ( 'Y-m-j' , $nuevafecha );
		//Paso 2 - creo un objeto para guardar en la bd
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
		//Paso 3 - guardo en bd
		$id_orden_recien_insertado = $this->Crud_model->insert_data("ordenTrabajo",$datos_orden);
		//echo $id_orden_recien_insertado;
		//Paso 4 - doy aviso
		redirect(base_url().'imprimir/crear_orden_de_trabajo_automatica'."/".$id_cliente."/".$domicilio_sum."/".$id_conexion."/".$id_orden_recien_insertado."/".$razon_social,'refresh');
	}
	public function agregar_orden_trabajo(){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede via  url o desde la opcion del menu de la izquierda
		//Que es lo que hace: Esta funcion sirva para muestra la pagina de carga de orden de trabajo - no guarda solo muestra
		//Tabla: 
		//Mejora: 
		/*Pasos que hace
		Paso 1 - compruebo logueo
		Paso 2 - busco vartaibles para pagina
		Paso 3 - cargo las viws necesarias
		*/
		//Paso 1 - compruebo logueo
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//Paso 2 - busco vartaibles para pagina
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
			//Paso 3 - cargo las viws necesarias
			$this->load->view('templates/header',$datos);
			$this->load->view('orden_trabajo/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}
	public function marcar_primer_medicion($id_conexion){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: esta funcion se usa solo por medio de otra funcion ( terminar_tarea )
		//Que es lo que hace: Esta funcion sirva para marcar una conexion como habilitada, quiere decir que desde que se puso el medidor
		// va a empezar a funcionar y medidr el consumo, por ende, se activa la conexion para empezar a cobrarle
		//Mejora: 
		//Tabla: conexion - medicion
		/*Pasos que hace
		Paso 1 - busco info de medicion
		Paso 2 - leo las variables del form
		Paso 3 - compruebo si es alta o edicion de orden de trabajo
		Paso 4 - creo un objeto para guardar en la bd
		Paso 5 - guardo en bd
		Paso 6 - doy aviso
		*/

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
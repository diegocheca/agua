<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Este controller se encarga de hacer el abm de los tipos de medidores
en la tabla tmedidor
pero tiene que estar bien sincronizado con los hooks del sistema
*/
class Tipos_medidores extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Crud_model");
	}
	public function index(){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede por url o por  el boton del menu de la izquierda
		//Que es lo que hace: Esta funcion sirva para cargar la lista de tipos de medidores y los muestra en pantalla 
		//Mejora: posible cambio de visual
		//Tabla: tmedidor
		/*Pasos que hace
		Paso 1 - crea pagina
		Paso 2 - busca los tipos de medidores en la bd
		Paso 3 - Carga pagina (views)
		*/
		//Paso 1 - crea pagina
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
		//Paso 2 - busca los tipos de medidores en la bd
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados("tmedidor","TMedidor_Borrado" );
		$datos['mensaje'] = $this->session->flashdata('aviso');
		//Paso 3 - Carga pagina (views)
		$this->load->view('templates/header',$datos);
		$this->load->view('tipos_medidores/tipos_medidores',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');
		endif;
	}
	public function borrar_tipos_medidores()
	{
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: form de la pagina principal de tmedidores (tabla donde salen todos)
		//Que es lo que hace: Esta funcion sirva para eliminar los tmedidores del sistema, lo hace por softdelete
		//Mejora: 
		//Tabla: tmedidor
		/*Pasos que hace
		Paso 1 - leo id a borrar
		Paso 2 - creo el objeto
		Paso 3 - lo borro en bd
		Paso 4 - respondo
		*/
		//Paso 1 - leo id a borrar
		$id=  $this->input->post("id");
		//Paso 2 - creo el objeto
		$data = array('TMedidor_Borrado' => 1 );
		//Paso 3 - lo borro en db
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "tmedidor", "TMedidor_Id");
		//Paso 4 - respondo
		echo true;
	}
	public function editar_tipo_medidor($id_medidor){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede mediante el boton del lapicito, de la tabla de tmdedidor y lleva a esta funcion
		//Que es lo que hace: Esta funcion sirva para cargar la pagina de editar los tmedidores , no los edita , solo carga la view
		//Tabla: tmedidor
		//Mejora: 
		/*Pasos que hace
		Paso 1 - compruebo que el usuario este logueado
		Paso 2 - obtiene la informacion de los  tmedidores en la base de datos
		Paso 3 - cargo las views con los datos
		*/
		//Paso 1 - compruebo que el usuario este logueado
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//Paso 2 - obtiene la informacion del tmedidore de la base de datos
			$datos['tipos'] = $this->Crud_model->get_data_row('tmedidor','TMedidor_Id',$id_medidor);
			$datos['url'] =base_url()."tipos_medidores/guardar_cambios_tmedidor";
			if ($datos['tipos']) {
				//Paso 3 - cargo las views con los datos
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
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede via form de la pagina donde se edita el tmedidor
		//Que es lo que hace: Esta funcion sirva para guardar los datos de los tmedidores a los que se modifica
		//Tabla: tmedidor
		//Mejora: 
		/*Pasos que hace
		Paso 1 - compruebo logueo
		Paso 2 - compruebo que es un post de form
		Paso 3 - leo todas las variables a sobreescribir provenientes del form
		Paso 4 - busco los datos "viejos" del tmedidor
		Paso 5 - creo objeto a guardar
		Paso 6 - escribo en la bd
		Paso 7 - doy aviso , de que se escribio en la bd
		Paso 8 - redirigo a la pagina de los tmedidores ppal (la que tiene la tabla)
		*/
		//Paso 1 - compruebo logueo
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//Paso 2 - compruebo que es un post de form
			if($this->input->post())
			{
				//Paso 3 - leo todas las variables a sobreescribir provenientes del form
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
				//Paso 4 - busco los datos "viejos" del tmedidor
				$datos_viejos  = $this->Crud_model->get_data_row("tmedidor", "TMedidor_Id", $id_tmedidor);
				//Paso 5 - creo objeto a guardar
				$datos_tmedidor = array(
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
					);
				//Paso 6 - escribo en la bd
				$this->Crud_model->update_data($datos_tmedidor,$id_tmedidor, "tmedidor", "TMedidor_Id");
				//Paso 7 - doy aviso , de que se escribio en la bd
				$this->session->set_flashdata('aviso', mensaje('Se modifico el medidor correctamente', 'success'));
				//Paso 8 - redirigo a la pagina de los tmedidores ppal (la que tiene la tabla)
				redirect(base_url('tipos_medidores'));
			}
		endif;
	}
	public function agregar()
	{
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede via  url y muestra la pagina de carga de tmedidor
		//Que es lo que hace: Esta funcion sirva para muestra la pagina de carga de tmedidor - no guarda solo muestra
		//Tabla: tmedidor
		//Mejora: 
		/*Pasos que hace
		Paso 1 - compruebo logueo
		Paso 2 - compruebo que sea un post de form de la view
		Paso 3 - leo todas las variables que hay en el form
		Paso 4 - creo el objeto nuevo para guardar en la base de datos
		Paso 5 - Meto el objeto en la base de datos
		Paso 6 - le aviso al usuario
		Paso 7 - redirigo a la pagina ppal de tmedidores
		*/
		//Paso 1 - compruebo logueo
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//Paso 2 - compruebo que sea un post de form de la view
			if($this->input->post())
			{
				//Paso 3 - leo todas las variables que hay en el form
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
				//Paso 4 - creo el objeto nuevo para guardar en la base de datps
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
				//Paso 5 - Meto el objeto en la base de datos
				$this->Crud_model->insert_data("tmedidor",$datos_tmedidor);
				//Paso 6 - le aviso al usuario
				$this->session->set_flashdata('aviso', mensaje('Se creó el tipo de medidor correctamente', 'success'));
				//Paso 7 - redirigo a la pagina ppal de tmedidores
				redirect(base_url('tipos_medidores'));
			}
		endif;
	}
	public function agregar_tipo(){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede mediante el boton del + , de la tabla de tmdedidor y lleva a esta funcion   o por medio del menu de la izquierda
		//Que es lo que hace: Esta funcion sirva para cargar la pagina de alta los tmedidores , no los crea , solo carga la view
		//Tabla: tmedidor
		//Mejora: 
		/*Pasos que hace
		Paso 1 - compruebo que el usuario este logueado
		Paso 2 - obtiene la informacion de los  tmedidores en la base de datos
		Paso 3 - cargo las views con los datos
		*/
		//Paso 1 - compruebo que el usuario este logueado
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//Paso 2 - obtiene la informacion de los  tmedidores en la base de datos
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Tipos Medidores', '/tipos_medidores');
			$this->breadcrumbs->push('Agregar Producto', '/tipos_medidores/agregar_tipo');
			// salida
			$datos['bread']=$this->breadcrumbs->show();
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			$datos['titulo']="Agregar Nuevo Tipo de Medidor";
			//Paso 3 - cargo las views con los datos
			$this->load->view('templates/header',$datos);
			$this->load->view('tipos_medidores/agregar');
			$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');
		endif;
	}
}
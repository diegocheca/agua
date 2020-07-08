<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Este controller se encarga de hacer el abm de usuarios
en la tabla usuarios
pero tiene que estar bien sincronizado con los hooks del sistema
*/
class Usuarios extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Crud_model"); // casi toddos o todos los abm se hacen con este model
	}
	public function index()
	{
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede por url o por  el boton del menu de la izquierda
		//Que es lo que hace: Esta funcion sirva para cargar la lista de usuario y los muestra en pantalla 
		//Mejora: posible cambio de visual
		//Tabla: usuarios
		/*Pasos que hace
		Paso 1 - crea pagina
		Paso 2 - busca los usarios en la bd
		Paso 3 - Carga pagina (views)
		*/
		//Paso 1 - crea pagina
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		// agregar breadcrumbs
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Usuarios', '/Tipos_medidores');
		// salida
		$datos['bread']=$this->breadcrumbs->show();
		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs
		$datos['titulo']= "Usuarios";//Titulo de la página
		//Paso 2 - busca los usarios en la bd
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados("usuarios", "Usuarios_Borrado");
		$datos['mensaje'] = $this->session->flashdata('aviso');
		//Paso 3 - Carga pagina (views)
		$this->load->view('templates/header',$datos);
		$this->load->view('usuarios/usuarios',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');
		$this->load->view('templates/footer_fin');
		endif;
	}

	public function borrar_usuarios()
	{
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: form de la pagina principal de usuarios (tabla donde salen todos)
		//Que es lo que hace: Esta funcion sirva para eliminar los usuarios del sistema, elo hace por softdelete
		//Mejora: 
		//Tabla: usuarios
		/*Pasos que hace
		Paso 1 - leo id a borrar
		Paso 2 - borro
		Paso 3 - respondo
		*/
		//Paso 1 - leo id a borrar
		$id=  $this->input->post("id");
		$data = array('Usuarios_Borrado' => 1 );
		//Paso 2 - borro
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "usuarios", "id");
		//Paso 3 - respondo
		echo true;
	}
	public function editar_usuarios($id_medidor){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede mediante el boton del lapicito, de la tabla de usuarios y lleva a esta funcion
		//Que es lo que hace: Esta funcion sirva para editar los valores de los usuarios 
		//Tabla: usuarios
		//Mejora: 
		/*Pasos que hace
		Paso 1 - datos para pagina
		Paso 2 - obtiene la informacion del cliente en la base de datos
		Paso 3 - cargo las views con los datos
		*/
		//Paso 1 - datos para pagina
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//Paso 2 - obtiene la informacion del cliente en la base de datos
			$datos['usuario'] = $this->Crud_model->get_data_row('usuarios','id',$id_medidor);
			//$datos['tipos'] = $this->Crud_model->get_data('tmedidor');
			$datos['url'] =base_url()."usuarios/guardar_cambios_usuarios";
			if ($datos['usuario']) {
				//Paso 3 - cargo las views con los datos
				$datos['titulo'] = "Editar Usuarios";
				$this->load->view('templates/header', $datos);
				$this->load->view('usuarios/agregar', $datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
				$this->load->view('templates/footer_fin');
			}else{
				$this->session->set_flashdata("document_status",mensaje("El Usuario No existe","danger"));
				redirect('usuarios');
			}
		endif;
	}
	public function guardar_agregar()
	{
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede via form de la pagina donde se edita el usuarios
		//Que es lo que hace: Esta funcion sirva para guardar los datos de los usuarios a los que se modifica
		//Tabla: usuarios
		//Mejora: 
		/*Pasos que hace
		Paso 1 - compruebo logueo
		Paso 2 - compruebo que es un post de form
		Paso 3 - leo todas las variables a sobreescribir provenientes del form
		Paso 4 - compruebo si es alta o edicion de usuario
		Paso 5 - compruebo si hay un usuarios con ese nombre
		Paso 6 - doy aviso de usuario existente
		Paso 7 - creo objeto a guardar
		Paso 8 - escribo en la bd
		Paso 9 - doy aviso , de que se escribio en la bd
		Paso 10 - creo objeto a escribir
		Paso 11 - compruebo si hasy q modificar la pass o dejarla como esta
		Paso 12 - escribo en bd
		Paso 13 - doy aviso
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
				$inputUsuario = $this->input->post("inputUsuario", true);
				$inputMail = $this->input->post("inputMail", true);
				$inputPass = $this->input->post("inputPass", true);
				$inputPass_dos = $this->input->post("inputPass_dos", true);
				$rol = $this->input->post("rol", true);
				$inputNombre = $this->input->post("inputNombre", true); 
				$id = $this->input->post("id", true);
				//Paso 4 - compruebo si es alta o edicion de usuario
				if($id == -1) // agregar nuevo usuario
				{
						//Paso 5 - compruebo si hay un usuarios con ese nombre
					$resultado = $this->Crud_model->get_data_row_dos_campos("usuarios","Usuarios_Borrado",1, "usuario",$inputUsuario);
					if( $resultado != false )// ya existe un usuario con ese nombre
						//Paso 6 - doy aviso de usuario existente
						$this->session->set_flashdata('aviso', mensaje('No se creó el usuario porque ya existe', 'danger'));
					else
					{
						//Paso 7 - creo objeto a guardar
						$datos_usuarios = array(
							'id' => null, 
							'usuario' => $inputUsuario, 
							'password' => md5($inputPass) , 
							'email' => $inputMail, 
							'rol' => $rol, 
							'nombre' => $inputNombre,
							'avatar_uri' => "img/avatar_default.png",
							'Usuarios_Borrado' => 0
						);
						//Paso 8 - escribo en la bd
						$this->Crud_model->insert_data("usuarios",$datos_usuarios);
						//Paso 9 - doy aviso , de que se escribio en la bd
						$this->session->set_flashdata('aviso', mensaje('Se creó el usuario correctamente', 'success'));
					}
				}
				//Paso 4 - compruebo si es alta o edicion de usuario
				else  //modificar usuario existente
				{
					//Paso 10 - creo objeto a escribir
					$datos_usuarios = array
					(
						'usuario' => $inputUsuario, 
						'email' => $inputMail, 
						'rol' => $rol, 
						'nombre' => $inputNombre,
						'avatar_uri' => "img/avatar_default.png",
						'Usuarios_Borrado' => 0
					);
					//Paso 11 - compruebo si hasy q modificar la pass o dejarla como esta
					if($inputPass != null)
					{
						$datos_usuarios ['password'] = md5($inputPass);
					}
					//Paso 12 - escribo en bd
					$this->Crud_model->update_data($datos_usuarios,$id,"usuarios","id");
					//Paso 13 - doy aviso
					$this->session->set_flashdata('aviso', mensaje('Se modificó el usuario correctamente', 'success'));
				}
			redirect(base_url('usuarios'));
			}
		endif;
	}
	public function agregar_usuario(){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//Como se usa: se accede via  url y muestra la pagina de carga de usuario
		//Que es lo que hace: Esta funcion sirva para muestra la pagina de carga de usuario - no guarda solo muestra
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
			$this->breadcrumbs->push('Usuarios', '/usuarios');
			$this->breadcrumbs->push('Agregar Usuarios', '/usuarios/agregar_usuario');
			// salida
			$datos['bread']=$this->breadcrumbs->show();
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			$datos['titulo']="Agregar Nuevo Usuarios";
			//Paso 3 - cargo las viws necesarias
			$this->load->view('templates/header',$datos);
			$this->load->view('usuarios/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$this->load->view('templates/footer_fin');
		endif;
	}
	

}
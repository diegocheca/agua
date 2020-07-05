<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Este controller se encarga de hacer el abm de usuarios
en la tabla usuarios
pero tiene que estar bien sincronizado con los hooks del sistema
*/
class Usuarios extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Crud_model");
	}


	// Pagina de Inventario
	public function index()
	{
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
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados("usuarios", "Usuarios_Borrado");
		$datos['mensaje'] = $this->session->flashdata('aviso');
		
		$this->load->view('templates/header',$datos);
		$this->load->view('usuarios/usuarios',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');
		$this->load->view('templates/footer_fin');
		endif;
	}

	public function borrar_usuarios()
	{
		$id=  $this->input->post("id");
		$data = array('Usuarios_Borrado' => 1 );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "usuarios", "id");
		echo true;
	}
	public function editar_usuarios($id_medidor){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			$datos['usuario'] = $this->Crud_model->get_data_row('usuarios','id',$id_medidor);
			//$datos['tipos'] = $this->Crud_model->get_data('tmedidor');
			$datos['url'] =base_url()."usuarios/guardar_cambios_usuarios";
			if ($datos['usuario']) {
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
	public function puedo_editar_usuario(){
		return null;
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
	public function guardar_agregar()
	{
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			if($this->input->post())
			{
				$inputUsuario = $this->input->post("inputUsuario", true);
				$inputMail = $this->input->post("inputMail", true);
				$inputPass = $this->input->post("inputPass", true);
				$inputPass_dos = $this->input->post("inputPass_dos", true);
				$rol = $this->input->post("rol", true);
				$inputNombre = $this->input->post("inputNombre", true); 
				$id = $this->input->post("id", true);
				if($id == -1) // agregar nuevo usuario
				{
					$resultado = $this->Crud_model->get_data_row_dos_campos("usuarios","Usuarios_Borrado",1, "usuario",$inputUsuario);
					//var_dump($resultado);die();
					if( $resultado != false )// ya existe un usuario con ese nombre
						$this->session->set_flashdata('aviso', mensaje('No se creó el usuario porque ya existe', 'danger'));
					else
					{
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
						$this->Crud_model->insert_data("usuarios",$datos_usuarios);
						$this->session->set_flashdata('aviso', mensaje('Se creó el usuario correctamente', 'success'));
					}
				}
				else  //modificar usuario existente
				{
					$datos_usuarios = array
					(
						'usuario' => $inputUsuario, 
						'email' => $inputMail, 
						'rol' => $rol, 
						'nombre' => $inputNombre,
						'avatar_uri' => "img/avatar_default.png",
						'Usuarios_Borrado' => 0
					);
					if($inputPass != null)
					{
						$datos_usuarios ['password'] = md5($inputPass);
					}

					$algo = $this->puedo_editar_usuario();
				//	var_dump($algo);die();
					$this->Crud_model->update_data($datos_usuarios,$id,"usuarios","id");
					$this->session->set_flashdata('aviso', mensaje('Se modificó el usuario correctamente', 'success'));
				}
			redirect(base_url('usuarios'));
			}
		endif;
	}
	public function agregar_usuario(){
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

			//$datos['tipos'] = $this->Crud_model->get_data_row('tmedidor',"TMedidor_Id",);

			$datos['titulo']="Agregar Nuevo Usuarios";
			$this->load->view('templates/header',$datos);
			$this->load->view('usuarios/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$this->load->view('templates/footer_fin');
		endif;
	}
	public function join_zarpado(){
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
			//$datos['tipos'] = $this->Crud_model->get_data_row('tmedidor',"TMedidor_Id",);
			$datos['titulo']="Agregar Nuevo Usuarios";
			$this->load->view('templates/header',$datos);
			$data ["usuarios"] = $this->Crud_model->join_nivel_dios();
			//var_dump($data ["usuarios"]);die();
			$this->load->view('usuarios/todos',$data);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}

}
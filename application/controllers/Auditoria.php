<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auditoria extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//$this->load->model('Nuevo_model');
		$this->load->model('Crud_model');
	}
	public function index(){
	}

	public function join_zarpado(){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
		//CREO Q NO SE USA
		//Como se usa: se accede por url o por  el boton del menu de la izquierda
		//Que es lo que hace: Esta funcion sirva para mostrar por pantalla un super join entre varias tablas (conexion,facturacion,medicion,planpago,deuda,medidor ) y ver si estan bien o le falta algo
		// .. si esta en verde, esta bien. Si esta en rojo, esta mal...
		//Mejora: hacerlo mas veloz, se deomora muchisimo - posible cambio de visual
		//Tabla: usuarios, conexion, 
		/*Pasos que hace
		Paso 1 - veo si esta logueado el usuario
		Paso 2 - creo las variables de la pagina
		Paso 3 - hago el super join
		Paso 4 - Carga pagina (views)
		*/
		//Paso 1 - veo si esta logueado el usuario
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//Paso 2 - creo las variables de la pagina
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
			//Paso 3 - hago el super join
			$data ["usuarios"] = $this->Crud_model->join_nivel_dios();
			//Paso 4 - Carga pagina (views)
			$this->load->view('usuarios/todos',$data);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}



}
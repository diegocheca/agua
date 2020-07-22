<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auditoria extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//$this->load->model('Nuevo_model');
		$this->load->model('Crud_model');
	}
	public function index(){
		//Usado: 18-7-20 - Diego
		//Corregido : 18-7-20 - Diego
		//Como se usa: se accede por url o por  el boton del menu de la izquierda
		//Que es lo que hace: Esta funcion sirva para mostrar por pantalla los ultimos registros de la base de datos, es decir el log del sistema
		//Mejora: hacerlo mas veloz, 
		//Tabla: auditoria
		/*Pasos que hace
		Paso 1 - veo si esta logueado el usuario
		Paso 2 - creo las variables de la pagina
		Paso 3 - hago el super join
		Paso 4 - Carga pagina (views)
		*/








		/*
		else:
			if($fin == 0)
				$fin = $this->input->post('fin_reporte_pagos');

			if($inicio == 0)
				$inicio = $this->input->post('inicio_reporte_pagos');

			if($tipo == 0)
				$tipo = $this->input->post('select_tipo_movimiento');


			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Movimientos', '/movimiento');
			$datos['bread']=$this->breadcrumbs->show();
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			$datos['titulo']= "Movimiento";

			$datos['consulta']=$this->Nuevo_model->buscar_movimientos_tabla($inicio,$fin, $tipo);

			$datos['mensaje'] = $this->session->flashdata('aviso');
			$this->load->view('templates/header',$datos);
			$data ['mensaje'] = $this->session->flashdata('aviso');
			if($data ['mensaje'] != null)
			{
				$data ['tipo'] = $this->session->flashdata('tipo_aviso');
				if($data ['tipo'] == "success")
				{
					$this->load->view("templates/notificacion_correcta_success", $data);
				}
				else
					$this->load->view("templates/notificacion_incorrecta_success", $data);
			}
			$this->load->view('movimiento/movimientos_view',$datos);
			$this->load->view('templates/footer');


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
			$datos['titulo']="Logs del Sistema";
			$this->load->view('templates/header',$datos);
			//Paso 3 - hago el super join
			//$data ["usuarios"] = $this->Crud_model->join_nivel_dios();
			//Paso 4 - Carga pagina (views)
			$datos['logs'] = $this->Crud_model->traer_toda_la_tabla('log_deuda_multa');
			//var_dump($datos['logs']);die();
			$this->load->view('auditoria/timeline-auditoria'); // es el css de la vista
			$this->load->view('auditoria/timeline', $datos);
			//$this->load->view('usuarios/todos',$data);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');


		endif;

	}

	public function join_zarpado(){
		//Usado: 8-7-20 - Diego
		//Corregido : 8-7-20 - Diego
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

	
	public function aprobar_registro()
	{
		$id=  $this->input->post("id");
		$data = array('log_deuda_multa_Revisado' => "Si", );
		$resultado =  $this->Crud_model->update_data($data,$id, "log_deuda_multa", "log_deuda_multa_Id");
		echo $resultado;
	}



}
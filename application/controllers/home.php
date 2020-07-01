<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('facturar_model');
	}

	public function index(){
		$datos['titulo'] = 'Iniciar Sesion';
		//si la session no esta iniciada, muestra el formulario
		var_dump($this->session->userdata('login'));

		if (!$this->session->userdata('login')):
			$this->load->view('login',$datos);
		else:
		 	
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			$datos['titulo']='Bienvenido al Escritorio';
			$this->load->view('templates/header',$datos);
			//$this->load->view('templates/calendar_script_view');
			//$this->load->view('templates/calendar_script_view');
			date_default_timezone_set("America/Argentina/Mendoza");
			// if($this->session->userdata('rol') == "administrador")
			// {
			// 	$this->load->view('dashboard/graficos_view');
			// }
			
			$this->load->view('dashboard');

			$datos["conexiones_a_imprimir"] = $this->Crud_model->buscar_conexion_a_imprmir_nuevo();
			//var_dump($datos["conexiones_a_imprimir"]);die();
			$datos["conexiones_a_imprimir_conexion"] = $this->Crud_model->buscar_conexion_a_imprmir();
			
			$this->load->view('dashboard/impresiones_view', $datos);
			// $this->load->view('codigo_barra/bienvenido_view');
			
			$ingresos[0] = $this->facturar_model->buscar_ingresos(date("Y-m-d"));
			$ingresos[1] = $this->facturar_model->buscar_ingresos (date("Y-m-d", strtotime('-1 day')));
			$ingresos[2] = $this->facturar_model->buscar_ingresos (date("Y-m-d", strtotime('-2 day')));
			$ingresos[3] = $this->facturar_model->buscar_ingresos (date("Y-m-d", strtotime('-3 day')));
			$ingresos[4] = $this->facturar_model->buscar_ingresos (date("Y-m-d", strtotime('-4 day')));
			$ingresos[5] = $this->facturar_model->buscar_ingresos (date("Y-m-d", strtotime('-5 day')));
			$ingresos[6] = $this->facturar_model->buscar_ingresos (date("Y-m-d", strtotime('-6 day')));
			$ingresos[7] = $this->facturar_model->buscar_ingresos (date("Y-m-d", strtotime('-7 day')));
			$egresos[0] = $this->facturar_model->buscar_egresos(date("Y-m-d"));
			$egresos[1] = $this->facturar_model->buscar_egresos (date("Y-m-d", strtotime('-1 day')));
			$egresos[2] = $this->facturar_model->buscar_egresos (date("Y-m-d", strtotime('-2 day')));
			$egresos[3] = $this->facturar_model->buscar_egresos (date("Y-m-d", strtotime('-3 day')));
			$egresos[4] = $this->facturar_model->buscar_egresos (date("Y-m-d", strtotime('-4 day')));
			$egresos[5] = $this->facturar_model->buscar_egresos (date("Y-m-d", strtotime('-5 day')));
			$egresos[6] = $this->facturar_model->buscar_egresos (date("Y-m-d", strtotime('-6 day')));
			$egresos[7] = $this->facturar_model->buscar_egresos (date("Y-m-d", strtotime('-7 day')));
			$datos["grafico"] = 
				"['dias', 'Ingreso', 'Egresos'],
				['".date("d/m")."',  ".$ingresos[0][0]["Mov_Monto"].",  ".$egresos[0][0]["Mov_Monto"]."],
				['".date('d/m', strtotime('-1 day')) ."',  ".$ingresos[1][0]["Mov_Monto"].",      ".$egresos[1][0]["Mov_Monto"]."],
				['".date('d/m', strtotime('-2 day')) ."',  ".$ingresos[2][0]["Mov_Monto"].",      ".$egresos[2][0]["Mov_Monto"]."],
				['".date('d/m', strtotime('-3 day')) ."',  ".$ingresos[3][0]["Mov_Monto"].",      ".$egresos[3][0]["Mov_Monto"]."],
				['".date('d/m', strtotime('-4 day')) ."',  ".$ingresos[4][0]["Mov_Monto"].",      ".$egresos[4][0]["Mov_Monto"]."],
				['".date('d/m', strtotime('-5 day')) ."',  ".$ingresos[5][0]["Mov_Monto"].",      ".$egresos[5][0]["Mov_Monto"]."],
				['".date('d/m', strtotime('-6 day')) ."',  ".$ingresos[6][0]["Mov_Monto"].",      ".$egresos[6][0]["Mov_Monto"]."],
				['".date('d/m', strtotime('-7 day')) ."',  ".$ingresos[7][0]["Mov_Monto"].",      ".$egresos[7][0]["Mov_Monto"]."] ";
			// if($this->session->userdata('rol') == "administrador")
			// {
			// }
			if($egresos[0][0]["Mov_Monto"] == 0 && $ingresos[0][0]["Mov_Monto"] == 0)
				$datos["total_plata"] = 1;
			else
			{
				//var_dump($datos["ingreso_plata"] );die();
				//if($egresos[0][0]["Mov_Monto"]  != 0 )
				//if (isset($datos["ingreso_plata"]) )
					$datos["total_plata"] = $egresos[0][0]["Mov_Monto"] + $ingresos[0][0]["Mov_Monto"];
				//else 
				//	$datos["total_plata"] = $egresos[0][0]["Mov_Monto"];
			}

			if($ingresos[0][0]["Mov_Monto"] == 0)
			{
				$datos["ingreso_plata"] = 0;
				$datos["ingreso_porcentaje"] =50;
			}
			else
			{
				$datos["ingreso_plata"] = $ingresos[0][0]["Mov_Monto"];
				if($datos["total_plata"]  != 0)
					$datos["ingreso_porcentaje"] =round(($ingresos[0][0]["Mov_Monto"] *100)/$datos["total_plata"] , 1);
				else $datos["ingreso_porcentaje"] = 50;
				
			}
			if( $egresos[0][0]["Mov_Monto"] == 0)
			{
				$datos["egreso_plata"] =0;
				$datos["egreso_porcentaje"] = 100 - $datos["ingreso_porcentaje"];
			}
			else
			{
				$datos["egreso_plata"] = $egresos[0][0]["Mov_Monto"];
				if($datos["total_plata"]  != 0)
					$datos["egreso_porcentaje"] = round(($egresos[0][0]["Mov_Monto"] *100)/$datos["total_plata"] , 1);
				else $datos["egreso_porcentaje"] = 50;
			}
			//var_dump($datos["ingreso_plata"], $datos["egreso_porcentaje"] , $datos["ingreso_porcentaje"] );die();
			

			
			
			$this->load->view('templates/ventas_grafico_view',$datos);
			$this->load->view('movimiento/modal_movimientos_del_dia');

			
			$datos["codigos"] = $this->Crud_model->buscar_codigos();
			$this->load->view('templates/cargar_ingreso_modal',$datos);
			$this->load->view('templates/reportes_modal',$datos);
			
			//$data["personas_conexiones"] = $this->Crud_model->buscar_personas_conexiones();
			//var_dump($datos["conexiones_a_imprimir_conexion"]);die();
			// $this->load->view('dashboard/pagar_por_nombre_view',$datos["conexiones_a_imprimir_conexion"]);
			if($this->session->userdata('rol') == "administrador")
			{
				$this->load->view('templates/calendar_view');
				$datos["tareas"] = $this->Crud_model->get_tareas_incompletas("tarea","Tarea_Borrado");
				//var_dump($datos["tareas"]);die();
				//$this->load->view('dashboard/todo_list_view',$datos);
				// $this->load->view('dashboard/mediores_graficos_view');
				// $this->load->view('dashboard/graficos_pagos_view');
				//$this->load->view('dashboard/recient_post_view');
			}
			$this->load->view("probando_boton");
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url());
	}
	public function mostrar_picker ()
	{
		echo $this->load->view("templates/avatar_picker_view",true);
	}
}
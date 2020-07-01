<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Movimientos extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Crud_model");
		$this->load->model("Nuevo_model");
			$this->load->model('facturar_model');
		$this->load->helper(array('form', 'url'));
	}

	public function index($inicio = 0,$fin=0, $tipo =0){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
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

		//	var_dump($inicio,$fin);die();


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
			//var_dump($datos['consulta']);die();
			$this->load->view('movimiento/movimientos_view',$datos);
			$this->load->view('templates/footer');
		endif;
	}

	public function ingresos(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Movimientos', '/movimientos');
		$datos['bread']=$this->breadcrumbs->show();
		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		$datos['titulo']= "Ingresos";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados_movimientos("movimiento", "Mov_Borrado",1);
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
		$this->load->view('movimiento/ingresos_list',$datos);
		$this->load->view('templates/footer');
		endif;
	}

	public function egresos(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Movimientos', '/movimientos');
		$datos['bread']=$this->breadcrumbs->show();
		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		$datos['titulo']= "Egresos";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_sin_borrados_movimientos("movimiento", "Mov_Borrado",3);
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
		$this->load->view('movimiento/ingresos_list',$datos);
		$this->load->view('templates/footer');
		endif;
	}

	public function agregar(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$datos['codigos'] = $this->Crud_model->get_data_sin_borrados("codigos", "Codigo_Borrado");
			if($datos['codigos']):
				$datos['titulo'] = "Agregar Movimiento";
				$this->load->view('templates/header', $datos);
				$this->load->view('movimiento/agregar', $datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');	
			else:
				$this->session->set_flashdata("document_status",mensaje("Error al cargar los codigos","danger"));
				redirect('movimientos');
			endif;
		endif;
	}
	public function editar($id){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$datos['movimiento'] = $this->Crud_model->get_data_row_sin_borrado('movimiento','Mov_Id','Mov_Borrado',$id);
			$datos['codigos'] = $this->Crud_model->get_data_sin_borrados("codigos", "Codigo_Borrado");
			if ($datos['movimiento']) {
				$datos['titulo'] = "Editar Movimiento";
				$this->load->view('templates/header', $datos);
				$this->load->view('movimiento/agregar', $datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
			}else{
				$this->session->set_flashdata("document_status",mensaje("El Movimiento No existe","danger"));
				redirect('movimientos');
			}
		endif;
	}

	public function borrar_movimiento()
	{
		$id=  $this->input->post("id");
		$data = array('Mov_Borrado' => 1, );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "movimiento", "Mov_Id");
		echo true;
	}

	public function guardar_movimiento(){
		if($this->input->post())
		{
			$id = $this->input->post("id", true);
			$tipo = $this->input->post("tipo_movimiento", true);
			$monto = $this->input->post("inputMonto", true);
			$codigo = $this->input->post("codigo_movimiento", true);
			$descripcion = $this->input->post("inputObservacion", true);
			if($id == -1):
				$datos_movimiento = array(
					'Mov_Id' => null,
					'Mov_Tipo' => $tipo,
					'Mov_Monto' => $monto,
					'Mov_Codigo' => $codigo,
					'Mov_Pago_Id' => null,
					'Mov_Revisado' => 0,
					'Mov_Quien' => 1,
					'Mov_Observacion' => $descripcion,
					'Mov_Habilitacion' => 1,
					'Mov_Borrado' => 0,
					'Mov_Timestamp' => null
				);
				$id_movimiento_recien_insertado = $this->Crud_model->insert_data("movimiento",$datos_movimiento);
				if(is_numeric( $id_movimiento_recien_insertado)):
					echo true;
					$this->session->set_flashdata("document_status",mensaje("El Movimiento fue agregado correctamente","succes"));
					redirect('movimientos');

				else:
					echo "false";
				endif;
			else:
				$datos_movimiento = array(
					'Mov_Tipo' => $tipo,
					'Mov_Monto' => $monto,
					'Mov_Codigo' => $codigo,
					'Mov_Pago_Id' => null,
					'Mov_Revisado' => 0,
					'Mov_Quien' => 1,
					'Mov_Observacion' => $descripcion,
					'Mov_Habilitacion' => 1,
					'Mov_Borrado' => 0,
					'Mov_Timestamp' => null
				);
				$this->Crud_model->update_data($datos_movimiento,$id,"movimiento","Mov_Id");
				$this->session->set_flashdata('aviso', mensaje('Se modificó correctamente el movimiento', 'success'));
				redirect('movimientos');
			endif;
		}
		else
			echo "false";
	}

	public function guardar_movimiento_desde_modal(){
		/*

		Pasos:
		1 Leo los datos del form y creo las variables a usar
			2 Calcula la deuda y el acuetna de la conexion , si es el caso
		3 Crear Movimiento (Con la ex tabla de pago incluida)
			4 Actualizo Factura Nueva
		5 Creo Recibo

		*/

		//PASO 1
		$tipo = $this->input->post("tipo", true);
		$monto = $this->input->post("monto", true);
		$codigo = $this->input->post("codigo", true);
		$descripcion = $this->input->post("descripcion", true);
		$tipo_pago = $this->input->post("tipo_pago", true);
		$estado = $this->input->post("estado", true);
		//$acuenta = $this->input->post("acuenta", true);
		$conexion_id = $this->input->post("conexion_id", true);
		$cliente_id = $this->input->post("cliente_id", true);
		$saldo = $this->input->post("saldo", true);
		$aquien = $this->input->post("aquien", true);
		$inputFechaaux=$this->security->xss_clean(strip_tags($this->input->post('fecha',true)));
		$aux =  str_replace('/', '-', $inputFechaaux);
		$inputFecha = date("Y-m-d H:i:s", strtotime($aux));
		/*
		Tipo : 
		     1 -> Ingreso
		     2 -> Egreso
		     4 -> Pago A cuenta o Deuda
		*/
		$factura_id = 0;
     	if(($tipo == 1) || ($tipo == 1) ) //1 -> Ingreso
		{
				$conexion_id = 0;
				$factura_id = 0;
		}
		//PASO 2
		if($tipo == 4) //4 -> Pago A cuenta o Deuda
		{
			//estoy cargando un nuevo monto a cuenta
			//	PASO 4
			// voy a actualizar la factura de este mes
			//busca la factura mas nueva  y le actyualizo el deuda y acuenta
			$anio = date("Y");
			$datos_factura = $this->Nuevo_model->get_data_tres_campos("facturacion_nueva", 'Factura_Mes', -1,'Factura_Año',$anio, 'Factura_Conexion_Id', $conexion_id);
			$factura_actual = null;
			foreach ($datos_factura as $key) {
				$mes_max = 0;
				if($key->Factura_Mes > $mes_max)
					{
						$factura_actual = $key;
						$mes_max = $key->Factura_Mes;
					}
			}
			//var_dump($factura_actual->Factura_Id);die();
			$acuenta = 0 ;
			$deuda  = 0 ; 
			if($saldo > 0 ) // significa q cambia el valor de acuenta
			{
				$acuenta = $saldo;// ya se calculo en la vista
				$datos_conexion = array(
					'Conexion_Deuda' => $deuda,
					'Conexion_Acuenta' => $acuenta,
				);
				$datos_factura = array(
					'Factura_Deuda' => $deuda,
					'Factura_Acuenta' => $acuenta
					);
				//var_dump($datos_factura);die();
				$resultado_datos_facturacion = $this->Crud_model->update_data($datos_factura, $factura_actual->Factura_Id, "facturacion_nueva","Factura_Id");
			
			}
			elseif($saldo < 0)
			{ // aca actualizo la deuda , y le descuento lo q esta poniendo
				$deuda  = floatval($saldo) *  -1 ; 
				$datos_conexion = array(
					'Conexion_Deuda' => $deuda ,
					'Conexion_Acuenta' => 0,
				);
				$datos_factura = array(
					'Factura_Deuda' => $deuda,
					'Factura_Acuenta' => 0
					);
				$resultado_datos_facturacion = $this->Crud_model->update_data($datos_factura, $factura_actual->Factura_Id, "facturacion_nueva","Factura_Id");
			}
			$resultado = $this->Crud_model->update_data($datos_conexion, $conexion_id, "Conexion","Conexion_Id");
			//recalcular la factura creada
			$recalculo_de_boleta = $this->corregir_boleta_por_id($factura_actual->Factura_Id);

			// $subtotal = 
			// 		floatval($deuda) +
			// 		floatval($factura_actual->Factura_TarifaSocial) +
			// 		floatval($factura_actual->Factura_ExcedentePrecio)+
			// 		floatval($factura_actual->Factura_CuotaSocial)+
			// 		floatval($factura_actual->Factura_PM_Cuota_Precio) +
			// 		floatval($factura_actual->Factura_PPC_Precio) +
			// 		floatval($factura_actual->Factura_Riego) ;

			// 	$total = $subtotal;
			// 	$bonificacion = 0;
			// 	if(intval($deuda)  == 0) 
			// 		$bonificacion = floatval($subtotal) * floatval(0.015);
			// 	$total = floatval($total)
			// 							- floatval($acuenta)
			// 							- floatval($bonificacion);
			// $todas_las_variables = $this->Nuevo_model->get_data("configuracion");
			// $vto_2_precio = floatval($total) + floatval($total) * floatval($todas_las_variables[18]->Configuracion_Valor);
			// $datos = array(
			// 	"Factura_SubTotal" => $subtotal,
			// 	"Factura_Bonificacion" => $bonificacion,
			// 	"Factura_Total" => $total,
			// 	"Factura_Vencimiento1_Precio" => $total,
			// 	"Factura_Vencimiento2_Precio" => $vto_2_precio,
			// );
			// $resultado = $this->Nuevo_model->update_data($datos, $factura_actual->Factura_Id, "facturacion_nueva","Factura_Id");
		//	var_dump($bonificacion,$deuda,$acuenta,$datos);die();
		}
		//PASO 3
		$monto = str_replace(",", ".", $monto);
		$datos_movimiento = array(
			'Mov_Id' => null,
			'Mov_Tipo' => $tipo,
			'Mov_Monto' => $monto,
			'Mov_Codigo' => $codigo,
			'Mov_Pago_Id' => null,
			'Mov_Revisado' => 0,
			'Mov_Quien' =>  $this->session->userdata('id_user'),
			'Mov_Observacion' => $descripcion,
			'Mov_Habilitacion' => 1,
			'Mov_Borrado' => 0,
			'Mov_Timestamp' => $inputFecha,
			'Mov_FechaInsert' => date("Y-m-d H:i:s"),
			'Mov_Conexion_Id' => $conexion_id,
			'Mov_Factura_Id' => $factura_id,
			'Mov_a_quien' => $aquien
			
		);
		$id_movimiento_recien_insertado = $this->Crud_model->insert_data("movimiento",$datos_movimiento);
		if(is_numeric( $id_movimiento_recien_insertado)):
			echo $id_movimiento_recien_insertado;
		else:
			echo "false";
		endif;
	}

}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Automatico extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
	}
	public function index(){
		//sin uso . te manda a la abm de  conexion
		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$datos['conexiones'] = $this->Crud_model->get_conexion_sin_borrados("conexion","clientes", "Conexion_Borrado","Conexion_Cliente_Id", "Cli_Id");
			$datos['titulo'] = 'Lista de Conexiones';
			$this->load->view('templates/header',$datos);
			$data ['mensaje'] = $this->session->flashdata('aviso');
			if($data ['mensaje'] != null)// hay aviso
			{
				$data ['tipo'] = $this->session->flashdata('tipo_aviso');
				if($data ['tipo'] == "success")
					$this->load->view("templates/notificacion_correcta_success", $data);
				else
					$this->load->view("templates/notificacion_incorrecta_success", $data);
			}
			$this->load->view('conexiones/conexiones', $datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$data ['aviso'] = $this->session->flashdata('aviso');
			if($data ['aviso'] != null)
				$this->load->view("templates/notificacion_view", $data);
		endif;
	}
	public function pasar_boletas_no_pagadas_a_deuda()
	{
		$anio_buscado = date("Y");
		$mes_buscado = intval( date("m") )-2;
		// if($mes_buscado<10)
		// 	$mes_buscado = "0".$mes_buscado;
		// if($mes_buscado == 1)
		// {
		// 	$mes_buscado = 11;
		// 	$anio_buscado = intval(date("Y")) -1;
		// }
		// elseif ($mes_buscado == 2) {
		// 	$mes_buscado = 12;
		// 	$anio_buscado = intval(date("Y")) -1;
		// }
		$facturas_del_mes_anterior = $this->Crud_model->traer_facturas_del_mes_anterior($mes_buscado , $anio_buscado);
		/**
		1 - ver si cada una de las facturas ha sido pagada  -> totalmente -> parcialmente -> o nada
				* ver si la factura tiene asociadoo un pago 
						si no teine significa q tiene deuda
						*cargar la deuda en la tabla conexion
		2 -
		*/
		foreach ($facturas_del_mes_anterior as $key) {
			if(isset($key->Pago_Id) && ($key->Pago_Id != null )  && ($key->Pago_Id > 0) )
			{ // signfica que si pago, debo ver si ya tenia deuda
				//ver si pago todo
				if($key->Pago_Monto == $key->Factura_Total) // pague todo
				{
					//saco las deudas anteriores para esa conexion
					$datos_conexion = array( 'Conexion_Deuda' => 0);
					$resultado_actualizar_deuda_en_conexion = $this->Crud_model->update_data($datos_conexion, $key->Conexion_Id, "conexion", "Conexion_Id");

					$datos_pago = array( 'Pago_Habilitacion' => 0, 'Pago_Observacion' => "Ya pago la deuda el: ".date("Y/m/d H:m:s") );
					$resultado_actualizar_pago = $this->Crud_model->update_data($datos_pago, $key->id, "pago", "Pago_Facturacion_Id");
				}
				else
				{
					//ver si pago una parte
					$datos_conexion = array( 'Conexion_Deuda' => floatval($key->Factura_Monto) - floatval($key->Pago_Total) );
					$resultado_actualizar_deuda_en_conexion = $this->Crud_model->update_data($datos_conexion, $key->Conexion_Id, "conexion", "Conexion_Id");
				}
			}
			else
			{// no pago y se debe cargar la deuda en su cuenta
				$datos_conexion = array( 'Conexion_Deuda' => $key->Factura_Monto);
				//actualizo la deuda de la conexion
				$resultado_actualizar_deuda_en_conexion = $this->Crud_model->update_data($datos_conexion, $key->Conexion_Id, "conexion", "Conexion_Id");
				//creo una deuda
				$arrayName = array(
					'Deuda_Id' => null ,
					'Deuda_Conexion_Id' => $key->Conexion_Id ,
					'Deuda_Concepto' => "No se registro pago para esta factura",
					'Deuda_Monto' => $key->Factura_Monto ,
					'Deuda_Habilitacion' => 1 ,
					'Deuda_Borrado' => 0 ,
					'Deuda_Timestamp' => null
					 );
				$resultado_cargar_deuda = $this->Crud_model->insert_data("deuda",$arrayName);
			}
			//$resultado = $this->Crud_model->insert_data("medicion",$datos_a_insertar);
		}
	}

}
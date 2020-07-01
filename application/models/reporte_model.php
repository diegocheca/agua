<?php
class Reporte_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	public function get_data_result_array($tabla,$campo,$valor){
		$this->db->where($campo, $valor);
		$consulta = $this->db->get($tabla);
		if ($consulta->num_rows() > 0):
			return $consulta->result_array();
		else:
			return false;
		endif;
	}

	
	public function buscar_pagos_entre_fechas($inicio,$fin){

		$dia_i = $inicio." 00:00:00";
		$dia_f = $fin." 23:59:59";
		$this->db->from('pago');
		//$this->db->where('disposicion',$disposicion);
		$this->db->where('Pago_Timestamp >=', $dia_i);
		$this->db->where('Pago_Timestamp <=', $dia_f);

		//$this->db->join("pago","pago.Pago_Facturacion_Id = facturacion.id");
		//$this->db->where($campo, $valor);

		$this->db->join("clientes","clientes.Cli_Id = pago.Pago_Cli_Id");
		$this->db->join("facturacion","facturacion.id = pago.Pago_Facturacion_Id");


		$this->db->join("conexion","conexion.Conexion_Id = facturacion.Factura_Conexion_Id");
		$this->db->join("medicion","medicion.Medicion_Id = facturacion.Factura_Medicion_Id");


		$this->db->join("planpago","planpago.PlanPago_Conexion_Id = conexion.Conexion_Id","left");
		$this->db->join("deuda","deuda.Deuda_Conexion_Id = conexion.Conexion_Id","left");
		
		//$this->db->join("medidor","medidor.Medidor_Conexion_Id = conexion.Conexion_Id","left");

		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0):
			return $consulta->result_array();
		else:
			return false;
		endif;
	}
	public function buscar_conexiones_para_informe(){
		$this->db->select('*');
		$this->db->where('Conexion.Conexion_Borrado', 0);
		//$this->db->where('clientes.Cliente_Borrado', 0);
		$this->db->from('Conexion');
		$this->db->join('clientes', 'clientes.Cli_Id = Conexion.Conexion_Cliente_Id');
		$consulta = $this->db->get();
		return $consulta->result_array();
	}
	public function buscar_historial_de_pago_cliente($valor){
		$this->db->select('*');
		$this->db->like('Cli_Id', $valor);
		$this->db->where('Cli_Borrado',0);
		$this->db->from('clientes');
		$this->db->join("conexion","conexion.Conexion_Cliente_Id = clientes.Cli_Id");
		$this->db->join("facturacion","facturacion.Factura_Conexion_Id = conexion.Conexion_Id");
		$this->db->join("medicion","medicion.Medicion_Conexion_Id = conexion.Conexion_Id");
		$this->db->join("pago","pago.Pago_Facturacion_Id = facturacion.id");
		$this->db->join("planpago","planpago.PlanPago_Conexion_Id = conexion.Conexion_Id","left");
		$this->db->join("deuda","deuda.Deuda_Conexion_Id = conexion.Conexion_Id","left");
		$this->db->join("medidor","medidor.Medidor_Conexion_Id = conexion.Conexion_Id","left");
		$consulta = $this->db->get();
		return $consulta->result_array();
	}
	public function buscar_pagos_por_mes($inicio){
		$fin_mes = "2017-10-31 23:59:59";
		$inicio = "2017-05-31 23:59:59";
		$this->db->select('*');
		$this->db->where('Pago_Timestamp >= ',$inicio);
		$this->db->where('Pago_Timestamp <= ',$fin_mes);
		$this->db->from('pago');
		$this->db->join("clientes","clientes.Cli_Id  = pago.Pago_Cli_Id");
		$this->db->join("conexion","conexion.Conexion_Cliente_Id = clientes.Cli_Id");
		$this->db->join("facturacion","facturacion.Factura_Conexion_Id = conexion.Conexion_Id");
		//$this->db->join("medicion","medicion.Medicion_Conexion_Id = conexion.Conexion_Id");
		$consulta = $this->db->get();
		return $consulta->result_array();
	}


}
?>
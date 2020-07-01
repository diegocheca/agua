<?php
class Impresiones_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	

	public function buscar_lote_por_sectores($datos){
		//var_dump($datos);die();
		$this->db->select('*');
		$this->db->group_by('Conexion_Id');

		$this->db->where('conexion.Conexion_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Habilitacion', 1);
		$this->db->where_in('conexion.Conexion_Sector', $datos); 
		//$this->db->where('conexion.Conexion_Sector', "A"); 
		$this->db->where('medicion.Medicion_Mes', 2); 
		$this->db->where('medicion.Medicion_Anio', 2018); 
		$this->db->where('facturacion.Factura_Periodo', 2); 
		$this->db->from('conexion');
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id'); 
		// //$this->db->join('conexion', 'conexion.Conexion_Id = facturacion.Factura_Conexion_Id','left'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id','left');
		$this->db->join('facturacion', 'facturacion.Factura_Conexion_Id = conexion.Conexion_Id'); 
		//$this->db->join('bonificacion', 'bonificacion.Bonificacion_Factura_Id = facturacion.id','left'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
		return $consulta->result();
		else return false;

	}

	public function buscar_lote_por_sectores_anterior($datos){
		//var_dump( intval(date("m"))-1);die();
		$this->db->select('*');
		$this->db->ORDER_BY("conexion.Conexion_UnionVecinal", "ASC");
		$this->db->group_by('Conexion_Id');

		$this->db->where('conexion.Conexion_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Habilitacion', 1);
		$this->db->where_in('conexion.Conexion_Sector', $datos); 
		//$this->db->where('conexion.Conexion_Sector', "A"); 
		$this->db->where('medicion.Medicion_Mes', 12); 
		$this->db->where('facturacion.Factura_Periodo', 12); 
		$this->db->from('conexion');
		$this->db->join('facturacion', 'facturacion.Factura_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id'); 
		// //$this->db->join('conexion', 'conexion.Conexion_Id = facturacion.Factura_Conexion_Id','left'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id','left');
		//$this->db->join('bonificacion', 'bonificacion.Bonificacion_Factura_Id = facturacion.id','left'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
		return $consulta->result();
	else return false;

	}

	
	public function buscar_lote_por_conexion($datos){

		$this->db->limit(1);
		$this->db->ORDER_BY("facturacion.Factura_Timestamp", "DESC");
		
		$this->db->select('*');
		$this->db->where('conexion.Conexion_Borrado', 0);
		$this->db->where('facturacion.Factura_Habilitacion', 1);
		//$this->db->where('bonificacion.Bonificacion_Habilitacion', 1);
		 //$this->db->where('PlanPago.PlanPago_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Habilitacion', 1);
		$this->db->where('conexion.Conexion_Id', $datos); 
		$this->db->from('conexion');
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id'); 
		$this->db->join('facturacion', 'facturacion.Factura_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id', 'left'); 
		$this->db->join('bonificacion', 'bonificacion.Bonificacion_Factura_Id = facturacion.id', 'left'); 
		$consulta = $this->db->get();
		//var_dump($consulta->num_rows());die();
		if($consulta->num_rows()>0)
		return $consulta->result();
	else return false;

	}


public function buscar_historial_cliente($cliente, $conexion){
		$this->db->select('*');
		$this->db->group_by('facturacion.Factura_Periodo');

		$this->db->where('conexion.Conexion_Borrado', 0);
		$this->db->where('conexion.Conexion_Id', $conexion); 
		$this->db->from('conexion');
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id'); 
		$this->db->join('facturacion', 'facturacion.Factura_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id', 'left'); 
		$this->db->join('bonificacion', 'bonificacion.Bonificacion_Factura_Id = facturacion.id', 'left'); 
		$this->db->join('pago', 'pago.Pago_Facturacion_Id = facturacion.id', 'left'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
		return $consulta->result();
	else return false;

	}

	public function buscar_por_conexion($conexion){

		$this->db->select('*');
		$this->db->where('conexion.Conexion_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Habilitacion', 1);
		$this->db->where('conexion.Conexion_Id', $conexion); 
		$this->db->from('conexion');
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id'); 
		// //$this->db->join('conexion', 'conexion.Conexion_Id = facturacion.Factura_Conexion_Id','left'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
	//	$this->db->join('Medicion', 'Medicion.Medicion_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id','left'); 
		//$this->db->join('bonificacion', 'bonificacion.BonificacionPlanMedidor_Conexion_Id = conexion.Conexion_Id','left'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()==1)
			return $consulta->row();
		else return false;

	}

	
	public function buscar_ultima_boleta($id_cliente,$conexion)
	{
		$this->db->limit(1);
		$this->db->ORDER_BY("facturacion.Factura_Timestamp", "DESC");
		$this->db->where('facturacion.id_cliente', $id_cliente); 
		$this->db->where('facturacion.Factura_Conexion_Id', $conexion); 
		//$this->db->where('bonificacion.Bonificacion_Habilitacion', 1);//si no tiene bonificacion , la rompe
	//	$this->db->where('facturacion.Factura_Habilitacion', 1);  // SI ES 1 ESTA SIN PAGAR - SI ES 0 YA SE PAGÓ
		$this->db->join('bonificacion', 'bonificacion.Bonificacion_Factura_Id = facturacion.id', 'left'); 
		$this->db->from('facturacion'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()==1)
			return $consulta->row();
		else return false;

	}

public function buscar_ultima_boleta_mejor($id)
	{
	//	$this->db->limit(1);
		$this->db->where('facturacion.id', $id); 
		$this->db->where('facturacion.Factura_Habilitacion', 1);  // SI ES 1 ESTA SIN PAGAR - SI ES 0 YA SE PAGÓ
		$this->db->from('facturacion'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()==1)
			return $consulta->row();
		else return false;

	}

	public function buscar_conexion($id_coenxionfactura){

		$this->db->select('Factura_Conexion_Id');
		$this->db->where('facturacion.Factura_Borrado', 0);
		$this->db->where('facturacion.id', $id_factura); 
		$this->db->from('facturacion'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()==1)
		return $consulta->row();
	else return false;

	}
	
	public function buscar_pago_para_recibo($id_apgo)
	{
		$this->db->select('*');
		$this->db->where('pago.Pago_Id', $id_apgo);
		// $this->db->where('pago.Pago_Borrado', 0);
		// $this->db->where('pago.Pago_Habilitacion', 1);
		$this->db->from('pago');
		$this->db->join('movimiento', 'movimiento.Mov_Pago_Id = pago.Pago_Id','left'); 
		$this->db->join('Facturacion', 'Facturacion.id = pago.Pago_Facturacion_Id','left'); 
		$this->db->join('clientes', 'clientes.Cli_Id = Facturacion.id_cliente'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;

	}

	public function buscar_pago_para_recibo_actualizado($id_mov)
	{
		$this->db->select('*');
		$this->db->where('movimiento.Mov_Id', $id_mov);
		$this->db->from('movimiento');
		//$this->db->join('movimiento', 'movimiento.Mov_Pago_Id = pago.Pago_Id','left'); 
		$this->db->join('facturacion_nueva', 'facturacion_nueva.Factura_Id = movimiento.Mov_Factura_Id','left'); 
		$this->db->join('conexion', 'conexion.Conexion_Id = movimiento.Mov_Conexion_Id', 'left'); 
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id', 'left'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;

	}


	public function buscar_pago_para_recibo_acuenta($id_apgo)
	{
		$this->db->select('*');
		$this->db->where('pago.Pago_Id', $id_apgo);
		// $this->db->where('pago.Pago_Borrado', 0);
		// $this->db->where('pago.Pago_Habilitacion', 1);
		$this->db->from('pago');
		$this->db->join('movimiento', 'movimiento.Mov_Pago_Id = pago.Pago_Id','left'); 
		$this->db->join('clientes', 'clientes.Cli_Id = pago.Pago_Cli_Id'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;

	}


// $names = array('Frank', 'Todd', 'James');
// $this->db->where_in('username', $names);
// // Produces: WHERE username IN ('Frank', 'Todd', 'James')


	// public function getEventos(){
	// 	$this->db->select('Evento_Id id, Evento_Titulo title, Evento_FechaInicio start, Evento_FechaFin end, Evento_Estado url');
	// 	$this->db->from('evento');

	// 	return $this->db->get()->result();
	// }
	
}
?>
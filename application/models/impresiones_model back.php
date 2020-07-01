<?php
class Impresiones_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	
	public function buscar_lote_por_sectores($datos){

		$this->db->select('*');
		$this->db->where('conexion.Conexion_Borrado', 0);
		$this->db->where('PlanPago.PlanPago_Borrado', 0);
		$this->db->where('PlanPago.PlanPago_Habilitacion', 1);
		$this->db->where_in('conexion.Conexion_Sector', $datos); 
		$this->db->from('conexion');
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id','left'); 
		// //$this->db->join('conexion', 'conexion.Conexion_Id = facturacion.Factura_Conexion_Id','left'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
		return $consulta->result();
	else return false;

	}
	
	public function buscar_lote_por_conexion($datos){

		$this->db->select('*');
		$this->db->where('conexion.Conexion_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Habilitacion', 1);
		$this->db->where_in('conexion.Conexion_Id', $datos); 
		$this->db->from('conexion');
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id','left'); 
		// //$this->db->join('conexion', 'conexion.Conexion_Id = facturacion.Factura_Conexion_Id','left'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id'); 
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
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id','left'); 
		// //$this->db->join('conexion', 'conexion.Conexion_Id = facturacion.Factura_Conexion_Id','left'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id','left'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()==1)
		return $consulta->row();
	else return false;

	}

	public function buscar_conexion($id_factura){

		$this->db->select('Factura_Conexion_Id');
		$this->db->where('facturacion.Factura_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Habilitacion', 1);
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
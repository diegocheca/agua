<?php
class Clientes_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function buscar_clientes($valor){

		$consulta = $this->db->like('Cli_RazonSocial', $valor); 
		$consulta = $this->db->get('clientes');
		return $consulta->result();

	}

	public function buscar_clientes_desde_tarea($valor){
		$this->db->from('clientes'); 
		$this->db->like('Cli_RazonSocial', $valor); 
		$this->db->join("conexion","conexion.Conexion_Cliente_Id = clientes.Cli_Id");
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1)
			return $consulta->result();
		// elseif($consulta->num_rows() >= 2)
		// {//significa que es un usuario con mas de una conexion a su nombre	
		// 	$resultado = $consulta->result();
		// 	return $resultado[0];
		// }
		else return false;

	}


}
?>
<?php
class Facturar_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	public function listar_documentos(){

		$this->db->order_by("id", "desc"); 
		$this->db->from('facturacion');
		$this->db->join('clientes','clientes.Cli_Id = facturacion.id_cliente','left');
		$consulta = $this->db->get();

		return $consulta->result();

	}
	// public function buscar_factura_por_medicion_id($id_medicion){
	// 	//his->db->order_by("id", "desc"); 
	// 	$this->db->from('facturacion');
	// 	$this->db->from('Facturar_Medicion_Id', $id_medicion );
	// 	$this->db->join('clientes','clientes.Cli_Id = facturacion.id_cliente');
	// 	$this->db->join('conexion','conexion.Conexion_Id = facturacion.Factura_Conexion_Id');
	// 	$consulta = $this->db->get();
	// 	return $consulta->row();
	// }

	
	public function buscar_mediciones_para_una_conexion($id_conexion){
		$mes = intval(date("m"))-1;
		if($mes == 0)
			$mes = 12;
		$this->db->from('medicion');
		$this->db->where('Medicion_Mes' , $mes);
		$this->db->where('Medicion_Anio' , 2018);
		$this->db->where('Medicion_Conexion_Id' ,$id_conexion);
		$consulta = $this->db->get();
		if ($consulta->num_rows() == 1)
			return $consulta->row();
		else return  false;
	}

		public function get_productos($tabla){
		$consulta = $this->db->get($tabla);

		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	} 

public function buscar_ingresos($valor, $fin = 0)
	{
		$fecha_inicio =  $valor." 00:00:00";
		if($fin  == 0 )
			$fecha_fin =  $valor." 23:59:59";
		else $fecha_fin =  $fin." 23:59:59";
		// $this->db->select_sum('Mov_Monto');
		// $this->db->select('Mov_Monto');
		$this->db->select('SUM(Mov_Monto) as Mov_Monto');

		$this->db->from('movimiento');
		$this->db->where('Mov_FechaInsert >=', $fecha_inicio);
		$this->db->where('Mov_FechaInsert <=', $fecha_fin);
		$this->db->where_in('Mov_Tipo', array('1','3'));

		$this->db->where('Mov_Borrado', 0);
		$consulta = $this->db->get();
		//	var_dump($consulta->num_rows);die();

		if($consulta->num_rows() >= 1)
		{
			$aux  = $consulta->result_array();
			if($aux[0]["Mov_Monto"] == NULL)
				{
					$aux[0]["Mov_Monto"] =0;
					return $aux	;
				} 
			return $consulta->result_array();
		}
		else return false;
	}
public function buscar_egresos($valor, $fin = 0)
	{
		$fecha_inicio =  $valor." 00:00:00";
		if($fin == 0)
			$fecha_fin =  $valor." 23:59:59";
		else $fecha_fin = $fin." 23:59:59";
		// $this->db->select_sum('Mov_Monto');
		// $this->db->select('Mov_Monto');

		$this->db->select('SUM(Mov_Monto) as Mov_Monto');
		$this->db->from('movimiento');
		$this->db->where('Mov_FechaInsert >=', $fecha_inicio);
		$this->db->where('Mov_FechaInsert <=', $fecha_fin);
		$this->db->where('Mov_Tipo', 2);

		$this->db->where('Mov_Borrado', 0);
		$consulta = $this->db->get();
		//	var_dump($consulta->num_rows);die();

		if($consulta->num_rows() >= 1)
		{
			$aux  = $consulta->result_array();
			if($aux[0]["Mov_Monto"] == NULL)
				{
					$aux[0]["Mov_Monto"] =0;
					return $aux	;
				} 
			return $consulta->result_array();
		}
		else return false;
	}

		public function buscar_ingresos_y_egresos($valor, $fin = 0)
	{
		$fecha_inicio =  $valor." 00:00:00";
		if($fin == 0)
			$fecha_fin =  $valor." 23:59:59";
		else $fecha_fin =  $fin." 23:59:59";
		$this->db->select('*');
		$this->db->from('movimiento');
		$this->db->where('Mov_FechaInsert >=', $fecha_inicio);
		$this->db->where('Mov_FechaInsert <=', $fecha_fin);
		$this->db->where('Mov_Borrado', 0);
		//$this->db->join('facturacion', 'facturacion.Id = movimiento.Mov_Pago_Id','left'); 
		$this->db->join('facturacion_nueva', 'facturacion_nueva.Factura_Id = movimiento.Mov_Factura_Id','left'); 
		$consulta = $this->db->get();
		if($consulta->num_rows() >= 1)

			return $consulta->result();
		else return false;
	}


	public function buscar_prueba($valor=8)
	{

		$this->db->select('*');
		$this->db->from('facturacion');
		$this->db->where('id >', $valor);
		$this->db->where('Factura_Borrado', 0);
		$this->db->join('clientes', 'clientes.Cli_Id = facturacion.id_cliente','left'); 
		$this->db->join('conexion', 'conexion.Conexion_Id = facturacion.Factura_Conexion_Id','left'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Id = facturacion.Factura_PlanPago_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Id = facturacion.Factura_Medicion_Id','left'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Id = facturacion.Factura_PlanMedidor_Id','left'); 

		$consulta = $this->db->get();
		return $consulta->result();
	} 

	
	
		
	public function get_factura_id($valor)
	{
		$this->db->select('*');
		$this->db->from('facturacion');
		$this->db->like('id_factura', $valor);
		$this->db->where('Factura_Borrado', 0);
		$this->db->join('clientes', 'clientes.Cli_Id = facturacion.id_cliente','left'); 
		$this->db->join('conexion', 'conexion.Conexion_Id = facturacion.Factura_Conexion_Id','left'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Id = facturacion.Factura_PlanPago_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Id = facturacion.Factura_Medicion_Id','left'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Id = facturacion.Factura_PlanMedidor_Id','left'); 

		$consulta = $this->db->get();
		return $consulta->result();
	}


public function get_factura_con_codigo($valor)
	{
		//var_dump($valor);die();
		$this->db->select('*');
		$this->db->from('facturacion');
		$this->db->where('id_factura', $valor);
		$this->db->where('Factura_Borrado', 0);
		$this->db->where('Factura_Habilitacion',1);
		$this->db->join('clientes', 'clientes.Cli_Id = facturacion.id_cliente'); 
		$this->db->join('conexion', 'conexion.Conexion_Id = facturacion.Factura_Conexion_Id'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = facturacion.Factura_Conexion_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Id = facturacion.Factura_Medicion_Id','left'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Id = facturacion.Factura_PlanMedidor_Id','left'); 

		$consulta = $this->db->get();
		return $consulta->result();
	}


	

	public function buscar_datos_crear_factura($valor)
	{
		$this->db->select('*');
		$this->db->from('clientes');
		$this->db->like('Cli_RazonSocial', $valor);
		// $this->db->where('Cli_Borrado', 0);
		// $this->db->where('Cli_Habilitacion', 1);
		// $this->db->where('PlanMedidor_Habilitacion', 1);
		// $this->db->where('PlanMedidor_Borrado', 0);
		// $this->db->where('PlanPago_Habilitacion', 1);
		// $this->db->where('PlanPago_Borrado', 0);
		 //$this->db->where('Deuda_Habilitacion', 1);
		 //$this->db->where('Deuda_Borrado', 0);
		//$this->db->where('medicion.Medicion_Mes', 9);
		$this->db->where('Conexion_Habilitacion', 1);
		$this->db->where('Conexion_Borrado', 0);
		$this->db->join('conexion', 'conexion.Conexion_Cliente_Id = clientes.Cli_Id'); 
		//$this->db->join('medicion', 'medicion.Medicion_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
		// // //$this->db->join('Bonificacion', 'Bonificacion.Medicion_Conexion_Id = conexion.Conexion_Id','left');   todavia no existe la factura
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('deuda', 'deuda.Deuda_Conexion_Id = conexion.Conexion_Id','left'); 

		$consulta = $this->db->get();
		return $consulta->result();
	}





	//Consulta los datos de la tabla de facturacion en la Base de Datos
	public function consultar_factura($valor){
		$this->db->select('id_factura');
		$this->db->where('id_factura', $valor);
		$query= $this->db->get('facturacion');

		//si no hay datos de serie en la Base de datos devolvera "nodata"
		if ($query->num_rows() > 0):
		    echo "true";
		else:
			echo "false";
		endif;

	}


	//Consulta los datos de la tabla de facturacion en la Base de Datos
	public function insertar_factura($tabla,$array){
	//var_dump($array);die();
	return $this->db->insert("facturacion",$array);	
	//return 	$this->db->insert_id();
	}


	public function insertar_factura_tres($tabla,$array){
		//var_dump($datos_a_poner);die();
		if(($array["id_cliente"] != null) && ($array["Factura_Medicion_Id"] != null))
			{
				 $this->db->insert("facturacion",$array);	
				return $this->db->insert_id();
			}
	}
/*
	public function buscar_plan_pago($id_factura)
	{
		$this->db->select('id_factura');
		$this->db->where('id_factura', $valor);
		$query= $this->db->get('facturacion');

		//si no hay datos de serie en la Base de datos devolvera "nodata"
		if ($query->num_rows() > 0):
		    echo "true";
		else:
			echo "false";
		endif;

		$this->db->join('PlanPago', 'PlanPago.PlanPago_Id = facturacion.Factura_PlanPago_Id','left'); 
	}*/
	

	//Consulta los datos de la tabla de facturacion en la Base de Datos
	public function buscar_coenxion_id_en_factura($id_factura){
		$this->db->select('Factura_Conexion_Id');
		$this->db->where('id', $id_factura);
		$query= $this->db->get('facturacion');

		//si no hay datos de serie en la Base de datos devolvera "nodata"
		if ($query->num_rows() == 1):
		    return $query->row();
		else:
			return false;
		endif;

	}
	//Se utiliza en editar documento
	public function actualizar_documento($id,$datos){

		$this->db->where('id', $id);
		$this->db->update('facturacion', $datos); 
	}

	public function actualizar_product($id){
		$this->db->where('id',$id);
		$this->db->update('items',$datos);
	}

	public function modficar_factura($codigo, $id){
	//	var_dump($codigo, $id);die();
		$this->db->where('id',$id);
		return $this->db->update('facturacion',$codigo);
	}

	public function grabar_producto($codigounico,$array){

		$datos = array(); //abre el array

		for($i=0; $i<count($array); $i++){
		    $datos[] = array(
		    	'id_factura'	=> $codigounico,
				'producto'		=> $array[$i]['producto'],
				'cantidad'		=> $array[$i]['cantidad'],
				'precio_unit'	=> $array[$i]['precio_unit'],
				'precio'		=> $array[$i]['precio']
		    );
		}

		$this->db->insert_batch('items',$datos);

	}
	

	public function buscar_datos_crear_factura_conexion_id($Conexion_Id)
	{
		$this->db->select('*');
		$this->db->from('conexion');
		$this->db->where('conexion.Conexion_Id', $Conexion_Id);
		$this->db->where('conexion.Conexion_Habilitacion', 1);
		$this->db->where('conexion.Conexion_Borrado', 0);
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id '); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('deuda', 'deuda.Deuda_Conexion_Id = conexion.Conexion_Id','left'); 
		//$this->db->join('Bonificacion', 'Bonificacion.Bonificacion_Conexion_Id = conexion.Conexion_Id','left'); 
		$consulta = $this->db->get();
		return $consulta->row();
	}
}
?>
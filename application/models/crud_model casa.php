<?php
class Crud_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	//++++++++++++++++++++++++++
	//Devuelve todos lo resultdos de una Tabla
	public function get_data($tabla){
		$consulta = $this->db->get($tabla);
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}
public function join_nivel_dios_dos(){
		//$this->db->distinct('clientes.Cli_Id');
		//$this->db->select('DISTINCT clientes.Cli_Id');
		$this->db->select('Cli_Id,Cli_RazonSocial,Conexion_Id,Conexion_UnionVecinal,Conexion_Categoria,Conexion_Deuda, Conexion_Acuenta,Conexion_Sector,Conexion_Latitud, id, id_factura, Factura_SubTotal,Factura_Total,Factura_Vencimiento1,Factura_Vencimiento2, Medicion_Id, Medicion_Mes,Medicion_Anio, Medicion_Anterior , Medicion_Actual , Medicion_Basico, Medicion_Excedente, Medicion_Importe,Medicion_Mts, Cli_DomicilioSuministro, Pago_Id, Pago_Monto, Pago_Total, Conexion_DomicilioSuministro');
		$this->db->group_by('conexion.Conexion_Id');
		$this->db->where("conexion.Conexion_Sector", "A");
		$this->db->from("conexion");
		$this->db->join("clientes","clientes.Cli_Id  = conexion.Conexion_Cliente_Id");
		$this->db->join("facturacion","facturacion.Factura_Conexion_Id = conexion.Conexion_Id","left");
		$this->db->join("pago","pago.Pago_Facturacion_Id = facturacion.id","left");
		$this->db->join("medicion","medicion.Medicion_Conexion_Id = conexion.Conexion_Id","left");
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result_array();
		else:
			return false;
		endif;
	}
	public function traer_facturas_por_barrio($sector){
		//$this->db->distinct('clientes.Cli_Id');
		//$this->db->select('DISTINCT clientes.Cli_Id');
		$this->db->select('Cli_Id,Cli_RazonSocial,Conexion_Id,Conexion_UnionVecinal,Conexion_Categoria,Conexion_Deuda, Conexion_Acuenta,Conexion_Sector,Conexion_Latitud, id, id_factura, Factura_SubTotal,Factura_Total,Factura_Vencimiento1,Factura_Vencimiento2, Medicion_Id, Medicion_Mes,Medicion_Anio, Medicion_Anterior , Medicion_Actual , Medicion_Basico, Medicion_Excedente, Medicion_Importe,Medicion_Mts, Cli_DomicilioSuministro, Pago_Id, Pago_Monto, Pago_Total, Conexion_DomicilioSuministro');
		$this->db->group_by('conexion.Conexion_Id');
		$this->db->where("conexion.Conexion_Sector", $sector);
		$this->db->from("conexion");
		$this->db->join("clientes","clientes.Cli_Id  = conexion.Conexion_Cliente_Id");
		$this->db->join("facturacion","facturacion.Factura_Conexion_Id = conexion.Conexion_Id","left");
		$this->db->join("pago","pago.Pago_Facturacion_Id = facturacion.id","left");
		$this->db->join("medicion","medicion.Medicion_Conexion_Id = conexion.Conexion_Id","left");
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result_array();
		else:
			return false;
		endif;
	}


	public function traer_facturas_por_barrio_nuevo($sector = -1, $mes = -1, $ano= -1, $id_conexion = -1, $pagado = -1 ){
		$mes = intval($mes);
		$ano = intval($ano);
		$id_conexion = intval($id_conexion);
		if($sector == "-1")
			$sector = intval(-1);
		//var_dump($sector , $mes , $ano, $id_conexion, $pagado);die();

		//$this->db->distinct('clientes.Cli_Id');
		//$this->db->select('DISTINCT clientes.Cli_Id');
		$this->db->select('Cli_Id,Cli_RazonSocial,Conexion_Id,Conexion_UnionVecinal,Conexion_Categoria,Conexion_Deuda, Factura_Riego, Conexion_Acuenta,Conexion_Sector,Conexion_Latitud, Factura_Id as id, Factura_CodigoBarra as id_factura, Factura_Acuenta, Factura_SubTotal as Factura_SubTotal,Factura_Total,Factura_Vencimiento1,Factura_Vencimiento1_Precio, Factura_Vencimiento2,Factura_Vencimiento2_Precio, Factura_TarifaSocial, Factura_CuotaSocial,Factura_PM_Cant_Cuotas, Factura_PM_Cuota_Actual, Factura_PM_Cuota_Precio, Factura_PP_Cant_Cuotas, Factura_PP_Cuota_Actual, Factura_PPC_Precio, Factura_Bonificacion,  Factura_Conexion_Id as Medicion_Id, Factura_Mes as Medicion_Mes,Factura_Año as Medicion_Anio, Factura_MedicionAnterior as Medicion_Anterior ,Factura_MedicionActual as Medicion_Actual , Factura_Basico as Medicion_Basico,Factura_Excedentem3 as  Medicion_Excedente,Factura_ExcedentePrecio as Medicion_Importe,Factura_FechaEmision as Medicion_Mts, Cli_DomicilioSuministro, Factura_PagoLugar as Pago_Id, Factura_PagoMonto as Pago_Monto, Factura_Timestamp as Pago_Total, Conexion_DomicilioSuministro');
		$this->db->group_by('conexion.Conexion_Id');
		$this->db->where('conexion.Conexion_Borrado', 0);
		if($mes != -1)
			$this->db->where('facturacion_nueva.Factura_Mes', $mes); 
		if($ano != -1)
			$this->db->where('facturacion_nueva.Factura_Año', $ano); 
		if($sector != -1)
			$this->db->where("conexion.Conexion_Sector", $sector);
		if($id_conexion != -1)
			$this->db->where("conexion.Conexion_Id", $id_conexion);
		if($pagado != -1) 
			$this->db->where("facturacion_nueva.Factura_PagoLugar !=", null);
		$this->db->from("conexion");
		$this->db->join("clientes","clientes.Cli_Id  = conexion.Conexion_Cliente_Id");
		$this->db->join("facturacion_nueva","facturacion_nueva.Factura_Conexion_Id = conexion.Conexion_Id","left");
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result_array();
		else:
			return false;
		endif;
	}


public function traer_facturas_por_barrio_nuevo_todocampo($sector = -1, $mes = -1, $ano= -1, $id_conexion = -1, $pagado = -1 ){
		$mes = intval($mes);
		$ano = intval($ano);
		$id_conexion = intval($id_conexion);
		if($sector == "-1")
			$sector = intval(-1);
		//var_dump($sector , $mes , $ano, $id_conexion, $pagado);die();

		//$this->db->distinct('clientes.Cli_Id');
		//$this->db->select('DISTINCT clientes.Cli_Id');
		$this->db->select('*');
		$this->db->group_by('conexion.Conexion_Id');
		$this->db->where('conexion.Conexion_Borrado', 0);
		if($mes != -1)
		{
			$this->db->where('facturacion_nueva.Factura_Mes', $mes); 
		}
		if($ano != -1)
		{
			$this->db->where('facturacion_nueva.Factura_Año', $ano); 
		}
		if($sector != -1)
			$this->db->where("conexion.Conexion_Sector", $sector);
		if($id_conexion != -1)
			$this->db->where("conexion.Conexion_Id", $id_conexion);
		if($pagado != -1) 
			$this->db->where("facturacion_nueva.Factura_PagoLugar !=", null);
		$this->db->from("conexion");
		$this->db->join("clientes","clientes.Cli_Id  = conexion.Conexion_Cliente_Id");
		//$this->db->join("medicion","medicion.Medicion_Conexion_Id = conexion.Conexion_Id");
		$this->db->join("facturacion_nueva","facturacion_nueva.Factura_Conexion_Id = conexion.Conexion_Id","left");
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result_array();
		else:
			return false;
		endif;
	}
	public function get_data_dos_campos($tabla,$campo,$valor, $campo1,$valor1){
		$this->db->where($campo, $valor);
		$this->db->where($campo1, $valor1);
		$consulta = $this->db->get($tabla);
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}


//Devulve un solo resultado
	public function get_data_row_dos_campos($tabla,$campo,$valor, $campo1,$valor1){
		$this->db->where($campo, $valor);
		$this->db->where($campo1, $valor1);
		$consulta = $this->db->get($tabla);
		if ($consulta->num_rows() > 0):
			return $consulta->row();
		else:
			return false;
		endif;
	}

	public function get_data_row_tres_campos($tabla,$campo,$valor, $campo1,$valor1, $campo2,$valor2){
		$this->db->where($campo, $valor);
		$this->db->where($campo1, $valor1);
		$consulta = $this->db->get($tabla);
		if ($consulta->num_rows() > 0):
			return $consulta->row();
		else:
			return false;
		endif;
	}


	

	


	
//INICIO DEL CALENDARIO
	public function getEventos(){
		$this->db->select('OrdenTrabajo_Id id, OrdenTrabajo_Tarea title, OrdenTrabajo_FechaInicio start, OrdenTrabajo_FechaFin end, OrdenTrabajo_Estado url,  OrdenTrabajo_Porcentaje porcentaje, OrdenTrabajo_Observacion observacion, OrdenTrabajo_CodigoMaterial1 materiales, OrdenTrabajo_Timestamp creada');
		$this->db->where('OrdenTrabajo_Borrado',0);
		$this->db->from('ordentrabajo');

		return $this->db->get()->result();
	}
	public function updEvento($param){
		$campos = array(
			'OrdenTrabajo_FechaInicio' => $param['fecini'],
			'OrdenTrabajo_FechaFin' => $param['fecfin']
			);

		$this->db->where('OrdenTrabajo_Id',$param['id']);
		$this->db->update('ordentrabajo',$campos);

		if ($this->db->affected_rows() == 1) {
			return 1;
		}else{
			return 0;
		}
	}

	public function deleteEvento($id){
		$arrayName = array('OrdenTrabajo_Borrado' =>1);
		$this->db->where('OrdenTrabajo_Id', $id);
		return $this->db->update('ordentrabajo',$arrayName);
	}

	public function updEvento2($param){
		$campos = array(
			'OrdenTrabajo_Tarea' => $param['nome'],
			'OrdenTrabajo_Estado' => $param['web']
			);

		$this->db->where('OrdenTrabajo_Id',$param['id']);
		$this->db->update('ordentrabajo',$campos);

		if ($this->db->affected_rows() == 1) {
			return 1;
		}else{
			return 0;
		}
	}

	//FIN DEL CALENDARIO
	
	public function get_data_sin_borrados($tabla, $campo){
		$this->db->where($campo,0);
		$consulta = $this->db->get($tabla);

		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}


	public function ordenes_trabajo_terminadas(){
		$this->db->where("OrdenTrabajo_Borrado",1);
		$this->db->where("OrdenTrabajo_Estado",1);
		$consulta = $this->db->get("ordenTrabajo");

		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function get_tareas_incompletas($tabla, $campo){
		$this->db->where($campo,0);
	//	$this->db->where("Tarea_Borrado",0);
		$consulta = $this->db->get($tabla);
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function get_data_sin_borrados_movimientos($tabla, $campo, $tipo){
		$this->db->where($campo,0);
		$this->db->where("Mov_Tipo",$tipo);
		$consulta = $this->db->get($tabla);
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function get_data_sin_borrados_bonif_pendiente(){
		$this->db->where("Bonificacion_Borrado",0);
		$this->db->where("Bonificacion_Aprobada",0);
		$this->db->where("Bonificacion_Pendiente",1);
		$consulta = $this->db->get("bonificacion");
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function get_data_sin_borrados_bonif_aprobadas(){
		$this->db->where("Bonificacion_Borrado",0);
		$this->db->where("Bonificacion_Aprobada",1);
		$this->db->where("Bonificacion_Pendiente",1);
		$consulta = $this->db->get("bonificacion");
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function get_data_sin_borrados_bonif_otorgadas(){
		$this->db->where("Bonificacion_Borrado",0);
		$this->db->where("Bonificacion_Aprobada",1);
		$this->db->where("Bonificacion_Pendiente",0);
		$consulta = $this->db->get("bonificacion");
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function suma_data($tabla, $campo, $campo_borrado, $campo_where, $valor_where){
		//$this->db->select_sum($campo);
		$this->db->select($campo);
		$this->db->where($campo_where, $valor_where);
		$this->db->where($campo_borrado,0);
		$consulta = $this->db->get($tabla);

		if ($consulta->num_rows() > 0):
			$valor = 0;
			$todos = $consulta->result();
			foreach ($todos as $key ) 
				$valor += $key->Deuda_Monto;
			return $valor;
		else:
			return false;
		endif;
	}


	public function buscar_personas_conexiones()
	{
		$this->db->select('Cli_Id, Cli_RazonSocial, Cli_NroDocumento,Conexion_Id');
		$this->db->from("clientes");
		$this->db->where("Cli_Borrado",0);
		//$this->db->where("Conexion_Borrado",0);

		$this->db->join("conexion","clientes.Cli_Id = conexion.Conexion_Cliente_Id","left");
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}


	public function get_data_join_sin_borrados($tabla1, $tabla2, $campo_borrado = 0, $campo1, $campo2){
		$string1 = $tabla1.".".$campo1." = ".$tabla2.".".$campo2;
		$this->db->select("*");
		$this->db->from($tabla1);
		if( ($campo_borrado != 0) && ($campo_borrado != null))
			$this->db->where($campo_borrado,0);

		$this->db->join($tabla2,$string1);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function get_data_join_sin_borrados_clientes($tabla1, $tabla2, $campo_borrado, $campo1, $campo2,$id){
		$string1 = $tabla1.".".$campo1." = ".$tabla2.".".$campo2;
		$this->db->select("*");
		$this->db->from($tabla1);
		$this->db->where($campo_borrado,0);
		$this->db->where($campo1,$id);
		
		$this->db->join($tabla2,$string1);
		$consulta = $this->db->get();
		if ($consulta->num_rows() == 1):
			return $consulta->row();
		else:
			return false;
		endif;
	}


public function get_conexion_sin_borrados($tabla1, $tabla2, $campo_borrado, $campo1, $campo2){
		$this->db->from("conexion");
		$this->db->where("Conexion_Borrado",0);
		$this->db->join("clientes","clientes.Cli_Id = conexion.Conexion_Cliente_Id");
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	
	public function get_conexion_id_sin_borrados($tabla1, $campo1){
		$this->db->from("conexion");
		$this->db->where("Conexion_Borrado",0);
		$this->db->where("Conexion_Id",$campo1);
		$this->db->join("clientes","clientes.Cli_Id = conexion.Conexion_Cliente_Id");
		$consulta = $this->db->get();
		if ($consulta->num_rows() == 1):
			return $consulta->row();
		else:
			return false;
		endif;
	}

	public function get_medicion_id_sin_borrados($id){
		$this->db->from("medicion");
		$this->db->where("Medicion_Borrado",0);
		$this->db->where("Medicion_Id",$id);
		$this->db->join("conexion","conexion.Conexion_Id = medicion.Medicion_Conexion_Id","left");
		$this->db->join("clientes","clientes.Cli_Id = conexion.Conexion_Cliente_Id","left");
		$consulta = $this->db->get();
		if ($consulta->num_rows() == 1):
			return $consulta->row();
		else:
			return false;
		endif;
	}

	public function get_bonificacion_id_sin_borrados($id){
		$this->db->from("bonificacion");
		$this->db->where("bonificacion.Bonificacion_Borrado",0);
		$this->db->where("bonificacion.Bonificacion_Id",$id);
		$this->db->join("facturacion","facturacion.id = bonificacion.Bonificacion_Factura_Id");
		$this->db->join("conexion","conexion.Conexion_Id = facturacion.Factura_Conexion_Id");
		$this->db->join("clientes","clientes.Cli_Id = conexion.Conexion_Cliente_Id","left");
		$consulta = $this->db->get();
		if ($consulta->num_rows() == 1):
			return $consulta->row();
		else:
			return false;
		endif;
	}




public function join_nivel_dios(){
		//$this->db->distinct('clientes.Cli_Id');
		//$this->db->select('DISTINCT `clientes.Cli_Id`');
		$this->db->select('*');
		$this->db->group_by('clientes.Cli_Id');
		$this->db->from("clientes");
		$this->db->join("conexion","conexion.Conexion_Cliente_Id = clientes.Cli_Id","left");
		$this->db->join("facturacion","facturacion.Factura_Conexion_Id = conexion.Conexion_Id","left");
		$this->db->join("medicion","medicion.Medicion_Conexion_Id = conexion.Conexion_Id","left");
		$this->db->join("planpago","planpago.PlanPago_Conexion_Id = conexion.Conexion_Id","left");
		$this->db->join("deuda","deuda.Deuda_Conexion_Id = conexion.Conexion_Id","left");
		$this->db->join("medidor","medidor.Medidor_Conexion_Id = conexion.Conexion_Id","left");
		//$this->db->join("pago","pago.Pago_Cli_Id = clientes.Cli_Id","left");
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result();
		else:
			return false;
		endif;
	}



	public function buscar_conexion_a_imprmir(){
		$this->db->select("Conexion_Id, Cli_RazonSocial, Cli_Id, Cli_NroDocumento");
		$this->db->from("conexion");
		$this->db->where("Conexion_Borrado",0);
		$this->db->where("Conexion_Habilitacion",1);
		$this->db->join("clientes","clientes.Cli_Id = conexion.Conexion_Cliente_Id");
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function buscar_conexion_a_imprmir_nuevo(){
		$this->db->select("Conexion_Sector");
		$this->db->group_by('Conexion_Sector');
		$this->db->from("conexion");
		$this->db->where("Conexion_Borrado",0);
		$this->db->where("Conexion_Habilitacion",1);
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result();
		else:
			return false;
		endif;
	}
public function buscar_codigos(){
		$this->db->select("Codigo_Id, Codigo_Numero, Codigo_Descripcion");
		$this->db->from("codigos");
		$this->db->where("Codigo_Borrado",0);
		$this->db->where("Codigo_Habilitacion",1);
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result();
		else:
			return false;
		endif;
	}


	public function get_data_sectores(){
		$this->db->distinct();
		$this->db->select("Conexion_Sector");
		$this->db->from("conexion");
		$this->db->where("Conexion_Habilitacion",1);
		$this->db->where("Conexion_Borrado",0);
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	//Devulve un solo resultado
	


	//Devulve un solo resultado
	public function get_data_row($tabla,$campo,$valor){
		$this->db->where($campo, $valor);
		$consulta = $this->db->get($tabla);

		if ($consulta->num_rows() > 0):
			return $consulta->row();
		else:
			return false;
		endif;
	}


	public function insertar_factura_tres($array){
	//var_dump($array);die();
	return $this->db->insert("facturacion",$array);	
	}
	

	//Devulve un solo resultado
	public function get_data_row_sin_borrado($tabla,$campo,$campo_borrado,$valor){
		$this->db->where($campo, $valor);
		$this->db->where($campo_borrado, 0);
		$consulta = $this->db->get($tabla);

		if ($consulta->num_rows() > 0):
			return $consulta->row();
		else:
			return false;
		endif;
	}

	
	public function get_sectores_query($sectores, $mes,$anio){
		$this->db->select('*');

		$this->db->from("conexion");
		//$this->db->distinct('Conexion.Conexion_Id');  
		//$this->db->limit(1);  
		$this->db->ORDER_BY("conexion.Conexion_UnionVecinal","asc");
		$this->db->ORDER_BY("medicion.Medicion_Timestamp","desc");
		$this->db->ORDER_BY("medicion.Medicion_Conexion_Id","desc");
		//poner el orden de la conxion
		$this->db->or_where_in("Conexion_Sector", $sectores);
		$this->db->where("medicion.Medicion_Mes", $mes);
		$this->db->where("medicion.Medicion_Anio", $anio);
		$this->db->join('medicion', 'medicion.Medicion_Conexion_Id = Conexion.Conexion_Id');
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 1 )
			return $consulta->result();
		elseif ($consulta->num_rows() ==  1 )
				return $consulta->row();
		else return false;
	}

	public function get_sectores_query_nuevo($sectores, $mes,$anio){
	
		$this->db->select('
			Conexion_Id,
			Conexion_UnionVecinal,
			Conexion_Categoria,
			Conexion_Acuenta,
			Conexion_Sector,
			Factura_Id as Medicion_Id, 
			Factura_CodigoBarra as id_factura, 
			Factura_Mes as Medicion_Mes,
			Factura_Año as Medicion_Anio,
			Factura_MedicionAnterior as Medicion_Anterior ,
			Factura_MedicionActual as Medicion_Actual , 
			Factura_Basico as Medicion_Basico,
			Factura_Excedentem3 as  Medicion_Excedente,
			Factura_ExcedentePrecio as Medicion_Importe,
			Factura_FechaEmision as Medicion_Mts'
			);
		
		//	$this->db->from("conexion");
		$this->db->from("facturacion_nueva");

		//$this->db->distinct('Conexion.Conexion_Id');  
		//$this->db->limit(1);  
		$this->db->ORDER_BY("conexion.Conexion_UnionVecinal","asc");
		$this->db->ORDER_BY("facturacion_nueva.Factura_MedicionTimestamp","desc");
		$this->db->ORDER_BY("conexion.Conexion_Id","desc");
		//poner el orden de la conxion
		$this->db->or_where_in("Conexion_Sector", $sectores);
		$this->db->where("facturacion_nueva.Factura_Mes", $mes);
		$this->db->where("facturacion_nueva.Factura_Año", $anio);
		$this->db->join('conexion', 'conexion.Conexion_Id = facturacion_nueva.Factura_Conexion_Id ');
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 1 )
			return $consulta->result();
		elseif ($consulta->num_rows() ==  1 )
				return $consulta->row();
		else return false;
	}




	public function get_sectores_query_corregir($sectores, $mes,$anio){
		$this->db->select('*');
		$this->db->from("conexion");
		$this->db->ORDER_BY("conexion.Conexion_UnionVecinal","asc");
		$this->db->ORDER_BY("medicion.Medicion_Timestamp","desc");
		$this->db->ORDER_BY("medicion.Medicion_Conexion_Id","desc");
		$this->db->or_where_in("Conexion_Sector", $sectores);
		$this->db->where("medicion.Medicion_Mes", $mes);
		$this->db->where("medicion.Medicion_Anio", $anio);
		$this->db->join('medicion', 'medicion.Medicion_Conexion_Id = Conexion.Conexion_Id');
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 1 )
			return $consulta->result();
		elseif ($consulta->num_rows() ==  1 )
				return $consulta->row();
		else return false;
	}
  
	//Devulve un solo resultado
	public function get_data_row_sin_borrado_en_array($tabla,$campo,$campo_borrado,$valor){
		$this->db->where($campo, $valor);
		$this->db->where($campo_borrado, 0);
		$consulta = $this->db->get($tabla);

		if ($consulta->num_rows() > 0):
			return $consulta->row_array();
		else:
			return false;
		endif;
	}



	//Devuelve multiples resultados
	public function get_data_result($tabla,$campo,$valor){
		$this->db->where($campo, $valor);
		$consulta = $this->db->get($tabla);

		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	
	public function traer_mediciones_del_mes_anterior($mes_buscado , $anio_buscado){
		$this->db->where("Medicion_Mes", $mes_buscado);
		$this->db->where("Medicion_Anio", $anio_buscado);
		$consulta = $this->db->get("medicion");
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function traer_mediciones_del_mes_anterior_no_habilitadas($mes_buscado , $anio_buscado){
		$this->db->where("Medicion_Mes", $mes_buscado);
		$this->db->where("Medicion_Anio", $anio_buscado);
		$this->db->where("Medicion_Habilitacion", 0);
		$consulta = $this->db->get("medicion");
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}


public function traer_facturas_del_mes_anterior($mes_buscado , $anio_buscado){
		$this->load->model('facturar_model');
		$this->db->from("medicion");
		$this->db->where("medicion.Medicion_Mes", $mes_buscado);
		$this->db->where("conexion.Conexion_Sector", "Aberanstain");
		$this->db->where("medicion.Medicion_Anio", $anio_buscado);
		$this->db->from('facturacion.Facturar_Medicion_Id', $id_medicion );
		$this->db->join('facturacion','facturacion.Facturar_Medicion_Id = medicion.Medicion_Id');
		$this->db->join('clientes','clientes.Cli_Id = facturacion.id_cliente');
		$this->db->join('conexion','conexion.Conexion_Id = facturacion.Factura_Conexion_Id');
		$this->db->join('deuda','deuda.Deuda_Conexion_Id = facturacion.Factura_Conexion_Id', 'left');
		$this->db->join('pago','pago.Pago_Facturacion_Id = facturacion.id','left');
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1)
			return  $consulta->result();
		else
			return false;
	}
	//Devuelve multiples resultados En ARRAY
	public function get_data_result_array($tabla,$campo,$valor){
		$this->db->where($campo, $valor);
		$consulta = $this->db->get($tabla);

		if ($consulta->num_rows() > 0):
			return $consulta->result_array();
		else:
			return false;
		endif;
	}

	//Devuelve multiples resultados
	public function get_cantidad_bajas($tabla){
		$this->db->where("Cli_Borrado",1);
		$consulta = $this->db->get($tabla);
		return $consulta->num_rows();
	}
	//Guardar datos en la BD
	public function insert_data($tabla,$data){
		$this->db->insert($tabla,$data);
		return $this->db->insert_id();


	}

	public function update_data($datos, $id, $tabla,$campo){
		$this->db->where($campo, $id);
		return $this->db->update($tabla, $datos);
	}

public function update_data_tres_campos($datos, $id, $tabla,$campo, $campo_1, $valor_1, $campo_2, $valor_2){
		$this->db->where($campo, $id);
		$this->db->where($campo_1, $valor_1);
		$this->db->where($campo_2, $valor_2);
		return $this->db->update($tabla, $datos);
	}

public function deshabilitar_todas_las_deudas_anteriores_conexion_id($id){
		$arrayName = array('Deuda_Habilitacion' => 0);
		$this->db->where("Deuda_Conexion_Id", $id);
		return $this->db->update("deuda", $arrayName);
	}

		//Modificar datos en la BD
	public function modificar_datos_clientes($datos, $id, $tabla,$campo){
		$this->db->where($campo, $id);
		return $this->db->update($tabla, $datos);
	}

public function borrar_cliente($datos, $id, $tabla,$campo){
		$this->db->where($campo, $id);
		return $this->db->update($tabla, $datos);
	}


	public function modificar_conexion($datos, $id, $tabla,$campo){
		$this->db->where($campo, $id);
		return $this->db->update($tabla, $datos);
	}

public function borrar_conexion($datos, $id, $tabla,$campo){
		$this->db->where($campo, $id);
		return $this->db->update($tabla, $datos);
	}

public function modificar_habilitacion($datos, $id){
		$this->db->where("Habilitacion_Id", $id);
		return $this->db->update("habilitacion", $datos);
	}


	//Elimina datos en una tabla
	public function delete_data($arraTabla,$arrayID){}

	//++++++++++++++++++++++++++

	//Obtener el id Maximo de un campo
	public function get_max_id($tabla, $campo){
		$this->db->select_max($campo);
		$consulta = $this->db->get($tabla);

		if ($consulta->num_rows() > 0):
			return $consulta->row_array();
		else:
			return false;
		endif;
	}

	public function buscar_conexiones($valor){

		$this->db->select('*');
		$this->db->where('Conexion_Id', $valor);
		$this->db->from('Conexion');
		$this->db->join('clientes', 'clientes.Cli_Id = Conexion.Conexion_Cliente_Id','left');
		$consulta = $this->db->get();
		return $consulta->result();

	}
	
		public function buscar_conexiones_desde_plan_pago($valor){

		$this->db->select('*');
		$this->db->like('Conexion_Id', $valor);
		$this->db->where('conexion.Conexion_Borrado',0);
		$this->db->from('conexion');
		$this->db->join('clientes', 'clientes.Cli_Id = Conexion.Conexion_Cliente_Id','left');
		$consulta = $this->db->get();
		return $consulta->result();

	}
	

	public function buscar_medicion_anterior($valor){
		$this->db->select('*');
		$this->db->from('medicion');
		$this->db->where('Medicion_Conexion_Id', $valor);
		$this->db->where('Medicion_Borrado', 0);
		$this->db->limit(1);
		$this->db->order_by('Medicion_Timestamp', 'desc');
		$consulta = $this->db->get();
		if($consulta->num_rows()== 1)
			return $consulta->row();
		else return false;
	}

	public function buscar_orden_conexion($sector)
	{
		$this->db->select("Conexion_Id, Conexion_UnionVecinal");
		$this->db->ORDER_BY("Conexion_UnionVecinal", "ASC");
		$this->db->where("Conexion_Sector", $sector);
		$this->db->where("Conexion_Habilitacion", 1);
		$this->db->where("Conexion_Borrado", 0);
		$this->db->from("conexion");
		$consulta = $this->db->get();
			if($consulta->num_rows()>= 1)
				return $consulta->result();
			else return false;
	}
	public function get_mediciones_para_un_mes($mes)
	{
		$this->db->select("Medicion_Id,Medicion_Conexion_Id,Medicion_Mes,Medicion_Anio,Medicion_Anterior,Medicion_Actual,Medicion_Mts,Medicion_Excedente");
		$this->db->ORDER_BY("Medicion_Id", "DESC");
		$this->db->where("Medicion_Mes", $mes);
		$this->db->where("Medicion_Habilitacion", 1);
		$this->db->where("Medicion_Borrado", 0);
		$this->db->from("medicion");
		$consulta = $this->db->get();
			if($consulta->num_rows()>= 1)
				return $consulta->result();
			else return false;
	}
	
	public function get_mediciones_para_un_mes_con_join($mes,$anio)
	{
		$this->db->select("Medicion_Id,Cli_RazonSocial,Conexion_Categoria,Conexion_Sector,Medicion_Observacion, Medicion_Conexion_Id,Medicion_Mes,Medicion_Anio,Medicion_Anterior,Medicion_Actual,Medicion_Mts,Medicion_Excedente,");
		$this->db->ORDER_BY("Medicion_Id", "DESC");
		$this->db->where("Medicion_Mes", $mes);
		$this->db->where("Medicion_Anio", $anio);
		$this->db->where("Medicion_Habilitacion", 1);
		$this->db->where("Medicion_Borrado", 0);
		$this->db->join("conexion","medicion.Medicion_Conexion_Id = conexion.Conexion_Cliente_Id","left");
		$this->db->join("clientes","clientes.Cli_Id = conexion.Conexion_Cliente_Id","left");
		$this->db->from("medicion");
		$consulta = $this->db->get();
			if($consulta->num_rows()>= 1)
				return $consulta->result();
			else return false;
	}


	public function get_mediciones_para_un_mes_id($mes,$anio, $id)
	{
		$this->db->where("Medicion_Mes", $mes);
		$this->db->where("Medicion_Anio", $anio);
		$this->db->where("Medicion_Conexion_Id", $id);
		$this->db->where("Medicion_Habilitacion", 1);
		$this->db->where("Medicion_Borrado", 0);
		$this->db->from("medicion");
		$consulta = $this->db->get();
			if($consulta->num_rows()== 1)
				return $consulta->result();
			else return false;
	}

	public function get_mediciones_a_aprobar($mes)
	{
		$this->db->where("Medicion_Mes", $mes);
		$this->db->where("Medicion_Habilitacion", 0);
		$this->db->where("Medicion_Observacion", 'Se recomienda revisar este consumo porque excede el promedio');
		$this->db->where("Medicion_Borrado", 0);
		$this->db->from("medicion");
		$consulta = $this->db->get();
			if($consulta->num_rows()>= 1)
				return $consulta->result();
			else return false;
	}

	public function borrar_medicion_para_siempre($id)
	{
		$this->db->where('Medicion_Id', $id);
		return $this->db->delete('medicion');
	}

	public function borrar($table, $campo, $valor)
	{
		$this->db->where($campo, $valor);
		return $this->db->delete($table);
	}

	public function conexiones_por_sector($datos){
		$this->db->select('*');
		$this->db->group_by('Conexion_Id');
		$this->db->where('conexion.Conexion_Borrado', 0);
		$this->db->where('conexion.Conexion_Habilitacion', 1);
		$this->db->where_in('conexion.Conexion_Sector', $datos); 
		$this->db->from('conexion');
		//$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id','left'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;
	}



}
?>
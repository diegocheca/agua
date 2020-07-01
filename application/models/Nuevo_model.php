<?php
class Nuevo_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function traer_movimentos(){
		$fecha_inicio =  "2018-04-01 00:00:00";
		$fecha_fin =  "2018-05-15 23:59:59";
		$this->db->select('*');
		$this->db->from('movimiento');
		$this->db->where('Mov_Timestamp >=', $fecha_inicio);
		$this->db->where('Mov_Timestamp <=', $fecha_fin);
		$this->db->where('Mov_Borrado', 0);
		$this->db->join('facturacion_nueva', 'facturacion_nueva.Factura_Id = movimiento.Mov_Factura_Id','left'); 
		$consulta = $this->db->get();
		if($consulta->num_rows() >= 1)

			return $consulta->result();
		else return false;
	}
	public function buscar_lote_por_conexion($datos, $mes, $anio){
		$this->db->select('Cli_Id,Cli_RazonSocial,Cli_Cuit,Conexion_Id,Conexion_UnionVecinal,Conexion_Categoria,Conexion_Deuda, Factura_Riego, Conexion_Acuenta,Conexion_Sector,Conexion_Latitud, Factura_Id as id, Factura_CodigoBarra as id_factura, Factura_Acuenta, Factura_SubTotal,Factura_Total,Factura_Vencimiento1,Factura_Vencimiento1_Precio, Factura_Vencimiento2,Factura_Vencimiento2_Precio, Factura_TarifaSocial, Factura_CuotaSocial,Factura_PM_Cant_Cuotas, Factura_PM_Cuota_Actual, Factura_PM_Cuota_Precio, Factura_PP_Cant_Cuotas, Factura_PP_Cuota_Actual, Factura_PPC_Precio, Factura_Bonificacion,  Factura_Conexion_Id as Medicion_Id, Factura_Mes as Medicion_Mes,Factura_Año as Medicion_Anio, Factura_MedicionAnterior as Medicion_Anterior ,Factura_MedicionActual as Medicion_Actual , Factura_Basico as Medicion_Basico,Factura_Excedentem3 as  Medicion_Excedente,Factura_ExcedentePrecio as Medicion_Importe,Factura_FechaEmision as Medicion_Mts, Cli_DomicilioSuministro, Factura_PagoLugar as Pago_Id, Factura_PagoMonto as Pago_Monto, Factura_Timestamp as Pago_Total, Conexion_DomicilioSuministro');
		$this->db->where_in('facturacion_nueva.Factura_Conexion_Id', $datos); 
		$this->db->where('facturacion_nueva.Factura_Mes', $mes); 
		$this->db->where('facturacion_nueva.Factura_Año', $anio); 
		$this->db->where('conexion.Conexion_Borrado', 0);
		$this->db->where('facturacion_nueva.Factura_Habilitacion', 1);
		$this->db->from('facturacion_nueva');
		$this->db->join('conexion', 'conexion.Conexion_Id = facturacion_nueva.Factura_Conexion_Id'); 
		$this->db->join('clientes', 'clientes.Cli_Id = facturacion_nueva.Factura_Cli_Id'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;
	}
	public function buscar_lote_por_sectores_anterior($datos, $mes, $anio){
		$this->db->select('Cli_Id,Cli_RazonSocial,Cli_Cuit,Conexion_Id,Conexion_UnionVecinal,Conexion_Categoria,Conexion_Deuda, Factura_Riego, Conexion_Acuenta,Conexion_Sector,Conexion_Latitud, Factura_Id as id, Factura_CodigoBarra as id_factura, Factura_Acuenta, Factura_SubTotal,Factura_Total,Factura_Vencimiento1,Factura_Vencimiento1_Precio, Factura_Vencimiento2,Factura_Vencimiento2_Precio, Factura_Multa, Factura_Deuda, Factura_TarifaSocial, Factura_CuotaSocial,Factura_PM_Cant_Cuotas, Factura_PM_Cuota_Actual, Factura_PM_Cuota_Precio, Factura_PP_Cant_Cuotas, Factura_PP_Cuota_Actual, Factura_PPC_Precio, Factura_Bonificacion,  Factura_Conexion_Id as Medicion_Id, Factura_Mes as Medicion_Mes,Factura_Año as Medicion_Anio, Factura_MedicionAnterior as Medicion_Anterior ,Factura_MedicionActual as Medicion_Actual , Factura_Basico as Medicion_Basico,Factura_Excedentem3 as  Medicion_Excedente,Factura_ExcedentePrecio as Medicion_Importe,Factura_FechaEmision as Medicion_Mts, Cli_DomicilioSuministro, Factura_PagoLugar as Pago_Id, Factura_PagoMonto as Pago_Monto, Factura_Timestamp as Pago_Total, Conexion_DomicilioSuministro');
		$this->db->ORDER_BY("conexion.Conexion_UnionVecinal", "ASC");
		$this->db->group_by('Conexion_Id');
		$this->db->where('conexion.Conexion_Borrado', 0);
		if( ($datos != -1) && ($datos))
			$this->db->where_in('conexion.Conexion_Sector', $datos); 
		if( ($mes != -1) && ($mes))
			$this->db->where('facturacion_nueva.Factura_Mes', $mes); 
		if( ($anio != -1) && ($anio))
			$this->db->where('facturacion_nueva.Factura_Año', $anio); 
		$this->db->from('conexion');
		$this->db->join('facturacion_nueva', 'facturacion_nueva.Factura_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;

	}


	public function buscar_movimientos_tabla($inicio,$fin, $tipo)
	{
		$dia_i = $inicio." 00:00:00";
		$dia_f = $fin." 23:59:59";
		$this->db->from('movimiento');
		if(! ( ($inicio == false) || ($inicio == 0) ||  ($fin == false) ||  ($fin == 0) ) )
		{
		//	var_dump($dia_i, $dia_f);die();
			$this->db->where('Mov_Timestamp >=', $dia_i);
			$this->db->where('Mov_Timestamp <=', $dia_f);
		}
		//var_dump($tipo);die();
		if ( ($tipo != false) ||  ($tipo != 0))
			$this->db->where('Mov_Tipo', $tipo);

		$this->db->join("facturacion_nueva","facturacion_nueva.Factura_Id = movimiento.Mov_Pago_Id", 'left');

		$this->db->join("clientes","clientes.Cli_Id = facturacion_nueva.Factura_Cli_Id", 'left');
		$this->db->join("conexion","conexion.Conexion_Id = facturacion_nueva.Factura_Conexion_Id", 'left');
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0):
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
	public function get_data_facturas_con_acuenta($mes, $anio){
		$this->db->where("Factura_Mes", $mes);
		$this->db->where("Factura_Año", $anio);
		$this->db->where("Factura_Acuenta !=", 0.00);
		$consulta = $this->db->get("facturacion_nueva");
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function get_data_dos_campos_con_conexion($tabla,$campo,$valor, $campo1,$valor1){
		$this->db->from($tabla);
		$this->db->where($campo, $valor);
		$this->db->where($campo1, $valor1);
		$this->db->join("conexion","conexion.Conexion_Id = facturacion_nueva.Factura_Conexion_Id");
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function get_data_dos_campos_B($tabla,$campo,$valor, $campo1,$valor1){
		$this->db->ORDER_BY("Conexion_UnionVecinal", "ASC");
		
		$this->db->where($campo, $valor);
		$this->db->where($campo1, $valor1);
		$consulta = $this->db->get($tabla);
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function buscar_pago_para_recibo($id_factura)
	{
		$this->db->select('Factura_Id,Factura_PagoTimestamp,Cli_RazonSocial,Factura_PagoMonto');
		$this->db->where('facturacion_nueva.Factura_Id', $id_factura);
		$this->db->from('facturacion_nueva');
		$this->db->join('movimiento', 'movimiento.Mov_Pago_Id = facturacion_nueva.Factura_Id','left'); 
		$this->db->join('clientes', 'clientes.Cli_Id = facturacion_nueva.Factura_Cli_Id'); 
		$this->db->join('conexion', 'conexion.Conexion_Id = facturacion_nueva.Factura_Conexion_Id'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;
	}
	public function get_sectores_query_corregir($sectores, $mes,$anio){
		$this->db->select('*');
		$this->db->from("conexion");
		$this->db->ORDER_BY("conexion.Conexion_UnionVecinal","asc");
		$this->db->ORDER_BY("facturacion_nueva.Factura_MedicionTimestamp","desc");
		$this->db->ORDER_BY("facturacion_nueva.Factura_Conexion_Id","desc");
		$this->db->or_where_in("Conexion_Sector", $sectores);
		$this->db->where("facturacion_nueva.Factura_Mes", $mes);
		$this->db->where("facturacion_nueva.Factura_Año", $anio);
		$this->db->join('facturacion_nueva', 'facturacion_nueva.Factura_Conexion_Id = Conexion.Conexion_Id');
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 1 )
			return $consulta->result();
		elseif ($consulta->num_rows() ==  1 )
				return $consulta->row();
		else return false;
	}
	
	public function lista_de_morosos(){
		$this->db->select('*');
		$this->db->from("conexion");
		//$this->db->ORDER_BY("conexion.Conexion_UnionVecinal","asc");
		$this->db->ORDER_BY("conexion.Conexion_Deuda","desc");
		//$this->db->ORDER_BY("facturacion_nueva.Factura_Conexion_Id","desc");
		//$this->db->or_where_in("Conexion_Sector", $sectores);
		$this->db->where("conexion.Conexion_Deuda > ", 0);
		$this->db->where("conexion.Conexion_Deuda > ", 0.00);
		$this->db->where("conexion.Conexion_Deuda != ", '');
		$this->db->join("clientes","clientes.Cli_Id  = conexion.Conexion_Cliente_Id");
		//$this->db->join('facturacion_nueva', 'facturacion_nueva.Factura_Conexion_Id = Conexion.Conexion_Id');
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 1 )
			return $consulta->result();
		elseif ($consulta->num_rows() ==  1 )
				return $consulta->row();
		else return false;
	}
	public function lista_de_pp($mes){
		$this->db->select('*');
		$this->db->from("facturacion_nueva");
		//$this->db->ORDER_BY("conexion.Conexion_UnionVecinal","asc");
		//$this->db->ORDER_BY("conexion.Conexion_Deuda","desc");
		//$this->db->ORDER_BY("facturacion_nueva.Factura_Conexion_Id","desc");
		//$this->db->or_where_in("Conexion_Sector", $sectores);
		$this->db->where("facturacion_nueva.Factura_Mes", $mes);
		$this->db->where("facturacion_nueva.Factura_PP_Cuota_Actual != ", 0);
		$this->db->where("facturacion_nueva.Factura_PPC_Precio > ", 0.00);
		$this->db->where("facturacion_nueva.Factura_PP_Cant_Cuotas != ", 0);
		$this->db->join("clientes","clientes.Cli_Id  = facturacion_nueva.Factura_Cli_Id");
		$this->db->join('conexion', 'conexion.conexion_Id = facturacion_nueva.Factura_Conexion_Id');
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 1 )
			return $consulta->result();
		elseif ($consulta->num_rows() ==  1 )
				return $consulta->row();
		else return false;
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
	public function traer_todas_facturas_tabla_vieja(){
		$this->db->select('Pago_Monto, Conexion_Id, Conexion_Sector');
		//$this->db->where("facturacion.Factura_Conexion_Id", $id);
		$this->db->where("medicion.Medicion_Mes", 2);
		$this->db->where("medicion.Medicion_Anio", 2018);
		$this->db->where("facturacion.Factura_Periodo", 2);
		$this->db->from("facturacion");
		$this->db->join("medicion","medicion.Medicion_Id = facturacion.Factura_Medicion_Id");
		$this->db->join("clientes","clientes.Cli_Id  = facturacion.id_cliente");
		$this->db->join("conexion","conexion.Conexion_Id  = facturacion.Factura_Conexion_Id");
		$this->db->join("pago","pago.Pago_Facturacion_Id = facturacion.id","left");
		$this->db->join("movimiento","movimiento.Mov_Pago_Id = pago.Pago_Id","left");
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result_array();
		else:
			return false;
		endif;
	}
	public function traer_factura_tabla_vieja($id){
		$this->db->select('*');
		$this->db->where("facturacion.Factura_Conexion_Id", $id);
		$this->db->where("medicion.Medicion_Mes", 2);
		$this->db->where("medicion.Medicion_Anio", 2018);
		$this->db->where("facturacion.Factura_Periodo", 2);
		$this->db->from("facturacion");
		$this->db->join("medicion","medicion.Medicion_Id = facturacion.Factura_Medicion_Id");
		$this->db->join("clientes","clientes.Cli_Id  = facturacion.id_cliente");
		$this->db->join("conexion","conexion.Conexion_Id  = facturacion.Factura_Conexion_Id");
		$this->db->join("pago","pago.Pago_Facturacion_Id = facturacion.id","left");
		$this->db->join("movimiento","movimiento.Mov_Pago_Id = pago.Pago_Id","left");
		$consulta = $this->db->get();
		if ($consulta->num_rows() >= 1):
			return $consulta->result_array();
		else:
			return false;
		endif;
	}
	public function traer_mediciones_tabla_vieja($sector){
		//$this->db->distinct('clientes.Cli_Id');
		//$this->db->select('DISTINCT clientes.Cli_Id');
		$this->db->select('*');
		//$this->db->group_by('conexion.Conexion_Id');
		$this->db->where("conexion.Conexion_Sector", $sector);
		$this->db->where("medicion.Medicion_Mes", 2);
		$this->db->where("medicion.Medicion_Anio", 2018);
		$this->db->from("conexion");
		$this->db->join("medicion","medicion.Medicion_Conexion_Id = conexion.Conexion_Id");
		$this->db->join("clientes","clientes.Cli_Id  = conexion.Conexion_Cliente_Id");
		$this->db->join("facturacion","facturacion.Factura_Medicion_Id = medicion.Medicion_Id");
		$this->db->join("pago","pago.Pago_Facturacion_Id = facturacion.id","left");
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
		//$this->db->distinct('clientes.Cli_Id');
		//$this->db->select('DISTINCT clientes.Cli_Id');
		$this->db->select('Factura_PagoMonto,Factura_Deuda,Factura_Multa,Cli_Id,Cli_RazonSocial,Conexion_Id,	Factura_CodigoBarra,Conexion_UnionVecinal,Conexion_Categoria,Conexion_Deuda, Factura_Riego, Conexion_Acuenta,Conexion_Sector,Conexion_Latitud, Factura_Id as id, Factura_CodigoBarra as id_factura, Factura_Acuenta, Factura_SubTotal as Factura_SubTotal,Factura_Total,Factura_Vencimiento1,Factura_Vencimiento1_Precio, Factura_Vencimiento2,Factura_Vencimiento2_Precio, Factura_TarifaSocial, Factura_CuotaSocial,Factura_PM_Cant_Cuotas, Factura_PM_Cuota_Actual, Factura_PM_Cuota_Precio, Factura_PP_Cant_Cuotas, Factura_PP_Cuota_Actual, Factura_PPC_Precio, Factura_Bonificacion,  Factura_Conexion_Id as Medicion_Id, Factura_Mes as Medicion_Mes,Factura_Año as Medicion_Anio, Factura_MedicionAnterior as Medicion_Anterior ,Factura_MedicionActual as Medicion_Actual , Factura_Basico as Medicion_Basico,Factura_Excedentem3 as  Medicion_Excedente,Factura_ExcedentePrecio as Medicion_Importe,Factura_FechaEmision as Medicion_Mts, Cli_DomicilioSuministro, Factura_PagoLugar as Pago_Id, Factura_PagoMonto as Pago_Monto, Factura_Timestamp as Pago_Total, Conexion_DomicilioSuministro, 	Factura_PagoTimestamp');
		//$this->db->group_by('conexion.Conexion_Id');
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
	
	public function update_data($datos, $id, $tabla,$campo){
		$this->db->where($campo, $id);
		return $this->db->update($tabla, $datos);
	}
	public function insert_data($tabla,$data){
		$this->db->insert($tabla,$data);
		return $this->db->insert_id();
	}
		public function get_codigos_movimientos(){
		$this->db->select('Codigo_Id, Codigo_Numero, Codigo_Descripcion');
		$consulta = $this->db->get("codigos");
		if ($consulta->num_rows() > 0):
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
	public function get_data($tabla){
		$consulta = $this->db->get($tabla);
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}
	public function get_sectores_query($sectores, $mes,$anio){
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
	public function update_data_tres_campos($datos, $id, $tabla,$campo, $campo_1, $valor_1, $campo_2, $valor_2){
		$this->db->where($campo, $id);
		$this->db->where($campo_1, $valor_1);
		$this->db->where($campo_2, $valor_2);
		return $this->db->update($tabla, $datos);
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
		$this->db->from("facturacion_nueva");
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
	
	public function buscar_lote_por_sectores_nuevo($id, $sector, $mes , $año){
		//var_dump($id, $sector, $mes , $año);die();
		//$this->db->select('*');
		$this->db->select('Factura_Multa,Factura_Deuda, Cli_Id,Cli_RazonSocial,Cli_Cuit,Conexion_Id,Conexion_UnionVecinal,Conexion_Categoria,Conexion_Deuda, Factura_Riego, Conexion_Acuenta,Conexion_Sector,Conexion_Latitud, Factura_Id as id, Factura_CodigoBarra as id_factura, Factura_Acuenta, Factura_SubTotal,Factura_Total,Factura_Vencimiento1,Factura_Vencimiento1_Precio, Factura_Vencimiento2,Factura_Vencimiento2_Precio, Factura_TarifaSocial, Factura_CuotaSocial,Factura_PM_Cant_Cuotas, Factura_PM_Cuota_Actual, Factura_PM_Cuota_Precio, Factura_PP_Cant_Cuotas, Factura_PP_Cuota_Actual, Factura_PPC_Precio, Factura_Bonificacion,  Factura_Conexion_Id as Medicion_Id, Factura_Mes as Medicion_Mes,Factura_Año as Medicion_Anio, Factura_MedicionAnterior as Medicion_Anterior ,Factura_MedicionActual as Medicion_Actual , Factura_Basico as Medicion_Basico,Factura_Excedentem3 as  Medicion_Excedente,Factura_ExcedentePrecio as Medicion_Importe,Factura_FechaEmision as Medicion_Mts, Cli_DomicilioSuministro, Factura_PagoLugar as Pago_Id, Factura_PagoMonto as Pago_Monto, Factura_Timestamp as Pago_Total, Conexion_DomicilioSuministro');
		$this->db->group_by('Conexion_Id');
		$this->db->where('conexion.Conexion_Borrado', 0);
		//$this->db->where_in('conexion.Conexion_Sector', $datos); 
		if($sector != -1)
			$this->db->where('conexion.Conexion_Sector', $sector); 
		if($id != -1)
			$this->db->where('conexion.Conexion_Id', $id); 
		if($mes != -1)
			$this->db->where('facturacion_nueva.Factura_Mes', $mes); 
		//$this->db->where('facturacion_nueva.Factura_Mes', 2); 
		if($año != -1)
			$this->db->where('facturacion_nueva.Factura_Año',$año ); 
		$this->db->from('facturacion_nueva');
		$this->db->join('clientes', 'clientes.Cli_Id = facturacion_nueva.Factura_Cli_Id'); 
		$this->db->join('conexion', 'conexion.Conexion_Id = facturacion_nueva.Factura_Conexion_Id'); 
		//$this->db->join('facturacion', 'facturacion.Factura_Conexion_Id = conexion.Conexion_Id'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
		return $consulta->result();
	else return false;

	}


	
		public function get_data_autorizacion($revisado,$mes,$anio,$id_conexion,$sector,$inicio,$fin){
		//var_dump($id, $sector, $mes , $año);die();
		$this->db->select('*');
		//$this->db->select('Cli_Id,Cli_RazonSocial,Cli_Cuit,Conexion_Id,Conexion_UnionVecinal,Conexion_Categoria,Conexion_Deuda, Factura_Riego, Conexion_Acuenta,Conexion_Sector,Conexion_Latitud, Factura_Id as id, Factura_CodigoBarra as id_factura, Factura_Acuenta, Factura_SubTotal,Factura_Total,Factura_Vencimiento1,Factura_Vencimiento1_Precio, Factura_Vencimiento2,Factura_Vencimiento2_Precio, Factura_TarifaSocial, Factura_CuotaSocial,Factura_PM_Cant_Cuotas, Factura_PM_Cuota_Actual, Factura_PM_Cuota_Precio, Factura_PP_Cant_Cuotas, Factura_PP_Cuota_Actual, Factura_PPC_Precio, Factura_Bonificacion,  Factura_Conexion_Id as Medicion_Id, Factura_Mes as Medicion_Mes,Factura_Año as Medicion_Anio, Factura_MedicionAnterior as Medicion_Anterior ,Factura_MedicionActual as Medicion_Actual , Factura_Basico as Medicion_Basico,Factura_Excedentem3 as  Medicion_Excedente,Factura_ExcedentePrecio as Medicion_Importe,Factura_FechaEmision as Medicion_Mts, Cli_DomicilioSuministro, Factura_PagoLugar as Pago_Id, Factura_PagoMonto as Pago_Monto, Factura_Timestamp as Pago_Total, Conexion_DomicilioSuministro');
		//$this->db->group_by('Conexion_Id');
		if( ($revisado != null) &&  ($revisado != false) )
			$this->db->where('autorizacion.Aut_Revisado', $revisado);
		//$this->db->where_in('conexion.Conexion_Sector', $datos); 
		if( ($mes != null) &&  ($mes != false) )
			$this->db->where('autorizacion.Aut_Mes', $mes); 
		if( ($anio != null) &&  ($anio != false) )
			$this->db->where('autorizacion.Aut_Año', $anio);
		if( ($id_conexion != null) &&  ($id_conexion != false) )
			$this->db->where('conexion.Conexion_Id', $id_conexion); 
		if( ($sector != null) &&  ($sector != false) )
			$this->db->where('conexion.Conexion_Sector', $sector); 

		if(! ( ($inicio == false) || ($inicio == 0) || ($inicio == null) ||  ($fin == false) || ($fin == null) ||   ($fin == 0) ) )
		{
			$dia_i = $inicio." 00:00:00";
			$dia_f = $fin." 23:59:59";
			$this->db->where('autorizacion.Aut_FechaHora >=', $dia_i);
			$this->db->where('autorizacion. Aut_FechaHora<=', $dia_f);
		}
		$this->db->from('autorizacion');
		$this->db->join('facturacion_nueva', 'facturacion_nueva.Factura_Id = autorizacion.Aut_Factura_Id'); 
		$this->db->join('conexion', 'conexion.Conexion_Id = facturacion_nueva.Factura_Conexion_Id'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
		return $consulta->result();
	else return false;

	}


	public function buscar_deuda_conexion($id){
		//var_dump($id, $sector, $mes , $año);die();
		$this->db->select('Conexion_Deuda');
		$this->db->where('Conexion_Id', $id);
		$consulta = $this->db->get('conexion');
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;
	}

	public function buscar_multa_conexion($id){
		//var_dump($id, $sector, $mes , $año);die();
		$this->db->select('Conexion_Multa');
		$this->db->where('Conexion_Id', $id);
		$consulta = $this->db->get('conexion');
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;
	}

	public function buscar_acuenta_conexion($id){
		//var_dump($id, $sector, $mes , $año);die();
		$this->db->select('Conexion_Acuenta');
		$this->db->where('Conexion_Id', $id);
		$consulta = $this->db->get('conexion');
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;
	}



	public function buscar_datos_movimientos_modal($id_movimiento){
		$this->db->from('movimiento');
		if ( ($id_movimiento != false) ||  ($id_movimiento != 0))
			$this->db->where('Mov_Id', $id_movimiento);
		$this->db->join("facturacion_nueva","facturacion_nueva.Factura_Id = movimiento.Mov_Pago_Id", 'left');
		$this->db->join("clientes","clientes.Cli_Id = facturacion_nueva.Factura_Cli_Id", 'left');
		$this->db->join("conexion","conexion.Conexion_Id = facturacion_nueva.Factura_Conexion_Id", 'left');
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0):
			return $consulta->result_array();
		else:
			return false;
		endif;

	}
	public function get_data_tres_campos($tabla,$campo,$valor, $campo1,$valor1, $campo2,$valor2){
		if($valor != -1)
			$this->db->where($campo, $valor);
		if($valor1 != -1)
			$this->db->where($campo1, $valor1);
		if($valor2 != -1)
			$this->db->where($campo2, $valor2);
		$consulta = $this->db->get($tabla);
		if ($consulta->num_rows() > 0):
			return $consulta->result();
		else:
			return false;
		endif;
	}

	public function buscar_lote_por_sectores($id, $mes, $anio){
		$this->db->select('*');
		$this->db->group_by('Conexion_Id');
		$this->db->where('conexion.Conexion_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Borrado', 0);
		// $this->db->where('PlanPago.PlanPago_Habilitacion', 1);
		$this->db->where('conexion.Conexion_Id', $id); 
		//$this->db->where('conexion.Conexion_Sector', "A"); 
		$this->db->where('medicion.Medicion_Mes', $mes); 
		$this->db->where('medicion.Medicion_Anio', $anio); 
		$this->db->where('facturacion.Factura_Periodo', $mes); 
		$this->db->from('conexion');
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id'); 
		// //$this->db->join('conexion', 'conexion.Conexion_Id = facturacion.Factura_Conexion_Id','left'); 
		$this->db->join('PlanPago', 'PlanPago.PlanPago_Conexion_Id = conexion.Conexion_Id','left'); 
		$this->db->join('Medicion', 'Medicion.Medicion_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('planmedidor', 'planmedidor.PlanMedidor_Conexion_Id = conexion.Conexion_Id','left');
		$this->db->join('facturacion', 'facturacion.Factura_Conexion_Id = conexion.Conexion_Id'); 
		$this->db->join('pago', 'pago.Pago_Facturacion_Id = facturacion.id','left'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
		return $consulta->result();
		else return false;
	}
	public function buscar_movimiento_para_imprimir($id_mov)
	{
		$this->db->select('*');
		$this->db->where('movimiento.Mov_Id', $id_mov);
		$this->db->from('movimiento');
		$this->db->join('conexion', 'conexion.Conexion_Id = movimiento.Mov_Conexion_Id','left');
		$this->db->join('clientes', 'clientes.Cli_Id = conexion.Conexion_Cliente_Id','left');
		$this->db->join('facturacion_nueva', 'facturacion_nueva.Factura_Id = movimiento.Mov_Factura_Id','left');
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
			return $consulta->result();
		else return false;

	}
	
	public function get_data_facturas_descuentos_a_aprobar($revisado,$mes,$anio,$id_conexion,$sector,$inicio,$fin){
		//var_dump($revisado);die();
		$this->db->select('*');
		//$this->db->select('Cli_Id,Cli_RazonSocial,Cli_Cuit,Conexion_Id,Conexion_UnionVecinal,Conexion_Categoria,Conexion_Deuda, Factura_Riego, Conexion_Acuenta,Conexion_Sector,Conexion_Latitud, Factura_Id as id, Factura_CodigoBarra as id_factura, Factura_Acuenta, Factura_SubTotal,Factura_Total,Factura_Vencimiento1,Factura_Vencimiento1_Precio, Factura_Vencimiento2,Factura_Vencimiento2_Precio, Factura_TarifaSocial, Factura_CuotaSocial,Factura_PM_Cant_Cuotas, Factura_PM_Cuota_Actual, Factura_PM_Cuota_Precio, Factura_PP_Cant_Cuotas, Factura_PP_Cuota_Actual, Factura_PPC_Precio, Factura_Bonificacion,  Factura_Conexion_Id as Medicion_Id, Factura_Mes as Medicion_Mes,Factura_Año as Medicion_Anio, Factura_MedicionAnterior as Medicion_Anterior ,Factura_MedicionActual as Medicion_Actual , Factura_Basico as Medicion_Basico,Factura_Excedentem3 as  Medicion_Excedente,Factura_ExcedentePrecio as Medicion_Importe,Factura_FechaEmision as Medicion_Mts, Cli_DomicilioSuministro, Factura_PagoLugar as Pago_Id, Factura_PagoMonto as Pago_Monto, Factura_Timestamp as Pago_Total, Conexion_DomicilioSuministro');
		//$this->db->group_by('Conexion_Id');
		if( ($revisado != null) &&  ($revisado != false) )
		{
			$this->db->where('facturacion_nueva.Factura_DescuentoRevisado', $revisado);
		}
		else 
		{ //caso basico, todos los que tengan descuento
			$this->db->where('facturacion_nueva.Factura_Descuento !=', 0);
		}
		//$this->db->where_in('conexion.Conexion_Sector', $datos); 
		if( ($mes != null) &&  ($mes != false) )
			$this->db->where('facturacion_nueva.Factura_Mes', $mes); 
		if( ($anio != null) &&  ($anio != false) )
		{
			$anio = intval($anio);
			$this->db->where('facturacion_nueva.Factura_Año', $anio);
		}
		if( ($id_conexion != null) &&  ($id_conexion != false) )
			$this->db->where('conexion.Conexion_Id', $id_conexion); 
		if( ($sector != null) &&  ($sector != false) )
			$this->db->where('conexion.Conexion_Sector', $sector); 

		if(! ( ($inicio == false) || ($inicio == 0) || ($inicio == null) ||  ($fin == false) || ($fin == null) ||   ($fin == 0) ) )
		{
			$dia_i = $inicio." 00:00:00";
			$dia_f = $fin." 23:59:59";
			$this->db->where('facturacion_nueva.Factura_Timestamp >=', $dia_i);
			$this->db->where('facturacion_nueva. Factura_Timestamp<=', $dia_f);
		}
		$this->db->from('facturacion_nueva');
		$this->db->join('conexion', 'conexion.Conexion_Id = facturacion_nueva.Factura_Conexion_Id'); 
		$this->db->join('clientes', 'clientes.Cli_Id = facturacion_nueva.Factura_Cli_Id'); 
		$consulta = $this->db->get();
		if($consulta->num_rows()>0)
		return $consulta->result();
		else return false;

	}



}
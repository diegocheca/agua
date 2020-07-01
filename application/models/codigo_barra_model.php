<?php
//heredamos todos los recursos de la clase CI_Model
class Codigo_barra_model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function ingresos_hoy()
	{
		$this->db->ORDER_BY('AMo_Hora', "DESC");
		$this->db->distinct('AMo_Per_ID');  
		$this->db->select('AMo_ID,AMo_Per_ID,Per_Apellido,Per_Nombre,AMo_Hora,AMo_Estado');
		$this->db->where('AMo_Fecha', hoy); 
		$this->db->from('AcreditacionMovimientos'); 
		$this->db->join('Persona', 'AMo_Per_ID = Per_ID');
		$query = $this->db->get();
		if($query->num_rows()>0)
			return $query->result();
		else return false;
	}

	public function buscar_persona($codigo_a_buscar)
	{
		$this->db->select('*');
		$this->db->where('Per_ID',$codigo_a_buscar);
		$query= $this->db->get('Persona');
		if($query->num_rows()==1)
			return $query->result_array();
		else
			return FALSE;
	}

	/*public function  actualizar_bandera($id)
	{
		$arrayName = array(
			'AMo_Puesto1' => $id, );
		$this->db->where('AMo_ID',$id);
		return ($this->db->update('AcreditacionMovimientos', $arrayName));
	}*/

	public function marcar_movimiento($codigo_a_buscar,$id,$e_s,$id_stand,$puesto)
	{
		$arrayName = array(
			'AMo_ID' => '' ,
			'AMo_Pun_ID' => puesto,
			'AMo_Per_ID' => $id,
			'AMo_Sta_ID' => $id_stand,
			'AMo_Fecha' =>hoy ,
			'AMo_Hora' =>hora,
			'AMo_Estado' =>$e_s
			// 'AMo_Puesto1' => 1,
			// 'AMo_Puesto2' => 0,
			// 'AMo_Puesto3' => 0
			 );
		$this->db->insert('AcreditacionMovimientos', $arrayName); 
		return $this->db->insert_id();
	}

	public function marcar_movimiento_sin_estado($codigo_a_buscar,$id,$id_stand,$puesto)
	{
		$this->db->ORDER_BY("AMo_ID","desc");
		$this->db->limit(1);
		$this->db->where('AMo_Per_ID',$id);
		$this->db->where('AMo_Fecha',hoy);
		$query= $this->db->get('acreditacionmovimientos');
		if($query->num_rows()>0) //hay registros
		{
			$temporal = $query->result_array();
			if($temporal[0]["AMo_Estado"]=="E")
				$temporal = "S";
			else $temporal = "E";
			$arrayName = array(
				'AMo_ID' => '' ,
				'AMo_Pun_ID' => puesto ,
				'AMo_Per_ID' => $id,
				'AMo_Sta_ID' => $id_stand,
				'AMo_Fecha' =>hoy ,
				'AMo_Hora' =>hora,
				'AMo_Estado' =>$temporal
				// 'AMo_Puesto1' => 1,
				// 'AMo_Puesto2' => 0,
				// 'AMo_Puesto3' => 0
				 );
			$resultado = $this->db->insert('AcreditacionMovimientos', $arrayName); 
			return $this->db->insert_id();
		}
		else
			return FALSE;
	}

	public function buscar_movimientos($idper)
	{
		$this->db->ORDER_BY("AMo_ID","desc");
		$this->db->where('AMo_Per_ID',$idper);
		$this->db->where('AMo_Fecha',hoy);
		$query= $this->db->get('AcreditacionMovimientos');
		if($query->num_rows()>0)
			return $query->result();
		else
			return FALSE;
	}

	public function buscar_tipo($idper)
	{
		$this->db->select('*');    
		$this->db->from('Acreditacion');
		$this->db->where('Acr_Per_ID',$idper);
		$this->db->join('Acreditaciontipo', 'Acreditacion.Acr_ATi_ID = Acreditaciontipo.ATi_ID');
		$query = $this->db->get();
		if($query->num_rows()>0)
			return $query->result_array();
		else
			return FALSE;
	}

	public function buscar_stand($idper)
	{
		$this->db->select('*');    
		$this->db->from('Acreditacion');
		$this->db->where('Acr_Per_ID',$idper);
		$this->db->join('Stand', 'Acreditacion.Acr_Sta_ID = Stand.Sta_ID');
		$this->db->join('Sector', 'Stand.STa_Sec_ID = Sector.Sec_ID');
		$query = $this->db->get();
		if($query->num_rows()>0)
			return $query->result_array();
		else
			return FALSE;
	}

	public function buscar_stand_por_id($idper)
	{
		$this->db->select('Acr_Sta_ID');    
		$this->db->from('Acreditacion');
		$this->db->where('Acr_Per_ID',$idper);
		$query = $this->db->get();
		if($query->num_rows()>0)
			return $query->result_array();
		else
			return FALSE;
	}
}

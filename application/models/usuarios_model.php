<?php
class Usuarios_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	public function login($usuario,$password){
		$this->db->where('usuario',$usuario)
			->where('password',$password)
			->from('usuarios');
		
		$consulta=$this->db->get();

		if ($consulta->num_rows > 0) {
			$consulta = $consulta->row();
			$this->session->set_userdata('login',$consulta->usuario);//Crea la Session
			$this->session->set_userdata('rol',$consulta->rol);//Crea la Session
			$this->session->set_userdata('nombre',$consulta->nombre);//Crea la Session
			$this->session->set_userdata('id_user',$consulta->id);//Crea la Session
			$this->session->set_userdata('is_logued_in',true);//Crea la Session
			

			echo "TRUE";
		}else{
			echo "FALSE";
		}
	}

	public function getEventos(){
		$this->db->select('Evento_Id id, Evento_Titulo title, Evento_FechaInicio start, Evento_FechaFin end, Evento_Estado url');
		$this->db->from('evento');

		return $this->db->get()->result();
	}
	
}
?>
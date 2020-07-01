<?php
if (!defined( 'BASEPATH')) exit('No direct script access allowed'); 
class Home
{
	private $ci;
	public function __construct()
	{
		$this->ci =& get_instance();
		!$this->ci->load->library('session') ? $this->ci->load->library('session') : false;
		!$this->ci->load->helper('url') ? $this->ci->load->helper('url') : false;
		!$this->ci->load->helper('form') ? $this->ci->load->helper('form') : false;
		!$this->ci->load->library('user_agent') ? $this->ci->load->library('user_agent') : false;
	}    
	public function check_login()
	{
		if ($this->ci->uri->segment(1) == "notificacion")
			return true;
		if($this->ci->uri->segment(1) != "login")
		{
			if(($this->ci->session->userdata('is_logued_in') != ''))
				return true;
			else 
		 	{
				if(($this->ci->uri->segment(1)==null)||($this->ci->uri->segment(1)==''))
					return true;
				else
				{
					echo "usted debe estar logado para ingresar al sistema. Hagalo aqui: <a href='http://localhost/codeigniter/'>Ingrese aqui</a> "; 
					die();
				} 
			}
		}
	}


		public function perfil_correcto()
		{
			if($this->ci->session->userdata('is_logued_in') == TRUE)
			{
				require 'levels.php';
				$class = $this->ci->uri->segment(1);
				$method = $this->ci->uri->segment(2);
				if($method==null)
					$method = 'index';
				if(($method == "logout_ci")&&($class == "login"))
					return true;
				if($method == "notificacion")
					return true;
				if(($method == "fin")&&($class == "login"))
					return true;
				if($class == "mensaje")
					return true;
				 if($class == "error")
					return true;
				elseif($this->ci->uri->segment(1) != "login")
				{
					if($this->ci->session->userdata('perfil') == 'administrador'){ //admin always true
							return true;
						}
						elseif($this->ci->uri->segment(1)=='password')
								return true;
						else{
								if($this->ci->session->userdata('is_logued_in'))
								{
									
									if($privs[$this->ci->session->userdata('perfil')][$class][$method]){ //if not exists = allowed
										return true;
									}
									else{
										echo $this->ci->session->userdata('perfil');
										echo $class;
										echo $method;
										echo $privs[$this->ci->session->userdata('perfil')][$class][$method]."---";
										echo "me fui por el refresh";
										die();
									 $oa = base_url('acceso_m');
									 redirect($oa,'refresh');  //redirect to not-authorized page
									}
								}
								else{
									redirect(base_url(),'refresh');
								}
						}
					}
			}
			elseif($this->ci->uri->segment(1)=='login') return true;
			elseif($this->ci->uri->segment(1)=='notificacion') return true;
	}
}

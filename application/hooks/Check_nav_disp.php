<?php if (!defined( 'BASEPATH')) exit('No direct script access allowed');

class Check_nav_disp
{
	
    private $ci;
	
    public function __construct()
    {
        $this->ci =& get_instance();
        !$this->ci->load->helper('url') ? $this->ci->load->helper('url') : false;
		!$this->ci->load->library('user_agent') ? $this->ci->load->library('user_agent') : false;
		!$this->ci->load->library('session') ? $this->ci->load->library('session') : false;
     	!$this->ci->load->helper('url') ? $this->ci->load->helper('url') : false;
	}
	
	//controlamos desde el navegador que llegan nuestros usuarios
    public function navegadores()
    {
    	if ( ($this->ci->session->userdata('is_logued_in') != '')  && ($this->ci->session->userdata('nav') == NULL) )
        {
			if ($this->ci->agent->is_browser('Chrome'))
			{
				$this->ci->session->set_userdata('nav','c');
			
			}elseif ($this->ci->agent->is_browser('Mozilla')){
				$this->ci->session->set_userdata('nav','m');
				}
				elseif ($this->ci->agent->is_browser('Internet Explorer')){
					$this->ci->session->set_userdata('nav','i');
				}
				else $this->ci->session->set_userdata('nav','n'); //no se que tiene
        }
    }
	
	//podemos detectar si es un dispositivo móvil
	public function dispositivos()
	{
		if ( ($this->ci->session->userdata('is_logued_in') != '')  && ($this->ci->session->userdata('is_mobile') == NULL) )
        {
        	if ($this->ci->agent->is_mobile())
        	{
	            $this->ci->session->set_userdata('is_mobile',true);
	            $this->ci->session->set_userdata('mobile',$this->ci->agent->mobile());
				//mostramos el nombre del dispositivo que nos visita
				//y cargamos la vista correspondiente
				//echo $this->ci->agent->mobile();
			}
			else
			{
				$this->ci->session->set_userdata('is_mobile',false);
				//cargamos la vista home
				//echo 'el dispositivo es de otro tipo';
				//$this->ci->load->view('home');
			}
        }
	}
	
	//el nombre de los robots que nos visitan
	public function robot()
	{
		
		if($this->ci->agent->is_robot())
		{
			
			//podemos guardar el nombre del robot
			$nombre_robot = $this->ci->agent->robot();
			/*
			TERMINAR ESTO EN ALGUN MOMENTO DE LA VIDA

				$this->ci->load->model("hooks_model");
				llamar a guardar los datos tal como: nombre del robot, timespand, etc.
			*/
			//echo $this->ci->agent->robot();
			
		}
		
	}
	
	//la plataforma/sistema operativo desde que nos visita el usuario
	public function plataforma()
	{
		
		//obtenemos el nombre del sistema operativo (Linux, Windows, OS X, etc.).
		echo $this->ci->agent->platform();
		
	}
}
/*
/end hooks/Check_nav_disp.php
*/
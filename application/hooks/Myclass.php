<?php
if (!defined( 'BASEPATH')) exit('No direct script access allowed'); 
class Myclass
{
    private $_ci;
    public function __construct()
    {
      $this->_ci =& get_instance();
      //var_dump(get_instance());die();
       if(!$this->_ci->load->library('session') )
       $this->_ci->load->library('session');
       if(!$this->_ci->load->helper('url') ) 
        $this->_ci->load->helper('url');
       if(!$this->_ci->load->helper('form') )
        $this->_ci->load->helper('form');
      if(!$this->_ci->load->library('user_agent') ) 
        $this->_ci->load->library('user_agent');
    }    

    public function Myfunction()
    {
      $class = $this->_ci->uri->segment(1);
      $method = $this->_ci->uri->segment(2);
      /*
      ACA ACLARO QUE PARA ACCEDER A LAS APIS NO SE NECESITA ESTAR LOGUEADO
      */
      if($class == "api")  
      {
        // $method = 'index';
        return true;
      }
      if($this->_ci->session->userdata('is_logued_in') == TRUE)
      {
        require 'levels.php';
        
       // var_dump($class,$method);die();
        if ( ($method==null) && ($class==null) )
          {
            // $method = 'index';
            // echo "me voy por el 1";
             return true;
          }
        // if(($method == "logout_ci")&&($class == "login"))
        //    {
        //     // $method = 'index';
        //     echo "me voy por el 2";
        //      return true;
        //   }
        if(($method == "logout")&&($class == "home"))
           {
            // $method = 'index';
            // echo "me voy por el 3";
             return true;
          }
        if($class == "mensaje")
           {
            // $method = 'index';
            // echo "me voy por el 4";
             return true;
          }

         if($class == "error")
           {
            //  $method = 'index';
            // echo "me voy por el 5";
             return true;
          }
        elseif($this->_ci->uri->segment(1) != "login")
        {
          if($this->_ci->session->userdata('rol') == 'administrador')//admin always true
          {
             // echo "me fui por el admin";
              return true;
          }
          elseif($this->_ci->uri->segment(1)=='password')
              return true;
          else
          {


            if($this->_ci->session->userdata('is_logued_in'))
            {
               if($method == false)
                $method = "index";
             // var_dump($this->_ci->session->userdata('rol'), $class, $method);
              if($privs[$this->_ci->session->userdata('rol')][$class][$method])//if not exists = allowed
                return true;
              else
              {
                
                redirect("notificacion/sin_permiso",'refresh');  //redirect to not-authorized page
              }
            }
            else
              redirect(base_url(),'refresh');
          }
        }
      }
      elseif ( ($this->_ci->session->userdata('is_logued_in') != true) && ($method!=null) && ($class!=null) )
         redirect("notificacion/sin_loguear",'refresh');  //redirect to not-authorized page
      else return true; //redirect(base_url(),'refresh');  //redirect to not-authorized page
        //if($this->ci->uri->segment(1)=='login') return true;
    }
 
    // public function check_login()
    // {
    //   echo "Estoy en el hooks";die();
    //   if($this->ci->uri->segment(1) != "login")
    //   {
    //     if(($this->ci->session->userdata('is_logued_in') != ''))
    //       return true;
    //     elseif ($this->ci->uri->segment(1) == "tareas")//despues se cambiara el nobre a webservice
    //       return true;
    //     else 
	  //   {
    //       if(($this->ci->uri->segment(1)==null)||($this->ci->uri->segment(1)==''))
    //         return true;
    //       else echo "usted debe estar logado para ingresar al sistema. Hagalo aqui: <a href='http://localhost/trazabilidad/'>Ingrese aqui</a> "; 
    //       die();
    //     }
    //   }
    // }
}

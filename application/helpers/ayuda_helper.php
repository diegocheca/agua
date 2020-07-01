<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//si no existe la función invierte_date_time la creamos
if(!function_exists('invierte_date_time'))
{
    //formateamos la fecha y la hora, función de cesarcancino.com
	function invierte_date_time($fecha)
	{

		$day=substr($fecha,8,2);
		$month=substr($fecha,5,2);
		$year=substr($fecha,0,4);
		$hour = substr($fecha,11,5);
		$datetime_format=$day."-".$month."-".$year.' '.$hour;
		return $datetime_format;

	}
}

if(!function_exists('get_users'))
{
	function get_users()
	{
        //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();
		$query = $ci->db->get('usuarios');
		return $query->result();

	}
}

function sin_cache()
{
	$this->output->set_header("HTTP/1.0 200 OK");
	$this->output->set_header("HTTP/1.1 200 OK");
	//$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
	$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
	$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
	$this->output->set_header("Pragma: no-cache");
}
//end application/helpers/ayuda_helper.php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buscador extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->database('default');
		//$this->load->model('codigo_barra_model');
	}
	public function index()
	{
		$this->load->view('bienvenido_view.php');
	}
	public function registrando()
	{
		$this->load->view('registrando_view');
	}
	function cifrar($string) 
	{
		$string = utf8_encode($string);
		$control = "NJnfjafapJFBaisfbasfapsfasf4186as1as6dD"; //defino la llave para encriptar la cadena, cambiarla por la que deseamos usar
		$string = $control.$string.$control; //concateno la llave para encriptar la cadena
		$string = base64_encode($string);//codifico la cadena
		return($string);
	} 


	function decifrar($string)
	{
		$string = base64_decode($string); //decodifico la cadena
		$control = "NJnfjafapJFBaisfbasfapsfasf4186as1as6dD"; //defino la llave con la que fue encriptada la cadena,, cambiarla por la que deseamos usar
		$string = str_replace($control, "", "$string"); //quito la llave de la cadena
		return $string;
	}
	public function traer() //listar
	{
		$datos = $this->codigo_barra_model->ingresos_hoy();
		$i=0;
		foreach ($datos as $key) 
		{
			if ( ! (isset($arrayName [$key->AMo_Per_ID])))
			{
				$arrayName [$key->AMo_Per_ID] = true;
				$mostrar[$i]["ID_marcacion"] = $key->AMo_ID;
				$mostrar[$i]["ID_per"] = $key->AMo_Per_ID;
				$mostrar[$i]["Nombre"] = $key->Per_Apellido.", ".$key->Per_Nombre;
				$mostrar[$i]["Hora"] = $key->AMo_Hora;
				$mostrar[$i]["Estado"] = $key->AMo_Estado;
				$i++;
			}
		}
		$data["mostrar"] = $mostrar;
		$this->load->view('bienvenido_view',$data);
	}
	public function buscar_codigo_foto() //REGISTRAR por el form
	{
		$codigo_a_buscar=$this->input->post("codigo");
		if(strlen($codigo_a_buscar)!=cantidad_digitos)
		{
			$data["error"] = "Cantidad de Digitos incorrecto";
			$this->load->view('registrando_view',$data);
		}
		else // cantida de digitos correcta
		{
			if(!(is_numeric($codigo_a_buscar)))
			{
				$data["error"] = "No es un numero";
				$this->load->view('registrando_view',$data);
			}
			else
			{
				//compruebo el numero de seguridad
				$i=0;
				$acumulado = 0;
				for ($j=0; $j < cantidad_digitos-1 ; $j++)
					$acumulado +=$codigo_a_buscar[$j] ;
				if (($acumulado %7) != $codigo_a_buscar[cantidad_digitos-1] )
				{
					$data["error"] = "Codigo Incorrecto";
					$this->load->view('registrando_view',$data);
				}
				else echo "codigo leido correctamente, valor:".$codigo_a_buscar;
			}
		}
	}
	public function buscar_codigo_foto_id($id) //REGISTRAR
	{
		$codigo_a_buscar=$id;
		//cantidad de caracteres
		if(strlen($codigo_a_buscar)!=cantidad_digitos)
		{
			$data["error"] = "Cantidad de Digitos incorrecto";
			$this->load->view('registrando_view',$data);
		}
		else // cantida de digitos correcta
		{
			if(!(is_numeric($codigo_a_buscar)))
			{
				$data["error"] = "No es un numero";
				$this->load->view('registrando_view',$data);
			}
			else
			{
				//compruebo el numero de seguridad
				$i=0;
				$acumulado = 0;
				for ($j=0; $j < cantidad_digitos-1 ; $j++) //sin el de seguridad
					$acumulado +=$codigo_a_buscar[$j] ;
				if (($acumulado %7) != $codigo_a_buscar[cantidad_digitos-1] )
				{
					$data["error"] = "Codigo Incorrecto";
					$this->load->view('registrando_view',$data);
				}
				else //codigo sin errores
				{
					$sub_cadena = substr($codigo_a_buscar, 0,cantidad_disponible);
					$i=0;
					while($sub_cadena[$i] == 0)
						$i++;
					$sub_cadena = substr($sub_cadena, $i,cantidad_disponible);
					$codigo_a_buscar = $sub_cadena;
					$aux = $this->codigo_barra_model->buscar_persona($codigo_a_buscar);//traigo los datos de la base de datos
					if($aux!=false)
					{
						//busco los movimientos del dia
						$data["movimientos"] = $this->codigo_barra_model->buscar_movimientos($aux[0]["Per_ID"]); //traigo los movimientos
						$data["movimientos"] = $this->codigo_barra_model->buscar_movimientos($aux[0]["Per_ID"]); //traigo los movimientos
						//buscar tipo de persona: constructor, expositor, etc
						$aux_tipo = $this->codigo_barra_model->buscar_tipo($aux[0]["Per_ID"]); //traigo los movimientos
						$data["tipo"] = $aux_tipo[0]["ATi_Nombre"];
						$aux_tipo = $this->codigo_barra_model->buscar_stand($aux[0]["Per_ID"]); //traigo los movimientos
						$data["stand"] = $aux_tipo[0]["Sec_Letra"];
						$data["dni"] = $aux[0]["Per_DNI"];
						$data["id"] = $aux[0]["Per_ID"];
						$data["apellido"] = $aux[0]["Per_Apellido"];
						$data["nombre"] = $aux[0]["Per_Nombre"];
						$data["sexo"] = $aux[0]["Per_Sexo"];
						$sexo= $aux[0]["Per_Sexo"];
						$data["fecha"] = $aux[0]["Per_FechaNac"];
						$file= "C:/xampp/htdocs/codigo_barra/fotos/".$data["id"].".png";
						//var_dump($file,file_exists($file));die();
						//foto
						if(file_exists($file))//veo si tiene la foto ya tomada
						{
							$data["foto"] = "http://localhost/codigo_barra/fotos/".$data["id"].".png";
						}
						else
						{
							if(($sexo == 'M')||($sexo == 'm')||($sexo == 'Masculino')|| ($sexo == 'masculino'))
								$data["foto"] = "http://localhost/codigo_barra/images/hombre.png";
							elseif(($sexo == 'F')||($sexo == 'f')||($sexo == 'Femenino')|| ($sexo == 'femenino'))
								$data["foto"] = "http://localhost/codigo_barra/images/mujer.png";
							else
								$data["foto"] = "http://localhost/codigo_barra/images/sin_foto.jpg";
						}
						$this->load->view('registrando_view',$data);
					}
					else //perona inexistente
					{
						$data["error"] = "Persona Inexistente";
						$this->load->view('registrando_view',$data);
					}
				}
			}
		}
	}
	public function calcular_id($id_abuscar)
	{
		$nuevo_id = null; //acumulador
		$i=0;//vuelta
		$max =5;//maximo
		$mod =0;
		for ($i=0; $i <$max ; $i++) { 
			$nuevo_id.="0";
		}
		if(is_numeric($id_abuscar) && (strlen($id_abuscar)>=1)&&( strlen($id_abuscar)<=6))
		{ 
			$max --;
			$i=strlen($id_abuscar)-1;
			while($i>=0)
			{
				$nuevo_id [$max]= $id_abuscar[$i];
				$mod +=$id_abuscar[$i];
				$i--;
				$max--;
			}
			$nuevo_id.=$mod%7;
			return $nuevo_id;
		}
		else return false;
	}
	public function descalcular_id($id_abuscar)
	{
		$nuevo_id = null; //acumulador
		$i=0;//vuelta
		$max =5;//maximo
		$mod =0;
		for ($i=0; $i <$max ; $i++) { 
			$nuevo_id.="0";
		}
		if(is_numeric($id_abuscar) && (strlen($id_abuscar)>=1)&&( strlen($id_abuscar)<=6))
		{ 
			$max --;
			$i=strlen($id_abuscar)-1;
			while($i>=0)
			{
				$nuevo_id [$max]= $id_abuscar[$i];
				$mod +=$id_abuscar[$i];
				$i--;
				$max--;
			}
			$nuevo_id.=$mod%7;
			return $nuevo_id;
		}
		else return false;
	}
	public function guardar_foto($id_abuscar) //Registrar
	{
		$nombre_foto = $id_abuscar;
		$config['upload_path'] = './fotos/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['file_name'] =  $nombre_foto;
		$config['max_size'] = '3000';
		$config['max_width'] = '3024';
		$config['max_height'] = '3008';
		$this->load->library('upload', $config);
		$img =  $this->input->post("ultima");
		if($img!=NULL)
		{
			echo "tengo algo en img";
			define('UPLOAD_DIR', 'fotos/');
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$datospp = base64_decode($img);
			$file = UPLOAD_DIR.$nombre_foto.'.png';
			var_dump($file);
			$success = file_put_contents($file, $datospp);
		}
		$id_abuscar = $this->calcular_id($id_abuscar);
		$path ='/index.php/buscador/buscar_codigo_foto_id/'.(string)$id_abuscar;
		redirect($path, 'refresh');
	}
	public function buscar_codigo() // para cobrar la boleta en el sistema de agua
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$codigo_a_buscar=$this->input->post("codigo");
			//cantidad de caracteres
			$codigo_a_buscar = substr( $codigo_a_buscar, 0,-1);
			//var_dump($codigo_a_buscar); //cantidad_digitos, strlen($codigo_a_buscar));
			if(strlen($codigo_a_buscar)!=cantidad_digitos)
			{
				//echo "estoy en 1";
				$data["error"] = "Cantidad de Digitos incorrecto";
				$data["mensaje"] = "Cantidad de Digitos incorrecto";
				$this->load->view('notificaciones/sin_permiso_view',$data);
			}
			else // cantidad de digitos correcta
			{
				if(!(is_numeric($codigo_a_buscar)))
				{
					echo "estoy en 2";
					$data["error"] = "No es un numero";
					$data["mensaje"] = "El valor ingresado no es numero";
					$this->load->view('notificaciones/sin_permiso_view',$data);
				}
				else
				{
					//compruebo el numero de seguridad
					$i=0;
					$acumulado = 0;
					for ($j=0; $j < cantidad_digitos-1 ; $j++) //sin el de seguridad
						$acumulado +=$codigo_a_buscar[$j] ;
					//var_dump($acumulado);die();
					//	var_dump($acumulado %7,$codigo_a_buscar[cantidad_digitos-1] );
					if (($acumulado %7) != intval($codigo_a_buscar[cantidad_digitos-1]))
					{
						//echo "estoy en4";
						$data["error"] = "Codigo Incorrecto";
						$data["mensaje"] = "Codigo Incorrecto";
						$this->load->view('notificaciones/sin_permiso_view',$data);
					}
					else 
						redirect('pago/agregar_por_codigo_barra'.'/'.$codigo_a_buscar,'refresh');
				}
			}
		}
		else 
		{
			$data["error"] = "Ingrese nuevamente el codigo";
			$this->load->view('bienvenido_view.php',$data);
		}
	}
	public function cargar_masivo() //MASIVO
	{
	/*		$cadena = "Fiesta Nacional del Sol";
		echo "Sin codificar: ".$cadena;
		echo "\n\n<br><br>";
		$codificada = $this->cifrar($cadena);
		echo "Ya codificada: ".$codificada;
		echo "\n\n<br><br>";
		echo "Decodificada: ".$this->decifrar($codificada);
		echo "\n\n<br><br>";
		echo "fin";die();*/
		//agrego un super for
		$para_insertar = array(
				'1' => "000011",
				'2' => "000022",
				'3' => "000033",
				'4' => "000044",
				'5' => "000055",
				'6' => "000066",
				'7' => "000070",
				'8' => "000081",
				'9' => "000092",
				'10' => "0000103",
				'11' => "000114",
				'12' => "000125",
				'13' => "000136",
				'14' => "000140",
				'15' => "000151",
				'16' => "000162",
				'17' => "000173",
				'18' => "000184",
				'19' => "000195",
				'20' => "000206",
				'21' => "000210",
				'22' => "000221" );
				for ($i=0; $i < 10000; $i++) { 
					$this->buscar_codigo_masivo($para_insertar[rand(1,22)]);
				}
	}
	public function buscar_codigo_masivo($id) //MASIVO
	{
		$codigo_a_buscar=$id;
		if(strlen($codigo_a_buscar)!=cantidad_digitos)
		{
					$data["error"] = "Cantidad de Digitos incorrecto";
					$this->load->view('bienvenido_view.php',$data);
		}
		else
		{
			if(!(is_numeric($codigo_a_buscar)))
			{
				$data["error"] = "No es un numero";
				$this->load->view('bienvenido_view.php',$data);
			}
			else
			{
					$i=0;
					$acumulado = 0;
					for ($j=0; $j < cantidad_digitos-1 ; $j++) //sin el de seguridad
						$acumulado +=$codigo_a_buscar[$j] ;
					if (($acumulado %7) != $codigo_a_buscar[cantidad_digitos-1] )
					{
						$data["error"] = "Codigo Incorrecto";
						$this->load->view('bienvenido_view.php',$data);
					}
					else //codigo sin errores
					{
						$sub_cadena = substr($codigo_a_buscar, 0,cantidad_disponible);
						$i=0;
						while($sub_cadena[$i] == 0)
							$i++;
						$sub_cadena = substr($sub_cadena, $i,cantidad_disponible);
						$codigo_a_buscar = $sub_cadena;
						$aux = $this->codigo_barra_model->buscar_persona($codigo_a_buscar);//traigo los datos de la base de datos
						if($aux!=false)
						{
							//busco el id del stan
							$nombre = $this->codigo_barra_model->buscar_stand_por_id($aux[0]["Per_ID"]); 
							//var_dump($data["id_stand"]);die();
							$data["id_stand"] = $nombre[0]["Acr_Sta_ID"];
							//busco los movimientos del dia
							$data["movimientos"] = $this->codigo_barra_model->buscar_movimientos($aux[0]["Per_ID"]); //traigo los movimientos
							//var_dump($data["movimientos"]);die();
							if($data["movimientos"]==false) //primer marcacion
							{
								$resultado_insercion = $this->codigo_barra_model->marcar_movimiento($codigo_a_buscar, $aux[0]["Per_ID"],"E",$data["id_stand"],1); //voy a marcar un movimiento
								//$resultado_actualizacion = $this->codigo_barra_model->actualizar_bandera($resultado_insercion); //voy a actualizar la bandera de sincronizacion
							}
							else //n-esima marcacion
							{
								$resultado_insercion = $this->codigo_barra_model->marcar_movimiento_sin_estado($codigo_a_buscar, $aux[0]["Per_ID"],$data["id_stand"],1); //voy a marcar un movimiento
								//	$resultado_actualizacion = $this->codigo_barra_model->actualizar_bandera($resultado_insercion); //voy a actualizar la bandera de sincronizacion
							}
							$data["movimientos"] = $this->codigo_barra_model->buscar_movimientos($aux[0]["Per_ID"]); //traigo los movimientos
						}
						else //perona inexistente
						{
							$data["error"] = "Persona Inexistente";
							$this->load->view('bienvenido_view.php',$data);
						}
					}
			}
		}
	}
}

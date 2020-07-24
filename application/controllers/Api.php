<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Api extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('Clientes_model');
	}
	public function index(){
		//Corregido : 18-7-20 - Diego
		//Usado: 18-7-20 - Diego
		//Como se usa: se accede por url o por  el boton del menu de la izquierda
		//Que es lo que hace: Esta funcion sirva para mostrar por pantalla los ultimos registros de la base de datos, es decir el log del sistema
		//Mejora: hacerlo mas veloz, 
		//Tabla: auditoria
		/*Pasos que hace
		Paso 1 - veo si esta logueado el usuario
		Paso 2 - creo las variables de la pagina
		Paso 3 - hago el super join
		Paso 4 - Carga pagina (views)
		*/
		echo "estoy dentro de las api, sin estar logueado";

	}

	public function test(){
		//Corregido : 18-7-20 - Diego
		//Usado: 18-7-20 - Diego
		//Como se usa: se accede por url o por  el boton del menu de la izquierda
		//Que es lo que hace: Esta funcion sirva para mostrar por pantalla los ultimos registros de la base de datos, es decir el log del sistema
		//Mejora: hacerlo mas veloz, 
		//Tabla: auditoria
		/*Pasos que hace
		Paso 1 - veo si esta logueado el usuario
		Paso 2 - creo las variables de la pagina
		Paso 3 - hago el super join
		Paso 4 - Carga pagina (views)
		*/
		echo "test --- stoy dentro de las api, sin estar logueado";
	}


	public function traer_los_clientes()
	{
		//Corregido : 23-7-20 - Diego
		//Usado: 23-7-20 - Diego
		//Como se usa: se accede por url
		//Que es lo que hace: Esta funcion sirva para enviarle los datos de los clientes a la aplicacion mobil
		//Mejora: hacerlo seguro
		//Tabla: clientes
		/*Pasos que hace
		Paso 1 - busca todos los clientes 
		Paso 2 - pasa el object de php a array
		Paso 3 - encode y lo envia
		*/
		//Paso 1 - busca todos los clientes 
		$data=$this->Clientes_model->buscar_clientes_desde_tarea(null);
		$clientes = array(); //creamos un array
		//Paso 2 - pasa el object de php a array
		foreach($data as $columna) 
		{ 
			$id	= $columna->Cli_Id;
			$conexion=$columna->Conexion_Id;
			$razon=$columna->Cli_RazonSocial;
			$direccion=$columna->Conexion_DomicilioSuministro;
			$clientes[] = array(
				'value'=> $razon, 
				'data' => $id, 
				'conexion' => $conexion,
				'direccion' => $direccion
			);
		}
		//Paso 3 - encode y lo envia
		echo json_encode($clientes);
	}
	public function celular()
	{
		//Corregido : 23-7-20 - Diego
		//Usado: 23-7-20 - Diego
		//Como se usa: se accede por url
		//Que es lo que hace: Esta funcion sirva para enviarle los datos de los clientes a la aplicacion mobil
		//Mejora: hacerlo seguro
		//Tabla: clientes
		/*Pasos que hace
		Paso 1 - busca todos los clientes 
		Paso 2 - pasa el object de php a array
		Paso 3 - encode y lo envia
		*/
		//Paso 1 - busca todos los clientes 
		$username = $_POST['username'];
		$password = $_POST['password'];


		//$data=$this->Clientes_model->buscar_clientes_desde_tarea(null);
		//$clientes = array("1", "jaime", "1234", "ventas"); //creamos un array
		$clientes = array("1", "jaime", "1234", "admin"); //creamos un array

		//id 1 username : jaime     password 1234     nivel ventas
		//id 3 username neo     password 4321     nivel admin
		//Paso 2 - pasa el object de php a array
		
		//Paso 3 - encode y lo envia
		//$algo = json_encode($clientes);
		echo json_encode($clientes);
		//echo json_decode($algo);

		/*
		datauser[0] = "1"
		datauser[1] = "jaime"
		datauser[2] = "1234"
		datauser[3] = "vemtas"
		*/

	}


	

	



}
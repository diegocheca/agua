<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seeder extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->database('default');
		$this->load->model("Crud_model");

		require_once 'vendor\fzaninotto\faker\src\autoload.php';
		//$this->load->model('codigo_barra_model');
	}
	public function index()
	{
		$this->load->view('bienvenido_view.php');
	}
	

	public function crear_mediciones_mes_fake($mes, $anio) 
	{
		$faker = Faker\Factory::create();

		// generate data by accessing properties
		echo $faker->name;
		  // 'Lucy Cechtelar';
		echo $faker->address;
		  // "426 Jordy Lodge
		  // Cartwrightshire, SC 88120-6700"
		echo $faker->text;
		  // Dolores sit sint laboriosam dolorem culpa et autem. Beatae nam sunt fugit
		  // et sit et mollitia sed.
		  // Fuga deserunt tempora facere magni omnis. Omnis quia temporibus laudantium
		  // sit minima sint.

		// voy a buscar todas las conexiones para el mes 2 y año 2018 
		//y en base a eso voy a tener todas las conexiones habilitadas y luego le voy a pegar los nuevos valores

		// busco todos los sectores o barrios
		$sectores = $this->Crud_model->get_data_sectores();
		// por cada sector, voy a conseguir todas sus mediciones
		foreach ($sectores as $sector) {
			//var_dump($sector->Conexion_Sector);die();
			//busco las mediciones para el mes 2 y anio 2018
			$mediciones_ejemplo = $this->Crud_model->get_sectores_query($sector->Conexion_Sector, 2, 2018 );
			//var_dump($mediciones_ejemplo);die();
			//por cada registro que encuentro , debo crear el nuevo registro con datos falsos y guardarlo en la bd
			foreach ($mediciones_ejemplo as $medicion) {

				/*//ejemplo de  $mediciones_ejemplo 
				["Conexion_Id"]=> string(1) "1" 
				["Conexion_Cliente_Id"]=> string(2) "19"
				["Conexion_DomicilioSuministro"]=> string(5) "gsdgs" 
				["Conexion_UnionVecinal"]=> string(1) "0" 
				["Conexion_Categoria"]=> string(8) "Familiar" 
				["Conexion_Deuda"]=> string(7) "-2781.1" 
				["Conexion_Multa"]=> string(6) "100.00" 
				["Conexion_Acuenta"]=> string(3) "100" 
				["Conexion_Sector"]=> string(18) "ASENTAMIENTO OLMOS" 
				["Conexion_Latitud"]=> string(1) "0" 
				["Conexion_Longuitud"]=> NULL 
				["Conexion_Bonificacion"]=> string(1) "0" 
				["Conexion_Observacion"]=> NULL 
				["Conexion_Habilitacion"]=> string(1) "1" 
				["Conexion_Borrado"]=> string(1) "0" 
				["Conexion_Timestamp"]=> string(19) "2018-05-19 21:56:00"
				["Medicion_Id"]=> string(5) "16128" 
				["Medicion_Conexion_Id"]=> string(1) "1" 
				["Medicion_Mes"]=> string(1) "2" 
				["Medicion_Anio"]=> string(4) "2018" 
				["Medicion_Anterior"]=> string(3) "156" 
				["Medicion_Actual"]=> string(3) "156" 
				["Medicion_Basico"]=> string(3) "100" 
				["Medicion_Excedente"]=> string(1) "0" 
				["Medicion_Importe"]=> string(1) "0" 
				["Medicion_Mts"]=> string(2) "10"
				["Medicion_IVA"]=> string(1) "0" 
				["Medicion_Porcentaje"]=> string(1) "0" 
				["Medicion_Tipo"]=> string(1) "0" 
				["Medicion_Recargo"]=> string(1) "0" 
				["Medicion_Observacion"]=> NULL 
				["Medicion_Habilitacion"]=> string(1) "1" 
				["Medicion_Borrado"]=> string(1) "0" 
				["Medicion_Timestamp"]=> string(19) "2018-03-07 10:56:56" }*/
				//creo el array fake
				$medicion_acutal_fake =  intval($medicion->Medicion_Actual) + intval($faker->numberBetween($min = 0, $max = 3000));
				$excedente_fake= intval($medicion_acutal_fake) - intval($medicion->Medicion_Actual) -  intval($medicion->Medicion_Basico);
				$importe_fake=0;
				if($medicion->Conexion_Categoria == "Familiar")
					$importe_fake  = $excedente_fake * 10; // $10 por cada mts excedido
				else $importe_fake  = $excedente_fake * 18; // $18 por cada mts excedido en el caso de las empresas
				$medicion_fake = array(
						'Medicion_Id' => null, 
						'Medicion_Conexion_Id' => $medicion->Conexion_Id, // lo tengo en el for
						'Medicion_Mes' => $mes, // parametro definido por la ulr,  
						'Medicion_Anio' => $anio, // parametro definido por la ulr,  
						'Medicion_Anterior' => intval($medicion->Medicion_Actual),  // seria anterior  -- en este caso fake, seria el de febrero 2018
						'Medicion_Actual' =>  $medicion_acutal_fake , // este el dato fake mio
						'Medicion_Basico' => $medicion->Medicion_Basico,
						'Medicion_Excedente' => $excedente_fake,
						'Medicion_Importe' => $importe_fake ,
						'Medicion_Mts' => $medicion->Medicion_Mts ,
						'Medicion_IVA' => 0,
						'Medicion_Porcentaje' => 0,
						'Medicion_Tipo' => $medicion->Medicion_Tipo,
						'Medicion_Recargo' => 0,
						'Medicion_Observacion' => null,
						'Medicion_Habilitacion' => 1,
						'Medicion_Borrado' => 0,
						'Medicion_Timestamp' => null
						);
			//	var_dump($medicion_fake);die();
					$id_medicion_recien_agregada = $this->Crud_model->insert_data("medicion",$medicion_fake);
					if($id_medicion_recien_agregada)
					{
						echo '\n Se agrego crrectamente la medicion BIEN \n';
					}
					else 
					{
						echo '\n NO Se agrego crrectamente la medicion  - ERROR \n';
					}

			}

		}
// mediciones de ejemplo :   Mostrando filas 0 - 24 (total de 830, La consulta tardó 0,0000 segundos.) [Medicion_Id: 16957... - 16933...]
		// despues del ejemplo Mostrando filas 0 - 24 (total de 829, La consulta tardó 0,0000 segundos.) [Medicion_Id: 17786... - 17762...]
	} 


	public function crear_deuda_multa($cantidad) 
	{
		//Usado: 18-7-20 - Diego
		//Corregido : 18-7-20 - Diego
		//Como se usa: se accede por url
		//Que es lo que hace: Esta funcion sirva para agregar muchos registros en la tabla log_deuda_multa , que luego se muestra en un timeline
		//  corre tantas veces como se pase el parametro
		//Mejora:
		//Tabla: log_deuda_multa
		/*Pasos que hace
		Paso 1 - crear array fake
		Paso 2 - lo guarda en la db
		*/

		$faker = Faker\Factory::create();

		// generate data by accessing properties
		
		$revisa = ["Si", "No"];
		$campo = ["Deuda", "Multa"];

		for ($i=0; $i < $cantidad ; $i++) {
			//Paso 1 - crear array fake
		$datos_fake = array(
						'log_deuda_multa_Id' => null, 
						'log_deuda_multa_Conexion_Id' => $faker->numberBetween($min = 1, $max = 1000),
						'log_deuda_multa_Valor_Anterior' => $faker->numberBetween($min = 1, $max = 10000),
						'log_deuda_multa_Valor_Actual' => $faker->numberBetween($min = 1, $max = 10000), 
						'log_deuda_multa_Campo' => $campo[ $faker->numberBetween($min = 0, $max = 1)], 
						'log_deuda_multa_Revisado' =>  $revisa[ $faker->numberBetween($min = 0, $max = 1)] ,
						'log_deuda_multa_Quien' => $faker->numberBetween($min = 1, $max = 8),
						'log_deuda_multa_Timestamp' =>  null,
						);
		//Paso 2 - lo guarda en la db
					$id_medicion_recien_agregada = $this->Crud_model->insert_data("log_deuda_multa",$datos_fake);
					if($id_medicion_recien_agregada)
					{
						echo '\n Se agrego crrectamente la medicion BIEN \n';
					}
					else 
					{
						echo '\n NO Se agrego crrectamente la medicion  - ERROR \n';
					}
			# code...
		}
		
	} 

}

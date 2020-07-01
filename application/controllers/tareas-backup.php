<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tareas extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('Usuarios_model');
	}

	public function index(){
		//echo "estoy aca en clientes index"; die();

		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:

			//$datos['clientes'] = $this->Crud_model->get_data("clientes");
			$datos['clientes'] = $this->Crud_model->get_data_sin_borrados("clientes", "Cli_Borrado");
		$datos['bajas'] = $this->Crud_model->get_cantidad_bajas("clientes");
		
		//var_dump($datos['clientes']);die();
			$datos['titulo'] = 'Lista de Clientes';
			$this->load->view('templates/header',$datos);
			$this->load->view('clientes/clientes', $datos);
			$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');
			$data ['aviso'] = $this->session->flashdata('aviso');
			//var_dump($data ['aviso']);
			if($data ['aviso'] != null)
			{
				//echo "estoy llamando";
				$this->load->view("templates/notificacion_view", $data);
			}
		endif;

	}
	public function getEventos(){
		$r = $this->Crud_model->getEventos();
		 for ($i=0; $i < sizeof($r); $i++) { 
		 	$r[$i]->url ="orden_trabajo/editar_orden_trabajo"."/".$r[$i]->id;
		 }
		//var_dump($r[0]->url);die();
		echo json_encode($r);
	}
	
	//Genera un Json para la seccion de Autocomplete en Crear Factura
	public function leer_clientes(){

		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:

			header('Content-Type: application/json');
			$valor=$_GET['query'];  //captura la variable que pasa el autocomplete
			//var_dump($valor);
			$data=$this->Clientes_model->buscar_clientes($valor);
			$clientes = array(); //creamos un array

			foreach($data as $columna) { 
				$id	=	$columna->Cli_Id;
			   $razon=$columna->Cli_RazonSocial;
			   $documento=$columna->Cli_NroDocumento;
			   $direccion=$columna->Cli_DomicilioPostal;
			    
			   $clientes[] = array(
			   	'value'=> $razon, 
			   	'data' => $id, 
			   	'nro_documento' => $documento, 
			   	'direccion' => $direccion
			   );
			 
			}

			$array = array("query" => "Unit", "suggestions" => $clientes);
			echo json_encode($array);
		endif;

	}

	public function guardar_evento(){

		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$id=$this->input->post("id_evento_nuevo");
			$inputFechaInicio=$this->input->post("fecha_inicio");
			$aux =  str_replace('/', '-', $inputFechaInicio);
			$fecha_inicio = date("Y-m-d", strtotime($aux));
			$inputFechaFin=$this->input->post("fecha_fin");
			$aux =  str_replace('/', '-', $inputFechaFin);
			$fecha_fin = date("Y-m-d", strtotime($aux));
			$titulo_evento_nuevo=$this->input->post("titulo_evento_nuevo");
			$aclaracion=$this->input->post("aclaracion");
			$procentaje=$this->input->post("procentaje");
			$estado=$this->input->post("estado");
			$duracion=$this->input->post("duracion");
			$materiales=$this->input->post("materiales");
			$color=$this->input->post("color");
			$todo_materiales = null;
			if($materiales != null)
			{
				if(sizeof($materiales) >1)
					for ($i=0; $i < sizeof($materiales) ; $i++) { 
								$todo_materiales.=$materiales[$i]."%";
							}
				else $todo_materiales = $materiales[0];
			}

			if($id == "-1") // entonces es nuevo
			{
				echo "estoy en 1, valor:".$id;
				$datos_eventos = array(
					'Evento_Id' => null,
					'Evento_FechaInicio' => $fecha_inicio,
					'Evento_FechaFin' => $fecha_fin,
					'Evento_Titulo' => $titulo_evento_nuevo,
					'Evento_Materiales' => $todo_materiales,
					'Evento_Estado' => $estado,
					'Evento_Porcentaje' => $procentaje,
					'Evento_Color' => $color,
					'Evento_Observacion' => $aclaracion,
					'Evento_Habilitacion' => 1,
					'Evento_Borrado' => 0,
					'Evento_Timestamp' => null
				);
				$resultado = $this->Crud_model->insert_data("evento", $datos_eventos);
			}
			else // es una modificacion
			{
				echo "estoy en 2, valor:".$id;
				$datos_eventos = array(
					//'Evento_Id' => $id,
					'Evento_FechaInicio' => $fecha_inicio,
					'Evento_FechaFin' => $fecha_fin,
					'Evento_Titulo' => $titulo_evento_nuevo,
					'Evento_Materiales' => $todo_materiales,
					'Evento_Estado' => $estado,
					'Evento_Porcentaje' => $procentaje,
					'Evento_Color' => $id,
					'Evento_Observacion' => $aclaracion,
					'Evento_Habilitacion' => 1,
					'Evento_Borrado' => 0,
					'Evento_Timestamp' => null
				);
				$resultado = $this->Crud_model->insert_data($datos_eventos, $id,"evento","Evento_Id" );
			}
			echo $resultado;
		endif;

	}
public function borrar_tarea($id)
{
	echo "la tarea a borrar es:".$id;
}

	public function guardar_tarea(){

		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$tarea=$this->input->post("tarea");
				$datos_tareas = array(
					'Tarea_Id' => null,
					'Tarea_Cuerpo' => $tarea,
					'Tarea_Estado' => "creada",
					'Tarea_Creador' => 1,
					'Tarea_Observacion' => null,
					'Tarea_Habilitacion' => 1,
					'Tarea_Borrado' => 0,
					'Tarea_Timestamp' => null
				);
				$resultado = $this->Crud_model->insert_data("tarea", $datos_tareas);
			// else // es una modificacion
			// {
			// 	echo "estoy en 2, valor:".$id;
			// 	$datos_eventos = array(
			// 		//'Evento_Id' => $id,
			// 		'Evento_FechaInicio' => $fecha_inicio,
			// 		'Evento_FechaFin' => $fecha_fin,
			// 		'Evento_Titulo' => $titulo_evento_nuevo,
			// 		'Evento_Materiales' => $todo_materiales,
			// 		'Evento_Estado' => $estado,
			// 		'Evento_Porcentaje' => $procentaje,
			// 		'Evento_Color' => $id,
			// 		'Evento_Observacion' => $aclaracion,
			// 		'Evento_Habilitacion' => 1,
			// 		'Evento_Borrado' => 0,
			// 		'Evento_Timestamp' => null
			// 	);
			// 	$resultado = $this->Crud_model->insert_data($datos_eventos, $id,"evento","Evento_Id" );
			// }
			echo $resultado;
		endif;

	}

	public function updEvento(){
		$param['id'] = $this->input->post('id');
		$param['fecini'] = $this->input->post('fecini');
		$param['fecfin'] = $this->input->post('fecfin');

		$r = $this->Crud_model->updEvento($param);

		echo $r;
	}

	public function deleteEvento(){
		$id = $this->input->post('id');
		$r = $this->Crud_model->deleteEvento($id);
		echo $r;
	}

	public function updEvento2(){
		$param['id'] = $this->input->post('id');
		$param['nome'] = $this->input->post('nom');
		$param['web'] = $this->input->post('web');

		$r = $this->Crud_model->updEvento2($param);

		echo $r;
	}

	public function generar_informe_tareas()
	{

	}

}
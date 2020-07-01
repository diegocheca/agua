<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tareas extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('Usuarios_model');
		$this->load->model('tareas_model');
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

public function guardar_evento()
{
	date_default_timezone_set("America/Argentina/Mendoza");
	$inputTarea = $this->input->post("inputTarea", true);
	$inputNombreUsuario = $this->input->post("inputNombreUsuario", true);
	$inputConexionId = $this->input->post("inputConexionId", true);
	$inputDireccion = $this->input->post("inputDireccion", true);
	$inputTecnico = $this->input->post("inputTecnico", true);
	$estado_evento_nuevo = $this->input->post("estado_evento_nuevo", true);
	$inputFechaaux=$this->security->xss_clean(strip_tags($this->input->post('fecha_inicio_evento_nuevo',true)));
	$aux =  str_replace('/', '-', $inputFechaaux);
	$inputFecha = date("Y-m-d", strtotime($aux));
	$inputFechaaux=$this->security->xss_clean(strip_tags($this->input->post('fecha_fin_evento_nuevo',true)));
	$aux =  str_replace('/', '-', $inputFechaaux);
	$fechafin = date("Y-m-d", strtotime($aux));
	$materiales = $this->input->post("materiales", true);
	$aux_materiales = array();
	for ($i=0; $i <= 5; $i++) { 
				$aux_materiales [$i] = 0;
				$aux_materiales_codigo [$i] = 0;
				$aux_materiales_cantidad [$i] = 0;
	}
	$i=0;
	if($materiales != null)
	{
						$i=1;
						foreach ($materiales as $key) {
							$aux_materiales [$i] = $key;
							$aux_materiales_codigo [$i] = $key;
							$aux_materiales_cantidad [$i] = 1;
							$i++;
						}
	}
	$inputObservacion = $this->input->post("inputObservacion", true);
	$id = $this->input->post("id", true);
	$datos_orden = array(
			'OrdenTrabajo_Id' => null, 
			'OrdenTrabajo_Tarea' => $inputTarea, 
			'OrdenTrabajo_Cliente' => $inputNombreUsuario, 
			'OrdenTrabajo_Direccion' => $inputDireccion, 
			'OrdenTrabajo_NConexion' => $inputConexionId,
			'OrdenTrabajo_Tecnico' => $inputTecnico,
			'OrdenTrabajo_FechaInicio' => $inputFecha,
			'OrdenTrabajo_FechaFin' => $fechafin,
			'OrdenTrabajo_Porcentaje' => 0,
			'OrdenTrabajo_Color' => null,
			'OrdenTrabajo_CodigoMaterial1' => $aux_materiales[0],
			'OrdenTrabajo_CodigoMaterial2' => $aux_materiales[1],
			'OrdenTrabajo_CodigoMaterial3' => $aux_materiales[2],
			'OrdenTrabajo_CodigoMaterial4' => $aux_materiales[3],
			'OrdenTrabajo_CodigoMaterial5' => $aux_materiales[4],
			'OrdenTrabajo_CantidadMaterial1' => 1,
			'OrdenTrabajo_CantidadMaterial2' => 1,
			'OrdenTrabajo_CantidadMaterial3' => 1,
			'OrdenTrabajo_CantidadMaterial4' => 1,
			'OrdenTrabajo_CantidadMaterial5' => 1,
			'OrdenTrabajo_Creador' => 1,
			'OrdenTrabajo_Estado' => $estado_evento_nuevo,
			'OrdenTrabajo_Observacion' => $inputObservacion,
			'OrdenTrabajo_Habilitacion' => 1,
			'OrdenTrabajo_Borrado' => 0,
			'OrdenTrabajo_Timestamp' => null
			);
	$id_orden_recien_insertado = $this->Crud_model->insert_data("ordenTrabajo",$datos_orden);
	// if(is_numeric( $id_orden_recien_insertado) )
	// {
	// 	$this->session->set_flashdata('aviso','Se guardo crrectamente la orden de trabajo');
	// 	$this->session->set_flashdata('tipo_aviso','success');
	// }
	// else 
	// {
	// 	$this->session->set_flashdata('aviso','NO se guardo correctamente la orden de trabajo');
	// 	$this->session->set_flashdata('tipo_aviso','danger');
	// }
	echo $id_orden_recien_insertado;
	//echo "true";
}
public function borrar_tarea($id)
{
	echo "la tarea a borrar es:".$id;
}


public function crear_informe_pantalla()
{
	$tareas = $this->tareas_model->traer_todas_tareas();
	if($tareas == false)
		echo '<div class="alert alert-danger">
                               Sin datos que mostrar
                            </div>';
	else
	{
		echo '<div class="table-responsive">
	            <table class="table">
	                <thead>
	                    <tr>
	                        <th>#</th>
	                        <th>Titulo</th>
	                        <th>Inicio</th>
	                        <th>Fin</th>
	                        <th>Porcentaje</th>
	                        <th>Realizador</th>
	                        <th>Accion</th>
	                    </tr>
	                </thead>
	                <tbody>
	                ';
	                $indice = 0;
	                foreach ($tareas as $key) 
	                {
	                	$indice ++;
	                	if($key->OrdenTrabajo_Estado == 1 ) //terminada
	                		echo '<tr class="success">';
	                	elseif($key->OrdenTrabajo_Estado == 2 ) //baja
	                		echo '<tr class="danger">';
	                	elseif($key->OrdenTrabajo_Estado == 3 ) //suspendida
	                		echo '<tr class="danger">';
	                	else
	                		echo '<tr>';
	                	echo '<td><a  target="_blank" href="http://192.168.1.61/codeigniter/orden_trabajo/editar_orden_trabajo/'.$key->OrdenTrabajo_Id.'">'.$indice.'</a></td>';
	                	echo '<td>'.$key->OrdenTrabajo_Tarea.'</td>';
	                	echo '<td>'.date( "d/m/Y ", strtotime($key->OrdenTrabajo_FechaInicio) ).'</td>';
	                	echo '<td>'.date( "d/m/Y ", strtotime($key->OrdenTrabajo_FechaFin) ).'</td>';
	                	echo '<td>'.$key->OrdenTrabajo_Porcentaje.'</td>';
	                	echo '<td>'.$key->OrdenTrabajo_Tecnico.'</td>';
	                	echo '<td><button type="button" class="btn btn-danger btn-circle" onclick="modal_borrar_orden_trabajo('.$key->OrdenTrabajo_Id.')"><i class="zmdi zmdi-minus"></i>
                            </button></td>';
	                    echo "</tr>";
	                }
	                    echo '
	                    
	                </tbody>
	            </table>
	        </div>
	';
	}

	
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// CONTROLADOR INVENTARIO
//////////////////////////

//class Tipos_medidores extends CI_Controller {
class Mediciones extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Crud_model");
	}


	// Pagina de Inventario
	public function index(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Usuarios', '/Mediciones');
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;

		// ./ agregar breadcrumbs

		$datos['titulo']= "Mediciones";//Titulo de la página
		$mes_buscado = intval(date("m"))-1;
		if($mes_buscado == 0)
			$mes_buscado =12;
		$anio_buscado = intval(date("Y"));
		$datos['consulta']=$this->Crud_model->get_mediciones_para_un_mes_con_join($mes_buscado, $anio_buscado);
		$this->load->view('templates/header',$datos);
		$data ['mensaje'] = $this->session->flashdata('aviso');
		if($data ['mensaje'] != null)// hay aviso
		{
			$data ['tipo'] = $this->session->flashdata('tipo_aviso');
			if($data ['tipo'] == "success")
			{
				$this->load->view("templates/notificacion_correcta_success", $data);
			}
			else
				$this->load->view("templates/notificacion_incorrecta_success", $data);
		}
		$this->load->view('medicion/mediciones',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');
		endif;
	}


	
	public function mediciones_a_aprobar(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Usuarios', '/Mediciones');
			$datos['bread']=$this->breadcrumbs->show();
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			$datos['titulo']= "Mediciones Raras";//Titulo de la página

			$datos["conexiones_a_imprimir"] = $this->Crud_model->buscar_conexion_a_imprmir_nuevo();
		
			// if(intval(date("d"))-1 >24)
			// 	$mes_buscado = intval(date("m"));
			// else $mes_buscado = intval(date("m"))-1;

			$datos['sector'] = $this->input->post('miselect');
			$datos['mes_buscado'] = $this->input->post('mes_select');
			$datos['anio'] = $this->input->post('anio_select_mediciones_raras');
			$datos['limite'] = $this->input->post('limite_raro');
			if( ($datos['limite'] == false) || ($datos['limite'] == -1) )$datos['limite'] = 25;
			if($datos['mes_buscado'] == false) $datos['mes_buscado'] = 2;
			if($datos['anio'] == false) $datos['anio'] = 2018;
			//if($datos['sector'] == false) $datos['limite'] = 25;
		//	var_dump($datos['sector'],$datos['mes_buscado'],$datos['anio'],$datos['limite']);die();



		//	$datos['mes_buscado'] = $mes_buscado;
			$datos['consulta']=$this->Crud_model->get_mediciones_a_aprobar($datos['sector'], $datos['mes_buscado'],$datos['anio'],$datos['limite']);
			//var_dump($datos['consulta']);die();
			if($datos['consulta'] == false)
			{
				$datos['mensaje'] =" no hay nada que mostrar aca, todas las mediciones han sido aprobadas";
				$this->load->view("notificaciones/sin_permiso_view", $datos);
			}
			else
			{
				//var_dump($datos['consulta']);die();
				$this->load->view('templates/header',$datos);
				$data ['mensaje'] = $this->session->flashdata('aviso');
				if($data ['mensaje'] != null)// hay aviso
				{
					$data ['tipo'] = $this->session->flashdata('tipo_aviso');
					if($data ['tipo'] == "success")
					{
						$this->load->view("templates/notificacion_correcta_success", $data);
					}
					else
						$this->load->view("templates/notificacion_incorrecta_success", $data);
				}
				$this->load->view('medicion/mediciones_raras',$datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
			}
			endif;
	}


	public function aprobar_medicion(){
		$id_medicion = $this->input->post("id_medicion");
		$actual = $this->input->post("Lectura_Actual");
		$tipo_conexion = $this->security->xss_clean(strip_tags($this->input->post('tipo_conexion_input')));
		$todas_las_variables = $this->Crud_model->get_data("configuracion");
		//var_dump($tipo_conexion);die();
		if( ($tipo_conexion == 1) || ($tipo_conexion == "Familiar") || ($tipo_conexion =="Familiar ") )
		{
			//echo "entrre familiar";die();
			$precio_metros = $todas_las_variables[3]->Configuracion_Valor;
			$metros_basicos = $todas_las_variables[5]->Configuracion_Valor;
			$precio_bsico = $todas_las_variables[4]->Configuracion_Valor;
		}
		else 
		{
		//	echo "entrre coemrcial";die();
			$precio_metros = $todas_las_variables[6]->Configuracion_Valor;
			$metros_basicos = $todas_las_variables[8]->Configuracion_Valor;
			$precio_bsico = $todas_las_variables[7]->Configuracion_Valor;
		}
		$anterior = $this->input->post("Lectura_Anterior");

		$inputExcedente = intval($actual) - intval($anterior) - intval($metros_basicos);
		if($inputExcedente < 0 )
			$inputExcedente = 0;
		//$actual = $this->input->post("Lectura_Actual");
		$importe_medicion = 0;
		if($inputExcedente == 0)
			$importe_medicion = 0;
		else $importe_medicion = floatval($precio_metros) * floatval($inputExcedente);

		$arrayName = array(
			'Medicion_Actual' => $actual,
			'Medicion_Observacion' => "Modificada y Aprobada",
			'Medicion_Excedente' => $inputExcedente,
			'Medicion_Importe' => floatval($importe_medicion),
			'Medicion_Habilitacion' => 1
			);
	//	var_dump($arrayName);die();

		$id_medicion_recien_modificada = $this->Crud_model->update_data($arrayName,$id_medicion,"medicion","Medicion_Id");
		if($id_medicion_recien_modificada)
		{
			$this->session->set_flashdata('aviso','Se modificó correctamente la medicion');
			$this->session->set_flashdata('tipo_aviso','success');
		}
		else 
		{
			$this->session->set_flashdata('aviso','NO se modificó la medicion');
			$this->session->set_flashdata('tipo_aviso','danger');
		}
		redirect(base_url('mediciones'), "refresh");
	}

	public function filtrar_mediciones_por_mes(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Usuarios', '/Mediciones');
		$datos['bread']=$this->breadcrumbs->show();
		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		$datos['titulo']= "Mediciones";//Titulo de la página
		$mes_buscado = $this->input->post('miselect');
		//echo $mes_buscado; die();
		$datos['consulta']=$this->Crud_model->get_mediciones_para_un_mes($mes_buscado);
		$this->load->view('templates/header',$datos);
		$data ['mensaje'] = $this->session->flashdata('aviso');
		if($data ['mensaje'] != null)// hay aviso
		{
			$data ['tipo'] = $this->session->flashdata('tipo_aviso');
			if($data ['tipo'] == "success")
			{
				$this->load->view("templates/notificacion_correcta_success", $data);
			}
			else
				$this->load->view("templates/notificacion_incorrecta_success", $data);
		}
		$this->load->view('medicion/mediciones',$datos);
		$this->load->view('templates/footer');
		$this->load->view('templates/footer_fin');
		endif;
	}

	public function borrar_mediciones()
	{
		$id=  $this->input->post("id");
		$resultado =  $this->Crud_model->borrar_medicion_para_siempre($id);
		echo true;
	}

	
	public function ejecutar_query()
	{
		$sectores=  $this->input->post("sectores");
		$mes = intval(date("m") );
		if( intval(date("d") ) < 25 )
			$mes = $mes -1;
		if( $mes < 1 )
			$mes = 12;
		$anio_actual = date("Y");
		//echo $mes;die();
		//echo "el emes es:".$anio_actual;die();
		$mediciones_desde_query =  $this->Crud_model->get_sectores_query($sectores, $mes, $anio_actual );
		//$mediciones_desde_query_mes_actual =  $this->Crud_model->get_sectores_query_mes_actual($sectores, intval(date("m")));
		//$resultado =  $this->Crud_model->get_sectores_query($sectores);
		$indice_actual = -1;
		//$indice_anterior = -1;
		$resultado = array( );
		if(sizeof($mediciones_desde_query)==1)
			$resultado =$mediciones_desde_query ;
		else 
			foreach ($mediciones_desde_query as $key ) {
				if (($indice_actual  == -1) || ($indice_actual != $key->Conexion_Id)){
					//cargo la primer medicion con el indice como se declaro
					$indice_actual = $key->Conexion_Id ;
					array_push($resultado,$key);
				}
			}

		$todas_las_variables = $this->Crud_model->get_data("configuracion");
		// var_dump($resultado);die();
		$cadena = null;
		$cantidad = null;
		$i = 0;
		if($resultado == false)
		{
			// significa que no hay datos en la base de datos
			$cadena.= 
			'<div class="alert alert-danger">
				Sin conexion en este sector.
			</div>';
		}
		elseif( sizeof($resultado)  == 1){
			//hay resultados que mostrar

			//var_dump($resultado);
			if(($resultado->Conexion_Categoria == 1) || ($resultado->Conexion_Categoria == "Familiar"))

			{
				$resultado->Conexion_Categoria = "Familiar";
				$metros_basicos = $todas_las_variables[5]->Configuracion_Valor;
			}
			else
			{
				$resultado->Conexion_Categoria = "Comercial";
				$metros_basicos = $todas_las_variables[8]->Configuracion_Valor;
			}
			$cadena.= 
			'<div data-repeater-list="productos" class="col-md-12 producto-container">
				<div data-repeater-item class="row">
					<div class="col-md-2">
						<div class="form-group fg-line">
							<label class="control-label" for="inputConexionId_'.$i.'">Conexion</label>
							<input type="text" id="inputConexionId_'.$i.'" name="inputConexionId_'.$i.'" value ="'.$resultado->Conexion_Id.'" class="form-control input-sm" readonly>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group fg-line">
							<label class="control-label" for="inputMedicionAnterior_'.$i.'">Anterior </label>
							<input type="text" id="inputMedicionAnterior_'.$i.'" name="inputMedicionAnterior_'.$i.'" value ="'.$resultado->Medicion_Anterior.'" class="form-control input-sm" readonly autocomplete="off">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group fg-line">
							<label class="control-label" for="inputMedicionActual_'.$i.'">Actual </label>
							<input type="text" id="inputMedicionActual_'.$i.'" name="inputMedicionActual_'.$i.'" class="form-control input-sm" placeholder="Solo Números" autocomplete="off" >
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group fg-line">
							<label class="control-label" for="inputExcedente_'.$i.'">Excedente </label>
							<input type="text" id="inputExcedente_'.$i.'" name="inputExcedente_'.$i.'" class="form-control input-sm" readonly autocomplete="off" value="0" >
							<input type="hidden" id="inputMetrosbasicos_'.$i.'" name="inputMetrosbasicos_'.$i.'" class="form-control input-sm"  value="'.$metros_basicos.'" autocomplete="off" >
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group fg-line">
							<label class="control-label" for="inputTipo_'.$i.'">Tipo Conexion </label>
							<input type="text" id="inputTipo_'.$i.'" name="inputTipo_'.$i.'" class="form-control input-sm"  value="'.$resultado->Conexion_Categoria.'" autocomplete="off"  readonly>
						</div>
					</div>
				</div>
			</div>';
			$i++;
		}
		else
		{
			//$cadena .= '<input type="text" id = "cantidad_errores_en_medicion_actual" value="0">';
			foreach ($resultado as $key) 
			{
				if( ($key->Conexion_Categoria == 1) || ($key->Conexion_Categoria == "Familiar") )
				{
					$key->Conexion_Categoria = "Familiar";
					$metros_basicos = $todas_las_variables[5]->Configuracion_Valor;
				}
				else
				{
					$key->Conexion_Categoria = "Comercial";
					$metros_basicos = $todas_las_variables[8]->Configuracion_Valor;
				}
				$cadena.= 
				' 
				<div data-repeater-list="productos" class="col-md-12 producto-container">
					<div data-repeater-item class="row">
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputConexionId_'.$i.'">Conexion</label>
								<input type="hidden" id="inputConexionId_'.$i.'" name="inputConexionId_'.$i.'" value ="'.$key->Conexion_Id.'" class="form-control input-sm" readonly>
								<input type="hidden" id="inputMedicionId_'.$i.'" name="inputMedicionId_'.$i.'" value ="'.$key->Medicion_Id.'" class="form-control input-sm" readonly>
								<p>'.$key->Conexion_Id.'</p>
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputMedicionAnterior_'.$i.'">Anterior </label>
								<input type="hidden" id="inputMedicionAnterior_'.$i.'" name="inputMedicionAnterior_'.$i.'" value =" '.$key->Medicion_Anterior.' " class="form-control input-sm" readonly autocomplete="off">
								<p>'.$key->Medicion_Anterior.'</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputMedicionActual_'.$i.'">Actual </label>
								<input type="text" id="inputMedicionActual_'.$i.'" name="inputMedicionActual_'.$i.'" class="form-control input-sm" placeholder="Solo Números" autocomplete="off"';
								if ( ($key->Medicion_Actual != null)  &&  ($key->Medicion_Actual != 0)  )
									$cadena.=  'readonly value=" '.$key->Medicion_Actual. ' " ';
								$cadena.=  '>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputExcedente_'.$i.'">Excedente </label>
								<input type="hidden" id="inputExcedente_'.$i.'" name="inputExcedente_'.$i.'" class="form-control input-sm" readonly autocomplete="off"';
								if ( ($key->Medicion_Excedente != null)  &&  ($key->Medicion_Excedente != 0)  )
									$cadena.=  'readonly value=" '.$key->Medicion_Excedente. ' " ';
								else $cadena.=  'value= "" ';
								$cadena.=  '>
								<p id="paragraphExcedente_'.$i.'">';
								if ( ($key->Medicion_Excedente != null)  &&  ($key->Medicion_Excedente != 0)  )
									$cadena.=  $key->Medicion_Excedente;
								$cadena.=  '
								 </p>
								<input type="hidden" id="inputTipo_'.$i.'" name="inputTipo_'.$i.'" class="form-control input-sm"  value="'.$key->Conexion_Categoria.'" autocomplete="off" >
								<input type="hidden" id="inputMetrosbasicos_'.$i.'" name="inputMetrosbasicos_'.$i.'" class="form-control input-sm"  value="'.$metros_basicos.'" autocomplete="off" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputTipo_'.$i.'">Tipo Conexion </label>
								<p>'.$key->Conexion_Categoria.'</p>
							</div>
						</div>
					</div>
					<hr style="background-color: #fff;border-top: 2px dashed #8c8b8b;">
				</div>';
				$i++;
			}
		}
		
		$cantidad.= 
			'<div class="col-md-2">
				<input type="hidden" id="inputCantidad" name="inputCantidad" value ="'.$i.'" class="form-control input-sm" readonly>
			</div>
			<script  type="text/javascript">
				$(document).ready(function(){
					$("[id^=\'inputMedicionActual_\']").change(function(e){
						e.preventDefault();
						var id_actual_actual = $(this).attr("id");
						var carga_actual = $(id_actual_actual).val();
						var id_valor = "#"+id_actual_actual;
						var valor_acutal = $(id_valor).val();  //medicion actual q estoy cargando
						var indice_id = id_actual_actual.split("_");

						var id_valor_anterior =  "#inputMedicionAnterior_"+indice_id[1]; 
						var valor_anterior = $(id_valor_anterior).val(); //medicion anterior

						var mtrs = "#inputMetrosbasicos_"+indice_id[1];
						var metros = $(mtrs).val(); 
						var id_excedente_actual = "#inputExcedente_"+indice_id[1]; 
						var id_p_excedente_actual = "#paragraphExcedente_"+indice_id[1]; 
						
						var valor =  parseFloat(valor_acutal) - parseFloat(valor_anterior);
						if( (valor >= 0) && (valor <= metros) )// entre 0 y metros basicos
							valor= 0;
						if(valor > metros)
							valor = parseFloat(valor) - parseFloat(metros);
						if(valor < 0){
							$(id_excedente_actual).css("background", "#E84F08");
							$(id_excedente_actual).css("border-color", "#E84F08");
							$(id_p_excedente_actual).css("background", "#FF4500");
							$(id_p_excedente_actual).css("border-color", "red");
							//$("#cantidad_errores_en_medicion_actual").val(parseInt($("#cantidad_errores_en_medicion_actual").val())+1);
						}
						else {
							//puede se q sea correcto pero excedido
							if( parseInt(valor) > parseInt(25) )
							{
								$(id_excedente_actual).css("background", "#F4A460");
								$(id_excedente_actual).css("border-color", "#F4A460");
								$(id_p_excedente_actual).css("background", "#FFAB05");
								$(id_p_excedente_actual).css("border-color", "#FFFA00");
							}
							else //sin exceso, es correcto
							{
								$(id_excedente_actual).css("background", "#F0F0F0");
								$(id_excedente_actual).css("border-color", "green");
								$(id_p_excedente_actual).css("background", "#00FA9A");
								$(id_p_excedente_actual).css("border-color", "green");
							}
							
						}
						$(id_excedente_actual).val(valor);
						$(id_p_excedente_actual).text(valor);
						//alert("actual:"+parseFloat(valor_acutal)+" metros:"+parseFloat(mtrs));
						});
				
				});
			</script>
			 ';
		$cadena .=$cantidad;
		echo $cadena;
	}

	public function cargar_mediciones_por_lote(){
		// if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
		// 	$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
		// 	redirect(base_url());
		// else:
			//obtiene la informacion del cliente en la base de datos
			$datos['sectores'] = $this->Crud_model->get_data_sectores();
			 //var_dump($datos['sectores']);die();
			//$datos['tipos'] = $this->Crud_model->get_data('tmedidor'); $datos['url'] =base_url()."mediciones/index";
			if ($datos['sectores']) {
				$datos['titulo'] = "Cargar Lote Mediciones";
				$this->load->view('templates/header', $datos);
				$this->load->view('medicion/agregar_lote_view', $datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
			}
			else
			{
				$this->session->set_flashdata("document_status",mensaje("La Medicion No existe","danger"));
				redirect('mediciones');
			}
		// endif;
	}

	public function modificar_medicion($id){
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//echo $id; die();
			$datos ['medicion'] = $this->Crud_model->get_medicion_id_sin_borrados($id);
		//var_dump($datos ['medicion']);die();
			if($datos ['medicion'] != false)
			{
				$datos['url'] =base_url()."mediciones/guardar_agregar";
				$datos['titulo'] = "Editar Medicion";
				$this->load->view('templates/header', $datos);
				$this->load->view('medicion/agregar', $datos);
				$this->load->view('templates/footer');
				$this->load->view('templates/footer_fin');
			}
			else
			{
				$this->session->set_flashdata("document_status",mensaje("La Medicion No existe","danger"));
				redirect('mediciones');
			}

			
		endif;
	}

	public function guardar_lote_medicion(){
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			$todas_las_variables = $this->Crud_model->get_data("configuracion");
			if($this->input->post())
			{
				$cantidad = $this->input->post("inputCantidad", true);
				//var_dump($cantidad);die();
				for($i = 0; $i < $cantidad; $i++){
					$imputConexionId = $this->input->post("inputConexionId_".$i, true);
					$inputMAnterior = $this->input->post("inputMedicionAnterior_".$i, true);
					$inputMActual = $this->input->post("inputMedicionActual_".$i, true);
					$inputExcedente = $this->input->post("inputExcedente_".$i, true); 
					$inputTipo = $this->input->post("inputTipo_".$i, true);
					if($inputTipo == 1)
						$basico = $todas_las_variables[5]->Configuracion_Valor;
					else $basico = $todas_las_variables[8]->Configuracion_Valor;
					if($inputTipo == 1)
						$preciomt = $todas_las_variables[3]->Configuracion_Valor;
					else $preciomt = $todas_las_variables[6]->Configuracion_Valor;
					if($inputTipo == 1)
						$metros = $todas_las_variables[3]->Configuracion_Valor;
					else $metros = $todas_las_variables[6]->Configuracion_Valor;
						//var_dump($basico);die();
					$datos_medicion = array(
						'Medicion_Id' => null, 
						'Medicion_Conexion_Id' => $imputConexionId, 
						'Medicion_Mes' => date("m"), 
						'Medicion_Anio' => date("Y"), 
						'Medicion_Anterior' => $inputMAnterior, 
						'Medicion_Actual' => $inputMActual,
						'Medicion_Basico' =>  $basico,
						'Medicion_Excedente' => $inputExcedente,
						'Medicion_Importe' => $inputExcedente * $preciomt ,
						'Medicion_Mts' => $basico,
						'Medicion_IVA' => 0,
						'Medicion_Porcentaje' => 0,
						'Medicion_Tipo' => $inputTipo,
						'Medicion_Recargo' => 0,
						'Medicion_Observacion' => null,
						'Medicion_Habilitacion' => 1,
						'Medicion_Borrado' => 0,
						'Medicion_Timestamp' => null
						);
					$resultado = $this->Crud_model->insert_data("medicion",$datos_medicion);
					//var_dump($id_medicion_recien_agregada);die();
					if($resultado)
					{
						$this->session->set_flashdata('aviso','Se guardo crrectamente la mediciones nuevas');
						$this->session->set_flashdata('tipo_aviso','success');
					}
					else 
					{
						$this->session->set_flashdata('aviso','NO se guardaron las mediciones cargadas');
						$this->session->set_flashdata('tipo_aviso','danger');
					}
					redirect(base_url("mediciones"), "refresh");
				}
			}
		endif;
	}



	public function cargar_medicion_parametro($id_conexion, $actual){
		$todas_las_variables = $this->Crud_model->get_data("configuracion");
		//var_dump($cantidad);die();
		$imputConexionId = $id_conexion;
		//$inputMActual = $actual;
		//busco los demas datos de la conexion
		$datos_conexion = $this->Crud_model->get_data_row("conexion","Conexion_Id",$imputConexionId);
		//busco los demas datos de la medicion
		$datos_medicion = $this->Crud_model->get_data_row_tres_campos("medicion","Medicion_Conexion_Id",$imputConexionId, "Medicion_Mes",1, "Medicion_Anio", 2018);
		

		//calculo el excedente
		if( ($datos_conexion->Conexion_Categoria == 1) || ($datos_conexion->Conexion_Categoria == "Familiar") || ($datos_conexion->Conexion_Categoria =="Familiar ") )
			{
				$precio_metros = $todas_las_variables[3]->Configuracion_Valor;
				$metros_basicos = $todas_las_variables[5]->Configuracion_Valor;
				$precio_bsico = $todas_las_variables[4]->Configuracion_Valor;
			}
			else 
			{
				$precio_metros = $todas_las_variables[6]->Configuracion_Valor;
				$metros_basicos = $todas_las_variables[8]->Configuracion_Valor;
				$precio_bsico = $todas_las_variables[7]->Configuracion_Valor;
			}
			$anterior = $datos_medicion->Medicion_Anterior;
			// $actual = $key->Medicion_Actual; viene por paramtetro
			$inputExcedente = intval($actual) - intval($anterior) - intval($metros_basicos);
			if($inputExcedente < 0 )
				$inputExcedente = 0;
			//$actual = $this->input->post("Lectura_Actual");
			$importe_medicion = 0;
			if($inputExcedente == 0)
				$importe_medicion = 0;
			else $importe_medicion = floatval($precio_metros) * floatval($inputExcedente);

			//$inputTipo = $this->input->post("inputTipo_".$i, true);
			// if($inputTipo == 1)
			// 	$basico = $todas_las_variables[5]->Configuracion_Valor;
			// else $basico = $todas_las_variables[8]->Configuracion_Valor;
			// if($inputTipo == 1)
			// 	$preciomt = $todas_las_variables[3]->Configuracion_Valor;
			// else $preciomt = $todas_las_variables[6]->Configuracion_Valor;
			// if($inputTipo == 1)
			// 	$metros = $todas_las_variables[3]->Configuracion_Valor;
			// else $metros = $todas_las_variables[6]->Configuracion_Valor;
			//var_dump($basico);die();
			$datos_medicion = array(
				'Medicion_Id' => null, 
				'Medicion_Conexion_Id' => $imputConexionId, 
				'Medicion_Mes' => 2, 
				'Medicion_Anio' => 2018, 
				'Medicion_Anterior' => $anterior, 
				'Medicion_Actual' => $actual,
				'Medicion_Basico' =>  $precio_bsico,
				'Medicion_Excedente' => $inputExcedente,
				'Medicion_Importe' => $importe_medicion,
				'Medicion_Mts' => $metros_basicos,
				'Medicion_IVA' => 0,
				'Medicion_Porcentaje' => 0,
				'Medicion_Tipo' => 0,
				'Medicion_Recargo' => 0,
				'Medicion_Observacion' => null,
				'Medicion_Habilitacion' => 1,
				'Medicion_Borrado' => 0,
				'Medicion_Timestamp' => null
				);
			$resultado = $this->Crud_model->insert_data("medicion",$datos_medicion);
			echo "Insertado:".$resultado;
	}




	public function guardar_mediciones_por_lotes_con_ajax(){
		$todas_las_variables = $this->Crud_model->get_data("configuracion");
		$cantidad = $this->input->post("cantidad_de_input", true);
		for($i = 0; $i < $cantidad; $i++)
		{
			$imputConexionId = $this->input->post("inputConexionId_".$i, true);
			$inputMedicionId = $this->input->post("inputMedicionId_".$i, true);
			$inputMAnterior = $this->input->post("inputMedicionAnterior_".$i, true);
			$inputMActual = $this->input->post("inputMedicionActual_".$i, true);
			if($inputMActual == null ) // si el valor no fue cargado
			{
				echo "        macutal con nada       --";
				continue;
			}	
			// if( $this->Crud_model->get_mediciones_para_un_mes_id(  date("m"),$imputConexionId) != false )// si la carga ya se habia hecho antes
			// {
			// 	echo "        mes ya cargado       --";
			// 	continue;
			// }	
			$inputExcedente = $this->input->post("inputExcedente_".$i, true); 
			$inputTipo = $this->input->post("inputTipo_".$i, true);
			if($inputTipo == 1)
				$basico = $todas_las_variables[5]->Configuracion_Valor;
			else $basico = $todas_las_variables[8]->Configuracion_Valor;
			if($inputTipo == 1)
				$preciomt = $todas_las_variables[3]->Configuracion_Valor;
			else $preciomt = $todas_las_variables[6]->Configuracion_Valor;
			if($inputTipo == 1)
				$metros = $todas_las_variables[3]->Configuracion_Valor;
			else $metros = $todas_las_variables[6]->Configuracion_Valor;
			$habilitacion = 1;
			$observaciones = null;
			if( intval($inputExcedente) > 25)
			{
				$habilitacion = 0;
				$observaciones = "Se recomienda revisar este consumo porque excede el promedio";
			}
			$datos_medicion = array(
				//'Medicion_Id' => null, 
				// 'Medicion_Conexion_Id' => $imputConexionId, 
				// 'Medicion_Mes' => intval (date("m"))-1, 
				// 'Medicion_Anio' => date("Y"), 
				// 'Medicion_Anterior' => $inputMAnterior, 
				'Medicion_Actual' => $inputMActual,
				// 'Medicion_Basico' =>  $basico,
				'Medicion_Excedente' => $inputExcedente,
				'Medicion_Importe' => floatval($inputExcedente) * floatval($preciomt),
				// 'Medicion_Mts' => $basico,
				// 'Medicion_IVA' => 0,
				// 'Medicion_Porcentaje' => 0,
				// 'Medicion_Tipo' => $inputTipo,
				// 'Medicion_Recargo' => 0,
				'Medicion_Observacion' => $observaciones,
				'Medicion_Habilitacion' => $habilitacion,
				// 'Medicion_Borrado' => 0,
				'Medicion_Timestamp' => null
				);
			// echo "por guardar";
			$resultado = $this->Crud_model->update_data($datos_medicion, $inputMedicionId, "medicion", "Medicion_Id");
			//echo "Resultado: ".$resultado. "  /Valor agregado: ".$inputMActual."  /Valor exc:  ".$inputExcedente. "   /Id MEdicion:".$inputMedicionId;
			echo $resultado;
		}
	}
	public function corregir_mediciones()
	{
		$sectores = "Aberanstain";
		$todas_las_variables = $this->Crud_model->get_data("configuracion");
		$mediciones_desde_query =  $this->Crud_model->get_sectores_query_corregir($sectores, 1, 2018 );
		foreach ($mediciones_desde_query as $key ) {
			if( ($key->Conexion_Categoria == 1) || ($key->Conexion_Categoria == "Familiar") || ($key->Conexion_Categoria =="Familiar ") )
			{
				$precio_metros = $todas_las_variables[3]->Configuracion_Valor;
				$metros_basicos = $todas_las_variables[5]->Configuracion_Valor;
				$precio_bsico = $todas_las_variables[4]->Configuracion_Valor;
			}
			else 
			{
				$precio_metros = $todas_las_variables[6]->Configuracion_Valor;
				$metros_basicos = $todas_las_variables[8]->Configuracion_Valor;
				$precio_bsico = $todas_las_variables[7]->Configuracion_Valor;
			}
			$anterior = $key->Medicion_Anterior;
			$actual = $key->Medicion_Actual;
			$inputExcedente = intval($actual) - intval($anterior) - intval($metros_basicos);
			if($inputExcedente < 0 )
				$inputExcedente = 0;
			//$actual = $this->input->post("Lectura_Actual");
			$importe_medicion = 0;
			if($inputExcedente == 0)
				$importe_medicion = 0;
			else $importe_medicion = floatval($precio_metros) * floatval($inputExcedente);

			$arrayName = array(
				//'Medicion_Actual' => $actual,
				'Medicion_Observacion' => "Corregida y Aprobada",
				'Medicion_Excedente' => $inputExcedente,
				'Medicion_Importe' => floatval($importe_medicion),
				'Medicion_Habilitacion' => 1
				);
		//	var_dump($arrayName);die();

			$id_medicion_recien_modificada = $this->Crud_model->update_data($arrayName,$key->Medicion_Id,"medicion","Medicion_Id");
		

			// if($key->Medicion_Actual -  $key->Medicion_Anterior < 10)
			// {
			// 	$datos_medicion = array(
			// 	//'Medicion_Id' => null, 
			// 	// 'Medicion_Conexion_Id' => $imputConexionId, 
			// 	// 'Medicion_Mes' => intval (date("m"))-1, 
			// 	// 'Medicion_Anio' => date("Y"), 
			// 	// 'Medicion_Anterior' => $inputMAnterior, 
			// //	'Medicion_Actual' => $inputMActual,
			// 	// 'Medicion_Basico' =>  $basico,
			// 	//'Medicion_Excedente' => $key->Medicion_Excedente+5,//if excenete es 0 no sumo
			// 	'Medicion_Excedente' => 0,//if excenete es 0 no sumo
			// 	'Medicion_Importe' => floatval($key->Medicion_Excedente+5) * floatval(5),
			// 	// 'Medicion_Mts' => $basico,
			// 	// 'Medicion_IVA' => 0,
			// 	// 'Medicion_Porcentaje' => 0,
			// 	// 'Medicion_Tipo' => $inputTipo,
			// 	// 'Medicion_Recargo' => 0,
			// 	//'Medicion_Observacion' => $observaciones,
			// 	//'Medicion_Habilitacion' => $habilitacion,
			// 	// 'Medicion_Borrado' => 0,
			// 	'Medicion_Timestamp' => null
			// 	);
			// // echo "por guardar";
			// $resultado = $this->Crud_model->update_data($datos_medicion,  $key->Medicion_Id, "medicion", "Medicion_Id");
			// }
			 
			
		
			# code...
		}
			


	}
	public function guardar_agregar()
	{
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			if($this->input->post())
			{
				$imputConexionId = $this->input->post("inputConexionId", true);
				
				$inputMes = $this->input->post("inputMes", true);
				$inputAnio = $this->input->post("inputAnio", true);

				$inputMAnterior = $this->input->post("inputMAnterior", true);
				$inputMActual = $this->input->post("inputMActual", true);
				$inputExcedente = $this->input->post("inputExcedente", true); 
				$inputTipo = $this->input->post("inputTipo", true);
				if($inputTipo == 1) // 1 es familiar
				{
					$importe  =  $inputExcedente * precio_mt_fam;
					$basico = mts_basicos_familiar;
				}
				else // 2 comercial
				{
					$basico = mts_basicos_comercio;
					$importe  =  $inputExcedente * precio_mt_com;
				} 
				$id = $this->input->post("id", true);
				if($id == -1) // agregar medicion
				{
					$datos_medicion = array(
						'Medicion_Id' => null, 
						'Medicion_Conexion_Id' => $imputConexionId, 
						'Medicion_Mes' => $inputMes, 
						'Medicion_Anio' => $inputAnio, 
						'Medicion_Anterior' => $inputMAnterior, 
						'Medicion_Actual' => $inputMActual,
						'Medicion_Basico' => $basico,
						'Medicion_Excedente' => $inputExcedente,
						'Medicion_Importe' => $importe ,
						'Medicion_Mts' => $basico ,
						'Medicion_IVA' => 0,
						'Medicion_Porcentaje' => 0,
						'Medicion_Tipo' => $inputTipo,
						'Medicion_Recargo' => 0,
						'Medicion_Observacion' => null,
						'Medicion_Habilitacion' => 1,
						'Medicion_Borrado' => 0,
						'Medicion_Timestamp' => null
						);
					$id_medicion_recien_agregada = $this->Crud_model->insert_data("medicion",$datos_medicion);
					if($id_medicion_recien_agregada)
					{
						$this->session->set_flashdata('aviso','Se agrego crrectamente la medicion');
						$this->session->set_flashdata('tipo_aviso','success');
					}
					else 
					{
						$this->session->set_flashdata('aviso','NO se guardo la medicion');
						$this->session->set_flashdata('tipo_aviso','danger');
					}
				}
				else  //modificar usuario existente
				{
					$datos_medicion = array(
						'Medicion_Conexion_Id' => $imputConexionId, 
						'Medicion_Mes' => $inputMes, 
						'Medicion_Anio' => $inputAnio, 
						'Medicion_Anterior' => $inputMAnterior, 
						'Medicion_Actual' => $inputMActual,
						'Medicion_Excedente' => $inputExcedente,
						'Medicion_Borrado' => 0
						);
					$id_medicion_recien_modificada = $this->Crud_model->update_data($datos_medicion,$id,"medicion","Medicion_Id");
					if($id_medicion_recien_modificada)
					{
						$this->session->set_flashdata('aviso','Se modificó correctamente la medicion');
						$this->session->set_flashdata('tipo_aviso','success');
					}
					else 
					{
						$this->session->set_flashdata('aviso','NO se modificó la medicion');
						$this->session->set_flashdata('tipo_aviso','danger');
					}
				}
			redirect(base_url('mediciones'), "refresh");
			}
		endif;
	}

	public function leer_conexiones()
	{
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			header('Content-Type: application/json');
			$valor=$_GET['query'];
			$data=$this->Crud_model->buscar_conexiones_desde_plan_pago($valor);
			$clientes = array(); 
			$datos=$this->Crud_model->buscar_medicion_anterior($valor);

			foreach($data as $columna) 
			{ 
				//$data2=$this->Crud_model->buscar_medicion_anterior($valor);
				$id	=	$columna->Conexion_Id;
				$razon=$columna->Cli_RazonSocial;
				$documento=$columna->Cli_NroDocumento;
				$direccion=$columna->Cli_DomicilioPostal;
				$domicilio=$columna->Conexion_DomicilioSuministro;
				if($datos != false)
				{
					$anterior = $datos->Medicion_Anterior;
					$mes = $datos->Medicion_Mes + 1;
					$anio = $datos->Medicion_Anio;
				}
				else
				{
					$anterior = 0;	
					$mes = date("m");
					$anio = date("Y");
				} 
				$tipo_c=$columna->Conexion_Categoria;
				$clientes[] = array(
					'value'=> $razon, 
					'data' => $id, 
					'nro_documento' => $documento, 
					'direccion' => $direccion,
					'domicilio' => $domicilio,
					'mes' => $mes,
					'anio' => $anio,
					'tipo' => $tipo_c,
					'anterior' => $anterior
				);
			}
			$array = array("query" => "Unit", "suggestions" => $clientes);
			echo json_encode($array);
		endif;
	}

	public function agregar_medicion(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Mediciones', '/mediciones');
			$this->breadcrumbs->push('Agregar Usuarios', '/usuarios/agregar_usuario');

			// salida
			$datos['bread']=$this->breadcrumbs->show();

			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;

			//$datos['tipos'] = $this->Crud_model->get_data_row('tmedidor',"TMedidor_Id",);

			$datos['titulo']="Agregar Nuevo Usuarios";
			$this->load->view('templates/header',$datos);
			$this->load->view('medicion/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}
	public function cargar_mediciones_por_lote_desde_archivo()
	{


		$array_mediciones = array();
			

			/*		$array_mediciones [1] = 
			(
				array(
				"cliente_id" => 19,
				"conexion_id" => 1,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 156,
				"anterior" =>156,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [2] = 
			(
				array(
				"cliente_id" => 62,
				"conexion_id" => 2,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 146,
				"anterior" =>1445,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [3] = 
			(
				array(
				"cliente_id" => 63,
				"conexion_id" => 3,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1131,
				"anterior" =>1120,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [4] = 
			(
				array(
				"cliente_id" => 743,
				"conexion_id" => 6,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 272,
				"anterior" =>263,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [5] = 
			(
				array(
				"cliente_id" => 702,
				"conexion_id" => 8,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5221,
				"anterior" =>5210,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [6] = 
			(
				array(
				"cliente_id" => 24,
				"conexion_id" => 9,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 160,
				"anterior" =>160,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [7] = 
			(
				array(
				"cliente_id" => 722,
				"conexion_id" => 10,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6088,
				"anterior" =>6045,
				"basico" => 98.00,
				"excedente" => 165.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [8] = 
			(
				array(
				"cliente_id" => 56,
				"conexion_id" => 11,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 980,
				"anterior" =>946,
				"basico" => 98.00,
				"excedente" => 120.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [9] = 
			(
				array(
				"cliente_id" => 55,
				"conexion_id" => 12,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3,
				"anterior" =>3,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [10] = 
			(
				array(
				"cliente_id" => 6,
				"conexion_id" => 13,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7428,
				"anterior" =>7379,
				"basico" => 98.00,
				"excedente" => 195.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [11] = 
			(
				array(
				"cliente_id" => 26,
				"conexion_id" => 14,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7863,
				"anterior" =>7851,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [12] = 
			(
				array(
				"cliente_id" => 26,
				"conexion_id" => 15,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>98.00,
				"basico" => 0.00 ,
				"excedente" => 98.00,
				"importe" => 10.00,
				"mts" => 00
				)
			);
			$array_mediciones [13] = 
			(
				array(
				"cliente_id" => 750,
				"conexion_id" => 16,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1757,
				"anterior" =>1757,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [14] = 
			(
				array(
				"cliente_id" => 54,
				"conexion_id" => 17,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7985,
				"anterior" =>7946,
				"basico" => 98.00,
				"excedente" => 145.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [15] = 
			(
				array(
				"cliente_id" => 701,
				"conexion_id" => 18,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 284,
				"anterior" =>252,
				"basico" => 98.00,
				"excedente" => 110.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [16] = 
			(
				array(
				"cliente_id" => 29,
				"conexion_id" => 19,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 822,
				"anterior" =>789,
				"basico" => 98.00,
				"excedente" => 115.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [17] = 
			(
				array(
				"cliente_id" => 11,
				"conexion_id" => 20,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 641,
				"anterior" =>641,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [18] = 
			(
				array(
				"cliente_id" => 30,
				"conexion_id" => 21,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 174,
				"anterior" =>155,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [19] = 
			(
				array(
				"cliente_id" => 31,
				"conexion_id" => 22,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1897,
				"anterior" =>1897,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [20] = 
			(
				array(
				"cliente_id" => 32,
				"conexion_id" => 24,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 327,
				"anterior" =>326,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [21] = 
			(
				array(
				"cliente_id" => 33,
				"conexion_id" => 25,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 393,
				"anterior" =>371,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [22] = 
			(
				array(
				"cliente_id" => 2,
				"conexion_id" => 26,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 170,
				"anterior" =>170,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [23] = 
			(
				array(
				"cliente_id" => 654,
				"conexion_id" => 27,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 3883,
				"anterior" =>3793,
				"basico" => 200.00,
				"excedente" => 600.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [24] = 
			(
				array(
				"cliente_id" => 8,
				"conexion_id" => 28,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8553,
				"anterior" =>8505,
				"basico" => 98.00,
				"excedente" => 190.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [25] = 
			(
				array(
				"cliente_id" => 53,
				"conexion_id" => 31,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 9941,
				"anterior" =>9941,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [26] = 
			(
				array(
				"cliente_id" => 53,
				"conexion_id" => 32,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2021,
				"anterior" =>1949,
				"basico" => 98.00,
				"excedente" => 310.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [27] = 
			(
				array(
				"cliente_id" => 51,
				"conexion_id" => 34,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3426,
				"anterior" =>3426,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [28] = 
			(
				array(
				"cliente_id" => 71,
				"conexion_id" => 35,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5553,
				"anterior" =>5541,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [29] = 
			(
				array(
				"cliente_id" => 150,
				"conexion_id" => 36,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4390,
				"anterior" =>4372,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [30] = 
			(
				array(
				"cliente_id" => 49,
				"conexion_id" => 37,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4483,
				"anterior" =>4483,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [31] = 
			(
				array(
				"cliente_id" => 48,
				"conexion_id" => 38,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2352,
				"anterior" =>2347,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [32] = 
			(
				array(
				"cliente_id" => 108,
				"conexion_id" => 39,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3815,
				"anterior" =>3812,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [33] = 
			(
				array(
				"cliente_id" => 68,
				"conexion_id" => 40,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 12,
				"anterior" =>12,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [34] = 
			(
				array(
				"cliente_id" => 616,
				"conexion_id" => 41,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 4937,
				"anterior" =>4937,
				"basico" => 200.00,
				"excedente" => 0.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [35] = 
			(
				array(
				"cliente_id" => 9,
				"conexion_id" => 42,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3762,
				"anterior" =>3751,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [36] = 
			(
				array(
				"cliente_id" => 498,
				"conexion_id" => 45,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 3050,
				"anterior" =>2920,
				"basico" => 200.00,
				"excedente" => 920.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [37] = 
			(
				array(
				"cliente_id" => 4,
				"conexion_id" => 46,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1690,
				"anterior" =>1616,
				"basico" => 98.00,
				"excedente" => 320.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [38] = 
			(
				array(
				"cliente_id" => 2,
				"conexion_id" => 47,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>5079,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [39] = 
			(
				array(
				"cliente_id" => 623,
				"conexion_id" => 48,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3726,
				"anterior" =>3638,
				"basico" => 98.00,
				"excedente" => 390.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [40] = 
			(
				array(
				"cliente_id" => 702,
				"conexion_id" => 49,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 476,
				"anterior" =>474,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [41] = 
			(
				array(
				"cliente_id" => 43,
				"conexion_id" => 50,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 120,
				"anterior" =>120,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [42] = 
			(
				array(
				"cliente_id" => 141,
				"conexion_id" => 52,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7,
				"anterior" =>7,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [43] = 
			(
				array(
				"cliente_id" => 73,
				"conexion_id" => 53,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5707,
				"anterior" =>5707,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [44] = 
			(
				array(
				"cliente_id" => 3,
				"conexion_id" => 54,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2715,
				"anterior" =>2701,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [45] = 
			(
				array(
				"cliente_id" => 145,
				"conexion_id" => 55,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7481,
				"anterior" =>7472,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [46] = 
			(
				array(
				"cliente_id" => 39,
				"conexion_id" => 57,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5140,
				"anterior" =>5129,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [47] = 
			(
				array(
				"cliente_id" => 76,
				"conexion_id" => 59,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 9891,
				"anterior" =>9891,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [48] = 
			(
				array(
				"cliente_id" => 151,
				"conexion_id" => 60,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5983,
				"anterior" =>5983,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [49] = 
			(
				array(
				"cliente_id" => 60,
				"conexion_id" => 62,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4450,
				"anterior" =>4450,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [50] = 
			(
				array(
				"cliente_id" => 77,
				"conexion_id" => 63,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 875,
				"anterior" =>870,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [51] = 
			(
				array(
				"cliente_id" => 78,
				"conexion_id" => 64,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3242,
				"anterior" =>3233,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [52] = 
			(
				array(
				"cliente_id" => 79,
				"conexion_id" => 65,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1424,
				"anterior" =>1424,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [53] = 
			(
				array(
				"cliente_id" => 703,
				"conexion_id" => 66,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 10763,
				"anterior" =>10132,
				"basico" => 200.00,
				"excedente" => 4928.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [54] = 
			(
				array(
				"cliente_id" => 143,
				"conexion_id" => 67,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1999,
				"anterior" =>1999,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [55] = 
			(
				array(
				"cliente_id" => 81,
				"conexion_id" => 68,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6221,
				"anterior" =>6221,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [56] = 
			(
				array(
				"cliente_id" => 81,
				"conexion_id" => 69,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 6671,
				"anterior" =>6671,
				"basico" => 200.00,
				"excedente" => 0.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [57] = 
			(
				array(
				"cliente_id" => 719,
				"conexion_id" => 70,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 502,
				"anterior" =>468,
				"basico" => 98.00,
				"excedente" => 120.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [58] = 
			(
				array(
				"cliente_id" => 83,
				"conexion_id" => 71,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>0,
				"basico" => 0.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [59] = 
			(
				array(
				"cliente_id" => 999,
				"conexion_id" => 72,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>0,
				"basico" => 0.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [60] = 
			(
				array(
				"cliente_id" => 84,
				"conexion_id" => 73,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1476,
				"anterior" =>1465,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [61] = 
			(
				array(
				"cliente_id" => 83,
				"conexion_id" => 74,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3085,
				"anterior" =>3085,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [62] = 
			(
				array(
				"cliente_id" => 85,
				"conexion_id" => 75,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [63] = 
			(
				array(
				"cliente_id" => 86,
				"conexion_id" => 76,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [64] = 
			(
				array(
				"cliente_id" => 87,
				"conexion_id" => 77,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7520,
				"anterior" =>7511,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [65] = 
			(
				array(
				"cliente_id" => 83,
				"conexion_id" => 78,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2401,
				"anterior" =>2401,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [66] = 
			(
				array(
				"cliente_id" => 83,
				"conexion_id" => 79,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3492,
				"anterior" =>3470,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [67] = 
			(
				array(
				"cliente_id" => 89,
				"conexion_id" => 81,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5965,
				"anterior" =>5965,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [68] = 
			(
				array(
				"cliente_id" => 90,
				"conexion_id" => 82,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7545,
				"anterior" =>7545,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [69] = 
			(
				array(
				"cliente_id" => 129,
				"conexion_id" => 83,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2151,
				"anterior" =>2145,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [70] = 
			(
				array(
				"cliente_id" => 91,
				"conexion_id" => 84,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5546,
				"anterior" =>5534,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [71] = 
			(
				array(
				"cliente_id" => 956,
				"conexion_id" => 85,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5097,
				"anterior" =>5082,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [72] = 
			(
				array(
				"cliente_id" => 94,
				"conexion_id" => 89,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6011,
				"anterior" =>6011,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [73] = 
			(
				array(
				"cliente_id" => 96,
				"conexion_id" => 91,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 788,
				"anterior" =>650,
				"basico" => 200.00,
				"excedente" => 984.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [74] = 
			(
				array(
				"cliente_id" => 98,
				"conexion_id" => 93,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 518,
				"anterior" =>502,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [75] = 
			(
				array(
				"cliente_id" => 86,
				"conexion_id" => 94,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 634,
				"anterior" =>626,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [76] = 
			(
				array(
				"cliente_id" => 86,
				"conexion_id" => 95,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>98.00,
				"basico" => 0.00 ,
				"excedente" => 98.00,
				"importe" => 10.00,
				"mts" => 00
				)
			);
			$array_mediciones [77] = 
			(
				array(
				"cliente_id" => 102,
				"conexion_id" => 97,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1908,
				"anterior" =>1901,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [78] = 
			(
				array(
				"cliente_id" => 144,
				"conexion_id" => 98,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 940,
				"anterior" =>931,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [79] = 
			(
				array(
				"cliente_id" => 73,
				"conexion_id" => 100,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 169,
				"anterior" =>141,
				"basico" => 98.00,
				"excedente" => 90.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [80] = 
			(
				array(
				"cliente_id" => 86,
				"conexion_id" => 101,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3612,
				"anterior" =>3612,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [81] = 
			(
				array(
				"cliente_id" => 104,
				"conexion_id" => 102,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5262,
				"anterior" =>5224,
				"basico" => 98.00,
				"excedente" => 140.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [82] = 
			(
				array(
				"cliente_id" => 92,
				"conexion_id" => 103,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3708,
				"anterior" =>3693,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [83] = 
			(
				array(
				"cliente_id" => 107,
				"conexion_id" => 105,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3050,
				"anterior" =>3047,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [84] = 
			(
				array(
				"cliente_id" => 108,
				"conexion_id" => 106,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 745,
				"anterior" =>739,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [85] = 
			(
				array(
				"cliente_id" => 39,
				"conexion_id" => 107,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>0,
				"basico" => 0.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [86] = 
			(
				array(
				"cliente_id" => 109,
				"conexion_id" => 108,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6356,
				"anterior" =>6347,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [87] = 
			(
				array(
				"cliente_id" => 110,
				"conexion_id" => 109,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2620,
				"anterior" =>2614,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [88] = 
			(
				array(
				"cliente_id" => 111,
				"conexion_id" => 110,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6595,
				"anterior" =>6581,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [89] = 
			(
				array(
				"cliente_id" => 125,
				"conexion_id" => 111,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3804,
				"anterior" =>3785,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [90] = 
			(
				array(
				"cliente_id" => 112,
				"conexion_id" => 112,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1527,
				"anterior" =>1517,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [91] = 
			(
				array(
				"cliente_id" => 113,
				"conexion_id" => 113,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 127,
				"anterior" =>98,
				"basico" => 98.00,
				"excedente" => 95.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [92] = 
			(
				array(
				"cliente_id" => 114,
				"conexion_id" => 114,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1712,
				"anterior" =>1701,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [93] = 
			(
				array(
				"cliente_id" => 115,
				"conexion_id" => 115,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 732,
				"anterior" =>711,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [94] = 
			(
				array(
				"cliente_id" => 116,
				"conexion_id" => 116,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7797,
				"anterior" =>7797,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [95] = 
			(
				array(
				"cliente_id" => 117,
				"conexion_id" => 117,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7190,
				"anterior" =>6982,
				"basico" => 98.00,
				"excedente" => 990.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [96] = 
			(
				array(
				"cliente_id" => 119,
				"conexion_id" => 119,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 626,
				"anterior" =>615,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [97] = 
			(
				array(
				"cliente_id" => 119,
				"conexion_id" => 120,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3697,
				"anterior" =>3689,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [98] = 
			(
				array(
				"cliente_id" => 85,
				"conexion_id" => 123,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>0,
				"basico" => 0.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [99] = 
			(
				array(
				"cliente_id" => 85,
				"conexion_id" => 124,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>0,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [100] = 
			(
				array(
				"cliente_id" => 130,
				"conexion_id" => 125,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2191,
				"anterior" =>2190,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [101] = 
			(
				array(
				"cliente_id" => 122,
				"conexion_id" => 126,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3114,
				"anterior" =>3114,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [102] = 
			(
				array(
				"cliente_id" => 123,
				"conexion_id" => 127,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3193,
				"anterior" =>3193,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [103] = 
			(
				array(
				"cliente_id" => 153,
				"conexion_id" => 131,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1485,
				"anterior" =>1471,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [104] = 
			(
				array(
				"cliente_id" => 127,
				"conexion_id" => 132,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2774,
				"anterior" =>2762,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [105] = 
			(
				array(
				"cliente_id" => 131,
				"conexion_id" => 133,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3528,
				"anterior" =>3521,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [106] = 
			(
				array(
				"cliente_id" => 132,
				"conexion_id" => 134,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3809,
				"anterior" =>3809,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [107] = 
			(
				array(
				"cliente_id" => 133,
				"conexion_id" => 136,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2545,
				"anterior" =>2537,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [108] = 
			(
				array(
				"cliente_id" => 134,
				"conexion_id" => 137,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8217,
				"anterior" =>8207,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [109] = 
			(
				array(
				"cliente_id" => 137,
				"conexion_id" => 139,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 729,
				"anterior" =>690,
				"basico" => 98.00,
				"excedente" => 145.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [110] = 
			(
				array(
				"cliente_id" => 138,
				"conexion_id" => 140,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3882,
				"anterior" =>3847,
				"basico" => 98.00,
				"excedente" => 125.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [111] = 
			(
				array(
				"cliente_id" => 146,
				"conexion_id" => 143,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2284,
				"anterior" =>2284,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [112] = 
			(
				array(
				"cliente_id" => 155,
				"conexion_id" => 145,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4755,
				"anterior" =>4755,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [113] = 
			(
				array(
				"cliente_id" => 156,
				"conexion_id" => 146,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 477,
				"anterior" =>433,
				"basico" => 98.00,
				"excedente" => 170.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [114] = 
			(
				array(
				"cliente_id" => 157,
				"conexion_id" => 147,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1571,
				"anterior" =>1562,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [115] = 
			(
				array(
				"cliente_id" => 158,
				"conexion_id" => 148,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3761,
				"anterior" =>3750,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [116] = 
			(
				array(
				"cliente_id" => 158,
				"conexion_id" => 149,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 961,
				"anterior" =>961,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [117] = 
			(
				array(
				"cliente_id" => 86,
				"conexion_id" => 150,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1615,
				"anterior" =>1606,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [118] = 
			(
				array(
				"cliente_id" => 86,
				"conexion_id" => 151,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2957,
				"anterior" =>2949,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [119] = 
			(
				array(
				"cliente_id" => 160,
				"conexion_id" => 152,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4113,
				"anterior" =>4094,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [120] = 
			(
				array(
				"cliente_id" => 161,
				"conexion_id" => 153,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3904,
				"anterior" =>3895,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [121] = 
			(
				array(
				"cliente_id" => 162,
				"conexion_id" => 154,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2156,
				"anterior" =>2156,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [122] = 
			(
				array(
				"cliente_id" => 162,
				"conexion_id" => 155,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5511,
				"anterior" =>5506,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [123] = 
			(
				array(
				"cliente_id" => 159,
				"conexion_id" => 156,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1754,
				"anterior" =>1746,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [124] = 
			(
				array(
				"cliente_id" => 163,
				"conexion_id" => 157,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 408,
				"anterior" =>406,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [125] = 
			(
				array(
				"cliente_id" => 165,
				"conexion_id" => 158,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2330,
				"anterior" =>2295,
				"basico" => 98.00,
				"excedente" => 125.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [126] = 
			(
				array(
				"cliente_id" => 86,
				"conexion_id" => 159,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1172,
				"anterior" =>1157,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [127] = 
			(
				array(
				"cliente_id" => 166,
				"conexion_id" => 160,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4037,
				"anterior" =>4037,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [128] = 
			(
				array(
				"cliente_id" => 168,
				"conexion_id" => 161,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3616,
				"anterior" =>3604,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [129] = 
			(
				array(
				"cliente_id" => 169,
				"conexion_id" => 162,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3192,
				"anterior" =>3106,
				"basico" => 98.00,
				"excedente" => 380.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [130] = 
			(
				array(
				"cliente_id" => 734,
				"conexion_id" => 163,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6561,
				"anterior" =>6547,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [131] = 
			(
				array(
				"cliente_id" => 171,
				"conexion_id" => 164,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4757,
				"anterior" =>4745,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [132] = 
			(
				array(
				"cliente_id" => 172,
				"conexion_id" => 165,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3583,
				"anterior" =>3569,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [133] = 
			(
				array(
				"cliente_id" => 173,
				"conexion_id" => 166,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1689,
				"anterior" =>1681,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [134] = 
			(
				array(
				"cliente_id" => 174,
				"conexion_id" => 167,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2101,
				"anterior" =>2101,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [135] = 
			(
				array(
				"cliente_id" => 174,
				"conexion_id" => 168,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2392,
				"anterior" =>2366,
				"basico" => 98.00,
				"excedente" => 80.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [136] = 
			(
				array(
				"cliente_id" => 738,
				"conexion_id" => 169,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3560,
				"anterior" =>3558,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [137] = 
			(
				array(
				"cliente_id" => 742,
				"conexion_id" => 170,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 406,
				"anterior" =>361,
				"basico" => 98.00,
				"excedente" => 175.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [138] = 
			(
				array(
				"cliente_id" => 178,
				"conexion_id" => 172,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4093,
				"anterior" =>4062,
				"basico" => 98.00,
				"excedente" => 105.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [139] = 
			(
				array(
				"cliente_id" => 179,
				"conexion_id" => 173,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1398,
				"anterior" =>1374,
				"basico" => 98.00,
				"excedente" => 70.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [140] = 
			(
				array(
				"cliente_id" => 180,
				"conexion_id" => 174,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 9597,
				"anterior" =>9575,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [141] = 
			(
				array(
				"cliente_id" => 181,
				"conexion_id" => 175,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4931,
				"anterior" =>4914,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [142] = 
			(
				array(
				"cliente_id" => 184,
				"conexion_id" => 177,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1321,
				"anterior" =>1310,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [143] = 
			(
				array(
				"cliente_id" => 185,
				"conexion_id" => 178,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>98.00,
				"basico" => 0.00 ,
				"excedente" => 98.00,
				"importe" => 10.00,
				"mts" => 00
				)
			);
			$array_mediciones [144] = 
			(
				array(
				"cliente_id" => 186,
				"conexion_id" => 179,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2487,
				"anterior" =>2472,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [145] = 
			(
				array(
				"cliente_id" => 187,
				"conexion_id" => 180,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2926,
				"anterior" =>2920,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [146] = 
			(
				array(
				"cliente_id" => 182,
				"conexion_id" => 185,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1890,
				"anterior" =>1872,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [147] = 
			(
				array(
				"cliente_id" => 755,
				"conexion_id" => 186,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 332,
				"anterior" =>332,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [148] = 
			(
				array(
				"cliente_id" => 193,
				"conexion_id" => 187,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1812,
				"anterior" =>1805,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [149] = 
			(
				array(
				"cliente_id" => 751,
				"conexion_id" => 188,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>98.00,
				"basico" => 0.00 ,
				"excedente" => 98.00,
				"importe" => 10.00,
				"mts" => 00
				)
			);
			$array_mediciones [150] = 
			(
				array(
				"cliente_id" => 195,
				"conexion_id" => 189,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2136,
				"anterior" =>2106,
				"basico" => 98.00,
				"excedente" => 100.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [151] = 
			(
				array(
				"cliente_id" => 199,
				"conexion_id" => 194,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2424,
				"anterior" =>2421,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [152] = 
			(
				array(
				"cliente_id" => 6,
				"conexion_id" => 196,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 164,
				"anterior" =>144,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [153] = 
			(
				array(
				"cliente_id" => 609,
				"conexion_id" => 197,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3648,
				"anterior" =>3639,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [154] = 
			(
				array(
				"cliente_id" => 610,
				"conexion_id" => 198,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 338,
				"anterior" =>330,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [155] = 
			(
				array(
				"cliente_id" => 617,
				"conexion_id" => 199,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2038,
				"anterior" =>2038,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [156] = 
			(
				array(
				"cliente_id" => 400,
				"conexion_id" => 200,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4928,
				"anterior" =>4913,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [157] = 
			(
				array(
				"cliente_id" => 401,
				"conexion_id" => 201,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4330,
				"anterior" =>4314,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [158] = 
			(
				array(
				"cliente_id" => 402,
				"conexion_id" => 202,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1766,
				"anterior" =>1758,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [159] = 
			(
				array(
				"cliente_id" => 403,
				"conexion_id" => 203,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3170,
				"anterior" =>3161,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [160] = 
			(
				array(
				"cliente_id" => 404,
				"conexion_id" => 204,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5792,
				"anterior" =>5758,
				"basico" => 98.00,
				"excedente" => 120.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [161] = 
			(
				array(
				"cliente_id" => 405,
				"conexion_id" => 205,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3542,
				"anterior" =>3530,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [162] = 
			(
				array(
				"cliente_id" => 406,
				"conexion_id" => 206,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3675,
				"anterior" =>3651,
				"basico" => 98.00,
				"excedente" => 70.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [163] = 
			(
				array(
				"cliente_id" => 407,
				"conexion_id" => 207,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4243,
				"anterior" =>4228,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [164] = 
			(
				array(
				"cliente_id" => 408,
				"conexion_id" => 208,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1504,
				"anterior" =>1475,
				"basico" => 98.00,
				"excedente" => 95.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [165] = 
			(
				array(
				"cliente_id" => 409,
				"conexion_id" => 209,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5289,
				"anterior" =>5273,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [166] = 
			(
				array(
				"cliente_id" => 410,
				"conexion_id" => 210,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8819,
				"anterior" =>8819,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [167] = 
			(
				array(
				"cliente_id" => 411,
				"conexion_id" => 211,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4947,
				"anterior" =>4945,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [168] = 
			(
				array(
				"cliente_id" => 412,
				"conexion_id" => 212,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4417,
				"anterior" =>4394,
				"basico" => 98.00,
				"excedente" => 65.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [169] = 
			(
				array(
				"cliente_id" => 413,
				"conexion_id" => 213,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4005,
				"anterior" =>3986,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [170] = 
			(
				array(
				"cliente_id" => 414,
				"conexion_id" => 214,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4144,
				"anterior" =>4114,
				"basico" => 98.00,
				"excedente" => 100.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [171] = 
			(
				array(
				"cliente_id" => 415,
				"conexion_id" => 215,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4253,
				"anterior" =>4209,
				"basico" => 98.00,
				"excedente" => 170.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [172] = 
			(
				array(
				"cliente_id" => 416,
				"conexion_id" => 216,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2513,
				"anterior" =>2492,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [173] = 
			(
				array(
				"cliente_id" => 417,
				"conexion_id" => 217,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3446,
				"anterior" =>3435,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [174] = 
			(
				array(
				"cliente_id" => 418,
				"conexion_id" => 218,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3382,
				"anterior" =>3371,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [175] = 
			(
				array(
				"cliente_id" => 419,
				"conexion_id" => 219,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4347,
				"anterior" =>4332,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [176] = 
			(
				array(
				"cliente_id" => 420,
				"conexion_id" => 220,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2699,
				"anterior" =>2690,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [177] = 
			(
				array(
				"cliente_id" => 421,
				"conexion_id" => 221,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2142,
				"anterior" =>2138,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [178] = 
			(
				array(
				"cliente_id" => 422,
				"conexion_id" => 222,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2613,
				"anterior" =>2602,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [179] = 
			(
				array(
				"cliente_id" => 423,
				"conexion_id" => 223,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4314,
				"anterior" =>4309,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [180] = 
			(
				array(
				"cliente_id" => 424,
				"conexion_id" => 224,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4345,
				"anterior" =>4336,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [181] = 
			(
				array(
				"cliente_id" => 425,
				"conexion_id" => 225,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2677,
				"anterior" =>2677,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [182] = 
			(
				array(
				"cliente_id" => 733,
				"conexion_id" => 226,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5079,
				"anterior" =>5062,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [183] = 
			(
				array(
				"cliente_id" => 427,
				"conexion_id" => 227,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3518,
				"anterior" =>3503,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [184] = 
			(
				array(
				"cliente_id" => 428,
				"conexion_id" => 228,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5426,
				"anterior" =>5419,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [185] = 
			(
				array(
				"cliente_id" => 429,
				"conexion_id" => 229,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2299,
				"anterior" =>2291,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [186] = 
			(
				array(
				"cliente_id" => 430,
				"conexion_id" => 230,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3126,
				"anterior" =>3119,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [187] = 
			(
				array(
				"cliente_id" => 431,
				"conexion_id" => 231,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5482,
				"anterior" =>5470,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [188] = 
			(
				array(
				"cliente_id" => 760,
				"conexion_id" => 232,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4718,
				"anterior" =>4706,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [189] = 
			(
				array(
				"cliente_id" => 433,
				"conexion_id" => 233,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4522,
				"anterior" =>4502,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [190] = 
			(
				array(
				"cliente_id" => 434,
				"conexion_id" => 234,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1686,
				"anterior" =>1678,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [191] = 
			(
				array(
				"cliente_id" => 435,
				"conexion_id" => 235,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4850,
				"anterior" =>4834,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [192] = 
			(
				array(
				"cliente_id" => 436,
				"conexion_id" => 236,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1444,
				"anterior" =>1439,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [193] = 
			(
				array(
				"cliente_id" => 437,
				"conexion_id" => 237,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3711,
				"anterior" =>3706,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [194] = 
			(
				array(
				"cliente_id" => 438,
				"conexion_id" => 238,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4566,
				"anterior" =>4557,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [195] = 
			(
				array(
				"cliente_id" => 439,
				"conexion_id" => 239,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5466,
				"anterior" =>5451,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [196] = 
			(
				array(
				"cliente_id" => 440,
				"conexion_id" => 240,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5389,
				"anterior" =>5378,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [197] = 
			(
				array(
				"cliente_id" => 441,
				"conexion_id" => 241,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2319,
				"anterior" =>2317,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [198] = 
			(
				array(
				"cliente_id" => 442,
				"conexion_id" => 242,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4124,
				"anterior" =>4119,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [199] = 
			(
				array(
				"cliente_id" => 443,
				"conexion_id" => 243,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4960,
				"anterior" =>4921,
				"basico" => 98.00,
				"excedente" => 145.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [200] = 
			(
				array(
				"cliente_id" => 444,
				"conexion_id" => 244,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4335,
				"anterior" =>4328,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [201] = 
			(
				array(
				"cliente_id" => 445,
				"conexion_id" => 245,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5711,
				"anterior" =>5699,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [202] = 
			(
				array(
				"cliente_id" => 446,
				"conexion_id" => 246,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4389,
				"anterior" =>4376,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [203] = 
			(
				array(
				"cliente_id" => 447,
				"conexion_id" => 247,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8579,
				"anterior" =>8562,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [204] = 
			(
				array(
				"cliente_id" => 448,
				"conexion_id" => 248,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4420,
				"anterior" =>4403,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [205] = 
			(
				array(
				"cliente_id" => 449,
				"conexion_id" => 249,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4192,
				"anterior" =>4179,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [206] = 
			(
				array(
				"cliente_id" => 450,
				"conexion_id" => 250,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4750,
				"anterior" =>4735,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [207] = 
			(
				array(
				"cliente_id" => 451,
				"conexion_id" => 251,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3912,
				"anterior" =>3896,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [208] = 
			(
				array(
				"cliente_id" => 452,
				"conexion_id" => 252,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6977,
				"anterior" =>6965,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [209] = 
			(
				array(
				"cliente_id" => 453,
				"conexion_id" => 253,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4860,
				"anterior" =>4844,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [210] = 
			(
				array(
				"cliente_id" => 454,
				"conexion_id" => 254,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1980,
				"anterior" =>1972,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [211] = 
			(
				array(
				"cliente_id" => 455,
				"conexion_id" => 255,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5160,
				"anterior" =>5141,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [212] = 
			(
				array(
				"cliente_id" => 456,
				"conexion_id" => 256,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4877,
				"anterior" =>4867,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [213] = 
			(
				array(
				"cliente_id" => 457,
				"conexion_id" => 257,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6399,
				"anterior" =>6390,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [214] = 
			(
				array(
				"cliente_id" => 458,
				"conexion_id" => 258,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 100,
				"anterior" =>85,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [215] = 
			(
				array(
				"cliente_id" => 459,
				"conexion_id" => 259,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5034,
				"anterior" =>5034,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [216] = 
			(
				array(
				"cliente_id" => 460,
				"conexion_id" => 260,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 950,
				"anterior" =>940,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [217] = 
			(
				array(
				"cliente_id" => 461,
				"conexion_id" => 261,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5340,
				"anterior" =>5327,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [218] = 
			(
				array(
				"cliente_id" => 462,
				"conexion_id" => 262,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5372,
				"anterior" =>5367,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [219] = 
			(
				array(
				"cliente_id" => 463,
				"conexion_id" => 263,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5386,
				"anterior" =>5368,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [220] = 
			(
				array(
				"cliente_id" => 464,
				"conexion_id" => 264,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4752,
				"anterior" =>4734,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [221] = 
			(
				array(
				"cliente_id" => 465,
				"conexion_id" => 265,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5781,
				"anterior" =>5756,
				"basico" => 98.00,
				"excedente" => 75.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [222] = 
			(
				array(
				"cliente_id" => 466,
				"conexion_id" => 266,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3010,
				"anterior" =>2995,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [223] = 
			(
				array(
				"cliente_id" => 467,
				"conexion_id" => 267,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3746,
				"anterior" =>3737,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [224] = 
			(
				array(
				"cliente_id" => 468,
				"conexion_id" => 268,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2547,
				"anterior" =>2530,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [225] = 
			(
				array(
				"cliente_id" => 469,
				"conexion_id" => 269,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5422,
				"anterior" =>5413,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [226] = 
			(
				array(
				"cliente_id" => 470,
				"conexion_id" => 270,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3758,
				"anterior" =>3744,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [227] = 
			(
				array(
				"cliente_id" => 471,
				"conexion_id" => 271,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5157,
				"anterior" =>5149,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [228] = 
			(
				array(
				"cliente_id" => 472,
				"conexion_id" => 272,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5710,
				"anterior" =>5695,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [229] = 
			(
				array(
				"cliente_id" => 473,
				"conexion_id" => 273,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5432,
				"anterior" =>5415,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [230] = 
			(
				array(
				"cliente_id" => 474,
				"conexion_id" => 274,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4063,
				"anterior" =>4057,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [231] = 
			(
				array(
				"cliente_id" => 475,
				"conexion_id" => 275,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5004,
				"anterior" =>5004,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [232] = 
			(
				array(
				"cliente_id" => 476,
				"conexion_id" => 276,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1171,
				"anterior" =>1162,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [233] = 
			(
				array(
				"cliente_id" => 477,
				"conexion_id" => 277,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3082,
				"anterior" =>3076,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [234] = 
			(
				array(
				"cliente_id" => 478,
				"conexion_id" => 278,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3721,
				"anterior" =>3712,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [235] = 
			(
				array(
				"cliente_id" => 479,
				"conexion_id" => 279,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5548,
				"anterior" =>5535,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [236] = 
			(
				array(
				"cliente_id" => 480,
				"conexion_id" => 280,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 2,
				"anterior" =>1,
				"basico" => 200.00,
				"excedente" => 0.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [237] = 
			(
				array(
				"cliente_id" => 134,
				"conexion_id" => 281,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5446,
				"anterior" =>5441,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [238] = 
			(
				array(
				"cliente_id" => 499,
				"conexion_id" => 283,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3350,
				"anterior" =>3321,
				"basico" => 98.00,
				"excedente" => 95.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [239] = 
			(
				array(
				"cliente_id" => 498,
				"conexion_id" => 284,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 1391,
				"anterior" =>1312,
				"basico" => 200.00,
				"excedente" => 512.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [240] = 
			(
				array(
				"cliente_id" => 607,
				"conexion_id" => 285,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7315,
				"anterior" =>7315,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [241] = 
			(
				array(
				"cliente_id" => 624,
				"conexion_id" => 287,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3144,
				"anterior" =>3057,
				"basico" => 98.00,
				"excedente" => 385.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [242] = 
			(
				array(
				"cliente_id" => 611,
				"conexion_id" => 288,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5017,
				"anterior" =>98.00,
				"basico" => 0.00 ,
				"excedente" => 98.00,
				"importe" => 10.00,
				"mts" => 00
				)
			);
			$array_mediciones [243] = 
			(
				array(
				"cliente_id" => 608,
				"conexion_id" => 289,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3352,
				"anterior" =>3341,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [244] = 
			(
				array(
				"cliente_id" => 614,
				"conexion_id" => 290,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1029,
				"anterior" =>1024,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [245] = 
			(
				array(
				"cliente_id" => 618,
				"conexion_id" => 291,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1171,
				"anterior" =>1160,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [246] = 
			(
				array(
				"cliente_id" => 619,
				"conexion_id" => 292,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4223,
				"anterior" =>4012,
				"basico" => 98.00,
				"excedente" => 1005.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [247] = 
			(
				array(
				"cliente_id" => 620,
				"conexion_id" => 293,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1317,
				"anterior" =>1317,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [248] = 
			(
				array(
				"cliente_id" => 184,
				"conexion_id" => 294,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5070,
				"anterior" =>5059,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [249] = 
			(
				array(
				"cliente_id" => 621,
				"conexion_id" => 295,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4704,
				"anterior" =>4675,
				"basico" => 98.00,
				"excedente" => 95.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [250] = 
			(
				array(
				"cliente_id" => 767,
				"conexion_id" => 296,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2361,
				"anterior" =>2361,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [251] = 
			(
				array(
				"cliente_id" => 607,
				"conexion_id" => 297,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6586,
				"anterior" =>6586,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [252] = 
			(
				array(
				"cliente_id" => 625,
				"conexion_id" => 298,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4043,
				"anterior" =>4043,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [253] = 
			(
				array(
				"cliente_id" => 62,
				"conexion_id" => 299,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>0,
				"basico" => 0.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [254] = 
			(
				array(
				"cliente_id" => 319,
				"conexion_id" => 301,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6146,
				"anterior" =>6125,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [255] = 
			(
				array(
				"cliente_id" => 272,
				"conexion_id" => 302,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2943,
				"anterior" =>2940,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [256] = 
			(
				array(
				"cliente_id" => 206,
				"conexion_id" => 303,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2817,
				"anterior" =>2794,
				"basico" => 98.00,
				"excedente" => 65.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [257] = 
			(
				array(
				"cliente_id" => 724,
				"conexion_id" => 304,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2508,
				"anterior" =>2472,
				"basico" => 98.00,
				"excedente" => 130.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [258] = 
			(
				array(
				"cliente_id" => 207,
				"conexion_id" => 305,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3885,
				"anterior" =>3870,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [259] = 
			(
				array(
				"cliente_id" => 234,
				"conexion_id" => 306,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6726,
				"anterior" =>6701,
				"basico" => 98.00,
				"excedente" => 75.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [260] = 
			(
				array(
				"cliente_id" => 253,
				"conexion_id" => 307,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4662,
				"anterior" =>4640,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [261] = 
			(
				array(
				"cliente_id" => 295,
				"conexion_id" => 308,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6566,
				"anterior" =>6560,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [262] = 
			(
				array(
				"cliente_id" => 318,
				"conexion_id" => 309,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5494,
				"anterior" =>5473,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [263] = 
			(
				array(
				"cliente_id" => 226,
				"conexion_id" => 310,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4077,
				"anterior" =>4058,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [264] = 
			(
				array(
				"cliente_id" => 309,
				"conexion_id" => 311,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3795,
				"anterior" =>3765,
				"basico" => 98.00,
				"excedente" => 100.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [265] = 
			(
				array(
				"cliente_id" => 306,
				"conexion_id" => 312,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7055,
				"anterior" =>7022,
				"basico" => 98.00,
				"excedente" => 115.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [266] = 
			(
				array(
				"cliente_id" => 245,
				"conexion_id" => 313,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3390,
				"anterior" =>3378,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [267] = 
			(
				array(
				"cliente_id" => 369,
				"conexion_id" => 314,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6900,
				"anterior" =>6871,
				"basico" => 98.00,
				"excedente" => 95.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [268] = 
			(
				array(
				"cliente_id" => 488,
				"conexion_id" => 315,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4270,
				"anterior" =>4250,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [269] = 
			(
				array(
				"cliente_id" => 481,
				"conexion_id" => 316,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6517,
				"anterior" =>6495,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [270] = 
			(
				array(
				"cliente_id" => 273,
				"conexion_id" => 317,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4436,
				"anterior" =>4410,
				"basico" => 98.00,
				"excedente" => 80.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [271] = 
			(
				array(
				"cliente_id" => 320,
				"conexion_id" => 318,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6825,
				"anterior" =>6786,
				"basico" => 98.00,
				"excedente" => 145.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [272] = 
			(
				array(
				"cliente_id" => 274,
				"conexion_id" => 319,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2874,
				"anterior" =>2862,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [273] = 
			(
				array(
				"cliente_id" => 298,
				"conexion_id" => 320,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2621,
				"anterior" =>2613,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [274] = 
			(
				array(
				"cliente_id" => 204,
				"conexion_id" => 321,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4139,
				"anterior" =>4124,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [275] = 
			(
				array(
				"cliente_id" => 492,
				"conexion_id" => 322,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3180,
				"anterior" =>3164,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [276] = 
			(
				array(
				"cliente_id" => 294,
				"conexion_id" => 323,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3176,
				"anterior" =>3154,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [277] = 
			(
				array(
				"cliente_id" => 255,
				"conexion_id" => 324,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4716,
				"anterior" =>4707,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [278] = 
			(
				array(
				"cliente_id" => 214,
				"conexion_id" => 325,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6254,
				"anterior" =>6209,
				"basico" => 98.00,
				"excedente" => 175.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [279] = 
			(
				array(
				"cliente_id" => 322,
				"conexion_id" => 326,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3884,
				"anterior" =>3849,
				"basico" => 98.00,
				"excedente" => 125.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [280] = 
			(
				array(
				"cliente_id" => 288,
				"conexion_id" => 327,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2722,
				"anterior" =>2721,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [281] = 
			(
				array(
				"cliente_id" => 275,
				"conexion_id" => 328,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2971,
				"anterior" =>2963,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [282] = 
			(
				array(
				"cliente_id" => 243,
				"conexion_id" => 329,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2247,
				"anterior" =>2221,
				"basico" => 98.00,
				"excedente" => 80.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [283] = 
			(
				array(
				"cliente_id" => 239,
				"conexion_id" => 330,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1346,
				"anterior" =>1340,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [284] = 
			(
				array(
				"cliente_id" => 260,
				"conexion_id" => 331,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3084,
				"anterior" =>3067,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [285] = 
			(
				array(
				"cliente_id" => 375,
				"conexion_id" => 332,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1552,
				"anterior" =>1543,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [286] = 
			(
				array(
				"cliente_id" => 321,
				"conexion_id" => 333,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2678,
				"anterior" =>2672,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [287] = 
			(
				array(
				"cliente_id" => 262,
				"conexion_id" => 334,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3020,
				"anterior" =>3009,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [288] = 
			(
				array(
				"cliente_id" => 723,
				"conexion_id" => 335,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2676,
				"anterior" =>2676,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [289] = 
			(
				array(
				"cliente_id" => 203,
				"conexion_id" => 336,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3439,
				"anterior" =>3385,
				"basico" => 98.00,
				"excedente" => 220.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [290] = 
			(
				array(
				"cliente_id" => 251,
				"conexion_id" => 337,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3039,
				"anterior" =>3025,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [291] = 
			(
				array(
				"cliente_id" => 290,
				"conexion_id" => 338,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3144,
				"anterior" =>3134,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [292] = 
			(
				array(
				"cliente_id" => 201,
				"conexion_id" => 339,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2975,
				"anterior" =>2963,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [293] = 
			(
				array(
				"cliente_id" => 725,
				"conexion_id" => 340,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4292,
				"anterior" =>4256,
				"basico" => 98.00,
				"excedente" => 130.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [294] = 
			(
				array(
				"cliente_id" => 218,
				"conexion_id" => 341,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4871,
				"anterior" =>4847,
				"basico" => 98.00,
				"excedente" => 70.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [295] = 
			(
				array(
				"cliente_id" => 311,
				"conexion_id" => 342,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2413,
				"anterior" =>2393,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [296] = 
			(
				array(
				"cliente_id" => 256,
				"conexion_id" => 343,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5398,
				"anterior" =>5365,
				"basico" => 98.00,
				"excedente" => 115.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [297] = 
			(
				array(
				"cliente_id" => 265,
				"conexion_id" => 344,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4892,
				"anterior" =>4867,
				"basico" => 98.00,
				"excedente" => 75.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [298] = 
			(
				array(
				"cliente_id" => 282,
				"conexion_id" => 345,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5564,
				"anterior" =>5531,
				"basico" => 98.00,
				"excedente" => 115.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [299] = 
			(
				array(
				"cliente_id" => 484,
				"conexion_id" => 346,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3260,
				"anterior" =>3223,
				"basico" => 98.00,
				"excedente" => 135.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [300] = 
			(
				array(
				"cliente_id" => 248,
				"conexion_id" => 347,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2881,
				"anterior" =>2879,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [301] = 
			(
				array(
				"cliente_id" => 307,
				"conexion_id" => 348,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2572,
				"anterior" =>2540,
				"basico" => 98.00,
				"excedente" => 110.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [302] = 
			(
				array(
				"cliente_id" => 211,
				"conexion_id" => 349,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3172,
				"anterior" =>3157,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [303] = 
			(
				array(
				"cliente_id" => 312,
				"conexion_id" => 350,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3663,
				"anterior" =>3645,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [304] = 
			(
				array(
				"cliente_id" => 496,
				"conexion_id" => 351,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4748,
				"anterior" =>4737,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [305] = 
			(
				array(
				"cliente_id" => 482,
				"conexion_id" => 352,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1282,
				"anterior" =>1264,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [306] = 
			(
				array(
				"cliente_id" => 329,
				"conexion_id" => 353,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4305,
				"anterior" =>4276,
				"basico" => 98.00,
				"excedente" => 95.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [307] = 
			(
				array(
				"cliente_id" => 293,
				"conexion_id" => 354,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3666,
				"anterior" =>3626,
				"basico" => 98.00,
				"excedente" => 150.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [308] = 
			(
				array(
				"cliente_id" => 489,
				"conexion_id" => 355,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6128,
				"anterior" =>6105,
				"basico" => 98.00,
				"excedente" => 65.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [309] = 
			(
				array(
				"cliente_id" => 238,
				"conexion_id" => 356,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2931,
				"anterior" =>2919,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [310] = 
			(
				array(
				"cliente_id" => 304,
				"conexion_id" => 357,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2227,
				"anterior" =>2189,
				"basico" => 98.00,
				"excedente" => 140.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [311] = 
			(
				array(
				"cliente_id" => 281,
				"conexion_id" => 358,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3560,
				"anterior" =>3536,
				"basico" => 98.00,
				"excedente" => 70.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [312] = 
			(
				array(
				"cliente_id" => 314,
				"conexion_id" => 359,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3370,
				"anterior" =>3350,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [313] = 
			(
				array(
				"cliente_id" => 230,
				"conexion_id" => 360,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2548,
				"anterior" =>2524,
				"basico" => 98.00,
				"excedente" => 70.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [314] = 
			(
				array(
				"cliente_id" => 212,
				"conexion_id" => 361,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3138,
				"anterior" =>3125,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [315] = 
			(
				array(
				"cliente_id" => 483,
				"conexion_id" => 362,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3826,
				"anterior" =>3824,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [316] = 
			(
				array(
				"cliente_id" => 649,
				"conexion_id" => 363,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2928,
				"anterior" =>2909,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [317] = 
			(
				array(
				"cliente_id" => 744,
				"conexion_id" => 364,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4332,
				"anterior" =>4298,
				"basico" => 98.00,
				"excedente" => 120.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [318] = 
			(
				array(
				"cliente_id" => 240,
				"conexion_id" => 365,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4194,
				"anterior" =>4164,
				"basico" => 98.00,
				"excedente" => 100.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [319] = 
			(
				array(
				"cliente_id" => 247,
				"conexion_id" => 366,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1583,
				"anterior" =>1565,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [320] = 
			(
				array(
				"cliente_id" => 237,
				"conexion_id" => 367,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5198,
				"anterior" =>5159,
				"basico" => 98.00,
				"excedente" => 145.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [321] = 
			(
				array(
				"cliente_id" => 242,
				"conexion_id" => 368,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2912,
				"anterior" =>2896,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [322] = 
			(
				array(
				"cliente_id" => 215,
				"conexion_id" => 369,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4620,
				"anterior" =>4542,
				"basico" => 98.00,
				"excedente" => 340.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [323] = 
			(
				array(
				"cliente_id" => 300,
				"conexion_id" => 370,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6212,
				"anterior" =>6199,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [324] = 
			(
				array(
				"cliente_id" => 280,
				"conexion_id" => 371,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2804,
				"anterior" =>2797,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [325] = 
			(
				array(
				"cliente_id" => 249,
				"conexion_id" => 372,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2692,
				"anterior" =>2690,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [326] = 
			(
				array(
				"cliente_id" => 236,
				"conexion_id" => 373,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3142,
				"anterior" =>3120,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [327] = 
			(
				array(
				"cliente_id" => 276,
				"conexion_id" => 374,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3213,
				"anterior" =>3202,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [328] = 
			(
				array(
				"cliente_id" => 494,
				"conexion_id" => 375,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4660,
				"anterior" =>4620,
				"basico" => 98.00,
				"excedente" => 150.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [329] = 
			(
				array(
				"cliente_id" => 316,
				"conexion_id" => 376,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4290,
				"anterior" =>4234,
				"basico" => 98.00,
				"excedente" => 230.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [330] = 
			(
				array(
				"cliente_id" => 250,
				"conexion_id" => 377,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3812,
				"anterior" =>3782,
				"basico" => 98.00,
				"excedente" => 100.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [331] = 
			(
				array(
				"cliente_id" => 261,
				"conexion_id" => 378,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4343,
				"anterior" =>4333,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [332] = 
			(
				array(
				"cliente_id" => 292,
				"conexion_id" => 379,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2106,
				"anterior" =>2070,
				"basico" => 98.00,
				"excedente" => 130.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [333] = 
			(
				array(
				"cliente_id" => 269,
				"conexion_id" => 380,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1708,
				"anterior" =>1707,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [334] = 
			(
				array(
				"cliente_id" => 208,
				"conexion_id" => 381,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3311,
				"anterior" =>3302,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [335] = 
			(
				array(
				"cliente_id" => 493,
				"conexion_id" => 382,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2278,
				"anterior" =>2262,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [336] = 
			(
				array(
				"cliente_id" => 291,
				"conexion_id" => 383,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4437,
				"anterior" =>4417,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [337] = 
			(
				array(
				"cliente_id" => 491,
				"conexion_id" => 384,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4190,
				"anterior" =>4180,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [338] = 
			(
				array(
				"cliente_id" => 305,
				"conexion_id" => 385,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1815,
				"anterior" =>1805,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [339] = 
			(
				array(
				"cliente_id" => 227,
				"conexion_id" => 386,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4456,
				"anterior" =>4439,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [340] = 
			(
				array(
				"cliente_id" => 220,
				"conexion_id" => 387,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2351,
				"anterior" =>2346,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [341] = 
			(
				array(
				"cliente_id" => 302,
				"conexion_id" => 388,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4471,
				"anterior" =>4451,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [342] = 
			(
				array(
				"cliente_id" => 485,
				"conexion_id" => 389,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2542,
				"anterior" =>2519,
				"basico" => 98.00,
				"excedente" => 65.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [343] = 
			(
				array(
				"cliente_id" => 222,
				"conexion_id" => 390,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3965,
				"anterior" =>3928,
				"basico" => 98.00,
				"excedente" => 135.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [344] = 
			(
				array(
				"cliente_id" => 327,
				"conexion_id" => 391,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1665,
				"anterior" =>1652,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [345] = 
			(
				array(
				"cliente_id" => 271,
				"conexion_id" => 392,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3647,
				"anterior" =>3639,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [346] = 
			(
				array(
				"cliente_id" => 267,
				"conexion_id" => 393,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2839,
				"anterior" =>2839,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [347] = 
			(
				array(
				"cliente_id" => 224,
				"conexion_id" => 394,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2315,
				"anterior" =>2282,
				"basico" => 98.00,
				"excedente" => 115.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [348] = 
			(
				array(
				"cliente_id" => 310,
				"conexion_id" => 395,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6219,
				"anterior" =>6188,
				"basico" => 98.00,
				"excedente" => 105.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [349] = 
			(
				array(
				"cliente_id" => 323,
				"conexion_id" => 396,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8085,
				"anterior" =>8051,
				"basico" => 98.00,
				"excedente" => 120.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [350] = 
			(
				array(
				"cliente_id" => 490,
				"conexion_id" => 397,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 114,
				"anterior" =>97,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [351] = 
			(
				array(
				"cliente_id" => 297,
				"conexion_id" => 398,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2768,
				"anterior" =>2752,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [352] = 
			(
				array(
				"cliente_id" => 264,
				"conexion_id" => 399,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6453,
				"anterior" =>6410,
				"basico" => 98.00,
				"excedente" => 165.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [353] = 
			(
				array(
				"cliente_id" => 268,
				"conexion_id" => 400,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2302,
				"anterior" =>2289,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [354] = 
			(
				array(
				"cliente_id" => 233,
				"conexion_id" => 401,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3438,
				"anterior" =>3424,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [355] = 
			(
				array(
				"cliente_id" => 317,
				"conexion_id" => 402,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3015,
				"anterior" =>3001,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [356] = 
			(
				array(
				"cliente_id" => 263,
				"conexion_id" => 403,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2375,
				"anterior" =>2370,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [357] = 
			(
				array(
				"cliente_id" => 202,
				"conexion_id" => 404,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3592,
				"anterior" =>3579,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [358] = 
			(
				array(
				"cliente_id" => 257,
				"conexion_id" => 405,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2382,
				"anterior" =>2362,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [359] = 
			(
				array(
				"cliente_id" => 324,
				"conexion_id" => 406,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4475,
				"anterior" =>4452,
				"basico" => 98.00,
				"excedente" => 65.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [360] = 
			(
				array(
				"cliente_id" => 266,
				"conexion_id" => 407,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3805,
				"anterior" =>3797,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [361] = 
			(
				array(
				"cliente_id" => 229,
				"conexion_id" => 408,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2380,
				"anterior" =>2369,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [362] = 
			(
				array(
				"cliente_id" => 330,
				"conexion_id" => 409,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3167,
				"anterior" =>3154,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [363] = 
			(
				array(
				"cliente_id" => 205,
				"conexion_id" => 410,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3589,
				"anterior" =>3549,
				"basico" => 98.00,
				"excedente" => 150.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [364] = 
			(
				array(
				"cliente_id" => 717,
				"conexion_id" => 411,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2563,
				"anterior" =>2545,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [365] = 
			(
				array(
				"cliente_id" => 315,
				"conexion_id" => 412,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2119,
				"anterior" =>2109,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [366] = 
			(
				array(
				"cliente_id" => 296,
				"conexion_id" => 413,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4152,
				"anterior" =>4140,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [367] = 
			(
				array(
				"cliente_id" => 615,
				"conexion_id" => 415,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3920,
				"anterior" =>3851,
				"basico" => 98.00,
				"excedente" => 295.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [368] = 
			(
				array(
				"cliente_id" => 626,
				"conexion_id" => 416,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4488,
				"anterior" =>4488,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [369] = 
			(
				array(
				"cliente_id" => 310,
				"conexion_id" => 417,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1208,
				"anterior" =>1197,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [370] = 
			(
				array(
				"cliente_id" => 631,
				"conexion_id" => 420,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2550,
				"anterior" =>2540,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [371] = 
			(
				array(
				"cliente_id" => 632,
				"conexion_id" => 421,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1965,
				"anterior" =>1950,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [372] = 
			(
				array(
				"cliente_id" => 635,
				"conexion_id" => 422,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3084,
				"anterior" =>3063,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [373] = 
			(
				array(
				"cliente_id" => 634,
				"conexion_id" => 423,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2232,
				"anterior" =>2218,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [374] = 
			(
				array(
				"cliente_id" => 633,
				"conexion_id" => 424,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2328,
				"anterior" =>2298,
				"basico" => 98.00,
				"excedente" => 100.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [375] = 
			(
				array(
				"cliente_id" => 636,
				"conexion_id" => 425,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2394,
				"anterior" =>2374,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [376] = 
			(
				array(
				"cliente_id" => 637,
				"conexion_id" => 426,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1246,
				"anterior" =>1236,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [377] = 
			(
				array(
				"cliente_id" => 638,
				"conexion_id" => 427,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1696,
				"anterior" =>1685,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [378] = 
			(
				array(
				"cliente_id" => 639,
				"conexion_id" => 428,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1719,
				"anterior" =>1709,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [379] = 
			(
				array(
				"cliente_id" => 640,
				"conexion_id" => 429,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3345,
				"anterior" =>3313,
				"basico" => 98.00,
				"excedente" => 110.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [380] = 
			(
				array(
				"cliente_id" => 641,
				"conexion_id" => 430,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2009,
				"anterior" =>2001,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [381] = 
			(
				array(
				"cliente_id" => 642,
				"conexion_id" => 431,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2075,
				"anterior" =>2060,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [382] = 
			(
				array(
				"cliente_id" => 643,
				"conexion_id" => 432,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2801,
				"anterior" =>2789,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [383] = 
			(
				array(
				"cliente_id" => 644,
				"conexion_id" => 433,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1419,
				"anterior" =>1414,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [384] = 
			(
				array(
				"cliente_id" => 645,
				"conexion_id" => 434,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2187,
				"anterior" =>2177,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [385] = 
			(
				array(
				"cliente_id" => 646,
				"conexion_id" => 435,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2218,
				"anterior" =>2200,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [386] = 
			(
				array(
				"cliente_id" => 647,
				"conexion_id" => 436,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1818,
				"anterior" =>1798,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [387] = 
			(
				array(
				"cliente_id" => 648,
				"conexion_id" => 437,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1763,
				"anterior" =>1750,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [388] = 
			(
				array(
				"cliente_id" => 704,
				"conexion_id" => 438,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2354,
				"anterior" =>2330,
				"basico" => 98.00,
				"excedente" => 70.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [389] = 
			(
				array(
				"cliente_id" => 705,
				"conexion_id" => 439,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 972,
				"anterior" =>970,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [390] = 
			(
				array(
				"cliente_id" => 706,
				"conexion_id" => 440,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1530,
				"anterior" =>1508,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [391] = 
			(
				array(
				"cliente_id" => 700,
				"conexion_id" => 441,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1382,
				"anterior" =>1371,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [392] = 
			(
				array(
				"cliente_id" => 707,
				"conexion_id" => 442,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5938,
				"anterior" =>5866,
				"basico" => 98.00,
				"excedente" => 310.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [393] = 
			(
				array(
				"cliente_id" => 708,
				"conexion_id" => 443,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1523,
				"anterior" =>1511,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [394] = 
			(
				array(
				"cliente_id" => 709,
				"conexion_id" => 444,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 678,
				"anterior" =>664,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [395] = 
			(
				array(
				"cliente_id" => 710,
				"conexion_id" => 445,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3045,
				"anterior" =>3035,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [396] = 
			(
				array(
				"cliente_id" => 711,
				"conexion_id" => 446,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2781,
				"anterior" =>2764,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [397] = 
			(
				array(
				"cliente_id" => 712,
				"conexion_id" => 447,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1091,
				"anterior" =>1082,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [398] = 
			(
				array(
				"cliente_id" => 713,
				"conexion_id" => 448,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1217,
				"anterior" =>1214,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [399] = 
			(
				array(
				"cliente_id" => 697,
				"conexion_id" => 449,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3169,
				"anterior" =>3125,
				"basico" => 98.00,
				"excedente" => 170.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [400] = 
			(
				array(
				"cliente_id" => 698,
				"conexion_id" => 450,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1817,
				"anterior" =>1804,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [401] = 
			(
				array(
				"cliente_id" => 699,
				"conexion_id" => 451,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2596,
				"anterior" =>2579,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [402] = 
			(
				array(
				"cliente_id" => 663,
				"conexion_id" => 452,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3870,
				"anterior" =>3857,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [403] = 
			(
				array(
				"cliente_id" => 664,
				"conexion_id" => 453,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2573,
				"anterior" =>2553,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [404] = 
			(
				array(
				"cliente_id" => 665,
				"conexion_id" => 454,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1848,
				"anterior" =>1829,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [405] = 
			(
				array(
				"cliente_id" => 666,
				"conexion_id" => 455,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 657,
				"anterior" =>648,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [406] = 
			(
				array(
				"cliente_id" => 667,
				"conexion_id" => 456,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2677,
				"anterior" =>2667,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [407] = 
			(
				array(
				"cliente_id" => 668,
				"conexion_id" => 457,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1672,
				"anterior" =>1663,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [408] = 
			(
				array(
				"cliente_id" => 669,
				"conexion_id" => 458,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2101,
				"anterior" =>2083,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [409] = 
			(
				array(
				"cliente_id" => 670,
				"conexion_id" => 459,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1924,
				"anterior" =>1912,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [410] = 
			(
				array(
				"cliente_id" => 671,
				"conexion_id" => 460,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2781,
				"anterior" =>2767,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [411] = 
			(
				array(
				"cliente_id" => 672,
				"conexion_id" => 461,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1298,
				"anterior" =>1284,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [412] = 
			(
				array(
				"cliente_id" => 673,
				"conexion_id" => 462,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2054,
				"anterior" =>2045,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [413] = 
			(
				array(
				"cliente_id" => 674,
				"conexion_id" => 463,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1024,
				"anterior" =>1018,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [414] = 
			(
				array(
				"cliente_id" => 675,
				"conexion_id" => 464,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1067,
				"anterior" =>1055,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [415] = 
			(
				array(
				"cliente_id" => 676,
				"conexion_id" => 465,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 776,
				"anterior" =>744,
				"basico" => 98.00,
				"excedente" => 110.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [416] = 
			(
				array(
				"cliente_id" => 677,
				"conexion_id" => 466,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2161,
				"anterior" =>2153,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [417] = 
			(
				array(
				"cliente_id" => 678,
				"conexion_id" => 467,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1514,
				"anterior" =>1508,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [418] = 
			(
				array(
				"cliente_id" => 679,
				"conexion_id" => 468,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1659,
				"anterior" =>1630,
				"basico" => 98.00,
				"excedente" => 95.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [419] = 
			(
				array(
				"cliente_id" => 680,
				"conexion_id" => 469,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3562,
				"anterior" =>3549,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [420] = 
			(
				array(
				"cliente_id" => 681,
				"conexion_id" => 470,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2555,
				"anterior" =>2530,
				"basico" => 98.00,
				"excedente" => 75.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [421] = 
			(
				array(
				"cliente_id" => 682,
				"conexion_id" => 471,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2019,
				"anterior" =>2003,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [422] = 
			(
				array(
				"cliente_id" => 683,
				"conexion_id" => 472,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1126,
				"anterior" =>1123,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [423] = 
			(
				array(
				"cliente_id" => 693,
				"conexion_id" => 476,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2263,
				"anterior" =>2242,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [424] = 
			(
				array(
				"cliente_id" => 694,
				"conexion_id" => 477,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1190,
				"anterior" =>1171,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [425] = 
			(
				array(
				"cliente_id" => 651,
				"conexion_id" => 478,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3074,
				"anterior" =>3062,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [426] = 
			(
				array(
				"cliente_id" => 652,
				"conexion_id" => 479,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 284,
				"anterior" =>283,
				"basico" => 200.00,
				"excedente" => 0.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [427] = 
			(
				array(
				"cliente_id" => 1046,
				"conexion_id" => 480,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 906,
				"anterior" =>905,
				"basico" => 200.00,
				"excedente" => 0.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [428] = 
			(
				array(
				"cliente_id" => 703,
				"conexion_id" => 481,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 317,
				"anterior" =>317,
				"basico" => 200.00,
				"excedente" => 0.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [429] = 
			(
				array(
				"cliente_id" => 735,
				"conexion_id" => 482,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1840,
				"anterior" =>1827,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [430] = 
			(
				array(
				"cliente_id" => 736,
				"conexion_id" => 483,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1620,
				"anterior" =>1614,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [431] = 
			(
				array(
				"cliente_id" => 737,
				"conexion_id" => 484,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1625,
				"anterior" =>1617,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [432] = 
			(
				array(
				"cliente_id" => 738,
				"conexion_id" => 485,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2249,
				"anterior" =>2228,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [433] = 
			(
				array(
				"cliente_id" => 174,
				"conexion_id" => 486,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 633,
				"anterior" =>633,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [434] = 
			(
				array(
				"cliente_id" => 741,
				"conexion_id" => 487,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4703,
				"anterior" =>4694,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [435] = 
			(
				array(
				"cliente_id" => 766,
				"conexion_id" => 489,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2137,
				"anterior" =>2128,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [436] = 
			(
				array(
				"cliente_id" => 756,
				"conexion_id" => 490,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1896,
				"anterior" =>1846,
				"basico" => 98.00,
				"excedente" => 200.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [437] = 
			(
				array(
				"cliente_id" => 757,
				"conexion_id" => 491,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1088,
				"anterior" =>1057,
				"basico" => 98.00,
				"excedente" => 105.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [438] = 
			(
				array(
				"cliente_id" => 758,
				"conexion_id" => 493,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 80,
				"anterior" =>80,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [439] = 
			(
				array(
				"cliente_id" => 759,
				"conexion_id" => 494,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1762,
				"anterior" =>1750,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [440] = 
			(
				array(
				"cliente_id" => 500,
				"conexion_id" => 500,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2816,
				"anterior" =>2796,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [441] = 
			(
				array(
				"cliente_id" => 501,
				"conexion_id" => 501,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2906,
				"anterior" =>2896,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [442] = 
			(
				array(
				"cliente_id" => 502,
				"conexion_id" => 502,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2253,
				"anterior" =>2220,
				"basico" => 98.00,
				"excedente" => 115.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [443] = 
			(
				array(
				"cliente_id" => 503,
				"conexion_id" => 503,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 582,
				"anterior" =>559,
				"basico" => 98.00,
				"excedente" => 65.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [444] = 
			(
				array(
				"cliente_id" => 504,
				"conexion_id" => 504,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2247,
				"anterior" =>2216,
				"basico" => 98.00,
				"excedente" => 105.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [445] = 
			(
				array(
				"cliente_id" => 505,
				"conexion_id" => 505,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 178,
				"anterior" =>177,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [446] = 
			(
				array(
				"cliente_id" => 506,
				"conexion_id" => 506,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2630,
				"anterior" =>2610,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [447] = 
			(
				array(
				"cliente_id" => 507,
				"conexion_id" => 507,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2290,
				"anterior" =>2281,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [448] = 
			(
				array(
				"cliente_id" => 508,
				"conexion_id" => 508,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4280,
				"anterior" =>4278,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [449] = 
			(
				array(
				"cliente_id" => 509,
				"conexion_id" => 509,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 125,
				"anterior" =>112,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [450] = 
			(
				array(
				"cliente_id" => 510,
				"conexion_id" => 510,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4719,
				"anterior" =>4706,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [451] = 
			(
				array(
				"cliente_id" => 511,
				"conexion_id" => 511,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3500,
				"anterior" =>3463,
				"basico" => 98.00,
				"excedente" => 135.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [452] = 
			(
				array(
				"cliente_id" => 512,
				"conexion_id" => 512,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1436,
				"anterior" =>1436,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [453] = 
			(
				array(
				"cliente_id" => 513,
				"conexion_id" => 513,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3899,
				"anterior" =>3899,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [454] = 
			(
				array(
				"cliente_id" => 514,
				"conexion_id" => 514,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 618,
				"anterior" =>618,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [455] = 
			(
				array(
				"cliente_id" => 515,
				"conexion_id" => 515,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 388,
				"anterior" =>353,
				"basico" => 98.00,
				"excedente" => 125.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [456] = 
			(
				array(
				"cliente_id" => 720,
				"conexion_id" => 516,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6581,
				"anterior" =>6555,
				"basico" => 98.00,
				"excedente" => 80.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [457] = 
			(
				array(
				"cliente_id" => 517,
				"conexion_id" => 517,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5141,
				"anterior" =>5114,
				"basico" => 98.00,
				"excedente" => 85.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [458] = 
			(
				array(
				"cliente_id" => 518,
				"conexion_id" => 518,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 153,
				"anterior" =>153,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [459] = 
			(
				array(
				"cliente_id" => 519,
				"conexion_id" => 519,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4719,
				"anterior" =>4631,
				"basico" => 98.00,
				"excedente" => 390.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [460] = 
			(
				array(
				"cliente_id" => 739,
				"conexion_id" => 520,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2192,
				"anterior" =>2158,
				"basico" => 98.00,
				"excedente" => 120.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [461] = 
			(
				array(
				"cliente_id" => 687,
				"conexion_id" => 521,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 526,
				"anterior" =>487,
				"basico" => 98.00,
				"excedente" => 145.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [462] = 
			(
				array(
				"cliente_id" => 522,
				"conexion_id" => 522,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4165,
				"anterior" =>4143,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [463] = 
			(
				array(
				"cliente_id" => 523,
				"conexion_id" => 523,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2307,
				"anterior" =>2273,
				"basico" => 98.00,
				"excedente" => 120.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [464] = 
			(
				array(
				"cliente_id" => 524,
				"conexion_id" => 524,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1958,
				"anterior" =>1943,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [465] = 
			(
				array(
				"cliente_id" => 525,
				"conexion_id" => 525,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5399,
				"anterior" =>5361,
				"basico" => 98.00,
				"excedente" => 140.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [466] = 
			(
				array(
				"cliente_id" => 526,
				"conexion_id" => 526,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4757,
				"anterior" =>4731,
				"basico" => 98.00,
				"excedente" => 80.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [467] = 
			(
				array(
				"cliente_id" => 740,
				"conexion_id" => 527,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2795,
				"anterior" =>2763,
				"basico" => 98.00,
				"excedente" => 110.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [468] = 
			(
				array(
				"cliente_id" => 528,
				"conexion_id" => 528,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2583,
				"anterior" =>2563,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [469] = 
			(
				array(
				"cliente_id" => 530,
				"conexion_id" => 530,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1990,
				"anterior" =>1953,
				"basico" => 98.00,
				"excedente" => 135.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [470] = 
			(
				array(
				"cliente_id" => 531,
				"conexion_id" => 531,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5260,
				"anterior" =>5230,
				"basico" => 98.00,
				"excedente" => 100.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [471] = 
			(
				array(
				"cliente_id" => 721,
				"conexion_id" => 532,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3392,
				"anterior" =>3374,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [472] = 
			(
				array(
				"cliente_id" => 533,
				"conexion_id" => 533,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2280,
				"anterior" =>2270,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [473] = 
			(
				array(
				"cliente_id" => 534,
				"conexion_id" => 534,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2302,
				"anterior" =>2287,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [474] = 
			(
				array(
				"cliente_id" => 535,
				"conexion_id" => 535,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2169,
				"anterior" =>2169,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [475] = 
			(
				array(
				"cliente_id" => 536,
				"conexion_id" => 536,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2957,
				"anterior" =>2926,
				"basico" => 98.00,
				"excedente" => 105.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [476] = 
			(
				array(
				"cliente_id" => 537,
				"conexion_id" => 537,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1024,
				"anterior" =>1021,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [477] = 
			(
				array(
				"cliente_id" => 562,
				"conexion_id" => 538,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4923,
				"anterior" =>4904,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [478] = 
			(
				array(
				"cliente_id" => 563,
				"conexion_id" => 539,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 742,
				"anterior" =>742,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [479] = 
			(
				array(
				"cliente_id" => 544,
				"conexion_id" => 540,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2124,
				"anterior" =>2095,
				"basico" => 98.00,
				"excedente" => 95.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [480] = 
			(
				array(
				"cliente_id" => 544,
				"conexion_id" => 540,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2124,
				"anterior" =>2095,
				"basico" => 98.00,
				"excedente" => 95.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [481] = 
			(
				array(
				"cliente_id" => 561,
				"conexion_id" => 541,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1998,
				"anterior" =>1987,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [482] = 
			(
				array(
				"cliente_id" => 747,
				"conexion_id" => 542,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2558,
				"anterior" =>2538,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [483] = 
			(
				array(
				"cliente_id" => 543,
				"conexion_id" => 543,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1836,
				"anterior" =>1826,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [484] = 
			(
				array(
				"cliente_id" => 601,
				"conexion_id" => 544,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3899,
				"anterior" =>3865,
				"basico" => 98.00,
				"excedente" => 120.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [485] = 
			(
				array(
				"cliente_id" => 602,
				"conexion_id" => 545,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4631,
				"anterior" =>4593,
				"basico" => 98.00,
				"excedente" => 140.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [486] = 
			(
				array(
				"cliente_id" => 540,
				"conexion_id" => 546,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2722,
				"anterior" =>2708,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [487] = 
			(
				array(
				"cliente_id" => 541,
				"conexion_id" => 547,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2513,
				"anterior" =>2504,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [488] = 
			(
				array(
				"cliente_id" => 542,
				"conexion_id" => 549,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4055,
				"anterior" =>3992,
				"basico" => 98.00,
				"excedente" => 265.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [489] = 
			(
				array(
				"cliente_id" => 600,
				"conexion_id" => 550,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2256,
				"anterior" =>2231,
				"basico" => 98.00,
				"excedente" => 75.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [490] = 
			(
				array(
				"cliente_id" => 768,
				"conexion_id" => 551,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2557,
				"anterior" =>2543,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [491] = 
			(
				array(
				"cliente_id" => 539,
				"conexion_id" => 552,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2138,
				"anterior" =>2125,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [492] = 
			(
				array(
				"cliente_id" => 597,
				"conexion_id" => 553,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3487,
				"anterior" =>3473,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [493] = 
			(
				array(
				"cliente_id" => 596,
				"conexion_id" => 554,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2433,
				"anterior" =>2433,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [494] = 
			(
				array(
				"cliente_id" => 538,
				"conexion_id" => 555,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2160,
				"anterior" =>2160,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [495] = 
			(
				array(
				"cliente_id" => 603,
				"conexion_id" => 556,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1095,
				"anterior" =>1095,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [496] = 
			(
				array(
				"cliente_id" => 564,
				"conexion_id" => 557,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3309,
				"anterior" =>3309,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [497] = 
			(
				array(
				"cliente_id" => 593,
				"conexion_id" => 558,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2275,
				"anterior" =>2275,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [498] = 
			(
				array(
				"cliente_id" => 599,
				"conexion_id" => 559,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3278,
				"anterior" =>3259,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [499] = 
			(
				array(
				"cliente_id" => 606,
				"conexion_id" => 560,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3286,
				"anterior" =>3277,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [500] = 
			(
				array(
				"cliente_id" => 545,
				"conexion_id" => 561,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2794,
				"anterior" =>2776,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [501] = 
			(
				array(
				"cliente_id" => 546,
				"conexion_id" => 562,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2281,
				"anterior" =>2261,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [502] = 
			(
				array(
				"cliente_id" => 591,
				"conexion_id" => 563,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2670,
				"anterior" =>2670,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [503] = 
			(
				array(
				"cliente_id" => 592,
				"conexion_id" => 564,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1835,
				"anterior" =>1834,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [504] = 
			(
				array(
				"cliente_id" => 761,
				"conexion_id" => 565,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2067,
				"anterior" =>2036,
				"basico" => 98.00,
				"excedente" => 105.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [505] = 
			(
				array(
				"cliente_id" => 547,
				"conexion_id" => 566,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1273,
				"anterior" =>1246,
				"basico" => 98.00,
				"excedente" => 85.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [506] = 
			(
				array(
				"cliente_id" => 566,
				"conexion_id" => 567,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3121,
				"anterior" =>3081,
				"basico" => 98.00,
				"excedente" => 150.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [507] = 
			(
				array(
				"cliente_id" => 567,
				"conexion_id" => 568,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 809,
				"anterior" =>790,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [508] = 
			(
				array(
				"cliente_id" => 568,
				"conexion_id" => 569,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3351,
				"anterior" =>3318,
				"basico" => 98.00,
				"excedente" => 115.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [509] = 
			(
				array(
				"cliente_id" => 569,
				"conexion_id" => 570,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1901,
				"anterior" =>1901,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [510] = 
			(
				array(
				"cliente_id" => 570,
				"conexion_id" => 571,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2818,
				"anterior" =>2818,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [511] = 
			(
				array(
				"cliente_id" => 548,
				"conexion_id" => 572,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1980,
				"anterior" =>1980,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [512] = 
			(
				array(
				"cliente_id" => 549,
				"conexion_id" => 573,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2911,
				"anterior" =>2911,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [513] = 
			(
				array(
				"cliente_id" => 550,
				"conexion_id" => 574,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2632,
				"anterior" =>2628,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [514] = 
			(
				array(
				"cliente_id" => 605,
				"conexion_id" => 575,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3562,
				"anterior" =>3543,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [515] = 
			(
				array(
				"cliente_id" => 571,
				"conexion_id" => 576,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3577,
				"anterior" =>3558,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [516] = 
			(
				array(
				"cliente_id" => 572,
				"conexion_id" => 577,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3611,
				"anterior" =>3606,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [517] = 
			(
				array(
				"cliente_id" => 573,
				"conexion_id" => 578,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3041,
				"anterior" =>3021,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [518] = 
			(
				array(
				"cliente_id" => 574,
				"conexion_id" => 579,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3768,
				"anterior" =>3762,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [519] = 
			(
				array(
				"cliente_id" => 575,
				"conexion_id" => 580,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2993,
				"anterior" =>2971,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [520] = 
			(
				array(
				"cliente_id" => 576,
				"conexion_id" => 581,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2501,
				"anterior" =>2501,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [521] = 
			(
				array(
				"cliente_id" => 551,
				"conexion_id" => 582,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3449,
				"anterior" =>3417,
				"basico" => 98.00,
				"excedente" => 110.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [522] = 
			(
				array(
				"cliente_id" => 552,
				"conexion_id" => 583,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2788,
				"anterior" =>2767,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [523] = 
			(
				array(
				"cliente_id" => 604,
				"conexion_id" => 584,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2480,
				"anterior" =>2776,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [524] = 
			(
				array(
				"cliente_id" => 594,
				"conexion_id" => 585,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3210,
				"anterior" =>3197,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [525] = 
			(
				array(
				"cliente_id" => 553,
				"conexion_id" => 586,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3624,
				"anterior" =>3603,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [526] = 
			(
				array(
				"cliente_id" => 577,
				"conexion_id" => 587,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 931,
				"anterior" =>903,
				"basico" => 98.00,
				"excedente" => 90.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [527] = 
			(
				array(
				"cliente_id" => 578,
				"conexion_id" => 588,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2850,
				"anterior" =>2841,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [528] = 
			(
				array(
				"cliente_id" => 598,
				"conexion_id" => 589,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3467,
				"anterior" =>3467,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [529] = 
			(
				array(
				"cliente_id" => 595,
				"conexion_id" => 590,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3624,
				"anterior" =>3600,
				"basico" => 98.00,
				"excedente" => 70.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [530] = 
			(
				array(
				"cliente_id" => 554,
				"conexion_id" => 591,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2751,
				"anterior" =>2734,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [531] = 
			(
				array(
				"cliente_id" => 579,
				"conexion_id" => 592,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3044,
				"anterior" =>3034,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [532] = 
			(
				array(
				"cliente_id" => 580,
				"conexion_id" => 593,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3737,
				"anterior" =>3724,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [533] = 
			(
				array(
				"cliente_id" => 581,
				"conexion_id" => 594,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 555,
				"anterior" =>536,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [534] = 
			(
				array(
				"cliente_id" => 582,
				"conexion_id" => 595,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3641,
				"anterior" =>3611,
				"basico" => 98.00,
				"excedente" => 100.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [535] = 
			(
				array(
				"cliente_id" => 583,
				"conexion_id" => 596,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3215,
				"anterior" =>3200,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [536] = 
			(
				array(
				"cliente_id" => 584,
				"conexion_id" => 597,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1923,
				"anterior" =>1905,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [537] = 
			(
				array(
				"cliente_id" => 555,
				"conexion_id" => 598,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3419,
				"anterior" =>3386,
				"basico" => 98.00,
				"excedente" => 115.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [538] = 
			(
				array(
				"cliente_id" => 556,
				"conexion_id" => 599,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3686,
				"anterior" =>3665,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [539] = 
			(
				array(
				"cliente_id" => 585,
				"conexion_id" => 600,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2010,
				"anterior" =>1979,
				"basico" => 98.00,
				"excedente" => 105.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [540] = 
			(
				array(
				"cliente_id" => 557,
				"conexion_id" => 601,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3481,
				"anterior" =>3467,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [541] = 
			(
				array(
				"cliente_id" => 586,
				"conexion_id" => 602,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1624,
				"anterior" =>1615,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [542] = 
			(
				array(
				"cliente_id" => 587,
				"conexion_id" => 603,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3136,
				"anterior" =>3136,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [543] = 
			(
				array(
				"cliente_id" => 588,
				"conexion_id" => 604,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1255,
				"anterior" =>1221,
				"basico" => 98.00,
				"excedente" => 120.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [544] = 
			(
				array(
				"cliente_id" => 589,
				"conexion_id" => 605,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2147,
				"anterior" =>2138,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [545] = 
			(
				array(
				"cliente_id" => 590,
				"conexion_id" => 606,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2185,
				"anterior" =>2174,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [546] = 
			(
				array(
				"cliente_id" => 628,
				"conexion_id" => 609,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1848,
				"anterior" =>1841,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [547] = 
			(
				array(
				"cliente_id" => 629,
				"conexion_id" => 610,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2722,
				"anterior" =>2722,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [548] = 
			(
				array(
				"cliente_id" => 630,
				"conexion_id" => 611,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3216,
				"anterior" =>3180,
				"basico" => 98.00,
				"excedente" => 130.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [549] = 
			(
				array(
				"cliente_id" => 316,
				"conexion_id" => 612,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2273,
				"anterior" =>2265,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [550] = 
			(
				array(
				"cliente_id" => 688,
				"conexion_id" => 613,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1963,
				"anterior" =>1909,
				"basico" => 98.00,
				"excedente" => 220.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [551] = 
			(
				array(
				"cliente_id" => 689,
				"conexion_id" => 614,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1945,
				"anterior" =>1897,
				"basico" => 98.00,
				"excedente" => 190.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [552] = 
			(
				array(
				"cliente_id" => 690,
				"conexion_id" => 615,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1893,
				"anterior" =>1866,
				"basico" => 98.00,
				"excedente" => 85.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [553] = 
			(
				array(
				"cliente_id" => 726,
				"conexion_id" => 616,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1640,
				"anterior" =>1536,
				"basico" => 98.00,
				"excedente" => 470.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [554] = 
			(
				array(
				"cliente_id" => 692,
				"conexion_id" => 617,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1651,
				"anterior" =>1603,
				"basico" => 98.00,
				"excedente" => 190.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [555] = 
			(
				array(
				"cliente_id" => 695,
				"conexion_id" => 618,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3544,
				"anterior" =>3511,
				"basico" => 98.00,
				"excedente" => 115.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [556] = 
			(
				array(
				"cliente_id" => 696,
				"conexion_id" => 619,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 3672,
				"anterior" =>3641,
				"basico" => 200.00,
				"excedente" => 128.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [557] = 
			(
				array(
				"cliente_id" => 714,
				"conexion_id" => 620,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2316,
				"anterior" =>2297,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [558] = 
			(
				array(
				"cliente_id" => 715,
				"conexion_id" => 621,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 489,
				"anterior" =>488,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [559] = 
			(
				array(
				"cliente_id" => 716,
				"conexion_id" => 622,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1101,
				"anterior" =>1094,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [560] = 
			(
				array(
				"cliente_id" => 718,
				"conexion_id" => 623,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1551,
				"anterior" =>1551,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [561] = 
			(
				array(
				"cliente_id" => 728,
				"conexion_id" => 624,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1950,
				"anterior" =>1942,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [562] = 
			(
				array(
				"cliente_id" => 729,
				"conexion_id" => 625,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5902,
				"anterior" =>5902,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [563] = 
			(
				array(
				"cliente_id" => 730,
				"conexion_id" => 626,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 959,
				"anterior" =>959,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [564] = 
			(
				array(
				"cliente_id" => 727,
				"conexion_id" => 627,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1050,
				"anterior" =>1050,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [565] = 
			(
				array(
				"cliente_id" => 731,
				"conexion_id" => 628,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 60,
				"anterior" =>51,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [566] = 
			(
				array(
				"cliente_id" => 732,
				"conexion_id" => 629,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2264,
				"anterior" =>2255,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [567] = 
			(
				array(
				"cliente_id" => 745,
				"conexion_id" => 630,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2816,
				"anterior" =>2806,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [568] = 
			(
				array(
				"cliente_id" => 746,
				"conexion_id" => 631,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>98.00,
				"basico" => 0.00 ,
				"excedente" => 98.00,
				"importe" => 10.00,
				"mts" => 00
				)
			);
			$array_mediciones [569] = 
			(
				array(
				"cliente_id" => 752,
				"conexion_id" => 632,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2442,
				"anterior" =>2382,
				"basico" => 98.00,
				"excedente" => 250.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [570] = 
			(
				array(
				"cliente_id" => 748,
				"conexion_id" => 633,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 422,
				"anterior" =>401,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [571] = 
			(
				array(
				"cliente_id" => 749,
				"conexion_id" => 634,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3959,
				"anterior" =>3945,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [572] = 
			(
				array(
				"cliente_id" => 753,
				"conexion_id" => 635,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 36,
				"anterior" =>22,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [573] = 
			(
				array(
				"cliente_id" => 762,
				"conexion_id" => 636,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1420,
				"anterior" =>1397,
				"basico" => 98.00,
				"excedente" => 65.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [574] = 
			(
				array(
				"cliente_id" => 763,
				"conexion_id" => 637,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 770,
				"anterior" =>756,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [575] = 
			(
				array(
				"cliente_id" => 764,
				"conexion_id" => 638,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2375,
				"anterior" =>2363,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [576] = 
			(
				array(
				"cliente_id" => 765,
				"conexion_id" => 639,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 443,
				"anterior" =>440,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [577] = 
			(
				array(
				"cliente_id" => 236,
				"conexion_id" => 640,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1410,
				"anterior" =>1404,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [578] = 
			(
				array(
				"cliente_id" => 777,
				"conexion_id" => 641,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 29579,
				"anterior" =>29456,
				"basico" => 200.00,
				"excedente" => 864.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [579] = 
			(
				array(
				"cliente_id" => 777,
				"conexion_id" => 642,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 42832,
				"anterior" =>41406,
				"basico" => 200.00,
				"excedente" => 11288.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [580] = 
			(
				array(
				"cliente_id" => 770,
				"conexion_id" => 644,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2854,
				"anterior" =>2840,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [581] = 
			(
				array(
				"cliente_id" => 723,
				"conexion_id" => 663,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4,
				"anterior" =>4,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [582] = 
			(
				array(
				"cliente_id" => 772,
				"conexion_id" => 664,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2570,
				"anterior" =>2535,
				"basico" => 98.00,
				"excedente" => 125.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [583] = 
			(
				array(
				"cliente_id" => 771,
				"conexion_id" => 665,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 731,
				"anterior" =>725,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [584] = 
			(
				array(
				"cliente_id" => 773,
				"conexion_id" => 667,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1174,
				"anterior" =>1173,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [585] = 
			(
				array(
				"cliente_id" => 774,
				"conexion_id" => 668,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 350,
				"anterior" =>347,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [586] = 
			(
				array(
				"cliente_id" => 778,
				"conexion_id" => 669,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 579,
				"anterior" =>576,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [587] = 
			(
				array(
				"cliente_id" => 779,
				"conexion_id" => 670,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 182,
				"anterior" =>179,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [588] = 
			(
				array(
				"cliente_id" => 780,
				"conexion_id" => 671,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1350,
				"anterior" =>1336,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [589] = 
			(
				array(
				"cliente_id" => 781,
				"conexion_id" => 672,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 932,
				"anterior" =>922,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [590] = 
			(
				array(
				"cliente_id" => 782,
				"conexion_id" => 673,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 586,
				"anterior" =>579,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [591] = 
			(
				array(
				"cliente_id" => 783,
				"conexion_id" => 674,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1231,
				"anterior" =>1215,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [592] = 
			(
				array(
				"cliente_id" => 784,
				"conexion_id" => 675,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1264,
				"anterior" =>1255,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [593] = 
			(
				array(
				"cliente_id" => 785,
				"conexion_id" => 676,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 637,
				"anterior" =>629,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [594] = 
			(
				array(
				"cliente_id" => 786,
				"conexion_id" => 677,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 731,
				"anterior" =>720,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [595] = 
			(
				array(
				"cliente_id" => 787,
				"conexion_id" => 678,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 153,
				"anterior" =>150,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [596] = 
			(
				array(
				"cliente_id" => 788,
				"conexion_id" => 679,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 701,
				"anterior" =>699,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [597] = 
			(
				array(
				"cliente_id" => 789,
				"conexion_id" => 680,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 899,
				"anterior" =>892,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [598] = 
			(
				array(
				"cliente_id" => 790,
				"conexion_id" => 681,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 65,
				"anterior" =>65,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [599] = 
			(
				array(
				"cliente_id" => 791,
				"conexion_id" => 682,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1734,
				"anterior" =>1728,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [600] = 
			(
				array(
				"cliente_id" => 792,
				"conexion_id" => 683,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 682,
				"anterior" =>670,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [601] = 
			(
				array(
				"cliente_id" => 793,
				"conexion_id" => 684,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 693,
				"anterior" =>686,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [602] = 
			(
				array(
				"cliente_id" => 794,
				"conexion_id" => 685,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1112,
				"anterior" =>1100,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [603] = 
			(
				array(
				"cliente_id" => 795,
				"conexion_id" => 686,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2017,
				"anterior" =>2001,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [604] = 
			(
				array(
				"cliente_id" => 796,
				"conexion_id" => 687,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 874,
				"anterior" =>866,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [605] = 
			(
				array(
				"cliente_id" => 797,
				"conexion_id" => 688,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 763,
				"anterior" =>756,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [606] = 
			(
				array(
				"cliente_id" => 798,
				"conexion_id" => 689,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1827,
				"anterior" =>1808,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [607] = 
			(
				array(
				"cliente_id" => 799,
				"conexion_id" => 690,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1098,
				"anterior" =>1083,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [608] = 
			(
				array(
				"cliente_id" => 800,
				"conexion_id" => 691,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1591,
				"anterior" =>1578,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [609] = 
			(
				array(
				"cliente_id" => 801,
				"conexion_id" => 692,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1069,
				"anterior" =>1053,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [610] = 
			(
				array(
				"cliente_id" => 802,
				"conexion_id" => 693,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 516,
				"anterior" =>515,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [611] = 
			(
				array(
				"cliente_id" => 803,
				"conexion_id" => 694,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2014,
				"anterior" =>1993,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [612] = 
			(
				array(
				"cliente_id" => 804,
				"conexion_id" => 695,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1036,
				"anterior" =>1022,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [613] = 
			(
				array(
				"cliente_id" => 805,
				"conexion_id" => 696,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1017,
				"anterior" =>995,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [614] = 
			(
				array(
				"cliente_id" => 806,
				"conexion_id" => 697,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 720,
				"anterior" =>706,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [615] = 
			(
				array(
				"cliente_id" => 807,
				"conexion_id" => 698,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 570,
				"anterior" =>550,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [616] = 
			(
				array(
				"cliente_id" => 808,
				"conexion_id" => 699,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1497,
				"anterior" =>1488,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [617] = 
			(
				array(
				"cliente_id" => 809,
				"conexion_id" => 700,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1590,
				"anterior" =>1578,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [618] = 
			(
				array(
				"cliente_id" => 810,
				"conexion_id" => 701,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 794,
				"anterior" =>783,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [619] = 
			(
				array(
				"cliente_id" => 811,
				"conexion_id" => 702,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2030,
				"anterior" =>2014,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [620] = 
			(
				array(
				"cliente_id" => 812,
				"conexion_id" => 703,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1077,
				"anterior" =>1057,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [621] = 
			(
				array(
				"cliente_id" => 813,
				"conexion_id" => 704,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1440,
				"anterior" =>1428,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [622] = 
			(
				array(
				"cliente_id" => 814,
				"conexion_id" => 705,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 798,
				"anterior" =>789,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [623] = 
			(
				array(
				"cliente_id" => 815,
				"conexion_id" => 706,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 356,
				"anterior" =>347,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [624] = 
			(
				array(
				"cliente_id" => 816,
				"conexion_id" => 707,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 905,
				"anterior" =>896,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [625] = 
			(
				array(
				"cliente_id" => 817,
				"conexion_id" => 708,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 436,
				"anterior" =>424,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [626] = 
			(
				array(
				"cliente_id" => 818,
				"conexion_id" => 709,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1670,
				"anterior" =>1644,
				"basico" => 98.00,
				"excedente" => 80.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [627] = 
			(
				array(
				"cliente_id" => 819,
				"conexion_id" => 710,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1312,
				"anterior" =>1292,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [628] = 
			(
				array(
				"cliente_id" => 820,
				"conexion_id" => 711,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 854,
				"anterior" =>846,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [629] = 
			(
				array(
				"cliente_id" => 821,
				"conexion_id" => 712,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1288,
				"anterior" =>1277,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [630] = 
			(
				array(
				"cliente_id" => 822,
				"conexion_id" => 713,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 831,
				"anterior" =>819,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [631] = 
			(
				array(
				"cliente_id" => 823,
				"conexion_id" => 714,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1070,
				"anterior" =>1057,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [632] = 
			(
				array(
				"cliente_id" => 824,
				"conexion_id" => 715,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1089,
				"anterior" =>1071,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [633] = 
			(
				array(
				"cliente_id" => 825,
				"conexion_id" => 716,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 240,
				"anterior" =>239,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [634] = 
			(
				array(
				"cliente_id" => 826,
				"conexion_id" => 717,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 583,
				"anterior" =>578,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [635] = 
			(
				array(
				"cliente_id" => 827,
				"conexion_id" => 718,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 813,
				"anterior" =>802,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [636] = 
			(
				array(
				"cliente_id" => 828,
				"conexion_id" => 719,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 828,
				"anterior" =>819,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [637] = 
			(
				array(
				"cliente_id" => 829,
				"conexion_id" => 720,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2130,
				"anterior" =>2103,
				"basico" => 98.00,
				"excedente" => 85.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [638] = 
			(
				array(
				"cliente_id" => 830,
				"conexion_id" => 721,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1552,
				"anterior" =>1543,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [639] = 
			(
				array(
				"cliente_id" => 831,
				"conexion_id" => 722,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1421,
				"anterior" =>1403,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [640] = 
			(
				array(
				"cliente_id" => 832,
				"conexion_id" => 723,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1682,
				"anterior" =>1655,
				"basico" => 98.00,
				"excedente" => 85.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [641] = 
			(
				array(
				"cliente_id" => 833,
				"conexion_id" => 724,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 851,
				"anterior" =>836,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [642] = 
			(
				array(
				"cliente_id" => 834,
				"conexion_id" => 725,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 896,
				"anterior" =>882,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [643] = 
			(
				array(
				"cliente_id" => 835,
				"conexion_id" => 726,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 379,
				"anterior" =>365,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [644] = 
			(
				array(
				"cliente_id" => 836,
				"conexion_id" => 727,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 403,
				"anterior" =>393,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [645] = 
			(
				array(
				"cliente_id" => 837,
				"conexion_id" => 728,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1110,
				"anterior" =>1092,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [646] = 
			(
				array(
				"cliente_id" => 838,
				"conexion_id" => 729,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 611,
				"anterior" =>603,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [647] = 
			(
				array(
				"cliente_id" => 839,
				"conexion_id" => 730,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 709,
				"anterior" =>702,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [648] = 
			(
				array(
				"cliente_id" => 840,
				"conexion_id" => 731,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 870,
				"anterior" =>863,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [649] = 
			(
				array(
				"cliente_id" => 841,
				"conexion_id" => 732,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1036,
				"anterior" =>1025,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [650] = 
			(
				array(
				"cliente_id" => 842,
				"conexion_id" => 733,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 692,
				"anterior" =>684,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [651] = 
			(
				array(
				"cliente_id" => 843,
				"conexion_id" => 734,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 435,
				"anterior" =>427,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [652] = 
			(
				array(
				"cliente_id" => 844,
				"conexion_id" => 735,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1249,
				"anterior" =>1237,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [653] = 
			(
				array(
				"cliente_id" => 845,
				"conexion_id" => 736,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 930,
				"anterior" =>921,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [654] = 
			(
				array(
				"cliente_id" => 846,
				"conexion_id" => 737,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 461,
				"anterior" =>453,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [655] = 
			(
				array(
				"cliente_id" => 847,
				"conexion_id" => 738,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 575,
				"anterior" =>567,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [656] = 
			(
				array(
				"cliente_id" => 848,
				"conexion_id" => 739,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 649,
				"anterior" =>645,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [657] = 
			(
				array(
				"cliente_id" => 849,
				"conexion_id" => 740,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1432,
				"anterior" =>1415,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [658] = 
			(
				array(
				"cliente_id" => 850,
				"conexion_id" => 741,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1326,
				"anterior" =>1310,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [659] = 
			(
				array(
				"cliente_id" => 851,
				"conexion_id" => 742,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 902,
				"anterior" =>889,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [660] = 
			(
				array(
				"cliente_id" => 852,
				"conexion_id" => 743,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 931,
				"anterior" =>918,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [661] = 
			(
				array(
				"cliente_id" => 853,
				"conexion_id" => 744,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 807,
				"anterior" =>790,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [662] = 
			(
				array(
				"cliente_id" => 854,
				"conexion_id" => 745,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1690,
				"anterior" =>1675,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [663] = 
			(
				array(
				"cliente_id" => 855,
				"conexion_id" => 746,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 502,
				"anterior" =>490,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [664] = 
			(
				array(
				"cliente_id" => 856,
				"conexion_id" => 747,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2036,
				"anterior" =>2023,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [665] = 
			(
				array(
				"cliente_id" => 857,
				"conexion_id" => 748,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 837,
				"anterior" =>828,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [666] = 
			(
				array(
				"cliente_id" => 858,
				"conexion_id" => 749,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 821,
				"anterior" =>810,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [667] = 
			(
				array(
				"cliente_id" => 859,
				"conexion_id" => 750,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 815,
				"anterior" =>805,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [668] = 
			(
				array(
				"cliente_id" => 860,
				"conexion_id" => 751,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 806,
				"anterior" =>794,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [669] = 
			(
				array(
				"cliente_id" => 861,
				"conexion_id" => 752,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 578,
				"anterior" =>573,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [670] = 
			(
				array(
				"cliente_id" => 862,
				"conexion_id" => 753,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 936,
				"anterior" =>920,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [671] = 
			(
				array(
				"cliente_id" => 863,
				"conexion_id" => 754,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 741,
				"anterior" =>733,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [672] = 
			(
				array(
				"cliente_id" => 864,
				"conexion_id" => 755,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1099,
				"anterior" =>1083,
				"basico" => 98.00,
				"excedente" => 30.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [673] = 
			(
				array(
				"cliente_id" => 865,
				"conexion_id" => 758,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1460,
				"anterior" =>1455,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [674] = 
			(
				array(
				"cliente_id" => 866,
				"conexion_id" => 759,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1027,
				"anterior" =>1017,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [675] = 
			(
				array(
				"cliente_id" => 867,
				"conexion_id" => 760,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 652,
				"anterior" =>644,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [676] = 
			(
				array(
				"cliente_id" => 868,
				"conexion_id" => 761,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [677] = 
			(
				array(
				"cliente_id" => 869,
				"conexion_id" => 762,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 119,
				"anterior" =>115,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [678] = 
			(
				array(
				"cliente_id" => 870,
				"conexion_id" => 763,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [679] = 
			(
				array(
				"cliente_id" => 871,
				"conexion_id" => 764,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [680] = 
			(
				array(
				"cliente_id" => 872,
				"conexion_id" => 765,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 708,
				"anterior" =>691,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [681] = 
			(
				array(
				"cliente_id" => 873,
				"conexion_id" => 766,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 731,
				"anterior" =>682,
				"basico" => 98.00,
				"excedente" => 195.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [682] = 
			(
				array(
				"cliente_id" => 874,
				"conexion_id" => 767,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 79,
				"anterior" =>79,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [683] = 
			(
				array(
				"cliente_id" => 875,
				"conexion_id" => 768,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 253,
				"anterior" =>239,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [684] = 
			(
				array(
				"cliente_id" => 876,
				"conexion_id" => 769,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 832,
				"anterior" =>810,
				"basico" => 98.00,
				"excedente" => 60.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [685] = 
			(
				array(
				"cliente_id" => 877,
				"conexion_id" => 770,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 715,
				"anterior" =>670,
				"basico" => 98.00,
				"excedente" => 175.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [686] = 
			(
				array(
				"cliente_id" => 878,
				"conexion_id" => 771,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1099,
				"anterior" =>1056,
				"basico" => 98.00,
				"excedente" => 165.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [687] = 
			(
				array(
				"cliente_id" => 879,
				"conexion_id" => 772,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 185,
				"anterior" =>185,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [688] = 
			(
				array(
				"cliente_id" => 880,
				"conexion_id" => 773,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1244,
				"anterior" =>1242,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [689] = 
			(
				array(
				"cliente_id" => 881,
				"conexion_id" => 774,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1571,
				"anterior" =>1562,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [690] = 
			(
				array(
				"cliente_id" => 882,
				"conexion_id" => 775,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3190,
				"anterior" =>3190,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [691] = 
			(
				array(
				"cliente_id" => 883,
				"conexion_id" => 776,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 481,
				"anterior" =>430,
				"basico" => 98.00,
				"excedente" => 205.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [692] = 
			(
				array(
				"cliente_id" => 884,
				"conexion_id" => 777,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1136,
				"anterior" =>1098,
				"basico" => 98.00,
				"excedente" => 140.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [693] = 
			(
				array(
				"cliente_id" => 885,
				"conexion_id" => 778,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 468,
				"anterior" =>465,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [694] = 
			(
				array(
				"cliente_id" => 886,
				"conexion_id" => 779,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 512,
				"anterior" =>494,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [695] = 
			(
				array(
				"cliente_id" => 887,
				"conexion_id" => 780,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 386,
				"anterior" =>379,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [696] = 
			(
				array(
				"cliente_id" => 888,
				"conexion_id" => 781,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 444,
				"anterior" =>417,
				"basico" => 98.00,
				"excedente" => 85.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [697] = 
			(
				array(
				"cliente_id" => 889,
				"conexion_id" => 782,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 29,
				"anterior" =>29,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [698] = 
			(
				array(
				"cliente_id" => 890,
				"conexion_id" => 783,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 318,
				"anterior" =>310,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [699] = 
			(
				array(
				"cliente_id" => 891,
				"conexion_id" => 784,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 368,
				"anterior" =>365,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [700] = 
			(
				array(
				"cliente_id" => 892,
				"conexion_id" => 785,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1544,
				"anterior" =>1459,
				"basico" => 98.00,
				"excedente" => 375.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [701] = 
			(
				array(
				"cliente_id" => 893,
				"conexion_id" => 786,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 588,
				"anterior" =>471,
				"basico" => 98.00,
				"excedente" => 535.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [702] = 
			(
				array(
				"cliente_id" => 894,
				"conexion_id" => 787,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1092,
				"anterior" =>1036,
				"basico" => 98.00,
				"excedente" => 230.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [703] = 
			(
				array(
				"cliente_id" => 895,
				"conexion_id" => 788,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 256,
				"anterior" =>255,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [704] = 
			(
				array(
				"cliente_id" => 896,
				"conexion_id" => 789,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 590,
				"anterior" =>531,
				"basico" => 98.00,
				"excedente" => 245.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [705] = 
			(
				array(
				"cliente_id" => 897,
				"conexion_id" => 790,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 937,
				"anterior" =>870,
				"basico" => 98.00,
				"excedente" => 285.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [706] = 
			(
				array(
				"cliente_id" => 898,
				"conexion_id" => 791,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 321,
				"anterior" =>312,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [707] = 
			(
				array(
				"cliente_id" => 899,
				"conexion_id" => 792,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 412,
				"anterior" =>410,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [708] = 
			(
				array(
				"cliente_id" => 900,
				"conexion_id" => 793,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 800,
				"anterior" =>762,
				"basico" => 98.00,
				"excedente" => 140.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [709] = 
			(
				array(
				"cliente_id" => 901,
				"conexion_id" => 794,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 629,
				"anterior" =>592,
				"basico" => 98.00,
				"excedente" => 135.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [710] = 
			(
				array(
				"cliente_id" => 902,
				"conexion_id" => 795,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 604,
				"anterior" =>579,
				"basico" => 98.00,
				"excedente" => 75.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [711] = 
			(
				array(
				"cliente_id" => 903,
				"conexion_id" => 796,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 670,
				"anterior" =>621,
				"basico" => 98.00,
				"excedente" => 195.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [712] = 
			(
				array(
				"cliente_id" => 904,
				"conexion_id" => 797,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 510,
				"anterior" =>461,
				"basico" => 98.00,
				"excedente" => 195.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [713] = 
			(
				array(
				"cliente_id" => 905,
				"conexion_id" => 798,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 611,
				"anterior" =>586,
				"basico" => 98.00,
				"excedente" => 75.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [714] = 
			(
				array(
				"cliente_id" => 906,
				"conexion_id" => 799,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 958,
				"anterior" =>917,
				"basico" => 98.00,
				"excedente" => 155.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [715] = 
			(
				array(
				"cliente_id" => 907,
				"conexion_id" => 800,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 721,
				"anterior" =>668,
				"basico" => 98.00,
				"excedente" => 215.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [716] = 
			(
				array(
				"cliente_id" => 908,
				"conexion_id" => 801,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3942,
				"anterior" =>3899,
				"basico" => 98.00,
				"excedente" => 165.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [717] = 
			(
				array(
				"cliente_id" => 909,
				"conexion_id" => 802,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2179,
				"anterior" =>2178,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [718] = 
			(
				array(
				"cliente_id" => 910,
				"conexion_id" => 803,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 388,
				"anterior" =>361,
				"basico" => 98.00,
				"excedente" => 85.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [719] = 
			(
				array(
				"cliente_id" => 911,
				"conexion_id" => 804,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>98.00,
				"basico" => 0.00 ,
				"excedente" => 98.00,
				"importe" => 10.00,
				"mts" => 00
				)
			);
			$array_mediciones [720] = 
			(
				array(
				"cliente_id" => 914,
				"conexion_id" => 806,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 182,
				"anterior" =>179,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [721] = 
			(
				array(
				"cliente_id" => 915,
				"conexion_id" => 807,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 297,
				"anterior" =>280,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [722] = 
			(
				array(
				"cliente_id" => 916,
				"conexion_id" => 808,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 247,
				"anterior" =>245,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [723] = 
			(
				array(
				"cliente_id" => 917,
				"conexion_id" => 809,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 806,
				"anterior" =>772,
				"basico" => 98.00,
				"excedente" => 120.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [724] = 
			(
				array(
				"cliente_id" => 918,
				"conexion_id" => 810,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1620,
				"anterior" =>1594,
				"basico" => 98.00,
				"excedente" => 80.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [725] = 
			(
				array(
				"cliente_id" => 919,
				"conexion_id" => 811,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 917,
				"anterior" =>900,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [726] = 
			(
				array(
				"cliente_id" => 912,
				"conexion_id" => 812,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 472,
				"anterior" =>436,
				"basico" => 98.00,
				"excedente" => 130.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [727] = 
			(
				array(
				"cliente_id" => 920,
				"conexion_id" => 813,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 174,
				"anterior" =>150,
				"basico" => 98.00,
				"excedente" => 70.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [728] = 
			(
				array(
				"cliente_id" => 921,
				"conexion_id" => 814,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 229,
				"anterior" =>222,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [729] = 
			(
				array(
				"cliente_id" => 923,
				"conexion_id" => 816,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 208,
				"anterior" =>190,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [730] = 
			(
				array(
				"cliente_id" => 924,
				"conexion_id" => 817,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2173,
				"anterior" =>1968,
				"basico" => 98.00,
				"excedente" => 975.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [731] = 
			(
				array(
				"cliente_id" => 926,
				"conexion_id" => 819,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1018,
				"anterior" =>1018,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [732] = 
			(
				array(
				"cliente_id" => 927,
				"conexion_id" => 820,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 51,
				"anterior" =>51,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [733] = 
			(
				array(
				"cliente_id" => 928,
				"conexion_id" => 821,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 197,
				"anterior" =>197,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [734] = 
			(
				array(
				"cliente_id" => 776,
				"conexion_id" => 824,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 546,
				"anterior" =>531,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [735] = 
			(
				array(
				"cliente_id" => 930,
				"conexion_id" => 825,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 321,
				"anterior" =>313,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [736] = 
			(
				array(
				"cliente_id" => 931,
				"conexion_id" => 826,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 324,
				"anterior" =>314,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [737] = 
			(
				array(
				"cliente_id" => 932,
				"conexion_id" => 827,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 294,
				"anterior" =>294,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [738] = 
			(
				array(
				"cliente_id" => 933,
				"conexion_id" => 828,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 464,
				"anterior" =>373,
				"basico" => 98.00,
				"excedente" => 405.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [739] = 
			(
				array(
				"cliente_id" => 934,
				"conexion_id" => 829,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 308,
				"anterior" =>299,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [740] = 
			(
				array(
				"cliente_id" => 936,
				"conexion_id" => 831,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>98.00,
				"basico" => 0.00 ,
				"excedente" => 98.00,
				"importe" => 10.00,
				"mts" => 00
				)
			);
			$array_mediciones [741] = 
			(
				array(
				"cliente_id" => 938,
				"conexion_id" => 833,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 178,
				"anterior" =>173,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [742] = 
			(
				array(
				"cliente_id" => 939,
				"conexion_id" => 834,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 225,
				"anterior" =>219,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [743] = 
			(
				array(
				"cliente_id" => 940,
				"conexion_id" => 835,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 197,
				"anterior" =>190,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [744] = 
			(
				array(
				"cliente_id" => 941,
				"conexion_id" => 836,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 657,
				"anterior" =>650,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [745] = 
			(
				array(
				"cliente_id" => 942,
				"conexion_id" => 837,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 550,
				"anterior" =>486,
				"basico" => 98.00,
				"excedente" => 270.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [746] = 
			(
				array(
				"cliente_id" => 944,
				"conexion_id" => 839,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 375,
				"anterior" =>370,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [747] = 
			(
				array(
				"cliente_id" => 945,
				"conexion_id" => 840,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2947,
				"anterior" =>2813,
				"basico" => 98.00,
				"excedente" => 620.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [748] = 
			(
				array(
				"cliente_id" => 946,
				"conexion_id" => 841,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1229,
				"anterior" =>1120,
				"basico" => 98.00,
				"excedente" => 495.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [749] = 
			(
				array(
				"cliente_id" => 947,
				"conexion_id" => 842,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 708,
				"anterior" =>708,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [750] = 
			(
				array(
				"cliente_id" => 948,
				"conexion_id" => 843,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 128,
				"anterior" =>126,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [751] = 
			(
				array(
				"cliente_id" => 949,
				"conexion_id" => 844,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 500,
				"anterior" =>470,
				"basico" => 98.00,
				"excedente" => 100.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [752] = 
			(
				array(
				"cliente_id" => 950,
				"conexion_id" => 845,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 180,
				"anterior" =>135,
				"basico" => 98.00,
				"excedente" => 175.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [753] = 
			(
				array(
				"cliente_id" => 951,
				"conexion_id" => 846,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 215,
				"anterior" =>211,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [754] = 
			(
				array(
				"cliente_id" => 952,
				"conexion_id" => 847,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 698,
				"anterior" =>651,
				"basico" => 98.00,
				"excedente" => 185.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [755] = 
			(
				array(
				"cliente_id" => 953,
				"conexion_id" => 848,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 466,
				"anterior" =>424,
				"basico" => 98.00,
				"excedente" => 160.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [756] = 
			(
				array(
				"cliente_id" => 954,
				"conexion_id" => 849,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 229,
				"anterior" =>222,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [757] = 
			(
				array(
				"cliente_id" => 955,
				"conexion_id" => 850,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 169,
				"anterior" =>143,
				"basico" => 98.00,
				"excedente" => 80.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [758] = 
			(
				array(
				"cliente_id" => 957,
				"conexion_id" => 852,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 16,
				"anterior" =>16,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [759] = 
			(
				array(
				"cliente_id" => 958,
				"conexion_id" => 853,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 114,
				"anterior" =>114,
				"basico" => 200.00,
				"excedente" => 0.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [760] = 
			(
				array(
				"cliente_id" => 959,
				"conexion_id" => 854,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [761] = 
			(
				array(
				"cliente_id" => 960,
				"conexion_id" => 860,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 242,
				"anterior" =>98.00,
				"basico" => 0.00 ,
				"excedente" => 98.00,
				"importe" => 10.00,
				"mts" => 00
				)
			);
			$array_mediciones [762] = 
			(
				array(
				"cliente_id" => 962,
				"conexion_id" => 862,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 112,
				"anterior" =>94,
				"basico" => 98.00,
				"excedente" => 40.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [763] = 
			(
				array(
				"cliente_id" => 652,
				"conexion_id" => 863,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 2918,
				"anterior" =>2912,
				"basico" => 200.00,
				"excedente" => 0.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [764] = 
			(
				array(
				"cliente_id" => 963,
				"conexion_id" => 864,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 90,
				"anterior" =>79,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [765] = 
			(
				array(
				"cliente_id" => 965,
				"conexion_id" => 866,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 313,
				"anterior" =>302,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [766] = 
			(
				array(
				"cliente_id" => 966,
				"conexion_id" => 867,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 179,
				"anterior" =>173,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [767] = 
			(
				array(
				"cliente_id" => 967,
				"conexion_id" => 868,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 72,
				"anterior" =>60,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [768] = 
			(
				array(
				"cliente_id" => 968,
				"conexion_id" => 869,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 14,
				"anterior" =>12,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [769] = 
			(
				array(
				"cliente_id" => 969,
				"conexion_id" => 870,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 90,
				"anterior" =>81,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [770] = 
			(
				array(
				"cliente_id" => 970,
				"conexion_id" => 871,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 74,
				"anterior" =>68,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [771] = 
			(
				array(
				"cliente_id" => 971,
				"conexion_id" => 872,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 93,
				"anterior" =>84,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [772] = 
			(
				array(
				"cliente_id" => 972,
				"conexion_id" => 873,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 81,
				"anterior" =>69,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [773] = 
			(
				array(
				"cliente_id" => 973,
				"conexion_id" => 874,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 124,
				"anterior" =>109,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [774] = 
			(
				array(
				"cliente_id" => 974,
				"conexion_id" => 875,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [775] = 
			(
				array(
				"cliente_id" => 975,
				"conexion_id" => 876,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [776] = 
			(
				array(
				"cliente_id" => 976,
				"conexion_id" => 877,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 11,
				"anterior" =>10,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [777] = 
			(
				array(
				"cliente_id" => 977,
				"conexion_id" => 878,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 61,
				"anterior" =>52,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [778] = 
			(
				array(
				"cliente_id" => 978,
				"conexion_id" => 879,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 107,
				"anterior" =>96,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [779] = 
			(
				array(
				"cliente_id" => 979,
				"conexion_id" => 880,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 11,
				"anterior" =>11,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [780] = 
			(
				array(
				"cliente_id" => 980,
				"conexion_id" => 881,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 13,
				"anterior" =>13,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [781] = 
			(
				array(
				"cliente_id" => 981,
				"conexion_id" => 882,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 92,
				"anterior" =>85,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [782] = 
			(
				array(
				"cliente_id" => 982,
				"conexion_id" => 883,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 21,
				"anterior" =>21,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [783] = 
			(
				array(
				"cliente_id" => 983,
				"conexion_id" => 884,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 251,
				"anterior" =>220,
				"basico" => 98.00,
				"excedente" => 105.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [784] = 
			(
				array(
				"cliente_id" => 984,
				"conexion_id" => 885,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7,
				"anterior" =>7,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [785] = 
			(
				array(
				"cliente_id" => 985,
				"conexion_id" => 886,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 112,
				"anterior" =>97,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [786] = 
			(
				array(
				"cliente_id" => 986,
				"conexion_id" => 887,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 117,
				"anterior" =>102,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [787] = 
			(
				array(
				"cliente_id" => 987,
				"conexion_id" => 888,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 105,
				"anterior" =>92,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [788] = 
			(
				array(
				"cliente_id" => 988,
				"conexion_id" => 889,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 228,
				"anterior" =>217,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [789] = 
			(
				array(
				"cliente_id" => 989,
				"conexion_id" => 890,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 91,
				"anterior" =>84,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [790] = 
			(
				array(
				"cliente_id" => 990,
				"conexion_id" => 891,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 115,
				"anterior" =>101,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [791] = 
			(
				array(
				"cliente_id" => 991,
				"conexion_id" => 892,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 57,
				"anterior" =>44,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [792] = 
			(
				array(
				"cliente_id" => 992,
				"conexion_id" => 893,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 15,
				"anterior" =>15,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [793] = 
			(
				array(
				"cliente_id" => 993,
				"conexion_id" => 894,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 46,
				"anterior" =>34,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [794] = 
			(
				array(
				"cliente_id" => 994,
				"conexion_id" => 895,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 101,
				"anterior" =>90,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [795] = 
			(
				array(
				"cliente_id" => 995,
				"conexion_id" => 896,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 180,
				"anterior" =>163,
				"basico" => 98.00,
				"excedente" => 35.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [796] = 
			(
				array(
				"cliente_id" => 996,
				"conexion_id" => 897,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 25,
				"anterior" =>25,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [797] = 
			(
				array(
				"cliente_id" => 997,
				"conexion_id" => 898,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 9,
				"anterior" =>9,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [798] = 
			(
				array(
				"cliente_id" => 998,
				"conexion_id" => 899,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 112,
				"anterior" =>92,
				"basico" => 98.00,
				"excedente" => 50.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [799] = 
			(
				array(
				"cliente_id" => 999,
				"conexion_id" => 900,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 162,
				"anterior" =>151,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [800] = 
			(
				array(
				"cliente_id" => 1000,
				"conexion_id" => 901,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 155,
				"anterior" =>123,
				"basico" => 98.00,
				"excedente" => 110.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [801] = 
			(
				array(
				"cliente_id" => 1001,
				"conexion_id" => 902,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 21,
				"anterior" =>21,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [802] = 
			(
				array(
				"cliente_id" => 1002,
				"conexion_id" => 903,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 112,
				"anterior" =>103,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [803] = 
			(
				array(
				"cliente_id" => 1003,
				"conexion_id" => 904,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 25,
				"anterior" =>25,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [804] = 
			(
				array(
				"cliente_id" => 1004,
				"conexion_id" => 905,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 68,
				"anterior" =>58,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [805] = 
			(
				array(
				"cliente_id" => 1005,
				"conexion_id" => 906,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 18,
				"anterior" =>18,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [806] = 
			(
				array(
				"cliente_id" => 1006,
				"conexion_id" => 907,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 121,
				"anterior" =>110,
				"basico" => 98.00,
				"excedente" => 5.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [807] = 
			(
				array(
				"cliente_id" => 1007,
				"conexion_id" => 908,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 185,
				"anterior" =>170,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [808] = 
			(
				array(
				"cliente_id" => 1008,
				"conexion_id" => 909,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 48,
				"anterior" =>38,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [809] = 
			(
				array(
				"cliente_id" => 1009,
				"conexion_id" => 910,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 195,
				"anterior" =>181,
				"basico" => 98.00,
				"excedente" => 20.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [810] = 
			(
				array(
				"cliente_id" => 1010,
				"conexion_id" => 911,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 31,
				"anterior" =>28,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [811] = 
			(
				array(
				"cliente_id" => 1011,
				"conexion_id" => 912,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 92,
				"anterior" =>80,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [812] = 
			(
				array(
				"cliente_id" => 1012,
				"conexion_id" => 913,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 149,
				"anterior" =>134,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [813] = 
			(
				array(
				"cliente_id" => 1013,
				"conexion_id" => 914,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 115,
				"anterior" =>105,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [814] = 
			(
				array(
				"cliente_id" => 1014,
				"conexion_id" => 915,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 70,
				"anterior" =>62,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [815] = 
			(
				array(
				"cliente_id" => 1015,
				"conexion_id" => 916,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [816] = 
			(
				array(
				"cliente_id" => 1016,
				"conexion_id" => 917,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [817] = 
			(
				array(
				"cliente_id" => 1017,
				"conexion_id" => 918,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 67,
				"anterior" =>64,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [818] = 
			(
				array(
				"cliente_id" => 1018,
				"conexion_id" => 919,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 146,
				"anterior" =>131,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [819] = 
			(
				array(
				"cliente_id" => 1019,
				"conexion_id" => 920,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 84,
				"anterior" =>78,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [820] = 
			(
				array(
				"cliente_id" => 1020,
				"conexion_id" => 921,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7,
				"anterior" =>7,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [821] = 
			(
				array(
				"cliente_id" => 1021,
				"conexion_id" => 922,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 136,
				"anterior" =>127,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [822] = 
			(
				array(
				"cliente_id" => 1022,
				"conexion_id" => 923,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 142,
				"anterior" =>129,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [823] = 
			(
				array(
				"cliente_id" => 1023,
				"conexion_id" => 924,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 68,
				"anterior" =>63,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [824] = 
			(
				array(
				"cliente_id" => 1024,
				"conexion_id" => 925,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 35,
				"anterior" =>30,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [825] = 
			(
				array(
				"cliente_id" => 1025,
				"conexion_id" => 926,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 151,
				"anterior" =>142,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [826] = 
			(
				array(
				"cliente_id" => 1026,
				"conexion_id" => 927,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 69,
				"anterior" =>61,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [827] = 
			(
				array(
				"cliente_id" => 1027,
				"conexion_id" => 928,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 81,
				"anterior" =>72,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [828] = 
			(
				array(
				"cliente_id" => 1028,
				"conexion_id" => 929,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 26,
				"anterior" =>26,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [829] = 
			(
				array(
				"cliente_id" => 1029,
				"conexion_id" => 930,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 132,
				"anterior" =>120,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [830] = 
			(
				array(
				"cliente_id" => 1030,
				"conexion_id" => 931,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 91,
				"anterior" =>84,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [831] = 
			(
				array(
				"cliente_id" => 1031,
				"conexion_id" => 932,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 140,
				"anterior" =>132,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [832] = 
			(
				array(
				"cliente_id" => 1032,
				"conexion_id" => 933,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 51,
				"anterior" =>48,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [833] = 
			(
				array(
				"cliente_id" => 1033,
				"conexion_id" => 934,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 30,
				"anterior" =>22,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [834] = 
			(
				array(
				"cliente_id" => 1034,
				"conexion_id" => 935,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 84,
				"anterior" =>71,
				"basico" => 98.00,
				"excedente" => 15.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [835] = 
			(
				array(
				"cliente_id" => 1035,
				"conexion_id" => 936,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6,
				"anterior" =>6,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [836] = 
			(
				array(
				"cliente_id" => 1036,
				"conexion_id" => 937,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 173,
				"anterior" =>161,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [837] = 
			(
				array(
				"cliente_id" => 1037,
				"conexion_id" => 938,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 19,
				"anterior" =>19,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [838] = 
			(
				array(
				"cliente_id" => 1038,
				"conexion_id" => 939,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 16,
				"anterior" =>15,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [839] = 
			(
				array(
				"cliente_id" => 1039,
				"conexion_id" => 940,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2,
				"anterior" =>2,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [840] = 
			(
				array(
				"cliente_id" => 1040,
				"conexion_id" => 941,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 134,
				"anterior" =>115,
				"basico" => 98.00,
				"excedente" => 45.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [841] = 
			(
				array(
				"cliente_id" => 1041,
				"conexion_id" => 942,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 117,
				"anterior" =>87,
				"basico" => 98.00,
				"excedente" => 100.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [842] = 
			(
				array(
				"cliente_id" => 1042,
				"conexion_id" => 945,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 124,
				"anterior" =>109,
				"basico" => 98.00,
				"excedente" => 25.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [843] = 
			(
				array(
				"cliente_id" => 1043,
				"conexion_id" => 946,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3,
				"anterior" =>3,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [844] = 
			(
				array(
				"cliente_id" => 1044,
				"conexion_id" => 947,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 177,
				"anterior" =>165,
				"basico" => 98.00,
				"excedente" => 10.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [845] = 
			(
				array(
				"cliente_id" => 1045,
				"conexion_id" => 948,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 12,
				"anterior" =>12,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [846] = 
			(
				array(
				"cliente_id" => 1047,
				"conexion_id" => 949,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 2770,
				"anterior" =>2715,
				"basico" => 200.00,
				"excedente" => 320.00,
				"importe" => 200.00,
				"mts" => 15.00
				)
			);
			$array_mediciones [847] = 
			(
				array(
				"cliente_id" => 1048,
				"conexion_id" => 950,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4,
				"anterior" =>4,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [848] = 
			(
				array(
				"cliente_id" => 1049,
				"conexion_id" => 951,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6,
				"anterior" =>5,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [849] = 
			(
				array(
				"cliente_id" => 1050,
				"conexion_id" => 952,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [850] = 
			(
				array(
				"cliente_id" => 1051,
				"conexion_id" => 953,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [851] = 
			(
				array(
				"cliente_id" => 1052,
				"conexion_id" => 954,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>1,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [852] = 
			(
				array(
				"cliente_id" => 1053,
				"conexion_id" => 955,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 48,
				"anterior" =>27,
				"basico" => 98.00,
				"excedente" => 55.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [853] = 
			(
				array(
				"cliente_id" => 1054,
				"conexion_id" => 956,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>98.00,
				"basico" => 0.00 ,
				"excedente" => 98.00,
				"importe" => 10.00,
				"mts" => 00
				)
			);
			$array_mediciones [854] = 
			(
				array(
				"cliente_id" => 1055,
				"conexion_id" => 957,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>0,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [855] = 
			(
				array(
				"cliente_id" => 1056,
				"conexion_id" => 958,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>0,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [856] = 
			(
				array(
				"cliente_id" => 1057,
				"conexion_id" => 959,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>0,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [857] = 
			(
				array(
				"cliente_id" => 1058,
				"conexion_id" => 960,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1,
				"anterior" =>0,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [858] = 
			(
				array(
				"cliente_id" => 1059,
				"conexion_id" => 961,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>0,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
			$array_mediciones [859] = 
			(
				array(
				"cliente_id" => 1060,
				"conexion_id" => 962,
				"mes" => 10,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 0,
				"anterior" =>0,
				"basico" => 98.00,
				"excedente" => 0.00,
				"importe" => 98.00,
				"mts" => 10.00
				)
			);
				*/










				/*
							$array_mediciones [1] = 
				(
					array(
					"cliente_id" => 19,
					"conexion_id" => 1,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 156,
					"anterior" => 156,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [2] = 
				(
					array(
					"cliente_id" => 62,
					"conexion_id" => 2,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1473,
					"anterior" => 1462,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [3] = 
				(
					array(
					"cliente_id" => 63,
					"conexion_id" => 3,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1143,
					"anterior" => 1131,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [4] = 
				(
					array(
					"cliente_id" => 743,
					"conexion_id" => 6,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 284,
					"anterior" => 272,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [5] = 
				(
					array(
					"cliente_id" => 702,
					"conexion_id" => 8,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5233,
					"anterior" => 5221,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [6] = 
				(
					array(
					"cliente_id" => 24,
					"conexion_id" => 9,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 160,
					"anterior" => 160,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [7] = 
				(
					array(
					"cliente_id" => 722,
					"conexion_id" => 10,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6152,
					"anterior" => 6088,
					"basico" => 100,
					"excedente" => 270,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [8] = 
				(
					array(
					"cliente_id" => 56,
					"conexion_id" => 11,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1014,
					"anterior" => 980,
					"basico" => 100,
					"excedente" => 120,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [9] = 
				(
					array(
					"cliente_id" => 55,
					"conexion_id" => 12,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3,
					"anterior" => 3,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [10] = 
				(
					array(
					"cliente_id" => 6,
					"conexion_id" => 13,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7531,
					"anterior" => 7428,
					"basico" => 100,
					"excedente" => 465,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [11] = 
				(
					array(
					"cliente_id" => 26,
					"conexion_id" => 14,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7872,
					"anterior" => 7863,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [12] = 
				(
					array(
					"cliente_id" => 26,
					"conexion_id" => 15,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [13] = 
				(
					array(
					"cliente_id" => 750,
					"conexion_id" => 16,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1757,
					"anterior" => 1757,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [14] = 
				(
					array(
					"cliente_id" => 54,
					"conexion_id" => 17,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 8026,
					"anterior" => 7985,
					"basico" => 100,
					"excedente" => 155,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [15] = 
				(
					array(
					"cliente_id" => 701,
					"conexion_id" => 18,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 289,
					"anterior" => 284,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [16] = 
				(
					array(
					"cliente_id" => 29,
					"conexion_id" => 19,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 869,
					"anterior" => 822,
					"basico" => 100,
					"excedente" => 185,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [17] = 
				(
					array(
					"cliente_id" => 11,
					"conexion_id" => 20,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 641,
					"anterior" => 641,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [18] = 
				(
					array(
					"cliente_id" => 30,
					"conexion_id" => 21,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 192,
					"anterior" => 174,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [19] = 
				(
					array(
					"cliente_id" => 31,
					"conexion_id" => 22,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1897,
					"anterior" => 1897,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [20] = 
				(
					array(
					"cliente_id" => 32,
					"conexion_id" => 24,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 341,
					"anterior" => 327,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [21] = 
				(
					array(
					"cliente_id" => 33,
					"conexion_id" => 25,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 416,
					"anterior" => 393,
					"basico" => 100,
					"excedente" => 65,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [22] = 
				(
					array(
					"cliente_id" => 2,
					"conexion_id" =>26,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 170,
					"anterior" => 170,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					 
					)
				);
				$array_mediciones [23] = 
				(
					array(
					"cliente_id" => 654,
					"conexion_id" => 27,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3902,
					"anterior" => 3883,
					"basico" => 220,
					"excedente" => 32,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [24] = 
				(
					array(
					"cliente_id" => 8,
					"conexion_id" =>28,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 8617,
					"anterior" => 8553,
					"basico" => 100,
					"excedente" => 270,
					"importe" => 100,
					"mts" => 10
					 
					)
				);
				$array_mediciones [25] = 
				(
					array(
					"cliente_id" => 53,
					"conexion_id" => 31,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 9941,
					"anterior" => 9941,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [26] = 
				(
					array(
					"cliente_id" => 53,
					"conexion_id" => 32,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2075,
					"anterior" => 2021,
					"basico" => 100,
					"excedente" => 220,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [27] = 
				(
					array(
					"cliente_id" => 51,
					"conexion_id" => 34,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3426,
					"anterior" => 3426,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [28] = 
				(
					array(
					"cliente_id" => 71,
					"conexion_id" => 35,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5568,
					"anterior" => 5553,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [29] = 
				(
					array(
					"cliente_id" => 150,
					"conexion_id" => 36,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4405,
					"anterior" => 4390,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [30] = 
				(
					array(
					"cliente_id" => 49,
					"conexion_id" => 37,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4483,
					"anterior" => 4483,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [31] = 
				(
					array(
					"cliente_id" => 48,
					"conexion_id" => 38,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2355,
					"anterior" => 2352,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [32] = 
				(
					array(
					"cliente_id" => 108,
					"conexion_id" => 39,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3832,
					"anterior" => 3815,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [33] = 
				(
					array(
					"cliente_id" => 68,
					"conexion_id" => 40,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 78,
					"anterior" => 12,
					"basico" => 100,
					"excedente" => 280,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [34] = 
				(
					array(
					"cliente_id" => 616,
					"conexion_id" => 41,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4937,
					"anterior" => 4937,
					"basico" => 220,
					"excedente" => 0,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [35] = 
				(
					array(
					"cliente_id" => 9,
					"conexion_id" =>42,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3773,
					"anterior" => 3762,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					 
					)
				);
				$array_mediciones [36] = 
				(
					array(
					"cliente_id" => 498,
					"conexion_id" => 45,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3242,
					"anterior" => 3050,
					"basico" => 220,
					"excedente" => 1416,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [37] = 
				(
					array(
					"cliente_id" => 4,
					"conexion_id" =>46,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1785,
					"anterior" => 1690,
					"basico" => 100,
					"excedente" => 425,
					"importe" => 100,
					"mts" => 10
					 
					)
				);
				$array_mediciones [38] = 
				(
					array(
					"cliente_id" => 2,
					"conexion_id" =>47,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 19,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					 
					)
				);
				$array_mediciones [39] = 
				(
					array(
					"cliente_id" => 623,
					"conexion_id" => 48,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3815,
					"anterior" => 3726,
					"basico" => 100,
					"excedente" => 395,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [40] = 
				(
					array(
					"cliente_id" => 702,
					"conexion_id" => 49,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 479,
					"anterior" => 476,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [41] = 
				(
					array(
					"cliente_id" => 43,
					"conexion_id" => 50,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 120,
					"anterior" => 120,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [42] = 
				(
					array(
					"cliente_id" => 141,
					"conexion_id" => 52,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7,
					"anterior" => 7,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [43] = 
				(
					array(
					"cliente_id" => 73,
					"conexion_id" => 53,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5707,
					"anterior" => 5707,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [44] = 
				(
					array(
					"cliente_id" => 3,
					"conexion_id" =>54,
					"mes" =>11,
					"anio" =>2017,
					"actual" =>2729,
					"anterior" =>2715,
					"basico" =>100,
					"excedente" =>20,
					"importe" =>100,
					"mts" =>10
					)
				);
				$array_mediciones [45] = 
				(
					array(
					"cliente_id" => 145,
					"conexion_id" => 55,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7489,
					"anterior" => 7481,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [46] = 
				(
					array(
					"cliente_id" => 39,
					"conexion_id" => 57,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5180,
					"anterior" => 5140,
					"basico" => 100,
					"excedente" => 150,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [47] = 
				(
					array(
					"cliente_id" => 76,
					"conexion_id" => 59,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 9891,
					"anterior" => 9891,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [48] = 
				(
					array(
					"cliente_id" => 151,
					"conexion_id" => 60,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5983,
					"anterior" => 5983,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [49] = 
				(
					array(
					"cliente_id" => 77,
					"conexion_id" => 63,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 909,
					"anterior" => 875,
					"basico" => 100,
					"excedente" => 120,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [50] = 
				(
					array(
					"cliente_id" => 78,
					"conexion_id" => 64,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3251,
					"anterior" => 3242,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [51] = 
				(
					array(
					"cliente_id" => 79,
					"conexion_id" => 65,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1424,
					"anterior" => 1424,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [52] = 
				(
					array(
					"cliente_id" => 703,
					"conexion_id" => 66,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 10964,
					"anterior" => 10763,
					"basico" => 220,
					"excedente" => 1488,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [53] = 
				(
					array(
					"cliente_id" => 143,
					"conexion_id" => 67,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1999,
					"anterior" => 1999,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [54] = 
				(
					array(
					"cliente_id" => 81,
					"conexion_id" => 68,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6221,
					"anterior" => 6221,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [55] = 
				(
					array(
					"cliente_id" => 81,
					"conexion_id" => 69,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6671,
					"anterior" => 6671,
					"basico" => 220,
					"excedente" => 0,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [56] = 
				(
					array(
					"cliente_id" => 719,
					"conexion_id" => 70,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 540,
					"anterior" => 502,
					"basico" => 100,
					"excedente" => 140,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [57] = 
				(
					array(
					"cliente_id" => 83,
					"conexion_id" => 71,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 0,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [58] = 
				(
					array(
					"cliente_id" => 999,
					"conexion_id" => 72,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 0,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [59] = 
				(
					array(
					"cliente_id" => 84,
					"conexion_id" => 73,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1485,
					"anterior" => 1476,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [60] = 
				(
					array(
					"cliente_id" => 83,
					"conexion_id" => 74,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3085,
					"anterior" => 3085,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [61] = 
				(
					array(
					"cliente_id" => 85,
					"conexion_id" => 75,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [62] = 
				(
					array(
					"cliente_id" => 86,
					"conexion_id" => 76,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [63] = 
				(
					array(
					"cliente_id" => 87,
					"conexion_id" => 77,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7527,
					"anterior" => 7520,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [64] = 
				(
					array(
					"cliente_id" => 83,
					"conexion_id" => 78,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2401,
					"anterior" => 2401,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [65] = 
				(
					array(
					"cliente_id" => 83,
					"conexion_id" => 79,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3510,
					"anterior" => 3492,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [66] = 
				(
					array(
					"cliente_id" => 89,
					"conexion_id" => 81,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5965,
					"anterior" => 5965,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [67] = 
				(
					array(
					"cliente_id" => 90,
					"conexion_id" => 82,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7545,
					"anterior" => 7545,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [68] = 
				(
					array(
					"cliente_id" => 129,
					"conexion_id" => 83,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2158,
					"anterior" => 2151,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [69] = 
				(
					array(
					"cliente_id" => 91,
					"conexion_id" => 84,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5554,
					"anterior" => 5546,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [70] = 
				(
					array(
					"cliente_id" => 956,
					"conexion_id" => 85,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5104,
					"anterior" => 5097,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [71] = 
				(
					array(
					"cliente_id" => 94,
					"conexion_id" => 89,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6011,
					"anterior" => 6011,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [72] = 
				(
					array(
					"cliente_id" => 96,
					"conexion_id" => 91,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 918,
					"anterior" => 788,
					"basico" => 220,
					"excedente" => 920,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [73] = 
				(
					array(
					"cliente_id" => 98,
					"conexion_id" => 93,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 569,
					"anterior" => 518,
					"basico" => 100,
					"excedente" => 205,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [74] = 
				(
					array(
					"cliente_id" => 86,
					"conexion_id" => 94,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 642,
					"anterior" => 634,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [75] = 
				(
					array(
					"cliente_id" => 86,
					"conexion_id" => 95,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [76] = 
				(
					array(
					"cliente_id" => 102,
					"conexion_id" => 97,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1917,
					"anterior" => 1908,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [77] = 
				(
					array(
					"cliente_id" => 144,
					"conexion_id" => 98,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 951,
					"anterior" => 940,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [78] = 
				(
					array(
					"cliente_id" => 73,
					"conexion_id" => 100,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 182,
					"anterior" => 169,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [79] = 
				(
					array(
					"cliente_id" => 86,
					"conexion_id" => 101,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3612,
					"anterior" => 3612,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [80] = 
				(
					array(
					"cliente_id" => 104,
					"conexion_id" => 102,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5297,
					"anterior" => 5262,
					"basico" => 100,
					"excedente" => 125,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [81] = 
				(
					array(
					"cliente_id" => 92,
					"conexion_id" => 103,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3719,
					"anterior" => 3708,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [82] = 
				(
					array(
					"cliente_id" => 107,
					"conexion_id" => 105,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3054,
					"anterior" => 3050,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [83] = 
				(
					array(
					"cliente_id" => 108,
					"conexion_id" => 106,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 753,
					"anterior" => 745,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [84] = 
				(
					array(
					"cliente_id" => 39,
					"conexion_id" => 107,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 0,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [85] = 
				(
					array(
					"cliente_id" => 109,
					"conexion_id" => 108,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6365,
					"anterior" => 6356,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [86] = 
				(
					array(
					"cliente_id" => 110,
					"conexion_id" => 109,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2620,
					"anterior" => 2620,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [87] = 
				(
					array(
					"cliente_id" => 111,
					"conexion_id" => 110,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6607,
					"anterior" => 6595,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [88] = 
				(
					array(
					"cliente_id" => 125,
					"conexion_id" => 111,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3853,
					"anterior" => 3804,
					"basico" => 100,
					"excedente" => 195,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [89] = 
				(
					array(
					"cliente_id" => 112,
					"conexion_id" => 112,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1535,
					"anterior" => 1527,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [90] = 
				(
					array(
					"cliente_id" => 113,
					"conexion_id" => 113,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 183,
					"anterior" => 127,
					"basico" => 100,
					"excedente" => 230,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [91] = 
				(
					array(
					"cliente_id" => 114,
					"conexion_id" => 114,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1721,
					"anterior" => 1712,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [92] = 
				(
					array(
					"cliente_id" => 115,
					"conexion_id" => 115,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 744,
					"anterior" => 732,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [93] = 
				(
					array(
					"cliente_id" => 116,
					"conexion_id" => 116,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7797,
					"anterior" => 7797,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [94] = 
				(
					array(
					"cliente_id" => 117,
					"conexion_id" => 117,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7577,
					"anterior" => 7190,
					"basico" => 100,
					"excedente" => 1885,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [95] = 
				(
					array(
					"cliente_id" => 119,
					"conexion_id" => 119,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 634,
					"anterior" => 626,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [96] = 
				(
					array(
					"cliente_id" => 119,
					"conexion_id" => 120,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3726,
					"anterior" => 3697,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [97] = 
				(
					array(
					"cliente_id" => 85,
					"conexion_id" => 123,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 0,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [98] = 
				(
					array(
					"cliente_id" => 85,
					"conexion_id" => 124,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [99] = 
				(
					array(
					"cliente_id" => 130,
					"conexion_id" => 125,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2194,
					"anterior" => 2191,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [100] = 
				(
					array(
					"cliente_id" => 122,
					"conexion_id" => 126,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3114,
					"anterior" => 3114,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [101] = 
				(
					array(
					"cliente_id" => 123,
					"conexion_id" => 127,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3193,
					"anterior" => 3193,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [102] = 
				(
					array(
					"cliente_id" => 153,
					"conexion_id" => 131,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1500,
					"anterior" => 1485,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [103] = 
				(
					array(
					"cliente_id" => 127,
					"conexion_id" => 132,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2792,
					"anterior" => 2774,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [104] = 
				(
					array(
					"cliente_id" => 131,
					"conexion_id" => 133,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3560,
					"anterior" => 3528,
					"basico" => 100,
					"excedente" => 110,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [105] = 
				(
					array(
					"cliente_id" => 132,
					"conexion_id" => 134,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3809,
					"anterior" => 3809,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [106] = 
				(
					array(
					"cliente_id" => 133,
					"conexion_id" => 136,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2555,
					"anterior" => 2545,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [107] = 
				(
					array(
					"cliente_id" => 134,
					"conexion_id" => 137,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 8226,
					"anterior" => 8217,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [108] = 
				(
					array(
					"cliente_id" => 137,
					"conexion_id" => 139,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 785,
					"anterior" => 729,
					"basico" => 100,
					"excedente" => 230,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [109] = 
				(
					array(
					"cliente_id" => 138,
					"conexion_id" => 140,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3930,
					"anterior" => 3882,
					"basico" => 100,
					"excedente" => 190,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [110] = 
				(
					array(
					"cliente_id" => 146,
					"conexion_id" => 143,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2284,
					"anterior" => 2284,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [111] = 
				(
					array(
					"cliente_id" => 155,
					"conexion_id" => 145,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4755,
					"anterior" => 4755,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [112] = 
				(
					array(
					"cliente_id" => 156,
					"conexion_id" => 146,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 482,
					"anterior" => 477,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [113] = 
				(
					array(
					"cliente_id" => 157,
					"conexion_id" => 147,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1582,
					"anterior" => 1571,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [114] = 
				(
					array(
					"cliente_id" => 158,
					"conexion_id" => 148,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3772,
					"anterior" => 3761,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [115] = 
				(
					array(
					"cliente_id" => 158,
					"conexion_id" => 149,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 961,
					"anterior" => 961,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [116] = 
				(
					array(
					"cliente_id" => 86,
					"conexion_id" => 150,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1624,
					"anterior" => 1615,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [117] = 
				(
					array(
					"cliente_id" => 86,
					"conexion_id" => 151,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2968,
					"anterior" => 2957,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [118] = 
				(
					array(
					"cliente_id" => 160,
					"conexion_id" => 152,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4129,
					"anterior" => 4113,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [119] = 
				(
					array(
					"cliente_id" => 161,
					"conexion_id" => 153,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3914,
					"anterior" => 3904,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [120] = 
				(
					array(
					"cliente_id" => 162,
					"conexion_id" => 154,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2156,
					"anterior" => 2156,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [121] = 
				(
					array(
					"cliente_id" => 162,
					"conexion_id" => 155,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5512,
					"anterior" => 5511,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [122] = 
				(
					array(
					"cliente_id" => 159,
					"conexion_id" => 156,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1763,
					"anterior" => 1754,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [123] = 
				(
					array(
					"cliente_id" => 163,
					"conexion_id" => 157,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 420,
					"anterior" => 408,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [124] = 
				(
					array(
					"cliente_id" => 165,
					"conexion_id" => 158,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2352,
					"anterior" => 2330,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [125] = 
				(
					array(
					"cliente_id" => 86,
					"conexion_id" => 159,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1205,
					"anterior" => 1172,
					"basico" => 100,
					"excedente" => 115,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [126] = 
				(
					array(
					"cliente_id" => 166,
					"conexion_id" => 160,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4037,
					"anterior" => 4037,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [127] = 
				(
					array(
					"cliente_id" => 168,
					"conexion_id" => 161,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3626,
					"anterior" => 3616,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [128] = 
				(
					array(
					"cliente_id" => 169,
					"conexion_id" => 162,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3232,
					"anterior" => 3192,
					"basico" => 100,
					"excedente" => 150,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [129] = 
				(
					array(
					"cliente_id" => 734,
					"conexion_id" => 163,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6573,
					"anterior" => 6561,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [130] = 
				(
					array(
					"cliente_id" => 171,
					"conexion_id" => 164,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4769,
					"anterior" => 4757,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [131] = 
				(
					array(
					"cliente_id" => 172,
					"conexion_id" => 165,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3616,
					"anterior" => 3583,
					"basico" => 100,
					"excedente" => 115,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [132] = 
				(
					array(
					"cliente_id" => 173,
					"conexion_id" => 166,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1695,
					"anterior" => 1689,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [133] = 
				(
					array(
					"cliente_id" => 174,
					"conexion_id" => 167,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2101,
					"anterior" => 2101,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [134] = 
				(
					array(
					"cliente_id" => 174,
					"conexion_id" => 168,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2421,
					"anterior" => 2392,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [135] = 
				(
					array(
					"cliente_id" => 738,
					"conexion_id" => 169,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3568,
					"anterior" => 3560,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [136] = 
				(
					array(
					"cliente_id" => 742,
					"conexion_id" => 170,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 439,
					"anterior" => 406,
					"basico" => 100,
					"excedente" => 115,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [137] = 
				(
					array(
					"cliente_id" => 178,
					"conexion_id" => 172,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4122,
					"anterior" => 4093,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [138] = 
				(
					array(
					"cliente_id" => 179,
					"conexion_id" => 173,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1416,
					"anterior" => 1398,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [139] = 
				(
					array(
					"cliente_id" => 180,
					"conexion_id" => 174,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 9619,
					"anterior" => 9597,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [140] = 
				(
					array(
					"cliente_id" => 181,
					"conexion_id" => 175,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4947,
					"anterior" => 4931,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [141] = 
				(
					array(
					"cliente_id" => 184,
					"conexion_id" => 177,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1330,
					"anterior" => 1321,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [142] = 
				(
					array(
					"cliente_id" => 185,
					"conexion_id" => 178,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [143] = 
				(
					array(
					"cliente_id" => 186,
					"conexion_id" => 179,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2498,
					"anterior" => 2487,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [144] = 
				(
					array(
					"cliente_id" => 187,
					"conexion_id" => 180,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2934,
					"anterior" => 2926,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [145] = 
				(
					array(
					"cliente_id" => 182,
					"conexion_id" => 185,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1908,
					"anterior" => 1890,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [146] = 
				(
					array(
					"cliente_id" => 755,
					"conexion_id" => 186,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 332,
					"anterior" => 332,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [147] = 
				(
					array(
					"cliente_id" => 193,
					"conexion_id" => 187,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1819,
					"anterior" => 1812,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [148] = 
				(
					array(
					"cliente_id" => 751,
					"conexion_id" => 188,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [149] = 
				(
					array(
					"cliente_id" => 195,
					"conexion_id" => 189,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2147,
					"anterior" => 2136,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [150] = 
				(
					array(
					"cliente_id" => 199,
					"conexion_id" => 194,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2427,
					"anterior" => 2424,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [151] = 
				(
					array(
					"cliente_id" => 6,
					"conexion_id" =>196,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 187,
					"anterior" => 164,
					"basico" => 100,
					"excedente" => 65,
					"importe" => 100,
					"mts" => 10
					 
					)
				);
				$array_mediciones [152] = 
				(
					array(
					"cliente_id" => 609,
					"conexion_id" => 197,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3675,
					"anterior" => 3648,
					"basico" => 100,
					"excedente" => 85,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [153] = 
				(
					array(
					"cliente_id" => 610,
					"conexion_id" => 198,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 382,
					"anterior" => 338,
					"basico" => 100,
					"excedente" => 170,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [154] = 
				(
					array(
					"cliente_id" => 617,
					"conexion_id" => 199,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2038,
					"anterior" => 2038,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [155] = 
				(
					array(
					"cliente_id" => 400,
					"conexion_id" => 200,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4940,
					"anterior" => 4928,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [156] = 
				(
					array(
					"cliente_id" => 401,
					"conexion_id" => 201,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4345,
					"anterior" => 4330,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [157] = 
				(
					array(
					"cliente_id" => 402,
					"conexion_id" => 202,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1775,
					"anterior" => 1766,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [158] = 
				(
					array(
					"cliente_id" => 403,
					"conexion_id" => 203,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3180,
					"anterior" => 3170,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [159] = 
				(
					array(
					"cliente_id" => 404,
					"conexion_id" => 204,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5821,
					"anterior" => 5792,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [160] = 
				(
					array(
					"cliente_id" => 405,
					"conexion_id" => 205,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3551,
					"anterior" => 3542,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [161] = 
				(
					array(
					"cliente_id" => 406,
					"conexion_id" => 206,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3692,
					"anterior" => 3675,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [162] = 
				(
					array(
					"cliente_id" => 407,
					"conexion_id" => 207,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4256,
					"anterior" => 4243,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [163] = 
				(
					array(
					"cliente_id" => 408,
					"conexion_id" => 208,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1530,
					"anterior" => 1504,
					"basico" => 100,
					"excedente" => 80,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [164] = 
				(
					array(
					"cliente_id" => 409,
					"conexion_id" => 209,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5306,
					"anterior" => 5289,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [165] = 
				(
					array(
					"cliente_id" => 410,
					"conexion_id" => 210,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 8819,
					"anterior" => 8819,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [166] = 
				(
					array(
					"cliente_id" => 411,
					"conexion_id" => 211,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1949,
					"anterior" => 4947,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [167] = 
				(
					array(
					"cliente_id" => 412,
					"conexion_id" => 212,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4425,
					"anterior" => 4417,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [168] = 
				(
					array(
					"cliente_id" => 413,
					"conexion_id" => 213,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4021,
					"anterior" => 4005,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [169] = 
				(
					array(
					"cliente_id" => 414,
					"conexion_id" => 214,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4163,
					"anterior" => 4144,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [170] = 
				(
					array(
					"cliente_id" => 415,
					"conexion_id" => 215,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4281,
					"anterior" => 4253,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [171] = 
				(
					array(
					"cliente_id" => 416,
					"conexion_id" => 216,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2531,
					"anterior" => 2513,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [172] = 
				(
					array(
					"cliente_id" => 417,
					"conexion_id" => 217,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3456,
					"anterior" => 3446,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [173] = 
				(
					array(
					"cliente_id" => 418,
					"conexion_id" => 218,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3390,
					"anterior" => 3382,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [174] = 
				(
					array(
					"cliente_id" => 419,
					"conexion_id" => 219,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4360,
					"anterior" => 4347,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [175] = 
				(
					array(
					"cliente_id" => 420,
					"conexion_id" => 220,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2707,
					"anterior" => 2699,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [176] = 
				(
					array(
					"cliente_id" => 421,
					"conexion_id" => 221,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2147,
					"anterior" => 2142,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [177] = 
				(
					array(
					"cliente_id" => 422,
					"conexion_id" => 222,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2621,
					"anterior" => 2613,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [178] = 
				(
					array(
					"cliente_id" => 423,
					"conexion_id" => 223,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4318,
					"anterior" => 4314,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [179] = 
				(
					array(
					"cliente_id" => 424,
					"conexion_id" => 224,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4353,
					"anterior" => 4345,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [180] = 
				(
					array(
					"cliente_id" => 425,
					"conexion_id" => 225,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2684,
					"anterior" => 2677,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [181] = 
				(
					array(
					"cliente_id" => 733,
					"conexion_id" => 226,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5090,
					"anterior" => 5079,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [182] = 
				(
					array(
					"cliente_id" => 427,
					"conexion_id" => 227,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3526,
					"anterior" => 3518,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [183] = 
				(
					array(
					"cliente_id" => 428,
					"conexion_id" => 228,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5433,
					"anterior" => 5426,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [184] = 
				(
					array(
					"cliente_id" => 429,
					"conexion_id" => 229,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2307,
					"anterior" => 2299,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [185] = 
				(
					array(
					"cliente_id" => 430,
					"conexion_id" => 230,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3134,
					"anterior" => 3126,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [186] = 
				(
					array(
					"cliente_id" => 431,
					"conexion_id" => 231,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5491,
					"anterior" => 5482,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [187] = 
				(
					array(
					"cliente_id" => 760,
					"conexion_id" => 232,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4726,
					"anterior" => 4718,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [188] = 
				(
					array(
					"cliente_id" => 433,
					"conexion_id" => 233,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4537,
					"anterior" => 4522,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [189] = 
				(
					array(
					"cliente_id" => 434,
					"conexion_id" => 234,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1694,
					"anterior" => 1686,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [190] = 
				(
					array(
					"cliente_id" => 435,
					"conexion_id" => 235,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4864,
					"anterior" => 4850,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [191] = 
				(
					array(
					"cliente_id" => 436,
					"conexion_id" => 236,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1449,
					"anterior" => 1444,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [192] = 
				(
					array(
					"cliente_id" => 437,
					"conexion_id" => 237,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3721,
					"anterior" => 3711,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [193] = 
				(
					array(
					"cliente_id" => 438,
					"conexion_id" => 238,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4574,
					"anterior" => 4566,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [194] = 
				(
					array(
					"cliente_id" => 439,
					"conexion_id" => 239,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5480,
					"anterior" => 5466,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [195] = 
				(
					array(
					"cliente_id" => 440,
					"conexion_id" => 240,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5397,
					"anterior" => 5389,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [196] = 
				(
					array(
					"cliente_id" => 441,
					"conexion_id" => 241,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2322,
					"anterior" => 2319,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [197] = 
				(
					array(
					"cliente_id" => 442,
					"conexion_id" => 242,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4130,
					"anterior" => 4124,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [198] = 
				(
					array(
					"cliente_id" => 443,
					"conexion_id" => 243,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4981,
					"anterior" => 4960,
					"basico" => 100,
					"excedente" => 55,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [199] = 
				(
					array(
					"cliente_id" => 444,
					"conexion_id" => 244,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4341,
					"anterior" => 4335,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [200] = 
				(
					array(
					"cliente_id" => 445,
					"conexion_id" => 245,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5720,
					"anterior" => 5711,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [201] = 
				(
					array(
					"cliente_id" => 446,
					"conexion_id" => 246,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4401,
					"anterior" => 4389,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [202] = 
				(
					array(
					"cliente_id" => 447,
					"conexion_id" => 247,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 8587,
					"anterior" => 8579,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [203] = 
				(
					array(
					"cliente_id" => 448,
					"conexion_id" => 248,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4434,
					"anterior" => 4420,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [204] = 
				(
					array(
					"cliente_id" => 449,
					"conexion_id" => 249,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4204,
					"anterior" => 4192,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [205] = 
				(
					array(
					"cliente_id" => 450,
					"conexion_id" => 250,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4762,
					"anterior" => 4750,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [206] = 
				(
					array(
					"cliente_id" => 451,
					"conexion_id" => 251,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3924,
					"anterior" => 3912,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [207] = 
				(
					array(
					"cliente_id" => 452,
					"conexion_id" => 252,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6988,
					"anterior" => 6977,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [208] = 
				(
					array(
					"cliente_id" => 453,
					"conexion_id" => 253,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4974,
					"anterior" => 4860,
					"basico" => 100,
					"excedente" => 520,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [209] = 
				(
					array(
					"cliente_id" => 454,
					"conexion_id" => 254,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1988,
					"anterior" => 1980,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [210] = 
				(
					array(
					"cliente_id" => 455,
					"conexion_id" => 255,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5177,
					"anterior" => 5160,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [211] = 
				(
					array(
					"cliente_id" => 456,
					"conexion_id" => 256,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4886,
					"anterior" => 4877,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [212] = 
				(
					array(
					"cliente_id" => 457,
					"conexion_id" => 257,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6407,
					"anterior" => 6399,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [213] = 
				(
					array(
					"cliente_id" => 458,
					"conexion_id" => 258,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 111,
					"anterior" => 100,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [214] = 
				(
					array(
					"cliente_id" => 459,
					"conexion_id" => 259,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5034,
					"anterior" => 5034,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [215] = 
				(
					array(
					"cliente_id" => 460,
					"conexion_id" => 260,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 958,
					"anterior" => 950,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [216] = 
				(
					array(
					"cliente_id" => 461,
					"conexion_id" => 261,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5349,
					"anterior" => 5340,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [217] = 
				(
					array(
					"cliente_id" => 462,
					"conexion_id" => 262,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5377,
					"anterior" => 5372,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [218] = 
				(
					array(
					"cliente_id" => 463,
					"conexion_id" => 263,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5402,
					"anterior" => 5386,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [219] = 
				(
					array(
					"cliente_id" => 464,
					"conexion_id" => 264,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4770,
					"anterior" => 4752,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [220] = 
				(
					array(
					"cliente_id" => 465,
					"conexion_id" => 265,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5800,
					"anterior" => 5781,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [221] = 
				(
					array(
					"cliente_id" => 466,
					"conexion_id" => 266,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3021,
					"anterior" => 3010,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [222] = 
				(
					array(
					"cliente_id" => 467,
					"conexion_id" => 267,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3756,
					"anterior" => 3746,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [223] = 
				(
					array(
					"cliente_id" => 468,
					"conexion_id" => 268,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2561,
					"anterior" => 2547,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [224] = 
				(
					array(
					"cliente_id" => 469,
					"conexion_id" => 269,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5431,
					"anterior" => 5422,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [225] = 
				(
					array(
					"cliente_id" => 470,
					"conexion_id" => 270,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3770,
					"anterior" => 3758,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [226] = 
				(
					array(
					"cliente_id" => 471,
					"conexion_id" => 271,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5164,
					"anterior" => 5157,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [227] = 
				(
					array(
					"cliente_id" => 472,
					"conexion_id" => 272,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5722,
					"anterior" => 5710,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [228] = 
				(
					array(
					"cliente_id" => 473,
					"conexion_id" => 273,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5446,
					"anterior" => 5432,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [229] = 
				(
					array(
					"cliente_id" => 474,
					"conexion_id" => 274,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4068,
					"anterior" => 4063,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [230] = 
				(
					array(
					"cliente_id" => 475,
					"conexion_id" => 275,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5004,
					"anterior" => 5004,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [231] = 
				(
					array(
					"cliente_id" => 476,
					"conexion_id" => 276,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1179,
					"anterior" => 1171,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [232] = 
				(
					array(
					"cliente_id" => 477,
					"conexion_id" => 277,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3086,
					"anterior" => 3082,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [233] = 
				(
					array(
					"cliente_id" => 478,
					"conexion_id" => 278,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3730,
					"anterior" => 3721,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [234] = 
				(
					array(
					"cliente_id" => 479,
					"conexion_id" => 279,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5559,
					"anterior" => 5548,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [235] = 
				(
					array(
					"cliente_id" => 480,
					"conexion_id" => 280,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3,
					"anterior" => 2,
					"basico" => 220,
					"excedente" => 0,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [236] = 
				(
					array(
					"cliente_id" => 134,
					"conexion_id" => 281,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5450,
					"anterior" => 5446,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [237] = 
				(
					array(
					"cliente_id" => 499,
					"conexion_id" => 283,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3370,
					"anterior" => 3350,
					"basico" => 100,
					"excedente" => 50,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [238] = 
				(
					array(
					"cliente_id" => 498,
					"conexion_id" => 284,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1469,
					"anterior" => 1391,
					"basico" => 220,
					"excedente" => 504,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [239] = 
				(
					array(
					"cliente_id" => 607,
					"conexion_id" => 285,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7315,
					"anterior" => 7315,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [240] = 
				(
					array(
					"cliente_id" => 624,
					"conexion_id" => 287,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3205,
					"anterior" => 3144,
					"basico" => 100,
					"excedente" => 255,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [241] = 
				(
					array(
					"cliente_id" => 611,
					"conexion_id" => 288,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [242] = 
				(
					array(
					"cliente_id" => 608,
					"conexion_id" => 289,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3367,
					"anterior" => 3352,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [243] = 
				(
					array(
					"cliente_id" => 614,
					"conexion_id" => 290,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1072,
					"anterior" => 1029,
					"basico" => 100,
					"excedente" => 165,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [244] = 
				(
					array(
					"cliente_id" => 618,
					"conexion_id" => 291,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1184,
					"anterior" => 1171,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [245] = 
				(
					array(
					"cliente_id" => 619,
					"conexion_id" => 292,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4255,
					"anterior" => 4223,
					"basico" => 100,
					"excedente" => 110,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [246] = 
				(
					array(
					"cliente_id" => 620,
					"conexion_id" => 293,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1317,
					"anterior" => 1317,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [247] = 
				(
					array(
					"cliente_id" => 184,
					"conexion_id" => 294,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5088,
					"anterior" => 5070,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [248] = 
				(
					array(
					"cliente_id" => 621,
					"conexion_id" => 295,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4745,
					"anterior" => 4704,
					"basico" => 100,
					"excedente" => 155,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [249] = 
				(
					array(
					"cliente_id" => 767,
					"conexion_id" => 296,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2361,
					"anterior" => 2361,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [250] = 
				(
					array(
					"cliente_id" => 607,
					"conexion_id" => 297,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6586,
					"anterior" => 6586,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [251] = 
				(
					array(
					"cliente_id" => 625,
					"conexion_id" => 298,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4043,
					"anterior" => 4043,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [252] = 
				(
					array(
					"cliente_id" => 62,
					"conexion_id" => 299,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 0,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [253] = 
				(
					array(
					"cliente_id" => 319,
					"conexion_id" => 301,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6161,
					"anterior" => 6146,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [254] = 
				(
					array(
					"cliente_id" => 272,
					"conexion_id" => 302,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2955,
					"anterior" => 2943,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [255] = 
				(
					array(
					"cliente_id" => 206,
					"conexion_id" => 303,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2835,
					"anterior" => 2817,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [256] = 
				(
					array(
					"cliente_id" => 724,
					"conexion_id" => 304,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2566,
					"anterior" => 2508,
					"basico" => 100,
					"excedente" => 240,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [257] = 
				(
					array(
					"cliente_id" => 207,
					"conexion_id" => 305,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3911,
					"anterior" => 3885,
					"basico" => 100,
					"excedente" => 80,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [258] = 
				(
					array(
					"cliente_id" => 234,
					"conexion_id" => 306,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6765,
					"anterior" => 6726,
					"basico" => 100,
					"excedente" => 145,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [259] = 
				(
					array(
					"cliente_id" => 253,
					"conexion_id" => 307,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4693,
					"anterior" => 4662,
					"basico" => 100,
					"excedente" => 105,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [260] = 
				(
					array(
					"cliente_id" => 295,
					"conexion_id" => 308,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6596,
					"anterior" => 6566,
					"basico" => 100,
					"excedente" => 100,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [261] = 
				(
					array(
					"cliente_id" => 318,
					"conexion_id" => 309,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5523,
					"anterior" => 5494,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [262] = 
				(
					array(
					"cliente_id" => 226,
					"conexion_id" => 310,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4110,
					"anterior" => 4077,
					"basico" => 100,
					"excedente" => 115,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [263] = 
				(
					array(
					"cliente_id" => 309,
					"conexion_id" => 311,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3738,
					"anterior" => 3795,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [264] = 
				(
					array(
					"cliente_id" => 306,
					"conexion_id" => 312,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7106,
					"anterior" => 7055,
					"basico" => 100,
					"excedente" => 205,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [265] = 
				(
					array(
					"cliente_id" => 245,
					"conexion_id" => 313,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3402,
					"anterior" => 3390,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [266] = 
				(
					array(
					"cliente_id" => 369,
					"conexion_id" => 314,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6952,
					"anterior" => 6900,
					"basico" => 100,
					"excedente" => 210,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [267] = 
				(
					array(
					"cliente_id" => 488,
					"conexion_id" => 315,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4308,
					"anterior" => 4270,
					"basico" => 100,
					"excedente" => 140,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [268] = 
				(
					array(
					"cliente_id" => 481,
					"conexion_id" => 316,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6576,
					"anterior" => 6517,
					"basico" => 100,
					"excedente" => 245,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [269] = 
				(
					array(
					"cliente_id" => 273,
					"conexion_id" => 317,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4461,
					"anterior" => 4436,
					"basico" => 100,
					"excedente" => 75,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [270] = 
				(
					array(
					"cliente_id" => 320,
					"conexion_id" => 318,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6910,
					"anterior" => 6825,
					"basico" => 100,
					"excedente" => 375,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [271] = 
				(
					array(
					"cliente_id" => 274,
					"conexion_id" => 319,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2889,
					"anterior" => 2874,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [272] = 
				(
					array(
					"cliente_id" => 298,
					"conexion_id" => 320,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2633,
					"anterior" => 2621,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [273] = 
				(
					array(
					"cliente_id" => 204,
					"conexion_id" => 321,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4161,
					"anterior" => 4139,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [274] = 
				(
					array(
					"cliente_id" => 492,
					"conexion_id" => 322,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3216,
					"anterior" => 3180,
					"basico" => 100,
					"excedente" => 130,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [275] = 
				(
					array(
					"cliente_id" => 294,
					"conexion_id" => 323,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3240,
					"anterior" => 3176,
					"basico" => 100,
					"excedente" => 270,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [276] = 
				(
					array(
					"cliente_id" => 255,
					"conexion_id" => 324,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4733,
					"anterior" => 4716,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [277] = 
				(
					array(
					"cliente_id" => 214,
					"conexion_id" => 325,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6339,
					"anterior" => 6254,
					"basico" => 100,
					"excedente" => 375,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [278] = 
				(
					array(
					"cliente_id" => 322,
					"conexion_id" => 326,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4063,
					"anterior" => 3884,
					"basico" => 100,
					"excedente" => 845,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [279] = 
				(
					array(
					"cliente_id" => 288,
					"conexion_id" => 327,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2732,
					"anterior" => 2722,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [280] = 
				(
					array(
					"cliente_id" => 275,
					"conexion_id" => 328,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2984,
					"anterior" => 2971,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [281] = 
				(
					array(
					"cliente_id" => 243,
					"conexion_id" => 329,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2310,
					"anterior" => 2247,
					"basico" => 100,
					"excedente" => 265,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [282] = 
				(
					array(
					"cliente_id" => 239,
					"conexion_id" => 330,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1357,
					"anterior" => 1346,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [283] = 
				(
					array(
					"cliente_id" => 260,
					"conexion_id" => 331,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3118,
					"anterior" => 3084,
					"basico" => 100,
					"excedente" => 120,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [284] = 
				(
					array(
					"cliente_id" => 375,
					"conexion_id" => 332,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1558,
					"anterior" => 1552,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [285] = 
				(
					array(
					"cliente_id" => 321,
					"conexion_id" => 333,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2690,
					"anterior" => 2678,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [286] = 
				(
					array(
					"cliente_id" => 262,
					"conexion_id" => 334,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3039,
					"anterior" => 3020,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [287] = 
				(
					array(
					"cliente_id" => 723,
					"conexion_id" => 335,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2676,
					"anterior" => 2676,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [288] = 
				(
					array(
					"cliente_id" => 203,
					"conexion_id" => 336,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3544,
					"anterior" => 3439,
					"basico" => 100,
					"excedente" => 475,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [289] = 
				(
					array(
					"cliente_id" => 251,
					"conexion_id" => 337,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3067,
					"anterior" => 3039,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [290] = 
				(
					array(
					"cliente_id" => 290,
					"conexion_id" => 338,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3159,
					"anterior" => 3144,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [291] = 
				(
					array(
					"cliente_id" => 201,
					"conexion_id" => 339,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2989,
					"anterior" => 2975,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [292] = 
				(
					array(
					"cliente_id" => 725,
					"conexion_id" => 340,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4364,
					"anterior" => 4292,
					"basico" => 100,
					"excedente" => 310,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [293] = 
				(
					array(
					"cliente_id" => 218,
					"conexion_id" => 341,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4919,
					"anterior" => 4871,
					"basico" => 100,
					"excedente" => 190,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [294] = 
				(
					array(
					"cliente_id" => 311,
					"conexion_id" => 342,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2466,
					"anterior" => 2413,
					"basico" => 100,
					"excedente" => 215,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [295] = 
				(
					array(
					"cliente_id" => 256,
					"conexion_id" => 343,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5445,
					"anterior" => 5398,
					"basico" => 100,
					"excedente" => 185,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [296] = 
				(
					array(
					"cliente_id" => 265,
					"conexion_id" => 344,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4945,
					"anterior" => 4892,
					"basico" => 100,
					"excedente" => 215,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [297] = 
				(
					array(
					"cliente_id" => 282,
					"conexion_id" => 345,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5617,
					"anterior" => 5564,
					"basico" => 100,
					"excedente" => 215,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [298] = 
				(
					array(
					"cliente_id" => 484,
					"conexion_id" => 346,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3320,
					"anterior" => 3260,
					"basico" => 100,
					"excedente" => 250,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [299] = 
				(
					array(
					"cliente_id" => 248,
					"conexion_id" => 347,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2889,
					"anterior" => 2881,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [300] = 
				(
					array(
					"cliente_id" => 307,
					"conexion_id" => 348,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2642,
					"anterior" => 2572,
					"basico" => 100,
					"excedente" => 300,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [301] = 
				(
					array(
					"cliente_id" => 211,
					"conexion_id" => 349,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3197,
					"anterior" => 3172,
					"basico" => 100,
					"excedente" => 75,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [302] = 
				(
					array(
					"cliente_id" => 312,
					"conexion_id" => 350,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3692,
					"anterior" => 3663,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [303] = 
				(
					array(
					"cliente_id" => 496,
					"conexion_id" => 351,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4764,
					"anterior" => 4748,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [304] = 
				(
					array(
					"cliente_id" => 482,
					"conexion_id" => 352,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1344,
					"anterior" => 1282,
					"basico" => 100,
					"excedente" => 260,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [305] = 
				(
					array(
					"cliente_id" => 329,
					"conexion_id" => 353,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4337,
					"anterior" => 4305,
					"basico" => 100,
					"excedente" => 110,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [306] = 
				(
					array(
					"cliente_id" => 293,
					"conexion_id" => 354,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3714,
					"anterior" => 3666,
					"basico" => 100,
					"excedente" => 190,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [307] = 
				(
					array(
					"cliente_id" => 489,
					"conexion_id" => 355,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6164,
					"anterior" => 6128,
					"basico" => 100,
					"excedente" => 130,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [308] = 
				(
					array(
					"cliente_id" => 238,
					"conexion_id" => 356,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2956,
					"anterior" => 2931,
					"basico" => 100,
					"excedente" => 75,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [309] = 
				(
					array(
					"cliente_id" => 304,
					"conexion_id" => 357,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2272,
					"anterior" => 2227,
					"basico" => 100,
					"excedente" => 175,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [310] = 
				(
					array(
					"cliente_id" => 281,
					"conexion_id" => 358,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3587,
					"anterior" => 3560,
					"basico" => 100,
					"excedente" => 85,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [311] = 
				(
					array(
					"cliente_id" => 314,
					"conexion_id" => 359,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3405,
					"anterior" => 3370,
					"basico" => 100,
					"excedente" => 125,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [312] = 
				(
					array(
					"cliente_id" => 230,
					"conexion_id" => 360,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2588,
					"anterior" => 2548,
					"basico" => 100,
					"excedente" => 150,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [313] = 
				(
					array(
					"cliente_id" => 212,
					"conexion_id" => 361,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3154,
					"anterior" => 3138,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [314] = 
				(
					array(
					"cliente_id" => 483,
					"conexion_id" => 362,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3836,
					"anterior" => 3826,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [315] = 
				(
					array(
					"cliente_id" => 649,
					"conexion_id" => 363,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2969,
					"anterior" => 2928,
					"basico" => 100,
					"excedente" => 155,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [316] = 
				(
					array(
					"cliente_id" => 744,
					"conexion_id" => 364,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4383,
					"anterior" => 4332,
					"basico" => 100,
					"excedente" => 205,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [317] = 
				(
					array(
					"cliente_id" => 240,
					"conexion_id" => 365,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4222,
					"anterior" => 4194,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [318] = 
				(
					array(
					"cliente_id" => 247,
					"conexion_id" => 366,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1611,
					"anterior" => 1583,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [319] = 
				(
					array(
					"cliente_id" => 237,
					"conexion_id" => 367,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5266,
					"anterior" => 5198,
					"basico" => 100,
					"excedente" => 290,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [320] = 
				(
					array(
					"cliente_id" => 242,
					"conexion_id" => 368,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2937,
					"anterior" => 2912,
					"basico" => 100,
					"excedente" => 75,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [321] = 
				(
					array(
					"cliente_id" => 215,
					"conexion_id" => 369,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4695,
					"anterior" => 4620,
					"basico" => 100,
					"excedente" => 325,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [322] = 
				(
					array(
					"cliente_id" => 300,
					"conexion_id" => 370,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6225,
					"anterior" => 6212,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [323] = 
				(
					array(
					"cliente_id" => 280,
					"conexion_id" => 371,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2822,
					"anterior" => 2804,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [324] = 
				(
					array(
					"cliente_id" => 249,
					"conexion_id" => 372,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2694,
					"anterior" => 2692,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [325] = 
				(
					array(
					"cliente_id" => 236,
					"conexion_id" => 373,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3175,
					"anterior" => 3142,
					"basico" => 100,
					"excedente" => 115,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [326] = 
				(
					array(
					"cliente_id" => 276,
					"conexion_id" => 374,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3225,
					"anterior" => 3213,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [327] = 
				(
					array(
					"cliente_id" => 494,
					"conexion_id" => 375,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4720,
					"anterior" => 4660,
					"basico" => 100,
					"excedente" => 250,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [328] = 
				(
					array(
					"cliente_id" => 316,
					"conexion_id" => 376,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4347,
					"anterior" => 4290,
					"basico" => 100,
					"excedente" => 235,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [329] = 
				(
					array(
					"cliente_id" => 250,
					"conexion_id" => 377,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3849,
					"anterior" => 3812,
					"basico" => 100,
					"excedente" => 135,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [330] = 
				(
					array(
					"cliente_id" => 261,
					"conexion_id" => 378,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4363,
					"anterior" => 4343,
					"basico" => 100,
					"excedente" => 50,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [331] = 
				(
					array(
					"cliente_id" => 292,
					"conexion_id" => 379,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2153,
					"anterior" => 2106,
					"basico" => 100,
					"excedente" => 185,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [332] = 
				(
					array(
					"cliente_id" => 269,
					"conexion_id" => 380,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1714,
					"anterior" => 1708,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [333] = 
				(
					array(
					"cliente_id" => 208,
					"conexion_id" => 381,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3325,
					"anterior" => 3311,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [334] = 
				(
					array(
					"cliente_id" => 493,
					"conexion_id" => 382,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2299,
					"anterior" => 2278,
					"basico" => 100,
					"excedente" => 55,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [335] = 
				(
					array(
					"cliente_id" => 291,
					"conexion_id" => 383,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4466,
					"anterior" => 4437,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [336] = 
				(
					array(
					"cliente_id" => 491,
					"conexion_id" => 384,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4128,
					"anterior" => 4190,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [337] = 
				(
					array(
					"cliente_id" => 305,
					"conexion_id" => 385,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1827,
					"anterior" => 1815,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [338] = 
				(
					array(
					"cliente_id" => 227,
					"conexion_id" => 386,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4488,
					"anterior" => 4456,
					"basico" => 100,
					"excedente" => 110,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [339] = 
				(
					array(
					"cliente_id" => 220,
					"conexion_id" => 387,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2360,
					"anterior" => 2351,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [340] = 
				(
					array(
					"cliente_id" => 302,
					"conexion_id" => 388,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4508,
					"anterior" => 4471,
					"basico" => 100,
					"excedente" => 135,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [341] = 
				(
					array(
					"cliente_id" => 485,
					"conexion_id" => 389,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2559,
					"anterior" => 2542,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [342] = 
				(
					array(
					"cliente_id" => 222,
					"conexion_id" => 390,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4116,
					"anterior" => 3965,
					"basico" => 100,
					"excedente" => 705,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [343] = 
				(
					array(
					"cliente_id" => 327,
					"conexion_id" => 391,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1667,
					"anterior" => 1665,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [344] = 
				(
					array(
					"cliente_id" => 271,
					"conexion_id" => 392,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3688,
					"anterior" => 3647,
					"basico" => 100,
					"excedente" => 155,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [345] = 
				(
					array(
					"cliente_id" => 267,
					"conexion_id" => 393,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2839,
					"anterior" => 2839,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [346] = 
				(
					array(
					"cliente_id" => 224,
					"conexion_id" => 394,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2362,
					"anterior" => 2315,
					"basico" => 100,
					"excedente" => 185,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [347] = 
				(
					array(
					"cliente_id" => 310,
					"conexion_id" => 395,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6268,
					"anterior" => 6219,
					"basico" => 100,
					"excedente" => 195,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [348] = 
				(
					array(
					"cliente_id" => 323,
					"conexion_id" => 396,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 8134,
					"anterior" => 8085,
					"basico" => 100,
					"excedente" => 195,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [349] = 
				(
					array(
					"cliente_id" => 490,
					"conexion_id" => 397,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 138,
					"anterior" => 114,
					"basico" => 100,
					"excedente" => 70,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [350] = 
				(
					array(
					"cliente_id" => 297,
					"conexion_id" => 398,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2781,
					"anterior" => 2768,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [351] = 
				(
					array(
					"cliente_id" => 264,
					"conexion_id" => 399,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6523,
					"anterior" => 6453,
					"basico" => 100,
					"excedente" => 300,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [352] = 
				(
					array(
					"cliente_id" => 268,
					"conexion_id" => 400,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2324,
					"anterior" => 2302,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [353] = 
				(
					array(
					"cliente_id" => 233,
					"conexion_id" => 401,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3443,
					"anterior" => 3438,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [354] = 
				(
					array(
					"cliente_id" => 317,
					"conexion_id" => 402,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3037,
					"anterior" => 3015,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [355] = 
				(
					array(
					"cliente_id" => 263,
					"conexion_id" => 403,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2399,
					"anterior" => 2375,
					"basico" => 100,
					"excedente" => 70,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [356] = 
				(
					array(
					"cliente_id" => 202,
					"conexion_id" => 404,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3608,
					"anterior" => 3592,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [357] = 
				(
					array(
					"cliente_id" => 257,
					"conexion_id" => 405,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2409,
					"anterior" => 2382,
					"basico" => 100,
					"excedente" => 85,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [358] = 
				(
					array(
					"cliente_id" => 324,
					"conexion_id" => 406,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4504,
					"anterior" => 4475,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [359] = 
				(
					array(
					"cliente_id" => 266,
					"conexion_id" => 407,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3820,
					"anterior" => 3805,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [360] = 
				(
					array(
					"cliente_id" => 229,
					"conexion_id" => 408,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2410,
					"anterior" => 2380,
					"basico" => 100,
					"excedente" => 100,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [361] = 
				(
					array(
					"cliente_id" => 330,
					"conexion_id" => 409,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3185,
					"anterior" => 3167,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [362] = 
				(
					array(
					"cliente_id" => 205,
					"conexion_id" => 410,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3642,
					"anterior" => 3589,
					"basico" => 100,
					"excedente" => 215,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [363] = 
				(
					array(
					"cliente_id" => 717,
					"conexion_id" => 411,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2593,
					"anterior" => 2563,
					"basico" => 100,
					"excedente" => 100,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [364] = 
				(
					array(
					"cliente_id" => 315,
					"conexion_id" => 412,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2142,
					"anterior" => 2119,
					"basico" => 100,
					"excedente" => 65,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [365] = 
				(
					array(
					"cliente_id" => 296,
					"conexion_id" => 413,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4177,
					"anterior" => 4152,
					"basico" => 100,
					"excedente" => 75,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [366] = 
				(
					array(
					"cliente_id" => 615,
					"conexion_id" => 415,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3949,
					"anterior" => 3920,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [367] = 
				(
					array(
					"cliente_id" => 626,
					"conexion_id" => 416,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5431,
					"anterior" => 4488,
					"basico" => 100,
					"excedente" => 4665,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [368] = 
				(
					array(
					"cliente_id" => 310,
					"conexion_id" => 417,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1219,
					"anterior" => 1208,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [369] = 
				(
					array(
					"cliente_id" => 631,
					"conexion_id" => 420,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2563,
					"anterior" => 2550,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [370] = 
				(
					array(
					"cliente_id" => 632,
					"conexion_id" => 421,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1984,
					"anterior" => 1965,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [371] = 
				(
					array(
					"cliente_id" => 635,
					"conexion_id" => 422,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3121,
					"anterior" => 3084,
					"basico" => 100,
					"excedente" => 135,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [372] = 
				(
					array(
					"cliente_id" => 634,
					"conexion_id" => 423,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2255,
					"anterior" => 2232,
					"basico" => 100,
					"excedente" => 65,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [373] = 
				(
					array(
					"cliente_id" => 633,
					"conexion_id" => 424,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2361,
					"anterior" => 2328,
					"basico" => 100,
					"excedente" => 115,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [374] = 
				(
					array(
					"cliente_id" => 636,
					"conexion_id" => 425,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2424,
					"anterior" => 2394,
					"basico" => 100,
					"excedente" => 100,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [375] = 
				(
					array(
					"cliente_id" => 637,
					"conexion_id" => 426,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1268,
					"anterior" => 1246,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [376] = 
				(
					array(
					"cliente_id" => 638,
					"conexion_id" => 427,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1720,
					"anterior" => 1696,
					"basico" => 100,
					"excedente" => 70,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [377] = 
				(
					array(
					"cliente_id" => 639,
					"conexion_id" => 428,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1734,
					"anterior" => 1719,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [378] = 
				(
					array(
					"cliente_id" => 640,
					"conexion_id" => 429,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3377,
					"anterior" => 3345,
					"basico" => 100,
					"excedente" => 110,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [379] = 
				(
					array(
					"cliente_id" => 641,
					"conexion_id" => 430,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2023,
					"anterior" => 2009,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [380] = 
				(
					array(
					"cliente_id" => 642,
					"conexion_id" => 431,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2100,
					"anterior" => 2075,
					"basico" => 100,
					"excedente" => 75,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [381] = 
				(
					array(
					"cliente_id" => 643,
					"conexion_id" => 432,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2822,
					"anterior" => 2801,
					"basico" => 100,
					"excedente" => 55,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [382] = 
				(
					array(
					"cliente_id" => 644,
					"conexion_id" => 433,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1428,
					"anterior" => 1419,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [383] = 
				(
					array(
					"cliente_id" => 645,
					"conexion_id" => 434,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2197,
					"anterior" => 2187,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [384] = 
				(
					array(
					"cliente_id" => 646,
					"conexion_id" => 435,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2244,
					"anterior" => 2218,
					"basico" => 100,
					"excedente" => 80,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [385] = 
				(
					array(
					"cliente_id" => 647,
					"conexion_id" => 436,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1830,
					"anterior" => 1818,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [386] = 
				(
					array(
					"cliente_id" => 648,
					"conexion_id" => 437,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1791,
					"anterior" => 1763,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [387] = 
				(
					array(
					"cliente_id" => 704,
					"conexion_id" => 438,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2399,
					"anterior" => 2354,
					"basico" => 100,
					"excedente" => 175,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [388] = 
				(
					array(
					"cliente_id" => 705,
					"conexion_id" => 439,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 973,
					"anterior" => 972,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [389] = 
				(
					array(
					"cliente_id" => 706,
					"conexion_id" => 440,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1567,
					"anterior" => 1530,
					"basico" => 100,
					"excedente" => 135,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [390] = 
				(
					array(
					"cliente_id" => 700,
					"conexion_id" => 441,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1430,
					"anterior" => 1382,
					"basico" => 100,
					"excedente" => 190,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [391] = 
				(
					array(
					"cliente_id" => 707,
					"conexion_id" => 442,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6053,
					"anterior" => 5938,
					"basico" => 100,
					"excedente" => 525,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [392] = 
				(
					array(
					"cliente_id" => 708,
					"conexion_id" => 443,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1546,
					"anterior" => 1523,
					"basico" => 100,
					"excedente" => 65,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [393] = 
				(
					array(
					"cliente_id" => 709,
					"conexion_id" => 444,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 694,
					"anterior" => 678,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [394] = 
				(
					array(
					"cliente_id" => 710,
					"conexion_id" => 445,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3063,
					"anterior" => 3045,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [395] = 
				(
					array(
					"cliente_id" => 711,
					"conexion_id" => 446,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2815,
					"anterior" => 2781,
					"basico" => 100,
					"excedente" => 120,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [396] = 
				(
					array(
					"cliente_id" => 712,
					"conexion_id" => 447,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1105,
					"anterior" => 1091,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [397] = 
				(
					array(
					"cliente_id" => 713,
					"conexion_id" => 448,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1233,
					"anterior" => 1217,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [398] = 
				(
					array(
					"cliente_id" => 697,
					"conexion_id" => 449,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3216,
					"anterior" => 3169,
					"basico" => 100,
					"excedente" => 185,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [399] = 
				(
					array(
					"cliente_id" => 698,
					"conexion_id" => 450,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1836,
					"anterior" => 1817,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [400] = 
				(
					array(
					"cliente_id" => 699,
					"conexion_id" => 451,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2627,
					"anterior" => 2596,
					"basico" => 100,
					"excedente" => 105,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [401] = 
				(
					array(
					"cliente_id" => 663,
					"conexion_id" => 452,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3888,
					"anterior" => 3870,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [402] = 
				(
					array(
					"cliente_id" => 664,
					"conexion_id" => 453,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2605,
					"anterior" => 2573,
					"basico" => 100,
					"excedente" => 110,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [403] = 
				(
					array(
					"cliente_id" => 665,
					"conexion_id" => 454,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1887,
					"anterior" => 1848,
					"basico" => 100,
					"excedente" => 145,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [404] = 
				(
					array(
					"cliente_id" => 666,
					"conexion_id" => 455,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 667,
					"anterior" => 657,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [405] = 
				(
					array(
					"cliente_id" => 667,
					"conexion_id" => 456,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2686,
					"anterior" => 2677,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [406] = 
				(
					array(
					"cliente_id" => 668,
					"conexion_id" => 457,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1703,
					"anterior" => 1672,
					"basico" => 100,
					"excedente" => 105,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [407] = 
				(
					array(
					"cliente_id" => 669,
					"conexion_id" => 458,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2126,
					"anterior" => 2101,
					"basico" => 100,
					"excedente" => 75,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [408] = 
				(
					array(
					"cliente_id" => 670,
					"conexion_id" => 459,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1941,
					"anterior" => 1924,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [409] = 
				(
					array(
					"cliente_id" => 671,
					"conexion_id" => 460,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2784,
					"anterior" => 2781,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [410] = 
				(
					array(
					"cliente_id" => 672,
					"conexion_id" => 461,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1347,
					"anterior" => 1298,
					"basico" => 100,
					"excedente" => 195,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [411] = 
				(
					array(
					"cliente_id" => 673,
					"conexion_id" => 462,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2068,
					"anterior" => 2054,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [412] = 
				(
					array(
					"cliente_id" => 674,
					"conexion_id" => 463,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1030,
					"anterior" => 1024,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [413] = 
				(
					array(
					"cliente_id" => 675,
					"conexion_id" => 464,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1083,
					"anterior" => 1067,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [414] = 
				(
					array(
					"cliente_id" => 676,
					"conexion_id" => 465,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 824,
					"anterior" => 776,
					"basico" => 100,
					"excedente" => 190,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [415] = 
				(
					array(
					"cliente_id" => 677,
					"conexion_id" => 466,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2187,
					"anterior" => 2161,
					"basico" => 100,
					"excedente" => 80,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [416] = 
				(
					array(
					"cliente_id" => 678,
					"conexion_id" => 467,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1526,
					"anterior" => 1514,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [417] = 
				(
					array(
					"cliente_id" => 679,
					"conexion_id" => 468,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1688,
					"anterior" => 1659,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [418] = 
				(
					array(
					"cliente_id" => 680,
					"conexion_id" => 469,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3600,
					"anterior" => 3562,
					"basico" => 100,
					"excedente" => 140,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [419] = 
				(
					array(
					"cliente_id" => 681,
					"conexion_id" => 470,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2609,
					"anterior" => 2555,
					"basico" => 100,
					"excedente" => 220,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [420] = 
				(
					array(
					"cliente_id" => 682,
					"conexion_id" => 471,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2050,
					"anterior" => 2019,
					"basico" => 100,
					"excedente" => 105,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [421] = 
				(
					array(
					"cliente_id" => 683,
					"conexion_id" => 472,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1134,
					"anterior" => 1126,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [422] = 
				(
					array(
					"cliente_id" => 693,
					"conexion_id" => 476,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2278,
					"anterior" => 2263,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [423] = 
				(
					array(
					"cliente_id" => 694,
					"conexion_id" => 477,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1208,
					"anterior" => 1190,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [424] = 
				(
					array(
					"cliente_id" => 651,
					"conexion_id" => 478,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3087,
					"anterior" => 3074,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [425] = 
				(
					array(
					"cliente_id" => 652,
					"conexion_id" => 479,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 298,
					"anterior" => 284,
					"basico" => 220,
					"excedente" => 0,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [426] = 
				(
					array(
					"cliente_id" => 1046,
					"conexion_id" => 480,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 907,
					"anterior" => 906,
					"basico" => 220,
					"excedente" => 0,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [427] = 
				(
					array(
					"cliente_id" => 703,
					"conexion_id" => 481,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 317,
					"anterior" => 317,
					"basico" => 220,
					"excedente" => 0,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [428] = 
				(
					array(
					"cliente_id" => 735,
					"conexion_id" => 482,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1854,
					"anterior" => 1840,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [429] = 
				(
					array(
					"cliente_id" => 736,
					"conexion_id" => 483,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1626,
					"anterior" => 1620,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [430] = 
				(
					array(
					"cliente_id" => 737,
					"conexion_id" => 484,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1641,
					"anterior" => 1625,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [431] = 
				(
					array(
					"cliente_id" => 738,
					"conexion_id" => 485,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2263,
					"anterior" => 2249,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [432] = 
				(
					array(
					"cliente_id" => 174,
					"conexion_id" => 486,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 633,
					"anterior" => 633,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [433] = 
				(
					array(
					"cliente_id" => 741,
					"conexion_id" => 487,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4713,
					"anterior" => 4703,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [434] = 
				(
					array(
					"cliente_id" => 766,
					"conexion_id" => 489,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2147,
					"anterior" => 2137,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [435] = 
				(
					array(
					"cliente_id" => 756,
					"conexion_id" => 490,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1933,
					"anterior" => 1896,
					"basico" => 100,
					"excedente" => 135,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [436] = 
				(
					array(
					"cliente_id" => 757,
					"conexion_id" => 491,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1102,
					"anterior" => 1088,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [437] = 
				(
					array(
					"cliente_id" => 758,
					"conexion_id" => 493,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 80,
					"anterior" => 80,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [438] = 
				(
					array(
					"cliente_id" => 759,
					"conexion_id" => 494,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1776,
					"anterior" => 1762,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [439] = 
				(
					array(
					"cliente_id" => 500,
					"conexion_id" => 500,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2820,
					"anterior" => 2816,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [440] = 
				(
					array(
					"cliente_id" => 501,
					"conexion_id" => 501,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2924,
					"anterior" => 2906,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [441] = 
				(
					array(
					"cliente_id" => 502,
					"conexion_id" => 502,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2270,
					"anterior" => 2253,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [442] = 
				(
					array(
					"cliente_id" => 503,
					"conexion_id" => 503,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 597,
					"anterior" => 582,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [443] = 
				(
					array(
					"cliente_id" => 504,
					"conexion_id" => 504,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2259,
					"anterior" => 2247,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [444] = 
				(
					array(
					"cliente_id" => 505,
					"conexion_id" => 505,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 178,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [445] = 
				(
					array(
					"cliente_id" => 506,
					"conexion_id" => 506,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2641,
					"anterior" => 2630,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [446] = 
				(
					array(
					"cliente_id" => 507,
					"conexion_id" => 507,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2301,
					"anterior" => 2290,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [447] = 
				(
					array(
					"cliente_id" => 508,
					"conexion_id" => 508,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4284,
					"anterior" => 4280,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [448] = 
				(
					array(
					"cliente_id" => 509,
					"conexion_id" => 509,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 131,
					"anterior" => 125,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [449] = 
				(
					array(
					"cliente_id" => 510,
					"conexion_id" => 510,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4726,
					"anterior" => 4719,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [450] = 
				(
					array(
					"cliente_id" => 511,
					"conexion_id" => 511,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3528,
					"anterior" => 3500,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [451] = 
				(
					array(
					"cliente_id" => 512,
					"conexion_id" => 512,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1436,
					"anterior" => 1436,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [452] = 
				(
					array(
					"cliente_id" => 513,
					"conexion_id" => 513,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3899,
					"anterior" => 3899,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [453] = 
				(
					array(
					"cliente_id" => 514,
					"conexion_id" => 514,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 618,
					"anterior" => 618,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [454] = 
				(
					array(
					"cliente_id" => 515,
					"conexion_id" => 515,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 404,
					"anterior" => 388,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [455] = 
				(
					array(
					"cliente_id" => 720,
					"conexion_id" => 516,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6593,
					"anterior" => 6581,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [456] = 
				(
					array(
					"cliente_id" => 517,
					"conexion_id" => 517,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5158,
					"anterior" => 5141,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [457] = 
				(
					array(
					"cliente_id" => 518,
					"conexion_id" => 518,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 153,
					"anterior" => 153,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [458] = 
				(
					array(
					"cliente_id" => 519,
					"conexion_id" => 519,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4730,
					"anterior" => 4719,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [459] = 
				(
					array(
					"cliente_id" => 739,
					"conexion_id" => 520,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2217,
					"anterior" => 2192,
					"basico" => 100,
					"excedente" => 75,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [460] = 
				(
					array(
					"cliente_id" => 687,
					"conexion_id" => 521,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 538,
					"anterior" => 526,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [461] = 
				(
					array(
					"cliente_id" => 522,
					"conexion_id" => 522,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4176,
					"anterior" => 4165,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [462] = 
				(
					array(
					"cliente_id" => 523,
					"conexion_id" => 523,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2322,
					"anterior" => 2307,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [463] = 
				(
					array(
					"cliente_id" => 524,
					"conexion_id" => 524,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1967,
					"anterior" => 1958,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [464] = 
				(
					array(
					"cliente_id" => 525,
					"conexion_id" => 525,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5415,
					"anterior" => 5399,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [465] = 
				(
					array(
					"cliente_id" => 526,
					"conexion_id" => 526,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4769,
					"anterior" => 4757,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [466] = 
				(
					array(
					"cliente_id" => 740,
					"conexion_id" => 527,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2815,
					"anterior" => 2795,
					"basico" => 100,
					"excedente" => 50,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [467] = 
				(
					array(
					"cliente_id" => 528,
					"conexion_id" => 528,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2601,
					"anterior" => 2583,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [468] = 
				(
					array(
					"cliente_id" => 530,
					"conexion_id" => 530,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2016,
					"anterior" => 1990,
					"basico" => 100,
					"excedente" => 80,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [469] = 
				(
					array(
					"cliente_id" => 531,
					"conexion_id" => 531,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5283,
					"anterior" => 5260,
					"basico" => 100,
					"excedente" => 65,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [470] = 
				(
					array(
					"cliente_id" => 721,
					"conexion_id" => 532,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3415,
					"anterior" => 3392,
					"basico" => 100,
					"excedente" => 65,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [471] = 
				(
					array(
					"cliente_id" => 533,
					"conexion_id" => 533,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2291,
					"anterior" => 2280,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [472] = 
				(
					array(
					"cliente_id" => 534,
					"conexion_id" => 534,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2321,
					"anterior" => 2302,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [473] = 
				(
					array(
					"cliente_id" => 535,
					"conexion_id" => 535,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2169,
					"anterior" => 2169,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [474] = 
				(
					array(
					"cliente_id" => 536,
					"conexion_id" => 536,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2968,
					"anterior" => 2957,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [475] = 
				(
					array(
					"cliente_id" => 537,
					"conexion_id" => 537,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1028,
					"anterior" => 1024,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [476] = 
				(
					array(
					"cliente_id" => 562,
					"conexion_id" => 538,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4942,
					"anterior" => 4923,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [477] = 
				(
					array(
					"cliente_id" => 563,
					"conexion_id" => 539,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 742,
					"anterior" => 742,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [478] = 
				(
					array(
					"cliente_id" => 544,
					"conexion_id" => 540,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2152,
					"anterior" => 2124,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [479] = 
				(
					array(
					"cliente_id" => 544,
					"conexion_id" => 540,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2152,
					"anterior" => 2124,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [480] = 
				(
					array(
					"cliente_id" => 561,
					"conexion_id" => 541,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2007,
					"anterior" => 1998,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [481] = 
				(
					array(
					"cliente_id" => 747,
					"conexion_id" => 542,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2570,
					"anterior" => 2558,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [482] = 
				(
					array(
					"cliente_id" => 543,
					"conexion_id" => 543,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1847,
					"anterior" => 1836,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [483] = 
				(
					array(
					"cliente_id" => 601,
					"conexion_id" => 544,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3918,
					"anterior" => 3899,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [484] = 
				(
					array(
					"cliente_id" => 602,
					"conexion_id" => 545,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4650,
					"anterior" => 4631,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [485] = 
				(
					array(
					"cliente_id" => 540,
					"conexion_id" => 546,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2731,
					"anterior" => 2722,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [486] = 
				(
					array(
					"cliente_id" => 541,
					"conexion_id" => 547,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2519,
					"anterior" => 2513,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [487] = 
				(
					array(
					"cliente_id" => 542,
					"conexion_id" => 549,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4072,
					"anterior" => 4055,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [488] = 
				(
					array(
					"cliente_id" => 600,
					"conexion_id" => 550,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2270,
					"anterior" => 2256,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [489] = 
				(
					array(
					"cliente_id" => 768,
					"conexion_id" => 551,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2565,
					"anterior" => 2557,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [490] = 
				(
					array(
					"cliente_id" => 539,
					"conexion_id" => 552,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2146,
					"anterior" => 2138,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [491] = 
				(
					array(
					"cliente_id" => 597,
					"conexion_id" => 553,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3498,
					"anterior" => 3487,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [492] = 
				(
					array(
					"cliente_id" => 596,
					"conexion_id" => 554,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2433,
					"anterior" => 2433,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [493] = 
				(
					array(
					"cliente_id" => 538,
					"conexion_id" => 555,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2177,
					"anterior" => 2160,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [494] = 
				(
					array(
					"cliente_id" => 603,
					"conexion_id" => 556,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1095,
					"anterior" => 1095,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [495] = 
				(
					array(
					"cliente_id" => 564,
					"conexion_id" => 557,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3309,
					"anterior" => 3309,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [496] = 
				(
					array(
					"cliente_id" => 593,
					"conexion_id" => 558,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2275,
					"anterior" => 2275,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [497] = 
				(
					array(
					"cliente_id" => 599,
					"conexion_id" => 559,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3289,
					"anterior" => 3278,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [498] = 
				(
					array(
					"cliente_id" => 606,
					"conexion_id" => 560,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3295,
					"anterior" => 3286,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [499] = 
				(
					array(
					"cliente_id" => 545,
					"conexion_id" => 561,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2820,
					"anterior" => 2794,
					"basico" => 100,
					"excedente" => 80,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [500] = 
				(
					array(
					"cliente_id" => 546,
					"conexion_id" => 562,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2298,
					"anterior" => 2281,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [501] = 
				(
					array(
					"cliente_id" => 591,
					"conexion_id" => 563,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2670,
					"anterior" => 2670,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [502] = 
				(
					array(
					"cliente_id" => 592,
					"conexion_id" => 564,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1837,
					"anterior" => 1835,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [503] = 
				(
					array(
					"cliente_id" => 761,
					"conexion_id" => 565,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2080,
					"anterior" => 2067,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [504] = 
				(
					array(
					"cliente_id" => 547,
					"conexion_id" => 566,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1294,
					"anterior" => 1273,
					"basico" => 100,
					"excedente" => 55,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [505] = 
				(
					array(
					"cliente_id" => 566,
					"conexion_id" => 567,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3137,
					"anterior" => 3121,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [506] = 
				(
					array(
					"cliente_id" => 567,
					"conexion_id" => 568,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 817,
					"anterior" => 809,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [507] = 
				(
					array(
					"cliente_id" => 568,
					"conexion_id" => 569,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3367,
					"anterior" => 3351,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [508] = 
				(
					array(
					"cliente_id" => 569,
					"conexion_id" => 570,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1901,
					"anterior" => 1901,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [509] = 
				(
					array(
					"cliente_id" => 570,
					"conexion_id" => 571,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2818,
					"anterior" => 2818,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [510] = 
				(
					array(
					"cliente_id" => 548,
					"conexion_id" => 572,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1980,
					"anterior" => 1980,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [511] = 
				(
					array(
					"cliente_id" => 549,
					"conexion_id" => 573,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 39,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 145,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [512] = 
				(
					array(
					"cliente_id" => 550,
					"conexion_id" => 574,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2641,
					"anterior" => 2632,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [513] = 
				(
					array(
					"cliente_id" => 605,
					"conexion_id" => 575,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3577,
					"anterior" => 3562,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [514] = 
				(
					array(
					"cliente_id" => 571,
					"conexion_id" => 576,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3591,
					"anterior" => 3577,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [515] = 
				(
					array(
					"cliente_id" => 572,
					"conexion_id" => 577,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3618,
					"anterior" => 3611,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [516] = 
				(
					array(
					"cliente_id" => 573,
					"conexion_id" => 578,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3055,
					"anterior" => 3041,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [517] = 
				(
					array(
					"cliente_id" => 574,
					"conexion_id" => 579,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3773,
					"anterior" => 3768,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [518] = 
				(
					array(
					"cliente_id" => 575,
					"conexion_id" => 580,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3006,
					"anterior" => 2993,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [519] = 
				(
					array(
					"cliente_id" => 576,
					"conexion_id" => 581,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2501,
					"anterior" => 2501,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [520] = 
				(
					array(
					"cliente_id" => 551,
					"conexion_id" => 582,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3460,
					"anterior" => 3449,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [521] = 
				(
					array(
					"cliente_id" => 552,
					"conexion_id" => 583,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2703,
					"anterior" => 2788,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [522] = 
				(
					array(
					"cliente_id" => 604,
					"conexion_id" => 584,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2491,
					"anterior" => 2480,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [523] = 
				(
					array(
					"cliente_id" => 594,
					"conexion_id" => 585,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3218,
					"anterior" => 3210,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [524] = 
				(
					array(
					"cliente_id" => 553,
					"conexion_id" => 586,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3632,
					"anterior" => 3624,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [525] = 
				(
					array(
					"cliente_id" => 577,
					"conexion_id" => 587,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 942,
					"anterior" => 931,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [526] = 
				(
					array(
					"cliente_id" => 578,
					"conexion_id" => 588,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2860,
					"anterior" => 2850,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [527] = 
				(
					array(
					"cliente_id" => 598,
					"conexion_id" => 589,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3467,
					"anterior" => 3467,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [528] = 
				(
					array(
					"cliente_id" => 595,
					"conexion_id" => 590,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3632,
					"anterior" => 3624,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [529] = 
				(
					array(
					"cliente_id" => 554,
					"conexion_id" => 591,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2764,
					"anterior" => 2751,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [530] = 
				(
					array(
					"cliente_id" => 579,
					"conexion_id" => 592,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3052,
					"anterior" => 3044,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [531] = 
				(
					array(
					"cliente_id" => 580,
					"conexion_id" => 593,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3746,
					"anterior" => 3737,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [532] = 
				(
					array(
					"cliente_id" => 581,
					"conexion_id" => 594,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 568,
					"anterior" => 555,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [533] = 
				(
					array(
					"cliente_id" => 582,
					"conexion_id" => 595,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3654,
					"anterior" => 3641,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [534] = 
				(
					array(
					"cliente_id" => 583,
					"conexion_id" => 596,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3228,
					"anterior" => 3215,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [535] = 
				(
					array(
					"cliente_id" => 584,
					"conexion_id" => 597,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1931,
					"anterior" => 1923,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [536] = 
				(
					array(
					"cliente_id" => 555,
					"conexion_id" => 598,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3434,
					"anterior" => 3419,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [537] = 
				(
					array(
					"cliente_id" => 556,
					"conexion_id" => 599,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3695,
					"anterior" => 3686,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [538] = 
				(
					array(
					"cliente_id" => 585,
					"conexion_id" => 600,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2026,
					"anterior" => 2010,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [539] = 
				(
					array(
					"cliente_id" => 557,
					"conexion_id" => 601,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3492,
					"anterior" => 3481,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [540] = 
				(
					array(
					"cliente_id" => 586,
					"conexion_id" => 602,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1636,
					"anterior" => 1624,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [541] = 
				(
					array(
					"cliente_id" => 587,
					"conexion_id" => 603,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3136,
					"anterior" => 3136,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [542] = 
				(
					array(
					"cliente_id" => 588,
					"conexion_id" => 604,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1272,
					"anterior" => 1255,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [543] = 
				(
					array(
					"cliente_id" => 589,
					"conexion_id" => 605,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2159,
					"anterior" => 2147,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [544] = 
				(
					array(
					"cliente_id" => 590,
					"conexion_id" => 606,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2197,
					"anterior" => 2185,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [545] = 
				(
					array(
					"cliente_id" => 628,
					"conexion_id" => 609,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1854,
					"anterior" => 1848,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [546] = 
				(
					array(
					"cliente_id" => 629,
					"conexion_id" => 610,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2722,
					"anterior" => 2722,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [547] = 
				(
					array(
					"cliente_id" => 630,
					"conexion_id" => 611,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3274,
					"anterior" => 3216,
					"basico" => 100,
					"excedente" => 240,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [548] = 
				(
					array(
					"cliente_id" => 316,
					"conexion_id" => 612,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2283,
					"anterior" => 2273,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [549] = 
				(
					array(
					"cliente_id" => 688,
					"conexion_id" => 613,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1979,
					"anterior" => 1963,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [550] = 
				(
					array(
					"cliente_id" => 689,
					"conexion_id" => 614,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1974,
					"anterior" => 1945,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [551] = 
				(
					array(
					"cliente_id" => 690,
					"conexion_id" => 615,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1915,
					"anterior" => 1893,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [552] = 
				(
					array(
					"cliente_id" => 726,
					"conexion_id" => 616,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1675,
					"anterior" => 1640,
					"basico" => 100,
					"excedente" => 125,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [553] = 
				(
					array(
					"cliente_id" => 692,
					"conexion_id" => 617,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1691,
					"anterior" => 1651,
					"basico" => 100,
					"excedente" => 150,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [554] = 
				(
					array(
					"cliente_id" => 695,
					"conexion_id" => 618,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3574,
					"anterior" => 3544,
					"basico" => 100,
					"excedente" => 100,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [555] = 
				(
					array(
					"cliente_id" => 696,
					"conexion_id" => 619,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3694,
					"anterior" => 3672,
					"basico" => 220,
					"excedente" => 56,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [556] = 
				(
					array(
					"cliente_id" => 714,
					"conexion_id" => 620,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2335,
					"anterior" => 2316,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [557] = 
				(
					array(
					"cliente_id" => 715,
					"conexion_id" => 621,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 490,
					"anterior" => 489,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [558] = 
				(
					array(
					"cliente_id" => 716,
					"conexion_id" => 622,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1111,
					"anterior" => 1101,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [559] = 
				(
					array(
					"cliente_id" => 718,
					"conexion_id" => 623,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1551,
					"anterior" => 1551,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [560] = 
				(
					array(
					"cliente_id" => 728,
					"conexion_id" => 624,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1960,
					"anterior" => 1950,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [561] = 
				(
					array(
					"cliente_id" => 729,
					"conexion_id" => 625,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5902,
					"anterior" => 5902,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [562] = 
				(
					array(
					"cliente_id" => 730,
					"conexion_id" => 626,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 959,
					"anterior" => 959,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [563] = 
				(
					array(
					"cliente_id" => 727,
					"conexion_id" => 627,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1050,
					"anterior" => 1050,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [564] = 
				(
					array(
					"cliente_id" => 731,
					"conexion_id" => 628,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 71,
					"anterior" => 60,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [565] = 
				(
					array(
					"cliente_id" => 732,
					"conexion_id" => 629,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2272,
					"anterior" => 2264,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [566] = 
				(
					array(
					"cliente_id" => 745,
					"conexion_id" => 630,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2830,
					"anterior" => 2816,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [567] = 
				(
					array(
					"cliente_id" => 746,
					"conexion_id" => 631,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [568] = 
				(
					array(
					"cliente_id" => 752,
					"conexion_id" => 632,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2477,
					"anterior" => 2442,
					"basico" => 100,
					"excedente" => 125,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [569] = 
				(
					array(
					"cliente_id" => 748,
					"conexion_id" => 633,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 438,
					"anterior" => 422,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [570] = 
				(
					array(
					"cliente_id" => 749,
					"conexion_id" => 634,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3972,
					"anterior" => 3959,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [571] = 
				(
					array(
					"cliente_id" => 753,
					"conexion_id" => 635,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 47,
					"anterior" => 36,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [572] = 
				(
					array(
					"cliente_id" => 762,
					"conexion_id" => 636,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1445,
					"anterior" => 1420,
					"basico" => 100,
					"excedente" => 75,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [573] = 
				(
					array(
					"cliente_id" => 763,
					"conexion_id" => 637,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 788,
					"anterior" => 770,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [574] = 
				(
					array(
					"cliente_id" => 764,
					"conexion_id" => 638,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2389,
					"anterior" => 2375,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [575] = 
				(
					array(
					"cliente_id" => 765,
					"conexion_id" => 639,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 445,
					"anterior" => 443,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [576] = 
				(
					array(
					"cliente_id" => 236,
					"conexion_id" => 640,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1416,
					"anterior" => 1410,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [577] = 
				(
					array(
					"cliente_id" => 777,
					"conexion_id" => 641,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 29718,
					"anterior" => 29579,
					"basico" => 220,
					"excedente" => 992,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [578] = 
				(
					array(
					"cliente_id" => 777,
					"conexion_id" => 642,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 44528,
					"anterior" => 42832,
					"basico" => 220,
					"excedente" => 13448,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [579] = 
				(
					array(
					"cliente_id" => 770,
					"conexion_id" => 644,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2863,
					"anterior" => 2854,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [580] = 
				(
					array(
					"cliente_id" => 723,
					"conexion_id" => 663,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 4,
					"anterior" => 4,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [581] = 
				(
					array(
					"cliente_id" => 772,
					"conexion_id" => 664,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2594,
					"anterior" => 2570,
					"basico" => 100,
					"excedente" => 70,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [582] = 
				(
					array(
					"cliente_id" => 771,
					"conexion_id" => 665,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 750,
					"anterior" => 731,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [583] = 
				(
					array(
					"cliente_id" => 773,
					"conexion_id" => 667,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1205,
					"anterior" => 1174,
					"basico" => 100,
					"excedente" => 105,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [584] = 
				(
					array(
					"cliente_id" => 774,
					"conexion_id" => 668,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 355,
					"anterior" => 350,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [585] = 
				(
					array(
					"cliente_id" => 778,
					"conexion_id" => 669,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 584,
					"anterior" => 579,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [586] = 
				(
					array(
					"cliente_id" => 779,
					"conexion_id" => 670,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 186,
					"anterior" => 182,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [587] = 
				(
					array(
					"cliente_id" => 780,
					"conexion_id" => 671,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1361,
					"anterior" => 1350,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [588] = 
				(
					array(
					"cliente_id" => 781,
					"conexion_id" => 672,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 941,
					"anterior" => 932,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [589] = 
				(
					array(
					"cliente_id" => 782,
					"conexion_id" => 673,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 595,
					"anterior" => 586,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [590] = 
				(
					array(
					"cliente_id" => 783,
					"conexion_id" => 674,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1244,
					"anterior" => 1231,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [591] = 
				(
					array(
					"cliente_id" => 784,
					"conexion_id" => 675,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1273,
					"anterior" => 1264,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [592] = 
				(
					array(
					"cliente_id" => 785,
					"conexion_id" => 676,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 646,
					"anterior" => 637,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [593] = 
				(
					array(
					"cliente_id" => 786,
					"conexion_id" => 677,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 740,
					"anterior" => 731,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [594] = 
				(
					array(
					"cliente_id" => 787,
					"conexion_id" => 678,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 157,
					"anterior" => 153,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [595] = 
				(
					array(
					"cliente_id" => 788,
					"conexion_id" => 679,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 710,
					"anterior" => 701,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [596] = 
				(
					array(
					"cliente_id" => 789,
					"conexion_id" => 680,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 908,
					"anterior" => 899,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [597] = 
				(
					array(
					"cliente_id" => 790,
					"conexion_id" => 681,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 65,
					"anterior" => 65,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [598] = 
				(
					array(
					"cliente_id" => 791,
					"conexion_id" => 682,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1742,
					"anterior" => 1734,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [599] = 
				(
					array(
					"cliente_id" => 792,
					"conexion_id" => 683,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 691,
					"anterior" => 682,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [600] = 
				(
					array(
					"cliente_id" => 793,
					"conexion_id" => 684,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 706,
					"anterior" => 693,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [601] = 
				(
					array(
					"cliente_id" => 794,
					"conexion_id" => 685,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1121,
					"anterior" => 1112,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [602] = 
				(
					array(
					"cliente_id" => 795,
					"conexion_id" => 686,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2026,
					"anterior" => 2017,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [603] = 
				(
					array(
					"cliente_id" => 796,
					"conexion_id" => 687,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 881,
					"anterior" => 874,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [604] = 
				(
					array(
					"cliente_id" => 797,
					"conexion_id" => 688,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 771,
					"anterior" => 763,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [605] = 
				(
					array(
					"cliente_id" => 798,
					"conexion_id" => 689,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1839,
					"anterior" => 1827,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [606] = 
				(
					array(
					"cliente_id" => 799,
					"conexion_id" => 690,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1106,
					"anterior" => 1098,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [607] = 
				(
					array(
					"cliente_id" => 800,
					"conexion_id" => 691,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1600,
					"anterior" => 1591,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [608] = 
				(
					array(
					"cliente_id" => 801,
					"conexion_id" => 692,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1074,
					"anterior" => 1069,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [609] = 
				(
					array(
					"cliente_id" => 802,
					"conexion_id" => 693,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 514,
					"anterior" => 516,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [610] = 
				(
					array(
					"cliente_id" => 803,
					"conexion_id" => 694,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2023,
					"anterior" => 2014,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [611] = 
				(
					array(
					"cliente_id" => 804,
					"conexion_id" => 695,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1045,
					"anterior" => 1036,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [612] = 
				(
					array(
					"cliente_id" => 805,
					"conexion_id" => 696,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1033,
					"anterior" => 1017,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [613] = 
				(
					array(
					"cliente_id" => 806,
					"conexion_id" => 697,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 739,
					"anterior" => 720,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [614] = 
				(
					array(
					"cliente_id" => 807,
					"conexion_id" => 698,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 585,
					"anterior" => 570,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [615] = 
				(
					array(
					"cliente_id" => 808,
					"conexion_id" => 699,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1506,
					"anterior" => 1497,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [616] = 
				(
					array(
					"cliente_id" => 809,
					"conexion_id" => 700,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1601,
					"anterior" => 1590,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [617] = 
				(
					array(
					"cliente_id" => 810,
					"conexion_id" => 701,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 803,
					"anterior" => 794,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [618] = 
				(
					array(
					"cliente_id" => 811,
					"conexion_id" => 702,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2042,
					"anterior" => 2030,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [619] = 
				(
					array(
					"cliente_id" => 812,
					"conexion_id" => 703,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1089,
					"anterior" => 1077,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [620] = 
				(
					array(
					"cliente_id" => 813,
					"conexion_id" => 704,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 1440,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [621] = 
				(
					array(
					"cliente_id" => 814,
					"conexion_id" => 705,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 806,
					"anterior" => 798,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [622] = 
				(
					array(
					"cliente_id" => 815,
					"conexion_id" => 706,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 364,
					"anterior" => 356,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [623] = 
				(
					array(
					"cliente_id" => 816,
					"conexion_id" => 707,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 912,
					"anterior" => 905,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [624] = 
				(
					array(
					"cliente_id" => 817,
					"conexion_id" => 708,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 445,
					"anterior" => 436,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [625] = 
				(
					array(
					"cliente_id" => 818,
					"conexion_id" => 709,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1689,
					"anterior" => 1670,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [626] = 
				(
					array(
					"cliente_id" => 819,
					"conexion_id" => 710,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1330,
					"anterior" => 1312,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [627] = 
				(
					array(
					"cliente_id" => 820,
					"conexion_id" => 711,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 863,
					"anterior" => 854,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [628] = 
				(
					array(
					"cliente_id" => 821,
					"conexion_id" => 712,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1296,
					"anterior" => 1288,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [629] = 
				(
					array(
					"cliente_id" => 822,
					"conexion_id" => 713,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 839,
					"anterior" => 831,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [630] = 
				(
					array(
					"cliente_id" => 823,
					"conexion_id" => 714,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1087,
					"anterior" => 1070,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [631] = 
				(
					array(
					"cliente_id" => 824,
					"conexion_id" => 715,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1097,
					"anterior" => 1089,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [632] = 
				(
					array(
					"cliente_id" => 825,
					"conexion_id" => 716,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 246,
					"anterior" => 240,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [633] = 
				(
					array(
					"cliente_id" => 826,
					"conexion_id" => 717,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 594,
					"anterior" => 583,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [634] = 
				(
					array(
					"cliente_id" => 827,
					"conexion_id" => 718,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 822,
					"anterior" => 813,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [635] = 
				(
					array(
					"cliente_id" => 828,
					"conexion_id" => 719,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 836,
					"anterior" => 828,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [636] = 
				(
					array(
					"cliente_id" => 829,
					"conexion_id" => 720,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2141,
					"anterior" => 2130,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [637] = 
				(
					array(
					"cliente_id" => 830,
					"conexion_id" => 721,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1564,
					"anterior" => 1552,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [638] = 
				(
					array(
					"cliente_id" => 831,
					"conexion_id" => 722,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1437,
					"anterior" => 1421,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [639] = 
				(
					array(
					"cliente_id" => 832,
					"conexion_id" => 723,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1697,
					"anterior" => 1682,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [640] = 
				(
					array(
					"cliente_id" => 833,
					"conexion_id" => 724,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 868,
					"anterior" => 851,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [641] = 
				(
					array(
					"cliente_id" => 834,
					"conexion_id" => 725,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 905,
					"anterior" => 896,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [642] = 
				(
					array(
					"cliente_id" => 835,
					"conexion_id" => 726,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 388,
					"anterior" => 379,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [643] = 
				(
					array(
					"cliente_id" => 836,
					"conexion_id" => 727,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 412,
					"anterior" => 403,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [644] = 
				(
					array(
					"cliente_id" => 837,
					"conexion_id" => 728,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1121,
					"anterior" => 1110,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [645] = 
				(
					array(
					"cliente_id" => 838,
					"conexion_id" => 729,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 620,
					"anterior" => 611,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [646] = 
				(
					array(
					"cliente_id" => 839,
					"conexion_id" => 730,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 716,
					"anterior" => 709,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [647] = 
				(
					array(
					"cliente_id" => 840,
					"conexion_id" => 731,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 881,
					"anterior" => 870,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [648] = 
				(
					array(
					"cliente_id" => 841,
					"conexion_id" => 732,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1045,
					"anterior" => 1036,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [649] = 
				(
					array(
					"cliente_id" => 842,
					"conexion_id" => 733,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 706,
					"anterior" => 692,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [650] = 
				(
					array(
					"cliente_id" => 843,
					"conexion_id" => 734,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 448,
					"anterior" => 435,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [651] = 
				(
					array(
					"cliente_id" => 844,
					"conexion_id" => 735,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1258,
					"anterior" => 1249,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [652] = 
				(
					array(
					"cliente_id" => 845,
					"conexion_id" => 736,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 941,
					"anterior" => 930,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [653] = 
				(
					array(
					"cliente_id" => 846,
					"conexion_id" => 737,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 470,
					"anterior" => 461,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [654] = 
				(
					array(
					"cliente_id" => 847,
					"conexion_id" => 738,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 584,
					"anterior" => 575,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [655] = 
				(
					array(
					"cliente_id" => 848,
					"conexion_id" => 739,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 657,
					"anterior" => 649,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [656] = 
				(
					array(
					"cliente_id" => 849,
					"conexion_id" => 740,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1444,
					"anterior" => 1432,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [657] = 
				(
					array(
					"cliente_id" => 850,
					"conexion_id" => 741,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1335,
					"anterior" => 1326,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [658] = 
				(
					array(
					"cliente_id" => 851,
					"conexion_id" => 742,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1004,
					"anterior" => 902,
					"basico" => 100,
					"excedente" => 460,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [659] = 
				(
					array(
					"cliente_id" => 852,
					"conexion_id" => 743,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 944,
					"anterior" => 931,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [660] = 
				(
					array(
					"cliente_id" => 853,
					"conexion_id" => 744,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 819,
					"anterior" => 807,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [661] = 
				(
					array(
					"cliente_id" => 854,
					"conexion_id" => 745,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1716,
					"anterior" => 1690,
					"basico" => 100,
					"excedente" => 80,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [662] = 
				(
					array(
					"cliente_id" => 855,
					"conexion_id" => 746,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 517,
					"anterior" => 502,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [663] = 
				(
					array(
					"cliente_id" => 856,
					"conexion_id" => 747,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2045,
					"anterior" => 2036,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [664] = 
				(
					array(
					"cliente_id" => 857,
					"conexion_id" => 748,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 844,
					"anterior" => 837,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [665] = 
				(
					array(
					"cliente_id" => 858,
					"conexion_id" => 749,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 830,
					"anterior" => 821,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [666] = 
				(
					array(
					"cliente_id" => 859,
					"conexion_id" => 750,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 824,
					"anterior" => 815,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [667] = 
				(
					array(
					"cliente_id" => 860,
					"conexion_id" => 751,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 814,
					"anterior" => 806,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [668] = 
				(
					array(
					"cliente_id" => 861,
					"conexion_id" => 752,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 584,
					"anterior" => 578,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [669] = 
				(
					array(
					"cliente_id" => 862,
					"conexion_id" => 753,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 943,
					"anterior" => 936,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [670] = 
				(
					array(
					"cliente_id" => 863,
					"conexion_id" => 754,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 750,
					"anterior" => 741,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [671] = 
				(
					array(
					"cliente_id" => 864,
					"conexion_id" => 755,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1108,
					"anterior" => 1099,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [672] = 
				(
					array(
					"cliente_id" => 865,
					"conexion_id" => 758,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1470,
					"anterior" => 1460,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [673] = 
				(
					array(
					"cliente_id" => 866,
					"conexion_id" => 759,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1036,
					"anterior" => 1027,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [674] = 
				(
					array(
					"cliente_id" => 867,
					"conexion_id" => 760,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 659,
					"anterior" => 652,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [675] = 
				(
					array(
					"cliente_id" => 868,
					"conexion_id" => 761,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [676] = 
				(
					array(
					"cliente_id" => 869,
					"conexion_id" => 762,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 123,
					"anterior" => 119,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [677] = 
				(
					array(
					"cliente_id" => 870,
					"conexion_id" => 763,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [678] = 
				(
					array(
					"cliente_id" => 871,
					"conexion_id" => 764,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [679] = 
				(
					array(
					"cliente_id" => 872,
					"conexion_id" => 765,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 724,
					"anterior" => 708,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [680] = 
				(
					array(
					"cliente_id" => 873,
					"conexion_id" => 766,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 750,
					"anterior" => 731,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [681] = 
				(
					array(
					"cliente_id" => 874,
					"conexion_id" => 767,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 79,
					"anterior" => 79,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [682] = 
				(
					array(
					"cliente_id" => 875,
					"conexion_id" => 768,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 261,
					"anterior" => 253,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [683] = 
				(
					array(
					"cliente_id" => 876,
					"conexion_id" => 769,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 850,
					"anterior" => 832,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [684] = 
				(
					array(
					"cliente_id" => 877,
					"conexion_id" => 770,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 727,
					"anterior" => 715,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [685] = 
				(
					array(
					"cliente_id" => 878,
					"conexion_id" => 771,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1122,
					"anterior" => 1099,
					"basico" => 100,
					"excedente" => 65,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [686] = 
				(
					array(
					"cliente_id" => 879,
					"conexion_id" => 772,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 185,
					"anterior" => 185,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [687] = 
				(
					array(
					"cliente_id" => 880,
					"conexion_id" => 773,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1245,
					"anterior" => 1244,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [688] = 
				(
					array(
					"cliente_id" => 881,
					"conexion_id" => 774,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1583,
					"anterior" => 1571,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [689] = 
				(
					array(
					"cliente_id" => 882,
					"conexion_id" => 775,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3190,
					"anterior" => 3190,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [690] = 
				(
					array(
					"cliente_id" => 883,
					"conexion_id" => 776,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 515,
					"anterior" => 481,
					"basico" => 100,
					"excedente" => 120,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [691] = 
				(
					array(
					"cliente_id" => 884,
					"conexion_id" => 777,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1181,
					"anterior" => 1136,
					"basico" => 100,
					"excedente" => 175,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [692] = 
				(
					array(
					"cliente_id" => 885,
					"conexion_id" => 778,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 469,
					"anterior" => 468,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [693] = 
				(
					array(
					"cliente_id" => 886,
					"conexion_id" => 779,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 528,
					"anterior" => 512,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [694] = 
				(
					array(
					"cliente_id" => 887,
					"conexion_id" => 780,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 400,
					"anterior" => 386,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [695] = 
				(
					array(
					"cliente_id" => 888,
					"conexion_id" => 781,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 461,
					"anterior" => 444,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [696] = 
				(
					array(
					"cliente_id" => 889,
					"conexion_id" => 782,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 29,
					"anterior" => 29,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [697] = 
				(
					array(
					"cliente_id" => 890,
					"conexion_id" => 783,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 328,
					"anterior" => 318,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [698] = 
				(
					array(
					"cliente_id" => 891,
					"conexion_id" => 784,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 377,
					"anterior" => 368,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [699] = 
				(
					array(
					"cliente_id" => 892,
					"conexion_id" => 785,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1626,
					"anterior" => 1544,
					"basico" => 100,
					"excedente" => 360,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [700] = 
				(
					array(
					"cliente_id" => 893,
					"conexion_id" => 786,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 636,
					"anterior" => 588,
					"basico" => 100,
					"excedente" => 190,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [701] = 
				(
					array(
					"cliente_id" => 894,
					"conexion_id" => 787,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1148,
					"anterior" => 1092,
					"basico" => 100,
					"excedente" => 230,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [702] = 
				(
					array(
					"cliente_id" => 895,
					"conexion_id" => 788,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 201,
					"anterior" => 256,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [703] = 
				(
					array(
					"cliente_id" => 896,
					"conexion_id" => 789,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 620,
					"anterior" => 590,
					"basico" => 100,
					"excedente" => 100,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [704] = 
				(
					array(
					"cliente_id" => 897,
					"conexion_id" => 790,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 984,
					"anterior" => 937,
					"basico" => 100,
					"excedente" => 185,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [705] = 
				(
					array(
					"cliente_id" => 898,
					"conexion_id" => 791,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 345,
					"anterior" => 321,
					"basico" => 100,
					"excedente" => 70,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [706] = 
				(
					array(
					"cliente_id" => 899,
					"conexion_id" => 792,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 417,
					"anterior" => 412,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [707] = 
				(
					array(
					"cliente_id" => 900,
					"conexion_id" => 793,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 816,
					"anterior" => 800,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [708] = 
				(
					array(
					"cliente_id" => 901,
					"conexion_id" => 794,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 645,
					"anterior" => 629,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [709] = 
				(
					array(
					"cliente_id" => 902,
					"conexion_id" => 795,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 618,
					"anterior" => 604,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [710] = 
				(
					array(
					"cliente_id" => 903,
					"conexion_id" => 796,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 683,
					"anterior" => 670,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [711] = 
				(
					array(
					"cliente_id" => 904,
					"conexion_id" => 797,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 538,
					"anterior" => 510,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [712] = 
				(
					array(
					"cliente_id" => 905,
					"conexion_id" => 798,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 627,
					"anterior" => 611,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [713] = 
				(
					array(
					"cliente_id" => 906,
					"conexion_id" => 799,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 986,
					"anterior" => 958,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [714] = 
				(
					array(
					"cliente_id" => 907,
					"conexion_id" => 800,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 743,
					"anterior" => 721,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [715] = 
				(
					array(
					"cliente_id" => 908,
					"conexion_id" => 801,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3960,
					"anterior" => 3942,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [716] = 
				(
					array(
					"cliente_id" => 909,
					"conexion_id" => 802,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2184,
					"anterior" => 2179,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [717] = 
				(
					array(
					"cliente_id" => 910,
					"conexion_id" => 803,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 388,
					"anterior" => 388,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [718] = 
				(
					array(
					"cliente_id" => 911,
					"conexion_id" => 804,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [719] = 
				(
					array(
					"cliente_id" => 914,
					"conexion_id" => 806,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 183,
					"anterior" => 182,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [720] = 
				(
					array(
					"cliente_id" => 915,
					"conexion_id" => 807,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 316,
					"anterior" => 297,
					"basico" => 100,
					"excedente" => 45,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [721] = 
				(
					array(
					"cliente_id" => 916,
					"conexion_id" => 808,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 248,
					"anterior" => 247,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [722] = 
				(
					array(
					"cliente_id" => 917,
					"conexion_id" => 809,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 821,
					"anterior" => 806,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [723] = 
				(
					array(
					"cliente_id" => 918,
					"conexion_id" => 810,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1653,
					"anterior" => 1620,
					"basico" => 100,
					"excedente" => 115,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [724] = 
				(
					array(
					"cliente_id" => 919,
					"conexion_id" => 811,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 934,
					"anterior" => 917,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [725] = 
				(
					array(
					"cliente_id" => 912,
					"conexion_id" => 812,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 488,
					"anterior" => 472,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [726] = 
				(
					array(
					"cliente_id" => 920,
					"conexion_id" => 813,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 176,
					"anterior" => 174,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [727] = 
				(
					array(
					"cliente_id" => 921,
					"conexion_id" => 814,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 229,
					"anterior" => 229,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [728] = 
				(
					array(
					"cliente_id" => 923,
					"conexion_id" => 816,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 216,
					"anterior" => 208,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [729] = 
				(
					array(
					"cliente_id" => 924,
					"conexion_id" => 817,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2268,
					"anterior" => 2173,
					"basico" => 100,
					"excedente" => 425,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [730] = 
				(
					array(
					"cliente_id" => 926,
					"conexion_id" => 819,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1018,
					"anterior" => 1018,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [731] = 
				(
					array(
					"cliente_id" => 927,
					"conexion_id" => 820,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 51,
					"anterior" => 51,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [732] = 
				(
					array(
					"cliente_id" => 928,
					"conexion_id" => 821,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 197,
					"anterior" => 197,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [733] = 
				(
					array(
					"cliente_id" => 776,
					"conexion_id" => 824,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 552,
					"anterior" => 546,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [734] = 
				(
					array(
					"cliente_id" => 930,
					"conexion_id" => 825,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 330,
					"anterior" => 321,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [735] = 
				(
					array(
					"cliente_id" => 931,
					"conexion_id" => 826,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 338,
					"anterior" => 324,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [736] = 
				(
					array(
					"cliente_id" => 932,
					"conexion_id" => 827,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 294,
					"anterior" => 294,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [737] = 
				(
					array(
					"cliente_id" => 933,
					"conexion_id" => 828,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 481,
					"anterior" => 464,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [738] = 
				(
					array(
					"cliente_id" => 934,
					"conexion_id" => 829,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 312,
					"anterior" => 308,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [739] = 
				(
					array(
					"cliente_id" => 936,
					"conexion_id" => 831,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [740] = 
				(
					array(
					"cliente_id" => 938,
					"conexion_id" => 833,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 184,
					"anterior" => 178,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [741] = 
				(
					array(
					"cliente_id" => 939,
					"conexion_id" => 834,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 237,
					"anterior" => 225,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [742] = 
				(
					array(
					"cliente_id" => 940,
					"conexion_id" => 835,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 206,
					"anterior" => 197,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [743] = 
				(
					array(
					"cliente_id" => 941,
					"conexion_id" => 836,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 660,
					"anterior" => 657,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [744] = 
				(
					array(
					"cliente_id" => 942,
					"conexion_id" => 837,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 568,
					"anterior" => 550,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [745] = 
				(
					array(
					"cliente_id" => 944,
					"conexion_id" => 839,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 378,
					"anterior" => 375,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [746] = 
				(
					array(
					"cliente_id" => 945,
					"conexion_id" => 840,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2947,
					"anterior" => 2947,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [747] = 
				(
					array(
					"cliente_id" => 946,
					"conexion_id" => 841,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1268,
					"anterior" => 1229,
					"basico" => 100,
					"excedente" => 145,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [748] = 
				(
					array(
					"cliente_id" => 947,
					"conexion_id" => 842,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 816,
					"anterior" => 708,
					"basico" => 100,
					"excedente" => 490,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [749] = 
				(
					array(
					"cliente_id" => 948,
					"conexion_id" => 843,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 129,
					"anterior" => 128,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [750] = 
				(
					array(
					"cliente_id" => 949,
					"conexion_id" => 844,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 520,
					"anterior" => 500,
					"basico" => 100,
					"excedente" => 50,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [751] = 
				(
					array(
					"cliente_id" => 950,
					"conexion_id" => 845,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 196,
					"anterior" => 180,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [752] = 
				(
					array(
					"cliente_id" => 951,
					"conexion_id" => 846,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 226,
					"anterior" => 215,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [753] = 
				(
					array(
					"cliente_id" => 952,
					"conexion_id" => 847,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 716,
					"anterior" => 698,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [754] = 
				(
					array(
					"cliente_id" => 953,
					"conexion_id" => 848,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 488,
					"anterior" => 466,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [755] = 
				(
					array(
					"cliente_id" => 954,
					"conexion_id" => 849,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 237,
					"anterior" => 229,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [756] = 
				(
					array(
					"cliente_id" => 955,
					"conexion_id" => 850,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 180,
					"anterior" => 169,
					"basico" => 100,
					"excedente" => 5,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [757] = 
				(
					array(
					"cliente_id" => 957,
					"conexion_id" => 852,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 16,
					"anterior" => 16,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [758] = 
				(
					array(
					"cliente_id" => 958,
					"conexion_id" => 853,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 114,
					"anterior" => 114,
					"basico" => 220,
					"excedente" => 0,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [759] = 
				(
					array(
					"cliente_id" => 959,
					"conexion_id" => 854,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [760] = 
				(
					array(
					"cliente_id" => 960,
					"conexion_id" => 860,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 257,
					"anterior" => 250,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [761] = 
				(
					array(
					"cliente_id" => 962,
					"conexion_id" => 862,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 122,
					"anterior" => 112,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [762] = 
				(
					array(
					"cliente_id" => 652,
					"conexion_id" => 863,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2929,
					"anterior" => 2918,
					"basico" => 220,
					"excedente" => 0,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [763] = 
				(
					array(
					"cliente_id" => 963,
					"conexion_id" => 864,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 100,
					"anterior" => 90,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [764] = 
				(
					array(
					"cliente_id" => 965,
					"conexion_id" => 866,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 344,
					"anterior" => 313,
					"basico" => 100,
					"excedente" => 105,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [765] = 
				(
					array(
					"cliente_id" => 966,
					"conexion_id" => 867,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 181,
					"anterior" => 179,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [766] = 
				(
					array(
					"cliente_id" => 967,
					"conexion_id" => 868,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 94,
					"anterior" => 72,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [767] = 
				(
					array(
					"cliente_id" => 968,
					"conexion_id" => 869,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 18,
					"anterior" => 14,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [768] = 
				(
					array(
					"cliente_id" => 969,
					"conexion_id" => 870,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 114,
					"anterior" => 90,
					"basico" => 100,
					"excedente" => 70,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [769] = 
				(
					array(
					"cliente_id" => 970,
					"conexion_id" => 871,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 96,
					"anterior" => 74,
					"basico" => 100,
					"excedente" => 60,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [770] = 
				(
					array(
					"cliente_id" => 971,
					"conexion_id" => 872,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 100,
					"anterior" => 93,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [771] = 
				(
					array(
					"cliente_id" => 972,
					"conexion_id" => 873,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 90,
					"anterior" => 81,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [772] = 
				(
					array(
					"cliente_id" => 973,
					"conexion_id" => 874,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 147,
					"anterior" => 124,
					"basico" => 100,
					"excedente" => 65,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [773] = 
				(
					array(
					"cliente_id" => 974,
					"conexion_id" => 875,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [774] = 
				(
					array(
					"cliente_id" => 975,
					"conexion_id" => 876,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [775] = 
				(
					array(
					"cliente_id" => 976,
					"conexion_id" => 877,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 12,
					"anterior" => 11,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [776] = 
				(
					array(
					"cliente_id" => 977,
					"conexion_id" => 878,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 70,
					"anterior" => 61,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [777] = 
				(
					array(
					"cliente_id" => 978,
					"conexion_id" => 879,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 142,
					"anterior" => 107,
					"basico" => 100,
					"excedente" => 125,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [778] = 
				(
					array(
					"cliente_id" => 979,
					"conexion_id" => 880,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 11,
					"anterior" => 11,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [779] = 
				(
					array(
					"cliente_id" => 980,
					"conexion_id" => 881,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 13,
					"anterior" => 13,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [780] = 
				(
					array(
					"cliente_id" => 981,
					"conexion_id" => 882,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 119,
					"anterior" => 92,
					"basico" => 100,
					"excedente" => 85,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [781] = 
				(
					array(
					"cliente_id" => 982,
					"conexion_id" => 883,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 24,
					"anterior" => 21,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [782] = 
				(
					array(
					"cliente_id" => 983,
					"conexion_id" => 884,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 286,
					"anterior" => 251,
					"basico" => 100,
					"excedente" => 125,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [783] = 
				(
					array(
					"cliente_id" => 984,
					"conexion_id" => 885,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7,
					"anterior" => 7,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [784] = 
				(
					array(
					"cliente_id" => 985,
					"conexion_id" => 886,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 127,
					"anterior" => 112,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [785] = 
				(
					array(
					"cliente_id" => 986,
					"conexion_id" => 887,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 126,
					"anterior" => 117,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [786] = 
				(
					array(
					"cliente_id" => 987,
					"conexion_id" => 888,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 128,
					"anterior" => 105,
					"basico" => 100,
					"excedente" => 65,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [787] = 
				(
					array(
					"cliente_id" => 988,
					"conexion_id" => 889,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 245,
					"anterior" => 228,
					"basico" => 100,
					"excedente" => 35,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [788] = 
				(
					array(
					"cliente_id" => 989,
					"conexion_id" => 890,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 97,
					"anterior" => 91,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [789] = 
				(
					array(
					"cliente_id" => 990,
					"conexion_id" => 891,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 124,
					"anterior" => 115,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [790] = 
				(
					array(
					"cliente_id" => 991,
					"conexion_id" => 892,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 72,
					"anterior" => 57,
					"basico" => 100,
					"excedente" => 25,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [791] = 
				(
					array(
					"cliente_id" => 992,
					"conexion_id" => 893,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 15,
					"anterior" => 15,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [792] = 
				(
					array(
					"cliente_id" => 993,
					"conexion_id" => 894,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 51,
					"anterior" => 46,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [793] = 
				(
					array(
					"cliente_id" => 994,
					"conexion_id" => 895,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 109,
					"anterior" => 101,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [794] = 
				(
					array(
					"cliente_id" => 995,
					"conexion_id" => 896,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 213,
					"anterior" => 180,
					"basico" => 100,
					"excedente" => 115,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [795] = 
				(
					array(
					"cliente_id" => 996,
					"conexion_id" => 897,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 31,
					"anterior" => 25,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [796] = 
				(
					array(
					"cliente_id" => 997,
					"conexion_id" => 898,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 9,
					"anterior" => 9,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [797] = 
				(
					array(
					"cliente_id" => 998,
					"conexion_id" => 899,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 133,
					"anterior" => 112,
					"basico" => 100,
					"excedente" => 55,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [798] = 
				(
					array(
					"cliente_id" => 999,
					"conexion_id" => 900,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 190,
					"anterior" => 162,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [799] = 
				(
					array(
					"cliente_id" => 1000,
					"conexion_id" => 901,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 197,
					"anterior" => 155,
					"basico" => 100,
					"excedente" => 160,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [800] = 
				(
					array(
					"cliente_id" => 1001,
					"conexion_id" => 902,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 21,
					"anterior" => 21,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [801] = 
				(
					array(
					"cliente_id" => 1002,
					"conexion_id" => 903,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 140,
					"anterior" => 112,
					"basico" => 100,
					"excedente" => 90,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [802] = 
				(
					array(
					"cliente_id" => 1003,
					"conexion_id" => 904,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 25,
					"anterior" => 25,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [803] = 
				(
					array(
					"cliente_id" => 1004,
					"conexion_id" => 905,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 86,
					"anterior" => 68,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [804] = 
				(
					array(
					"cliente_id" => 1005,
					"conexion_id" => 906,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 18,
					"anterior" => 18,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [805] = 
				(
					array(
					"cliente_id" => 1006,
					"conexion_id" => 907,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 142,
					"anterior" => 121,
					"basico" => 100,
					"excedente" => 55,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [806] = 
				(
					array(
					"cliente_id" => 1007,
					"conexion_id" => 908,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 197,
					"anterior" => 185,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [807] = 
				(
					array(
					"cliente_id" => 1008,
					"conexion_id" => 909,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 60,
					"anterior" => 48,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [808] = 
				(
					array(
					"cliente_id" => 1009,
					"conexion_id" => 910,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 207,
					"anterior" => 195,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [809] = 
				(
					array(
					"cliente_id" => 1010,
					"conexion_id" => 911,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 41,
					"anterior" => 31,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [810] = 
				(
					array(
					"cliente_id" => 1011,
					"conexion_id" => 912,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 113,
					"anterior" => 92,
					"basico" => 100,
					"excedente" => 55,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [811] = 
				(
					array(
					"cliente_id" => 1012,
					"conexion_id" => 913,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 180,
					"anterior" => 149,
					"basico" => 100,
					"excedente" => 105,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [812] = 
				(
					array(
					"cliente_id" => 1013,
					"conexion_id" => 914,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 124,
					"anterior" => 115,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [813] = 
				(
					array(
					"cliente_id" => 1014,
					"conexion_id" => 915,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 88,
					"anterior" => 70,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [814] = 
				(
					array(
					"cliente_id" => 1015,
					"conexion_id" => 916,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [815] = 
				(
					array(
					"cliente_id" => 1016,
					"conexion_id" => 917,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [816] = 
				(
					array(
					"cliente_id" => 1017,
					"conexion_id" => 918,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 68,
					"anterior" => 67,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [817] = 
				(
					array(
					"cliente_id" => 1018,
					"conexion_id" => 919,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 160,
					"anterior" => 146,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [818] = 
				(
					array(
					"cliente_id" => 1019,
					"conexion_id" => 920,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 96,
					"anterior" => 84,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [819] = 
				(
					array(
					"cliente_id" => 1020,
					"conexion_id" => 921,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7,
					"anterior" => 7,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [820] = 
				(
					array(
					"cliente_id" => 1021,
					"conexion_id" => 922,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 149,
					"anterior" => 136,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [821] = 
				(
					array(
					"cliente_id" => 1022,
					"conexion_id" => 923,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 179,
					"anterior" => 142,
					"basico" => 100,
					"excedente" => 135,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [822] = 
				(
					array(
					"cliente_id" => 1023,
					"conexion_id" => 924,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 75,
					"anterior" => 68,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [823] = 
				(
					array(
					"cliente_id" => 1024,
					"conexion_id" => 925,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 64,
					"anterior" => 35,
					"basico" => 100,
					"excedente" => 95,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [824] = 
				(
					array(
					"cliente_id" => 1025,
					"conexion_id" => 926,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 181,
					"anterior" => 151,
					"basico" => 100,
					"excedente" => 100,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [825] = 
				(
					array(
					"cliente_id" => 1026,
					"conexion_id" => 927,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 72,
					"anterior" => 69,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [826] = 
				(
					array(
					"cliente_id" => 1027,
					"conexion_id" => 928,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 97,
					"anterior" => 81,
					"basico" => 100,
					"excedente" => 30,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [827] = 
				(
					array(
					"cliente_id" => 1028,
					"conexion_id" => 929,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 26,
					"anterior" => 26,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [828] = 
				(
					array(
					"cliente_id" => 1029,
					"conexion_id" => 930,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 152,
					"anterior" => 132,
					"basico" => 100,
					"excedente" => 50,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [829] = 
				(
					array(
					"cliente_id" => 1030,
					"conexion_id" => 931,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 109,
					"anterior" => 91,
					"basico" => 100,
					"excedente" => 40,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [830] = 
				(
					array(
					"cliente_id" => 1031,
					"conexion_id" => 932,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 154,
					"anterior" => 140,
					"basico" => 100,
					"excedente" => 20,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [831] = 
				(
					array(
					"cliente_id" => 1032,
					"conexion_id" => 933,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 78,
					"anterior" => 51,
					"basico" => 100,
					"excedente" => 85,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [832] = 
				(
					array(
					"cliente_id" => 1033,
					"conexion_id" => 934,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 42,
					"anterior" => 30,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [833] = 
				(
					array(
					"cliente_id" => 1034,
					"conexion_id" => 935,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 89,
					"anterior" => 84,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [834] = 
				(
					array(
					"cliente_id" => 1035,
					"conexion_id" => 936,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 6,
					"anterior" => 6,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [835] = 
				(
					array(
					"cliente_id" => 1036,
					"conexion_id" => 937,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 178,
					"anterior" => 173,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [836] = 
				(
					array(
					"cliente_id" => 1037,
					"conexion_id" => 938,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 19,
					"anterior" => 19,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [837] = 
				(
					array(
					"cliente_id" => 1038,
					"conexion_id" => 939,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 16,
					"anterior" => 16,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [838] = 
				(
					array(
					"cliente_id" => 1039,
					"conexion_id" => 940,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2,
					"anterior" => 2,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [839] = 
				(
					array(
					"cliente_id" => 1040,
					"conexion_id" => 941,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 147,
					"anterior" => 134,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [840] = 
				(
					array(
					"cliente_id" => 1041,
					"conexion_id" => 942,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 119,
					"anterior" => 117,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [841] = 
				(
					array(
					"cliente_id" => 1042,
					"conexion_id" => 945,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 136,
					"anterior" => 124,
					"basico" => 100,
					"excedente" => 10,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [842] = 
				(
					array(
					"cliente_id" => 1043,
					"conexion_id" => 946,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3,
					"anterior" => 3,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [843] = 
				(
					array(
					"cliente_id" => 1044,
					"conexion_id" => 947,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 190,
					"anterior" => 177,
					"basico" => 100,
					"excedente" => 15,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [844] = 
				(
					array(
					"cliente_id" => 1045,
					"conexion_id" => 948,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 20,
					"anterior" => 12,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [845] = 
				(
					array(
					"cliente_id" => 1047,
					"conexion_id" => 949,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 2812,
					"anterior" => 2770,
					"basico" => 220,
					"excedente" => 216,
					"importe" => 220,
					"mts" => 15
					)
				);
				$array_mediciones [846] = 
				(
					array(
					"cliente_id" => 1048,
					"conexion_id" => 950,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7,
					"anterior" => 4,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [847] = 
				(
					array(
					"cliente_id" => 1049,
					"conexion_id" => 951,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 7,
					"anterior" => 6,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [848] = 
				(
					array(
					"cliente_id" => 1050,
					"conexion_id" => 952,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [849] = 
				(
					array(
					"cliente_id" => 1051,
					"conexion_id" => 953,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 5,
					"anterior" => 3,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [850] = 
				(
					array(
					"cliente_id" => 1052,
					"conexion_id" => 954,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 1,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [851] = 
				(
					array(
					"cliente_id" => 1053,
					"conexion_id" => 955,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 72,
					"anterior" => 48,
					"basico" => 100,
					"excedente" => 70,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [852] = 
				(
					array(
					"cliente_id" => 1054,
					"conexion_id" => 956,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [853] = 
				(
					array(
					"cliente_id" => 1055,
					"conexion_id" => 957,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 41,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 155,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [854] = 
				(
					array(
					"cliente_id" => 1056,
					"conexion_id" => 958,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [855] = 
				(
					array(
					"cliente_id" => 1057,
					"conexion_id" => 959,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 11,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [856] = 
				(
					array(
					"cliente_id" => 1058,
					"conexion_id" => 960,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 1,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [857] = 
				(
					array(
					"cliente_id" => 1059,
					"conexion_id" => 961,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [858] = 
				(
					array(
					"cliente_id" => 1060,
					"conexion_id" => 962,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 0,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);
				$array_mediciones [859] = 
				(
					array(
					"cliente_id" => 1061,
					"conexion_id" => 963,
					"mes" => 11,
					"anio" => 2017,
					"actual" => 3,
					"anterior" => 0,
					"basico" => 100,
					"excedente" => 0,
					"importe" => 100,
					"mts" => 10
					)
				);

				*/




			$array_mediciones = [];

			$array_mediciones [1] = 
			( 
				array (
				"cliente_id" => 19,
				"conexion_id" => 1,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 156,
				"anterior" => 156,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [2] = 
			( 
				array (
				"cliente_id" => 62,
				"conexion_id" => 2,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1506,
				"anterior" => 1473,
				"basico" => 100,
				"excedente" => 115,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [3] = 
			( 
				array (
				"cliente_id" => 63,
				"conexion_id" => 3,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1160,
				"anterior" => 1143,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2015.64
				)
			);
			$array_mediciones [4] = 
			( 
				array (
				"cliente_id" => 743,
				"conexion_id" => 6,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 285,
				"anterior" => 284,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [5] = 
			( 
				array (
				"cliente_id" => 702,
				"conexion_id" => 8,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5242,
				"anterior" => 5233,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [6] = 
			( 
				array (
				"cliente_id" => 24,
				"conexion_id" => 9,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 160,
				"anterior" => 160,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1215.49
				)
			);
			$array_mediciones [7] = 
			( 
				array (
				"cliente_id" => 722,
				"conexion_id" => 10,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6212,
				"anterior" => 6152,
				"basico" => 100,
				"excedente" => 250,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [8] = 
			( 
				array (
				"cliente_id" => 56,
				"conexion_id" => 11,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1042,
				"anterior" => 1014,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 301.46
				)
			);
			$array_mediciones [10] = 
			( 
				array (
				"cliente_id" => 6,
				"conexion_id" => 13,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7593,
				"anterior" => 7531,
				"basico" => 100,
				"excedente" => 260,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [11] = 
			( 
				array (
				"cliente_id" => 26,
				"conexion_id" => 14,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7884,
				"anterior" => 7872,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1434.91
				)
			);
			$array_mediciones [13] = 
			( 
				array (
				"cliente_id" => 750,
				"conexion_id" => 16,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1757,
				"anterior" => 1757,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1361.15
				)
			);
			$array_mediciones [14] = 
			( 
				array (
				"cliente_id" => 54,
				"conexion_id" => 17,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8043,
				"anterior" => 8026,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [15] = 
			( 
				array (
				"cliente_id" => 701,
				"conexion_id" => 18,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 310,
				"anterior" => 289,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1685.42
				)
			);
			$array_mediciones [16] = 
			( 
				array (
				"cliente_id" => 29,
				"conexion_id" => 19,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 896,
				"anterior" => 869,
				"basico" => 100,
				"excedente" => 85,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [17] = 
			( 
				array (
				"cliente_id" => 11,
				"conexion_id" => 20,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 641,
				"anterior" => 641,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 375.01
				)
			);
			$array_mediciones [18] = 
			( 
				array (
				"cliente_id" => 30,
				"conexion_id" => 21,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 205,
				"anterior" => 192,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 461.98
				)
			);
			$array_mediciones [19] = 
			( 
				array (
				"cliente_id" => 31,
				"conexion_id" => 22,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1897,
				"anterior" => 1897,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 315.97
				)
			);
			$array_mediciones [20] = 
			( 
				array (
				"cliente_id" => 32,
				"conexion_id" => 24,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 344,
				"anterior" => 341,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 267.82
				)
			);
			$array_mediciones [21] = 
			( 
				array (
				"cliente_id" => 33,
				"conexion_id" => 25,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 436,
				"anterior" => 416,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [22] = 
			( 
				array (
				"cliente_id" => 2,
				"conexion_id" => 26,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 170,
				"anterior" => 170,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 8549.41
				)
			);
			$array_mediciones [23] = 
			( 
				array (
				"cliente_id" => 654,
				"conexion_id" => 27,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 3912,
				"anterior" => 3902,
				"basico" => 220,
				"excedente" => 0,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 0
				)
			);
			$array_mediciones [24] = 
			( 
				array (
				"cliente_id" => 8,
				"conexion_id" => 28,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8672,
				"anterior" => 8617,
				"basico" => 100,
				"excedente" => 225,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [25] = 
			( 
				array (
				"cliente_id" => 53,
				"conexion_id" => 31,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 9941,
				"anterior" => 9941,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2932.4
				)
			);
			$array_mediciones [26] = 
			( 
				array (
				"cliente_id" => 53,
				"conexion_id" => 32,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2118,
				"anterior" => 2075,
				"basico" => 100,
				"excedente" => 165,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [27] = 
			( 
				array (
				"cliente_id" => 51,
				"conexion_id" => 34,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3426,
				"anterior" => 3426,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [28] = 
			( 
				array (
				"cliente_id" => 71,
				"conexion_id" => 35,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5584,
				"anterior" => 5568,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [29] = 
			( 
				array (
				"cliente_id" => 150,
				"conexion_id" => 36,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4418,
				"anterior" => 4405,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 25.38
				)
			);
			$array_mediciones [30] = 
			( 
				array (
				"cliente_id" => 49,
				"conexion_id" => 37,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4483,
				"anterior" => 4483,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [31] = 
			( 
				array (
				"cliente_id" => 48,
				"conexion_id" => 38,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2357,
				"anterior" => 2355,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [32] = 
			( 
				array (
				"cliente_id" => 108,
				"conexion_id" => 39,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3842,
				"anterior" => 3832,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [33] = 
			( 
				array (
				"cliente_id" => 68,
				"conexion_id" => 40,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 150,
				"anterior" => 78,
				"basico" => 100,
				"excedente" => 310,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 391.79
				)
			);
			$array_mediciones [34] = 
			( 
				array (
				"cliente_id" => 616,
				"conexion_id" => 41,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 4937,
				"anterior" => 4937,
				"basico" => 220,
				"excedente" => 0,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 7380.59
				)
			);
			$array_mediciones [35] = 
			( 
				array (
				"cliente_id" => 9,
				"conexion_id" => 42,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3787,
				"anterior" => 3773,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [36] = 
			( 
				array (
				"cliente_id" => 498,
				"conexion_id" => 45,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 3408,
				"anterior" => 3242,
				"basico" => 220,
				"excedente" => 1208,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 1602.89
				)
			);
			$array_mediciones [37] = 
			( 
				array (
				"cliente_id" => 4,
				"conexion_id" => 46,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1824,
				"anterior" => 1785,
				"basico" => 100,
				"excedente" => 145,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [38] = 
			( 
				array (
				"cliente_id" => 2,
				"conexion_id" => 47,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 21,
				"anterior" => 19,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 160.37
				)
			);
			$array_mediciones [39] = 
			( 
				array (
				"cliente_id" => 623,
				"conexion_id" => 48,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3896,
				"anterior" => 3815,
				"basico" => 100,
				"excedente" => 355,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 502.68
				)
			);
			$array_mediciones [40] = 
			( 
				array (
				"cliente_id" => 702,
				"conexion_id" => 49,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 481,
				"anterior" => 479,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [41] = 
			( 
				array (
				"cliente_id" => 43,
				"conexion_id" => 50,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 120,
				"anterior" => 120,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [43] = 
			( 
				array (
				"cliente_id" => 73,
				"conexion_id" => 53,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5707,
				"anterior" => 5707,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [44] = 
			( 
				array (
				"cliente_id" => 3,
				"conexion_id" => 54,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2741,
				"anterior" => 2729,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [45] = 
			( 
				array (
				"cliente_id" => 145,
				"conexion_id" => 55,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7496,
				"anterior" => 7489,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [46] = 
			( 
				array (
				"cliente_id" => 39,
				"conexion_id" => 57,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5248,
				"anterior" => 5180,
				"basico" => 100,
				"excedente" => 290,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 266.44
				)
			);
			$array_mediciones [47] = 
			( 
				array (
				"cliente_id" => 76,
				"conexion_id" => 59,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 9891,
				"anterior" => 9891,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [48] = 
			( 
				array (
				"cliente_id" => 151,
				"conexion_id" => 60,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5983,
				"anterior" => 5983,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [49] = 
			( 
				array (
				"cliente_id" => 77,
				"conexion_id" => 63,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 917,
				"anterior" => 909,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 237.51
				)
			);
			$array_mediciones [50] = 
			( 
				array (
				"cliente_id" => 78,
				"conexion_id" => 64,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3260,
				"anterior" => 3251,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [51] = 
			( 
				array (
				"cliente_id" => 79,
				"conexion_id" => 65,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1424,
				"anterior" => 1424,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [52] = 
			( 
				array (
				"cliente_id" => 703,
				"conexion_id" => 66,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 11108,
				"anterior" => 10964,
				"basico" => 220,
				"excedente" => 1032,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 6802.56
				)
			);
			$array_mediciones [53] = 
			( 
				array (
				"cliente_id" => 143,
				"conexion_id" => 67,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1999,
				"anterior" => 1999,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [54] = 
			( 
				array (
				"cliente_id" => 81,
				"conexion_id" => 68,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6221,
				"anterior" => 6221,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2955.07
				)
			);
			$array_mediciones [55] = 
			( 
				array (
				"cliente_id" => 81,
				"conexion_id" => 69,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 6671,
				"anterior" => 6671,
				"basico" => 220,
				"excedente" => 0,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 2552.02
				)
			);
			$array_mediciones [56] = 
			( 
				array (
				"cliente_id" => 719,
				"conexion_id" => 70,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 573,
				"anterior" => 540,
				"basico" => 100,
				"excedente" => 115,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [59] = 
			( 
				array (
				"cliente_id" => 84,
				"conexion_id" => 73,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1509,
				"anterior" => 1485,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [60] = 
			( 
				array (
				"cliente_id" => 83,
				"conexion_id" => 74,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3085,
				"anterior" => 3085,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 7159.51
				)
			);
			$array_mediciones [63] = 
			( 
				array (
				"cliente_id" => 87,
				"conexion_id" => 77,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7610,
				"anterior" => 7527,
				"basico" => 100,
				"excedente" => 365,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 14371.53
				)
			);
			$array_mediciones [64] = 
			( 
				array (
				"cliente_id" => 83,
				"conexion_id" => 78,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2401,
				"anterior" => 2401,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 814.41
				)
			);
			$array_mediciones [65] = 
			( 
				array (
				"cliente_id" => 83,
				"conexion_id" => 79,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3510,
				"anterior" => 3510,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 160.37
				)
			);
			$array_mediciones [66] = 
			( 
				array (
				"cliente_id" => 89,
				"conexion_id" => 81,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5965,
				"anterior" => 5965,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [67] = 
			( 
				array (
				"cliente_id" => 90,
				"conexion_id" => 82,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7545,
				"anterior" => 7545,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [68] = 
			( 
				array (
				"cliente_id" => 129,
				"conexion_id" => 83,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2165,
				"anterior" => 2158,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [69] = 
			( 
				array (
				"cliente_id" => 91,
				"conexion_id" => 84,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5563,
				"anterior" => 5554,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [70] = 
			( 
				array (
				"cliente_id" => 956,
				"conexion_id" => 85,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5109,
				"anterior" => 5104,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [71] = 
			( 
				array (
				"cliente_id" => 94,
				"conexion_id" => 89,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6011,
				"anterior" => 6011,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [72] = 
			( 
				array (
				"cliente_id" => 96,
				"conexion_id" => 91,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 1018,
				"anterior" => 918,
				"basico" => 220,
				"excedente" => 680,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 48697.65
				)
			);
			$array_mediciones [73] = 
			( 
				array (
				"cliente_id" => 98,
				"conexion_id" => 93,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 581,
				"anterior" => 569,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 319.47
				)
			);
			$array_mediciones [74] = 
			( 
				array (
				"cliente_id" => 86,
				"conexion_id" => 94,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 650,
				"anterior" => 642,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [76] = 
			( 
				array (
				"cliente_id" => 102,
				"conexion_id" => 97,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1930,
				"anterior" => 1917,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [77] = 
			( 
				array (
				"cliente_id" => 144,
				"conexion_id" => 98,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 959,
				"anterior" => 951,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 902.22
				)
			);
			$array_mediciones [78] = 
			( 
				array (
				"cliente_id" => 73,
				"conexion_id" => 100,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 195,
				"anterior" => 182,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [79] = 
			( 
				array (
				"cliente_id" => 86,
				"conexion_id" => 101,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3612,
				"anterior" => 3612,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [80] = 
			( 
				array (
				"cliente_id" => 104,
				"conexion_id" => 102,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5371,
				"anterior" => 5297,
				"basico" => 100,
				"excedente" => 320,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [81] = 
			( 
				array (
				"cliente_id" => 92,
				"conexion_id" => 103,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3728,
				"anterior" => 3719,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [82] = 
			( 
				array (
				"cliente_id" => 107,
				"conexion_id" => 105,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3057,
				"anterior" => 3054,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [83] = 
			( 
				array (
				"cliente_id" => 108,
				"conexion_id" => 106,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 761,
				"anterior" => 753,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 635.78
				)
			);
			$array_mediciones [85] = 
			( 
				array (
				"cliente_id" => 109,
				"conexion_id" => 108,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6373,
				"anterior" => 6365,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [86] = 
			( 
				array (
				"cliente_id" => 110,
				"conexion_id" => 109,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2620,
				"anterior" => 2620,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [87] = 
			( 
				array (
				"cliente_id" => 111,
				"conexion_id" => 110,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6619,
				"anterior" => 6607,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [88] = 
			( 
				array (
				"cliente_id" => 125,
				"conexion_id" => 111,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3915,
				"anterior" => 3853,
				"basico" => 100,
				"excedente" => 260,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [89] = 
			( 
				array (
				"cliente_id" => 112,
				"conexion_id" => 112,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1557,
				"anterior" => 1535,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [90] = 
			( 
				array (
				"cliente_id" => 113,
				"conexion_id" => 113,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 217,
				"anterior" => 183,
				"basico" => 100,
				"excedente" => 120,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [91] = 
			( 
				array (
				"cliente_id" => 114,
				"conexion_id" => 114,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1732,
				"anterior" => 1721,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [92] = 
			( 
				array (
				"cliente_id" => 115,
				"conexion_id" => 115,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 753,
				"anterior" => 744,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [93] = 
			( 
				array (
				"cliente_id" => 116,
				"conexion_id" => 116,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7797,
				"anterior" => 7797,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [94] = 
			( 
				array (
				"cliente_id" => 117,
				"conexion_id" => 117,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7720,
				"anterior" => 7577,
				"basico" => 100,
				"excedente" => 665,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1939.41
				)
			);
			$array_mediciones [95] = 
			( 
				array (
				"cliente_id" => 119,
				"conexion_id" => 119,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 642,
				"anterior" => 634,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 697.2
				)
			);
			$array_mediciones [96] = 
			( 
				array (
				"cliente_id" => 119,
				"conexion_id" => 120,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3731,
				"anterior" => 3726,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 841.34
				)
			);
			$array_mediciones [99] = 
			( 
				array (
				"cliente_id" => 130,
				"conexion_id" => 125,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2222,
				"anterior" => 2194,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [100] = 
			( 
				array (
				"cliente_id" => 122,
				"conexion_id" => 126,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3114,
				"anterior" => 3114,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2494.47
				)
			);
			$array_mediciones [101] = 
			( 
				array (
				"cliente_id" => 123,
				"conexion_id" => 127,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3193,
				"anterior" => 3193,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [102] = 
			( 
				array (
				"cliente_id" => 153,
				"conexion_id" => 131,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1511,
				"anterior" => 1500,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [103] = 
			( 
				array (
				"cliente_id" => 127,
				"conexion_id" => 132,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2805,
				"anterior" => 2792,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [104] = 
			( 
				array (
				"cliente_id" => 131,
				"conexion_id" => 133,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3584,
				"anterior" => 3560,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 227.87
				)
			);
			$array_mediciones [105] = 
			( 
				array (
				"cliente_id" => 132,
				"conexion_id" => 134,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3809,
				"anterior" => 3809,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 247.52
				)
			);
			$array_mediciones [106] = 
			( 
				array (
				"cliente_id" => 133,
				"conexion_id" => 136,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2566,
				"anterior" => 2555,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2279.71
				)
			);
			$array_mediciones [107] = 
			( 
				array (
				"cliente_id" => 134,
				"conexion_id" => 137,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8234,
				"anterior" => 8226,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 247.52
				)
			);
			$array_mediciones [108] = 
			( 
				array (
				"cliente_id" => 137,
				"conexion_id" => 139,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 819,
				"anterior" => 785,
				"basico" => 100,
				"excedente" => 120,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 343.58
				)
			);
			$array_mediciones [109] = 
			( 
				array (
				"cliente_id" => 138,
				"conexion_id" => 140,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3962,
				"anterior" => 3930,
				"basico" => 100,
				"excedente" => 110,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [110] = 
			( 
				array (
				"cliente_id" => 146,
				"conexion_id" => 143,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2284,
				"anterior" => 2284,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [111] = 
			( 
				array (
				"cliente_id" => 155,
				"conexion_id" => 145,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4755,
				"anterior" => 4755,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [112] = 
			( 
				array (
				"cliente_id" => 156,
				"conexion_id" => 146,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 486,
				"anterior" => 482,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 606.8
				)
			);
			$array_mediciones [113] = 
			( 
				array (
				"cliente_id" => 157,
				"conexion_id" => 147,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1582,
				"anterior" => 1582,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2228.83
				)
			);
			$array_mediciones [114] = 
			( 
				array (
				"cliente_id" => 158,
				"conexion_id" => 148,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3818,
				"anterior" => 3772,
				"basico" => 100,
				"excedente" => 180,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 24516.55
				)
			);
			$array_mediciones [115] = 
			( 
				array (
				"cliente_id" => 158,
				"conexion_id" => 149,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 961,
				"anterior" => 961,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 769.1
				)
			);
			$array_mediciones [116] = 
			( 
				array (
				"cliente_id" => 86,
				"conexion_id" => 150,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1588,
				"anterior" => 1624,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 981.58
				)
			);
			$array_mediciones [117] = 
			( 
				array (
				"cliente_id" => 86,
				"conexion_id" => 151,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2997,
				"anterior" => 2968,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [118] = 
			( 
				array (
				"cliente_id" => 160,
				"conexion_id" => 152,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4140,
				"anterior" => 4129,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [119] = 
			( 
				array (
				"cliente_id" => 161,
				"conexion_id" => 153,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3988,
				"anterior" => 3914,
				"basico" => 100,
				"excedente" => 320,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [120] = 
			( 
				array (
				"cliente_id" => 162,
				"conexion_id" => 154,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2156,
				"anterior" => 2156,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [121] = 
			( 
				array (
				"cliente_id" => 162,
				"conexion_id" => 155,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5692,
				"anterior" => 5512,
				"basico" => 100,
				"excedente" => 850,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [122] = 
			( 
				array (
				"cliente_id" => 159,
				"conexion_id" => 156,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1747,
				"anterior" => 1763,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [123] = 
			( 
				array (
				"cliente_id" => 163,
				"conexion_id" => 157,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 524,
				"anterior" => 420,
				"basico" => 100,
				"excedente" => 470,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [124] = 
			( 
				array (
				"cliente_id" => 165,
				"conexion_id" => 158,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2374,
				"anterior" => 2352,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [125] = 
			( 
				array (
				"cliente_id" => 86,
				"conexion_id" => 159,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1220,
				"anterior" => 1205,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [126] = 
			( 
				array (
				"cliente_id" => 166,
				"conexion_id" => 160,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4037,
				"anterior" => 4037,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [127] = 
			( 
				array (
				"cliente_id" => 168,
				"conexion_id" => 161,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3635,
				"anterior" => 3626,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [128] = 
			( 
				array (
				"cliente_id" => 169,
				"conexion_id" => 162,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3253,
				"anterior" => 3232,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1869.75
				)
			);
			$array_mediciones [129] = 
			( 
				array (
				"cliente_id" => 734,
				"conexion_id" => 163,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6584,
				"anterior" => 6573,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 131.44
				)
			);
			$array_mediciones [130] = 
			( 
				array (
				"cliente_id" => 171,
				"conexion_id" => 164,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4782,
				"anterior" => 4769,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [131] = 
			( 
				array (
				"cliente_id" => 172,
				"conexion_id" => 165,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3629,
				"anterior" => 3616,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 7074.39
				)
			);
			$array_mediciones [132] = 
			( 
				array (
				"cliente_id" => 173,
				"conexion_id" => 166,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1716,
				"anterior" => 1695,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [133] = 
			( 
				array (
				"cliente_id" => 174,
				"conexion_id" => 167,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2101,
				"anterior" => 2101,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [134] = 
			( 
				array (
				"cliente_id" => 174,
				"conexion_id" => 168,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2449,
				"anterior" => 2421,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [135] = 
			( 
				array (
				"cliente_id" => 738,
				"conexion_id" => 169,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3576,
				"anterior" => 3568,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [136] = 
			( 
				array (
				"cliente_id" => 742,
				"conexion_id" => 170,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 470,
				"anterior" => 439,
				"basico" => 100,
				"excedente" => 105,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 535.51
				)
			);
			$array_mediciones [137] = 
			( 
				array (
				"cliente_id" => 178,
				"conexion_id" => 172,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4143,
				"anterior" => 4122,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [138] = 
			( 
				array (
				"cliente_id" => 179,
				"conexion_id" => 173,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1428,
				"anterior" => 1416,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [139] = 
			( 
				array (
				"cliente_id" => 180,
				"conexion_id" => 174,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 9684,
				"anterior" => 9619,
				"basico" => 100,
				"excedente" => 275,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 179.66
				)
			);
			$array_mediciones [140] = 
			( 
				array (
				"cliente_id" => 181,
				"conexion_id" => 175,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4959,
				"anterior" => 4947,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [141] = 
			( 
				array (
				"cliente_id" => 184,
				"conexion_id" => 177,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1342,
				"anterior" => 1330,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 535.72
				)
			);

			$array_mediciones [143] = 
			( 
				array (
				"cliente_id" => 186,
				"conexion_id" => 179,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2509,
				"anterior" => 2498,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [144] = 
			( 
				array (
				"cliente_id" => 187,
				"conexion_id" => 180,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2941,
				"anterior" => 2934,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [145] = 
			( 
				array (
				"cliente_id" => 182,
				"conexion_id" => 185,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1920,
				"anterior" => 1908,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [146] = 
			( 
				array (
				"cliente_id" => 755,
				"conexion_id" => 186,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 332,
				"anterior" => 332,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 8143.62
				)
			);
			$array_mediciones [147] = 
			( 
				array (
				"cliente_id" => 193,
				"conexion_id" => 187,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1822,
				"anterior" => 1819,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);

			$array_mediciones [149] = 
			( 
				array (
				"cliente_id" => 195,
				"conexion_id" => 189,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2159,
				"anterior" => 2147,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [150] = 
			( 
				array (
				"cliente_id" => 199,
				"conexion_id" => 194,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2431,
				"anterior" => 2427,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 379.3
				)
			);
			$array_mediciones [151] = 
			( 
				array (
				"cliente_id" => 6,
				"conexion_id" => 196,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 201,
				"anterior" => 187,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 184.48
				)
			);

			$array_mediciones [153] = 
			( 
				array (
				"cliente_id" => 610,
				"conexion_id" => 198,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 405,
				"anterior" => 382,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 285.72
				)
			);
			$array_mediciones [154] = 
			( 
				array (
				"cliente_id" => 617,
				"conexion_id" => 199,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2038,
				"anterior" => 2038,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 635.78
				)
			);
			$array_mediciones [155] = 
			( 
				array (
				"cliente_id" => 400,
				"conexion_id" => 200,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4968,
				"anterior" => 4940,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 8845.67
				)
			);
			$array_mediciones [156] = 
			( 
				array (
				"cliente_id" => 401,
				"conexion_id" => 201,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4375,
				"anterior" => 4345,
				"basico" => 100,
				"excedente" => 100,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [157] = 
			( 
				array (
				"cliente_id" => 402,
				"conexion_id" => 202,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1786,
				"anterior" => 1775,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [158] = 
			( 
				array (
				"cliente_id" => 403,
				"conexion_id" => 203,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3191,
				"anterior" => 3180,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [159] = 
			( 
				array (
				"cliente_id" => 404,
				"conexion_id" => 204,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5899,
				"anterior" => 5821,
				"basico" => 100,
				"excedente" => 340,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [160] = 
			( 
				array (
				"cliente_id" => 405,
				"conexion_id" => 205,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3581,
				"anterior" => 3551,
				"basico" => 100,
				"excedente" => 100,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [161] = 
			( 
				array (
				"cliente_id" => 406,
				"conexion_id" => 206,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3699,
				"anterior" => 3692,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 8274.42
				)
			);
			$array_mediciones [162] = 
			( 
				array (
				"cliente_id" => 407,
				"conexion_id" => 207,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4288,
				"anterior" => 4256,
				"basico" => 100,
				"excedente" => 110,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 5062.28
				)
			);
			$array_mediciones [163] = 
			( 
				array (
				"cliente_id" => 408,
				"conexion_id" => 208,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1572,
				"anterior" => 1530,
				"basico" => 100,
				"excedente" => 160,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [164] = 
			( 
				array (
				"cliente_id" => 409,
				"conexion_id" => 209,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5310,
				"anterior" => 5306,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 954.37
				)
			);
			$array_mediciones [165] = 
			( 
				array (
				"cliente_id" => 410,
				"conexion_id" => 210,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8819,
				"anterior" => 8819,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 445.9
				)
			);
			$array_mediciones [166] = 
			( 
				array (
				"cliente_id" => 411,
				"conexion_id" => 211,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5020,
				"anterior" => 4949,
				"basico" => 100,
				"excedente" => 305,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [167] = 
			( 
				array (
				"cliente_id" => 412,
				"conexion_id" => 212,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4472,
				"anterior" => 4425,
				"basico" => 100,
				"excedente" => 185,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [168] = 
			( 
				array (
				"cliente_id" => 413,
				"conexion_id" => 213,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4065,
				"anterior" => 4021,
				"basico" => 100,
				"excedente" => 170,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 150.73
				)
			);
			$array_mediciones [169] = 
			( 
				array (
				"cliente_id" => 414,
				"conexion_id" => 214,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4182,
				"anterior" => 4163,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 752.73
				)
			);
			$array_mediciones [170] = 
			( 
				array (
				"cliente_id" => 415,
				"conexion_id" => 215,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4334,
				"anterior" => 4281,
				"basico" => 100,
				"excedente" => 215,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 740.84
				)
			);
			$array_mediciones [171] = 
			( 
				array (
				"cliente_id" => 416,
				"conexion_id" => 216,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2564,
				"anterior" => 2531,
				"basico" => 100,
				"excedente" => 115,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 160.37
				)
			);
			$array_mediciones [172] = 
			( 
				array (
				"cliente_id" => 417,
				"conexion_id" => 217,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3475,
				"anterior" => 3456,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [173] = 
			( 
				array (
				"cliente_id" => 418,
				"conexion_id" => 218,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3411,
				"anterior" => 3390,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [174] = 
			( 
				array (
				"cliente_id" => 419,
				"conexion_id" => 219,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4390,
				"anterior" => 4360,
				"basico" => 100,
				"excedente" => 100,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2619.32
				)
			);
			$array_mediciones [175] = 
			( 
				array (
				"cliente_id" => 420,
				"conexion_id" => 220,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2709,
				"anterior" => 2707,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [176] = 
			( 
				array (
				"cliente_id" => 421,
				"conexion_id" => 221,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2162,
				"anterior" => 2147,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [177] = 
			( 
				array (
				"cliente_id" => 422,
				"conexion_id" => 222,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2631,
				"anterior" => 2621,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [178] = 
			( 
				array (
				"cliente_id" => 423,
				"conexion_id" => 223,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4481,
				"anterior" => 4318,
				"basico" => 100,
				"excedente" => 765,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [179] = 
			( 
				array (
				"cliente_id" => 424,
				"conexion_id" => 224,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4361,
				"anterior" => 4353,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [180] = 
			( 
				array (
				"cliente_id" => 425,
				"conexion_id" => 225,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2692,
				"anterior" => 2684,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [181] = 
			( 
				array (
				"cliente_id" => 733,
				"conexion_id" => 226,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5126,
				"anterior" => 5090,
				"basico" => 100,
				"excedente" => 130,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [182] = 
			( 
				array (
				"cliente_id" => 427,
				"conexion_id" => 227,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3559,
				"anterior" => 3526,
				"basico" => 100,
				"excedente" => 115,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [183] = 
			( 
				array (
				"cliente_id" => 428,
				"conexion_id" => 228,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5490,
				"anterior" => 5433,
				"basico" => 100,
				"excedente" => 235,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [184] = 
			( 
				array (
				"cliente_id" => 429,
				"conexion_id" => 229,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2308,
				"anterior" => 2307,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [185] = 
			( 
				array (
				"cliente_id" => 430,
				"conexion_id" => 230,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3138,
				"anterior" => 3134,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [186] = 
			( 
				array (
				"cliente_id" => 431,
				"conexion_id" => 231,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5501,
				"anterior" => 5491,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [187] = 
			( 
				array (
				"cliente_id" => 760,
				"conexion_id" => 232,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4746,
				"anterior" => 4726,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [188] = 
			( 
				array (
				"cliente_id" => 433,
				"conexion_id" => 233,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4543,
				"anterior" => 4537,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 476.73
				)
			);
			$array_mediciones [189] = 
			( 
				array (
				"cliente_id" => 434,
				"conexion_id" => 234,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1702,
				"anterior" => 1694,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 394.88
				)
			);
			$array_mediciones [190] = 
			( 
				array (
				"cliente_id" => 435,
				"conexion_id" => 235,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4925,
				"anterior" => 4864,
				"basico" => 100,
				"excedente" => 255,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [191] = 
			( 
				array (
				"cliente_id" => 436,
				"conexion_id" => 236,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1460,
				"anterior" => 1449,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [192] = 
			( 
				array (
				"cliente_id" => 437,
				"conexion_id" => 237,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3730,
				"anterior" => 3721,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [193] = 
			( 
				array (
				"cliente_id" => 438,
				"conexion_id" => 238,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4622,
				"anterior" => 4574,
				"basico" => 100,
				"excedente" => 190,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0.43
				)
			);
			$array_mediciones [194] = 
			( 
				array (
				"cliente_id" => 439,
				"conexion_id" => 239,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5518,
				"anterior" => 5480,
				"basico" => 100,
				"excedente" => 140,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [195] = 
			( 
				array (
				"cliente_id" => 440,
				"conexion_id" => 240,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5468,
				"anterior" => 5397,
				"basico" => 100,
				"excedente" => 305,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [196] = 
			( 
				array (
				"cliente_id" => 441,
				"conexion_id" => 241,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2342,
				"anterior" => 2322,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [197] = 
			( 
				array (
				"cliente_id" => 442,
				"conexion_id" => 242,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4174,
				"anterior" => 4130,
				"basico" => 100,
				"excedente" => 170,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [198] = 
			( 
				array (
				"cliente_id" => 443,
				"conexion_id" => 243,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5000,
				"anterior" => 4981,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3902.05
				)
			);
			$array_mediciones [199] = 
			( 
				array (
				"cliente_id" => 444,
				"conexion_id" => 244,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4378,
				"anterior" => 4341,
				"basico" => 100,
				"excedente" => 135,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [200] = 
			( 
				array (
				"cliente_id" => 445,
				"conexion_id" => 245,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5742,
				"anterior" => 5720,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 706.44
				)
			);
			$array_mediciones [201] = 
			( 
				array (
				"cliente_id" => 446,
				"conexion_id" => 246,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4441,
				"anterior" => 4401,
				"basico" => 100,
				"excedente" => 150,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 24299.02
				)
			);
			$array_mediciones [202] = 
			( 
				array (
				"cliente_id" => 447,
				"conexion_id" => 247,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8610,
				"anterior" => 8587,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 22495.32
				)
			);
			$array_mediciones [203] = 
			( 
				array (
				"cliente_id" => 448,
				"conexion_id" => 248,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4445,
				"anterior" => 4434,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [204] = 
			( 
				array (
				"cliente_id" => 449,
				"conexion_id" => 249,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4285,
				"anterior" => 4204,
				"basico" => 100,
				"excedente" => 355,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 131.44
				)
			);
			$array_mediciones [205] = 
			( 
				array (
				"cliente_id" => 450,
				"conexion_id" => 250,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4789,
				"anterior" => 4762,
				"basico" => 100,
				"excedente" => 85,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [206] = 
			( 
				array (
				"cliente_id" => 451,
				"conexion_id" => 251,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3941,
				"anterior" => 3924,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [207] = 
			( 
				array (
				"cliente_id" => 452,
				"conexion_id" => 252,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7005,
				"anterior" => 6988,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [208] = 
			( 
				array (
				"cliente_id" => 453,
				"conexion_id" => 253,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4994,
				"anterior" => 4874,
				"basico" => 100,
				"excedente" => 550,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [209] = 
			( 
				array (
				"cliente_id" => 454,
				"conexion_id" => 254,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2010,
				"anterior" => 1988,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [210] = 
			( 
				array (
				"cliente_id" => 455,
				"conexion_id" => 255,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5220,
				"anterior" => 5177,
				"basico" => 100,
				"excedente" => 165,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 286.5
				)
			);
			$array_mediciones [211] = 
			( 
				array (
				"cliente_id" => 456,
				"conexion_id" => 256,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4923,
				"anterior" => 4886,
				"basico" => 100,
				"excedente" => 135,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [212] = 
			( 
				array (
				"cliente_id" => 457,
				"conexion_id" => 257,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6416,
				"anterior" => 6407,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [213] = 
			( 
				array (
				"cliente_id" => 458,
				"conexion_id" => 258,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 195,
				"anterior" => 111,
				"basico" => 100,
				"excedente" => 370,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [214] = 
			( 
				array (
				"cliente_id" => 459,
				"conexion_id" => 259,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5034,
				"anterior" => 5034,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [215] = 
			( 
				array (
				"cliente_id" => 460,
				"conexion_id" => 260,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 999,
				"anterior" => 958,
				"basico" => 100,
				"excedente" => 155,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [216] = 
			( 
				array (
				"cliente_id" => 461,
				"conexion_id" => 261,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5399,
				"anterior" => 5349,
				"basico" => 100,
				"excedente" => 200,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [217] = 
			( 
				array (
				"cliente_id" => 462,
				"conexion_id" => 262,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5402,
				"anterior" => 5377,
				"basico" => 100,
				"excedente" => 75,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [218] = 
			( 
				array (
				"cliente_id" => 463,
				"conexion_id" => 263,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5409,
				"anterior" => 5402,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [219] = 
			( 
				array (
				"cliente_id" => 464,
				"conexion_id" => 264,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4811,
				"anterior" => 4770,
				"basico" => 100,
				"excedente" => 155,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 730.01
				)
			);
			$array_mediciones [220] = 
			( 
				array (
				"cliente_id" => 465,
				"conexion_id" => 265,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5852,
				"anterior" => 5800,
				"basico" => 100,
				"excedente" => 210,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [221] = 
			( 
				array (
				"cliente_id" => 466,
				"conexion_id" => 266,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3030,
				"anterior" => 3021,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [222] = 
			( 
				array (
				"cliente_id" => 467,
				"conexion_id" => 267,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3765,
				"anterior" => 3756,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1836.83
				)
			);
			$array_mediciones [223] = 
			( 
				array (
				"cliente_id" => 468,
				"conexion_id" => 268,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2572,
				"anterior" => 2561,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 141.09
				)
			);
			$array_mediciones [224] = 
			( 
				array (
				"cliente_id" => 469,
				"conexion_id" => 269,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5469,
				"anterior" => 5431,
				"basico" => 100,
				"excedente" => 140,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [225] = 
			( 
				array (
				"cliente_id" => 470,
				"conexion_id" => 270,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3777,
				"anterior" => 3770,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [226] = 
			( 
				array (
				"cliente_id" => 471,
				"conexion_id" => 271,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5193,
				"anterior" => 5164,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 21592.06
				)
			);
			$array_mediciones [227] = 
			( 
				array (
				"cliente_id" => 472,
				"conexion_id" => 272,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5741,
				"anterior" => 5722,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2030.43
				)
			);
			$array_mediciones [228] = 
			( 
				array (
				"cliente_id" => 473,
				"conexion_id" => 273,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5483,
				"anterior" => 5446,
				"basico" => 100,
				"excedente" => 135,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 14854.93
				)
			);
			$array_mediciones [229] = 
			( 
				array (
				"cliente_id" => 474,
				"conexion_id" => 274,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4072,
				"anterior" => 4068,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 247.52
				)
			);
			$array_mediciones [230] = 
			( 
				array (
				"cliente_id" => 475,
				"conexion_id" => 275,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5004,
				"anterior" => 5004,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [231] = 
			( 
				array (
				"cliente_id" => 476,
				"conexion_id" => 276,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1219,
				"anterior" => 1179,
				"basico" => 100,
				"excedente" => 150,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [232] = 
			( 
				array (
				"cliente_id" => 477,
				"conexion_id" => 277,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3090,
				"anterior" => 3086,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [233] = 
			( 
				array (
				"cliente_id" => 478,
				"conexion_id" => 278,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3760,
				"anterior" => 3730,
				"basico" => 100,
				"excedente" => 100,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [234] = 
			( 
				array (
				"cliente_id" => 479,
				"conexion_id" => 279,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5588,
				"anterior" => 5559,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [236] = 
			( 
				array (
				"cliente_id" => 134,
				"conexion_id" => 281,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5833,
				"anterior" => 5450,
				"basico" => 100,
				"excedente" => 1865,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [237] = 
			( 
				array (
				"cliente_id" => 499,
				"conexion_id" => 283,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3383,
				"anterior" => 3370,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 170.01
				)
			);
			$array_mediciones [238] = 
			( 
				array (
				"cliente_id" => 498,
				"conexion_id" => 284,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 1532,
				"anterior" => 1469,
				"basico" => 220,
				"excedente" => 384,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 0
				)
			);
			$array_mediciones [239] = 
			( 
				array (
				"cliente_id" => 607,
				"conexion_id" => 285,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7315,
				"anterior" => 7315,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [240] = 
			( 
				array (
				"cliente_id" => 624,
				"conexion_id" => 287,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3237,
				"anterior" => 3205,
				"basico" => 100,
				"excedente" => 110,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 8152.74
				)
			);
			$array_mediciones [241] = 
			( 
				array (
				"cliente_id" => 611,
				"conexion_id" => 288,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5017,
				"anterior" => 5017,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2700.76
				)
			);
			$array_mediciones [242] = 
			( 
				array (
				"cliente_id" => 608,
				"conexion_id" => 289,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3697,
				"anterior" => 3367,
				"basico" => 100,
				"excedente" => 1600,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [243] = 
			( 
				array (
				"cliente_id" => 614,
				"conexion_id" => 290,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1086,
				"anterior" => 1072,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [244] = 
			( 
				array (
				"cliente_id" => 618,
				"conexion_id" => 291,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1197,
				"anterior" => 1184,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [245] = 
			( 
				array (
				"cliente_id" => 619,
				"conexion_id" => 292,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4270,
				"anterior" => 4255,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3270.41
				)
			);
			$array_mediciones [246] = 
			( 
				array (
				"cliente_id" => 620,
				"conexion_id" => 293,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1317,
				"anterior" => 1317,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 8320.16
				)
			);
			$array_mediciones [247] = 
			( 
				array (
				"cliente_id" => 184,
				"conexion_id" => 294,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5099,
				"anterior" => 5088,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 135
				)
			);
			$array_mediciones [248] = 
			( 
				array (
				"cliente_id" => 621,
				"conexion_id" => 295,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4786,
				"anterior" => 4745,
				"basico" => 100,
				"excedente" => 155,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [249] = 
			( 
				array (
				"cliente_id" => 767,
				"conexion_id" => 296,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2361,
				"anterior" => 2361,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [250] = 
			( 
				array (
				"cliente_id" => 607,
				"conexion_id" => 297,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6586,
				"anterior" => 6586,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [251] = 
			( 
				array (
				"cliente_id" => 625,
				"conexion_id" => 298,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4043,
				"anterior" => 4043,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [253] = 
			( 
				array (
				"cliente_id" => 319,
				"conexion_id" => 301,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6169,
				"anterior" => 6161,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [254] = 
			( 
				array (
				"cliente_id" => 272,
				"conexion_id" => 302,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2961,
				"anterior" => 2955,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 823.22
				)
			);
			$array_mediciones [255] = 
			( 
				array (
				"cliente_id" => 206,
				"conexion_id" => 303,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2843,
				"anterior" => 2835,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [256] = 
			( 
				array (
				"cliente_id" => 724,
				"conexion_id" => 304,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2601,
				"anterior" => 2566,
				"basico" => 100,
				"excedente" => 125,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2257.99
				)
			);
			$array_mediciones [257] = 
			( 
				array (
				"cliente_id" => 207,
				"conexion_id" => 305,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3924,
				"anterior" => 3911,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 262.89
				)
			);
			$array_mediciones [258] = 
			( 
				array (
				"cliente_id" => 234,
				"conexion_id" => 306,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6791,
				"anterior" => 6765,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3316.35
				)
			);
			$array_mediciones [259] = 
			( 
				array (
				"cliente_id" => 253,
				"conexion_id" => 307,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4703,
				"anterior" => 4693,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 6569.09
				)
			);
			$array_mediciones [260] = 
			( 
				array (
				"cliente_id" => 295,
				"conexion_id" => 308,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6621,
				"anterior" => 6596,
				"basico" => 100,
				"excedente" => 75,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [261] = 
			( 
				array (
				"cliente_id" => 318,
				"conexion_id" => 309,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5543,
				"anterior" => 5523,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 471.62
				)
			);
			$array_mediciones [262] = 
			( 
				array (
				"cliente_id" => 226,
				"conexion_id" => 310,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4133,
				"anterior" => 4110,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [263] = 
			( 
				array (
				"cliente_id" => 309,
				"conexion_id" => 311,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3767,
				"anterior" => 3738,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [264] = 
			( 
				array (
				"cliente_id" => 306,
				"conexion_id" => 312,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 7133,
				"anterior" => 7106,
				"basico" => 100,
				"excedente" => 85,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 383.42
				)
			);
			$array_mediciones [265] = 
			( 
				array (
				"cliente_id" => 245,
				"conexion_id" => 313,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3409,
				"anterior" => 3402,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [266] = 
			( 
				array (
				"cliente_id" => 369,
				"conexion_id" => 314,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6981,
				"anterior" => 6952,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 4064.98
				)
			);
			$array_mediciones [267] = 
			( 
				array (
				"cliente_id" => 488,
				"conexion_id" => 315,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4327,
				"anterior" => 4308,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 320.74
				)
			);
			$array_mediciones [268] = 
			( 
				array (
				"cliente_id" => 481,
				"conexion_id" => 316,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6595,
				"anterior" => 6576,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3030.04
				)
			);
			$array_mediciones [269] = 
			( 
				array (
				"cliente_id" => 273,
				"conexion_id" => 317,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4484,
				"anterior" => 4461,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [270] = 
			( 
				array (
				"cliente_id" => 320,
				"conexion_id" => 318,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6934,
				"anterior" => 6910,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [271] = 
			( 
				array (
				"cliente_id" => 274,
				"conexion_id" => 319,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2897,
				"anterior" => 2889,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [272] = 
			( 
				array (
				"cliente_id" => 298,
				"conexion_id" => 320,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2637,
				"anterior" => 2633,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2070.15
				)
			);
			$array_mediciones [273] = 
			( 
				array (
				"cliente_id" => 204,
				"conexion_id" => 321,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4173,
				"anterior" => 4161,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1155.16
				)
			);
			$array_mediciones [274] = 
			( 
				array (
				"cliente_id" => 492,
				"conexion_id" => 322,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3237,
				"anterior" => 3216,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [275] = 
			( 
				array (
				"cliente_id" => 294,
				"conexion_id" => 323,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3268,
				"anterior" => 3240,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 905.6
				)
			);
			$array_mediciones [276] = 
			( 
				array (
				"cliente_id" => 255,
				"conexion_id" => 324,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4741,
				"anterior" => 4733,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 7871.23
				)
			);
			$array_mediciones [277] = 
			( 
				array (
				"cliente_id" => 214,
				"conexion_id" => 325,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6375,
				"anterior" => 6339,
				"basico" => 100,
				"excedente" => 130,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [278] = 
			( 
				array (
				"cliente_id" => 322,
				"conexion_id" => 326,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4105,
				"anterior" => 4063,
				"basico" => 100,
				"excedente" => 160,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1356.37
				)
			);
			$array_mediciones [279] = 
			( 
				array (
				"cliente_id" => 288,
				"conexion_id" => 327,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2744,
				"anterior" => 2732,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [280] = 
			( 
				array (
				"cliente_id" => 275,
				"conexion_id" => 328,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2991,
				"anterior" => 2984,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [281] = 
			( 
				array (
				"cliente_id" => 243,
				"conexion_id" => 329,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2338,
				"anterior" => 2310,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [282] = 
			( 
				array (
				"cliente_id" => 239,
				"conexion_id" => 330,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1363,
				"anterior" => 1357,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [283] = 
			( 
				array (
				"cliente_id" => 260,
				"conexion_id" => 331,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3136,
				"anterior" => 3118,
				"basico" => 100,
				"excedente" => 40,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 621.88
				)
			);
			$array_mediciones [284] = 
			( 
				array (
				"cliente_id" => 375,
				"conexion_id" => 332,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1564,
				"anterior" => 1558,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 376.36
				)
			);
			$array_mediciones [285] = 
			( 
				array (
				"cliente_id" => 321,
				"conexion_id" => 333,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2697,
				"anterior" => 2690,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [286] = 
			( 
				array (
				"cliente_id" => 262,
				"conexion_id" => 334,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3048,
				"anterior" => 3039,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3347.57
				)
			);
			$array_mediciones [287] = 
			( 
				array (
				"cliente_id" => 723,
				"conexion_id" => 335,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2676,
				"anterior" => 2676,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 185.75
				)
			);
			$array_mediciones [288] = 
			( 
				array (
				"cliente_id" => 203,
				"conexion_id" => 336,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3607,
				"anterior" => 3544,
				"basico" => 100,
				"excedente" => 265,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 5609.55
				)
			);
			$array_mediciones [289] = 
			( 
				array (
				"cliente_id" => 251,
				"conexion_id" => 337,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3081,
				"anterior" => 3067,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 907.13
				)
			);
			$array_mediciones [290] = 
			( 
				array (
				"cliente_id" => 290,
				"conexion_id" => 338,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3168,
				"anterior" => 3159,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [291] = 
			( 
				array (
				"cliente_id" => 201,
				"conexion_id" => 339,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3002,
				"anterior" => 2989,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [292] = 
			( 
				array (
				"cliente_id" => 725,
				"conexion_id" => 340,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4387,
				"anterior" => 4364,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [293] = 
			( 
				array (
				"cliente_id" => 218,
				"conexion_id" => 341,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4945,
				"anterior" => 4919,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [294] = 
			( 
				array (
				"cliente_id" => 311,
				"conexion_id" => 342,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2486,
				"anterior" => 2466,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 393.06
				)
			);
			$array_mediciones [295] = 
			( 
				array (
				"cliente_id" => 256,
				"conexion_id" => 343,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5488,
				"anterior" => 5445,
				"basico" => 100,
				"excedente" => 165,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 364.13
				)
			);
			$array_mediciones [296] = 
			( 
				array (
				"cliente_id" => 265,
				"conexion_id" => 344,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4962,
				"anterior" => 4945,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [297] = 
			( 
				array (
				"cliente_id" => 282,
				"conexion_id" => 345,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5641,
				"anterior" => 5617,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1174.66
				)
			);
			$array_mediciones [298] = 
			( 
				array (
				"cliente_id" => 484,
				"conexion_id" => 346,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3346,
				"anterior" => 3320,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 4998.05
				)
			);
			$array_mediciones [299] = 
			( 
				array (
				"cliente_id" => 248,
				"conexion_id" => 347,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2894,
				"anterior" => 2889,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [300] = 
			( 
				array (
				"cliente_id" => 307,
				"conexion_id" => 348,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2658,
				"anterior" => 2642,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [301] = 
			( 
				array (
				"cliente_id" => 211,
				"conexion_id" => 349,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3211,
				"anterior" => 3197,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [302] = 
			( 
				array (
				"cliente_id" => 312,
				"conexion_id" => 350,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3708,
				"anterior" => 3692,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 277.35
				)
			);
			$array_mediciones [303] = 
			( 
				array (
				"cliente_id" => 496,
				"conexion_id" => 351,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4785,
				"anterior" => 4764,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 744.68
				)
			);
			$array_mediciones [304] = 
			( 
				array (
				"cliente_id" => 482,
				"conexion_id" => 352,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1365,
				"anterior" => 1344,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 679.41
				)
			);
			$array_mediciones [305] = 
			( 
				array (
				"cliente_id" => 329,
				"conexion_id" => 353,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4362,
				"anterior" => 4337,
				"basico" => 100,
				"excedente" => 75,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [306] = 
			( 
				array (
				"cliente_id" => 293,
				"conexion_id" => 354,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3735,
				"anterior" => 3714,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 368.95
				)
			);
			$array_mediciones [307] = 
			( 
				array (
				"cliente_id" => 489,
				"conexion_id" => 355,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6181,
				"anterior" => 6164,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1306.5
				)
			);
			$array_mediciones [308] = 
			( 
				array (
				"cliente_id" => 238,
				"conexion_id" => 356,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2967,
				"anterior" => 2956,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1508.2
				)
			);
			$array_mediciones [309] = 
			( 
				array (
				"cliente_id" => 304,
				"conexion_id" => 357,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2288,
				"anterior" => 2272,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [310] = 
			( 
				array (
				"cliente_id" => 281,
				"conexion_id" => 358,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3599,
				"anterior" => 3587,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 267.71
				)
			);
			$array_mediciones [311] = 
			( 
				array (
				"cliente_id" => 314,
				"conexion_id" => 359,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3428,
				"anterior" => 3405,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 523.83
				)
			);
			$array_mediciones [312] = 
			( 
				array (
				"cliente_id" => 230,
				"conexion_id" => 360,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2620,
				"anterior" => 2588,
				"basico" => 100,
				"excedente" => 110,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 330.38
				)
			);
			$array_mediciones [313] = 
			( 
				array (
				"cliente_id" => 212,
				"conexion_id" => 361,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3162,
				"anterior" => 3154,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [314] = 
			( 
				array (
				"cliente_id" => 483,
				"conexion_id" => 362,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3837,
				"anterior" => 3836,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [315] = 
			( 
				array (
				"cliente_id" => 649,
				"conexion_id" => 363,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2985,
				"anterior" => 2969,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 335.2
				)
			);
			$array_mediciones [316] = 
			( 
				array (
				"cliente_id" => 744,
				"conexion_id" => 364,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4409,
				"anterior" => 4383,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 383.42
				)
			);
			$array_mediciones [317] = 
			( 
				array (
				"cliente_id" => 240,
				"conexion_id" => 365,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4241,
				"anterior" => 4222,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2464.27
				)
			);
			$array_mediciones [318] = 
			( 
				array (
				"cliente_id" => 247,
				"conexion_id" => 366,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1628,
				"anterior" => 1611,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 272.53
				)
			);
			$array_mediciones [319] = 
			( 
				array (
				"cliente_id" => 237,
				"conexion_id" => 367,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5310,
				"anterior" => 5266,
				"basico" => 100,
				"excedente" => 170,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1113.9
				)
			);
			$array_mediciones [320] = 
			( 
				array (
				"cliente_id" => 242,
				"conexion_id" => 368,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2956,
				"anterior" => 2937,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 258.06
				)
			);
			$array_mediciones [321] = 
			( 
				array (
				"cliente_id" => 215,
				"conexion_id" => 369,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4757,
				"anterior" => 4695,
				"basico" => 100,
				"excedente" => 260,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 16026.54
				)
			);
			$array_mediciones [322] = 
			( 
				array (
				"cliente_id" => 300,
				"conexion_id" => 370,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6231,
				"anterior" => 6225,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [323] = 
			( 
				array (
				"cliente_id" => 280,
				"conexion_id" => 371,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2831,
				"anterior" => 2822,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [324] = 
			( 
				array (
				"cliente_id" => 249,
				"conexion_id" => 372,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2695,
				"anterior" => 2694,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1508.98
				)
			);
			$array_mediciones [325] = 
			( 
				array (
				"cliente_id" => 236,
				"conexion_id" => 373,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3183,
				"anterior" => 3175,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [326] = 
			( 
				array (
				"cliente_id" => 276,
				"conexion_id" => 374,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3232,
				"anterior" => 3225,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [327] = 
			( 
				array (
				"cliente_id" => 494,
				"conexion_id" => 375,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4764,
				"anterior" => 4720,
				"basico" => 100,
				"excedente" => 170,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1263.07
				)
			);
			$array_mediciones [328] = 
			( 
				array (
				"cliente_id" => 316,
				"conexion_id" => 376,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4365,
				"anterior" => 4347,
				"basico" => 100,
				"excedente" => 40,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1089.92
				)
			);
			$array_mediciones [329] = 
			( 
				array (
				"cliente_id" => 250,
				"conexion_id" => 377,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3870,
				"anterior" => 3849,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3218.77
				)
			);
			$array_mediciones [330] = 
			( 
				array (
				"cliente_id" => 261,
				"conexion_id" => 378,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4371,
				"anterior" => 4363,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [331] = 
			( 
				array (
				"cliente_id" => 292,
				"conexion_id" => 379,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2171,
				"anterior" => 2153,
				"basico" => 100,
				"excedente" => 40,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [332] = 
			( 
				array (
				"cliente_id" => 269,
				"conexion_id" => 380,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1720,
				"anterior" => 1714,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [333] = 
			( 
				array (
				"cliente_id" => 208,
				"conexion_id" => 381,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3338,
				"anterior" => 3325,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [334] = 
			( 
				array (
				"cliente_id" => 493,
				"conexion_id" => 382,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2308,
				"anterior" => 2299,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [335] = 
			( 
				array (
				"cliente_id" => 291,
				"conexion_id" => 383,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4487,
				"anterior" => 4466,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1310.07
				)
			);
			$array_mediciones [336] = 
			( 
				array (
				"cliente_id" => 491,
				"conexion_id" => 384,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4149,
				"anterior" => 4128,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [337] = 
			( 
				array (
				"cliente_id" => 305,
				"conexion_id" => 385,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1832,
				"anterior" => 1827,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [338] = 
			( 
				array (
				"cliente_id" => 227,
				"conexion_id" => 386,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4507,
				"anterior" => 4488,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1912.4
				)
			);
			$array_mediciones [339] = 
			( 
				array (
				"cliente_id" => 220,
				"conexion_id" => 387,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2367,
				"anterior" => 2360,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 185.75
				)
			);
			$array_mediciones [340] = 
			( 
				array (
				"cliente_id" => 302,
				"conexion_id" => 388,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4522,
				"anterior" => 4508,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [341] = 
			( 
				array (
				"cliente_id" => 485,
				"conexion_id" => 389,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2572,
				"anterior" => 2559,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [342] = 
			( 
				array (
				"cliente_id" => 222,
				"conexion_id" => 390,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4143,
				"anterior" => 4116,
				"basico" => 100,
				"excedente" => 85,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1224.07
				)
			);
			$array_mediciones [343] = 
			( 
				array (
				"cliente_id" => 327,
				"conexion_id" => 391,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1668,
				"anterior" => 1667,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [344] = 
			( 
				array (
				"cliente_id" => 271,
				"conexion_id" => 392,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3719,
				"anterior" => 3688,
				"basico" => 100,
				"excedente" => 105,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 7514.57
				)
			);
			$array_mediciones [345] = 
			( 
				array (
				"cliente_id" => 267,
				"conexion_id" => 393,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2839,
				"anterior" => 2839,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 376.36
				)
			);
			$array_mediciones [346] = 
			( 
				array (
				"cliente_id" => 224,
				"conexion_id" => 394,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2388,
				"anterior" => 2362,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 238.55
				)
			);
			$array_mediciones [347] = 
			( 
				array (
				"cliente_id" => 310,
				"conexion_id" => 395,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6295,
				"anterior" => 6268,
				"basico" => 100,
				"excedente" => 85,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 17468.54
				)
			);
			$array_mediciones [348] = 
			( 
				array (
				"cliente_id" => 323,
				"conexion_id" => 396,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 8153,
				"anterior" => 8134,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1545.43
				)
			);
			$array_mediciones [349] = 
			( 
				array (
				"cliente_id" => 490,
				"conexion_id" => 397,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 152,
				"anterior" => 138,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [350] = 
			( 
				array (
				"cliente_id" => 297,
				"conexion_id" => 398,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2793,
				"anterior" => 2781,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [351] = 
			( 
				array (
				"cliente_id" => 264,
				"conexion_id" => 399,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6551,
				"anterior" => 6523,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 4209.25
				)
			);
			$array_mediciones [352] = 
			( 
				array (
				"cliente_id" => 268,
				"conexion_id" => 400,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2336,
				"anterior" => 2324,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 607.65
				)
			);
			$array_mediciones [353] = 
			( 
				array (
				"cliente_id" => 233,
				"conexion_id" => 401,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3445,
				"anterior" => 3443,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [354] = 
			( 
				array (
				"cliente_id" => 317,
				"conexion_id" => 402,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3046,
				"anterior" => 3037,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2710.72
				)
			);
			$array_mediciones [355] = 
			( 
				array (
				"cliente_id" => 263,
				"conexion_id" => 403,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2415,
				"anterior" => 2399,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 447.41
				)
			);
			$array_mediciones [356] = 
			( 
				array (
				"cliente_id" => 202,
				"conexion_id" => 404,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3613,
				"anterior" => 3608,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1935.43
				)
			);
			$array_mediciones [357] = 
			( 
				array (
				"cliente_id" => 257,
				"conexion_id" => 405,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2422,
				"anterior" => 2409,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [358] = 
			( 
				array (
				"cliente_id" => 324,
				"conexion_id" => 406,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4515,
				"anterior" => 4504,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 4600.89
				)
			);
			$array_mediciones [359] = 
			( 
				array (
				"cliente_id" => 266,
				"conexion_id" => 407,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3830,
				"anterior" => 3820,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [360] = 
			( 
				array (
				"cliente_id" => 229,
				"conexion_id" => 408,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2418,
				"anterior" => 2410,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [361] = 
			( 
				array (
				"cliente_id" => 330,
				"conexion_id" => 409,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3194,
				"anterior" => 3185,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [362] = 
			( 
				array (
				"cliente_id" => 205,
				"conexion_id" => 410,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3659,
				"anterior" => 3642,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [363] = 
			( 
				array (
				"cliente_id" => 717,
				"conexion_id" => 411,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2609,
				"anterior" => 2593,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 282.17
				)
			);
			$array_mediciones [364] = 
			( 
				array (
				"cliente_id" => 315,
				"conexion_id" => 412,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2171,
				"anterior" => 2142,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [365] = 
			( 
				array (
				"cliente_id" => 296,
				"conexion_id" => 413,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4200,
				"anterior" => 4177,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [366] = 
			( 
				array (
				"cliente_id" => 615,
				"conexion_id" => 415,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3961,
				"anterior" => 3949,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 251.97
				)
			);
			$array_mediciones [367] = 
			( 
				array (
				"cliente_id" => 626,
				"conexion_id" => 416,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5433,
				"anterior" => 5431,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 4620.03
				)
			);
			$array_mediciones [368] = 
			( 
				array (
				"cliente_id" => 310,
				"conexion_id" => 417,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1232,
				"anterior" => 1219,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 386.33
				)
			);
			$array_mediciones [369] = 
			( 
				array (
				"cliente_id" => 631,
				"conexion_id" => 420,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2573,
				"anterior" => 2563,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [370] = 
			( 
				array (
				"cliente_id" => 632,
				"conexion_id" => 421,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2006,
				"anterior" => 1984,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [371] = 
			( 
				array (
				"cliente_id" => 635,
				"conexion_id" => 422,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3150,
				"anterior" => 3121,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [372] = 
			( 
				array (
				"cliente_id" => 634,
				"conexion_id" => 423,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2272,
				"anterior" => 2255,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [373] = 
			( 
				array (
				"cliente_id" => 633,
				"conexion_id" => 424,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2388,
				"anterior" => 2361,
				"basico" => 100,
				"excedente" => 85,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [374] = 
			( 
				array (
				"cliente_id" => 636,
				"conexion_id" => 425,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2451,
				"anterior" => 2424,
				"basico" => 100,
				"excedente" => 85,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 218.23
				)
			);
			$array_mediciones [375] = 
			( 
				array (
				"cliente_id" => 637,
				"conexion_id" => 426,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1281,
				"anterior" => 1268,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [376] = 
			( 
				array (
				"cliente_id" => 638,
				"conexion_id" => 427,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1745,
				"anterior" => 1720,
				"basico" => 100,
				"excedente" => 75,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [377] = 
			( 
				array (
				"cliente_id" => 639,
				"conexion_id" => 428,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1748,
				"anterior" => 1734,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 145.91
				)
			);
			$array_mediciones [378] = 
			( 
				array (
				"cliente_id" => 640,
				"conexion_id" => 429,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3416,
				"anterior" => 3377,
				"basico" => 100,
				"excedente" => 145,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 227.87
				)
			);
			$array_mediciones [379] = 
			( 
				array (
				"cliente_id" => 641,
				"conexion_id" => 430,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2038,
				"anterior" => 2023,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [380] = 
			( 
				array (
				"cliente_id" => 642,
				"conexion_id" => 431,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2116,
				"anterior" => 2100,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [381] = 
			( 
				array (
				"cliente_id" => 643,
				"conexion_id" => 432,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2851,
				"anterior" => 2822,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [382] = 
			( 
				array (
				"cliente_id" => 644,
				"conexion_id" => 433,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1437,
				"anterior" => 1428,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [383] = 
			( 
				array (
				"cliente_id" => 645,
				"conexion_id" => 434,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2207,
				"anterior" => 2197,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 384.95
				)
			);
			$array_mediciones [384] = 
			( 
				array (
				"cliente_id" => 646,
				"conexion_id" => 435,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2264,
				"anterior" => 2244,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 367.87
				)
			);
			$array_mediciones [385] = 
			( 
				array (
				"cliente_id" => 647,
				"conexion_id" => 436,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1843,
				"anterior" => 1830,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 131.44
				)
			);
			$array_mediciones [386] = 
			( 
				array (
				"cliente_id" => 648,
				"conexion_id" => 437,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1811,
				"anterior" => 1791,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 208.58
				)
			);
			$array_mediciones [387] = 
			( 
				array (
				"cliente_id" => 704,
				"conexion_id" => 438,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2437,
				"anterior" => 2399,
				"basico" => 100,
				"excedente" => 140,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1465.95
				)
			);
			$array_mediciones [388] = 
			( 
				array (
				"cliente_id" => 705,
				"conexion_id" => 439,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 980,
				"anterior" => 973,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 656.85
				)
			);
			$array_mediciones [389] = 
			( 
				array (
				"cliente_id" => 706,
				"conexion_id" => 440,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1597,
				"anterior" => 1567,
				"basico" => 100,
				"excedente" => 100,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 658.29
				)
			);
			$array_mediciones [390] = 
			( 
				array (
				"cliente_id" => 700,
				"conexion_id" => 441,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1443,
				"anterior" => 1430,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [391] = 
			( 
				array (
				"cliente_id" => 707,
				"conexion_id" => 442,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6155,
				"anterior" => 6053,
				"basico" => 100,
				"excedente" => 460,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 628.03
				)
			);
			$array_mediciones [392] = 
			( 
				array (
				"cliente_id" => 708,
				"conexion_id" => 443,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1546,
				"anterior" => 1546,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 184.48
				)
			);
			$array_mediciones [393] = 
			( 
				array (
				"cliente_id" => 709,
				"conexion_id" => 444,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 710,
				"anterior" => 694,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [394] = 
			( 
				array (
				"cliente_id" => 710,
				"conexion_id" => 445,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3076,
				"anterior" => 3063,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 288.12
				)
			);
			$array_mediciones [395] = 
			( 
				array (
				"cliente_id" => 711,
				"conexion_id" => 446,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2873,
				"anterior" => 2815,
				"basico" => 100,
				"excedente" => 240,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [396] = 
			( 
				array (
				"cliente_id" => 712,
				"conexion_id" => 447,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1120,
				"anterior" => 1105,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [397] = 
			( 
				array (
				"cliente_id" => 713,
				"conexion_id" => 448,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1240,
				"anterior" => 1233,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 277.97
				)
			);
			$array_mediciones [398] = 
			( 
				array (
				"cliente_id" => 697,
				"conexion_id" => 449,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3287,
				"anterior" => 3216,
				"basico" => 100,
				"excedente" => 305,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [399] = 
			( 
				array (
				"cliente_id" => 698,
				"conexion_id" => 450,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1853,
				"anterior" => 1836,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 307.87
				)
			);
			$array_mediciones [400] = 
			( 
				array (
				"cliente_id" => 699,
				"conexion_id" => 451,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2659,
				"anterior" => 2627,
				"basico" => 100,
				"excedente" => 110,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 750.13
				)
			);
			$array_mediciones [401] = 
			( 
				array (
				"cliente_id" => 663,
				"conexion_id" => 452,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3912,
				"anterior" => 3888,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 160.37
				)
			);
			$array_mediciones [402] = 
			( 
				array (
				"cliente_id" => 664,
				"conexion_id" => 453,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2629,
				"anterior" => 2605,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 227.87
				)
			);
			$array_mediciones [403] = 
			( 
				array (
				"cliente_id" => 665,
				"conexion_id" => 454,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1910,
				"anterior" => 1887,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [404] = 
			( 
				array (
				"cliente_id" => 666,
				"conexion_id" => 455,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 670,
				"anterior" => 667,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [405] = 
			( 
				array (
				"cliente_id" => 667,
				"conexion_id" => 456,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2752,
				"anterior" => 2686,
				"basico" => 100,
				"excedente" => 280,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 976.89
				)
			);
			$array_mediciones [406] = 
			( 
				array (
				"cliente_id" => 668,
				"conexion_id" => 457,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1721,
				"anterior" => 1703,
				"basico" => 100,
				"excedente" => 40,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [407] = 
			( 
				array (
				"cliente_id" => 669,
				"conexion_id" => 458,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2144,
				"anterior" => 2126,
				"basico" => 100,
				"excedente" => 40,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3342.38
				)
			);
			$array_mediciones [408] = 
			( 
				array (
				"cliente_id" => 670,
				"conexion_id" => 459,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1954,
				"anterior" => 1941,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [409] = 
			( 
				array (
				"cliente_id" => 671,
				"conexion_id" => 460,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2789,
				"anterior" => 2784,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 9179.84
				)
			);
			$array_mediciones [410] = 
			( 
				array (
				"cliente_id" => 672,
				"conexion_id" => 461,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1367,
				"anterior" => 1347,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 309.83
				)
			);
			$array_mediciones [411] = 
			( 
				array (
				"cliente_id" => 673,
				"conexion_id" => 462,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2082,
				"anterior" => 2068,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 141.09
				)
			);
			$array_mediciones [412] = 
			( 
				array (
				"cliente_id" => 674,
				"conexion_id" => 463,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1036,
				"anterior" => 1030,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3552.83
				)
			);
			$array_mediciones [413] = 
			( 
				array (
				"cliente_id" => 675,
				"conexion_id" => 464,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1096,
				"anterior" => 1083,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 8321.66
				)
			);
			$array_mediciones [414] = 
			( 
				array (
				"cliente_id" => 676,
				"conexion_id" => 465,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 862,
				"anterior" => 824,
				"basico" => 100,
				"excedente" => 140,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3666.01
				)
			);
			$array_mediciones [415] = 
			( 
				array (
				"cliente_id" => 677,
				"conexion_id" => 466,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2223,
				"anterior" => 2187,
				"basico" => 100,
				"excedente" => 130,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [416] = 
			( 
				array (
				"cliente_id" => 678,
				"conexion_id" => 467,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1534,
				"anterior" => 1526,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [417] = 
			( 
				array (
				"cliente_id" => 679,
				"conexion_id" => 468,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1716,
				"anterior" => 1688,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [418] = 
			( 
				array (
				"cliente_id" => 680,
				"conexion_id" => 469,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3649,
				"anterior" => 3600,
				"basico" => 100,
				"excedente" => 195,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 256.8
				)
			);
			$array_mediciones [419] = 
			( 
				array (
				"cliente_id" => 681,
				"conexion_id" => 470,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2640,
				"anterior" => 2609,
				"basico" => 100,
				"excedente" => 105,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3080.09
				)
			);
			$array_mediciones [420] = 
			( 
				array (
				"cliente_id" => 682,
				"conexion_id" => 471,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2078,
				"anterior" => 2050,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [421] = 
			( 
				array (
				"cliente_id" => 683,
				"conexion_id" => 472,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1139,
				"anterior" => 1134,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [422] = 
			( 
				array (
				"cliente_id" => 693,
				"conexion_id" => 476,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2302,
				"anterior" => 2278,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [423] = 
			( 
				array (
				"cliente_id" => 694,
				"conexion_id" => 477,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1234,
				"anterior" => 1208,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [424] = 
			( 
				array (
				"cliente_id" => 651,
				"conexion_id" => 478,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3096,
				"anterior" => 3087,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 550.29
				)
			);
			$array_mediciones [425] = 
			( 
				array (
				"cliente_id" => 652,
				"conexion_id" => 479,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 301,
				"anterior" => 298,
				"basico" => 220,
				"excedente" => 0,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 0
				)
			);
			$array_mediciones [426] = 
			( 
				array (
				"cliente_id" => 1046,
				"conexion_id" => 480,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 908,
				"anterior" => 907,
				"basico" => 220,
				"excedente" => 0,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 237.51
				)
			);
			$array_mediciones [427] = 
			( 
				array (
				"cliente_id" => 703,
				"conexion_id" => 481,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 317,
				"anterior" => 317,
				"basico" => 220,
				"excedente" => 0,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 237.51
				)
			);
			$array_mediciones [428] = 
			( 
				array (
				"cliente_id" => 735,
				"conexion_id" => 482,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1867,
				"anterior" => 1854,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [429] = 
			( 
				array (
				"cliente_id" => 736,
				"conexion_id" => 483,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1631,
				"anterior" => 1626,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1330.01
				)
			);
			$array_mediciones [430] = 
			( 
				array (
				"cliente_id" => 737,
				"conexion_id" => 484,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1649,
				"anterior" => 1641,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [431] = 
			( 
				array (
				"cliente_id" => 738,
				"conexion_id" => 485,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2279,
				"anterior" => 2263,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 6328.74
				)
			);
			$array_mediciones [432] = 
			( 
				array (
				"cliente_id" => 174,
				"conexion_id" => 486,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 633,
				"anterior" => 633,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [433] = 
			( 
				array (
				"cliente_id" => 741,
				"conexion_id" => 487,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4997,
				"anterior" => 4713,
				"basico" => 100,
				"excedente" => 1370,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [434] = 
			( 
				array (
				"cliente_id" => 766,
				"conexion_id" => 489,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2159,
				"anterior" => 2147,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1919.26
				)
			);
			$array_mediciones [435] = 
			( 
				array (
				"cliente_id" => 756,
				"conexion_id" => 490,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1960,
				"anterior" => 1933,
				"basico" => 100,
				"excedente" => 85,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [436] = 
			( 
				array (
				"cliente_id" => 757,
				"conexion_id" => 491,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1106,
				"anterior" => 1102,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [437] = 
			( 
				array (
				"cliente_id" => 758,
				"conexion_id" => 493,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 80,
				"anterior" => 80,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3237.38
				)
			);
			$array_mediciones [438] = 
			( 
				array (
				"cliente_id" => 759,
				"conexion_id" => 494,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1783,
				"anterior" => 1776,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 9003.92
				)
			);
			$array_mediciones [439] = 
			( 
				array (
				"cliente_id" => 500,
				"conexion_id" => 500,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2856,
				"anterior" => 2820,
				"basico" => 100,
				"excedente" => 130,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [440] = 
			( 
				array (
				"cliente_id" => 501,
				"conexion_id" => 501,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2924,
				"anterior" => 2924,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [441] = 
			( 
				array (
				"cliente_id" => 502,
				"conexion_id" => 502,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2296,
				"anterior" => 2270,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [442] = 
			( 
				array (
				"cliente_id" => 503,
				"conexion_id" => 503,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 609,
				"anterior" => 597,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 472.32
				)
			);
			$array_mediciones [443] = 
			( 
				array (
				"cliente_id" => 504,
				"conexion_id" => 504,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2327,
				"anterior" => 2259,
				"basico" => 100,
				"excedente" => 290,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [444] = 
			( 
				array (
				"cliente_id" => 505,
				"conexion_id" => 505,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1816,
				"anterior" => 1784,
				"basico" => 100,
				"excedente" => 110,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 329.52
				)
			);
			$array_mediciones [445] = 
			( 
				array (
				"cliente_id" => 506,
				"conexion_id" => 506,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2662,
				"anterior" => 2641,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [446] = 
			( 
				array (
				"cliente_id" => 507,
				"conexion_id" => 507,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2302,
				"anterior" => 2301,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [447] = 
			( 
				array (
				"cliente_id" => 508,
				"conexion_id" => 508,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4299,
				"anterior" => 4284,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [448] = 
			( 
				array (
				"cliente_id" => 509,
				"conexion_id" => 509,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 144,
				"anterior" => 131,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [449] = 
			( 
				array (
				"cliente_id" => 510,
				"conexion_id" => 510,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4786,
				"anterior" => 4726,
				"basico" => 100,
				"excedente" => 250,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1837.65
				)
			);
			$array_mediciones [450] = 
			( 
				array (
				"cliente_id" => 511,
				"conexion_id" => 511,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3577,
				"anterior" => 3528,
				"basico" => 100,
				"excedente" => 195,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [451] = 
			( 
				array (
				"cliente_id" => 512,
				"conexion_id" => 512,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1436,
				"anterior" => 1436,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 375.01
				)
			);
			$array_mediciones [452] = 
			( 
				array (
				"cliente_id" => 513,
				"conexion_id" => 513,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3899,
				"anterior" => 3899,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [453] = 
			( 
				array (
				"cliente_id" => 514,
				"conexion_id" => 514,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 618,
				"anterior" => 618,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 42914.57
				)
			);
			$array_mediciones [454] = 
			( 
				array (
				"cliente_id" => 515,
				"conexion_id" => 515,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 440,
				"anterior" => 404,
				"basico" => 100,
				"excedente" => 130,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 5131.95
				)
			);
			$array_mediciones [455] = 
			( 
				array (
				"cliente_id" => 720,
				"conexion_id" => 516,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 6662,
				"anterior" => 6593,
				"basico" => 100,
				"excedente" => 295,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [456] = 
			( 
				array (
				"cliente_id" => 517,
				"conexion_id" => 517,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5198,
				"anterior" => 5158,
				"basico" => 100,
				"excedente" => 150,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [457] = 
			( 
				array (
				"cliente_id" => 518,
				"conexion_id" => 518,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 153,
				"anterior" => 153,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [458] = 
			( 
				array (
				"cliente_id" => 519,
				"conexion_id" => 519,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4840,
				"anterior" => 4730,
				"basico" => 100,
				"excedente" => 500,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 5835.98
				)
			);
			$array_mediciones [459] = 
			( 
				array (
				"cliente_id" => 739,
				"conexion_id" => 520,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2263,
				"anterior" => 2217,
				"basico" => 100,
				"excedente" => 180,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [460] = 
			( 
				array (
				"cliente_id" => 687,
				"conexion_id" => 521,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 584,
				"anterior" => 538,
				"basico" => 100,
				"excedente" => 180,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [461] = 
			( 
				array (
				"cliente_id" => 522,
				"conexion_id" => 522,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4225,
				"anterior" => 4176,
				"basico" => 100,
				"excedente" => 195,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [462] = 
			( 
				array (
				"cliente_id" => 523,
				"conexion_id" => 523,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2387,
				"anterior" => 2322,
				"basico" => 100,
				"excedente" => 275,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3780.44
				)
			);
			$array_mediciones [463] = 
			( 
				array (
				"cliente_id" => 524,
				"conexion_id" => 524,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1969,
				"anterior" => 1967,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 271.98
				)
			);
			$array_mediciones [464] = 
			( 
				array (
				"cliente_id" => 525,
				"conexion_id" => 525,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5460,
				"anterior" => 5415,
				"basico" => 100,
				"excedente" => 175,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [465] = 
			( 
				array (
				"cliente_id" => 526,
				"conexion_id" => 526,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4803,
				"anterior" => 4769,
				"basico" => 100,
				"excedente" => 120,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2657.15
				)
			);
			$array_mediciones [466] = 
			( 
				array (
				"cliente_id" => 740,
				"conexion_id" => 527,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2850,
				"anterior" => 2815,
				"basico" => 100,
				"excedente" => 125,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [467] = 
			( 
				array (
				"cliente_id" => 528,
				"conexion_id" => 528,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2622,
				"anterior" => 2601,
				"basico" => 100,
				"excedente" => 55,
				"importe" => 100,
				"mts" => 10,-
				"deuda" => 0.78
				)
			);
			$array_mediciones [468] = 
			( 
				array (
				"cliente_id" => 530,
				"conexion_id" => 530,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2029,
				"anterior" => 2016,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 13315.54
				)
			);
			$array_mediciones [469] = 
			( 
				array (
				"cliente_id" => 531,
				"conexion_id" => 531,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5308,
				"anterior" => 5283,
				"basico" => 100,
				"excedente" => 75,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 411.36
				)
			);
			$array_mediciones [470] = 
			( 
				array (
				"cliente_id" => 721,
				"conexion_id" => 532,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3421,
				"anterior" => 3415,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 886.9
				)
			);
			$array_mediciones [471] = 
			( 
				array (
				"cliente_id" => 533,
				"conexion_id" => 533,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2316,
				"anterior" => 2291,
				"basico" => 100,
				"excedente" => 75,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [472] = 
			( 
				array (
				"cliente_id" => 534,
				"conexion_id" => 534,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2327,
				"anterior" => 2321,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 317.65
				)
			);
			$array_mediciones [473] = 
			( 
				array (
				"cliente_id" => 535,
				"conexion_id" => 535,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2169,
				"anterior" => 2169,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [474] = 
			( 
				array (
				"cliente_id" => 536,
				"conexion_id" => 536,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2999,
				"anterior" => 2968,
				"basico" => 100,
				"excedente" => 105,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [475] = 
			( 
				array (
				"cliente_id" => 537,
				"conexion_id" => 537,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1033,
				"anterior" => 1028,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [476] = 
			( 
				array (
				"cliente_id" => 562,
				"conexion_id" => 538,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5037,
				"anterior" => 4942,
				"basico" => 100,
				"excedente" => 425,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 12252.77
				)
			);
			$array_mediciones [477] = 
			( 
				array (
				"cliente_id" => 563,
				"conexion_id" => 539,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 742,
				"anterior" => 742,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [478] = 
			( 
				array (
				"cliente_id" => 544,
				"conexion_id" => 540,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2175,
				"anterior" => 2152,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3567.49
				)
			);
			$array_mediciones [479] = 
			( 
				array (
				"cliente_id" => 544,
				"conexion_id" => 540,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2175,
				"anterior" => 2152,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3567.49
				)
			);
			$array_mediciones [480] = 
			( 
				array (
				"cliente_id" => 561,
				"conexion_id" => 541,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2020,
				"anterior" => 2007,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 395.06
				)
			);
			$array_mediciones [481] = 
			( 
				array (
				"cliente_id" => 747,
				"conexion_id" => 542,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2595,
				"anterior" => 2570,
				"basico" => 100,
				"excedente" => 75,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 436.67
				)
			);
			$array_mediciones [482] = 
			( 
				array (
				"cliente_id" => 543,
				"conexion_id" => 543,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1854,
				"anterior" => 1847,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [483] = 
			( 
				array (
				"cliente_id" => 601,
				"conexion_id" => 544,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3957,
				"anterior" => 3918,
				"basico" => 100,
				"excedente" => 145,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [484] = 
			( 
				array (
				"cliente_id" => 602,
				"conexion_id" => 545,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4694,
				"anterior" => 4650,
				"basico" => 100,
				"excedente" => 170,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1237.24
				)
			);
			$array_mediciones [485] = 
			( 
				array (
				"cliente_id" => 540,
				"conexion_id" => 546,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2754,
				"anterior" => 2731,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [486] = 
			( 
				array (
				"cliente_id" => 541,
				"conexion_id" => 547,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2524,
				"anterior" => 2519,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [487] = 
			( 
				array (
				"cliente_id" => 542,
				"conexion_id" => 549,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4132,
				"anterior" => 4072,
				"basico" => 100,
				"excedente" => 250,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [488] = 
			( 
				array (
				"cliente_id" => 600,
				"conexion_id" => 550,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2299,
				"anterior" => 2270,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [489] = 
			( 
				array (
				"cliente_id" => 768,
				"conexion_id" => 551,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2589,
				"anterior" => 2565,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [490] = 
			( 
				array (
				"cliente_id" => 539,
				"conexion_id" => 552,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2158,
				"anterior" => 2146,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3331.51
				)
			);
			$array_mediciones [491] = 
			( 
				array (
				"cliente_id" => 597,
				"conexion_id" => 553,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3517,
				"anterior" => 3498,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 126.62
				)
			);
			$array_mediciones [492] = 
			( 
				array (
				"cliente_id" => 596,
				"conexion_id" => 554,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2433,
				"anterior" => 2433,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [493] = 
			( 
				array (
				"cliente_id" => 538,
				"conexion_id" => 555,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2255,
				"anterior" => 2177,
				"basico" => 100,
				"excedente" => 340,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [494] = 
			( 
				array (
				"cliente_id" => 603,
				"conexion_id" => 556,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1095,
				"anterior" => 1095,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [495] = 
			( 
				array (
				"cliente_id" => 564,
				"conexion_id" => 557,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3309,
				"anterior" => 3309,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 885.43
				)
			);
			$array_mediciones [496] = 
			( 
				array (
				"cliente_id" => 593,
				"conexion_id" => 558,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2275,
				"anterior" => 2275,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 509.63
				)
			);
			$array_mediciones [497] = 
			( 
				array (
				"cliente_id" => 599,
				"conexion_id" => 559,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3325,
				"anterior" => 3289,
				"basico" => 100,
				"excedente" => 130,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 535.24
				)
			);
			$array_mediciones [498] = 
			( 
				array (
				"cliente_id" => 606,
				"conexion_id" => 560,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3304,
				"anterior" => 3295,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [499] = 
			( 
				array (
				"cliente_id" => 545,
				"conexion_id" => 561,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2832,
				"anterior" => 2820,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 198.94
				)
			);
			$array_mediciones [500] = 
			( 
				array (
				"cliente_id" => 546,
				"conexion_id" => 562,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2325,
				"anterior" => 2298,
				"basico" => 100,
				"excedente" => 85,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [501] = 
			( 
				array (
				"cliente_id" => 591,
				"conexion_id" => 563,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2670,
				"anterior" => 2670,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [502] = 
			( 
				array (
				"cliente_id" => 592,
				"conexion_id" => 564,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1837,
				"anterior" => 1837,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [503] = 
			( 
				array (
				"cliente_id" => 761,
				"conexion_id" => 565,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2113,
				"anterior" => 2080,
				"basico" => 100,
				"excedente" => 115,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 136.26
				)
			);
			$array_mediciones [504] = 
			( 
				array (
				"cliente_id" => 547,
				"conexion_id" => 566,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1310,
				"anterior" => 1294,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 214.93
				)
			);
			$array_mediciones [505] = 
			( 
				array (
				"cliente_id" => 566,
				"conexion_id" => 567,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3137,
				"anterior" => 3137,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [506] = 
			( 
				array (
				"cliente_id" => 567,
				"conexion_id" => 568,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 827,
				"anterior" => 817,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [507] = 
			( 
				array (
				"cliente_id" => 568,
				"conexion_id" => 569,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3429,
				"anterior" => 3367,
				"basico" => 100,
				"excedente" => 260,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [508] = 
			( 
				array (
				"cliente_id" => 569,
				"conexion_id" => 570,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1901,
				"anterior" => 1901,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [509] = 
			( 
				array (
				"cliente_id" => 570,
				"conexion_id" => 571,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2818,
				"anterior" => 2818,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [510] = 
			( 
				array (
				"cliente_id" => 548,
				"conexion_id" => 572,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1980,
				"anterior" => 1980,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [511] = 
			( 
				array (
				"cliente_id" => 549,
				"conexion_id" => 573,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 89,
				"anterior" => 39,
				"basico" => 100,
				"excedente" => 200,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [512] = 
			( 
				array (
				"cliente_id" => 550,
				"conexion_id" => 574,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2645,
				"anterior" => 2641,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 763.17
				)
			);
			$array_mediciones [513] = 
			( 
				array (
				"cliente_id" => 605,
				"conexion_id" => 575,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3612,
				"anterior" => 3577,
				"basico" => 100,
				"excedente" => 125,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 4172.22
				)
			);
			$array_mediciones [514] = 
			( 
				array (
				"cliente_id" => 571,
				"conexion_id" => 576,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3611,
				"anterior" => 3591,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3219.78
				)
			);
			$array_mediciones [515] = 
			( 
				array (
				"cliente_id" => 572,
				"conexion_id" => 577,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3636,
				"anterior" => 3618,
				"basico" => 100,
				"excedente" => 40,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [516] = 
			( 
				array (
				"cliente_id" => 573,
				"conexion_id" => 578,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3084,
				"anterior" => 3055,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [517] = 
			( 
				array (
				"cliente_id" => 574,
				"conexion_id" => 579,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3776,
				"anterior" => 3773,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [518] = 
			( 
				array (
				"cliente_id" => 575,
				"conexion_id" => 580,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3039,
				"anterior" => 3006,
				"basico" => 100,
				"excedente" => 115,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 136.26
				)
			);
			$array_mediciones [519] = 
			( 
				array (
				"cliente_id" => 576,
				"conexion_id" => 581,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2501,
				"anterior" => 2501,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [520] = 
			( 
				array (
				"cliente_id" => 551,
				"conexion_id" => 582,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3493,
				"anterior" => 3460,
				"basico" => 100,
				"excedente" => 115,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [521] = 
			( 
				array (
				"cliente_id" => 552,
				"conexion_id" => 583,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2797,
				"anterior" => 2703,
				"basico" => 100,
				"excedente" => 420,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 815.15
				)
			);
			$array_mediciones [522] = 
			( 
				array (
				"cliente_id" => 604,
				"conexion_id" => 584,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2525,
				"anterior" => 2491,
				"basico" => 100,
				"excedente" => 120,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 5689.9
				)
			);
			$array_mediciones [523] = 
			( 
				array (
				"cliente_id" => 594,
				"conexion_id" => 585,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3251,
				"anterior" => 3218,
				"basico" => 100,
				"excedente" => 115,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1043.73
				)
			);
			$array_mediciones [524] = 
			( 
				array (
				"cliente_id" => 553,
				"conexion_id" => 586,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3663,
				"anterior" => 3632,
				"basico" => 100,
				"excedente" => 105,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [525] = 
			( 
				array (
				"cliente_id" => 577,
				"conexion_id" => 587,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 978,
				"anterior" => 942,
				"basico" => 100,
				"excedente" => 130,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [526] = 
			( 
				array (
				"cliente_id" => 578,
				"conexion_id" => 588,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2866,
				"anterior" => 2860,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [527] = 
			( 
				array (
				"cliente_id" => 598,
				"conexion_id" => 589,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3467,
				"anterior" => 3467,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [528] = 
			( 
				array (
				"cliente_id" => 595,
				"conexion_id" => 590,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3697,
				"anterior" => 3632,
				"basico" => 100,
				"excedente" => 275,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [529] = 
			( 
				array (
				"cliente_id" => 554,
				"conexion_id" => 591,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2787,
				"anterior" => 2764,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [530] = 
			( 
				array (
				"cliente_id" => 579,
				"conexion_id" => 592,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3066,
				"anterior" => 3052,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 379.99
				)
			);
			$array_mediciones [531] = 
			( 
				array (
				"cliente_id" => 580,
				"conexion_id" => 593,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3768,
				"anterior" => 3746,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 688.07
				)
			);
			$array_mediciones [532] = 
			( 
				array (
				"cliente_id" => 581,
				"conexion_id" => 594,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 594,
				"anterior" => 568,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [533] = 
			( 
				array (
				"cliente_id" => 582,
				"conexion_id" => 595,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4692,
				"anterior" => 3654,
				"basico" => 100,
				"excedente" => 5140,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [534] = 
			( 
				array (
				"cliente_id" => 583,
				"conexion_id" => 596,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3258,
				"anterior" => 3228,
				"basico" => 100,
				"excedente" => 100,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 889.05
				)
			);
			$array_mediciones [535] = 
			( 
				array (
				"cliente_id" => 584,
				"conexion_id" => 597,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1974,
				"anterior" => 1931,
				"basico" => 100,
				"excedente" => 165,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [536] = 
			( 
				array (
				"cliente_id" => 555,
				"conexion_id" => 598,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3483,
				"anterior" => 3434,
				"basico" => 100,
				"excedente" => 195,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [537] = 
			( 
				array (
				"cliente_id" => 556,
				"conexion_id" => 599,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3742,
				"anterior" => 3695,
				"basico" => 100,
				"excedente" => 185,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [538] = 
			( 
				array (
				"cliente_id" => 585,
				"conexion_id" => 600,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2072,
				"anterior" => 2026,
				"basico" => 100,
				"excedente" => 180,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [539] = 
			( 
				array (
				"cliente_id" => 557,
				"conexion_id" => 601,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3522,
				"anterior" => 3492,
				"basico" => 100,
				"excedente" => 100,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [540] = 
			( 
				array (
				"cliente_id" => 586,
				"conexion_id" => 602,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1653,
				"anterior" => 1636,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [541] = 
			( 
				array (
				"cliente_id" => 587,
				"conexion_id" => 603,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3136,
				"anterior" => 3136,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [542] = 
			( 
				array (
				"cliente_id" => 588,
				"conexion_id" => 604,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1291,
				"anterior" => 1272,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 754.32
				)
			);
			$array_mediciones [543] = 
			( 
				array (
				"cliente_id" => 589,
				"conexion_id" => 605,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2165,
				"anterior" => 2159,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [544] = 
			( 
				array (
				"cliente_id" => 590,
				"conexion_id" => 606,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2217,
				"anterior" => 2197,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 861.59
				)
			);
			$array_mediciones [545] = 
			( 
				array (
				"cliente_id" => 628,
				"conexion_id" => 609,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1866,
				"anterior" => 1854,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [546] = 
			( 
				array (
				"cliente_id" => 629,
				"conexion_id" => 610,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2722,
				"anterior" => 2722,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [547] = 
			( 
				array (
				"cliente_id" => 630,
				"conexion_id" => 611,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3289,
				"anterior" => 3274,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 15424.11
				)
			);
			$array_mediciones [548] = 
			( 
				array (
				"cliente_id" => 316,
				"conexion_id" => 612,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2290,
				"anterior" => 2283,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 429.65
				)
			);
			$array_mediciones [549] = 
			( 
				array (
				"cliente_id" => 688,
				"conexion_id" => 613,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1990,
				"anterior" => 1979,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [550] = 
			( 
				array (
				"cliente_id" => 689,
				"conexion_id" => 614,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1988,
				"anterior" => 1974,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1962.62
				)
			);
			$array_mediciones [551] = 
			( 
				array (
				"cliente_id" => 690,
				"conexion_id" => 615,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1930,
				"anterior" => 1915,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2976.35
				)
			);
			$array_mediciones [552] = 
			( 
				array (
				"cliente_id" => 726,
				"conexion_id" => 616,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1688,
				"anterior" => 1675,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [553] = 
			( 
				array (
				"cliente_id" => 692,
				"conexion_id" => 617,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1716,
				"anterior" => 1691,
				"basico" => 100,
				"excedente" => 75,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 723
				)
			);
			$array_mediciones [554] = 
			( 
				array (
				"cliente_id" => 695,
				"conexion_id" => 618,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3592,
				"anterior" => 3574,
				"basico" => 100,
				"excedente" => 40,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 6642.87
				)
			);
			$array_mediciones [555] = 
			( 
				array (
				"cliente_id" => 696,
				"conexion_id" => 619,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 3706,
				"anterior" => 3694,
				"basico" => 220,
				"excedente" => 0,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 0
				)
			);
			$array_mediciones [556] = 
			( 
				array (
				"cliente_id" => 714,
				"conexion_id" => 620,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2351,
				"anterior" => 2335,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 482.28
				)
			);
			$array_mediciones [557] = 
			( 
				array (
				"cliente_id" => 715,
				"conexion_id" => 621,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 492,
				"anterior" => 490,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [558] = 
			( 
				array (
				"cliente_id" => 716,
				"conexion_id" => 622,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1122,
				"anterior" => 1111,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [559] = 
			( 
				array (
				"cliente_id" => 718,
				"conexion_id" => 623,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1551,
				"anterior" => 1551,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [560] = 
			( 
				array (
				"cliente_id" => 728,
				"conexion_id" => 624,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1971,
				"anterior" => 1960,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [561] = 
			( 
				array (
				"cliente_id" => 729,
				"conexion_id" => 625,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 5902,
				"anterior" => 5902,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2375.93
				)
			);
			$array_mediciones [562] = 
			( 
				array (
				"cliente_id" => 730,
				"conexion_id" => 626,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 959,
				"anterior" => 959,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);

			$array_mediciones [564] = 
			( 
				array (
				"cliente_id" => 731,
				"conexion_id" => 628,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 110,
				"anterior" => 71,
				"basico" => 100,
				"excedente" => 145,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [565] = 
			( 
				array (
				"cliente_id" => 732,
				"conexion_id" => 629,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2591,
				"anterior" => 2272,
				"basico" => 100,
				"excedente" => 1545,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 509.35
				)
			);
			$array_mediciones [566] = 
			( 
				array (
				"cliente_id" => 745,
				"conexion_id" => 630,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2846,
				"anterior" => 2830,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 9467.65
				)
			);

			$array_mediciones [568] = 
			( 
				array (
				"cliente_id" => 752,
				"conexion_id" => 632,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2490,
				"anterior" => 2477,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 5048.7
				)
			);
			$array_mediciones [569] = 
			( 
				array (
				"cliente_id" => 748,
				"conexion_id" => 633,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 449,
				"anterior" => 438,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3738.3
				)
			);
			$array_mediciones [570] = 
			( 
				array (
				"cliente_id" => 749,
				"conexion_id" => 634,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 4063,
				"anterior" => 3972,
				"basico" => 100,
				"excedente" => 405,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [571] = 
			( 
				array (
				"cliente_id" => 753,
				"conexion_id" => 635,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 59,
				"anterior" => 47,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [572] = 
			( 
				array (
				"cliente_id" => 762,
				"conexion_id" => 636,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1461,
				"anterior" => 1445,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [573] = 
			( 
				array (
				"cliente_id" => 763,
				"conexion_id" => 637,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 796,
				"anterior" => 788,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 160.37
				)
			);
			$array_mediciones [574] = 
			( 
				array (
				"cliente_id" => 764,
				"conexion_id" => 638,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2401,
				"anterior" => 2389,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1525.55
				)
			);
			$array_mediciones [575] = 
			( 
				array (
				"cliente_id" => 765,
				"conexion_id" => 639,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 451,
				"anterior" => 445,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [576] = 
			( 
				array (
				"cliente_id" => 236,
				"conexion_id" => 640,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1421,
				"anterior" => 1416,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [577] = 
			( 
				array (
				"cliente_id" => 777,
				"conexion_id" => 641,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 29987,
				"anterior" => 29718,
				"basico" => 220,
				"excedente" => 2032,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 5902.56
				)
			);
			$array_mediciones [578] = 
			( 
				array (
				"cliente_id" => 777,
				"conexion_id" => 642,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 45970,
				"anterior" => 44528,
				"basico" => 220,
				"excedente" => 11416,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 13717
				)
			);
			$array_mediciones [579] = 
			( 
				array (
				"cliente_id" => 770,
				"conexion_id" => 644,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2927,
				"anterior" => 2863,
				"basico" => 100,
				"excedente" => 270,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [581] = 
			( 
				array (
				"cliente_id" => 772,
				"conexion_id" => 664,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2610,
				"anterior" => 2594,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1700.42
				)
			);
			$array_mediciones [582] = 
			( 
				array (
				"cliente_id" => 771,
				"conexion_id" => 665,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 775,
				"anterior" => 750,
				"basico" => 100,
				"excedente" => 75,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2270.8
				)
			);
			$array_mediciones [583] = 
			( 
				array (
				"cliente_id" => 773,
				"conexion_id" => 667,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1215,
				"anterior" => 1205,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 223.05
				)
			);
			$array_mediciones [584] = 
			( 
				array (
				"cliente_id" => 774,
				"conexion_id" => 668,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 373,
				"anterior" => 355,
				"basico" => 100,
				"excedente" => 40,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [585] = 
			( 
				array (
				"cliente_id" => 778,
				"conexion_id" => 669,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 585,
				"anterior" => 584,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [586] = 
			( 
				array (
				"cliente_id" => 779,
				"conexion_id" => 670,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 190,
				"anterior" => 186,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 375.83
				)
			);
			$array_mediciones [587] = 
			( 
				array (
				"cliente_id" => 780,
				"conexion_id" => 671,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1411,
				"anterior" => 1361,
				"basico" => 100,
				"excedente" => 200,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 126.62
				)
			);
			$array_mediciones [588] = 
			( 
				array (
				"cliente_id" => 781,
				"conexion_id" => 672,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 995,
				"anterior" => 941,
				"basico" => 100,
				"excedente" => 220,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1241.08
				)
			);
			$array_mediciones [589] = 
			( 
				array (
				"cliente_id" => 782,
				"conexion_id" => 673,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 631,
				"anterior" => 595,
				"basico" => 100,
				"excedente" => 130,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [590] = 
			( 
				array (
				"cliente_id" => 783,
				"conexion_id" => 674,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1301,
				"anterior" => 1244,
				"basico" => 100,
				"excedente" => 235,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [591] = 
			( 
				array (
				"cliente_id" => 784,
				"conexion_id" => 675,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1292,
				"anterior" => 1273,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [592] = 
			( 
				array (
				"cliente_id" => 785,
				"conexion_id" => 676,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 668,
				"anterior" => 646,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [593] = 
			( 
				array (
				"cliente_id" => 786,
				"conexion_id" => 677,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 789,
				"anterior" => 740,
				"basico" => 100,
				"excedente" => 195,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [594] = 
			( 
				array (
				"cliente_id" => 787,
				"conexion_id" => 678,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 158,
				"anterior" => 157,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [595] = 
			( 
				array (
				"cliente_id" => 788,
				"conexion_id" => 679,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 766,
				"anterior" => 710,
				"basico" => 100,
				"excedente" => 230,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 6870.52
				)
			);
			$array_mediciones [596] = 
			( 
				array (
				"cliente_id" => 789,
				"conexion_id" => 680,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 955,
				"anterior" => 908,
				"basico" => 100,
				"excedente" => 185,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 278.47
				)
			);
			$array_mediciones [597] = 
			( 
				array (
				"cliente_id" => 790,
				"conexion_id" => 681,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 65,
				"anterior" => 65,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [598] = 
			( 
				array (
				"cliente_id" => 791,
				"conexion_id" => 682,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1986,
				"anterior" => 1742,
				"basico" => 100,
				"excedente" => 1170,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 7929.51
				)
			);
			$array_mediciones [599] = 
			( 
				array (
				"cliente_id" => 792,
				"conexion_id" => 683,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 723,
				"anterior" => 691,
				"basico" => 100,
				"excedente" => 110,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [600] = 
			( 
				array (
				"cliente_id" => 793,
				"conexion_id" => 684,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 732,
				"anterior" => 706,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [601] = 
			( 
				array (
				"cliente_id" => 794,
				"conexion_id" => 685,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1176,
				"anterior" => 1121,
				"basico" => 100,
				"excedente" => 225,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 257.3
				)
			);
			$array_mediciones [602] = 
			( 
				array (
				"cliente_id" => 795,
				"conexion_id" => 686,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2147,
				"anterior" => 2026,
				"basico" => 100,
				"excedente" => 555,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [603] = 
			( 
				array (
				"cliente_id" => 796,
				"conexion_id" => 687,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 884,
				"anterior" => 881,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [604] = 
			( 
				array (
				"cliente_id" => 797,
				"conexion_id" => 688,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 811,
				"anterior" => 771,
				"basico" => 100,
				"excedente" => 150,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 220.41
				)
			);
			$array_mediciones [605] = 
			( 
				array (
				"cliente_id" => 798,
				"conexion_id" => 689,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1942,
				"anterior" => 1839,
				"basico" => 100,
				"excedente" => 465,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 318.71
				)
			);
			$array_mediciones [606] = 
			( 
				array (
				"cliente_id" => 799,
				"conexion_id" => 690,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1233,
				"anterior" => 1106,
				"basico" => 100,
				"excedente" => 585,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1484.09
				)
			);
			$array_mediciones [607] = 
			( 
				array (
				"cliente_id" => 800,
				"conexion_id" => 691,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1705,
				"anterior" => 1600,
				"basico" => 100,
				"excedente" => 475,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1144.93
				)
			);
			$array_mediciones [608] = 
			( 
				array (
				"cliente_id" => 801,
				"conexion_id" => 692,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1184,
				"anterior" => 1074,
				"basico" => 100,
				"excedente" => 500,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 276.87
				)
			);
			$array_mediciones [609] = 
			( 
				array (
				"cliente_id" => 802,
				"conexion_id" => 693,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 519,
				"anterior" => 514,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 251.97
				)
			);
			$array_mediciones [610] = 
			( 
				array (
				"cliente_id" => 803,
				"conexion_id" => 694,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2127,
				"anterior" => 2023,
				"basico" => 100,
				"excedente" => 470,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 4211.13
				)
			);
			$array_mediciones [611] = 
			( 
				array (
				"cliente_id" => 804,
				"conexion_id" => 695,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1060,
				"anterior" => 1045,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 267.09
				)
			);
			$array_mediciones [612] = 
			( 
				array (
				"cliente_id" => 805,
				"conexion_id" => 696,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1056,
				"anterior" => 1033,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1993
				)
			);
			$array_mediciones [613] = 
			( 
				array (
				"cliente_id" => 806,
				"conexion_id" => 697,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 742,
				"anterior" => 739,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2401.35
				)
			);
			$array_mediciones [614] = 
			( 
				array (
				"cliente_id" => 807,
				"conexion_id" => 698,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 594,
				"anterior" => 585,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 145.91
				)
			);
			$array_mediciones [615] = 
			( 
				array (
				"cliente_id" => 808,
				"conexion_id" => 699,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1620,
				"anterior" => 1506,
				"basico" => 100,
				"excedente" => 520,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [616] = 
			( 
				array (
				"cliente_id" => 809,
				"conexion_id" => 700,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1697,
				"anterior" => 1601,
				"basico" => 100,
				"excedente" => 430,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 510.51
				)
			);
			$array_mediciones [617] = 
			( 
				array (
				"cliente_id" => 810,
				"conexion_id" => 701,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 860,
				"anterior" => 803,
				"basico" => 100,
				"excedente" => 235,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [618] = 
			( 
				array (
				"cliente_id" => 811,
				"conexion_id" => 702,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2259,
				"anterior" => 2042,
				"basico" => 100,
				"excedente" => 1035,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3884.32
				)
			);
			$array_mediciones [619] = 
			( 
				array (
				"cliente_id" => 812,
				"conexion_id" => 703,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1137,
				"anterior" => 1089,
				"basico" => 100,
				"excedente" => 190,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [620] = 
			( 
				array (
				"cliente_id" => 813,
				"conexion_id" => 704,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1513,
				"anterior" => 0,
				"basico" => 100,
				"excedente" => 7515,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [621] = 
			( 
				array (
				"cliente_id" => 814,
				"conexion_id" => 705,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 819,
				"anterior" => 806,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [622] = 
			( 
				array (
				"cliente_id" => 815,
				"conexion_id" => 706,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 368,
				"anterior" => 364,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [623] = 
			( 
				array (
				"cliente_id" => 816,
				"conexion_id" => 707,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 960,
				"anterior" => 912,
				"basico" => 100,
				"excedente" => 190,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 255.3
				)
			);
			$array_mediciones [624] = 
			( 
				array (
				"cliente_id" => 817,
				"conexion_id" => 708,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 460,
				"anterior" => 445,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [625] = 
			( 
				array (
				"cliente_id" => 818,
				"conexion_id" => 709,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1753,
				"anterior" => 1689,
				"basico" => 100,
				"excedente" => 270,
				"importe" => 100,
				"mts" => 10,-
				"deuda" => 323.65
				)
			);
			$array_mediciones [626] = 
			( 
				array (
				"cliente_id" => 819,
				"conexion_id" => 710,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1364,
				"anterior" => 1330,
				"basico" => 100,
				"excedente" => 120,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2738.38
				)
			);
			$array_mediciones [627] = 
			( 
				array (
				"cliente_id" => 820,
				"conexion_id" => 711,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 898,
				"anterior" => 863,
				"basico" => 100,
				"excedente" => 125,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [628] = 
			( 
				array (
				"cliente_id" => 821,
				"conexion_id" => 712,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1342,
				"anterior" => 1296,
				"basico" => 100,
				"excedente" => 180,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [629] = 
			( 
				array (
				"cliente_id" => 822,
				"conexion_id" => 713,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 891,
				"anterior" => 839,
				"basico" => 100,
				"excedente" => 210,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [630] = 
			( 
				array (
				"cliente_id" => 823,
				"conexion_id" => 714,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1106,
				"anterior" => 1087,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 155.55
				)
			);
			$array_mediciones [631] = 
			( 
				array (
				"cliente_id" => 824,
				"conexion_id" => 715,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1134,
				"anterior" => 1097,
				"basico" => 100,
				"excedente" => 135,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 7959.3
				)
			);
			$array_mediciones [632] = 
			( 
				array (
				"cliente_id" => 825,
				"conexion_id" => 716,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 249,
				"anterior" => 246,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [633] = 
			( 
				array (
				"cliente_id" => 826,
				"conexion_id" => 717,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 611,
				"anterior" => 594,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [634] = 
			( 
				array (
				"cliente_id" => 827,
				"conexion_id" => 718,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 856,
				"anterior" => 822,
				"basico" => 100,
				"excedente" => 120,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [635] = 
			( 
				array (
				"cliente_id" => 828,
				"conexion_id" => 719,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 869,
				"anterior" => 836,
				"basico" => 100,
				"excedente" => 115,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 4296.17
				)
			);
			$array_mediciones [636] = 
			( 
				array (
				"cliente_id" => 829,
				"conexion_id" => 720,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2267,
				"anterior" => 2141,
				"basico" => 100,
				"excedente" => 580,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [637] = 
			( 
				array (
				"cliente_id" => 830,
				"conexion_id" => 721,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1645,
				"anterior" => 1564,
				"basico" => 100,
				"excedente" => 355,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 449.73
				)
			);
			$array_mediciones [638] = 
			( 
				array (
				"cliente_id" => 831,
				"conexion_id" => 722,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1508,
				"anterior" => 1437,
				"basico" => 100,
				"excedente" => 305,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 602.12
				)
			);
			$array_mediciones [639] = 
			( 
				array (
				"cliente_id" => 832,
				"conexion_id" => 723,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1769,
				"anterior" => 1697,
				"basico" => 100,
				"excedente" => 310,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1989.89
				)
			);
			$array_mediciones [640] = 
			( 
				array (
				"cliente_id" => 833,
				"conexion_id" => 724,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 928,
				"anterior" => 868,
				"basico" => 100,
				"excedente" => 250,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 155.55
				)
			);
			$array_mediciones [641] = 
			( 
				array (
				"cliente_id" => 834,
				"conexion_id" => 725,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 942,
				"anterior" => 905,
				"basico" => 100,
				"excedente" => 135,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [642] = 
			( 
				array (
				"cliente_id" => 835,
				"conexion_id" => 726,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 444,
				"anterior" => 388,
				"basico" => 100,
				"excedente" => 230,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2665.71
				)
			);
			$array_mediciones [643] = 
			( 
				array (
				"cliente_id" => 836,
				"conexion_id" => 727,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 416,
				"anterior" => 412,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 860.66
				)
			);
			$array_mediciones [644] = 
			( 
				array (
				"cliente_id" => 837,
				"conexion_id" => 728,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1220,
				"anterior" => 1121,
				"basico" => 100,
				"excedente" => 445,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 7705.14
				)
			);
			$array_mediciones [645] = 
			( 
				array (
				"cliente_id" => 838,
				"conexion_id" => 729,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 751,
				"anterior" => 620,
				"basico" => 100,
				"excedente" => 605,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [646] = 
			( 
				array (
				"cliente_id" => 839,
				"conexion_id" => 730,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 765,
				"anterior" => 716,
				"basico" => 100,
				"excedente" => 195,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [647] = 
			( 
				array (
				"cliente_id" => 840,
				"conexion_id" => 731,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 915,
				"anterior" => 881,
				"basico" => 100,
				"excedente" => 120,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1157.43
				)
			);
			$array_mediciones [648] = 
			( 
				array (
				"cliente_id" => 841,
				"conexion_id" => 732,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1094,
				"anterior" => 1045,
				"basico" => 100,
				"excedente" => 195,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2821.64
				)
			);
			$array_mediciones [649] = 
			( 
				array (
				"cliente_id" => 842,
				"conexion_id" => 733,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 730,
				"anterior" => 706,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1438.52
				)
			);
			$array_mediciones [650] = 
			( 
				array (
				"cliente_id" => 843,
				"conexion_id" => 734,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 504,
				"anterior" => 448,
				"basico" => 100,
				"excedente" => 230,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [651] = 
			( 
				array (
				"cliente_id" => 844,
				"conexion_id" => 735,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1300,
				"anterior" => 1258,
				"basico" => 100,
				"excedente" => 160,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [652] = 
			( 
				array (
				"cliente_id" => 845,
				"conexion_id" => 736,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 976,
				"anterior" => 941,
				"basico" => 100,
				"excedente" => 125,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 738.18
				)
			);
			$array_mediciones [653] = 
			( 
				array (
				"cliente_id" => 846,
				"conexion_id" => 737,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 499,
				"anterior" => 470,
				"basico" => 100,
				"excedente" => 95,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [654] = 
			( 
				array (
				"cliente_id" => 847,
				"conexion_id" => 738,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 641,
				"anterior" => 584,
				"basico" => 100,
				"excedente" => 235,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [655] = 
			( 
				array (
				"cliente_id" => 848,
				"conexion_id" => 739,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 689,
				"anterior" => 657,
				"basico" => 100,
				"excedente" => 110,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [656] = 
			( 
				array (
				"cliente_id" => 849,
				"conexion_id" => 740,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1561,
				"anterior" => 1444,
				"basico" => 100,
				"excedente" => 535,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [657] = 
			( 
				array (
				"cliente_id" => 850,
				"conexion_id" => 741,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1340,
				"anterior" => 1335,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 6626.87
				)
			);
			$array_mediciones [658] = 
			( 
				array (
				"cliente_id" => 851,
				"conexion_id" => 742,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1009,
				"anterior" => 1004,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 9450.63
				)
			);
			$array_mediciones [659] = 
			( 
				array (
				"cliente_id" => 852,
				"conexion_id" => 743,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1017,
				"anterior" => 944,
				"basico" => 100,
				"excedente" => 315,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2901.69
				)
			);
			$array_mediciones [660] = 
			( 
				array (
				"cliente_id" => 853,
				"conexion_id" => 744,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 881,
				"anterior" => 819,
				"basico" => 100,
				"excedente" => 260,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 6157.86
				)
			);
			$array_mediciones [661] = 
			( 
				array (
				"cliente_id" => 854,
				"conexion_id" => 745,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1788,
				"anterior" => 1716,
				"basico" => 100,
				"excedente" => 310,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 291.16
				)
			);
			$array_mediciones [662] = 
			( 
				array (
				"cliente_id" => 855,
				"conexion_id" => 746,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 551,
				"anterior" => 517,
				"basico" => 100,
				"excedente" => 120,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 145.91
				)
			);
			$array_mediciones [663] = 
			( 
				array (
				"cliente_id" => 856,
				"conexion_id" => 747,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2127,
				"anterior" => 2045,
				"basico" => 100,
				"excedente" => 360,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3292.54
				)
			);
			$array_mediciones [664] = 
			( 
				array (
				"cliente_id" => 857,
				"conexion_id" => 748,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 895,
				"anterior" => 844,
				"basico" => 100,
				"excedente" => 205,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 546.25
				)
			);
			$array_mediciones [665] = 
			( 
				array (
				"cliente_id" => 858,
				"conexion_id" => 749,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 875,
				"anterior" => 830,
				"basico" => 100,
				"excedente" => 175,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 391.14
				)
			);
			$array_mediciones [666] = 
			( 
				array (
				"cliente_id" => 859,
				"conexion_id" => 750,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 840,
				"anterior" => 824,
				"basico" => 100,
				"excedente" => 30,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [667] = 
			( 
				array (
				"cliente_id" => 860,
				"conexion_id" => 751,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 817,
				"anterior" => 814,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 385.31
				)
			);
			$array_mediciones [668] = 
			( 
				array (
				"cliente_id" => 861,
				"conexion_id" => 752,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 612,
				"anterior" => 584,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [669] = 
			( 
				array (
				"cliente_id" => 862,
				"conexion_id" => 753,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 950,
				"anterior" => 943,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 475.46
				)
			);
			$array_mediciones [670] = 
			( 
				array (
				"cliente_id" => 863,
				"conexion_id" => 754,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 801,
				"anterior" => 750,
				"basico" => 100,
				"excedente" => 205,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [671] = 
			( 
				array (
				"cliente_id" => 864,
				"conexion_id" => 755,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1193,
				"anterior" => 1108,
				"basico" => 100,
				"excedente" => 375,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 8934.76
				)
			);
			$array_mediciones [672] = 
			( 
				array (
				"cliente_id" => 865,
				"conexion_id" => 758,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1603,
				"anterior" => 1470,
				"basico" => 100,
				"excedente" => 615,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1195.89
				)
			);
			$array_mediciones [673] = 
			( 
				array (
				"cliente_id" => 866,
				"conexion_id" => 759,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1076,
				"anterior" => 1036,
				"basico" => 100,
				"excedente" => 150,
				"importe" => 100,
				"mts" => 10,-
				"deuda" => 180
				)
			);
			$array_mediciones [674] = 
			( 
				array (
				"cliente_id" => 867,
				"conexion_id" => 760,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 664,
				"anterior" => 659,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);

			$array_mediciones [685] = 
			( 
				array (
				"cliente_id" => 878,
				"conexion_id" => 771,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1159,
				"anterior" => 1122,
				"basico" => 100,
				"excedente" => 135,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 449.41
				)
			);
			$array_mediciones [686] = 
			( 
				array (
				"cliente_id" => 879,
				"conexion_id" => 772,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 185,
				"anterior" => 185,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [687] = 
			( 
				array (
				"cliente_id" => 880,
				"conexion_id" => 773,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1254,
				"anterior" => 1245,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 6718.24
				)
			);
			$array_mediciones [688] = 
			( 
				array (
				"cliente_id" => 881,
				"conexion_id" => 774,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1695,
				"anterior" => 1583,
				"basico" => 100,
				"excedente" => 510,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 131.44
				)
			);
			$array_mediciones [689] = 
			( 
				array (
				"cliente_id" => 882,
				"conexion_id" => 775,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3190,
				"anterior" => 3190,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [690] = 
			( 
				array (
				"cliente_id" => 883,
				"conexion_id" => 776,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 532,
				"anterior" => 515,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 237.51
				)
			);
			$array_mediciones [691] = 
			( 
				array (
				"cliente_id" => 884,
				"conexion_id" => 777,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1217,
				"anterior" => 1181,
				"basico" => 100,
				"excedente" => 130,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1671.84
				)
			);
			$array_mediciones [692] = 
			( 
				array (
				"cliente_id" => 885,
				"conexion_id" => 778,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 471,
				"anterior" => 469,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [693] = 
			( 
				array (
				"cliente_id" => 886,
				"conexion_id" => 779,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 536,
				"anterior" => 528,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 400.41
				)
			);
			$array_mediciones [694] = 
			( 
				array (
				"cliente_id" => 887,
				"conexion_id" => 780,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 427,
				"anterior" => 400,
				"basico" => 100,
				"excedente" => 85,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [695] = 
			( 
				array (
				"cliente_id" => 888,
				"conexion_id" => 781,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 472,
				"anterior" => 461,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 498.11
				)
			);
			$array_mediciones [696] = 
			( 
				array (
				"cliente_id" => 889,
				"conexion_id" => 782,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 29,
				"anterior" => 29,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 5391.42
				)
			);
			$array_mediciones [697] = 
			( 
				array (
				"cliente_id" => 890,
				"conexion_id" => 783,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 336,
				"anterior" => 328,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 247.52
				)
			);
			$array_mediciones [698] = 
			( 
				array (
				"cliente_id" => 891,
				"conexion_id" => 784,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 384,
				"anterior" => 377,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 356.93
				)
			);
			$array_mediciones [699] = 
			( 
				array (
				"cliente_id" => 892,
				"conexion_id" => 785,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1648,
				"anterior" => 1626,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 12774.63
				)
			);
			$array_mediciones [700] = 
			( 
				array (
				"cliente_id" => 893,
				"conexion_id" => 786,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 650,
				"anterior" => 636,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [701] = 
			( 
				array (
				"cliente_id" => 894,
				"conexion_id" => 787,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1203,
				"anterior" => 1148,
				"basico" => 100,
				"excedente" => 225,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 845.41
				)
			);

			$array_mediciones [703] = 
			( 
				array (
				"cliente_id" => 896,
				"conexion_id" => 789,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 657,
				"anterior" => 620,
				"basico" => 100,
				"excedente" => 135,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 728.92
				)
			);
			$array_mediciones [704] = 
			( 
				array (
				"cliente_id" => 897,
				"conexion_id" => 790,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1008,
				"anterior" => 984,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 300.19
				)
			);
			$array_mediciones [705] = 
			( 
				array (
				"cliente_id" => 898,
				"conexion_id" => 791,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 353,
				"anterior" => 345,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 3178.38
				)
			);
			$array_mediciones [706] = 
			( 
				array (
				"cliente_id" => 899,
				"conexion_id" => 792,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 425,
				"anterior" => 417,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 876.34
				)
			);
			$array_mediciones [707] = 
			( 
				array (
				"cliente_id" => 900,
				"conexion_id" => 793,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 828,
				"anterior" => 816,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [708] = 
			( 
				array (
				"cliente_id" => 901,
				"conexion_id" => 794,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 658,
				"anterior" => 645,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [709] = 
			( 
				array (
				"cliente_id" => 902,
				"conexion_id" => 795,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 630,
				"anterior" => 618,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [710] = 
			( 
				array (
				"cliente_id" => 903,
				"conexion_id" => 796,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 694,
				"anterior" => 683,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 938.57
				)
			);
			$array_mediciones [711] = 
			( 
				array (
				"cliente_id" => 904,
				"conexion_id" => 797,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 550,
				"anterior" => 538,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1572.4
				)
			);
			$array_mediciones [712] = 
			( 
				array (
				"cliente_id" => 905,
				"conexion_id" => 798,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 639,
				"anterior" => 627,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2186.27
				)
			);
			$array_mediciones [713] = 
			( 
				array (
				"cliente_id" => 906,
				"conexion_id" => 799,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1008,
				"anterior" => 986,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1030.89
				)
			);
			$array_mediciones [714] = 
			( 
				array (
				"cliente_id" => 907,
				"conexion_id" => 800,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 758,
				"anterior" => 743,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 7421.47
				)
			);
			$array_mediciones [715] = 
			( 
				array (
				"cliente_id" => 908,
				"conexion_id" => 801,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 3998,
				"anterior" => 3960,
				"basico" => 100,
				"excedente" => 140,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [716] = 
			( 
				array (
				"cliente_id" => 909,
				"conexion_id" => 802,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2206,
				"anterior" => 2184,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [717] = 
			( 
				array (
				"cliente_id" => 910,
				"conexion_id" => 803,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 400,
				"anterior" => 388,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);

			$array_mediciones [719] = 
			( 
				array (
				"cliente_id" => 914,
				"conexion_id" => 806,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 185,
				"anterior" => 183,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 53.85
				)
			);
			$array_mediciones [720] = 
			( 
				array (
				"cliente_id" => 915,
				"conexion_id" => 807,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 330,
				"anterior" => 316,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1711.54
				)
			);
			$array_mediciones [721] = 
			( 
				array (
				"cliente_id" => 916,
				"conexion_id" => 808,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 248,
				"anterior" => 248,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1440.62
				)
			);

			$array_mediciones [723] = 
			( 
				array (
				"cliente_id" => 918,
				"conexion_id" => 810,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1754,
				"anterior" => 1653,
				"basico" => 100,
				"excedente" => 455,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 2911.3
				)
			);
			$array_mediciones [724] = 
			( 
				array (
				"cliente_id" => 919,
				"conexion_id" => 811,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 971,
				"anterior" => 934,
				"basico" => 100,
				"excedente" => 135,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 317.3
				)
			);
			$array_mediciones [725] = 
			( 
				array (
				"cliente_id" => 912,
				"conexion_id" => 812,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 501,
				"anterior" => 488,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);



			$array_mediciones [729] = 
			( 
				array (
				"cliente_id" => 924,
				"conexion_id" => 817,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2296,
				"anterior" => 2268,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [730] = 
			( 
				array (
				"cliente_id" => 926,
				"conexion_id" => 819,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1018,
				"anterior" => 1018,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 247.52
				)
			);

			$array_mediciones [732] = 
			( 
				array (
				"cliente_id" => 928,
				"conexion_id" => 821,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 197,
				"anterior" => 197,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [733] = 
			( 
				array (
				"cliente_id" => 776,
				"conexion_id" => 824,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 552,
				"anterior" => 552,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 983.21
				)
			);
			$array_mediciones [734] = 
			( 
				array (
				"cliente_id" => 930,
				"conexion_id" => 825,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 342,
				"anterior" => 330,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 380.75
				)
			);
			$array_mediciones [735] = 
			( 
				array (
				"cliente_id" => 931,
				"conexion_id" => 826,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 347,
				"anterior" => 338,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [736] = 
			( 
				array (
				"cliente_id" => 932,
				"conexion_id" => 827,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 294,
				"anterior" => 294,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);

			$array_mediciones [738] = 
			( 
				array (
				"cliente_id" => 934,
				"conexion_id" => 829,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 316,
				"anterior" => 312,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 185.75
				)
			);

			$array_mediciones [740] = 
			( 
				array (
				"cliente_id" => 938,
				"conexion_id" => 833,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 197,
				"anterior" => 184,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [741] = 
			( 
				array (
				"cliente_id" => 939,
				"conexion_id" => 834,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 248,
				"anterior" => 237,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 884.54
				)
			);
			$array_mediciones [742] = 
			( 
				array (
				"cliente_id" => 940,
				"conexion_id" => 835,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 220,
				"anterior" => 206,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [743] = 
			( 
				array (
				"cliente_id" => 941,
				"conexion_id" => 836,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 670,
				"anterior" => 660,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 499.19
				)
			);
			$array_mediciones [744] = 
			( 
				array (
				"cliente_id" => 942,
				"conexion_id" => 837,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 568,
				"anterior" => 568,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [745] = 
			( 
				array (
				"cliente_id" => 944,
				"conexion_id" => 839,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 380,
				"anterior" => 378,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 885.43
				)
			);
			$array_mediciones [746] = 
			( 
				array (
				"cliente_id" => 945,
				"conexion_id" => 840,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 2947,
				"anterior" => 2947,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,-
				"deuda" => 5252.79
				)
			);
			$array_mediciones [747] = 
			( 
				array (
				"cliente_id" => 946,
				"conexion_id" => 841,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1279,
				"anterior" => 1268,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 983.67
				)
			);
			$array_mediciones [748] = 
			( 
				array (
				"cliente_id" => 947,
				"conexion_id" => 842,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 860,
				"anterior" => 816,
				"basico" => 100,
				"excedente" => 170,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 5770.42
				)
			);
			$array_mediciones [749] = 
			( 
				array (
				"cliente_id" => 948,
				"conexion_id" => 843,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 129,
				"anterior" => 129,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);

			$array_mediciones [751] = 
			( 
				array (
				"cliente_id" => 950,
				"conexion_id" => 845,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 204,
				"anterior" => 196,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [752] = 
			( 
				array (
				"cliente_id" => 951,
				"conexion_id" => 846,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 231,
				"anterior" => 226,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 5050.83
				)
			);

			$array_mediciones [754] = 
			( 
				array (
				"cliente_id" => 953,
				"conexion_id" => 848,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 506,
				"anterior" => 488,
				"basico" => 100,
				"excedente" => 40,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 4109.9
				)
			);
			$array_mediciones [755] = 
			( 
				array (
				"cliente_id" => 954,
				"conexion_id" => 849,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 542,
				"anterior" => 237,
				"basico" => 100,
				"excedente" => 1475,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);

			$array_mediciones [757] = 
			( 
				array (
				"cliente_id" => 957,
				"conexion_id" => 852,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 16,
				"anterior" => 16,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1108.77
				)
			);
			$array_mediciones [758] = 
			( 
				array (
				"cliente_id" => 958,
				"conexion_id" => 853,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 114,
				"anterior" => 114,
				"basico" => 220,
				"excedente" => 0,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 3917.45
				)
			);

			$array_mediciones [760] = 
			( 
				array (
				"cliente_id" => 960,
				"conexion_id" => 860,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 263,
				"anterior" => 257,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 375.01
				)
			);

			$array_mediciones [762] = 
			( 
				array (
				"cliente_id" => 652,
				"conexion_id" => 863,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 2939,
				"anterior" => 2929,
				"basico" => 220,
				"excedente" => 0,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 840.14
				)
			);
			$array_mediciones [763] = 
			( 
				array (
				"cliente_id" => 963,
				"conexion_id" => 864,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 109,
				"anterior" => 100,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [764] = 
			( 
				array (
				"cliente_id" => 965,
				"conexion_id" => 866,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 382,
				"anterior" => 344,
				"basico" => 100,
				"excedente" => 140,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [765] = 
			( 
				array (
				"cliente_id" => 966,
				"conexion_id" => 867,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 194,
				"anterior" => 181,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [766] = 
			( 
				array (
				"cliente_id" => 967,
				"conexion_id" => 868,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 114,
				"anterior" => 94,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1496.77
				)
			);
			$array_mediciones [767] = 
			( 
				array (
				"cliente_id" => 968,
				"conexion_id" => 869,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 19,
				"anterior" => 18,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [768] = 
			( 
				array (
				"cliente_id" => 969,
				"conexion_id" => 870,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 134,
				"anterior" => 114,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [769] = 
			( 
				array (
				"cliente_id" => 970,
				"conexion_id" => 871,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 114,
				"anterior" => 96,
				"basico" => 100,
				"excedente" => 40,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 179.66
				)
			);
			$array_mediciones [770] = 
			( 
				array (
				"cliente_id" => 971,
				"conexion_id" => 872,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 105,
				"anterior" => 100,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [771] = 
			( 
				array (
				"cliente_id" => 972,
				"conexion_id" => 873,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 97,
				"anterior" => 90,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [772] = 
			( 
				array (
				"cliente_id" => 973,
				"conexion_id" => 874,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 166,
				"anterior" => 147,
				"basico" => 100,
				"excedente" => 45,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [775] = 
			( 
				array (
				"cliente_id" => 976,
				"conexion_id" => 877,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 12,
				"anterior" => 12,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 247.52
				)
			);
			$array_mediciones [776] = 
			( 
				array (
				"cliente_id" => 977,
				"conexion_id" => 878,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 78,
				"anterior" => 70,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [777] = 
			( 
				array (
				"cliente_id" => 978,
				"conexion_id" => 879,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 166,
				"anterior" => 142,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 379.29
				)
			);
			$array_mediciones [778] = 
			( 
				array (
				"cliente_id" => 979,
				"conexion_id" => 880,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 11,
				"anterior" => 11,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [779] = 
			( 
				array (
				"cliente_id" => 980,
				"conexion_id" => 881,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 13,
				"anterior" => 13,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [780] = 
			( 
				array (
				"cliente_id" => 981,
				"conexion_id" => 882,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 139,
				"anterior" => 119,
				"basico" => 100,
				"excedente" => 50,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [781] = 
			( 
				array (
				"cliente_id" => 982,
				"conexion_id" => 883,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 27,
				"anterior" => 24,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [782] = 
			( 
				array (
				"cliente_id" => 983,
				"conexion_id" => 884,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 312,
				"anterior" => 286,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [784] = 
			( 
				array (
				"cliente_id" => 985,
				"conexion_id" => 886,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 138,
				"anterior" => 127,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [785] = 
			( 
				array (
				"cliente_id" => 986,
				"conexion_id" => 887,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 133,
				"anterior" => 126,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1133.51
				)
			);
			$array_mediciones [786] = 
			( 
				array (
				"cliente_id" => 987,
				"conexion_id" => 888,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 145,
				"anterior" => 128,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [787] = 
			( 
				array (
				"cliente_id" => 988,
				"conexion_id" => 889,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 259,
				"anterior" => 245,
				"basico" => 100,
				"excedente" => 20,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [788] = 
			( 
				array (
				"cliente_id" => 989,
				"conexion_id" => 890,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 102,
				"anterior" => 97,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [789] = 
			( 
				array (
				"cliente_id" => 990,
				"conexion_id" => 891,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 132,
				"anterior" => 124,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [790] = 
			( 
				array (
				"cliente_id" => 991,
				"conexion_id" => 892,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 84,
				"anterior" => 72,
				"basico" => 100,
				"excedente" => 10,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 145.91
				)
			);
			$array_mediciones [791] = 
			( 
				array (
				"cliente_id" => 992,
				"conexion_id" => 893,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 15,
				"anterior" => 15,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [792] = 
			( 
				array (
				"cliente_id" => 993,
				"conexion_id" => 894,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 56,
				"anterior" => 51,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [793] = 
			( 
				array (
				"cliente_id" => 994,
				"conexion_id" => 895,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 115,
				"anterior" => 109,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [794] = 
			( 
				array (
				"cliente_id" => 995,
				"conexion_id" => 896,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 228,
				"anterior" => 213,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 232.69
				)
			);
			$array_mediciones [795] = 
			( 
				array (
				"cliente_id" => 996,
				"conexion_id" => 897,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 36,
				"anterior" => 31,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [797] = 
			( 
				array (
				"cliente_id" => 998,
				"conexion_id" => 899,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 148,
				"anterior" => 133,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [798] = 
			( 
				array (
				"cliente_id" => 999,
				"conexion_id" => 900,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 215,
				"anterior" => 190,
				"basico" => 100,
				"excedente" => 75,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [799] = 
			( 
				array (
				"cliente_id" => 1000,
				"conexion_id" => 901,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 230,
				"anterior" => 197,
				"basico" => 100,
				"excedente" => 115,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [800] = 
			( 
				array (
				"cliente_id" => 1001,
				"conexion_id" => 902,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 21,
				"anterior" => 21,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [801] = 
			( 
				array (
				"cliente_id" => 1002,
				"conexion_id" => 903,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 162,
				"anterior" => 140,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [802] = 
			( 
				array (
				"cliente_id" => 1003,
				"conexion_id" => 904,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 25,
				"anterior" => 25,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [803] = 
			( 
				array (
				"cliente_id" => 1004,
				"conexion_id" => 905,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 91,
				"anterior" => 86,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [804] = 
			( 
				array (
				"cliente_id" => 1005,
				"conexion_id" => 906,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 18,
				"anterior" => 18,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [805] = 
			( 
				array (
				"cliente_id" => 1006,
				"conexion_id" => 907,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 157,
				"anterior" => 142,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [806] = 
			( 
				array (
				"cliente_id" => 1007,
				"conexion_id" => 908,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 208,
				"anterior" => 197,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 131.44
				)
			);
			$array_mediciones [807] = 
			( 
				array (
				"cliente_id" => 1008,
				"conexion_id" => 909,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 70,
				"anterior" => 60,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 131.44
				)
			);
			$array_mediciones [808] = 
			( 
				array (
				"cliente_id" => 1009,
				"conexion_id" => 910,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 215,
				"anterior" => 207,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [809] = 
			( 
				array (
				"cliente_id" => 1010,
				"conexion_id" => 911,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 49,
				"anterior" => 41,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [810] = 
			( 
				array (
				"cliente_id" => 1011,
				"conexion_id" => 912,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 128,
				"anterior" => 113,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [811] = 
			( 
				array (
				"cliente_id" => 1012,
				"conexion_id" => 913,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 202,
				"anterior" => 180,
				"basico" => 100,
				"excedente" => 60,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [812] = 
			( 
				array (
				"cliente_id" => 1013,
				"conexion_id" => 914,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 131,
				"anterior" => 124,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [813] = 
			( 
				array (
				"cliente_id" => 1014,
				"conexion_id" => 915,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 101,
				"anterior" => 88,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1043.13
				)
			);
			$array_mediciones [816] = 
			( 
				array (
				"cliente_id" => 1017,
				"conexion_id" => 918,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 68,
				"anterior" => 68,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [817] = 
			( 
				array (
				"cliente_id" => 1018,
				"conexion_id" => 919,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 160,
				"anterior" => 160,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [818] = 
			( 
				array (
				"cliente_id" => 1019,
				"conexion_id" => 920,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 105,
				"anterior" => 96,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 131.44
				)
			);
			$array_mediciones [820] = 
			( 
				array (
				"cliente_id" => 1021,
				"conexion_id" => 922,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 159,
				"anterior" => 149,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 136.26
				)
			);
			$array_mediciones [821] = 
			( 
				array (
				"cliente_id" => 1022,
				"conexion_id" => 923,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 207,
				"anterior" => 179,
				"basico" => 100,
				"excedente" => 90,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 399.22
				)
			);
			$array_mediciones [822] = 
			( 
				array (
				"cliente_id" => 1023,
				"conexion_id" => 924,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 81,
				"anterior" => 75,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [823] = 
			( 
				array (
				"cliente_id" => 1024,
				"conexion_id" => 925,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 90,
				"anterior" => 64,
				"basico" => 100,
				"excedente" => 80,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 213.4
				)
			);
			$array_mediciones [824] = 
			( 
				array (
				"cliente_id" => 1025,
				"conexion_id" => 926,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 205,
				"anterior" => 181,
				"basico" => 100,
				"excedente" => 70,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [825] = 
			( 
				array (
				"cliente_id" => 1026,
				"conexion_id" => 927,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 74,
				"anterior" => 72,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [826] = 
			( 
				array (
				"cliente_id" => 1027,
				"conexion_id" => 928,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 110,
				"anterior" => 97,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [827] = 
			( 
				array (
				"cliente_id" => 1028,
				"conexion_id" => 929,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 26,
				"anterior" => 26,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [828] = 
			( 
				array (
				"cliente_id" => 1029,
				"conexion_id" => 930,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 167,
				"anterior" => 152,
				"basico" => 100,
				"excedente" => 25,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 170.01
				)
			);
			$array_mediciones [829] = 
			( 
				array (
				"cliente_id" => 1030,
				"conexion_id" => 931,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 122,
				"anterior" => 109,
				"basico" => 100,
				"excedente" => 15,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 415.61
				)
			);
			$array_mediciones [830] = 
			( 
				array (
				"cliente_id" => 1031,
				"conexion_id" => 932,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 165,
				"anterior" => 154,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [831] = 
			( 
				array (
				"cliente_id" => 1032,
				"conexion_id" => 933,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 101,
				"anterior" => 78,
				"basico" => 100,
				"excedente" => 65,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 203.76
				)
			);
			$array_mediciones [832] = 
			( 
				array (
				"cliente_id" => 1033,
				"conexion_id" => 934,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 49,
				"anterior" => 42,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1356.33
				)
			);
			$array_mediciones [833] = 
			( 
				array (
				"cliente_id" => 1034,
				"conexion_id" => 935,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 92,
				"anterior" => 89,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [835] = 
			( 
				array (
				"cliente_id" => 1036,
				"conexion_id" => 937,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 182,
				"anterior" => 178,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [836] = 
			( 
				array (
				"cliente_id" => 1037,
				"conexion_id" => 938,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 19,
				"anterior" => 19,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 121.8
				)
			);
			$array_mediciones [837] = 
			( 
				array (
				"cliente_id" => 1038,
				"conexion_id" => 939,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 16,
				"anterior" => 16,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 247.52
				)
			);
			$array_mediciones [839] = 
			( 
				array (
				"cliente_id" => 1040,
				"conexion_id" => 941,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 158,
				"anterior" => 147,
				"basico" => 100,
				"excedente" => 5,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [840] = 
			( 
				array (
				"cliente_id" => 1041,
				"conexion_id" => 942,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 122,
				"anterior" => 119,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 1292.48
				)
			);

			$array_mediciones [843] = 
			( 
				array (
				"cliente_id" => 1044,
				"conexion_id" => 947,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 1244,
				"anterior" => 190,
				"basico" => 100,
				"excedente" => 5220,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [844] = 
			( 
				array (
				"cliente_id" => 1045,
				"conexion_id" => 948,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 27,
				"anterior" => 20,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);
			$array_mediciones [845] = 
			( 
				array (
				"cliente_id" => 1047,
				"conexion_id" => 949,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Comercial",
				"actual" => 2838,
				"anterior" => 2812,
				"basico" => 220,
				"excedente" => 88,
				"importe" => 220,
				"mts" => 15,
				"deuda" => 0
				)
			);


			$array_mediciones [851] = 
			( 
				array (
				"cliente_id" => 1053,
				"conexion_id" => 955,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 89,
				"anterior" => 72,
				"basico" => 100,
				"excedente" => 35,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 163.92
				)
			);

			$array_mediciones [853] = 
			( 
				array (
				"cliente_id" => 1055,
				"conexion_id" => 957,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 41,
				"anterior" => 41,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 457.15
				)
			);

			$array_mediciones [855] = 
			( 
				array (
				"cliente_id" => 1057,
				"conexion_id" => 959,
				"mes" => 12,
				"anio" => 2017,
				"categoria" => "Familiar",
				"actual" => 19,
				"anterior" => 11,
				"basico" => 100,
				"excedente" => 0,
				"importe" => 100,
				"mts" => 10,
				"deuda" => 0
				)
			);

			//$array_mediciones =  $array_mediciones_febrero;
			//var_dump($arrayName[63]["id_conexion"]);die();
			for ($i=1; $i <= count($array_mediciones) ; $i++) 
			{
				echo "'\n\n\nhaciendo: ".$i.": ";
				//busco si la conexion ya existe
				if( ($array_mediciones[$i]["conexion_id"] != NULL) && ($array_mediciones[$i]["conexion_id"] != 0) )
				{
					$row_de_tabla_medicion = $this->Crud_model-> get_data_row_dos_campos("medicion", "Medicion_Mes", $array_mediciones[$i]["mes"], "Medicion_Conexion_Id", $array_mediciones[$i]["conexion_id"] ) ;
					if($row_de_tabla_medicion == false) // no existe conexion
					{
						$row_a_insertar = array(
							'Medicion_Id' => null,
							'Medicion_Conexion_Id' =>  $array_mediciones[$i]["conexion_id"],
							'Medicion_Mes' =>  $array_mediciones[$i]["mes"],
							'Medicion_Anio' =>  $array_mediciones[$i]["anio"],
							'Medicion_Anterior' =>  $array_mediciones[$i]["anterior"],
							'Medicion_Actual' =>   $array_mediciones[$i]["actual"],
							'Medicion_Basico' =>  $array_mediciones[$i]["basico"],
							'Medicion_Excedente' =>  $array_mediciones[$i]["excedente"],
							'Medicion_Importe' => $array_mediciones[$i]["importe"],
							'Medicion_Mts' =>  $array_mediciones[$i]["mts"],
							'Medicion_IVA' =>  0,
							'Medicion_Porcentaje' =>  null,
							'Medicion_Tipo' =>  0,
							'Medicion_Recargo' => 0,
							'Medicion_Observacion' =>  null,
							'Medicion_Habilitacion' => 1,
							'Medicion_Borrado' => 0,
							'Medicion_Timestamp' => null
						);
						$resultado_insercion = $this->Crud_model->insert_data("medicion",$row_a_insertar);
						echo"  - insertado  : ";
						var_dump($resultado_insercion);
						echo"\n\n          *                 _";
					}
					else // siginifica que existe conexion pero hay q actualizarlo
					{
						$row_a_actualizar = array(
							'Medicion_Conexion_Id' =>  $array_mediciones[$i]["conexion_id"],
							'Medicion_Mes' =>  $array_mediciones[$i]["mes"],
							'Medicion_Anio' =>  $array_mediciones[$i]["anio"],
							'Medicion_Anterior' =>  $array_mediciones[$i]["anterior"],
							'Medicion_Actual' =>   $array_mediciones[$i]["actual"],
							'Medicion_Basico' =>  $array_mediciones[$i]["basico"],
							'Medicion_Excedente' =>  $array_mediciones[$i]["excedente"],
							'Medicion_Importe' => $array_mediciones[$i]["importe"],
							'Medicion_Mts' =>  $array_mediciones[$i]["mts"],
							'Medicion_IVA' =>  0,
							'Medicion_Porcentaje' =>  null,
							'Medicion_Tipo' =>  0,
							'Medicion_Recargo' => 0,
							'Medicion_Observacion' =>  null,
							'Medicion_Habilitacion' => 1,
							'Medicion_Borrado' => 0,
							'Medicion_Timestamp' => null
						);
						$resultado_update = $this->Crud_model->update_data($row_a_actualizar,  $row_de_tabla_medicion->Medicion_Id , "medicion", "Medicion_Id" );
						echo"  - updatezado : ";
						var_dump($resultado_update);
						echo"\n\n          *                 _";
					}
				}
				else echo "Sin medicion: id. \n";
			}
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Este controller quedosin uso , desde que se comenzo a usar el controller nuevo
pero deberia volver a usarse , pasando las funciones de nuevo aca
*/
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
}
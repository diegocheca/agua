<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nuevo extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('Nuevo_model');
		$this->load->helper('PDF_helper');
		$this->load->helper('eFPDF_helper');
		$this->load->library('zend');
	}
	public function index(){
		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//estoy adentro
			$this->load->view("nuevo/tabla_pago");
		endif;
	}
	public function cargar_mediciones_por_lote(){
		$datos['sectores'] = $this->Nuevo_model->get_data_sectores();
		if ($datos['sectores']) {
			$datos['titulo'] = "Cargar Lote Mediciones";
			$this->load->view('templates/header', $datos);
			$this->load->view('nuevo/agregar_lote_view', $datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		}
		else
		{
			$this->session->set_flashdata("document_status",mensaje("La Medicion No existe","danger"));
			redirect('mediciones');
		}
	}
	
	public function ejecutar_query()
	{
		$sectores=  $this->input->post("sectores");
		$fecha_aux = $this->input->post("mes");

		$inputFechaaux=$this->input->post('mes',true);
		//$aux =  str_replace('/', '-', $inputFechaaux);
		$anio = date("Y", strtotime($inputFechaaux));
		$mes = date("m", strtotime($inputFechaaux));
		//var_dump($anio, $mes);die();

		//	$anio_actual = date("Y");
		$mediciones_desde_query =  $this->Nuevo_model->get_sectores_query_nuevo($sectores, $mes, $anio );
		//var_dump($mediciones_desde_query);die();
		$indice_actual = -1;
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

		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		// var_dump($resultado);die();
		$cadena = null;
		$cantidad = null;
		$i = 0;
		if($resultado == false)
		{
			$cadena.= 
			'<div class="alert alert-danger">
				Sin conexion en este sector.
			</div>';
		}
		elseif( sizeof($resultado)  == 1){
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
								<label class="control-label" for="inputMedicionAnterior_'.$i.'">Anterior</label>
								<input type="text" id="inputMedicionAnterior_'.$i.'" name="inputMedicionAnterior_'.$i.'" value =" '.$key->Medicion_Anterior.' " class="form-control input-sm">
								
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputMedicionActual_'.$i.'">Actual </label>
								<input type="text" id="inputMedicionActual_'.$i.'" name="inputMedicionActual_'.$i.'" class="form-control input-sm" placeholder="Solo Números" autocomplete="off"';
								if ( ($key->Medicion_Actual != null)  &&  ($key->Medicion_Actual != 0)  )
									$cadena.=  ' value=" '.$key->Medicion_Actual. ' " ';
								$cadena.=  '>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group fg-line">
								<label class="control-label" for="inputExcedente_'.$i.'">Excedente </label>
								<input type="hidden" id="inputExcedente_'.$i.'" name="inputExcedente_'.$i.'" class="form-control input-sm" readonly autocomplete="off"';
								if ( ($key->Medicion_Excedente != null)  &&  ($key->Medicion_Excedente != 0)  )
									$cadena.=  ' value=" '.$key->Medicion_Excedente. ' " ';
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
	public function guardar_mediciones_por_lotes_con_ajax(){
		$todas_las_variables = $this->Crud_model->get_data("configuracion");
		$cantidad = $this->input->post("cantidad_de_input", true);
		$mes = $this->input->post("mes_de_input");
		$anio = $this->input->post("anio_de_input");
		$miselect = $this->input->post("sector_input");
		//var_dump($miselect);die();


		for($i = 0; $i < $cantidad; $i++)
		{
			$imputConexionId = $this->input->post("inputConexionId_".$i, true);
			//$inputMedicionId = $this->input->post("inputMedicionId_".$i, true);
			
			$anterior = $this->input->post("inputMedicionAnterior_".$i, true);
			$actual = $this->input->post("inputMedicionActual_".$i, true);
			if($actual == null ) // si el valor no fue cargado
			{
				echo "        macutal con nada       --";
				continue;
			}	
			$inputExcedente = $this->input->post("inputExcedente_".$i, true); 
			$inputTipo = $this->input->post("inputTipo_".$i, true);
			if( ($inputTipo == 1) || ($inputTipo == "Familiar") || ($inputTipo =="Familiar ") )
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

			//$anterior = $key->Factura_MedicionAnterior;
			//$actual = $key->Factura_MedicionActual;
			$inputExcedente = intval($actual) - intval($anterior) - intval($metros_basicos);
			if($inputExcedente < 0 ) // significa q actual < anterior
				if($inputExcedente < -10 ) // significa q actual < anterior
				{
					$inputExcedente = intval($actual) - intval($metros_basicos);
				}
				else
				{
					$inputExcedente =	0;
				}
			$importe_medicion = 0;
			if($inputExcedente == 0)
				$importe_medicion = 0;
			else $importe_medicion = floatval($precio_metros) * floatval($inputExcedente);

			$datos_medicion = array(
				'Factura_MedicionAnterior' => intval($anterior),
				'Factura_MedicionActual' => intval($actual),
				'Factura_ExcedentePrecio' => floatval($importe_medicion),
				'Factura_Excedentem3' => $inputExcedente,
				'Factura_MedicionTimestamp' => date("Y-m-d H:i:s")
				);
			//$resultado = $this->Crud_model->update_data($datos_medicion, $inputMedicionId, "medicion", "Medicion_Id");
			$resultado = $this->Nuevo_model->update_data_tres_campos($datos_medicion, $imputConexionId, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
			$res = $this->corregir_boletas_por_sector($miselect, $mes, $anio);
			echo $resultado;
		}
	}
	public function corregir_boletas_por_sector($sectores = 0, $mes = 0, $anio = 0)
	{
		//viene si o si po parametro
		// if($sectores == 0 )
		// 	$sectores = $this->input->post('select_tablet');
		// if($mes == 0 )
		// 	$mes = $this->input->post('mes');
		// if($anio == 0 )
		// 	$anio = $this->input->post('anio');
		// if($sectores === 0 )
		// 	{
		// 		echo "Error";die();
		// 	}
		// elseif($sectores == "A")
		// 	$sectores = [ "A", "Jardines del Sur", "Aberanstain", "Medina", "Salas", "Santa Barbara" , "V Elisa"];
		// else $sectores = [ "B", "C", "David", "ASENTAMIENTO OLMOS", "Zaldivar" ];
		$todas_las_variables = $this->Nuevo_model->get_data("configuracion");
		$mediciones_desde_query =  $this->Nuevo_model->get_sectores_query_corregir($sectores, $mes, $anio );
		//var_dump($mediciones_desde_query);die();
		if($mediciones_desde_query != false)
		{
			$indice_actual = 0;
			foreach ($mediciones_desde_query as $key ) {
				if( ($key->Factura_MedicionAnterior == 0) && ($key->Factura_MedicionActual == 1) ) // bandera de tablet
					continue;
				if( ( floatval($key->Factura_PagoMonto) != floatval(0)) && ($key->Factura_PagoContado != NULL) && ($key->Factura_PagoContado != NULL) ) //si esta pagada no se re calcula
					continue;
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
				$anterior = $key->Factura_MedicionAnterior;
				$actual = $key->Factura_MedicionActual;
				$inputExcedente = intval($actual) - intval($anterior) - intval($metros_basicos);
				if($inputExcedente < 0 )
					$inputExcedente = 0;
				$importe_medicion = 0;
				if($inputExcedente == 0)
					$importe_medicion = 0;
				else $importe_medicion = floatval($precio_metros) * floatval($inputExcedente);
				//calculo el subtotal y total
				$sub_total = floatval($key->Factura_TarifaSocial) 
										+ floatval($key->Factura_Deuda)
										+ floatval($key->Factura_ExcedentePrecio )
										+ floatval($key->Factura_CuotaSocial )
										+ floatval($key->Factura_Riego )
										+ floatval($key->Factura_PM_Cuota_Precio)
										+ floatval($key->Factura_PPC_Precio)
										+ floatval($key->Factura_Multa);
				$total  = $sub_total;
				$bonificacion = 0;
				if($key->Conexion_Deuda == 0)
						//$bonificacion_pago_puntual =  (floatval ($excedente) + floatval($tarifa_basica)) * floatval (5) / floatval(100) ;//con bonificacion
						$bonificacion = (floatval ($inputExcedente) + floatval($key->Factura_TarifaSocial)) * floatval (5) / floatval(100) ;//con bonificacion
				$total =	floatval($total)
										- floatval($key->Factura_Acuenta)
										- floatval($bonificacion);
				//vtos
				$vto_2_precio = floatval($total) + floatval($total) * floatval($todas_las_variables[18]->Configuracion_Valor);
				$vto_1_precio = $total;
				$indice_actual++;
				$datos_factura_nueva = array(
					'Factura_SubTotal' => floatval($sub_total),
					'Factura_Total' => floatval($total),
					'Factura_Vencimiento1_Precio' => floatval($vto_1_precio),
					'Factura_Vencimiento2_Precio' => floatval($vto_2_precio),
					'Factura_ExcedentePrecio' => floatval($importe_medicion),
					'Factura_Excedentem3' => $inputExcedente
					 );
				$resultado[$indice_actual] = $this->Nuevo_model->update_data_tres_campos($datos_factura_nueva, $key->Conexion_Id, "facturacion_nueva","Factura_Conexion_Id", "Factura_Mes", $mes, "Factura_Año", $anio);
				var_dump($datos_factura_nueva,$key->Conexion_Id);
			}
			var_dump($datos_factura_nueva);
		}
		else
			var_dump("Error. no hay medciones para las variables");	
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Conexion extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
	}
	public function index(){
		if (!$this->session->userdata('login')): //estoy logeado
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//$datos['clientes'] = $this->Crud_model->get_data("clientes");
			$datos['conexiones'] = $this->Crud_model->get_conexion_sin_borrados("conexion","clientes", "Conexion_Borrado","Conexion_Cliente_Id", "Cli_Id");
			//$datos['bajas'] = $this->Crud_model->get_cantidad_bajas("conexion");
			//var_dump($datos['conexiones']);die();
			$datos['titulo'] = 'Lista de Conexiones';
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
			$this->load->view('conexiones/conexiones', $datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$data ['aviso'] = $this->session->flashdata('aviso');
			//var_dump($data ['aviso']);
			if($data ['aviso'] != null)
				$this->load->view("templates/notificacion_view", $data);
		endif;
	}
	public function editar($nuevo){
		$datos['info'] = $this->Crud_model->get_conexion_id_sin_borrados("conexion",$nuevo);
			$datos['titulo'] = 'MOdificando Conexion';
			$datos["conexiones_a_imprimir"] = $this->Crud_model->buscar_conexion_a_imprmir_nuevo();
			
			$this->load->view('templates/header',$datos);
			$this->load->view('conexiones/modificar', $datos);
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
	}

	public function buscar_deuda_conexion()
	{
		$id=$this->security->xss_clean(strip_tags($this->input->post('id_conexion')));
		$deuda = $this->Crud_model->get_conexion_id_sin_borrados(0,$id);
		//var_dump($deuda->Conexion_Deuda);die();
		echo $deuda->Conexion_Deuda;
	}
	
	public function modificar()
	{
		$this->load->model('Nuevo_model');
		if($this->input->post())
		{
			//coenxion
			$inputDomSum=$this->security->xss_clean(strip_tags($this->input->post('inputDomSum')));
			$inputSector=$this->security->xss_clean(strip_tags($this->input->post('sector')));
			$inputtipo_conexion=$this->security->xss_clean(strip_tags($this->input->post('tipo_conexion')));
			$deuda=$this->security->xss_clean(strip_tags($this->input->post('deuda')));
			$multa=$this->security->xss_clean(strip_tags($this->input->post('multa')));
			$acuenta=$this->security->xss_clean(strip_tags($this->input->post('acuenta')));
			
			$multa = floatval($multa);
			$deuda = floatval($deuda);
			$id_conexion = $this->input->post("id_oculto_conexion", true);
			$id = $this->input->post("id_oculto", true);
			$cambio_multa = false;
			$cambio_deuda = false;
			$cambio_acuenta = false;
			
			$multa_motivo = $this->input->post("multa_motivo", true);

			$datos_conexion_viejos = $this->Crud_model->get_data_dos_campos("conexion","Conexion_Id",$id_conexion, "Conexion_Borrado",0);
			if(floatval($datos_conexion_viejos[0]->Conexion_Multa) != $multa) 
				$cambio_multa  = true;
			if( ($multa == false) || ($multa < 0) || ($multa == null))
				{
					$multa = floatval(0);
					$multa_motivo = null;

				}

			if(floatval($datos_conexion_viejos[0]->Conexion_Deuda) != $deuda) 
				$cambio_deuda  = true;
			if( ($deuda == false) || ($deuda < 0) || ($deuda == null))
				$deuda = floatval(0);



			if(floatval($datos_conexion_viejos[0]->Conexion_Acuenta) != $acuenta) 
				$cambio_acuenta  = true;
			if( ($acuenta == false) || ($acuenta < 0) || ($acuenta == null))
				$acuenta = floatval(0);

			//$id_cliente=$this->security->xss_clean(strip_tags($this->input->post('idcliente')));
			//validation 
			//var_dump($cambio_acuenta);die();
			$riego = $this->input->post("select_riego", true);
			//var_dump($inputDomSum,$inputSector,$inputtipo_conexion,$id,$id_conexion);die();
			if($inputSector != -9)
				$datos_conexion = array(
							'Conexion_Cliente_Id' => $id,
							'Conexion_DomicilioSuministro' => $inputDomSum,
							'Conexion_Categoria' =>$inputtipo_conexion,
							'Conexion_Sector' =>  $inputSector,
							'Conexion_Deuda' =>  $deuda,
							'Conexion_Latitud' =>  $riego,
							'Conexion_Multa' =>  $multa,
							'Conexion_Multa_Motivo' =>  $multa_motivo,
							'Conexion_Multa_Timestamp' =>  null,
							'Conexion_Acuenta' =>  $acuenta,
							'Conexion_Habilitacion' =>1
				);
			else 
				$datos_conexion = array(
							'Conexion_Cliente_Id' => $id,
							'Conexion_DomicilioSuministro' => $inputDomSum,
							'Conexion_Categoria' =>$inputtipo_conexion,
							'Conexion_Deuda' =>  $deuda,
							'Conexion_Latitud' =>  $riego,
							'Conexion_Multa' =>  $multa,
							'Conexion_Multa_Motivo' =>  $multa_motivo,
							'Conexion_Multa_Timestamp' =>  null,
							'Conexion_Acuenta' =>  $acuenta,
							'Conexion_Habilitacion' =>1
				);
			$id_conexion_recien_insertado = $this->Crud_model->modificar_conexion($datos_conexion, $id_conexion, "conexion","Conexion_Id");
			//compruebo cambio de deuoda o multa y lo agrego a los logs
			if($cambio_deuda || $cambio_multa || $cambio_acuenta)//agrego a la lista de cambios de deudas o multas
			{
				if($datos_conexion_viejos[0]->Conexion_Deuda == null)
					$datos_conexion_viejos[0]->Conexion_Deuda = 0;
				if($datos_conexion_viejos[0]->Conexion_Multa == null)
					$datos_conexion_viejos[0]->Conexion_Multa = 0;
				if($datos_conexion_viejos[0]->Conexion_Acuenta == null)
					$datos_conexion_viejos[0]->Conexion_Acuenta = 0;
				if($cambio_deuda)
				{
					$datos_log = array(
							'log_deuda_multa_Id' => null,
							'log_deuda_multa_Conexion_Id' => $id_conexion,
							'log_deuda_multa_Valor_Anterior' =>$datos_conexion_viejos[0]->Conexion_Deuda,
							'log_deuda_multa_Valor_Actual' =>  $deuda,
							'log_deuda_multa_Campo' => "Deuda",
							'log_deuda_multa_motivo' => null,
							'log_deuda_multa_Revisado' => "No",
							'log_deuda_multa_Quien' =>$this->session->userdata('id_user'),
							'log_deuda_multa_Timestamp' =>null
					);
					$id_conexion_log_deuda = $this->Crud_model->insert_data("log_deuda_multa",$datos_log);
				}
				if($cambio_multa)
				{
					$datos_log = array(
							'log_deuda_multa_Id' => null,
							'log_deuda_multa_Conexion_Id' => $id_conexion,
							'log_deuda_multa_Valor_Anterior' =>$datos_conexion_viejos[0]->Conexion_Multa,
							'log_deuda_multa_Valor_Actual' =>  $multa,
							'log_deuda_multa_Campo' => "Multa",
							'log_deuda_multa_motivo' => $multa_motivo,
							'log_deuda_multa_Revisado' => "No",
							'log_deuda_multa_Quien' =>$this->session->userdata('id_user'),
							'log_deuda_multa_Timestamp' =>null
					);
					$id_conexion_log_multa = $this->Crud_model->insert_data("log_deuda_multa",$datos_log);
				}
				if($cambio_acuenta)
				{
					$datos_log = array(
							'log_deuda_multa_Id' => null,
							'log_deuda_multa_Conexion_Id' => $id_conexion,
							'log_deuda_multa_Valor_Anterior' =>$datos_conexion_viejos[0]->Conexion_Acuenta,
							'log_deuda_multa_Valor_Actual' =>  $acuenta,
							'log_deuda_multa_Campo' => "Acuenta",
							'log_deuda_multa_motivo' => null,
							'log_deuda_multa_Revisado' => "No",
							'log_deuda_multa_Quien' =>$this->session->userdata('id_user'),
							'log_deuda_multa_Timestamp' =>null
					);
					$id_conexion_log_multa = $this->Crud_model->insert_data("log_deuda_multa",$datos_log);
				}
				// voy a ver si la factura para el ems actual no esta paga, si no lo esta, entonces voy a pagarla con el pago a cuenta recien hecho
				$mes_actual = date("m");
				$mes_anterior = date("m")-1;
				$anio_actual =  date("Y");
				if($mes_anterior == 0)
				{
					$mes_anterior = 1;
					$anio_actual =  date("Y")-1;
				}
				$ultima_factura = $this->Nuevo_model->get_data_tres_campos("facturacion_nueva", 'Factura_Mes', $mes_actual,'Factura_Año', $anio_actual, 'Factura_Conexion_Id', $id_conexion);
				if( $ultima_factura == false ) //encontre la factura mas actual
					//busco un mes mas atras
					$ultima_factura = $this->Nuevo_model->get_data_tres_campos("facturacion_nueva", 'Factura_Mes', $mes_anterior,'Factura_Año', $anio_actual, 'Factura_Conexion_Id', $id_conexion);
				if( $ultima_factura != false ) 
				{
					//significa que hay boleta, voy a pregutnar si ya se pagaron o no
					if(
						($ultima_factura[0]->Factura_PagoMonto == null  )
						&& 
						($ultima_factura[0]->Factura_PagoTimestamp == null )
						)
					//significa que la boleta no esta paga 
					//entonces actualizo la boleta
					//para ello debo actualizar el valor de factura_acuenta
					if($cambio_deuda)
					{
						$datos = array(
							"Factura_Deuda" => $deuda
						);
						$resultado = $this->Crud_model->update_data($datos, $ultima_factura[0]->Factura_Id, "facturacion_nueva","Factura_Id");
					}
					if($cambio_multa)
					{
						$datos = array(
							"Factura_Multa" => $multa
						);
						$resultado = $this->Crud_model->update_data($datos, $ultima_factura[0]->Factura_Id, "facturacion_nueva","Factura_Id");
					}
					if($cambio_acuenta)
					{
						$datos = array(
							"Factura_Acuenta" => $acuenta
						);
						$resultado = $this->Crud_model->update_data($datos, $ultima_factura[0]->Factura_Id, "facturacion_nueva","Factura_Id");
					}
					$this->corregir_boleta_por_id($ultima_factura[0]->Factura_Id);
				}
				//else   no existen boletas para hacer el descuento, por tanto no hago nada
				//xq significa que el descuento se hará en la proxima boleta
			}
			if($id_conexion_recien_insertado)
			{
				$this->session->set_flashdata('aviso','La conexion fue modificado correctamente');
				$this->session->set_flashdata('tipo_aviso','success');
			}
			else 
			{
				$this->session->set_flashdata('aviso','ERROR, Debes seleccionar la conexion a editar');
				$this->session->set_flashdata('tipo_aviso','danger');
			}
			redirect(base_url("conexion"), "refresh");
		}
		else 
		{
			$this->session->set_flashdata('mensaje','');
			redirect(base_url());
		}
	}

	public function borrar_conexion()
	{
		$id=  $this->input->post("id");
		$data = array('Conexion_Borrado' => 1, );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "conexion", "Conexion_Id");
		echo true;
	}

	//Pagina para Ingresar Clientes
	public function agregar(){
		$this->load->helper('form');
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$datos['titulo'] = 'Agregar Clientes';
			$this->load->view('templates/header',$datos);
			$this->load->view('clientes/agregar');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
			$btn_enviar =$this->input->post('enviar');
			if (isset($btn_enviar)) {
				//ASIGNAMOS UNA VARIABLE A CADA CAMPO RECIBIDO
				$tipo_persona=$this->security->xss_clean(strip_tags($this->input->post('tipo_persona')));
				$tipo_doc=$this->security->xss_clean(strip_tags($this->input->post('tipo_documento')));
				$nro_documento=$this->security->xss_clean(strip_tags($this->input->post('nro_documento')));
				$razon_social=$this->security->xss_clean(strip_tags($this->input->post('razon_social')));
				//$direccion=$this->security->xss_clean(strip_tags($this->input->post('direccion')));
				$email=$this->security->xss_clean(strip_tags($this->input->post('email')));
				$telefono=$this->security->xss_clean(strip_tags($this->input->post('telefono')));
				$celular=$this->security->xss_clean(strip_tags($this->input->post('celular')));
				$representante=$this->security->xss_clean(strip_tags($this->input->post('representante')));
				$localidad=$this->security->xss_clean(strip_tags($this->input->post('localidad')));
				$tienda=$this->security->xss_clean(strip_tags($this->input->post('tienda')));

				$inputNroCliente=$this->security->xss_clean(strip_tags($this->input->post('inputNroCliente')));
				$inputDomPost=$this->security->xss_clean(strip_tags($this->input->post('inputDomPost')));
				$id = $this->input->post("id_oculto", true);

				$inputCuit=$this->security->xss_clean(strip_tags($this->input->post('inputCuit')));
				$inputIVA=$this->security->xss_clean(strip_tags($this->input->post('inputIVA')));
				$inputObservacion=$this->security->xss_clean(strip_tags($this->input->post('inputObservacion')));
				$tipo_persona = $this->input->post("inputTipoPersona", true);

		//coenxion
				$inputDomSum=$this->security->xss_clean(strip_tags($this->input->post('inputDomSum')));
				$inputSector=$this->security->xss_clean(strip_tags($this->input->post('inputsector')));
				$inputtipo_conexion=$this->security->xss_clean(strip_tags($this->input->post('tipo_conexion')));

				$datos_modificados = array(
						'Cli_Id' => null,
						'Cli_TipoPersona' => $tipo_persona,
						'Cli_TipoDoc' => $tipo_doc,
						'Cli_NroDocumento' => $nro_documento,
						'Cli_NroCliente' =>$inputNroCliente,
						'Cli_RazonSocial' =>  $razon_social,
						//'Cli_DomicilioSuministro' => 	$direccion,
						'Cli_DomicilioPostal' => 		$inputDomPost,
						'Cli_Email' => 			$email,
						'Cli_Telefono' => 		$telefono,
						'Cli_Celular' => 	$celular,
						'Cli_Representante' => 	$representante,
						'Cli_Localidad' =>		$localidad,
						'Cli_Tienda' =>		$id,
						'Cli_Cuit' =>	$inputCuit,			
						'Cli_Deudor' =>	0,
						'Cli_TipoIVAId' => 		$inputIVA,
						'Cli_Observacion' =>	$inputObservacion,
						'Cli_Habilitacion' =>1,
						'Cli_Borrado' =>0,
						'Cli_Timestamp' =>null
					);

				//COMPROBAMOS QUE TODOS LOS CAMPOS TENGAN DATOS

				if(isset($razon_social) && !empty($razon_social) && isset($nro_documento) && !empty($nro_documento)){

					//SI LOS CAMPOS ESTAN CORRECTOS LOS INSERTAMOS EN LA BASE DE DATOS
					//LLAMAMOS AL MODELO Clientes_model QUE SE ENCARGARÁ DE INGRESAR LOS DATOS

					$id_cliente = $this->Crud_model->insert_data("clientes",$datos_modificados);
					
					$datos_conexion = array(
						'Conexion_Id' => null,
						'Conexion_Cliente_Id' => $id_cliente,
						'Conexion_DomicilioSuministro' => $inputDomSum,
						'Conexion_UnionVecinal' => 1,
						'Conexion_Categoria' =>$inputtipo_conexion,
						'Conexion_Sector' =>  $inputSector,
						'Conexion_Latitud' => null,
						'Conexion_Longuitud' => null,
						'Conexion_Observacion' => null,
						'Conexion_Habilitacion' =>1,
						'Conexion_Borrado' =>0,
						'Conexion_Timestamp' =>null
					);

					$id_comexion = $this->Crud_model->insert_data("conexion",$datos_conexion);

					$this->session->set_flashdata('document_status', mensaje('Se Guardó el Cliente','success'));
					redirect(base_url("clientes"));
				}
			}
		endif;
	}

	public function agregar_solo_conexion(){
		$this->load->helper('form');
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:

			$datos['titulo'] = 'Agregar Coenxion';
			$this->load->view('templates/header',$datos);
			$this->load->view('conexiones/agregar_conexion_view');
			$this->load->view('templates/footer');
			$this->load->view('templates/footer_fin');
		endif;
	}

	public function recibir_datos_agregar_solo_conexion(){
		$id_cliente=$this->security->xss_clean(strip_tags($this->input->post('id_cliente')));
		$tipo_conexion=$this->security->xss_clean(strip_tags($this->input->post('tipo_conexion')));
		$direccion=$this->security->xss_clean(strip_tags($this->input->post('inputDomSum')));
		$sector=$this->security->xss_clean(strip_tags($this->input->post('sector')));
		$datos_conexion = array(
			'Conexion_Id' => null,
			'Conexion_Cliente_Id' => $id_cliente,
			'Conexion_DomicilioSuministro' => $direccion,
			'Conexion_UnionVecinal' => 1,
			'Conexion_Categoria' =>$tipo_conexion,
			'Conexion_Sector' =>  $sector,
			'Conexion_Latitud' => null,
			'Conexion_Longuitud' => null,
			'Conexion_Observacion' => null,
			'Conexion_Habilitacion' =>1,
			'Conexion_Borrado' =>0,
			'Conexion_Timestamp' =>null
		);

		$id_comexion = $this->Crud_model->insert_data("conexion",$datos_conexion);
		if($id_comexion)
		{
			$this->session->set_flashdata('aviso','Se guardo crrectamente el cambio');
			$this->session->set_flashdata('tipo_aviso','success');
		}
		else 
		{
			$this->session->set_flashdata('aviso','NO se guardo crrectamente el cambio');
			$this->session->set_flashdata('tipo_aviso','danger');
		}
		redirect(base_url("conexion"), "refresh");
	}
	public function medina()
	{
		for ($i=1, $id_con = 500; $i <= 106 ;  $i++, $id_con ++) { 
			echo "          !Haciendo el ".$i." y Conexion:".$id_con."     /     " ; 
			$datos = array(
				"Conexion_UnionVecinal" => $i,
				"Conexion_Sector" =>  "Medina"
			);
			$resultado = $this->Crud_model->update_data($datos, $id_con, "Conexion","Conexion_Id");
			var_dump($resultado);
		}
	}
}
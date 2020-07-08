<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imprimir extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Crud_model');
		$this->load->model('Impresiones_model');
		$this->load->helper('PDF_helper');
		$this->load->helper('eFPDF_helper');

	}
	public function index(){
	}
	public function factura(){
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			$nro_documento=$this->uri->segment(3);
			$id_factura=$this->Crud_model->get_data_row('facturacion','id',$nro_documento);
			$val['valores']=$id_factura;
			$val['items']=$this->Crud_model->get_data_result('items','id_factura',$id_factura->id_factura);
			$this->load->view("imprimir/template_factura",$val);

		endif;
	}

	public function re_imprimir_orden_trabajo($id_ot)
	{
		$datos["resultado"] = $this->Crud_model->get_data_row("ordentrabajo","OrdenTrabajo_Id",$id_ot);
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		$pdf = new eFPDF();
		$pdf->creando_orden_trabajo($datos);
	}
	public function pasar_observacion_a_factura_id()
	{
		$this->load->model('Nuevo_model');
		$movimientos = $this->Nuevo_model->traer_movimentos();
		$log = null;
		foreach ($movimientos as $key ) {
			if(substr($key->Mov_Observacion, 0, 7) == "Pago Bo") // es una boleta
			{
				$id_factura = explode(":", $key->Mov_Observacion);
				$log .= "     -          ".$id_factura[1]."         -      ";
				$arrayName = array(
					'Mov_Factura_Id' => $id_factura[1],
				);
				$actualizacion_codigo_barra  = $this->Nuevo_model->update_data($arrayName, $key->Mov_Id, "movimiento" ,"Mov_Id");
				$log .= $actualizacion_codigo_barra."                  *       "; 
			}
		}
		var_dump($log);
	}
	public function movimientos_diarios($fecha = 0, $fecha_fin = 0)
	{
		$this->load->model('Facturar_model');
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		if($fecha == 0)
		{
			$movimientos = $this->Facturar_model->buscar_ingresos_y_egresos(date("Y-m-d"));
			$total_ingreso = $this->Facturar_model->buscar_ingresos(date("Y-m-d"));
			$total_egreso = $this->Facturar_model->buscar_egresos(date("Y-m-d"));
		}
		elseif($fecha_fin != 0){
			$movimientos = $this->Facturar_model->buscar_ingresos_y_egresos($fecha, $fecha_fin);
			$total_ingreso = $this->Facturar_model->buscar_ingresos($fecha, $fecha_fin);
			$total_egreso = $this->Facturar_model->buscar_egresos($fecha, $fecha_fin);
		}
		else
		{
			$movimientos = $this->Facturar_model->buscar_ingresos_y_egresos($fecha);
			$total_ingreso = $this->Facturar_model->buscar_ingresos($fecha);
			$total_egreso = $this->Facturar_model->buscar_egresos($fecha);
			
		}
		$total_ingreso = $total_ingreso[0]["Mov_Monto"];
		$total_egreso = $total_egreso[0]["Mov_Monto"];
		if($movimientos == null)
		{
			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;
			$this->load->view('templates/header',$datos);
			$data["mensaje"] = "Sin movimientos registrado para el día de hoy";
			$this->load->view("templates/notificacion_incorrecta_success", $data);
			$this->load->view('templates/footer');
		} 
		else
		{
			$this->load->model('Nuevo_model');
			$aux_codigos = $this->Nuevo_model->get_codigos_movimientos("codigos");
			$pdf = new eFPDF();
			$data["codigos"] = [];
			foreach ($aux_codigos as $key) {
				$data["codigos"] [$key->Codigo_Id] ["nombre"]= $key->Codigo_Descripcion;
				$data["codigos"] [$key->Codigo_Id] ["numero"]= $key->Codigo_Numero;
			}
		//var_dump($movimientos);die();
			//var_dump($movimientos);die();
			$pdf->crear_balance_diario($movimientos, $total_ingreso,$total_egreso, $data);
		}
	}
	public function crear_factura_por_sector()
	{
		$this->load->model('Impresiones_model');
		$sectores = $this->input->post("miselect");
		$dia = date("d");
		// if($dia < 10) // todavia estoy imprimiendo las boletas del mes anterior, xq este aun no se carga
		// 	$datos["resultado"] = $this->Impresiones_model->buscar_lote_por_sectores_anterior($sectores);
		// else 
			$datos["resultado"] = $this->Impresiones_model->buscar_lote_por_sectores($sectores);
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		if($datos["resultado"] == false)
			echo "Sin resultados por el momento" ;
		else
		{

			$pdf = new eFPDF();
			$pdf->probando_tabla_por_lote($datos);
		}
	}
	public function tareas_terminadas(){
		$datos['consulta']=$this->Crud_model->ordenes_trabajo_terminadas();
		//var_dump($datos['consulta']);die();
		$pdf = new eFPDF();
			$pdf->tabla_ordenes_trabajo_terminadas($datos);
		// $this->load->view('templates/header',$datos);
		// $this->load->view('orden_trabajo/orden_trabajo',$datos);
	}
	public function buscar_historial($cliente,$conexion)
	{
		$this->load->model('Impresiones_model');
		$datos["resultado"] = $this->Impresiones_model->buscar_historial_cliente($cliente, $conexion);
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		if($datos["resultado"] == false)
		{
			echo "Sin resultados por el momento" ;
		}
		else
		{
			$pdf = new eFPDF();
			$pdf->tabla_historial($datos);
		}
	}
	
	public function deudas_conexiones()
	{
		$this->load->model('Nuevo_model');
		$datos["resultado"] = $this->Nuevo_model->lista_de_morosos();
		//var_dump($datos["resultado"]);die();
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		if($datos["resultado"] == false)
		{
			echo "Sin resultados por el momento" ;
		}
		else
		{
			$pdf = new eFPDF();
			$pdf->imprimir_morosos($datos);
		}
	}
	public function pp_de_conexiones($mes = 0)
	{
		if($mes == 0)
			$mes = date("m");
		$this->load->model('Nuevo_model');
		$datos["resultado"] = $this->Nuevo_model->lista_de_pp($mes);
		//var_dump($datos["resultado"]);die();
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		if($datos["resultado"] == false)
		{
			echo "Sin resultados por el momento" ;
		}
		else
		{
			$pdf = new eFPDF();
			$pdf->imprimir_pp($datos);
		}
	}


	public function probar_numero()
	{
		$pdf = new eFPDF();
		$pdf->arreglar_numero("69.74000000000001");
	}

	public function sacar_acentos($stringcito)
	{
		$stringcito = str_replace( "%20", " ", $stringcito);//espacio
		$stringcito = str_replace( "%C3%A1", "á", $stringcito);//á
		$stringcito = str_replace( "%C3%A9", "é", $stringcito);//é
		$stringcito = str_replace( "%C3%AD", "í", $stringcito);//í
		$stringcito = str_replace( "%C3%B3", "ó", $stringcito);//ó
		$stringcito = str_replace( "%C3%BA", "ú", $stringcito);//ú
		$stringcito = str_replace( "%C3%B1", "ñ", $stringcito);//ñ

		$stringcito = str_replace( "%C3%81", "Á", $stringcito);//Á
		$stringcito = str_replace( "%C3%89", "É", $stringcito);//É
		$stringcito = str_replace( "%C3%8D", "Í", $stringcito);//Í
		$stringcito = str_replace( "%C3%93", "Ó", $stringcito);//Ó
		$stringcito = str_replace( "%C3%9A", "Ú", $stringcito);//Ú
		$stringcito = str_replace( "%C3%91", "Ñ", $stringcito);//Ú
		return $stringcito;
	}
	public function crear_contrato_conexion($nombre,$dni,$id_conexion,$id_medidor,$domicilio, $id_cliente)
	{
		$nombre = $this->sacar_acentos($nombre); 
		$domicilio =  $this->sacar_acentos($domicilio);
		$pdf = new eFPDF();
		$pdf->crear_contratro_nueva_conexion($nombre,$dni,$id_conexion,$id_medidor,$domicilio, $id_cliente);
	}

	public function crear_orden_de_trabajo_automatica($id_cliente, $domicilio_sum,$id_conexion,$id_orden_recien_insertado,$razon_social)
	{
		$domicilio_sum = $this->sacar_acentos($domicilio_sum);
		$razon_social = $this->sacar_acentos($razon_social);
		$pdf = new eFPDF();
		$pdf->crear_ot_nueva_conexion($id_cliente, $domicilio_sum,$id_conexion,$id_orden_recien_insertado,$razon_social);
	}

	public function crear_factura_por_conexion()
	{
		//Se crea un obdeto de PDF
		//Para hacer uso de los métodos
		$this->load->model('Impresiones_model');
		$conexiones = $this->input->post("miselect");
		//var_dump($conexiones);die();
		$datos["resultado"] = $this->Impresiones_model->buscar_lote_por_conexion($conexiones);
		//var_dump($datos["resultado"]);die();
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
        		//var_dump($nuevafecha,$fecha );die();
		$pdf = new eFPDF();
		$pdf->probando_tabla_por_lote($datos);
	}


	public function probando_tabla($id_factura)
	{
		$this->load->model('Impresiones_model');
		$conexion = $this->Impresiones_model->buscar_conexion($id_factura); // busco solamente el id de la conexion....
		$datos["resultado"] = $this->Impresiones_model->buscar_por_conexion($conexion->Factura_Conexion_Id); // resultado = conexion, clientem plan pago y planmeiddior
		$datos["boleta"] = $this->Impresiones_model->buscar_ultima_boleta($datos["resultado"]->Conexion_Cliente_Id); // boleta es ultima factura
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
		//var_dump(is_float(151.3));die();
		$pdf = new eFPDF();
		$pdf->probando_tabla($datos);
	}

	public function crear_factura_por_conexion_id($id_factura)
	{
		$this->load->model('Impresiones_model');
		$this->load->model('Facturar_model');
		//$conexion = $this->Impresiones_model->buscar_conexion($id_factura); // busco solamente el id de la conexion....
		$datos["boleta"] = $this->Impresiones_model->buscar_ultima_boleta_mejor($id_factura); // boleta es ultima factura
		//var_dump($datos["boleta"]);die();
		$datos["resultado"] = $this->Impresiones_model->buscar_por_conexion($datos["boleta"]->Factura_Conexion_Id); // resultado = conexion, clientem plan pago y planmeiddior
		$datos["medicion"] = $this->Facturar_model->buscar_mediciones_para_una_conexion ($datos["boleta"]->Factura_Conexion_Id); // resultado = conexion, clientem plan pago y planmeiddior
		
		$datos["resultado"]->Medicion_Id = $datos["medicion"]->Medicion_Id;
		$datos["resultado"]->Medicion_Conexion_Id = $datos["medicion"]->Medicion_Conexion_Id;
		$datos["resultado"]->Medicion_Mes = $datos["medicion"]->Medicion_Mes;
		$datos["resultado"]->Medicion_Anio = $datos["medicion"]->Medicion_Anio;
		$datos["resultado"]->Medicion_Anterior = $datos["medicion"]->Medicion_Anterior;
		$datos["resultado"]->Medicion_Actual = $datos["medicion"]->Medicion_Actual;
		$datos["resultado"]->Medicion_Basico = $datos["medicion"]->Medicion_Basico;
		$datos["resultado"]->Medicion_Excedente = $datos["medicion"]->Medicion_Excedente;
		$datos["resultado"]->Medicion_Importe = $datos["medicion"]->Medicion_Importe;
		$datos["resultado"]->Medicion_Mts = $datos["medicion"]->Medicion_Mts;
		// estoy aca
		/*
		object(stdClass)#25 (18) { 
			["Medicion_Id"]=> string(4) "9631" 
			["Medicion_Conexion_Id"]=> string(2) "98" 
			["Medicion_Mes"]=> string(2) "10" 
			["Medicion_Anio"]=> string(4) "2017" 
			["Medicion_Anterior"]=> string(3) "931" 
			["Medicion_Actual"]=> string(3) "953" 
			["Medicion_Basico"]=> string(2) "98" 
			["Medicion_Excedente"]=> string(2) "12" 
			["Medicion_Importe"]=> string(4) "87.6" 
			["Medicion_Mts"]=> string(2) "10" 
			["Medicion_IVA"]=> string(1) "0" 
			["Medicion_Porcentaje"]=> string(1) "0" 
			["Medicion_Tipo"]=> string(1) "0" 
			["Medicion_Recargo"]=> string(1) "0" 
			["Medicion_Observacion"]=> NULL ["Medicion_Habilitacion"]=> string(1) "1" ["Medicion_Borrado"]=> string(1) "0" ["Medicion_Timestamp"]=> string(19) "2017-10-31 19:43:15" }*/
		// a $datos["resultado"] le debo agregar cada campo q tenga $data["medicion"]
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
        //var_dump($nuevafecha,$fecha );die();
		$pdf = new eFPDF();
		$pdf->probando_tabla($datos);
	}

	public function crear_factura_por_conexion_id_bien($id_conexion)
	{
		// ya se tiene el id_ conexion
		// se va a buscar los datos de la medicion
		// se va buscar los clientes
		//var_dump($id_conexion);die();
		$this->load->model('Impresiones_model');
		echo"nuebvo";
		//$datos["fa"] = $this->Crud_model->get_data_row_sin_borrado("conexion","Conexion_Id","Conexion_Borrado",$id_conexion);
		$datos["conexion"] = $this->Impresiones_model->buscar_por_conexion($id_conexion);
		if($datos["conexion"] ==  null )
			echo "no existe la conexion buscada. Comuniquese con el administrador.  <a href='http://localhost/codeigniter'>volver al sistema</a> ";die();	
		$datos["boleta"] = $this->Impresiones_model->buscar_ultima_boleta($datos["conexion"]->Conexion_Cliente_Id);
		if($datos["boleta"] == null )
			echo "no se ha creado ninguna boleta para esta conexion. Hagalo <a href='http://localhost/codeigniter/mediciones/agregar_medicion'>aqui</a>";die();
		$datos["configuracion"] =  $this->Crud_model->get_data("configuracion");
        var_dump($datos["boleta"] );die();
		$pdf = new eFPDF();
		$pdf->crear_factura_por_conexion($datos);
	
	}

	public function crear_recibo_de_pago($id_pago)
	{
		$this->load->model('Impresiones_model');
		//$datos["resultado"] = $this->Impresiones_model->buscar_pago_para_recibo($id_pago);
		$datos["resultado"] = $this->Impresiones_model->buscar_pago_para_recibo_actualizado($id_pago);
		//var_dump($datos["resultado"]);die();
		$datos["nombre"] =$this->session->userdata('nombre');
     	//var_dump($datos["resultado"][0] );die();
     	//echo "hollla";die();
		$pdf = new eFPDF();
		//$pdf->crear_recibo_ingreso($datos,1);
		if($datos["resultado"][0]->Mov_Tipo == 2)
			$pdf->crear_recibo_egreso($datos,1);
		else 
			$pdf->crear_recibo_ingreso_actualizado($datos,1);
		
	}

		public function crear_recibo_de_movimiento($id_mov)
	{
		/*
		busco el movimiento que quiero imprimir, y luego lo mando a q se cree el pdf*/
		$this->load->model('Nuevo_model');
		$datos["resultado"] = $this->Nuevo_model->buscar_movimiento_para_imprimir($id_mov);
	//	var_dump($datos["resultado"][0]->Conexion_Id);die();
		$datos["nombre"] =$this->session->userdata('nombre');
		$pdf = new eFPDF();
		$pdf->crear_recibo_movimiento_nuevo($datos,1);
	}

	public function crear_recibo_de_pago_acuenta($id_pago)
	{
		$this->load->model('Impresiones_model');
		$datos["resultado"] = $this->Impresiones_model->buscar_pago_para_recibo_acuenta($id_pago);
		//var_dump($datos["resultado"]);die();
		$datos["nombre"] =$this->session->userdata('nombre');
		$pdf = new eFPDF();
		$pdf->crear_recibo_ingreso($datos,2);
	}


	public function crear_recibo_de_pago_medidor_nuevo($id_recibo, $razon_social, $precio)
	{
		$razon_social = $this->sacar_acentos($razon_social);
		//var_dump($id_recibo, $razon_social, $precio);die();
		$this->load->model('Impresiones_model');

		$datos["resultado"] = $this->Impresiones_model->buscar_pago_para_recibo_actualizado($id_recibo);
	//	var_dump($datos["resultado"]);die();
		$datos["nombre"] =$this->session->userdata('nombre');
		$datos["id_recibo"] =$id_recibo;
		$datos["razon_social"] =$razon_social;
		$datos["precio"] =$precio;
        //var_dump($nuevafecha,$fecha );die();
		$pdf = new eFPDF();
		$pdf->crear_recibo_ingreso_por_medidor_nuevo($datos);
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// CONTROLADOR INVENTARIO
//////////////////////////

class Inventario extends CI_Controller {
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
		// agregar breadcrumbs
		$this->breadcrumbs->push('Dashboard', '/');
		$this->breadcrumbs->push('Productos', '/inventario');

		// salida
		$datos['bread']=$this->breadcrumbs->show();

		$segmentos_totales=$this->uri->total_segments();
		$datos['segmentos']=$segmentos_totales;
		// ./ agregar breadcrumbs

		$datos['titulo']= "Inventario";//Titulo de la página
		$datos['consulta']=$this->Crud_model->get_data_join_sin_borrados("medidor","tmedidor","Medidor_Borrado", "Medidor_TMedidor_Id", "TMedidor_Id" );
		$datos['mensaje'] = $this->session->flashdata('aviso');
		
		$this->load->view('templates/header',$datos);
		$this->load->view('inventario/inventario_vista',$datos);
		$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');

		

		//if($datos ['aviso'] != null)
		//	$this->load->view("templates/notificacion_view", $datos);
		endif;

		
		
	}

public function borrar_medidor()
	{
		$id=  $this->input->post("id");
		$data = array('Medidor_Borrado' => 1, );
		$resultado =  $this->Crud_model->borrar_cliente($data,$id, "medidor", "Medidor_Id");
		echo true;
	}
public function editar_medidor($id_medidor){
			//echo "llegue del formulario";die();
		if (!$this->session->userdata('login') || $this->uri->segment(3) == FALSE):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			$datos['medidor'] = $this->Crud_model->get_data_row('medidor','Medidor_Id',$id_medidor);
			$datos['tipos'] = $this->Crud_model->get_data('tmedidor');
			//var_dump($datos['tipos'] );die();
			$datos['url'] =base_url()."inventario/guardar_cambios_medidor";
			if ($datos['medidor']) {
				$datos['titulo'] = "Editar Medidor";
				$this->load->view('templates/header', $datos);
				$this->load->view('inventario/agregar', $datos);
				$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');
			}else{
				$this->session->set_flashdata("document_status",mensaje("El Meididor no existe","danger"));
				redirect('inventario');
			}
		endif;
	}

	public function modificar_medidor(){
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			if($this->input->post())
			{

				$codigo_producto = $this->input->post("codigo_producto", true);

				$tipo = $this->input->post("tipo_medidor", true);
				$precio = $this->input->post("inputPrecio", true);
				$rep = $this->input->post("rep_oculto", true);
				$intervenciones = $this->input->post("inputIntervenciones", true);
				$observaciones = $this->input->post("inputObservacion", true);
				$hab = $this->input->post("hab_oculto", true);
				if($rep === "true")
					$rep =1;
					else $rep =0;
				if($hab === "true")
					$hab =1;
				else $hab =0;
				//var_dump($rep);die();
				$id_medidor = $this->input->post("id", true);
				$datos_viejos  = $this->Crud_model->get_data_row("medidor", "Medidor_Id", $id_medidor);
				$fechs_instalacion = $this->input->post("finstalacion", true);
				if($fechs_instalacion != null || $fechs_instalacion != 0 || $fechs_instalacion != "00/00/0000" )
				{
					$date = str_replace('/', '-', $fechs_instalacion);
					$fecha_ins = date( "Y-m-d H:i:s", strtotime($date) );
				}
				else $fecha_ins = $datos_viejos->Medidor_FechaInstalacion;
				if($codigo_producto == null || $codigo_producto == 0 || $codigo_producto == "")
					$codigo_producto  = $datos_viejos->Medidor_Codigo;
				if($tipo == null || $tipo == 0  || $tipo == "")
					$tipo = $datos_viejos->Medidor_TMedidor_Id;
					
				$datos_medidor = array(
					'Medidor_Codigo' => $codigo_producto, 
					'Medidor_TMedidor_Id' => $tipo, 
					'Medidor_FechaInstalacion' => $fecha_ins, 
					//'Medidor_Codigo' => $precio, 
					'Medidor_EnReparacion' => $rep, 
					'Medidor_CantIntervenido' => $intervenciones, 
					'Medidor_Observacion' => $observaciones,
					'Medidor_Habilitacion' => $hab
					);
				$this->Crud_model->update_data($datos_medidor,$id_medidor, "medidor", "Medidor_Id");
				$this->session->set_flashdata('aviso', mensaje('Se modifico el medidor correctamente', 'success'));
				redirect(base_url('inventario'));
			}
		endif;
	}


	public function agregar()
	{
		if (!$this->session->userdata('login') ):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			//obtiene la informacion del cliente en la base de datos
			if($this->input->post())
			{
				$codigo_producto = $this->input->post("codigo_producto", true);
				$tipo = $this->input->post("tipo_medidor", true);
				$precio = $this->input->post("inputPrecio", true);
				$rep = $this->input->post("rep_oculto", true);
				$intervenciones = $this->input->post("inputIntervenciones", true);
				$observaciones = $this->input->post("inputObservacion", true);
				$hab = $this->input->post("hab_oculto", true);
				if($rep === "true")
					$rep =1;
					else $rep =0;
				if($hab === "true")
					$hab =1;
				else $hab =0;
				$id_medidor = $this->input->post("id", true);
				$datos_viejos  = $this->Crud_model->get_data_row("medidor", "Medidor_Id", $id_medidor);
				$fechs_instalacion = $this->input->post("finstalacion", true);
				if($fechs_instalacion != null || $fechs_instalacion != 0 || $fechs_instalacion != "00/00/0000" )
				{
					$date = str_replace('/', '-', $fechs_instalacion);
					$fecha_ins = date( "Y-m-d H:i:s", strtotime($date) );
				}
				else $fecha_ins = $datos_viejos->Medidor_FechaInstalacion;
				if($codigo_producto == null || $codigo_producto == 0 || $codigo_producto == "")
					$codigo_producto  = $datos_viejos->Medidor_Codigo;
				if($tipo == null || $tipo == 0  || $tipo == "")
					$tipo = $datos_viejos->Medidor_TMedidor_Id;
					
				$datos_medidor = array(
					'Medidor_Id' => null,
					'Medidor_Codigo' => $codigo_producto,
					'Medidor_TMedidor_Id' => $tipo,
					'Medidor_FechaInstalacion' => $fecha_ins,
					'Medidor_EnReparacion' => $rep,
					'Medidor_CantIntervenido' => $intervenciones,
					'Medidor_Observacion' => $observaciones,
					'Medidor_Habilitacion' => 1,
					'Medidor_Borrado' => 0,
					'Medidor_Timestamp' => null
					);
				$this->Crud_model->insert_data("medidor",$datos_medidor);
				$this->session->set_flashdata('aviso', mensaje('Se agrego el medidor correctamente', 'success'));
				redirect(base_url('inventario'));
			}
		endif;
	}




	public function agregar_producto(){
		if (!$this->session->userdata('login')):
			$this->session->set_flashdata('mensaje','Debes Iniciar Sesion');
			redirect(base_url());
		else:
			// agregar breadcrumbs
			$this->breadcrumbs->push('Dashboard', '/');
			$this->breadcrumbs->push('Productos', '/inventario');
			$this->breadcrumbs->push('Agregar Producto', '/inventario/agregar_producto');

			// salida
			$datos['bread']=$this->breadcrumbs->show();

			$segmentos_totales=$this->uri->total_segments();
			$datos['segmentos']=$segmentos_totales;

			// ./ agregar breadcrumbs

			$datos['tipos'] = $this->Crud_model->get_data('tmedidor');

			$datos['titulo']="Agregar Nuevo Producto";
			$this->load->view('templates/header',$datos);
			$this->load->view('inventario/agregar');
			$this->load->view('templates/footer');
$this->load->view('templates/footer_fin');

			$btn_enviar = $this->input->post('enviar');
				if (isset($btn_enviar)) {
				
				//asignamos una variable a cada campo

				$sku=$this->security->xss_clean(strip_tags($this->input->post('codigo_producto')));
				$nombre_producto=$this->security->xss_clean(strip_tags($this->input->post('nombre_producto')));
				$cantidad=$this->security->xss_clean(strip_tags($this->input->post('cantidad')));
				$precio=$this->security->xss_clean(strip_tags($this->input->post('precio')));

				$data = array(
					'sku' 				=> $sku,
					'nombre_producto' 	=> $nombre_producto,
					'cantidad'			=> $cantidad,
					'precio_unit'		=> $precio,
				 );

				//comprobamos que los campos no esten vacios

					if( isset($sku) && !empty($sku) && isset($nombre_producto) && !empty($nombre_producto) 
						&& isset($cantidad) && !empty($cantidad) && isset($precio) && !empty($precio) ){

						$this->Crud_model->insert_data("productos", $data);
						$this->session->set_flashdata('document_status', mensaje('Se guardo el nuevo producto','success'));
						redirect(base_url('inventario'));
					}
			}

		endif;
	}
}
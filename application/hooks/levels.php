<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /** classes and methods that dont' require users to log in */
 $noLogin = array(
  'index'=>array()
 );
 $noLogin['login']['logout_ci'] = true;
 $noLogin['login']['index'] = true;
 $noLogin['login']['acceso_indebido'] = true;
 $noLogin['acceso_restringido']['index'] = true;

 $privs =array();

 /** admin, user id = 1, dont' need to define anything since all is allowed */

 /** non-admin, usertype_id = 2 , list the class and method that non-admin have no access to. */
// "2" is the usertype_id on database
 //$privs[2]['account']['add_account'] = true; //literally means: user with usertype_id = 2 don't have access to method "add_account" on "account" class/controller

// $privs['generador']['generador']['modificar_generador'] = true; //literally means: user with perfil = 'generador' don't have access to method "modificar_generador" on "generador" class/controller
 	//   perfil        controller      clase



/*
 Ajax
	login
	comprobar_correlativo

bonificacion
	index
	cargar_aprobadas
	cargar_otorgadas
	borrar_bonificacion
	modificar_bonificacion
	guardar_agregar
	agregar_bonificacion

Buscador
	index
	registrando
	cifrar
	decifrar
	traer
	buscar_codigo_foto
	buscar_codigo_foto_id
	calcular_id
	descalcular_id
	guardar_foto
	buscar_codigo
	cargar_masivo


Clientes
	index
	leer_clientes
	guardar_cambios_cliente
	borrar_cliente
	agregar
	agregar_nuevo


Conexion
	index
	editar
	modificar
	borrar_conexion
	agregar

Configuracion
	index
	editar_variable
	configuracion_guardar_modificado
	ver_variable
	leer_clientes
	guardar_cambios_cliente
	borrar_cliente
	agregar


deuda
	index
	guardar_agregar
	borrar_deuda
	editar_deuda
	agregar_deuda

Facturar
	index
	crear
	crear_nueva
	editar_factura
	anular_doc
	buscar_datos_crear_factura
	buscar_factura
	buscar_datos_factura
	actualizar_documento
	guardar_datos_factura


Imprimir
	factura
	movimientos_diarios
	crear_factura_por_sector
	crear_contrato_conexion
	crear_orden_de_trabajo_automatica
	crear_factura_por_conexion
	crear_factura_por_conexion_id
	crear_recibo_de_pago
	crear_recibo_de_pago_medidor_nuevo


Inventario
	index
	borrar_medidor
	editar_medidor
	modificar_medidor
	agregar
	agregar_producto


materiales
	index
	borrar_materiales
	editar_material
	modificar_bonificacion
	guardar_agregar
	agregar_materiales



Mediciones
	index
	borrar_mediciones
	ejecutar_query
	cargar_mediciones_por_lote
	modificar_medicion
	guardar_lote_medicion
	guardar_agregar
	leer_conexiones
	agregar_medicion

Movimientos
	index
	ingresos
	egresos
	editar_ingreso
	borrar_movimiento
	guardar_movimiento

Orden_trabajo
	index
	borrar_orden_trabajo
	modificar_orden_trabajo
	editar_usuarios
	modificar_bonificacion
	guardar_agregar
	guardar_desde_ajax
	agregar_orden_trabajo
	

Pago
	index
	nuevo
	crear_factura_por_lote
	boleta
	ver_codigo
	crear_factura
	crear_factura_por_sector
	crear_factura_por_conexion
	barcode
	set_barcode
	leer_clientes
	guardar_cambios_cliente
	llenar_modal_bonificacion_nuevo
	guardar_plan_pago
	borrar_cliente
	datos_envios
	guardar_pago_nuevo
	agregar
	agregar_por_codigo_barra
	modificar_pago
	agregar_plan_pago_por_parametros




Usuarios
	index
	borrar_usuarios
	editar_usuarios
	puedo_editar_usuario
	modificar_tmedidor
	guardar_agregar
	agregar_usuario
*/


//Secretaria


$privs['secretaria']['Ajax']['login'] = true; 
$privs['secretaria']['Ajax']['comprobar_correlativo'] = true; 


$privs['secretaria']['ajax']['login'] = true; 
$privs['secretaria']['ajax']['comprobar_correlativo'] = true; 


$privs['secretaria']['bonificacion']['index'] = true; 
$privs['secretaria']['bonificacion']['cargar_aprobadas'] = true; 
$privs['secretaria']['bonificacion']['cargar_otorgadas'] = true; 
$privs['secretaria']['bonificacion']['borrar_bonificacion'] = true; 
$privs['secretaria']['bonificacion']['modificar_bonificacion'] = true; 
$privs['secretaria']['bonificacion']['guardar_agregar'] = true; 
$privs['secretaria']['bonificacion']['agregar_bonificacion'] = true; 



$privs['secretaria']['Buscador']['index'] = true; 
$privs['secretaria']['Buscador']['registrando'] = true; 
$privs['secretaria']['Buscador']['cifrar'] = true; 
$privs['secretaria']['Buscador']['decifrar'] = true; 
$privs['secretaria']['Buscador']['traer'] = true; 
$privs['secretaria']['Buscador']['buscar_codigo_foto'] = true; 
$privs['secretaria']['Buscador']['buscar_codigo_foto_id'] = true; 
$privs['secretaria']['Buscador']['calcular_id'] = true; 
$privs['secretaria']['Buscador']['descalcular_id'] = true; 
$privs['secretaria']['Buscador']['guardar_foto'] = true; 
$privs['secretaria']['Buscador']['buscar_codigo'] = true; 
$privs['secretaria']['Buscador']['cargar_masivo'] = true; 


// $privs['secretaria']['Clientes']['index'] = true; 
// $privs['secretaria']['Clientes']['leer_clientes'] = true; 
// $privs['secretaria']['Clientes']['guardar_cambios_cliente'] = true; 
// $privs['secretaria']['Clientes']['borrar_cliente'] = true; 
// $privs['secretaria']['Clientes']['agregar'] = true; 
// $privs['secretaria']['Clientes']['agregar_nuevo'] = true; 




$privs['secretaria']['conexion']['index'] = true; 
$privs['secretaria']['conexion']['editar'] = true; 
$privs['secretaria']['conexion']['modificar'] = true; 
$privs['secretaria']['conexion']['borrar_conexion'] = true; 
$privs['secretaria']['conexion']['agregar'] = true; 


$privs['secretaria']['configuracion']['index'] = true; 
$privs['secretaria']['configuracion']['editar_variable'] = true; 
$privs['secretaria']['configuracion']['configuracion_guardar_modificado'] = true; 
$privs['secretaria']['configuracion']['ver_variable'] = true; 
$privs['secretaria']['configuracion']['leer_clientes'] = true; 
$privs['secretaria']['configuracion']['guardar_cambios_cliente'] = true; 
$privs['secretaria']['configuracion']['borrar_cliente'] = true; 
$privs['secretaria']['configuracion']['agregar'] = true; 


$privs['secretaria']['deuda']['index'] = true; 
$privs['secretaria']['deuda']['guardar_agregar'] = true; 
$privs['secretaria']['deuda']['borrar_deuda'] = true; 
$privs['secretaria']['deuda']['editar_deuda'] = true; 
$privs['secretaria']['deuda']['agregar_deuda'] = true; 


$privs['secretaria']['facturar']['index'] = true; 
$privs['secretaria']['facturar']['crear'] = true; 
$privs['secretaria']['facturar']['crear_nueva'] = true; 
$privs['secretaria']['facturar']['editar_factura'] = true; 
$privs['secretaria']['facturar']['anular_doc'] = true; 
$privs['secretaria']['facturar']['buscar_datos_crear_factura'] = true; 
$privs['secretaria']['facturar']['buscar_factura'] = true; 
$privs['secretaria']['facturar']['buscar_datos_factura'] = true; 
$privs['secretaria']['facturar']['actualizar_documento'] = true; 
$privs['secretaria']['facturar']['guardar_datos_factura'] = true; 



$privs['secretaria']['imprimir']['factura'] = true; 
$privs['secretaria']['imprimir']['movimientos_diarios'] = true; 
$privs['secretaria']['imprimir']['crear_factura_por_sector'] = true; 
$privs['secretaria']['imprimir']['crear_contrato_conexion'] = true; 
$privs['secretaria']['imprimir']['crear_orden_de_trabajo_automatica'] = true; 
$privs['secretaria']['imprimir']['crear_factura_por_conexion'] = true; 
$privs['secretaria']['imprimir']['crear_factura_por_conexion_id'] = true; 
$privs['secretaria']['imprimir']['crear_recibo_de_pago'] = true; 
$privs['secretaria']['imprimir']['crear_recibo_de_pago_medidor_nuevo'] = true; 



$privs['secretaria']['inventario']['index'] = true; 
$privs['secretaria']['inventario']['borrar_medidor'] = true; 
$privs['secretaria']['inventario']['editar_medidor'] = true; 
$privs['secretaria']['inventario']['modificar_medidor'] = true; 
$privs['secretaria']['inventario']['agregar'] = true; 
$privs['secretaria']['inventario']['agregar_producto'] = true; 



$privs['secretaria']['materiales']['index'] = true; 
$privs['secretaria']['materiales']['borrar_materiales'] = true; 
$privs['secretaria']['materiales']['editar_material'] = true; 
$privs['secretaria']['materiales']['modificar_bonificacion'] = true; 
$privs['secretaria']['materiales']['guardar_agregar'] = true; 
$privs['secretaria']['materiales']['agregar_materiales'] = true; 


$privs['secretaria']['mediciones']['index'] = true; 
$privs['secretaria']['mediciones']['borrar_mediciones'] = true; 
$privs['secretaria']['mediciones']['ejecutar_query'] = true; 
$privs['secretaria']['mediciones']['cargar_mediciones_por_lote'] = true; 
$privs['secretaria']['mediciones']['modificar_medicion'] = true; 
$privs['secretaria']['mediciones']['guardar_lote_medicion'] = true; 
$privs['secretaria']['mediciones']['guardar_agregar'] = true; 
$privs['secretaria']['mediciones']['leer_conexiones'] = true; 
$privs['secretaria']['mediciones']['agregar_medicion'] = true; 

$privs['secretaria']['movimientos']['index'] = true; 
$privs['secretaria']['movimientos']['ingresos'] = true; 
$privs['secretaria']['movimientos']['egresos'] = true; 
$privs['secretaria']['movimientos']['editar_ingreso'] = true; 
$privs['secretaria']['movimientos']['borrar_movimiento'] = true; 
$privs['secretaria']['movimientos']['guardar_movimiento'] = true; 



$privs['secretaria']['orden_trabajo']['index'] = true; 
$privs['secretaria']['orden_trabajo']['borrar_orden_trabajo'] = true; 
$privs['secretaria']['orden_trabajo']['modificar_orden_trabajo'] = true; 
$privs['secretaria']['orden_trabajo']['editar_usuarios'] = true; 
$privs['secretaria']['orden_trabajo']['modificar_bonificacion'] = true; 
$privs['secretaria']['orden_trabajo']['guardar_agregar'] = true; 
$privs['secretaria']['orden_trabajo']['guardar_desde_ajax'] = true; 
$privs['secretaria']['orden_trabajo']['agregar_orden_trabajo'] = true; 




$privs['secretaria']['pago']['index'] = false; 
$privs['secretaria']['pago']['nuevo'] = true; 
$privs['secretaria']['pago']['crear_factura_por_lote'] = true; 
$privs['secretaria']['pago']['boleta'] = true; 
$privs['secretaria']['pago']['ver_codigo'] = true; 
$privs['secretaria']['pago']['crear_factura'] = true; 
$privs['secretaria']['pago']['crear_factura_por_sector'] = true; 
$privs['secretaria']['pago']['crear_factura_por_conexion'] = true; 
$privs['secretaria']['pago']['barcode'] = true; 
$privs['secretaria']['pago']['set_barcode'] = true; 
$privs['secretaria']['pago']['leer_clientes'] = true; 
$privs['secretaria']['pago']['guardar_cambios_cliente'] = true; 
$privs['secretaria']['pago']['llenar_modal_bonificacion_nuevo'] = true; 
$privs['secretaria']['pago']['guardar_plan_pago'] = true; 
$privs['secretaria']['pago']['borrar_cliente'] = true; 
$privs['secretaria']['pago']['datos_envios'] = true; 
$privs['secretaria']['pago']['guardar_pago_nuevo'] = true; 
$privs['secretaria']['pago']['agregar'] = true; 
$privs['secretaria']['pago']['agregar_por_codigo_barra'] = true; 
$privs['secretaria']['pago']['modificar_pago'] = true; 
$privs['secretaria']['pago']['agregar_plan_pago_por_parametros'] = true; 
$privs['secretaria']['pago']['datos_personales_para_pago'] = true; 



$privs['secretaria']['notificacion']['sin_permiso'] = true; 
$privs['secretaria']['notificacion']['sin_logear'] = true; 


$privs['secretaria']['usuarios']['index'] = true; 
$privs['secretaria']['usuarios']['borrar_usuarios'] = true; 
$privs['secretaria']['usuarios']['editar_usuarios'] = true; 
$privs['secretaria']['usuarios']['puedo_editar_usuario'] = false; 
$privs['secretaria']['usuarios']['modificar_tmedidor'] = true; 
$privs['secretaria']['usuarios']['guardar_agregar'] = true; 
$privs['secretaria']['usuarios']['agregar_usuario'] = true; 



$privs['secretaria']['api']['test'] = true; 

/*$privs['generador']['generador']['modificar_generador'] = true; 
/*$privs['generador']['generador']['modificar_transportista'] = true; 
/*$privs['generador']['generador']['transportista_modificado'] = true; 



$privs['generador']['generador']['generador_modificado'] = true; */



<?php 
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0');
header('Content-Encoding: UTF-8');
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel;charset=UTF-8'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel;charset=UTF-8'); // This should work for the rest 
header('Content-Disposition: attachment; filename="nombre.xls"');
echo '<table xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
		<tr>
			<th>Tipo Documento</th>
			<th>Nro Documento</th>
			<th>Razon Social</th>
			<th>Moneda</th>
			<th>Monto</th>
			<th>Fecha</th>
			<th>Estado</th>
		</tr>
		<tr>
			<td>Factura</td>
			<td>00000000</td>
			<td>Cliente de Prueba para Reporte</td>
			<td>Soles</td>
			<td>300.00</td>
			<td>29/12/15</td>
			<td>Activo</td>
		</tr>
	</table>';

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Imprimir Documento</title>
	<script>
	window.print();
	</script>
</head>
<style type="text/css">
.tabla {
	background-image: url(../../img/templates-img/bgfactura2.png);
	background-repeat: no-repeat;
	background-position: center top;
	background-size:cover;
	height:440px;
}
/*table th,td { border:0px solid #03F; }*/
*{
	font-family: "Consolas", Courier, monospace;
	font-size: 11px;
}
.ncliente{padding-left:110px; text-transform:uppercase;}
.ruc{padding-left:70px;}
.direccion{padding-left:110px; text-transform:uppercase;}
.numletras{padding-left:90px;}
.subtotal{padding-left:106px;}
.igv{padding-left:106px;}
.total{padding-left:106px;}
#productos { height: 253px;}


@media print{
	*{font-size: 7.2pt!important;}
	.tabla {height:7.1cm;margin-top:2.1cm;}
	
	table .rfecha td,
	table .rcliente td,
	table .rdireccion td,
	table .numletras,
	table .row-footer {height: 0.4cm;}

	table #productos {background:#09C;padding:0;height: auto;}
	table .rproductos td {height: 0.5cm;padding:0;}
	
	table table {background:#F00;}
}
</style>

<?php $meses=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"] ?>
<body>
<table width="810" align="center" cellpadding="0" cellspacing="0" class="tabla">
  <tr>
    <td height="25" colspan="2">&nbsp;</td>
    <td width="255">&nbsp;</td>
    <td width="248"><?php echo $valores->serie;?> - <?php echo $valores->correlativo;?></td>
  </tr>
  <tr class="rfecha">
    <td width="98" height="32" align="right" valign="bottom" class="dia"><?php echo cutString($valores->fecha_emision,"/",0) ?></td>
    <td width="207" align="center" valign="bottom"><?php echo cutString($valores->fecha_emision,"/",1,$meses) ?></td>
    <td valign="bottom"><?php echo cutString($valores->fecha_emision,"/",2) ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr class="rcliente">
    <td class="ncliente" height="25" colspan="3" valign="bottom"><?php echo $valores->razon_social;?></td>
    <td class="ruc" valign="bottom">20145986521</td>
  </tr>
  <tr class="rdireccion">
    <td class="direccion" height="24" colspan="3" valign="bottom">Direccion del cliente</td>
    <td valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" valign="top" id="productos">
    <table width="735" align="center" cellpadding="0" cellspacing="0">
      <tr class="rproductos">
        <td height="27" colspan="4" align="center">&nbsp;</td>
      </tr>
      <?php if(count($items) > 0 && $items != FALSE): ?>
      	<?php foreach ($items as $productoValor):?>
      <tr class="rproductos">
        <td width="74" height="32" align="center"><?php echo $productoValor->cantidad; ?></td>
        <td width="474"><?php echo $productoValor->producto; ?></td>
        <td width="88" align="center"><?php echo $productoValor->precio_unit;?></td>
        <td width="97" align="center"><?php echo $productoValor->precio;?></td>
      </tr>
      	<?php endforeach; ?>
      <?php else: ?>
      <tr>
      	<td>
      			No hay Productos
      	</td>
      </tr>
      <?php endif; ?>
    </table></td>
  </tr>
    <?php $igv= $valores->monto * 18 /100;?>
  	<?php $subtotal= $valores->monto - $igv;?>
  <tr class="row-footer">
    <td class="numletras" colspan="3"><?php echo num_to_letras($valores->monto)?></td>
    <td class="subtotal"><?php echo number_format($subtotal,2); ?></td>
  </tr>
  <tr class="row-footer">
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="igv"><?php echo number_format($igv,2); ?></td>
  </tr>
  <tr class="row-footer">
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="total"><?php echo $valores->monto; ?></td>
  </tr>
</table>
</body>
</html>
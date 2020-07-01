<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Preview Documento</title>
</head>
<body>
	<div style="width:850px;margin:0 auto;">
		<canvas id="canvasDocument" width="850" height="650">
			Su Navegador no Soporta HTML5, necesita actualizarse.
		</canvas>
	</div>
</body>
<?php 
$meses=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
?>
<script>
//CANVAS PREVIEW DOCUMENT
var img = new Image();
img.src = "<?php echo base_url('user-content/bgfactura.jpg') ?>";
var canvas = document.getElementById('canvasDocument');

//Accedo al contexto de '2d' de este canvas, necesario para dibujar
var contexto = canvas.getContext('2d');
img.onload = function(){
contexto.drawImage(img, 0, 0);
contexto.font = "bold 14px consolas";
contexto.fillText("<?php echo $valores->serie;?> - <?php echo $valores->correlativo; ?>",600,170);
contexto.fillText("<?php echo $cliente->Cli_RazonSocial; ?>",125,230);
contexto.fillText("<?php echo $cliente->Cli_NroDocumento; ?>",660,230);
contexto.fillText("<?php echo $cliente->Cli_DomicilioPostal; ?>",125,260);
contexto.fillText("<?php echo cutString($valores->fecha_emision,'/',0); ?>",90,205);
contexto.fillText("<?php echo cutString($valores->fecha_emision,'/',1,$meses); ?>",170,205);
contexto.fillText("<?php echo cutString($valores->fecha_emision,'/',2); ?>",350,205);
<?php if(count($items) > 0 && $items != FALSE): ?>
	<?php for ($y="277",$x=0;$x < count($items);$x++,$y){ 
		$y=$y+"33";
	?>
contexto.fillText("<?php echo $items[$x]['cantidad']; ?>",60,<?php echo $y; ?>);
contexto.fillText("<?php echo $items[$x]['producto']; ?>",120,<?php echo $y; ?>);
contexto.fillText("<?php echo $items[$x]['precio_unit']; ?>",635,<?php echo $y; ?>);
contexto.fillText("<?php echo $items[$x]['precio']; ?>",720,<?php echo $y; ?>);
		<?php } ?>
<?php else: ?>
contexto.fillText("No hay productos para este documento",120,310);
<?php endif; ?>

// contexto.fillText("4",60,<?php echo $y+"33"; ?>);
// contexto.fillText("Putas y lesbianas",120,<?php echo $y+"33"; ?>);
// contexto.fillText("450.00 ",635,<?php echo $y+"33"; ?>);
// contexto.fillText("1800.00",720,<?php echo $y+"33"; ?>);

contexto.fillText("<?php echo cutString($valores->fecha_emision,'/',0); ?>",215,605);
contexto.fillText("<?php echo cutString($valores->fecha_cancelacion,'/',1,$meses); ?>",265,605);
contexto.fillText("<?php echo cutString($valores->fecha_emision,'/',2); ?>",375,605);
contexto.fillText("<?php echo num_to_letras($valores->monto)?>",95,544);
contexto.fillText("<?php echo get_totals($valores->igv,$valores->monto,0); ?>",710,540);
contexto.fillText("<?php echo get_totals($valores->igv,$valores->monto,1); ?>",710,570);
contexto.fillText("<?php echo get_totals($valores->igv,$valores->monto,2); ?>",710,605);
}
</script>
</html>
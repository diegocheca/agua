<script type="text/javascript">
var mensaje = <?php if ($resultado) echo "Cliente modificado correctamente"; else echo "El cliente no se modifico correctamente";?>
	notify(mensaje, 'inverse',5000);
</script>
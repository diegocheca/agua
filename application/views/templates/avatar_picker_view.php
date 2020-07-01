<html lang="es">
	<head>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url('image-picker/image-picker.css'); ?>"  rel="stylesheet">
	</head>
	<body>
		<br/>
		<div class="container">
			<!-- <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title" id="myModalLabel">Graficos de clientes</h4>
					</div>
					<div class="modal-body">
						<div >
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					</div> -->
			<div class="picker">
				<select class="image-picker show-html">
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar13.png" value="1">  Page 1  </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar02.png" value="2">  Page 2  </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar03.png" value="3">  Page 3  </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar04.png" value="4">  Page 4  </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar05.png" value="5">  Page 5  </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar06.png" value="6">  Page 6  </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar07.png" value="7">  Page 7  </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar08.png" value="8">  Page 8  </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar09.png" value="9">  Page 9  </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar10.png" value="10"> Page 10 </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar11.png" value="11"> Page 11 </option>
					<option data-img-src="<?php echo base_url("image-picker")."/"; ?>avatar12.png" value="12"> Page 12 </option>
				</select>
			</div>
		</div>
		<script src="<?php echo base_url('image-picker/image-picker.js'); ?> "></script>
		<script>
			jQuery("select.image-picker").imagepicker({
				hide_select:  false,
			});
		</script>
	</body>
</html>
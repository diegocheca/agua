<div class="col-sm-12">
	<div class="card z-depth-2">
		<div class="card-header bgm-red">
			<h2>Pagar por Nombre <small>Titular de la conexion - Dni - Número de Conexión</small></h2>
			<ul class="actions">
				<li class="dropdown">
					<a href="" data-toggle="dropdown" aria-expanded="false">
						<i class="zmdi zmdi-more-vert"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-right">
				<li>
					<a href="">Recargar</a>
				</li>
				<li>
					<a href="javascript:void(0)" class="aaafff" id="buscar_historial">Historial</a>
				</li>
				<li>
					<a href="">Other Settings</a>
				</li>
					</ul>
				</li>
			</ul>
		</div>      
		<div class="modal fade" id="modal_historial_cliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Seleccione cliente para ver su historial</h4>
					</div>
					<div class="modal-body">
						<label for="select_material">Datos de la persona:</label>
						<select name="datos_personales_para_historial"  id="datos_personales_para_historial" class="chosen" style="width:100%" data-placeholder="Seleecinar Datos" >
							<option value="-1" selected disabled>Seleccionar</option>
							<?php
							foreach ($conexiones_a_imprimir_conexion as $key ) 
							{
								echo '<option value="'.$key->Conexion_Id.'*'.$key->Cli_Id.'">Nombre: '.$key->Cli_RazonSocial.' DNI: '.$key->Cli_NroDocumento.' N°Conexion: '.$key->Conexion_Id.'</option>';
							} 
							?>
						</select>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						<button type="button" id="buscardor_historial" class="btn btn-primary" >Buscar</button>
					</div>
				</div>
			</div>
		</div>                   
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					<form method="post" action="<?php echo base_url("pago/datos_personales_para_pago");?>" autocomplete="off">
						<br>
						<div class="col-md-9">
							<label for="select_material">Datos de la persona:</label>
							<div class="fg-line select">
								<select name="datos_personales_para_pagar"  id="select_persona" class="chosen" style="width:100%" data-placeholder="Seleecinar Datos" >
									<option value="-1" selected disabled>Seleccionar</option>
									<?php
									foreach ($conexiones_a_imprimir_conexion as $key ) 
									{
										echo '<option value="'.$key->Conexion_Id.'*'.$key->Cli_Id.'">Nombre: '.$key->Cli_RazonSocial.' DNI: '.$key->Cli_NroDocumento.' N°Conexion: '.$key->Conexion_Id.'</option>';
									} 
									?>
								</select>
							</div>
							<br>
						</div>
						<br>
						<div class="col-md-3">
							<div class="row">
								<button type="submit" id="buscar_factura_para_pago" name="buscar_factura_para_pago" style="width:95%" class="btn btn-success waves-effect"><i class="zmdi zmdi-search"></i> Buscar</button>
							</div>
						</div>
						<br>
					</form>
				<br>
				</div>
			</div>
		</div>
</div>
<script type="text/javascript">
$(".aaafff").on("click",function(){
	$("#modal_historial_cliente").modal("show");
});
$("#buscardor_historial").on("click",function(){
	var datos = $("#datos_personales_para_historial").val();
	var arre = datos.split("*");
	var conexion = arre[0];
	var cliente = arre[1];
	var a = document.createElement("a");
	a.target = "_blank";
	a.href = 'http://localhost/codeigniter/imprimir/buscar_historial'+"/"+cliente+"/"+conexion;
	a.click();
	//window.location = 'http://localhost/codeigniter';
	//window.location = ;

	//alert("enviado: "+conexion+" - Cliente: "+cliente);
	

});

</script>
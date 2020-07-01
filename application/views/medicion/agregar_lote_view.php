	<div class="row">
		<div class="card col-md-12">
			<div class="card-header">
				<h2>Agregar Mediciones por Lotes</h2>
			</div>
			<div class="card-body card-padding">
				<div class="card">
					<div class="card-header bgm-lightblue m-b-20">
						<h2>Consulta <small>Seleccione que desea cargar</small></h2>
					</div>
					<form id="form_agregar_medicion_lote" method="POST" target="_blank" action="<?php echo base_url('mediciones/ejecutar_query'); ?>">
						<div class="card-body card-padding">
							<div class="row">
								<div class="col-md-6 col-xs-12">
									<div class="tab-pane fade active in" id="home-pills">
										<label for="select_sector_imprimir">Seleccion Sectores:</label>
										<select name="miselect"  id="select_sector_imprimir" class="chosen" data-placeholder="Elige los sectores" >
										<?php 
										foreach ($sectores as $key) {
											echo '<option value="'.$key->Conexion_Sector.'" >'.$key->Conexion_Sector.'</option>';
										}?>
										</select>
										<br>
									</div>
								</div>
								<div class="col-md-4 col-xs-12">
									<button type="button" id="enviarConsulta" class="btn btn-lg btn-block btn-success">Buscar Sector</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card repeater">
							<div class="card-header bgm-lightblue m-b-20">
								<h2>Datos de las Mediciones <small>Ingresa los valores de las Mediciones</small></h2>
							</div>
							<div class="card-body card-padding">
								<div class="row">
									<form id="formulario_de_mediciones_lote"  action="<?php 
										echo base_url();
										if(isset($bonificacion))
											echo "mediciones/guardar_mediciones_por_lotes_con_ajax";
										else echo "mediciones/guardar_mediciones_por_lotes_con_ajax";
										 ?>" method="post" class="">
										 <input id="cantidad_de_input" name="cantidad_de_input" type="hidden" value="0">
										<div id="resultado_query">
										</div>
										<div class="row">
											<button type="button" name="enviar_formulario_de_mediciones_lote" id="enviar_formulario_de_mediciones_lote" style="width:100%; height:50%" class="btn btn-success">Guardar</button>
										</div>
										<br>
										<br>
										<div class="row">
											<button type="reset"  style="width:100%" class="btn btn-danger">Cancelar</button>
										</div>
										<br>
										<br>
										<div class="row">
											<a href="<?php echo base_url("mediciones");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
										</div>
									</form>		
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<!-- 
<form class="repitoso">
    <!-
        The value given to the data-repeater-list attribute will be used as the
        base of rewritten name attributes.  In this example, the first
        data-repeater-item's name attribute would become group-a[0][text-input],
        and the second data-repeater-item would become group-a[1][text-input]
    ->
    <div data-repeater-list="group-a">
      <div data-repeater-item>
        <input type="text" name="text-input" placeholder="Valor"/>
        <input data-repeater-delete type="button" value="Delete"/>
      </div>
      <div data-repeater-item>
        <input type="text" name="text-input" placeholder="Valor"/>
        <input data-repeater-delete type="button" value="Delete"/>
      </div>
    </div>
    <input data-repeater-create type="button" value="Add"/>
</form> -->
	</div>
	<script src="<?php echo base_url();?>js/validations/validations_agregar_medicion_lote.js"></script>

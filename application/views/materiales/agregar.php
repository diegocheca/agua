	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-6">
			<div class="card-header">
				<h2>Agregar Nuevo Materiales</h2>
			</div>
			<div class="card-body card-padding">
						
				<form id="form_agregar_material" action="<?php 
				echo base_url();
				if(isset($material))
					echo "materiales/guardar_agregar";
				else echo "materiales/guardar_agregar";
				 ?>" method="post" class="">
					<div class="row">
						<div class="col-md-6">
							<label for="inputCodigo">Codigo</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputCodigo" placeholder="Codigo material" type="number" min="1" max="6000" step=any  name="inputCodigo" class="form-control input-sm" required
									<?php 
									if(isset($material))
										echo  'value= "'.$material->Materiales_Codigo.'"';
									 ?>
								>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputCantidad">Cantidad</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputCantidad" placeholder="cantidad" type="number" min="1" max="6000" step=any name="inputCantidad"  class="form-control input-sm" required
									<?php 
									if(isset($material))
										echo  'value= "'.$material->Materiales_Cantidad.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-12">
							<label for="inputDescripcion">Descripcion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputDescripcion" placeholder="Descripcion del material" type="text" maxlength="200" name="inputDescripcion" class="form-control input-sm" required
									<?php 
									if(isset($material))
										echo  'value= "'.$material->Materiales_Descripcion.'"';
									 ?>
									 >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="inputObservacion">Observacion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<input id="inputObservacion" type="text" maxlength="200" name="inputObservacion" name="inputObservacion" class="form-control input-sm"
									<?php  
									if(isset($material))
										echo 'value= "'.$material->Materiales_Observacion.'"';
									 ?>
									 >
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="id" id="id" value="<?php if(isset($material)) echo $material->Materiales_Id; else echo "-1";  ?>" style="display: none">
					<div class="row">
						<div class="col-md-4">
							<button type="submit" name="enviar" style="width:100%" class="btn btn-success">Guardar</button>
						</div>
						<div class="col-md-4">
							<button type="reset"  style="width:100%" class="btn btn-warning">Cancelar</button>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("materiales");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<script src="<?php echo base_url();?>js/validations/validations_agregar_material.js"></script>

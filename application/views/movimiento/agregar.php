	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-6">
			<div class="card-header">
				<h2>Agregar Bonificacion</h2>
			</div>
			<div class="card-body card-padding">
				<form  id ="form_movimiento" action="<?php 
				echo base_url();
				if(isset($movimiento))
					echo "movimientos/guardar_movimiento";
				else echo "movimientos/guardar_movimiento";
				?>" method="post" class="">
					<div class="row">
						<div class="col-md-6">
							<label for="tipo_movimiento">Tipo de movimiento:</label>
							<select id="tipo_movimiento" type="text" name="tipo_movimiento" class="chosen" >
                                <option value="0" <?php
                                    if(!isset($movimiento)) echo 'selected';
                                ?>>Seleccione</option>
								<option value="1" <?php
                                    if(isset($movimiento))
                                        if($movimiento->Mov_Tipo === '1') echo 'selected';
                                ?>>Ingreso</option>
                                <option value="3" <?php
                                    if(isset($movimiento))
                                        if($movimiento->Mov_Tipo === '3') echo 'selected';
                                ?>>Egreso</option>
                            </select>
						</div>
						<div class="col-md-6">
							<label for="codigo_movimiento">Codigo:</label>
							<select id="codigo_movimiento" type="text" name="codigo_movimiento" class="chosen" style="width:100%">
                                <?php
                                if(isset($movimiento))
                                    echo  '<option value="'.$movimiento->Mov_Codigo.'" > N: '.$movimiento->Mov_Codigo.'</option>';
                                else
                                    echo  '<option value="0"> Seleccione </option>';
                                ?>
								<?php
								foreach ($codigos as $key) {
									echo  '<option value="'.$key->Codigo_Numero.'" > N: '.$key->Codigo_Numero."  D:". $key->Codigo_Descripcion.'</option>';
								}
								?>
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-6">
							<label for="inputMonto">Monto</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
								<input id="inputMonto" type="number" step="0.01" name="inputMonto" class="form-control input-sm" 
								 	<?php 
									if(isset($movimiento))
										echo  'value= "'.$movimiento->Mov_Monto.'"';
									 ?>
									 >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="inputObservacion">Descripcion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-code-setting"></i></span>
								<div class="fg-line">
									<input id="inputObservacion" type="text" maxlength="200" name="inputObservacion" name="inputObservacion" class="form-control input-sm"
									<?php  
									if(isset($movimiento))
										echo 'value= "'.$movimiento->Mov_Observacion.'"';
									 ?>
									 >
								</div>
							</div>
						</div>
					</div>
					<br>
					<input type="hidden" name="id" id="id" value="<?php if(isset($movimiento)) echo $movimiento->Mov_Id; else echo "-1";  ?>" style="display: none">
					<div class="row">
						<div class="col-md-4">
							<button type="submit" name="enviar" style="width:100%" class="btn btn-success">Guardar</button>
						</div>
						<div class="col-md-4">
							<button type="reset"  style="width:100%" class="btn btn-warning">Cancelar</button>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("movimientos");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<script src="<?php echo base_url();?>js/validations/validations_agregar_bonificacion.js"></script>

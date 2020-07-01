	<div class="row">
		<!-- Agregar clientes -->
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-12">
			<div class="card-header">
				<h2>Lista de habilitaciones</h2>
			</div>
			<div class="card-body card-padding">
						
				<form action="<?php 
					echo base_url("habilitaciones/guardar");
					 ?>" method="post" class="">
					<br>
					<div class="row">
						<div class="col-md-4">
							<label for="Hab_1">Habilitacion 1</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
									<?php 
									if (isset($consulta))
										if($consulta[0]->Habilitacion_Estado == 1 )
											echo '<input id="input_hab_1" type="checkbox" hidden="hidden" checked>';
										else echo '<input id="input_hab_1" type="checkbox" hidden="hidden">';
									else echo '<input id="input_hab_1" type="checkbox" hidden="hidden">';
									?>  
		                            <label for="input_hab_1" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
		                        <input type="text" name="rep_oculto_1" id="rep_oculto_1" style="display:none" 
									<?php 
										if (isset($consulta))
											if($consulta[0]->Habilitacion_Estado == 1 )
												echo 'value= "1"';
											else
												echo 'value= "0"';
									 ?>
		                        >
							</div>
						</div>
						<div class="col-md-4">
							<label for="Hab_2">Habilitacion 2</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
								<?php 
								if (isset($consulta))
									if($consulta[1]->Habilitacion_Estado == 1 )
										echo '<input id="input_hab_2" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="input_hab_2" type="checkbox" hidden="hidden">';
								else echo '<input id="input_hab_2" type="checkbox" hidden="hidden" checked>';
								?>  
		                            <label for="input_hab_2" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
							</div>
							<input type="text" name="rep_oculto_2" id="rep_oculto_2" style="display:none" 
									<?php 
										if (isset($consulta))
											if($consulta[1]->Habilitacion_Estado == 1 )
												echo 'value= "1"';
											else
												echo 'value= "0"';
									 ?>
		                        >
						</div>
						<div class="col-md-4">
							<label for="Hab_#">Habilitacion 3</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
								<?php 
								if (isset($consulta))
									if($consulta[2]->Habilitacion_Estado == 1 )
										echo '<input id="input_hab_3" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="input_hab_3" type="checkbox" hidden="hidden">';
								else echo '<input id="input_hab_3" type="checkbox" hidden="hidden" checked>';
								?>  
		                            <label for="input_hab_3" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
							</div>
							<input type="text" name="rep_oculto_3" id="rep_oculto_3" style="display:none" 
								<?php 
									if (isset($consulta))
										if($consulta[2]->Habilitacion_Estado == 1 )
											echo 'value= "1"';
										else
											echo 'value= "0"';
								 ?>
	                        >
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<label for="Hab_4">Habilitacion 4</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
								<?php 
								if (isset($consulta))
									if($consulta[3]->Habilitacion_Estado == 1 )
										echo '<input id="input_hab_4" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="input_hab_4" type="checkbox" hidden="hidden">';
								else echo '<input id="input_hab_4" type="checkbox" hidden="hidden">';
								?>  
		                            <label for="input_hab_4" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
		                        <input type="text" name="rep_oculto_4" id="rep_oculto_4" style="display:none" 
								<?php 
									if (isset($consulta))
										if($consulta[3]->Habilitacion_Estado == 1 )
											echo 'value= "1"';
										else
											echo 'value= "0"';
								 ?>
	                        >
							</div>
						</div>
						<div class="col-md-4">
							<label for="Han_5">Habilitacion 5</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
								<?php 
								if (isset($consulta))
									if($consulta[4]->Habilitacion_Estado == 1 )
										echo '<input id="input_hab_5" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="input_hab_5" type="checkbox" hidden="hidden">';
								else echo '<input id="input_hab_5" type="checkbox" hidden="hidden" checked>';
								?>  
		                            <label for="input_hab_5" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
							</div>
							<input type="text" name="rep_oculto_5" id="rep_oculto_5" style="display:none" 
								<?php 
									if (isset($consulta))
										if($consulta[4]->Habilitacion_Estado == 1 )
											echo 'value= "1"';
										else
											echo 'value= "0"';
								 ?>
	                        >
						</div>
						<div class="col-md-4">
							<label for="Hab_6">Habilitacion 6</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
								<?php 
								if (isset($consulta))
									if($consulta[5]->Habilitacion_Estado == 1 )
										echo '<input id="input_hab_6" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="input_hab_6" type="checkbox" hidden="hidden">';
								else echo '<input id="input_hab_6" type="checkbox" hidden="hidden" checked>';
								?>  
		                            <label for="input_hab_6" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
							</div>
							<input type="text" name="rep_oculto_6" id="rep_oculto_6" style="display:none" 
								<?php 
									if (isset($consulta))
										if($consulta[5]->Habilitacion_Estado == 1 )
											echo 'value= "1"';
										else
											echo 'value= "0"';
								 ?>
	                        >
						</div>						
					</div>
					<div class="row">
						<div class="col-md-4">
							<label for="Hab_7">Habilitacion 7</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
								<?php 
								if (isset($consulta))
									if($consulta[6]->Habilitacion_Estado == 1 )
										echo '<input id="input_hab_7" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="input_hab_7" type="checkbox" hidden="hidden">';
								else echo '<input id="input_hab_7" type="checkbox" hidden="hidden">';
								?>  
		                            <label for="input_hab_7" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
		                        <input type="text" name="rep_oculto_7" id="rep_oculto_7" style="display:none" 
								<?php 
									if (isset($consulta))
										if($consulta[6]->Habilitacion_Estado == 1 )
											echo 'value= "1"';
										else
											echo 'value= "0"';
								 ?>
	                        >
							</div>
						</div>
						<div class="col-md-4">
							<label for="Hab_8">Habilitacion 8</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
		                        <?php 
								if (isset($consulta))
									if($consulta[7]->Habilitacion_Estado == 1 )
										echo '<input id="input_hab_8" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="input_hab_8" type="checkbox" hidden="hidden">';
								else echo '<input id="input_hab_8" type="checkbox" hidden="hidden" checked>';
								?>  
		                            <label for="input_hab_8" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
							</div>
							<input type="text" name="rep_oculto_8" id="rep_oculto_8" style="display:none" 
								<?php 
									if (isset($consulta))
										if($consulta[7]->Habilitacion_Estado == 1 )
											echo 'value= "1"';
										else
											echo 'value= "0"';
								 ?>
	                        >
						</div>
						<div class="col-md-4">
							<label for="Hab_9">Habilitacion 9</label>
							<div class="input-group form-group">
								NO&nbsp&nbsp&nbsp
								<div class="toggle-switch">
								<?php 
								if (isset($consulta))
									if($consulta[8]->Habilitacion_Estado == 1 )
										echo '<input id="input_hab_9" type="checkbox" hidden="hidden" checked>';
									else echo '<input id="input_hab_9" type="checkbox" hidden="hidden">';
								else echo '<input id="input_hab_9" type="checkbox" hidden="hidden" checked>';
								?>  
		                            <label for="input_hab_9" class="ts-helper"></label>
		                        </div>&nbsp&nbsp&nbspSI
							</div>
							<input type="text" name="rep_oculto_9" id="rep_oculto_9" style="display:none" 
								<?php 
									if (isset($consulta))
										if($consulta[8]->Habilitacion_Estado == 1 )
											echo 'value= "1"';
										else
											echo 'value= "0"';
								 ?>
	                        >
						</div>						
					</div>
					<br>
					<div class="row">
						<div class="col-md-4">
							<button type="submit" name="enviar" style="width:100%" class="btn btn-success">Guardar</button>
						</div>
						<div class="col-md-4">
							<button type="reset"  style="width:100%" class="btn btn-danger">Cancelar</button>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- fin de agregar clientes -->
	</div>
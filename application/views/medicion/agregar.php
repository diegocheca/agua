	<div class="row">
		<div class="card col-md-6">
			<div class="card-header">
				<h2>Agregar Nueva Medicion</h2>
			</div>
			<div class="card-body card-padding">
						
				<form id ="form_agregar_medicion"action="<?php 
				echo base_url();
				if(isset($medicion))
					echo "mediciones/guardar_agregar";
				else echo "mediciones/guardar_agregar";
				 ?>" method="post" class="">
					<div class="row">
						<div class="col-md-6">
							<label for="inputConexionId">Conexion Id</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputConexionId" placeholder="Usuario..." type="text" maxlength="200" name="inputConexionId" class="form-control input-sm"
								<?php 
									if(isset($medicion))
										echo  'value= "'.$medicion->Conexion_Id.'" ';
									 ?>
									 >
								<input type="hidden" id="inputIdCliente" name="idcone">
							</div>
						</div>
						<!-- <div class="col-md-6">
							<label for="inputCliente">Nombre cliente</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputCliente" placeholder="Nombre..." type="text" maxlength="200" name="inputClienteId" class="form-control input-sm" 
								<?php 
									if(isset($medicion))
										echo  'value= "'.$medicion->Cli_RazonSocial.'"   ';
									 ?>
									 >
							</div>
						</div> -->
					</div>
					<!-- <div class="row">
						<div class="col-md-6">
							<label for="inputConexionDomicilio">Domicilio conexion</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputConexionDomicilio" placeholder="Domicilio..." type="text" maxlength="200" name="inputConexionDomicilio" class="form-control input-sm"   
									<?php 
									if(isset($medicion))
										echo  'value= "'.$medicion->Conexion_DomicilioSuministro.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputConexionSector">Sector</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputConexionSector" placeholder="Sector..." type="text" maxlength="200" name="inputConexionSector" class="form-control input-sm"   
									<?php 
									if(isset($medicion))
										echo  'value= "'.$medicion->Conexion_Sector.'"';
									 ?>
									 >
							</div>
						</div>
					</div> -->
					<div class="row">
						<div class="col-md-6">
							<!-- <label for="inputTipo">Tipo de Conexion</label> -->
							<div class="input-group form-group">
								<!-- <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span> -->
								<input id="inputTipo" placeholder="Tipo de Conexion..." type="hidden" name="inputTipo" class="form-control input-sm"  value="1"  
									<?php 
									if(isset($medicion))
										echo  'value= "'.$medicion->Conexion_Categoria.'"';
									?>
									>
							</div>
						</div>
						<!-- <div class="col-md-6">
							<label for="inputConexionSector">Sector</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputConexionSector" placeholder="Sector..." type="text" maxlength="200" name="inputConexionSector" class="form-control input-sm"   
									<?php 
									if(isset($medicion))
										echo  'value= "'.$medicion->usuario.'"';
									 ?>
									 >
							</div>
						</div> -->
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="inputMes">Mes</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
								<input id="inputMes" placeholder="Mes..." type="text" maxlength="200" name="inputMes" class="form-control input-sm"
									<?php 
									if(isset($medicion))
										echo  'value= "'.$medicion->Medicion_Mes.'"';
									else echo '  ';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputAnio">Año</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar-alt"></i></span>
								<div class="fg-line">
									<input id="inputAnio" placeholder="Año..." type="text" maxlength="200"  name="inputAnio" class="form-control input-sm input-mask"
									<?php  
									if(isset($medicion))
										echo 'value= "'.$medicion->Medicion_Anio.'"';
									else echo '  ';
									 ?>
									 requierd>
								</div>
							</div>
						</div>
					</div>						
					<div class="row">
						<div class="col-md-6">
							<label for="inputMAnterior">Medicion anterior</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<input id="inputMAnterior" placeholder="Anterior" type="text" maxlength="10" name="inputMAnterior" class="form-control input-sm"   
									<?php 
									if(isset($medicion))
										echo 'value= "'.$medicion->Medicion_Anterior.'"';
									 ?>
									 readonly>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputMActual">Medicion actual</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<input id="inputMActual" placeholder="Actual" type="number" max="9999" min="1" name="inputMActual" class="form-control input-sm" required
								<?php 
									if(isset($medicion))
										echo 'value= "'.$medicion->Medicion_Actual.'"';
									 ?>
									>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputExcedente">Excedente</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<input id="inputExcedente" readonly placeholder="Excedente" type="number" max="9999" min="1" name="inputExcedente" class="form-control input-sm"   
								<?php 
									if(isset($medicion))
										echo 'value= "'.$medicion->Medicion_Excedente.'"';
									 ?>
									>
							</div>
						</div>
						
					</div>
					<input type="hidden" name="id" id="id" value="<?php if(isset($medicion)) echo $medicion->Medicion_Id; else echo "-1";  ?>" style="display: none">
					<div class="row">
						<button type="submit" name="enviar" style="width:100%" class="btn btn-success">Guardar</button>
					</div>
					<div class="row">
							<button type="reset"  style="width:100%" class="btn btn-danger">Cancelar</button>
						</div>
						<div class="row">
							<a href="<?php echo base_url("mediciones");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
				</form>
			</div>
		</div>
	</div>
	<!--<script type="text/javascript">
	// $("form_agregar_medicion").keypress(function(e) {
	//         if (e.which == 13) {
	//             return false;
	//         }
	//     });
 //    </script>-->
<script src="<?php echo base_url();?>js/validations/validations_agregar_medicion.js"></script>

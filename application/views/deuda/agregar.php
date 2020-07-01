	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-6">
			<div class="card-header">
				<h2>Agregar Nueva Deuda</h2>
			</div>
			<div class="card-body card-padding">
						
				<form action="<?php 
				echo base_url();
				if(isset($deuda))
					echo "deuda/guardar_agregar";
				else echo "deuda/guardar_agregar";
				 ?>" method="post" class="">
					<div class="row">
						<div class="col-md-6">
							<label for="inputConexionId">Conexion Id</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputConexionId" placeholder="Conexion Id" type="text" maxlength="200" name="inputConexionId" class="form-control input-sm" required
									<?php 
									if(isset($deuda))
										echo  'value= "'.$deuda->Deuda_Conexion_Id.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputMonto">Monto</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-money-box"></i></span>
								<div class="fg-line">
									<input id="inputMail" placeholder="Monto" maxlength="200" name="inputMonto" class="form-control input-sm input-mask"
									<?php  
									if(isset($deuda))
										echo 'value= "'.$deuda->Deuda_Monto.'"';
									 ?>
									 requierd>
								</div>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="inputConcepto">Concepto</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
								<input id="inputConcepto" maxlength="200" name="inputConcepto" class="form-control input-sm" required
									<?php 
									if(isset($deuda))
										echo 'value= "'.$deuda->Deuda_Concepto.'"';
									 ?>
									>
							</div>
						</div>
					</div>
					
					<input type="hidden" name="id" id="id" value="<?php if(isset($deuda)) echo $deuda->Deuda_Id; else echo "-1";  ?>" style="display: none">
					<div class="row">
						<div class="col-md-4">
							<button type="submit" name="enviar" style="width:100%" class="btn btn-success">Guardar</button>
						</div>
						<div class="col-md-4">
							<button type="reset"  style="width:100%" class="btn btn-danger">Cancelar</button>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("deuda");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
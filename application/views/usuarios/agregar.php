	<div class="row">
		<?php echo $this->session->flashdata('mensaje'); ?>
		<div class="card col-md-6">
			<div class="card-header">
				<h2>Agregar Nuevo Tipo de Usuarios</h2>
			</div>
			<div class="card-body card-padding">
						
				<form id="form_agregar_usuarios" action="<?php 
				echo base_url();
				if(isset($usuario))
					echo "usuarios/guardar_agregar";
				else echo "usuarios/guardar_agregar";
				 ?>" method="post" class="">
					<div class="row">
						<div class="col-md-6">
							<label for="inputUsuario">Usuario</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<input id="inputUsuario" placeholder="Usuario..." type="text" maxlength="200" name="inputUsuario" class="form-control input-sm" required
									<?php 
									if(isset($usuario))
										echo  'value= "'.$usuario->usuario.'"';
									 ?>
									 >
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputMail">Email</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
								<div class="fg-line">
									<input id="inputMail" placeholder="E-mail" type="email" maxlength="200"  name="inputMail" class="form-control input-sm input-mask"
									<?php  
									if(isset($usuario))
										echo 'value= "'.$usuario->email.'"';
									 ?>
									 requierd>
								</div>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="inputPass">Contraseña</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputPass" placeholder="Password...." type="password" maxlength="10" name="inputPass" class="form-control input-sm" 
									
									>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputPass_dos">Repetir Contraseña</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-pin-account"></i></span>
								<input id="inputPass_dos" placeholder="Password...." type="password" maxlength="10" name="inputPass_dos" class="form-control input-sm" <?php 
									if(! isset($usuario))
										echo 'required';
									 ?> >
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="inputPrecioUni">Rol</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-receipt"></i></span>
								<div class="fg-line select">
									<select id="rol" name="rol"  class="form-control input-sm">
									<option value="0" >Seleccione</option>
									<option value="administrador">Administrador</option>
									<option value="secretaria">Secretaria</option>
									<option value="medidores">Medidores</option>
									
								</select>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputNombre">Nombre</label>
							<div class="input-group form-group">
								<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
								<div class="fg-line">
									<input id="inputNombre" placeholder="Nombre..."  type="text" name="inputNombre" class="form-control input-sm"
									<?php  
									if(isset($usuario))
										echo 'value= "'.$usuario->nombre.'"';
									 ?>
									 >
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="id" id="id" value="<?php if(isset($usuario)) echo $usuario->id; else echo "-1";  ?>" style="display: none">
					<div class="row">
						<div class="col-md-4">
							<!-- <button type="submit" name="enviar" style="width:100%" class="btn btn-success">Guardar</button> -->
							<button name="validar_campos_de_usuarios" id="validar_campos_de_usuarios" style="width:100%" class="btn btn-success">Guardar</button>
						</div>
						<div class="col-md-4">
							<button type="reset"  style="width:100%" class="btn btn-danger">Cancelar</button>
						</div>
						<div class="col-md-4">
							<a href="<?php echo base_url("usuarios");?>"><button type="button" style="width:100%" class="btn btn-primary" name="volver" id="volver">Volver</button></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<script src="<?php echo base_url();?>js/validations/validations_agregar_usuario.js"></script>
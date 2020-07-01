<!-- <div class="col-sm-6">
	<div class="card">
		<div class="card-header bgm-green">
			<h2>Impresiones  <small>Impresiones de boletas</small></h2>
			<ul class="actions">
				<li class="dropdown">
					<a href="" data-toggle="dropdown">
					   <i class="zmdi zmdi-more-vert" style="color:white"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li>
							<a href="">Change Date Range</a>
						</li>
						<li>
							<a href="">Change Graph Type</a>
						</li>
						<li>
							<a href="">Descargar todo</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="card-body">
			<div class="listview">
				<div class="panel-body">
					<ul class="nav nav-pills">
						<li class="active"><a href="#home-pills" data-toggle="tab" aria-expanded="true">Por Sectores</a>
						</li>
						<li class=""><a href="#profile-pills" data-toggle="tab" aria-expanded="false">Por Conexiones</a>
						</li>
						<li class=""><a href="#corregir" data-toggle="tab" aria-expanded="false">Corregir Mediciones</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade active in" id="home-pills">
							<form method="POST" target="_blank" action="<?php echo base_url('imprimir/crear_factura_por_sector'); ?>">
								<label for="select_sector_imprimir">Seleccion Sectores:</label>
								<select name="miselect[]" id="select_sector_imprimir" class="chosen" data-placeholder="Elige los sectores" >
									<?php 
									foreach ($conexiones_a_imprimir as $key) {
										echo '<option value="'.$key->Conexion_Sector.'" >'.$key->Conexion_Sector.'</option>';
									}
									?>
								</select>
								<button class="btn bgm-lime btn-float waves-effect waves-button waves-float waves-effect waves-circle waves-float" id="imprmir_select_sector" name="imprmir_select_sector" type="submit"><i class="zmdi zmdi-print"></i></button>
								<br>
							</form>
						</div>
						<div class="tab-pane fade" id="profile-pills">
							<form method="POST" target="_blank" action="<?php echo base_url('imprimir/crear_factura_por_conexion'); ?>">
								<label for="select_conexion_imprimir">Seleccion Tablet:</label>
								<select name="miselect" id="select_conexion_imprimir" class="chosen" data-placeholder="Elige los sectores" >
								<?php
								foreach ($conexiones_a_imprimir_conexion as $key ) 
								{
									echo '<option value="'.$key->Conexion_Id.'" >Con: '.$key->Conexion_Id.' Nombre: '.$key->Cli_RazonSocial.'  Dni: '.$key->Cli_NroDocumento.' </option>';
								}
								?>
								</select>
								<button class="btn bgm-lime btn-float waves-effect waves-button waves-float waves-effect waves-circle waves-float" id="imprmir_select_conexion" name="imprmir_select_conexion"><i class="zmdi zmdi-print"></i></button>
								<br>
							</form>
						</div>
						<div class="tab-pane fade" id="corregir">
							<form method="POST" target="_blank" action="<?php echo base_url('nuevo/corregir_mediciones'); ?>">
								<label for="select_tablet">Seleccion Conexiones:</label>
								<select name="select_tablet" id="select_tablet" class="chosen" data-placeholder="Elige los sectores" >
									<option value="A" >A</option>
									<option value="B" >B</option>
								</select>
								<select name="mes" id="mes" class="chosen" data-placeholder="Elige los mes" >
									<option value="1" >Enero</option>
									<option value="2" >Febrero</option>
									<option value="3" >Marzo</option>
									<option value="4" >Abril</option>
									<option value="5" >Mayo</option>
									<option value="6" >Junio</option>
									<option value="7" >Julio</option>
									<option value="8" >Agosto</option>
									<option value="9" >Setiembre</option>
									<option value="10" >Octubre</option>
									<option value="11" >Noviembre</option>
									<option value="12" >Diciembre</option>

								</select>
								<select name="anio" id="anio" class="chosen" data-placeholder="Elige los anio" >
									<option value="2017" >2017</option>
									<option value="2018" >2018</option>
								</select>

								<button class="btn bgm-lime btn-float waves-effect waves-button waves-float waves-effect waves-circle waves-float" id="corregir_mediciones_nuevo_desde_dash" name="corregir_mediciones_nuevo_desde_dash"><i class="zmdi zmdi-print"></i></button>
								<br>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->

<div class="col-sm-12">
	<div class="card">
		<div class="card-header bgm-black">
			<h2>Impresiones Nuevas  <small>Impresiones nuevas de  boletas</small></h2>
		</div>
		<div class="card-body">
			<div class="listview">
				<div class="panel-body">
					<ul class="nav nav-pills">
						<li class="active"><a href="#home-pills_nuevo" data-toggle="tab" aria-expanded="true">Por Sectores</a>
						</li>
						<li class=""><a href="#profile-pills_nuevo" data-toggle="tab" aria-expanded="false">Por Conexiones</a>
						</li>
						<li class=""><a href="#corregir_nuevo" data-toggle="tab" aria-expanded="false">Corregir Mediciones</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade active in" id="home-pills_nuevo">
							<form method="POST" target="_blank" action="<?php echo base_url('nuevo/crear_factura_por_sector'); ?>">
								<label for="select_sector_imprimir">Seleccion Sectores:</label>
								<select name="miselect[]" id="select_sector_imprimir" class="chosen" data-placeholder="Elige los sectores" >
									<?php 
									foreach ($conexiones_a_imprimir as $key) {
										echo '<option value="'.$key->Conexion_Sector.'" >'.$key->Conexion_Sector.'</option>';
									}
									?>
								</select>
								<br>
								<br>
								<label for="mes_a_imprimir">Seleccion mes:</label>
								<select name="mes_a_imprimir" id="mes_a_imprimir" class="chosen" data-placeholder="Elige los sectores" >
									<option value="1">Enero</option>
									<option value="2">Febrero</option>
									<option value="3">Marzo</option>
									<option value="4">Abril</option>
									<option value="5">Mayo</option>
									<option value="6">Junio</option>
									<option value="7">Julio</option>
									<option value="8">Agosto</option>
									<option value="9">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
								<br>
								<br>
								<label for="anio_a_imprimir">Seleccion año:</label>
								<select name="anio_a_imprimir" id="anio_a_imprimir" class="chosen" data-placeholder="Elige los sectores" >
									<option value="2017">2017</option>
									<option value="2018">2018</option>
									<option value="2020">2020</option>
								</select>
								<button class="btn bgm-lime btn-float waves-effect waves-button waves-float waves-effect waves-circle waves-float" id="imprmir_select_por_conexion_nuevo" name="imprmir_select_por_conexion_nuevo" type="submit"><i class="zmdi zmdi-print"></i></button>
								<br>
								<br>
							</form>
						</div>
						<div class="tab-pane fade" id="profile-pills_nuevo">
							<form method="POST" target="_blank" action="<?php echo base_url('nuevo/crear_factura_por_conexion'); ?>">
								<label for="select_conexion_imprimir">Seleccion la conexion:</label>
								<select name="coenxion_a_imprimir_por_conexion" id="select_conexion_imprimir" class="chosen" data-placeholder="Elige los sectores" >
								<?php
								foreach ($conexiones_a_imprimir_conexion as $key ) 
								{
									echo '<option value="'.$key->Conexion_Id.'" >Con: '.$key->Conexion_Id.' Nombre: '.$key->Cli_RazonSocial.'  Dni: '.$key->Cli_NroDocumento.' </option>';
								}
								?>
								</select>
								<br>
								<br>
								<label for="mes_a_imprimir_por_conexion">Seleccion mes:</label>
								<select name="mes_a_imprimir_por_conexion" id="mes_a_imprimir_por_conexion" class="chosen" data-placeholder="Elige los sectores" >
									<option value="1">Enero</option>
									<option value="2">Febrero</option>
									<option value="3">Marzo</option>
									<option value="4">Abril</option>
									<option value="5">Mayo</option>
									<option value="6">Junio</option>
									<option value="7">Julio</option>
									<option value="8">Agosto</option>
									<option value="9">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
								<br>
								<br>
								<label for="anio_a_imprimir_por_conexion">Seleccion año:</label>
								<select name="anio_a_imprimir_por_conexion" id="anio_a_imprimir_por_conexion" class="chosen" data-placeholder="Elige los sectores" >
									<option value="2017">2017</option>
									<option value="2018">2018</option>
								</select>
								<button class="btn bgm-lime btn-float waves-effect waves-button waves-float waves-effect waves-circle waves-float" id="imprmir_select_conexion" name="imprmir_select_conexion"><i class="zmdi zmdi-print"></i></button>
								<br>
							</form>
						</div>
						<div class="tab-pane fade" id="corregir_nuevo">
							<form method="POST" target="_blank" action="<?php echo base_url('nuevo/corregir_mediciones'); ?>">
								<label for="select_tablet">Seleccion Conexiones:</label>
								<select name="select_tablet" id="select_tablet" class="chosen" data-placeholder="Elige los sectores" >
									<option value="A" >A</option>
									<option value="B" >B</option>
								</select>
								<br>
								<br>
								<label for="mes">Seleccion la mes:</label>
								<select name="mes" id="mes" class="chosen" data-placeholder="Elige los mes" >
									<option value="1" >Enero</option>
									<option value="2" >Febrero</option>
									<option value="3" >Marzo</option>
									<option value="4" >Abril</option>
									<option value="5" >Mayo</option>
									<option value="6" >Junio</option>
									<option value="7" >Julio</option>
									<option value="8" >Agosto</option>
									<option value="9" >Setiembre</option>
									<option value="10" >Octubre</option>
									<option value="11" >Noviembre</option>
									<option value="12" >Diciembre</option>
								</select>
								<br>
								<br>
								<label for="anio">Seleccion la año:</label>
								<select name="anio" id="anio" class="chosen" data-placeholder="Elige los anio" >
									<option value="2017" >2017</option>
									<option value="2018" >2018</option>
								</select>
								<button class="btn bgm-lime btn-float waves-effect waves-button waves-float waves-effect waves-circle waves-float" id="corregir_mediciones_nuevo_desde_dash" name="corregir_mediciones_nuevo_desde_dash"><i class="zmdi zmdi-print"></i></button>
								<br>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


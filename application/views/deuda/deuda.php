	<!-- contents -->
		<!-- Titulos y botones superiores -->
		<div class="block-header">
			<h2>Lista de deudas</h2>
	                        
				<ul class="actions">
					<li>
						+<?php echo anchor('deuda/agregar_deuda','<i class="md md-add"></i>'); ?>
					</li><li>
						<a href="">
							-<i class="md md-trending-up"></i>
						</a>
					</li>
					<li>
					   <a href="">
							*<i class="md md-done-all"></i>                    
						</a>
					</li>
					<li class="dropdown">
						<a href="" data-toggle="dropdown">
							/<i class="md md-more-vert"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-right">
							<li>
								<a href="">Refresh</a>
							</li>
							<li>
								<a href="">Manage Widgets</a>
							</li>
							<li>
								<a href="">Widgets Settings</a>
							</li>
						</ul>
					</li>
				</ul>
		</div>
		<!-- ./ Titulo y botones superiores -->
		<?php 
		function arreglar_numero($numero)
		{
			if( intval($numero) >= 1000)
				$numero = str_replace(".", "",$numero);
			$inicio_coma = strpos($numero, '.');
			if ( is_float($numero) )
				$numero .= "00";
			else $numero .= ",00";
			if( is_numeric( $inicio_coma) &&  ($inicio_coma >= 1) &&  ($inicio_coma < strlen($numero) ) )
				$numero =  substr($numero, 0,  ($inicio_coma+3)); 
			
			return str_replace(".", ",",$numero);
		}
		?>
	<div class="row">
		<!-- fin de agregar clientes -->
		<div class="card">
			<div class="card-header">
				<h2>Lista de deudas</h2>
			</div>
			<table id="data-table-deuda"" class="table table-striped table-vmiddle">
				<thead>
					<tr>
						<th data-column-id="sku">ID</th>
						<th data-column-id="conexion_id">Conexion Id</th>
						<th data-column-id="concepto" >Concepto</th>
						<th data-column-id="monto" >Monto</th>
						<th data-column-id="comandos" data-formatter="comandos" data-sortable="false">Comandos</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($deudas as $columna):?>
					<tr>
						<td><?php echo $columna->Deuda_Id ?></td>
						<td><?php echo $columna->Deuda_Conexion_Id ?></td>
						<td><?php echo $columna->Deuda_Concepto ?></td>
						<td><?php echo arreglar_numero($columna->Deuda_Monto);  ?></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
			
	<!-- ./ contents -->				

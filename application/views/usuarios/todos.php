			<table class="table">
				<thead>
					<tr>
						<th>ID Cliente</th>
						<th>ID Conexion</th>
						<th>Id facturacion</th>
						<th>Id mediciones</th>
						<th>Id Plan Pago</th>
						<th>Deuda</th>
						<th>Medidor</th>
						<!-- <th>Pago</th> -->
						<th>Nombre Usuario</th>
						<th>Direccion Suministro</th>
						<th>Direccion Postal</th>

					</tr>
				</thead>
				<tbody>
				<?php foreach ($usuarios as $columna)
				{
					
					if ( ($columna->Cli_Id != null )&& ($columna->Conexion_Id != null ) && ($columna->id != null )  && ($columna->Medicion_Id != null ))
						echo "<tr class='success'>";
						else echo "<tr class='danger'>";
					echo "<td>".$columna->Cli_Id."</td>";
					if($columna->Conexion_Id == null)
						echo "<td><a href='http://localhost/codeigniter/conexion/agregar_solo_conexion'>Cargar</a></td>";

					else echo "<td>".$columna->Conexion_Id."</td>";
					if($columna->id == null)  
					echo "<td><a href='http://localhost/codeigniter/facturar/crear_nueva'>Cargar</a></td>";

					else echo "<td>".$columna->id."</td>";
					if($columna->Medicion_Id == null)
						echo "<td><a href='http://localhost/codeigniter/mediciones/agregar_medicion'>Cargar</a></td>";
					else echo
					 "<td>".$columna->Medicion_Id."</td>";
					echo "
					<td>".$columna->PlanPago_Id."</td>
					<td>".$columna->Deuda_Id."</td>
					<td>".$columna->Medidor_Id."</td>

					<td>".$columna->Cli_RazonSocial."</td>
					<td>".$columna->Cli_DomicilioSuministro."</td>
					<td>".$columna->Conexion_DomicilioSuministro."</td>

					</tr>
					";
				}
				?>
					
				</tbody>
			</table>

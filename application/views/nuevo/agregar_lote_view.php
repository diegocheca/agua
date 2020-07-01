	<div class="row">
		<div class="card col-md-12">
			<div class="card-header">
				<h2>Agregar Mediciones por Lotes</h2>
			</div>
			<div class="card-body card-padding">
				<div class="card">
					<div class="card-header bgm-black m-b-20">
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
										<label for="selectfecha">Fecha:</label>
										<input id="selectfecha" type="month">
									</div>
								</div>
								<div class="col-md-4 col-xs-12">
									<button type="button" id="enviarConsulta_nueva" class="btn btn-lg btn-block btn-success">Buscar Secto8888r</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card repeater">
							<div class="card-header bgm-indigo m-b-20">
								<h2>Datos de las Mediciones <small>Ingresa los valores de las Mediciones</small></h2>
							</div>
							<div class="card-body card-padding">
								<div class="row">
									<form id="formulario_de_mediciones_lote"  action="<?php 
										echo base_url();
										if(isset($bonificacion))
											echo "nuevo/guardar_mediciones_por_lotes_con_ajax";
										else echo "nuevo/guardar_mediciones_por_lotes_con_ajax";
										 ?>" method="post" class="">
										 <input id="cantidad_de_input" name="cantidad_de_input" type="hidden" value="0">
										 <input id="mes_de_input" name="mes_de_input" type="hidden" value="0">
										 <input id="anio_de_input" name="anio_de_input" type="hidden" value="0">
										 <input id="sector_input" name="sector_input" type="hidden" value="0">
										<div id="resultado_query">
										</div>
										<div class="row">
											<button type="button" name="enviar_formulario_de_mediciones_lote_nuevo" id="enviar_formulario_de_mediciones_lote_nuevo" style="width:100%; height:50%" class="btn btn-success">Guardar</button>
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
	<script type="text/javascript">
	$(document).ready(function() {
		$('#enviarConsulta_nueva').click(function(){
				$("#resultado_query").html('');
					console.log("hola desde la cosnola");
					var mes = $("#selectfecha").val();
					mes = mes.split("-");
					$("#anio_de_input").val(mes[0]);
					$("#mes_de_input").val(mes[1]);
					$("#sector_input").val($("#select_sector_imprimir").val());
					var select_sector_imprimir = $("#select_sector_imprimir").val();
					var mes = $("#selectfecha").val();
					//alert(mes);
					$.ajax({
						url: 'http://localhost/codeigniter/nuevo/ejecutar_query',
						type: 'POST',
						async: true,
						data: {'sectores': select_sector_imprimir, 'mes': mes},
						success: function(response){
						if(response != false ) // se cargo correctamente
						{
							$("#resultado_query").html(response);
						}
						else // no se cargo en la base de datos
						{
							alert ("No se pudo guardar en la base de datos");
						}
					},
					error: function(){
						alert("Hubo un error enviando la petición al servidor, contactar al administrador")
					}
				});
		});
	$('#enviar_formulario_de_mediciones_lote_nuevo').on("click", function(e){
		e.preventDefault();
		var con_error = 0  ;
		var correctos = 0;
		var incompletos = 0;
		var excedidos = 0;
		var id_del_iput = null;
		var id_medicion_anterior = null;
		var carga_actual  = 0;
		var aux;
		var arreglo = [];
		var carga_anterior  = 0;
		var indice = 0;
		$("#formulario_de_mediciones_lote input").each(function(){
			if($(this).attr('id').substring(0, 12) == "inputExceden" )
			{
				indice ++;
				id_del_iput = $(this).attr('id');
				id_del_iput = "#"+id_del_iput;
				carga_actual = $(id_del_iput).val();
				if((carga_actual == null) || (carga_actual == "") )
					incompletos ++;
				else
					if(carga_actual < 0)
						con_error ++;
					else // (carga_actual >= 0) //correctos
					{
						arreglo = id_del_iput.split("_");
						id_medicion_anterior = "#inputMedicionAnterior_"+arreglo[1];
						carga_anterior = $(id_medicion_anterior).val();
						//alert("Valor actual:" +parseInt(carga_actual)+ "- Valor anterior +35:"+);
						if(parseInt(carga_actual) > parseInt(35)  )// si es mucho mas grande
							excedidos ++;
						else correctos ++;
						//alert("Vuleta: "+indice+"   - Decision: Correcta");
					}
				$("#cantidad_de_input").val(indice);
			}
		});
	var form = $("#formulario_de_mediciones_lote");
	swal({
		title: "Estas seguro cargar estas mediciones?",   
		text: "Se han detectado\n\n Errores: "+con_error+"\n\n Incompletos: "+incompletos+"\n\n Excedidos: "+excedidos+"\n\n Correctos: "+correctos+"!",   
		type: "info",   
		showCancelButton: true,   
		confirmButtonColor: "#00FF7F",   
		confirmButtonText: "Si, Guardarlo!",
		cancelButtonText: 'Cancelar',   
		closeOnConfirm: false
		}, function(){  
			$.ajax({
			data:  form.serialize(),
			url: form.attr( 'action' ),
			type:  'post',
		          success:  
			function (response) {
			  	console.log(response);
			  	if(response !== false ){
			  		console.log("entre al true");
		           location.reload();
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
		            	    }
		            	}
		            }
		        });
		    });
        });

$("#inputTipoPago").on("change",function(){
    var valor = $("#inputTipoPago").val();
    if(valor === "Contado" )
        $("#div_cantidad_cuotas").hide('1800');
    else
       $("#div_cantidad_cuotas").show('1800');
    });





















	});
		</script>


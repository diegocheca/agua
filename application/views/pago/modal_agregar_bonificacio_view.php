<div class="row">
	<ul class="nav nav-pills">
        <li class=""><a href="#home-pills" data-toggle="tab" aria-expanded="false">Monto</a>
        </li>
        <li class="active"><a href="#profile-pills" data-toggle="tab" aria-expanded="true">Porcentaje</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade" id="home-pills">
            <label for="monto_a_bonificacion_agregar">Monto a bonificar:</label>
			<div class="input-group form-group">
				<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
				<div class="fg-line">
					<input id="monto_a_bonificacion_agregar" type="number" name="monto_a_bonificacion_agregar" class="form-control input-sm">
				</div>
			</div>
        </div>
        <div class="tab-pane fade active in" id="profile-pills">
            <label for="procentaje_bonificacion_agregar">Porcentaje de Pago</label>
			<div class="input-group form-group">
				<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
				<div class="fg-line select">
					<select id="procentaje_bonificacion_agregar" type="text" name="procentaje_bonificacion_agregar" class="form-control input-sm" >
						<option value="-1" disabled="" selected>Seleccione</option>
						<option value="0" >Sin Descuento</option>
						<option value="5">5</option>
						<option value="10">10</option>
						<option value="15">15</option>
						<option value="20">20</option>
						<option value="25">25</option>
						<option value="30">30</option>
						<option value="35">35</option>
						<option value="40">40</option>
					</select>
				</div>
			</div>
        </div>
    </div>
    <div clas="row">
		<div class="col-md-6">
			<label for="monto_pagar_con_bonificacion">Monto Total:</label>
			<div class="input-group form-group">
				<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
				<div class="fg-line">
					<input id="monto_pagar_con_bonificacion" type="number" name="monto_pagar_con_bonificacion" class="form-control input-sm" ng-model="monto_total" readonly>
				</div>
			</div>
		</div>
	</div>
	<div clas="row">
		<div class="col-md-6">
			<label for="monto_descontado_por_bonificacion">Monto descontado:</label>
			<div class="input-group form-group">
				<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
				<div class="fg-line">
					<input id="monto_descontado_por_bonificacion" type="number" name="monto_descontado_por_bonificacion" class="form-control input-sm"   readonly>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<label for="monto_despues_bonificacion">Monto Resultante:</label>
			<div class="input-group form-group">
				<span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
				<div class="fg-line">
					<input id="monto_despues_bonificacion" type="number" name="monto_despues_bonificacion" class="form-control input-sm"   readonly>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#procentaje_bonificacion_agregar").on("change",function(){
        var procent = $("#procentaje_bonificacion_agregar").val() ;
        console.log("el porcentaje loko es:"+procent);
       // var total = $('[name=inputtotal]').val();
        var total =  $("#inputtotal").val();
        console.log("el total loko es:"+total);
        $("#monto_pagar_con_bonificacion").val(total.replace(",","."));
        var a_pagar = (parseFloat(total)*parseFloat(procent))/100;
        a_pagar = a_pagar.toFixed(2);
        // monto_pagar_con_bonificacion
        if(($("#inputendeuda").val() >0)  && ($("#inputendeuda").val() !=0) && ($("#inputendeuda").val()!= null) && ($("#inputendeuda").val()!= NaN))
            total = $("#inputendeuda").val();
        $("#monto_descontado_por_bonificacion").val(a_pagar);
        var mont = parseFloat(total)-parseFloat(a_pagar);
        $("#monto_despues_bonificacion").val(mont.toFixed(2));


        $("#hay_bonificacion_form").val(1);
        $("#porcentaje_bonificacion_form").val(procent);
        $("#monto_a_descontar_form").val(a_pagar);
        $("#inputbonificacion").val(a_pagar);
        
        $("#monto_descontado_form").val(0);
        $("#monto_descontado_form_div").hide('1000');
        $("#porcentaje_bonificacion_form_div").show('1000');
        $("#monto_a_descontar_form_div").show('1000');

var aux_total = $("[name = total]").val();
var auxzx = parseFloat(aux_total) - parseFloat(a_pagar);
 $("[name = total]").val(auxzx.toFixed(2)  );


		$("#datos_bonificacion").show('800');
        $("#datos_bonificacion").show('800');
        $("#Borrar_bonificacion_btn").show('800'); 
		$("#Modificar_bonificacion_btn").show('800');
		$("#agregar_bonificacion").hide('800');
		$("#monto_a_bonificacion_agregar").val('');
		

        


    });

    $("#monto_a_bonificacion_agregar").on("change",function(){
        var monto = $("#monto_a_bonificacion_agregar").val() ;
        var total = $('[name=inputtotal]').val();
        var a_pagar = parseFloat(total)-parseFloat(monto);
        a_pagar =a_pagar.toFixed(2);
       // monto_pagar_con_bonificacion
       if(($("#inputendeuda").val() >0)  && ($("#inputendeuda").val() !=0) && ($("#inputendeuda").val()!= null) && ($("#inputendeuda").val()!= NaN))
            total = $("#inputendeuda").val();
        $("#monto_descontado_por_bonificacion").val(parseFloat(total)-parseFloat(a_pagar));
        $("#monto_despues_bonificacion").val(a_pagar);
        $("#hay_bonificacion_form").val(1);
        $("#monto_descontado_form").val(monto);
        $("#inputbonificacion").val(monto);

       var aux_total =  $("[name = total]").val();
        $("[name = total]").val( parseFloat(aux_total) - parseFloat(monto) );


        $("#porcentaje_bonificacion_form").val(0);
        $('#procentaje_bonificacion_agregar option:eq(0)').prop('selected', true);
        $("#porcentaje_bonificacion_form_div").hide('1000');
        $("#monto_a_descontar_form_div").hide('1000');
        $("#monto_descontado_form_div").show('1000');

        $("#datos_bonificacion").show('800');
        $("#Borrar_bonificacion_btn").show('800'); 
		$("#Modificar_bonificacion_btn").show('800'); 	
		$("#agregar_bonificacion").hide('800');



    });

</script>

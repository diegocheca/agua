$("#inputPorcentajeBonificado").on("change",function(){
	        var porcent = $("#inputPorcentajeBonificado").val() ;
	        var total = $('[name=inputMonto]').val();
	        var a_pagar = (parseFloat(total)*parseFloat(porcent))/100;
	        $("#inputMontoBonificado").val('');
	        $("#inputMontoFinal").val(parseFloat(total)-parseFloat(a_pagar));

	    });

	    $("#inputMontoBonificado").on("change",function(){
	        var monto = $("#inputMontoBonificado").val() ;
	        var total = $('[name=inputMonto]').val();

	        var a_pagar = parseFloat(total)-parseFloat(monto);
	         $("#inputPorcentajeBonificado").val('');
	        //$("#inputPorcentajeBonificado").val((parseFloat(total)*100)/parseFloat(monto));
	        //console.log(monto, total , a_pagar);
	       	$("#inputMontoFinal").val(a_pagar);

	    });
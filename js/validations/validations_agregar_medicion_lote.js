    $(document).ready(function() {
      //validar los campos que tiene agregar cliente

        $('#enviar_formulario_de_mediciones_lote').on("click", function(e){
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
		           // beforeSend: function () {
		           //         $("#status").html('<div class="alert alert-info"><i class="md md-spin md-rotate-right"></i> Comprobando Datos..</div>');
		           // },
		          success:  
			function (response) {
			 //alert("vovlio"+response);
			  	console.log(response);
			  	if(response !== false ){
			  		console.log("entre al true");
			  		//window.setTimeout(exito,600);
		            	    //function redireccion(){
		            	        location.reload();
		            	  //  }
			  		
		     //        		swal("Cliente Borrado!", "Se ha eliminado el cliente.", "success"); 
							
							// setTimeout("redireccionarPaginaConexion()", 1500);
		            	   /* function exito(){
		            	    $("#status").html('<div class="alert alert-success"><i class="md md-check"></i> Estas Logueado</div>');
		            	    }
		            	    window.setTimeout(exito,600);
		            	    function redireccion(){
		            	        location.reload();
		            	    }
		            	    window.setTimeout(redireccion,1200);*/
				}else{
					if(response == false){
						alert ("volvio un false");
						console.log("entre al faslse");
		            		//	setTimeout("redireccionarPaginaConexion()", 1200);

		            	       /* function error(){
		            	            $("#status").html('<div class="alert alert-danger">El usuario y la contraseña son incorrectos</div>');
		            	        }
		            	        window.setTimeout(error,600);*/
		            	    }
		            	}
		            }
		        });
		    });


         


          // if(ok == null)
          // {
          //   //$( "#form_agregar_medicion" ).submit();
          //   return;
          // }
          // else
          // {
          //   //alert(ok);
          //   swal("Formulario con errores!", "Debe corregir:"+ok, "error");


          //   event.preventDefault();
          //   return;
          // }
        });



$("#inputTipoPago").on("change",function(){
    var valor = $("#inputTipoPago").val();
    if(valor === "Contado" )
    {
        $("#div_cantidad_cuotas").hide('1800');
    }
    else
    {
       $("#div_cantidad_cuotas").show('1800');

    }


    });
  });
    
    
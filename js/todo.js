
    $(document).ready(function() {

    //  $(".chosen").chosen({allow_single_deselect: false, disable_search: false});

    //     $('.aaff').click(function(){
    //     $('#nuevo_evento_modal').modal("show");


    // });
        

        $('#agregar_tarea_nueva').click(function(){
            var cuerpo_tarea = $('#cuerpo_de_tarea').val();
            
            $.ajax({
              url: 'tareas/guardar_tarea',
              type: 'POST',
              async: true,
              data: {'tarea': cuerpo_tarea},
              success: function(response){
                alert(response);
                if(response != false ) // se cargo correctamente
                {
                    alert ("Tarea guardada correctamente");
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


        
        
      

    });
    
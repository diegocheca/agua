/*$(document).on('ready',function()
{
 $('#calendar').fullCalendar({
        //weekends: false
        header: {
						left: 'prev,next today',
						center: 'title',
						right: 'month,basicWeek,basicDay'
					},
        defaultDate: new Date(),
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		editable: true,
					
		//handleWindowResize: true,
		//weekends: false, // Hide weekends
		//defaultView: 'agendaWeek', // Only show week view
		height: 150,
		//header: false, // Hide buttons/titles
		minTime: '07:30:00', // Start time for the calendar
		maxTime: '22:00:00', // End time for the calendar
		// columnFormat: {
		//     week: 'ddd' // Only show day of the week names
		// },
		lang:'es',
		events : [
        {
            title  : 'event1',
            start  : '2017-07-01'
        },
        {
            title  : 'event2',
            start  : '2017-07-06',
            end    : '2017-07-07'
        },
        {
            title  : 'event3',
            start  : '2010-01-09 12:30:00',
            allDay : false // will make the time show
        }
        ],
        eventColor: '#378006'
		// displayEventTime: true, // Display event time
  //       dayClick: function() {
  //           alert('a day has been clicked!');
  //       }
    })
 });//fin document on ready
*/
    $(document).ready(function() {

     $(".chosen").chosen({allow_single_deselect: false, disable_search: false});

        $('.aaff').click(function(){
        $('#nuevo_evento_modal').modal("show");


    });

        function redireccionarPaginaIndex () {
  window.location = 'http://192.168.1.35/codeigniter';
}


        $('#guadar_evento_nuevo_modal').click(function(){
            var id =  $('#id_evento_nuevo').val();
            var persona = $('#select_persona_raliza_evento_nuevo').val();
            var fecha_inicio = $('#fecha_inicio_evento_nuevo').val();
            var fecha_fin = $('#fecha_fin_evento_nuevo').val();
            var titulo_evento_nuevo = $('#titulo_evento_nuevo').val();
            var aclaracion = $('#aclaracion_evento_nuevo').val();
            var procentaje = $('#porcentaje_evento_nuevo').val();
            var estado = $('#estado_evento_nuevo').val();
            var duracion = $('#duracion_evento_nuevo').val();
            var materiales = $('#select_material').val();
            var color = $('#html5colorpicker').val();
            //validar los datos ingresados
            //if(resultado)
            //alert(color);
            
            $.ajax({
              url: 'tareas/guardar_evento',
              type: 'POST',
              async: true,
              data: {'id_evento_nuevo': id, 'persona': persona,'fecha_inicio': fecha_inicio,'fecha_fin': fecha_fin,'titulo_evento_nuevo': titulo_evento_nuevo,'aclaracion': aclaracion,'procentaje': procentaje,'estado': estado,'duracion': duracion,'materiales': materiales,'color': color},
              success: function(response){
                if(response != false ) // se cargo correctamente
                {
                    $('#id_evento_nuevo').val('-1');
                    $('#select_persona_raliza_evento_nuevo').val('');
                    $('#fecha_inicio_evento_nuevo').val('');
                    $('#fecha_fin_evento_nuevo').val('');
                    $('#titulo_evento_nuevo').val('');
                    $('#aclaracion_evento_nuevo').val('');
                    $('#porcentaje_evento_nuevo').val('');
                    $('#estado_evento_nuevo').val('');
                    $('#duracion_evento_nuevo').val('');
                    $('#select_material').val('');
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

            //$('#nuevo_evento_modal').modal("show");
        });


        
        
        $.post('http://localhost/codeigniter/tareas/getEventos',
            function(data){
                //alert(data);

                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,basicWeek,basicDay'
                    },
                    defaultDate: new Date(),
                    navLinks: true, // can click day/week names to navigate views
                    editable: true,
                    eventLimit: true, // allow "more" link when too many events
                    editable: true,
                    minTime: '07:30:00', // Start time for the calendar
                    maxTime: '22:00:00', // End time for the calendar
                    lang:'es',
                    events: $.parseJSON(data),
                    eventColor: '#378336',
                    eventDrop: function(event, delta, revertFunc){
                        var id = event.id;

                        var fi = event.start.format();
                        var ff = event.end.format();

                        if (!confirm("Esta seguro??")) {
                            revertFunc();
                        }else{
                            $.post("http://192.168.1.35/codeigniter/tareas/updEvento",
                            {
                                id:id,
                                fecini:fi,
                                fecfin:ff
                            },
                            function(data){
                                if (data == 1) {
                                    swal("Tarea Modificada!", "Se ha actualizado correctamente la tarea", "success"); 
                                    setTimeout("redireccionarPaginaIndex()", 500);
                                    //alert('Se actualizo correctamente');
                                }else{
                                   // alert('ERROR.');
                                   swal("Error!", "Se ha producido un error con la base de datos", "error"); 
                                }
                            });
                        }
                    },
                    eventResize: function(event, delta, revertFunc) {
                        var id = event.id;
                        var fi = event.start.format();
                        var ff = event.end.format();

                        if (!confirm("Esta seguro de cambiar la fecha?")) {
                            revertFunc();
                        }else{
                            $.post("http://192.168.1.35/codeigniter/tareas/updEvento",
                            {
                                id:id,
                                fecini:fi,
                                fecfin:ff
                            },
                            function(data){
                                if (data == 1) {
                                    swal("Tarea Modificada!", "Se ha actualizado correctamente la tarea", "success");
                                    setTimeout("redireccionarPaginaIndex()", 500);
                                    //alert('Se cambio correctamente');
                                }else{
                                    swal("Error!", "Se ha producido un error con la base de datos", "error"); 
                                    //alert('ERROR.');
                                }
                            });
                        }
                    },
                    eventClick: function(event) {

                        // alert(event.title);
                        // var superstring = null;
                        // superstring = event.id+event.title+event.start+event.end+event.porcentaje+event.materiales;
                        // alert (superstring);
                        

                        //datos del modal para cargar y modificar
                       // $('#select_persona_evento_nuevo').val(1);
                       //  $('#nuevo_evento_modal').modal('show');
                       //  $('#fechainicio_evento_nuevo').val(event.start);
                       //  $('#fechaFin_evento_nuevo').val(event.end);
                       //  $('#descripcion_evento_nuevo').val(event.title);
                       //  $('#aclaracion_evento_nuevo').val(event.observacion);
                       // // $('#select_material_evento_nuevo').val();
                       //  $('#porcentaje_evento_nuevo').val(event.porcentaje);
                       //  $('#select_estado_evento_nuevo').val(event.url);
                      //  $('#example-color-input').val();

                        // superstring = "la concha de tu madre";
                        // if (!($.isNumeric( event.url)) ) {
                        //     window.open(event.url);
                        //     return false;
                        // }
                   
                    },
                    eventRender: function(event, element) {
                        var el = element.html();
                        element.html("<div>" + el + "</div>" + 
                                    "<div style='color:red;text-align:right;' class='closeE'>" +
                                        "<i class='zmdi zmdi-delete'></i>" +
                                    "</div>");

                        element.find('.closeE').click(function(){
                            if (!confirm("Esta seguro de eliminar el evento?")) {
                                return false;
                            }else{
                                var id = event.id;
                                $.post("http://192.168.1.35/codeigniter/tareas/deleteEvento",
                                {
                                    id:id
                                },
                                function(data){
                                    alert(data);
                                    if (data == 1) {
                                        $('#calendar').fullCalendar( 'removeEvents', event.id);
                                        alert('Se elimino correctamente');
                                    }else{
                                        alert('ERROR.');
                                    }
                                });
                                
                            }

                        });
                    }
                    
                });
            });

$('#btnUpdEvento').click(function(){
        var nome = $('#txtBandaRP').val();
        var web = $('#txtWeb').val();
        var ide = $('#mhdnIdEvento').val();

        $.post("http://192.168.1.35/codeigniter/tareas/updEvento2",
        {
            nom: nome,
            web: web,
            id: ide
        },
        function(data){
            if (data == 1) {
                $('#btnCerrarModal').click();
            }
        })
    })

    });
    
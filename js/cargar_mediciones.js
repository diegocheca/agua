
	$(document).ready(function() {

	//  $(".chosen").chosen({allow_single_deselect: false, disable_search: false});

	//     $('.aaff').click(function(){
	//     $('#nuevo_evento_modal').modal("show");


	// });
		
  

   
		$('#enviarConsulta').click(function(){
			$("#resultado_query").html('');
				console.log("hola desde la cosnola");
				var select_sector_imprimir = $("#select_sector_imprimir").val();
				//alert(select_sector_imprimir);
				$.ajax({
					url: 'http://localhost/codeigniter/mediciones/ejecutar_query',
					type: 'POST',
					async: true,
					data: {'sectores': select_sector_imprimir},
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

		// $('[id^=inputMedicionActual_]').change(function(){
		//   alert("cambio algo");
		// });



 $('.repitoso').repeater({
			// (Optional)
			// start with an empty list of repeaters. Set your first (and only)
			// "data-repeater-item" with style="display:none;" and pass the
			// following configuration flag
			initEmpty: true,
			// (Optional)
			// "defaultValues" sets the values of added items.  The keys of
			// defaultValues refer to the value of the input's name attribute.
			// If a default value is not specified for an input, then it will
			// have its value cleared.
			// defaultValues: {
			//     'text-input': '0'
			// },
			// (Optional)
			// "show" is called just after an item is added.  The item is hidden
			// at this point.  If a show callback is not given the item will
			// have $(this).show() called on it.
			show: function () {
				$(this).slideDown();
			},
			// (Optional)
			// "hide" is called when a user clicks on a data-repeater-delete
			// element.  The item is still visible.  "hide" is passed a function
			// as its first argument which will properly remove the item.
			// "hide" allows for a confirmation step, to send a delete request
			// to the server, etc.  If a hide callback is not given the item
			// will be deleted.
			hide: function (deleteElement) {
				if(confirm('Are you sure you want to delete this element?')) {
					$(this).slideUp(deleteElement);
				}
			},
			// (Optional)
			// You can use this if you need to manually re-index the list
			// for example if you are using a drag and drop library to reorder
			// list items.
			ready: function (setIndexes) {
				$dragAndDrop.on('drop', setIndexes);
			},
			// (Optional)
			// Removes the delete button from the first list item,
			// defaults to false.
			isFirstItemUndeletable: true
		})


		
		
	  

	});
	
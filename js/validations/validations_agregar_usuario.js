$(document).ready(function() {
//validar los campos que tiene agregar usuarios
	$('#validar_campos_de_usuarios').click(function(e) {
		e.preventDefault();
		var ok = '';
		var usuario = $('#inputUsuario').val();
		if (!((usuario.length <40) && (usuario.length >4) && (usuario != "")))
			ok += " * Error en el campo Usuario \n ";
		var bandera =  $('#id').val();
		if( bandera == -1)
		{
			var pass1 = $('#inputPass').val();
			if(!((pass1.length>=4) && (pass1.length<=12)))
				ok += " * Error en el campo Contraseña: debe tener en 4 y 12 caracteres \n ";
			if(pass1 == "")
				ok += " * Error en el campo Contraseña: no puede ser nula \n ";
			var pass2 = $('#inputPass_dos').val();
			if(pass2 != pass1)
				ok += " * Error las contraseñas deben ser iguales \n ";
		}
		var rol = $('#rol').val();
		if(!((rol == "administrador") || (rol == "secretaria") || (rol == "medidores")))
			ok += " * Error en el campo Rol \n "; 
		var nombre = $('#inputNombre').val();
		if (!((nombre.length <40) && (nombre.length >4) && (nombre != "")))
			ok += " * Error en el campo Nombre \n ";
	 if(ok == '')
		{
			$( "#form_agregar_usuarios" ).submit();
			return;
		}
		else
		{
			swal("Formulario con errores!", "Debe corregir: "+ok, "error");
			event.preventDefault();
			return;
		}
	});
});
 $(document).ready(function() {
      //validar los campos que tiene agregar cliente

        $('#form_agregar_material').submit(function(e){
          e.preventDefault();
          var ok = '';
          var codigo = $('#inputCodigo').val();
          if (
              !(
                (codigo>=1) 
                  && 
                !(isNaN(codigo))
                 && 
                 (codigo != "")
                 &&
                 (codigo<6000)
               )
              )
            ok += "\n * Error en el campo Codigo \n";
          var cantidad = $('#inputCantidad').val();
          if (!((cantidad>=1) && !(isNaN(cantidad)) && (cantidad != "") && (cantidad<6000)))
            ok += " * Error en el campo Cantidad \n ";
          var descripcion = $('#inputDescripcion').val();
          if (!((descripcion.length <100) && (descripcion.length >=3) && (descripcion != "")))
            ok += " * Error en el campo Descripcion \n ";
          if(ok == '')
          {
            this.submit();
            return;
          }
          else
          {
            //alert(ok);
            swal("Formulario con errores!", "Debe corregir:"+ok, "error");
            event.preventDefault();
            return;
          }
        });

  });
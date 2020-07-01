$(document).ready(function() {
      //validar los campos que tiene agregar cliente

        $('#form_agregar_orden_trabajo').submit(function(e){
          //e.preventDefault();
          var ok = '';
          var tarea = $('#inputTarea').val();
          if (!((tarea.length>=4) &&(tarea.length<100) && (tarea != "")))
          {
            ok += "\n * Error en el campo Tarea \n";
          }
          // var usuario = $('#inputNombreUsuario').val();
          // if (!((usuario.length>=4) &&(usuario.length<100) && (usuario != "")))
          // {
          //   ok += " * Error en el campo Usuario \n ";
          // }
          // var conexion = $('#inputConexionId').val();
          // if (!((conexion>=1) && (conexion<9999) && (conexion != "")))
          // {
          //   ok += " * Error en el campo Conexion \n ";
          // }
          var tecnico = $('#inputTecnico').val();
          if (!((tecnico.length <100) && (tecnico.length >=3) && (tecnico != "")))
          {
            ok += " * Error en el campo Técnico \n ";
          }
          var fecha = $('#inputFecha').val();
          //revisar otra validacion de fecha
          if (!((fecha.length <100) && (fecha.length >=3) && (fecha != "")))
          {
            ok += " * Error en el campo Fecha \n ";
          }
          //materiales
        //   var codigoMat1 = $('#inputCodigoMaterial1').val();
        //   if (!((codigoMat1>=1) && (codigoMat1<9999) && (codigoMat1 != "")))
        //   {
        //     ok += " * Error en el campo Código material 1 \n ";
        //   }
        //    var descripcionMat1 = $('#inputDescricionMaterial1').val();
        //   if (!((descripcionMat1.length <100) && (descripcionMat1.length >=3) && (descripcionMat1 != "")))
        //   {
        //     ok += " * Error en el campo Descripcion material 1 \n ";
        //   }
        //    var cantidadMat1 = $('#inputCantMaterial1').val();
        //   if (!((cantidadMat1>=1) && (cantidadMat1<9999) && (cantidadMat1 != "")))
        //   {
        //     ok += " * Error en el campo Cantidad material 1 \n ";
        //   }
        //   //a partir de aca el material puede ser null
        //   //material 2
        //     var codigoMat2 = $('#inputCodigoMaterial2').val();
        //     var mat2 = false;
        //     if(codigoMat2 != "")
        //     {
        //         mat2 = true;
        //     }
        //     if(mat2 == true)
        //     {
        //         if (!((codigoMat2>=1) && (codigoMat2<9999)))
        //         {
        //             ok += " * Error en el campo Código material 2 \n ";
        //         }
        //     }
        //     var descripcionMat2 = $('#inputDescricionMaterial2').val();
        //     if(mat2 == true){
        //         if (!((descripcionMat2.length <100) && (descripcionMat2.length >=3)))
        //         {
        //             ok += " * Error en el campo Descripcion material 2 \n ";
        //         }    
        //     } else {
        //         if(descripcionMat2 != "")
        //         {
        //             ok += " * Error en el campo Descripcion material 2 \n ";
        //         }
        //     }
            
        //     var cantidadMat2 = $('#inputCantMaterial2').val();
        //     if(mat2 == true)
        //     {
        //         if (!((cantidadMat2>=1) && (cantidadMat2<9999)))
        //         {
        //             ok += " * Error en el campo Cantidad material 2 \n ";
        //         }
        //     } else {
        //         if(cantidadMat2 != "")
        //         {
        //             ok += " * Error en el campo Cantidad material 2 \n ";
        //         }
        //     }
        // //material 3
        //     var codigoMat3 = $('#inputCodigoMaterial3').val();
        //     var mat3 = false;
        //     if(codigoMat3 != "")
        //     {
        //         mat3 = true;
        //     }
        //     if(mat3 == true)
        //     {
        //         if (!((codigoMat3>=1) && (codigoMat3<9999)))
        //         {
        //             ok += " * Error en el campo Código material 3 \n ";
        //         }
        //     }
        //     var descripcionMat3 = $('#inputDescricionMaterial3').val();
        //     if(mat3 == true){
        //         if (!((descripcionMat3.length <100) && (descripcionMat3.length >=3)))
        //         {
        //             ok += " * Error en el campo Descripcion material 3 \n ";
        //         }    
        //     } else {
        //         if(descripcionMat3 != "")
        //         {
        //             ok += " * Error en el campo Descripcion material 3 \n ";
        //         }
        //     }
            
        //     var cantidadMat3 = $('#inputCantMaterial3').val();
        //     if(mat3 == true)
        //     {
        //         if (!((cantidadMat3>=1) && (cantidadMat3<9999)))
        //         {
        //             ok += " * Error en el campo Cantidad material 3 \n ";
        //         }
        //     } else {
        //         if(cantidadMat3 != "")
        //         {
        //             ok += " * Error en el campo Cantidad material 3 \n ";
        //         }
        //     }
        // //meterial 4
        //     var codigoMat4 = $('#inputCodigoMaterial4').val();
        //     var mat4 = false;
        //     if(codigoMat4 != "")
        //     {
        //         mat4 = true;
        //     }
        //     if(mat4 == true)
        //     {
        //         if (!((codigoMat4>=1) && (codigoMat4<9999)))
        //         {
        //             ok += " * Error en el campo Código material 4 \n ";
        //         }
        //     }
        //     var descripcionMat4 = $('#inputDescricionMaterial4').val();
        //     if(mat4 == true){
        //         if (!((descripcionMat4.length <100) && (descripcionMat4.length >=3)))
        //         {
        //             ok += " * Error en el campo Descripcion material 4 \n ";
        //         }    
        //     } else {
        //         if(descripcionMat4 != "")
        //         {
        //             ok += " * Error en el campo Descripcion material 4 \n ";
        //         }
        //     }
            
        //     var cantidadMat4 = $('#inputCantMaterial4').val();
        //     if(mat4 == true)
        //     {
        //         if (!((cantidadMat4>=1) && (cantidadMat4<9999)))
        //         {
        //             ok += " * Error en el campo Cantidad material 4 \n ";
        //         }
        //     } else {
        //         if(cantidadMat4 != "")
        //         {
        //             ok += " * Error en el campo Cantidad material 4 \n ";
        //         }
        //     }
        // //meterial 5
        //     var codigoMat5 = $('#inputCodigoMaterial5').val();
        //     var mat5 = false;
        //     if(codigoMat5 != "")
        //     {
        //         mat5 = true;
        //     }
        //     if(mat5 == true)
        //     {
        //         if (!((codigoMat5>=1) && (codigoMat5<9999)))
        //         {
        //             ok += " * Error en el campo Código material 5 \n ";
        //         }
        //     }
        //     var descripcionMat5 = $('#inputDescricionMaterial5').val();
        //     if(mat5 == true){
        //         if (!((descripcionMat5.length <100) && (descripcionMat5.length >=3)))
        //         {
        //             ok += " * Error en el campo Descripcion material 5 \n ";
        //         }    
        //     } else {
        //         if(descripcionMat5 != "")
        //         {
        //             ok += " * Error en el campo Descripcion material 5 \n ";
        //         }
        //     }
            
        //     var cantidadMat5 = $('#inputCantMaterial5').val();
        //     if(mat5 == true)
        //     {
        //         if (!((cantidadMat5>=1) && (cantidadMat5<9999)))
        //         {
        //             ok += " * Error en el campo Cantidad material 5 \n ";
        //         }
        //     } else {
        //         if(cantidadMat5 != "")
        //         {
        //             ok += " * Error en el campo Cantidad material 5 \n ";
        //         }
        //     }
        // //
         if(ok == '')
          {
            $( "#form_agregar_material" ).submit();
            return;
          }
          else
          {
            //alert(ok);
            swal("Formulario con errores!", "Debe corregir: "+ok, "error");

            event.preventDefault();
            return;
          }
        });

  });
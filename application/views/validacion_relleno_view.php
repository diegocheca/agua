<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Trazabilidad de Residuos</title>
    <style>
        SELECT, INPUT[type="text"] {
            width: 160px;
            box-sizing: border-box;
        }
        SECTION {
            padding: 8px;
            background-color: #f0f0f0;
            overflow: auto;
        }
        SECTION > DIV {
            float: left;
            padding: 4px;
        }
        SECTION > DIV + DIV {
            width: 40px;
            text-align: center;
        }
    </style>
  
  
</head>
<body>
<?php echo validation_errors(); ?>
    <div id="wrapper">
        <!-- Navigation -->
      <?php include('link_relleno_view.php');?>
        
         
        <div id="page-wrapper">      
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="text-align: center">Confirmando Recepcion en Relleno</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">                
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"> 
                            Datos de La Orden De Transporte N° 
                        </div>
                        <div class="panel-body">



                            <div class="row">
                                 <?=@$error?>
                                 <?php $direccion = base_url('relleno/validacion_final_relleno/');?>
                                <?=form_open_multipart(base_url()."relleno/validacion_final_relleno"?>
                                         <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                   <font color="purple">Tipo de Residuo</font> 
                                                </div>
                                            <div class="panel-body">

                                        <br><br><br>
                                        <div class="form-group">
                                            <label>Agregar foto:</label>
                                            <input type="file"  name="userfile" class="btn btn-info">
                                        </div>
                                        <br>
                                        <input type="submit" class="btn btn-outline btn-success" style="width:40%; height:99%" value="Recepcionar" />
                                        <br><br>
                                        <input class="btn btn-outline btn-warning"  style='width:40%; height:99%' type="reset" aling="center" name="Submit" value="Borrar">
                                    <br><br>
                                    <a href='<?php echo base_url("relleno") ?>' class="btn btn-outline btn-info" style='width:40%; height:99%' type="button" >Voler a menu</a>
                                    

                              <!--  </form>-->
                                <?=form_close()?>
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
        </div>
            <!-- /.row -->
        </div>
        <br><br>
        <!-- /#page-wrapper -->
    </div>

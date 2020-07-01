<?php
include_once('fpdf.php');
class PDF extends FPDF
{
        function Footer() // Pie de página
        {
        // Posición: a 4,0 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        /* Cell(ancho, alto, txt, border, ln, alineacion)
         * ancho=0, extiende el ancho de celda hasta el margen de la derecha
         * alto=10, altura de la celda a 10
         * txt= Texto a ser impreso dentro de la celda
         * borde=T Pone margen en la posición Top (arriba) de la celda
         * ln=0 Indica dónde sigue el texto después de llamada a Cell(), en este caso con 0, enseguida de nuestro texto
         * alineación=C Texto alineado al centro
         */
        $this->Cell(0,5,'La totalidad de los datos presentados e este orden de transporte presetan un caracrter de "declaracion jurada" debiendo reflejar','T',1,'L');
        $this->Cell(0,5,'su contenido la total realidad de todos los items considerados.','N',2,'L');
        //$this->Cell(0,5,'El trasnportista Municipal deberá completar el aprtado de "GENERADOR" ademas de adjuntar la Hoja de Rta de recoleccion a la presente.','N',3,'L');
        //$this->Cell(0,12,'ORIGINAL: BLANCO - DUPLICADO: ROSA - TRIPLICADO: VERDE - CUATRIPLICADO: AMARILLO     R014-03','T',0,'C');
        }

       function Header()
        {
            //Define tipo de letra a usar, Arial, Negrita, 15
            $this->SetFont('Arial','B',12);
            /* Líneas paralelas
            * Line(x1,y1,x2,y2)
            * El origen es la esquina superior izquierda
            * Cambien los parámetros y chequen las posiciones
            * */
            $this->Line(10,10,206,10);
            $this->Line(10,25,206,25);
            /* Explicaré el primer Cell() (Los siguientes son similares)
            * 30 : de ancho
            * 25 : de alto
            * ' ' : sin texto
            * 0 : sin borde
            * 0 : Lo siguiente en el código va a la derecha (en este caso la segunda celda)
            * 'C' : Texto Centrado
            * $this->Image('images/logo.png', 152,12, 19) Método para insertar imagen
            *     'images/logo.png' : ruta de la imagen
            *         152 : posición X (recordar que el origen es la esquina superior izquierda)
            *         12 : posición Y
             *     19 : Ancho de la imagen <span class="wp-smiley emoji emoji-wordpress" title="(w)">(w)</span>
            *     Nota: Al no especificar el alto de la imagen (h), éste se calcula automáticamente
            * */

            /*VOY A SACAR LAS DOS PROXIMAS LINEAS*
            $this->Cell(30,25,'',0,0,'C',$this->Image('C:/xampp/htdocs/Ejemplo de crear pdf desde php/pdfenphpupdate/img/logo.jpg', 152,12, 19));
            $this->Cell(111,25,'ORDEN DE TRANSPORTE',0,0,'C', $this->Image('C:/xampp/htdocs/Ejemplo de crear pdf desde php/pdfenphpupdate/img/logo.jpg',20,12,20));



            /*Voy a poner las dos siguietnees lineas*/

           // $this->Cell(30,25,'',0,0,'C','asdasd', 152,12, 19));
            //$this->Cell(111,25,'ORDEN DE TRANSPORTE',0,0,'C',"da" ,20,12,20);




            //$this->Cell(40,25,'',0,0,'C',$this->Image('C:/xampp/htdocs/Ejemplo de crear pdf desde php/pdfenphpupdate/img/logoDerecha.png', 175, 12, 19));
            //Se da un salto de línea de 25
            //$this->Ln(25);
          //  $this->Ln();
        }
        function ticker($fecha,$orden,$gen,$sol_trans,$nombre,$dni)
        {

            $this->AddPage('P', 'A5'); //Vertical, Carta
            $this->SetFont('Arial',9); //Arial, 9 puntos
            $fecha= date("d-m-Y H:i:s");
            //Imprime la fecha
            $pdf->Cell(0,5,"Fecha de emisión: ".$fecha,1,0,'L');
             //Imprime un texto
             $pdf->Ln(2);
             $str =  html_entity_decode("&uacute;");

             //$pdf->Cell(0,5, $str.$orden->id_orden,0, 1 ,'L');
             $pdf->Cell(0,5, "N".$str."mero de OT:".$orden->id_orden,'L');
             $pdf->Ln(2);
             if($gen->dni!=NULL)
                 if($gen->tipo_dni=='mu')
                     $pdf->Cell(0,5,"Razon Social:  $gen->razon_social - Zona:$solicitud->zona ",3,'L');
                 else
                     $pdf->Cell(0,5,"Razon Social:  $gen->razon_social                                          DNI:     $gen->dni  ",3,'L');
             else if($gen->tipo_dni=='mu')
                     $pdf->Cell(0,5,"Razon Social:  $gen->razon_social Zona:$solicitud->zona ",3,'L');
                 else $pdf->Cell(0,5,"Razon Social:  $gen->razon_social    ",3,'L');
             $pdf->Ln(2);
            if(($sol_trans->tipo_residuos=='VIDRIO/9')||($sol_trans->tipo_residuos=='RSU_MUNI/1')||($sol_trans->tipo_residuos=='RSU_PRIV/2')||($sol_trans->tipo_residuos=='RSU_PRIV_DES/8')||(substr($sol_trans->tipo_residuos, -2)=='-1'))
                $pdf->Cell(0,5,"Tipo de Residuo Solido Urbano (RSU) Entregado:".substr($sol_trans->tipo_residuos , 0,-2)."                                           ",5,'L');
            else $pdf->Cell(0,5,"Tipo de Residuo Solido Urbano (RSU) Entregado:".substr($sol_trans->tipo_residuos , 0,-3)."                                           ",5,'L');
            $pdf->Ln(2);
            $pdf->Cell(0,5,"Transportista:  $sol_trans->razon_social_transportista                   NdeReg: $sol_trans->id_transportista        ",9,'L');
            $pdf->Ln(2);
            $pdf->Cell(0,5,"Vehiculo/Dominio:  $sol_trans->descripcion_vehiculo / $sol_trans->patente        NdeReg:  $sol_trans->id_vehiculo       ",10,'L');
            $pdf->Ln(2);
            $pdf->Cell(0,5,"Contenedores:  $sol_trans->cantidad_contenedores  Kilos declarados:  $sol_trans->toneladas       Kilos Reales: $aux_t                   ",11,'L');
            $pdf->Ln(2);
            $pdf->Cell(0,5,"Apellido y Nombre del Chofer:  $sol_trans->nombre_chofer  -  DNI del chofer: $sol_trans->dni_chofer  ",'L');
            $pdf->Ln(2);
            $pdf->Cell(0,5,"Disposión: $disposicion Ingreso: $orden->fecha_hora_ingreso        Salida: $orden->fecha_hora_salida             ",10,'L');
            $pdf->Ln(2);
            $pdf->Cell(0,5,"Nombre del Encargado de Recepcion/ DNI:   $nombre / $dni                                                      ",11,'L');
            $pdf->Ln(2);
            if($orden->id_constatacion == null)
                $orden->id_constatacion = "no registra";
            if($orden->id_infraccion == null)
                $orden->id_infraccion = "no registra";
            $pdf->Cell(0,5,"Acta de Contatacion: $orden->id_constatacion  Acta de Infraccion: $orden->id_infraccion        ",12,'L');
            $pdf->Ln(2);
        }

        // Simple table
function BasicTable($header, $contenedores)
{
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data
    $i=1;
    foreach($contenedores as $contenedor)
    {
        $this->Cell(40,6,$i,1); $i++;
        $this->Cell(40,6,$contenedor->ContOT_Contenedor_OT,1);
        $this->Cell(40,6,$contenedor->ContOT_PesoReal,1);
        $this->Cell(40,6,$contenedor->tara,1);
        $this->Cell(40,6,$contenedor->ContOT_Porcentaje,1);
        /*
        foreach($contenedore as $key)
            $this->Cell(40,6,$key->,1);*/
        $this->Ln();
    }
}



        function FancyTable($header,$w,$data,$header_uno,$w_uno,$header_ordenes,$w_ordenes,$header_ordenes_camino,$w_ordenes_camino,$data_uno,$data_dos,$data_tres,$nombre,$algo)
    {
        // Colores, ancho de línea y fuente en negrita
        $this->Ln(18);
        $this->SetFillColor(147,244,102);
        $this->SetTextColor(34,89,198);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        // Cabecera
       // $w = array(15,40, 60,30,30);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $this->Ln();
        // Restauración de colores y fuentes
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Datos
        $fill = false;
        $num = 0;

        foreach($data as $row)
        {
          //  var_dump($row);die();
            $this->Cell($w[0],6,($num+1),'LR',0,'L',$fill); // num 
           // $this->Cell($w[1],6,$row[0],'LR',0,'L',$fill); // idu [0]
            //$this->Cell($w[2],6,$row[3],'LR',0,'L',$fill); // direccion [3]
            $this->Cell($w[1],6,$row["id_solicitud"],'LR',0,'L',$fill); //n  solicitud [4]
            $this->Cell($w[2],6,$row["fecha_hora"],'LR',0,'L',$fill); //Fecha [5]
            /*Le agrego los :*///horas[1]
            $this->Cell($w[3],6,$row["id_generador"],'LR',0,'C',$fill); //horas normales
             $this->Cell($w[4],6,$row["tipo_residuo"],'LR',0,'C',$fill); //horas normales
              $this->Cell($w[5],6,$row["razon_social_transportista"],'LR',0,'C',$fill); //horas normales
            $this->Ln();
            $fill = !$fill;
            $num++;
        }
        // Línea de cierre
        $this->Cell(array_sum($w),0,'','T');





        /* TABLA numero 1*/
          // Colores, ancho de línea y fuente en negrita
        $this->Ln(18);
        $this->SetFillColor(147,244,102);
        $this->SetTextColor(34,89,198);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        // Cabecera
       // $w = array(15,40, 60,30,30);
        for($i=0;$i<count($header_uno);$i++)
            $this->Cell($w_uno[$i],7,$header_uno[$i],1,0,'C',true);
        $this->Ln();
        // Restauración de colores y fuentes
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Datos
        $fill = false;
        $num = 0;

        foreach($data_uno as $row)
        {
          //  var_dump($row);die();
            $this->Cell($w_uno[0],6,($num+1),'LR',0,'L',$fill); // num 
           // $this->Cell($w[1],6,$row[0],'LR',0,'L',$fill); // idu [0]
            //$this->Cell($w[2],6,$row[3],'LR',0,'L',$fill); // direccion [3]
            $this->Cell($w_uno[1],6,$row["id_solicitud_transportista"],'LR',0,'L',$fill); //n  solicitud [4]
            $this->Cell($w_uno[2],6,$row["id_solicitud"],'LR',0,'L',$fill); //Fecha [5]
            /*Le agrego los :*///horas[1]
            $this->Cell($w_uno[3],6,$row["fecha_solicitud"],'LR',0,'C',$fill); //horas normales
             $this->Cell($w_uno[4],6,$row["id_generador"],'LR',0,'C',$fill); //horas normales
              $this->Cell($w_uno[5],6,$row["tipo_residuos"],'LR',0,'C',$fill); //horas normales
               $this->Cell($w_uno[6],6,$row["razon_social_transportista"],'LR',0,'C',$fill); //horas normales
            $this->Ln();
            $fill = !$fill;
            $num++;
        }
        // Línea de cierre
        $this->Cell(array_sum($w_uno),0,'','T');


         /* TABLA numero 2*/
          // Colores, ancho de línea y fuente en negrita
        $this->Ln(18);
        $this->SetFillColor(147,244,102);
        $this->SetTextColor(34,89,198);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        // Cabecera
       // $w = array(15,40, 60,30,30);
        for($i=0;$i<count($header_ordenes);$i++)
            $this->Cell($w_ordenes[$i],7,$header_ordenes[$i],1,0,'C',true);
        $this->Ln();
        // Restauración de colores y fuentes
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Datos
        $fill = false;
        $num = 0;

        foreach($data_dos as $row)
        {
          //  var_dump($row);die();
            $this->Cell($w_ordenes[0],6,($num+1),'LR',0,'L',$fill); // num 
           // $this->Cell($w[1],6,$row[0],'LR',0,'L',$fill); // idu [0]
            //$this->Cell($w[2],6,$row[3],'LR',0,'L',$fill); // direccion [3]
            $this->Cell($w_ordenes[1],6,$row["id_orden"],'LR',0,'L',$fill); //n  solicitud [4]
            $this->Cell($w_ordenes[2],6,$row["fecha_hora_ingreso"],'LR',0,'L',$fill); //Fecha [5]
            /*Le agrego los :*///horas[1]
            $this->Cell($w_ordenes[3],6,$row["tipo_residuos"],'LR',0,'C',$fill); //horas normales
            $this->Cell($w_ordenes[4],6,$row["id_generador"],'LR',0,'C',$fill); //horas normales
            $this->Cell($w_ordenes[5],6,"planta",'LR',0,'C',$fill); //horas normales
            $this->Ln();
            $fill = !$fill;
            $num++;
        }
        // Línea de cierre
        $this->Cell(array_sum($w_ordenes),0,'','T');



    }
    
 
}//fin clase PDF


?> 
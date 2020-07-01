<?php
include_once('fpdf.php');
class tickets extends FPDF
{
	    function Footer() // Pie de página
        {
        // Posición: a 4,0 cm del final
        $this->SetY(-20);
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
        //$this->Cell(0,5,'La totalidad de los datos presentados e este orden de transporte presetan un caracrter de "declaracion jurada" debiendo reflejar','T',1,'L');
        //$this->Cell(0,5,'su contenido la total realidad de todos los items considerados.','N',2,'L');
       // $this->Cell(0,5,'El trasnportista Municipal deberá completar el aprtado de "GENERADOR" ademas de adjuntar la Hoja de Rta de recoleccion a la presente.','N',3,'L');
        //$this->Cell(0,12,'ORIGINAL: BLANCO - DUPLICADO: ROSA - TRIPLICADO: VERDE - CUATRIPLICADO: AMARILLO     R014-03','T',0,'C');
        }

       function Header()
        {
	   	   	//Define tipo de letra a usar, Arial, Negrita, 15
    	    $this->SetFont('Arial','B',12);
            // Movernos a la derecha
            $this->Ln(10);
    $this->Cell(80);
    // Título
    $this->Cell(50,10,'Ticekt Comprobante',1,0,'C');
    $this->Ln(4);
            /* Líneas paralelas
         	* Line(x1,y1,x2,y2)
         	* El origen es la esquina superior izquierda
         	* Cambien los parámetros y chequen las posiciones
         	* */
        	
            //$this->Line(10,10,206,10);
        	//$this->Line(10,25,206,25);

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

        	//$this->Cell(30,25,'',0,0,'C',$this->Image('C:/xampp/htdocs/Ejemplo de crear pdf desde php/pdfenphpupdate/img/logo.jpg', 152,12, 19));
        	//$this->Cell(111,25,'Orden de Trasporte',0,0,'C', $this->Image('C:/xampp/htdocs/Ejemplo de crear pdf desde php/pdfenphpupdate/img/logo.jpg',20,12,20));

        	//Se da un salto de línea de 25
        	//$this->Ln(25);
          //  $this->Ln();
    	}
    
 
}//fin clase PDF


?> 
<?php
include_once('fpdf.php');
class Salida_rapida extends FPDF
{
	    function Footer() // Pie de página
        {
        $this->SetY(-7);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Gracias por utilizar este servicio','N',3,'L');
        }
}//fin clase PDF
?> 
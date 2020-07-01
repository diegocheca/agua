<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once('pdf_js.php');


class PDF_AutoPrint extends PDF_JavaScript
{
	function Footer() // Pie de página
    {
        $this->SetY(-7);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Gracias por utilizar este servicio','N',3,'L');
    }

	function AutoPrint($dialog=false)
	{
		//Open the print dialog or start printing immediately on the standard printer
		$param=($dialog ? 'true' : 'false');
		$script="print($param);";
		$this->IncludeJS($script);
	}

	function AutoPrintToPrinter($server, $printer, $dialog=false)
	{
		//Print on a shared printer (requires at least Acrobat 6)
		$script = "var pp = getPrintParams();";
		if($dialog)
			$script .= "pp.interactive = pp.constants.interactionLevel.full;";
		else
			$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
		$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
		$script .= "print(pp);";
		$this->IncludeJS($script);
	}

}

?> 
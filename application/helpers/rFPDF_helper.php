<?php
include_once('fpdf.php');

class rFPDF extends FPDF
{

	private $meses = array(
		'01' => "Enero" ,
		'02' => "Febrero" ,
		'03' => "Marzo" , 
		'04' => "Abril" ,
		'05' => "Mayo" ,
		'06' => "Junio" ,
		'07' => "Julio" ,
		'08' => "Agosto" ,
		'09' => "Semptiembre",
		'10' => "Octubre",
		'11' => "Noviembre",
		'12' => "Diciembre"
	);
	function Header()
	{
		// Logo
	//	$this->Image(base_url().'img/logo_villa_elisa.jpg',665,8,153);
		// Arial bold 15
		$this->SetFont('Arial','B',15);
		// Movernos a la derecha
		$this->Cell(80);
		// Título
		//$this->Cell(190,18,'Informe d',1,0,'C');
		// Salto de línea
		$this->Ln(20);
	}
	function Footer()
	{
		// Posición: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Número de página
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
	{
		$font_angle+=90+$txt_angle;
		$txt_angle*=M_PI/180;
		$font_angle*=M_PI/180;
		
		$txt_dx=cos($txt_angle);
		$txt_dy=sin($txt_angle);
		$font_dx=cos($font_angle);
		$font_dy=sin($font_angle);
		
		$s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		if ($this->ColorFlag)
		    $s='q '.$this->TextColor.' '.$s.' Q';
		$this->_out($s);
	}
	
	function creando_informe_tabla($data)
	{
		$header = $data["header"];
		$ancho_columna = $data["ancho_columna"];
		$filas = $data["filas"];
		$pdf = new rFPDF('L', 'pt');
		$pdf->SetFont('Arial','B',14);
		$pdf->AddPage();
		$this->Cell(190,18,'Informe de CLientes',1,0,'C');
		// Colors, line width and bold font
		$pdf->SetFillColor(255,0,0);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(128,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('Arial','B',14);
		for($i=0;$i<count($header);$i++)
		    $pdf->Cell($ancho_columna[$i],17,$header[$i],1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		foreach($filas as $row)
		{
			$pdf->Cell($ancho_columna[0],16,$row["Cli_Id"],'LR',0,'L',$fill);
			$pdf->Cell($ancho_columna[1],16,$row["Cli_NroDocumento"],'LR',0,'L',$fill);
			$pdf->Cell($ancho_columna[2],16,utf8_decode($row["Cli_RazonSocial"]),'LR',0,'R',$fill);
			$pdf->Cell($ancho_columna[3],16,utf8_decode($row["Cli_DomicilioSuministro"]),'LR',0,'R',$fill);
			$pdf->Cell($ancho_columna[4],16,$row["Cli_Celular"],'LR',0,'R',$fill);
			$pdf->Ln();
			$fill = !$fill;
		}
		$pdf->Cell(array_sum($ancho_columna),0,'','T');
		$pdf->Output();
	}

	function creando_informe_conexion($data)
	{
		$header = $data["header"];
		$ancho_columna = $data["ancho_columna"];
		$filas = $data["filas"];
		$pdf = new rFPDF('L', 'pt');
		$pdf->SetFont('Arial','B',14);
		$pdf->AddPage();
		// Colors, line width and bold font
		$pdf->SetFillColor(0,255,0);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(128,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('Arial','B',14);
		for($i=0;$i<count($header);$i++)
		    $pdf->Cell($ancho_columna[$i],17,$header[$i],1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		foreach($filas as $row)
		{
			$pdf->Cell($ancho_columna[0],16,$row["Cli_Id"],'LR',0,'L',$fill);
			$pdf->Cell($ancho_columna[1],16,$row["Conexion_Id"],'LR',0,'L',$fill);
			$pdf->Cell($ancho_columna[2],16,utf8_decode($row["Cli_RazonSocial"]),'LR',0,'R',$fill);
			$pdf->Cell($ancho_columna[3],16,utf8_decode($row["Conexion_DomicilioSuministro"]),'LR',0,'R',$fill);
			$pdf->Cell($ancho_columna[4],16,$row["Conexion_Deuda"],'LR',0,'R',$fill);
			if($row["Conexion_Categoria"] == 1)
				$pdf->Cell($ancho_columna[5],16,"Familiar",'LR',0,'R',$fill);
			else 
				$pdf->Cell($ancho_columna[5],16,"Comercial",'LR',0,'R',$fill);
			$pdf->Ln();
			$fill = !$fill;
		}
		$pdf->Cell(array_sum($ancho_columna),0,'','T');
		$pdf->Output();
	}

	function creando_informe_historial($data)
	{

		$header = $data["header"];
		$ancho_columna = $data["ancho_columna"];
		$filas = $data["filas"];
		$pdf = new rFPDF('L', 'pt');
		$pdf->SetFont('Arial','B',14);
		$pdf->AddPage();
		$this->Cell(190,18,'Historial de Pago',1,0,'C');
		// Colors, line width and bold font
		$pdf->SetFillColor(0,255,0);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(128,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('Arial','B',14);
		for($i=0;$i<count($header);$i++)
		    $pdf->Cell($ancho_columna[$i],17,$header[$i],1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		foreach($filas as $row)
		{

			$pdf->Cell($ancho_columna[0],16,$row["Cli_Id"],'LR',0,'L',$fill);
			$pdf->Cell($ancho_columna[1],16,$row["Conexion_Id"],'LR',0,'L',$fill);
			$pdf->Cell($ancho_columna[2],16,utf8_decode($row["Cli_RazonSocial"]),'LR',0,'R',$fill);
			$pdf->Cell($ancho_columna[3],16,utf8_decode($row["id"]),'LR',0,'R',$fill);
			$pdf->Cell($ancho_columna[4],16,date("d/m/Y" , strtotime($row["fecha_emision"])),'LR',0,'R',$fill);
			$pdf->Cell($ancho_columna[5],16,$row["Factura_Total"],'LR',0,'R',$fill);


			$pdf->Cell($ancho_columna[6],16,number_format($row["Pago_Monto"]),'LR',0,'R',$fill);
			$pdf->Cell($ancho_columna[7],16,$row["Conexion_Deuda"],'LR',0,'R',$fill);

			$pdf->Cell($ancho_columna[8],16,$row["Medicion_Anterior"],'LR',0,'R',$fill);
			$pdf->Cell($ancho_columna[9],16,$row["Medicion_Actual"],'LR',0,'R',$fill);

			if($row["Conexion_Categoria"] == 1)
				$pdf->Cell($ancho_columna[10],16,"Familiar",'LR',0,'R',$fill);
			else 
				$pdf->Cell($ancho_columna[10],16,"Comercial",'LR',0,'R',$fill);
			$pdf->Ln();
			$fill = !$fill;
		}
		$pdf->Cell(array_sum($ancho_columna),0,'','T');
		$pdf->Output();
	}


	function creando_informe_pagos_por_mes($data)
	{

		$header = $data["header"];
		$ancho_columna = $data["ancho_columna"];
		$filas = $data["filas"];
		$pdf = new rFPDF('L', 'pt');
		$pdf->SetFont('Arial','B',14);
		$pdf->AddPage();
		$this->Cell(190,18,'Historial de Pago',1,0,'C');
		// Colors, line width and bold font
		$pdf->SetFillColor(11,255,202);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(128,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('Arial','B',14);
		for($i=0;$i<count($header);$i++)
		    $pdf->Cell($ancho_columna[$i],17,$header[$i],1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		foreach($filas as $row)
		{
			$fechass = date( "m/Y", strtotime($row["fecha_emision"]) );
			$fechas_timestamp = date( "d/m/Y H:i:s", strtotime($row["Pago_Timestamp"]) );
			if( isset($row["Pago_Id"]))
				$pdf->Cell($ancho_columna[0],16,$row["Pago_Id"],'LR',0,'L',$fill);
			else 
				$pdf->Cell($ancho_columna[0],16,"Sin",'LR',0,'L',$fill);
			if( isset($row["Cli_Id"]))
				$pdf->Cell($ancho_columna[1],16,$row["Cli_Id"],'LR',0,'L',$fill);
			else
				$pdf->Cell($ancho_columna[1],16,"Sin",'LR',0,'L',$fill);
			if( isset($row["Cli_RazonSocial"]))
				$pdf->Cell($ancho_columna[2],16,utf8_decode($row["Cli_RazonSocial"]),'LR',0,'R',$fill);
			else
				$pdf->Cell($ancho_columna[2],16,"Sin",'LR',0,'R',$fill);
			if( isset($row["id"]))
				$pdf->Cell($ancho_columna[3],16,utf8_decode($row["id"]),'LR',0,'R',$fill);
			else
				$pdf->Cell($ancho_columna[3],16,"Sin",'LR',0,'R',$fill);
			if( isset($fechass))
				$pdf->Cell($ancho_columna[4],16, $fechass,'LR',0,'R',$fill);
			else
				$pdf->Cell($ancho_columna[4],16, "Sin",'LR',0,'R',$fill);
			if( isset($row["Pago_Monto"]))
				$pdf->Cell($ancho_columna[5],16,"$ ".$row["Pago_Monto"],'LR',0,'R',$fill);
			else
				$pdf->Cell($ancho_columna[5],16,"Sin",'LR',0,'R',$fill);
			if( isset($row["Medicion_Excedente"]))
				$pdf->Cell($ancho_columna[6],16,$row["Medicion_Excedente"]. " m3",'LR',0,'R',$fill);
			else
				$pdf->Cell($ancho_columna[6],16,"Sin",'LR',0,'R',$fill);
			if( isset($fechas_timestamp))
				$pdf->Cell($ancho_columna[7],16,$fechas_timestamp,'LR',0,'R',$fill);
			else
				$pdf->Cell($ancho_columna[7],16,"Sin",'LR',0,'R',$fill);
			$pdf->Ln();
			$fill = !$fill;
		}
		$pdf->Cell(array_sum($ancho_columna),0,'','T');
		$pdf->Output();
	}
}

	
?>



<?php
include_once('fpdf.php');
include_once('php-barcode.php');
/*
class Salida_rapida extends FPDF
{
		function Footer() // Pie de página
		{
		$this->SetY(-7);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,5,'Gracias por utilizar este servicio','N',3,'L');
		}
}//fin clase PDF*/

  // -------------------------------------------------- //
  //                      USEFUL
  // -------------------------------------------------- //
  
  class eFPDF extends FPDF
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
  
	public function crear_boleta()
	{
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		
		$fontSize = 10;
		$marge    = 10;   // between barcode and hri in pixel
		$x        = 150;  // barcode center
		$y        = 780;  // barcode center
		$height   = 50;   // barcode height in 1D ; module size in 2D
		$width    = 2;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		
		$code     = '183956089415'; // barcode, of course ;)
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		
		
		// -------------------------------------------------- //
		//            ALLOCATE FPDF RESSOURCE
		// -------------------------------------------------- //
		  
		$pdf = new eFPDF('P', 'pt');
		$pdf->AddPage();
		//$pdf->Image('../images/'.$image, 0, 0, $size[0], $size[1]); 
		$pdf->Image(base_url().'/img/Balance_diario.jpg', 0, 0 ,575, 750); 
		
		
		// -------------------------------------------------- //
		//                      BARCODE
		// -------------------------------------------------- //
		
		$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		
		// -------------------------------------------------- //
		//                      HRI
		// -------------------------------------------------- //
		
		$pdf->SetFont('Arial','B',$fontSize);
		$pdf->SetTextColor(0, 0, 0);
		$len = $pdf->GetStringWidth($data['hri']);
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
		
		$pdf->Output();

	}

	public function crear_factura($id)
	{
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		
		$fontSize = 8;
		$marge    = 3;   // between barcode and hri in pixel
		$x        = 232;  // barcode center
		$y        = 375;  // barcode center
		$height   = 42;   // barcode height in 1D ; module size in 2D
		$width    = 1;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		
		$code     = '183956089415'; // barcode, of course ;)
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		
		
		// -------------------------------------------------- //
		//            ALLOCATE FPDF RESSOURCE
		// -------------------------------------------------- //
		  
		$pdf = new eFPDF('P', 'pt');
		$pdf->AddPage();
		//$pdf->Image('../images/'.$image, 0, 0, $size[0], $size[1]); 
		$pdf->Image(base_url().'img/boleta_villa_elisa_v4.jpg', 0, 0 ,595, 418); 
		 
		// -------------------------------------------------- //
		//                      BARCODE
		// -------------------------------------------------- //
		
		$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		
		// -------------------------------------------------- //
		//                      HRI
		// -------------------------------------------------- //
		
		$pdf->SetFont('Arial','B',$fontSize);
		$pdf->SetTextColor(0, 0, 0);
		$len = $pdf->GetStringWidth($data['hri']);
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
		$this->SetX(0);
		$pdf->Ln(100);    
		$pdf->Cell(0,10,'                                                                07                    2017               Familiar            112233          11223344   ',0, 2 ,'R',false);
		$pdf->Ln(7);
		$pdf->Cell(0,10,'    07      2017      Familiar        112233           Excento         11223344',0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->Cell(0,10,'                                      Hombre oso                                          12                     21/09/2017                      21/09/2017                        150',0, 2 ,'L',false);
		$pdf->Ln(2);    
		$pdf->Cell(0,10,'                                      Los panales 1000 (s)                                                       21/09/2017                      21/09/2017                        150',0, 2 ,'L',false);
		$pdf->Ln(2);    
		$pdf->Cell(0,10,'                                      24-12345678-1',0, 2 ,'L',false);
		$pdf->Ln(37);
		$pdf->Cell(0,10,'       120                13                 133                10                   3',0, 2 ,'L',false);
		$pdf->Ln(14);
		$pdf->Cell(0,10,'                                    $1500.50',0, 2 ,'L',false);
		$pdf->Ln(2);
		$pdf->Cell(0,10,'                                      $150.99',0, 2 ,'L',false);
		$pdf->Ln(3);
		$pdf->Cell(0,10,'                                      $200.10                                       20/09/2017',0, 2 ,'L',false);
		$pdf->Ln(3);
		$pdf->Cell(0,10,'                                      $125.01                                       29/09/2017                           07              2017             Familiar            112233           11223344',0, 2 ,'L',false);
		$pdf->Ln(3);
		$pdf->Cell(0,10,'                                       $50.0F$shif0',0, 2 ,'L',false);
		$pdf->Ln(3);
		$pdf->Cell(0,10,'                                       $75.00                                             $2000.00                           Hombre oso                                                                 223344',0, 2 ,'L',false);
		$pdf->Ln(3);
		$pdf->Cell(0,10,'                                      $100.00',0, 2 ,'L',false);
		$pdf->Ln(4);
		$pdf->Cell(0,10,'                                    $2001.60',0, 2 ,'L',false);
		$pdf->Ln(3);
		$pdf->Cell(0,10,'                                       $80.00                                                                              21/09/2017                      21/09/2017                        150',0, 2 ,'L',false);
		$pdf->Ln(2);
		$pdf->Cell(0,10,'                                      $200.60                                                                              21/09/2017                      21/09/2017                        150',0, 2 ,'L',false);
		$pdf->Ln(3);
		$pdf->Cell(0,10,'                                    $1721.00',0, 2 ,'L',false);

		$pdf->Output();
	}

	public function calcular_codigo_barra_agua($conexion_id, $factura_id )
	{
		$code     = '1 8 3 9 5 6 0 8 9 4 1 5'; // barcode, of course ;)
		$aux_id_conexion = $conexion_id;
		$aux_id_conexion = str_pad($aux_id_conexion, 4, "0", STR_PAD_LEFT);
		$aux_factura = $factura_id;
		$aux_factura = str_pad($aux_factura, 6, "0", STR_PAD_LEFT);
		$codigo_barra = "1".$aux_id_conexion.$aux_factura;
		$control= 0;
		for ($i=0; $i < strlen($codigo_barra) ; $i++) { 
			$control  += $codigo_barra[$i];
		}
		$control_dos=$control %7;
		//var_dump($control, $control_dos);die();
		
		$codigo_barra = "1".$aux_id_conexion.$aux_factura.$control_dos; //boleta de servicio de agua
		return $codigo_barra;
	}


// // Tabla simple
// function BasicTable($header, $data)
// {
//     // Cabecera
//     foreach($header as $col)
//         $this->Cell(110,25,$col,1);
//     $this->Ln();
//     // Datos
//     foreach($data as $row)
//     {
//         foreach($row as $col)
//             $this->Cell(110,25,$col,1);
//         $this->Ln();
//     }
// }


// // Una tabla más completa
// function ImprovedTable($header, $data)
// {
//     // Anchuras de las columnas
//     $w = array(40, 35, 45, 40);
//     // Cabeceras
//     for($i=0;$i<count($header);$i++)
//         $this->Cell($w[$i],7,$header[$i],1,0,'C');
//     $this->Ln();
//     // Datos
//     foreach($data as $row)
//     {
//         $this->Cell($w[0],6,$row[0],'LR');
//         $this->Cell($w[1],6,$row[1],'LR');
//         $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
//         $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
//         $this->Ln();
//     }
//     // Línea de cierre
//     $this->Cell(array_sum($w),0,'','T');
// }

	function poner_codigo_barra($code,$x= 80,$y=125,$pdf, $nuena_pagina=0)
	{
		if($x == null)
			$x = 80;
		if($y == null)
			$y = 125;
		//$code  = $this->calcular_codigo_barra_agua($key->Conexion_Id,$key_boleta->id);
		$fontSize = 8;
		$marge    = 0;   // between barcode and hri in pixel
		$height   = 13;   // barcode height in 1D ; module size in 2D
		$width    = 0.4;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		// $key = $datos["resultado"];
		// $key_boleta = $datos["boleta"];
		// $code  = $this->calcular_codigo_barra_agua($key->Conexion_Id,$key_boleta->id);
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		
		$pdf->SetAutoPageBreak(true,1); 
		if($nuena_pagina==0)
			{
					$this->SetX(0);
					$pdf->AddPage();
					$pdf->Image(base_url().'img/Boleta_Villa_Elisa_5.jpg', 0, 0 ,210, 150); 
			}
		else 
			{
				$pdf->Image(base_url().'img/Boleta_Villa_Elisa_5.jpg', 0, 150 ,210, 150); 
			}
		// $x        = 80;  // barcode center
		// $y        = 125;  // barcode center
		$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		$pdf->SetFont('Arial','B',$fontSize);
		$pdf->SetTextColor(0, 0, 0);
		$len = $pdf->GetStringWidth($data['hri']);
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
	}
	function tabla_datos_facturas($header, $data, $x = 0, $y = 0) {
		$this->SetXY($x , $y);
		// Header
		foreach($header as $col)
			$this->Cell(15 ,7,utf8_decode($col),1,0,'C');
		$this->Ln();
		// Data
		$i = 7;
		$this->SetXY($x , $y + $i);
		foreach($data as $row){
			foreach($row as $col){
				//$this->SetXY($x , $y + $i);
				$this->Cell(15 ,6,utf8_decode($col),1,0,'C');
				
			}
			$i= $i + 6 ;  // incremento el valor de la columna
			$this->SetXY($x , $y + $i);     
		  //$this->Ln();
		}
	}

	function tabla_datos_factura_1($header, $data, $x = 0, $y = 0) {
		$this->SetXY($x , $y);
		// Header
		foreach($header as $col)
			$this->Cell(18 ,7,utf8_decode($col),1,0,'C');
		$this->Ln();
		// Data
		$i = 7;
		$this->SetXY($x , $y + $i);
		foreach($data as $row){
			foreach($row as $col){
				//$this->SetXY($x , $y + $i);
				$this->Cell(18 ,6,utf8_decode($col),1,0,'C');
				
			}
			$i= $i + 6 ;  // incremento el valor de la columna
			$this->SetXY($x , $y + $i);     
		  //$this->Ln();
		}
	}
	function tabla_vencimientos_1($header, $data, $x = 0, $y = 0) {
		$this->SetXY($x , $y);
		// Header
		// foreach($header as $col)
		// 	$this->Cell(30 ,6,utf8_decode($col),1,0,'C');
		$this->Ln();
		// Data
		$i = 6;
		
		$this->SetXY($x , $y + $i);
		foreach($data as $row){
			$primero = true;
			foreach($row as $col){
				//$this->SetXY($x , $y + $i);
				if($primero )
				{
					$this->Cell(15 ,5,utf8_decode($col),1,0,'C');
					$primero  = false;
				}
				else $this->Cell(25,5,utf8_decode($col),1,0,'C');
			}
			$i= $i + 5 ;  // incremento el valor de la columna
			$this->SetXY($x , $y + $i);     
		  //$this->Ln();
		}
	}
	function tabla_datos_personales($data, $x = 0, $y = 0) {
		$this->SetXY($x , $y);
		$i = 6;
		$contador=0;
		$this->SetXY($x , $y + $i);
		foreach($data as $row){
			foreach($row as $col){
				//$this->SetXY($x , $y + $i);
				$ancho_de_celda = 37;
				$contador++;
				if(($col === "Nombre") || ($col === "CUIT"))
					$ancho_de_celda = 18;
				elseif(($col === "Direccion"))
						$ancho_de_celda = 18 ;
				elseif ($col === "N°")
					$ancho_de_celda = 5;
				  elseif ($contador == 43)
					$ancho_de_celda = 12;

				elseif ($contador == 4)
					$ancho_de_celda = 25;
				if($contador == 99 )//direccion
					//$this->Cell(72 ,5,utf8_decode($col),1,0,'C');
					$ancho_de_celda = 72;
				if($contador == 95 ) //nombre
					$this->Cell(55 ,5,utf8_decode($col),1,0,'C');
			 
				else $this->Cell($ancho_de_celda ,5,utf8_decode($col),1,0,'C');
				if(($col === "Direccion"))// voy a poner el la direccion con una medida especial xq es muy larga
				{
					$contador = 98 ; 
				}

				 if(($col === "Nombre"))// voy a poner el la direccion con una medida especial xq es muy larga
				{
					$contador = 94 ; 
				}
				  if(($col ===  "N°"))// voy a poner el la direccion con una medida especial xq es muy larga
				{
					$contador = 42 ; 
				}

			}
			$i= $i + 5 ;  // incremento el valor de la columna
			$this->SetXY($x , $y + $i);     
		}
	}
	function tabla_datos_personales_linea($data, $x = 0, $y = 0) {
		$this->SetXY($x , $y);
		$i = 6;
		$this->SetXY($x , $y + $i);
		$contador= 0;
		foreach($data as $row){
			foreach($row as $col){
				$ancho_de_celda = 42;
				$contador++;
				if($col === "Socio")
					$ancho_de_celda = 12;
				elseif ($col === "N°")
					$ancho_de_celda = 10;
				elseif ($contador == 4)
					$ancho_de_celda = 26;
				$this->Cell($ancho_de_celda ,5,utf8_decode($col),1,0,'C');
			}
			$i= $i + 5 ;  // incremento el valor de la columna
			$this->SetXY($x , $y + $i);     
		}
	}
	function tabla_detalle_consume($header, $data, $x = 0, $y = 0) {

		$this->SetXY($x , $y);
		
		// Header
		foreach($header as $col){
			if($col == "Estado Medidor")
				$ancho_de_celda = 44;
			else $ancho_de_celda = 45;
			$this->Cell($ancho_de_celda,6,utf8_decode($col),1,0,'C');
		}
		$this->Ln();
		
		// Data
		$contador =0;
		$i = 6;
		$this->SetXY($x , $y + $i);
		foreach($data as $row){
			foreach($row as $col){
				//$this->SetXY($x , $y + $i);
				$ancho_de_celda = 15;
				$contador++;
				if(($col === "Anterior") || ($col === "Actual"))
					$ancho_de_celda = 22;
				elseif(($col === "Total") || ($col === "Básico") || ($col === "Excedente"))
					$ancho_de_celda = 15;
				elseif(($contador == 6) || ($contador == 7))
					$ancho_de_celda = 22;
				$this->Cell($ancho_de_celda ,5,utf8_decode($col),1,0,'C');
				
			}
			$i= $i + 5 ;  // incremento el valor de la columna
			$this->SetXY($x , $y + $i);     
		  //$this->Ln();
		}
	}
	function tabla_de_costos($data, $x = 0, $y = 0) {
		$this->SetXY($x , $y);
		// Data
		$i = 5;
		$contador=0;
		$this->SetXY($x , $y + $i);
		foreach($data as $row){
			foreach($row as $col){
				//$this->SetXY($x , $y + $i);
				$ancho_de_celda = 22;
				$contador++;
				 if($contador % 2 == 0 )
					$alineado = 'R';
				else $alineado = 'L';
				//     $ancho_de_celda = 18;
				// elseif ($col === "N°")
				//     $ancho_de_celda = 10;
				// elseif ($contador == 4)
				//     $ancho_de_celda = 25;
				if($col == "Medidor")
					$this->Cell($ancho_de_celda ,5,utf8_decode($col),1,0,$alineado);
				else $this->Cell($ancho_de_celda ,5,utf8_decode($col),1,0,$alineado);
			}
			$i= $i + 5 ;  // incremento el valor de la columna
			$this->SetXY($x , $y + $i);     
		  //$this->Ln();
		}
	}
	function tabla_vencimientos_2($header, $data, $x = 0, $y = 0) {
		$this->SetXY($x , $y);
		// Header
		foreach($header as $col)
			$this->Cell(42 ,6,utf8_decode($col),1,0,'C');
		$this->Ln();
		// Data
		$i = 6;
		$contador=0;
		$this->SetXY($x , $y + $i);
		foreach($data as $row){
			foreach($row as $col){
				//$this->SetXY($x , $y + $i);
				$contador ++;
				$ancho_de_celda = 13;
				if(($contador == 3) || ($contador == 6))
					$ancho_de_celda = 16;
				$this->Cell($ancho_de_celda ,5,utf8_decode($col),1,0,'C');
			}
			$i= $i + 5 ;  // incremento el valor de la columna
			$this->SetXY($x , $y + $i);     
		  //$this->Ln();
		}
	}
	function tabla_nombre_cliente_orden_trabajo($data, $x = 0, $y = 0) {
		$this->SetXY($x , $y);
		$i = 6;
		$this->SetXY($x , $y + $i);
		$contador = 1;
		foreach($data as $row){
			foreach($row as $col)
				if($contador == 1)
					$this->Cell(355 ,5,utf8_decode($col),0,0,'C');
				else $this->Cell(40 ,5,utf8_decode($col),0,0,'C');
		$i= $i + 5 ;
		$this->SetXY($x , $y + $i);     
			}
	}
	function arreglar_numero($numero)
	{
		// if( intval($numero) >= 1000)
		// 	$numero = str_replace(".", "",$numero);
		$inicio_coma = strpos($numero, '.');
		//var_dump($inicio_coma);die();
		// if ( is_float($numero)  &&  ($inicio_coma != false))
		// 	$numero .= "00";
		// else $numero .= ",00";
		if( is_numeric( $inicio_coma) &&  ($inicio_coma >= 1) &&  ($inicio_coma < strlen($numero) ) )
			$numero =  substr($numero, 0,  ($inicio_coma+3)); 
		else $numero .= ".00";
		//return str_replace(".", ",",$numero);
		return $numero;
	}
	function calcular_valores_a_facturar($key,$datos, $key_boleta)
	{
		
		$data["monto_cuota_medidor"] = $key->PlanMedidor_MontoCuota ;
		if($data["monto_cuota_medidor"] == null)
			$data["monto_cuota_medidor"] = 0;
		$data["monto_cuota_plan_pago"] = $key->PlanPago_MontoCuota ;
		if($data["monto_cuota_plan_pago"] == null)
			$data["monto_cuota_plan_pago"] = 0;
		$precio_riego = 0;
		if($key->Conexion_Latitud == "1")
			$precio_riego = floatval ($datos[17]->Configuracion_Valor);

		if($key->Conexion_Categoria == "Familiar")
			$tarifa_social = $datos[4]->Configuracion_Valor ;
		else $tarifa_social = $datos[7]->Configuracion_Valor;

		$data["subtotal_sin_bonificacion"] = floatval($key->Conexion_Deuda) + 
			floatval ($tarifa_social)  + 
			floatval ($key->Medicion_Importe)  +   
			floatval ($datos[2]->Configuracion_Valor) +
			floatval ($data["monto_cuota_medidor"])  +
			floatval ($data["monto_cuota_plan_pago"]) +
			$precio_riego;

		


		if( $key->Conexion_Deuda == 0)//aplico descuento en el subtotal
			$data["subtotal_con_bonificacion"] = floatval($data["subtotal_sin_bonificacion"]) - ( (floatval ($key->Medicion_Importe) + floatval($tarifa_social)) * floatval(0.05) ) ;//con bonificacion
		else $data["subtotal_con_bonificacion"] = floatval($data["subtotal_sin_bonificacion"]); // sin bonificacion
		// if($key->Conexion_Id == 253)
		// {
		// 	var_dump( floatval ($key->Medicion_Excedente)  , floatval($tarifa_social),  ( (floatval ($key->Medicion_Excedente) + floatval($tarifa_social)) * floatval(0.05) ));die();
		// }
		

		// if($key->Conexion_Deuda == 0)//aplico descuento en el subtotal
		// 	$data["subtotal_con_bonificacion"]= floatval($data["subtotal_sin_bonificacion"]) * floatval(0.9525) ;
		// else $data["subtotal_con_bonificacion"] = $data["subtotal_sin_bonificacion"];
		$data["total"] = floatval( $data["subtotal_con_bonificacion"]) - floatval($key->Conexion_Acuenta);
		/*
		AL TOTAL NO LE RESTO LA BONIFICACION; XQ ESTA AFECTA DIRECTAMENTE A LA DEUDA
		ASIQUE CUANDO CREE UNA BONIFICACION NUEVA, VOY A DESCONTAR LA DEUDA DE LA CONEXION DIRECTAMENTE
		ASIQ AHORA ESTARIA CALCULANDO EL TOTAL CON EL DESCUENTO YA HECHO EN LA DEUDA, Y SIMPLEMENTE LO ESCRIBO EN LA BOLETA*/
		// if(isset( $key->Bonificacion_Monto) && (is_numeric( $key->Bonificacion_Monto)) &&  $key->Bonificacion_Monto> 0)
		//       $data["total"] = floatval($data["total"]) - floatval( $key_boleta->Bonificacion_Monto);
		$data["total"] = $this->arreglar_numero($data["total"]);
		$data["subtotal_con_bonificacion"] = $this->arreglar_numero($data["subtotal_con_bonificacion"]);
		$data["subtotal_sin_bonificacion"] = $this->arreglar_numero($data["subtotal_sin_bonificacion"]);  
		$data["monto_cuota_medidor"] = $this->arreglar_numero($data["monto_cuota_medidor"]);  
		$data["monto_cuota_plan_pago"] = $this->arreglar_numero($data["monto_cuota_plan_pago"]);  
		return $data;
	}
	public function probando_tabla($datos)
	{
		$key = $datos["resultado"];
		$key_boleta = $datos["boleta"];
		$pdf = new eFPDF('P');
		$code  = $this->calcular_codigo_barra_agua($key->Conexion_Id,$key_boleta->id);
		$this->poner_codigo_barra($code,null,null,$pdf);
		$valores = $this->calcular_valores_a_facturar($key,$datos["configuracion"], $key_boleta);

		//var_dump($valores["total"]);die();
		//TABLA 1
		if($key->Conexion_Categoria == 1) 
		   $tipo_conexion =  "Familiar";
		else $tipo_conexion =  "Comercial";
		$header = array('Mes', 'Año', 'Categoria', 'Conexión', 'Iva', 'Factura');
		$data = [];
		//$data[0] = array("10", "2017", "Familiar", '56', 'Excento', '21220101');
		$data[0] = array(date("m"), date("Y"), $tipo_conexion, $key->Conexion_Id, 'Excento', $key_boleta->id);
		$pdf->tabla_datos_facturas($header, $data, 9, 46);
		//TABLA 2
		$header = array('Mes', 'Año', 'Categoria', 'Conexión', 'Factura');
		$data1 = [];
		$data1[0] = array(date("m"), date("Y"), $tipo_conexion, $key->Conexion_Id, $key_boleta->id);
		$pdf->tabla_datos_factura_1($header, $data1 , 110,39  );
		$pdf->tabla_datos_factura_1($header, $data1 , 110,100  );
		$pdf->Ln(5);
		//TABLA 3
		$fecha = date('Y-m-d');
		$fecha [strlen($fecha)-1] = "1";
		$fecha [strlen($fecha)-2] = "0";
		$nuevafecha = strtotime ( '+15 day' , strtotime ( $fecha ) ) ;
		$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
		$fecha = date('Y-m-d');
		$fecha [strlen($fecha)-1] = "1";
		$fecha [strlen($fecha)-2] = "0";
		$nuevafecha_dos = strtotime ( '+20 day' , strtotime ( $fecha ) ) ;
		$nuevafecha_dos = date ( 'd/m/Y' , $nuevafecha_dos );
		 $fecha = date('Y-m-d');
		$fecha [strlen($fecha)-1] = "1";
		$fecha [strlen($fecha)-2] = "0";
		$nuevafecha_tres = strtotime ( '+21 day' , strtotime ( $fecha ) ) ;
		$nuevafecha_tres = date ( 'd/m/Y' , $nuevafecha_tres );
		$fecha = date('Y-m-d');
		$fecha [strlen($fecha)-1] = "1";
		$fecha [strlen($fecha)-2] = "0";
		$nuevafecha_cuatro = strtotime ( '+28 day' , strtotime ( $fecha ) ) ;
		$nuevafecha_cuatro = date ( 'd/m/Y' , $nuevafecha_cuatro );

		$header = array('1° Vencimiento', '2° Vencimiento', 'Total');
		$data2 = [];
		//var_dump(    floatval( $valores["total"] ) *  0.1  *   floatval($valores["total"])   ) ;die();
		$data2[0] = array($nuevafecha,$nuevafecha_dos, "$ ".$this->arreglar_numero( floatval( $valores["total"] ) *  0.1  *   floatval($valores["total"])  )  );
		$data2[1] = array($nuevafecha_tres, $nuevafecha_cuatro, "$ ".$this->arreglar_numero(     floatval( $valores["total"] ) *  0.2  *   floatval($valores["total"])  )   );
		$pdf->tabla_vencimientos_1($header, $data2 , 110,54  );
		$pdf->tabla_vencimientos_1($header, $data2 , 110,123  );
		$pdf->Ln(5);
		//TABLA 4
		$data2 = [];
		$data2[0] = array("Nombre",  $key->Cli_RazonSocial, "N°" , $key->Cli_Id);
		$data2[1] = array("Direccion", $key->Cli_DomicilioSuministro);
		$data2[2] = array("CUIT", $key->Cli_Cuit);
		$pdf->tabla_datos_personales($data2 , 9,54  );
		$data_linea_personal[0] = array("Socio", $key->Cli_RazonSocial, "N°" , $key->Cli_Id);
		$pdf->tabla_datos_personales_linea($data_linea_personal , 110,109  );
		$pdf->Ln(5);
		//TABLA 4
		$data4 = [];
		$header = array("Estado Medidor", "Consume en m3");
		$data4[1] = array("Anterior", "Actual", "Total", "Básico", "Excedente");
		$data4[2] = array($key->Medicion_Anterior, $key->Medicion_Actual, $key->Medicion_Basico+$key->Medicion_Excedente,$key->Medicion_Basico, $key->Medicion_Excedente);
		$pdf->tabla_detalle_consume($header, $data4 , 9,77  );
		$pdf->Ln(5);
		//TABLA 5
		$data5[0] = array("Deuda Anterior", "$".$this->arreglar_numero($key->Conexion_Deuda));
		$data5[1] = array("Tarifa básica", "$".$this->arreglar_numero($datos["configuracion"][4]->Configuracion_Valor));
		$data5[2] = array("Excedente", "$".$this->arreglar_numero(($key->Medicion_Excedente) * floatval ($datos["configuracion"][3]->Configuracion_Valor)));
		$data5[3] = array("Couta Social", "$". $this->arreglar_numero($datos["configuracion"][2]->Configuracion_Valor));
		if (($key->PlanMedidor_MontoCuota == 0 ) ||  ($key->PlanMedidor_MontoCuota == null ) )
		  $aux_medidor_string = "$ 0,00";
		else $aux_medidor_string = "(".$key->PlanMedidor_CoutaActual."/".$key->PlanMedidor_Coutas.")       $ ".$this->arreglar_numero($key->PlanMedidor_MontoCuota);
		$data5[4] = array("Medidor", $aux_medidor_string );
		  if (($key->PlanPago_MontoCuota == 0 ) ||  ($key->PlanPago_MontoCuota == null ) )
		  $aux_planpago = "$ 0,00";
		else $aux_planpago = "(".$key->PlanPago_CoutaActual."/".$key->PlanPago_Coutas.")       $ ".$this->arreglar_numero($key->PlanPago_MontoCuota);
		$data5[5] = array("Plan de Pago", $aux_planpago);
		if(isset($key_boleta->Bonificacion_Monto) && (is_numeric($key_boleta->Bonificacion_Monto)) && $key_boleta->Bonificacion_Monto> 0)
			$aux_boni = "$".$this->arreglar_numero($key_boleta->Bonificacion_Monto);
		else $aux_boni = "$ 0,00";
		$data5[6] = array("Bonificacion",$aux_boni);
		$data5[7] = array("Subtotal","$".$valores["subtotal_sin_bonificacion"]);
		 if(isset( $key->Conexion_Acuenta) && (is_numeric( $key->Conexion_Acuenta)) &&  $key->Conexion_Acuenta> 0)
			$aux_apagar = "$".$this->arreglar_numero($key_boleta->Bonificacion_Monto);
		else $aux_apagar = "$ 0,00";
		$data5[8] = array("Pagos a cuenta", $aux_apagar);
		$data5[9] = array("Bonificacion", "$ 80.00");
		$data5[10] = array("Total a Pagar", "$".$valores['total']);
		$pdf->tabla_de_costos($data5, 9, 89);
		//
		$header = array( 'Vencimiento');
		$data2 = [];
		$data2[0] = array($nuevafecha,$nuevafecha_dos, "$".$valores["total"]);
		$data2[1] = array($nuevafecha_tres, $nuevafecha_cuatro, "$".$valores["total"]);
		$pdf->tabla_vencimientos_2($header, $data2 ,56, 99  );
		$pdf->Ln(5);
		//
		$pdf->Output();
	}
	public function probando_tabla_por_lote($datos)
	{
		//var_dump($datos);die();
		$inicio_hoja_nueva = true;
		$pdf = new eFPDF('P');
		$vueltas = 0;
		foreach ($datos["resultado"] as $key) 
		{
			if($inicio_hoja_nueva)
			{
				$coordinada_x_1 =9;
				$coordinada_y_1 = 38;
				$coordinada_x_2 = 110;
				$coordinada_y_2 = 39;
				$coordinada_x_3 = 110 ;
				$coordinada_y_3 = 100;
				$coordinada_x_4 = 110;
				$coordinada_y_4 = 54;
				$coordinada_x_5 = 110 ;
				$coordinada_y_5 = 123;
				$coordinada_x_6 = 110;
				$coordinada_y_6 = 109;
				$coordinada_x_7 = 9;
				$coordinada_y_7 = 70;
				$coordinada_x_8 = 56;
				$coordinada_y_8 = 99;
				$coordinada_x_9 = 9 ;
				$coordinada_y_9 = 48 ;
				$coordinada_x_10 = 9 ;
				$coordinada_y_10 = 83 ;
				$coordinada_x_11= null ;
				$coordinada_y_11 = null ;
				$aux_inicio = 0;
			}
			else 
			{
				$shifteo = 147;
				$coordinada_x_1 =9 ;
				$coordinada_y_1 = 46 + $shifteo;
				$coordinada_x_2 = 110 ;
				$coordinada_y_2 = 39 + $shifteo;
				$coordinada_x_3 = 110 ;
				$coordinada_y_3 = 100 + $shifteo;
				$coordinada_x_4 = 110 ;
				$coordinada_y_4 = 54 + $shifteo;
				$coordinada_x_5 = 110 ;
				$coordinada_y_5 = 123 + $shifteo;
				$coordinada_x_6 = 110 ;
				$coordinada_y_6 = 109 + $shifteo;
				$coordinada_x_7 = 9 ;
				$coordinada_y_7 = 77 + $shifteo;
				$coordinada_x_8 = 56 ;
				$coordinada_y_8 = 99 + $shifteo;
				$coordinada_x_9 = 9 ;
				$coordinada_y_9 = 54 + $shifteo;
				$coordinada_x_10 = 9 ;
				$coordinada_y_10 = 89 + $shifteo;
				$coordinada_x_11= 80 ;
				$coordinada_y_11 = 276 ;
				$aux_inicio = 1 ;
			}
				$code  = $this->calcular_codigo_barra_agua($key->Conexion_Id,$key->id);
				$this->poner_codigo_barra($key->id_factura,$coordinada_x_11,$coordinada_y_11,$pdf,$aux_inicio);
				$valores = $this->calcular_valores_a_facturar($key,$datos["configuracion"], $key);
				//var_dump($valores);die();
				//  $vueltas++;
				// if($vueltas >= 2)
				// {
				//      continue;
				// }
				//  var_dump($valores["total"]);
				//  continue;
				// die();
				//TABLA 1
				if(( $key->Conexion_Categoria == 1) || ($key->Conexion_Categoria == "Familiar"))
				   $tipo_conexion =  "Familiar";
				else $tipo_conexion =  "Comercial";
				$header = array('Mes', 'Año', 'Categoria', 'Conexión', 'Iva', 'Factura');
				$data = [];
				//$data[0] = array("10", "2017", "Familiar", '56', 'Excento', '21220101');
				$data[0] = array($key->Factura_Periodo, date("Y"), $tipo_conexion, $key->Conexion_Id, 'Excento', $key->id);
				$pdf->tabla_datos_facturas($header, $data,  $coordinada_x_1,  $coordinada_y_1);
				//TABLA 2
				$header = array('Mes', 'Año', 'Categoria', 'Conexión', 'Factura');
				$data1 = [];
				$data1[0] = array(date("m"), date("Y"), $tipo_conexion, $key->Conexion_Id, $key->id);
				$pdf->tabla_datos_factura_1($header, $data1 , $coordinada_x_2, $coordinada_y_2  );
				$pdf->tabla_datos_factura_1($header, $data1 , $coordinada_x_3 ,  $coordinada_y_3);
				$pdf->Ln(5);
				//TABLA 3
				$fecha = date('Y-m-d');
				$fecha [strlen($fecha)-1] = "1";
				$fecha [strlen($fecha)-2] = "0";
				//$nuevafecha = strtotime ( '-1 month' , strtotime ( $fecha ) ) ;
				$nuevafecha =  strtotime ( $fecha ) ;
				$nuevafecha = date ( 'd/m/y' , $nuevafecha );
				$fecha = date('Y-m-d');
				$fecha [strlen($fecha)-1] = "1";
				$fecha [strlen($fecha)-2] = "0";
				$nuevafecha_dos = strtotime ( $key->Factura_Vencimiento1 ) ;
				$nuevafecha_dos = date ( 'd/m/y' , $nuevafecha_dos );
				 $fecha = date('Y-m-d');
				$fecha [strlen($fecha)-1] = "1";
				$fecha [strlen($fecha)-2] = "0";
				$nuevafecha_tres = strtotime ( '+1 day' , strtotime ( $key->Factura_Vencimiento1 ) ) ;
				$nuevafecha_tres = date ( 'd/m/y' , $nuevafecha_tres );
				$fecha = date('Y-m-d');
				$fecha [strlen($fecha)-1] = "1";
				$fecha [strlen($fecha)-2] = "0";
				$nuevafecha_cuatro = strtotime ( $key->Factura_Vencimiento2 );
				$nuevafecha_cuatro = date ( 'd/m/y' , $nuevafecha_cuatro );
				$header = array('1° Vencimiento', '2° Vencimiento', 'Total');
				$data2 = [];
				$aux_total = $valores["total"];
				$data2[0] = array($nuevafecha,$nuevafecha_dos, "$ ".$this->arreglar_numero(floatval($aux_total)  ));
				$data2[1] = array($nuevafecha_tres, $nuevafecha_cuatro, "$ ".$this->arreglar_numero(floatval($aux_total) + floatval($aux_total)  * floatval($datos["configuracion"][18]->Configuracion_Valor) ));
				$pdf->tabla_vencimientos_1($header, $data2 ,$coordinada_x_4 ,  $coordinada_y_4);
				$pdf->tabla_vencimientos_1($header, $data2 , $coordinada_x_5 , $coordinada_y_5  );
				$pdf->Ln(5);
				//TABLA 4
				$datapersona2 = [];
				$datapersona2[0] = array("Nombre",  $key->Cli_RazonSocial, "N°" , $key->Cli_Id);
				$datapersona2[1] = array("Direccion", $key->Cli_DomicilioSuministro);
				$datapersona2[2] = array("CUIT", $key->Cli_Cuit);
				$pdf->tabla_datos_personales($datapersona2 , $coordinada_x_9 , $coordinada_y_9  );
				$data_linea_personal[0] = array("Socio", $key->Cli_RazonSocial, "N°" , $key->Cli_Id);
					$pdf->tabla_datos_personales_linea($data_linea_personal , $coordinada_x_6 , $coordinada_y_6  );
				$pdf->Ln(5);
				//TABLA 4
				$data4 = [];
				$header = array("Estado Medidor", "Consume en m3");
				$data4[1] = array("Anterior", "Actual", "Total", "Básico", "Excedente");
				if($key->Conexion_Categoria == "Familiar")
					$basico = $datos["configuracion"][5]->Configuracion_Valor;
				else $basico = $datos["configuracion"][8]->Configuracion_Valor;
				$diferenncia = intval($key->Medicion_Actual)-intval($key->Medicion_Anterior);
				if($diferenncia > 10)
					$exxcedente = $diferenncia-$basico ;
				else 
				{
				//	$diferenncia = 0;
					$exxcedente = 0;
				}
				// if($key->Conexion_Id == 325)
				// 	{var_dump($diferenncia, $exxcedente , intval($key->Medicion_Actual), intval($key->Medicion_Actual),intval($key->Medicion_Basico));die();}
				$data4[2] = array($key->Medicion_Anterior, $key->Medicion_Actual, $diferenncia,$basico, $exxcedente );
				$pdf->tabla_detalle_consume($header, $data4 , $coordinada_x_7 , $coordinada_y_7  );
				$pdf->Ln(5);
				//TABLA 5
				$data5[0] = array("Deuda Anterior", "$".$key->Conexion_Deuda);
				if($key->Conexion_Categoria == "Familiar")
					$data5[1] = array("Tarifa básica", "$".$this->arreglar_numero($datos["configuracion"][4]->Configuracion_Valor));
				else $data5[1] = array("Tarifa básica", "$".$this->arreglar_numero($datos["configuracion"][7]->Configuracion_Valor));
				$data5[2] = array("Excedente", "$".$this->arreglar_numero($key->Medicion_Importe));
				$data5[3] = array("Couta Social", "$". $this->arreglar_numero($datos["configuracion"][2]->Configuracion_Valor));
				if (($key->PlanMedidor_MontoCuota == 0 ) ||  ($key->PlanMedidor_MontoCuota == null ) )
				  $aux_medidor_string = "$ 0,00";
				else $aux_medidor_string = "(".$key->PlanMedidor_CoutaActual."/".$key->PlanMedidor_Coutas.") $ ".$this->arreglar_numero($key->PlanMedidor_MontoCuota);
				$data5[4] = array("Medidor", $aux_medidor_string );
				  if (($key->PlanPago_MontoCuota == 0 ) ||  ($key->PlanPago_MontoCuota == null ) )
				  $aux_planpago = "$ 0,00";
				else $aux_planpago = "(".$key->PlanPago_CoutaActual."/".$key->PlanPago_Coutas.")  $ ".$this->arreglar_numero($key->PlanPago_MontoCuota);
				$data5[5] = array("Plan de Pago", $aux_planpago);
				if(isset($key->Conexion_Latitud) && ($key->Conexion_Latitud== "1" ))
					$aux_boni = "$".$this->arreglar_numero($datos["configuracion"][17]->Configuracion_Valor);
				else $aux_boni = "$ 0,00";
				$data5[6] = array("Riego",$aux_boni);
				$data5[7] = array("Subtotal","$ ". $valores["subtotal_sin_bonificacion"]);
				 if(isset( $key->Conexion_Acuenta) && (is_numeric( $key->Conexion_Acuenta)) &&  $key->Conexion_Acuenta> 0)
					$aux_apagar = "$".$this->arreglar_numero($key->Conexion_Acuenta);
				else $aux_apagar = "$ 0,00";
				$data5[8] = array("Pagos a cuenta", $aux_apagar);
				if($key->Conexion_Categoria == "Familiar")
					$tarifa_social = $datos["configuracion"][4]->Configuracion_Valor;
				else $tarifa_social = $datos["configuracion"][7]->Configuracion_Valor;
				 if($key->Conexion_Deuda == 0)
					$aux_bonificacion =  "$ ".$this->arreglar_numero ( (floatval ($key->Medicion_Importe) + floatval($tarifa_social)) * floatval(0.05) ) ;
				else $aux_bonificacion = "$ 0.00";
				$data5[9] = array("Bonificacion", $aux_bonificacion);
				$data5[10] = array("Total a Pagar", "$".$valores['total']);
				$pdf->tabla_de_costos($data5,  $coordinada_x_10,  $coordinada_y_10);
				//
				$header = array( 'Vencimiento');
				$data2 = [];
				$data2[0] = array($nuevafecha,$nuevafecha_dos, "$".$valores["total"]);
				$data2[1] = array($nuevafecha_tres, $nuevafecha_cuatro, "$".$this->arreglar_numero(floatval($aux_total) + floatval($aux_total)  * floatval(0.015) ));
				$pdf->tabla_vencimientos_2($header, $data2 , $coordinada_x_8, $coordinada_y_8   );
				$pdf->Ln(5);
				if($inicio_hoja_nueva)
					$inicio_hoja_nueva= false;
				else $inicio_hoja_nueva=true;
		}
		$pdf->Output();
	}


	public function creando_orden_trabajo($datos)
	{
		//var_dump($datos["resultado"]);die();
		$inicio_hoja_nueva = true;
		$pdf = new eFPDF('P', 'pt');
		$pdf->SetMargins(25, 20 , 20); 
		$pdf->SetAutoPageBreak(true,15);  
		$vueltas = 0;
		$shifteo = 0;
		$coordinada_x_1 =9 ;
		$coordinada_y_1 = 46 + $shifteo;
		$coordinada_x_2 = 110 ;
		$coordinada_y_2 = 39 + $shifteo;
		$coordinada_x_3 = 110 ;
		$coordinada_y_3 = 100 + $shifteo;
		$coordinada_x_4 = 110 ;
		$coordinada_y_4 = 54 + $shifteo;
		$coordinada_x_5 = 110 ;
		$coordinada_y_5 = 123 + $shifteo;
		$coordinada_x_6 = 110 ;
		$coordinada_y_6 = 109 + $shifteo;
		$coordinada_x_7 = 9 ;
		$coordinada_y_7 = 77 + $shifteo;
		//$pdf->SetAutoPageBreak(true,1); 
		$this->SetX(0);
		$pdf->AddPage();
		$pdf->Image(base_url().'img/orden_de_trabajo.jpg', 0, 0 ,600, 850);
		$pdf->SetFont('Arial','B',15);
		$pdf->SetTextColor(0, 0, 0);
		//LINEA 1
		$pdf->Ln(33);
		$uno_uno = "                     ";
		$pdf->Cell(0,10,$datos["resultado"]->OrdenTrabajo_Id."                         ",0, 2 ,'R',false);
		//LINEA 2
		$pdf->Ln(16);
		$dos_uno = "       ";
		//
		$dos_dos = " / ";
		//
		$dos_tres = " / ";
		$pdf->Cell(0,10,date("d").$dos_dos.date("m").$dos_tres.date("Y").$dos_uno,0, 2 ,'R',false);
		//LINEA  3 
		$pdf->Ln(45);
		$estado = "sin empezar";
		switch ($datos["resultado"]->OrdenTrabajo_Estado) {
			case '1':
				$estado = "Sin Empezar";
				break;
			case '2':
				$estado = "Comenzo";
				break;
			case '3':
				$estado = "Suspendida";
				break;
			case '4':
				$estado = "Termino";
				break;
			default:
				$estado = "sin empezar";
				break;
		}
		$inicio_d = new DateTime($datos["resultado"]->OrdenTrabajo_FechaInicio);
		$fin_d = new DateTime($datos["resultado"]->OrdenTrabajo_FechaFin);
		$inicio = $inicio_d->format('Y-m-d');
		$fin = $fin_d->format('Y-m-d');
		$break_1_start = DateTime::createFromFormat('Y-m-d', $inicio);
		$break_1_ends = DateTime::createFromFormat('Y-m-d', $fin);
		$dias = $break_1_start->diff($break_1_ends);
		$pdf->Cell(0,10,"                      Estado: ".utf8_decode($estado).utf8_decode("     -    Duración: ").$dias->format('%d').utf8_decode(" días"),0, 2 ,'L',false);
		
		$pdf->Ln(12);
		$tres_uno = "                            ";
		$pdf->Cell(0,10,$tres_uno.utf8_decode($datos["resultado"]->OrdenTrabajo_Tarea),0, 2 ,'L',false);
		$pdf->Ln(12);
		$data = null;
		$data[0] = array($datos["resultado"]->OrdenTrabajo_Cliente, $datos["resultado"]->OrdenTrabajo_NConexion);

		$pdf->tabla_nombre_cliente_orden_trabajo($data,0, 175 );

		// $pdf->Cell(0,10,$tres_uno.utf8_decode(),0, 2 ,'L',false);
		// $pdf->Ln(12);
		$pdf->Ln(12);
		$pdf->Cell(0,10,$tres_uno.utf8_decode($datos["resultado"]->OrdenTrabajo_Direccion),0, 2 ,'L',false);

		// //TABLA 1
		// $header = array('Mes', 'Año', 'Categoria', 'Conexión', 'Iva', 'Factura');
		// $data = [];
		// //$data[0] = array("10", "2017", "Familiar", '56', 'Excento', '21220101');
		// $data[0] = array(date("m"), date("Y"), $tipo_conexion, $key->Conexion_Id, 'Excento', $key->id);
		// $pdf->tabla_datos_facturas($header, $data,  $coordinada_x_1,  $coordinada_y_1);
		// //TABLA 2
		// $header = array('Mes', 'Año', 'Categoria', 'Conexión', 'Factura');
		// $data1 = [];
		// $data1[0] = array(date("m"), date("Y"), $tipo_conexion, $key->Conexion_Id, $key->id);
		// $pdf->tabla_datos_factura_1($header, $data1 , $coordinada_x_2, $coordinada_y_2  );
		// $pdf->tabla_datos_factura_1($header, $data1 , $coordinada_x_3 ,  $coordinada_y_3);
		// $pdf->Ln(5);
		$pdf->Output();
	}
// public function crear_factura_por_conexion($datos)
//   {
//     $fontSize = 8;
//     $marge    = 3;   // between barcode and hri in pixel
//     $x        = 232;  // barcode center
//     $y        = 375;  // barcode center
//     $height   = 42;   // barcode height in 1D ; module size in 2D
//     $width    = 1;    // barcode height in 1D ; not use in 2D
//     $angle    = 0;   // rotation in degrees
//     $key = $datos["resultado"];
//     $key_boleta = $datos["boleta"];
//     $code  = $this->calcular_codigo_barra_agua($key->Conexion_Id,$key_boleta->id);
//     $type     = 'ean13';
//     $black    = '000000'; // color in hexa
//     $pdf = new eFPDF('P', 'pt');
//     $pdf->SetMargins(25, 30 , 20); 
//     $pdf->SetAutoPageBreak(true,15);  
//     $band_impar = 0;
//     $this->SetX(0);
//     $pdf->AddPage();
//     $pdf->Image(base_url().'img/boleta_villa_elisa_v4.jpg', 0, 0 ,595, 418); 
//     $x        = 232;  // barcode center
//     $y        = 375;  // barcode center
//     $data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
//     $pdf->SetFont('Arial','B',$fontSize);
//     $pdf->SetTextColor(0, 0, 0);
//     $len = $pdf->GetStringWidth($data['hri']);
//     Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
//     $pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
//     $pdf->Ln(100);   
//     $monto_cuota_medidor = $key->PlanMedidor_MontoCuota ;
//     if($monto_cuota_medidor == null)
//         $monto_cuota_medidor = 0;
//     $monto_cuota_plan_pago = $key->PlanPago_MontoCuota ;
//     if($monto_cuota_plan_pago == null)
//         $monto_cuota_plan_pago = 0;
//     $subtotal_sin_bonificacion = $key->Conexion_Deuda + 
//                 $datos["configuracion"][4]->Configuracion_Valor  + 
//                 floatval ($key->Medicion_Excedente) * floatval ($datos["configuracion"][3]->Configuracion_Valor) +   
//                 floatval ($datos["configuracion"][2]->Configuracion_Valor) +
//                 $monto_cuota_medidor  +
//                 $monto_cuota_plan_pago ;
//     if(isset($key_boleta->Bonificacion_Monto) )
//         $subtotal_con_bonificacion = $subtotal_sin_bonificacion * floatval("0.5");
//     else $subtotal_con_bonificacion = $subtotal_sin_bonificacion;
//     $total = $subtotal_con_bonificacion + $key->Conexion_Acuenta;
//     if(isset($key_boleta->Bonificacion_Monto) )
//         $total -=$key_boleta->Bonificacion_Monto;
//    // var_dump($total);die();
//    $uno_uno =  "                                                                         ".date("m")."               ";
//    $uno_dos =  date("Y")."           ";
//    if($key->Conexion_Categoria == 1) 
//        $uno_tres =  "Familiar            ";
//    else $uno_tres =  "Comercial            ";
//    $aux_conexion_id = null;
//    $aux_conexion_id .= $key->Conexion_Id;
//    for ($i= (4-sizeof($aux_conexion_id)); $i >= 0; $i--) { 
//        $aux_conexion_id ="0".$aux_conexion_id;
//    }
//    $uno_cuatro = $aux_conexion_id."          ";
//    $uno_cinco = "$key_boleta->id              ";
//    $pdf->Cell(0,10,$uno_uno.$uno_dos.$uno_tres.$uno_cuatro.$uno_cinco,0, 2 ,'R',false);
//    $pdf->Ln(7);
//     //LINEA DOS
//     $dos_uno = "    ".date("m");
//     $dos_dos = "      ".date("Y");
//      if($key->Conexion_Categoria == 1) 
//         $uno_tres =  "      Familiar";
//     else $uno_tres =  "      Comercial";
//     $aux_conexion_id = null;
//     $aux_conexion_id .= $key->Conexion_Id;
//     for ($i= (4-sizeof($aux_conexion_id)); $i >= 0; $i--) { 
//         $aux_conexion_id ="0".$aux_conexion_id;
//     }
//     $dos_cuatro= "            ".$aux_conexion_id;
//     $dos_cinco = "           Excento";
//     $dos_seis = "          ".$key_boleta->id;
//     $pdf->Cell(0,10,$dos_uno.$dos_dos.$uno_tres.$dos_cuatro.$dos_cinco.$dos_seis,0, 2 ,'L',false);
//     $pdf->Ln(10);
//     //LINEA TRES
//     $tres_uno="                                      ";
//     //
//     $aux_razon_socal = null;
//     $aux_razon_socal .= $key->Cli_RazonSocial;
//     $aux_tamanio =  '                                                    ';
//     for ($i= (strlen($aux_tamanio)-strlen($key->Cli_RazonSocial)); $i >= 0; $i--) { 
//         $aux_razon_socal =$aux_razon_socal." ";
//     }
//     //$tres_dos =  "Hombre oso                                          ";
//     //
//     $aux_num_cli = null;
//     $aux_num_cli .= $key->Cli_Id;
//     $aux_tamanio =  "                       ";
//     for ($i= (strlen($aux_tamanio)-strlen($key->Cli_Id)); $i >= 0; $i--) { 
//         $aux_num_cli =$aux_num_cli." ";
//     }
//     //$tres_tres = "12                     ";
//     //
//     $aux_vto_1_inicio = null;
//     $fecha = date('Y-m-d');
//     $fecha [strlen($fecha)-1] = "1";
//     $fecha [strlen($fecha)-2] = "0";
//     $nuevafecha = strtotime ( '+15 day' , strtotime ( $fecha ) ) ;
//     $nuevafecha = date ( 'd/m/Y' , $nuevafecha );
//     $aux_vto_1_inicio .= $nuevafecha;
//     $aux_tamanio =  "                             ";
//     for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//         $aux_vto_1_inicio =$aux_vto_1_inicio." ";
//     }
//     //$tres_cuatro ="21/09/2017                      ";
//     //
//     $aux_vto_1_fin = null;
//     $fecha = date('Y-m-d');
//     $fecha [strlen($fecha)-1] = "1";
//     $fecha [strlen($fecha)-2] = "0";
//     $nuevafecha = strtotime ( '+20 day' , strtotime ( $fecha ) ) ;
//     $nuevafecha = date ( 'd/m/Y' , $nuevafecha );
//     $aux_vto_1_fin .= $nuevafecha;
//     $aux_tamanio =  "                             ";
//     for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//         $aux_vto_1_fin =$aux_vto_1_fin." ";
//     }
//     //$tres_cinco= "21/09/2017                        ";
//     //
//     $aux_vto_1_monto = null;
//     $aux_vto_1_monto .= $total;
//     $aux_tamanio = "150    ";
//     for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//         $aux_vto_1_monto =$aux_vto_1_monto." ";
//     }
//     //$tres_seis ="150";
//     $pdf->Cell(0,10,$tres_uno.utf8_decode($aux_razon_socal).$aux_num_cli.$aux_vto_1_inicio.$aux_vto_1_fin."$".$aux_vto_1_monto,0, 2 ,'L',false);
//     $pdf->Ln(2);
//     //LINEA 4
//     //Cli_DomicilioSuministro
//     $cuatro_uno = "                                      ";
//     //
//     $aux_domicilio = null;
//     $aux_domicilio .= $key->Cli_DomicilioSuministro;
//     if(sizeof($key->Cli_DomicilioSuministro)>20)
//         $aux_tamanio = "                                                                                  ";
//     else $aux_tamanio = "                                                                  ";
//     for ($i= (strlen($aux_tamanio)-strlen($key->Cli_DomicilioSuministro)); $i >= 0; $i--) { 
//         $aux_domicilio =$aux_domicilio." ";
//     }
//      //
//     $aux_vto_2_inicio = null;
//     $fecha = date('Y-m-d');
//     $fecha [strlen($fecha)-1] = "1";
//     $fecha [strlen($fecha)-2] = "0";
//     $nuevafecha = strtotime ( '+21 day' , strtotime ( $fecha ) ) ;
//     $nuevafecha = date ( 'd/m/Y' , $nuevafecha );
//     $aux_vto_2_inicio .= $nuevafecha;
//     $aux_tamanio = "21/09/2017                  ";
//     for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//         $aux_vto_2_inicio =$aux_vto_2_inicio." ";
//     }
//      //
//     $aux_vto_2_fin = null;
//     $fecha = date('Y-m-d');
//     $fecha [strlen($fecha)-1] = "1";
//     $fecha [strlen($fecha)-2] = "0";
//     $nuevafecha = strtotime ( '+27 day' , strtotime ( $fecha ) ) ;
//     $nuevafecha = date ( 'd/m/Y' , $nuevafecha );
//     $aux_vto_2_fin .= $nuevafecha;
//     $aux_tamanio = "21/09/2017                    ";
//     for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//         $aux_vto_2_fin =$aux_vto_2_fin." ";
//     }
//     //
//     $aux_vto_2_monto = null;
//     $aux_vto_2_monto .= $total;
//     $aux_tamanio = "150    ";
//     for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//         $aux_vto_2_monto =$aux_vto_2_monto." ";
//     }
//     //$tres_seis ="150";
//     $pdf->Cell(0,10,$cuatro_uno.$aux_domicilio.$aux_vto_2_inicio.$aux_vto_2_fin."$".$aux_vto_2_monto,0, 2 ,'L',false);
//     $pdf->Ln(2);    
//     //LINEA 5
//     //Cli_Cuit
//     $cinco_uno = "                                      ";
//     $pdf->Cell(0,10,$cinco_uno.$key->Cli_Cuit,0, 2 ,'L',false);
//     $pdf->Ln(37);
//     //LINEA 6
//     $seis_uno ="     ";
//     //
//     $aux_medicion_anterior = null;
//     $aux_medicion_anterior .= $key->Medicion_Anterior;
//     $aux_tamanio = "120                ";
//     for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Anterior)); $i >= 0; $i--) { 
//         $aux_medicion_anterior =$aux_medicion_anterior." ";
//     }
//     //
//     $aux_medicion_actual = null;
//     $aux_medicion_actual .= $key->Medicion_Actual;
//     $aux_tamanio = "13                ";
//     for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Actual)); $i >= 0; $i--) { 
//         $aux_medicion_actual =$aux_medicion_actual." ";
//     }
//     //
//     $aux_medicion_total = null;
//     $aux_medicion_total .= $key->Medicion_Basico+$key->Medicion_Excedente;
//     $aux_tamanio = "133                ";
//     for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Basico+$key->Medicion_Excedente)); $i >= 0; $i--) { 
//         $aux_medicion_total =$aux_medicion_total." ";
//     }
//     //
//     $aux_medicion_basico = null;
//     $aux_medicion_basico .= $key->Medicion_Basico;
//     $aux_tamanio = "10                    ";
//     for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Basico)); $i >= 0; $i--) { 
//         $aux_medicion_basico =$aux_medicion_basico." ";
//     }
//     $pdf->Cell(0,10,$seis_uno.$aux_medicion_anterior.$aux_medicion_actual.$aux_medicion_total.$aux_medicion_basico.$key->Medicion_Excedente,0, 2 ,'L',false);
//     $pdf->Ln(14);
//         //LINEA 7
//         $siete_uno = "                                    ";
//         $aux_medicion_basico = null;
//         $aux_medicion_basico .= $key->Medicion_Basico;
//         $aux_tamanio = "10                      ";
//         for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Basico)); $i >= 0; $i--) { 
//             $aux_medicion_basico =$aux_medicion_basico." ";
//         }
//         $pdf->Cell(0,10,$siete_uno."  $".$key->Conexion_Deuda,0, 2 ,'L',false);
//         $pdf->Ln(2);
//         //LINEA 8
//         $ocho_uno ="                                      ";
//         $pdf->Cell(0,10,$ocho_uno."$".$datos["configuracion"][4]->Configuracion_Valor,0, 2 ,'L',false);
//         $pdf->Ln(3);
//         //LINEA 9
//         $nueve_uno = "                                      $";
//         //$
//         $aux_excedente_monto = null;
//         $aux_excedente_monto .= floatval ($key->Medicion_Excedente) * floatval ($datos["configuracion"][3]->Configuracion_Valor);
//         $aux_tamanio = "200.10                                       ";
//         for ($i= (strlen($aux_tamanio)-strlen(floatval ($key->Medicion_Excedente) * floatval ($datos["configuracion"][3]->Configuracion_Valor))); $i >= 0; $i--) { 
//             $aux_excedente_monto =$aux_excedente_monto." ";
//         }
//         $pdf->Cell(0,10,$nueve_uno.$aux_excedente_monto.$aux_vto_1_inicio,0, 2 ,'L',false);
//         $pdf->Ln(3);
//         //LINEA 10
//         $diez_uno = "                                      $";
//         //$
//         $aux_cuota_social_monto = null;
//         $aux_cuota_social_monto .= floatval ($datos["configuracion"][2]->Configuracion_Valor);
//         $aux_tamanio = "125.01                                         ";
//         for ($i= (strlen($aux_tamanio)-strlen(floatval ($datos["configuracion"][2]->Configuracion_Valor))); $i >= 0; $i--) { 
//             $aux_cuota_social_monto =$aux_cuota_social_monto." ";
//         }
//          //$
//         $aux_fecha_vto_2 = null;
//         $fecha = date('Y-m-d');
//         $fecha [strlen($fecha)-1] = "1";
//         $fecha [strlen($fecha)-2] = "0";
//         $nuevafecha = strtotime ( '+21 day' , strtotime ( $fecha ) ) ;
//         $nuevafecha = date ( 'd/m/Y' , $nuevafecha );

//         $aux_fecha_vto_2 .=  $nuevafecha;
//         $aux_tamanio = "29/09/2017                     ";
//         for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//             $aux_fecha_vto_2 =$aux_fecha_vto_2." ";
//         }
//         $pdf->Cell(0,10,$diez_uno.$aux_cuota_social_monto.$aux_fecha_vto_2.$dos_uno."            ".$dos_dos."      ".$uno_tres.$dos_cuatro."  ".$dos_seis,0, 2 ,'L',false);
//         $pdf->Ln(3);
//         //LINEA 11
//         $once_uno = "                             (";
//         //
//         $once_dos = $key->PlanMedidor_CoutaActual."/".$key->PlanMedidor_Coutas.")  $";
//         $pdf->Cell(0,10,$once_uno.$once_dos.$key->PlanMedidor_MontoCuota,0, 2 ,'L',false);
//         $pdf->Ln(3);
//         //LINEA 12
//         $doce_uno = "                                (";
//         //
//         $doce_dos = $key->PlanPago_CoutaActual."/".$key->PlanPago_Coutas.")  $";
//         //
//         $aux_plan_pago_monto = null;
//         $aux_plan_pago_monto .= $key->PlanPago_MontoCuota;
//         if($key->PlanPago_CoutaActual == null)
//             $aux_tamanio = "75.0       0                                           ";
//         else $aux_tamanio = "75.00                                           ";
//         for ($i= (strlen($aux_tamanio)-strlen($key->PlanPago_MontoCuota)); $i >= 0; $i--) { 
//             $aux_plan_pago_monto =$aux_plan_pago_monto." ";
//         }
//         //
//         $aux_razon_social_doce = null;
//         $aux_razon_social_doce .= $key->Cli_RazonSocial;
//         $aux_tamanio = "Hombre oso                                                            ";
//         for ($i= (strlen($aux_tamanio)-strlen($key->Cli_RazonSocial)); $i >= 0; $i--) { 
//             $aux_razon_social_doce =$aux_razon_social_doce." ";
//         }
//         $pdf->Cell(0,10,$doce_uno.$doce_dos.$aux_plan_pago_monto.'$2000.00                           '.$aux_razon_social_doce.$key->Cli_Id,0, 2 ,'L',false);
//         $pdf->Ln(3);
//         //LINEA 13
//         $trece_uno = "                                      $";
//         $trece_dos=0;

//         if($key->Conexion_Bonificacion == 0)
//             $trece_dos=0;
//         elseif ($key->Conexion_Bonificacion == 1)
//         {
//             $descuento = floatval("0.5");
//             $trece_dos= $descuento * floatval("500"); // poner el subtotal
//         }
//         $pdf->Cell(0,10,$trece_uno.$trece_dos,0, 2 ,'L',false);
//         $pdf->Ln(4);
//         //LINEA 14
//         $catorce_uno = "                                    $";
//         $catorce_dos ="500.58"; //PONER EL SUBTOTAL
//         $pdf->Cell(0,10,$catorce_uno.$catorce_dos,0, 2 ,'L',false);
//         $pdf->Ln(3);
//         //LINEA 15
//         //Conexion_Acuenta
//         $quince_uno = "                                      $";
//         //
//         $aux_acuenta = null;
//         if($key->Conexion_Acuenta == null)
//             $aux_acuenta .= "0";
//         else $aux_acuenta .= $key->Conexion_Acuenta;
//         $aux_tamanio = "$80.00                                                                                      ";
//         for ($i= (strlen($aux_tamanio)-strlen($key->Conexion_Acuenta)); $i >= 0; $i--) { 
//             $aux_acuenta =$aux_acuenta." ";
//         }
//          //
//         $aux_vto_1_inicio = null;
//         $fecha = date('Y-m-d');
//         $fecha [strlen($fecha)-1] = "1";
//         $fecha [strlen($fecha)-2] = "0";
//         $nuevafecha = strtotime ( '+15 day' , strtotime ( $fecha ) ) ;
//         $nuevafecha = date ( 'd/m/Y' , $nuevafecha );
//         $aux_vto_1_inicio .= $nuevafecha;
//         $aux_tamanio =  "21/09/2017                      ";
//         for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//             $aux_vto_1_inicio =$aux_vto_1_inicio." ";
//         }
//         //$tres_cuatro ="21/09/2017                      ";
//         //
//         $aux_vto_1_fin = null;
//         $fecha = date('Y-m-d');
//         $fecha [strlen($fecha)-1] = "1";
//         $fecha [strlen($fecha)-2] = "0";
//         $nuevafecha = strtotime ( '+20 day' , strtotime ( $fecha ) ) ;
//         $nuevafecha = date ( 'd/m/Y' , $nuevafecha );
//         $aux_vto_1_fin .= $nuevafecha;
//         $aux_tamanio =  "                                   ";
//         for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//             $aux_vto_1_fin =$aux_vto_1_fin." ";
//         }
	   

//         $pdf->Cell(0,10,$quince_uno.$aux_acuenta.$aux_vto_1_inicio.$aux_vto_1_fin.'150',0, 2 ,'L',false);
//         $pdf->Ln(2);

//         //LINEA 16
//         $dieciseis_uno = "                                      ";
//         //
//         $bonificacion = "$200.60                                                                                    ";// PONER BONIFICACION AQUI
//         //
//         $aux_vto_2_inicio = null;
//         $fecha = date('Y-m-d');
//         $fecha [strlen($fecha)-1] = "1";
//         $fecha [strlen($fecha)-2] = "0";
//         $nuevafecha = strtotime ( '+21 day' , strtotime ( $fecha ) ) ;
//         $nuevafecha = date ( 'd/m/Y' , $nuevafecha );
//         $aux_vto_2_inicio .= $nuevafecha;
//         $aux_tamanio = "21/09/2017                      ";
//         for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//             $aux_vto_2_inicio =$aux_vto_2_inicio." ";
//         }
//         //
//         $aux_vto_2_fin = null;
//         $fecha = date('Y-m-d');
//         $fecha [strlen($fecha)-1] = "1";
//         $fecha [strlen($fecha)-2] = "0";
//         $nuevafecha = strtotime ( '+27 day' , strtotime ( $fecha ) ) ;
//         $nuevafecha = date ( 'd/m/Y' , $nuevafecha );
//         $aux_vto_2_fin .= $nuevafecha;
//         $aux_tamanio = "21/09/2017                          ";
//         for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
//             $aux_vto_2_fin =$aux_vto_2_fin." ";
//         }
//         $pdf->Cell(0,10,$dieciseis_uno.$bonificacion.$aux_vto_2_inicio.$aux_vto_2_fin.'150',0, 2 ,'L',false);
//         $pdf->Ln(3);
//         //LINEA 17
//         $diecisiete_uno = "                                    ";

//         $pdf->Cell(0,10,$diecisiete_uno.'$1721.00',0, 2 ,'L',false);//PONER EL TOTAL
//         if($band_impar == 0) 
//         {
//             $pdf->Ln(47);  
//             $band_impar = 1;
//         }
//         else $band_impar = 0;
//     $pdf->Output();
   

//   }

	public function tabla_historial($datos)
	{
		//$pdf = new eFPDF('P', 'pt');
		$pdf = new eFPDF('L', 'pt');
		$pdf->SetMargins(25, 30 , 20); 
		$pdf->SetAutoPageBreak(true,15);  
		$pdf->AddPage();
		// $pdf = new eFPDF('P');
		// $pdf->SetAutoPageBreak(true,1); 
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(0,10,'    Historial de Boletas - Pagos de:'.utf8_decode($datos["resultado"][0]->Cli_RazonSocial),0, 2 ,'L',false);
		//TABLA 1
		
		$header = array('Id Fact', 'Periodo', 'Monto', 'Bonific', 'Deuda', 'Plan Pago', 'Plan Medi' , 'Actual', 'Anterior', 'Exceden' , 'Import');
		$pdf->tabla_datos_historial($header, $datos["resultado"], 9, 46);
		$pdf->Output();
	}

	public function imprimir_morosos($datos)
	{
		//$pdf = new eFPDF('P', 'pt');
		$pdf = new eFPDF('L', 'pt');
		$pdf->SetMargins(25, 30 , 20); 
		$pdf->SetAutoPageBreak(true,15);  
		$pdf->AddPage();
		// $pdf = new eFPDF('P');
		// $pdf->SetAutoPageBreak(true,1); 
		$pdf->SetFont('Arial','B',20);
		$pdf->SetTextColor(75, 100, 210);
		$pdf->Cell(0,20,'    Lista de Morosos  ',0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->SetFont('Courier','B',20);
		$pdf->SetTextColor(20, 239, 16 );
		$pdf->Cell(0,20,' Fecha: '.date("d/m/Y H:i:s"),0, 2 ,'R',false);
		//TABLA 1
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0, 0, 0);
		$header = array('Orden','Id Conexion', 'Nombre', 'Deuda');
		$pdf->tabla_morosos($header, $datos["resultado"], 9, 46);
		$pdf->Output();
	}
	public function imprimir_pp($datos)
	{
		//$pdf = new eFPDF('P', 'pt');
		$pdf = new eFPDF('L', 'pt');
		$pdf->SetMargins(25, 30 , 20); 
		$pdf->SetAutoPageBreak(true,15);  
		$pdf->AddPage();
		// $pdf = new eFPDF('P');
		// $pdf->SetAutoPageBreak(true,1); 
		$pdf->SetFont('Arial','B',20);
		$pdf->SetTextColor(75, 100, 210);
		$pdf->Cell(0,20,'    Lista de Morosos  ',0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->SetFont('Courier','B',20);
		$pdf->SetTextColor(20, 239, 16 );
		$pdf->Cell(0,20,' Fecha: '.date("d/m/Y H:i:s"),0, 2 ,'R',false);
		//TABLA 1
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0, 0, 0);
		$header = array('Orden','Id Conexion', 'Nombre', 'Cuotas', 'Actual', 'Precio');
		$pdf->tabla_pp($header, $datos["resultado"], 9, 46);
		$pdf->Output();
	}



	public function tabla_ordenes_trabajo_terminadas($datos)
	{
		$pdf = new eFPDF('L', 'pt');
		$pdf->SetMargins(25, 30 , 20); 
		$pdf->SetAutoPageBreak(true,15);  
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(0,10,'    Historial de Ordenes de Trabajo Terminadas ',0, 2 ,'L',false);
		//TABLA 1
		
		$header = array('Id Orden', 'Quien', 'Inicio', 'Fin', 'Duracion',  'Materiales' , 'Estado');
		$pdf->tabla_datos_ot_terminadas($header, $datos, 9, 46);
		$pdf->Output();
	}
	public function crear_factura_por_lote_conexiones($datos)
	{
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		$fontSize = 8;
		$marge    = 3;   // between barcode and hri in pixel
		$x        = 232;  // barcode center
		$y        = 375;  // barcode center
		$height   = 42;   // barcode height in 1D ; module size in 2D
		$width    = 1;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		$code     = '183956089415'; // barcode, of course ;)
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		// -------------------------------------------------- //
		//            ALLOCATE FPDF RESSOURCE
		// -------------------------------------------------- //
		$pdf = new eFPDF('P', 'pt');
		#Establecemos los márgenes izquierda, arriba y derecha: 
		$pdf->SetMargins(25, 30 , 20); 
		#Establecemos el margen inferior: 
		$pdf->SetAutoPageBreak(true,15);  
		$band_impar = 0;
		//var_dump($datos);die();
		foreach ($datos["resultado"] as $key) 
		{
			$this->SetX(0);
			if($band_impar == 0)
			{
				$pdf->AddPage();
				$pdf->Image(base_url().'img/boleta_villa_elisa_v4.jpg', 0, 0 ,595, 418); 
				// -------------------------------------------------- //
				//                      BARCODE
				// -------------------------------------------------- //
				$x        = 232;  // barcode center
				$y        = 375;  // |barcode center
				$code     = $this->calcular_codigo_barra_agua($key->Conexion_Id, $key->id ); // barcode, of course ;)
				$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
				$pdf->SetFont('Arial','B',$fontSize);
				$pdf->SetTextColor(0, 0, 0);
				$len = $pdf->GetStringWidth($data['hri']);
				Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
				$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
			}
			else
			{
				$pdf->Image(base_url().'img/boleta_villa_elisa_v4.jpg', 0, 419 ,595, 418); 
				 // -------------------------------------------------- //
				//                      BARCODE
				// -------------------------------------------------- //
				$x        = 232;  // barcode center
				$y        = 793;  // barcode center
				$code     = $this->calcular_codigo_barra_agua($key->Conexion_Id, $key->id ); // barcode, of course ;)
				
				$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
				$pdf->SetFont('Arial','B',$fontSize);
				$pdf->SetTextColor(0, 0, 0);
				$len = $pdf->GetStringWidth($data['hri']);
				Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
				$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
			}

			/*
			CALUCLO DE DATOS DE LA BOLETA
			TOTAL = 
			SUBTOTAL = 
			$key->Conexion_Bonificacion == 1)
			{
				$descuento = floatval("0.5");
				$trece_dos= $descuento * floatval("500"); // poner el subtotal
			*/


			$monto_cuota_medidor = $key->PlanMedidor_MontoCuota ;
			if($monto_cuota_medidor == null)
				$monto_cuota_medidor = 0;
			$monto_cuota_plan_pago = $key->PlanPago_MontoCuota ;
			if($monto_cuota_plan_pago == null)
				$monto_cuota_plan_pago = 0;
			$subtotal_sin_bonificacion = $key->Conexion_Deuda + 
						$datos["configuracion"][4]->Configuracion_Valor  + 
						floatval ($key->Medicion_Excedente) * floatval ($datos["configuracion"][3]->Configuracion_Valor) +   
						floatval ($datos["configuracion"][2]->Configuracion_Valor) +
						$monto_cuota_medidor  +
						$monto_cuota_plan_pago ;
			if ($key->Conexion_Bonificacion == 1)
				$subtotal_con_bonificacion = $subtotal_sin_bonificacion * floatval("0.5");
			else $subtotal_con_bonificacion = $subtotal_sin_bonificacion;

			$total = $subtotal_con_bonificacion + $key->Conexion_Acuenta;
			if(isset($key->Bonificacion_Monto) )
				$total -=$key->Bonificacion_Monto;
			//var_dump($key->PlanPago_MontoCuota);die();
			$pdf->Ln(100);   
			$uno_uno =  "                                                                         ".date("m")."               ";
			$uno_dos =  date("Y")."           ";
			if($key->Conexion_Categoria == 1) 
				$uno_tres =  "Familiar            ";
			else $uno_tres =  "Comercial            ";
			$aux_conexion_id = null;
			$aux_conexion_id .= $key->Conexion_Id;

			
			for ($i= (4-sizeof($aux_conexion_id)); $i >= 0; $i--) { 
				$aux_conexion_id ="0".$aux_conexion_id;
			}
			$uno_cuatro = $aux_conexion_id."          ";
			$uno_cinco = "$key->id              ";
			$pdf->Cell(0,10,$uno_uno.$uno_dos.$uno_tres.$uno_cuatro.$uno_cinco,0, 2 ,'R',false);
			$pdf->Ln(7);
			//LINEA DOS
			$dos_uno = "    ".date("m");
			$dos_dos = "      ".date("Y");
			 if($key->Conexion_Categoria == 1) 
				$uno_tres =  "      Familiar";
			else $uno_tres =  "      Comercial";
			$aux_conexion_id = null;
			$aux_conexion_id .= $key->Conexion_Id;
			for ($i= (4-sizeof($aux_conexion_id)); $i >= 0; $i--) { 
				$aux_conexion_id ="0".$aux_conexion_id;
			}
			$dos_cuatro= "            ".$aux_conexion_id;
			$dos_cinco = "           Excento";
			$dos_seis = "      ".$key->id;
			$pdf->Cell(0,10,$dos_uno.$dos_dos.$uno_tres.$dos_cuatro.$dos_cinco.$dos_seis,0, 2 ,'L',false);
			$pdf->Ln(10);
			//LINEA TRES
			$tres_uno="                                      ";
			//
			$aux_razon_socal = null;
			$aux_razon_socal .= $key->Cli_RazonSocial;
			$aux_tamanio =  '                                                    ';
			for ($i= (strlen($aux_tamanio)-strlen($key->Cli_RazonSocial)); $i >= 0; $i--) { 
				$aux_razon_socal =$aux_razon_socal." ";
			}
			//$tres_dos =  "Hombre oso                                          ";
			//
			$aux_num_cli = null;
			$aux_num_cli .= $key->Cli_Id;
			$aux_tamanio =  "                       ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Cli_Id)); $i >= 0; $i--) { 
				$aux_num_cli =$aux_num_cli." ";
			}
			//$tres_tres = "12                     ";
			//
			$aux_vto_1_inicio = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+15 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_1_inicio .= $nuevafecha;
			$aux_tamanio =  "                             ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_1_inicio =$aux_vto_1_inicio." ";
			}
			//$tres_cuatro ="21/09/2017                      ";
			//
			$aux_vto_1_fin = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+20 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_1_fin .= $nuevafecha;
			$aux_tamanio =  "                             ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_1_fin =$aux_vto_1_fin." ";
			}
			//$tres_cinco= "21/09/2017                        ";
			//
			$aux_vto_1_monto = null;
			$aux_vto_1_monto .= $total;
			$aux_tamanio = "150    ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_1_monto =$aux_vto_1_monto." ";
			}
			//$tres_seis ="150";
			$pdf->Cell(0,10,$tres_uno.utf8_decode($aux_razon_socal).$aux_num_cli.$aux_vto_1_inicio.$aux_vto_1_fin."$ ".$aux_vto_1_monto,0, 2 ,'L',false);
			$pdf->Ln(2);
			//LINEA 4
			//Cli_DomicilioSuministro
			$cuatro_uno = "                                      ";
			//
			$aux_domicilio = null;
			$aux_domicilio .= $key->Cli_DomicilioSuministro;
			if(sizeof($aux_domicilio) > 13)
				$aux_tamanio = "                                                                                  ";
			else $aux_tamanio = "                                                                  ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Cli_DomicilioSuministro)); $i >= 0; $i--) { 
				$aux_domicilio =$aux_domicilio." ";
			}
			 //
			$aux_vto_2_inicio = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+21 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_2_inicio .= $nuevafecha;
			$aux_tamanio = "21/09/2017                  ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_2_inicio =$aux_vto_2_inicio." ";
			}
			 //
			$aux_vto_2_fin = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+27 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_2_fin .= $nuevafecha;
			$aux_tamanio = "21/09/2017                    ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_2_fin =$aux_vto_2_fin." ";
			}
			//
			$aux_vto_2_monto = null;
			$aux_vto_2_monto .= $total;
			$aux_tamanio = "150    ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_2_monto =$aux_vto_2_monto." ";
			}
			//$tres_seis ="150";
			$pdf->Cell(0,10,$cuatro_uno.$aux_domicilio.$aux_vto_2_inicio.$aux_vto_2_fin."$ ".$aux_vto_2_monto,0, 2 ,'L',false);
			$pdf->Ln(2);    
			//LINEA 5
			//Cli_Cuit
			$cinco_uno = "                                      ";
			$pdf->Cell(0,10,$cinco_uno.$key->Cli_Cuit,0, 2 ,'L',false);
			$pdf->Ln(37);
			//LINEA 6
			$seis_uno ="     ";
			//
			$aux_medicion_anterior = null;
			$aux_medicion_anterior .= $key->Medicion_Anterior;
			$aux_tamanio = "120                ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Anterior)); $i >= 0; $i--) { 
				$aux_medicion_anterior =$aux_medicion_anterior." ";
			}
			//
			$aux_medicion_actual = null;
			$aux_medicion_actual .= $key->Medicion_Actual;
			$aux_tamanio = "13                ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Actual)); $i >= 0; $i--) { 
				$aux_medicion_actual =$aux_medicion_actual." ";
			}
			//
			$aux_medicion_total = null;
			$aux_medicion_total .= $key->Medicion_Basico+$key->Medicion_Excedente;
			$aux_tamanio = "133                ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Basico+$key->Medicion_Excedente)); $i >= 0; $i--) { 
				$aux_medicion_total =$aux_medicion_total." ";
			}
			//
			$aux_medicion_basico = null;
			$aux_medicion_basico .= $key->Medicion_Basico;
			$aux_tamanio = "10                    ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Basico)); $i >= 0; $i--) { 
				$aux_medicion_basico =$aux_medicion_basico." ";
			}
			$pdf->Cell(0,10,$seis_uno.$aux_medicion_anterior.$aux_medicion_actual.$aux_medicion_total.$aux_medicion_basico.$key->Medicion_Excedente,0, 2 ,'L',false);
			$pdf->Ln(14);
			//LINEA 7    DEUDA DE LA CONEXION
			$siete_uno = "                                    ";
			$aux_medicion_basico = null;
			$aux_medicion_basico .= $key->Medicion_Basico;
			$aux_tamanio = "10                    ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Basico)); $i >= 0; $i--) { 
				$aux_medicion_basico =$aux_medicion_basico." ";
			}
			$pdf->Cell(0,10,$siete_uno.$key->Conexion_Deuda,0, 2 ,'L',false);
			$pdf->Ln(2);
			//LINEA 8   TARIFA BASICA
			$ocho_uno ="                                      $";
			$pdf->Cell(0,10,$ocho_uno.$datos["configuracion"][4]->Configuracion_Valor,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 9   EXCEDENTE
			$nueve_uno = "                                      $";
			//$
			$aux_excedente_monto = null;
			$aux_excedente_monto .= floatval ($key->Medicion_Excedente) * floatval ($datos["configuracion"][3]->Configuracion_Valor);
			$aux_tamanio = "200.10                                       ";
			for ($i= (strlen($aux_tamanio)-strlen(floatval ($key->Medicion_Excedente) * floatval ($datos["configuracion"][3]->Configuracion_Valor))); $i >= 0; $i--) { 
				$aux_excedente_monto =$aux_excedente_monto." ";
			}
			$pdf->Cell(0,10,$nueve_uno.$aux_excedente_monto.$aux_vto_1_inicio,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 10  CUOTA SOCIAL
			$diez_uno = "                                      $";
			//$
			$aux_cuota_social_monto = null;
			$aux_cuota_social_monto .= floatval ($datos["configuracion"][2]->Configuracion_Valor);
			$aux_tamanio = "125.01                                         ";
			for ($i= (strlen($aux_tamanio)-strlen(floatval ($datos["configuracion"][2]->Configuracion_Valor))); $i >= 0; $i--) { 
				$aux_cuota_social_monto =$aux_cuota_social_monto." ";
			}
			 //$
			$aux_fecha_vto_2 = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+21 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );

			$aux_fecha_vto_2 .=  $nuevafecha;
			$aux_tamanio = "29/09/2017                     ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_fecha_vto_2 =$aux_fecha_vto_2." ";
			}
			$pdf->Cell(0,10,$diez_uno.$aux_cuota_social_monto.$aux_fecha_vto_2.$dos_uno."            ".$dos_dos."      ".$uno_tres.$dos_cuatro."  ".$dos_seis,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 11   MEDIDOR
			$once_uno = "                             (";
			//
			$once_dos = $key->PlanMedidor_CoutaActual."/".$key->PlanMedidor_Coutas.")  $";
			$pdf->Cell(0,10,$once_uno.$once_dos.$key->PlanMedidor_MontoCuota,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 12   PLAN DE PAGO
			$doce_uno = "                                (";
			//
			$doce_dos = $key->PlanPago_CoutaActual."/".$key->PlanPago_Coutas.")  $";
			//   
			$aux_plan_pago_monto = null;
			$aux_plan_pago_monto .= $key->PlanPago_MontoCuota;
			if ( ($key->PlanPago_MontoCuota == NULL )|| ($key->PlanPago_CoutaActual== NULL)|| ($key->PlanPago_Coutas== NULL)  )
				$aux_tamanio = "75.00                                                  ";
			else $aux_tamanio = "75.00                                           ";

			for ($i= (strlen($aux_tamanio)-strlen($key->PlanPago_MontoCuota)); $i >= 0; $i--) { 
				$aux_plan_pago_monto =$aux_plan_pago_monto." ";
			}
			//
			$aux_razon_social_doce = null;
			$aux_razon_social_doce .= $key->Cli_RazonSocial;
			$aux_tamanio = "Hombre oso                                                            ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Cli_RazonSocial)); $i >= 0; $i--) { 
				$aux_razon_social_doce =$aux_razon_social_doce." ";
			}
			$pdf->Cell(0,10,$doce_uno.$doce_dos.$aux_plan_pago_monto.'$2000.00                           '.$aux_razon_social_doce.$key->Cli_Id,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 13    BONIFICACION
			$trece_uno = "                                      $";
			$trece_dos=0;
			if($key->Conexion_Bonificacion == 0)
				$trece_dos=0;
			elseif ($key->Conexion_Bonificacion == 1)
			{
				$trece_dos = $subtotal_sin_bonificacion - $subtotal_con_bonificacion;
			}
			$pdf->Cell(0,10,$trece_uno.$trece_dos,0, 2 ,'L',false);
			$pdf->Ln(4);
			//LINEA 14   SUBTOTAL  
			$catorce_uno = "                                    $";
			$catorce_dos = round($subtotal_con_bonificacion, 2 ); //PONER EL SUBTOTAL
			$pdf->Cell(0,10,$catorce_uno.$catorce_dos,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 15  PAGOS A CUENTA
			$quince_uno = "                                      $";
			$aux_acuenta = null;
			if($key->Conexion_Acuenta == null)
				$aux_acuenta .= "0";
			else $aux_acuenta .= $key->Conexion_Acuenta;
			$aux_tamanio = "$80.00                                                                                      ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Conexion_Acuenta)); $i >= 0; $i--) { 
				$aux_acuenta =$aux_acuenta." ";
			}
			//
			$aux_vto_1_inicio = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+15 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_1_inicio .= $nuevafecha;
			$aux_tamanio =  "21/09/2017                      ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_1_inicio =$aux_vto_1_inicio." ";
			}
			//$tres_cuatro ="21/09/2017                      ";
			//
			$aux_vto_1_fin = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+20 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_1_fin .= $nuevafecha;
			$aux_tamanio =  "                                   ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_1_fin =$aux_vto_1_fin." ";
			}
			$pdf->Cell(0,10,$quince_uno.$aux_acuenta.$aux_vto_1_inicio.$aux_vto_1_fin."$ ". $total,0, 2 ,'L',false);
			$pdf->Ln(2);
			//LINEA 16  BONIFICACION
			$dieciseis_uno = "                                    ";
			//
			if(isset($key->Bonificacion_Monto))
				$bonificacion = "$$key->Bonificacion_Monto                                                                                    ";
			else $bonificacion = "$0                                                                                    ";
			//
			$aux_vto_2_inicio = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+21 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_2_inicio .= $nuevafecha;
			$aux_tamanio = "21/09/2017                      ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_2_inicio =$aux_vto_2_inicio." ";
			}
			//
			$aux_vto_2_fin = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+27 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_2_fin .= $nuevafecha;
			$aux_tamanio = "21/09/2017                          ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_2_fin =$aux_vto_2_fin." ";
			}
			$pdf->Cell(0,10,$dieciseis_uno.$bonificacion.$aux_vto_2_inicio.$aux_vto_2_fin."$ ".$total,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 17
			$diecisiete_uno = "                                    ";
			$pdf->Cell(0,10,$diecisiete_uno."$ ".$total,0, 2 ,'L',false);//PONER EL TOTAL
			if($band_impar == 0) 
			{
				$pdf->Ln(47);  
				$band_impar = 1;
			}
			else $band_impar = 0;
		}
		$pdf->Output();
	}

	public function crear_factura_por_lote($datos)
	{
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		$fontSize = 8;
		$marge    = 3;   // between barcode and hri in pixel
		$x        = 232;  // barcode center
		$y        = 375;  // barcode center
		$height   = 42;   // barcode height in 1D ; module size in 2D
		$width    = 1;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		// -------------------------------------------------- //
		//            ALLOCATE FPDF RESSOURCE
		// -------------------------------------------------- //
		$pdf = new eFPDF('P', 'pt');
		#Establecemos los márgenes izquierda, arriba y derecha: 
		$pdf->SetMargins(25, 30 , 20); 
		#Establecemos el margen inferior: 
		$pdf->SetAutoPageBreak(true,15);  
		$band_impar = 0;
		foreach ($datos["resultado"] as $key) 
		{
			$this->SetX(0);
			$code = $this->calcular_codigo_barra_agua($key->Conexion_Id, $key->id ); // barcode, of course ;)
			if($band_impar == 0)
			{
				$pdf->AddPage();
				$pdf->Image(base_url().'img/boleta_villa_elisa_v4.jpg', 0, 0 ,595, 418); 
				// -------------------------------------------------- //
				//                      BARCODE
				// -------------------------------------------------- //
				$x        = 232;  // barcode center
				$y        = 375;  // barcode center
				$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
				$pdf->SetFont('Arial','B',$fontSize);
				$pdf->SetTextColor(0, 0, 0);
				$len = $pdf->GetStringWidth($data['hri']);
				Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
				$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
			}
			else
			{
				$pdf->Image(base_url().'img/boleta_villa_elisa_v4.jpg', 0, 419 ,595, 418); 
				// -------------------------------------------------- //
				//                      BARCODE
				// -------------------------------------------------- //
				$x        = 232;  // barcode center
				$y        = 793;  // barcode center
				$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
				$pdf->SetFont('Arial','B',$fontSize);
				$pdf->SetTextColor(0, 0, 0);
				$len = $pdf->GetStringWidth($data['hri']);
				Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
				$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
			}
			/*
			CALUCLO DE DATOS DE LA BOLETA
			TOTAL = 
			SUBTOTAL = 
			$key->Conexion_Bonificacion == 1)
			{
				$descuento = floatval("0.5");
				$trece_dos= $descuento * floatval("500"); // poner el subtotal
			*/
			if(isset($key->PlanMedidor_MontoCuota))
				$monto_cuota_medidor = $key->PlanMedidor_MontoCuota ;
			else $monto_cuota_medidor = 0 ;
			if($monto_cuota_medidor == null)
				$monto_cuota_medidor = 0;
			if(isset($key->PlanPago_MontoCuota))
				$monto_cuota_plan_pago = $key->PlanPago_MontoCuota ;
			else $monto_cuota_plan_pago = 0;
			if($monto_cuota_plan_pago == null)
				$monto_cuota_plan_pago = 0;
			$subtotal_sin_bonificacion = $key->Conexion_Deuda + 
						$datos["configuracion"][4]->Configuracion_Valor  + 
						floatval ($key->Medicion_Excedente) * floatval ($datos["configuracion"][3]->Configuracion_Valor) +   
						floatval ($datos["configuracion"][2]->Configuracion_Valor) +
						$monto_cuota_medidor  +
						$monto_cuota_plan_pago ;
			if ($key->Conexion_Bonificacion == 1)
				$subtotal_con_bonificacion = $subtotal_sin_bonificacion * floatval("0.5");
			else $subtotal_con_bonificacion = $subtotal_sin_bonificacion;

			$total = $subtotal_con_bonificacion + $key->Conexion_Acuenta;
			if(isset($key->Bonificacion_Monto) )
				$total -=$key->Bonificacion_Monto;
			//var_dump($key->PlanPago_MontoCuota);die();
			$pdf->Ln(100);   
			$uno_uno =  "                                                                         ".date("m")."               ";
			$uno_dos =  date("Y")."           ";
			if($key->Conexion_Categoria == 1) 
				$uno_tres =  "Familiar            ";
			else $uno_tres =  "Comercial            ";
			$aux_conexion_id = null;
			$aux_conexion_id .= $key->Conexion_Id;

			
			for ($i= (4-sizeof($aux_conexion_id)); $i >= 0; $i--) { 
				$aux_conexion_id ="0".$aux_conexion_id;
			}
			$uno_cuatro = $aux_conexion_id."          ";
			$uno_cinco = "$key->id               ";
			$pdf->Cell(0,10,$uno_uno.$uno_dos.$uno_tres.$uno_cuatro.$uno_cinco,0, 2 ,'R',false);
			$pdf->Ln(7);
			//LINEA DOS
			$dos_uno = "    ".date("m");
			$dos_dos = "      ".date("Y");
			 if($key->Conexion_Categoria == 1) 
				$uno_tres =  "      Familiar";
			else $uno_tres =  "      Comercial";
			$aux_conexion_id = null;
			$aux_conexion_id .= $key->Conexion_Id;
			for ($i= (4-sizeof($aux_conexion_id)); $i >= 0; $i--) { 
				$aux_conexion_id ="0".$aux_conexion_id;
			}
			$dos_cuatro= "            ".$aux_conexion_id;
			$dos_cinco = "           Excento";
			$dos_seis = "            $key->id";
			$pdf->Cell(0,10,$dos_uno.$dos_dos.$uno_tres.$dos_cuatro.$dos_cinco.$dos_seis,0, 2 ,'L',false);
			$pdf->Ln(10);
			//LINEA TRES
			$tres_uno="                                      ";
			//
			$aux_razon_socal = null;
			$aux_razon_socal .= $key->Cli_RazonSocial;
			$aux_tamanio =  '                                                    ';
			for ($i= (strlen($aux_tamanio)-strlen($key->Cli_RazonSocial)); $i >= 0; $i--) { 
				$aux_razon_socal =$aux_razon_socal." ";
			}
			//$tres_dos =  "Hombre oso                                          ";
			//
			$aux_num_cli = null;
			$aux_num_cli .= $key->Cli_Id;
			$aux_tamanio =  "                       ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Cli_Id)); $i >= 0; $i--) { 
				$aux_num_cli =$aux_num_cli." ";
			}
			//$tres_tres = "12                     ";
			//
			$aux_vto_1_inicio = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+15 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_1_inicio .= $nuevafecha;
			$aux_tamanio =  "                             ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_1_inicio =$aux_vto_1_inicio." ";
			}
			//$tres_cuatro ="21/09/2017                      ";
			//
			$aux_vto_1_fin = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+20 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_1_fin .= $nuevafecha;
			$aux_tamanio =  "                             ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_1_fin =$aux_vto_1_fin." ";
			}
			//$tres_cinco= "21/09/2017                        ";
			//
			$aux_vto_1_monto = null;
			$aux_vto_1_monto .= "$ ".$total;
			$aux_tamanio = "$total    ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_1_monto =$aux_vto_1_monto." ";
			}
			//$tres_seis ="150";
			$pdf->Cell(0,10,$tres_uno.utf8_decode($aux_razon_socal).$aux_num_cli.$aux_vto_1_inicio.$aux_vto_1_fin.$aux_vto_1_monto,0, 2 ,'L',false);
			$pdf->Ln(2);
			//LINEA 4
			//Cli_DomicilioSuministro
			$cuatro_uno = "                                      ";
			//
			$aux_domicilio = null;
			$aux_domicilio .= $key->Cli_DomicilioSuministro;
			if(sizeof($key->Cli_DomicilioSuministro) < 16)
				$aux_tamanio = "                                                                                            ";
			else $aux_tamanio = "                                                                                  ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Cli_DomicilioSuministro)); $i >= 0; $i--) { 
				$aux_domicilio =$aux_domicilio." ";
			}
			 //
			$aux_vto_2_inicio = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+21 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_2_inicio .= $nuevafecha;
			$aux_tamanio = "21/09/2017                  ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_2_inicio =$aux_vto_2_inicio." ";
			}
			 //
			$aux_vto_2_fin = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+27 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_2_fin .= $nuevafecha;
			$aux_tamanio = "21/09/2017                    ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_2_fin =$aux_vto_2_fin." ";
			}
			//
			$aux_vto_2_monto = null;
			$aux_vto_2_monto .= "$ ".$total;
			$aux_tamanio = "$total    ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_2_monto =$aux_vto_2_monto." ";
			}
			//$tres_seis ="150";
			$pdf->Cell(0,10,$cuatro_uno.$aux_domicilio.$aux_vto_2_inicio.$aux_vto_2_fin.$aux_vto_2_monto,0, 2 ,'L',false);
			$pdf->Ln(2);    
			//LINEA 5
			//Cli_Cuit
			$cinco_uno = "                                      ";
			$pdf->Cell(0,10,$cinco_uno.$key->Cli_Cuit,0, 2 ,'L',false);
			$pdf->Ln(37);
			//LINEA 6
			$seis_uno ="     ";
			//
			$aux_medicion_anterior = null;
			$aux_medicion_anterior .= $key->Medicion_Anterior;
			$aux_tamanio = "120                ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Anterior)); $i >= 0; $i--) { 
				$aux_medicion_anterior =$aux_medicion_anterior." ";
			}
			//
			$aux_medicion_actual = null;
			$aux_medicion_actual .= $key->Medicion_Actual;
			$aux_tamanio = "13                ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Actual)); $i >= 0; $i--) { 
				$aux_medicion_actual =$aux_medicion_actual." ";
			}
			//
			$aux_medicion_total = null;
			$aux_medicion_total .= $key->Medicion_Basico+$key->Medicion_Excedente;
			$aux_tamanio = "133                ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Basico+$key->Medicion_Excedente)); $i >= 0; $i--) { 
				$aux_medicion_total =$aux_medicion_total." ";
			}
			//
			$aux_medicion_basico = null;
			$aux_medicion_basico .= $key->Medicion_Basico;
			$aux_tamanio = "10                    ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Basico)); $i >= 0; $i--) { 
				$aux_medicion_basico =$aux_medicion_basico." ";
			}
			$pdf->Cell(0,10,$seis_uno.$aux_medicion_anterior.$aux_medicion_actual.$aux_medicion_total.$aux_medicion_basico.$key->Medicion_Excedente,0, 2 ,'L',false);
			$pdf->Ln(14);
			//LINEA 7   DEUDA
			$siete_uno = "                                    ";
			$aux_medicion_basico = null;
			$aux_medicion_basico .= $key->Medicion_Basico;
			$aux_tamanio = "10                      ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Medicion_Basico)); $i >= 0; $i--) { 
				$aux_medicion_basico =$aux_medicion_basico." ";
			}
			$pdf->Cell(0,10,$siete_uno."$ ".$key->Conexion_Deuda,0, 2 ,'L',false);
			$pdf->Ln(2);
			//LINEA 8  Tarifa Basica
			$ocho_uno ="                                    ";
			$pdf->Cell(0,10,$ocho_uno."$ ".$datos["configuracion"][4]->Configuracion_Valor,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 9 Excedente
			$nueve_uno = "                                    $";
			//$
			$aux_excedente_monto = null;
			$aux_excedente_monto .= floatval ($key->Medicion_Excedente) * floatval ($datos["configuracion"][3]->Configuracion_Valor);
			$aux_tamanio = "200.10                                    ";
			for ($i= (strlen($aux_tamanio)-strlen(floatval ($key->Medicion_Excedente) * floatval ($datos["configuracion"][3]->Configuracion_Valor))); $i >= 0; $i--) { 
				$aux_excedente_monto =$aux_excedente_monto." ";
			}
			$pdf->Cell(0,10,$nueve_uno.$aux_excedente_monto.$aux_vto_1_inicio,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 10    CUOTA SOCIAL
			$diez_uno = "                                    $";
			//$
			$aux_cuota_social_monto = null;
			$aux_cuota_social_monto .= floatval ($datos["configuracion"][2]->Configuracion_Valor);
			$aux_tamanio = "125.01                                         ";
			for ($i= (strlen($aux_tamanio)-strlen(floatval ($datos["configuracion"][2]->Configuracion_Valor))); $i >= 0; $i--) { 
				$aux_cuota_social_monto =$aux_cuota_social_monto." ";
			}
			 //$
			$aux_fecha_vto_2 = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+21 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );

			$aux_fecha_vto_2 .=  $nuevafecha;
			$aux_tamanio = "29/09/2017                     ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_fecha_vto_2 =$aux_fecha_vto_2." ";
			}
			$pdf->Cell(0,10,$diez_uno.$aux_cuota_social_monto.$aux_fecha_vto_2.$dos_uno."            ".$dos_dos."      ".$uno_tres.$dos_cuatro."  ".$dos_seis,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 11
			$once_uno = "                             (";
			//
			$once_dos = $key->PlanMedidor_CoutaActual."/".$key->PlanMedidor_Coutas.")  $";
			$pdf->Cell(0,10,$once_uno.$once_dos.$key->PlanMedidor_MontoCuota,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 12
			$doce_uno = "                                (";
			//
			$doce_dos = $key->PlanPago_CoutaActual."/".$key->PlanPago_Coutas.")  $";
			//
			$aux_plan_pago_monto = null;
			$aux_plan_pago_monto .= $key->PlanPago_MontoCuota;
			$aux_tamanio = "75.00                                           ";
			for ($i= (strlen($aux_tamanio)-strlen($key->PlanPago_MontoCuota)); $i >= 0; $i--) { 
				$aux_plan_pago_monto =$aux_plan_pago_monto." ";
			}
			//
			$aux_razon_social_doce = null;
			$aux_razon_social_doce .= $key->Cli_RazonSocial;
			$aux_tamanio = "Hombre oso                                                            ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Cli_RazonSocial)); $i >= 0; $i--) { 
				$aux_razon_social_doce =$aux_razon_social_doce." ";
			}
			$pdf->Cell(0,10,$doce_uno.$doce_dos.$aux_plan_pago_monto.'$2000.00                           '.$aux_razon_social_doce.$key->Cli_Id,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 13
			$trece_uno = "                                      $";
			$trece_dos=0;

			if($key->Conexion_Bonificacion == 0)
				$trece_dos=0;
			elseif ($key->Conexion_Bonificacion == 1)
			{
				$descuento = floatval("0.5");
				$trece_dos= $descuento * floatval("500"); // poner el subtotal
			}
			$pdf->Cell(0,10,$trece_uno.$trece_dos,0, 2 ,'L',false);
			$pdf->Ln(4);
			//LINEA 14
			$catorce_uno = "                                    $";
			$catorce_dos =$subtotal_sin_bonificacion; //PONER EL SUBTOTAL
			$pdf->Cell(0,10,$catorce_uno.$catorce_dos,0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 15
			//Conexion_Acuenta
			$quince_uno = "                                      $";
			//
			$aux_acuenta = null;
			if($key->Conexion_Acuenta == null)
				$aux_acuenta .= "0";
			else $aux_acuenta .= $key->Conexion_Acuenta;
			$aux_tamanio = "$80.00                                                                                  ";
			for ($i= (strlen($aux_tamanio)-strlen($key->Conexion_Acuenta)); $i >= 0; $i--) { 
				$aux_acuenta =$aux_acuenta." ";
			}
			 //
			$aux_vto_1_inicio = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+15 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_1_inicio .= $nuevafecha;
			$aux_tamanio =  "21/09/2017                      ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_1_inicio =$aux_vto_1_inicio." ";
			}
			//$tres_cuatro ="21/09/2017                      ";
			//
			$aux_vto_1_fin = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+20 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_1_fin .= $nuevafecha;
			$aux_tamanio =  "                                   ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_1_fin =$aux_vto_1_fin." ";
			}
		   

			$pdf->Cell(0,10,$quince_uno.$aux_acuenta.$aux_vto_1_inicio.$aux_vto_1_fin.'150',0, 2 ,'L',false);
			$pdf->Ln(2);

			//LINEA 16
			$dieciseis_uno = "                                      ";
			//
			$bonificacion = "$200.60                                                                                    ";// PONER BONIFICACION AQUI
			//
			$aux_vto_2_inicio = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+21 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_2_inicio .= $nuevafecha;
			$aux_tamanio = "21/09/2017                      ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_2_inicio =$aux_vto_2_inicio." ";
			}
			//
			$aux_vto_2_fin = null;
			$fecha = date('Y-m-d');
			$fecha [strlen($fecha)-1] = "1";
			$fecha [strlen($fecha)-2] = "0";
			$nuevafecha = strtotime ( '+27 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aux_vto_2_fin .= $nuevafecha;
			$aux_tamanio = "21/09/2017                          ";
			for ($i= (strlen($aux_tamanio)-strlen($nuevafecha)); $i >= 0; $i--) { 
				$aux_vto_2_fin =$aux_vto_2_fin." ";
			}
			$pdf->Cell(0,10,$dieciseis_uno.$bonificacion.$aux_vto_2_inicio.$aux_vto_2_fin.'150',0, 2 ,'L',false);
			$pdf->Ln(3);
			//LINEA 17
			$diecisiete_uno = "                                    ";

			$pdf->Cell(0,10,$diecisiete_uno.'$1721.00',0, 2 ,'L',false);//PONER EL TOTAL
			if($band_impar == 0) 
			{
				$pdf->Ln(47);  
				$band_impar = 1;
			}
			else $band_impar = 0;
		}
		$pdf->Output();
	}
	
	public function crear_recibo_ingreso_nuevo($datos, $tipo)
	{
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		$fontSize = 15;
		$marge    = 3;   // between barcode and hri in pixel
		$x        = 300;  // barcode center
		$y        = 170;  // barcode center
		$height   = 42;   // barcode height in 1D ; module size in 2D
		$width    = 1;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		
		$code     = '183956089415'; // barcode, of course ;)
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		// -------------------------------------------------- //
		//            ALLOCATE FPDF RESSOURCE
		// -------------------------------------------------- //
		$pdf = new eFPDF('P', 'pt');
		#Establecemos los márgenes izquierda, arriba y derecha: 
		$pdf->SetMargins(25, 20 , 20); 
		#Establecemos el margen inferior: 
		$pdf->SetAutoPageBreak(true,15);  
	   
		$pdf->AddPage();
		$pdf->Image(base_url().'/img/recibo.jpg', 0, 0 ,600, 200); 
		// -------------------------------------------------- //
		//                      BARCODE
		// -------------------------------------------------- //
		$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		// -------------------------------------------------- //
		//                      HRI
		// -------------------------------------------------- //
		$pdf->SetFont('Arial','B',$fontSize);
		$pdf->SetTextColor(0, 0, 0);
		$len = $pdf->GetStringWidth($data['hri']);
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
		//LINEA 1
		$uno_uno = "              ";
		$pdf->Cell(0,10,$datos["resultado"][0]->Factura_Id.$uno_uno,0, 2 ,'R',false);
		//LINEA 2
		$pdf->Ln(14);
		$dos_uno = "                                             ";
		//
		$dos_dos = "                            ";
		//
		$dos_tres = "             ";
		$dias = date("d", strtotime($datos["resultado"][0]->Factura_PagoTimestamp));
		$mes = date("m", strtotime($datos["resultado"][0]->Factura_PagoTimestamp));
		$years = date("Y", strtotime($datos["resultado"][0]->Factura_PagoTimestamp));

		$pdf->Cell(0,10,$dos_uno.$dias.$dos_dos.$this->meses[$mes].$dos_tres.$years,0, 2 ,'R',false);
		//LINEA  3 
		$pdf->Ln(19);
		$tres_uno = "                                  ";
		$pdf->Cell(0,10,$tres_uno.utf8_decode($datos["resultado"][0]->Cli_RazonSocial),0, 2 ,'L',false);
		$pdf->Ln(13);
		//LINEA 4
		$cuatro_uno ="                         " ;
		$pdf->Cell(0,10,$cuatro_uno.$this->convertir_a_letras(floatval( $datos["resultado"][0]->Factura_PagoMonto) ) ,0, 2 ,'L',false);
		$pdf->Ln(12);
		//LINEA 5
		$cinco_uno = "                         ";
		if($tipo == 1)
			$pdf->Cell(0,10,$cinco_uno.'Pago de impuesto por servicios de agua potable en domicilio',0, 2 ,'L',false);
		elseif($tipo == 2)
			$pdf->Cell(0,10,$cinco_uno.'Pago acuenta de deuda o impuesto de agua',0, 2 ,'L',false);
		$pdf->Ln(34);
		//LINEA 6
		$seis_uno = "                ";
		$seis_dos = "                                                                        ";
		  
		$pdf->Cell(0,10,$cinco_uno.$this->arreglar_numero($datos["resultado"][0]->Factura_PagoMonto).$seis_dos. $datos["nombre"] ,0, 2 ,'L',false);
		$pdf->Output();
	}
	public function crear_recibo_ingreso($datos, $tipo)
	{
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		$fontSize = 15;
		$marge    = 3;   // between barcode and hri in pixel
		$x        = 300;  // barcode center
		$y        = 170;  // barcode center
		$height   = 42;   // barcode height in 1D ; module size in 2D
		$width    = 1;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		
		$code     = '183956089415'; // barcode, of course ;)
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		// -------------------------------------------------- //
		//            ALLOCATE FPDF RESSOURCE
		// -------------------------------------------------- //
		$pdf = new eFPDF('P', 'pt');
		#Establecemos los márgenes izquierda, arriba y derecha: 
		$pdf->SetMargins(25, 20 , 20); 
		#Establecemos el margen inferior: 
		$pdf->SetAutoPageBreak(true,15);  
	   
		$pdf->AddPage();
		$pdf->Image(base_url().'/img/recibo.jpg', 0, 0 ,600, 200); 
		// -------------------------------------------------- //
		//                      BARCODE
		// -------------------------------------------------- //
		$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		// -------------------------------------------------- //
		//                      HRI
		// -------------------------------------------------- //
		$pdf->SetFont('Arial','B',$fontSize);
		$pdf->SetTextColor(0, 0, 0);
		$len = $pdf->GetStringWidth($data['hri']);
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
		//LINEA 1
		$uno_uno = "              ";
		$pdf->Cell(0,10,$datos["resultado"][0]->Pago_Id.$uno_uno,0, 2 ,'R',false);
		//LINEA 2
		$pdf->Ln(14);
		$dos_uno = "                                             ";
		//
		$dos_dos = "                            ";
		//
		$dos_tres = "             ";
		$dias = date("d", strtotime($datos["resultado"][0]->Pago_Timestamp));
		$mes = date("m", strtotime($datos["resultado"][0]->Pago_Timestamp));
		$years = date("Y", strtotime($datos["resultado"][0]->Pago_Timestamp));

		$pdf->Cell(0,10,$dos_uno.$dias.$dos_dos.$this->meses[$mes].$dos_tres.$years,0, 2 ,'R',false);
		//LINEA  3 
		$pdf->Ln(19);
		$tres_uno = "                                  ";
		$pdf->Cell(0,10,$tres_uno.utf8_decode($datos["resultado"][0]->Cli_RazonSocial),0, 2 ,'L',false);
		$pdf->Ln(13);
		//LINEA 4
		$cuatro_uno ="                         " ;
		//$pdf->Cell(0,10,$cuatro_uno.$this->convertir_a_letras($datos["resultado"][0]->Pago_Monto),0, 2 ,'L',false);
		//$algo = $this->convertir_a_letras(floatval( $datos["resultado"][0]->Pago_Monto) ) ;
		//var_dump($algo);die();
		$pdf->Cell(0,10,$cuatro_uno.$this->convertir_a_letras(floatval( $datos["resultado"][0]->Pago_Monto) ) ,0, 2 ,'L',false);
		$pdf->Ln(12);
		//LINEA 5
		$cinco_uno = "                         ";
		if($tipo == 1)
			$pdf->Cell(0,10,$cinco_uno.'Pago de impuesto por servicios de agua potable en domicilio',0, 2 ,'L',false);
		elseif($tipo == 2)
			$pdf->Cell(0,10,$cinco_uno.'Pago acuenta de deuda o impuesto de agua',0, 2 ,'L',false);
		$pdf->Ln(34);
		//LINEA 6
		$seis_uno = "                ";
		$seis_dos = "                                                                        ";
		  
		$pdf->Cell(0,10,$cinco_uno.$this->arreglar_numero($datos["resultado"][0]->Pago_Monto).$seis_dos. $datos["nombre"] ,0, 2 ,'L',false);
		$pdf->Output();
	}
	public function crear_recibo_ingreso_actualizado($datos, $tipo)
	{
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		$fontSize = 15;
		$marge    = 3;   // between barcode and hri in pixel
		$x        = 300;  // barcode center
		$y        = 170;  // barcode center
		$height   = 42;   // barcode height in 1D ; module size in 2D
		$width    = 1;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		
		$code     = '183956089415'; // barcode, of course ;)
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		// -------------------------------------------------- //
		//            ALLOCATE FPDF RESSOURCE
		// -------------------------------------------------- //
		$pdf = new eFPDF('P', 'pt');
		#Establecemos los márgenes izquierda, arriba y derecha: 
		$pdf->SetMargins(25, 20 , 20); 
		#Establecemos el margen inferior: 
		$pdf->SetAutoPageBreak(true,15);  
	   
		$pdf->AddPage();
		$pdf->Image(base_url().'/img/recibo.jpg', 0, 0 ,600, 200); 
		// -------------------------------------------------- //
		//                      BARCODE
		// -------------------------------------------------- //
		$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		// -------------------------------------------------- //
		//                      HRI
		// -------------------------------------------------- //
		$pdf->SetFont('Arial','B',$fontSize);
		$pdf->SetTextColor(0, 0, 0);
		$len = $pdf->GetStringWidth($data['hri']);
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
		//LINEA 1
		$uno_uno = "              ";
		$pdf->Cell(0,10,$datos["resultado"][0]->Mov_Id.$uno_uno,0, 2 ,'R',false);
		//LINEA 2
		$pdf->Ln(14);
		$dos_uno = "                                             ";
		//
		$dos_dos = "                              ";
		//
		$dos_tres = "             ";
		$dias = date("d", strtotime($datos["resultado"][0]->Mov_Timestamp));
		$mes = date("m", strtotime($datos["resultado"][0]->Mov_Timestamp));
		$years = date("Y", strtotime($datos["resultado"][0]->Mov_Timestamp));

		$pdf->Cell(0,10,$dos_uno.$dias.$dos_dos.$this->meses[$mes].$dos_tres.$years,0, 2 ,'R',false);
		//LINEA  3 
		$pdf->Ln(19);
		$tres_uno = "                                  ";
		$pdf->Cell(0,10,$tres_uno.utf8_decode(	$datos["resultado"][0]->Mov_a_quien),0, 2 ,'L',false);
		$pdf->Ln(13);
		//LINEA 4
		$cuatro_uno ="                       " ;
		//$pdf->Cell(0,10,$cuatro_uno.$this->convertir_a_letras($datos["resultado"][0]->Pago_Monto),0, 2 ,'L',false);
		//$algo = $this->convertir_a_letras(floatval( $datos["resultado"][0]->Pago_Monto) ) ;
		//var_dump($algo);die();
		$pdf->Cell(0,10,$cuatro_uno."pesos ".$this->convertir_a_letras(floatval( $datos["resultado"][0]->Mov_Monto) ) ,0, 2 ,'L',false);
		$pdf->Ln(12);
		//LINEA 5
		$cinco_uno = "                         ";
		$pdf->Cell(0,10,$cinco_uno.utf8_decode($datos["resultado"][0]->Mov_Observacion),0, 2 ,'L',false);
		$pdf->Ln(34);
		//LINEA 6
		$seis_uno = "                ";
		$seis_dos = "                                                                        ";
		  
		$pdf->Cell(0,10,$cinco_uno.$this->arreglar_numero($datos["resultado"][0]->Mov_Monto).$seis_dos. $datos["nombre"] ,0, 2 ,'L',false);
		$pdf->Output();
	}
	public function crear_recibo_egreso($datos, $tipo)
	{
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		$fontSize = 15;
		$marge    = 3;   // between barcode and hri in pixel
		$x        = 300;  // barcode center
		$y        = 170;  // barcode center
		$height   = 42;   // barcode height in 1D ; module size in 2D
		$width    = 1;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		
		$code     = '183956089415'; // barcode, of course ;)
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		// -------------------------------------------------- //
		//            ALLOCATE FPDF RESSOURCE
		// -------------------------------------------------- //
		$pdf = new eFPDF('P', 'pt');
		#Establecemos los márgenes izquierda, arriba y derecha: 
		$pdf->SetMargins(25, 20 , 20); 
		#Establecemos el margen inferior: 
		$pdf->SetAutoPageBreak(true,15);  
	   
		$pdf->AddPage();
		$pdf->Image(base_url().'/img/recibo_egreso.png', 0, 0 ,600, 200); 
		// -------------------------------------------------- //
		//                      BARCODE
		// -------------------------------------------------- //
		$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		// -------------------------------------------------- //
		//                      HRI
		// -------------------------------------------------- //
		$pdf->SetFont('Arial','B',$fontSize);
		$pdf->SetTextColor(0, 0, 0);
		$len = $pdf->GetStringWidth($data['hri']);
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
		//LINEA 1
		$uno_uno = "              ";
		$pdf->Cell(0,10,$datos["resultado"][0]->Mov_Id.$uno_uno,0, 2 ,'R',false);
		//LINEA 2
		$pdf->Ln(14);
		$dos_uno = "                                             ";
		//
		$dos_dos = "                              ";
		//
		$dos_tres = "             ";
		$dias = date("d", strtotime($datos["resultado"][0]->Mov_Timestamp));
		$mes = date("m", strtotime($datos["resultado"][0]->Mov_Timestamp));
		$years = date("Y", strtotime($datos["resultado"][0]->Mov_Timestamp));

		$pdf->Cell(0,10,$dos_uno.$dias.$dos_dos.$this->meses[$mes].$dos_tres.$years,0, 2 ,'R',false);
		//LINEA  3 
		$pdf->Ln(19);
		$tres_uno = "                                  ";
		$pdf->Cell(0,10,$tres_uno.utf8_decode(	$datos["resultado"][0]->Mov_a_quien),0, 2 ,'L',false);
		$pdf->Ln(13);
		//LINEA 4
		$cuatro_uno ="                       " ;
		//$pdf->Cell(0,10,$cuatro_uno.$this->convertir_a_letras($datos["resultado"][0]->Pago_Monto),0, 2 ,'L',false);
		//$algo = $this->convertir_a_letras(floatval( $datos["resultado"][0]->Pago_Monto) ) ;
		//var_dump($algo);die();
		$pdf->Cell(0,10,$cuatro_uno."pesos ".$this->convertir_a_letras(floatval( $datos["resultado"][0]->Mov_Monto) ) ,0, 2 ,'L',false);
		$pdf->Ln(12);
		//LINEA 5
		$cinco_uno = "                         ";
		$pdf->Cell(0,10,$cinco_uno.utf8_decode($datos["resultado"][0]->Mov_Observacion),0, 2 ,'L',false);
		$pdf->Ln(34);
		//LINEA 6
		$seis_uno = "                ";
		$seis_dos = "                                                                        ";
		  
		$pdf->Cell(0,10,$cinco_uno.$this->arreglar_numero($datos["resultado"][0]->Mov_Monto).$seis_dos. $datos["nombre"] ,0, 2 ,'L',false);
		$pdf->Output();
	}

	public function crear_recibo_movimiento_nuevo($datos, $tipo)
	{
		//var_dump($datos["resultado"][0]->Conexion_Id);die();
		// -------------------------------------------------- //
		$fontSize = 15;
		$marge    = 3;   // between barcode and hri in pixel
		$x        = 300;  // barcode center
		$y        = 170;  // barcode center
		$height   = 42;   // barcode height in 1D ; module size in 2D
		$width    = 1;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		// -------------------------------------------------- //		
		$code     = '183956089415'; // barcode, of course ;)
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		// -------------------------------------------------- //
		$pdf = new eFPDF('P', 'pt');
		#Establecemos los márgenes izquierda, arriba y derecha: 
		$pdf->SetMargins(25, 20 , 20); 
		#Establecemos el margen inferior: 
		$pdf->SetAutoPageBreak(true,15);  
		$pdf->AddPage();
		$pdf->Image(base_url().'/img/recibo.jpg', 0, 0 ,600, 200); 
		$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		// -------------------------------------------------- //
		$pdf->SetFont('Arial','B',$fontSize);
		$pdf->SetTextColor(0, 0, 0);
		$len = $pdf->GetStringWidth($data['hri']);
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
		//LINEA 1
		$uno_uno = "              ";
		//if( (isset($datos["resultado"][0]->Factura_Id)) && ($datos["resultado"][0]->Factura_Id != null))
			$pdf->Cell(0,10,$datos["resultado"][0]->Mov_Id.$uno_uno,0, 2 ,'R',false);
		// else
		// 	$pdf->Cell(0,10,"S/B".$uno_uno,0, 2 ,'R',false);
		//LINEA 2
		$pdf->Ln(14);
		$dos_uno = "                                             ";
		//
		$dos_dos = "                            ";
		//
		$dos_tres = "             ";
		$dias = date("d", strtotime($datos["resultado"][0]->Mov_Timestamp));
		$mes = date("m", strtotime($datos["resultado"][0]->Mov_Timestamp));
		$years = date("Y", strtotime($datos["resultado"][0]->Mov_Timestamp));

		$pdf->Cell(0,10,$dos_uno.$dias.$dos_dos.$this->meses[$mes].$dos_tres.$years,0, 2 ,'R',false);
		//LINEA  3 
		$pdf->Ln(19);
		$tres_uno = "                                  ";
		//if((isset($datos["resultado"][0]->Cli_RazonSocial)) &&  (isset($datos["resultado"][0]->Conexion_id)) )
			$pdf->Cell(0,10,$tres_uno.utf8_decode($datos["resultado"][0]->Cli_RazonSocial)." *Con: ".$datos["resultado"][0]->Conexion_Id,0, 2 ,'L',false);
		// else 
		// 	$pdf->Cell(0,10,$tres_uno.utf8_decode($datos["resultado"][0]->Mov_Observacion),0, 2 ,'L',false);
		$pdf->Ln(13);
		//LINEA 4
		$cuatro_uno ="                         " ;
		$pdf->Cell(0,10,$cuatro_uno.$this->convertir_a_letras(floatval( $datos["resultado"][0]->Mov_Monto) ) ,0, 2 ,'L',false);
		$pdf->Ln(12);
		//LINEA 5
		$cinco_uno = "                         ";
		if($tipo == 1)
			$pdf->Cell(0,10,$cinco_uno.'Pago de impuesto por servicios de agua potable en domicilio',0, 2 ,'L',false);
		elseif($tipo == 4)
			$pdf->Cell(0,10,$cinco_uno.'Pago acuenta de deuda o impuesto de agua',0, 2 ,'L',false);
		$pdf->Ln(34);
		//LINEA 6
		$seis_uno = "                ";
		$seis_dos = "                                                                        ";
		$pdf->Cell(0,10,$cinco_uno.$this->arreglar_numero($datos["resultado"][0]->Mov_Monto).$seis_dos. $datos["nombre"] ,0, 2 ,'L',false);
		$pdf->Output();
	}

	public function crear_ot_nueva_conexion($id_cliente, $domicilio_sum,$id_conexion,$id_orden_recien_insertado,$razon_social)
	{
		$domicilio_sum = str_replace( "%20", " ", $domicilio_sum);
	  	$pdf = new eFPDF('P', 'pt');
		#Establecemos los márgenes izquierda, arriba y derecha: 
		$pdf->SetMargins(25, 20 , 20); 
		#Establecemos el margen inferior: 
		$pdf->SetAutoPageBreak(true,15);  
	   
		$pdf->AddPage();
		$pdf->Image(base_url().'img/orden_de_trabajo.jpg', 0, 0 ,600, 850); 
		$pdf->SetFont('Arial','B',15);
		$pdf->SetTextColor(0, 0, 0);
		//LINEA 1
		$pdf->Ln(33);
		$uno_uno = "                     ";
		$pdf->Cell(0,10,$id_orden_recien_insertado.$uno_uno,0, 2 ,'R',false);
		//LINEA 2
		$pdf->Ln(16);
		$dos_uno = "                                                                                                 ";
		//
		$dos_dos = " del ";
		//
		$dos_tres = " de ";
		$pdf->Cell(0,10,$dos_uno.date("d").$dos_dos.date("m").$dos_tres.date("Y"),0, 2 ,'L',false);
		//LINEA  3 
		$pdf->Ln(69);
		$tres_uno = "                            ";
		$pdf->Cell(0,10,$tres_uno.utf8_decode("Instalación del medidor en domicilio con nueva conexión"),0, 2 ,'L',false);
		$pdf->Ln(12);
		// //LINEA 4
		$cuatro_uno ="                   " ;
		$cuatro_tres ="                                                                            " ;

		$data2 = [];
		$data2[1] = array(utf8_decode($razon_social), $id_conexion);
		$pdf->tabla_nombre_cliente_orden_trabajo($data2 ,0, 176  );
		//    $pdf->Cell(0,10,$cuatro_uno.utf8_decode($razon_social).$cuatro_tres.$id_conexion,0, 2 ,'L',false);
		$pdf->Ln(13);
		// //LINEA 5
		$cinco_uno = "                     ";
		$pdf->Cell(0,10,$cinco_uno.$domicilio_sum,0, 2 ,'L',false);
		$pdf->Ln(90);
		// //LINEA 6
		 $seis_uno = "  ";
		 $seis_dos = "                                                                     ";
		$pdf->Output();

	}


	function tabla_de_movimientos_diarios($header, $data, $x = 0, $y = 0) {
		// Cabecera
		$this->Cell(60,25,$header[0],1);
		$this->Cell(300,25,$header[1],1);
		$this->Cell(90,25,$header[2],1);
		$this->Cell(90,25,$header[3],1);

		// foreach($header as $col)
		// 	$this->Cell(130,25,$col,1);
		$this->Ln();
		// Datos
		$columna = 0;
		foreach($data as $row)
		{
			$this->Cell(60,25,$row[0],1,0,'L');
			$this->Cell(300,25,utf8_decode($row[1]),1,0,'L');
			if($row[2] != null) //significa entrada de dinero
				if(strpos($row[2], '|') === false)
					$this->Cell(90,25,"$ " .$this->arreglar_numero($row[2]),1,0,'R');
				else
					$this->Cell(90,25,$row[2],1,0,'L');
			else
				$this->Cell(90,25,' ',1,0,'L');
			if($row[3] != null)
				$this->Cell(90,25,"$ " .$this->arreglar_numero($row[3]),1,0,'R');
			else
				$this->Cell(90,25,' ',1,0,'L');

			// foreach($row as $col)
			// 	$this->Cell(130,25,$col,1);
			$this->Ln();
		}
	}


	function tabla_de_movimientos_diarios_totales($t_debe, $t_haber,$saldo, $tarifa, $sin_tarifa, $x = 0, $y = 0) {
		$this->Ln();
		$this->Cell(90,25,"TOTAL  ",1,0,'C');
		$this->Cell(80,25,"$ ".$this->arreglar_numero($t_debe),1,0,'C');
		$this->Cell(80,25,"$ ".$this->arreglar_numero($t_haber),1,0,'C');
		$this->Ln();
		$this->Cell(90,25,"Detalle  ",1,0,'C');
		$this->Cell(80,25,"$ ". $sin_tarifa. " | $ ".$tarifa,1,0,'C');
		$this->Ln();
		$this->Cell(90,25,"SALDO  ",1,0,'C');
		$this->Cell(160,25,"$ ".$this->arreglar_numero($saldo),1,0,'C');
		$this->Ln();
	}




	function tabla_datos_historial($header, $datos, $x = 0, $y = 0) {
		// Cabecera
		$this->Cell(60,25,$header[0],1);
		$this->Cell(60,25,$header[1],1);
		$this->Cell(60,25,$header[2],1);
		$this->Cell(60,25,$header[3],1);
		$this->Cell(60,25,$header[4],1);
		$this->Cell(60,25,$header[5],1);
		$this->Cell(60,25,$header[6],1);
		$this->Cell(60,25,$header[7],1);
		$this->Cell(60,25,$header[8],1);
		$this->Cell(60,25,$header[9],1);
		$this->Cell(60,25,$header[10],1);
		$this->Ln();
		// $pdf->SetFont('Arial','B',9);
		// 	$pdf->SetTextColor(0, 0, 0);

		foreach($datos as $row)
		{
		//	var_dump($row->id);
				
			$this->Cell(60,25,$row->id,1,0,'L');  //0
			$this->Cell(60,25,$row->Factura_Periodo,1,0,'L');//1
			$this->Cell(60,25,$row->monto,1,0,'L'); //2 
			if($row->Bonificacion_Monto != NULL)
				$this->Cell(60,25,$row->Bonificacion_Monto,1,0,'L'); //3
			else $this->Cell(60,25,"Sin",1,0,'L'); //3
			$this->Cell(60,25,$row->Conexion_Deuda,1,0,'L');//4
			$this->Cell(60,25,$row->PlanPago_MontoCuota,1,0,'L');//5
			$this->Cell(60,25,$row->PlanMedidor_MontoCuota,1,0,'L');//6
			$this->Cell(60,25,$row->Medicion_Actual,1,0,'L');//7
			$this->Cell(60,25,$row->Medicion_Anterior,1,0,'L');//8
			$this->Cell(60,25,$row->Medicion_Excedente,1,0,'L');//9
			$this->Cell(60,25,$row->Medicion_Importe,1,0,'L');//10

			// $this->Cell(60,25,"algo",1,0,'L');  //0
			// $this->Cell(60,25,"algo",1,0,'L');//1
			// $this->Cell(60,25,"algo",1,0,'L'); //2 
			// $this->Cell(60,25,"Sin",1,0,'L'); //3
			// $this->Cell(60,25,"algo ! ",1,0,'L');//4
			// $this->Cell(60,25,"algo ! ",1,0,'L');//5
			// $this->Cell(60,25,"algo ! ",1,0,'L');//6
			// $this->Cell(60,25,"algo ! ",1,0,'L');//7
			// $this->Cell(60,25,"algo ! ",1,0,'L');//8
			// $this->Cell(60,25,"algo ! ",1,0,'L');//9
			// $this->Cell(60,25,"algo ! ",1,0,'L');//10
			$this->Ln();
		}
		//die();
	}

	function tabla_morosos($header, $datos, $x = 0, $y = 0) {
		// Cabecera
		$this->Cell(30,25,$header[0],1);
		$this->Cell(90,25,$header[1],1);
		$this->Cell(190,25,$header[2],1);
		$this->Cell(90,25,$header[3],1);
		$this->Ln();
		// $pdf->SetFont('Arial','B',9);
		// 	$pdf->SetTextColor(0, 0, 0);
		$orden=0;
		if(sizeof($datos) >= 2)
			foreach($datos as $row)
			{
			//	var_dump($row->id);
				$orden++;	
				$this->Cell(30,25,$orden,1,0,'L');  //0
				$this->Cell(90,25,$row->Conexion_Id,1,0,'L');  //0
				$this->Cell(190,25,$row->Cli_RazonSocial,1,0,'L');//2
				$this->Cell(90,25,number_format(floatval($row->Conexion_Deuda), 2, '.', '')  ,1,0,'L');//1
				$this->Ln();
			}
		else
		{
			$orden++;	
				$this->Cell(30,25,$orden,1,0,'L');  //0
				$this->Cell(90,25,$datos->Conexion_Id,1,0,'L');  //0
				$this->Cell(190,25,$datos->Cli_RazonSocial,1,0,'L');//2
				$this->Cell(90,25,number_format(floatval($datos->Conexion_Deuda), 2, '.', '')  ,1,0,'L');//1
				$this->Ln();

		}
		//die();
	}
	function tabla_pp($header, $datos, $x = 0, $y = 0) {
		// Cabecera
		$this->Cell(30,25,$header[0],1);
		$this->Cell(90,25,$header[1],1);
		$this->Cell(190,25,$header[2],1);
		$this->Cell(90,25,$header[3],1);
		$this->Cell(90,25,$header[4],1);
		$this->Cell(90,25,$header[5],1);
		$this->Ln();
		// $pdf->SetFont('Arial','B',9);
		// 	$pdf->SetTextColor(0, 0, 0);
		//var_dump(sizeof($datos));
		$orden=0;
		if(sizeof($datos) >= 2)
			foreach($datos as $row)
			{
				$orden++;	
				$this->Cell(30,25,$orden,1,0,'L');  //1
				$this->Cell(90,25,$row->Conexion_Id,1,0,'L');  //2
				$this->Cell(190,25,$row->Cli_RazonSocial,1,0,'L');//3
				$this->Cell(90,25,$row->Factura_PP_Cant_Cuotas ,1,0,'L');//4
				$this->Cell(90,25,$row->Factura_PP_Cuota_Actual ,1,0,'L');//5
				$this->Cell(90,25,number_format(floatval($row->Factura_PPC_Precio), 2, '.', '')  ,1,0,'L');//6
				$this->Ln();
			}
		//die();
		else
		{
			$orden++;	
			$this->Cell(30,25,$orden,1,0,'L');  //1
			$this->Cell(90,25,$datos->Conexion_Id,1,0,'L');  //2
			$this->Cell(190,25,$datos->Cli_RazonSocial,1,0,'L');//3
			$this->Cell(90,25,$datos->Factura_PP_Cant_Cuotas ,1,0,'L');//4
			$this->Cell(90,25,$datos->Factura_PP_Cuota_Actual ,1,0,'L');//5
			$this->Cell(90,25,number_format(floatval($datos->Factura_PPC_Precio), 2, '.', '')  ,1,0,'L');//6
			$this->Ln();
		}

	}



	function tabla_datos_ot_terminadas($header, $datos, $x = 0, $y = 0) {
		// Cabecera
		$this->Cell(50,25,$header[0],1);
		$this->Cell(60,25,$header[1],1);
		$this->Cell(105,25,$header[2],1);
		$this->Cell(105,25,$header[3],1);
		$this->Cell(70,25,$header[4],1);
		$this->Cell(90,25,$header[5],1);
		$this->Cell(90,25,$header[6],1);
		$this->Ln();
		foreach($datos as $fila)
		{
			foreach ($fila as $row) {
				$this->Cell(50,25,$row->OrdenTrabajo_Id,1,0,'L');  //0
				$this->Cell(60,25,$row->OrdenTrabajo_Tecnico,1,0,'L');//1


				$this->Cell(105,25,$row->OrdenTrabajo_FechaInicio,1,0,'L'); //2 OrdenTrabajo_Cliente
				$this->Cell(105,25,$row->OrdenTrabajo_FechaFin,1,0,'L'); //3

				$inicio_d = new DateTime($row->OrdenTrabajo_FechaInicio);
				$fin_d = new DateTime($row->OrdenTrabajo_FechaFin);

				$inicio = $inicio_d->format('Y-m-d');
				$fin = $fin_d->format('Y-m-d');
				$break_1_start = DateTime::createFromFormat('Y-m-d',$inicio);
				$break_1_ends = DateTime::createFromFormat('Y-m-d', $fin);
				//var_dump($break_1_start);die();
				$dias = $break_1_start->diff($break_1_ends);
				$this->Cell(70,25,$dias->format('%d').utf8_decode(" días"),1,0,'L');//4$row->OrdenTrabajo_Cliente
				$this->Cell(90,25,$row->OrdenTrabajo_NConexion,1,0,'L');//5
				$this->Cell(90,25,$row->OrdenTrabajo_Estado,1,0,'L');//6
				$this->Ln();
			}
		}
	}


	public function crear_balance_diario($movimientos, $total_ingreso, $total_egreso, $codigos)
	{
		$codigos = $codigos["codigos"];
		//	var_dump($codigos["codigos"]);die();
		$pdf = new eFPDF('P', 'pt');
		$pdf->AddPage();
		$pdf->Image(base_url().'img/Balance_diario.jpg', 0, 0 ,600, 840); 
		$pdf->SetFont('Arial','B',10);
		 $pdf->SetTextColor(0, 0, 0);
		$pdf->SetMargins(17, 40 , 20); 
		$pdf->SetAutoPageBreak(true,15); 
		$this->SetX(10);
		$pdf->Ln(28);
		$fecha_ins = date("d/m/Y");
		$fecha_time = date("H:i:s");
		$uno_uno ="  ";
		$pdf->Cell(0,1,$fecha_ins.$uno_uno."           ",0, 1 ,'R',false);
		$pdf->Ln(36);
		$pdf->Cell(0,1,$fecha_time.$uno_uno."               ",0, 1 ,'R',false);
		$pdf->Ln(76);
		$cantidad_de_lineas = 0;

		$header = array('Codigo', 'Cuentas', 'Debe', 'Haber');
		$data = [];
		$indice = 0;
		$total_tarifas = 0;
		$total_sin_tarifas = 0;
		foreach ($movimientos as $key) 
		{
			if ( ($key->Mov_Tipo == 1) || ($key->Mov_Tipo == 3) )
			{
				//var_dump(substr($key->Mov_Observacion, 0, 7));die();
				if(isset($key->Mov_Codigo) && ($key->Mov_Codigo != NULL) )
					if (($key->Mov_Codigo == 3) && ( substr($key->Mov_Observacion, 0, 7) == "Pago Bo"))// significa q es un pago de una boleta
						{
							$sin_tarifa =  floatval($key->Mov_Monto) - floatval($key->Factura_CuotaSocial);
							$total_sin_tarifas += $sin_tarifa;
							$tarifa = floatval($key->Factura_CuotaSocial);
							$total_tarifas += $tarifa;
							$poner = number_format($tarifa , 2, '.', '') ."  |  ".number_format( $sin_tarifa , 2, '.', '');
						//	var_dump($sin_tarifa, $tarifa, $key->Factura_TarifaSocial, $key->Factura_Id, $poner);die();
							$data[$indice ] = array($key->Mov_Id,$key->Mov_Observacion. " * ".$key->Mov_Conexion_Id. " * ".$codigos[$key->Mov_Codigo]["numero"]. " * ".$codigos[$key->Mov_Codigo]["nombre"], $poner , null);
						}
						//Mov_Conexion_Id
					else
						$data[$indice ] = array($key->Mov_Id,$key->Mov_Observacion." * ".$key->Mov_Conexion_Id. " * ".$codigos[$key->Mov_Codigo]["numero"]. " * ".$codigos[$key->Mov_Codigo]["nombre"],number_format(floatval($key->Mov_Monto), 2, '.', '') , null);
				else 
					$data[$indice ] = array($key->Mov_Id,$key->Mov_Observacion. " * S/C",number_format(floatval($key->Mov_Monto), 2, '.', '') , null);
			}
			else
				if(isset($key->Mov_Codigo) && ($key->Mov_Codigo != NULL)  && ($key->Mov_Codigo != 0) )
					$data[$indice ] = array($key->Mov_Id,$key->Mov_Observacion. " * ".$codigos[$key->Mov_Codigo]["numero"]. " * ".$codigos[$key->Mov_Codigo]["nombre"],  null , number_format(floatval($key->Mov_Monto), 2, '.', ''));	
				else
					$data[$indice ] = array($key->Mov_Id,$key->Mov_Observacion. " * S/C", null ,number_format(floatval($key->Mov_Monto), 2, '.', ''));
			$indice ++;
		}

		$pdf->tabla_de_movimientos_diarios($header, $data, 0, 20);
		$pdf->Ln(30);
		$saldo = floatval($total_ingreso) - floatval($total_egreso);
		$pdf->tabla_de_movimientos_diarios_totales(number_format(floatval($total_ingreso), 2, '.', '') , number_format(floatval($total_egreso), 2, '.', '') , number_format(floatval($saldo), 2, '.', '') , $total_sin_tarifas, $total_tarifas , 12, 70);
		$pdf->Output();
	}

	public function crear_recibo($id)
	{
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		
		$fontSize = 15;
		$marge    = 3;   // between barcode and hri in pixel
		$x        = 232;  // barcode center
		$y        = 600;  // barcode center
		$height   = 42;   // barcode height in 1D ; module size in 2D
		$width    = 1;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		
		$code     = '183956089415'; // barcode, of course ;)
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		
		
		// -------------------------------------------------- //
		//            ALLOCATE FPDF RESSOURCE
		// -------------------------------------------------- //
		  
		$pdf = new eFPDF('P', 'pt');
		$pdf->AddPage();
		//$pdf->Image('../images/'.$image, 0, 0, $size[0], $size[1]); 
		$pdf->Image(base_url().'/img/Balance_diario.jpg', 0, 0 ,575, 750); 
		
		 
		// -------------------------------------------------- //
		//                      BARCODE
		// -------------------------------------------------- //
		
		$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		
		// -------------------------------------------------- //
		//                      HRI
		// -------------------------------------------------- //
		
		$pdf->SetFont('Arial','B',$fontSize);
		$pdf->SetTextColor(0, 0, 0);
		$len = $pdf->GetStringWidth($data['hri']);
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
		$this->SetX(0);
		$pdf->Ln(18);
		$pdf->Cell(0,10,'                                                                                                                                25    07    2017        ',0, 2 ,'R',false);
		$pdf->Ln(152);
		$pdf->Cell(0,10,'        018         Pago servicio de agua potable mes 07',0, 2 ,'L',false);
		$pdf->Ln(300);
		$pdf->Cell(0,10,'        018         Pago servicio de agua potable mes 07',0, 2 ,'L',false);
		$pdf->Output();
	}
	public function crear_recibo_ingreso_por_medidor_nuevo($datos)
	{
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		/*
		http://localhost/codeigniter/imprimir/crear_recibo_de_pago_medidor_nuevo/512/Lionel%20Messi/1000

		parametros traidos
		
		$datos["nombre"]
		$datos["id_recibo"]
		$datos["razon_social"]
		$datos["precio"]
		*/
		// -------------------------------------------------- //
		//                  PROPERTIES
		// -------------------------------------------------- //
		$fontSize = 15;
		$marge    = 3;   // between barcode and hri in pixel
		$x        = 300;  // barcode center
		$y        = 170;  // barcode center
		$height   = 42;   // barcode height in 1D ; module size in 2D
		$width    = 1;    // barcode height in 1D ; not use in 2D
		$angle    = 0;   // rotation in degrees
		
		$code     = '183956089415'; // barcode, of course ;)
		$type     = 'ean13';
		$black    = '000000'; // color in hexa
		// -------------------------------------------------- //
		//            ALLOCATE FPDF RESSOURCE
		// -------------------------------------------------- //
		$pdf = new eFPDF('P', 'pt');
		#Establecemos los márgenes izquierda, arriba y derecha: 
		$pdf->AddPage();
		$pdf->SetMargins(25, 20 , 20); 
		#Establecemos el margen inferior: 
		$pdf->SetAutoPageBreak(true,15);  
	   
		$pdf->Image(base_url().'/img/recibo.jpg', 0, 0 ,600, 200); 
		// -------------------------------------------------- //
		//                      BARCODE
		// -------------------------------------------------- //
		$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		// -------------------------------------------------- //
		//                      HRI
		// -------------------------------------------------- //
		$pdf->SetFont('Arial','B',$fontSize);
		$pdf->SetTextColor(0, 0, 0);
		$len = $pdf->GetStringWidth($data['hri']);
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
		//LINEA 1
		$uno_uno = "              ";
		$pdf->Cell(0,10,$datos["id_recibo"].$uno_uno,0, 2 ,'R',false);
		//$pdf->Output();
		//LINEA 2
		$pdf->Ln(14);
		$dos_uno = "                                                       ";
		//
		$dos_dos = "                       ";
		//
		$dos_tres = "                         ";
		$dos_cuatro = "         ";
		$pdf->Cell(0,10,$dos_uno.date("d").$dos_tres.$this->meses[date("m")].$dos_cuatro.date("Y"),0, 2 ,'R',false);
		//LINEA  3 
		$pdf->Ln(18);
		$tres_uno = "                                  ";
		$pdf->Cell(0,10,$tres_uno.utf8_decode($datos["razon_social"]),0, 2 ,'L',false);
		$pdf->Ln(14);
		//LINEA 4
		$cuatro_uno ="                        " ;
		$cuatro_dos= $this->convertir_a_letras($datos["precio"]);
		if(strlen($cuatro_dos) > 60)
		{
			$cuatro_uno ="                             " ;
			$pdf->SetFont('Arial','B',13);
		}
		$pdf->Cell(0,10,$cuatro_uno.utf8_decode($cuatro_dos),0, 2 ,'L',false);
		if(strlen($cuatro_dos) > 62)
			$pdf->SetFont('Arial','B',$fontSize);
		$pdf->Ln(11);
		//LINEA 5
		$cinco_uno = "                         ";
		$pdf->Cell(0,10,$cinco_uno.'Pago de medidor de agua y colocacion del mismo en domicilio',0, 2 ,'L',false);
		$pdf->Ln(33);
		//LINEA 6
		$seis_uno = "                ";
		$seis_dos = "                                                                        ";
		$pdf->Cell(0,10,$cinco_uno.$datos["precio"].$seis_dos. $datos["nombre"] ,0, 2 ,'L',false);
		$pdf->Output();
	}
	// FUNCIONES DE CONVERSION DE NUMEROS A LETRAS.
	 
	public function centimos()
	{
		global $importe_parcial;

	 
		$importe_parcial = number_format(floatval($importe_parcial), 2, ".", "") * 100;
	 
		if ($importe_parcial > 0)
			$num_letra = " con ".$this->decena_centimos($importe_parcial);
		else
			$num_letra = "";
	 
		return $num_letra;
	}
	 
	public function unidad_centimos($numero)
	{
		$numero = intval($numero);
		switch ($numero)
		{
			case 9:
			{
				$num_letra = "nueve centavos";
				break;
			}
			case 8:
			{
				$num_letra = "ocho centavos";
				break;
			}
			case 7:
			{
				$num_letra = "siete centavos";
				break;
			}
			case 6:
			{
				$num_letra = "seis centavos";
				break;
			}
			case 5:
			{
				$num_letra = "cinco centavos";
				break;
			}
			case 4:
			{
				$num_letra = "cuatro centavos";
				break;
			}
			case 3:
			{
				$num_letra = "tres centavos";
				break;
			}
			case 2:
			{
				$num_letra = "dos centavos";
				break;
			}
			case 1:
			{
				$num_letra = "un centavo";
				break;
			}
			default:
			{
				//var_dump("El valor de numero es:".$numero);die();
				$num_letra = "";
				break;
			}
		}
		return $num_letra;
	}
	 
	public function decena_centimos($numero)
	{
		if ($numero >= 10)
		{
			if ($numero >= 90 && $numero <= 99)
			{
				  if ($numero == 90)
					  return "noventa centavos";
				  else if ($numero == 91)
					  return "noventa y un centavos";
				  else
					  return "noventa y ".$this->unidad_centimos($numero - 90);
			}
			if ($numero >= 80 && $numero <= 89)
			{
				if ($numero == 80)
					return "ochenta centavos";
				else if ($numero == 81)
					return "ochenta y un centavos";
				else
					return "ochenta y ".$this->unidad_centimos($numero - 80);
			}
			if ($numero >= 70 && $numero <= 79)
			{
				if ($numero == 70)
					return "setenta centavos";
				else if ($numero == 71)
					return "setenta y un centavos";
				else
					return "setenta y ".$this->unidad_centimos($numero - 70);
			}
			if ($numero >= 60 && $numero <= 69)
			{
				if ($numero == 60)
					return "sesenta centavos";
				else if ($numero == 61)
					return "sesenta y un centavos";
				else
					return "sesenta y ".$this->unidad_centimos($numero - 60);
			}
			if ($numero >= 50 && $numero <= 59)
			{
				if ($numero == 50)
					return "cincuenta centavos";
				else if ($numero == 51)
					return "cincuenta y un centavos";
				else
					return "cincuenta y ".$this->unidad_centimos($numero - 50);
			}
			if ($numero >= 40 && $numero <= 49)
			{
				if ($numero == 40)
					return "cuarenta centavos";
				else if ($numero == 41)
					return "cuarenta y un centavos";
				else
					return "cuarenta y ".$this->unidad_centimos($numero - 40);
			}
			if ($numero >= 30 && $numero <= 39)
			{
				if ($numero == 30)
					return "treinta centavos";
				else if ($numero == 91)
					return "treinta y un centavos";
				else
					return "treinta y ".$this->unidad_centimos($numero - 30);
			}
			if ($numero >= 20 && $numero <= 29)
			{
				if ($numero == 20)
					return "veinte centavos";
				else if ($numero == 21)
					return "veintiun centavos";
				else
					return "veinti".$this->unidad_centimos($numero - 20);
			}
			if ($numero >= 10 && $numero <= 19)
			{
				if ($numero == 10)
					return "diez centavos";
				else if ($numero == 11)
					return "once centavos";
				else if ($numero == 11)
					return "doce centavos";
				else if ($numero == 11)
					return "trece centavos";
				else if ($numero == 11)
					return "catorce centavos";
				else if ($numero == 11)
					return "quince centavos";
				else if ($numero == 11)
					return "dieciseis centavos";
				else if ($numero == 11)
					return "diecisiete centavos";
				else if ($numero == 11)
					return "dieciocho centavos";
				else if ($numero == 11)
					return "diecinueve centavos";
			}
		}
		else
			return $this->unidad_centimos($numero);
	}
 
	public function unidad($numero)
	{
		switch ($numero)
		{
			case 9:
			{
				$num = "nueve";
				break;
			}
			case 8:
			{
				$num = "ocho";
				break;
			}
			case 7:
			{
				$num = "siete";
				break;
			}
			case 6:
			{
				$num = "seis";
				break;
			}
			case 5:
			{
				$num = "cinco";
				break;
			}
			case 4:
			{
				$num = "cuatro";
				break;
			}
			case 3:
			{
				$num = "tres";
				break;
			}
			case 2:
			{
				$num = "dos";
				break;
			}
			case 1:
			{
				$num = "uno";
				break;
			}
		}
		return $num;
	}
 
	public function decena($numero)
	{
		if ($numero >= 90 && $numero <= 99)
		{
			$num_letra = "noventa ";
	 
			if ($numero > 90)
				$num_letra = $num_letra."y ".$this->unidad($numero - 90);
		}
		else if ($numero >= 80 && $numero <= 89)
		{
			$num_letra = "ochenta ";
	 
			if ($numero > 80)
				$num_letra = $num_letra."y ".$this->unidad($numero - 80);
		}
		else if ($numero >= 70 && $numero <= 79)
		{
				$num_letra = "setenta ";
	 
			if ($numero > 70)
				$num_letra = $num_letra."y ".$this->unidad($numero - 70);
		}
		else if ($numero >= 60 && $numero <= 69)
		{
			$num_letra = "sesenta ";
	 
			if ($numero > 60)
				$num_letra = $num_letra."y ".$this->unidad($numero - 60);
		}
		else if ($numero >= 50 && $numero <= 59)
		{
			$num_letra = "cincuenta ";
	 
			if ($numero > 50)
				$num_letra = $num_letra."y ".$this->unidad($numero - 50);
		}
		else if ($numero >= 40 && $numero <= 49)
		{
			$num_letra = "cuarenta ";
	 
			if ($numero > 40)
				$num_letra = $num_letra."y ".$this->unidad($numero - 40);
		}
		else if ($numero >= 30 && $numero <= 39)
		{
			$num_letra = "treinta ";
	 
			if ($numero > 30)
				$num_letra = $num_letra."y ".$this->unidad($numero - 30);
		}
		else if ($numero >= 20 && $numero <= 29)
		{
			if ($numero == 20)
				$num_letra = "veinte ";
			else
				$num_letra = "veinti".$this->unidad($numero - 20);
		}
		else if ($numero >= 10 && $numero <= 19)
		{
			switch ($numero)
			{
				case 10:
				{
					$num_letra = "diez ";
					break;
				}
				case 11:
				{
					$num_letra = "once ";
					break;
				}
				case 12:
				{
					$num_letra = "doce ";
					break;
				}
				case 13:
				{
					$num_letra = "trece ";
					break;
				}
				case 14:
				{
					$num_letra = "catorce ";
					break;
				}
				case 15:
				{
					$num_letra = "quince ";
					break;
				}
				case 16:
				{
					$num_letra = "dieciseis ";
					break;
				}
				case 17:
				{
					$num_letra = "diecisiete ";
					break;
				}
				case 18:
				{
					$num_letra = "dieciocho ";
					break;
				}
				case 19:
				{
					$num_letra = "diecinueve ";
					break;
				}
			}
		}
		else
			$num_letra = $this->unidad($numero);
	 
		return $num_letra;
	}
 
	public function centena($numero)
	{
		if ($numero >= 100)
		{
			if ($numero >= 900 & $numero <= 999)
			{
				$num_letra = "novecientos ";
	 
				if ($numero > 900)
					$num_letra = $num_letra.$this->decena($numero - 900);
			}
			else if ($numero >= 800 && $numero <= 899)
			{
				$num_letra = "ochocientos ";
	 
				if ($numero > 800)
					$num_letra = $num_letra.$this->decena($numero - 800);
			}
			else if ($numero >= 700 && $numero <= 799)
			{
				$num_letra = "setecientos ";
	 
				if ($numero > 700)
					$num_letra = $num_letra.$this->decena($numero - 700);
			}
			else if ($numero >= 600 && $numero <= 699)
			{
				$num_letra = "seiscientos ";
	 
				if ($numero > 600)
					$num_letra = $num_letra.$this->decena($numero - 600);
			}
			else if ($numero >= 500 && $numero <= 599)
			{
				$num_letra = "quinientos ";
	 
				if ($numero > 500)
					$num_letra = $num_letra.$this->decena($numero - 500);
			}
			else if ($numero >= 400 && $numero <= 499)
			{
				$num_letra = "cuatrocientos ";
	 
				if ($numero > 400)
					$num_letra = $num_letra.$this->decena($numero - 400);
			}
			else if ($numero >= 300 && $numero <= 399)
			{
				$num_letra = "trescientos ";
	 
				if ($numero > 300)
					$num_letra = $num_letra.$this->decena($numero - 300);
			}
			else if ($numero >= 200 && $numero <= 299)
			{
				$num_letra = "doscientos ";
	 
				if ($numero > 200)
					$num_letra = $num_letra.$this->decena($numero - 200);
			}
			else if ($numero >= 100 && $numero <= 199)
			{
				if ($numero == 100)
					$num_letra = "cien ";
				else
					$num_letra = "ciento ".$this->decena($numero - 100);
			}
		}
		else
			$num_letra = $this->decena($numero);
	 
		return $num_letra;
	}
 
	public function cien()
	{
		global $importe_parcial;
	 
		$parcial = 0; $car = 0;
	 
		while (substr($importe_parcial, 0, 1) == 0)
			$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	 
		if ($importe_parcial >= 1 && $importe_parcial <= 9.99)
			$car = 1;
		else if ($importe_parcial >= 10 && $importe_parcial <= 99.99)
			$car = 2;
		else if ($importe_parcial >= 100 && $importe_parcial <= 999.99)
			$car = 3;
	 
		$parcial = substr($importe_parcial, 0, $car);
		$importe_parcial = substr($importe_parcial, $car);
	 
		$num_letra = $this->centena($parcial).$this->centimos();
	 
		return $num_letra;
	}
 
	public function cien_mil()
	{
		global $importe_parcial;
	 
		$parcial = 0; $car = 0;
	 
		while (substr($importe_parcial, 0, 1) == 0)
			$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	 
		if ($importe_parcial >= 1000 && $importe_parcial <= 9999.99)
			$car = 1;
		else if ($importe_parcial >= 10000 && $importe_parcial <= 99999.99)
			$car = 2;
		else if ($importe_parcial >= 100000 && $importe_parcial <= 999999.99)
			$car = 3;
	 
		$parcial = substr($importe_parcial, 0, $car);
		$importe_parcial = substr($importe_parcial, $car);
	 
		if ($parcial > 0)
		{
			if ($parcial == 1)
				$num_letra = "mil ";
			else
				$num_letra = $this->centena($parcial)." mil ";
		}
	 
		return $num_letra;
	}
 
	public function millon()
	{
		global $importe_parcial;
	 
		$parcial = 0; $car = 0;
	 
		while (substr($importe_parcial, 0, 1) == 0)
			$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	 
		if ($importe_parcial >= 1000000 && $importe_parcial <= 9999999.99)
			$car = 1;
		else if ($importe_parcial >= 10000000 && $importe_parcial <= 99999999.99)
			$car = 2;
		else if ($importe_parcial >= 100000000 && $importe_parcial <= 999999999.99)
			$car = 3;
	 
		$parcial = substr($importe_parcial, 0, $car);
		$importe_parcial = substr($importe_parcial, $car);
	 
		if ($parcial == 1)
			$num_letras = "un millón ";
		else
			$num_letras = $this->centena($parcial)." millones ";
	 
		return $num_letras;
	}
 
	public function convertir_a_letras($numero)
	{
		global $importe_parcial;
	 
		$importe_parcial = $numero;
	 	$num_letras = "cero";
		if ($numero < 1000000000)
		{
			if ($numero >= 1000000 && $numero <= 999999999.99)
				$num_letras = $this->millon().$this->cien_mil().$this->cien();
			else if ($numero >= 1000 && $numero <= 999999.99)
				$num_letras = $this->cien_mil().$this->cien();
			else if ($numero >= 1 && $numero <= 999.99)
				$num_letras = $this->cien();
			else if ($numero >= 0.01 && $numero <= 0.99)
			{
				if ($numero == 0.01)
					$num_letras = "un centavo";
				else
					$num_letras = $this->convertir_a_letras(($numero * 100)."/100")." centavos";
			}
		}
		return $num_letras;
	}
	public function crear_contratro_nueva_conexion($nombre,$dni,$id_conexion,$id_medidor,$domicilio,$id_cliente)
	{
		
		$dia = date("d");
		$mes = date("m");
		$anio = date("Y");
		$sangria = "                           ";
		$sangriaFecha = "    ";
		$sangriaTexto = "                          ";
		$pdf = new eFPDF('P', 'pt');
		$pdf->AddPage();
		
		$pdf->SetFont('Arial','B',28);
		$pdf->SetTextColor(0, 0, 0);
		$this->SetX(0);
		$pdf->Ln(35);
		$pdf->Cell(0,10,'UNION VECINAL VILLA ELISA',0, 2 ,'L',false);
		$pdf->Ln(20);   
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(0,10,utf8_decode('Personería Jurídica N= 2683-G-68'),0, 2 ,'L',false);
		$pdf->Ln(10);   
		$pdf->Cell(0,10,utf8_decode('Ing. Marcos Zalazar 458 - Villa Elisa '),0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->Cell(0,10,utf8_decode('Villa Aberastaín - San Juan'),0, 2 ,'L',false);
		$pdf->Ln(20);
		$pdf->Cell(0,10,utf8_decode('       SOLICITUD DE SOCIO N° ').$id_cliente,0, 2 ,'R',false);
		$pdf->Ln(20);
		$pdf->SetFont('Arial','BI',12);
		$pdf->Cell(0,10, $sangriaFecha. ' San Juan, dia ' .$dia.' del mes '.$this->meses[$mes]. utf8_decode(" del año ").$anio. '',0,2,'R',false);
		$pdf->Ln(20);
		$pdf->SetFont('Arial','I',12);
		$pdf->Cell(0,10,utf8_decode('Señor UNION VECINAL VILLA ELISA'),0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->Cell(0,10,utf8_decode('Presente:'),0, 2 ,'L',false);
		 $pdf->Ln(10);
		  //$pdf->SetFont('Arial','N',15);
		$pdf->Cell(0,10,utf8_decode('De mi consideración: '),0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->Cell(0,10,$sangriaTexto.'El que suscribe: '.utf8_decode($nombre).', con DNI: '.utf8_decode($dni).', con domicilio: ',0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->Cell(0,10,utf8_decode($domicilio).utf8_decode(', San Juan.'),0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->Cell(0,10, utf8_decode('tiene el agrado de dirigirse a Ud. para solicitarle asociarse a la Union Vecinal que preside,'),0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->Cell(0,10,utf8_decode(' para lo cual declaro conocer y aceptar los Estatutos Sociales, Reglamentos y disposiciones'),0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->Cell(0,10,utf8_decode(' emanadas de las Asambleas.'),0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->Cell(0,10,$sangriaTexto.utf8_decode('Sin otro particular, saludo a Ud. y demás miembros de la Comisión '),0, 2 ,'L',false);
		$pdf->Ln(10);
		$pdf->Cell(0,10,utf8_decode('Directiva muy cordialmente.'),0, 2 ,'L',false);
		$pdf->Ln(90);
		$pdf->Cell(0,10, 'FIRMA : _________________________             ',0,10,'R',false);
		$pdf->Ln(50);
		$pdf->Cell(0,10, 'ACLARACION: _________________________             ',0,10,'R',false);
		$pdf->Output();
	}

	public function probando_tabla_por_lote_nueva($datos)
	{
		//var_dump($datos);die();
		$inicio_hoja_nueva = true;
		$pdf = new eFPDF('P');
		$vueltas = 0;
		foreach ($datos["resultado"] as $key) 
		{
			if($inicio_hoja_nueva)
			{
				$coordinada_x_1 =9;
				$coordinada_y_1 = 38;
				$coordinada_x_2 = 110;
				$coordinada_y_2 = 39;
				$coordinada_x_3 = 110 ;
				$coordinada_y_3 = 100;
				$coordinada_x_4 = 110;
				$coordinada_y_4 = 52;
				$coordinada_x_5 = 110 ;
				$coordinada_y_5 = 123;
				$coordinada_x_6 = 110;
				$coordinada_y_6 = 109;
				$coordinada_x_7 = 9;
				$coordinada_y_7 = 70;
				$coordinada_x_8 = 56;
				$coordinada_y_8 = 99;
				$coordinada_x_9 = 9 ;
				$coordinada_y_9 = 48 ;
				$coordinada_x_10 = 9 ;
				$coordinada_y_10 = 83 ;
				$coordinada_x_11= null ;
				$coordinada_y_11 = null ;
				$aux_inicio = 0;
			}
			else 
			{
				$shifteo = 147;
				$coordinada_x_1 =9 ;
				$coordinada_y_1 = 46 + $shifteo;
				$coordinada_x_2 = 110 ;
				$coordinada_y_2 = 39 + $shifteo;
				$coordinada_x_3 = 110 ;
				$coordinada_y_3 = 100 + $shifteo;
				$coordinada_x_4 = 110 ;
				$coordinada_y_4 = 54 + $shifteo;
				$coordinada_x_5 = 110 ;
				$coordinada_y_5 = 123 + $shifteo;
				$coordinada_x_6 = 110 ;
				$coordinada_y_6 = 109 + $shifteo;
				$coordinada_x_7 = 9 ;
				$coordinada_y_7 = 77 + $shifteo;
				$coordinada_x_8 = 56 ;
				$coordinada_y_8 = 99 + $shifteo;
				$coordinada_x_9 = 9 ;
				$coordinada_y_9 = 54 + $shifteo;
				$coordinada_x_10 = 9 ;
				$coordinada_y_10 = 89 + $shifteo;
				$coordinada_x_11= 80 ;
				$coordinada_y_11 = 276 ;
				$aux_inicio = 1 ;
			}
				$code  = $this->calcular_codigo_barra_agua($key->Conexion_Id,$key->id);
				$this->poner_codigo_barra($key->id_factura,$coordinada_x_11,$coordinada_y_11,$pdf,$aux_inicio);
				//$valores = $this->calcular_valores_a_facturar($key,$datos["configuracion"], $key);
				//var_dump($valores);die();
				//  $vueltas++;
				// if($vueltas >= 2)
				// {
				//      continue;
				// }
				//  var_dump($valores["total"]);
				//  continue;
				// die();
				//TABLA 1
				if(( $key->Conexion_Categoria == 1) || ($key->Conexion_Categoria == "Familiar"))
				   $tipo_conexion =  "Familiar";
				else $tipo_conexion =  "Comercial";
				$header = array('Mes', 'Año', 'Categoria', 'Conexión', 'Iva', 'Factura');
				$data = [];
				//$data[0] = array("10", "2017", "Familiar", '56', 'Excento', '21220101');


				$data[0] = array($key->Medicion_Mes,$key->Medicion_Anio, $tipo_conexion, $key->Conexion_Id, 'Excento', $key->id);
				$pdf->tabla_datos_facturas($header, $data,  $coordinada_x_1,  $coordinada_y_1);
				//TABLA 2 FECHAS Y VECIMIENTOS
				$header = array('Mes', 'Año', 'Categoria', 'Conexión', 'Factura');
				$data1 = [];
				$data1[0] = array($key->Medicion_Mes,$key->Medicion_Anio,  $tipo_conexion, $key->Conexion_Id, $key->id);
				$pdf->tabla_datos_factura_1($header, $data1 , $coordinada_x_2, $coordinada_y_2  );
				$pdf->tabla_datos_factura_1($header, $data1 , $coordinada_x_3 ,  $coordinada_y_3);
				$pdf->Ln(5);






				
				//TABLA 3
				$nuevafetytycha_dos = strtotime ( $key->Factura_Vencimiento1 ) ;
				$fecha = date ( 'Y-m-d' , $nuevafetytycha_dos );
				//$fecha = date('Y-m-d');
				$fecha [strlen($fecha)-1] = "1";
				$fecha [strlen($fecha)-2] = "0";
				$nuevafecha =  strtotime ( $fecha ) ;
				$nuevafecha = date ( 'd/m/y' , $nuevafecha );
				$fecha = date('Y-m-d');
				$fecha [strlen($fecha)-1] = "1";
				$fecha [strlen($fecha)-2] = "0";
				$nuevafecha_dos = strtotime ( $key->Factura_Vencimiento1 ) ;
				$nuevafecha_dos = date ( 'd/m/y' , $nuevafecha_dos );
				 $fecha = date('Y-m-d');
				$fecha [strlen($fecha)-1] = "1";
				$fecha [strlen($fecha)-2] = "0";
				$nuevafecha_tres = strtotime ( '+1 day' , strtotime ( $key->Factura_Vencimiento1 ) ) ;
				$nuevafecha_tres = date ( 'd/m/y' , $nuevafecha_tres );
				$fecha = date('Y-m-d');
				$fecha [strlen($fecha)-1] = "1";
				$fecha [strlen($fecha)-2] = "0";
				$nuevafecha_cuatro = strtotime ( $key->Factura_Vencimiento2 );
				$nuevafecha_cuatro = date ( 'd/m/y' , $nuevafecha_cuatro );
				$header = array('1° Vencimiento', '2° Vencimiento', 'Total');
				$data2 = [];
				//$aux_total = $valores["total"];
				$data2[0] = array("Vto 1",$nuevafecha,$nuevafecha_dos, "$ ".number_format(floatval($key->Factura_Total), 2, '.', ''));
				$data2[1] = array("Vto 2",$nuevafecha_tres, $nuevafecha_cuatro, "$ ".number_format(floatval($key->Factura_Vencimiento2_Precio), 2, '.', '')); 
				$pdf->tabla_vencimientos_1($header, $data2 ,$coordinada_x_4 ,  $coordinada_y_4);
				$pdf->tabla_vencimientos_1($header, $data2 , $coordinada_x_5 , $coordinada_y_5  );
				$pdf->Ln(5);
				//TABLA 4
				$datapersona2 = [];
				$datapersona2[0] = array("Nombre",  $key->Cli_RazonSocial, "N°" , $key->Cli_Id);
				$datapersona2[1] = array("Direccion", $key->Cli_DomicilioSuministro);
				$datapersona2[2] = array("CUIT", $key->Cli_Cuit);
				$pdf->tabla_datos_personales($datapersona2 , $coordinada_x_9 , $coordinada_y_9  );
				$data_linea_personal[0] = array("Socio", $key->Cli_RazonSocial, "N°" , $key->Cli_Id);
					$pdf->tabla_datos_personales_linea($data_linea_personal , $coordinada_x_6 , $coordinada_y_6  );
				$pdf->Ln(5);
				//TABLA 5
				$data4 = [];
				$header = array("Estado Medidor", "Consume en m3");
				$data4[1] = array("Anterior", "Actual", "Total", "Básico", "Excedente");
				$basico = $key->Medicion_Basico;
				$diferenncia = intval($key->Medicion_Actual)-intval($key->Medicion_Anterior);
				// if($diferenncia > 10)
				// 	$exxcedente = $diferenncia-$basico ;
				// else 
				// {
				// 	$exxcedente = 0;
				// }
				$data4[2] = array($key->Medicion_Anterior, $key->Medicion_Actual,$diferenncia, $basico, $key->Medicion_Excedente );
				$pdf->tabla_detalle_consume($header, $data4 , $coordinada_x_7 , $coordinada_y_7  );
				$pdf->Ln(5);

				//TABLA 6
				$data5[0] = array("Deuda Anterior", "$".number_format(floatval($key->Factura_Deuda), 2, '.', ''));
				$data5[1] = array("Tarifa básica", "$".number_format(floatval($key->Factura_TarifaSocial), 2, '.', ''));
				$data5[2] = array("Excedente", "$".number_format(floatval($key->Medicion_Importe), 2, '.', ''));
				$data5[3] = array("Couta Social", "$".number_format(floatval($key->Factura_CuotaSocial), 2, '.', ''));
				if (($key->Factura_PM_Cuota_Actual == 0 ) ||  ($key->Factura_PM_Cuota_Actual == null ) )
					$aux_medidor_string = "$ 0,00";
				else $aux_medidor_string = "(".$key->Factura_PM_Cuota_Actual."/".$key->Factura_PM_Cant_Cuotas.") $ ".number_format(floatval($key->Factura_PM_Cuota_Precio), 2, '.', '');
				$data5[4] = array("Medidor", $aux_medidor_string );
				if (($key->Factura_PP_Cant_Cuotas == 0 ) ||  ($key->Factura_PP_Cant_Cuotas == null ) )
					$aux_planpago = "$ 0,00";
				else $aux_planpago = "(".$key->Factura_PP_Cuota_Actual."/".$key->Factura_PP_Cant_Cuotas.") $ ".number_format(floatval($key->Factura_PPC_Precio), 2, '.', ''); 
				$data5[5] = array("Plan de Pago", $aux_planpago);
				$data5[6] = array("Riego","$".number_format(floatval($key->Factura_Riego), 2, '.', ''));
				if( ($key->Factura_Multa != 0 ) && ($key->Factura_Multa > 0 ) && ($key->Factura_Multa != null ) && ($key->Factura_Multa != '' ) )
					$data5[7] = array("Multa","$".number_format(floatval($key->Factura_Multa), 2, '.', ''));
				$data5[8] = array("Subtotal","$ ".number_format(floatval($key->Factura_SubTotal), 2, '.', ''));
				$data5[9] = array("Pagos a cuenta","$ ".number_format(floatval($key->Factura_Acuenta), 2, '.', ''));
				//  if($key->Conexion_Deuda == 0)
				// 	$aux_bonificacion =  "$ ".$this->arreglar_numero ( (floatval ($key->Medicion_Importe) + floatval($tarifa_social)) * floatval(0.05) ) ;
				// else $aux_bonificacion = "$ 0.00";
				$data5[10] = array("Bonificacion", "$".number_format(floatval($key->Factura_Bonificacion), 2, '.', ''));
				$data5[11] = array("Total a Pagar", "$".number_format(floatval($key->Factura_Total), 2, '.', ''));
				$pdf->tabla_de_costos($data5,  $coordinada_x_10,  $coordinada_y_10);
				//
				$header = array( 'Vencimiento');
				$data2 = [];
				$data2[0] = array($nuevafecha,$nuevafecha_dos, "$".number_format(floatval($key->Factura_Total), 2, '.', ''));
				//$data2[1] = array($nuevafecha_tres, $nuevafecha_cuatro, "$".$this->arreglar_numero(floatval(.$key->Factura_Total) + floatval($aux_total)  * floatval(0.015) ));
				$data2[1] = array($nuevafecha_tres, $nuevafecha_cuatro, "$".$this->arreglar_numero($key->Factura_Vencimiento2_Precio) );
				$pdf->tabla_vencimientos_2($header, $data2 , $coordinada_x_8, $coordinada_y_8   );

				$pdf->Ln(5);
				if($inicio_hoja_nueva)
					$inicio_hoja_nueva= false;
				else $inicio_hoja_nueva=true;
		}
		$pdf->Output();
	}
  
}
?>



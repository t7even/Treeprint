<?php
	require_once('../tcpdf/config/lang/eng.php');
#	require_once('../inc/pdf_functions.php');
	require_once('../tcpdf/tcpdf.php');
	require_once('../inc/utf8_charmap.php');
	
	if(!$_GET['font']){
		header('Content-Type: text/html; charset=utf-8');
		exit('Bitte zu berechnenden Font über GET (font=) übergeben. (Font muss dazu im TCPDF-Fonts-Ordner abgelegt sein)');
	}else{
		$font = $_GET['font'];
	}
	
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

	// set font
	$pdf->SetFont($font, '', 10);
	
	// set units
	$pdf->setPageUnit('mm');
	
	header('Content-Type: text/html; charset=utf-8');
	print 'Datei "charWidth.php" mit folgendem Array ergänzen:<br/><br/>';
	print '<pre>';
	print "\$charWidth['".$font."'] = array(\n";

	foreach ($charmap as $val) {
		$width = $pdf->GetStringWidth($val[0], $font);
		print "\t'".$val[0]."' => ".$width.",\n";
	}

	print ");";
	print "</pre>";
?>


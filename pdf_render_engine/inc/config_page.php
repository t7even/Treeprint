<?php
// minimale Druckbogengroesse (A5)
	$page_minwidth	= 210; 
	$page_minheight	= 145; 

// maximale Druckbogengroesse (mehr nicht möglich. oder?)
	$page_maxwidth	= 50800; 
	$page_maxheight	= ($_GET['height']) ? $_GET['height'] : 941; 
#	$page_maxheight	= $page_maxheight; 


// Skalierungsfaktor (default 1 = 100%)
	$scaling = 1;


//------------------------------------------------------------------
// XML-Seitenlaengen berechnen
//------------------------------------------------------------------

	// Breite = Familienbreite Person #1
	$file_width = $family_maxwidth + $size['offset']['box']['x']; // Aussenabstaende;

	// Hoehe = Anzahl XML-Generationen x Elemente
	if(count($xml->generations->generation)%2){
		//Wenn Anzahl Generationen UNgerade
		$file_height = 	$size['margin']['top'] + $size['margin']['bottom'] + $size['offset']['box']['y']; // Aussenabstaende
		$file_height +=	( (((count($xml->generations->generation))-1)/2) * ($size['box']['max_height']+(2*$size['margin']['box']['y'])+$size['box']['rel']['max_height']) ) ; // Höhe Kinder + Margins + Höhe Beziehungsdatum zu Partner
		$file_height +=	( (((count($xml->generations->generation))-1)/2) * ($size['box']['max_height']+(2*$size['margin']['box']['y'])+(2*$size['length']['line']['vertical'])) ); // Höhe Partner + Margins + Linien zu Kindern
#		$file_height +=	( $size['box']['max_height'] ); // Höhe letzte Kinder - keine Margins/Beziehungsdaten
		$file_height +=	( $size['box']['max_height']+(5*$size['margin']['box']['y']) ); // Höhe letzte Kinder - keine Beziehungsdaten
		$file_height = 	$file_height * 1.01; // 1% Puffer
	}else{
		//Wenn Anzahl Generationen gerade
		$file_height = 	$size['margin']['top'] + $size['margin']['bottom'] + $size['offset']['box']['y']; // Aussenabstaende
		$file_height +=	( ((count($xml->generations->generation))/2) * ($size['box']['max_height']+(2*$size['margin']['box']['y'])+$size['box']['rel']['max_height']) ); // Höhe Kinder + Margins + Höhe Beziehungsdatum zu Partner
		$file_height +=	( ((count($xml->generations->generation))/2) * ($size['box']['max_height']+(2*$size['margin']['box']['y'])+(2*$size['length']['line']['vertical'])) ); // Höhe Partner + Margins + Linien zu Kindern
#		$file_height -=	( (2*$size['margin']['box']['y']) + (2*$size['length']['line']['vertical']) ); // Bei letzter Generation keine Margins/Linien
		$file_height -=	( (2*$size['length']['line']['vertical']) ); // Bei letzter Generation keine Linien
		$file_height = 	$file_height * 1.01; // 1% Puffer
	}

	
//-------------------------------------------------------------------
// Berechnung Skalierung
// (wenn Laenge oder Breite groesser als der maximale Druckbogen ist)
//-------------------------------------------------------------------

	// Wenn XML-Inhalt zu groß für max. Druckbogengroesse
	if($file_width > $page_maxwidth || $file_height > $page_maxheight) {		
		// Skalierungsfaktoren berechnen (jede Seite)
		$scalingX = ($file_width > $page_maxwidth ? $page_maxwidth/$file_width : $scaling);
		$scalingY = ($file_height > $page_maxheight ? $page_maxheight/$file_height : $scaling);
		// Pruefung, welcher Faktor staerker ist
#		$scaling = ($scalingX < $scalingY ? $scalingX : $scalingY);
		$scaling = round(($scalingX < $scalingY ? $scalingX : $scalingY), 2, PHP_ROUND_HALF_DOWN);
	}


//------------------------------------------------------------------
// Berechnung tatsaechliche Seitenlaengen
//------------------------------------------------------------------
	$page_width	= $file_width * $scaling;
#	$page_height = $file_height * $scaling;
	$page_height = $page_maxheight;

	// Wenn XML-Inhalt kürzer oder schmäler als A5-Breite/-Höhe
#	if($file_width < $page_minwidth)
#		$page_width	= $page_minwidth;
#	if($file_height < $page_minheight)
#		$page_height = $page_minheight;

	// PDF-Ausrichtung
	if($page_height > $page_width){
		define('PDF_PAGE_ORIENTATION', 'P');
	}else{
		define('PDF_PAGE_ORIENTATION', 'L');
	}

	
	
// ---------------------------------------------------------------------------------
// DEBUG
// ---------------------------------------------------------------------------------
if($_GET['debug'] == "all" || $_GET['debug'] == "pagesize") {
		echo 'generationen '.count($xml->generations->generation).'<br>';
		echo 'boxheight: '.$size['box']['max_height'].'<br>';
		
		echo 'berechnung file_height: '.$size['margin']['top'].' + '.$size['margin']['bottom'].' + ('.count($xml->generations->generation).' * ((2*'.$size['box']['max_height'].')+'.$size['box']['rel']['max_height'].'+(4*'.$size['margin']['box']['y'].')+(2*'.$size['length']['line']['vertical'].')))'.'<br>'; 
		echo 'berechnung file_height: '.$size['margin']['top'].' + '.$size['margin']['bottom'].' + ('.count($xml->generations->generation).' * ('.(2*$size['box']['max_height']).'+'.$size['box']['rel']['max_height'].'+'.(4*$size['margin']['box']['y']).'+'.(2*$size['length']['line']['vertical']).'))'.'<br>'; 
		echo 'berechnung file_height: '. $size['margin']['top'] .' + '. $size['margin']['bottom'] .' + ('. count($xml->generations->generation) .' * ('. ( 2*$size['box']['max_height'] ) + ( $size['box']['rel']['max_height'] ) + ( 4*$size['margin']['box']['y'] ) + ( 2*$size['length']['line']['vertical'] ).'))'.'<br>'; 
		echo 'file_width: '.$file_width.'<br>';
		echo 'file_height: '.$file_height.'<br><br>';
		
		echo 'scaling: '.$scaling.'<br><br>';
		
		echo 'berechnung file_height: '.$size['margin']['top']*$scaling.' + '.$size['margin']['bottom']*$scaling.' + ('.count($xml->generations->generation).' * ((2*'.$size['box']['max_height']*$scaling.')+'.$size['box']['rel']['max_height']*$scaling.'+(4*'.$size['margin']['box']['y']*$scaling.')+(2*'.$size['length']['line']['vertical']*$scaling.')))'.'<br>'; 
		echo 'berechnung file_height: '.$size['margin']['top']*$scaling.' + '.$size['margin']['bottom']*$scaling.' + ('.count($xml->generations->generation).' * ('.(2*$size['box']['max_height']*$scaling).'+'.$size['box']['rel']['max_height']*$scaling.'+'.(4*$size['margin']['box']['y']*$scaling).'+'.(2*$size['length']['line']['vertical']*$scaling).'))'.'<br>'; 
		echo 'berechnung file_height: '.$size['margin']['top']*$scaling.' + '.$size['margin']['bottom']*$scaling.' + ('.count($xml->generations->generation).' * ('.(2*$size['box']['max_height']*$scaling)+($size['box']['rel']['max_height']*$scaling)+(4*$size['margin']['box']['y']*$scaling)+(2*$size['length']['line']['vertical']*$scaling).'))'.'<br>'; 
		echo 'page_width: '.$page_width.'<br>';
		echo 'page_height: '.$page_height.'<br><br>';
		exit;
}

?>
<?php
require('tcpdf/tcpdf.php');

class XML2PDF extends TCPDF {

	
  public function Header() {
		global $style, $size;
		
		// Titel
		$this->SetFont($style['font']['title'],'',$size['font']['title']); // Hauptschrift: Schriftauswahl, Schriftgroesse
		$this->SetLineStyle(array('width' => $size['line']['title'], 'cap' => LINESYTYLE_CAP, 'join' => LINESYTYLE_JOIN, 'dash' => LINETYPE_TITLES, 'color' => array(LINECOLOR_TITLES)));
		$this->Cell(0, $size['leading']['title'], $this->header_title, LINE_TITLES, 1, L); // Rahmengroesse, Inhalt, Rahmenart, Ausrichtung

		// Meta-Info
		$this->SetFont($style['font']['sub'],'',$size['font']['sub']); // Sekundaerschrift: Schriftauswahl, Schriftgroesse
		$this->Cell(0, $size['leading']['sub'], $this->header_string, 0, 1, L); // Rahmengroesse, Inhalt, Rahmenart, Ausrichtung
  }

    
  public function Footer() {
		global $style, $size;
		
		// Custom text
		$this->SetXY($size['margin']['left'],-($size['margin']['footer']+$size['leading']['footer']));
		$this->SetFont($style['font']['footer'],'',$size['font']['footer']); // Sekundaerschrift: Schriftauswahl, Schriftgroesse
		$this->Cell(0, $size['leading']['footer'], FOOTER_DATA, 0, 1, L); // Rahmengroesse, Inhalt, Rahmenart, Ausrichtung

    // Pagenumber
#   $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');

    //Logo
    if(SHOW_LOGO)
	    $this->ImageEps(PATH_TEMPLATES.DEFAULT_LOGO, FOOTER_OFFSET_X_LOGO, FOOTER_OFFSET_Y_LOGO, $size['size']['logo']['x'], $size['size']['logo']['y']);
  }


	function PrintPerson($posX, $posY, $width, $sex = 'm', $firstname = 'unknown', $fname_vague = '0', $lastname = 'unknown', $lname_vague = '0', $birthdate = 'unknown', $deathdate = 'none', $age = 'none', $partner = TRUE, $reltype = 'none', $marriagedate = 'unknown', $divorcedate = 'unknown', $gen_divorced = FALSE){  
		global $style, $size;
		
		// oberer Margin uebergeben
		$posY += $size['margin']['box']['y']; 		

		// Defaults
		$posX_padding = $posX+$size['padding']['box']; 
		$posY_padding = $posY+$size['padding']['box'];
		$this->SetLineWidth($size['line']['box']);
		$this->SetLineStyle(array('width' => $size['line']['box'], 'cap' => LINESYTYLE_CAP, 'join' => LINESYTYLE_JOIN, 'dash' => LINETYPE_BOX, 'color' => array(LINECOLOR_BOX)));
		$this->SetXY($posX,$posY); // Position setzen
		
		// Box-Hintergrund
		if($style['box']['bg'] == 1)
			$this->SetFillColorArray($style['box']['color']); // Hintergrund farbig
		
		// Wenn Person == 'Partner', Beziehungsdaten ausgeben (inkl ggf. Symbole)
		if($partner){
			
			// Wenn geschieden, Beziehungs-Box doppelt so groß zeichnen, sonst normal
			if($divorcedate != 'none' && $divorcedate != '')
				$this->Cell($size['box']['min_width']+($size['padding']['box']*2), ($size['leading'][2]*2)+($size['padding']['box']*2), '', BOX_BORDER, 1, TEXTALIGN, $style['box']['bg']);
			else
				$this->Cell($size['box']['min_width']+($size['padding']['box']*2), $size['leading'][2]+($size['padding']['box']*2), '', BOX_BORDER, 1, TEXTALIGN, $style['box']['bg']);
  	
			// Padding fuer Inhalt
			$this->SetXY($posX_padding,$posY_padding);
  	
			// Heiratssymbol + -datum
			if ($reltype == 'married' || $reltype == 'divorced') {
				$this->SetXY($posX_padding+$size['offset']['symbol']['marriage']['x'], $posY_padding+$size['offset']['symbol']['marriage']['y']); // Versatz fuer Zeichen
				$this->Write($size['leading'][2], '⚭'); // Zeichen verheiratet
				$posX_indent = $posX_padding+$size['indent']['dates']; // Versatz fuer Datum
			}else{
				$posX_indent = $posX_padding;
#				$posX_indent = $posX_padding+$size['indent']['dates']; // Versatz fuer Datum
			}
			// Wenn Datum nicht bekannt, Platzhalter zeichnen
			if($marriagedate == 'unknown'){
				$this->SetLineStyle(array('width' => $size['line']['dates'], 'cap' => LINESYTYLE_CAP, 'join' => LINESYTYLE_JOIN, 'dash' => LINETYPE_DATES, 'color' => array(LINECOLOR_DATES)));
#				$this->Line($posX_indent+0.2, $posY_padding+($size['leading'][2]*0.8), $posX_padding+$size['box']['min_width'], $posY_padding+($size['leading'][2]*0.8));
				$this->Line($posX_indent, $posY_padding+($size['leading'][2]*0.8), $posX_padding+$size['box']['min_width'], $posY_padding+($size['leading'][2]*0.8));
				$this->Ln($size['leading'][2]); // Zeilenhoehe 2
			}else{
				$this->SetXY($posX_indent, $posY_padding); // Cursor verschieben
				$this->Cell($size['box']['min_width']-$size['indent']['dates'], $size['leading'][2], $marriagedate, BORDER_DATES, 1, TEXTALIGN);
#				$this->Cell($size['box']['min_width'], $size['leading'][2], $marriagedate, BORDER_DATES, 1, TEXTALIGN);
			}
			
			// Wenn Scheidung in Generation vorhanden
			if($gen_divorced == TRUE){
				if(($reltype == 'divorced') || ($divorcedate != 'none' && $divorcedate != '')){

					// (Falls aktuelle Person geschieden) Scheidungssymbol + -datum
					if ($reltype == 'divorced'){
						$posY = $this->GetY(); // current Y coordinate
						$this->SetXY($posX_padding+$size['offset']['symbol']['divorce']['x'], $posY+$size['offset']['symbol']['divorce']['y']);
						$this->Write($size['leading'][2], '⚮'); // Zeichen geschieden
					}
					if($divorcedate != 'none' && $divorcedate != ''){
						// Wenn Datum nicht bekannt, Platzhalter zeichnen
						if($divorcedate == 'unknown'){
							$this->SetLineStyle(array('width' => $size['line']['dates'], 'cap' => LINESYTYLE_CAP, 'join' => LINESYTYLE_JOIN, 'dash' => LINETYPE_DATES, 'color' => array(LINECOLOR_DATES)));
#							$this->Line($posX_indent+0.2, $posY+($size['leading'][2]*0.8), $posX_padding+$size['box']['min_width'], $posY+($size['leading'][2]*0.8));
							$this->Line($posX_indent, $posY+($size['leading'][2]*0.8), $posX_padding+$size['box']['min_width'], $posY+($size['leading'][2]*0.8));
							$this->Ln($size['leading'][2]); //Zeilenhoehe 2
						}else{
							$this->SetX($posX_indent); // indent to current position instead of linestart/pageborder
							$this->Cell($size['box']['min_width']-$size['indent']['dates'], $size['leading'][2], $divorcedate, BORDER_DATES, 1, TEXTALIGN);
						}
					}
				
				}else{
					// Sonst hier nur Leerzeile ausgeben
					$this->Ln($size['leading'][2]); // Zeilenhoehe Scheidungsdatum
				}
			}
			
			$this->Ln($size['padding']['box']+$size['margin']['box']['y']); // Abstand zwischen Beziehungsdaten und Partner
			$posY = $this->GetY(); // current Y coordinate
		}


		$this->SetXY($posX,$posY); // indent to current position instead of linestart/pageborder
	

		// Person ausgeben
		// RAHMEN --------------------------------------------------------------
		$this->Cell($width+($size['padding']['box']*2), $size['box']['max_height'], '', BOX_BORDER, 1, TEXTALIGN, $style['box']['bg']);

		// Padding fuer Inhalt
		$this->Ln($size['padding']['box']); // vertikales Padding			

		$posX_padding = $posX+$size['padding']['box'];
		$posY_padding = $posY+$size['padding']['box'];

		// NAME + GESCHLECHT --------------------------------------------------------------
		// Icon Geschlecht
		$this->SetFont($style['font']['names'], '', $size['font']['names']);	//Schriftauswahl, Schriftgroesse

		if(SHOW_SEX){
			if($sex == 'm'){
				$this->SetXY($posX_padding+$size['offset']['symbol']['sex_male']['x'], $posY_padding+$size['offset']['symbol']['sex']['y']);
				$this->Write($size['leading'][1], '♂'); // Zeichen maennlich
			}else{
				$this->SetXY($posX_padding+$size['offset']['symbol']['sex_female']['x'], $posY_padding+$size['offset']['symbol']['sex']['y']);
				$this->Write($size['leading'][1], '♀'); // Zeichen weiblich
			}
		}

		// Vorname
		if(SHOW_FIRSTNAME){
			$this->SetXY($posX_padding,$posY_padding);
			
			// Wenn Name nicht bekannt, Platzhalter zeichnen
			if($firstname == 'unknown'){
				$this->SetLineStyle(array('width' => $size['line']['names'], 'cap' => LINESYTYLE_CAP, 'join' => LINESYTYLE_JOIN, 'dash' => LINETYPE_NAMES, 'color' => array(LINECOLOR_NAMES)));
				$this->Line($posX_padding, $posY_padding+($size['leading'][1]*0.8), $posX_padding+$width, $posY_padding+($size['leading'][1]*0.8));
				$this->Ln($size['leading'][1]); //Zeilenhoehe 1
			}else if($fname_vague == '1'){
				$this->SetTextColorArray($style['font']['color']['unknown']); // Schriftfarbe
				$this->Cell($width, $size['leading'][1], $firstname, BORDER_NAMES, 1, TEXTALIGN); //Vorname
				$this->SetTextColorArray($style['font']['color']['default']); // Schriftfarbe
			}else{
				$this->Cell($width, $size['leading'][1], $firstname, BORDER_NAMES, 1, TEXTALIGN); //Vorname
			}

		}
		// Nachname
		if(SHOW_LASTNAME){
			$this->SetX($posX_padding); // indent to current position instead of linestart/pageborder
			
			// Wenn Name nicht bekannt, Platzhalter zeichnen
			if($lastname == 'unknown'){
				$posY = $this->GetY(); // current Y coordinate
				$this->SetLineStyle(array('width' => $size['line']['names'], 'cap' => LINESYTYLE_CAP, 'join' => LINESYTYLE_JOIN, 'dash' => LINETYPE_NAMES, 'color' => array(LINECOLOR_NAMES)));
				$this->Line($posX_padding, $posY+($size['leading'][1]*0.8), $posX_padding+$width, $posY+($size['leading'][1]*0.8));
#Bug?				$this->Ln($size['leading'][1]+$size['linespace'][1]); //Zeilenhoehe 1 + Abstand 1
				$this->Ln($size['leading'][1]); //Zeilenhoehe 1
			}else if($lname_vague == '1'){
				$this->SetTextColorArray($style['font']['color']['unknown']); // Schriftfarbe
				$this->Cell($width, $size['leading'][1], $lastname, BORDER_NAMES, 1, TEXTALIGN); //Vorname
				$this->SetTextColorArray($style['font']['color']['default']); // Schriftfarbe
			}else{
#				$this->SetFillColor(140,200,250); //Farbe Hintergrund																															 v 0 = transparent, 1 = farbig
				$this->Cell($width, $size['leading'][1], $lastname, BORDER_NAMES, 1, TEXTALIGN); //Nachname
			}
#			$this->SetFillColorArray($style['box']['color']); // Hintergrund farbig
		}

		// WEITERE INFOS + DATEN etc.  --------------------------------------------------------------
		// Abstand zu Metadaten
		if(SHOW_TITLE || SHOW_BIRTHDATE || SHOW_DEATHDATE)
			$this->Ln($size['linespace'][1]); // Abstand 1

		// Font fuer Metadaten
		$this->SetFont($style['font']['dates'], '', $size['font']['dates']);

		// TITEL etc.  --------------------------------------------------------------
		if(SHOW_TITLE){
			$this->Cell($width, $size['leading'][2], $title, BORDER_DATES, 1, TEXTALIGN);
			$this->Ln($size['linespace'][2]); // Abstand 2
		}
		
		// Geburtsdatum  --------------------------------------------------------------
		if(SHOW_BIRTHDATE){
			$posY = $this->GetY(); // current Y coordinate
			$this->SetXY($posX_padding+$size['offset']['symbol']['birth']['x'],$posY+$size['offset']['symbol']['birth']['y']);
			$this->Write($size['leading'][2], '⁎'); // Zeichen geboren
			
			// Wenn Datum nicht bekannt, Platzhalter zeichnen
			if($birthdate == 'unknown'){
				$this->SetLineStyle(array('width' => $size['line']['dates'], 'cap' => LINESYTYLE_CAP, 'join' => LINESYTYLE_JOIN, 'dash' => LINETYPE_DATES, 'color' => array(LINECOLOR_DATES)));
#				$this->Line($posX_padding+$size['indent']['dates']+0.2, $posY+($size['leading'][2]*0.8), $posX_padding+$size['box']['min_width'], $posY+($size['leading'][2]*0.8));
				$this->Line($posX_padding+$size['indent']['dates'], $posY+($size['leading'][2]*0.8), $posX_padding+$size['box']['min_width'], $posY+($size['leading'][2]*0.8));
				$this->Ln($size['leading'][2]); //Zeilenhoehe 2
			}else{
				$this->SetX($posX_padding+$size['indent']['dates']); // indent to current position instead of linestart/pageborder
				$this->Cell($width-$size['indent']['dates'], $size['leading'][2], $birthdate, BORDER_DATES, 1, TEXTALIGN);
			}
		}
		
		// Sterbedatum (falls vorhanden - sonst Lücke)  --------------------------------------------------------------
		if(SHOW_DEATHDATE && $deathdate != 'none' && $deathdate != ''){
			$posY = $this->GetY(); // current Y coordinate
			$this->SetXY($posX_padding+$size['offset']['symbol']['death']['x'],$posY+$size['offset']['symbol']['death']['y']);
			$this->Write($size['leading'][2], '✝'); // Zeichen geboren
			
			// Wenn Datum nicht bekannt, Platzhalter zeichnen
			if($deathdate == 'unknown'){
				$this->SetLineStyle(array('width' => $size['line']['dates'], 'cap' => LINESYTYLE_CAP, 'join' => LINESYTYLE_JOIN, 'dash' => LINETYPE_DATES, 'color' => array(LINECOLOR_DATES)));
#				$this->Line($posX_padding+$size['indent']['dates']+0.2, $posY+($size['leading'][2]*0.8), $posX_padding+$size['box']['min_width'], $posY+($size['leading'][2]*0.8));
				$this->Line($posX_padding+$size['indent']['dates'], $posY+($size['leading'][2]*0.8), $posX_padding+$size['box']['min_width'], $posY+($size['leading'][2]*0.8));
				$this->Ln($size['leading'][2]); //Zeilenhoehe 2
			}else{
				$this->SetX($posX_padding+$size['indent']['dates']); // indent to current position instead of linestart/pageborder
				$this->Cell($width-$size['indent']['dates'], $size['leading'][2], $deathdate, BORDER_DATES, 1, TEXTALIGN);
			}
		}
		$this->Ln($size['linespace'][2]); //Abstand 2
		
		// Alter
		if(SHOW_AGE == TRUE && $age != 'none'){

			// Wenn Alter nicht bekannt, Platzhalter zeichnen
			if(strstr($age, '__')){
				$posY = $this->GetY(); // current Y coordinate
				$age = str_replace('__ ', '', $age);
				$this->SetLineStyle(array('width' => $size['line']['dates'], 'cap' => LINESYTYLE_CAP, 'join' => LINESYTYLE_JOIN, 'dash' => LINETYPE_DATES, 'color' => array(LINECOLOR_DATES)));
				$this->Line($posX_padding, $posY+($size['leading'][2]*0.8), $posX_padding+(1.5*$size['indent']['dates']), $posY+($size['leading'][2]*0.8));
				$this->SetX($posX_padding+(2*$size['indent']['dates']));
				$this->Cell($width-(2*$size['indent']['dates']), $size['leading'][2], $age, BORDER_DATES, 1, TEXTALIGN);
			}else{
				$this->SetX($posX_padding);
				$this->Cell($width, $size['leading'][2], $age, BORDER_DATES, 1, TEXTALIGN);
			}
		}
		
	}

	
}
?>
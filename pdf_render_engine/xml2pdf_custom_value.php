<?php
	header('Content-type: text/html; charset=utf-8');

#------------------------------------------------------------------
# Pfade und Standardwerte uebernehmen
#------------------------------------------------------------------
	include('config.php');


#------------------------------------------------------------------
# URL-Variablen uebergeben
#------------------------------------------------------------------
	# Sprache
	if(empty($_POST["lang"])) $lang = "de";
	elseif(empty($_GET["lang"])) $lang = "de";

	# Datenquelle
	if($_POST["xml"]) $xml_src = $_POST[$_POST["xml"]];
	elseif($_GET["xml"]) $xml_src = $_GET[$_GET["xml"]];
	else $xml_src = DEFAULT_SRC;
	
	# Style / Template
	if($_GET["style"]){
		define('PATH_TEMPLATE',PATH_TEMPLATES.$_POST["style"].'/');
		
		if(file_exists($path_template.'style.php'))
			include(PATH_TEMPLATE.'style.php');
		else
			exit('Style not found');
	}else{
		define('PATH_TEMPLATE',PATH_TEMPLATES.DEFAULT_TEMPLATE.'/');
		include(PATH_TEMPLATE.'style.php');
	}
	
	# Anzeige Vornamen
	preg_match("/^([^\s]+)\s.*$/", strtolower($_POST['name_length']), $get_name_length);
	if($get_name_length[1] && in_array($get_name_length[1],$a_name_lengths)){
		$name_length = $get_name_length[1];
	}else{
		$name_length = $a_name_lengths[0];
	}
	
	# Datumsdetails (genaue daten oder nur jahr)
	if($_GET["detail"] == "year"){
		$date_detail = $_GET["detail"];
		define('SHOW_AGE',FALSE);
	}else{
		$date_detail = "date";
		define('SHOW_AGE',TRUE);
	}


#------------------------------------------------------------------
# Individuelle Vorgaben
#------------------------------------------------------------------
	# Texte fuer Header+Footer
	include('description_'.$lang.'.php');
	






#------------------------------------------------------------------
# XML lesen
#------------------------------------------------------------------
	if ($xml = simplexml_load_file($xml_src)) {
		
		If($xml->generations[type] == "a")
			exit("vorfahren-modus noch nicht implementiert");


		# laengsten namen ermitteln
		$longest_name = 0;
		foreach ($xml->generations->generation as $generation) {
			foreach ($generation->person as $person) {
	    	if((int)$person->firstname->default['length'] > $longest_name) $longest_name = $person->firstname->default['length'];
		  }
			foreach ($generation->person as $person) {
	    	if((int)$person->lastname->default['length'] > $longest_name) $longest_name = $person->lastname->default['length'];
		  }
		}

		# Breite laengster Name festlegen
#		define('BOX_MAXWIDTH',35);
		define('BOX_MAXWIDTH', FONTSIZE_1 * GLYPH_WIDTH * $longest_name );


		# Seitenabmessungen anhand XML errechnen
		include('config_page.php');



		#------------------------------------------------------------------
		# PDF 
		#------------------------------------------------------------------

		# PDF initialisieren  
		require_once('tcpdf/config/lang/eng.php');
		require_once('pdf_functions.php');

		$pdf = new XML2PDF($pageorientation, $measurement, array($page_width,$page_height), true, 'UTF-8', false); 

		# Set document information
		$pdf->SetAuthor(PDF_AUTHOR); #PDF_AUTHOR (default: t7even)
		$pdf->SetTitle($content_title[0].$xml->generations->generation->person->firstname->default.' '.$xml->generations->generation->person->lastname->default); #PDF_HEADER_TITLE
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		$pdf->SetCreator(PDF_CREATOR); #PDF_CREATOR (default: treeprint.com)
#		$pdf->SetProtection('modify');


		# Header
		$pdf->SetHeaderData('','', $content_title[0].$xml->generations->generation->person->firstname->default.' '.$xml->generations->generation->person->lastname->default, $content_stats[0].$xml->vars->count->people.$content_stats[1].$xml->vars->count->generations.$content_stats[2].$xml->vars->count->columns.$content_stats[3]);
		$pdf->setHeaderMargin(MARGIN_HEADER);

		# Footer
    $pdf->setFooterFont(array(FONTFAM_FOOTER, 'I', FONTSIZE_FOOTER));
		define('FOOTER_DATA', $content_meta[0].date($content_meta[1], strtotime($xml->vars->gedcom->timestamp)).$content_meta[2].$xml->vars->gedcom->file.' - Längster Name: '.$longest_name.' Buchstaben');
		define('FOOTER_OFFSET_X_LOGO', $page_width-MARGIN_RIGHT-30);
		define('FOOTER_OFFSET_Y_LOGO', $page_height-MARGIN_FOOTER-15);

		# Page
		$pdf->SetMargins(MARGIN_LEFT, MARGIN_TOP, MARGIN_RIGHT);
		$pdf->SetAutoPageBreak(off, MARGIN_BOTTOM);
		$pdf->SetCellPadding(CELL_PADDING); # Einzug fuer Boxes, Names, Dates

		$pdf->AddPage(); #definiert Seite inkl Header und Footer 


		#------------------------------------------------------------------
		# Content
		#------------------------------------------------------------------
		#Start-Nullpunkt fuer Personen
#		$posX = OFFSET_X_BOX; $posY = OFFSET_Y_BOX;
		$posX = MARGIN_LEFT; $posY = MARGIN_TOP;
		
		# Nullpunkt markieren
#		$pdf->Line($posX, $posY, $posX+3, $posY);
#		$pdf->Line($posX, $posY, $posX, $posY+3);


		#------------------------------------------------------------------
		# Aktuelle Generation (jeweils neue Zeile)
		#------------------------------------------------------------------
		foreach ($xml->generations->generation as $generation) {


			#------------------------------------------------------------------
			# Einzelne Personen positionieren und ausgeben
			#------------------------------------------------------------------
			foreach ($generation->person as $person) {

				# horiz. Position anhand XML errechnen 
				$posX = (BOX_MAXWIDTH+GAP_X_BOX)*($person['pos']+($person['width']/2));

				#------------------------------------------------------------------
				# Person ausgeben (inkl Geschlecht, Lebensdaten)
				#------------------------------------------------------------------
				$pdf->PrintPerson( $posX, $posY, $person->firstname->$name_length, $person->lastname->default, $person->sex, $person->birth->$date_detail, $person->death->$date_detail, $person->age );

				#------------------------------------------------------------------
				# Wenn Person == "Partner"  Beziehungsdaten ausgeben (inkl ggf. Symbole)
				#------------------------------------------------------------------
				if(($generation['level'] %2) != 0 )
					$pdf->PrintRelDate( $posX, $posY, $person->relation->type, $person->relation->marriage->$date_detail, $person->relation->divorce->$date_detail);


				#------------------------------------------------------------------
				# Verwandschaftslinien
				#------------------------------------------------------------------

			 	# Linienstaerke (Bluts)Verwandschaft festlegen
			 	$pdf->SetLineWidth(LINESIZE_BLOOD);
			 	$pdf->SetLineStyle(array('width' => LINESIZE_BLOOD, 'cap' => 'round', 'join' => 'round', 'dash' => LINETYPE_BLOOD, 'color' => array(LINECOLOR_BLOOD)));
			 	

	 			# Zur Vereinfachung Variablennamen aus config uebersetzen
	 			$cX = OFFSET_X_CHILDLINE;
		 		$cY = OFFSET_Y_CHILDLINE;
		 		$vl = LENGTH_VERTLINES;

			 	# bei "Kindern": Linien zeichnen (nur bei der allerersten Person (=Urahn) nicht)
		 		if(($generation['level'] %2) == 0 && $generation['level'] > 0) {

					# Obere senkrechte Linien zwischen Eltern + Kindern zeichnen
					# (aus aufgezeichneten Koordinaten von Partnern)
		 			if(isset($width[intval($person["pos"])])){
		 				$pdf->SetLineStyle(array('width' => LINESIZE_BLOOD, 'cap' => 'round', 'join' => 'round', 'dash' => LINETYPE_BLOOD, 'color' => array((int)$person["pos"]*10,(int)$person["width"]*10,(int)$person["pos"]*5)));
		 				$pdf->Line($vlines[$i_k][0], $vlines[$i_k][1], $vlines[$i_k][2], $vlines[$i_k][3]);
		 				$i_k++;
		 			}

			 		# Untere senkrechte Linie zwischen Eltern + Kind zeichnen
		 			$pdf->Line($posX+$cX, $posY-GAP_Y_BOX, $posX+$cX, $posY-GAP_Y_BOX-$vl);


		 			# Wenn Familie mit min. 2 Kindern:
		 			# Koordinaten fuer waagrechte Linien merken (fuer spaeteren Durchlauf)
		 			if($width[intval($person["pos"])]>1) {
		 				$hlines[$i_h][1] = $posX+$cX;
		 				$hlines[$i_h][2] = $posY-GAP_Y_BOX-$vl;
		 				$parentwidth	= $width[intval($person["pos"])]+$person["pos"];
					}
					if($parentwidth == ($person["pos"]+$person["width"])) {
		 				$hlines[$i_h][3] = $posX+$cX;
		 				$hlines[$i_h][4] = $posY-GAP_Y_BOX-$vl;
		 				unset($parentwidth);
			 			$i_h++;
		 		 	}
		 		}
		 		

				# Zur Vereinfachung Variablennamen aus config uebersetzen
				$cX = OFFSET_X_PARTNERLINE;
				$cY = OFFSET_Y_PARTNERLINE;
				$vl =	LENGTH_VERTLINES;

			 	# wenn aktuelle Person = Partner
		 		if(($generation['level'] %2) != 0) {
					# Koordinaten fuer obere senkrechte Linie zwischen Eltern + Kind merken (fuer spaeteren Durchlauf)
			 		$vlines[$i_v] = array($posX+$cX, $posY+BOX_MAXHEIGHT+GAP_Y_BOX, $posX+$cX, $posY+BOX_MAXHEIGHT+GAP_Y_BOX+$vl);
					$i_v++;

					# Breite von Eltern (als Wert) zusammen mit Position (als Schluessel) in Array an Kind uebergeben
					$width[intval($person["pos"])] = intval($person["width"]);
				}


				#------------------------------------------------------------------
				# Beziehungslinien
				#------------------------------------------------------------------

			 	# Linienstil Beziehung festlegen
			 	$pdf->SetLineWidth(LINESIZE_LOVE);
#			 	$pdf->SetLineStyle(Array(1, 'butt', '', '2,1', '', array(LINECOLOR_LOVE)));
			 	$pdf->SetLineStyle(array('width' => LINESIZE_LOVE, 'cap' => 'round', 'join' => 'round', 'dash' => LINETYPE_LOVE, 'color' => array(LINECOLOR_LOVE)));



			 	# wenn aktuelle Person == Partner
		 		# Linienkoordinaten unten (zu Kind) merken (fuer spaeteren Durchlauf)
			 	if(($generation['level'] %2) != 0) {
					
					$pdf->Line($posX+$cX, $posY-GAP_Y_BOX-4, $posX+$cX, $posY-GAP_Y_BOX-$vl-4);
				}
			 	
/*


	 			# Zur Vereinfachung Variablennamen aus config uebersetzen
	 			$cX = OFFSET_X_CHILDLINE;
		 		$cY = OFFSET_Y_CHILDLINE;
		 		$vl = LENGTH_VERTLINES;

			 	# bei "Kindern": Linien zeichnen (nur bei der allerersten Person (falls Einzelkind) nicht)
		 		if(($generation['level'] %2) == 0 && $generation['level'] > 0) {
			 		# Senkrechte Linie ueber (zum) Kind zeichnen
		 			$pdf->Line($posX+$cX, $posY-GAP_Y_BOX, $posX+$cX, $posY-GAP_Y_BOX-$vl);

					# Senkrechte Linien von Eltern zu Kindern zeichnen (sofern Kind/er vorhanden)
		 			if(isset($width[intval($person["pos"])])){
		 				$pdf->Line($vlines[$i_k][0], $vlines[$i_k][1], $vlines[$i_k][2], $vlines[$i_k][3]);
						$i_k++;
		 			}

		 			# Wenn Familie mit min. 2 Kindern:
		 			# Koordinaten fuer waagrechte Linien merken (fuer spaeteren Durchlauf)
		 			if($width[intval($person["pos"])]>1) {
		 				$hlines[$i_h][1] = $posX+$cX;
		 				$hlines[$i_h][2] = $posY-GAP_Y_BOX-$vl;
		 				$parentwidth	= $width[intval($person["pos"])]+$person["pos"];
					}
					if($parentwidth == ($person["pos"]+$person["width"])) {
		 				$hlines[$i_h][3] = $posX+$cX;
		 				$hlines[$i_h][4] = $posY-GAP_Y_BOX-$vl;
		 				unset($parentwidth);
			 			$i_h++;
		 		 	}
		 		}
		 		

				# Zur Vereinfachung Variablennamen aus config uebersetzen
				$cX = OFFSET_X_PARTNERLINE;
				$cY = OFFSET_Y_PARTNERLINE;
				$vl =	LENGTH_VERTLINES;

			 	# wenn aktuelle Person = Partner
		 		# Linienkoordinaten unten (zu Kind) merken (fuer spaeteren Durchlauf)
			 	if(($generation['level'] %2) != 0) {
					# Koordinaten Senkrechte Linie unter Eltern fuer spaeteren Durchlauf aufzeichnen
					$vlines[$i_v] = array($posX+$cX, $posY+BOX_MAXHEIGHT+GAP_Y_BOX, $posX+$cX, $posY+BOX_MAXHEIGHT+GAP_Y_BOX+$vl);
					$i_v++;

					# Breite von Eltern (als Wert) zusammen mit Position (als Schluessel) in Array an Kind uebergeben
					$width[intval($person["pos"])] = intval($person["width"]);
				}
				*/
				
				
				
			}
			#------------------------------------------------------------------
			# Ende Person
			#------------------------------------------------------------------

			# Y-Position fuer nächste "Generation" (eins nach unten) verschieben 
			# Bei Kinder zusaetzlich Versatz fuer Linien
			$posY = (( (($generation['level'] %2) == 0) && ($generations['type'] = 'd') ) ? $posY+BOX_MAXHEIGHT+GAP_Y_BOX+LINEHEIGHT_2+(LINESPACE_2*2)+GAP_Y_BOX : $posY+BOX_MAXHEIGHT+GAP_Y_BOX+($vl*2)+GAP_Y_BOX );

		}
		#------------------------------------------------------------------
		# Ende Generation
		#------------------------------------------------------------------

/*
echo '<pre>';
print_r($width);
print_r($hlines);
print_r($vlines);
echo '</pre>';
exit;
*/

		#------------------------------------------------------------------
		# waagrechte Verwandschaftslinien zeichnen
		#------------------------------------------------------------------
		$pdf->SetLineStyle(array('width' => LINESIZE_BLOOD, 'cap' => 'round', 'join' => 'round', 'dash' => LINETYPE_BLOOD, 'color' => array(LINECOLOR_BLOOD)));
		if(count($hlines)!=0) {
			for($i=0;$i<count($hlines);$i++) {
				$hlines[$i] = $pdf->Line($hlines[$i][1], $hlines[$i][2], $hlines[$i][3], $hlines[$i][4]);
			}
		}


		#------------------------------------------------------------------
		# PDF ausgeben
		#------------------------------------------------------------------
#		$pdf->Output('Stammbaum.pdf', 'D');
		$pdf->Output();
		
	}else{
		# Wenn Probleme beim lesen des XMLs
		exit("Konnte Datei nicht laden.");
	}
?>
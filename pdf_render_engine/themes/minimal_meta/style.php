<?php
//------------------------------------------------------------------
// Icons
//------------------------------------------------------------------
	$image_malesex = 		'male.ai';
	$image_femalesex = 	'female.ai';
	$image_birthday = 	'birth.ai';
	$image_deathday =		'death.ai';
	$image_marriage =		'marriage.ai';
	$image_divorce =		'divorce.ai';

//------------------------------------------------------------------
// Offsets, Margins
//------------------------------------------------------------------
	//Seitenränder
	$margin_top = 10;
	$margin_left = 10;
	$margin_right = 10;
	$margin_bottom = 5;

	// Abstaende zwischen Boxen
	$gapX_box = 7.8;
	$gapY_box = 2.3;
	
	// Rahmen ein-/ausschalten bzw. -position
	$borderstyle = 0;

	// Koordinaten erste Box
	$offsetX_box = 10;
	$offsetY_box = 40;
	
	// Symbole Geschlecht (maennlich und weiblich bzw. beide)
	$offsetX_sex = 2.1;
	$offsetY_sex = 0;
	$offsetX_malesex = $offsetX_sex;
	$offsetY_malesex = 0.6;
	$offsetX_femalesex = $offsetX_sex;
	$offsetY_malesex = 0.7;

	// Symbole Lebensdaten
	$offsetX_dates = 0;
	$offsetY_dates = 0;
	$offsetX_birthday = 0.7;
	$offsetY_birthday = 11.45;
	$offsetX_deathday = 1.1;
	$offsetY_deathday = 15.15;
	$offsetX_marriage = $offsetX_dates;
	$offsetY_marriage = 7.9;

	// Linien
	$length_vertlines = 5;

	$offsetX_vertlines = 2.3;
	$offsetY_vertlines = 0;
	$offsetX_childline = $offsetX_vertlines;
	$offsetY_childline = 2.3;
	$offsetX_partnerline = $offsetX_vertlines;
	$offsetY_partnerline = 24;
	
	

//------------------------------------------------------------------
// Textformatierung und -abstaende
//------------------------------------------------------------------
	// Schriftarten (case insensitive)
	// default verfügbar: Courier, Helvetica, Arial, Times, Symbol, ZapfDingbats
	// bzw deren Synonyme: fixed-width, sans serif, serif, symbolic, 
	
	// Zusätzliche individuelle Schriftarten
	$custom_fonts = array( 
		array('meta', 'Meta-Normal_Lf.php')
	);
	
	// Schriftart je Seitenbereich
	$fontfam_title = 'meta';
	$fontfam_subtitle = 'meta';
	$fontfam_names = 'meta';
	$fontfam_dates = 'meta';
	
	// Schriftgröße (pt)
	$fontsize1 = 13;
	$fontsize2 = 8;
	$fontsize_title = 18;
	$fontsize_subtitle = 9;
	$fontsize_names = $fontsize1;
	$fontsize_dates = $fontsize2;
	// Zeilenhoehe (mm)
	$lineheight1 = 3.4;
	$lineheight2 = 1.9;
	// Zeilenzwischenraum (mm)
	$linespace1 = 1.7;
	$linespace2 = 3;
	$linespace3 = 6.2;
	// Texteinzug Lebensdaten
	$textindent_dates = 2.7;
	$textorientation = 'L';


//------------------------------------------------------------------
// Linien
//------------------------------------------------------------------
	// Verwandschaft
	$linetype_blood	="solid";
	$linesize_blood = 0.13;
	$linecolor_blood = "black";
	// Beziehung
	$linetype_love	="dotted";
	$linesize_love = 0.23;
	$linecolor_love = "grey";
	
	
// Breite laengster Name festlegen
	$box_maxwidth		= 35;
// Maximalhoehe der "Box" aus Bestandteilen errechnen
	$box_maxheight	= $lineheight1+$linespace1+$lineheight1+$linespace2+$lineheight2+$linespace1+$lineheight2+$linespace2+$lineheight2;

?>
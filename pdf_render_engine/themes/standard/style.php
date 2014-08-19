<?php
//------------------------------------------------------------------
// Icons
//------------------------------------------------------------------
	define('IMAGE_MALESEX','male.ai');
	define('IMAGE_FEMALESEX','female.ai');
	define('IMAGE_BIRTHDAY','birth.ai');
	define('IMAGE_DEATHDAY','death.ai');
	define('IMAGE_MARRIAGE','marriage.ai');
	define('IMAGE_DIVORCE','divorce.ai');


//------------------------------------------------------------------
// Offsets, Margins
//------------------------------------------------------------------
	//Seitenränder
	$size['margin']['header'] = 10;
	$size['margin']['top'] = 30;
	$size['margin']['left'] = 10;
	$size['margin']['right'] = 10;
	$size['margin']['bottom'] = 25;
	$size['margin']['footer'] = 5;

	// Koordinaten erste Box
#	$size['offset']['box']['x'] = 10;
#	$size['offset']['box']['y'] = 30;


//------------------------------------------------------------------
// Aussehen Boxen
//------------------------------------------------------------------
	// Mindestbreite Box ( > breite datumsangaben)
	$size['box']['min_width'] = 20;

	// Innenabstand der "Box" festlegen
	$size['padding']['box'] = 1;

	// Aussenabstand der "Box" festlegen
#	$size['margin']['box'] = 2.5;
	$size['margin']['box']['x'] = 6;
	$size['margin']['box']['y'] = 3;

	
	// Rahmen ein-/ausschalten bzw. -position
	define('BOX_BORDER',0); 			// 0 = keine, 1 = alle, TRBL = einzelne/mehrere
	define('BORDER_NAMES',1); 		// 0 = keine, 1 = alle, TRBL = einzelne/mehrere
	define('BORDER_DATES',1); 		// 0 = keine, 1 = alle, TRBL = einzelne/mehrere
	
	// Rahmenfarbe/-stil
	define('LINETYPE_BOX',0); 		// 0=solid
	$size['line']['box'] = 0.13;
	define('LINECOLOR_BOX',0);
#	define('LINECOLOR_BOX',255);
	
	// Hintergrundfuellfarbe festlegen
	$style['box']['bg'] = 0;  	// 0 = transparent, 1 = deckend
#	$style['box']['color'] = array(250); // grey
	$style['box']['color'] = array(250,234,247); // colored

//------------------------------------------------------------------
// Linienstile
//------------------------------------------------------------------
	// Namen
	$size['line']['names'] = 0.13;
	define('LINETYPE_NAMES',0); // 0=solid
	define('LINECOLOR_NAMES',0);
	// Dates
	$size['line']['dates'] = 0.13;
	define('LINETYPE_DATES',0); // 0=solid
	define('LINECOLOR_DATES',0);
	// Verwandschaft
	$size['line']['blood'] = 0.13;
	define('LINETYPE_BLOOD',0); // 0=solid
	define('LINECOLOR_BLOOD',0);
	// Beziehung
	$size['line']['love'] = 0.13;
	define('LINETYPE_LOVE','"'.$size['line']['love'] / PT_MM * 0.02.','.$size['line']['love'] / PT_MM * 1.6.'"'); // =dashed 
	#define('LINETYPE_LOVE','0.01,0.98'); // =dashed 
	define('LINECOLOR_LOVE',120);



//------------------------------------------------------------------
// Detailabstaende/-versaetze Symbole
//------------------------------------------------------------------
	
	// Symbole Geschlecht (maennlich und weiblich bzw. beide)
	define('OFFSET_X_SEX',-2.5);
	define('OFFSET_Y_SEX',0);
	define('OFFSET_X_MALESEX',-2.8);
	define('OFFSET_X_FEMALESEX',OFFSET_X_SEX);
	/*
  define('OFFSET_X_MALESEX',2.1+0.125+0.071);
	define('OFFSET_Y_MALESEX',0.6+0.918-0.187);
	define('OFFSET_X_FEMALESEX',2.1+0.282+0.071);
	define('OFFSET_Y_MALESEX',0.7+1.548+1.645);

 	 
  // Symbole Lebensdaten
	define('OFFSET_X_DATES',0);
	define('OFFSET_Y_DATES',0);
	define('OFFSET_X_BIRTHDAY',0.7+0.195-1.329);
	#define('OFFSET_Y_BIRTHDAY',11.45);
	define('OFFSET_Y_BIRTHDAY',0.824+0.45);
	define('OFFSET_X_DEATHDAY',1.1+0.453-2.129);
	#define('OFFSET_Y_DEATHDAY',15.15);
	define('OFFSET_Y_DEATHDAY',0.821+0.45);
	#define('OFFSET_X_MARRIAGE',OFFSET_X_DATES);
	define('OFFSET_X_MARRIAGE',1.06+0.071);
	define('OFFSET_Y_MARRIAGE',7.9+0.713+0.769);
  define('OFFSET_X_DIVORCE',1.06);
	define('OFFSET_Y_DIVORCE',7.9+0.713);
 	*/

	define('OFFSET_X_DATES',0);
	define('OFFSET_Y_DATES',0);
	define('OFFSET_X_BIRTHDAY',0.15);
	define('OFFSET_Y_BIRTHDAY',0);
	define('OFFSET_X_DEATHDAY',0.27);
	define('OFFSET_Y_DEATHDAY',-0.2);
	define('OFFSET_X_MARRIAGE',-0.2);
	define('OFFSET_Y_MARRIAGE',0.1);
  define('OFFSET_X_DIVORCE',-0.2);
	define('OFFSET_Y_DIVORCE',0.1);
  

//------------------------------------------------------------------
// Detailabstaende/-versaetze Linien
//------------------------------------------------------------------

  // Linien
	$size['length']['line']['vertical'] = 3.5;
  
  $size['offset']['line']['vertical']['x'] = 1.5;
	$size['offset']['line']['vertical']['y'] = 0;
	$size['offset']['line']['children']['x'] = 1.5;
#	$size['offset']['line']['children']['y'] = 2.3;
	$size['offset']['line']['children']['y'] = 0;
	$size['offset']['line']['partner']['x'] = 1.5;
#	$size['offset']['line']['partner']['y'] = 24;
	$size['offset']['line']['partner']['y'] = 0;
	
	$size['offset']['line']['rel']['x'] = 2;
#	$size['offset']['line']['rel']['y'] = 3.7;
	$size['offset']['line']['rel']['y'] = 0;
	$size['length']['line']['rel'] = 5;
	



//------------------------------------------------------------------
// Textformatierung und -abstaende
//------------------------------------------------------------------
	// Padding um den Text
	$size['padding']['cell'] = 0;


	// Schriftarten (case insensitive)
	// default verfügbar: 
	//	 	Times 		(times,timesb,timesi,timesbi)
	//		Helvetica (helvetica,helveticab,helveticai,helveticabi)
	//		Courier 	(courier,courierb,courieri,courierbi) 
	//		Symbol		(symbol)
	//		ZapfDingbats (zapfdingbats)
	// bzw deren Synonyme?: fixed-width, sans serif, serif, symbolic, 

	
	// Zusätzliche individuelle Schriftarten - Feature in neuer PDF-Klasse noch nicht konfiguriert
	$custom_fonts = array( 
		array('courier', 'courier.php')
	);
	
	// Schriftart je Seitenbereich
	$style['font']['title'] =			'helvetica';
	$style['font']['subtitle'] =	'helvetica';
	$style['font']['names'] =			'helvetica';
	$style['font']['dates'] =			'helvetica';
	$style['font']['footer'] =		'helvetica';

	
	// Schriftgröße (pt)
	$size['font']['title'] = 	18;
	$size['font']['sub'] = 		9;
	$size['font']['names'] = 	13;
	$size['font']['dates'] =	8;
	$size['font']['footer'] =	8;
	
	// Zeilenabstand (mm - von PDF-Klasse standardmaessig auf 120% von FONTSIZE festgelegt - nicht zu aendern)
	$min_leading = K_CELL_HEIGHT_RATIO * PT_MM;
	
	// Zeilenhoehe (mm)  - dient in ersterlinie zur berechnung der boxhoehe
	$size['leading'][1] =	$size['font']['names'] * $min_leading + 0; // Minimal-Zeilenhoehe + custom wert
	$size['leading'][2] =	$size['font']['dates'] * $min_leading + 0; // Minimal-Zeilenhoehe + custom wert

	// Zeilenzwischenraum (mm)
	/*
	$size['linespace'][1] = 	1.7;
	$size['linespace'][2] = 	3;
	$size['linespace'][3] = 	6.2;
	*/
	$size['linespace'][1] = 	0;
	$size['linespace'][2] = 	0;
	$size['linespace'][3] = 	0;
	
	// Texteinzug Lebensdaten
#	$size['indent']['dates'] = 	2.7;
	$size['indent']['dates'] = 	3.1;
	
	define('TEXTALIGN','L');


	// durchschnittliche Buchstabenbreite
	define('GLYPH_WIDTH', 1 * PT_MM * 0.48);
	

?>
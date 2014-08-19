<?php
if($_GET['debug'] == "all" || $_GET['debug'] == "border") {



//------------------------------------------------------------------
// Offsets, Margins
//------------------------------------------------------------------
	//Seitenränder
	$size['margin']['header'] = 10;
	$size['margin']['top'] = 30;
#	$size['margin']['left'] = 10;
	$size['margin']['left'] = 20;
#	$size['margin']['right'] = 10;
	$size['margin']['right'] = 20;
	$size['margin']['bottom'] = 25;
	$size['margin']['footer'] = 5;

	// Koordinaten erste Box
	$size['offset']['box']['x'] = 0;
	$size['offset']['box']['y'] = 0;

//------------------------------------------------------------------
// Aussehen Boxen
//------------------------------------------------------------------
	// Mindestbreite Box ( > breite datumsangaben)
	$size['box']['min_width'] = 20;


	// Innenabstand der "Box" festlegen
#	$size['padding']['box'] = 1;
	$size['padding']['box'] = 0;

	// Aussenabstand der "Box" festlegen
#	$size['margin']['box'] = 2.5;
	$size['margin']['box']['x'] = 8;
#	$size['margin']['box']['x'] = 0;
	$size['margin']['box']['y'] = 3.15;
#	$size['margin']['box']['y'] = 0;

	
	// Rahmen ein-/ausschalten bzw. -position
	define('BOX_BORDER',1); 		// 0 = keine, 1 = alle, TRBL = einzelne/mehrere
	define('BORDER_NAMES',1); 		// 0 = keine, 1 = alle, TRBL = einzelne/mehrere
	define('BORDER_DATES',1); 		// 0 = keine, 1 = alle, TRBL = einzelne/mehrere

	
	// Rahmenfarbe/-stil
	define('LINETYPE_BOX',0); 		// 0=solid
	$size['line']['box'] = 0.13;
	define('LINECOLOR_BOX',0);
	
	// Hintergrundfuellfarbe festlegen
	$style['box']['bg'] = 0;  	// 0 = transparent, 1 = deckend
#	$style['box']['color'] = array(250); // grey
	$style['box']['color'] = array(250,234,247); // colored


//------------------------------------------------------------------
// Rahmen-/Linienstile
//------------------------------------------------------------------
	// Namen
	$size['line']['title'] = 0.13;
	define('LINE_TITLES',0); // 0=none, B=bottom, L=left etc.
	define('LINETYPE_TITLES',0); // 0=solid
	define('LINECOLOR_TITLES',120);
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
	$size['line']['love_dash']['dot'] = $size['line']['love'] / PT_MM * 0.02;
	$size['line']['love_dash']['gap'] = $size['line']['love'] / PT_MM * 4;
	define('LINETYPE_LOVE','"'.($size['line']['love'] / PT_MM * 0.02).','.($size['line']['love'] / PT_MM * 1.6).'"'); // =dashed 
	#define('LINETYPE_LOVE','0.01,0.98'); // =dashed 
	define('LINECOLOR_LOVE',0);

	// Linienenden (round, solid, butt)
	define('LINESYTYLE_CAP','round');
	define('LINESYTYLE_JOIN','round');

//------------------------------------------------------------------
// Detailabstaende/-versaetze Linien
//------------------------------------------------------------------

  // Linien
	$size['length']['line']['vertical'] = 3.5;
  
	$size['offset']['line']['vertical']['x'] = 1.5;
	$size['offset']['line']['vertical']['y'] = 0;
	$size['offset']['line']['children']['x'] = 1.5;
	$size['offset']['line']['children']['y'] = 0;
	$size['offset']['line']['partner']['x'] = 1.5;
	$size['offset']['line']['partner']['y'] = 0;
	
	$size['offset']['line']['rel']['x'] = 2;
	$size['offset']['line']['rel']['y'] = 0;
	$size['length']['line']['rel'] = 5;
	


//------------------------------------------------------------------
// Textformatierung und -abstaende
//------------------------------------------------------------------
	
	// Schriftarten (case insensitive)
	// default verfügbar: 
	//	 	Times 		(times,timesb,timesi,timesbi)
	//		Helvetica (helvetica,helveticab,helveticai,helveticabi)
	//		Courier 	(courier,courierb,courieri,courierbi) 
	//		Symbol		(symbol)
	//		ZapfDingbats (zapfdingbats)
	// bzw deren Synonyme?: fixed-width, sans serif, serif, symbolic, 

	// Schriftart je Seitenbereich/Element
	$style['font']['title'] =		'metabkr';
	$style['font']['subtitle'] =	'metabkr';
	$style['font']['names'] =		'metabkr';
	$style['font']['dates'] =		'metabkr';
	$style['font']['footer'] =		'metabkr';

	//Schriftfarbe
	$style['font']['color']['default'] = array(0); // black
#	$style['font']['color']['unknown'] = array(210); // grey
	$style['font']['color']['unknown'] = array(120); // grey

	// Schriftgröße (pt) je Seitenbereich/Element
#	$size['font']['title'] = 	18;
	$size['font']['title'] = 	136;
#	$size['font']['sub'] = 		13;
	$size['font']['sub'] = 		18;
	$size['font']['names'] = 	13;
	$size['font']['dates'] =	8;
#	$size['font']['footer'] =	8;
	$size['font']['footer'] =	13;
	
	// Zeilenhoehe (mm) - dient zur Berechnung der Boxhoehe / Positionierung Zeilen
#	$size['leading'][1] =	$size['font']['names'] * $min_leading + 0; // Minimal-Zeilenhoehe + custom wert
#	$size['leading'][2] =	$size['font']['dates'] * $min_leading + 0; // Minimal-Zeilenhoehe + custom wert
	$size['leading']['title'] =	$size['font']['title'] * K_CELL_HEIGHT_RATIO * 1.5 * PT_MM; // Fontsize(pt) * Proportion Zeile * Faktor Zeilenabstand * Faktor pt->mm
	$size['leading']['sub'] =	$size['font']['title'] * K_CELL_HEIGHT_RATIO * 1.2 * PT_MM; // Fontsize(pt) * Proportion Zeile * Faktor Zeilenabstand * Faktor pt->mm
	$size['leading']['footer'] =	$size['font']['title'] * K_CELL_HEIGHT_RATIO * 1.2 * PT_MM; // Fontsize(pt) * Proportion Zeile * Faktor Zeilenabstand * Faktor pt->mm
	$size['leading'][1] =	$size['font']['names'] * K_CELL_HEIGHT_RATIO * 1.077 * PT_MM; // Fontsize(pt) * Proportion Zeile * Faktor Zeilenabstand * Faktor pt->mm
	$size['leading'][2] =	$size['font']['dates'] * K_CELL_HEIGHT_RATIO * 1.374 * PT_MM; // Fontsize(pt) * Proportion Zeile * Faktor Zeilenabstand * Faktor pt->mm

	// Zeilenzwischenraum/"Abstand nach" (mm)
	$size['linespace'][1] = 	1.1;
	$size['linespace'][2] = 	1;
	
	// Textausrichtung
	define('TEXTALIGN','L');

	// Padding direkt um den Text
	$size['padding']['cell'] = 0;

//------------------------------------------------------------------
// Detailabstaende/-versaetze Symbole
//------------------------------------------------------------------

	// Texteinzug Lebensdaten
	$size['indent']['dates'] = 	2.5;
	
	// Symbole Geschlecht (maennlich und weiblich bzw. beide)
	$size['offset']['symbol']['sex']['x'] = -2.5-0.449;
	$size['offset']['symbol']['sex_male']['x'] = -2.5-0.664;
	$size['offset']['symbol']['sex_female']['x'] = $size['offset']['symbol']['sex']['x'];
	$size['offset']['symbol']['sex']['y'] = 0;

	$size['offset']['symbol']['dates']['x'] = 0;
	$size['offset']['symbol']['dates']['y'] = 0;
	$size['offset']['symbol']['birth']['x'] = -0.046;
	$size['offset']['symbol']['birth']['y'] = 0;
	$size['offset']['symbol']['death']['x'] = 0.04;
	$size['offset']['symbol']['death']['y'] = 0;
	$size['offset']['symbol']['marriage']['x'] = 0.2;
	$size['offset']['symbol']['marriage']['y'] = 0;
	$size['offset']['symbol']['divorce']['x'] = -0.2;
	$size['offset']['symbol']['divorce']['y'] = 0;


//------------------------------------------------------------------
// Copyright / Logo
//------------------------------------------------------------------

	define('SHOW_LOGO',1);
	define('DEFAULT_LOGO','logo_t7even_grey.ai');
	$size['size']['logo']['x'] = 30;
	$size['size']['logo']['y'] = 15;
	


}else{


//------------------------------------------------------------------
// Offsets, Margins
//------------------------------------------------------------------
	//Seitenränder
	$size['margin']['header'] = 10;
	$size['margin']['top'] = 30;
#	$size['margin']['left'] = 10;
	$size['margin']['left'] = 15;
#	$size['margin']['right'] = 10;
	$size['margin']['right'] = 15;
	$size['margin']['bottom'] = 25;
	$size['margin']['footer'] = 5;

	// Koordinaten erste Box
	$size['offset']['box']['x'] = 0;
	$size['offset']['box']['y'] = 0;


 

//------------------------------------------------------------------
// Aussehen Boxen
//------------------------------------------------------------------
	// Mindestbreite Box ( > breite datumsangaben)
	$size['box']['min_width'] = 20;


	// Innenabstand der "Box" festlegen
#	$size['padding']['box'] = 1;
	$size['padding']['box'] = 0;

	// Aussenabstand der "Box" festlegen
#	$size['margin']['box'] = 2.5;
	$size['margin']['box']['x'] = 8;
#	$size['margin']['box']['x'] = 0;
	$size['margin']['box']['y'] = 3.15;
#	$size['margin']['box']['y'] = 0;

	
	// Rahmen ein-/ausschalten bzw. -position
	define('BOX_BORDER',0); 		// 0 = keine, 1 = alle, TRBL = einzelne/mehrere
	define('BORDER_NAMES',0); 		// 0 = keine, 1 = alle, TRBL = einzelne/mehrere
	define('BORDER_DATES',0); 		// 0 = keine, 1 = alle, TRBL = einzelne/mehrere

	
	// Rahmenfarbe/-stil
	define('LINETYPE_BOX',0); 		// 0=solid
	$size['line']['box'] = 0.13;
	define('LINECOLOR_BOX',0);
	
	// Hintergrundfuellfarbe festlegen
	$style['box']['bg'] = 0;  	// 0 = transparent, 1 = deckend
#	$style['box']['color'] = array(250); // grey
	$style['box']['color'] = array(250,234,247); // colored


//------------------------------------------------------------------
// Rahmen-/Linienstile
//------------------------------------------------------------------
	// Namen
	$size['line']['title'] = 0.13;
	define('LINE_TITLES',0); // 0=none, B=bottom, L=left etc.
	define('LINETYPE_TITLES',0); // 0=solid
	define('LINECOLOR_TITLES',120);
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
#	$size['line']['love'] = 0.13;
	$size['line']['love'] = 0.25;
	$size['line']['love_dash']['dot'] = $size['line']['love'] / PT_MM * 0.02;
#	$size['line']['love_dash']['dot'] = $size['line']['love'] / PT_MM * 0.25;
#	$size['line']['love_dash']['gap'] = $size['line']['love'] / PT_MM * 1.6;
	$size['line']['love_dash']['gap'] = $size['line']['love'] / PT_MM * 4;
	define('LINETYPE_LOVE','"'.($size['line']['love'] / PT_MM * 0.02).','.($size['line']['love'] / PT_MM * 1.6).'"'); // =dashed 
	#define('LINETYPE_LOVE','0.01,0.98'); // =dashed 
#	define('LINECOLOR_LOVE',120);
#	define('LINECOLOR_LOVE',80);
	define('LINECOLOR_LOVE',0);

	// Linienenden (round, solid, butt)
	define('LINESYTYLE_CAP','round');
	define('LINESYTYLE_JOIN','round');

//------------------------------------------------------------------
// Detailabstaende/-versaetze Linien
//------------------------------------------------------------------

  // Linien
	$size['length']['line']['vertical'] = 3.5;
  
	$size['offset']['line']['vertical']['x'] = 1.5;
	$size['offset']['line']['vertical']['y'] = 0;
	$size['offset']['line']['children']['x'] = 1.5;
	$size['offset']['line']['children']['y'] = 0;
	$size['offset']['line']['partner']['x'] = 1.5;
	$size['offset']['line']['partner']['y'] = 0;
	
	$size['offset']['line']['rel']['x'] = 2;
	$size['offset']['line']['rel']['y'] = 0;
	$size['length']['line']['rel'] = 5;
	


//------------------------------------------------------------------
// Textformatierung und -abstaende
//------------------------------------------------------------------
	
	// Schriftarten (case insensitive)
	// default verfügbar: 
	//	 	Times 		(times,timesb,timesi,timesbi)
	//		Helvetica (helvetica,helveticab,helveticai,helveticabi)
	//		Courier 	(courier,courierb,courieri,courierbi) 
	//		Symbol		(symbol)
	//		ZapfDingbats (zapfdingbats)
	// bzw deren Synonyme?: fixed-width, sans serif, serif, symbolic, 

	// Schriftart je Seitenbereich/Element
	$style['font']['title'] =		'metabkr';
	$style['font']['subtitle'] =	'metabkr';
	$style['font']['names'] =		'metabkr';
	$style['font']['dates'] =		'metabkr';
	$style['font']['footer'] =		'metabkr';

	//Schriftfarbe
	$style['font']['color']['default'] = array(0); // black
#	$style['font']['color']['unknown'] = array(210); // grey
#	$style['font']['color']['unknown'] = array(170); // grey
#	$style['font']['color']['unknown'] = array(120); // grey	
	$style['font']['color']['unknown'] = array(0); // grey
	
	// Schriftgröße (pt) je Seitenbereich/Element
#	$size['font']['title'] = 	18;
	$size['font']['title'] = 	30;
#	$size['font']['sub'] = 		13;
	$size['font']['sub'] = 		20;
	$size['font']['names'] = 	13;
	$size['font']['dates'] =	8;
#	$size['font']['footer'] =	8;
	$size['font']['footer'] =	13;
	
	// Zeilenhoehe (mm) - dient zur Berechnung der Boxhoehe / Positionierung Zeilen
#	$size['leading'][1] =	$size['font']['names'] * $min_leading + 0; // Minimal-Zeilenhoehe + custom wert
#	$size['leading'][2] =	$size['font']['dates'] * $min_leading + 0; // Minimal-Zeilenhoehe + custom wert
	$size['leading']['title'] =	$size['font']['title'] * K_CELL_HEIGHT_RATIO * 1.5 * PT_MM; // Fontsize(pt) * Proportion Zeile * Faktor Zeilenabstand * Faktor pt->mm
	$size['leading']['sub'] =	$size['font']['title'] * K_CELL_HEIGHT_RATIO * 1.2 * PT_MM; // Fontsize(pt) * Proportion Zeile * Faktor Zeilenabstand * Faktor pt->mm
	$size['leading']['footer'] =	$size['font']['title'] * K_CELL_HEIGHT_RATIO * 1.2 * PT_MM; // Fontsize(pt) * Proportion Zeile * Faktor Zeilenabstand * Faktor pt->mm
	$size['leading'][1] =	$size['font']['names'] * K_CELL_HEIGHT_RATIO * 1.077 * PT_MM; // Fontsize(pt) * Proportion Zeile * Faktor Zeilenabstand * Faktor pt->mm
	$size['leading'][2] =	$size['font']['dates'] * K_CELL_HEIGHT_RATIO * 1.374 * PT_MM; // Fontsize(pt) * Proportion Zeile * Faktor Zeilenabstand * Faktor pt->mm

	// Zeilenzwischenraum/"Abstand nach" (mm)
	$size['linespace'][1] = 	1.1;
	$size['linespace'][2] = 	1;
	
	// Textausrichtung
	define('TEXTALIGN','L');

	// Padding direkt um den Text
	$size['padding']['cell'] = 0;

//------------------------------------------------------------------
// Detailabstaende/-versaetze Symbole
//------------------------------------------------------------------

	// Texteinzug Lebensdaten
	$size['indent']['dates'] = 	2.5;
	
	// Symbole Geschlecht (maennlich und weiblich bzw. beide)
	$size['offset']['symbol']['sex']['x'] = -2.5-0.449;
	$size['offset']['symbol']['sex_male']['x'] = -2.5-0.664;
	$size['offset']['symbol']['sex_female']['x'] = $size['offset']['symbol']['sex']['x'];
	$size['offset']['symbol']['sex']['y'] = 0;

	$size['offset']['symbol']['dates']['x'] = 0;
	$size['offset']['symbol']['dates']['y'] = 0;
	$size['offset']['symbol']['birth']['x'] = -0.046;
	$size['offset']['symbol']['birth']['y'] = 0;
	$size['offset']['symbol']['death']['x'] = 0.04;
	$size['offset']['symbol']['death']['y'] = 0;
	$size['offset']['symbol']['marriage']['x'] = 0.2;
	$size['offset']['symbol']['marriage']['y'] = 0;
	$size['offset']['symbol']['divorce']['x'] = -0.2;
	$size['offset']['symbol']['divorce']['y'] = 0;


//------------------------------------------------------------------
// Copyright / Logo
//------------------------------------------------------------------

	define('SHOW_LOGO',1);
	define('DEFAULT_LOGO','logo_t7even_grey.ai');
	$size['size']['logo']['x'] = 30;
	$size['size']['logo']['y'] = 15;

}
?>
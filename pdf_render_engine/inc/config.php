<?php
// Pfade festlegen
#	define('PATH_ROOT','/kunden/115537_10435/sites/t7even.com_dev/treeprint/pdf_render_engine/');
	define('PATH_ROOT','');
	define('FPDF_FONTPATH',PATH_ROOT.'fonts/');
	define('PATH_IMAGES',PATH_ROOT.'img/');
	define('PATH_TEMPLATES',PATH_ROOT.'themes/');
#	define('DEFAULT_TEMPLATE','minimal_meta');
#	define('DEFAULT_TEMPLATE','standard');
#	define('PATH_TEMPLATE',PATH_TEMPLATES.DEFAULT_TEMPLATE.'/');
#	define('PATH_TEMPLATE',PATH_ROOT.'themes/standard/');
	define('PATH_TEMPLATE',PATH_ROOT.'themes/standard_meta/');
	
	define('DEFAULT_SRC','src/dummy.xml');


#	define('DEFAULT_LANG','de');
#	define('DEFAULT_CONTENT','inc/description_'.DEFAULT_LANG.'.php');
	define('DEFAULT_CONTENT','inc/description_de.php');


	#define('DEFAULT_NAMELENGTH','default');
	$a_name_lengths = array('full','firstFullSecondFull','firstFullSecondAbbr','firstFull','firstAbbr');

	# Datumsdetails (genaues datum oder nur jahr)
	define('DEFAULT_DATE','date'); 
	
	define('SHOW_SEX',1);
	define('SHOW_FIRSTNAME',1);
	define('SHOW_LASTNAME',1);
	define('SHOW_TITLE',0);
	define('SHOW_BIRTHDATE',1);
	define('SHOW_DEATHDATE',1);
	define('SHOW_AGE',1);

		
	// Array Lines setzen
	$hlines			= array();
	$vlines			= array();
	
	// Zaehler setzen
	$i_h				= 1;


	define('K_TCPDF_EXTERNAL_CONFIG',1);
#	define('K_PATH_MAIN', '/kunden/115537_10435/sites/t7even.com_dev/treeprint/pdf_render_engine/tcpdf/');
	define('K_PATH_MAIN', 'tcpdf/');
#	define('K_PATH_URL', 'http://t7even.com/treeprint/pdf_render_engine/tcpdf/');																# URL path to tcpdf installation folder
	define('K_PATH_URL', 'http://127.0.0.1/tp/pdf_render_engine/tcpdf/');																# URL path to tcpdf installation folder
	define('K_PATH_FONTS', K_PATH_MAIN.'fonts/');
	define('K_PATH_CACHE', K_PATH_MAIN.'cache/');
	define('K_PATH_URL_CACHE', K_PATH_URL.'cache/');
	define('K_PATH_IMAGES', K_PATH_MAIN.'images/');
	define('K_BLANK_IMAGE', K_PATH_IMAGES.'_blank.png');

	define('PDF_PAGE_FORMAT', 'A4');
#	define('PDF_PAGE_ORIENTATION', 'P');
	

	define('PDF_CREATOR', 'treeprint.com');
	define('PDF_AUTHOR', 't7even');
	define('PDF_HEADER_TITLE', 'TCPDF Example');
	define('PDF_HEADER_STRING', 'by Nicola Asuni - Tecnick.com\nwww.tcpdf.org');
	define('PDF_HEADER_LOGO', 'tcpdf_logo.jpg');
	define('PDF_HEADER_LOGO_WIDTH', 30);

	define('PDF_UNIT', 'mm');
	define('PT_MM',0.3528);
	
	define('PDF_FONT_NAME_MAIN', 'helvetica');
	define('PDF_FONT_SIZE_MAIN', 10);
	define('PDF_FONT_NAME_DATA', 'helvetica');
	define('PDF_FONT_SIZE_DATA', 8);

	define('PDF_FONT_MONOSPACED', 'courier');
	
	define('PDF_IMAGE_SCALE_RATIO', 1);
	
	define('HEAD_MAGNIFICATION', 1.1);
	
	# height of cell respect font height
#	define('K_CELL_HEIGHT_RATIO', 1.25);
	define('K_CELL_HEIGHT_RATIO', 1);
	
	# title magnification respect main font size
	define('K_TITLE_MAGNIFICATION', 1.3);
	
	# reduction factor for small font
	define('K_SMALL_RATIO', 2/3);
	

?>
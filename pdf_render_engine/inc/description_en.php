<?php
// Header
	// Descendants of FIRSTNAME LASTNAME
#	$content_title = 'Descendants of '.$xml->generations->generation->person->firstname->full.' '.$xml->generations->generation->person->lastname->full;

	// Pedigree/Descendants of FIRSTNAME LASTNAME (BIRTHYEAR - DEATHYEAR)
	if($xml->generations['type'] == 'd')	$content_type = 'Descendants';
	else									$content_type = 'Pedigree';
	
	$content_title = $content_type.' of '.$xml->generations->generation->person->firstname->full.' '.$xml->generations->generation->person->lastname->full.' ('.$xml->generations->generation->person->birth->year.' – '.$xml->generations->generation->person->death->year.')';


	// XX persons over XX generations in total 
#	$content_stats = $xml->vars->count->people.' persons over '.$xml->vars->count->generations.' generations in total';
	// XX persons over XX generations
	$content_stats = number_format((int)$xml->vars->count->people,0,',','.').' persons over '.$xml->vars->count->generations.' generations';


// Footer
	// FILENAME.ged MM/DD/YYYY
	$content_footer = $xml->vars->gedcom->file.' '. date('n/j/Y', strtotime($xml->vars->gedcom->timestamp));
	
	// 	Generated on MONTH D, YYYY';
	$content_footer = 'Generated on '.date('F jS, Y', strtotime($xml->vars->projectedDate));
?>
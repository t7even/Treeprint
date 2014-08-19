<?php
// Header
	// Stammbaum von VORNAME NACHNAME
#	$content_title = 'Stammbaum von '.$xml->generations->generation->person->firstname->full.' '.$xml->generations->generation->person->lastname->full;

	// Ahnentafel/Nachfahrentafel des/der VORNAME NACHNAME (GEBURTSJAHR – STERBEJAHR)
	if($xml->generations['type'] == 'd')	$content_type = 'Nachfahrentafel';
	else									$content_type = 'Ahnentafel';
	
	if($xml->generations->generation->person->sex == 'm')	$stats_of = 'des';
	else													$stats_of = 'der';
	
	$content_title = $content_type.' '.$stats_of.' '.$xml->generations->generation->person->firstname->full.' '.$xml->generations->generation->person->lastname->full.' ('.$xml->generations->generation->person->birth->year.' – '.$xml->generations->generation->person->death->year.')';

	
	// Insgesamt XX Personen in XX Generationen (X Spalten)
#	$content_stats = 'Insgesamt '.$xml->vars->count->people.' Personen in '.$xml->vars->count->generations.' Generationen ('.$xml->vars->count->columns.' Spalten)';
	// XX Personen über XX Generationen
	$content_stats = number_format((int)$xml->vars->count->people,0,',','.').' Personen über '.$xml->vars->count->generations.' Generationen';
	


// Footer
	// XML generiert aus der Datei DATEINAME.ged vom DD.MM.YYYY um HH:MM:SS Uhr ';
#	$content_footer = 'XML generiert aus der Datei \''.$xml->vars->gedcom->file.'\' vom '.date('j.n.Y \u\m H:i:s', strtotime($xml->vars->gedcom->timestamp)).' Uhr';
	
	// 	Erzeugt am DD.MM.YYYY';
	$content_footer = 'Erzeugt am '.date('d.m.Y', strtotime($xml->vars->projectedDate));
?>
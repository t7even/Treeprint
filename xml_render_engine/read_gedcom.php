<?
	$time = microtime(true);
	//Predefined Vars
	$abs_x = 0;
	$abs_y = 0;
	$div_w = 75;
	$div_h = 30;
	$pad = 12;
	$months = array("jan" => 1,"feb" => 2,"mar" => 3,"apr" => 4,"may" => 5,"jun" => 6,"jul" => 7,"aug" => 8,"sep" => 9,"oct" => 10,"nov" => 11,"dec" => 12);

	//FUNCTIONS
	function calc_age($date1,$date2="") {
		//Funktion zur Kalkulation des Alters einer Person
		//Ausgabe: Array(int,str(y|m|d))
		$month_diff = array(0,3,0,1,0,1,0,0,1,0,1,0);
		if(empty($date2)) $date2 = date("Y-n-d");
		$date1 = explode("-",$date1);
		$date2 = explode("-",$date2);
		$age = $date2[0]-$date1[0];
		if(($date2[1]-$date1[1])<0) $age--;
		if(($date2[1]-$date1[1])==0 && ($date2[2]-$date1[2])<0) $age--;
		if($age>0) return array($age,"y"); //Ausgabe, wenn älter als ein Jahr
		$age = $date2[1]-$date1[1];
		if($age<=0) $age=12+$age;
		if(($date2[2]-$date1[2])<0) $age--;
		if($age>0) return array($age,"m"); //Ausgabe, wenn älter als ein Monat
		$age = $date2[2]-$date1[2];
		if($age<0) {
			if(!$date2[0]%4 && $date2[1]==3) $month_diff[1] = 2;
			$age = $date2[2] + ((31 - $month_diff[$date1[1]-1]) - $date1[2]);
		}
		return array($age,"d"); //Ausgabe, wenn jünger als ein Monat
	}
	
	function render_agen($id,$l,$rel="",$arel="") {
		//Funktion zur Generierung eines Vorfahrenbaums
		//Ausgabe: Vorfahren werden in globaler Variable $generation ausgegeben
		if(!isset($a)) $a=0;
		if(!isset($generation)) $generation = array();
		global $a,$generation,$individual,$family;
		$a++;
		//Anlegen der bezeichneten Person
		$generation[$l][]["id"] = $id;
		$generation[$l][count($generation[$l])-1]["rel"] = $rel;
		$generation[$l][count($generation[$l])-1]["a"] = $a;
		$generation[$l][count($generation[$l])-1]["arel"] = $arel;
		$generation[$l][count($generation[$l])-1]["name"] = $individual[$id]["firstname"]." ".$individual[$id]["lastname"];
		if(count($individual[$id]["fam_child_of"])) {
			$b=$a;
			if($family[$individual[$id]["fam_child_of"]]["husband"]) render_agen($family[$individual[$id]["fam_child_of"]]["husband"],$l+1,$id,$b);
			if($family[$individual[$id]["fam_child_of"]]["wife"]) render_agen($family[$individual[$id]["fam_child_of"]]["wife"],$l+1,$id,$b);
		}
	}
	
	function render_dgen($id,$l,$rel="",$arel="") {
		//Funktion zur Generierung eines Nachkommenbaums
		//Ausgabe: Nachkommen werden in globaler Variable $generation ausgegeben
		if(!isset($a)) $a=0;
		if(!isset($generation)) $generation = array();
		global $a,$generation,$individual,$family;
		$a++;
		//Anlegen der bezeichneten Person
		$generation[$l][]["id"] = $id;
		$generation[$l][count($generation[$l])-1]["rel"] = $rel;
		$generation[$l][count($generation[$l])-1]["a"] = $a;
		$generation[$l][count($generation[$l])-1]["arel"] = $arel;
		$generation[$l][count($generation[$l])-1]["name"] = $individual[$id]["firstname"]." ".$individual[$id]["lastname"];
		//Anlegen der Heiratsbeziehungen
		if(count($individual[$id]["fam_parent_of"])) {
			foreach($individual[$id]["fam_parent_of"] as $k => $v) {
				if($family[$v]["husband"] == $id) $partner_id = $family[$v]["wife"];
				else $partner_id = $family[$v]["husband"];
				$generation[$l+1][]["id"] = $partner_id;
				$generation[$l+1][count($generation[$l+1])-1]["rel"] = $id;
				$b=$generation[$l][count($generation[$l])-1]["a"]; //fortlaufende ID des ersten Partners sichern
				$a++; //fortlaufende ID iterieren
				$c=$a; //fortlaufende ID des zweiten Partners sichern
				$generation[$l+1][count($generation[$l+1])-1]["a"] = $a;
				$generation[$l+1][count($generation[$l+1])-1]["arel"] = $b;
				$generation[$l+1][count($generation[$l+1])-1]["name"] = $individual[$partner_id]["firstname"]." ".$individual[$partner_id]["lastname"];
				//Anlegen der Kinder
				if(count($family[$v]["children"])) {
					foreach($family[$v]["children"] as $k => $v) {
						render_dgen($v,$l+2,$partner_id,$c);
					}
				}
			}
		}
	}
	
	function rel_pos($id) {
		//Funktion zur Bestimmung der Position von Relatives
		global $generation;
		foreach($generation as $k => $v) {
			foreach($v as $sk => $sv) {
				if($sv["a"]==$id) return $sv["pos"];
			}
		}
	}

	//Definition GEDCOM-Datei
	if(empty($_GET["src"])) $gedcom_file = "schmidt.ged";
	else $gedcom_file = $_GET["src"];
	
	//GEDCOM-File einlesen
	$gedcom = implode('', file('ged/'.$gedcom_file));
	
	//Source lesen
	preg_match_all("/(0 HEAD\r?\n([1-9]+ .*\r?\n)*)/im",$gedcom,$gedcom_head,PREG_SET_ORDER);
	preg_match_all("/1 SOUR (.*)\r?\n([2-9]+ .*\r?\n)*/im",$gedcom_head[0][1],$gedcom_head_source,PREG_SET_ORDER);
	$gedcom_source_name = $gedcom_head_source[0][1];
	if(!empty($gedcom_head_source[0][2])) preg_match_all("/2 VERS (.*)\r?\n/im",$gedcom_head_source[0][0],$gedcom_head_source_vers,PREG_SET_ORDER);
	$gedcom_source_version = $gedcom_head_source_vers[0][1];
	preg_match_all("/1 DATE (.*)\r?\n([2-9]+ .*\r?\n)*/im",$gedcom_head[0][1],$gedcom_head_date,PREG_SET_ORDER);
	$gedcom_date = $gedcom_head_date[0][1];
	if(!empty($gedcom_head_date[0][2])) preg_match_all("/2 TIME (.*)\r?\n/im",$gedcom_head_date[0][2],$gedcom_head_time,PREG_SET_ORDER);
	$gedcom_time = $gedcom_head_time[0][1];
	preg_match_all("/1 GEDC\r?\n([2-9]+ .*\r?\n)*/im",$gedcom_head[0][1],$gedcom_head_gedc,PREG_SET_ORDER);
	preg_match_all("/2 VERS (.*)\r?\n/im",$gedcom_head_gedc[0][0],$gedcom_head_ver,PREG_SET_ORDER);
	$gedcom_ver = $gedcom_head_ver[0][1];
	preg_match_all("/2 FORM (.*)\r?\n/im",$gedcom_head_gedc[0][0],$gedcom_head_ver,PREG_SET_ORDER);
	$gedcom_form = $gedcom_head_ver[0][1];
	preg_match_all("/1 CHAR (.*)\r?\n/im",$gedcom_head[0][1],$gedcom_head_char,PREG_SET_ORDER);
	$gedcom_char = $gedcom_head_char[0][1];
	
	//Individuals erfassen
	preg_match_all("/(0 \@I([0-9]+)\@ INDI\r?\n([^0].*\r?\n)+)/im",$gedcom,$gedcom_individual,PREG_SET_ORDER);
	//gedcom_individual[x][1] = GEDCOM of individual
	//gedcom_individual[x][2] = GEDCOM_ID of individual
	if(count($gedcom_individual)) {
		foreach($gedcom_individual as $k => $v) {
			unset($name);unset($sex);unset($birthday);unset($deathday);unset($fams);unset($famc);
			preg_match("/1 NAME ([^\/]+) \/([^\/]+)\/\r?\n(2 GIVN (.+))?/i",$v[1],$name);
			$individual[$v[2]]["firstname"] = utf8_encode(trim($name[1]));
			$individual[$v[2]]["lastname"] = utf8_encode(trim($name[2]));
			if(!empty($name[4]))
				$individual[$v[2]]["firstname"] = utf8_encode(trim($name[4]));
			preg_match("/1 SEX (M|F)/i",$v[1],$sex);
			$individual[$v[2]]["sex"] = strtolower($sex[1]);
			preg_match("/1 BIRT\r?\n2 DATE ([0-9]{1,2}) ([A-Z]{3}) ([0-9]{1,4})/i",$v[1],$birthday);
			if(count($birthday)) $individual[$v[2]]["birthday"] = $birthday[3]."-".$months[strtolower($birthday[2])]."-".intval($birthday[1]);
			preg_match("/1 DEAT\r?\n2 DATE ([0-9]{1,2}) ([A-Z]{3}) ([0-9]{1,4})/i",$v[1],$deathday);
			if(count($deathday)) $individual[$v[2]]["deathday"] = $deathday[3]."-".$months[strtolower($deathday[2])]."-".intval($deathday[1]);
			if(!empty($individual[$v[2]]["deathday"]) && !empty($individual[$v[2]]["birthday"])) $individual[$v[2]]["age"] = calc_age($individual[$v[2]]["birthday"],$individual[$v[2]]["deathday"]);
			else if(!empty($individual[$v[2]]["birthday"]) && !preg_match("/1 DEAT/i",$v[1])) $individual[$v[2]]["age"] = calc_age($individual[$v[2]]["birthday"]);
			preg_match_all("/1 FAMS \@F([0-9]+)\@/i",$v[1],$fams);
			foreach($fams[1] as $sk => $sv) 
				$individual[$v[2]]["fam_parent_of"][] = $sv;
			preg_match("/1 FAMC \@F([0-9]+)\@/i",$v[1],$famc);
			$individual[$v[2]]["fam_child_of"] = intval($famc[1]);
			if($_GET["adop"]) {
				//Nach Adoptivkinder suchen
//				preg_match_all("/1 ADOP Y\r?\n([2-9]+ .*\r?\n)*/im",$v[1],$adop,PREG_SET_ORDER);
//				if(!empty($adop[0][0])) preg_match_all("/2 FAMC \@F([0-9]+)\@/i",$adop[0][0],$famc_adop,PREG_SET_ORDER);
//				if(count($famc_adop)) {
//					$individual[$v[2]]["fam_child_of"] = intval($famc_adop[0][1]);
//					$family[intval($famc_adop[0][1])]["children"][] = $v[2];
//					unset($famc_adop);
//				}
			}
		}
	}

	//Families erfassen
	preg_match_all("/(0 \@F([0-9]+)\@ FAM\r?\n([^0].*\r?\n)+)/im",$gedcom,$gedcom_family,PREG_SET_ORDER);
	//gedcom_family[x][1] = GEDCOM of family
	//gedcom_family[x][2] = GEDCOM_ID of family
	if(count($gedcom_family)) {
		foreach($gedcom_family as $k => $v) {
			preg_match("/1 HUSB \@I([0-9]+)\@/i",$v[1],$husband);
			$family[$v[2]]["husband"] = $husband[1];
			//$family[$v[2]]["husband"] = $individual[$husband[1]]["firstname"]." ".$individual[$husband[1]]["lastname"];
			preg_match("/1 WIFE \@I([0-9]+)\@/i",$v[1],$wife);
			$family[$v[2]]["wife"] = $wife[1];
			//$family[$v[2]]["wife"] = $individual[$wife[1]]["firstname"]." ".$individual[$wife[1]]["lastname"];
			preg_match_all("/1 CHIL \@I([0-9]+)\@/i",$v[1],$children);
			foreach($children[1] as $sk => $sv) {
				$family[$v[2]]["children"][] = $sv;
				//$family[$v[2]]["children"][] = $individual[$sv]["firstname"]." ".$individual[$sv]["lastname"];
			}
		}
	}
		
	if(empty($_GET["i"])) $_GET["i"] = 5; //72 = Andreas Schmidt, 5 = David Schmidt
	if(empty($_GET["m"])) $_GET["m"] = "d"; //a = ancestors., d = descendants
	if(empty($_GET["g"])) $_GET["g"] = 10; //Anzahl der Generationen (0 = max)

	print "<html>";
	print "<head>";
	print "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	print "</head>";
	print "<body>";
	print "<h1>read_gedcom.php</h1>";
	print "<p>";
	printf("GEDCOM File: %s<br />",$gedcom_file);
	printf("GEDCOM Version: %s<br />",$gedcom_ver);
	printf("GEDCOM Form: %s<br />",$gedcom_form);
	printf("GEDCOM Encoding: %s<br />",$gedcom_char);
	print "<br />";
	printf("file exported by: %s%s<br />",$gedcom_source_name," ".$gedcom_source_version);
	printf("at %s%s",$gedcom_date," ".$gedcom_time);
	print "</p>";
	//printf("<h2>Beispiel anhand der %s von %s</h2>",($_GET["m"]=="a")?"Vorfahren":"Nachkommen",$individual[$_GET["i"]]["firstname"]." ".$individual[$_GET["i"]]["lastname"]);
	if($_GET["m"] == "a") { //Vorfahrenbaum wird angefordert
		//Rendert die Vorfahren abgehend von der vorgegebenen Person
		render_agen($_GET["i"],0,0);
		
		//Anzahl der Generationen per Parameter, sonst max
		if($_GET["g"]<=count($generation) && $_GET["g"]>0) $gen_anzahl = $_GET["g"];
		else $gen_anzahl = count($generation);
		print "<p>Anzahl Generationen: $gen_anzahl <br />";

		//Sind Generationen vorhanden und ist eine Anzahl von Generationen vorgegeben,
		//wird das Array entsprechend freigestellt
		if(count($generation)) {
			$generation = array_slice($generation,0,$gen_anzahl);
			//Zählt die Gesamtzahl von Personen
			foreach($generation as $k => $v) {
				foreach($v as $sk => $sv) {
					if($sv["id"]!=0) $pers_anzahl++;
				}
			}
		}
		print "Anzahl Personen: $pers_anzahl <br />";

		//spiegelt die Reihenfolge der auszugebenden Generationen
		$generation = array_reverse($generation, true);

		//Berechnung der Breiten
		foreach($generation as $k => $v) {
			foreach($v as $sk => $sv) {
				if(count($generation[($k+1)])) {
					foreach($generation[($k+1)] as $ssk => $ssv) {
						if($sv["a"]==$ssv["arel"]) $generation[$k][$sk]["width"] += $generation[($k+1)][$ssk]["width"];
					}
				}
				if(empty($generation[$k][$sk]["width"]) && $generation[$k][$sk]["id"]!=0) $generation[$k][$sk]["width"] = 1;
			}
		}

		//spiegelt die Reihenfolge der auszugebenden Generationen
		$generation = array_reverse($generation, true);

		//Setzt Position innerhalb einer Generation unabhängig von Vor- oder Nachgeneration
		$sum_width = 0;
		foreach($generation as $k => $v) {
			foreach($v as $sk => $sv) {
				$generation[$k][$sk]["pos"] = $sum_width;
				$sum_width += $sv["width"];
			}
			$sum_width = 0;
		}

		//Berechnet Position abhängig von Vorgenerationen
		foreach($generation as $k => $v) {
			foreach($v as $sk => $sv) {
				if(rel_pos($sv["arel"]) > $generation[$k][$sk]["pos"]) {
					$generation[$k][$sk]["pos"] = rel_pos($sv["arel"]);
					$generation[$k][$sk+1]["pos"] = rel_pos($sv["arel"])+$generation[$k][$sk]["width"];
				}
			}
		}

		print "Anzahl Spalten: ".$generation[0][0]["width"]."</p>";

		//spiegelt die Reihenfolge der auszugebenden Generationen
		$generation = array_reverse($generation, true);

		//Gibt Vorfahrenbaum aus
		printf("<div id=\"familytree\" style=\"position:relative;height:%spx;\">",(($div_h+$pad)*$gen_anzahl)+30);
		foreach($generation as $k => $v) {
			foreach($v as $sk => $sv) {
				printf("<div id=\"ind%s\" style=\"padding:2px;border:1px solid #ccc;font-size:10px;position:absolute;width:%spx;height:%spx;top:%spx;left:%spx;\"><a style=\"text-decoration:none;\" href=\"%s?i=%s&m=a&src=%s\">▲</a> %s<br /><a style=\"text-decoration:none;\" href=\"%s?i=%s&m=d&src=%s\">▼</a> %s (%s)</div>",$sv["id"],(($div_w+$pad)*$sv["width"])-$pad,$div_h,($div_h+$pad)*abs($k-count($generation)),($div_w+$pad)*$generation[$k][$sk]["pos"],$_SERVER["PHP_SELF"],$sv["id"],$gedcom_file,$individual[$sv["id"]]["firstname"],$_SERVER["PHP_SELF"],$sv["id"],$gedcom_file,$individual[$sv["id"]]["lastname"],$individual[$sv["id"]]["age"][0].$individual[$sv["id"]]["age"][1]);
			}
		}
		print "</div>";
		
	} else if($_GET["m"] == "d") { //Nachkommenbaum wird angefordert
		//Rendert die Nachkommen abgehend von der vorgegebenen Person
		render_dgen($_GET["i"],0);

		//Anzahl der Generationen per Parameter, sonst max
		if($_GET["g"]<=ceil(count($generation)/2) && $_GET["g"]>0) $gen_anzahl = $_GET["g"];
		else $gen_anzahl = ceil(count($generation)/2);
		print "<p>Anzahl Generationen: $gen_anzahl <br />";
		
		//Sind Generationen vorhanden und ist eine Anzahl von Generationen vorgegeben,
		//wird das Array entsprechend freigestellt
		if(count($generation)) {
			$generation = array_slice($generation,0,($gen_anzahl*2)-1);
			//Zählt die Gesamtzahl von Personen
			foreach($generation as $k => $v) {
				foreach($v as $sk => $sv) {
					if($sv["id"]!=0) $pers_anzahl++;
				}
			}
		}
		print "Anzahl Personen: $pers_anzahl <br />";

		//spiegelt die Reihenfolge der auszugebenden Generationen
		$generation = array_reverse($generation, true);

		//Berechnung der Breiten
		foreach($generation as $k => $v) {
			foreach($v as $sk => $sv) {
				if(empty($generation[$k][$sk]["width"]) && $generation[$k][$sk]["id"]!=0) $generation[$k][$sk]["width"] = 1;
				if(count($generation[($k-1)])) {
					foreach($generation[($k-1)] as $ssk => $ssv) {
						if($ssv["a"]==$sv["arel"]) $generation[($k-1)][$ssk]["width"] += $generation[$k][$sk]["width"];
					}
				}
			}
		}

		//spiegelt die Reihenfolge der auszugebenden Generationen
		$generation = array_reverse($generation, true);
		
		//Setzt Position innerhalb einer Generation unabhängig von Vor- oder Nachgeneration
		$sum_width = 0;
		foreach($generation as $k => $v) {
			foreach($v as $sk => $sv) {
				$pos[$sv["a"]] = $sum_width;
				$sum_width += $sv["width"];
			}
			$sum_width = 0;
		}

		//Korrigiert die Position innerhalb der Familie
		foreach($generation as $k => $v) {
			foreach($v as $sk => $sv) {
				if($pos[$sv["a"]]>=$pos[$sv["arel"]]) 
					$npos[$sv["a"]] = $pos[$sv["a"]] - $pos[$sv["arel"]];
			}
		}

		//Berechnet Position abhängig von Vorgenerationen
		foreach($generation as $k => $v) {
			foreach($v as $sk => $sv) {
				$npos[$sv["a"]] += $npos[$sv["arel"]];
				if($npos[$sv["a"]] <= ($npos[$generation[$k][$sk-1]["a"]] + $generation[$k][$sk-1]["width"])) {
					$npos[$generation[$k][$sk]["a"]] = ($npos[$generation[$k][$sk-1]["a"]] + $generation[$k][$sk-1]["width"]);
				}
				if($npos[$sv["a"]] < $npos[$sv["arel"]]) $npos[$sv["a"]] = $npos[$sv["arel"]];
			}
		}

		print "Anzahl Spalten: ".$generation[0][0]["width"]."</p>";

		//Gibt Nachkommenbaum aus
		printf("<div id=\"familytree\" style=\"position:relative;height:%spx;\">",($div_h+$pad)*(($gen_anzahl*2)-1));
		foreach($generation as $k => $v) {
			foreach($v as $sk => $sv) {
				printf("<div id=\"ind%s\" style=\"padding:2px;border:1px solid #ccc;font-size:10px;position:absolute;width:%spx;height:%spx;top:%spx;left:%spx;\"><a style=\"text-decoration:none;\" href=\"%s?i=%s&m=a&src=%s\">▲</a> %s<br /><a style=\"text-decoration:none;\" href=\"%s?i=%s&m=d&src=%s\">▼</a> %s (%s)</div>",$sv["id"],(($div_w+$pad)*$sv["width"])-$pad,$div_h,($div_h+$pad)*$k,($div_w+$pad)*$npos[$sv["a"]],$_SERVER["PHP_SELF"],$sv["id"],$gedcom_file,$individual[$sv["id"]]["firstname"],$_SERVER["PHP_SELF"],$sv["id"],$gedcom_file,$individual[$sv["id"]]["lastname"],$individual[$sv["id"]]["age"][0].$individual[$sv["id"]]["age"][1]);
			}
		}
		print "</div>";
	}
	$time = microtime(true) - $time;
	print "<p>This request took ".round($time,2)." s.</p>";
	print "</body></html>";
?>
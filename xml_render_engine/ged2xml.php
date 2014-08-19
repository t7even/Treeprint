<?
  //Predefined Vars
  $months = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
  $ged_dir = 'ged/';

/*
  //Definition GEDCOM-Datei
  if(empty($_GET['src'])) $_GET['src'] = 'schmidt.ged';

  //Definition Ursprungsperson
  if(empty($_GET['i'])) $_GET['i'] = 1; //72 = Andreas Schmidt, 5 = David Schmidt
  
  //Definition Modus Vorfahrenbaum oder Nachkommenbaum
  if(empty($_GET['m'])) $_GET['m'] = 'd'; //a = ancestors., d = descendants
  
  //Definition Anzahl Generationen
  if(empty($_GET['g'])) $_GET['g'] = 10; //Anzahl der Generationen (0 = max)

  //Definition Ausgabesprache
  if(empty($_GET['lang'])) $_GET['lang'] = 'de';

  //Definition Datum
  if(empty($_GET['date'])) $_GET['date'] = '2011-08-20';
*/
  
  if(empty($_GET['g']) || $_GET['g'] > 20) $_GET['g'] = 20;
  if(empty($_GET['lang'])) $_GET['lang'] = 'de';
  if(empty($_GET['date'])) $_GET['date'] = date('Y-n-d');

  if(empty($_GET['src']) || !file_exists($ged_dir.$_GET['src']) || empty($_GET['i']) || empty($_GET['m']) || empty($_GET['g']) || empty($_GET['lang'])) input_form();

  //Functions
  function input_form() {
?>
<html>
<head>
  <style>
    body{font:12px Arial;color:#000;line-height:1.6em;}
    body h1:first-child{margin-top:0;}
    h1{font-size:1.5em;margin-top:1.5em}
    h2{font-size:1.2em;}
    form{padding:0;margin:0;}
    label{font-weight:bold;float:left;display:inline;clear:both;margin-top:0.5em;}
    input,select{display:block;width:300px;margin-left:100px;margin-top:0.5em;}
    input[type=submit]{margin-top:10px;width:auto;display:block;}
    input.error{color:red;}
  </style>
</head>
<body>
  <h1>GED2XML</h1>
  <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
    <label for="src">Source</label><input type="text" name="src" class="<?=(!empty($_GET['src']) && !file_exists($ged_dir.$_GET['src']))?'error':''?>" value="<?=$_GET['src']?>" />
    <label for="i">ID</label><input type="text" name="i" value="<?=$_GET['i']?>" />
    <label for="m">Mode</label><select name="m">
      <option value="">Please select...</option>
      <option value="a"<?=($_GET['m']=='a')?' selected="selected"':''?>>Ancestors</option>
      <option value="d"<?=($_GET['m']=='d')?' selected="selected"':''?>>Descendants</option>
    </select>
    <label for="g">Generations</label><input type="text" name="g" value="<?=$_GET['g']?>" />
    <label for="lang">Language</label><select name="lang">
      <option value="">Please select...</option>
      <option value="de"<?=($_GET['lang']=='de')?' selected="selected"':''?>>German</option>
      <option value="en"<?=($_GET['lang']=='en')?' selected="selected"':''?>>English</option>
    </select>
    <input type="submit" value="Get XML" />
  </form>
</body>
</html>
<?
    exit;
  }
  
  function calc_age($date1,$date2='') {
    //Funktion zur Kalkulation des Alters einer Person
    //Ausgabe: Array(int,str(y|m|d))
    $month_diff = array(0,3,0,1,0,1,0,0,1,0,1,0);
    if(empty($date2)) $date = date('Y-n-d');
    $date1 = explode('-',$date1);
    $date2 = explode('-',$date2);
    $age = $date2[0]-$date1[0];
    if(($date2[1]-$date1[1])<0) $age--;
    if(($date2[1]-$date1[1])==0 && ($date2[2]-$date1[2])<0) $age--;
    if($age>0) return array($age,'y'); //Ausgabe, wenn älter als ein Jahr
    $age = $date2[1]-$date1[1];
    if($age<0) $age=12+$age;
    if(($date2[2]-$date1[2])<0) $age--;
    if($age>0) return array($age,'m'); //Ausgabe, wenn älter als ein Monat
    $age = $date2[2]-$date1[2];
    if($age<0) {
      if(!$date2[0]%4 && $date2[1]==3) $month_diff[1] = 2;
      $age = $date2[2] + ((31 - $month_diff[$date1[1]-1]) - $date1[2]);
    }
    return array($age,'d'); //Ausgabe, wenn jünger als ein Monat
  }
  
  function render_agen($id,$l,$rel='',$arel='') {
    //Funktion zur Generierung eines Vorfahrenbaums
    //Ausgabe: Vorfahren werden in globaler Variable $generation ausgegeben
    if(!isset($a)) $a=0;
    if(!isset($generation)) $generation = array();
    global $a,$generation,$individual,$family;
    $a++;
    //Anlegen der bezeichneten Person
    $generation[$l][]['id'] = $id;
    $generation[$l][count($generation[$l])-1]['rel'] = $rel;
    $generation[$l][count($generation[$l])-1]['a'] = $a;
    $generation[$l][count($generation[$l])-1]['arel'] = $arel;
    $generation[$l][count($generation[$l])-1]['name'] = $individual[$id]['firstname'].' '.$individual[$id]['lastname'];

    $generation[$l][count($generation[$l])-1]['relmdate'] = $family[$individual[$rel]['fam_child_of']]['mdate'];
    $generation[$l][count($generation[$l])-1]['relddate'] = $family[$individual[$rel]['fam_child_of']]['ddate'];
    $generation[$l][count($generation[$l])-1]['reltype'] = $family[$individual[$rel]['fam_child_of']]['type'];

    if(count($individual[$id]['fam_child_of'])) {
      $b=$a;
      if($family[$individual[$id]['fam_child_of']]['husband']) render_agen($family[$individual[$id]['fam_child_of']]['husband'],$l+1,$id,$b);
      if($family[$individual[$id]['fam_child_of']]['wife']) render_agen($family[$individual[$id]['fam_child_of']]['wife'],$l+1,$id,$b);
    }
  }
  
  function render_dgen($id,$l,$rel='',$arel='') {
    //Funktion zur Generierung eines Nachkommenbaums
    //Ausgabe: Nachkommen werden in globaler Variable $generation ausgegeben
    if(!isset($a)) $a=0;
    if(!isset($generation)) $generation = array();
    global $a,$generation,$individual,$family;
    $a++;
    //Anlegen der bezeichneten Person
    $generation[$l][]['id'] = $id;
    $generation[$l][count($generation[$l])-1]['rel'] = $rel;
    $generation[$l][count($generation[$l])-1]['a'] = $a;
    $generation[$l][count($generation[$l])-1]['arel'] = $arel;
    $generation[$l][count($generation[$l])-1]['name'] = $individual[$id]['firstname'].' '.$individual[$id]['lastname'];
    //Anlegen der Heiratsbeziehungen
    if(count($individual[$id]['fam_parent_of'])) {
      foreach($individual[$id]['fam_parent_of'] as $k => $v) {
        if($family[$v]['husband'] == $id) $partner_id = $family[$v]['wife'];
        else $partner_id = $family[$v]['husband'];
        $generation[$l+1][]['id'] = $partner_id;
        $generation[$l+1][count($generation[$l+1])-1]['relmdate'] = $family[$v]['mdate'];
        $generation[$l+1][count($generation[$l+1])-1]['relddate'] = $family[$v]['ddate'];
        $generation[$l+1][count($generation[$l+1])-1]['reltype'] = $family[$v]['type'];
        $generation[$l+1][count($generation[$l+1])-1]['rel'] = $id;
        $b=$generation[$l][count($generation[$l])-1]['a']; //fortlaufende ID des ersten Partners sichern
        $a++; //fortlaufende ID iterieren
        $c=$a; //fortlaufende ID des zweiten Partners sichern
        $generation[$l+1][count($generation[$l+1])-1]['a'] = $a;
        $generation[$l+1][count($generation[$l+1])-1]['arel'] = $b;
        $generation[$l+1][count($generation[$l+1])-1]['name'] = $individual[$partner_id]['firstname'].' '.$individual[$partner_id]['lastname'];
        //Anlegen der Kinder
        if(count($family[$v]['children'])) {
          foreach($family[$v]['children'] as $k => $v) {
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
        if($sv['a']==$id) return $sv['pos'];
      }
    }
  }

  function print_date($date,$print=0) {
    if(!empty($date)) {
      $date = explode('-',$date);
      if($_GET['lang']=='de') return str_pad($date[2],2,'0',STR_PAD_LEFT).'.'.str_pad($date[1],2,'0',STR_PAD_LEFT).'.'.$date[0];
      else if($_GET['lang']=='en') return str_pad($date[1],2,'0',STR_PAD_LEFT).'/'.str_pad($date[2],2,'0',STR_PAD_LEFT).'/'.$date[0];
    } else {
      if(!$print) {
        if($_GET['lang']=='de') return '__.__.____';
        else if($_GET['lang']=='en') return '__/__/____';
      }
    }
    return '';
  }  

  function print_year($date,$print=0) {
    if(!empty($date)) {
      $date = explode('-',$date);
      return $date[0];
    } else {
      if(!$print) return '____';
    }
    return '';
  }  

  function print_age($array) {
    $period = array(
      'de' => array('y' => 'Jahr/Jahre', 'm' => 'Monat/Monate', 'd' => 'Tag/Tage'),
      'en' => array('y' => 'year/years', 'm' => 'month/months', 'd' => 'day/days'),
    );
    if($array[0]>1) preg_match('/.*\/(.*)/',$period[$_GET['lang']][$array[1]],$match);
    else if($array[0]>0) preg_match('/(.*)\/.*/',$period[$_GET['lang']][$array[1]],$match);
    if($array[0]>0) return $array[0].' '.$match[1];
    if($array[0]==0 && $array[1]=='d') return '';
    else {
      preg_match('/.*\/(.*)/',$period[$_GET['lang']]['y'],$match);
      return '__ '.$match[1];
    }
  }

  function shorten_firstname($name, $mode='firstFullSecondFull') {
    $names = explode(' ',$name);
    switch($mode) {
      case 'firstFullSecondFull':
        $firstname = $names[0];  
        if(count($names) > 1) {
          if(preg_match('/^[IVXCLM]+\.$/',$names[1]) && count($names) > 2) $firstname .= ' '.$names[1].' '.$names[2];
          else $firstname .= ' '.$names[1];
        }
        break;
      case 'firstFullSecondAbbr':
        $firstname = $names[0];  
        if(count($names) > 1) {
          if(preg_match('/^[IVXCLM]+\.$/',$names[1])) {
            if(count($names) > 2) $firstname .= ' '.$names[1].' '.mb_substr($names[2],0,1,'UTF-8').'.';
            else $firstname .= ' '.$names[1];
          } else $firstname .= ' '.mb_substr($names[1],0,1,'UTF-8').'.';
        }
        break;
      case 'firstFull':
        $firstname = $names[0];  
        if(count($names) > 1 && preg_match('/^[IVXCLM]+\.$/',$names[1])) $firstname .= ' '.$names[1];
        break;
      case 'firstAbbr':
        $firstname = mb_substr($names[0],0,1,'UTF-8').'.';
        break;
    }
    return $firstname;
  }
    
  function is_name_unknown($name) {
    if(preg_match('/^(NN|\?)$/i',$name)) return 1;
    return 0;
  }

  function is_name_vague($name) {
    if(preg_match('/^[\^\°\[\]\<\>\|\-\_\?\!\"\'\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\.\\\]+([^\^\°\[\]\<\>\|\_\?\!\"\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\\\]+)[\^\°\[\]\<\>\|\-\_\?\!\"\'\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\\\]?$/',$name)) return 1;
    if(preg_match('/^[\^\°\[\]\<\>\|\-\_\?\!\"\'\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\.\\\]?([^\^\°\[\]\<\>\|\_\?\!\"\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\\\]+)[\^\°\[\]\<\>\|\-\_\?\!\"\'\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\\\]+$/',$name)) return 1;
    return 0;
  }

  function extract_vague_name($name) {
    if(preg_match('/^[\^\°\[\]\<\>\|\-\_\?\!\"\'\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\.\\\]+([^\^\°\[\]\<\>\|\_\?\!\"\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\\\]+)[\^\°\[\]\<\>\|\-\_\?\!\"\'\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\\\]?$/',$name,$match)) return $match[1];
    if(preg_match('/^[\^\°\[\]\<\>\|\-\_\?\!\"\'\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\.\\\]?([^\^\°\[\]\<\>\|\_\?\!\"\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\\\]+)[\^\°\[\]\<\>\|\-\_\?\!\"\'\§\$\%\&\/\(\)\=\+\*\~\#\;\:\,\\\]+$/',$name,$match)) return $match[1];
    return $name;
  }
    
  //GEDCOM-File einlesen
  $gedcom = implode('', file($ged_dir.$_GET['src']));
  
  //Source lesen
  preg_match_all('/(0 HEAD\r?\n([1-9]+ .*\r?\n)*)/im',$gedcom,$gedcom_head,PREG_SET_ORDER);
  preg_match_all('/1 SOUR (.*)\r?\n([2-9]+ .*\r?\n)*/im',$gedcom_head[0][1],$gedcom_head_source,PREG_SET_ORDER);
  $gedcom_source_name = trim($gedcom_head_source[0][1]);
  if(!empty($gedcom_head_source[0][2])) preg_match_all('/2 VERS (.*)\r?\n/im',$gedcom_head_source[0][0],$gedcom_head_source_vers,PREG_SET_ORDER);
  $gedcom_source_version = trim($gedcom_head_source_vers[0][1]);
  preg_match_all('/1 DATE ([0-9]{1,2}) ([A-Z]{3}) ([0-9]{1,4})\r?\n([2-9]+ .*\r?\n)*/im',$gedcom_head[0][1],$gedcom_head_date,PREG_SET_ORDER);
  if(count($gedcom_head_date)) $gedcom_date = $gedcom_head_date[0][3].'-'.(array_search(strtolower($gedcom_head_date[0][2]),$months)+1).'-'.intval($gedcom_head_date[0][1]);
  if(!empty($gedcom_head_date[0][4])) preg_match_all('/2 TIME (.*)\r?\n/im',$gedcom_head_date[0][4],$gedcom_head_time,PREG_SET_ORDER);
  $gedcom_time = trim($gedcom_head_time[0][1]);
  preg_match_all('/1 GEDC\r?\n([2-9]+ .*\r?\n)*/im',$gedcom_head[0][1],$gedcom_head_gedc,PREG_SET_ORDER);
  preg_match_all('/2 VERS (.*)\r?\n/im',$gedcom_head_gedc[0][0],$gedcom_head_ver,PREG_SET_ORDER);
  $gedcom_ver = trim($gedcom_head_ver[0][1]);
  preg_match_all('/2 FORM (.*)\r?\n/im',$gedcom_head_gedc[0][0],$gedcom_head_ver,PREG_SET_ORDER);
  $gedcom_form = trim($gedcom_head_ver[0][1]);
  preg_match_all('/1 CHAR (.*)\r?\n/im',$gedcom_head[0][1],$gedcom_head_char,PREG_SET_ORDER);
  $gedcom_char = trim($gedcom_head_char[0][1]);
  
  //Individuals erfassen
  preg_match_all('/(0 \@I([0-9]+)\@ INDI\r?\n([^0].*\r?\n)+)/im',$gedcom,$gedcom_individual,PREG_SET_ORDER);
  //gedcom_individual[x][1] = GEDCOM of individual
  //gedcom_individual[x][2] = GEDCOM_ID of individual
  if(count($gedcom_individual)) {
    foreach($gedcom_individual as $k => $v) {
      unset($name);unset($sex);unset($birthday);unset($deathday);unset($fams);unset($famc);
      preg_match('/1 NAME ([^\/]+) \/([^\/]+)\/\r?\n(2 GIVN (.+))?/i',$v[1],$name);
      $individual[$v[2]]['firstname'] = utf8_encode(trim($name[1]));
      $individual[$v[2]]['lastname'] = utf8_encode(trim($name[2]));
      if(!empty($name[4]))
        $individual[$v[2]]['firstname'] = utf8_encode(trim($name[4]));
      preg_match('/1 SEX (M|F)/i',$v[1],$sex);
      $individual[$v[2]]['sex'] = strtolower($sex[1]);
      preg_match('/1 BIRT\r?\n2 DATE ([0-9]{1,2}) ([A-Z]{3}) ([0-9]{1,4})/i',$v[1],$birthday);
      if(count($birthday)) $individual[$v[2]]['birthday'] = $birthday[3].'-'.(array_search(strtolower($birthday[2]),$months)+1).'-'.intval($birthday[1]);
      preg_match('/1 DEAT\r?\n2 DATE ([0-9]{1,2}) ([A-Z]{3}) ([0-9]{1,4})/i',$v[1],$deathday);
      if(count($deathday)) $individual[$v[2]]['deathday'] = $deathday[3].'-'.(array_search(strtolower($deathday[2]),$months)+1).'-'.intval($deathday[1]);
      if(!empty($individual[$v[2]]['deathday']) && !empty($individual[$v[2]]['birthday'])) $individual[$v[2]]['age'] = calc_age($individual[$v[2]]['birthday'],$individual[$v[2]]['deathday']);
      else if(!empty($individual[$v[2]]['birthday']) && !preg_match('/1 DEAT/i',$v[1])) $individual[$v[2]]['age'] = calc_age($individual[$v[2]]['birthday'],$_GET['date']);
      if(preg_match('/1 DEAT/i',$v[1])) $individual[$v[2]]['living'] = 0;
      else $individual[$v[2]]['living'] = 1;
      preg_match('/1 OCCU ([^\r\n]+)/i',$v[1],$occupation);
      $individual[$v[2]]['occupation'] = utf8_encode($occupation[1]);
      preg_match('/1 TITL ([^\r\n,]+),? ?([^\r\n]+)?/i',$v[1],$title);
      $individual[$v[2]]['title'] = utf8_encode($title[1]);
      $individual[$v[2]]['title_of'] = utf8_encode($title[2]);
      preg_match_all('/1 FAMS \@F([0-9]+)\@/i',$v[1],$fams);
      foreach($fams[1] as $sk => $sv) 
        $individual[$v[2]]['fam_parent_of'][] = $sv;
      preg_match('/1 FAMC \@F([0-9]+)\@/i',$v[1],$famc);
      $individual[$v[2]]['fam_child_of'] = intval($famc[1]);
      if($_GET['adop']) {
        //Nach Adoptivkinder suchen
//        preg_match_all('/1 ADOP Y\r?\n([2-9]+ .*\r?\n)*/im',$v[1],$adop,PREG_SET_ORDER);
//        if(!empty($adop[0][0])) preg_match_all('/2 FAMC \@F([0-9]+)\@/i',$adop[0][0],$famc_adop,PREG_SET_ORDER);
//        if(count($famc_adop)) {
//          $individual[$v[2]]['fam_child_of'] = intval($famc_adop[0][1]);
//          $family[intval($famc_adop[0][1])]['children'][] = $v[2];
//          unset($famc_adop);
//        }
      }
    }
  }

  //Families erfassen
  preg_match_all('/(0 \@F([0-9]+)\@ FAM\r?\n([^0].*\r?\n)+)/im',$gedcom,$gedcom_family,PREG_SET_ORDER);
  //gedcom_family[x][1] = GEDCOM of family
  //gedcom_family[x][2] = GEDCOM_ID of family
  if(count($gedcom_family)) {
    foreach($gedcom_family as $k => $v) {
      preg_match('/1 MARR[^\r\n]*\r?\n([^1].*\r?\n)*/im',$v[1],$gedcom_family_marr);
      if(count($gedcom_family_marr)) {
        $family[$v[2]]['type'] = 'married';
        preg_match('/2 DATE ([0-9]{1,2}) ([A-Z]{3}) ([0-9]{1,4})/im',$gedcom_family_marr[0],$date);
        if(count($date)) $family[$v[2]]['mdate'] = $date[3].'-'.(array_search(strtolower($date[2]),$months)+1).'-'.intval($date[1]);
        preg_match('/2 PLAC unmarried/i',$gedcom_family_marr[0],$married);
        if(count($married)) $family[$v[2]]['type'] = 'unmarried';
      }
      preg_match('/1 DIV[^\r\n]*\r?\n([^1].*\r?\n)*/im',$v[1],$gedcom_family_div);
      if(count($gedcom_family_div)) {
        $family[$v[2]]['type'] = 'divorced';
        preg_match('/2 DATE ([0-9]{1,2}) ([A-Z]{3}) ([0-9]{1,4})/im',$gedcom_family_div[0],$date);
        if(count($date)) $family[$v[2]]['ddate'] = $date[3].'-'.(array_search(strtolower($date[2]),$months)+1).'-'.intval($date[1]);
      }
      preg_match('/1 HUSB \@I([0-9]+)\@/i',$v[1],$husband);
      $family[$v[2]]['husband'] = $husband[1];
      //$family[$v[2]]['husband'] = $individual[$husband[1]]['firstname'].' '.$individual[$husband[1]]['lastname'];
      preg_match('/1 WIFE \@I([0-9]+)\@/i',$v[1],$wife);
      $family[$v[2]]['wife'] = $wife[1];
      //$family[$v[2]]['wife'] = $individual[$wife[1]]['firstname'].' '.$individual[$wife[1]]['lastname'];
      preg_match_all('/1 CHIL \@I([0-9]+)\@/i',$v[1],$children);
      foreach($children[1] as $sk => $sv) {
        $family[$v[2]]['children'][] = $sv;
        //$family[$v[2]]['children'][] = $individual[$sv]['firstname'].' '.$individual[$sv]['lastname'];
      }
    }
  }
  
  header('Content-type: text/xml');
  $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  $xmlstr .= "<root><vars></vars><generations></generations></root>";
  $xml = new SimpleXMLElement($xmlstr);
  $xml->vars->gedcom->file = $_GET['src'];
  $xml->vars->gedcom->version = $gedcom_ver;
  $xml->vars->gedcom->form = $gedcom_form;
  $xml->vars->gedcom->charset = $gedcom_char;
  $xml->vars->gedcom->timestamp = date('Y-m-d H:i',strtotime($gedcom_date.' '.$gedcom_time));
  if(!empty($_GET['date'])) $xml->vars->projectedDate = date('Y-m-d H:i',strtotime($_GET['date']));
  $xml->vars->gedcom->source->name = $gedcom_source_name;
  $xml->vars->gedcom->source->version = $gedcom_source_version;

  if($_GET['m'] == 'a') { //Vorfahrenbaum wird angefordert
    //Rendert die Vorfahren abgehend von der vorgegebenen Person
    render_agen($_GET['i'],0,0);
    //Anzahl der Generationen per Parameter, sonst max
    if($_GET['g']<=count($generation) && $_GET['g']>0) $gen_anzahl = $_GET['g'];
    else $gen_anzahl = count($generation);

    //Sind Generationen vorhanden und ist eine Anzahl von Generationen vorgegeben,
    //wird das Array entsprechend freigestellt
    if(count($generation)) {
      $generation = array_slice($generation,0,$gen_anzahl);
      //Zählt die Gesamtzahl von Personen
      foreach($generation as $k => $v) {
        foreach($v as $sk => $sv) {
          if($sv['id']!=0) $pers_anzahl++;
        }
      }
    }

    //spiegelt die Reihenfolge der auszugebenden Generationen
    $generation = array_reverse($generation, true);

    //Berechnung der Breiten
    foreach($generation as $k => $v) {
      foreach($v as $sk => $sv) {
        if(count($generation[($k+1)])) {
          foreach($generation[($k+1)] as $ssk => $ssv) {
            if($sv['a']==$ssv['arel']) $generation[$k][$sk]['width'] += $generation[($k+1)][$ssk]['width'];
          }
        }
        if(empty($generation[$k][$sk]['width']) && $generation[$k][$sk]['id']!=0) $generation[$k][$sk]['width'] = 1;
      }
    }

    //spiegelt die Reihenfolge der auszugebenden Generationen
    $generation = array_reverse($generation, true);

    //Setzt Position innerhalb einer Generation unabhängig von Vor- oder Nachgeneration
    $sum_width = 0;
    foreach($generation as $k => $v) {
      foreach($v as $sk => $sv) {
        $generation[$k][$sk]['pos'] = $sum_width;
        $sum_width += $sv['width'];
      }
      $sum_width = 0;
    }

    //Berechnet Position abhängig von Vorgenerationen
    foreach($generation as $k => $v) {
      foreach($v as $sk => $sv) {
        if(rel_pos($sv['arel']) > $generation[$k][$sk]['pos']) {
          $generation[$k][$sk]['pos'] = rel_pos($sv['arel']);
          $generation[$k][$sk+1]['pos'] = rel_pos($sv['arel'])+$generation[$k][$sk]['width'];
        }
      }
    }

    $xml->vars->count->generations = $gen_anzahl;
    $xml->vars->count->columns = $generation[0][0]['width'];
    $xml->vars->count->people = $pers_anzahl;

    //spiegelt die Reihenfolge der auszugebenden Generationen
    $generation = array_reverse($generation);

    //Gibt Vorfahrenbaum aus
    $xml->generations['type'] = 'a';
    foreach($generation as $k => $v) {
      $generation = $xml->generations->addChild('generation');
      $generation['level'] = $k;
      foreach($v as $sk => $sv) {
        $person = $generation->addChild('person');
        if(!is_name_unknown($individual[$sv['id']]['firstname'])) {
          $person->firstname['vague'] = is_name_vague($individual[$sv['id']]['firstname']);
          $person->firstname->full = ($person->firstname['vague'])?extract_vague_name($individual[$sv['id']]['firstname']):$individual[$sv['id']]['firstname'];
          $person->firstname->full['length'] = strlen($person->firstname->full);
          $person->firstname->firstFullSecondFull = ($person->firstname['vague'])?shorten_firstname(extract_vague_name($individual[$sv['id']]['firstname']),'firstFullSecondFull'):shorten_firstname($individual[$sv['id']]['firstname'],'firstFullSecondFull');
          $person->firstname->firstFullSecondFull['length'] = strlen($person->firstname->firstFullSecondFull);
          $person->firstname->firstFullSecondAbbr = ($person->firstname['vague'])?shorten_firstname(extract_vague_name($individual[$sv['id']]['firstname']),'firstFullSecondAbbr'):shorten_firstname($individual[$sv['id']]['firstname'],'firstFullSecondAbbr');
          $person->firstname->firstFullSecondAbbr['length'] = strlen($person->firstname->firstFullSecondAbbr);
          $person->firstname->firstFull = ($person->firstname['vague'])?shorten_firstname(extract_vague_name($individual[$sv['id']]['firstname']),'firstFull'):shorten_firstname($individual[$sv['id']]['firstname'],'firstFull');
          $person->firstname->firstFull['length'] = strlen($person->firstname->firstFull);
          $person->firstname->firstAbbr = ($person->firstname['vague'])?shorten_firstname(extract_vague_name($individual[$sv['id']]['firstname']),'firstAbbr'):shorten_firstname($individual[$sv['id']]['firstname'],'firstAbbr');
          $person->firstname->firstAbbr['length'] = strlen($person->firstname->firstAbbr);
        } else {
          $person->firstname['vague'] = 1;
          $person->firstname->full = '_______';
          $person->firstname->full['length'] = 0;
          $person->firstname->firstFullSecondFull = '_______';
          $person->firstname->firstFullSecondFull['length'] = 0;
          $person->firstname->firstFullSecondAbbr = '_______';
          $person->firstname->firstFullSecondAbbr['length'] = 0;
          $person->firstname->firstFull = '_______';
          $person->firstname->firstFull['length'] = 0;
          $person->firstname->firstAbbr = '_______';
          $person->firstname->firstAbbr['length'] = 0;
        }
        if(!is_name_unknown($individual[$sv['id']]['lastname'])) {
          $person->lastname['vague'] = is_name_vague($individual[$sv['id']]['lastname']);
          $person->lastname->full = ($person->lastname['vague'])?extract_vague_name($individual[$sv['id']]['lastname']):$individual[$sv['id']]['lastname'];
          $person->lastname->full['length'] = strlen($person->lastname->full);
        } else {
          $person->lastname['vague'] = 1;
          $person->lastname->full = '_______';
          $person->lastname->full['length'] = 0;
        }
        $person->sex = $individual[$sv['id']]['sex'];
        if(empty($individual[$sv['id']]['birthday'])) $person->birth['length'] = 0;
        $person->birth->date = print_date($individual[$sv['id']]['birthday']);
        $person->birth->year = print_year($individual[$sv['id']]['birthday']);
        if(empty($individual[$sv['id']]['deathday']) && !$individual[$sv['id']]['living']) $person->death['length'] = 0;
        $person->death->date = print_date($individual[$sv['id']]['deathday'],$individual[$sv['id']]['living']);
        $person->death->year = print_year($individual[$sv['id']]['deathday'],$individual[$sv['id']]['living']);
        if($individual[$sv['id']]['age'][0]=='' && $individual[$sv['id']]['age'][1]=='') $person->age['length'] = 0;
        $person->age = print_age($individual[$sv['id']]['age']);
        if(count($v) > 1) {
          //$person->age = urlencode(var_dump($sv));
          if(!($sk%2) && empty($sv['relmdate'])) $person->relation->wedding['length'] = 0;
          if(!($sk%2)) $person->relation->wedding->date = print_date($sv['relmdate']);
          if(!($sk%2)) $person->relation->wedding->year = print_year($sv['relmdate']);
          if(!($sk%2) && $sv['reltype'] == 'divorced' && empty($sv['relddate'])) $person->relation->divorce['length'] = 0;
          if(!($sk%2) && $sv['reltype'] == 'divorced') $person->relation->divorce->date = print_date($sv['relddate']);
          if(!($sk%2) && $sv['reltype'] == 'divorced') $person->relation->divorce->year = print_year($sv['relddate']);
          if(!($sk%2)) $person->relation->type = $sv['reltype'];
          if(!($sk%2)) $person->relation['partner_id'] = $family[$individual[$sv['rel']]['fam_child_of']]['wife'];
        }
        $person->occupation = $individual[$sv['id']]['occupation'];
        $person->title->name = $individual[$sv['id']]['title'];
        $person->title->location = $individual[$sv['id']]['title_of'];
        //$person->relday = $sv['reldate'];
        $person['id'] = $sv['id'];
        $person['uid'] = $sv['id'].'-'.$k.'-'.$sv['pos'];
        $person['pos'] = $sv['pos'];
        $person['width'] = $sv['width'];
        $person['living'] = $individual[$sv['id']]['living'];
      }
    }
    
  } else if($_GET['m'] == 'd') { //Nachkommenbaum wird angefordert
    //Rendert die Nachkommen abgehend von der vorgegebenen Person
    render_dgen($_GET['i'],0);

    //Anzahl der Generationen per Parameter, sonst max
    if($_GET['g']<=ceil(count($generation)/2) && $_GET['g']>0) $gen_anzahl = $_GET['g'];
    else $gen_anzahl = ceil(count($generation)/2);
    
    //Sind Generationen vorhanden und ist eine Anzahl von Generationen vorgegeben,
    //wird das Array entsprechend freigestellt
    if(count($generation)) {
      $generation = array_slice($generation,0,($gen_anzahl*2)-1);
      //Zählt die Gesamtzahl von Personen
      foreach($generation as $k => $v) {
        foreach($v as $sk => $sv) {
          if($sv['id']!=0) $pers_anzahl++;
        }
      }
    }

    //spiegelt die Reihenfolge der auszugebenden Generationen
    $generation = array_reverse($generation, true);

    //Berechnung der Breiten
    foreach($generation as $k => $v) {
      foreach($v as $sk => $sv) {
        if(empty($generation[$k][$sk]['width']) && $generation[$k][$sk]['id']!=0) $generation[$k][$sk]['width'] = 1;
        if(count($generation[($k-1)])) {
          foreach($generation[($k-1)] as $ssk => $ssv) {
            if($ssv['a']==$sv['arel']) $generation[($k-1)][$ssk]['width'] += $generation[$k][$sk]['width'];
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
        $pos[$sv['a']] = $sum_width;
        $sum_width += $sv['width'];
      }
      $sum_width = 0;
    }

    //Korrigiert die Position innerhalb der Familie
    foreach($generation as $k => $v) {
      foreach($v as $sk => $sv) {
        if($pos[$sv['a']]>=$pos[$sv['arel']]) 
          $npos[$sv['a']] = $pos[$sv['a']] - $pos[$sv['arel']];
      }
    }

    //Berechnet Position abhängig von Vorgenerationen
    foreach($generation as $k => $v) {
      foreach($v as $sk => $sv) {
        $npos[$sv['a']] += $npos[$sv['arel']];
        if($npos[$sv['a']] <= ($npos[$generation[$k][$sk-1]['a']] + $generation[$k][$sk-1]['width'])) {
          $npos[$generation[$k][$sk]['a']] = ($npos[$generation[$k][$sk-1]['a']] + $generation[$k][$sk-1]['width']);
        }
        if($npos[$sv['a']] < $npos[$sv['arel']]) $npos[$sv['a']] = $npos[$sv['arel']];
      }
    }

    $xml->vars->count->generations = $gen_anzahl;
    $xml->vars->count->columns = $generation[0][0]['width'];
    $xml->vars->count->people = $pers_anzahl;

    //Gibt Nachkommenbaum aus
    $xml->generations['type'] = 'd';
    foreach($generation as $k => $v) {
      $generation = $xml->generations->addChild('generation');
      $generation['level'] = $k;
      foreach($v as $sk => $sv) {
        $person = $generation->addChild('person');
        if(!is_name_unknown($individual[$sv['id']]['firstname'])) {
          $person->firstname['vague'] = is_name_vague($individual[$sv['id']]['firstname']);
          $person->firstname->full = ($person->firstname['vague'])?extract_vague_name($individual[$sv['id']]['firstname']):$individual[$sv['id']]['firstname'];
          $person->firstname->full['length'] = strlen($person->firstname->full);
          $person->firstname->firstFullSecondFull = ($person->firstname['vague'])?shorten_firstname(extract_vague_name($individual[$sv['id']]['firstname']),'firstFullSecondFull'):shorten_firstname($individual[$sv['id']]['firstname'],'firstFullSecondFull');
          $person->firstname->firstFullSecondFull['length'] = strlen($person->firstname->firstFullSecondFull);
          $person->firstname->firstFullSecondAbbr = ($person->firstname['vague'])?shorten_firstname(extract_vague_name($individual[$sv['id']]['firstname']),'firstFullSecondAbbr'):shorten_firstname($individual[$sv['id']]['firstname'],'firstFullSecondAbbr');
          $person->firstname->firstFullSecondAbbr['length'] = strlen($person->firstname->firstFullSecondAbbr);
          $person->firstname->firstFull = ($person->firstname['vague'])?shorten_firstname(extract_vague_name($individual[$sv['id']]['firstname']),'firstFull'):shorten_firstname($individual[$sv['id']]['firstname'],'firstFull');
          $person->firstname->firstFull['length'] = strlen($person->firstname->firstFull);
          $person->firstname->firstAbbr = ($person->firstname['vague'])?shorten_firstname(extract_vague_name($individual[$sv['id']]['firstname']),'firstAbbr'):shorten_firstname($individual[$sv['id']]['firstname'],'firstAbbr');
          $person->firstname->firstAbbr['length'] = strlen($person->firstname->firstAbbr);
        } else {
          $person->firstname['vague'] = 1;
          $person->firstname->full = '_______';
          $person->firstname->full['length'] = 0;
          $person->firstname->firstFullSecondFull = '_______';
          $person->firstname->firstFullSecondFull['length'] = 0;
          $person->firstname->firstFullSecondAbbr = '_______';
          $person->firstname->firstFullSecondAbbr['length'] = 0;
          $person->firstname->firstFull = '_______';
          $person->firstname->firstFull['length'] = 0;
          $person->firstname->firstAbbr = '_______';
          $person->firstname->firstAbbr['length'] = 0;
        }
        if(!is_name_unknown($individual[$sv['id']]['lastname'])) {
          $person->lastname['vague'] = is_name_vague($individual[$sv['id']]['lastname']);
          $person->lastname->full = ($person->lastname['vague'])?extract_vague_name($individual[$sv['id']]['lastname']):$individual[$sv['id']]['lastname'];
          $person->lastname->full['length'] = strlen($person->lastname->full);
        } else {
          $person->lastname['vague'] = 1;
          $person->lastname->full = '_______';
          $person->lastname->full['length'] = 0;
        }
        $person->sex = $individual[$sv['id']]['sex'];
        if(empty($individual[$sv['id']]['birthday'])) $person->birth['length'] = 0;
        $person->birth->date = print_date($individual[$sv['id']]['birthday']);
        $person->birth->year = print_year($individual[$sv['id']]['birthday']);
        if(empty($individual[$sv['id']]['deathday']) && !$individual[$sv['id']]['living']) $person->death['length'] = 0;
        $person->death->date = print_date($individual[$sv['id']]['deathday'],$individual[$sv['id']]['living']);
        $person->death->year = print_year($individual[$sv['id']]['deathday'],$individual[$sv['id']]['living']);
        if($individual[$sv['id']]['age'][0]=='' && $individual[$sv['id']]['age'][1]=='') $person->age['length'] = 0;
        $person->age = print_age($individual[$sv['id']]['age']);
        if($k%2 && empty($sv['relmdate'])) $person->relation->wedding['length'] = 0;
        if($k%2) $person->relation->wedding->date = print_date($sv['relmdate']);
        if($k%2) $person->relation->wedding->year = print_year($sv['relmdate']);
        if($k%2 && $sv['reltype'] == 'divorced' && empty($sv['relddate'])) $person->relation->divorce['length'] = 0;
        if($k%2 && $sv['reltype'] == 'divorced') $person->relation->divorce->date = print_date($sv['relddate']);
        if($k%2 && $sv['reltype'] == 'divorced') $person->relation->divorce->year = print_year($sv['relddate']);
        if($k%2) $person->relation->type = $sv['reltype'];
        if($k%2) $person->relation['partner_id'] = $sv['rel'];
        $person->occupation = $individual[$sv['id']]['occupation'];
        $person->title->name = $individual[$sv['id']]['title'];
        $person->title->location = $individual[$sv['id']]['title_of'];
        $person['id'] = $sv['id'];
        $person['uid'] = $sv['id'].'-'.$k.'-'.$npos[$sv['a']];
        $person['parent_id'] = $sv['rel'];
        $person['pos'] = $npos[$sv['a']];
        $person['width'] = $sv['width'];
        $person['living'] = $individual[$sv['id']]['living'];
      }
    }
  }
  echo $xml->asXML();
?>
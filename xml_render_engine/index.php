<?
	function is_ext($file,$ext) {
		if(preg_match("/^.+\.".$ext."$/",$file)) return true;
		else return false;
	}
	function is_valid_filename($file) {
		if(preg_match("/^[A-Za-z0-9-_]+\.[A-Za-z]{1,3}$/",$file)) return true;
		else return false;
	}
	
	function file_upload_error_message($error_code) {
		switch ($error_code) { 
			case UPLOAD_ERR_INI_SIZE: 
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini...'; 
			case UPLOAD_ERR_FORM_SIZE: 
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form....'; 
			case UPLOAD_ERR_PARTIAL: 
				return 'The uploaded file was only partially uploaded...'; 
			case UPLOAD_ERR_NO_FILE: 
				return 'No file was uploaded...'; 
			case UPLOAD_ERR_NO_TMP_DIR: 
				return 'Missing a temporary folder...'; 
			case UPLOAD_ERR_CANT_WRITE: 
				return 'Failed to write file to disk...'; 
			case UPLOAD_ERR_EXTENSION: 
				return 'File upload stopped by extension...'; 
			default: 
				return 'Unknown upload error...'; 
		} 
	} 
		
	$ged_dir = "./ged";
	$max_file_size = 1024 * 1024;
	if(!empty($_POST["form_name"])) {
		switch($_POST["form_name"]) {
			case "form1":
				if(!is_dir(dirname(__FILE__)."/".$get_dir)) mkdir(dirname(__FILE__)."/".$get_dir);
				chmod($ged_dir,0777);
				//print filesize($_FILES["file"]["tmp_name"])." : ".$max_file_size;
				if($_FILES['file']['error'] === UPLOAD_ERR_OK) {
			    if(filesize($_FILES["file"]["tmp_name"]) >= $max_file_size) $error1 = sprintf("File<b>%s</b> is too big to be uploaded...",($_FILES["file"]["name"])?" ".$_FILES["file"]["name"]:"");
					else if(!is_ext($_FILES["file"]["name"],"ged")) $error1 = sprintf("File<b>%s</b> is not a GEDCOM file...",($_FILES["file"]["name"])?" ".$_FILES["file"]["name"]:"");
					else if(!is_valid_filename($_FILES["file"]["name"])) $error1 = sprintf("File<b>%s</b> is not a valid filename...",($_FILES["file"]["name"])?" ".$_FILES["file"]["name"]:"");
					else if(!move_uploaded_file($_FILES["file"]["tmp_name"],$ged_dir."/".$_FILES["file"]["name"])) $error1 = sprintf("File<b>%s</b> could not be uploaded...",($_FILES["file"]["name"])?" ".$_FILES["file"]["name"]:"");
				}
				else $error1 = file_upload_error_message($_FILES['file']['error']); 
				if(!$error1) {
					header("Location:".$_SERVER["PHP_SELF"]."?file=".basename($_FILES["file"]["name"]));
					exit;
				}
				break;
			case "form2":
				if(empty($_POST["db"])) $error2 = "No file selected...";
				if(!$error2) {
					header("Location:".$_SERVER["PHP_SELF"]."?file=".$_POST["db"]."&p=2");
					exit;
				}
				break;
		}
	}

	if(!empty($_GET["file"]) && $_GET["p"]=="2" && file_exists($ged_dir."/".$_GET["file"])) {
		print "true";
		exit;
	} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
	
<html> 
<head> 
	<title>Upload GEDCOM file</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<style>
		body{font:12px Arial;color:#000;line-height:1.6em;}
		body h1:first-child{margin-top:0;}
		h1{font-size:1.5em;margin-top:1.5em}
		h2{font-size:1.2em;}
		form{padding:0;margin:0;}
		label{margin-top:-0.5em;}
		input[type=submit]{margin-top:10px;width:auto;display:block;}
		.error{color:red;}
	</style>
</head>

<body>
	<h1>Upload GEDCOM file...</h1>
	<form action="<?=$_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data">
		<p class="error"><?=($error1)?$error1:""?></p>
		<input type="file" name="file" />
		<input type="hidden" name="form_name" value="form1" /><input type="submit" name="submit" value="Upload" />
	</form>
	<h1>Use one of the existing GEDCOM files...</h1>
	<form action="<?=$_SERVER["PHP_SELF"]?>" method="post">
		<p class="error"><?=($error2)?$error2:""?></p>
<?
	if(is_dir($ged_dir)) {
		if($dh=opendir($ged_dir)) {
			while(($file=readdir($dh))!== false) {
				if($file != "." && $file != ".." & !is_dir($file)) $files[] = $file;
			}
			closedir($dh);
		}
	}
	if(!count($files)) print "No files available yet. Upload one...";
	else {
		sort($files);
		foreach($files as $k => $v) {
			printf("<input type=\"radio\" id=\"%s\" name=\"db\" value=\"%s\"%s /><label for=\"%s\"> %s</label><br />",$v,$v,($_GET["file"]==$v)?"checked=\"checked\"":"",$v,$v);
		}
		print "<input type=\"hidden\" name=\"form_name\" value=\"form2\" /><input type=\"submit\" name=\"submit\" value=\"Select\" />";
	}
?>
	</form>
</body>
</html>
<?
	}
?>
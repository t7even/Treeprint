<?php

	if(!$_GET['font'] || !$_GET['fm']){
		header('Content-Type: text/html; charset=utf-8');
		exit('Bitte zu berechnenden Font,Metrics und Encoding über GET (font= / fm= / enc=) übergeben. also zb: " ?font=meta.ttf&fm=meta.ufm&enc= " (Font dazu im aktuellen Ordner ablegen)');
	}else{
		$font = $_GET['font'];
	}

	include('./makefont.php');

	if( MakeFont($_GET['font'], $_GET['fm'], $embedded=true, $_GET['enc'], $patch=array()) )
			echo 'file successfully created';
		
?>
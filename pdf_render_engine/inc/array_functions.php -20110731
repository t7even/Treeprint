<?php
	include('tools/charWidth.php');

	function calcStrLength($font, $str, $size=1) {
		if(!isset($font)) exit('no font chosen');
		
		global $charWidth;
		if(!$charWidth[$font]) exit('font not calculated');
		
		$str = preg_split('//u',$str);
		foreach($str as $v) {
			$w += $charWidth[$font][$v] * $size;
		}
		return round($w,3);
	}
	
	function search_parent($pid,$gen,$pos,$array){
/*		$puid = $pid.'-'.($gen-1).'-'.$pos;
		while(array_search($puid,$array)){
			$pos++;
			$puid = $pid.'-'.($gen-1).'-'.$pos;
		}
*/
		$found_piud = false;
		
		while(!$found_piud ){
			$puid = $pid.'-'.($gen-1).'-'.$pos;

#			if(array_key_exists($puid,$array)){
			if( isset($array[$puid]) ){
				$found_piud = true;
				return $puid;

		/*	#DEBUG
		echo $puid."<br>";
		echo (array_key_exists($puid,$array)) ? "true"."<br>" : "false"."<br>";
		echo (isset($array[$puid])) ? "true"."<br>" : "false"."<br>";
		echo ($found_piud) ? "true" : "false"."<br>";
		echo "<pre>";
		print_r($array);
		echo "</pre>";
		exit;
		*/	

#			}elseif($pos > 15){
#				return 'not found';	

			}elseif($pos == 0){
				return 'not found';	

			}else{
#				$pos++;
				$pos--;
			}
		}
	}

?>
<?php

/*function Parse($p1, $p2, $p3){
	$num1 = strpos($p1, $p2);
	if ($num1 === false) return 0;
	$num2 = substr($p1, $num1);
	return strip_tags(substr($num2, 0, strpos($num2, $p3)));	
}*/
for ($a = 15001; $a <= 20796; $a++){ 

	$ch = curl_init("https://www.badips.com/info/".$a);
	$fp = fopen($a.".txt", "w") or die("не удалось создать файл");

	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_exec($ch);
	if(curl_error($ch)) {
	    fwrite($fp, curl_error($ch));
	}
	curl_close($ch);
	fclose($fp);

	$String = file_get_contents($a.'.txt');
	//var_dump($String);
	$findme = '<a class="badips" href="https://www.badips.com';
	$pos = strpos($String, $findme);
	//var_dump($pos);
	$String1 = substr($String, $pos);
	//var_dump($String1);
	$findme = '<div class="gad" id="gad2">';
	$pos = strpos($String1, $findme);
	$String2 = substr($String1, 0, $pos-6);
	$String2 = strip_tags($String2);
	//var_dump($String2);


	$String3 = "";
	$arr = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.', 'a', 'b', 'c', 'd', 'e', 'f', ':');
	$j=0;
	for ($i = 0; $i <= strlen($String2); $i++){
		if (in_array($String2[$i], $arr)) {
			$String3[$j] = $String2[$i];
			$j++;
		}
		elseif ((!in_array($String2[$i], $arr))&&(in_array($String2[$i+1], $arr))) {
			$String3[$j] = "\n";
			$j++;
		}
	}

	//var_dump($String3);

	$fp = fopen($a.".txt", "w") or die("не удалось создать файл");
	fwrite($fp, $String3);
	fclose($fp);

	//var_dump($a);
	sleep(2);
}















?>
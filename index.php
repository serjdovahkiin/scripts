<?php

ini_set('max_execution_time', 9000000);

$mysqli = new mysqli ("localhost", "root", "root", "badips");
$mysqli->query ("SET NAME 'utf8'");






function Parse($p1, $p2, $p3){
	$num1 = strpos($p1, $p2);
	if ($num1 === false) return 0;
	$num2 = substr($p1, $num1);
	return strip_tags(substr($num2, 0, strpos($num2, $p3)));	
}
for ($a = 0; $a <= 4; $a++){ 

	var_dump($a);
	
	$start = microtime(true);
	
	$IPS = fopen($a.".txt", "r");
	if ($IPS) {
		$i = 0;
	    while (($line = fgets($IPS)) !== false) { 
	    	$i++;
	    	if ($i < 500){ 
	    		$line = substr($line,0,-1); // обрезаем \п в конце строки кроме крайней
	    	}
	        //var_dump($line);
	    	
			$output2 = curl_init();  //подключаем курл
		    curl_setopt($output2, CURLOPT_URL, "https://ipdb.me/".$line);  //отправляем адрес страницы
		    curl_setopt($output2, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($output2, CURLOPT_HEADER, 0);
		    curl_setopt($output2, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($output2, CURLOPT_TIMEOUT, 10);
		    $out2 = curl_exec($output2);    //помещаем html-контент в строку
		    //curl_close($output2);  //закрываем подключение
		    //var_dump($out2);
		    

			$COUNTRYCODE = substr(Parse($out2, "The IP is from ","\n"),-3,2);
			if ($COUNTRYCODE===false){
		    	$COUNTRYCODE = "--";
		    }
		    //var_dump($COUNTRYCODE);

		    $COUNTRY = substr(Parse($out2, "The IP is from "," ("),15);
		    if ($COUNTRY===false){
		    	$COUNTRY = "unknown";
		    }
		    //var_dump($COUNTRY);

		    $ASN = substr(Parse($out2, "AS Number:","\n"),16);
		    if ($ASN===false){
		    	$ASN = "unknown";
		    }
		    //var_dump($ASN);

		    $ASDescription = substr(Parse($out2, "AS Description:","\n"),16);
		    if ($ASDescription===false){
		    	$ASDescription = "unknown";
		    }
		    //var_dump($ASDescription);

		    $NETNAME = substr(Parse($out2, "netname:","\n"),16);
		    if ($NETNAME===false){
		    	$NETNAME = "unknown";
		    }
		    //var_dump($NETNAME);


		    if (($COUNTRYCODE=="UA")||($COUNTRY=="Ukraine")) {
		    	$output = curl_init();  //подключаем курл
			    curl_setopt($output, CURLOPT_URL, "https://www.badips.com/info/".$line);  //отправляем адрес страницы
			    curl_setopt($output, CURLOPT_RETURNTRANSFER, 1);
			    curl_setopt($output, CURLOPT_HEADER, 0);
			    curl_setopt($output, CURLOPT_SSL_VERIFYPEER, false);
			    curl_setopt($output, CURLOPT_TIMEOUT, 15);
			    $out = curl_exec($output);    //помещаем html-контент в строку
			    curl_close($output);  //закрываем подключение
			    //var_dump($out);
			    //die;    // прерываем цыкл
			    // парсим $out
			    $ACS = substr(Parse($out, "With an average score of ",","),25);
			    //var_dump($ACS);

			    if ($ACS===false) {
			    	$ACS = "0";
			    }

			    $MOSTRECENTREPORT = substr(Parse($out, "The last report for this IP came on","\n"),36);
			    //var_dump($MOSTRECENTREPORT);
			    if ($MOSTRECENTREPORT===false) {
			    	$MOSTRECENTREPORT = "unknown";
			    }
		    }
		    else {
			    $ACS = "0";
			    //var_dump($ACS);
			    $MOSTRECENTREPORT = "unknown";
			    //var_dump($MOSTRECENTREPORT);
			}
		    
		    $mysqli->query ("INSERT INTO `report`(`NUMTXT`, `IP`, `COUNTRYCODE`, `COUNTRY`, `ASNUMBER`, `ASDESCRIPTION`, `NETNAME`, `ACS`, `MOSTRECENTREPORT`) VALUES 
				('$a', '$line', '$COUNTRYCODE','$COUNTRY','$ASN','$ASDescription','$NETNAME','$ACS','$MOSTRECENTREPORT')");
		    usleep(250000);

	    }

		curl_close($output2);

	    if (!feof($IPS)) {
	        echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
	    }
	    fclose($IPS);

	    
	}
	/*$ch = curl_init("https://www.badips.com/info/".$a);
	$fp = fopen($a.".txt", "w") or die("не удалось создать файл");

	
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
	sleep(2); */
	echo 'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.';
	echo "<p>";
}


$mysqli->close ();

?>
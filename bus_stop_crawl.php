<?php
	include('config.php');

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => 'http://timbus.vn/Engine/Business/Search/action.ashx',
		CURLOPT_RETURNTRANSFER => true,
		
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => "act=searchfull&typ=2&key=",
		CURLOPT_HEADER => false,
		CURLOPT_HTTPHEADER => array(
			'Host: timbus.vn',
			'Connection: keep-alive',
			'Accept: application/json, text/javascript, */*; q=0.01',
			'X-Requested-With: XMLHttpRequest',
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
			'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
			'Origin: http://timbus.vn',
			'Referer: http://timbus.vn/?',
			'Accept-Encoding: gzip, deflate',
			'Accept-Language: en-US,en;q=0.9',
		),
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
	));

	$response = curl_exec($curl);

	curl_close($curl);
	// var_dump($response);

	$jsoncode = json_decode($response, true);

	echo "<pre>";
	var_dump($jsoncode);
	echo "</pre>";

	// $i= 0;
	// while($jsoncode["dt"]["Data"][$i] != NULL){

	// 	if(strlen($jsoncode["dt"]["Data"][$i]["Code"]) != 0){
	// 		var_dump($jsoncode["dt"]["Data"][$i]);
	// 	}
		
	// 	$i++;
	// }

	$i= 0;
	while($jsoncode["dt"]["Data"][$i] != NULL){

		$sql = "INSERT INTO bus_stop(ObjectType, ObjectID, Code, Street, Name, PrivateName, CompanyId, NameNoSign, FleetOver, Latitude, Longtitude, Distance, LandmarkCatalogueID) VALUES (".$jsoncode["dt"]["Data"][$i]["ObjectType"].", ".$jsoncode["dt"]["Data"][$i]["ObjectID"].", '".$jsoncode["dt"]["Data"][$i]["Code"]."', '".$jsoncode["dt"]["Data"][$i]["Street"]."', '".$jsoncode["dt"]["Data"][$i]["Name"]."', '".$jsoncode["dt"]["Data"][$i]["PrivateName"]."', ".strval($jsoncode["dt"]["Data"][$i]["CompanyId"]).", '".$jsoncode["dt"]["Data"][$i]["NameNoSign"]."', '".$jsoncode["dt"]["Data"][$i]["FleetOver"]."', '".$jsoncode["dt"]["Data"][$i]["Geo"]["Lat"]."', '".$jsoncode["dt"]["Data"][$i]["Geo"]["Lng"]."', ".strval($jsoncode["dt"]["Data"][$i]["Distance"]).", ".strval($jsoncode["dt"]["Data"][$i]["LandmarkCatalogueID"]).")";
		
		$query = mysqli_query($con, $sql);

		if($query){
			echo "Thành công </br>";

		}
		else{
			echo "Thất bại </br>";
		}

		$i++;
	}




?>
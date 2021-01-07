<?php
	include('config.php');

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => 'http://timbus.vn/Engine/Business/Search/action.ashx',
		CURLOPT_RETURNTRANSFER => true,
		
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => "act=searchfull&typ=1&key=",
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

	var_dump($jsoncode);

	$i= 0;
	while($jsoncode["dt"]["Data"][$i] != NULL){

		if($jsoncode["dt"]["Data"][$i]["IsTwin"]){
			$sql = "INSERT INTO bus_route(ObjectType, ObjectID, Name, FleedCode, Data, isTwin) VALUES (".strval($jsoncode["dt"]["Data"][$i]["ObjectType"]).", ".strval($jsoncode["dt"]["Data"][$i]["ObjectID"]).", '".$jsoncode["dt"]["Data"][$i]["Name"]."', '".$jsoncode["dt"]["Data"][$i]["FleedCode"]."', '".$jsoncode["dt"]["Data"][$i]["Data"]."', 1)";
		}
		else{
			$sql = "INSERT INTO bus_route(ObjectType, ObjectID, Name, FleedCode, Data, isTwin) VALUES (".strval($jsoncode["dt"]["Data"][$i]["ObjectType"]).", ".strval($jsoncode["dt"]["Data"][$i]["ObjectID"]).", '".$jsoncode["dt"]["Data"][$i]["Name"]."', '".$jsoncode["dt"]["Data"][$i]["FleedCode"]."', '".$jsoncode["dt"]["Data"][$i]["Data"]."', 0)";
		}
		
		$query = mysqli_query($con, $sql);

		$i++;
	}




?>
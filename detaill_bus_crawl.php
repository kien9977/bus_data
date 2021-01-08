<?php
	include('config.php');

	$sql = "SELECT ObjectID FROM bus_route WHERE 1 ORDER BY ObjectID ASC";
	$masterquery = mysqli_query($con, $sql);

	while($data = mysqli_fetch_array($masterquery)){
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://timbus.vn/Engine/Business/Search/action.ashx',
			CURLOPT_RETURNTRANSFER => true,
			
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => "act=fleetdetail&fid=".strval($data["ObjectID"]),
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

		$sql = "INSERT INTO bus_data(FleetID, Enterprise, Code, Name, OperationsTime, Frequency, BusCount, Cost, FirstStation, LastStation) VALUES (".strval($jsoncode["dt"]["FleetID"]).", '".$jsoncode["dt"]["Enterprise"]."', '".$jsoncode["dt"]["Code"]."', '".$jsoncode["dt"]["Name"]."', '".$jsoncode["dt"]["OperationsTime"]."', '".$jsoncode["dt"]["Frequency"]."', '".$jsoncode["dt"]["BusCount"]."', ".strval($jsoncode["dt"]["CostInt"]).", '".$jsoncode["dt"]["FirstStation"]."', '".$jsoncode["dt"]["LastStation"]."')";
		$query = mysqli_query($con, $sql);

		if($query){
			echo "Thành công </br>";

		}
		else{
			echo "Thất bại </br>";
		}

		

		// crawl bus desc
		$sql = "INSERT INTO bus_gone_description(FleetID, Journey, Anomaly, Route) VALUES (".strval($jsoncode["dt"]["FleetID"]).", 'GO', ".strval($jsoncode["dt"]["Go"]["Anomaly"]).", '".$jsoncode["dt"]["Go"]["Route"]."')";
		$query = mysqli_query($con, $sql);

		if($query){
			echo "Thành công </br>";

		}
		else{
			echo "Thất bại </br>";
		}

		$sql = "INSERT INTO bus_gone_description(FleetID, Journey, Anomaly, Route) VALUES (".strval($jsoncode["dt"]["FleetID"]).", 'RE', ".strval($jsoncode["dt"]["Re"]["Anomaly"]).", '".$jsoncode["dt"]["Re"]["Route"]."')";
		$query = mysqli_query($con, $sql);

		if($query){
			echo "Thành công </br>";

		}
		else{
			echo "Thất bại </br>";
		}



		// to get over the geometry go journey
		$i = 0;
		while($jsoncode["dt"]["Go"]["Geo"][$i] != NULL){
			$sql = "INSERT INTO bus_gone_over_geo(FleetID, Journey, OrderBy, Latitude, Longtitude) VALUES (".strval($jsoncode["dt"]["FleetID"]).", 'GO', ".strval($i).", '".$jsoncode["dt"]["Go"]["Geo"][$i]["Lat"]."', '".$jsoncode["dt"]["Go"]["Geo"][$i]["Lng"]."')";
			$query = mysqli_query($con, $sql);
			$i++;

			if($query){
				echo "Thành công </br>";

			}
			else{
				echo "Thất bại </br>";
			}
		}

		// to get over the geometry re journey
		$i = 0;
		while($jsoncode["dt"]["Re"]["Geo"][$i] != NULL){
			$sql = "INSERT INTO bus_gone_over_geo(FleetID, Journey, OrderBy, Latitude, Longtitude) VALUES (".strval($jsoncode["dt"]["FleetID"]).", 'RE', ".strval($i).", '".$jsoncode["dt"]["Re"]["Geo"][$i]["Lat"]."', '".$jsoncode["dt"]["Re"]["Geo"][$i]["Lng"]."')";
			$query = mysqli_query($con, $sql);
			$i++;

			if($query){
				echo "Thành công </br>";

			}
			else{
				echo "Thất bại </br>";
			}
		}

		// to get over the stop for go journey
		$i = 0;
		while($jsoncode["dt"]["Go"]["Station"][$i] != NULL){
			$sql = "INSERT INTO bus_gone_stop(FleetID, ObjectID, Code, Journey, FleetOver, OrderBy, Latitude, Longtitude) VALUES (".strval($jsoncode["dt"]["FleetID"]).", ".strval($jsoncode["dt"]["Go"]["Station"][$i]["ObjectID"]).", '".$jsoncode["dt"]["Go"]["Station"][$i]["Code"]."', 'GO', '".$jsoncode["dt"]["Go"]["Station"][$i]["FleetOver"]."', ".strval($i).", '".$jsoncode["dt"]["Go"]["Station"][$i]["Geo"]["Lat"]."', '".$jsoncode["dt"]["Go"]["Station"][$i]["Geo"]["Lng"]."')";
			$query = mysqli_query($con, $sql);
			$i++;

			if($query){
				echo "Thành công </br>";

			}
			else{
				echo "Thất bại </br>";
			}
		}

		// to get over the stop for go journey
		$i = 0;
		while($jsoncode["dt"]["Re"]["Station"][$i] != NULL){
			$sql = "INSERT INTO bus_gone_stop(FleetID, ObjectID, Code, Journey, FleetOver, OrderBy, Latitude, Longtitude) VALUES (".strval($jsoncode["dt"]["FleetID"]).", ".strval($jsoncode["dt"]["Re"]["Station"][$i]["ObjectID"]).", '".$jsoncode["dt"]["Re"]["Station"][$i]["Code"]."', 'RE', '".$jsoncode["dt"]["Re"]["Station"][$i]["FleetOver"]."', ".strval($i).", '".$jsoncode["dt"]["Re"]["Station"][$i]["Geo"]["Lat"]."', '".$jsoncode["dt"]["Re"]["Station"][$i]["Geo"]["Lng"]."')";
			$query = mysqli_query($con, $sql);
			$i++;

			if($query){
				echo "Thành công </br>";

			}
			else{
				echo "Thất bại </br>";
			}
		}


	}


?>
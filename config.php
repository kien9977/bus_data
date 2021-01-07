<?php
	$host='localhost';
	$username='root';
	$password='';
	$database='bus_data';

	$con=mysqli_connect($host,$username,$password,$database) or die('không thể kết nối tới cơ sở dữ liệu');

	

	// mysqli_select_db($database,$con);

	// utf8 support
	mysqli_query($con, "SET NAMES 'utf8mb4'");
	mysqli_query($con, "SET CHARACTER SET utf8mb4");
	mysqli_query($con, "SET SESSION collation_connection = 'utf32_general_ci'");

	set_time_limit(100000);
	session_start();

	date_default_timezone_set('Asia/Ho_Chi_Minh');
?>
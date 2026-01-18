<?php
	//$con=mysqli_connect("localhost","root","","greenland");
	//$con=mysqli_connect("localhost:3306","mudikhana","valoMudikhana@2021","greenland");
	// =====================
	// Database Config
	// =====================
	$db_host = 'localhost';
	$db_user = 'root';
	$db_pass = '';
	$db_name = 'greenland';

	// =====================
	// PDO Connection
	// =====================
	$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

	try {
		$pdo = new PDO($dsn, $db_user, $db_pass, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		]);
	} catch (PDOException $e) {
		die("PDO Connection Failed");
	}

	// =====================
	// MySQLi (Keep if old pages need it)
	// =====================
	$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

	if (!$con) {
		die("MySQLi Connection Failed: " . mysqli_connect_error());
	}

	// Return PDO for new modules
	return $pdo;

?>
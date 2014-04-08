<?php

include 'config.php';

function connectMySQL() {
	include 'config.php';
	$db = $conf['mysql_db'];
	$user = $conf['mysql_user'];
	$pass = $conf['mysql_pass'];
	$ip = $conf['mysql_ip'];
	
	$mysqli = new mysqli($ip, $user, $pass, $db);
	
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	return $mysqli;
}
function checkError($query, $conn) {
	if (!$query) {
		printf("Error: %s\n", $conn->error);
		exit();
	}
}

$conn = connectMySQL();

$check = $conn->query("SHOW TABLES LIKE 'players'");
checkError($check, $conn);

if ($check->num_rows > 0) {
	echo "Setup already ran.";
	exit();
}

$results = $conn->query("CREATE TABLE players (ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, username VARCHAR(100), ip VARCHAR(20), lastOpen BIGINT, bId BIGINT)");
checkError($results, $conn);

echo "Setup all set!";
?>
<?php
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

function canOpen() {
	include 'config.php';
	$ip = $_SERVER['REMOTE_ADDR'];
	$user = $_GET['user'];
	
	$conn = connectMySQL();
	
	$stmt = $conn->prepare('SELECT * FROM players WHERE ip = ? OR username = ?');
	if (!stmt) {
		printf("Error: %s\n", $conn->error);
		exit();
	}
	$stmt->bind_param('ss', $ip, $user);
	$stmt->execute();
	
	$stmt->bind_result($id, $username, $_ip, $lastOpen, $bId);
	$a_week = $conf['blindbag_days'] * 24 * 60 * 60;
	while ($stmt->fetch()) {
		$now = time();
		$then = $lastOpen;
		if ($now - $then < $a_week) {
			return false;
		}
	}
	
	return true;
}

$request = $_GET['action'];
header('Access-Control-Allow-Origin: *'); //Allow cross domain access

if ($request == "canOpen") { //Can the current user?
	if (!canOpen()) echo "false";
	else echo "true";
}

else if ($request == "open") {
	if (!canOpen()) echo "failed";
	else {
		echo "temp";
	}
}

?>
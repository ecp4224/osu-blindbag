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

function fetchBeatmaps() {
	include 'config.php';
	include 'beatmap.php';
	
	$time = time();
	$time -= rand(12, 24) * 2419200; 
	
	$timestr = date("Y-m-d H:i:s", $time);
	
	$url = 'https://osu.ppy.sh/api/get_beatmaps';
	$data = array('k' => $conf['osu_api-key'], 'since' => $timestr);

	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data),
		),
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	
	$maps = json_decode($result, true);

	$maparray = array();
	foreach ($maps as $map) {
		$beatmap = new Beatmap($map);
		$maparray[] = $beatmap;
	}
	
	return $maparray;
}

function saveUser($bid) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$user = $_GET['user'];
	
	$conn = connectMySQL();
	$conn->store_result();
	$stmt = $conn->prepare('SELECT COUNT(*) FROM players WHERE ip = ? OR username = ?');
	if (!stmt) {
		printf("Error: %s\n", $conn->error);
		exit();
	}
	$stmt->bind_param('ss', $ip, $user);
	$stmt->execute();
	$num = 0;
	$use_update = false;
	$stmt->bind_result($num);
	while ($stmt->fetch()) {
		if ($num > 0) {
			$use_update = true;
			break;
		}
	}
	$stmt->close();
	
	if ($use_update) {
		$update = $conn->prepare('UPDATE players SET bId = ?, lastOpen = ? WHERE ip = ? OR username = ?');
		if (!$update) {
			printf("Error: %s\n", $conn->error);
			exit();
		}
		$r = $update->bind_param('ssss', $bid, time(), $ip, $user);
		if (!$r) {
			printf("Error: %s\n", $update->error);
			exit();
		}
		$r = $update->execute();
		if (!$r) {
			printf("Error: %s\n", $update->error);
			exit();
		}
		return;
	}
	

	$update = $conn->prepare('INSERT INTO players (username, ip, lastOpen, bId) VALUES (?, ?, ?, ?)');
	if (!$update) {
		printf("Error: %s\n", $conn->error);
		exit();
	}
	$r = $update->bind_param('ssss', $user, $ip, time(), $bid);
	if (!$r) {
		printf("Error: %s\n%s", $update->error);
		exit();
	}
	$r = $update->execute();
	if (!$r) {
		printf("Error: %s\n", $update->error);
		exit();
	}
	return;
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
		$maps = fetchBeatmaps();
		$selected = rand(0, count($maps) - 1);
		$array = array(
			"selected" => $selected,
			"maps" => $maps,
		);
		saveUser($maps[$selected]->beatmapset_id);
		echo json_encode($array);
	}
}

?>
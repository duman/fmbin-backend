<?php
//setting header to json
header('Content-Type: application/json');

//database
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'admin_fmbin');
define('DB_PASSWORD', 'anka0606ankA');
define('DB_NAME', 'admin_fmbin');

//get connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$player_id = mysqli_real_escape_string($mysqli, $_REQUEST['player_id']);

if(!$mysqli){
  die("Connection failed: " . $mysqli->error);
}

//query to get data from the table
$sql = "SELECT MAX(id) as max_id FROM players";
if(!empty($player_id)) {
	$sql .= " WHERE player_id = " . $player_id;
}
$query = sprintf($sql);
// SELECT cname, pos, ovr, MAX(price_value) as max_value, AVG(price_value) as avg_value, MIN(price_value) as min_value FROM information, players WHERE players.player_id = 2 AND information.player_id = 2;
// add WHERE = player_id = "434" to make it runnable with any distinct player
// add WHERE = time_interval_from_user so that only data between certain date/time will be displayed
// SELECT * FROM players WHERE last_report > NOW() - INTERVAL 1 DAY // Returns records within 24 hours

//execute query
$result = $mysqli->query($query);

//loop through the returned data
$data = array();
foreach ($result as $row) {
  $data[] = $row;
}

//free memory associated with result
$result->close();

//close connection
$mysqli->close();

//now print the data
print json_encode($data);
<?php
$link = mysqli_connect("localhost", "admin_fmbin", "anka0606ankA", "admin_fmbin");

if($link === false){
	die("ERROR: Could not connect. " . mysqli_connect_error());
}

$price = mysqli_real_escape_string($link, $_REQUEST['price']);
$price = str_replace(array('.', ','), '' , $price);
$date = date("Y-m-d H:i:s", $current_timestamp);

$sql = "INSERT INTO players (player_id, price_value) VALUES ('1','$price')"; //2nd value represents player_id, should be dynamic

if(mysqli_query($link, $sql)){
	echo "Records added successfully.";
} else{
	echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

mysqli_close($link);
?>
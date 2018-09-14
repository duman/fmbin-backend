<html>
	<body>
		<?php
		$con = mysql_connect("localhost", "admin_fmbin", "anka0606ankA");

		if (!$con) {
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db("admin_fmbin", $con);
		$sql = "INSERT INTO players (id, player_id, price_value, last_report) VALUES ('null', '1','$_POST[price]','CURRENT_TIMESTAMP')"; //2nd value represents player_id, should be dynamic

		if (!mysql_query($sql, $con)) {
			die('Error: ' . mysql_error());
		}

		mysql_close($con);
		?>
	</body>
</html>
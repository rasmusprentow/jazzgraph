<?php
function connect_db() {

	$mysql_host = "localhost";
	$mysql_database = "phponline";
	$mysql_user = "phponline";
	$mysql_password = "fVWXPp4PvQ2uqq5U";
	$con = mysql_connect("$mysql_host","$mysql_user","$mysql_password");	// Opretter forbindelsen til databasen (SERVER,BRUGER,KODE)
	if (!$con) {												// Hvis ikke der blev oprettet forbindelse til databasen
		die('Could not connect: ' . mysql_error());			// Dræber vi alt, og skriver en fejl.
	}
	mysql_select_db("$mysql_database", $con);						// Vælger den database vi skal bruge
}
?>
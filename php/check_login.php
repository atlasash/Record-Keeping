<?php


include "config.php";

// mysql database connection details
$host = DB_HOST;
$username = DB_USER;
$password = DB_PASSWORD;
$dbname = DB_NAME;


	$user = $_POST['check_login_username'];

	mysql_connect($host, $username, $password) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);

	$result = mysql_query("SELECT logged_in FROM users WHERE  user_login='" . $user . "' LIMIT 1");
	$row = mysql_fetch_array($result, MYSQL_NUM);
	$logged = $row[0];

	mysql_free_result($result);

	if($logged != '1'){
		echo ("<SCRIPT LANGUAGE='JavaScript'>window.location.href='http://10.35.0.235/ora/';</SCRIPT>");		
	}


	//echo "<script type='text/javascript'>alert('" . $user . "');</script>";


       

?>
<?php


include "config.php";

// mysql database connection details
$host = DB_HOST;
$username = DB_USER;
$password = DB_PASSWORD;
$dbname = DB_NAME;

	$dbhost = $host;
	$dbuser = $username;
	$dbpass = $password;


	$user_login = $_POST['add_users_user_login'];
	$user_pass = $_POST['add_users_user_pass'];
	$user_firstname = $_POST['add_users_firstname'];
	$user_lastname = $_POST['add_users_lastname'];
	$user_pass = password_hash($user_pass, PASSWORD_BCRYPT);  //encrypt password


	mysql_connect($host, $username, $password) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	$result = mysql_query("SELECT user_login,user_pass,user_firstname,user_lastname,is_admin FROM users WHERE user_login='" . $user_login . "' LIMIT 1");
	$row = mysql_fetch_array($result, MYSQL_NUM);
	$num_rows = mysql_num_rows($result);
	mysql_free_result($result);

	if ($num_rows > 0) {

		echo "<script type='text/javascript'>parent.alert('User " . $user_login . " already exist.');</script>";
		exit;
	}

	$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_select_db($dbname);

	if(! $conn ) {
		die('Could not connect: ' . mysql_error());
	}

	$sql = "INSERT INTO users ". "(user_login,user_pass,user_firstname,user_lastname ) ". "VALUES('$user_login','$user_pass','$user_firstname','$user_lastname')";
	$retval = mysql_query( $sql, $conn );
	if(! $retval ) {
		die('Could not enter data: ' . mysql_error());
	}
	echo "<script type='text/javascript'>parent.alert('User " . $user_login . " has been added. Success.');</script>";
  	  	
	mysql_close($conn);

       

?>
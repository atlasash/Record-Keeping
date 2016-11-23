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
	$ID = $_POST['set_request_id'];

	mysql_connect($host, $username, $password) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	$result = mysql_query("SELECT ID,type,start_date,end_date,hours,approved,rejected FROM request WHERE ID='" . $ID . "' LIMIT 1");
	$row = mysql_fetch_array($result, MYSQL_NUM);
	
	if($row[5] == "1"){
		mysql_free_result($result);
		echo "<script type='text/javascript'>parent.alert('Entry has already been approved.');</script>";
		exit;
	}

	mysql_free_result($result);

	$start_date = date('n/d/Y',strtotime($row[2]));
	$end_date = date('n/d/Y',strtotime($row[3]));

	echo "<script type='text/javascript'>parent.setRequestEntry('" . $row[0] . "','" . $row[1] . "','" . $start_date . "','" . $end_date . "','" . $row[4] . "');</script>";


?>
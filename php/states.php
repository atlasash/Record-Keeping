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

	$user = $_POST['state_username'];
	$state = $_POST['state_id'];
	$date = $_POST['date1'];

	$user_date = date("Y-m-d", strtotime($date));
	$current_date = date("Y-m-d", strtotime("now"));
	$current_date_mdy = date("n/d/Y", strtotime("now"));

	mysql_connect($host, $username, $password) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);

	$result2 = mysql_query("SELECT logged_in FROM users WHERE  user_login='" . $user . "' LIMIT 1");
	$row2 = mysql_fetch_array($result2, MYSQL_NUM);
	$logged = $row2[0];
	mysql_free_result($result2);
	if($logged == "0"){
		echo ("<SCRIPT LANGUAGE='JavaScript'>parent.window.location.href='http://10.35.0.235/ora/';</SCRIPT>");
		exit;
	}

	if($user_date>$current_date){
		echo "<script type='text/javascript'>parent.alert('You can not choose a date in the future. Please choose another date.');</script>";
		echo "<script type='text/javascript'>parent.reset_date('" . $current_date_mdy . "');</script>";
		exit;
	}


	if($state == "2"){
		/*
		echo "<script type='text/javascript'>parent.resetStudyList();</script>";
		$result = mysql_query("SELECT study FROM studies");
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			echo "<script type='text/javascript'>parent.setStudyList('" . $row[0] . "','" . $row[0] . "');</script>";
		}
		*/
		echo "<script type='text/javascript'>parent.state2();</script>";
		exit;

	}
	elseif($state == "3"){

		echo "<script type='text/javascript'>parent.state3();</script>";
		exit;

	}

/*
	if($state == "2"){

		echo "<script type='text/javascript'>parent.state2();</script>";
		//exit;

	}

	echo "<script type='text/javascript'>parent.resetActivity1List();</script>";
	echo "<script type='text/javascript'>parent.resetActivity2List();</script>";
	echo "<script type='text/javascript'>parent.reset_entry();</script>";
	echo "<script type='text/javascript'>parent.resetHistory();</script>";

	$result = mysql_query("SELECT date,study,activity_1,activity_2,hours,ID FROM timesheet_log WHERE user_login='" . $user . "'");
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.addHistory('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $row[4] . "','" . $row[5] . "');</script>";
	}

	echo "<script type='text/javascript'>parent.resetStudyList();</script>";
	$result = mysql_query("SELECT study FROM studies");
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.setStudyList('" . $row[0] . "','" . $row[0] . "');</script>";
	}

	mysql_free_result($result);

*/
       

?>
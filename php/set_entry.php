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
	$ID = $_POST['set_entry_id'];

	mysql_connect($host, $username, $password) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	$result = mysql_query("SELECT ID,study,activity_1,activity_2,date,hours,byweek,year,approved FROM timesheet_log WHERE ID='" . $ID . "' LIMIT 1");
	$row = mysql_fetch_array($result, MYSQL_NUM);
	
	if($row[8] == "1"){
		mysql_free_result($result);
		echo "<script type='text/javascript'>parent.alert('Entry has already been approved.');</script>";
		exit;
	}


/***********set study list START **************/

	echo "<script type='text/javascript'>parent.resetStudyList();</script>";
	$result = mysql_query("SELECT study FROM studies");
	while ($row1 = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.setStudyList('" . $row1[0] . "','" . $row1[0] . "');</script>";
	}

/***********set study list END **************/

/***********set activity 1 list START **************/
	$result = mysql_query("SELECT type FROM studies WHERE study='" . $row[1] . "' LIMIT 1");
	$row2 = mysql_fetch_array($result, MYSQL_NUM);
	$study_type = $row2[0];
	//echo "<script type='text/javascript'>parent.alert('" . $row2[0] . "');</script>";
	echo "<script type='text/javascript'>parent.resetActivity1List();</script>";
	echo "<script type='text/javascript'>parent.resetActivity2List();</script>";

	$result = mysql_query("SELECT activity_1 FROM activity_level_1 WHERE type='" . $study_type . "'");
	while ($row2 = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.setActivity1List('" . $row2[0] . "','" . $row2[0] . "');</script>";
	}
/***********set activity 1 list END **************/


/***********set activity 2 list START **************/
	$result = mysql_query("SELECT type FROM studies WHERE study='" . $row[1] . "' LIMIT 1");
	$row3 = mysql_fetch_array($result, MYSQL_NUM);
	$study_type = $row3[0];
	//echo "<script type='text/javascript'>parent.alert('" . $row3[0] . "');</script>";
	echo "<script type='text/javascript'>parent.resetActivity2List();</script>";

	$result = mysql_query("SELECT activity_2 FROM activity_level_2 WHERE type='" . $study_type . "' AND  activity_1='" . $row[2] . "'");
	while ($row3 = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.setActivity2List('" . $row3[0] . "','" . $row3[0] . "');</script>";
	}
/***********set activity 2 list END **************/

	mysql_free_result($result);

	$date = date('m/d/Y',strtotime($row[4]));

	echo "<script type='text/javascript'>parent.cancel_update_show();</script>";
	echo "<script type='text/javascript'>parent.setEntry('" . $date . "','" . $row[6] . "','" . $row[7] . "','" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $row[5] . "');</script>";


?>
<?php


include "config.php";

// mysql database connection details
$host = DB_HOST;
$username = DB_USER;
$password = DB_PASSWORD;
$dbname = DB_NAME;

	$flag = $_POST['history_flag'];
	$start = $_POST['history_start'];
	$end = $_POST['history_end'];
	$user = $_POST['history_username'];
	$start = date('Y-m-d',strtotime($start));
	$end = date('Y-m-d',strtotime($end));

	echo "<script type='text/javascript'>parent.resetHistory();</script>";
	mysql_connect($host, $username, $password) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);

	if($flag != '2'){
	//$result = mysql_query("SELECT date,study,activity_1,activity_2,hours,ID,notes FROM timesheet_log WHERE user_login='" . $user . "' ORDER BY date DESC");
	$result = mysql_query("SELECT * FROM (SELECT date,study,activity_1,activity_2,hours,ID,approved,notes FROM timesheet_log WHERE user_login='$user' AND approved='0' ORDER BY date ASC) AS sq ORDER BY date ASC");
	}

	if($flag == '2'){
		$result = mysql_query("SELECT * FROM (SELECT date,study,activity_1,activity_2,hours,ID,approved,notes FROM timesheet_log WHERE user_login='$user' AND date >= '$start' AND date <= '$end' ORDER BY date ASC) AS sq ORDER BY date ASC");
	}

	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.addHistory('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $row[4] . "','" . $row[5] . "','" . $row[6] . "','" . $row[7] . "');</script>";
	}


	if($flag != '1'){
	//Set Request History
	$result = mysql_query("SELECT * FROM (SELECT type,start_date,end_date,hours,approved,rejected,ID,note FROM request WHERE user_login='$user' AND approved='0' ORDER BY ID ASC) AS sq ORDER BY ID ASC");
	}	

	if($flag == '1'){
		$result = mysql_query("SELECT * FROM (SELECT type,start_date,end_date,hours,approved,rejected,ID,note FROM request WHERE user_login='$user' AND start_date >= '$start' AND start_date <= '$end' ORDER BY ID ASC) AS sq ORDER BY ID ASC");
	}

	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {	
		if(($row[4] == '0') && ($row[5] == '0')){
			$status = '<font color="orange"><b>PENDING</b></font>';	
		}
		elseif($row[4] == '1'){
			$status = '<font color="green"><b>APPROVED</b></font>';	
		}
		else{
			$status = '<font color="red"><b>REJECTED</b></font>';	
		}
		echo "<script type='text/javascript'>parent.addRequestHistory('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $status . "','" . $row[6] . "','" . $row[7] . "');</script>";
	}


	//Set Admin Request History
	$result = mysql_query("SELECT * FROM (SELECT type,start_date,end_date,hours,approved,rejected,ID,note,user_login FROM request WHERE approved='0' ORDER BY approved DESC) AS sq ORDER BY approved DESC");

	echo "<script type='text/javascript'>parent.reset_request_alert_counter();</script>";

	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

		$result2 = mysql_query("SELECT Company FROM users WHERE user_login='$row[8]' LIMIT 1");
		$row2 = mysql_fetch_array($result2, MYSQL_NUM);
	
		if(($row[4] == '0') && ($row[5] == '0')){
			$status = '<font color="orange"><b>PENDING</b></font>';	
		}
		elseif($row[4] == '1'){
			$status = '<font color="green"><b>APPROVED</b></font>';	
		}
		else{
			$status = '<font color="red"><b>REJECTED</b></font>';	
		}
		echo "<script type='text/javascript'>parent.addRequestHistory_Admin('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $status . "','" . $row[6] . "','" . $row[7] . "','" . $row[8] . "','" . $row2[0] . "');</script>";
	}


	mysql_free_result($result);
	mysql_free_result($result2);


       

?>
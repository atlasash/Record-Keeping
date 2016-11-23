<?php


include "config.php";

// mysql database connection details
$host = DB_HOST;
$username = DB_USER;
$password = DB_PASSWORD;
$dbname = DB_NAME;


	$study = $_POST['set_activity2_study'];
	$activity1 = $_POST['set_activity2_activity1'];

	//$byweek = $_POST['byweek'];
	//$year = $_POST['year'];
	//$day1 = $_POST['day1'] . "/" . $year;
	//$count = count($_POST['day_1']);
	//echo "<script type='text/javascript'>alert('" . $user . "');</script>";
	//echo "<script type='text/javascript'>alert('" . $byweek . "');</script>";

	mysql_connect($host, $username, $password) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);

	$result = mysql_query("SELECT type FROM studies WHERE study='" . $study . "' LIMIT 1");
	$row = mysql_fetch_array($result, MYSQL_NUM);
	$study_type = $row[0];
	//echo "<script type='text/javascript'>parent.alert('" . $row[0] . "');</script>";
	echo "<script type='text/javascript'>parent.resetActivity2List();</script>";

	$result = mysql_query("SELECT activity_2 FROM activity_level_2 WHERE type='" . $study_type . "' AND  activity_1='" . $activity1 . "'");
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.setActivity2List('" . $row[0] . "','" . $row[0] . "');</script>";
	}


/*
		for ($x = 0; $x < $count; $x++) {
			$day1_hours = $_POST['day_1'][$x];
			$day1_study = addslashes($_POST['study'][$x]);
			$day1_activity1 = addslashes($_POST['activity1'][$x]);

			if(!empty($day1_hours)){
				$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	    			mysql_select_db($dbname);

				if(! $conn ) {
					die('Could not connect: ' . mysql_error());
				}

				$sql = "INSERT INTO timesheet_log ". "(user_login,study,activity_1,date,hours,byweek,year,day) ". "VALUES('$user','$day1_study','$day1_activity1','$day1','$day1_hours','$byweek','$year','1')";
				$retval = mysql_query( $sql, $conn );
				if(! $retval ) {
					die('Could not enter data: ' . mysql_error());
				}
				//echo "<script type='text/javascript'>alert('Success');</script>";
			}
	
		} 
		
		echo "<script type='text/javascript'>alert('Hours were saved successfully.');</script>";

	mysql_free_result($result);


*/ 

	mysql_free_result($result);


       

?>
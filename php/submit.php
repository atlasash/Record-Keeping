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

	$user = $_POST['user'];
	$byweek = $_POST['byweek'];
	$year = $_POST['year'];
	$day1 = $_POST['day1'] . "/" . $year;
	$date = $_POST['date2'];
	$date = date('Y-m-d',strtotime($date));
	$ID = $_POST['ID'];

	$day1 = $_POST['day1'];
	$day2 = $_POST['day2'];
	$day3 = $_POST['day3'];
	$day4 = $_POST['day4'];
	$day5 = $_POST['day5'];
	$day6 = $_POST['day6'];
	$day7 = $_POST['day7'];
	$day8 = $_POST['day8'];
	$day9 = $_POST['day9'];
	$day10 = $_POST['day10'];
	$day11 = $_POST['day11'];
	$day12 = $_POST['day12'];
	$day13 = $_POST['day13'];
	$day14 = $_POST['day14'];


	$trigger = date('H:i:s', strtotime('16:30:00')); 
   	date_default_timezone_set('US/Eastern');
   	$currenttime = date('H:i:s');


/******************* START send alert to Users ****************/

		mysql_connect($host, $username, $password) or
    			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		$result = mysql_query("SELECT date,sent FROM alert LIMIT 1");
		$row = mysql_fetch_array($result, MYSQL_NUM);
		$alert_date = $row[0];
		$alert_sent = $row[1];
		
		//$current_date = date('Y-m-d',strtotime($date));
		$current_date = date('Y-m-d');
		$day1 = date('Y-m-d',strtotime($day1));
		$day2 = date('Y-m-d',strtotime($day2));
		$day3 = date('Y-m-d',strtotime($day3));
		$day4 = date('Y-m-d',strtotime($day4));
		$day5 = date('Y-m-d',strtotime($day5));
		$day8 = date('Y-m-d',strtotime($day8));
		$day9 = date('Y-m-d',strtotime($day9));
		$day10 = date('Y-m-d',strtotime($day10));
		$day11= date('Y-m-d',strtotime($day11));
		$day12= date('Y-m-d',strtotime($day12));

		//echo "<script type='text/javascript'>parent.alert('" . $current_date . " " . $currenttime . "');</script>";

		$result = mysql_query("SELECT user_login,user_email,user_firstname,user_lastname FROM users WHERE active='1'");
		$result2 = mysql_query("SELECT user_login,user_email,user_firstname,user_lastname FROM users WHERE active='1'"); //dummy variable to init result2

		if(($currenttime>$trigger) && ($alert_date!=$current_date)){

			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				// clear addresses of all types
				//$mail->ClearAddresses();  // each AddAddress add to list
				//$mail->ClearCCs();
				//$mail->ClearBCCs();
				$temp_name = $row[2];
				$full_name = $row[2] . ' ' . $row[3];
				//$mail->AddAddress($row[1], $temp_name);
				$body = "";
				$page = "";
				$alert = "";
				$email = $row[1];

				if($current_date >= $day1){
					$time_off=0;
					$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $row[0] . "' AND date='$day1' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);
					$num_rows = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND start_date='$day1' AND (type='Personal' OR type='Unpaid') LIMIT 1");
					$num_rows2 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND (start_date<='$day1' AND end_date>='$day1') AND type='Vacation' LIMIT 1");
					$num_rows3 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT day1_waiver FROM users WHERE user_login='" . $row[0] . "' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);

					if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
					}
					else{

							$alert .= $day1 . '<br>';
					}

				}
				if($current_date >= $day2){
					$time_off=0;
					$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $row[0] . "' AND date='$day2' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);
					$num_rows = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND start_date='$day2' AND (type='Personal' OR type='Unpaid') LIMIT 1");
					$num_rows2 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND (start_date<='$day2' AND end_date>='$day2') AND type='Vacation' LIMIT 1");
					$num_rows3 = mysql_num_rows($result2);

					$result2 = mysql_query("SELECT day2_waiver FROM users WHERE user_login='" . $row[0] . "' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);

					if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
					}
					else{

							$alert .= $day2 . '<br>';
					}

				}
				if($current_date >= $day3){
					$time_off=0;
					$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $row[0] . "' AND date='$day3' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);
					$num_rows = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND start_date='$day3' AND (type='Personal' OR type='Unpaid') LIMIT 1");
					$num_rows2 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND (start_date<='$day3' AND end_date>='$day3') AND type='Vacation' LIMIT 1");
					$num_rows3 = mysql_num_rows($result2);

					$result2 = mysql_query("SELECT day3_waiver FROM users WHERE user_login='" . $row[0] . "' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);

					if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
					}
					else{

							$alert .= $day3 . '<br>';
					}

				}
				if($current_date >= $day4){
					$time_off=0;
					$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $row[0] . "' AND date='$day4' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);
					$num_rows = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND start_date='$day4' AND (type='Personal' OR type='Unpaid') LIMIT 1");
					$num_rows2 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND (start_date<='$day4' AND end_date>='$day4') AND type='Vacation' LIMIT 1");
					$num_rows3 = mysql_num_rows($result2);

					$result2 = mysql_query("SELECT day4_waiver FROM users WHERE user_login='" . $row[0] . "' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);

					if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
					}
					else{

							$alert .= $day4 . '<br>';
					}

				}
				if($current_date >= $day5){
					$time_off=0;
					$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $row[0] . "' AND date='$day5' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);
					$num_rows = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND start_date='$day5' AND (type='Personal' OR type='Unpaid') LIMIT 1");
					$num_rows2 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND (start_date<='$day5' AND end_date>='$day5') AND type='Vacation' LIMIT 1");
					$num_rows3 = mysql_num_rows($result2);

					$result2 = mysql_query("SELECT day5_waiver FROM users WHERE user_login='" . $row[0] . "' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);

					if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
					}
					else{

							$alert .= $day5 . '<br>';
					}

				}
				if($current_date >= $day8){
					$time_off=0;
					$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $row[0] . "' AND date='$day8' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);
					$num_rows = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND start_date='$day8' AND (type='Personal' OR type='Unpaid') LIMIT 1");
					$num_rows2 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND (start_date<='$day8' AND end_date>='$day8') AND type='Vacation' LIMIT 1");
					$num_rows3 = mysql_num_rows($result2);

					$result2 = mysql_query("SELECT day1_waiver FROM users WHERE user_login='" . $row[0] . "' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);

					if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
					}
					else{

							$alert .= $day8 . '<br>';
					}

				}
				if($current_date >= $day9){
					$time_off=0;
					$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $row[0] . "' AND date='$day9' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);
					$num_rows = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND start_date='$day9' AND (type='Personal' OR type='Unpaid') LIMIT 1");
					$num_rows2 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND (start_date<='$day9' AND end_date>='$day9') AND type='Vacation' LIMIT 1");
					$num_rows3 = mysql_num_rows($result2);

					$result2 = mysql_query("SELECT day2_waiver FROM users WHERE user_login='" . $row[0] . "' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);

					if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
					}
					else{

							$alert .= $day9 . '<br>';
					}

				}
				if($current_date >= $day10){
					$time_off=0;
					$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $row[0] . "' AND date='$day10' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);
					$num_rows = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND start_date='$day10' AND (type='Personal' OR type='Unpaid') LIMIT 1");
					$num_rows2 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND (start_date<='$day10' AND end_date>='$day10') AND type='Vacation' LIMIT 1");
					$num_rows3 = mysql_num_rows($result2);

					$result2 = mysql_query("SELECT day3_waiver FROM users WHERE user_login='" . $row[0] . "' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);

					if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
					}
					else{

							$alert .= $day10 . '<br>';
					}

				}
				if($current_date >= $day11){
					$time_off=0;
					$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $row[0] . "' AND date='$day11' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);
					$num_rows = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND start_date='$day11' AND (type='Personal' OR type='Unpaid') LIMIT 1");
					$num_rows2 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND (start_date<='$day11' AND end_date>='$day11') AND type='Vacation' LIMIT 1");
					$num_rows3 = mysql_num_rows($result2);

					$result2 = mysql_query("SELECT day4_waiver FROM users WHERE user_login='" . $row[0] . "' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);

					if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
					}
					else{

							$alert .= $day11 . '<br>';
					}

				}
				if($current_date >= $day12){
					$time_off=0;
					$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $row[0] . "' AND date='$day12' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);
					$num_rows = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND start_date='$day12' AND (type='Personal' OR type='Unpaid') LIMIT 1");
					$num_rows2 = mysql_num_rows($result2);
					$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$row[0]' AND (start_date<='$day12' AND end_date>='$day12') AND type='Vacation' LIMIT 1");
					$num_rows3 = mysql_num_rows($result2);

					$result2 = mysql_query("SELECT day5_waiver FROM users WHERE user_login='" . $row[0] . "' LIMIT 1");
					$row2 = mysql_fetch_array($result2, MYSQL_NUM);

					if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
					}
					else{

							$alert .= $day12 . '<br>';
					}

				}
				
				$body = $alert;
				$link = 'http://www.veritasirb.com/htmmail/sendit.php?body=' . urlencode($body) . '&email=' . urlencode($email) . '&name=' . $temp_name . '&fullname=' . urlencode($full_name);
				if(($alert != "") && ($user != $row[0])){

					$page = file_get_contents($link);
				}

			}

		}//trigger alerts

		if(($currenttime>$trigger) && ($alert_date!=$current_date)){
			$result = mysql_query("UPDATE alert SET date='$current_date' WHERE ID='1'");
		}

		mysql_free_result($result);
		mysql_free_result($result2);
		echo "<script type='text/javascript'>parent.loading_hide();</script>";
/******************* END send alert to Users ****************/


/******************* START Check if user didnt enter time for week ****************/

		mysql_connect($host, $username, $password) or
    			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);

		$current_date = date('Y-m-d',strtotime($date));
		$day1 = date('Y-m-d',strtotime($day1));
		$day2 = date('Y-m-d',strtotime($day2));
		$day3 = date('Y-m-d',strtotime($day3));
		$day4 = date('Y-m-d',strtotime($day4));
		$day5 = date('Y-m-d',strtotime($day5));
		$day8 = date('Y-m-d',strtotime($day8));
		$day9 = date('Y-m-d',strtotime($day9));
		$day10 = date('Y-m-d',strtotime($day10));
		$day11= date('Y-m-d',strtotime($day11));
		$day12= date('Y-m-d',strtotime($day12));

		if($current_date > $day1){
			$time_off=0;
			$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND date='$day1' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);
			$num_rows = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND start_date='$day1' AND (type='Personal' OR type='Unpaid') LIMIT 1");
			$num_rows2 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND (start_date<='$day1' AND end_date>='$day1') AND type='Vacation' LIMIT 1");
			$num_rows3 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT day1_waiver FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);


			if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
			}
			else{

					echo "<script type='text/javascript'>alert('Please enter your hours for " . $day1 . "');</script>";exit;
			}

		}
		if($current_date > $day2){
			$time_off=0;
			$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND date='$day2' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);
			$num_rows = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND start_date='$day2' AND (type='Personal' OR type='Unpaid') LIMIT 1");
			$num_rows2 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND (start_date<='$day2' AND end_date>='$day2') AND type='Vacation' LIMIT 1");
			$num_rows3 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT day2_waiver FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);

			if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
			}
			else{

					echo "<script type='text/javascript'>alert('Please enter your hours for " . $day2 . "');</script>";exit;
			}

		}
		if($current_date > $day3){
			$time_off=0;
			$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND date='$day3' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);
			$num_rows = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND start_date='$day3' AND (type='Personal' OR type='Unpaid') LIMIT 1");
			$num_rows2 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND (start_date<='$day3' AND end_date>='$day3') AND type='Vacation' LIMIT 1");
			$num_rows3 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT day3_waiver FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);

			if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
			}
			else{

					echo "<script type='text/javascript'>alert('Please enter your hours for " . $day3 . "');</script>";exit;
			}

		}
		if($current_date > $day4){
			$time_off=0;
			$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND date='$day4' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);
			$num_rows = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND start_date='$day4' AND (type='Personal' OR type='Unpaid') LIMIT 1");
			$num_rows2 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND (start_date<='$day4' AND end_date>='$day4') AND type='Vacation' LIMIT 1");
			$num_rows3 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT day4_waiver FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);

			if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
			}
			else{

					echo "<script type='text/javascript'>alert('Please enter your hours for " . $day4 . "');</script>";exit;
			}

		}
		if($current_date > $day5){
			$time_off=0;
			$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND date='$day5' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);
			$num_rows = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND start_date='$day5' AND (type='Personal' OR type='Unpaid') LIMIT 1");
			$num_rows2 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND (start_date<='$day5' AND end_date>='$day5') AND type='Vacation' LIMIT 1");
			$num_rows3 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT day5_waiver FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);

			if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
			}
			else{

					echo "<script type='text/javascript'>alert('Please enter your hours for " . $day5 . "');</script>";exit;
			}

		}
		if($current_date > $day8){
			$time_off=0;
			$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND date='$day8' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);
			$num_rows = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND start_date='$day8' AND (type='Personal' OR type='Unpaid') LIMIT 1");
			$num_rows2 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND (start_date<='$day8' AND end_date>='$day8') AND type='Vacation' LIMIT 1");
			$num_rows3 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT day1_waiver FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);

			if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
			}
			else{

					echo "<script type='text/javascript'>alert('Please enter your hours for " . $day8 . "');</script>";exit;
			}

		}
		if($current_date > $day9){
			$time_off=0;
			$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND date='$day9' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);
			$num_rows = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND start_date='$day9' AND (type='Personal' OR type='Unpaid') LIMIT 1");
			$num_rows2 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND (start_date<='$day9' AND end_date>='$day9') AND type='Vacation' LIMIT 1");
			$num_rows3 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT day2_waiver FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);

			if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
			}
			else{

					echo "<script type='text/javascript'>alert('Please enter your hours for " . $day9 . "');</script>";exit;
			}

		}
		if($current_date > $day10){
			$time_off=0;
			$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND date='$day10' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);
			$num_rows = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND start_date='$day10' AND (type='Personal' OR type='Unpaid') LIMIT 1");
			$num_rows2 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND (start_date<='$day10' AND end_date>='$day10') AND type='Vacation' LIMIT 1");
			$num_rows3 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT day3_waiver FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);

			if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
			}
			else{

					echo "<script type='text/javascript'>alert('Please enter your hours for " . $day10 . "');</script>";exit;
			}

		}
		if($current_date > $day11){
			$time_off=0;
			$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND date='$day11' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);
			$num_rows = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND start_date='$day11' AND (type='Personal' OR type='Unpaid') LIMIT 1");
			$num_rows2 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND (start_date<='$day11' AND end_date>='$day11') AND type='Vacation' LIMIT 1");
			$num_rows3 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT day4_waiver FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);

			if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
			}
			else{

					echo "<script type='text/javascript'>alert('Please enter your hours for " . $day11 . "');</script>";exit;
			}

		}
		if($current_date > $day12){
			$time_off=0;
			$result2 = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND date='$day12' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);
			$num_rows = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND start_date='$day12' AND (type='Personal' OR type='Unpaid') LIMIT 1");
			$num_rows2 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT start_date FROM request WHERE user_login='$user' AND (start_date<='$day12' AND end_date>='$day12') AND type='Vacation' LIMIT 1");
			$num_rows3 = mysql_num_rows($result2);
			$result2 = mysql_query("SELECT day5_waiver FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row2 = mysql_fetch_array($result2, MYSQL_NUM);

			if ($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $row2[0]=='1') {
			}
			else{

					echo "<script type='text/javascript'>alert('Please enter your hours for " . $day12 . "');</script>";exit;
			}

		}

		mysql_free_result($result2);

/******************* END Check if user didnt enter time for week ****************/



	$count = count($_POST['day_1']);
	//echo "<script type='text/javascript'>alert('" . $user . "');</script>";
	//echo "<script type='text/javascript'>alert('" . $byweek . "');</script>";

	if($ID == "0"){

		for ($x = 0; $x < $count; $x++) {
			$notes = $_POST['notes'][$x];
			$day1_hours = $_POST['day_1'][$x];
			$day1_study = addslashes($_POST['study'][$x]);
			$day1_activity1 = addslashes($_POST['activity1'][$x]);
			$day1_activity2 = addslashes($_POST['activity2'][$x]);

			if(is_numeric($day1_hours)&&!empty($day1_study)&&!empty($day1_activity1)){
				$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	    			mysql_select_db($dbname);

				if(! $conn ) {
					die('Could not connect: ' . mysql_error());
				}

				$sql = "INSERT INTO timesheet_log ". "(user_login,study,activity_1,activity_2,date,hours,byweek,year,day,notes) ". "VALUES('$user','$day1_study','$day1_activity1','$day1_activity2','$date','$day1_hours','$byweek','$year','1','$notes')";
				$retval = mysql_query( $sql, $conn );
				if(! $retval ) {
					die('Could not enter data: ' . mysql_error());
				}
				//echo "<script type='text/javascript'>alert('Success');</script>";
			}
	
		} 
		
		if(is_numeric($day1_hours)&&!empty($day1_study)&&!empty($day1_activity1)){
			echo "<script type='text/javascript'>alert('Hours were saved successfully.');</script>";
		}
		else{
			echo "<script type='text/javascript'>alert('Please select an action and activity. Press save to record hours.');</script>";
		}

		echo "<script type='text/javascript'>parent.resetActivity1List();</script>";
		echo "<script type='text/javascript'>parent.resetActivity2List();</script>";
		echo "<script type='text/javascript'>parent.reset_entry();</script>";
		//echo "<script type='text/javascript'>parent.click_expense();</script>";
  	  	
		mysql_close($conn);
	}
	else{

		for ($x = 0; $x < $count; $x++) {
			$notes = $_POST['notes'][$x];
			$day1_hours = $_POST['day_1'][$x];
			$day1_study = addslashes($_POST['study'][$x]);
			$day1_activity1 = addslashes($_POST['activity1'][$x]);
			$day1_activity2 = addslashes($_POST['activity2'][$x]);

			//if(!empty($day1_hours)&&!empty($day1_study)&&!empty($day1_activity1)){
				$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	    			mysql_select_db($dbname);

				if(! $conn ) {
					die('Could not connect: ' . mysql_error());
				}

				
				$sql = "UPDATE timesheet_log ". "SET user_login='$user',study='$day1_study',activity_1='$day1_activity1',activity_2='$day1_activity2',date='$date',hours='$day1_hours',byweek='$byweek',year='$year',day='1',notes='$notes' ". "WHERE ID='$ID'";
				$retval = mysql_query( $sql, $conn );
				if(! $retval ) {
					die('Could not enter data: ' . mysql_error());
				}
				//echo "<script type='text/javascript'>alert('Success');</script>";
			//}
	
		} 
		
		echo "<script type='text/javascript'>alert('Hours were updated successfully.');</script>";

		echo "<script type='text/javascript'>parent.cancel_update_hide();</script>";
		echo "<script type='text/javascript'>parent.resetActivity1List();</script>";
		echo "<script type='text/javascript'>parent.resetActivity2List();</script>";
		echo "<script type='text/javascript'>parent.reset_entry();</script>";
		//echo "<script type='text/javascript'>parent.click_expense();</script>";
  	  	
		mysql_close($conn);

	}


		
	echo "<script type='text/javascript'>parent.resetHistory();</script>";
	echo "<script type='text/javascript'>parent.resetMessage();</script>";
	mysql_connect($host, $username, $password) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);

	//$result = mysql_query("SELECT date,study,activity_1,activity_2,hours,ID FROM timesheet_log WHERE user_login='" . $user . "' ORDER BY date DESC");
	$result = mysql_query("SELECT * FROM (SELECT date,study,activity_1,activity_2,hours,ID,approved,notes FROM timesheet_log WHERE user_login='$user' AND approved='0' ORDER BY date ASC) AS sq ORDER BY date ASC");
	
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.addHistory('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $row[4] . "','" . $row[5] . "','" . $row[6] . "','" . $row[7] . "');</script>";
	}

	//Set Request History
	$result = mysql_query("SELECT * FROM (SELECT type,start_date,end_date,hours,approved,rejected,ID,note FROM request WHERE user_login='$user' AND approved='0' ORDER BY ID ASC) AS sq ORDER BY ID ASC");

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

	echo "<script type='text/javascript'>parent.resetStudyList();</script>";
	$result = mysql_query("SELECT study FROM studies");
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.setStudyList('" . $row[0] . "','" . $row[0] . "');</script>";
	}

	mysql_free_result($result);
	mysql_free_result($result2);


       

?>
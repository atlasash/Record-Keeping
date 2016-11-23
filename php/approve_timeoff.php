<?php

function getWorkingDays($startDate,$endDate,$holidays){
    // do strtotime calculations just once
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);


    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    }
    else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)

        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        }
        else {
            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
            // so we skip an entire weekend and subtract 2 days
            $no_remaining_days -= 2;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
   $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
      $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    foreach($holidays as $holiday){
        $time_stamp=strtotime($holiday);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;
}

$holidays=array("");


include "config.php";

// mysql database connection details
$host = DB_HOST;
$username = DB_USER;
$password = DB_PASSWORD;
$dbname = DB_NAME;

	$dbhost = $host;
	$dbuser = $username;
	$dbpass = $password;
	$user = $_POST['approve_timeoff_user'];
	$ID = $_POST['approve_timeoff_id'];
	$admin = $_POST['approve_timeoff_admin'];


	//echo "<script type='text/javascript'>parent.alert('Entry has already been approved.');</script>";

/************************   Get Requested Days/Hours START   *************/

		mysql_connect($host, $username, $password) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		$result = mysql_query("SELECT hours,start_date,end_date,type,user_login,approved FROM request WHERE ID='" . $ID . "' LIMIT 1");
		$row = mysql_fetch_array($result, MYSQL_NUM);
		mysql_free_result($result);

		$user = $row[4];
		$approved = $row[5];
		$request_hours = $row[0];
		$request_type = $row[3];
		$request_start_date = $row[1];
		$request_end_date = $row[2];

		if($approved == "1"){
			echo "<script type='text/javascript'>alert('Request has already been approved.');</script>";
			echo "<script type='text/javascript'>parent.loading_hide();</script>";
			exit(); 
		}



/************************   Check Vacation Days Remaining END   *************/


/************************   Check Vacation Days Remaining START   *************/

		if($request_type == 'Vacation'){


			$start = new DateTime($request_start_date);
			$end = new DateTime($request_end_date);
			$difference = $start->diff($end);
			if($start > $end){ 
				echo "<script type='text/javascript'>parent.loading_hide();</script>";
				exit(); 
			}
			//$days_requested = $difference->d + 1;
			$days_requested = floor(getWorkingDays($request_start_date,$request_end_date,$holidays));

			mysql_connect($host, $username, $password) or
				die("Could not connect: " . mysql_error());
			mysql_select_db($dbname);
			$result = mysql_query("SELECT vacation,vacation_used FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row = mysql_fetch_array($result, MYSQL_NUM);
			mysql_free_result($result);

			$days_available = $row[0];
			$days_used = $row[1];
			$days_available = intval($days_available);
			$days_used = intval($days_used);



			if($days_requested > $days_available){
				echo "<script type='text/javascript'>alert('Only " . $days_available . " vacation days available.');</script>";
				echo "<script type='text/javascript'>parent.loading_hide();</script>";
				exit(); 
			}
			else{


				$days_update = $days_available - $days_requested;
				$days_used_update = $days_used + $days_requested;

				$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	    			mysql_select_db($dbname);
				if(! $conn ) {
					die('Could not connect: ' . mysql_error());
				}
				$sql = "UPDATE users SET vacation='$days_update',vacation_used='$days_used_update' WHERE user_login='$user' LIMIT 1";
				$retval = mysql_query( $sql, $conn );
				if(! $retval ) {
					die('Could not enter data: ' . mysql_error());
				}
				mysql_close($conn);

				mysql_connect($host, $username, $password) or
					die("Could not connect: " . mysql_error());
				mysql_select_db($dbname);
				$result = mysql_query("UPDATE request SET approved='1',rejected='0',approved_by='$admin' WHERE ID='" . $ID . "'");
				mysql_free_result($result);

				if($user == $_POST['approve_timeoff_user'])
				{
					echo "<script type='text/javascript'>parent.resetVacation('" . $days_update . "','" . $days_used_update . "','none');</script>";
				}
			
			}



		}

/************************   Check Vacation Days Remaining END   *************/




/************************   Check Personal Hours Remaining START   *************/

		if($request_type == 'Personal'){
			mysql_connect($host, $username, $password) or
				die("Could not connect: " . mysql_error());
			mysql_select_db($dbname);
			$result = mysql_query("SELECT personal_time FROM users WHERE user_login='" . $user . "' LIMIT 1");
			$row = mysql_fetch_array($result, MYSQL_NUM);
			mysql_free_result($result);

			$hours_available = $row[0];
			$hours_available = floatval($hours_available);
			$hours = floatval($request_hours);

			if($hours > $hours_available){
				echo "<script type='text/javascript'>alert('Only " . $hours_available . " personal hours available.');</script>";
				echo "<script type='text/javascript'>parent.loading_hide();</script>";
				exit(); 
			}
			else{
				$hours_update = $hours_available - $hours;

				mysql_connect($host, $username, $password) or
					die("Could not connect: " . mysql_error());
				mysql_select_db($dbname);
				$result = mysql_query("UPDATE request SET approved='1',rejected='0',approved_by='$admin' WHERE ID='" . $ID . "'");
				$result = mysql_query("UPDATE users SET personal_time='$hours_update' WHERE user_login='$user' LIMIT 1");
				mysql_free_result($result);


				if($user == $_POST['approve_timeoff_user'])
				{
					echo "<script type='text/javascript'>parent.resetVacation('none','none','" . $hours_update . "');</script>";
				}

			}

		}

/************************   Check Personal Hours Remaining END   *************/


		if($request_type == 'Unpaid'){

				mysql_connect($host, $username, $password) or
					die("Could not connect: " . mysql_error());
				mysql_select_db($dbname);
				$result = mysql_query("UPDATE request SET approved='1',rejected='0',approved_by='$admin' WHERE ID='" . $ID . "'");
				mysql_free_result($result);

		}


	$user = $_POST['approve_timeoff_user'];
/****************************************** Reset History START ****************************************************************/
		
	echo "<script type='text/javascript'>parent.resetHistory();</script>";
	echo "<script type='text/javascript'>parent.resetMessage();</script>";
	echo "<script type='text/javascript'>parent.resetRequest();</script>";
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

	echo "<script type='text/javascript'>parent.loading_hide();</script>";

/****************************************** Reset History END ****************************************************************/
	
?>
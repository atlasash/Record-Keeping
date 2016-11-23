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

	$user = $_POST['user_request'];
	$type = $_POST['activity_request'];
	$ID = $_POST['ID_request'];
	$hours = $_POST['hours_request'];

	$cancel = $_POST['cancel'];

	$current_date = date('n/j/Y');
	$start_date = $_POST['textbox2'];
	$end_date = $_POST['textbox3'];
	$note = $_POST['note_request'];

	$start_date = date('Y-m-d',strtotime($start_date));
	$end_date = date('Y-m-d',strtotime($end_date));

	if($ID == "0" && $cancel=='1'){
	
		echo "<script type='text/javascript'>parent.state2();</script>";
		exit();
	}

	if($type == 'Vacation' && $cancel=='0'){
		$start = new DateTime($start_date);
		$end = new DateTime($end_date);
		$difference = $start->diff($end);
		if($start > $end){ exit(); }
		$hours = 0;
		//$days_requested = $difference->d + 1;
		$days_requested = floor(getWorkingDays($start_date,$end_date,$holidays));

/************************   Check Vacation Days Remaining START   *************/
		mysql_connect($host, $username, $password) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		$result = mysql_query("SELECT vacation FROM users WHERE  user_login='" . $user . "' LIMIT 1");
		$row = mysql_fetch_array($result, MYSQL_NUM);
		mysql_free_result($result);

/************************   Check Vacation Days Remaining END   *************/

		$days_available = $row[0];
		$days_available = intval($days_available);

		if($days_requested > $days_available){
			echo "<script type='text/javascript'>alert('Your only have " . $days_available . " vacation days available.');</script>";
			exit(); 
		}
		

	}
	if($type == 'Personal'){
		$end_date = '0000-00-00';

/************************   Check Personal Hours Remaining START   *************/
		mysql_connect($host, $username, $password) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		$result = mysql_query("SELECT personal_time FROM users WHERE user_login='" . $user . "' LIMIT 1");
		$row = mysql_fetch_array($result, MYSQL_NUM);
		mysql_free_result($result);

/************************   Check Personal Hours Remaining END   *************/

		$hours_available = $row[0];
		$hours_available = floatval($hours_available);
		$hours = floatval($hours);

		if($hours > $hours_available){
			echo "<script type='text/javascript'>alert('Your only have " . $hours_available . " personal hours available.');</script>";
			exit(); 
		}

	}
	if($type == 'Unpaid'){
		$end_date = '0000-00-00';
	}
	
	
	if($ID == "0" && $cancel=='0'){

		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db($dbname);

		if(! $conn ) {
			die('Could not connect: ' . mysql_error());
		}

				
		$sql = "INSERT INTO request ". "(user_login,type,start_date,end_date,note,hours) ". "VALUES('$user','$type','$start_date','$end_date','$note','$hours')";
		$retval = mysql_query( $sql, $conn );
		if(! $retval ) {
			die('Could not enter data: ' . mysql_error());
		}
			  	
		mysql_close($conn);

		echo "<script type='text/javascript'>alert('Your request has been submitted.');</script>";
	}
	elseif($ID == "0" && $cancel=='1'){
	
		echo "<script type='text/javascript'>parent.state2();</script>";

	}
	else{

		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db($dbname);

		if(! $conn ) {
			die('Could not connect: ' . mysql_error());
		}

		if($cancel=='1'){
			$sql = "DELETE FROM request WHERE ID='$ID'";
			$retval = mysql_query( $sql, $conn );
			if(! $retval ) {
				die('Could not enter data: ' . mysql_error());
			}
	  	
			mysql_close($conn);
		}
		else{
			$sql = "UPDATE request ". "SET user_login='$user',type='$type',start_date='$start_date',end_date='$end_date',note='$note',hours='$hours',rejected='0' ". "WHERE ID='$ID'";
			$retval = mysql_query( $sql, $conn );
			if(! $retval ) {
				die('Could not enter data: ' . mysql_error());
			}
	  	
			mysql_close($conn);
		}


		if($cancel=='1'){
			echo "<script type='text/javascript'>alert('Your request has been canceled.');</script>";
		}
		else{
			echo "<script type='text/javascript'>alert('Your request has been updated.');</script>";
		}

	}




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

/****************************************** Reset History END ****************************************************************/


       

?>
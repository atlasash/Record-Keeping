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

define('DB_NAME', 'LogTE');
/** MySQL database username */
define('DB_USER', 'root');
/** MySQL database password */
define('DB_PASSWORD', '5ofikA@)!#.');
/** MySQL hostname */
define('DB_HOST', 'localhost');

// mysql database connection details
$host = DB_HOST;
$username = DB_USER;
$password = DB_PASSWORD;
$dbname = DB_NAME;

$dbhost = $host;
$dbuser = $username;
$dbpass = $password;

	$action = $_POST['action_request'];
	$ID = $_POST['ID_request_reports'];
	$current_date = date('n/j/Y');
	$start_date = $_POST['textbox4'];
	$end_date = $_POST['textbox5'];
	$count = count($_POST['requests_id']);

	$start_date = date('Y-m-d',strtotime($start_date));
	$end_date = date('Y-m-d',strtotime($end_date));
	$total_hours = 0;




if($action == "1"){

	// output headers so that the file is downloaded rather than displayed
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=report.csv');

	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	// output the column headings
	/*  fputcsv($output, array('Column 1', 'Column 2', 'Column 3'));  */
	fputcsv($output, array('Total time','From ' . $start_date,'Ending ' . $end_date));
	fputcsv($output, array(''));
	fputcsv($output, array('EMPLOYEE NAME', 'Total'));

	// fetch the data
	//mysql_connect('localhost', 'username', 'password');
	//mysql_select_db('database');
	//$rows = mysql_query('SELECT field1,field2,field3 FROM table');

	// loop over the rows, outputting them
	//while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);

	
	if($ID == "0"){

		for ($x = 1; $x < $count; $x++) {
			$total_hours_employee = 0;
			$user = $_POST['requests_id'][$x];
			$name = $_POST['requests_name'][$x];
			$flag = $_POST['requests_set'][$x];

			if($flag=="1"){

				mysql_connect($host, $username, $password) or
					die("Could not connect: " . mysql_error());
				mysql_select_db($dbname);

				$result = mysql_query("SELECT hours,date FROM timesheet_log WHERE  user_login='" . $user . "'");

				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

					$entry_date = $row[1];
					$entry_date = date('Y-m-d',strtotime($entry_date));
					$entry_hours = $row[0];
					$entry_hours = floatval($entry_hours);
					if( ($entry_date>=$start_date) && ($entry_date<=$end_date) ){

						$total_hours_employee += $entry_hours;
					}


				}
	
				fputcsv($output, array($name, $total_hours_employee));
				$total_hours += $total_hours_employee;
				mysql_free_result($result);


			}

	
		} //for loop
	
		fputcsv($output, array('Total', $total_hours));
		fputcsv($output, array(''));
		fputcsv($output, array(''));
		fputcsv($output, array(''));
		fputcsv($output, array('ACTIVITY', 'Personal Time','Unpaid Leave','Vacation','Total'));

		$total_personal_hours = 0;
		$total_unpaid_hours = 0;
		$total_vacation_days = 0;
		for ($x = 1; $x < $count; $x++) {
			$total_personal_hours_employee = 0;
			$total_unpaid_hours_employee = 0;
			$total_vacation_days_employee = 0;
			$user = $_POST['requests_id'][$x];
			$name = $_POST['requests_name'][$x];
			$flag = $_POST['requests_set'][$x];

			if($flag=="1"){

				mysql_connect($host, $username, $password) or
					die("Could not connect: " . mysql_error());
				mysql_select_db($dbname);

				//$result = mysql_query("SELECT hours,start_date,type,approved FROM request WHERE user_login='" . $user . "' AND (type='Personal' OR type='Unpaid')");
				$result = mysql_query("SELECT hours,start_date,type,approved,end_date FROM request WHERE user_login='" . $user . "'");

				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

					$start = $row[1];
					$end = $row[4];
					if($row[2]=='Vacation'){
						$days_requested = floor(getWorkingDays($start,$end,$holidays));
						$days_period = floor(getWorkingDays($start,$end_date,$holidays));
					}
					
					$entry_date = $row[1];
					$entry_date = date('Y-m-d',strtotime($entry_date));
					$entry_hours = $row[0];
					$entry_hours = floatval($entry_hours);
					if( ($entry_date>=$start_date) && ($entry_date<=$end_date) ){

						if($row[2]=='Personal'){
							$total_personal_hours_employee += $entry_hours;
						}
						if($row[2]=='Unpaid'){
							$total_unpaid_hours_employee += $entry_hours;
						}
						if($row[2]=='Vacation'){
							if($days_requested>$days_period){
								$total_vacation_days_employee += $days_period;
							}
							else{
								$total_vacation_days_employee += $days_requested;	
							}
						}
					}


				}
				
				if(($total_personal_hours_employee>0) || ($total_unpaid_hours_employee>0)){	
					fputcsv($output, array($name, $total_personal_hours_employee,$total_unpaid_hours_employee,$total_vacation_days_employee,($total_personal_hours_employee+$total_unpaid_hours_employee)));
				}
				$total_personal_hours += $total_personal_hours_employee;
				$total_unpaid_hours += $total_unpaid_hours_employee;
				$total_vacation_days += $total_vacation_days_employee;
				mysql_free_result($result);


			}

	
		} //for loop


		fputcsv($output, array('Total', $total_personal_hours,$total_unpaid_hours,$total_vacation_days,($total_personal_hours+$total_unpaid_hours)));

		exit();

	}//ID IF
	else{
		
		exit();
	}

} //action1 IF
if($action == "2"){


	if($ID == "0"){

		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	    	mysql_select_db($dbname);
		if(! $conn ) {
			die('Could not connect: ' . mysql_error());
		}

		for ($x = 1; $x < $count; $x++) {
			$total_hours_employee = 0;
			$user = $_POST['requests_id'][$x];
			$name = $_POST['requests_name'][$x];
			$flag = $_POST['requests_set'][$x];

			if($flag=="1"){


				$sql = "UPDATE timesheet_log SET approved='1' WHERE date >= '$start_date' AND date <= '$end_date'";
				$retval = mysql_query( $sql, $conn );
				if(! $retval ) {
					die('Could not enter data: ' . mysql_error());
				}
/*
				$result = mysql_query("SELECT date FROM timesheet_log WHERE user_login='" . $user . "' AND (date>='$start_date' AND date<='$end_date')");

				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

					$entry_date = $row[0];
					$entry_date = date('Y-m-d',strtotime($entry_date));
					if( ($entry_date>=$start_date) && ($entry_date<=$end_date) ){

						$result2 = mysql_query("UPDATE timesheet_log SET approved='1' WHERE user_login='" . $user . "' AND date='" . $entry_date . "'");

					}


				}
*/

			}

	
		} //for loop

		mysql_close($conn);

		echo "<script type='text/javascript'>alert('Hours have been Approved.');</script>";

	}//ID IF
	else{
	}

/****************************************** Reset History START ****************************************************************/
		
	echo "<script type='text/javascript'>parent.resetHistory();</script>";
	echo "<script type='text/javascript'>parent.resetMessage();</script>";
	echo "<script type='text/javascript'>parent.resetRequest();</script>";
	mysql_connect($host, $username, $password) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);

	//$result = mysql_query("SELECT date,study,activity_1,activity_2,hours,ID FROM timesheet_log WHERE user_login='" . $user . "' ORDER BY date DESC");
	$result = mysql_query("SELECT * FROM (SELECT date,study,activity_1,activity_2,hours,ID,approved FROM timesheet_log WHERE user_login='" . $user . "' ORDER BY date ASC) AS sq ORDER BY date ASC");

	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.addHistory('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $row[4] . "','" . $row[5] . "','" . $row[6] . "');</script>";
	}


	//Set Request History
	$result = mysql_query("SELECT * FROM (SELECT type,start_date,end_date,hours,approved,rejected,ID,note FROM request WHERE user_login='" . $user . "' ORDER BY ID ASC) AS sq ORDER BY ID ASC");

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
	$result = mysql_query("SELECT * FROM (SELECT type,start_date,end_date,hours,approved,rejected,ID,note,user_login FROM request ORDER BY ID ASC) AS sq ORDER BY ID ASC");

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
		echo "<script type='text/javascript'>parent.addRequestHistory_Admin('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $status . "','" . $row[6] . "','" . $row[7] . "','" . $row[8] . "');</script>";
	}


	echo "<script type='text/javascript'>parent.resetStudyList();</script>";
	$result = mysql_query("SELECT study FROM studies");
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.setStudyList('" . $row[0] . "','" . $row[0] . "');</script>";
	}

	mysql_free_result($result);

	echo "<script type='text/javascript'>parent.loading_hide();</script>";
	exit();

/****************************************** Reset History END ****************************************************************/

} //action2 IF
if($action == "3"){


	if($ID == "0"){

		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	    	mysql_select_db($dbname);
		if(! $conn ) {
			die('Could not connect: ' . mysql_error());
		}

		for ($x = 1; $x < $count; $x++) {
			$total_hours_employee = 0;
			$user = $_POST['requests_id'][$x];
			$name = $_POST['requests_name'][$x];
			$flag = $_POST['requests_set'][$x];

			if($flag=="1"){

				$sql = "UPDATE timesheet_log SET approved='0' WHERE date >= '$start_date' AND date <= '$end_date'";
				$retval = mysql_query( $sql, $conn );
				if(! $retval ) {
					die('Could not enter data: ' . mysql_error());
				}
/*
				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

					$entry_date = $row[0];
					$entry_date = date('Y-m-d',strtotime($entry_date));
					if( ($entry_date>=$start_date) && ($entry_date<=$end_date) ){

						$result2 = mysql_query("UPDATE timesheet_log SET approved='0' WHERE user_login='" . $user . "' AND date='" . $entry_date . "'");

					}


				}
*/

			}

	
		} //for loop

		mysql_close($conn);

		echo "<script type='text/javascript'>alert('Approvals have been reverted.');</script>";

	}//ID IF
	else{

	}

/****************************************** Reset History START ****************************************************************/
		
	echo "<script type='text/javascript'>parent.resetHistory();</script>";
	echo "<script type='text/javascript'>parent.resetMessage();</script>";
	echo "<script type='text/javascript'>parent.resetRequest();</script>";
	mysql_connect($host, $username, $password) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);

	//$result = mysql_query("SELECT date,study,activity_1,activity_2,hours,ID FROM timesheet_log WHERE user_login='" . $user . "' ORDER BY date DESC");
	$result = mysql_query("SELECT * FROM (SELECT date,study,activity_1,activity_2,hours,ID,approved FROM timesheet_log WHERE user_login='" . $user . "' ORDER BY date ASC) AS sq ORDER BY date ASC");

	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.addHistory('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $row[4] . "','" . $row[5] . "','" . $row[6] . "');</script>";
	}


	//Set Request History
	$result = mysql_query("SELECT * FROM (SELECT type,start_date,end_date,hours,approved,rejected,ID,note FROM request WHERE user_login='" . $user . "' ORDER BY ID ASC) AS sq ORDER BY ID ASC");

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
	$result = mysql_query("SELECT * FROM (SELECT type,start_date,end_date,hours,approved,rejected,ID,note,user_login FROM request ORDER BY ID ASC) AS sq ORDER BY ID ASC");

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
		echo "<script type='text/javascript'>parent.addRequestHistory_Admin('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $status . "','" . $row[6] . "','" . $row[7] . "','" . $row[8] . "');</script>";
	}


	echo "<script type='text/javascript'>parent.resetStudyList();</script>";
	$result = mysql_query("SELECT study FROM studies");
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "<script type='text/javascript'>parent.setStudyList('" . $row[0] . "','" . $row[0] . "');</script>";
	}

	mysql_free_result($result);

	echo "<script type='text/javascript'>parent.loading_hide();</script>";
	exit();

/****************************************** Reset History END ****************************************************************/


} //action3 IF


       

?>
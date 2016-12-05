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

$ID = $_POST['ID_request_reports'];
$action = $_POST['action_request'];
$active_user = $_POST['user_request_reports'];
$current_date = date('n/j/Y');
$start_date = $_POST['textbox4'];
$end_date = $_POST['textbox5'];
$report_type = $_POST['report_type'];
$company_type = $_POST['company_type'];
$company_type_arr = array();
if($company_type == "All"){
    array_push($company_type_arr,"Veritas IRB Inc.","ethica CRO Inc.","ethica Clinical Research Inc.");
}
else{
    array_push($company_type_arr,$company_type);    
}
$company_type_filters = join("','",$company_type_arr);

$count = count($_POST['requests_id']);

//$add_history = $_POST['add_history'];
$add_history = '1';


$start_date = date('Y-m-d',strtotime($start_date));
$end_date = date('Y-m-d',strtotime($end_date));
$total_hours = 0;

//header("Content-Type: text/plain");

$user_items = array();
foreach ($_POST['user_rightList'] as $item){
    array_push($user_items,$item);
}
$user_count = count($_POST['user_rightList']);

$user_items_left = array();
foreach ($_POST['user_leftList'] as $item){
    array_push($user_items_left,$item);
}
$user_items_all = array_merge($user_items, $user_items_left);
$user_count_all = count($user_items_all);
if($_POST['userReport_set'][0] == "0"){
    $user_items = $user_items_all;
    $user_count = $user_count_all;
}

$study_items = array();
foreach ($_POST['study_rightList'] as $item){
    array_push($study_items,$item);
}
$study_count = count($_POST['study_rightList']);

$study_items_left = array();
foreach ($_POST['study_leftList'] as $item){
    array_push($study_items_left,$item);
}
$study_items_all = array_merge($study_items, $study_items_left);
$study_count_all = count($study_items_all);
if($_POST['studyReport_set'][0] == "0"){
    $study_items = $study_items_all;
    $study_count = $study_count_all;
}

$activity1_items = array();
foreach ($_POST['activity1_rightList'] as $item){
    array_push($activity1_items,$item);
}
$activity1_count = count($_POST['activity1_rightList']);

$activity1_items_left = array();
foreach ($_POST['activity1_leftList'] as $item){
    array_push($activity1_items_left,$item);
}
$activity1_items_all = array_merge($activity1_items, $activity1_items_left);
$activity1_count_all = count($activity1_items_all);
if($_POST['activity1Report_set'][0] == "0"){
    $activity1_items = $activity1_items_all;
    $activity1_count = $activity1_count_all;
}

$activity2_items = array();
foreach ($_POST['activity2_rightList'] as $item){
    array_push($activity2_items,$item);
}
$activity2_count = count($_POST['activity2_rightList']);

$activity2_items_left = array();
foreach ($_POST['activity2_leftList'] as $item){
    array_push($activity2_items_left,$item);
}
$activity2_items_all = array_merge($activity2_items, $activity2_items_left);
$activity2_count_all = count($activity2_items_all);
if($_POST['activity2Report_set'][0] == "0"){
    $activity2_items = $activity2_items_all;
    $activity2_count = $activity2_count_all;
}


if($action == "1"){

    // output headers so that the file is downloaded rather than displayed
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=report.csv');
    header('Set-Cookie: fileLoading=true');

    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');


    if($report_type == "Study L1 Activity report"){


        $activity1_filters = join("','",$activity1_items);

        fputcsv($output, array('Study L1 Activity report','From ' . $start_date,'Ending ' . $end_date));
        fputcsv($output, array(''));

        $totals_arr = array();
        $activity1_filtered_arr = array();

        mysql_connect($host, $username, $password) or
            die("Could not connect: " . mysql_error());
        mysql_select_db($dbname);

        /* START ACTIVITY 2 FILTER */
        if($_POST['activity2Report_set'][0] != "0"){
            $activity2_filters = join("','",$activity2_items);
            for ($z = 0; $z < $activity1_count; $z++) {
                $result = mysql_query("SELECT DISTINCT activity_1 FROM activity_level_2 WHERE activity_1='$activity1_items[$z]' AND activity_2 IN ('$activity2_filters')");
                $num_rows = mysql_num_rows($result);
                $row = mysql_fetch_array($result, MYSQL_NUM);
                if ($num_rows > 0){
                    array_push($activity1_filtered_arr,$row[0]);
                }
            }
            $activity1_items = $activity1_filtered_arr;
            $activity1_count = count($activity1_filtered_arr);
        }
        /* END ACTIVITY 2 FILTER */

        $temp_arr = $activity1_items;
        array_unshift($temp_arr, 'Study', 'User');
        array_push($temp_arr,"TOTAL");
        fputcsv($output, $temp_arr);

        for ($x = 0; $x < $study_count; $x++) {

            for ($y = 0; $y < $user_count; $y++) {

                $result = mysql_query("SELECT user_login FROM users WHERE user_login='$user_items[$y]' AND (company IN ('$company_type_filters'))");
                $num_rows = mysql_num_rows($result);

                if ($num_rows > 0){

                    //$activity_arr = array_fill(0, 10 , NULL);
                    $activity_arr = array();
                    $temp = '';
                    $total_study = 0;
                    for ($z = 0; $z < $activity1_count; $z++) {

                        $total_hours_employee = 0;
                        //for ($m = 0; $m < $activity2_count; $m++) {
                            $result = mysql_query("SELECT hours,date,study,activity_1,activity_2 FROM timesheet_log WHERE study='$study_items[$x]' AND user_login='$user_items[$y]'");
                            //$result = mysql_query("SELECT hours,date FROM timesheet_log WHERE study='$study_items[$x]' AND user_login='$user_items[$y]' AND activity_1='$activity1_items[$z]'");
                            while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
                                $temp = $row[3];
                                if($temp == $activity1_items[$z]){
                                    $entry_date = $row[1];
                                    $entry_date = date('Y-m-d',strtotime($entry_date));
                                    $entry_hours = $row[0];
                                    $entry_hours = floatval($entry_hours);

                                    if( ($entry_date>=$start_date) && ($entry_date<=$end_date)){
                                        $total_hours_employee += $entry_hours;
                                    }
                                }
                            }   
                        //}

                        $total_study += $total_hours_employee;
                        $totals_arr[$z] += $total_hours_employee;
                        array_push($activity_arr,$total_hours_employee);
                    }

                    array_unshift($activity_arr, $study_items[$x] , $user_items[$y]);
                    array_push($activity_arr,$total_study);
                    if($total_study != 0){
                        fputcsv($output, $activity_arr);
                    }
                }
            }
        }

        array_unshift($totals_arr, "" , "TOTAL");
        fputcsv($output, $totals_arr);

        mysql_free_result($result);

        fclose($output);

        exit(); 
    }
    elseif($report_type == "Study L2 Activity report"){


        $activity1_filters = join("','",$activity1_items);
        $activity2_filters = join("','",$activity2_items);

        fputcsv($output, array('Study L2 Activity report','From ' . $start_date,'Ending ' . $end_date));
        fputcsv($output, array(''));

        $totals_arr = array();
        $activity2_filtered_arr = array();

        mysql_connect($host, $username, $password) or
            die("Could not connect: " . mysql_error());
        mysql_select_db($dbname);


        /* START ACTIVITY 1 FILTER */

        if($_POST['activity1Report_set'][0] != "0"){
            for ($z = 0; $z < $activity2_count; $z++) {
                $result = mysql_query("SELECT DISTINCT activity_2 FROM activity_level_2 WHERE activity_2='$activity2_items[$z]' AND activity_1 IN ('$activity1_filters')");
                $num_rows = mysql_num_rows($result);
                $row = mysql_fetch_array($result, MYSQL_NUM);
                if ($num_rows > 0){
                    array_push($activity2_filtered_arr,$row[0]);
                }
            }
            $activity2_items = $activity2_filtered_arr;
            $activity2_count = count($activity2_filtered_arr);
        }

        /* END ACTIVITY 1 FILTER */

        $temp_arr = $activity2_items;
        array_unshift($temp_arr, 'Study', 'User', 'Activity 1');
        array_push($temp_arr,"TOTAL");
        fputcsv($output, $temp_arr);

        for ($x = 0; $x < $study_count; $x++) {

            for ($y = 0; $y < $user_count; $y++) {

                $result = mysql_query("SELECT user_login FROM users WHERE user_login='$user_items[$y]' AND company IN ('$company_type_filters')");
                $num_rows = mysql_num_rows($result);

                if ($num_rows > 0){
                    //$activity_arr = array_fill(0, 10 , NULL);
                    $activity_arr = array();
                    //$activity2_arr = array();
                    $temp = '';
                    $temp2 = '';
                    $total_study = 0;
                    for ($z = 0; $z < $activity2_count; $z++) {

                        $total_hours_employee = 0;
                        $result = mysql_query("SELECT hours,date,study,activity_1,activity_2 FROM timesheet_log WHERE study='$study_items[$x]' AND user_login='$user_items[$y]' AND activity_1 IN ('$activity1_filters')");
                        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
                            $temp = $row[4];
                            if($row[4] === ''){
                                $temp = '(empty)';
                            }
                            if($temp == $activity2_items[$z]){
                                $entry_date = $row[1];
                                $entry_date = date('Y-m-d',strtotime($entry_date));
                                $entry_hours = $row[0];
                                $entry_hours = floatval($entry_hours);
                                $temp2 = $row[3];

                                if( ($entry_date>=$start_date) && ($entry_date<=$end_date)){
                                    $total_hours_employee += $entry_hours;
                                }
                            }
                        }

                        /****** START MYSQL ONLY ALTERNATIVE ************
                        $result = mysql_query("SELECT SUM(hours) AS hours_sum FROM timesheet_log WHERE activity_2='$activity2_items[$z]' AND study='$study_items[$x]' AND user_login='$user_items[$y]' AND activity_1 IN ('$activity1_filters') AND (date >= '$start_date' AND date <= '$end_date')"); 
                        $row = mysql_fetch_assoc($result); 
                        $total_hours_employee = $row['hours_sum'];
                        ****** END MYSQL ONLY ALTERNATIVE ************/ 

                        $total_study += $total_hours_employee;
                        $totals_arr[$z] += $total_hours_employee;
                        array_push($activity_arr,$total_hours_employee);
                    }

                    array_unshift($activity_arr, $study_items[$x] , $user_items[$y], $temp2);
                    array_push($activity_arr,$total_study);
                    if($total_study != 0){
                        fputcsv($output, $activity_arr);
                    }
                }
            }
        }

        array_unshift($totals_arr, "" , "", "TOTAL");
        fputcsv($output, $totals_arr);

        mysql_free_result($result);

        fclose($output);

        exit(); 
    }
    elseif($report_type == "User Study Activity Report"){

        $activity1_filters = join("','",$activity1_items);
        $activity2_filters = join("','",$activity2_items);

        fputcsv($output, array('User Study Activity Report','From ' . $start_date,'Ending ' . $end_date));
        fputcsv($output, array(''));
        $temp_arr = $study_items;
        array_unshift($temp_arr, 'PROJECTS');
        array_push($temp_arr, "Vacation + Statutory Holidays + Unpaid Days", "TOTAL");
        fputcsv($output, $temp_arr);
        fputcsv($output, array(''));
        fputcsv($output, array('Employees'));
        fputcsv($output, array(''));
        $totals_arr = array();

        mysql_connect($host, $username, $password) or
            die("Could not connect: " . mysql_error());
        mysql_select_db($dbname);

        for ($x = 0; $x < $user_count; $x++) {

            $result = mysql_query("SELECT user_login FROM users WHERE user_login='$user_items[$x]' AND (company IN ('$company_type_filters'))");
            $num_rows = mysql_num_rows($result);

            if ($num_rows > 0){

                //$activity_arr = array_fill(0, 10 , NULL);
                $activity_arr = array();
                //$activity2_arr = array();
                $temp = '';
                $temp2 = '';
                $time_off_count = 0;
                $total_study = 0;

                for ($y = 0; $y < $study_count; $y++) {

                    $total_hours_employee = 0;
                    $result = mysql_query("SELECT hours,date,study,activity_1,activity_2 FROM timesheet_log WHERE study='$study_items[$y]' AND user_login='$user_items[$x]'");
                    //$result = mysql_query("SELECT hours,date FROM timesheet_log WHERE study='$study_items[$x]' AND user_login='$user_items[$y]' AND activity_1='$activity1_items[$z]'");
                    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

                        $entry_date = $row[1];
                        $entry_date = date('Y-m-d',strtotime($entry_date));
                        $entry_hours = $row[0];
                        $entry_hours = floatval($entry_hours);

                        if( ($entry_date>=$start_date) && ($entry_date<=$end_date)){
                            $total_hours_employee += $entry_hours;
                        }
                    }   

                    $total_study += $total_hours_employee;
                    $totals_arr[$y] += $total_hours_employee;
                    array_push($activity_arr,$total_hours_employee);
                    $time_off_count = $y;   
                }

                //ADD TIME OFF COLUMNS
                //for ($y = 0; $y < $study_count; $y++) {

                    $total_hours_employee = 0;
                    $result = mysql_query("SELECT hours,start_date,type,approved,end_date,note FROM request WHERE user_login='$user_items[$x]'");
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
                                $total_hours_employee += $entry_hours;
                            }
                            if($row[2]=='Unpaid'){
                                $total_hours_employee += $entry_hours;
                            }
                            if($row[2]=='Vacation'){
                                if($days_requested>$days_period){
                                    $total_hours_employee += $days_period*8;
                                }
                                else{
                                    $total_hours_employee += $days_requested*8; 
                                }
                            }
                        }
                    }   

                    $total_study += $total_hours_employee;
                    $totals_arr[$time_off_count+1] += $total_hours_employee;
                    array_push($activity_arr,$total_hours_employee);    
                //}

                array_unshift($activity_arr, $user_items[$x]);
                array_push($activity_arr,$total_study);
                if($total_study != 0){
                    fputcsv($output, $activity_arr);
                }
            }
        }

        array_unshift($totals_arr, "TOTAL");
        fputcsv($output, $totals_arr);

        mysql_free_result($result);

        fclose($output);

        exit(); 
    }
    else{

        $activity2_filters = join("','",$activity2_items);
        $activity1_filters = join("','",$activity1_items);
        $study_filters = join("','",$study_items);

        // output the column headings
        /*  fputcsv($output, array('Column 1', 'Column 2', 'Column 3'));  */
        fputcsv($output, array('Total time','From ' . $start_date,'Ending ' . $end_date));
        fputcsv($output, array(''));
        fputcsv($output, array('EMPLOYEE NAME', 'Total Worked Time' , 'Personal Time' , 'Unpaid Leave' , 'Vacation' , 'Grand Total'));

        // fetch the data
        //mysql_connect('localhost', 'username', 'password');
        //mysql_select_db('database');
        //$rows = mysql_query('SELECT field1,field2,field3 FROM table');

        // loop over the rows, outputting them
        //while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);
        if($ID == "0"){

            $total_personal_hours = 0;
            $total_unpaid_hours = 0;
            $total_vacation_days = 0;
            for ($x = 0; $x < $user_count; $x++) {
                $total_hours_employee = 0;
                $total_personal_hours_employee = 0;
                $total_unpaid_hours_employee = 0;
                $total_vacation_days_employee = 0;
                //$user = $_POST['requests_id'][$x];
                $user = $user_items[$x];

                mysql_connect($host, $username, $password) or
                    die("Could not connect: " . mysql_error());
                mysql_select_db($dbname);
                $result = mysql_query("SELECT user_login FROM users WHERE user_login='$user_items[$x]' AND company IN ('$company_type_filters')");
                $num_rows = mysql_num_rows($result);
                mysql_free_result($result);
                if ($num_rows > 0){

                    mysql_connect($host, $username, $password) or
                        die("Could not connect: " . mysql_error());
                    mysql_select_db($dbname);
                    $result = mysql_query("SELECT user_firstname,user_lastname FROM users WHERE user_login='$user'");
                    $row = mysql_fetch_array($result, MYSQL_NUM);
                    $name = $row[0] . ' ' . $row[1];
                    mysql_free_result($result);
                    //$name = $_POST['requests_name'][$x];

                    $flag = $_POST['requests_set'][$x];

                    //if($flag=="1"){

                        mysql_connect($host, $username, $password) or
                            die("Could not connect: " . mysql_error());
                        mysql_select_db($dbname);

                        $result = mysql_query("SELECT hours,date,study,activity_1,activity_2,notes FROM timesheet_log WHERE  user_login='$user' AND study IN ('$study_filters')");

                        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

                            $entry_date = $row[1];
                            $entry_date = date('Y-m-d',strtotime($entry_date));
                            $entry_hours = $row[0];
                            $entry_hours = floatval($entry_hours);
                            if( ($entry_date>=$start_date) && ($entry_date<=$end_date) ){

                                $total_hours_employee += $entry_hours;
                            }
                        }

                        $result = mysql_query("SELECT hours,start_date,type,approved,end_date,note FROM request WHERE user_login='$user'");

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
            
                        fputcsv($output, array($name, $total_hours_employee , $total_personal_hours_employee,$total_unpaid_hours_employee,$total_vacation_days_employee,($total_hours_employee+$total_personal_hours_employee+$total_unpaid_hours_employee+($total_vacation_days_employee*8))));
                        $total_hours += $total_hours_employee;
                        $total_personal_hours += $total_personal_hours_employee;
                        $total_unpaid_hours += $total_unpaid_hours_employee;
                        $total_vacation_days += $total_vacation_days_employee;
                        mysql_free_result($result);
                    //} 
                }
            } //for loop
        
            fputcsv($output, array('Total', $total_hours,$total_personal_hours,$total_unpaid_hours,$total_vacation_days,($total_hours+$total_personal_hours+$total_unpaid_hours+($total_vacation_days*8))));
            fputcsv($output, array(''));
            fputcsv($output, array(''));
            fputcsv($output, array('HISTORY'));
            fputcsv($output, array(''));

            if($add_history == '1')
            {

                for ($x = 0; $x < $user_count; $x++) {

                    //$user = $_POST['requests_id'][$x];
                    //$name = $_POST['requests_name'][$x];
                    $user = $user_items[$x];

                    mysql_connect($host, $username, $password) or
                        die("Could not connect: " . mysql_error());
                    mysql_select_db($dbname);
                    $result = mysql_query("SELECT user_login FROM users WHERE user_login='$user_items[$x]' AND company IN ('$company_type_filters')");
                    $num_rows = mysql_num_rows($result);
                    mysql_free_result($result);
                    if ($num_rows > 0){

                        mysql_connect($host, $username, $password) or
                            die("Could not connect: " . mysql_error());
                        mysql_select_db($dbname);
                        $result = mysql_query("SELECT user_firstname,user_lastname FROM users WHERE user_login='$user'");
                        $row = mysql_fetch_array($result, MYSQL_NUM);
                        $name = $row[0] . ' ' . $row[1];
                        mysql_free_result($result);
                        
                        $flag = $_POST['requests_set'][$x];

                        //if($flag=="1"){

                            mysql_connect($host, $username, $password) or
                                die("Could not connect: " . mysql_error());
                            mysql_select_db($dbname);

                            $result = mysql_query("SELECT hours,date,study,activity_1,activity_2,notes FROM timesheet_log WHERE user_login='$user' AND (activity_1 IN ('$activity1_filters')) AND (activity_2 IN ('$activity2_filters')) AND (study IN ('$study_filters'))");

                            while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

                                $entry_date = $row[1];
                                $entry_date = date('Y-m-d',strtotime($entry_date));
                                $entry_hours = $row[0];
                                //$entry_hours = floatval($entry_hours);
                                if( ($entry_date>=$start_date) && ($entry_date<=$end_date) ){

                                    fputcsv($output, array($name,$row[1],$row[2],$row[3],$row[4],$row[0] . ' hours','note: ' . $row[5]));
                                }
                            }

                            $result = mysql_query("SELECT hours,start_date,type,approved,end_date,note,approved_by FROM request WHERE user_login='" . $user . "'");

                            while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

                                $start = $row[1];
                                $end = $row[4];
                                if($row[2]=='Vacation'){
                                    $days_requested = floor(getWorkingDays($start,$end,$holidays));
                                    $days_period = floor(getWorkingDays($start,$end_date,$holidays));
                                }
                                
                                $entry_date = $row[1];
                                $entry_date = date('Y-m-d',strtotime($entry_date));
                                if( ($entry_date>=$start_date) && ($entry_date<=$end_date) ){

                                    if($row[2]=='Personal'){
                                        fputcsv($output, array($name,$row[1],$row[2],'','',$row[0] . ' hours','note: ' . $row[5],'approved by: ' . $row[6]));
                                    }
                                    if($row[2]=='Unpaid'){
                                        fputcsv($output, array($name,$row[1],$row[2],'','',$row[0] . ' hours','note: ' . $row[5],'approved by: ' . $row[6]));
                                    }
                                    if($row[2]=='Vacation'){
                                        if($days_requested>$days_period){
                                            fputcsv($output, array($name,$row[1] . ' to ' . $row[4],$row[2],'','',$days_requested . ' days','note: ' . $row[5],'approved by: ' . $row[6]));
                                        }
                                        else{
                                            fputcsv($output, array($name,$row[1] . ' to ' . $row[4],$row[2],'','',$days_requested . ' days','note: ' . $row[5],'approved by: ' . $row[6]));
                                        }
                                    }
                                }
                            }


                            mysql_free_result($result);
                        //}
                    }
                } //for loop
            }
            /*************************************************************************
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
                        fputcsv($output, array($name, $total_personal_hours_employee,$total_unpaid_hours_employee,$total_vacation_days_employee,($total_personal_hours_employee+$total_unpaid_hours_employee+($total_vacation_days_employee*8))));
                    }
                    $total_personal_hours += $total_personal_hours_employee;
                    $total_unpaid_hours += $total_unpaid_hours_employee;
                    $total_vacation_days += $total_vacation_days_employee;
                    mysql_free_result($result);


                }
            } //for loop
            fputcsv($output, array('Total', $total_personal_hours,$total_unpaid_hours,$total_vacation_days,($total_personal_hours+$total_unpaid_hours+($total_vacation_days*8))));
            ********************************************************************************************/

            exit();
        }//ID IF
        else{       
            exit();
        }
    }
} //action1 IF
if($action == "2"){


    if($ID == "0"){

        $conn = mysql_connect($dbhost, $dbuser, $dbpass);
            mysql_select_db($dbname);
        if(! $conn ) {
            die('Could not connect: ' . mysql_error());
        }

        for ($x = 0; $x < $user_count; $x++) {
            //$total_hours_employee = 0;
            //$user = $_POST['requests_id'][$x];
            //$name = $_POST['requests_name'][$x];
            //$flag = $_POST['requests_set'][$x];

            //if($flag=="1"){


                $sql = "UPDATE timesheet_log SET approved='1' WHERE date >= '$start_date' AND date <= '$end_date' AND user_login='$user_items[$x]'";
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

            //}

    
        } //for loop

        mysql_close($conn);

        echo "<script type='text/javascript'>alert('Hours have been Approved.');</script>";
    }//ID IF
    else{
    }

    /****************************************** Reset History START ****************************************************************/


    echo "<script type='text/javascript'>parent.loading_hide();</script>";
    echo "<script type='text/javascript'>parent.set_history('0');</script>";
    exit();

    /*      
    echo "<script type='text/javascript'>parent.resetHistory();</script>";
    echo "<script type='text/javascript'>parent.resetMessage();</script>";
    echo "<script type='text/javascript'>parent.resetRequest();</script>";
    mysql_connect($host, $username, $password) or
        die("Could not connect: " . mysql_error());
    mysql_select_db($dbname);

    //$result = mysql_query("SELECT date,study,activity_1,activity_2,hours,ID FROM timesheet_log WHERE user_login='" . $user . "' ORDER BY date DESC");
    $result = mysql_query("SELECT * FROM (SELECT date,study,activity_1,activity_2,hours,ID,approved FROM timesheet_log WHERE user_login='" . $active_user . "' ORDER BY date ASC) AS sq ORDER BY date ASC");

    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        echo "<script type='text/javascript'>parent.addHistory('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $row[4] . "','" . $row[5] . "','" . $row[6] . "');</script>";
    }


    //Set Request History
    $result = mysql_query("SELECT * FROM (SELECT type,start_date,end_date,hours,approved,rejected,ID,note FROM request WHERE user_login='" . $active_user . "' ORDER BY ID ASC) AS sq ORDER BY ID ASC");

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
    echo "<script type='text/javascript'>parent.reset_request_alert_counter();</script>";
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

        for ($x = 0; $x < $user_count; $x++) {
            //$total_hours_employee = 0;
            //$user = $_POST['requests_id'][$x];
            //$name = $_POST['requests_name'][$x];
            //$flag = $_POST['requests_set'][$x];

            //if($flag=="1"){

                $sql = "UPDATE timesheet_log SET approved='0' WHERE date >= '$start_date' AND date <= '$end_date'  AND user_login='$user_items[$x]'";
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

            //}
        } //for loop

        mysql_close($conn);


        echo "<script type='text/javascript'>alert('Approvals have been reverted.');</script>";

    }//ID IF
    else{

    }

    /****************************************** Reset History START ****************************************************************/

    echo "<script type='text/javascript'>parent.loading_hide();</script>";     
    echo "<script type='text/javascript'>parent.set_history('0');</script>";
    exit();
     /*   
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
    exit();

    /****************************************** Reset History END ****************************************************************/
} //action3 IF


?>
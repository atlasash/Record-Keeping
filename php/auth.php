<?php

include "config.php";

// mysql database connection details
$host = DB_HOST;
$username = DB_USER;
$password = DB_PASSWORD;
$dbname = DB_NAME;

$dt = date("Y-m-d h:i:sa");
$user = $_POST['u'];
$pass = $_POST['p'];
$remember = $_POST['check_set'];

mysql_connect($host, $username, $password) or
    die("Could not connect: " . mysql_error());
mysql_select_db($dbname);

$result = mysql_query("SELECT user_login,user_pass,user_firstname,user_lastname,is_admin,Director,last_login,vacation,vacation_used,personal_time,user_email,Company,active,reporting FROM users WHERE user_login='" . $user . "' LIMIT 1");
$row = mysql_fetch_array($result, MYSQL_NUM);
$num_rows = mysql_num_rows($result);

mysql_free_result($result);

if ($num_rows > 0) {
	if ($row[1] == $pass | password_verify($pass, $row[1])) {

		if($row[12] == '0'){

			echo ("<SCRIPT LANGUAGE='JavaScript'>
			window.alert('User in inactive.')
			window.location.href='http://10.35.0.235/ora/';
			</SCRIPT>");	
		}	


		readfile("../header.html");

	/************** START set login ************************/

		$dbhost = $host;
		$dbuser = $username;
		$dbpass = $password;
		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	    	mysql_select_db($dbname);
		if(! $conn ) {
			die('Could not connect: ' . mysql_error());
		}
		$sql = "UPDATE users ". "SET logged_in='1' ". "WHERE  user_login='$row[0]'";
		$retval = mysql_query( $sql, $conn );
		if(! $retval ) {
			die('Could not enter data: ' . mysql_error());
		}

		$sql = "UPDATE users ". "SET  last_login='$dt' ". "WHERE  user_login='$row[0]'";
		$retval = mysql_query( $sql, $conn );
		if(! $retval ) {
			die('Could not enter data: ' . mysql_error());
		}

		mysql_close($conn);

	/************** END set login ************************/

	/************** START INITIALIZE CODE ************************/


		$current_date = date("m/d/Y", strtotime("now"));
		//$nextweek= date('m/d/Y', strtotime($current_date . " +7 days"));

		$year = date("Y", strtotime($current_date));
		$dayofweek = date("N", strtotime("now"));		
		$byweek = date("W", strtotime("now"))/2;
		$byweek_floor = floor(date("W", strtotime("now"))/2);
		if($byweek == $byweek_floor){
			$dayofweek = $dayofweek + 7;
		}
		$N = $dayofweek-1;
		if($N<0){
			$day1 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day1 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-2;
		if($N<0){
			$day2 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day2 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-3;
		if($N<0){
			$day3 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day3 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-4;
		if($N<0){
			$day4 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day4 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}
		
		$N = $dayofweek-5;
		if($N<0){
			$day5 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day5 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-6;
		if($N<0){
			$day6 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day6 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-7;
		if($N<0){
			$day7 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day7 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-8;
		if($N<0){
			$day8 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day8 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-9;
		if($N<0){
			$day9 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day9 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-10;
		if($N<0){
			$day10 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day10 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-11;
		if($N<0){
			$day11 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day11 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-12;
		if($N<0){
			$day12 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day12 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-13;
		if($N<0){
			$day13 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day13 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		$N = $dayofweek-14;
		if($N<0){
			$day14 = date('m/d/Y', strtotime($current_date . " +". abs($N) . " days"));
		}
		else{
			$day14 = date('m/d/Y', strtotime($current_date . " -". abs($N) . " days"));
		}

		/*
		if($remember) {
			echo 'setCookies("' . $user . '","' . $pass . '");';	
		}
		else{
			echo 'clearCookies();';
		}
		*/
		
		echo '$("input[name=day1]").val("' . $day1 . '");';
		echo '$("input[name=day2]").val("' . $day2 . '");';	
		echo '$("input[name=day3]").val("' . $day3 . '");';	
		echo '$("input[name=day4]").val("' . $day4 . '");';	
		echo '$("input[name=day5]").val("' . $day5 . '");';	
		echo '$("input[name=day6]").val("' . $day6 . '");';	
		echo '$("input[name=day7]").val("' . $day7 . '");';	
		echo '$("input[name=day8]").val("' . $day8 . '");';	
		echo '$("input[name=day9]").val("' . $day9 . '");';	
		echo '$("input[name=day10]").val("' . $day10 . '");';	
		echo '$("input[name=day11]").val("' . $day11 . '");';	
		echo '$("input[name=day12]").val("' . $day12 . '");';	
		echo '$("input[name=day13]").val("' . $day13 . '");';
		echo '$("input[name=day14]").val("' . $day14 . '");';

		echo '$("input[name=user]").val("' . $row[0] . '");';
		echo '$("input[name=edit_profile_user]").val("' . $row[0] . '");';
		echo '$("input[name=byweek]").val("' . $byweek_floor . '");';
		echo '$("input[name=year]").val("' . $year . '");';
		echo '$("input[name=Director]").val("' . $row[5] . '");';
		echo '$("input[name=edit_profile_name]").val("' . $row[2] . ' ' . $row[3] . '");';
		echo '$("#last_login_span").html("' . $row[6] . '");';
		echo '$("#user_email").val("' . $row[10] . '");';
		echo '$("#company").val("' . $row[11] . '");';

		echo '$("#user_firstname").html("' . strtoupper($row[2]) . '");';
		echo '$("#welcome").html("Hi ' . $row[2] . '");';			

		echo 'resetEmployeeList();';

		if($row[4]!=1){
		echo 'setEmployeeList("' . $row[2] . ' ' . $row[3] . '","' . $row[0] . '");';
		}

	/****************************** START check if ADMIN **************************************************/
		echo '$("#label_tab2").hide();';
		echo '$("#label_tab3").hide();';
		echo '$("#label_tab4").hide();';

		if($row[13]==1){
			echo '$("#label_tab3").show();';
			echo '$("#approval_btn").hide();';
			echo '$("#revert_btn").hide();';
			echo 'showEmployeeTable();';
			echo 'showRequestsTable();';
		}
		if($row[4]==1){


			echo 'setAdmin("' . $row[0] . '");';
			echo 'showEmployeeTable();';
			echo 'showRequestsTable();';
			echo '$("#edit_profile_vacation").show();';
			echo '$("#edit_profile_vacation_used").show();';
			echo '$("#edit_profile_personal").show();';
			echo '$("#edit_profile_vacation_title").show();';
			echo '$("#edit_profile_vacation_used_title").show();';
			echo '$("#edit_profile_personal_title").show();';

			echo '$("#approval_btn").show();';
			echo '$("#revert_btn").show();';

			echo '$("#label_tab2").show();';
			echo '$("#label_tab3").show();';
			echo '$("#label_tab4").show();';

			mysql_connect($host, $username, $password) or
    				die("Could not connect: " . mysql_error());
			mysql_select_db($dbname);
			$result2 = mysql_query("SELECT user_login,user_pass,user_firstname,user_lastname,is_admin,Director,last_login,vacation,vacation_used,personal_time,user_email FROM users WHERE active='1'");
			while ($row2 = mysql_fetch_array($result2, MYSQL_NUM)) {
				echo 'setEmployeeList("' . $row2[2] . ' ' . $row2[3] . '","' . $row2[0] . '");';
			}
			mysql_free_result($result2);
		}

	/****************************** END check if ADMIN **************************************************/
			

		echo 'checkLogin();';
		echo 'set_history("0");';
		echo 'set_profile(' . $row[5] . ',"' . $row[6] . '","' . $row[7] . '","' . $row[8] . '","' . $row[9] . '");';



	/******************* START Check if user didnt enter time for week ****************/


		mysql_connect($host, $username, $password) or
    			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);


		$current_date = date('Y-m-d',strtotime($current_date));
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
		//$day12= date('n/j/Y',strtotime($day12));

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

					echo 'set_message("' . $day1 . '");';
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

					echo 'set_message("' . $day2 . '");';
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

					echo 'set_message("' . $day3 . '");';
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

					echo 'set_message("' . $day4 . '");';
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

					echo 'set_message("' . $day5 . '");';
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

					echo 'set_message("' . $day8 . '");';
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

					echo 'set_message("' . $day9 . '");';
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

					echo 'set_message("' . $day10 . '");';
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

					echo 'set_message("' . $day11 . '");';
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

					echo 'set_message("' . $day12 . '");';
			}

		}

		mysql_free_result($result2);


	/******************* END Check if user didnt enter time for week ****************/		

		echo 'resetStudyList();';

		mysql_connect($host, $username, $password) or
    			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
		if($row[5] == 1){
			$result2 = mysql_query("SELECT study FROM studies");
			while ($row2 = mysql_fetch_array($result2, MYSQL_NUM)) {
				echo 'setStudyList("' . $row2[0] . '","' . $row2[0] . '");'; 
			}

			
		}
		else{
			$result2 = mysql_query("SELECT study FROM studies WHERE Director='0'");
			while ($row2 = mysql_fetch_array($result2, MYSQL_NUM)) {
				echo 'setStudyList("' . $row2[0] . '","' . $row2[0] . '");'; 
			}

		}

		mysql_free_result($result2);

	/******************* START add users for reports ****************/

		mysql_connect($host, $username, $password) or
    			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
		$result3 = mysql_query("SELECT user_login,user_firstname,user_lastname FROM users WHERE active='1'");
		while ($row3 = mysql_fetch_array($result3, MYSQL_NUM)) {
			echo 'set_users("' . $row3[0] . '","' . $row3[1] . ' ' . $row3[2] . '");'; 
		}

		mysql_free_result($result3);

	/******************* END add users for reports ****************/


	/******************* START SET Reports Options ****************/

		echo 'resetReportEmployeeList();';
		echo 'resetReportStudyList();';
		echo 'resetReportActivity1List();';
		echo 'resetReportActivity2List();';

		mysql_connect($host, $username, $password) or
    			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);

		$result2 = mysql_query("SELECT user_login,user_firstname,user_lastname FROM users WHERE active='1' ORDER BY user_lastname");
		while ($row2 = mysql_fetch_array($result2, MYSQL_NUM)) {
			echo 'setReportEmployeeList("' . $row2[1] . ' ' . $row2[2] . '","' . $row2[0] . '");'; 
		}

		$result2 = mysql_query("SELECT study FROM studies ORDER BY type");
		while ($row2 = mysql_fetch_array($result2, MYSQL_NUM)) {
			echo 'setReportStudyList("' . $row2[0] . '","' . $row2[0] . '");'; 
		}

		$result2 = mysql_query("SELECT DISTINCT activity_1 FROM activity_level_1 ORDER BY activity_1");
		while ($row2 = mysql_fetch_array($result2, MYSQL_NUM)) {
			echo 'setReportActivity1List("' . $row2[0] . '","' . $row2[0] . '");'; 
		}
		
		$result2 = mysql_query("SELECT DISTINCT activity_2 FROM activity_level_2 ORDER BY activity_2");
		while ($row2 = mysql_fetch_array($result2, MYSQL_NUM)) {
			echo 'setReportActivity2List("' . $row2[0] . '","' . $row2[0] . '");'; 
		}

		mysql_free_result($result2);

	/******************* END SET Reports Options ****************/


		//echo 'alert("' . $year . '");';
		//echo '$( "#add_button" ).click();';

	/************** END INITIALIZE CODE ************************/

		readfile("../footer.html");
	}
	else{

		echo ("<SCRIPT LANGUAGE='JavaScript'>
		window.alert('Wrong Password. Please try again.')
		window.location.href='http://10.35.0.235/ora/';
		</SCRIPT>");	
	}	
}
else {
	echo ("<SCRIPT LANGUAGE='JavaScript'>
	window.alert('User Not Found.')
	window.location.href='http://10.35.0.235/ora/';
	</SCRIPT>");
}


?>
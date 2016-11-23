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

	$pass = $_POST['edit_profile_pass'];
	$pass_confirm = $_POST['edit_profile_confirm'];
	$user = $_POST['edit_profile_user'];
	$vacation = $_POST['edit_profile_vacation'];
	$vacation_used = $_POST['edit_profile_vacation_used'];
	$personal = $_POST['edit_profile_personal'];
	$user_pass = password_hash($pass, PASSWORD_BCRYPT);  //encrypt password

	if($pass == $pass_confirm && !empty($pass)){

		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db($dbname);

		if(! $conn ) {
			die('Could not connect: ' . mysql_error());
		}
		
		$sql = "UPDATE users ". "SET user_pass='$user_pass' ". "WHERE user_login='$user'";
		$retval = mysql_query( $sql, $conn );
		if(! $retval ) {
			die('Could not enter data: ' . mysql_error());
		}

		if(is_numeric($vacation)){
			$sql = "UPDATE users ". "SET vacation='$vacation' ". "WHERE user_login='$user'";
			$retval = mysql_query( $sql, $conn );
			if(! $retval ) {
				die('Could not enter data: ' . mysql_error());
			}
		}
		if(is_numeric($vacation_used)){
			$sql = "UPDATE users ". "SET vacation_used='$vacation_used' ". "WHERE user_login='$user'";
			$retval = mysql_query( $sql, $conn );
			if(! $retval ) {
				die('Could not enter data: ' . mysql_error());
			}
		}
		if(is_numeric($personal)){
			$sql = "UPDATE users ". "SET personal_time='$personal' ". "WHERE user_login='$user'";
			$retval = mysql_query( $sql, $conn );
			if(! $retval ) {
				die('Could not enter data: ' . mysql_error());
			}
		}

		mysql_close($conn);

		echo "<script type='text/javascript'>parent.alert('" . $user . " profile has been updated.');</script>";

/************************   Check Vacation Remaining START   *************/

		mysql_connect($host, $username, $password) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		$result = mysql_query("SELECT vacation,vacation_used,personal_time FROM users WHERE user_login='" . $user . "' LIMIT 1");
		$row = mysql_fetch_array($result, MYSQL_NUM);
		mysql_free_result($result);

		echo "<script type='text/javascript'>parent.resetVacation('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "');</script>";

/************************   Check Vacation Remaining END   *************/

		exit();
	}
	elseif(empty($pass) && empty($pass_confirm) && !is_numeric($personal) && !is_numeric($vacation_used) && !is_numeric($vacation)){

		echo "<script type='text/javascript'>parent.alert('Please enter information.');</script>";
		exit();

	}
	elseif(empty($pass) && empty($pass_confirm)){

		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db($dbname);

		if(! $conn ) {
			die('Could not connect: ' . mysql_error());
		}

		if(is_numeric($vacation)){
			$sql = "UPDATE users ". "SET vacation='$vacation' ". "WHERE user_login='$user'";
			$retval = mysql_query( $sql, $conn );
			if(! $retval ) {
				die('Could not enter data: ' . mysql_error());
			}
		}
		if(is_numeric($vacation_used)){
			$sql = "UPDATE users ". "SET vacation_used='$vacation_used' ". "WHERE user_login='$user'";
			$retval = mysql_query( $sql, $conn );
			if(! $retval ) {
				die('Could not enter data: ' . mysql_error());
			}
		}
		if(is_numeric($personal)){
			$sql = "UPDATE users ". "SET personal_time='$personal' ". "WHERE user_login='$user'";
			$retval = mysql_query( $sql, $conn );
			if(! $retval ) {
				die('Could not enter data: ' . mysql_error());
			}
		}

		mysql_close($conn);

		echo "<script type='text/javascript'>parent.alert('" . $user . " profile has been updated.');</script>";

/************************   Check Vacation Remaining START   *************/

		mysql_connect($host, $username, $password) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		$result = mysql_query("SELECT vacation,vacation_used,personal_time FROM users WHERE user_login='" . $user . "' LIMIT 1");
		$row = mysql_fetch_array($result, MYSQL_NUM);
		mysql_free_result($result);

		echo "<script type='text/javascript'>parent.resetVacation('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "');</script>";

/************************   Check Vacation Remaining END   *************/

		exit();
	}
	else{

		echo "<script type='text/javascript'>parent.alert('Please enter the same password to confirm.');</script>";
		exit();
	}

	//echo "<script type='text/javascript'>parent.resetActivity1List();</script>";
	//echo "<script type='text/javascript'>parent.resetActivity2List();</script>";
	//echo "<script type='text/javascript'>parent.reset_entry();</script>";


       

?>
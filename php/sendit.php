<?php

require_once('PHPMailer/PHPMailerAutoload.php');

$email = $_GET['email'];
$message = $_GET['body'];
//$toemail = 'aoziegbe@ethicaclinical.ca';
$name = $_GET['name'];
$fullname =  $_GET['fullname'];

$mail = new PHPMailer();

$response_message = file_get_contents('mail_templates/ora.html');
$response_message = str_replace('*|MESSAGE|*', $message, $response_message);
$response_message = str_replace('*|NAME|*', $name, $response_message);

$mail->SetFrom( "noreply@ethicaclinical.ca" , "ORA" );
//$mail->AddReplyTo( "noreply@ethicaclinical.ca" , "ORA" );
$mail->AddAddress($email, $fullname);

$mail->Subject = "REMINDER: Please enter your hours in ORA";
$mail->MsgHTML($response_message);
if ($mail->Send()) {  
    exit();  
} else {  
    exit();    
}


?>
<?php
$email=$_POST['email'];
$uname=$_POST['uname'];
$subject=$_POST['subject'];
$comment=$_POST['comment'];

$to='sarveshsparab@gmail.com';
$subject = 'LFHS Report Card : Bug Report ( '.$subject.' )';
$message = 'Reported by : '.$uname.' ( '.$email.' )'."\r\n";
$message .= 'Bug Details : '."\r\n".$comment;

$headers = 'From: noreply@lfhschool.org' . "\r\n" .
    'Reply-To: noreply@lfhschool.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	
	
$message1="\r\n"."\r\n"."CodeCrypt Webmaster.";

if(mail($to, $subject, $message.$message1, $headers))
	echo "done";
else
	echo "error";
?>
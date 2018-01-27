<?php
include("connect_db.php");
$email_id  = mysqli_real_escape_string($conn, $_REQUEST['email']);
$sql = "SELECT * FROM teacher WHERE teacher_email='$email_id'";
$query = mysqli_query($conn, $sql);
$email_exist = mysqli_num_rows($query);

if($email_exist) {
	echo 'false';
}
else{
	echo 'true';
}
?>
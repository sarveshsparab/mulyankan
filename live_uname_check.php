<?php
include("connect_db.php");
$uname  = mysqli_real_escape_string($conn, $_REQUEST['uname']);
$sql = "SELECT * FROM teacher WHERE teacher_name='$uname'";
$query = mysqli_query($conn, $sql);
$username_exist = mysqli_num_rows($query);

if($username_exist) {
	echo 'false';
}
else{
	echo 'true';
}
?>
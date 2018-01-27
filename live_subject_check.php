<?php
include("connect_db.php");
$sub_name  = mysqli_real_escape_string($conn, $_REQUEST['sub_name']);
$sql = "SELECT * FROM subject WHERE subject_name='$sub_name'";
$query = mysqli_query($conn, $sql);
$sub_name_exist = mysqli_num_rows($query);

if($sub_name_exist) {
	echo 'false';
}
else{
	echo 'true';
}
?>
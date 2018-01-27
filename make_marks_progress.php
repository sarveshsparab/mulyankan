<?php
include_once("connect_db.php");
$uid=$_POST['uid'];
$uname=$_POST['uname'];
$role_id=$_POST['role_id'];
$val=$_POST['val'];

$sql = "UPDATE teacher_roles set marks_lock='$val' where role_id='$role_id'";
$query = mysqli_query($conn, $sql);
echo "done";
?>
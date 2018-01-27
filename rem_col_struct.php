<?php
include_once("connect_db.php");
$uid=$_POST['uid'];
$uname=$_POST['uname'];
$role_id=$_POST['role_id'];
$child_id=$_POST['child_id'];
$par_id=$_POST['par_id'];

$sql = "SELECT * FROM report_structure where role_id='$role_id' and column_id in (SELECT column_id FROM report_columns where column_parent='$child_id')";
$query = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($query);

if($num_rows==0){
	$sql = "DELETE FROM report_structure where role_id='$role_id' and column_id='$child_id'";
	$query = mysqli_query($conn, $sql);
	
	$sql = "UPDATE teacher_roles set structure_lock=1 where role_id='$role_id'";
	$query = mysqli_query($conn, $sql);
	
	echo "done";
}else{
	echo "non_terminal";
}


?>
<?php
include_once("connect_db.php");
$uid=$_POST['uid'];
$uname=$_POST['uname'];
$role_id=$_POST['role_id'];
$child_id=$_POST['child_id'];
$par_id=$_POST['par_id'];
$marks=$_POST['max_marks'];

if($par_id==-1){
	$sql = "INSERT INTO report_structure (role_id, column_id, maxmarks) VALUES ('$role_id', '$child_id', '$marks')";
	$query = mysqli_query($conn, $sql);
	
	$sql = "UPDATE teacher_roles set structure_lock=1 where role_id='$role_id'";
	$query = mysqli_query($conn, $sql);
	
	echo "done";
}else{
	$sql = "SELECT sum(maxmarks) as mysum FROM report_structure where role_id='$role_id' AND column_id in (select column_id from report_columns where column_parent='$par_id') limit 1;";
	$query = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($query)){
		$score = $row['mysum'];
	}
	
	$sql = "SELECT maxmarks as limits FROM report_structure where role_id='$role_id' AND column_id ='$par_id' limit 1;";
	$query = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($query)){
		$limit = $row['limits'];
	}
	
	if($limit>=($marks+$score)){
		$sql = "INSERT INTO report_structure (role_id, column_id, maxmarks) VALUES ('$role_id', '$child_id', '$marks')";
		$query = mysqli_query($conn, $sql);
		
		$sql = "UPDATE teacher_roles set structure_lock=1 where role_id='$role_id'";
		$query = mysqli_query($conn, $sql);
		
		echo "done";
	}else{
		echo "marks_excess";
	}
}
?>
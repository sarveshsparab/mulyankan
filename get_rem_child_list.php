<?php
include_once("connect_db.php");
$uid=$_POST['uid'];
$uname=$_POST['uname'];
$par_id=$_POST['par_id'];
$role_id=$_POST['role_id'];
$retarray = array();
$counter=0;

if($par_id!=-1){
	$sql = "SELECT * FROM report_columns where column_parent='$par_id'";
}else{
	$sql = "SELECT * FROM report_columns where column_parent is null";
}
$sql .= " and column_id in (select column_id from report_structure where role_id='$role_id')";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$retarray[$counter][0]=$row['column_id'];
	$retarray[$counter][1]=$row['column_name'];
	$counter++;
}
echo json_encode($retarray);
?>
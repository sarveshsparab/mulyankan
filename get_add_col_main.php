<?php
include_once("connect_db.php");
$role_id=$_POST['role_id'];
$retarray = array();
$counter=0;
$sql = "select * from report_columns where column_id in (select distinct column_parent FROM report_columns) and column_id in (select column_id from report_structure where role_id='$role_id')";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$retarray[$counter][0]=$row['column_id'];
	$retarray[$counter][1]=$row['column_name'];
	$counter++;
}
echo json_encode($retarray);
?>
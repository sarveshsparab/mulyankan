<?php
include_once("connect_db.php");
$role_id=$_POST['role_id'];
$retarray = array();
$counter=0;
$sql = "select * from report_columns where column_id in (select distinct t1.column_parent FROM report_columns as t1, report_structure as t2 where t2.role_id='$role_id' and t1.column_id=t2.column_id)";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$retarray[$counter][0]=$row['column_id'];
	$retarray[$counter][1]=$row['column_name'];
	$counter++;
}
echo json_encode($retarray);
?>
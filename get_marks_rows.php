<?php
include_once("connect_db.php");
$uid = $_POST['uid'];
$uname = $_POST['uname'];
$role_id = $_POST['role_id'];

$retarray = array();
$counter=0;

$sql = "SELECT t1.record_id as record_id, t1.student_id as student_id, t2.student_name as student_name, t2.student_roll as student_roll, t1.column_id as column_id, t1.marks as marks, t3.column_parent as column_parent, t4.maxmarks as maxmarks FROM marks as t1, student as t2, report_columns as t3, report_structure as t4 where t1.role_id='$role_id' and t1.student_id=t2.student_id and t1.column_id=t3.column_id and t1.role_id=t4.role_id and t1.column_id=t4.column_id order by t1.record_id";

$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$retarray[$counter][0]=$row['record_id'];
	$retarray[$counter][1]=$row['student_id'];
	$retarray[$counter][2]=$row['student_name'];
	$retarray[$counter][3]=$row['student_roll'];
	$retarray[$counter][4]=$row['column_id'];
	$retarray[$counter][5]=$row['marks'];
	$retarray[$counter][6]=$row['column_parent'];
	$retarray[$counter][7]=$row['maxmarks'];
	$counter++;
}
echo json_encode($retarray);

?>
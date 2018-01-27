<?php
include_once("connect_db.php");
$std_id=$_POST['std_id'];
$retarray = array();
$counter=0;
$sql = "SELECT t1.subject_id as subject_id, t1.subject_name as subject_name FROM subject as t1 where t1.subject_id not in ( select t2.subject_id from standard_syllabus as t2 where t2.standard_id='$std_id')";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$retarray[$counter][0]=$row['subject_id'];
	$retarray[$counter][1]=$row['subject_name'];
	$counter++;
}
echo json_encode($retarray);
?>
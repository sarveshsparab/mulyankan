<?php
include_once("connect_db.php");
$retarray = array();
$counter=0;
$sql = "SELECT t1.subject_id as subject_id, t1.subject_name as subject_name FROM subject as t1 order by t1.subject_name";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$retarray[$counter][0]=$row['subject_id'];
	$retarray[$counter][1]=$row['subject_name'];
	$counter++;
}
echo json_encode($retarray);
?>
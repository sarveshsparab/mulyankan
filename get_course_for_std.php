<?php
include_once("connect_db.php");
$std_id = $_POST['std_id'];
$retarray = array();
$counter=0;
$sql = "SELECT t2.subject_id AS subject_id, t2.subject_name AS subject_name FROM standard_syllabus AS t1, subject AS t2 WHERE t1.standard_id='$std_id' AND t1.subject_id = t2.subject_id";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$retarray[$counter][0]=$row['subject_id'];
	$retarray[$counter][1]=$row['subject_name'];
	$counter++;
}
echo json_encode($retarray);
?>
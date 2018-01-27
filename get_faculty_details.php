<?php
include_once("connect_db.php");
$fac_id = $_POST['fac_id'];
$retarray = array();
$counter=0;
$sql = "SELECT t1.teacher_id AS teacher_id, t1.teacher_name AS teacher_name, t1.teacher_email AS teacher_email, t3.standard_name AS standard_name, t5.division_name AS division_name,t4.subject_name AS subject_name, t2.role_id AS role_id FROM teacher AS t1, teacher_roles AS t2, standard AS t3, subject AS t4, division AS t5 WHERE t1.teacher_id='$fac_id' AND t1.teacher_id=t2.teacher_id AND t2.standard_id=t3.standard_id AND t2.subject_id=t4.subject_id AND t5.division_id=t2.division_id";
$query = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($query);
if($num_rows!=0){
	while($row = mysqli_fetch_assoc($query)){
		$retarray[$counter][0]=$row['teacher_id'];
		$retarray[$counter][1]=$row['teacher_name'];
		$retarray[$counter][2]=$row['teacher_email'];
		$retarray[$counter][3]=$row['standard_name'];
		$retarray[$counter][4]=$row['subject_name'];
		$retarray[$counter][5]=$row['role_id'];
		$retarray[$counter][6]=$row['division_name'];
		$counter++;
	}
}else{
	$sql = "SELECT t1.teacher_id AS teacher_id, t1.teacher_name AS teacher_name, t1.teacher_email AS teacher_email FROM teacher AS t1 WHERE t1.teacher_id='$fac_id'";
	$query = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($query)){
		$retarray[$counter][0]=$row['teacher_id'];
		$retarray[$counter][1]=$row['teacher_name'];
		$retarray[$counter][2]=$row['teacher_email'];
		$counter++;
	}
}
echo json_encode($retarray);
?>
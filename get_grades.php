<?php
include_once("connect_db.php");
$val=$_POST['val'];
$maxmarks=$_POST['maxmarks'];
$grade = '';
$sql = "SELECT grade_name FROM reportcard.grades where grade_max='$maxmarks' and grade_top>='$val' and grade_down<='$val' limit 1";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$grade = $row['grade_name'];
}
echo $grade;
?>
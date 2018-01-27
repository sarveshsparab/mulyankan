<?php
include_once("connect_db.php");
$std_id = $_POST['std_id'];
$course_id = $_POST['course_id'];

$sql = "DELETE FROM standard_syllabus WHERE subject_id='$course_id' AND standard_id='$std_id'";
$query = mysqli_query($conn, $sql);

echo "done";
?>
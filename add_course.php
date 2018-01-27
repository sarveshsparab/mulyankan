<?php
include_once("connect_db.php");
$std_id = $_POST['std_id'];
$new_course_id = $_POST['new_course'];

$sql = "INSERT INTO standard_syllabus (standard_id, subject_id) VALUES ('$std_id', '$new_course_id')";
$query = mysqli_query($conn, $sql);

echo "done";
?>
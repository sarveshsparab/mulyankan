<?php
include_once("connect_db.php");
$newName = $_POST['newName'];
$year_id = $_POST['year_id'];

$mark_ck = $_POST['mark_ck'];
$column_ck = $_POST['column_ck'];
$structure_ck = $_POST['structure_ck'];
$subject_ck = $_POST['subject_ck'];
$course_ck = $_POST['course_ck'];
$student_ck = $_POST['student_ck'];
$role_ck = $_POST['role_ck'];
$stddiv_ck = $_POST['stddiv_ck'];
$grade_ck = $_POST['grade_ck'];

$sql = "INSERT INTO sessions (sessions_name, sessions_parent) VALUES ('$newName', '$year_id')";
$query = mysqli_query($conn, $sql);

$sql = "SELECT * FROM sessions ORDER BY sessions_id DESC LIMIT 1";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$newId = $row['sessions_id'];
}

$sql = "Update teacher set session_id='$newId'";
$query = mysqli_query($conn, $sql);

if($mark_ck==1){
	$sql = "TRUNCATE TABLE  marks";
	$query = mysqli_query($conn, $sql);
}
if($column_ck==1){
	$sql = "TRUNCATE TABLE  report_columns";
	$query = mysqli_query($conn, $sql);
}
if($structure_ck==1){
	$sql = "TRUNCATE TABLE  report_structure";
	$query = mysqli_query($conn, $sql);
}
if($subject_ck==1){
	$sql = "TRUNCATE TABLE  subject";
	$query = mysqli_query($conn, $sql);
}
if($course_ck==1){
	$sql = "TRUNCATE TABLE  standard_syllabus";
	$query = mysqli_query($conn, $sql);
}
if($student_ck==1){
	$sql = "TRUNCATE TABLE  student";
	$query = mysqli_query($conn, $sql);
}
if($role_ck==1){
	$sql = "TRUNCATE TABLE  teacher_roles";
	$query = mysqli_query($conn, $sql);
}
if($stddiv_ck==1){
	$sql = "TRUNCATE TABLE  standard";
	$query = mysqli_query($conn, $sql);
	$sql = "TRUNCATE TABLE  division";
	$query = mysqli_query($conn, $sql);
}
if($grade_ck==1){
	$sql = "TRUNCATE TABLE  grades";
	$query = mysqli_query($conn, $sql);
}

echo "done";
?>
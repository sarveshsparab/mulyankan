<?php
include_once("connect_db.php");
$fac_id = $_POST['fac_id'];
$std_id = $_POST['std_id'];
$div_id = $_POST['div_id'];
$sub_id = $_POST['sub_id'];

$sql = "INSERT INTO teacher_roles (teacher_id, standard_id, division_id, subject_id) VALUES ('$fac_id', '$std_id', '$div_id', '$sub_id')";
$query = mysqli_query($conn, $sql);
echo "done";
?>
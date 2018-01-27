<?php
include_once("connect_db.php");
$role_id = $_POST['role_id'];

$sql = "DELETE FROM teacher_roles where role_id='$role_id'";
$query = mysqli_query($conn, $sql);
echo "done";
?>
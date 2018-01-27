<?php
include_once("connect_db.php");
$id = $_POST['id'];
$sql = "UPDATE teacher SET activated=1 WHERE teacher_id='$id'";
$query = mysqli_query($conn, $sql);
echo "done";
?>
<?php
include_once("connect_db.php");
$uname = $_POST['uname'];

$sql = "UPDATE teacher SET teacher_pass='forgot' where teacher_name='$uname'";
$query = mysqli_query($conn, $sql);
?>
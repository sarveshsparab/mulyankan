<?php
include_once("connect_db.php");
$new_pass = md5($_POST['new_pass']);
$uname = $_POST['uname'];
$sql = "UPDATE teacher SET teacher_pass='$new_pass' where teacher_name='$uname'";
$query = mysqli_query($conn, $sql);
echo "done";
?>
<?php
include_once("connect_db.php");
$new_email = md5($_POST['new_email']);
$uname = $_POST['uname'];
$sql = "UPDATE teacher SET teacher_email='$new_email' where teacher_name='$uname'";
$query = mysqli_query($conn, $sql);
echo "done";
?>
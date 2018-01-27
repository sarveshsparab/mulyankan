<?php
include_once("connect_db.php");
$newName = $_POST['newName'];

$sql = "INSERT INTO sessions (sessions_name) VALUES ('$newName')";
$query = mysqli_query($conn, $sql);

echo "done";
?>
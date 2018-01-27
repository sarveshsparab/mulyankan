<?php
include_once("connect_db.php");
$sub_name = $_POST['sub_name'];

$sql = "INSERT INTO subject (subject_name) VALUES ('$sub_name')";
$query = mysqli_query($conn, $sql);

echo "done";
?>
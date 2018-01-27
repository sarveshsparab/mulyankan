<?php
include_once("connect_db.php");
$col_id = $_POST['col_id'];

$sql = "DELETE FROM report_columns WHERE column_id='$col_id'";
$query = mysqli_query($conn, $sql);

echo "done";
?>
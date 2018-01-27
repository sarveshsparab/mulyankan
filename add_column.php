<?php
include_once("connect_db.php");
$par_id = $_POST['par_id'];
$col_name = $_POST['col_name'];

if($par_id==0){
	$sql = "INSERT INTO report_columns (column_name, column_parent) VALUES ('$col_name', NULL)";
}else{
	$sql = "INSERT INTO report_columns (column_name, column_parent) VALUES ('$col_name', '$par_id')";
}
$query = mysqli_query($conn, $sql);

echo "done";
?>
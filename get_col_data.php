<?php
include_once("connect_db.php");
$col_id = $_POST['col_id'];
$retarray = array();
$counter=0;
$sql = "SELECT * FROM report_columns WHERE column_id='$col_id'";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$retarray[$counter][0]=$row['column_id'];
	$retarray[$counter][1]=$row['column_name'];
	$retarray[$counter][2]=$row['column_parent'];
	
	$innersql = "SELECT * FROM report_columns WHERE column_parent='$col_id'";
	$innerquery = mysqli_query($conn, $innersql);
	$num_rows = mysqli_num_rows($innerquery);
	$retarray[$counter][3]=$num_rows;
	$counter++;
}
echo json_encode($retarray);
?>
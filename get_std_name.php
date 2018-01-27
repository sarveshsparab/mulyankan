<?php
include_once("connect_db.php");
$id = $_POST['id'];
$retarray = array();
$counter=0;
$sql = "Select * from standard where standard_id='$id' limit 1";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$retarray[$counter][0]=$row['standard_id'];
	$retarray[$counter][1]=$row['standard_name'];
	$counter++;
}
echo json_encode($retarray);
?>
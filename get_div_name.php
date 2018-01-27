<?php
include_once("connect_db.php");
$id = $_POST['id'];
$retarray = array();
$counter=0;
$sql = "Select * from division where division_id='$id' limit 1";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)){
	$retarray[$counter][0]=$row['division_id'];
	$retarray[$counter][1]=$row['division_name'];
	$counter++;
}
echo json_encode($retarray);
?>
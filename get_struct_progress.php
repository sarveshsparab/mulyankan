<?php
include_once("connect_db.php");
$role_id=$_POST['role_id'];
$val=-1;

$sql = "SELECT structure_lock FROM teacher_roles where role_id='$role_id' limit 1;";
$query = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($query)){
	$val = $row['structure_lock'];
}
echo $val;
?>
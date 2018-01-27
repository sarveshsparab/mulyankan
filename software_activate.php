<?php
include("connect_db.php");
$act_code = md5($_POST['act_code']);

$sql = "SELECT * FROM config order by config_id DESC LIMIT 1";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);

if($row['act_key']==$act_code) {
	$act_status='c93726808d1743b3ce7c0a2a2644bff1';
	$sql = "update config set act_status='$act_status' where act_key='$act_code'";
	$query = mysqli_query($conn, $sql);
	echo 'done';
}
else{
	echo 'error';
}
?>

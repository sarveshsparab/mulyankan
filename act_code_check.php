<?php
include("connect_db.php");
$act_code = md5($_REQUEST['act_code']);

$sql = "SELECT * FROM config order by config_id DESC LIMIT 1";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);


if($row['act_key']==$act_code) {
	echo 'true';
}
else{
	echo 'false';
}
?>
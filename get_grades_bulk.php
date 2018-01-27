<?php
include_once("connect_db.php");

$markslist=$_POST['markslist'];
$markslist_array = json_decode($markslist,true);

$retarray = array();
$counter=0;

for($i = 0 ; $i<count($markslist_array) ; $i++){
	$m = $markslist_array[$i]['maxmarks'];
	$v = $markslist_array[$i]['val'];
	$sql = "SELECT grade_name FROM reportcard.grades where grade_max='$m' and grade_top>='$v' and grade_down<='$v' limit 1";
	$query = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($query)){
		$retarray[$counter][0] = $row['grade_name'];
	}
	$counter++;
}

echo json_encode($retarray);

?>
<?php
include_once("connect_db.php");

$uid=$_POST['uid'];
$uname=$_POST['uname'];
$role_id=$_POST['role_id'];
$tab_head=$_POST['tab_head'];
$tab_body=$_POST['tab_body'];

$tab_head_array = json_decode($tab_head,true);
$tab_body_array = json_decode($tab_body,true);

$sql = "";
for($i = 0 ; $i<count($tab_body_array) ; $i++){
	$c = $tab_body_array[$i]['column_id'];
	if($c!='null'){
		$s = $tab_body_array[$i]['student_id'];
		$m = $tab_body_array[$i]['cell_value'];
		if($m=='-')
			$m=0;
		$sql .= "UPDATE marks SET marks='$m' WHERE role_id=$role_id AND student_id='$s' AND column_id='$c';";
	}
}
if (mysqli_multi_query($conn, $sql)) {
    echo "done";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
};

?>
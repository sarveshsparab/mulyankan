<?php
include_once("connect_db.php");
$std_id = $_POST['std_id'];
$div_id = $_POST['div_id'];
$num_rows = $_POST['num_rows'];
$num_cols = $_POST['num_cols'];
$tab_json = $_POST['table'];
$table = json_decode($tab_json);

$sql = "";
for($i = 0 ; $i<$num_rows ; $i++){
	$a = $table[$i][0];
	$b = $table[$i][1];
	$c = $table[$i][2];
	$sql .= "INSERT INTO student (student_ref, student_name, student_roll, standard_id, division_id) VALUES ('$a', '$b', '$c', '$std_id', '$div_id');";
}

if (mysqli_multi_query($conn, $sql)) {
    echo "done";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
};

?>
<?php
include_once("connect_db.php");
$searchTerm = $_GET['term'];
$data=array();
$counter=0;
$sql = "SELECT * FROM subject WHERE subject_name LIKE '%".$searchTerm."%' ORDER BY subject_name ASC";
$query = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($query)) {
	$data[$counter] = $row['subject_name'];
	$counter++;
}
echo json_encode($data);
?>
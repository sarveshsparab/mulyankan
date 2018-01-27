<?php
include_once("connect_db.php");

$uid=$_POST['uid'];
$uname=$_POST['uname'];
$role_id=$_POST['role_id'];

//$role_id=6;

$data = array();
$index = array();
$retarray = array();
$counter = -1;
$res = "";
$query = mysqli_query($conn,"SELECT t1.column_id as column_id, t1.column_parent as column_parent, t1.column_name as column_name, t2.maxmarks as maxmarks FROM report_columns as t1, report_structure as t2 WHERE t1.column_id=t2.column_id and t2.role_id='$role_id' ORDER BY t1.column_name");
while ($row = mysqli_fetch_assoc($query)) {
    $id = $row["column_id"];
    $parent_id = $row["column_parent"] === NULL ? "NULL" : $row["column_parent"];
    $data[$id] = $row;
    $index[$parent_id][] = $id;
}


function display_child_nodes($conn, $parent_id, $level, $role_id)
{
    global $data, $index, $res, $counter, $retarray;
	$counter++;
    $parent_id = $parent_id === NULL ? "NULL" : $parent_id;
    if (isset($index[$parent_id])) {
		$x_offset = 0 ;
        foreach ($index[$parent_id] as $id) {
			$par_id = $data[$id]["column_id"];
			$query = mysqli_query($conn,"select count(t1.column_id) as child_count from report_structure as t1, report_columns as t2 where t1.role_id='$role_id' and t1.column_id=t2.column_id and t2.column_parent='$par_id'");
			while ($row = mysqli_fetch_assoc($query)) {
				$child_count = $row['child_count'];
			}
            $res .= str_repeat("→", $level);
			$res .= " ( ".$level." , ".$x_offset." ) ";
			$retarray[$counter]['level']=$level;
			$retarray[$counter]['x_offset']=$x_offset;
			
			$res .= $data[$id]["column_id"]." - ";
			$retarray[$counter]['column_id']=$data[$id]["column_id"];
			
			$res .= $data[$id]["column_name"];
			$retarray[$counter]['column_name']=$data[$id]["column_name"];
			
			$res .= " [ ".$data[$id]["maxmarks"]." ] ";
			$retarray[$counter]['maxmarks']=$data[$id]["maxmarks"];
			
			$res .= " [ ".$data[$id]["column_parent"]." ] ";
			$retarray[$counter]['parent_id']=$data[$id]["column_parent"];
			
			$res .= " [ ".$child_count." ] ";
			$retarray[$counter]['child_count']=$child_count;
			
			$res .= " [ ".$counter." ] ";
			$res .= "<br />";
			$x_offset++;
            display_child_nodes($conn, $id, $level + 1, $role_id);
        }
    }
}
display_child_nodes($conn, NULL, 0, $role_id);

$leaves = array();
$counter=0;
foreach($retarray as $val){
	if($val['child_count']==0){
		$leaves[$counter]=$val['column_id'];
		$counter++;
	}
}

$check_sql = "SELECT * FROM marks where role_id='$role_id'";
$check_query = mysqli_query($conn,$check_sql);
$check_rows = mysqli_num_rows($check_query);

if($check_rows>0){
	$check_sql = "DELETE FROM marks where role_id='$role_id'";
	$check_query = mysqli_query($conn,$check_sql);
}

$marks_sql = "";

$roles_sql="SELECT t1.student_id as student_id, t1.student_name as student_name, t1.student_roll as student_roll FROM student as t1, teacher_roles as t2 where t2.role_id='$role_id' and t1.standard_id=t2.standard_id and t1.division_id=t2.division_id";
$roles_query = mysqli_query($conn,$roles_sql);
while ($roles_row = mysqli_fetch_assoc($roles_query)) {
	$a = $roles_row['student_id'];
	for($i=0;$i<count($leaves);$i++){
		$b = $leaves[$i];
		$marks_sql .= "INSERT INTO marks (student_id, role_id, column_id, marks) VALUES ('$a', '$role_id', '$b', -1);";
	}
}
if (mysqli_multi_query($conn, $marks_sql)) {
    echo "done";
} else {
    echo "error";
};

//echo $res;
//echo json_encode($retarray);
?>
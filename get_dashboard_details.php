<?php
include_once("connect_db.php");
$uid=$_POST['uid'];
$uname=$_POST['uname'];

$sql = "SELECT t1.role_id as role_id, t3.standard_name as standard_name, t4.division_name as division_name, t2.subject_name as subject_name, t1.structure_lock as structure_lock, t1.marks_lock as marks_lock FROM teacher_roles as t1, subject as t2, standard as t3, division as t4 WHERE t1.teacher_id='$uid' and t2.subject_id = t1.subject_id and t3.standard_id=t1.standard_id and t4.division_id=t1.division_id order by t3.standard_id, t4.division_id";

$query = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($query);
if($num_rows==0){
	echo "<h4>No Roles Assigned to You</h4>";
}else{
	$dash = '<div class="grid-row">';
	$dash .= '<div class="grid-col grid-col-4"><h4>Roles</h4></div><div class="grid-col grid-col-4"><h4>Report Card Structure</h4></div><div class="grid-col grid-col-4"><h4>Marks Entered</h4></div>';
	$dash .= '</div>';
	$dash .= '<div class="grid-row">';
	$dash .= '<div class="grid-col grid-col-4"><h4>&nbsp;&nbsp;</h4></div>';
	$dash .= '<div class="grid-col grid-col-1"><abbr title="Initialised"><i class="fa fa-circle-o fa-lg2 faa-pulse animated-hover" style="float:right;"></i></abbr></div><div class="grid-col grid-col-1"><abbr title="Create or Edit"><i class="fa fa-pencil fa-lg2 faa-wrench animated-hover"style="float:right;"></i></abbr></div><div class="grid-col grid-col-1"><abbr title="Saved"><i class="fa fa-save fa-lg2 faa-flash animated-hover" style="float:right;"></i></abbr></div><div class="grid-col grid-col-1"><abbr title="Locked"><i class="fa fa-lock fa-lg2 faa-burst animated-hover" style="float:right;"></i></abbr></div>';
	$dash .= '<div class="grid-col grid-col-1"><abbr title="Initialised"><i class="fa fa-circle-o fa-lg2 faa-pulse animated-hover" style="float:right;"></i></abbr></div><div class="grid-col grid-col-1"><abbr title="Create or Edit"><i class="fa fa-pencil fa-lg2 faa-wrench animated-hover"style="float:right;"></i></abbr></div><div class="grid-col grid-col-1"><abbr title="Saved"><i class="fa fa-save fa-lg2 faa-flash animated-hover" style="float:right;"></i></abbr></div><div class="grid-col grid-col-1"><abbr title="Locked"><i class="fa fa-lock fa-lg2 faa-burst animated-hover" style="float:right;"></i></abbr></div>';
	$dash .= '</div>';
	
	while($row = mysqli_fetch_assoc($query)){
		$dash .= '<div class="grid-row" style="margin-top:5px;">';
		$dash .= '<div class="grid-col grid-col-2"><label class="label">'.$row['standard_name'].' : '.$row['division_name'].'</label></div>';
		$dash .= '<div class="grid-col grid-col-2"><label class="label">'.$row['subject_name'].'</label></div>';
		$dash .= '<div class="grid-col grid-col-4">';
		$dash .= '<div class="progress">';
		$dash .= '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'.getPercentVal($row['structure_lock']).'" aria-valuemin="0" aria-valuemax="100" style="width:'.getPercentVal($row['structure_lock']).'%">';
		$dash .= ''.getPercentDispVal($row['structure_lock']).'%';
		$dash .= '</div>';
		$dash .= '</div>';
		$dash .= '</div>';
		$dash .= '<div class="grid-col grid-col-4">';
		$dash .= '<div class="progress">';
		$dash .= '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'.getPercentVal($row['marks_lock']).'" aria-valuemin="0" aria-valuemax="100" style="width:'.getPercentVal($row['marks_lock']).'%">';
		$dash .= ''.getPercentDispVal($row['marks_lock']).'%';
		$dash .= '</div>';
		$dash .= '</div>';
		$dash .= '</div>';
		$dash .= '</div>';
	}
	echo $dash;
}

function getPercentVal($val){
	$ret='0';
	if($val==0){
		$ret='20';
	}else if($val==1){
		$ret='45';
	}else if($val==2){
		$ret='75';
	}else if($val==3){
		$ret='100';
	}
	return $ret;
}
function getPercentDispVal($val){
	$ret='0';
	if($val==0){
		$ret='25';
	}else if($val==1){
		$ret='50';
	}else if($val==2){
		$ret='75';
	}else if($val==3){
		$ret='100';
	}
	return $ret;
}
?>

<?php
include_once("connect_db.php");
$fac_id = $_POST['fac_id'];

$sql = "SELECT t1.role_id as role_id, t1.structure_lock as struct_lock, t1.marks_lock as marks_lock, t2.teacher_name as teacher_name, t3.standard_name as standard_name, t4.division_name as division_name, t5.subject_name as subject_name FROM teacher_roles as t1, teacher as t2, standard as t3, division as t4, subject as t5 where t1.teacher_id='$fac_id' and t1.teacher_id=t2.teacher_id and t1.standard_id=t3.standard_id and t1.division_id=t4.division_id and t1.subject_id=t5.subject_id";

$query = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($query);
$dash = '';

if($num_rows==0){
	$dash .= "not_done";
}else{
	$dash .= '<div class="grid-row">';
	$dash .= '<section class="sky-form col" style="box-shadow:none">';
	$dash .= '<div class="grid-col grid-col-2"><label class="label">Class</label>';
	$dash .= '</div>';
	$dash .= '<div class="grid-col grid-col-2"><label class="label">Subject Name</label>';
	$dash .= '</div>';
	$dash .= '<div class="grid-col grid-col-2"><label class="label">Structure Status</label>';
	$dash .= '</div>';
	$dash .= '<div class="grid-col grid-col-2"><label class="label">Structure Options</label>';
	$dash .= '</div>';
	$dash .= '<div class="grid-col grid-col-2"><label class="label">Marks Status</label>';
	$dash .= '</div>';
	$dash .= '<div class="grid-col grid-col-2"><label class="label">Marks Options</label>';
	$dash .= '</div>';
	$dash .= '</section>';
	$dash .= '</div>';
	while($row = mysqli_fetch_assoc($query)){
		$dash .= '<div class="grid-row">';
		$dash .= '<section class="sky-form col" style="box-shadow:none">';
		
		$dash .= '<div class="grid-col grid-col-2">';
		$dash .= '<label class="label" style="font-size:16px">'.$row["standard_name"].' | '.$row["division_name"].'</label>';
		$dash .= '</div>';
		
		$dash .= '<div class="grid-col grid-col-2">';
		$dash .= '<label class="label" style="font-size:16px">'.$row["subject_name"].'</label>';
		$dash .= '</div>';
		
		$dash .= '<div class="grid-col grid-col-2">';
		$dash .= '<label class="label" style="font-size:16px">'.getStatus($row["struct_lock"]).'</label>';
		$dash .= '</div>';
		
		$dash .= '<div class="grid-col grid-col-2">';
		$dash .= '<label class="label" style="font-size:16px">'.getStructOptions($row["struct_lock"],$row['role_id']).'</label>';
		$dash .= '</div>';
		
		$dash .= '<div class="grid-col grid-col-2">';
		$dash .= '<label class="label" style="font-size:16px">'.getStatus($row["marks_lock"]).'</label>';
		$dash .= '</div>';
		
		$dash .= '<div class="grid-col grid-col-2">';
		$dash .= '<label class="label" style="font-size:16px">'.getMarksOptions($row["marks_lock"],$row['role_id']).'</label>';
		$dash .= '</div>';
		
		$dash .= '</section>';
		$dash .= '</div>';
	}	
}

		/*$dash .= '<label class="label">&nbsp;</label><label class="label" style="font-size:16px"></label>';
		$dash .= '';
		$dash .= '';
		$dash .= '';
		$dash .= '';
		$dash .= '';*/
echo $dash;

function getStatus($stat){
	if($stat==0)
		return '<i class="fa fa-circle-o"></i>&nbsp;&nbsp;Initialized';
	else if($stat==1)
		return '<i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Mode';
	else if($stat==2)
		return '<i class="fa fa-save"></i>&nbsp;&nbsp;Saved';
	else if($stat==3)
		return '<i class="fa fa-lock"></i>&nbsp;&nbsp;Locked';
}
function getStructOptions($stat, $id){
	if($stat<3){
		return '<label class="toggle" style="margin-right:25px;"><input type="checkbox" name="struct_toggle'.$id.'" id="struct_toggle'.$id.'"  onClick="lock_unlock(0,'.$id.');" /><i></i>Lock</label>';
	}else{
		return '<label class="toggle" style="margin-right:25px;"><input type="checkbox" name="struct_toggle'.$id.'" id="struct_toggle'.$id.'" checked onClick="lock_unlock(0,'.$id.');" /><i></i>Lock</label>';
	}
}
function getMarksOptions($stat, $id){
	if($stat<3){
		return '<label class="toggle" style="margin-right:25px;"><input type="checkbox" name="marks_toggle'.$id.'" id="marks_toggle'.$id.'"  onClick="lock_unlock(1,'.$id.');" /><i></i>Lock</label>';
	}else{
		return '<label class="toggle" style="margin-right:25px;"><input type="checkbox" name="marks_toggle'.$id.'" id="marks_toggle'.$id.'" checked onClick="lock_unlock(1,'.$id.');" /><i></i>Lock</label>';
	}
}
?>
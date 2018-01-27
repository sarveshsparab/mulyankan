<?php
include_once("connect_db.php");
$uid=$_POST['uid'];
$uname=$_POST['uname'];

$dash = '';

$dash .= '<div class="grid-row"><div class="grid-col grid-col-6"><section class="sky-form col" style="box-shadow:none"><label class="label">Choose a Role</label><label class="select"><select id="subject_marksheet_role_list" onChange="subject_marksheet_disp();">';

$sql = "SELECT * FROM teacher_roles as t1, teacher as t2, standard as t3, division as t4, subject as t5 where t1.teacher_id = t2.teacher_id and t1.standard_id = t3.standard_id and t1.division_id = t4.division_id and t1.subject_id = t5.subject_id order by t3.standard_id, t4.division_id, t5.subject_name";
$query = mysqli_query($conn, $sql);
if(mysqli_num_rows($query)==0){
	$dash .= '<option value="0">No Roles Available</option>';
}else{
	$dash .= '<option value="0">Choose a Role</option>';
	while($row = mysqli_fetch_assoc($query)){
		$dash .= '<option value="'.$row["role_id"].'">';
		$dash .= $row['standard_name'].' | '.$row['division_name'].' || '.$row['subject_name'];
		$dash .= '</option>';
	}
}
$dash .= '</select></label></section></div><div class="grid-col grid-col-2" id="marks_toggle_div" style="visibility:hidden;"><section class="sky-form col" style="box-shadow:none"><label class="label">Toggle to See Marks</label><label class="toggle" style="float:left;"><input type="checkbox" checked onClick="showMarksGradesToggle();" id="sub_marks_toggle"><i></i></label></section></div><div class="grid-col grid-col-2" id="grades_toggle_div" style="visibility:hidden;"><section class="sky-form col" style="box-shadow:none"><label class="label">Toggle to See Grades</label><label class="toggle" style="float:left;"><input type="checkbox" onClick="showMarksGradesToggle();" id="sub_grades_toggle"><i></i></label></section></div><div class="grid-col grid-col-1" id="view_submarks_div" style="visibility:hidden;"><label class="label">View &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><abbr title="Click to View"><i class="fa fa-eye faa-slow fa-2x faa-pulse animated-hover" onClick="submarks_view();"></i></abbr></div><div class="grid-col grid-col-1" id="down_submarks_div" style="visibility:hidden;"><label class="label">Download</label><abbr title="Click to Download"><i class="fa fa-download fa-2x faa-slow faa-bounce animated-hover" onClick="submarks_down();"></i></abbr></div></div>';

echo $dash;
?>
                
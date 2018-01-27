<?php
include_once("connect_db.php");
$uid=$_POST['uid'];
$uname=$_POST['uname'];

$dash = '';

$dash .= '<div class="grid-row"><section class="sky-form col" style="box-shadow:none"><div class="row"><div class="col col-6"><label class="label">Select Role</label><label class="select"><select id="create_struct_role_list" onChange="enable_create_options();"> <option value="0">Choose a Role</option>';

$sql_roles = "SELECT t3.standard_name AS standard_name, t5.division_name AS division_name,t4.subject_name AS subject_name, t2.role_id AS role_id FROM teacher AS t1, teacher_roles AS t2, standard AS t3, subject AS t4, division AS t5 WHERE t1.teacher_id='$uid' AND t1.teacher_id=t2.teacher_id AND t2.standard_id=t3.standard_id AND t2.subject_id=t4.subject_id AND t5.division_id=t2.division_id";

$query_roles = mysqli_query($conn, $sql_roles);
while($row_roles = mysqli_fetch_assoc($query_roles)){
	$dash .='<option value="'.$row_roles['role_id'].'">'.$row_roles['standard_name'].' | '.$row_roles['division_name'].' || '.$row_roles['subject_name'].'</option>';
}

$dash .= '</select><i></i></label></div><div class="col col-2"> &nbsp;&nbsp;</div><div class="col col-1" id="view_struct_div" style="visibility:hidden;"><label class="label">View</label><abbr title="Click to View"><i class="fa fa-eye fa-2x faa-pulse animated-hover" onClick="struct_view();"></i></abbr></div><div class="col col-1" id="edit_struct_div" style="visibility:hidden;"><label class="label">Edit</label><abbr title="Click to Edit"><i class="fa fa-pencil fa-2x faa-wrench animated-hover" onClick="struct_edit();"></i></abbr></div><div class="col col-1" id="save_struct_div" style="visibility:hidden;"><label class="label">Save</label><abbr title="Click to Save"><i class="fa fa-save fa-2x faa-flash animated-hover" onClick="struct_save();"></i></abbr></div><div class="col col-1" id="lock_struct_div" style="visibility:hidden;"><label class="label">Lock</label><abbr title="Click to Lock"><i class="fa fa-lock fa-2x faa-burst animated-hover" ></i></abbr></div></div></section></div>';

$dash .= '<br />';

echo $dash;
?>




<?php
include_once("connect_db.php");
$uid=$_POST['uid'];
$uname=$_POST['uname'];
$role_id=$_POST['role_id'];

$dash = '';

$dash .= '<div class="grid-row" id="col_struct_row_div"><div class="grid-col grid-col-5" id="col_struct_edit_options_div" style="display:none;"><h4>Editing Options</h4><br /><section class="sky-form col" style="box-shadow:none"><label class="label" style="font-size:16px;">Add New Column</label><div class="row"><div class="col col-4"><label class="label">Choose Main Column</label><label class="select"><select id="add_col_par_list" onChange="make_add_child_list();"></select><i></i></label></div>';

/*$sql_add = "select * from report_columns where column_id in (select distinct column_parent FROM report_columns) and column_id in (select column_id from report_structure where role_id='$role_id')";

$query_add = mysqli_query($conn, $sql_add);
while($row_add = mysqli_fetch_assoc($query_add)){
	$dash .='<option value="'.$row_add['column_id'].'">'.$row_add['column_name'].'</option>';
}*/

$dash .= '<div class="col col-4"><label class="label">Choose Sub-Column</label><label class="select"><select id="add_col_child_list"><option value="0">Choose a Sub-Column</option></select><i></i></label></div><div class="col col-3"><label class="label">Max Marks</label><label class="input"><input type="text" placeholder="Marks" id="add_col_marks"></label></div><div class="col col-1" style="margin-top:23px;"><abbr title="Click to Add"><i class="fa fa-plus-square fa-3x" onClick="add_col_struct();"></i></abbr></div></div><br /> <label class="label" style="font-size:16px;">Remove Column</label><div class="row"><div class="col col-5"><label class="label">Choose Main Column</label><label class="select"><select id="rem_col_par_list" onChange="make_rem_child_list();"></select><i></i></label></div>';

/*$sql_rem = "select * from report_columns where column_id in (select distinct t1.column_parent FROM report_columns as t1, report_structure as t2 where t2.role_id='$role_id' and t1.column_id=t2.column_id)";

$query_rem = mysqli_query($conn, $sql_rem);
$num_rows = mysqli_num_rows($query_rem);

if($num_rows!=0){
	$dash .= '<option value="-1">Base</option>';
	while($row_rem = mysqli_fetch_assoc($query_rem)){
		$dash .='<option value="'.$row_rem['column_id'].'">'.$row_rem['column_name'].'</option>';
	}
}
*/
$dash .= '<div class="col col-5"><label class="label">Choose Sub-Column</label><label class="select"><select id="rem_col_child_list"></select> <i></i></label></div><div class="col col-1">&nbsp;</div><div class="col col-1" style="margin-top:23px;"><abbr title="Click to Remove"><i class="fa fa-minus-square fa-3x" onClick="rem_col_struct();"></i></abbr></div></div></section></div><div class="grid-col grid-col-7"><h4>Structure Preview</h4><br /><div id="col_struct_preview" style="border-style:groove; border-width:medium; width:100%; height:300px; overflow:auto;"></div></div></div>';

echo $dash;

?>


                                                        
                                                        
                                                        
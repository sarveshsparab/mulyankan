<?php
include_once("connect_db.php");
$sql = "SELECT * FROM config order by config_id DESC LIMIT 1";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);
$activation = 0;
if($row['act_status'] == 'c93726808d1743b3ce7c0a2a2644bff1'){
	$activation = -1;
}else if($row['act_status'] == '0cf6f4b0cfa8856b98a2c630b2556383'){
	$curr_time = time(); 
	$reg_time = strtotime($row['reg_time']);
	$datediff = abs($curr_time - $reg_time);
	$days = floor($datediff/(60*60*24));
	if($days>$row['trial_limit']){
		$activation = -2;
		header("location: trial_expire.php");
		exit();
	}else{
		$activation = $row['trial_limit']-$days;
	}
}else{
	header("location: index.php");
	exit();
}

?>

<?php
include_once("check_login_stat.php");

if(isset($_GET["uname"])){
	$uname = preg_replace('#[^a-z0-9]#i', '', $_GET['uname']);
	$userid = $_GET['uid'];
} else {
    header("location: report_login.php");
    exit();	
}

$isOwner = "no";
if($uname == $log_username && $user_ok == true){
	$isOwner = "yes";
	$sql = "select * from teacher where teacher_name='$uname' limit 1";
	$query = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($query)){
		$user_email = $row['teacher_email'];
	}
}

if($isOwner=="no"){
	header("location: report_login.php");
    exit();	
}

?>
<!DOCTYPE html> 
<html>
<head>
		<meta charset="utf-8">		
		<title><?php echo $uname." Panel";?></title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<link rel="stylesheet" href="css/demo-tabs.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/font-awesome-animate.css">
		<link rel="stylesheet" href="css/sky-tabs.css">
        <link rel="stylesheet" href="css/sky-forms.css" />
        <link rel="stylesheet" href="css/tables.css" />
        <link rel="stylesheet" href="css/progress.css" /> 
        <link rel="stylesheet" href="css/footer.css" />      
        
        <script src="js/jquery-1.9.1.min.js"></script>
    	<script src="js/jquery.validate.min.js"></script>
    	<script src="js/jquery.placeholder.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/tableExport1.js"></script>
		<script type="text/javascript" src="js/jquery.base64.js"></script>
        <script type="text/javascript" src="js/html2canvas.js"></script>
        <script type="text/javascript" src="js/jspdf/libs/sprintf.js"></script>
        <script type="text/javascript" src="js/jspdf/jspdf.js"></script>
        <script type="text/javascript" src="js/jspdf/libs/base64.js"></script>
        
	</head>
	
	<body class="bg-cyan" id="mainbody">
    	<input type="hidden" id="get_uid" value="<?php echo $_GET['uid']?>" />
        <input type="hidden" id="get_uname" value="<?php echo $_GET['uname']?>" />
		<div class="body">
		
			<div class="sky-tabs sky-tabs-amount-4 sky-tabs-pos-top-justify sky-tabs-anim-flip sky-tabs-response-to-icons">
				<input type="radio" name="sky-tabs" id="sky-tab1" class="sky-tab-content-1" onClick="makeDashboard();">
				<label for="sky-tab1"><span><span><i class="fa fa-dashboard"></i>Dashboard</span></span></label>
				
				<input type="radio" name="sky-tabs" id="sky-tab2" class="sky-tab-content-2" onClick="makeCreateStructure();">
				<label for="sky-tab2"><span><span><i class="fa fa-code-fork"></i>Create Structure</span></span></label>
				
				<input type="radio" name="sky-tabs" id="sky-tab3" class="sky-tab-content-3">
				<label for="sky-tab3"><span><span><i class="fa fa-list-alt"></i>Report Card</span></span></label>
				
				<input type="radio" name="sky-tabs" id="sky-tab4" class="sky-tab-content-4">
				<label for="sky-tab4"><span><span><i class="fa fa-user"></i>Profile</span></span></label>
				
				<ul>
					<li class="sky-tab-content-1">					
						<div class="typography">
							<h1>Dashboard</h1>
							<p>Here you will get the details of the roles assigned to you. Also will show your progress in each phase.</p><br />
                            <div id="dashboard_div">
                            </div>
						</div>
					</li>
					
					<li class="sky-tab-content-2">
						<div class="typography">
							<h1>Create Report Card Structure</h1>
							<p>Here you will be able to build your own custom template for your subject marking.</p><br />
                            <div id="create_struct_div">
                           	</div>
                            <div id="create_struct_options_div" style="visibility:hidden; display:none;"></div>
						</div> 
					</li>
					
					<li class="sky-tab-content-3">
						<div class="typography">
							<h1>Enter Marks and Make Report Cards</h1>
							<p>Here you will be able to enter in the marks and then auto generate the report cards.</p><br />
                        </div>  
                        <div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-scale sky-tabs-response-to-stack">
                        <input type="radio" name="sky-tabs-3" id="sky-tab3-1" class="sky-tab-content-1" onClick="makeMarksEnter();">
                        <label for="sky-tab3-1"><span><span>Enter Marks</span></span></label>
                        
                        <input type="radio" name="sky-tabs-3" id="sky-tab3-2" class="sky-tab-content-2" onClick="makeSubjectMarksheet();">
                        <label for="sky-tab3-2"><span><span>Subject Marksheet</span></span></label>
                        
                        <!--<input type="radio" name="sky-tabs-3" id="sky-tab3-3" class="sky-tab-content-3" onClick="makeStudentMarksheet();">
                        <label for="sky-tab3-3"><span><span>Student Marksheet</span></span></label>-->
                        <ul>
                            <li class="sky-tab-content-1">
                                <div class="typography">
                                    <h3>Enter Marks</h3><br />
                                </div>
                                <div id="marks_enter_div"></div>
                                <div id="marks_enter_table_div" style="visibility:hidden; display:none; overflow:auto;">
                                </div>
                            </li>
                            
                            <li class="sky-tab-content-2">
                                <div class="typography">
                                    <h3>Subject - Wise Marksheet</h3><br />
                                </div>
                                <div id="subject_marksheet_div"></div>
                                <div id="subject_marksheet_table">
                                	
                                </div>
                            </li>
                            
                            <li class="sky-tab-content-3">
                                <div class="typography">
                                    <h3>Student - Wise Marksheet</h3><br />
                                </div>
                            </li>
                            
                        </ul>
                        </div>
					</li>
					
					<li class="sky-tab-content-4">
						<div class="typography">
							<h1>Profile</h1>
                            <p>Here you have the functionality regarding your profile <br>
                            Functionality to update your password or email address<br><br></p> 
						</div>
                        <div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-scale sky-tabs-response-to-stack">
                        	<input type="radio" name="sky-tabs-5" id="sky-tab5-1" class="sky-tab-content-1">
							<label for="sky-tab5-1"><span><span>Logout</span></span></label>
                            
							<input type="radio" name="sky-tabs-5" id="sky-tab5-2" class="sky-tab-content-2">
							<label for="sky-tab5-2"><span><span>Update Password</span></span></label>
							
							<input type="radio" name="sky-tabs-5" id="sky-tab-5-3" class="sky-tab-content-3">
							<label for="sky-tab-5-3"><span><span>Update Email</span></span></label>
                            <ul>
                            	<li class="sky-tab-content-1">
									<div class="typography">
										<h3>Logout</h3><br />
                                        <p>Thank you for using this system. Click below to logout.<br><br></p> 
									</div>
                                    <form id="logout_form" method="post" action="logout.php" class="sky-form">
                                        <input type="submit" class="button button-secondary" value="Logout" />
                                    </form>
								</li>
                            	<li class="sky-tab-content-2">
									<div class="typography">
										<h3>Update Password</h3><br />
									</div>
                                    <form id="change_pass_form" class="sky-form" style="box-shadow:none">
                                        <section>
                                        	<label class="label">Enter new Password</label>
                                            <label class="input">
                                            	<i class="icon-append icon-lock"></i>
                                                <input type="password" placeholder="New Password" id="new_password" name="new_password">
                                            </label>
                                            <label class="label">Re-enter new Password</label>
                                            <label class="input">
                                            	<i class="icon-append icon-lock"></i>
                                                <input type="password" placeholder="New Password" id="new_password_confirm" name="new_password_confirm">
                                            </label>
                                            <input type="hidden" value="<?php echo $_GET["uname"];?>" id="uname_pass" />
                                            <input type="submit" class="button button-secondary" value="Change" />
                                        </section>
                                    </form>
								</li>
                                <li class="sky-tab-content-3">
									<div class="typography">
										<h3>Update Email Address</h3><br />
									</div>	
                                    <form id="change_email_form" class="sky-form" style="box-shadow:none">
                                        <section>
                                        	<label class="label">Enter new Email Address</label>
                                            <label class="input">
                                            	<i class="icon-append icon-envelope-alt"></i>
                                                <input type="email" placeholder="New Email Address" id="email" name="email" autocomplete="off">
                                            </label>
                                            <input type="hidden" value="<?php echo $_GET["uname"];?>" id="uname_email" />
                                            <input type="submit" class="button button-secondary" value="Change" />
                                        </section>
                                    </form>								
								</li>
                            </ul>
                         </div>
					</li>					
				</ul>
			</div>
		</div>
        
        <script>
		var subMarksArray = [];
		function makeSubjectMarksheet(){
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			$.ajax({
				type: 'POST',
				url: 'get_subject_marksheet.php',
				data: {uid : uid, uname : uname},
				success: function(data) {
					document.getElementById('subject_marksheet_div').innerHTML=data;
					document.getElementById('subject_marksheet_table').style.visibility="hidden";
					document.getElementById('subject_marksheet_table').style.display="none";
					document.getElementById('view_submarks_div').style.visibility="hidden";
					document.getElementById('down_submarks_div').style.visibility="hidden";
					document.getElementById('marks_toggle_div').style.visibility="hidden";
					document.getElementById('grades_toggle_div').style.visibility="hidden";
				}
			});
		}
		function subject_marksheet_disp(){
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			var role_list = document.getElementById('subject_marksheet_role_list');
			var role_id = role_list.value;
			document.getElementById('subject_marksheet_table').style.visibility="hidden";
			document.getElementById('subject_marksheet_table').style.display="none";
			if(role_id!=0){
				var div = document.getElementById('subject_marksheet_table');
				document.getElementById('view_submarks_div').style.visibility="visible";
				document.getElementById('down_submarks_div').style.visibility="hidden";
				document.getElementById('marks_toggle_div').style.visibility="hidden";
				document.getElementById('grades_toggle_div').style.visibility="hidden";
				
				$.ajax({
					type: 'POST',
					url: 'get_report_preview.php',
					data: {uid : uid, uname : uname, role_id : role_id},
					success: function(data) {
						var x=JSON.parse(data);
						if(x.length!=0){
							document.getElementById('down_submarks_div').style.visibility="visible";
							var pre_x = JSON.parse(data);
							x = addTotalColumn(x);
							x = cleanXOffset(x);
							var netLev = getNumLevels(x);
							var lev_off = new Array(netLev+1);
							var tab = '';
							tab += '<table class="grade_table" id="subject_marksheet_main_table"><thead>';
							for(var lev=0;lev<=netLev;lev++){
								lev_off[lev]=0;
								tab += '<tr>';
								if(lev==0){
									tab += '<th rowspan="'+(netLev+1)+'" colspan="1" id="i-2|c0|a0|o-2|m-1|pnull">Student Roll Number</th>';
									tab += '<th rowspan="'+(netLev+1)+'" colspan="1" id="i-1|c0|a0|o-1|m-1|pnull">Student Names</th>';
								}
								for(var i=0;i<x.length;i++){
									if(x[i]['level']==lev){
										tab += '<th id="i'+x[i]['column_id']+'|c'+x[i]['child_count']+'|a'+getAllChildrenCount(x,i,0)+'|o'+x[i]['x_offset']+'|m'+x[i]['maxmarks']+'|p'+x[i]['parent_id']+'" ';
										tab += 'colspan="'+(getAllChildrenCount(x,i,0)>1?getAllChildrenCount(x,i,0):getAllChildrenCount(x,i,0))+'" ';
										tab += 'rowspan="'+(x[i]['child_count']==0?(netLev-lev+1):(1))+'" ';
										if(x[i]['column_name']=='Total'){
											tab += 'class="totalCol" ';
										}
										tab += '>';
										tab += x[i]['column_name'];
										tab += '<br />( '+x[i]['maxmarks']+' )';
										tab += '</th>';
										lev_off[x[i]['level']] = x[i]['x_offset'] + getAllChildrenCount(x,i,0);
									}
								}
								tab += '</tr>';
							}
							tab += '</thead>';
							tab += '<tbody id="subject_marksheet_table_body">';						
							tab += '</tbody></table>';
							div.innerHTML = tab;
							getSubjectMarksheetRows(x,pre_x);
						}else{
							div.innerHTML = "<br><br>No Records Defined";
						}
					}
				});
			}else{
				document.getElementById('view_submarks_div').style.visibility="hidden";
				document.getElementById('down_submarks_div').style.visibility="hidden";
				document.getElementById('marks_toggle_div').style.visibility="hidden";
				document.getElementById('grades_toggle_div').style.visibility="hidden";
			}
		}
		function getSubjectMarksheetRows(x,pre_x){
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			var role_list = document.getElementById('subject_marksheet_role_list');
			var role_id = role_list.value;
			var tbod = document.getElementById('subject_marksheet_table_body');
			//alert(getGradesForMarks(85,100));
			$.ajax({
				type: 'POST',
				url: 'get_marks_rows.php',
				data: {uid : uid, uname : uname, role_id : role_id},
				success: function(data) {
					var r=JSON.parse(data);
					var pre_leaves = new Array();
					var new_leaves = new Array();
					
					pre_leaves = getAllLeaves(pre_x);
					new_leaves = getAllLeaves(x);
					
					/*console.log("pre : "+pre_leaves);
					console.log("new : "+new_leaves);
					console.log(r.length);*/
					
					var tb = '';
					subMarksArray = [];
					for(var i=0;i<r.length;){
						var every_row = new Array();
						tb += '<tr>';
						tb += '<td id="s'+r[i][1]+'|cnull|pnull|mnull|tnull">'+r[i][3]+'</td>';
						tb +=  '<td class="firstMark" id="s'+r[i][1]+'|cnull|pnull|mnull|tnull">'+r[i][2]+'</td>';
						for(var j=0;j<new_leaves.length;j++){
							//console.log(i+" -> "+r[i]);
							if(i<r.length && r[i][4]==parseInt(new_leaves[j],10)){
								//var grade = getGradesForMarks(r[i][5],r[i][7]);
								var val = r[i][5]<=0?0:r[i][5];
								tb += '<td id="s'+r[i][1]+'|c'+r[i][4]+'|p'+r[i][6]+'|m'+r[i][7];
								if(getParentChild_count(x,r[i][6])>1){
									tb += '|t'+getTotalColOffset(j,r[i][4],new_leaves);
								}else{
									tb += '|t0';
								}
								//tb += '|g'+grade;
								tb += '|v'+val;
								tb += '">';
								tb += r[i][5]<=0?'-':r[i][5];
								tb += '</td>';
								every_row.push(r[i][5]);
								subMarksArray.push({val: val, maxmarks: r[i][7]});
								var prev_i = i;
								i++;
							}else/* if(parseInt(new_leaves[j],10)<0)*/{
								var par_id = parseInt(new_leaves[j],10)*(-1);
								var val = getImmediateChildMarksTotal(x,every_row,par_id);
								var colmax = getParentMaxmarks(x,par_id);
								//var grade = getGradesForMarks(val,colmax);
								tb += '<td class="totalCell" id="s'+r[prev_i][1]+'|c-'+par_id+'|p'+par_id+'|m'+colmax;
								tb += '|t'+getParentChild_count(x,par_id);
								//tb += '|g'+grade;
								tb += '|v'+(val>0?val:0);
								tb += '">';
								tb += val>0?val:0;
								tb += '</td>';
								subMarksArray.push({val: val, maxmarks: colmax});
							}							
						}
						tb += '</tr>';
					}
					tbod.innerHTML=tb;
				}
			});
		}
		function getGradesForMarks(val,maxmarks){
			getGradesFromDB(val,maxmarks,function(data){
				console.log("val : "+val+" max: "+maxmarks+" grade: "+data);
				alert(data);
				return data;
			});
		}
		function getGradesFromDB(val,maxmarks,callBack){
			$.ajax({
				type: 'POST',
				url: 'get_grades.php',
				data: {val : val, maxmarks : maxmarks},
				success: function(data) {
					return callBack(data);
				}
			});		
		}
		function showMarksGradesToggle(){
			var m_t = document.getElementById('sub_marks_toggle').checked;
			var g_t = document.getElementById('sub_grades_toggle').checked;
			var tab_body = document.getElementById('subject_marksheet_table_body');
			var num_rows = tab_body==null?0:tab_body.childNodes.length;
			var num_cols = tab_body.firstChild==null?0:tab_body.firstChild.childNodes.length;
			var row = tab_body.firstChild;
	
			var d;
			$.ajax({
				type: 'POST',
				url: 'get_grades_bulk.php',
				async: false,
				data: {markslist : array2json(subMarksArray)},
				success: function(data) {
					d = JSON.parse(data);
				}
			});
				
			if(!m_t && !g_t){
				for(var i=0;i<num_rows;i++){
					var col = row.firstChild.nextSibling.nextSibling; 
					for(var j=2;j<num_cols;j++){
						col.innerHTML = "";
						col = col.nextSibling;
					}
					row = row.nextSibling;
				}
			}else if(m_t && !g_t){
				for(var i=0;i<num_rows;i++){
					var col = row.firstChild.nextSibling.nextSibling; 
					for(var j=2;j<num_cols;j++){
						var id = col.id;
						col.innerHTML = id.split('|')[5].substring(1);
						col = col.nextSibling;
					}
					row = row.nextSibling;
				}
			}else if(!m_t && g_t){
				var count=0;
				for(var i=0;i<num_rows;i++){
					var col = row.firstChild.nextSibling.nextSibling; 
					for(var j=2;j<num_cols;j++){
						var id = col.id;
						col.innerHTML = d[count][0];
						col = col.nextSibling;
						count++;
					}
					row = row.nextSibling;
				}
			}else{
				var count=0;
				for(var i=0;i<num_rows;i++){
					var col = row.firstChild.nextSibling.nextSibling; 
					for(var j=2;j<num_cols;j++){
						var id = col.id;
						var val = parseFloat(id.split('|')[5].substring(1),10);
						col.innerHTML = val+" | "+d[count][0];
						col = col.nextSibling;
						count++;
					}
					row = row.nextSibling;
				}
			}
		}
		function submarks_view(){
			var role_list = document.getElementById('subject_marksheet_role_list');
			var role_id = role_list.value;
			if(role_id!=0){
				document.getElementById('subject_marksheet_table').style.visibility="visible";
				document.getElementById('subject_marksheet_table').style.display="block";
				if(document.getElementById('down_submarks_div').style.visibility=="visible"){
					document.getElementById('marks_toggle_div').style.visibility="visible";
					document.getElementById('grades_toggle_div').style.visibility="visible";
				}
			}
		}
		function submarks_down(){
			var role_list = document.getElementById('subject_marksheet_role_list');
			var role_name = role_list.options[role_list.options.selectedIndex].text;
			$('#subject_marksheet_main_table').tableExport({type:'excel',escape:'false',sheetname:role_name});			
		}
		
		
		
		
		
		
		
		
		
		
		function makeMarksEnter(){
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			$.ajax({
				type: 'POST',
				url: 'get_marks_enter_details.php',
				data: {uid : uid, uname : uname},
				success: function(data) {
					document.getElementById('marks_enter_div').innerHTML=data;
					document.getElementById('marks_enter_table_div').style.visibility="hidden";
					document.getElementById('marks_enter_table_div').style.display="none";
					document.getElementById('view_marks_div').style.visibility="hidden";
					document.getElementById('edit_marks_div').style.visibility="hidden";
					document.getElementById('save_marks_div').style.visibility="hidden";
					document.getElementById('lock_marks_div').style.visibility="hidden";
				}
			});
		}
		function enable_marks_enter_div(){
			var preview_div = document.getElementById('marks_enter_table_div');
			var role_list = document.getElementById('enter_marks_role_list');
			var role_id = role_list.value;
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			document.getElementById('marks_enter_table_div').style.visibility="hidden";
			document.getElementById('marks_enter_table_div').style.display="none";
			$.ajax({
				type: 'POST',
				url: 'get_report_preview.php',
				data: {uid : uid, uname : uname, role_id : role_id},
				success: function(data) {
					var x=JSON.parse(data);
					check_marks_progress_stat(role_id);
					if(x.length!=0){
						var pre_x = JSON.parse(data);
						x = addTotalColumn(x);
						x = cleanXOffset(x);
						var netLev = getNumLevels(x);
						var lev_off = new Array(netLev+1);
						var tab = '<table class="grade_table" id="mark_enter_table"><thead>';
						for(var lev=0;lev<=netLev;lev++){
							lev_off[lev]=0;
							tab += '<tr>';
							if(lev==0){
								tab += '<th rowspan="'+(netLev+1)+'" colspan="1" id="i-2|c0|a0|o-2|m-1|pnull">Student Roll Number</th>';
								tab += '<th rowspan="'+(netLev+1)+'" colspan="1" id="i-1|c0|a0|o-1|m-1|pnull">Student Names</th>';
							}
							for(var i=0;i<x.length;i++){
								if(x[i]['level']==lev){
									tab += '<th id="i'+x[i]['column_id']+'|c'+x[i]['child_count']+'|a'+getAllChildrenCount(x,i,0)+'|o'+x[i]['x_offset']+'|m'+x[i]['maxmarks']+'|p'+x[i]['parent_id']+'" ';
									tab += 'colspan="'+(getAllChildrenCount(x,i,0)>1?getAllChildrenCount(x,i,0):getAllChildrenCount(x,i,0))+'" ';
									tab += 'rowspan="'+(x[i]['child_count']==0?(netLev-lev+1):(1))+'" ';
									if(x[i]['column_name']=='Total'){
										tab += 'class="totalCol" ';
									}
									tab += '>';
									tab += x[i]['column_name'];
									tab += '<br />( '+x[i]['maxmarks']+' )';
									tab += '</th>';
									lev_off[x[i]['level']] = x[i]['x_offset'] + getAllChildrenCount(x,i,0);
								}
							}
							tab += '</tr>';
						}
						tab += '</thead>';
						tab += '<tbody id="marks_enter_table_body">';						
						tab += '</tbody></table>';
						preview_div.innerHTML = tab;
						getMarksEnterRows(x,pre_x);
					}else{
						preview_div.innerHTML = "No structure Defined";
						make_marks_progress(0);
					}
				}
			});
		}
		function getMarksEnterRows(x,pre_x){
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			var tbod = document.getElementById('marks_enter_table_body');
			var role_list = document.getElementById('enter_marks_role_list');
			var role_id = role_list.value;
			$.ajax({
				type: 'POST',
				url: 'get_marks_rows.php',
				data: {uid : uid, uname : uname, role_id : role_id},
				success: function(data) {
					var r=JSON.parse(data);
					var pre_leaves = new Array();
					var new_leaves = new Array();
					
					pre_leaves = getAllLeaves(pre_x);
					new_leaves = getAllLeaves(x);
					
					/*console.log("pre : "+pre_leaves);
					console.log("new : "+new_leaves);
					console.log(r.length);*/
					
					var tb = '';
					for(var i=0;i<r.length;){
						var every_row = new Array();
						tb += '<tr>';
						tb += '<td id="s'+r[i][1]+'|cnull|pnull|mnull|tnull">'+r[i][3]+'</td>';
						tb +=  '<td class="firstMark" id="s'+r[i][1]+'|cnull|pnull|mnull|tnull">'+r[i][2]+'</td>';
						for(var j=0;j<new_leaves.length;j++){
							//console.log(i+" -> "+r[i]);
							if(i<r.length && r[i][4]==parseInt(new_leaves[j],10)){
								tb += '<td id="s'+r[i][1]+'|c'+r[i][4]+'|p'+r[i][6]+'|m'+r[i][7];
								if(getParentChild_count(x,r[i][6])>1){
									tb += '|t'+getTotalColOffset(j,r[i][4],new_leaves);
								}else{
									tb += '|t0';
								}
								tb += '">';
								tb += r[i][5]<=0?'-':r[i][5];
								tb += '</td>';
								every_row.push(r[i][5]);
								var prev_i = i;
								i++;
							}else/* if(parseInt(new_leaves[j],10)<0)*/{
								var par_id = parseInt(new_leaves[j],10)*(-1);
								var val = getImmediateChildMarksTotal(x,every_row,par_id);
								tb += '<td class="totalCell" id="s'+r[prev_i][1]+'|c-'+par_id+'|p'+par_id+'|m-1';
								tb += '|t'+getParentChild_count(x,par_id);
								tb += '">';
								tb += val>0?val:0;
								tb += '</td>';
							}							
						}
						tb += '</tr>';
					}
					tbod.innerHTML=tb;
				}
			});
		}
		function getNetColUnder(x,i,c){
			var sum = 0;
			if(x[i]['child_count']==0)
				return 1;
			else{
				var child_arr = getChildIndexArr(x,i);
				for(var j=0;j<child_arr.length;j++){
					c += getNetColUnder(x,child_arr[j],c);
				}
				return c;
			}
		}
		function getTotalColOffset(j,id,new_leaves){
			var count=0;
			var flag=1;
			for(var i=j;i<new_leaves.length && flag==1;i++){
				if(new_leaves[i]<0)
					flag=0;
				else	
					count++;					
			}
			return count==(new_leaves.length-j)?0:count;
		}
		function getImmediateChildMarksTotal(x,r,id){
			var sum =0;
			var netCol = x.length;
			for(var i=0;i<netCol;i++){
				if(x[i]['column_id']==id){
					var children = x[i]['child_count'];
					for(var j=(r.length-1);children>0;j--){
						sum += parseFloat(r[j],10);
						children--;
					}
				}
			}
			return Math.ceil(sum);
		}
		function getAllLeaves(x){
			var leaves = new Array();
			var netCol = x.length;
			for(var i=0;i<netCol;i++){
				if(x[i]['child_count']==0){
					leaves.push(x[i]['column_id']);
				}
			}
			return leaves;
		}
		function addTotalColumn(x){
			//console.log(x);
			var netCol = x.length;
			for(var i=0;i<netCol;i++){
				if(x[i]['child_count']>1){
					var newCol = new Object();
					newCol['child_count']=0;
					newCol['column_id']=(-1*parseInt(x[i]['column_id'],10)).toString();
					newCol['column_name']="Total";
					newCol['level']=parseInt(x[i]['level'],10)+1;
					newCol['maxmarks']=x[i]['maxmarks'];
					newCol['parent_id']=x[i]['column_id'];
					newCol['x_offset']=parseInt(getParentChild_count(x,x[i]['column_id']),10)+1;
					//console.log((i+1+getAllChildrenCount(x,i,0)));
					//console.log("parent : "+x[i]['column_name']+" all_c : "+getAllChildrenCount(x,i,0)+"  I: "+i);
					x.splice((i+1+getNetColUnder(x,i,0)),0,newCol);
					//console.log("parent : "+x[i]['column_name']+" all_c : "+getAllChildrenCount(x,i,0));
					
				}
				
			}
			//console.log(x);
			return x;
		}
		function marks_view(){
			var role_list = document.getElementById('enter_marks_role_list');
			var role_id = role_list.value;
			if(role_id!=0 && document.getElementById('marks_enter_table_div').style.display!="block"){
				document.getElementById('marks_enter_table_div').style.visibility="visible";
				document.getElementById('marks_enter_table_div').style.display="block";
			}
		}
		$("#marks_enter_table_div").keydown(function(event) { 
			if((event.keyCode>95 && event.keyCode<106)||(event.keyCode>47 && event.keyCode<58)||event.keyCode==190||event.keyCode==110||event.keyCode==8||event.keyCode==9|| event.keyCode==46||(event.keyCode>36 && event.keyCode<41)||event.keyCode==65)
				return true;
			else
				return false;
		});
		function marks_edit(){
			var role_list = document.getElementById('enter_marks_role_list');
			var role_id = role_list.value;
			if(role_id!=0 && document.getElementById('marks_enter_table_div').style.display!="none"){
				var tab_body = document.getElementById('marks_enter_table_body');
				var num_rows = tab_body==null?0:tab_body.childNodes.length;
				var num_cols = tab_body.firstChild==null?0:tab_body.firstChild.childNodes.length;
				var row = tab_body.firstChild;
				for(var i=0;i<num_rows;i++){
					var col = row.firstChild.nextSibling.nextSibling; 
					for(var j=2;j<num_cols;j++){
						var data = col.innerHTML;
						var id = col.id;
						var id_arr = id.split('|');
						if(id_arr[1].charAt(1)!='-'){
							col.contentEditable=true;
							col.className = "editmode";
							col.addEventListener("keydown",function(e){
								if(this.innerHTML=='-')
									this.innerHTML="";
							});
							col.addEventListener("keyup", function(e){
								this.className = "edited";
								var new_val = this.innerHTML;
								var new_id = this.id;
								var new_id_arr = new_id.split('|');
								//if(new_val!='-'){
									if(parseFloat(this.innerHTML,10)>parseInt((new_id_arr[3]).substring(1),10)){
										showFailureMsg("<p>Warning</p><p>You have exceed the columns maximum marks limit</p>");
										this.innerHTML = '-';
									}else if(parseFloat(this.innerHTML,10)<0){
										showFailureMsg("<p>Warning</p><p>You cannot give negative marks</p>");
										this.innerHTML = '-';
									}else if(this.innerHTML.trim()==''){
										this.innerHTML='-';
									}else if(this.innerHTML.trim()=='a'){
										this.innerHTML='A';
									}else if(isNaN(this.innerHTML)){
										//showFailureMsg("<p>Warning</p><p>Enter only digits</p>");
										//this.innerHTML = '-';
										
									}else{
										if(parseInt(new_id_arr[4].substring(1),10)!=0){
											var total_pos = parseInt(new_id_arr[4].substring(1),10);
											var currentCol = this;
											for(var k=0;k<total_pos;k++){
												currentCol = currentCol.nextSibling;
											}
											var totCol = currentCol;
											//console.log();
											var old_tot = parseFloat(currentCol.innerHTML,10);
											var sib = parseInt(currentCol.id.split('|')[4].substring(1),10);
											var sum = 0;
											for(k=0;k<sib;k++){
												currentCol = currentCol.previousSibling;
												sum += parseFloat(((currentCol.innerHTML!='-')?currentCol.innerHTML:0),10);
											}
											if(parseFloat(this.innerHTML,10)>=0){
												totCol.innerHTML = sum;
											}
										}
									}
								//}
							});
						}
						col = col.nextSibling;
					}
					row = row.nextSibling;
				}
			}
		}
		function marks_save(){
			alert("safe");
			var role_list = document.getElementById('enter_marks_role_list');
			var role_id = role_list.value;
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			var tab_head = createHeadersArray();
			//console.log(tab_head);
			
			var tab_body = createMarksArray();
			console.log(tab_body);
			if(tab_body!=null){
				$.ajax({
					type: 'POST',
					url: 'save_marks.php',
					data: {uid : uid, uname : uname, tab_head : tab_head, tab_body : tab_body, role_id : role_id},
					success: function(data) {
						console.log(data);
						makeMarksEnter();
					}
				});	
			}else{
				showFailureMsg("<p>Save Warning</p><p>You cant leave cells blank (highlighted in red)</p>");
			}
		}
		function createHeadersArray(){
			var role_list = document.getElementById('enter_marks_role_list');
			var role_id = role_list.value;
			var div = document.getElementById('marks_enter_table_div');
			var jsonHead = [];
			var tab_head = div.firstChild.firstChild;
			var num_rows = tab_head==null?0:tab_head.childNodes.length;
			
			var row = tab_head.firstChild;
			
			
			for(var i=0;i<num_rows;i++){
				var num_cols = row==null?0:row.childNodes.length;
				var col = row.firstChild;
				
				for(var j=0;j<num_cols;j++){
					var id = col.id;
					var id_arr = id.split('|');
					if(col.className!='totalCol'){
						jsonHead.push({
							role_id: role_id,
							column_id: id_arr[0].substring(1),
							column_name: col.innerHTML,
							child_count: id_arr[1].substring(1),
							ancestry: id_arr[2].substring(1),
							x_offset: id_arr[3].substring(1),
							maxmarks: id_arr[4].substring(1),
							parent_id: id_arr[5].substring(1),
							colspan: col.colSpan,
							rowspan: col.rowSpan
						});
					}
					col = col.nextSibling;
				}
				
				row = row.nextSibling;
			}
			return array2json(jsonHead);
		}
		function createMarksArray(){
			var role_list = document.getElementById('enter_marks_role_list');
			var role_id = role_list.value;
			var div = document.getElementById('marks_enter_table_div');
			var tab_body = div.firstChild.firstChild.nextSibling;
			var num_rows = tab_body==null?0:tab_body.childNodes.length;
			var num_cols = tab_body.firstChild==null?0:tab_body.firstChild.childNodes.length;
			var flag = 0;
			
			var row = tab_body.firstChild;
			var jsonBody = [];
			
			for(var i=0;i<num_rows;i++){
				var num_cols = row.childNodes.length;
				var col = row.firstChild;
				var innerJson = [];
				
				for(var j=0;j<num_cols;j++){
					var id = col.id;
					var id_arr = id.split('|');
					if(col.className!='totalCell'){
						if(col.innerHTML=='-'){
							col.className='edited';
							flag=1;
						}else{
							col.className='';
							jsonBody.push({
								role_id: role_id,
								student_id: id_arr[0].substring(1),
								column_id: id_arr[1].substring(1),
								parent_id: id_arr[2].substring(1),
								maxmarks: id_arr[3].substring(1),
								total_offset: id_arr[4].substring(1),
								cell_value: col.innerHTML,
								colspan: col.colSpan,
								rowspan: col.rowSpan
							});
						}
					}
					col = col.nextSibling;
				}
				/*jsonBody.push({
					row: innerJson
				});*/
				row = row.nextSibling;
			}
			if(flag==0)
				return array2json(jsonBody);
			else
				return null;
		}
		function array2json(arr) {
			var parts = [];
			var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');
		
			for(var key in arr) {
				var value = arr[key];
				if(typeof value == "object") { //Custom handling for arrays
					if(is_list) parts.push(array2json(value)); /* :RECURSION: */
					else parts.push('"' + key + '":' + array2json(value)); /* :RECURSION: */
					//else parts[key] = array2json(value); /* :RECURSION: */
					
				} else {
					var str = "";
					if(!is_list) str = '"' + key + '":';
		
					//Custom handling for multiple data types
					if(typeof value == "number") str += value; //Numbers
					else if(value === false) str += 'false'; //The booleans
					else if(value === true) str += 'true';
					else str += '"' + value + '"'; //All other things
					// :TODO: Is there any more datatype we should be in the lookout for? (Functions?)
		
					parts.push(str);
				}
			}
			var json = parts.join(",");
			
			if(is_list) return '[' + json + ']';//Return numerical JSON
			return '{' + json + '}';//Return associative JSON
		}
		function marks_lock(){
			var role_list = document.getElementById('enter_marks_role_list');
			var role_id = role_list.value;
			var div = document.getElementById('marks_enter_table_div');
			var tab_body = div.firstChild.firstChild.nextSibling;
			var num_rows = tab_body==null?0:tab_body.childNodes.length;
			
			var flag = 0;
			var row = tab_body.firstChild;
			for(var i=0;i<num_rows;i++){
				var num_cols = row==null?0:row.childNodes.length;
				var col = row.firstChild;
			
				for(var j=0;j<num_cols;j++){
					var id = col.id;
					var id_arr = id.split('|');
					if(col.className!='totalCell'){
						if(col.innerHTML=='-'){
							col.className='edited';
							flag=1;
						}else{
							col.className='';
						}
					}
					col = col.nextSibling;
				}
				row = row.nextSibling;
			}
			if(flag==0){
				make_marks_progress(3);	
				makeMarksEnter();	
				check_marks_progress_stat(role_id);
			}else
				showFailureMsg("<p>Save Warning</p><p>You cant leave cells blank </p><p>(highlighted in red)</p>");
		}
		function check_marks_progress_stat(role_id){
			if(role_id!=0){
				$.ajax({
					type: 'POST',
					url: 'get_marks_progress.php',
					data: {role_id : role_id},
					success: function(data) {
						var x=data;
						if(x==3){
							document.getElementById('view_marks_div').style.visibility="visible";
							document.getElementById('edit_marks_div').style.visibility="hidden";
							document.getElementById('save_marks_div').style.visibility="hidden";
							document.getElementById('lock_marks_div').style.visibility="visible";
							document.getElementById('lock_marks_div').firstChild.innerHTML="Locked";
							document.getElementById('lock_marks_div').firstChild.nextSibling.firstChild.className="fa fa-lock fa-2x faa-burst animated-hover";
							document.getElementById('lock_marks_div').onclick=function(){showFailureMsg("<p>Warning</p><p>Marksheet already locked</p><p>Contact admin to unlock it</p>");};
						}else if(x>=0 && x<=2){
							document.getElementById('view_marks_div').style.visibility="visible";
							document.getElementById('edit_marks_div').style.visibility="visible";
							document.getElementById('save_marks_div').style.visibility="visible";
							document.getElementById('lock_marks_div').style.visibility="visible";
							document.getElementById('lock_marks_div').firstChild.innerHTML="Lock";
							document.getElementById('lock_marks_div').firstChild.nextSibling.firstChild.className="fa fa-unlock fa-2x faa-burst animated-hover";
						}else{
							showFailureMsg("<p>Error</p><p>An error has occured.... please refresh the page</p>");
						}
					}
				});
			}else{
				document.getElementById('view_marks_div').style.visibility="hidden";
				document.getElementById('edit_marks_div').style.visibility="hidden";
				document.getElementById('save_marks_div').style.visibility="hidden";
				document.getElementById('lock_marks_div').style.visibility="hidden";
			}
		}
		function make_marks_progress(val){
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			var role_list = document.getElementById('enter_marks_role_list');
			var role_id = role_list.value;
			$.ajax({
				type: 'POST',
				url: 'make_marks_progress.php',
				data: {uid : uid, uname : uname, val : val, role_id : role_id},
				success: function(data) {
					
				}
			});			
		}
		</script>
        
        <script>
		function showFailureMsg(msg){
			$("#failure_form").addClass('submited');
			document.getElementById('failure_message').innerHTML=msg;
			jQuery("#mainbody").append('<div id="failure_form_overlay" class="sky-form-modal-overlay"></div>');
			stat_form = $('#failure_form');
			$('#failure_form_overlay').fadeIn();
			stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
			setTimeout(function(){
				$('#failure_form_overlay').fadeOut();
				$('.sky-form-modal').fadeOut();
			},2000)
		}
		function showSuccessMsg(msg){
			$("#success_form").addClass('submited');
			document.getElementById('success_message').innerHTML=msg;
			jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
			stat_form = $('#success_form');
			$('#success_form_overlay').fadeIn();
			stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
			setTimeout(function(){
				$('#success_form_overlay').fadeOut();
				$('.sky-form-modal').fadeOut();
			},2000)
		}
        </script>
        <script>
		function enable_create_options(){
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			var role_list = document.getElementById('create_struct_role_list');
			var role_name = role_list.options[role_list.selectedIndex].text;
			var role_id = role_list.value;
				
			if(role_id!=0){
				$.ajax({
					type: 'POST',
					url: 'get_create_options.php',
					data: {uid : uid, uname : uname, role_id : role_id},
					success: function(data) {
						check_struct_progress_stat(role_id);
						document.getElementById('create_struct_options_div').innerHTML=data;
						document.getElementById('create_struct_options_div').style.visibility="hidden";
						document.getElementById('create_struct_options_div').style.display="none";
						document.getElementById('col_struct_edit_options_div').style.visibility="";
						document.getElementById('col_struct_edit_options_div').style.display="none";
						make_add_col_main();
						make_rem_col_main();
					}
				});
			}
		}
		function make_add_col_main(){
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			$.ajax({
				type: 'POST',
				url: 'get_add_col_main.php',
				data: {uid : uid, uname : uname, role_id : role_id},
				success: function(data) {
					var x=JSON.parse(data);
					var mainBox = document.getElementById('add_col_par_list');
					mainBox.options.length = 0;
					if(x.length==0){
						mainBox.options[mainBox.options.length] = new Option('Choose a Column',0);
						mainBox.options[mainBox.options.length] = new Option('Base',-1);
					}else{
						mainBox.options[mainBox.options.length] = new Option('Choose a Column',0);
						mainBox.options[mainBox.options.length] = new Option('Base',-1);
						for(var i=0;i<x.length;i++){
							mainBox.options[mainBox.options.length] = new Option(x[i][1], x[i][0]);
						}
					}
				}
			});
		}
		function make_rem_col_main(){
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			$.ajax({
				type: 'POST',
				url: 'get_add_col_main.php',
				data: {uid : uid, uname : uname, role_id : role_id},
				success: function(data) {
					var x=JSON.parse(data);
					var mainBox = document.getElementById('rem_col_par_list');
					mainBox.options.length = 0;
					if(x.length==0){
						mainBox.options[mainBox.options.length] = new Option('No Column',0);
					}else{
						mainBox.options[mainBox.options.length] = new Option('Choose a Column',0);
						mainBox.options[mainBox.options.length] = new Option('Base',-1);
						for(var i=0;i<x.length;i++){
							mainBox.options[mainBox.options.length] = new Option(x[i][1], x[i][0]);
						}
					}
				}
			});
		}
		function struct_view(){
			var role_list = document.getElementById('create_struct_role_list');
			var preview_div = document.getElementById('col_struct_preview');
			var role_id = role_list.value;
			if(role_id!=0 && document.getElementById('col_struct_edit_options_div').style.display!="block"){
				document.getElementById('create_struct_options_div').style.visibility="visible";
				document.getElementById('create_struct_options_div').style.display="block";
				document.getElementById('col_struct_edit_options_div').style.visibility="";
				document.getElementById('col_struct_edit_options_div').style.display="none";
				populate_preview();
			}
		}
		function struct_edit(){
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			if(role_id!=0){
				document.getElementById('create_struct_options_div').style.visibility="visible";
				document.getElementById('create_struct_options_div').style.display="block";
				document.getElementById('col_struct_edit_options_div').style.visibility="visible";
				document.getElementById('col_struct_edit_options_div').style.display="block";
				populate_preview();
			}
		}
		function struct_save(){
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			$.ajax({
				type: 'POST',
				url: 'get_report_preview.php',
				data: {uname : uname, uid : uid, role_id : role_id},
				success: function(data) {
					var x=JSON.parse(data);
					var tab_head = document.getElementById('col_struct_preview').firstChild.firstChild;
					var num_rows = tab_head==null?0:tab_head.childNodes.length;
					var flag = 0;
					for(var i=0;i<x.length;i++){
						if(x[i]['child_count']>0){
							if(check_col_total_balanced(x,i)!=parseInt(x[i]['maxmarks'],10)){
								var row = tab_head.firstChild;
								for(var j=0;j<num_rows;j++){
									var num_cols = row==null?0:row.childNodes.length;
									var col = row.firstChild;
									for(var k=0;k<num_cols;k++){
										if(col.id.substring(1)==x[i]['column_id']){
											col.className="edited";
											flag++;
										}
										col = col.nextSibling;
									}
									row = row.nextSibling;
								}
							}
						}
					}
					
					if(flag==0){
						showSuccessMsg("<p>Success</p><p>Your Structure has been successfully saved</p>");
						document.getElementById('lock_struct_div').firstChild.nextSibling.firstChild.onclick = function(){struct_lock();};
					}else{
						showFailureMsg("<p>Warning</p><p>The Columns highlighted in red, dont have sub columns that add up to the parent.</p><p>Structure will be still saved.</p>");
						document.getElementById('lock_struct_div').firstChild.nextSibling.firstChild.onclick = function(){showFailureMsg("<p>Error</p><p>You need to save your structure first</p>");};
					}
					
					make_struct_progress(2);
				}
			});
			
		}
		function check_col_total_balanced(x,id){
			var sum = 0;
			if(x[id]['child_count']==0){
				return parseInt(x[id]['maxmarks'],10);
			}else{
				var children = getChildIndexArr(x,id);
				for(j=0;j<children.length;j++){
					sum = sum + check_col_total_balanced(x,children[j]);
				}
				return sum;
			}
		}
		function struct_lock(){
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			
			$.ajax({
				type: 'POST',
				url: 'lock_struct.php',
				data: {uname : uname, uid : uid, role_id : role_id},
				success: function(data) {
					if(data=="error"){
						showFailureMsg("<p>Failure</p><p>Locking Failed.</p><p>Make Sure Admin has added the respective student list</p>");
					}else if(data=="done"){
						showSuccessMsg("<p>Success</p><p>Marksheet Structure Successfully locked</p>");
						make_struct_progress(3);
						makeCreateStructure();
						check_struct_progress_stat(role_id);
					}
				}
			});
			
			
			/*$.ajax({
				type: 'POST',
				url: 'get_report_preview.php',
				data: {uname : uname, uid : uid, role_id : role_id},
				success: function(data) {
					var x=JSON.parse(data);
			
					var tab_head = document.getElementById('col_struct_preview').firstChild.firstChild;
					var num_rows = tab_head==null?0:tab_head.childNodes.length;
					var flag = 0;
					for(var i=0;i<x.length;i++){
						if(x[i]['child_count']>0){
							if(check_col_total_balanced(x,i)!=parseInt(x[i]['maxmarks'],10)){
								var row = tab_head.firstChild;
								for(var j=0;j<num_rows;j++){
									var num_cols = row==null?0:row.childNodes.length;
									var col = row.firstChild;
									for(var k=0;k<num_cols;k++){
										if(col.id.substring(1)==x[i]['column_id']){
											col.className="edited";
											flag++;
										}
										col = col.nextSibling;
									}
									row = row.nextSibling;
								}
							}
						}
					}
					
					if(flag==0){
						$.ajax({
							type: 'POST',
							url: 'lock_struct.php',
							data: {uname : uname, uid : uid, role_id : role_id},
							success: function(data) {
								if(data=="non_terminal"){
									showFailureMsg("<p>Failure</p><p>Locking Failed.</p>");
								}else if(data=="done"){
									showSuccessMsg("<p>Success</p><p>Marksheet Structure Successfully locked</p>");
									make_struct_progress(3);
									makeCreateStructure();
									check_struct_progress_stat(role_id);
								}
							}
						});
						
					}else{
						showFailureMsg("<p>Error</p><p>The Columns highlighted in red, dont have sub columns that add up to the parent.</p><p>Structure not saved.</p>");
					}
				}		
			});*/
		}
		function make_add_child_list(){
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			var par_list = document.getElementById('add_col_par_list');
			var par_id = par_list.value;
			if(par_id!=0){
				$.ajax({
					type: 'POST',
					url: 'get_add_child_list.php',
					data: {uid : uid, uname : uname, par_id : par_id, role_id : role_id},
					success: function(data) {
						var x=JSON.parse(data);
						var childBox = document.getElementById('add_col_child_list');
						childBox.options.length = 0;
						if(x.length==0){
							childBox.options[childBox.options.length] = new Option('No Sub-Column',0);
						}else{
							childBox.options[childBox.options.length] = new Option('Choose Sub-Column',0);
							for(var i=0;i<x.length;i++){
								childBox.options[childBox.options.length] = new Option(x[i][1], x[i][0]);
							}
						}
					}
				});
			}
		}
		function make_rem_child_list(){
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			var par_list = document.getElementById('rem_col_par_list');
			var par_id = par_list.value;
			if(par_id!=0){
				$.ajax({
					type: 'POST',
					url: 'get_rem_child_list.php',
					data: {uid : uid, uname : uname, par_id : par_id, role_id : role_id},
					success: function(data) {
						var x=JSON.parse(data);
						var childBox = document.getElementById('rem_col_child_list');
						childBox.options.length = 0;
						if(x.length==0){
							childBox.options[childBox.options.length] = new Option('No Sub-Column',0);
						}else{
							childBox.options[childBox.options.length] = new Option('Choose Sub-Column',0);
							for(var i=0;i<x.length;i++){
								childBox.options[childBox.options.length] = new Option(x[i][1], x[i][0]);
							}
						}
					}
				});
			}
		}
		function add_col_struct(){
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			var child_list = document.getElementById('add_col_child_list');
			var child_id = child_list.value;
			var par_list = document.getElementById('add_col_par_list');
			var par_id = par_list.value;
			
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			var max_marks = document.getElementById('add_col_marks').value;
			if(max_marks!='' && child_id!=0 && par_id!=0){
				$.ajax({
					type: 'POST',
					url: 'add_col_struct.php',
					data: {uid : uid, uname : uname, role_id : role_id, child_id : child_id, max_marks : max_marks, par_id : par_id},
					success: function(data) {
						if(data=="marks_excess"){
							showFailureMsg("<p>Failure</p><p>You have exceeded the total marks limit of your immediate parent column</p>");
						}else if(data=="done"){
							showSuccessMsg("<p>Success</p><p>Column Added</p>");
						}
						populate_preview();
						make_add_col_main();
						make_rem_col_main();
						child_list.selectedIndex = 0;
						par_list.selectedIndex = 0;
						document.getElementById('rem_col_par_list').selectedIndex = 0;
						document.getElementById('add_col_marks').value = '';
					}
				});
			}
		}
		function rem_col_struct(){
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			var child_list = document.getElementById('rem_col_child_list');
			var child_id = child_list.value;
			var par_list = document.getElementById('rem_col_par_list');
			var par_id = par_list.value;
			
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			
			if(child_id!=0 && par_id!=0){
				$.ajax({
					type: 'POST',
					url: 'rem_col_struct.php',
					data: {uid : uid, uname : uname, role_id : role_id, child_id : child_id, par_id : par_id},
					success: function(data) {
						if(data=="non_terminal"){
							showFailureMsg("<p>Failure</p><p>You cant remove a column without removing all its sub columns first.</p>");
						}else if(data=="done"){
							showSuccessMsg("<p>Success</p><p>Column Removed</p>");
						}
						populate_preview();
						make_add_col_main();
						make_rem_col_main();
						child_list.selectedIndex = 0;
						par_list.selectedIndex = 0;
						document.getElementById('add_col_par_list').selectedIndex = 0;
					}
				});
			}
		}
		function populate_preview(){
			var preview_div = document.getElementById('col_struct_preview');
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			$.ajax({
				type: 'POST',
				url: 'get_report_preview.php',
				data: {uid : uid, uname : uname, role_id : role_id},
				success: function(data) {
					var x=JSON.parse(data);
					if(x.length!=0){
						x = cleanXOffset(x);
						var netLev = getNumLevels(x);
						var lev_off = new Array(netLev+1);
						var tab = '<table class="grade_table"><thead>';
						for(var lev=0;lev<=netLev;lev++){
							lev_off[lev]=0;
							tab += '<tr>';
							for(var i=0;i<x.length;i++){
								if(x[i]['level']==lev){
									tab += '<th id="c'+x[i]['column_id'];
									tab += '" colspan="'+getAllChildrenCount(x,i,0);
									tab += '" rowspan="'+(x[i]['child_count']==0?(netLev-lev+1):(1));
									tab += '">';
									tab += x[i]['column_name'];
									tab += '<br />( '+x[i]['maxmarks']+' )';
									tab += '</th>';
									lev_off[x[i]['level']] = x[i]['x_offset'] + getAllChildrenCount(x,i,0);
								}
							}
							tab += '</tr>';
						}
						tab += '</thead></table>';
						preview_div.innerHTML = tab;
					}else{
						preview_div.innerHTML = "No structure Defined";
						make_struct_progress(0);
					}
				}
			});
		}
		function cleanXOffset(x){
			var netLev = getNumLevels(x);
			var lev_off = new Array(netLev+1);
			for(var lev=0;lev<=netLev;lev++){
				lev_off[lev]=0;
			}
			for(var i=0;i<x.length;i++){
			  var my_off = getParentXOffset(x,x[i]['parent_id'])>lev_off[x[i]['level']] ? getParentXOffset(x,x[i]['parent_id']) : lev_off[x[i]['level']];
				x[i]['x_offset'] = my_off;
				lev_off[x[i]['level']] = x[i]['x_offset'] + getAllChildrenCount(x,i,0);
			}	
			return x;
		}
		function getParentChild_count(x,par_id){
			if(par_id!=null){
				for(var i=0;i<x.length;i++){
					if(x[i]['column_id']==par_id)
						return x[i]['child_count'];
				}	
			}else{
				return 0;
			}
		}
		function getParentMaxmarks(x,par_id){
			if(par_id!=null){
				for(var i=0;i<x.length;i++){
					if(x[i]['column_id']==par_id)
						return x[i]['maxmarks'];
				}	
			}else{
				return 0;
			}
		}
		function getParentXOffset(x,par_id){
			if(par_id!=null){
				for(var i=0;i<x.length;i++){
					if(x[i]['column_id']==par_id)
						return x[i]['x_offset'];
				}	
			}else{
				return 0;
			}
		}
		function getNumLevels(x){
			var lev = 0; 
			for(var i=0;i<x.length;i++){
				if(x[i]['level']>=lev)
					lev=x[i]['level'];
			}
			return lev;
		}
		function getAllChildrenCount(x,i,c){
			var sum = 0;
			if(x[i]['child_count']==0)
				return 1;
			else{
				var child_arr = getChildIndexArr(x,i);
				for(var j=0;j<child_arr.length;j++){
					var res = getAllChildrenCount(x,child_arr[j], c);
					sum = sum + res;
				}
				
				
				/*var c_base = i;
				for(var j=0;j<x[i]['child_count'];j++){
					//console.log("col : "+x[][]);
					var res = getAllChildrenCount(x,c_base+1, c);
					c = c + res;
					c_base = c_base +res;
					//j += (getAllChildrenCount(x,j+i+1, c))+1;
				}*/
				return sum;
			}
		}
		function getChildIndexArr(x,id){
			var c_arr = new Array();
			for(var i=0;i<x.length;i++){
				if(x[i]['parent_id']==x[id]['column_id']){
					c_arr.push(i);
				}
			}
			return c_arr;
		}
		function makeCreateStructure(){
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			$.ajax({
				type: 'POST',
				url: 'get_create_structure_details.php',
				data: {uid : uid, uname : uname},
				success: function(data) {
					document.getElementById('create_struct_div').innerHTML=data;
					document.getElementById('create_struct_options_div').style.display="none";
					document.getElementById('create_struct_options_div').style.visibility="hidden";
					document.getElementById('view_struct_div').style.visibility="hidden";
					document.getElementById('edit_struct_div').style.visibility="hidden";
					document.getElementById('save_struct_div').style.visibility="hidden";
					document.getElementById('lock_struct_div').style.visibility="hidden";
					document.getElementById('lock_struct_div').firstChild.nextSibling.firstChild.onclick = function(){showFailureMsg("<p>Error</p><p>You need to save your structure first</p>");};
				}
			});
		}
		function check_struct_progress_stat(role_id){
			if(role_id!=0){
				$.ajax({
					type: 'POST',
					url: 'get_struct_progress.php',
					data: {role_id : role_id},
					success: function(data) {
						var x=data;
						if(x==3){
							document.getElementById('view_struct_div').style.visibility="visible";
							document.getElementById('edit_struct_div').style.visibility="hidden";
							document.getElementById('save_struct_div').style.visibility="hidden";
							document.getElementById('lock_struct_div').style.visibility="visible";
							document.getElementById('lock_struct_div').firstChild.innerHTML="Locked";
							document.getElementById('lock_struct_div').firstChild.nextSibling.firstChild.className="fa fa-lock fa-2x faa-burst animated-hover";
							document.getElementById('lock_struct_div').onclick=function(){showFailureMsg("<p>Warning</p><p>Marksheet Structure already locked</p><p>Contact admin to unlock it</p>");};
						}else if(x>=0 && x<=2){
							document.getElementById('view_struct_div').style.visibility="visible";
							document.getElementById('edit_struct_div').style.visibility="visible";
							document.getElementById('save_struct_div').style.visibility="visible";
							document.getElementById('lock_struct_div').style.visibility="visible";
							document.getElementById('lock_struct_div').firstChild.innerHTML="Lock";
							document.getElementById('lock_struct_div').firstChild.nextSibling.firstChild.className="fa fa-unlock fa-2x faa-burst animated-hover";
						}else{
							showFailureMsg("<p>Error</p><p>An error has occured.... please refresh the page</p>");
						}
					}
				});
			}else{
				document.getElementById('view_struct_div').style.visibility="hidden";
				document.getElementById('edit_struct_div').style.visibility="hidden";
				document.getElementById('save_struct_div').style.visibility="hidden";
				document.getElementById('lock_struct_div').style.visibility="hidden";
			}
		}
		function make_struct_progress(val){
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			var role_list = document.getElementById('create_struct_role_list');
			var role_id = role_list.value;
			$.ajax({
				type: 'POST',
				url: 'make_struct_progress.php',
				data: {uid : uid, uname : uname, val : val, role_id : role_id},
				success: function(data) {
					
				}
			});			
		}
		</script>
        <script>
		function makeDashboard(){
			var uname = document.getElementById('get_uname').value;
			var uid = document.getElementById('get_uid').value;
			$.ajax({
				type: 'POST',
				url: 'get_dashboard_details.php',
				data: {uid : uid, uname : uname},
				success: function(data) {
					document.getElementById('dashboard_div').innerHTML=data;
				}
			});
		}
		</script>
        <script>
		$(function(){
        $("#change_email_form").validate(
            {
                rules:
                {
                    email:
                    {
                        required: true,
                        email: true,
						remote: {
							url: "live_email_check.php",
							type: "post"
						}
                    }
                },
                messages:
                {
                    email:
                    {
                        required: 'Please enter your email address',
                        email: 'Please enter a VALID email address',
						remote: 'Email address already registered'
                    }
                },
                errorPlacement: function(error, element)
                {
                    error.insertAfter(element.parent());
                },
                submitHandler: function(form) {
					var new_email = document.getElementById('email').value;
					var uname = document.getElementById('uname_email').value;
					$.ajax({
						type: 'POST',
						url: 'change_email.php',
						data: {new_email : new_email, uname : uname},
						success: function(data) {
							if(data=="done"){
								document.getElementById('email').value="";
								showSuccessMsg("<p>Success</p><p>Change of Email Successful!!</p>");
							}
						}
					});
				}
            });
    	});
		</script>
        <script>
		$(function(){
        $("#change_pass_form").validate(
            {
                rules:
                {
                    new_password:
                    {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    new_password_confirm:
                    {
                        required: true,
                        minlength: 3,
                        maxlength: 20,
                        equalTo: '#new_password'
                    }
                },
                messages:
                {
                    new_password:
                    {
                        required: 'Please enter your password'
                    },
					new_password_confirm:
					{
						required: 'Please enter your password one more time',
                        equalTo: 'Please enter the same password as above'
					}
                },

                errorPlacement: function(error, element)
                {
                    error.insertAfter(element.parent());
                },
                submitHandler: function(form) {
					var new_pass = document.getElementById('new_password').value;
					var uname = document.getElementById('uname_pass').value;
					$.ajax({
						type: 'POST',
						url: 'change_pass.php',
						data: {new_pass : new_pass, uname : uname},
						success: function(data) {
							if(data=="done"){
								document.getElementById('new_password').value="";
								showSuccessMsg("<p>Success</p><p>Change of Password Successful!!</p>");
							}
						}
					});
				}
            });
    	});
		</script>
        <form id="success_form" name="success_form" class="sky-form sky-form-modal">
            <div class="message">
                <i class="icon-check"></i>
                <div id="success_message">
                </div>
            </div>
        </form>
        <form id="failure_form" name="failure_form" class="sky-form sky-form-modal">
            <div class="message-error">
                <i class="icon-warning-sign"></i>
                <div id="failure_message">
                </div>
            </div>
        </form>
        <form id="report_bug_form" name="report_bug_form" class="sky-form sky-form-modal-wide">
        <header>Report Bug form</header>	
        <fieldset>					
            <div class="row">
                <section class="col col-6">
                    <label class="label">Name</label>
                    <label class="input">
                        <i class="icon-append fa fa-user"></i>
                        <input type="text" name="report_uname" id="report_uname" value="<?php echo $uname;?>" disabled>
                    </label>
                </section>
                <section class="col col-6">
                    <label class="label">E-mail</label>
                    <label class="input">
                        <i class="icon-append fa fa-at"></i>
                        <input type="email" name="report_email" id="report_email" value="<?php echo $user_email;?>" disabled>
                    </label>
                </section>
            </div>
            <section>
                <label class="label">Subject</label>
                <label class="input">
                    <i class="icon-append fa fa-envelope"></i>
                    <input type="text" name="report_subject" id="report_subject">
                </label>
            </section>
            
            <section>
                <label class="label">Comment</label>
                <label class="textarea">
                    <i class="icon-append fa fa-comment"></i>
                    <textarea rows="4" name="report_comment" id="report_comment"></textarea>
                </label>
                <div class="note">You may use these HTML tags and attributes: &lt;a href="" title=""&gt;, &lt;abbr title=""&gt;, &lt;acronym title=""&gt;, &lt;b&gt;, &lt;blockquote cite=""&gt;, &lt;cite&gt;, &lt;code&gt;, &lt;del datetime=""&gt;, &lt;em&gt;, &lt;i&gt;, &lt;q cite=""&gt;, &lt;strike&gt;, &lt;strong&gt;.</div>
            </section>
        </fieldset>
        
        <footer>
            <button type="submit" name="submit" class="button">Post Bug</button>
        </footer>
        <div class="message">
            <i class="icon-ok"></i>
            <p>Your bug was successfully reported!</p>
            <p>The webmaster will be in touch with you shortly.</p>
        </div>
        
    </form>
    <script>
	function report_bug_modal(){
		//$("#report_bug_form").addClass('submited1');
		jQuery("#mainbody").append('<div id="report_bug_form_overlay" class="sky-form-modal-overlay"></div>');
		stat_form = $('#report_bug_form');
		$('#report_bug_form_overlay').fadeIn();
		stat_form.css('top', '30%').css('left', '50%').css('margin-top', -125).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
		$('#report_bug_form_overlay').on('click', function(){
			$('#report_bug_form_overlay').fadeOut();
			$('.sky-form-modal-wide').fadeOut();
		});
	}
	$(function(){
	$("#report_bug_form").validate(
		{
			rules:
			{
				report_uname:
				{
					required: true
				},
				report_email:
				{
					required: true,
					email: true
				},
				report_subject:
				{
					required: true
				}
			},
			messages:
			{
				report_uname:
				{
					required: 'Please Enter Your Name'
				},
				report_email:
				{
					required: 'Please enter your email address',
                    email: 'Please enter a VALID email address'
				},
				report_subject:
				{
					required: 'Please a Subject'
				}
			},

			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			},
			submitHandler: function(form) {
				var uname = document.getElementById('report_uname').value;
				var email = document.getElementById('report_email').value;
				var subject = document.getElementById('report_subject').value;
				var comment = document.getElementById('report_comment').value;
				$.ajax({
					type: 'POST',
					url: 'report_bug.php',
					data: {uname : uname, email : email, subject : subject, comment : comment},
					success: function(data) {
						if(data=="done"){
							$("#report_bug_form").addClass('submited');
						}else{
							alert("failed");
						}
					}
				});
			}
		});
	});
	</script>
        <form id="activate_form" name="activate_form" class="sky-form sky-form-modal">
        <header>Activation form</header>	
        <fieldset>
            <section>
                <label class="label">Activation Code</label>
                <label class="input">
                    <i class="icon-append fa fa-code"></i>
                    <input type="text" name="act_code" id="act_code" autocomplete="off">
                </label>
            </section>
        </fieldset>
        
        <footer>
            <button type="submit" name="submit" class="button">Activate</button>
        </footer>
        <div class="message">
            <i class="icon-ok"></i>
            <p>Activation request underway</p>
            <p>This page will automatically redirect.</p>
        </div>
        
    </form>
     <script>
	function activate_form_modal(){
		//$("#report_bug_form").addClass('submited1');
		jQuery("#mainbody").append('<div id="activate_form_overlay" class="sky-form-modal-overlay"></div>');
		stat_form = $('#activate_form');
		$('#activate_form_overlay').fadeIn();
		stat_form.css('top', '30%').css('left', '50%').css('margin-top', -125).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
		$('#activate_form_overlay').on('click', function(){
			$('#activate_form_overlay').fadeOut();
			$('.sky-form-modal').fadeOut();
		});
	}
	$(function(){
	$("#activate_form").validate(
		{
			rules:
			{
				act_code:
				{
					required: true,
					remote: {
								url: "act_code_check.php",
								type: "post"
							}
				}
			},
			messages:
			{
				act_code:
				{
					required: 'Please Enter the Activation Code',
					remote: 'Incorrect Activation Code'
				}
			},

			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			},
			submitHandler: function(form) {
				var act_code = document.getElementById('act_code').value;
				$.ajax({
					type: 'POST',
					url: 'software_activate.php',
					data: {act_code : act_code},
					success: function(data) {
						if(data=="done"){
							$("#activate_form").addClass('submited');
							setTimeout(function(){
								window.location = "report_login.php";
							},2000)
						}else{
							alert("failed");
						}
					}
				});
			}
		});
	});
	</script>
	</body>
   <div class="footer">
    	<div class="footer-left">
	        <i class="fa fa-lg fa-copyright"></i> Copyright&nbsp;2016 | CodeCrypt<br />
            &nbsp;Designed and Developed by <strong>Sarvesh Parab</strong>
        </div>
        <div class="footer-center">
        	<a href="https://www.facebook.com/sarveshsparab" target="_blank"><i class="fa fa-2x fa-facebook-square"></i></a>&nbsp;&nbsp;&nbsp;
            <a href="" target="_blank"><i class="fa fa-2x fa-twitter-square"></i></a>&nbsp;&nbsp;&nbsp;
            <a href="https://www.linkedin.com/in/sarveshsparab" target="_blank"><i class="fa fa-2x fa-linkedin-square"></i></a>&nbsp;&nbsp;&nbsp;
            <a href="https://github.com/sarveshsparab" target="_blank"><i class="fa fa-2x fa-github-square"></i></a>&nbsp;&nbsp;&nbsp;
            <a href="mailto:sarveshsparab@gmail.com?Subject=LFHS%20ReportCard" target="_blank"><i class="fa fa-2x fa-envelope-square"></i></a><br />
            <div class="fnote" onClick="report_bug_modal();">Report Bug</div>
        </div>
        <div class="footer-right">
        	<a href="https://lfhschool.org"><i class="fa fa-lg2 fa-home"></i></a>&nbsp;Little Flower High School<br>
            <div class="fnote">
            <?php
            	if($activation==-1){
					?>Registered School Copy<?php
				}else{
					?><div onClick="activate_form_modal();">Trial version. (<?php echo $activation;?> Days Left)</div><?php
				}
			?>
            </div>
        </div>
    </div>
    
</html>
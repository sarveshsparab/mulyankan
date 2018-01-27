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
include("connect_db.php");
if(isset($_GET["uname"])){
	$uname = preg_replace('#[^a-z0-9]#i', '', $_GET['uname']);
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
		<title>Admin Mode</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		
		<link rel="stylesheet" href="css/demo-tabs.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/sky-tabs.css">
        <link rel="stylesheet" href="css/sky-forms.css" />
        <link rel="stylesheet" href="css/tables.css" />
        <link rel="stylesheet" href="css/font-awesome-animate.css">
        <link rel="stylesheet" href="css/footer.css" />
        
		<script src="js/jquery-1.9.1.min.js"></script>
    	<script src="js/jquery.validate.min.js"></script>
    	<script src="js/jquery.placeholder.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        
	</head>
	
	<body class="bg-cyan" id="mainbody">
		<div class="body">
		
			<!-- tabs -->
			<div class="sky-tabs sky-tabs-amount-5 sky-tabs-pos-top-justify sky-tabs-anim-flip sky-tabs-response-to-icons">
				<input type="radio" name="sky-tabs" checked id="sky-tab1" class="sky-tab-content-1">
				<label for="sky-tab1"><span><span><i class="fa fa-users"></i>Faculty Functions</span></span></label>
				
				<input type="radio" name="sky-tabs" id="sky-tab2" class="sky-tab-content-2">
				<label for="sky-tab2"><span><span><i class="fa fa-cogs"></i>Admin Functions</span></span></label>
				
				<input type="radio" name="sky-tabs" id="sky-tab3" class="sky-tab-content-3">
				<label for="sky-tab3"><span><span><i class="fa fa-tasks"></i>DB Functions</span></span></label>
				
                <input type="radio" name="sky-tabs" id="sky-tab4" class="sky-tab-content-4">
				<label for="sky-tab4"><span><span><i class="fa fa-bar-chart-o"></i>Statistics</span></span></label>
                
				<input type="radio" name="sky-tabs" id="sky-tab5" class="sky-tab-content-5">
				<label for="sky-tab5"><span><span><i class="fa fa-user"></i>Profile</span></span></label>
				
				<ul>
					<li class="sky-tab-content-1">
						<div class="typography">
							<h1>Faculty Functions</h1>
							<p>Here you have the functionality to activate or de-activate a faculty. Also to remove a faculty all together. <br>Functionality to assign a appropriate role to individual faculty.<br><br></p>
						</div>
						
						<div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-scale sky-tabs-response-to-stack">
							<input type="radio" name="sky-tabs-1" id="sky-tab1-1" class="sky-tab-content-1">
							<label for="sky-tab1-1"><span><span>Activate / Deactivate</span></span></label>
							
							<input type="radio" name="sky-tabs-1" id="sky-tab-1-2" class="sky-tab-content-2">
							<label for="sky-tab-1-2"><span><span>Remove Faculty</span></span></label>
							
							<input type="radio" name="sky-tabs-1" id="sky-tab1-3" class="sky-tab-content-3">
							<label for="sky-tab1-3"><span><span>Faculty Roles and tasks</span></span></label>
							
							<ul>
								<li class="sky-tab-content-1">
									<div class="typography">
										<div class="grid-row">
                                            <div class="grid-col grid-col-6">
                                                <h3>Active Faculty</h3><br />
                                                
                                                <section class="sky-form col" style="box-shadow:none" id="active_list">				
                                                    <label class="label">Toggle to Deactivate</label>
                                                    <?php
                                                    $sql = "SELECT * FROM teacher WHERE activated=1";
    												$query = mysqli_query($conn, $sql);
													while($row = mysqli_fetch_assoc($query)){
														?>
														<label class="toggle" id="<?php echo "teach".$row['teacher_id'];?>">
                                                        	<input type="checkbox" name="checkbox-toggle" checked onClick="activate_deactivate(<?php echo $row['teacher_id'];?>,this);">
                                                            	<i></i><?php echo $row['teacher_name']." ( ".$row['teacher_email']." )";?>
                                                        </label>
														<?php
													}
													?>	
                                                </section>
                                                
                                                
                                            </div>
                                            <div class="grid-col grid-col-6">
                                                <h3>In-Active Faculty</h3><br />
                                                
                                                <section class="sky-form col" style="box-shadow:none" id="inactive_list">				
                                                    <label class="label">Toggle to Activate</label>
                                                    <?php
                                                    $sql = "SELECT * FROM teacher WHERE activated=0";
    												$query = mysqli_query($conn, $sql);
													while($row = mysqli_fetch_assoc($query)){
														?>
														<label class="toggle" id="<?php echo "teach".$row['teacher_id'];?>">
                                                        	<input type="checkbox" name="checkbox-toggle" onClick="activate_deactivate(<?php echo $row['teacher_id'];?>,this);">
                                                            	<i></i><?php echo $row['teacher_name']." ( ".$row['teacher_email']." )";?>
                                                        </label>
														<?php
													}
													?>	
                                                </section>
                                                
                                            </div>
                                        </div>
									</div>
								</li>
                                
								<li class="sky-tab-content-2">
									<div class="typography">
										<h3>Remove Faculty</h3>
                                    <p>The following list contains only ACTIVE faculty accounts.<br />
                                    NOTE: Deleting a faculty will remove all trace of that faculty from the entire database. Please be sure before you proceed to remove one.</p><br />                                    
                                    </div>
                                    
                                    <section class="sky-form col" style="box-shadow:none">	
                                        <div class="row">
                                            <div class="col col-5">
                                            	<label class="label">Select Faculty</label>
                                                <label class="select">
                                                    <select id="view_faculty_list" onChange="viewFacultyListChange();">
                                                        <option value="0">Choose a Faculty</option>
                                                        <?php
                                                            $sql = "SELECT * FROM teacher WHERE activated=1";
                                                            $query = mysqli_query($conn, $sql);
                                                            while($row = mysqli_fetch_assoc($query)){
                                                                ?>
                                                                <option value="<?php echo $row['teacher_id'];?>"><?php echo $row['teacher_name'];?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </div>
                                            <div hidden="true" id="rem_faculty_div">
                                                <div class="col col-5">
                                                	<label class="label">Details</label>
                                                    <label class="select select-multiple">
                                                        <select multiple disabled id="view_faculty_details">
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="col col-2">
                                                    <label class="label">Click to Remove</label>
                                                        <i class="fa fa-trash-o fa-4x" onClick="rem_faculty();"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
								</li>
								<li class="sky-tab-content-3">
									<div class="typography">
										<div class="grid-row">
                                            <div class="grid-col grid-col-6">
                                                <h3>Assign Role</h3><br />
                                                
                                                <section class="sky-form col" style="box-shadow:none">	
                                                    <div class="row">
														<div class="col col-3">
                                                        	<label class="label">Select Faculty</label>
                                                            <label class="select">
                                                                <select id="assign_fac_list">
                                                                    <option value="0">Choose a Faculty</option>
                                                                    <?php
                                                                        $sql = "SELECT * FROM teacher WHERE activated=1";
                                                                        $query = mysqli_query($conn, $sql);
                                                                        while($row = mysqli_fetch_assoc($query)){
                                                                            ?>
                                                                            <option value="<?php echo $row['teacher_id'];?>"><?php echo $row['teacher_name'];?></option>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <i></i>
                                                            </label>
                                                        </div>
                                                        <div class="col col-3">
                                                        	<label class="label">Select Standard</label>
                                                            <label class="select">
                                                                <select id="assign_std_list">
                                                                    <option value="0">Choose a Standard</option>
                                                                    <?php
                                                                        $sql = "SELECT * FROM standard";
                                                                        $query = mysqli_query($conn, $sql);
                                                                        while($row = mysqli_fetch_assoc($query)){
                                                                            ?>
                                                                            <option value="<?php echo $row['standard_id'];?>"><?php echo $row['standard_name'];?></option>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <i></i>
                                                            </label>
                                                        </div>
                                                        <div class="col col-3">
                                                            <label class="label">Select Division</label>
                                                            <label class="select">
                                                                <select id="assign_div_list">
                                                                    <option value="0">Choose a Division</option>
                                                                    <?php
                                                                        $sql = "SELECT * FROM division";
                                                                        $query = mysqli_query($conn, $sql);
                                                                        while($row = mysqli_fetch_assoc($query)){
                                                                            ?>
                                                                            <option value="<?php echo $row['division_id'];?>"><?php echo $row['division_name'];?></option>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <i></i>
                                                            </label>
                                                        </div>
                                                        <div class="col col-3">
                                                        	<label class="label">Select Subject</label>
                                                            <label class="select">
                                                                <select id="assign_sub_list">
                                                                    <option value="0">Choose a Subject</option>
                                                                    <?php
                                                                        $sql = "SELECT * FROM subject";
                                                                        $query = mysqli_query($conn, $sql);
                                                                        while($row = mysqli_fetch_assoc($query)){
                                                                            ?>
                                                                            <option value="<?php echo $row['subject_id'];?>"><?php echo $row['subject_name'];?></option>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <i></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col col-2">
                                                        </div>
                                                        <div class="col col-8">
                                                        	<input type="button" class="button_green" value="Assign Role" onClick="assign_role();"/>
                                                        </div>
                                                        <div class="col col-2">
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                            <div class="grid-col grid-col-6">
                                                <h3>Revoke Role</h3><br />
                                                <section class="sky-form col" style="box-shadow:none">	
                                                    <div class="row">
														<div class="col col-4">
                                                        	<label class="label">Select Faculty</label>
                                                            <label class="select">
                                                                <select id="revoke_fac_list" onChange="revokeFacListChange();">
                                                                    <option value="0">Choose a Faculty</option>
                                                                    <?php
                                                                        $sql = "SELECT * FROM teacher WHERE activated=1";
                                                                        $query = mysqli_query($conn, $sql);
                                                                        while($row = mysqli_fetch_assoc($query)){
                                                                            ?>
                                                                            <option value="<?php echo $row['teacher_id'];?>"><?php echo $row['teacher_name'];?></option>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <i></i>
                                                            </label>
                                                        </div>
                                                        <div class="col col-8">
                                                        	<label class="label">Select Role</label>
                                                            <label class="select">
                                                                <select id="revoke_role_list" >
                                                                </select>
                                                                <i></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col col-2">
                                                        </div>
                                                        <div class="col col-8">
                                                        	<input type="button" class="button_red" value="Revoke Role" onClick="revoke_role();"/>
                                                        </div>
                                                        <div class="col col-2">
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
									</div>									
								</li>
							</ul>
						</div>						
					</li>
					
					<li class="sky-tab-content-2">
						<div class="typography">
							<h1>Admin Functions</h1>
							<p>Here you have the functionality being the administrator of the system. <br>Functionality to Create new sessions | Lock and unlock files | Generate Reports | Reset forgotten passwords<br><br></p>
						</div>
                        <div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-scale sky-tabs-response-to-stack">
							<input type="radio" name="sky-tabs-2" id="sky-tab2-1" class="sky-tab-content-1">
							<label for="sky-tab2-1"><span><span>Sessions</span></span></label>
                            
                            <input type="radio" name="sky-tabs-2" id="sky-tab2-2" class="sky-tab-content-2">
							<label for="sky-tab2-2"><span><span>Lock / Unlock</span></span></label>
                            
                            <input type="radio" name="sky-tabs-2" id="sky-tab2-3" class="sky-tab-content-3">
							<label for="sky-tab2-3"><span><span>Generate Reports</span></span></label>
                            
                            <input type="radio" name="sky-tabs-2" id="sky-tab2-4" class="sky-tab-content-4">
							<label for="sky-tab2-4"><span><span>Reset Passwords</span></span></label>
                            
                            <ul>
                            	<li class="sky-tab-content-1">
									<div class="typography">
										<div class="grid-row">
                                        	<div class="grid-col grid-col-6">
                                            	<h3>Create A New Year</h3><br />
                                                    <section class="sky-form col" style="box-shadow:none">
                                                    	<div class="grid-row">
                                                        	<label class="label">Existing Years</label>
                                                             <label class="select select-multiple">
                                                                <select multiple disabled id="view_years_list">
                                                                <?php
                                                                $sql = "SELECT * FROM sessions where sessions_parent is null";
                                                            	$query = mysqli_query($conn, $sql);
																$numrows = mysqli_num_rows($query);
																if($numrows==0){
																	?>
                                                                    <option value="0">No Years Made Yet</option>
																	<?php
																}else{
																	while($row = mysqli_fetch_assoc($query)){
																		?>
                                                                        <option value="<?php echo $row['sessions_id'];?>"><?php echo $row['sessions_name'];?></option>
																		<?php
																	}
																}
																?>
                                                                </select>
                                                            </label>
                                                        </div>	
                                                        <div class="grid-row">
                                                        	<div class="grid-col grid-col-4">
                                                            	<label class="label">New Year Name</label>
                                                                <label class="input">
                                                                    <input type="text" placeholder="New Year" id="new_year_tb"/>
                                                                </label>
                                                            </div>
                                                            <div class="grid-col grid-col-8">
                                                            	<label class="label">Click to add</label>
                                                                <i class="fa fa-plus-square fa-3x" onClick="addYear();"></i>
                                                            </div>
                                                        </div>
                                                    </section>
                                            </div>
                                            <div class="grid-col grid-col-6">
                                            	<h3>Create A New Semester</h3><br />
                                                <section class="sky-form col" style="box-shadow:none">
                                                    <div class="grid-row">
                                                        <label class="label">Existing Semesters</label>
                                                         <label class="select select-multiple">
                                                            <select multiple disabled id="view_sems_list">
                                                            <?php
                                                            $sql = "SELECT * FROM sessions where sessions_parent is not null";
                                                            $query = mysqli_query($conn, $sql);
                                                            $numrows = mysqli_num_rows($query);
                                                            if($numrows==0){
                                                                ?>
                                                                <option value="0">No Semesters Made Yet</option>
                                                                <?php
                                                            }else{
                                                                while($row = mysqli_fetch_assoc($query)){
                                                                    ?>
                                                                    <option value="<?php echo $row['sessions_id'];?>"><?php echo $row['sessions_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            </select>
                                                        </label>
                                                    </div>	
                                                    <div class="grid-row">
                                                    	<div class="grid-col grid-col-8">
                                                        	<div class="grid-row">
                                                                <div class="grid-col grid-col-6">
                                                                    <label class="label">Choose a Year</label>
                                                                    <label class="select">
                                                                       <select id="add_sem_years_list">
                                                                        <?php
                                                                        $sql = "SELECT * FROM sessions where sessions_parent is null";
                                                                        $query = mysqli_query($conn, $sql);
                                                                        $numrows = mysqli_num_rows($query);
                                                                        if($numrows==0){
                                                                            ?>
                                                                            <option value="0">No Year Made Yet</option>
                                                                            <?php
                                                                        }else{
                                                                            ?><option value="0">Choose a Year</option><?php
                                                                            while($row = mysqli_fetch_assoc($query)){
                                                                                ?>
                                                                                <option value="<?php echo $row['sessions_id'];?>"><?php echo $row['sessions_name'];?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                       </select>
                                                                    </label>
                                                                </div>
                                                                <div class="grid-col grid-col-6">
                                                                    <label class="label">New Year Name</label>
                                                                    <label class="input">
                                                                        <input type="text" placeholder="New Semester" id="new_sem_tb"/>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="grid-col grid-col-4">
                                                        	&nbsp;&nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="grid-row">
                                                    	<div class="grid-col grid-col-8">
                                                        	<label class="label">Check which data you want to DELETE / RESET?</label>
                                                            <div class="grid-row">
                                                            	<div class="grid-col grid-col-4">
                                                                    <label class="checkbox">
                                                                        <input type="checkbox" id="mark_ck"><i></i>Marks
                                                                    </label>
                                                                </div>
                                                                <div class="grid-col grid-col-4">
                                                                	<label class="checkbox">
                                                                        <input type="checkbox" id="column_ck"><i></i>Columns
                                                                    </label>
                                                                </div>
                                                                <div class="grid-col grid-col-4">
                                                                	<label class="checkbox">
                                                                        <input type="checkbox" id="structure_ck"><i></i>Structures
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="grid-row">
                                                            	<div class="grid-col grid-col-4">
                                                                    <label class="checkbox">
                                                                        <input type="checkbox" id="subject_ck"><i></i>Subjects
                                                                    </label>
                                                                </div>
                                                                <div class="grid-col grid-col-4">
                                                                	<label class="checkbox">
                                                                        <input type="checkbox" id="course_ck"><i></i>Courses
                                                                    </label>
                                                                </div>
                                                                <div class="grid-col grid-col-4">
                                                                	<label class="checkbox">
                                                                        <input type="checkbox" id="student_ck"><i></i>Students
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="grid-row">
                                                            	<div class="grid-col grid-col-4">
                                                                    <label class="checkbox">
                                                                        <input type="checkbox" id="role_ck"><i></i>Roles
                                                                    </label>
                                                                </div>
                                                                <div class="grid-col grid-col-4">
                                                                	<label class="checkbox">
                                                                        <input type="checkbox" id="stddiv_ck"><i></i>Std. / Div.
                                                                    </label>
                                                                </div>
                                                                <div class="grid-col grid-col-4">
                                                                	<label class="checkbox">
                                                                        <input type="checkbox" id="grade_ck"><i></i>Grades
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="grid-col grid-col-4">
                                                        	<label class="label">Click to add</label>
                                                            <i class="fa fa-plus-square fa-3x" onClick="addSemester();"></i>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </li>
                                <li class="sky-tab-content-2">
									<div class="typography">
										<div class="grid-row">
                                        <h3>Lock or Unlock Report Structures or MarkSheets</h3><br />
                                        <section class="sky-form col" style="box-shadow:none">	
                                            <div class="grid-col grid-col-6">
                                            	<label class="label">Select Faculty</label>
                                                <label class="select">
                                                    <select id="lock_unlock_faculty_list" onChange="lockUnlockFacultyListChange();">
                                                        <option value="0">Choose a Faculty</option>
                                                        <?php
                                                            $sql = "SELECT * FROM teacher WHERE activated=1";
                                                            $query = mysqli_query($conn, $sql);
                                                            while($row = mysqli_fetch_assoc($query)){
                                                                ?>
                                                                <option value="<?php echo $row['teacher_id'];?>"><?php echo $row['teacher_name']." ( ".$row['teacher_email']." )";?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </div>
                                        </section>
                                        </div>
                                        <br /><br />
                                        <div id="lock_unlock_details_div" style="visibility:hidden; display:none;">
                                        
                                        </div>
                                        
                                    </div>
                                </li>
                                <li class="sky-tab-content-3">
									<div class="typography">
										<h3>Generate Reports</h3><br />
                                    </div>
                                </li>
                            	<li class="sky-tab-content-4">
									<div class="typography">
										<h3>Password Reset Requests</h3><br />
                                        <p>Here are the list of users that have requested a password reset.</p><br /><br />
                                        <section class="sky-form col" style="box-shadow:none">
                                        <?php
											$sql = "SELECT * FROM teacher WHERE teacher_pass='forgot' AND activated=1";
											$query = mysqli_query($conn, $sql);
											while($row = mysqli_fetch_assoc($query)){
												?>
                                                <div class="row" id="<?php echo"reset_row".$row['teacher_id'];?>">
                                                	<div class="col col-1">
                                                    	<label class="label">&nbsp;</label>
                                                    	<i class="fa fa-user fa-2x" style="float:right;"></i>
                                                    </div>
                                                    <div class="col col-4">
                                                    	<label class="label">&nbsp;</label>
                                                        <label class="label" style="font-size:16px"><?php echo $row['teacher_name']." ( ".$row['teacher_email']." )";?></label>
                                                    </div>
                                                    <div class="col col-3">
                                                        <label class="input">
                                                            <input type="text" placeholder="New Password" id="<?php echo "reset".$row['teacher_id'];?>">
                                                        </label>
                                                    </div>
                                                    <div class="col col-2">
                                                    	<button type="button" class="button_red" style="margin-top:0px;" onClick="rand_pass(<?php echo $row['teacher_id'];?>);">Random Gen</button>
                                                    </div>
                                                    <div class="col col-2">
                                                    	<button type="button" class="button_green" style="margin-top:0px;" onClick="reset_pass(<?php echo "'".$row['teacher_name']."'";?>,<?php echo $row['teacher_id'];?>);">Reset</button>
                                                    </div>
                                                </div>
												<?php
											}
										?>
                                        </section>
									</div>
								</li>
                            </ul>
                         </div>
					</li>
					
					<li class="sky-tab-content-3">
						<div class="typography">
							<h1>Database Functions</h1>
							<p>Here you have the functionality regarding the database contents and structures <br>Functionality to create and modify the student list | Update grades table | Populate courses | Maintain column list<br><br></p>
						</div>
                         <div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-scale sky-tabs-response-to-stack">
							<input type="radio" name="sky-tabs-3" id="sky-tab3-1" class="sky-tab-content-1">
							<label for="sky-tab3-1"><span><span>Student List</span></span></label>
							
							<input type="radio" name="sky-tabs-3" id="sky-tab-3-2" class="sky-tab-content-2">
							<label for="sky-tab-3-2"><span><span>Grades Table</span></span></label>
                            
                            <input type="radio" name="sky-tabs-3" id="sky-tab-3-3" class="sky-tab-content-3" onClick="load_course_section();">
							<label for="sky-tab-3-3"><span><span>Courses </span></span></label>
                            
                            <input type="radio" name="sky-tabs-3" id="sky-tab-3-4" class="sky-tab-content-4" onClick="load_col_structure();">
							<label for="sky-tab-3-4"><span><span>Column List</span></span></label>
                            <ul>
                            	<li class="sky-tab-content-1">
									<div class="typography">
                                        <div class="grid-row">
                                            <div class="grid-col grid-col-5">
                                            	<h3>Data Upload</h3><br />
                                            	<section class="sky-form col" style="box-shadow:none">
                                                <div class="row">
                                                    <label class="label">Choose a file</label>
                                                    <label for="file" class="input input-file">
                                                        <div class="button"><input type="file" id="fileUpload" onchange="this.parentNode.nextSibling.value = this.value">Browse</div><input type="text" id="fileUploadPath" readonly>
                                                    </label>
                                                 </div><br />
                                                 <div class="row">
                                                 	<div class="col col-4">
                                                    	<label class="label">Select Standard</label>
                                                        <label class="select">
                                                            <select id="view_upload_std_list" onChange="document.getElementById('dispCSV').innerHTML='';">
                                                                <option value="0">Choose a Standard</option>
                                                                <?php
                                                                    $sql = "SELECT * FROM standard";
                                                                    $query = mysqli_query($conn, $sql);
                                                                    while($row = mysqli_fetch_assoc($query)){
                                                                        ?>
                                                                        <option value="<?php echo $row['standard_id'];?>"><?php echo $row['standard_name'];?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                    </div>
                                                    <div class="col col-4">
                                                    	<label class="label">Select Division</label>
                                                        <label class="select">
                                                            <select id="view_upload_div_list" onChange="document.getElementById('dispCSV').innerHTML='';">
                                                                <option value="0">Choose a Division</option>
                                                                <?php
                                                                    $sql = "SELECT * FROM division";
                                                                    $query = mysqli_query($conn, $sql);
                                                                    while($row = mysqli_fetch_assoc($query)){
                                                                        ?>
                                                                        <option value="<?php echo $row['division_id'];?>"><?php echo $row['division_name'];?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                    </div>
                                                 	<div class="col col-4">
                                                    	<input type="button" class="button_red" style="margin-top:25px;" value="Preview" id="preview_bt" onClick="preview_file();"/>
                                                    </div>
                                                 </div><br />
                                                 <div class="row">
                                                 	<label class="label">Options</label>
                                                    <div class="col col-6">
                                                    	<label class="button button-secondary">
                                                    		<button onClick="make_editable();" style="font-size:16px; color:#FFF;"><i class="fa fa-pencil" ></i>&nbsp;&nbsp;EDIT</button>
                                                        </label>
                                                    </div>
                                                    <div class="col col-6">
                                                    	<label class="button button-secondary"> 
                                                        	<button onClick="make_uneditable();" style="font-size:16px; color:#FFF;"><i class="fa fa-save" ></i>&nbsp;&nbsp;SAVE</button>
                                                        </label>
                                                    </div>
                                                 </div><br />
                                                 <div class="row">
                                                    <input type="button" class="button_green" value="Upload" id="upload_bt" onClick="uploadStudent();"/>
                                                 </div>  
                                                </section>
                                            </div>
                                            <div class="grid-col grid-col-7">
                                            	<h3>Data Preview</h3><br />
                                                <div id="dispCSV">
                                                </div>
                                            </div>
                                        </div>    
									</div>
								</li>
                                <li class="sky-tab-content-2">
									<div class="typography">
										 <div class="grid-row">
                                            <div class="grid-col grid-col-12">
                                                <h3>Grades Table</h3>
                                                <table cellspacing='0' class="grade_table">
                                                	<thead>
                                                    	<tr>
                                                            <th>Max. Marks</th>
                                                            <?php
                                                            $sql = "SELECT distinct grade_name FROM grades order by grade_name asc";
                                                            $query = mysqli_query($conn, $sql);
                                                            while($row = mysqli_fetch_assoc($query)){
                                                                ?>
                                                                <th><?php echo $row['grade_name']; ?></th>
                                                                <?php
                                                            }
                                                            ?>
                                                            <th>Edit</th>
                                                            <th>Delete</th>
                                                         </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<?php
                                                        	$row_head=0;
															$sql = "SELECT * FROM grades order by grade_max asc, grade_name";
                                                        	$query = mysqli_query($conn, $sql);
															$rw_count=0;
                                                        	while($row = mysqli_fetch_assoc($query)){
																if($row_head!=$row['grade_max']){
																	if($row_head!=0){
																		?>
                                                                        </tr>
																		<?php
																	}
																	$row_head=$row['grade_max'];
																	?>
                                                                    <tr <?php if($rw_count%2==0){?> class="even"<?php }?> id=<?php echo "grade_row".$row['grade_max']?>>
                                                                    	<td><?php echo $row_head;?></td>
																	<?php
																}
																?>
																<td><?php echo $row['grade_down']." - ".$row['grade_top'];?></td>
																<?php
																$rw_count++;
																if($rw_count%9==0){
																	?>
																	<td>
																		 <i class="fa fa-pencil fa-2x" onClick="edit_grade_row(<?php echo $row['grade_max']?>);"></i>
																	</td>
                                                                    <td>
																		 <i class="fa fa-trash-o fa-2x" onClick="rem_grade_entry(<?php echo $row['grade_max']?>);"></i>
																	</td>
                                                                    <?php
																}
															}
														?>
                                                    </tbody>
                                                </table>
                                            </div>
                                          </div>
									</div>									
								</li>
                                <li class="sky-tab-content-3">
									<div class="typography">
										<div class="grid-row">
                                            <div class="grid-col grid-col-3">
                                                <h3>Courses Offered</h3><br />
                                                
                                                <section class="sky-form col" style="box-shadow:none">	
                                                    <label class="label">Select Standard</label>
                                                    <label class="select">
                                                        <select id="view_std_list" onChange="viewStdListChange();">
                                                            <option value="0">Choose a Standard</option>
                                                            <?php
                                                    			$sql = "SELECT * FROM standard";
    															$query = mysqli_query($conn, $sql);
																while($row = mysqli_fetch_assoc($query)){
																	?>
                                                                    <option value="<?php echo $row['standard_id'];?>"><?php echo $row['standard_name'];?></option>
                                                                    <?php
																}
															?>
                                                        </select>
                                                        <i></i>
                                                    </label><br />
                                                 </section>
                                                 <section class="sky-form col" style="box-shadow:none" id="view_courses_section" hidden="true">	
                                                    <label class="label">Courses</label>
                                                    <label class="select select-multiple">
                                                        <select multiple disabled id="view_courses_list">
                                                        </select>
                                                    </label>
                                                </section>
                                            </div>
                                            <div class="grid-col grid-col-3">
                                                <h3>Subjects Offered</h3><br />
                                                <form class="sky-form" id="sub_form">
                                                <section class="sky-form col" style="box-shadow:none" id="add_courses_section">	
                                                    <div class="row">
                                                    	<label class="label">Subjects Available</label>
                                                        <label class="select">
                                                            <select id="sub_list" size="3" style="height:100px" disabled>
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                    </div>
                                                    <div class="row">
                                                    	<label class="label">Name of New Subject</label>
                                                        <label class="input">
                                                            <input type="text" placeholder="New Subject Name" id="sub_name" name="sub_name">
                                                        </label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="submit" class="button_green" value="Add Subject"/>
                                                    </div>
                                                </section>
                                                </form>
                                            </div>
                                            <div class="grid-col grid-col-6">
                                                <h3>Modify Courses</h3>
                                                <div class="grid-row">
                                                	<h5>Add Courses</h5>
                                                    
                                                    <section class="sky-form col" style="box-shadow:none" id="add_courses_section">	
                                                    <div class="row">
														<div class="col col-4">
                                                        <label class="label">Select Standard</label>
                                                        <label class="select">
                                                            <select id="add_std_list" onChange="addStdListChange();">
                                                                <option value="0">Choose a Standard</option>
                                                                <?php
                                                                    $sql = "SELECT * FROM standard";
                                                                    $query = mysqli_query($conn, $sql);
                                                                    while($row = mysqli_fetch_assoc($query)){
                                                                        ?>
                                                                        <option value="<?php echo $row['standard_id'];?>"><?php echo $row['standard_name'];?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                        </div>
                                                        <div class="col col-5">
                                                        <label class="label">Name of New Course</label>
                                                        <label class="select">
                                                            <select id="new_course_tb" disabled>
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                        </div>
                                                        <div class="col col-3">
                                                        <label class="label">Click to Add</label>
                                                        	<i class="fa fa-plus-square fa-3x" onClick="add_course();"></i>
                                                     	</div>
                                                    </div>
                                                    </section>
                                                </div>
                                                <div class="grid-row">
                                                	<h5>Remove Courses</h5>
                                                    
                                                    <section class="sky-form col" style="box-shadow:none" id="rem_courses_section">	
                                                    <div class="row">
														<div class="col col-4">
                                                        <label class="label">Select Standard</label>
                                                        <label class="select">
                                                            <select id="rem_std_list" onChange="remStdListChange();">
                                                                <option value="0">Choose a Standard</option>
                                                                <?php
                                                                    $sql = "SELECT * FROM standard";
                                                                    $query = mysqli_query($conn, $sql);
                                                                    while($row = mysqli_fetch_assoc($query)){
                                                                        ?>
                                                                        <option value="<?php echo $row['standard_id'];?>"><?php echo $row['standard_name'];?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                        </div>
                                                        <div class="col col-5">
                                                        <label class="label">Select Old Course</label>
                                                        <label class="select">
                                                            <select id="rem_course_list">
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                        </div>
                                                        <div class="col col-3">
                                                        <label class="label">Click to Remove</label>
                                                        	<i class="fa fa-minus-square fa-3x" onClick="rem_course();"></i>
                                                     	</div>
                                                    </div>
                                                    </section>
                                                </div>
                                            </div>
                                         </div>
									</div>									
								</li>
                                <li class="sky-tab-content-4">
									<div class="typography">		
                                        <div class="grid-row">
                                            <div class="grid-col grid-col-6">
                                                <h3>Column Layout</h3>
                                                <section class="sky-form col" style="box-shadow:none">
                                                    <label class="label">Tree Structure of the column Hierarchy</label>
                                                    <label class="textarea">
                                                        <textarea rows="15" disabled id="col_structure"></textarea>
                                                    </label>
                                                </section>
                                                
                                            </div>
                                            <div class="grid-col grid-col-6">
                                                <h3>Modify Columns</h3>
                                                <div class="grid-row">
                                                	<h5>Add Columns</h5>
                                                    <section class="sky-form col" style="box-shadow:none" id="add_columns_section">	
                                                        <div class="row">
                                                       		<div class="col col-6">
                                                            	<label class="label">Name of New Column</label>
                                                                <label class="input">
                                                                    <input type="text" placeholder="New Column Name" id="add_col_name_tb">
                                                                </label>
                                                            </div>
                                                            <div class="col col-3">
                                                            	<label class="label">Enter Parent ID [?]</label>
                                                                <label class="input">
                                                                    <input type="number" placeholder="Parent Column" id="add_col_id_tb">
                                                                </label>
                                                            </div>
                                                            <div class="col col-3">
                                                            	<label class="label">Click to Add</label>
                                                        		<i class="fa fa-plus-square fa-3x" onClick="add_column();"></i>
                                                            </div>
                                                        </div>
                                                        <div class="note"><strong>Note:</strong> If column has no parent then put ID as 0 .</div>
                                                    </section>
                                                </div>
                                                <div class="grid-row">
                                                	<br /><h5>Remove Columns</h5>
                                                    <section class="sky-form col" style="box-shadow:none" id="rem_columns_section">	
                                                        <div class="row">
                                                        	<div class="col col-3">
                                                            	<label class="label">Enter Column ID [?]</label>
                                                                <label class="input">
                                                                    <input type="number" placeholder="Column ID" id="rem_col_id_tb" onKeyUp="verify_col();">
                                                                </label>
                                                            </div>
                                                            <div class="col col-6">
                                                            	<label class="label">Name of Column to be removed</label>
                                                                <label class="input">
                                                                    <input type="text" disabled placeholder="Column Name" id="rem_col_name_tb">
                                                                </label>
                                                            </div>
                                                            <div class="col col-3">
                                                            	<label class="label">Click to Remove</label>
                                                        		<i class="fa fa-minus-square fa-3x" onClick="rem_column();"></i>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>	
                                    </div>					
								</li>
                            </ul>
                         </div>
					</li>
					
					<li class="sky-tab-content-4">
						<div class="typography">
							<h1>Statistical Data</h1>
							
                            
                            
						</div>
					</li>
                    
                    <li class="sky-tab-content-5">
						<div class="typography">
							<h1>Profile</h1>
                            <p>Here you have the functionality regarding your profile <br>Functionality to update your password or email address<br><br></p> 
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
		function addYear(){
			var newName = document.getElementById('new_year_tb').value;
			var year_list = document.getElementById('view_years_list');
			if(newName!=''){
				$.ajax({
					type: 'POST',
					url: 'add_year.php',
					data: {newName : newName},
					success: function(data) {
						document.getElementById('new_year_tb').value='';
						if(year_list.options[0].text=="No Years Made Yet"){
							year_list.options[0] = new Option(newName,-1);
						}else{
							year_list.options[year_list.options.length] = new Option(newName,-1);
						}
						$("#success_form").addClass('submited');
						jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
						stat_form = $('#success_form');
						$('#success_form_overlay').fadeIn();
						stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
						setTimeout(function(){
							$('#success_form_overlay').fadeOut();
							$('.sky-form-modal').fadeOut();
						},2000)
					}
				});	
			}
		}
		function addSemester(){
			var newName = document.getElementById('new_sem_tb').value;
			var sems_list = document.getElementById('view_sems_list');
			var year_list = document.getElementById('add_sem_years_list');
			var year_id = year_list.options[year_list.selectedIndex].value;
			var year_name = year_list.options[year_list.selectedIndex].text;
			
			var mark_ck = document.getElementById('mark_ck').checked?1:0;
			var column_ck = document.getElementById('column_ck').checked?1:0;
			var structure_ck = document.getElementById('structure_ck').checked?1:0;
			var subject_ck = document.getElementById('subject_ck').checked?1:0;
			var course_ck = document.getElementById('course_ck').checked?1:0;
			var student_ck = document.getElementById('student_ck').checked?1:0;
			var role_ck = document.getElementById('role_ck').checked?1:0;
			var stddiv_ck = document.getElementById('stddiv_ck').checked?1:0;
			var grade_ck = document.getElementById('grade_ck').checked?1:0;
			
			if(newName!='' && year_id!=0){
				newName = year_name+" : "+newName;
				$.ajax({
					type: 'POST',
					url: 'add_semester.php',
					data: {newName : newName, year_id : year_id, mark_ck : mark_ck, column_ck : column_ck, structure_ck : structure_ck, subject_ck : subject_ck, course_ck : course_ck, student_ck : student_ck, role_ck : role_ck, stddiv_ck : stddiv_ck, grade_ck : grade_ck},
					success: function(data) {
						document.getElementById('new_sem_tb').value='';
						document.getElementById('mark_ck').checked = false;
						document.getElementById('column_ck').checked = false;
						document.getElementById('structure_ck').checked = false;
						document.getElementById('subject_ck').checked = false;
						document.getElementById('course_ck').checked = false;
						document.getElementById('student_ck').checked = false;
						document.getElementById('role_ck').checked = false;
						document.getElementById('stddiv_ck').checked = false;
						document.getElementById('grade_ck').checked = false;
						if(sems_list.options[0].text=="No Semesters Made Yet"){
							sems_list.options[0] = new Option(newName,-1);
						}else{
							sems_list.options[sems_list.options.length] = new Option(newName,-1);
						}
						$("#success_form").addClass('submited');
						jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
						stat_form = $('#success_form');
						$('#success_form_overlay').fadeIn();
						stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
						setTimeout(function(){
							$('#success_form_overlay').fadeOut();
							$('.sky-form-modal').fadeOut();
						},2000)
					}
				});	
			}
		}
		function lockUnlockFacultyListChange(){
			var fac_list = document.getElementById('lock_unlock_faculty_list');
			var fac_id = fac_list.value;
			if(fac_id!=0){
				$.ajax({
					type: 'POST',
					url: 'get_lock_unlock_details.php',
					data: {fac_id : fac_id},
					success: function(data) {
						var details_div = document.getElementById('lock_unlock_details_div');
						details_div.innerHTML=data;
						details_div.style.visibility="visible";
						details_div.style.display="block";
					}
				});	
			}
		}
		function lock_unlock(type,role_id){
			var val;
			var fac_list = document.getElementById('lock_unlock_faculty_list');
			var fac_id = fac_list.value;
			if(type==0){
				var tog = document.getElementById('struct_toggle'+role_id);
				if(tog.checked){
					val=3;
				}else{
					val=0;
				}
				$.ajax({
					type: 'POST',
					url: 'make_struct_progress.php',
					data: {uid : 0, uname : 0, val : val, role_id : role_id},
					success: function(data) {
						$.ajax({
							type: 'POST',
							url: 'get_lock_unlock_details.php',
							data: {fac_id : fac_id},
							success: function(data) {
								var details_div = document.getElementById('lock_unlock_details_div');
								details_div.innerHTML=data;
								details_div.style.visibility="visible";
								details_div.style.display="block";
							}
						});
					}
				});
			}else{
				var tog = document.getElementById('marks_toggle'+role_id);
				if(tog.checked){
					val=3;
				}else{
					val=0;
				}
				$.ajax({
					type: 'POST',
					url: 'make_marks_progress.php',
					data: {uid : 0, uname : 0, val : val, role_id : role_id},
					success: function(data) {
						$.ajax({
							type: 'POST',
							url: 'get_lock_unlock_details.php',
							data: {fac_id : fac_id},
							success: function(data) {
								var details_div = document.getElementById('lock_unlock_details_div');
								details_div.innerHTML=data;
								details_div.style.visibility="visible";
								details_div.style.display="block";
							}
						});
					}
				});
			}
		}
		function uploadStudent(){
			var std_id = document.getElementById('view_upload_std_list').value;
			var div_id = document.getElementById('view_upload_div_list').value;
			var div = document.getElementById('dispCSV');
			var tab_body = div.firstChild.firstChild.nextSibling;
			var num_rows = tab_body.childNodes.length;
			var num_cols = tab_body.firstChild.childNodes.length;
			if(std_id!=0 && div_id!=0){
				$.ajax({
					type: 'POST',
					url: 'upload_students.php',
					data: {std_id : std_id, div_id : div_id, table : createJSON(), num_rows : num_rows, num_cols : num_cols},
					success: function(data) {
						alert(data);
						if(data=="done"){
							document.getElementById('view_upload_std_list').selectedIndex=0;
							document.getElementById('view_upload_div_list').selectedIndex=0;
							document.getElementById('dispCSV').innerHTML="";
							document.getElementById('fileUploadPath').value="";
							$("#success_form").addClass('submited');
							jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
							stat_form = $('#success_form');
							$('#success_form_overlay').fadeIn();
							stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
							setTimeout(function(){
								$('#success_form_overlay').fadeOut();
								$('.sky-form-modal').fadeOut();
							},2000)
						}
					}
				});	
			}
		}
        function createJSON(){
			var std_list = document.getElementById('view_upload_std_list');
			var div_list = document.getElementById('view_upload_div_list');
			var std_name = std_list.options[std_list.selectedIndex].text;
			var div_name = div_list.options[div_list.selectedIndex].text;
			
			var div = document.getElementById('dispCSV');
			var tab_body = div.firstChild.firstChild.nextSibling;
			var num_rows = tab_body.childNodes.length;
			var num_cols = tab_body.firstChild.childNodes.length;
			var row = tab_body.firstChild;
			var tab_json = [];
			
			for(var i=0;i<num_rows;i++){
				var col = row.firstChild; 
				var row_json = [];
				var row_str = "";
				for(var j=0;j<num_cols;j++){
					var data = col.innerHTML;
					if(j<3){
						row_str += data;
						row_str += ",";
					}else if(j==3){
						row_str += std_name;
						row_str += ",";
					}else{
						row_str += div_name;
					}
					col = col.nextSibling;
				}
				var row_split = row_str.split(",");
				for(var j=0; j<row_split.length; j++){
					row_json.push(row_split[j]);
				}
				
				tab_json.push(row_json);
				
				row = row.nextSibling;
			}
			return JSON.stringify(tab_json);
		}
        function make_editable(){
			var div = document.getElementById('dispCSV');
			if(div.innerHTML!=''){
				document.getElementById('upload_bt').disabled=true;
				var tab_body = div.firstChild.firstChild.nextSibling;
				var num_rows = tab_body.childNodes.length;
				var num_cols = tab_body.firstChild.childNodes.length;
				var row = tab_body.firstChild;
				
				for(var i=0;i<num_rows;i++){
					var col = row.firstChild; 
					for(var j=0;j<num_cols;j++){
						var data = col.innerHTML;
						if(j<3){
							col.contentEditable=true;
							col.className = "editmode";
							col.addEventListener("keyup", function(e){
								this.className = "edited";
							});
						}
						col = col.nextSibling;
					}
					row = row.nextSibling;
				}
			}
		}
		function make_uneditable(){
			var div = document.getElementById('dispCSV');
			if(div.innerHTML!=''){
				document.getElementById('upload_bt').disabled=false;
				var tab_body = div.firstChild.firstChild.nextSibling;
				var num_rows = tab_body.childNodes.length;
				var num_cols = tab_body.firstChild.childNodes.length;
				var row = tab_body.firstChild;
				
				for(var i=0;i<num_rows;i++){
					var col = row.firstChild; 
					for(var j=0;j<num_cols;j++){
						var data = col.innerHTML;
						if(j<3){
							col.contentEditable=false;
							col.className = "";
						}
						col = col.nextSibling;
					}
					row = row.nextSibling;
				}
			}
		}
		function preview_file() {
			var std_list = document.getElementById('view_upload_std_list');
			var div_list = document.getElementById('view_upload_div_list');
			var std_id = std_list.value;
			var div_id = div_list.value;
			var std_name = std_list.options[std_list.selectedIndex].text;
			var div_name = div_list.options[div_list.selectedIndex].text;
			if(std_id!=0 && div_id!=0){
				var fileUpload = document.getElementById("fileUpload");
				var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;
				if (regex.test(fileUpload.value.toLowerCase())) {
					if (typeof (FileReader) != "undefined") {
						var reader = new FileReader();
						reader.onload = function (e) {
							var tab = '<table class="grade_table">';
							var rows = e.target.result.split("\n");
							for (var i = 0; i < 1; i++) {
								tab += '<thead>';
								var cells = rows[i].split(",");
								for (var j = 0; j < cells.length; j++) {
									tab += '<th>'+cells[j]+'</th>';
								}
								tab += '</thead>';
							}
							tab += '<tbody id="file_body">';
							for (var i = 1; i < rows.length; i++) {
								tab += '<tr>';
								var cells = rows[i].split(",");
								for (var j = 0; j < cells.length; j++) {
									if(j<3)
										tab += '<td>'+cells[j]+'</td>';
									else if(j==3)
										tab += '<td>'+std_name+'</td>';
									else if(j==4)
										tab += '<td>'+div_name+'</td>';
								}
								tab += '</tr>';					
							}
							tab += '</tbody>';
							tab += "</table>";
							var dvCSV = document.getElementById("dispCSV");
							dvCSV.innerHTML = tab;
						}
						reader.readAsText(fileUpload.files[0]);
					} else {
						alert("This browser does not support HTML5.");
					}
				} else {
					alert("Please upload a valid CSV file.");
				}
			}
		}
		$(function(){
        $("#sub_form").validate(
            {
                rules:
                {
                    sub_name:
                    {
                        required: true,
						remote: {
							url: "live_subject_check.php",
							type: "post"
						}
                    }
                },
                messages:
                {
                    sub_name:
                    {
                        required: 'Please enter your a subject name',
						remote: 'Subject Already Exists'
                    }
                },
                errorPlacement: function(error, element)
                {
                    error.insertAfter(element.parent());
                },
                submitHandler: function(form) {
					var sub_name = document.getElementById('sub_name').value;
					$.ajax({
						type: 'POST',
						url: 'add_subject.php',
						data: {sub_name : sub_name},
						success: function(data) {
							if(data=="done"){
								document.getElementById('sub_name').value="";
								var sub_list = document.getElementById('sub_list');
								sub_list.options[sub_list.options.length] = new Option(sub_name,-1);
								$("#success_form").addClass('submited');
								jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
								stat_form = $('#success_form');
								$('#success_form_overlay').fadeIn();
								stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
								setTimeout(function(){
									$('#success_form_overlay').fadeOut();
									$('.sky-form-modal').fadeOut();
								},2000)
							}
						}
					});
				}
            });
    	});
		function load_course_section(){
			$.ajax({
				type: 'POST',
				url: 'get_all_subjects.php',
				success: function(data) {
					var x=JSON.parse(data);
					var courseBox = document.getElementById('sub_list');
					courseBox.options.length = 0;
					if(x.length==0){
						courseBox.options[courseBox.options.length] = new Option('No courses',0);
					}else{
						for(var i=0;i<x.length;i++){
							courseBox.options[courseBox.options.length] = new Option(x[i][1], x[i][0]);
						}
					}
				}
			});
		}
		function edit_grade_row(max_marks){
			alert(max_marks);
		}
		function reset_pass(uname,id){
			var new_pass = document.getElementById('reset'+id).value;
			if(new_pass!=''){
				$.ajax({
						type: 'POST',
						url: 'change_pass.php',
						data: {new_pass : new_pass, uname : uname},
						success: function(data) {
							if(data=="done"){
								document.getElementById('reset_row'+id).hidden="true";
								$("#success_form").addClass('submited');
								jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
								stat_form = $('#success_form');
								$('#success_form_overlay').fadeIn();
								stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
								setTimeout(function(){
									$('#success_form_overlay').fadeOut();
									$('.sky-form-modal').fadeOut();
								},2000)
							}
						}
					});
			}
		}
		function generatePassword() {
			var length = 8,
				charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
				retVal = "";
			for (var i = 0, n = charset.length; i < length; ++i) {
				retVal += charset.charAt(Math.floor(Math.random() * n));
			}
			return retVal;
		}
		function rand_pass(id){
			document.getElementById('reset'+id).value=generatePassword();
		}
		function add_column(){
			var par_id = document.getElementById('add_col_id_tb').value;
			var col_name = document.getElementById('add_col_name_tb').value;
			if(par_id!='' && col_name!=''){
				$.ajax({
					type: 'POST',
					url: 'add_column.php',
					data: {par_id : par_id, col_name : col_name},
					success: function(data) {
						if(data=="done"){
							document.getElementById('add_col_id_tb').value = "";
							document.getElementById('add_col_name_tb').value = "";
							load_col_structure();
							
							$("#success_form").addClass('submited');
							jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
							stat_form = $('#success_form');
							$('#success_form_overlay').fadeIn();
							stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
							setTimeout(function(){
								$('#success_form_overlay').fadeOut();
								$('.sky-form-modal').fadeOut();
							},2000)
						}
					}
				});
			}
		}
		function rem_column(){
			var col_id = document.getElementById('rem_col_id_tb').value;
			var col_name = document.getElementById('rem_col_name_tb').value;
			if(col_id!='' && col_name!='' && col_name!='ERROR: Not a terminal Column'){
				$.ajax({
					type: 'POST',
					url: 'rem_column.php',
					data: {col_id : col_id},
					success: function(data) {
						if(data=="done"){
							document.getElementById('rem_col_id_tb').value = "";
							document.getElementById('rem_col_name_tb').value = "";
							load_col_structure();
							$("#success_form").addClass('submited');
							jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
							stat_form = $('#success_form');
							$('#success_form_overlay').fadeIn();
							stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
							setTimeout(function(){
								$('#success_form_overlay').fadeOut();
								$('.sky-form-modal').fadeOut();
							},2000)
						}
					}
				});
			}
		}
		function verify_col(){
			var col_id = document.getElementById('rem_col_id_tb').value;
			if(col_id!=""){
				$.ajax({
					type: 'POST',
					url: 'get_col_data.php',
					data: {col_id : col_id},
					success: function(data) {
						var x=JSON.parse(data);
						if(x[0][3]==0)
							document.getElementById('rem_col_name_tb').value=x[0][1];
						else
							document.getElementById('rem_col_name_tb').value='ERROR: Not a terminal Column';
					}
				});
			}else{
				document.getElementById('rem_col_name_tb').value="";
			}
		}
		function load_col_structure(){
			var txtarea = document.getElementById('col_structure');
			var struct="";
			$.ajax({
				type: 'POST',
				url: 'get_col_structure.php',
				success: function(data) {
					var x=data;
					var x_array = x.split(',');
					
					for(var i = 0; i <(x_array.length-1); i++) {
					   var arrow_split = x_array[i].split('→');
					   var level = arrow_split.length-1;
					   for(var j = 0;j<level;j++){
						   struct += "\t";
					   }
					   if(i!=0 && j==0)
						   struct += "\n|→ ";
					   else
						   struct += "|→ ";
					   struct += arrow_split[j];
					   struct += "\n";
					}
					txtarea.value=struct;
				}
			});
		}
		function assign_role(){
			var facBox = document.getElementById("assign_fac_list");
			var fac_id = facBox.options[facBox.selectedIndex].value;
			var stdBox = document.getElementById("assign_std_list");
			var std_id = stdBox.options[stdBox.selectedIndex].value;
			var divBox = document.getElementById("assign_div_list");
			var div_id = divBox.options[divBox.selectedIndex].value;
			var subBox = document.getElementById("assign_sub_list");
			var sub_id = subBox.options[subBox.selectedIndex].value;
			
			if(fac_id!=0 && std_id!=0 && div_id!=0 && sub_id!=0){
				$.ajax({
					type: 'POST',
					url: 'assign_role.php',
					data: {fac_id : fac_id, std_id : std_id, div_id : div_id, sub_id : sub_id},
					success: function(data) {
						if(data=="done"){
							facBox.selectedIndex = 0;
							stdBox.selectedIndex = 0;
							divBox.selectedIndex = 0;
							subBox.selectedIndex = 0;
							$("#success_form").addClass('submited');
							jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
							stat_form = $('#success_form');
							$('#success_form_overlay').fadeIn();
							stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
							setTimeout(function(){
								$('#success_form_overlay').fadeOut();
								$('.sky-form-modal').fadeOut();
							},2000)
						}
					}
				});
			}
		}
		function revoke_role(){
			var roleBox = document.getElementById("revoke_role_list");
			var role_id = roleBox.options[roleBox.selectedIndex].value;
			if(role_id!=0){
				$.ajax({
					type: 'POST',
					url: 'revoke_role.php',
					data: {role_id : role_id},
					success: function(data) {
						if(data=="done"){
							var selectBox = document.getElementById("revoke_fac_list");
							selectBox.selectedIndex = 0;
							roleBox.options.length = 0;	
							roleBox.options[roleBox.options.length] = new Option('Select a Role',0);
							$("#success_form").addClass('submited');
							jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
							stat_form = $('#success_form');
							$('#success_form_overlay').fadeIn();
							stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
							setTimeout(function(){
								$('#success_form_overlay').fadeOut();
								$('.sky-form-modal').fadeOut();
							},2000)
						}
					}
				});
			}
		}
		function revokeFacListChange(){
			var selectBox = document.getElementById("revoke_fac_list");
			var fac_id = selectBox.options[selectBox.selectedIndex].value;
			if(fac_id!=0){
				var roleBox = document.getElementById('revoke_role_list');
				$.ajax({
					type: 'POST',
					url: 'get_faculty_details.php',
					data: {fac_id : fac_id},
					success: function(data) {
						var x=JSON.parse(data);
						roleBox.options.length = 0;
						if(x.length==0){
							roleBox.options[roleBox.options.length] = new Option('No role assigned to selected faculty',0);
						}else{
							for(var i=0;i<x.length;i++){
								roleBox.options[roleBox.options.length] = new Option(x[i][3]+" | "+x[i][6]+" || "+x[i][4], x[i][5]);
							}
						}
					}
				});
			}else{
				var roleBox = document.getElementById('revoke_role_list');	
				roleBox.options.length = 0;	
				roleBox.options[roleBox.options.length] = new Option('Select a Role',0);	
			}
		}
        function rem_faculty(){
			alert("will write later... have to make foriegn keys");
		}
        function viewFacultyListChange(){
			var selectBox = document.getElementById("view_faculty_list");
			var fac_id = selectBox.options[selectBox.selectedIndex].value;
			if(fac_id==0){
				document.getElementById('rem_faculty_div').hidden=true;
			}else{
				$.ajax({
					type: 'POST',
					url: 'get_faculty_details.php',
					data: {fac_id : fac_id},
					success: function(data) {
						var x=JSON.parse(data);
						document.getElementById('rem_faculty_div').hidden=false;
						var facBox = document.getElementById('view_faculty_details');
						facBox.options.length = 0;
						if(x.length==0){
							facBox.options[facBox.options.length] = new Option('No details available for the given faculty',0);
						}else{
							facBox.options[facBox.options.length] = new Option('Teacher ID : '+x[0][0], 1);
							facBox.options[facBox.options.length] = new Option('Teacher Username : '+x[0][1], 2);
							facBox.options[facBox.options.length] = new Option('Teacher Email : '+x[0][2], 3);
							
							if(x[0].length>3){
								facBox.options[facBox.options.length] = new Option('Roles Assigned : ', 4);
								for(var i=0;i<x.length;i++){
									facBox.options[facBox.options.length] = new Option('=>> Standard : \''+x[i][3]+'\' | Subject : \''+x[i][4]+'\'', i+5);
								}
							}
						}
					}
				});
			}
		}
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
								$("#success_form").addClass('submited');
								jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
								stat_form = $('#success_form');
								$('#success_form_overlay').fadeIn();
								stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
								setTimeout(function(){
									$('#success_form_overlay').fadeOut();
									$('.sky-form-modal').fadeOut();
								},2000)
							}
						}
					});
				}
            });
    	});
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
								$("#success_form").addClass('submited');
								jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
								stat_form = $('#success_form');
								$('#success_form_overlay').fadeIn();
								stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
								setTimeout(function(){
									$('#success_form_overlay').fadeOut();
									$('.sky-form-modal').fadeOut();
								},2000)
							}
						}
					});
				}
            });
    	});
		function remStdListChange(){
			var selectBox = document.getElementById("rem_std_list");
			var std_id = selectBox.options[selectBox.selectedIndex].value;
			if(std_id!=0){
				$.ajax({
					type: 'POST',
					url: 'get_course_for_std.php',
					data: {std_id : std_id},
					success: function(data) {
						var x=JSON.parse(data);
						var courseBox = document.getElementById('rem_course_list');
						courseBox.options.length = 0;
						if(x.length==0){
							courseBox.options[courseBox.options.length] = new Option('No courses',0);
						}else{
							for(var i=0;i<x.length;i++){
								courseBox.options[courseBox.options.length] = new Option(x[i][1], x[i][0]);
							}
						}
					}
				});
			}
		}
		function rem_course(){
			var selectBox = document.getElementById("rem_std_list");
			var std_id = selectBox.options[selectBox.selectedIndex].value;
			var courseBox = document.getElementById("rem_course_list");
			var course_id = courseBox.options[courseBox.selectedIndex].value;
			if(std_id!=0 && course_id!=0){
				$.ajax({
					type: 'POST',
					url: 'rem_course.php',
					data: {std_id : std_id, course_id : course_id},
					success: function(data) {
						if(data=="done"){
							courseBox.selectedIndex = 0;
							selectBox.selectedIndex = 0;
							$("#success_form").addClass('submited');
							jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
							stat_form = $('#success_form');
							$('#success_form_overlay').fadeIn();
							stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
							setTimeout(function(){
								$('#success_form_overlay').fadeOut();
								$('.sky-form-modal').fadeOut();
							},2000)
						}
					}
				});
			}
		}
		function addStdListChange(){
			var selectBox = document.getElementById("add_std_list");
			var std_id = selectBox.options[selectBox.selectedIndex].value;
			if(std_id==0){
				document.getElementById('new_course_tb').disabled=true;
			}else{
				document.getElementById('new_course_tb').disabled=false;
				$.ajax({
					type: 'POST',
					url: 'get_all_courses.php',
					data: {std_id : std_id},
					success: function(data) {
						var x=JSON.parse(data);
						var courseBox = document.getElementById('new_course_tb');
						courseBox.options.length = 0;
						if(x.length==0){
							courseBox.options[courseBox.options.length] = new Option('No courses',0);
						}else{
							for(var i=0;i<x.length;i++){
								courseBox.options[courseBox.options.length] = new Option(x[i][1], x[i][0]);
							}
						}
					}
				});
			}
		}
		function add_course(){
			var courseBox = document.getElementById('new_course_tb');
			var selectBox = document.getElementById("add_std_list");
			var std_id = selectBox.options[selectBox.selectedIndex].value;
			var new_course = courseBox.options[courseBox.selectedIndex].value;
			if(new_course!="" && std_id!=0){
				$.ajax({
					type: 'POST',
					url: 'add_course.php',
					data: {std_id : std_id, new_course : new_course},
					success: function(data) {
						if(data=="done"){
							selectBox.selectedIndex = 0;
							//document.getElementById('view_std_list').selectedIndex=0;
							viewStdListChange();
							$("#success_form").addClass('submited');
							jQuery("#mainbody").append('<div id="success_form_overlay" class="sky-form-modal-overlay"></div>');
							stat_form = $('#success_form');
							$('#success_form_overlay').fadeIn();
							stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
							setTimeout(function(){
								$('#success_form_overlay').fadeOut();
								$('.sky-form-modal').fadeOut();
							},2000)
						}
					}
				});
			}
		}
    	function viewStdListChange() {
			var selectBox = document.getElementById("view_std_list");
			var std_id = selectBox.options[selectBox.selectedIndex].value;
			if(std_id==0){
				document.getElementById('view_courses_section').hidden=true;
			}else{
				$.ajax({
					type: 'POST',
					url: 'get_course_for_std.php',
					data: {std_id : std_id},
					success: function(data) {
						var x=JSON.parse(data);
						document.getElementById('view_courses_section').hidden=false;
						var courseBox = document.getElementById('view_courses_list');
						courseBox.options.length = 0;
						if(x.length==0){
							courseBox.options[courseBox.options.length] = new Option('No courses for selected standard',0);
						}else{
							for(var i=0;i<x.length;i++){
								courseBox.options[courseBox.options.length] = new Option(x[i][1], x[i][0]);
							}
						}
					}
				});
			}
	   }
	
		function activate_deactivate(id,ch){
			var par = ch.parentNode.parentNode;
			if(par.getAttribute('id')=="active_list"){
				$.ajax({
					type: 'POST',
					url: 'deactivate_teacher.php',
					data: {id : id},
					success: function(data) {
						if(data=="done"){
							var child = document.getElementById('teach'+id);
							var active_parent = document.getElementById('active_list');
							var inactive_parent = document.getElementById('inactive_list').appendChild(child);
							active_parent.removeChild(child);
						}
					}
				});
			}else if(par.getAttribute('id')=="inactive_list"){
				$.ajax({
					type: 'POST',
					url: 'activate_teacher.php',
					data: {id : id},
					success: function(data) {
						if(data=="done"){
							var child = document.getElementById('teach'+id);
							var active_parent = document.getElementById('active_list').appendChild(child);
							var inactive_parent = document.getElementById('inactive_list');
							inactive_parent.removeChild(child);
						}
					}
				});
			}
		}
        </script>
    <form id="success_form" name="success_form" class="sky-form sky-form-modal">
        <div class="message">
            <i class="icon-check"></i>
            <p>Successful</p>
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
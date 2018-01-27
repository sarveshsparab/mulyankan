<?php
if(file_exists("init.php") || file_exists("dbcommands.sql")){
	header("location: init.php");
	exit();
}
?>
<?php
include_once("connect_db.php");
$sql = "SELECT * FROM config order by config_id DESC LIMIT 1";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);
if($row['act_status'] == 'c93726808d1743b3ce7c0a2a2644bff1'){
	header("location: report_login.php");
}else if($row['act_status'] == '0cf6f4b0cfa8856b98a2c630b2556383'){
	$curr_time = time(); 
	$reg_time = strtotime($row['reg_time']);
	$datediff = $curr_time - $reg_time;
	$days = floor($datediff/(60*60*24));
	if($days>$row['trial_limit']){
		header("location: trial_expire.php");
	}else{
		header("location: report_login.php");
	}
	exit();
}else{
	
}
?>
<?php
if(isset($_POST['act_bt']) && $_POST['act_bt']!=''){
	if(isset($_POST["uname"])){
		include_once("connect_db.php");
		$uname = $_POST["uname"];
		$school = $_POST["school"];
		$act_code = trim($_POST["act_code"]);
		if($uname == "" || $school == ""){
			echo "act_failed";
			exit();
		} else {
			if($act_code == ""){
				$act_status = '0cf6f4b0cfa8856b98a2c630b2556383';
				$reg_time = date();
				$sql = "insert into config (reg_name, inst_name, reg_time, act_status) values ('$uname', '$school', now() , '$act_status')";
				$query = mysqli_query($conn, $sql);
				echo "trial";
			}else{
				$act_code = md5($act_code);
				$sql = "SELECT * FROM config order by config_id DESC LIMIT 1";
				$query = mysqli_query($conn, $sql);
				$rowin = mysqli_fetch_assoc($query);
				if($rowin['act_key']==$act_code){
					$act_status = 'c93726808d1743b3ce7c0a2a2644bff1';
					$sql = "insert into config (reg_name, inst_name, reg_time, act_status) values ('$uname', '$school', now() , '$act_status')";
					$query = mysqli_query($conn, $sql);
					echo "act_success";
				}else{
					echo "act_failed";
					exit();
				}
			}
		}
		exit();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Activation</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />

    <link rel="stylesheet" href="css/demo.css" />
    <link rel="stylesheet" href="css/sky-forms.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />

    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/jquery.placeholder.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body class="bg-cyan" id="mainbody">
<div class="body body-s">
    <form id="activate_form" class="sky-form">
    <header>Activation</header>

    <fieldset>
        <section>
            <div class="row">
                <label class="label">Name</label>
                    <label class="input">
                        <i class="icon-append icon-user"></i>
                        <input type="text" name="uname" id="uname" autocomplete="off" placeholder="Name"/>
                        <b class="tooltip tooltip-bottom-right">Enter Your Name</b>
                    </label>
            </div>
        </section>

        <section>
            <div class="row">
                <label class="label">School / Institute Name</label>
                    <label class="input">
                        <i class="icon-append icon-book"></i>
                        <input type="text" name="school" id="school" autocomplete="off" placeholder="School / Institute Name"/>
                        <b class="tooltip tooltip-bottom-right">Enter School / Insitiute Name</b>
                    </label>
            </div>
        </section>
        
        <section>
            <div class="row">
                <label class="label">Activation Code (Leave Blank to proceed to trial mode)</label>
                    <label class="input">
                        <i class="icon-append icon-code"></i>
                        <input type="text" name="act_code" id="act_code" autocomplete="off" placeholder="Activation code"/>
                        <b class="tooltip tooltip-bottom-right">Enter Actiavtion Code</b>
                    </label>
            </div>
        </section>
    </fieldset>
    <footer>
        <input type="submit" class="button" id="act_bt" name="act_bt" value="Actiavte">
    </footer>
    </form>
</div>

<script type="text/javascript">
    $(function()
    {
        $("#activate_form").validate(
            {
                rules:
                {
                    uname:
                    {
                        required: true
                    },
                    school:
                    {
                        required: true
                    },
					act_code:
					{
						remote: {
								url: "act_code_check.php",
								type: "post"
							}
					}
                },
                messages:
                {
                    uname:
                    {
                        required: 'Please enter your uname'
                    },
                    school:
                    {
                        required: 'Please enter your school/institute name'
                    },
					act_code:
					{
						remote: 'Incorrect Activation Code'
					}
                },

                errorPlacement: function(error, element)
                {
                    error.insertAfter(element.parent());
                },
                submitHandler: function(form) {
					$.ajax({
						type: 'post',
						url: 'index.php',
						data: $('#activate_form').serialize(),
						success: function (result) {
							//alert(result);
						  if(result == "act_failed"){
								$("#act_failure").addClass('submited');
								jQuery("#mainbody").append('<div id="act_failure_overlay" class="sky-form-modal-overlay"></div>');
								stat_form = $('#act_failure');
								$('#act_failure_overlay').fadeIn();
								stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
								
								$('#act_failure_overlay').on('click', function(){
									$('#act_failure_overlay').fadeOut();
									$('.sky-form-modal-wide').fadeOut();
								});
								
							} else {
								$("#act_success").addClass('submited');
								jQuery("#mainbody").append('<div id="act_success_overlay" class="sky-form-modal-overlay"></div>');
								stat_form = $('#act_success');
								$('#act_success_overlay').fadeIn();
								stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
								
								
								setTimeout(function(){
									window.location = "report_login.php";
								},3000)
								
							}
						}
					});
                }
            });
    });
</script>

<form id="act_success" name="act_success" class="sky-form sky-form-modal-wide">
    <div class="message">
        <i class="icon-signin"></i>
        <p>Activation Successful</p>
        <p>You will be Automatically Redirected...</p>
    </div>
</form>

<form id="act_failure" name="act_failure" class="sky-form sky-form-modal-wide">
    <div class="message-error">
        <i class="icon-warning-sign"></i>
        <p>Activation Failed</p>
        <p>Please enter Proper Actiavtion Code and try again</p>
    </div>
</form>

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
        </div>
    </div>
</html>
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
if($user_ok == true){
	if($_SESSION["username"]=="admin")
		header("location: adminpanel.php?uname=".$_SESSION["username"]."&uid=".$_SESSION["userid"]);
	else
		header("location: teacherpanel.php?uname=".$_SESSION["username"]."&uid=".$_SESSION["userid"]);
    exit();
}
?>
<?php
if(isset($_POST['login_bt']) && $_POST['login_bt']!=''){
	if(isset($_POST["uname"])){
		include_once("connect_db.php");
		$uname = mysqli_real_escape_string($conn, $_POST["uname"]);
		$pass = md5($_POST["password"]);
		$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
		if($uname == "" || $pass == ""){
			echo "login_failed";
			exit();
		} else {
			$sql = "SELECT teacher_id, teacher_name, teacher_pass FROM teacher WHERE teacher_name='$uname' AND activated>0 LIMIT 1";
			$query = mysqli_query($conn, $sql);
			$row = mysqli_fetch_row($query);
			$db_id = $row[0];
			$db_username = $row[1];
			$db_pass_str = $row[2];
			if($pass != $db_pass_str){
				echo "login_failed";
				exit();
			} else {
				// CREATE THEIR SESSIONS AND COOKIES
				$_SESSION['userid'] = $db_id;
				$_SESSION['username'] = $db_username;
				$_SESSION['password'] = $db_pass_str;
				setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
				setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
				setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE);
				// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
				$sql = "UPDATE teacher SET ip='$ip', lastlogin=now() WHERE teacher_name='$db_username' LIMIT 1";
				$query = mysqli_query($conn, $sql);
				echo json_encode(array($uname,$db_id));
				exit();
			}
		}
		exit();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />

    <link rel="stylesheet" href="css/demo.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/sky-forms.css" />

    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/jquery.placeholder.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body class="bg-cyan" id="mainbody">
<div class="body body-s">
    <form id="login_form" class="sky-form">
    <header>Login</header>

    <fieldset>
        <section>
            <div class="row">
                <label class="label col col-4">Username</label>
                <div class="col col-8">
                    <label class="input">
                        <i class="icon-append icon-user"></i>
                        <input type="text" name="uname" id="uname" autocomplete="off" placeholder="Username"/>
                        <b class="tooltip tooltip-bottom-right">Enter Username</b>
                    </label>
                </div>
            </div>
        </section>

        <section>
            <div class="row">
                <label class="label col col-4">Password</label>
                <div class="col col-8">
                    <label class="input">
                        <i class="icon-append icon-lock"></i>
                        <input type="password" name="password" id="password" autocomplete="off" placeholder="Password"/>
                        <b class="tooltip tooltip-bottom-right">Enter Password</b>
                    </label>
                    <br />
                    <div class="note"><a style="color:#3256E9; text-decoration:none" href="pass_recover.php" >Forgot Password?</a></div>
                </div>
            </div>
        </section>
    </fieldset>
    <footer>
        <input type="submit" class="button" id="login_bt" name="login_bt" value="Log In">
        <a href="register.php" class="button button-secondary">Register</a>
    </footer>
    </form>
</div>

<script type="text/javascript">
    $(function()
    {
        $("#login_form").validate(
            {
                rules:
                {
                    uname:
                    {
                        required: true
                    },
                    password:
                    {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    }
                },
                messages:
                {
                    uname:
                    {
                        required: 'Please enter your username'
                    },
                    password:
                    {
                        required: 'Please enter your password'
                    }
                },

                errorPlacement: function(error, element)
                {
                    error.insertAfter(element.parent());
                },
                submitHandler: function(form) {
					$.ajax({
						type: 'post',
						url: 'report_login.php',
						data: $('#login_form').serialize(),
						success: function (result) {
						  if(result == "login_failed"){
								$("#login_failure").addClass('submited');
								jQuery("#mainbody").append('<div id="login_failure_overlay" class="sky-form-modal-overlay"></div>');
								stat_form = $('#login_failure');
								$('#login_failure_overlay').fadeIn();
								stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
								
								$('#login_failure_overlay').on('click', function(){
									$('#login_failure_overlay').fadeOut();
									$('.sky-form-modal-wide').fadeOut();
								});
								
							} else {
								var r = JSON.parse(result);
								$("#login_success").addClass('submited');
								jQuery("#mainbody").append('<div id="login_success_overlay" class="sky-form-modal-overlay"></div>');
								stat_form = $('#login_success');
								$('#login_success_overlay').fadeIn();
								stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
								if(r[0] == "admin"){
									setTimeout(function(){
										window.location = "adminpanel.php?uname="+r[0]+"&uid="+r[1];
									},4000)
								}else{
									setTimeout(function(){
										window.location = "teacherpanel.php?uname="+r[0]+"&uid="+r[1];
									},4000)
								}
							}
						}
					});
                }
            });
    });
</script>

<form id="login_success" name="login_success" class="sky-form sky-form-modal-wide">
    <div class="message">
        <i class="icon-signin"></i>
        <p>Login Successful</p>
        <p>You will be Automatically Redirected...</p>
    </div>
</form>

<form id="login_failure" name="login_failure" class="sky-form sky-form-modal-wide">
    <div class="message-error">
        <i class="icon-warning-sign"></i>
        <p>Login Failed</p>
        <p>Please enter Proper Credentials and try again</p>
        <p>Or wait till account is activated.</p>
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
                        <input type="text" name="report_uname" id="report_uname" >
                    </label>
                </section>
                <section class="col col-6">
                    <label class="label">E-mail</label>
                    <label class="input">
                        <i class="icon-append fa fa-at"></i>
                        <input type="email" name="report_email" id="report_email">
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
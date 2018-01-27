<?php
if(isset($_POST['reg_submit']) && $_POST['reg_submit']!=''){
	if(isset($_POST["uname"])){
		include_once("connect_db.php");
		$uname = mysqli_real_escape_string($conn, $_POST["uname"]);
		$email = $_POST["email"];
		$pass = md5($_POST["password"]);
		$passConfirm = md5($_POST["passwordConfirm"]);
		if($uname == "" || $email == ""){
			echo "register_failed";
			exit();
		} else if($pass!=$passConfirm){
			echo "register_failed";
			exit();
		} else if(mysqli_num_rows(mysqli_query($conn, "SELECT teacher_id FROM teacher WHERE teacher_name='$uname' LIMIT 1"))>0){
			echo "register_failed";
			exit();
		} else if(mysqli_num_rows(mysqli_query($conn, "SELECT teacher_id FROM teacher WHERE teacher_email='$email' LIMIT 1"))>0){
			echo "register_failed";
			exit();
		} else {
			$sql = "INSERT INTO `teacher` (teacher_name, teacher_email, teacher_pass) VALUES ('$uname','$email','$pass')";
			$query = mysqli_query($conn, $sql);
			echo "register_success";
			exit();
		}
		exit();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />

    <link rel="stylesheet" href="css/demo.css" />
    <link rel="stylesheet" href="css/sky-forms.css" />

    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/jquery.placeholder.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body class="bg-cyan" id="mainbody">
<div class="body body-s">
    <form id="register-form" class="sky-form" />
    <header>Registration form</header>

    <fieldset>
        <section>
            <label class="input">
                <i class="icon-append icon-user"></i>
                <input type="text" name="uname" id="uname" placeholder="Username" autocomplete="off"/>
                <b class="tooltip tooltip-top-right">Choose a Username</b>
            </label>
        </section>

        <section>
            <label class="input">
                <i class="icon-append icon-envelope-alt"></i>
                <input type="email" name="email" id="email" placeholder="Email address" autocomplete="off"/>
                <b class="tooltip tooltip-top-right">Enter your Email Address</b>
            </label>
        </section>

        <section>
            <label class="input">
                <i class="icon-append icon-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" id="password" autocomplete="off"/>
                <b class="tooltip tooltip-top-right">Choose a strong password</b>
            </label>
        </section>

        <section>
            <label class="input">
                <i class="icon-append icon-lock"></i>
                <input type="password" name="passwordConfirm" id="passwordConfirm" placeholder="Confirm password" autocomplete="off"/>
                <b class="tooltip tooltip-top-right">Re-enter password</b>
            </label>
        </section>
    </fieldset>
    <footer>
        <input type="submit" class="button" id="reg_submit" name="reg_submit" value="Register">
        <a href="report_login.php" class="button button-secondary">Back</a>
    </footer>
    </form>
</div>

<script type="text/javascript">
    $(function()
    {
        // Validation
        $("#register-form").validate(
            {
                // Rules for form validation
                rules:
                {
                    uname:
                    {
                        required: true,
						remote: {
							url: "live_uname_check.php",
							type: "post"
						}
                    },
                    email:
                    {
                        required: true,
                        email: true,
						remote: {
							url: "live_email_check.php",
							type: "post"
						}
                    },
                    password:
                    {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    passwordConfirm:
                    {
                        required: true,
                        minlength: 3,
                        maxlength: 20,
                        equalTo: '#password'
                    }
                },

                // Messages for form validation
                messages:
                {
                    uname:
                    {
                        required: 'Please enter a desired username',
						remote: 'Username already taken'
                    },
                    email:
                    {
                        required: 'Please enter your email address',
                        email: 'Please enter a VALID email address',
						remote: 'Email address already registered'
                    },
                    password:
                    {
                        required: 'Please enter your password'
                    },
                    passwordConfirm:
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
					$.ajax({
						type: 'post',
						url: 'register.php',
						data: $('#register-form').serialize(),
						success: function (result) {
						  if(result == "register_failed"){
								$("#register_failure").addClass('submited');
								jQuery("#mainbody").append('<div id="register_failure_overlay" class="sky-form-modal-overlay"></div>');
								stat_form = $('#register_failure');
								$('#register_failure_overlay').fadeIn();
								stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
								
								$('#register_failure_overlay').on('click', function(){
									$('#register_failure_overlay').fadeOut();
									$('.sky-form-modal-wide').fadeOut();
								});
								
							} else {
								$("#register_success").addClass('submited');
								jQuery("#mainbody").append('<div id="register_success_overlay" class="sky-form-modal-overlay"></div>');
								stat_form = $('#register_success');
								$('#register_success_overlay').fadeIn();
								stat_form.css('top', '30%').css('left', '50%').css('margin-top', -stat_form.outerHeight()/2).css('margin-left', -stat_form.outerWidth()/2).fadeIn();
								
								setTimeout(function(){
									window.location = "report_login.php";
								},4000)
							}
						}
					});
                }
            });
    });
</script>

<form id="register_success" name="register_success" class="sky-form sky-form-modal-wide">
    <div class="message">
        <i class="icon-ok-sign"></i>
        <p>Registeration Successful</p>
        <p>You will be Automatically Redirected...</p>
    </div>
</form>

<form id="register_failure" name="register_failure" class="sky-form sky-form-modal-wide">
    <div class="message-error">
        <i class="icon-warning-sign"></i>
        <p>Registeration Failed</p>
        <p>Try again in some time...</p>
        <p>Sorry for the inconvenience</p>
    </div>
</form>

</body>
</html>
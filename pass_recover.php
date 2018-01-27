<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Password Recovery</title>
		<link rel="stylesheet" href="css/demo.css" />
    	<link rel="stylesheet" href="css/sky-forms.css" />
        <link rel="stylesheet" href="css/font-awesome.min.css"> <!-- icons css -->
		<script src="js/jquery-1.9.1.min.js"></script>
		<script src="js/jquery.form.min.js"></script>
		<script src="js/jquery.validate.min.js"></script>
</head>

<body class="bg-cyan">
		<div class="body body-s">	
        	
			<form method="post" action="" id="forgotpassform" class="sky-form">
            <header>Password Recovery</header>
				<fieldset>					
					<section>
						<label class="input" for="uname">
							<i class="icon-append icon-user"></i>
							<input autocomplete="off" type="text" name="uname" id="uname" placeholder="Username"/>
                            <div class="status-message" align="right" id="check-status"></div>
							<b class="tooltip tooltip-bottom-right">Username which needs recovery</b>
						</label>
					</section>
                    </fieldset>
                    <footer>
                    <input type="submit" class="button" name="probutton" id="probutton" value="Proceed"/>
                    <button type="button" class="button button-secondary"><a style="text-decoration:none; color:#FFF;" href="report_login.php">Back</a></button>
				</footer>
                <div class="message">
					<i class="icon-ok"></i><font size="-1">
					<p>Your password reset request is put for processing</p>
                    <p>Contact your administrator for futher procedures</p></font>
				</div>
                </form>
            
           <script type="text/javascript">
			$(function()
			{
				// Validation		
				$("#forgotpassform").validate(
				{					
					// Rules for form validation
					rules:
					{
						uname:
						{
							required: true,
							minlength : 4,
							remote: {
								url: "live_uname_check_recover.php",
								type: "post"
							}
						}
					},
					
					// Messages for form validation 
					messages:
					{
						uname:
						{
							required: 'Please enter your username',
							minlenght : 'Please enter your username',
							remote: 'Username doesnt Exist'
						}
					},					
					submitHandler: function(form)
					{
						var uname=document.getElementById('uname').value;
						$(form).ajaxSubmit(
						{
							type: "POST",
							url: 'forgot_pass.php',
							data: {uname: uname },
							success: function(data)
							{
								$("#forgotpassform").addClass('submited');
								setTimeout(function(){
									window.location="report_login.php";
								},2000);
							}
						});
					},
					// Do not change code below
					errorPlacement: function(error, element)
					{
						error.insertAfter(element.parent());
					}
				});
			});			
		</script>
        </div>
</body>
</html>
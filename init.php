<?php
if(isset($_POST['init_bt']) && $_POST['init_bt']!=''){
	
	$uname = $_POST['uname'];
	$school = $_POST['school'];
	
	$host = $_POST['host'];
	$dbuser = $_POST['dbuser'];
	$dbpass = $_POST['dbpass'];
	$dbname = $_POST['dbname'];
	
	$adminuser = 'admin';
	$adminemail = $_POST['adminemail'];
	$adminpass = md5($_POST['adminpass']);
	$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	
	$connfilepath = "connect_db.php";
	$dbcmdpath = "dbcommands.sql";
		
	if(file_exists($connfilepath)){
		if (!unlink($connfilepath)){
			echo "conn_delete_error";
			exit();
		}
	}
	$connfile = fopen($connfilepath, "a");
	$conntxt = "<?php"."\n".'$HOST = "'.$host.'";'."\n".'$USER = "'.$dbuser.'";'."\n".'$PASSWORD = "'.$dbpass.'";'."\n".'$DATABASE = "'.$dbname.'";'."\n".'$conn = mysqli_connect($HOST,$USER,$PASSWORD,$DATABASE);'."\n".'if (mysqli_connect_errno()){'."\n\t".'echo "db_conn_error";exit();'."\n".'}'."\n".'?>';
	fwrite($connfile, $conntxt);
	fclose($connfile);
	
	include_once($connfilepath);
	
	$dbcommands = fopen($dbcmdpath, "r");
	if ($dbcommands) {
		while (($line = fgets($dbcommands)) !== false) {
			$query = mysqli_query($conn, $line);
			if(!$query){
				echo "query_failed";
				exit();
			}
		}
		fclose($dbcommands);
	} else {
		echo "db_cmd_error";
		exit();
	} 
	
	$sql = "INSERT INTO `config`(`reg_name`, `inst_name`) VALUES ('$uname','$school')";
	$query = mysqli_query($conn, $sql);
	if(!$query){
		echo "config_query_failed";
		exit();
	}
	$sql = "INSERT INTO `teacher`(`teacher_name`, `teacher_pass`, `teacher_email`, `ip`, `lastlogin`, `activated`, `session_id`) VALUES ('$adminuser','$adminpass','$adminemail','$ip',now(),2,0)";
	$query = mysqli_query($conn, $sql);
	if(!$query){
		echo "admin_query_failed";
		exit();
	}
	
	unlink($dbcmdpath);
	unlink(__FILE__);
	echo "init_success";
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Initiation</title>

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
<div class="body">
    <form id="init_form" class="sky-form">
    <header>Initialization</header>

    <fieldset>
    	<div class="row">
            <label class="label">Personal Details</label>
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append icon-user"></i>
                    <input type="text" name="uname" id="uname" autocomplete="off" placeholder="Name"/>
                    <b class="tooltip tooltip-bottom-right">Enter Your Name</b>
                </label>
            </section>
    
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append icon-book"></i>
                    <input type="text" name="school" id="school" autocomplete="off" placeholder="School / Institute Name"/>
                    <b class="tooltip tooltip-bottom-right">Enter School / Insitiute Name</b>
                </label>
            </section>
    	</div>
    </fieldset>
    <fieldset>
    <label class="label">Database Details</label>
    	<div class="row">
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-road"></i>
                    <input type="text" name="host" id="host" autocomplete="off" placeholder="Database Host"/>
                    <b class="tooltip tooltip-bottom-right">Enter Database Hostname</b>
              </label>
            </section>
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-user"></i>
                    <input type="text" name="dbuser" id="dbuser" autocomplete="off" placeholder="Database Username"/>
                    <b class="tooltip tooltip-bottom-right">Enter Database Username</b>
              </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-key"></i>
                    <input type="text" name="dbpass" id="dbpass" autocomplete="off" placeholder="Database Password"/>
                    <b class="tooltip tooltip-bottom-right">Enter Database Password</b>
              </label>
            </section>
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-database"></i>
                    <input type="text" name="dbname" id="dbname" autocomplete="off" placeholder="Database Name"/>
                    <b class="tooltip tooltip-bottom-right">Enter Database Name</b>
              </label>
              <div class="note"><strong>Note: </strong>Database Needs to be created first.</div>
            </section>
        </div>
    </fieldset>
    <fieldset>
    <label class="label">Admin Details</label>
    <div class="row">
            <section class="col col-4">
                <label class="input">
                    <i class="icon-append fa fa-user"></i>
                    <input type="text" name="adminuser" id="adminuser" autocomplete="off" value="admin"/>
                    <b class="tooltip tooltip-bottom-right">Enter Admin Username</b>
              </label>
            </section>
            <section class="col col-4">
                <label class="input">
                    <i class="icon-append fa fa-envelope"></i>
                    <input type="email" name="adminemail" id="adminemail" autocomplete="off" placeholder="Admin Email"/>
                    <b class="tooltip tooltip-bottom-right">Enter Admin Email</b>
              </label>
            </section>
            <section class="col col-4">
                <label class="input">
                    <i class="icon-append fa fa-key"></i>
                    <input type="text" name="adminpass" id="adminpass" autocomplete="off" placeholder="Admin Password"/>
                    <b class="tooltip tooltip-bottom-right">Enter Admin Password</b>
              </label>
            </section>
        </div>
    </fieldset>
    <footer>
        <input type="submit" class="button" id="init_bt" name="init_bt" value="Initiate">
    </footer>
    </form>
</div>

<script type="text/javascript">
    $(function()
    {
        $("#init_form").validate(
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
                    host:
                    {
                        required: true
                    },
                    dbuser:
                    {
                        required: true
                    },
                    dbpass:
                    {
                        required: true
                    },
                    dbname:
                    {
                        required: true
                    },
                    adminuser:
                    {
                        required: true
                    },
                    adminemail:
                    {
                        required: true,
						email: true
                    },
                    adminpass:
                    {
                        required: true
                    }
                },
                messages:
                {
                    uname:
                    {
                        required: 'Enter your Name'
                    },
                    school:
                    {
                        required: 'Enter your School/Institute name'
                    },
                    host:
                    {
                        required: 'Enter Database Hostname'
                    },
                    dbuser:
                    {
                        required: 'Enter Database Username'
                    },
                    dbpass:
                    {
                        required: 'Enter Database Password'
                    },
                    dbname:
                    {
                        required: 'Enter Database Name'
                    },
                    adminuser:
                    {
                        required: 'Enter Admin Username'
                    },
                    adminemail:
                    {
                        required: 'Enter Admin Email',
						email: 'Enter valid Email'
                    },
                    adminpass:
                    {
                        required: 'Enter Admin Password'
                    }
                },

                errorPlacement: function(error, element)
                {
                    error.insertAfter(element.parent());
                },
                submitHandler: function(form) {
					$.ajax({
						type: 'post',
						url: 'init.php',
						data: $('#init_form').serialize(),
						success: function (result) {
							console.log(result);								
							if(result=="init_success"){
								showSuccessMsg('<p>Success</p><p>The application has been initialized.</p>');
								setTimeout(function(){
									window.location = "index.php";
								},50)
								
							} else if(result=="db_conn_error") {
								showFailureMsg('<p>Error</p><p>Failed to connect to your database</p>');
							} else if(result=="conn_delete_error") {
								showFailureMsg('<p>Error</p><p>Failed to create connect_db.php</p>');
							} else if(result=="db_cmd_error") {
								showFailureMsg('<p>Error</p><p>Failed to read db_commands.sql</p>');
							} else if(result=="query_failed") {
								showFailureMsg('<p>Error</p><p>Failed to run query</p>');
							} else if(result=="config_query_failed") {
								showFailureMsg('<p>Error</p><p>Failed to run config query</p>');
							} else if(result=="admin_query_failed") {
								showFailureMsg('<p>Error</p><p>Failed to run admin query</p>');
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
</body>
<div class="footer" style="z-index:0;">
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
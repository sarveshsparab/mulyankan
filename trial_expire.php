<!DOCTYPE html>
<html>
<head>
    <title>Product Expired</title>

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
<form id="activate_form" name="activate_form" class="sky-form">
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
</div>
<script>
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
</html>
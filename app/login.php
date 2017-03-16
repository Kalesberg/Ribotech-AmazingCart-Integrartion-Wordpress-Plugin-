<?php
    include_once "config.inc.php";
    
    $config = new JConfig();
    
    $status = isset($_REQUEST["status"])?$_REQUEST["status"]:"";
?>
<html>
    <head>
        <title><?php echo $config->sitename;?></title>
        <meta name="Author" content="">
        <meta name="Keywords" content="">
        <meta name="Description" content="">
                
        <link href="css/system.css" rel="stylesheet" media="screen" type="text/css" />
        <link href="css/topmenu.css" rel="stylesheet" media="screen" type="text/css" />
        <link href="css/controls.css" rel="stylesheet" media="screen" type="text/css" />
    
        <script type="text/javascript" src="js/jquery-1.6.2.js"></script>
        <script type="text/javascript" src="js/jquery.validate-1.5.5.js"></script>
    </head>
    <script type="text/javascript">
        $(document).ready( function () {
            $("#admin_username").focus();
            jQuery.validator.messages.required = "";
            jQuery('#login_form').validate({});
        });
    </script>
    <body class="loginform">
        <div class="container">
            <h1>Welcome to <?php echo $config->sitename;?></h1>
            <br/>
            <form action="confirm.php" class="login" id="login_form" name="login_form" method="post">
                <fieldset style="width:350px;">
                    <legend>Login</legend>
                    <div class="item">
                        <label for="admin_username" class="logintxt">Username:</label>
                        <input id="admin_username" class="textfield input required" type="text" size="50" value="" name="admin_username" autocomplete="off"></input>
                    </div>
                    <div class="item">
                        <label for="admin_password" class="logintxt">Password:</label>
                        <input id="admin_password" class="textfield input required" type="password" size="50" value="" name="admin_password"></input>
                    </div>
                </fieldset>
                <fieldset class="tblFooters" style="width:350px;">
                    <input id="input_go" name="submit" type="submit" value="Login"></input>
                </fieldset>
            </form>
            <?php
                if( strlen($status) > 0 )
                {
                    echo '<div class="errormsg">Your Username or Password is incorrect.</div>';
                }
            ?>
        </div>
    </body>
</html>

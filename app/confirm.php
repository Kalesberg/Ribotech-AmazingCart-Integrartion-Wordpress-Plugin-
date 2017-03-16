<?php
    //error_reporting(E_ALL & ~E_NOTICE);
    //ini_set('display_errors', E_ALL);
    
    include_once "config.inc.php";
    include_once "includes/class_database.php";
    include_once "includes/session.php";
    
    $config = new JConfig();
    
    $db = new JDatabase($config->dbhost, $config->dbname, $config->dbuser, $config->dbpassword);
    
    if( isset( $_POST["submit"] ) )
    {
        $userid = mysql_real_escape_string(htmlspecialchars($_POST["admin_username"]));
        $passwd = mysql_real_escape_string(htmlspecialchars($_POST["admin_password"]));
        
        $admin = $db->get_result("SELECT id, username, userid, passwd FROM tbl_admin WHERE userid='$userid' AND passwd='$passwd'");
		
		if( count($admin) > 0 )
        {
            $_SESSION["aidx"] = $admin[0]["id"];
            $_SESSION["cuname"] = $userid;
            $_SESSION["cpasswd"] = $passwd;
            
            header("Location: index.php");
        }
        else
        {
            header("Location: login.php?status=error");
        }
    }
?>
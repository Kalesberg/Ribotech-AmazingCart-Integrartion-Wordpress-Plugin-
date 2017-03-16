<?php
    include_once "config.inc.php";
    include_once "includes/class_database.php";
    // include_once "includes/session.php";
    
    $config = new JConfig();
    
    $db = new JDatabase($config->dbhost, $config->dbname, $config->dbuser, $config->dbpassword);
      
    if(isset($_POST['action']) && $_POST['action'] == "changepassword" )
    {
        $new_password = $_POST["new_password"];
        // $result = mysql_query("UPDATE tbl_admin SET passwd='$new_password' WHERE id=".$admin_idx) or die("failed");
        $result = mysql_query("UPDATE tbl_admin SET passwd='$new_password' WHERE id=1") or die("failed");
        
        // $_SESSION["cpasswd"] = $new_password;
        
        echo "success";
    }
?>
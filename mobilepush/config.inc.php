<?php
	
	define("SEND_PUSH_MODE", "production");
	define("SEND_PUSH_MODE", "sandbox");

    class JConfig {
        
        var $sitename = "Que Buena Tulsa App Administration";
        
        /******** Database Settings *********/
        var $dbtype = "mysql";
        var $dbhost = "localhost";
        var $dbname = "ribotech_quebuena";
        // var $dbuser = "ribotech_user";
        // var $dbpassword = "quebuena1";
        var $dbuser = "root";
        var $dbpassword = "";
        
        var $APNS_PRODUCTION_CER= "ChatPush_Dist.pem";
        var $APNS_SANDBOX_CER	= "ChatPush_Dev.pem";
        var $APNS_LOG_FILE		= "apns_push.log";
        
        var $ANDROID_GCM_KEY	= "AIzaSyD4agY8aTO2PbGMrHssBFpVcQV_7mia0Cg";
     
    }
    
    define("JDEBUG", 0);
    
    //date_default_timezone_set("Asia/Chongqing");
    //date_default_timezone_set("America/Denver");
?>
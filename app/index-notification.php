<?php
	// Insert errors
   // error_reporting(E_ALL & ~E_NOTICE);
   // ini_set('display_errors', E_ALL);
	
	//include all filles
    include_once "config.inc.php";
    include_once "includes/class_database.php";
    include_once "includes/functions.lib.php";
    // include_once "includes/session.php";
	//Data base connection 
    $config = new JConfig();
    $db = new JDatabase($config->dbhost, $config->dbname, $config->dbuser, $config->dbpassword);
	
	//Connection request
    if( isset($_REQUEST["action"]) &&  $_REQUEST["action"]=="send" )
    {
		$message = $_REQUEST["notify_text"];	
		$deviceTokenArray = $db->get_result("SELECT * FROM tbl_devicetoken");
		$iOSTokenArray = array();	
		foreach ($deviceTokenArray as $deviceToken){    
			if ( $deviceToken["platform"] == "iphone" ){
			array_push($iOSTokenArray, $deviceToken["token"]);
			} else if( $deviceToken["platform"] == "android" )
			{
			sendAndroid_Push($message, $deviceToken["token"], $config->ANDROID_GCM_KEY);
			}
		}
			
		if( count($iOSTokenArray) > 0 ) 
		{
			sendIOS_Push($message, $iOSTokenArray);
		}
    }
	
    //Send push to IOS 
    function sendIOS_Push($message, $device_token_list)
    {
	for ($i = 0; $i < count($device_token_list); $i++) {     
	    $deviceToken = $device_token_list[$i];

	    // Pruduction and Development settings
	    $passphrase = '';
	    $productmode = true;
	    $developmentmode = true;
  
	    $body['aps'] = array('alert' => $message,
                            'sound' => 'default',
                             );
        
	    // Encode the payload as JSON
	    $payload = json_encode($body);
	    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        
	    if ($productmode)
	    {
	        //Production push 
	        $ctx = stream_context_create();
	        stream_context_set_option($ctx, 'ssl', 'local_cert', 'ChatPush_Dist.pem');
		
	        if ($passphrase)
				stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);	    
			$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err,
                                       $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp)
		    exit("Failed to connect: $err $errstr" . PHP_EOL);    
		$result = fwrite($fp, $msg, strlen($msg));	
		fclose($fp);
	    }
        
	    if ($developmentmode)
	    {
	       //Desvelopment push 
	        $ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', 'ChatPush_Dev.pem');
		
		if ($passphrase)
		    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		    
		$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,
                                       $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp)
		    exit("Failed to connect: $err $errstr" . PHP_EOL);	    
		$result = fwrite($fp, $msg, strlen($msg));
		fclose($fp);
	    }
	}
        
	return 1;
    }
    
    //Send to Android Push Notification
    function sendAndroid_Push($message, $registration_id, $api_key)
    {
	include_once "includes/class_android_gcm.php";
	$androidLogPath = dirname(__FILE__)."/gcm.log";
	$gcm = new AndroidGCM($api_key, $androidLogPath);
	$gcm->processQueue($registration_id, $message);
	unset($gcm);
    }

?>
<link rel="stylesheet" href="<?php echo PLUGIN_DIR . 'app/css/system.css'; ?>" />
<div id="pagecontainer">
    <fieldset>
        <legend>
            Push Notification
        </legend>
		<form method="post" action="" name="notify_frm" id="notify_frm">
		<div class="genaral-row-div" style="font:bold 16px/18px Arial, Helvetica, sans-serif;">
				Notification Message:
			</div>
			<div class="genaral-row-div">
				<textarea name="notify_text" id="notify_text" style="width:600px;height:150px;" class="contents input required"></textarea>
			</div>
			<div class="genaral-row-div">
				<input type="hidden" name="action" id="action" value="send">
				<input type="submit" value=" Send " style="margin-top:10px;margin-left:500px;">
			</div>
		</form>
	</fieldset>
   <fieldset class="tblFooters"></fieldset>
</div>

<script type="text/javascript">
	$(document).ready( function() {
		jQuery.validator.messages.required = "";
        jQuery("#notify_frm").validate({
            submitHandler : function(form) {
                jQuery(form).ajaxSubmit({
                    target: "",
                    beforeSend : function(msg) {
                    },
                    success : function(msg) {

                        $("#maincontainer").html(msg);
                    }
                });
           },
            invalidHandler : function(form, validator) {
            }

        });

    });	

</script>
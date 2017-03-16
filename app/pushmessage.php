<?php
	include_once "config.inc.php";
	if( !isset($_POST['devToken') || !isset($_POST['cert']) ) die('Illegal actino');
	
	$message = $_POST['body'];
	$token = $_POST['devToken'];
	$device = $_POST['device'];
	$pem = $_POST['cert'];
	$pass = $_POST['pass'];
	$env = $_POST['enviroment'];
   
	if ( $device == "iphone" ){
		sendIOS_Push($message, $token, $pem, $pass, $env);
	}
	else if( $device == "android" )
	{
		sendAndroid_Push($message, $token, $config->ANDROID_GCM_KEY);
	}

	
    //Send push to IOS 
    function sendIOS_Push($message, $deviceToken, $pem, $pass, $env)
    {	   
	    $passphrase = trim($pass);
  
	    $body['aps'] = array('alert' => $message,
                            'sound' => 'default',
                             );
        
	    // Encode the payload as JSON
	    $payload = json_encode($body);
	    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $pem);
		
		if($env == 'production') {
			if ($passphrase)
				stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);	    
			$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err,
									   $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
		
			if (!$fp)
				exit("Failed to connect: $err $errstr" . PHP_EOL);    
			$result = fwrite($fp, $msg, strlen($msg));	
			fclose($fp);
		}
	    elseif($env == 'sandbox') {
	       //Desvelopment push 
	        $ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $pem);
			
			if ($passphrase)
				stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
				
			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,
										   $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
			
			if (!$fp)
				exit("Failed to connect: $err $errstr" . PHP_EOL);	    
			$result = fwrite($fp, $msg, strlen($msg));
			fclose($fp);
		}
	
		return 1;
    }
    
    //Send to Android Push Notification
    function sendAndroid_Push($message, $registration_id, $api_key)
    {
		include_once "class_android_gcm.php";
		$androidLogPath = dirname(__FILE__)."/gcm.log";
		$gcm = new AndroidGCM($api_key, $androidLogPath);
		$gcm->processQueue($registration_id, $message);
		unset($gcm);
		
		return 1;
    }

?>
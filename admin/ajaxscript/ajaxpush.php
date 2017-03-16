<?php 
header('Content-type: text/html; charset=utf-8');
header('HTTP/1.1 200 OK');
session_start(); 


$post = new pushnotification;

if(get_option("amazingcart_ApnsEnv") == 1)
{
	
	
	$nameEnv = "sandbox";
	$pemEnv = get_option("amazingcart_pem");
}
else
{
	$nameEnv = "production";
	$pemEnv = get_option("amazingcart_pem_production");
}

 		global $wpdb;
        $sql = "SELECT DISTINCT pushTokenID,deviceID,device FROM `".$wpdb->prefix."amazingcart_user` WHERE pushTokenID IS NOT NULL AND indicator='0' LIMIT 1";
		$results = $wpdb->get_results($sql);
		$count=0;
		
		foreach ($results as $result) {
			$count++;
			$post->pushMessage($_SESSION['pushMsg'],$result->pushTokenID,$result->device,get_option("amazingcart_pemPassword"),$nameEnv,$pemEnv,$_SESSION['pushType'],$_SESSION['pushID']);
			$post->updateIndicator($result->deviceID);
		}
		
		
		echo json_encode(array("total_user_left"=>$post->getTotalPushLeft()));

 
 
?>
<?php
class pushnotification
{
	
	
	public function init()
	{
		add_action('template_redirect', array($this,'template_redirect'), 1);
	
		
		
		add_action('comment_post', array($this,'onCommentPost'));
		
		add_action('woocommerce_new_customer_note', array($this,'onAddNote'));
		add_action('woocommerce_order_status_changed', array($this,'onStatusChange'));
		
		
	}
	
	
	function check_if_user_is_login($wpuserID)
	{
		
		global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user` WHERE wpuserid='".$wpuserID."' AND is_login='1' ORDER BY no DESC LIMIT 1";
		$results = $wpdb->get_results($sql);
		
		if($results){
			
			return true;	
		}
		else
		{
			return false;
		}
	}
	
	
	function onCommentPost($commentID){
		
		global $wpdb;

 		
		$comment = get_comment($commentID);
		
		$commentParent = get_comment($comment->comment_parent);
		
		$results = $this->getAllCommentFollower($commentParent->comment_ID);
		
		//1st we check all the follower inside this comment
		foreach($results as $result)
		{
			$user = get_user_by( 'id', $result->wpuserID );
			
			//2nd we check if the comment author available in follower table if not just ignore.. we dont want send a notification to his/her 
			if($result->wpuserID == $comment->user_id)
			{
				
				
			}
			else
			{
			
				
				
				$commentContent = strip_tags(stripslashes($comment->comment_content));
				
				//4th if parent comment author is same with follower we say YOU if not we say the comment parent author name
				if($commentParent->user_id == $result->wpuserID)
				{
					$notification = "".$comment->comment_author." commented on your reviewed : ".$commentContent.""; //For other follower
				}
				else
				{
				$notification = "".$comment->comment_author." commented on ".$commentParent->comment_author." reviewed : ".$commentContent.""; //For other follower
				}
				
				 $qry = "INSERT INTO ".$wpdb->prefix."amazingcart_user_notification (notification, unixtime, type,objectID,userID) values('".$notification."','".date('U')."','comment','".$commentParent->comment_ID."','".$result->wpuserID."')";
 		 $wpdb->query($qry);
		 
		 
		 		//5th Then we try to see if the follower is using iOS Mobile
				if($this->check_the_user_if_they_are_on_ios($result->wpuserID) == true)
				{
					//If true then we check how many device they got to send push notification
					$userDevices = $this->get_user_token_key($result->wpuserID);
					
					$notificationLimit = substr($notification, 0, 160);
					
					$pushMsg = $notificationLimit."...";
					//then we send them a push notification on every mobile they registered hook with thier wp_userid
					foreach($userDevices as $userDevice)
					{
						if($userDevice->pushTokenID == "")
						{
							//If token device empty.. just ignore to make it fast...maybe they not subscribe yet...
						}
						else
						{
							if(get_option( 'amazingcart_comment_follow_up_push' ) == 1)
							{
								
								if($this->check_if_user_is_login($result->wpuserID) == true)
								{
								
						$this->push_programitically($userDevice->pushTokenID,stripslashes($pushMsg),$commentParent->comment_ID,"comment");
								}
							}
						}
						
					}
					
				}
				
				
			}
			
		}
			
		 
	}
	
	function getAllCommentFollower($commentID)
	{
		
		global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user_comment_followed` WHERE commentID='".$commentID."'";
		$results = $wpdb->get_results($sql);
		
		return $results;
	}
	
	function check_the_user_if_they_are_on_ios($wpuserID)
	{
		
		global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user` WHERE wpuserid='".$wpuserID."' ORDER BY no DESC";
		$results = $wpdb->get_results($sql);
		
		if($results){
			
			return true;	
		}
		else
		{
			return false;
		}
	}
	
	
	function get_user_token_key($wpuserID)
	{
		
		global $wpdb;
        
        $sql = "SELECT DISTINCT pushTokenID FROM `".$wpdb->prefix."amazingcart_user` WHERE wpuserid='".$wpuserID."'";
		$results = $wpdb->get_results($sql);
		
		return $results;
	}
	
	function onAddNote($order){
		
		global $wpdb;

 		
		$notification = "A note has just been added to your order #".$order['order_id']." : ".$order['customer_note']."";
		
		
		
		
		
		if($this->check_if_order_using_ios($order['order_id']) == true)
		{
		$orderDe = new WC_Order($order['order_id']);
		
 		 $qry = "INSERT INTO ".$wpdb->prefix."amazingcart_user_notification (notification, unixtime, type,objectID,userID) values('".mysql_escape_string(stripslashes($notification))."','".date('U')."','order_note','".$order['order_id']."','".$orderDe->user_id."')";
 		 $wpdb->query($qry);
		 
		 //Get user Token
		 
			$results = $this->user_device_data($orderDe->user_id);
			
			foreach($results as $result)
			{
				if(get_option( 'amazingcart_customer_note_push' ) == 1)
							{
								if($this->check_if_user_is_login($orderDe->user_id) == true)
								{
								
					$this->push_programitically($result->pushTokenID,stripslashes($notification),$order['order_id'],"order_note");
					
								}
							}
			}
		}
		
	}
	
	
	function onStatusChange($orderID){
		
		global $wpdb;

 		
		
		
		
		
		
		
		if($this->check_if_order_using_ios($orderID) == true)
		{
		$orderDe = new WC_Order($orderID);
		
		$notification = "Your order #".$orderID." status changed to ".$orderDe->status."";
		
 		 $qry = "INSERT INTO ".$wpdb->prefix."amazingcart_user_notification (notification, unixtime, type,objectID,userID) values('".mysql_escape_string(stripslashes($notification))."','".date('U')."','status_changed','".$orderID."','".$orderDe->user_id."')";
 		 $wpdb->query($qry);
		 
		 //Get user Token
		 
			$results = $this->user_device_data($orderDe->user_id);
			
			foreach($results as $result)
			{
				if(get_option( 'amazingcart_save_order_push' ) == 1)
							{
								if($this->check_if_user_is_login($orderDe->user_id) == true)
								{
								
					$this->push_programitically($result->pushTokenID,stripslashes($notification),$orderID,"status_changed");
					
								}
							}
			}
		}
		
	}
	
	
	private function find_last_ordernotes($orderID)
	{
		
		global $wpdb;
        $sql = "SELECT * FROM `".$wpdb->prefix."comments` WHERE comment_post_ID='".$orderID."' ORDER BY comment_ID DESC LIMIT 1";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			
			$value = $result->comment_content;
		}
		
		return $value;
	}
	
	function include_wordpress_template($t)
	{
    global $wp_query;
    
    if ($wp_query->is_404)
    {
        $wp_query->is_404 = false;
        
        $wp_query->is_archive = true;
        
    }
    
    header("HTTP/1.1 200 OK");
    
    include($t);
    
	}
	
	
	public function insert_comment_follow($username,$password,$commentID)
	{
		$user = $this->user_login_bool($username,$password);
		
		if($user == false)
		{
			return array("status"=>1,"reason"=>"Unauthorized");
		}
		else
		{
	 	
		if($this->check_user_comment_followed($user->ID,$commentID) == false)
		{
			global $wpdb;
			$sql = "INSERT INTO ".$wpdb->prefix."amazingcart_user_comment_followed(wpuserID, commentID) values('".$user->ID."','".$commentID."')";
			$wpdb->query($sql);
		
			return array("status"=>0,"reason"=>"Successful");
		}
		else
		{
			return array("status"=>2,"reason"=>"Already followed");
		}
		
		}
	
	}
	
	
	
	 private function check_user_comment_followed($wpuserID,$commentID)
  {
	  global $wpdb;
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user_comment_followed` WHERE wpuserID='".$wpuserID."' AND commentID='".$commentID."'";
		$results = $wpdb->get_results($sql);
		
		if($results)
		{
			return true;	//followed
			
		}
		else
		{
			
			return false; //unfollowed
		}
		
	  
  }
	
	
	public function delete_comment_follow($username,$password,$commentID)
	{
		$user = $this->user_login_bool($username,$password);
		
		if($user == false)
		{
			return array("status"=>1,"reason"=>"Unauthorized");
		}
		else
		{
	 	
			global $wpdb;
			$sql = "DELETE FROM ".$wpdb->prefix."amazingcart_user_comment_followed WHERE wpuserID='".$user->ID."' AND commentID = '".$commentID."'";
			$wpdb->query($sql);
		
			return array("status"=>0,"reason"=>"Successful");
		
		}
	
	}
	
	
	 private function user_login_bool($userlogin,$userPassword){
		
			$creds = array();
			
			if($this->check_email_address($userlogin) == true)
			{
				$user = get_user_by( 'email', $userlogin );
				$username = $user->user_login;	
			}
			else
			{
				$username = $userlogin;
			}
			
			
			$creds['user_login'] = $username;
			$creds['user_password'] = $userPassword;
			$creds['remember'] = true;
			$user = wp_signon( $creds, true );
			if ( is_wp_error($user) )
			{
			  return false;
			}
			else
			{
				
				return $user;
			}
			
			
			
			
	}
	
	
	private function check_email_address($email) {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;
    }
	
	public function template_redirect() {
        
			global $wpdb;
        if($_GET['amazingcart']=="pushnotificationajax")
		{
			
			$this->include_wordpress_template(AMAZING_CART_PLUGIN_PATH. "/admin/ajaxscript/pushnotificationajax.php");
			die();
			
		}
		else if($_GET['amazingcart']=="previous_pushed")
		{
			$this->include_wordpress_template(AMAZING_CART_PLUGIN_PATH . "/admin/ajaxscript/getpreviouspushed.php");
			die();
		}
		else if($_GET['amazingcart']=="ajax_push")
		{
			$this->include_wordpress_template(AMAZING_CART_PLUGIN_PATH . "/admin/ajaxscript/ajaxpush.php");
			die();
		}
		else if($_GET['amazingcart']=="simple_push_test")
		{
				 print_r($this->simple_push_test_development($_GET['tokenID'],$_GET['msg']));
			die();
		}
		else if($_GET['amazingcart']=="comment-follow")
		{
		   if($_GET['type'] == "follow")
		   {
			  echo json_encode($this->insert_comment_follow($_POST['username'],$_POST['password'],$_POST['commentID'])); 
			   
		   }
		   else if($_GET['type'] == "unfollow")
		   {
			   echo json_encode($this->delete_comment_follow($_POST['username'],$_POST['password'],$_POST['commentID']));
		   }
			
			die();
		}
		else if($_GET['amazingcart']=="notification")
		{
		   if($_GET['type'] == "json")
		   {
			  
			  echo json_encode($this->getAllNotification($_GET['page'],$_GET['postPerPage'],$_POST['username'],$_POST['password']));
		   }
		   
			
			die();
		}
		
		
	}
	
	private function getAllNotification($page,$postPerPage,$username,$password)
	{
		
		$pagination = $page-1;
		
		$limit = $pagination * $postPerPage;
		
		global $wpdb;
		
		
		$user = $this->user_login_bool($username,$password);
		
		if($user == false)
		{
		 $sql = "SELECT pushmsg AS content, unixtime as unix,type as type,pID as objectID
FROM  `".$wpdb->prefix."amazingcart_pushnotification` ORDER BY  `unix` DESC LIMIT ".$limit.",".$postPerPage."";

		$logged = false;
		}
		else
		{
			$logged = true;
        $sql = "(
SELECT pushmsg AS content, unixtime as unix,type as type,pID as objectID
FROM  `".$wpdb->prefix."amazingcart_pushnotification`
)
UNION (

SELECT notification AS content, unixtime as unix,type as type,objectID as objectID
FROM  `".$wpdb->prefix."amazingcart_user_notification` WHERE userid='".$user->ID."'
)
ORDER BY  `unix` DESC LIMIT ".$limit.",".$postPerPage."";

		}
		
		$results = $wpdb->get_results($sql);
		$count=0;
		$ary = array();
		foreach ($results as $result) {
			$count++;
			array_push($ary,array(
								"pushed_message"=>$result->content,
								"unixtime"=>$result->unix,
								"date_time"=>date("d M Y, H:i",$result->unix),
								"ago"=>$this->time_elapsed_string($result->unix),
								"type"=>$result->type,
								"objectID"=>$result->objectID
								));
		}
		
		if($user == false)
		{
			$sql2 = "SELECT pushmsg AS content, unixtime as unix,type as type,pID as objectID
FROM  `".$wpdb->prefix."amazingcart_pushnotification`";
		}
		else
		{
		$sql2 = "(
SELECT pushmsg AS content, unixtime as unix
FROM  `".$wpdb->prefix."amazingcart_pushnotification`
)
UNION (

SELECT notification AS content, unixtime as unix
FROM  `".$wpdb->prefix."amazingcart_user_notification` WHERE userid='".$user->ID."'
)";
		}
		$results2 = $wpdb->get_results($sql2);
		$countTotal=0;
		
		foreach ($results2 as $result) {
			$countTotal++;
			
		}
		
		$totalPage = ceil($countTotal/$postPerPage);
		
		return array(
					"logged"=>(bool)$logged,
					"page"=>(int)$page,
					"total_page"=>$totalPage,
					"postPerPage"=>(int)$postPerPage,
					"page_count"=>$count,
					"total_post"=>$countTotal,
					"data"=>$ary
					);
	}
	
	
	private function time_elapsed_string($ptime) {
    $etime = time() - $ptime;
    
    if ($etime < 1) {
        return '0 seconds';
    }
    
    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );
    
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's ago' : ' ago');
        }
    }
	
	}
	
	private function check_if_order_using_ios($orderID)
	{
		
		global $wpdb;
        $sql = "SELECT * FROM `".$wpdb->prefix."postmeta` WHERE post_id='".$orderID."' AND meta_key='_customer_user_agent'";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			
			$value = $result->meta_value;
		}
		
		if($value == "iOS")
		{
			
			return true;	
		}
		else
		{
			return false;	
		}
	}
	
	public function simple_push_test_development($tokenID,$message){
		
		
		global $wpdb;
        
     
			
			  $msgArray = array(
					"body"=>$message,
					"action-loc-key"=>"View",
					"postID"=>"0",
					"type"=>"none"
					);
		
		$body['aps'] = array(
	'alert' => $msgArray,
	'sound' => 'default'
	);
			
			  $data = array(
    'devToken' => $tokenID,
    'pass' =>  get_option("amazingcart_pemPassword"),
	'message' =>json_encode($body),
	'enviroment' => "sandbox",
	'cert'=>"http://".get_option("amazingcart_pem")
		);
			
		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://".get_option("amazingcart_externalServer")."");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);


		$decoded = json_decode($output);
		
		return $decoded;
	}
	
	
	
	public function push_programitically($tokenID,$message,$postID,$postType){
		
		
		global $wpdb;
        
     
			if(get_option("amazingcart_ApnsEnv") == 1)
			{
				$enviroment = "sandbox";
			}
			 else
			 {
				 $enviroment = "production";
			 }
			
		 $data = array(
    'devToken' => $tokenID,
    'pass' => get_option("amazingcart_pemPassword"),
	'body' =>$message,
	'postID' =>$postID,
	'type' =>$postType,
	'enviroment' => $enviroment,
	'cert'=>"http://".get_option("amazingcart_pem")
		);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://".get_option("amazingcart_externalServer")."");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);


		$decoded = json_decode($output);
		
		return $decoded;
	}
	
	
	
	
	public function pushToAllTestExternalServer($pemPass,$enviroment,$cert){
		
		
		global $wpdb;
        
        $sql = "SELECT DISTINCT pushTokenID,deviceID,indicator,is_login,wpuserid FROM `".$wpdb->prefix."amazingcart_user` LIMIT 1";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			
			   $data = array(
    'devToken' => $result->pushTokenID,
    'pass' => $pemPass,
	'body' =>"Test : What makes candies so sweet ?",
	'postID' =>"0",
	'type' =>"none",
	'enviroment' => $enviroment,
	'cert'=>"http://".$cert
		);
		
		
			
		}
		
		$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://".get_option("amazingcart_externalServer")."");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);


$decoded = json_decode($output);
		
		return $decoded;
	}
	
	
	public function pushToAllTestCurrentServer($pemPass,$enviroment,$cert){
		
		
		global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user` LIMIT 1";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			
			 
		
		
		
		  $data = array(
    'devToken' => $result->pushTokenID,
    'pass' => $pemPass,
	'body' =>"Test : What makes candies so sweet ?",
	'postID' =>"0",
	'type' =>"none",
	'enviroment' => $enviroment,
	'cert'=>"http://".$cert
		);
			
		}
		
		$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "".get_bloginfo( 'url' )."/?scrollblogkruk=apns");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);


$decoded = json_decode($output);
		
		return $decoded;
	}
	




function pushMessage($msg,$tokenID,$device,$pemPass,$enviroment,$cert,$pushType,$pRediredirectIDOrLink)
{
		
		
		
		
			
			  $data = array(
    'devToken' => $tokenID,
	'device' => strtolower($device),
    'pass' => $pemPass,
	'body' =>$msg,
	'postID' =>$pRediredirectIDOrLink,
	'type' =>$pushType,
	'enviroment' => $enviroment,
	'cert'=>"http://".$cert
		);
			
		
		
		$ch = curl_init();
		
		if(get_option("amazingcart_ChooseHost") == 1)
		{
			curl_setopt($ch, CURLOPT_URL, "".get_bloginfo( 'url' )."/?scrollblogkruk=apns");
		}
		else if(get_option("amazingcart_ChooseHost") == 2)
		{
			curl_setopt($ch, CURLOPT_URL, "http://".get_option("amazingcart_externalServer")."");
		}
		
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);


$decoded = json_decode($output);

return $decoded;
	
}


function getTotalSubscriber()
{
	   global $wpdb;
        $sql = "SELECT DISTINCT pushTokenID FROM `".$wpdb->prefix."amazingcart_user`";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			if(!empty($result->pushTokenID))
			{
			   $count++;
			}
		}
		
		return $count;
	
}


function getTotalPushLeft()
{
	   global $wpdb;
        $sql = "SELECT DISTINCT pushTokenID FROM `".$wpdb->prefix."amazingcart_user` WHERE indicator='0'";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			
			   $count++;
			
		}
		
		return $count;
	
}

public function user_device_data($wpuserid)
{
	global $wpdb;
	 $sql = "SELECT DISTINCT pushTokenID FROM `".$wpdb->prefix."amazingcart_user` WHERE wpuserid='".$wpuserid."'";
		$results = $wpdb->get_results($sql);
		
		return $results;
}


public function updateIndicator($deviceID)
{
	 global $wpdb;
	$sql = "UPDATE ".$wpdb->prefix."amazingcart_user SET indicator='1' WHERE deviceID='".$deviceID."'";
$wpdb->query($sql);
	
}

public function getAllPreviousPushed($page,$perPage){
	
		
		$pageTotal = ($page-1) * $perPage;
	
	       global $wpdb;
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_pushnotification` ORDER BY no DESC LIMIT $pageTotal,$perPage";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			$count++;
		?>

			<div style="padding:5px; width:100%; background-color:#CCC; margin-bottom:10px;">
        		<div><?php echo $result->pushmsg; ?></div>
            	<div style="font-size:9px; margin-top:10px;">Pushed on <?php echo date("j F Y",$result->unixtime); ?></div>
 			</div>
 
 		<?php 
 		} 
		
		if($count == 0)
		{
			?>
            <div style="padding:5px; width:100%; background-color:#CCC; margin-bottom:10px;">
        	<div>There is no push notification</div>
 			</div>
            <?php
		}
		
		$this->pagination($page,$perPage);
}

function pagination($page,$perPage){
	
	  global $wpdb;
	
	 $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_pushnotification` ";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			$count++;
			
		}
		
		$totalPageNumber = ceil($count/$perPage);
		
		for($i=1;$i<=$totalPageNumber;$i++)
		{
			if($i == $totalPageNumber)
			{
				if($i == $page)
				{
					echo '<span style="color:red;">'.$i.'</span>';
				}
				else
				{
					echo '<span onclick="pagination('.$i.')" style="cursor:pointer;">'.$i.'</span>';
				}
			}
			else
			{
			
			
				if($i == $page)
				{
					echo '<span style="color:red;">'.$i.'</span> | ';
				}
				else
				{
					echo '<span onclick="pagination('.$i.')" style="cursor:pointer;">'.$i.'</span> | ';
				}
			}
		}
		
}

}
?>
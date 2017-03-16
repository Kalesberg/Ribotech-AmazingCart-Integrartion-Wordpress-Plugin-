<?php
class amazingcart_analytics
{
	
	public function init(){
		
	add_action('template_redirect', array($this,'amazingcart_template_redirect'), 1);
	}
	
	
	public function amazingcart_template_redirect() {
        
		error_reporting(0);
        if($_GET['amazingcart']=="analytics")
		{
			
			if($_GET['type'] == "insert")
			{
				if($_POST)
				{
					if($this->findDeviceID($_POST['deviceID']) == false)
					{
						$data['deviceID'] = $_POST['deviceID'];
						$data['pushTokenID'] = $_POST['pushTokenID'];
						$data['device'] = $_POST['device'];
						$this->analyticsInsert($data);
						echo json_encode(array("status"=>0,"reason"=>"Successful"));
					}
					else
					{
						echo json_encode(array("status"=>1,"reason"=>"Device already registered"));
					}
				}
				else
				{
					echo json_encode(array("status"=>-1,"reason"=>"No Input"));
				}
			}
			else if($_GET['type'] == "update")
			{
				if($_POST)
				{
					$this->updateApnsToken($_POST['token'],$_POST['deviceID']);
					echo json_encode(array("status"=>0,"reason"=>"Successful"));
				}
				else
				{
					echo json_encode(array("status"=>-1,"reason"=>"No Input"));
				}
			}
			die();
		}
		
		
		
	 }
	
	
	public function getAllTimeTotalDownload()
	{
		
		 global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user`";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			
			   $count++;
		}
		
		return $count;
	}
	
	
		public function getiPhoneTotalDownload()
	{
		
		 global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user`";
		$results = $wpdb->get_results($sql);
		$totaliPhone = 0;
		$iPhonelist=array();
		
		foreach ($results as $word => $iPhone) {
  		if (array_key_exists($word, $iPhonelist)) {
    	$iPhonelist[$word]+=$iPhone;
  	}
		}
		
		return $iPhonelist[$word]+=$iPhone;
	}
	
	public function getAndroidTotalDownload()
	{
        global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user`";
		$results = $wpdb->get_results($sql);
		$totaliPhone = 0;
		$iPhonelist=array();
		
		foreach ($results as $word => $iPhone) {
  		if (array_key_exists($word, $iPhonelist)) {
    	$iPhonelist[$word]+=$iPhone;
  	}
		}
		
		return $iPhonelist[$word]+=$iPhone;
	}
	
	
	
	public function getSubscriber()
	{
		
		 global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user`";
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
	

	
	public function getTodays()
	{
		
		 global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user`";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			if($result->day == date('j') && $result->month == date('n') && $result->year == date('Y'))
			{
			   $count++;
			}
		}
		
		return $count;
	}
	
	
	public function getMonths()
	{
		
		 global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user`";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			if($result->month == date('n') && $result->year == date('Y'))
			{
			   $count++;
			}
		}
		
		return $count;
	}
	
	
	public function getYear()
	{
		
		 global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user`";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			if($result->year == date('Y'))
			{
			   $count++;
			}
		}
		
		return $count;
	}
	
	
	public function getByMonth($month,$year)
	{
		
		 global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user` WHERE month = '".$month."' AND year='".$year."'";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			
			   $count++;
			
		}
		
		return $count;
	}
	
	
	public function getByDay($day,$month,$year)
	{
		
		 global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user` WHERE day='".$day."' AND month = '".$month."' AND year='".$year."'";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			
			   $count++;
			
		}
		
		return $count;
	}
	
	
	
	public function analyticsInsert($data)
	{
		global $wpdb;

 		if($this->findDeviceID($data['deviceID']) == true)
		{
			
		}
		else
		{
 		 $qry = "INSERT INTO ".$wpdb->prefix."amazingcart_user (deviceID, pushTokenID, day,month,year,unixtime,device) values('".$data['deviceID']."','".$data['pushTokenID']."','".date('j')."','".date('n')."','".date('Y')."','".date('U')."','".$data['device']."')";
 		 $wpdb->query($qry);
		}
		
	}
	
	
	public function updateApnsToken($apnsToken,$deviceID){
	
			global $wpdb;
			
			
			$sql = "UPDATE ".$wpdb->prefix."amazingcart_user SET pushTokenID='".$apnsToken."' WHERE deviceID='".$deviceID."'";
			$wpdb->query($sql);
		}
		
	
	
	private function findDeviceID($deviceID){
		
		
		global $wpdb;
        
        $sql = "SELECT * FROM `".$wpdb->prefix."amazingcart_user` WHERE deviceID='".$deviceID."'";
		$results = $wpdb->get_results($sql);
		$count=0;
		foreach ($results as $result) {
			
			   $count++;
			
		}
		
		if($count == 0)
		{
		return false;
		}
		else
		{
			return true;
		}
		
	}
	
}


?>
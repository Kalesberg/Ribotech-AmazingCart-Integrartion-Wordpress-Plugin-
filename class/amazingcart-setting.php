<?php
class amazingcart_setting{
	
	public function __construct() {	

	}
	
	
	public function init()
	{
		
		add_action('admin_menu', array($this,'create_menu'));
		add_action('template_redirect', array($this,'template_redirect'), 1);
		
	}
	

	public function template_redirect() {
			
				global $wpdb;
				error_reporting(0);
			if($_GET['amazingCart']=="adminAjaxSave")
			{
				if($_GET['type'] == "setting_general")
				{
					
					if (!is_super_admin()) {
    					echo json_encode(array("status"=>1,"reason"=>"Not Authorized"));
						die();
						
					} else {
						if($_POST)
						{
							$this->setting_general_save($_POST);
						
							echo json_encode(array("status"=>0,"reason"=>"Successfully Save"));
							die();
						}
						else
						{
							echo json_encode(array("status"=>-1,"reason"=>"No Input"));
							die();
						}
     					
					}
					
				
				}
				else if($_GET['type'] == "setting_general_push")
				{
					
					if (!is_super_admin()) {
    					echo json_encode(array("status"=>1,"reason"=>"Not Authorized"));
						die();
						
					} else {
						if($_POST)
						{
				
							$this->setting_general_save_push($_POST);
							echo json_encode(array("status"=>0,"reason"=>"Successfully Save"));
							die();
						}
						else
						{
							echo json_encode(array("status"=>-1,"reason"=>"No Input"));
							die();
						}
     					
					}
					
				
				}
				
				
			}
			
			
	}	
	
	
	
	public function activation()
	{
		register_setting('instagramClientID', '');
    
    	register_setting('instagramClientSecret', '');
    
   		 register_setting('instagramWebsiteUrl', '');
	
		register_setting('instagramRedirectUri', '');
		
		register_setting('amazingcart_save_order_push', '');
		update_option( 'amazingcart_save_order_push', '1');
		
		register_setting('amazingcart_customer_note_push', '');
		update_option( 'amazingcart_customer_note_push', '1');
		
		register_setting('amazingcart_comment_follow_up_push', '');
		update_option( 'amazingcart_comment_follow_up_push', '1');
		
		
		register_setting('amazingcart_pushNotificationWorkingIndicator', '');
    
    	register_setting('amazingcart_excludeCategories', '');
    
    	register_setting('amazingcart_ApnsEnv', '');
    
    	register_setting('amazingcart_ChooseHost', '');
    
    	register_setting('amazingcart_pem', '');
    
    	register_setting('amazingcart_pem_production', '');
    
   		register_setting('amazingcart_pemPassword', '');
    
    	register_setting('amazingcart_externalServer', '');
		
		
		
		register_setting('amazingcart_home_appearance_choose', '');
		update_option( 'amazingcart_home_appearance_choose', 'usenative');
    	
		register_setting('amazingcart_home_appearance_use_html_link', '');
		
		register_setting('amazingcart_categories_option', '');
		update_option( 'amazingcart_categories_option', 'listall');
		
		register_setting('amazingcart_show_category_thumb', '');
		update_option( 'amazingcart_show_category_thumb', 'hide');
	}
	
	
	
	function create_menu()
	{
		
		
		add_menu_page('Ribotech', 'Ribotech App', 'administrator', 'RibotechApp', array($this,'amazingDashboard'));
		// add_submenu_page('RibotechApp', 'Push Notification', 'Push Notification', 'administrator', 'AmazingPushNotification',  array($this,'amazingPushNotification'));
		// add_submenu_page('RibotechApp', 'Appearance', 'Appearance', 'administrator', 'AmazingMainPageAppearance',  array($this,'homeControllerAppearace'));
		// add_submenu_page('RibotechApp', 'Settings', 'Settings', 'administrator', 'AmazingCartSettings',  array($this,'amazingSettings'));
		// add_submenu_page('RibotechApp', 'ShortCode Docs', 'ShortCode Docs', 'administrator', 'AmazingCartDocumentation',  array($this,'amazingDocumentation'));
		
		 

/*
		Statistic Dashboard  (Merge with admin app)
		Gallery 
		Push Audio
		Push Notification  (marge with amazing chart and admin app)
			
		Shoutcast (Two tabs. Shoutcast and Shoutcast Statistics)  (Already working )
		Documents (for user to have any documentation) (Already working)
		
		Settings  
			Password change 
			Push Notification  Certification 

*/
//	function add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null ) {

//  function add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {
		add_submenu_page('RibotechApp', 'Statistics Dashboard', 'Statistics Dashboard', 'administrator', 'StatisticDashboard',  array($this,'dashboard'));
		add_submenu_page('RibotechApp', 'Gallery', 'Gallery', 'administrator', 'Gallery',  array($this,'gallery'));
		add_submenu_page('RibotechApp', 'Push Audio', 'Push Audio', 'administrator', 'PushAudio',  array($this,'audioPush'));
		add_submenu_page('RibotechApp', 'Push Notification', 'Push Notification', 'administrator', 'PushNotification',  array($this,'pushNotification'));
		add_submenu_page('RibotechApp', 'Shoutcast', 'Shoutcast', 'administrator', 'Shoutcast',  array($this,'shoutcast'));
		add_submenu_page('RibotechApp', 'Documents', 'Documents', 'administrator', 'Documentation',  array($this,'documentation'));
		add_submenu_page('RibotechApp', 'Settings', 'Settings', 'administrator', 'Settings',  array($this,'settings'));

	}
	
	function amazingDashboard()
	{
    
   		 require AMAZING_CART_PLUGIN_PATH."admin/template/dashboardmenu.php";
	}
	
	function amazingPushNotification()
	{
    
    	 require AMAZING_CART_PLUGIN_PATH."admin/template/push-notification.php";
	}
	
	
	
	function homeControllerAppearace()
	{
    	require AMAZING_CART_PLUGIN_PATH."admin/template/appearance.php";
    
	}
	
	function categoriesExclude()
	{
    
    
	}
	
	
	function amazingSettings()
	{
    	require AMAZING_CART_PLUGIN_PATH."admin/template/settings.php";
    
	}
	
	function amazingDocumentation()
	{
    	require AMAZING_CART_PLUGIN_PATH."admin/template/docs.php";
    
	}
	
	function dashboard()
	{
		$this->amazingDashboard();
	}
	
	function gallery()
	{		
		require AMAZING_CART_PLUGIN_PATH."app/index-gallery-manager.php";
	}
	
	function audioPush()
	{
		require AMAZING_CART_PLUGIN_PATH."app/index-audio-manager.php";
	}
	function pushNotification()
	{
		require AMAZING_CART_PLUGIN_PATH."app/index-notification.php";
	}
	
	function shoutcast()
	{
		require AMAZING_CART_PLUGIN_PATH."app/shoutcast.php";
	}
	
	function documentation()
	{
		require AMAZING_CART_PLUGIN_PATH."app/index-docu.php";
	}
	function settings()
	{
		// require AMAZING_CART_PLUGIN_PATH."app/settings.php";
		require AMAZING_CART_PLUGIN_PATH."app/push-cert.php";
	}
	
	function setting_general_save($data)
	{
		update_option( 'instagramClientID', $data['instagramClientID'] );
		update_option( 'instagramClientSecret', $data['instagramClientSecret'] );
    	 update_option( 'instagramWebsiteUrl', $data['instagramWebsiteUrl']  );
   		update_option( 'instagramRedirectUri', $data['instagramRedirectUri'] );
   		
		
	}
	
	
	function setting_general_save_push($data)
	{
		
					update_option('amazingcart_save_order_push', $data['amazingCart_save_order_pushs'] );
		update_option('amazingcart_customer_note_push', $data['amazingCart_save_note_for_customer_pushs'] );
    	 update_option('amazingcart_comment_follow_up_push', $data['amazingCart_comment_follow_pushs']  );
   		
	}
	
}


?>
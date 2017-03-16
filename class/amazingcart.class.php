<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'AmazingCart' ) ) {
class AmazingCart{
		
	public function __construct() {


		
			add_action('init', array($this,'amazing_cart_admin_init'));
			
			

	}
		
	public function amazing_cart_admin_init() {
		if (!session_id())
		session_start();
		
		 
	}

	public function activation() {
		 
		
		 
		$amazingcart_setting = new amazingcart_setting();
		$amazingcart_setting->activation();
		
		$AmazingCartSqlTableActivationClass = new AmazingCartSqlTableActivationClass();
		$AmazingCartSqlTableActivationClass->activation();
		
	}
	
	
	public function load()
	{
		$this->activateWooCommerceAPIPlugin();
		$this->activateQrCodeGeneratorPlugin();	
		$this->activateMenuApiPlugin();
		$this->activateWpJsonApiPlugin();
		$this->activateAmazingCartAdminMetaBoxPlugin();
		$this->activatAmazingCartAdminSettings();
		$this->activateAmazingCartStatus();
		$this->activateAnalyticsApi();
		$this->pushnotificationInit();
		$this->home_appearance_init();
		$this->category_appearance_init();
		$this->gateway_meta_init();
		$this->shortcode_init();
	}
	

	
   	public function activateWooCommerceAPIPlugin()
	 {
		 
		 $amazingcart_woo_json_api = new amazingcart_woo_json_api();
	 }
	 
	 
	 public function activateQrCodeGeneratorPlugin()
	 {
		 
		 $amazingcart_qrcodegenerator = new amazingcart_qrcodegenerator();
	 }
	 
	public function activateMenuApiPlugin()
	 {
		 
		 $amazing_cart_menu = new amazing_cart_menu();
	 }
	 
	 public function activateWpJsonApiPlugin()
	 {
		 
		 $amazingcart_wp_json_api = new amazingcart_wp_json_api();
	 }
	 
	 public function activateAmazingCartAdminMetaBoxPlugin()
	 {
		 
		 $amazingcart_admin_meta_box = new amazingcart_admin_meta_box();
	 }
	 
	 public function activatAmazingCartAdminSettings()
	 {
		 
		 $amazingcart_setting = new amazingcart_setting();
		  $amazingcart_setting->init();
	 }
	 
	 
	 public function activateAmazingCartStatus()
	 {
		 global $AmazingCartStatus;
		 $AmazingCartStatus = new AmazingCartStatus();
		 
	 }
	 
	 
	  public function activateAnalyticsApi()
	 {
		 
		 $amazingcart_analytics = new amazingcart_analytics();
		 $amazingcart_analytics->init();
		 
	 }
	 
	 
	  public function pushnotificationInit()
	 {
		 
		$push = new pushnotification;
		 $push->init();
		 
	 }
	 
	  public function home_appearance_init()
	 {
		 
		$home = new AmazingCart_homeappearances();
		 $home->init();
		 
	 }
	 
	  public function category_appearance_init()
	 {
		 
		$category = new AmazingCart_category_appearance();
		 $category->init();
		 
	 }
	 
	 public function gateway_meta_init()
	 {
		 
		$gateway_meta = new AmazingCart_paymentgateway_meta();
		 $gateway_meta->init();
		 
	 }
	 
	   public function shortcode_init()
	 {
		 
		$AmazingCart_shortcode = new AmazingCart_shortcode();
		 $AmazingCart_shortcode->init();
		 
	 }
		
	}
}
?>
<?php

class AmazingCartSqlTableActivationClass{
	
	function activation()
	{
		$this->userTable();
		$this->pushNotificationTable();
		$this->usernotificationTable();
		$this->usercommentfollowed();
		$this->homeappearance();
		$this->homeappearance_product();
		$this->homeappearance_product_categories();
		$this->payment_gateway_meta();
	}
	
	
	
	function userTable(){
		
		  global $wpdb;
		
		$wpdb->query('CREATE TABLE `' . $wpdb->prefix . 'amazingcart_user` (
	
	  `no` bigint(20) NOT NULL AUTO_INCREMENT,
	
	  `deviceID` text NOT NULL,
	
	  `pushTokenID` text NOT NULL,
	
	  `day` bigint(20) NOT NULL,
	
	  `month` bigint(20) NOT NULL,
	
	  `year` bigint(20) NOT NULL,
	
	  `unixtime` bigint(20) NOT NULL,
	
	  `device` text NOT NULL,
	  `wpuserid` bigint(20) NOT NULL DEFAULT "0",
	
	  `indicator` bigint(20) NOT NULL DEFAULT "0",
	  `is_login` bigint(20) NOT NULL DEFAULT "0",
	
	  PRIMARY KEY (`no`)
	
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');
	}
	
	
	function pushNotificationTable(){
		
		  global $wpdb;
		
				 $wpdb->query('CREATE TABLE `' . $wpdb->prefix . 'amazingcart_pushnotification` (
		
		  `no` bigint(20) NOT NULL AUTO_INCREMENT,
		
		  `pushmsg` text NOT NULL,
		
		  `unixtime` bigint(20) NOT NULL,
		  `type` text NOT NULL,
		  `pID` text NOT NULL,
		
		  PRIMARY KEY (`no`)
		
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');
	}
	
	
	function usernotificationTable(){
		
		  global $wpdb;
		
				 $wpdb->query('CREATE TABLE `' . $wpdb->prefix . 'amazingcart_user_notification` (
		
		  `no` bigint(20) NOT NULL AUTO_INCREMENT,
		
		  `notification` text NOT NULL,
		
		  `unixtime` bigint(20) NOT NULL,
		  `type` text NOT NULL,
		  `objectID` text NOT NULL,
		  `userID` text NOT NULL,
		  PRIMARY KEY (`no`)
		
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');
	}
	
	
	function usercommentfollowed(){
		
		  global $wpdb;
		
				 $wpdb->query('CREATE TABLE `' . $wpdb->prefix . 'amazingcart_user_comment_followed` (
		
		  `no` bigint(20) NOT NULL AUTO_INCREMENT,
		  `wpuserID` bigint(20) NOT NULL,
		  `commentID` bigint(20) NOT NULL,
		  PRIMARY KEY (`no`)
		
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');
	}
	
	
	function homeappearance(){
		
		  global $wpdb;
		
				 $wpdb->query('CREATE TABLE `' . $wpdb->prefix . 'amazingcart_home_appearance` (
		
		  `no` bigint(20) NOT NULL AUTO_INCREMENT,
		  `title` text NOT NULL,
		  `type` text NOT NULL,
		  `ordering` bigint(20) NOT NULL,
		  `link` text NOT NULL,
		  PRIMARY KEY (`no`)
		
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');
		
		
		
		 $wpdb->query("INSERT INTO  `" . $wpdb->prefix . "amazingcart_home_appearance` (
`title` ,
`type` ,
`ordering` ,
`link`
)
VALUES ('New Items',  'newitems',  '0',  ''
);");
		
		
		

	}
	
	
	function homeappearance_product(){
		
		  global $wpdb;
		
				 $wpdb->query('CREATE TABLE `' . $wpdb->prefix . 'amazingcart_home_appearance_product` (
		
		  `no` bigint(20) NOT NULL AUTO_INCREMENT,
		  `id` bigint(20) NOT NULL,
		  `product_id` bigint(20) NOT NULL,
		  `ordering` bigint(20) NOT NULL,
		  PRIMARY KEY (`no`)
		
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');
	}
	
	
	function homeappearance_product_categories(){
		
		  global $wpdb;
		
				 $wpdb->query('CREATE TABLE `' . $wpdb->prefix . 'amazingcart_home_appearance_product_categories` (
		
		  `no` bigint(20) NOT NULL AUTO_INCREMENT,
		  `category_id` bigint(20) NOT NULL,
		  `ordering` bigint(20) NOT NULL,
		  PRIMARY KEY (`no`)
		
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');
	}
	
	function payment_gateway_meta(){
		
		  global $wpdb;
		
				 $wpdb->query('CREATE TABLE `' . $wpdb->prefix . 'amazingcart_paymentgateway_meta` (
		
		  `no` bigint(20) NOT NULL AUTO_INCREMENT,
		  `gateway_key` text NOT NULL,
		  `hideit` bigint(20) NOT NULL,
		  `safari` bigint(20) NOT NULL,
		  PRIMARY KEY (`no`)
		
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');
	}
	
}

?>
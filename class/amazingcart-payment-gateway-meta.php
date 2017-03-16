<?php

class AmazingCart_paymentgateway_meta
{
	function payment_gateway_list()
	{
	global $woocommerce;
	foreach($woocommerce->payment_gateways->get_available_payment_gateways() as $key=>$value)
		{
			
			$result = $this->check_key_if_exist($key);
			if($result == true)
					{
						$res = $this->get_key($key);
						if($res->hideit == 0)
						{
							$color = "#0C0";
						}
						else
						{
							$color = "#F00";
						}
						
						
						if($res->safari == 0)
						{
							$icon = "display:none;";
						}
						else
						{
							$icon = "";
						}
					}
					else
					{
						
						$color = "#0C0";
						$icon = "display:none;";
					}
			
			?>
            <div style="background-color: <?php echo $color; ?>; color:#FFF; margin-bottom:10px; padding:10px;" id="<?php echo $key; ?>">
            	<div style="float:left; width:70%;">
            		<div style="font-weight:bold;" ><img src="<?php echo plugins_url( 'AmazingCart/images/safari-icon.png')."" ?>" height="10" width="10" style="<?php echo $icon; ?>" /> <?php echo $value->settings['title']; ?> (<?php echo $key; ?>)</div>
             		<div ><?php echo $value->settings['description']; ?></div>
                </div>
                <div style="float:left; width:29%;">
                	<div align="right">
                    <?php 
					if($result == true)
					{ 
						$res = $this->get_key($key);
						if($res->hideit == 0)
						{
							?>
							<a href="javascript:hideItExe('<?php echo $key; ?>')" style="color:#FFF;">Hide It</a>
							<?php
						}
						else
						{
							?>
							<a href="javascript:displayItExe('<?php echo $key; ?>')" style="color:#FFF;">Display it</a>
							<?php	
						}
                    }
                    else
                    {
                    ?>
                   <a href="javascript:hideItExe('<?php echo $key; ?>')" style="color:#FFF;">Hide it</a>
                    <?php
                    }
					?>
                     
                    | 
                     <?php if($result == true){ 
						$res = $this->get_key($key);
						if($res->safari == 0)
						{
							?>
							<a href="javascript:safariItExe('<?php echo $key; ?>')" style="color:#FFF;">Use Safari</a>
							<?php
						}
						else
						{
							?>
							<a href="javascript:useInWebItExe('<?php echo $key; ?>')" style="color:#FFF;">Use inAppWeb</a>
							<?php	
						}
                    }
                    else
                    {
                    ?>
                   <a href="javascript:safariItExe('<?php echo $key; ?>')" style="color:#FFF;">Use Safari</a>
                    <?php
                    }
					?>
                    
                    
                    </div>
                </div>
                <div style="clear:both;"></div>
             </div>
            <?php
	
		}

	}
	
	public function init()
	{
		
		add_action('template_redirect', array($this,'template_redirect'), 1);
	}
	
	 public function template_redirect() {
		 
		 error_reporting(0);
		 
		  if($_GET['amazingcart']=="adminAjaxSave")
		{
			
			if($_GET['type'] == "payment_gateway_meta_hide_it_to_display_it")
			{
				
			    $this->changeHideItToDisplayIt($_POST['key']);
				die();
				
			}
			else if($_GET['type'] == "payment_gateway_meta_display_it_to_hide_it")
			{
				
			    $this->changeDisplayITToHideIt($_POST['key']);
				die();
				
			}
			else if($_GET['type'] == "payment_gateway_meta_useInApp_to_safari")
			{
				
			 	$this->change_useInAppWeb_to_safari($_POST['key']);
				die();
				
			}
			else if($_GET['type'] == "payment_gateway_meta_safari_to_useInAppWeb")
			{
				
			 	$this->change_safari_to_useInAppWeb($_POST['key']);
				die();
				
			}
			else if($_GET['type'] == "payment_gateway_list_ajax")
			{
				
			    $this->payment_gateway_list();
				die();
				
			}
		}
	 }
	
	
	function change_safari_to_useInAppWeb($key)
	{ global $wpdb;
			
		if($this->check_key_if_exist($key))
		{
			 $qry = "UPDATE `" . $wpdb->prefix . "amazingcart_paymentgateway_meta` SET safari='0' WHERE gateway_key='".$key."';";
 			 $wpdb->query($qry);
		
		}
		else
		{
			
			 $qry = "INSERT INTO `" . $wpdb->prefix . "amazingcart_paymentgateway_meta` (gateway_key,hideit,safari)
VALUES ('".$key."','0', '0')";
 			$wpdb->query($qry);
		}
		
	}	
	
	function change_useInAppWeb_to_safari($key)
	{ global $wpdb;
			
		if($this->check_key_if_exist($key))
		{
			 $qry = "UPDATE `" . $wpdb->prefix . "amazingcart_paymentgateway_meta` SET safari='1' WHERE gateway_key='".$key."';";
 			 $wpdb->query($qry);
		
		}
		else
		{
			
			 $qry = "INSERT INTO `" . $wpdb->prefix . "amazingcart_paymentgateway_meta` (gateway_key,hideit,safari)
VALUES ('".$key."','0', '1')";
 			$wpdb->query($qry);
		}
		
	}	
	
	
	function changeHideItToDisplayIt($key)
	{ global $wpdb;
			
		if($this->check_key_if_exist($key))
		{
			 $qry = "UPDATE `" . $wpdb->prefix . "amazingcart_paymentgateway_meta` SET hideit='1' WHERE gateway_key='".$key."';";
 			 $wpdb->query($qry);
		
		}
		else
		{
			
			 $qry = "INSERT INTO `" . $wpdb->prefix . "amazingcart_paymentgateway_meta` (gateway_key,hideit,safari)
VALUES ('".$key."','1', '0')";
 			$wpdb->query($qry);
		}
		
	}	
	
	
	function changeDisplayITToHideIt($key)
	{ global $wpdb;
			
		if($this->check_key_if_exist($key))
		{
			 $qry = "UPDATE `" . $wpdb->prefix . "amazingcart_paymentgateway_meta` SET hideit='0' WHERE gateway_key='".$key."';";
 			 $wpdb->query($qry);
			
		}
		else
		{
			
			 $qry = "INSERT INTO `" . $wpdb->prefix . "amazingcart_paymentgateway_meta` (gateway_key,hideit,safari)
VALUES ('".$key."','1', '0')";
 			$wpdb->query($qry);
		}
		
	}
	
	
	function check_key_if_exist($key)
	{
		
		global $wpdb;
        	$sql = "SELECT * FROM `" . $wpdb->prefix . "amazingcart_paymentgateway_meta` WHERE gateway_key='".$key."'";
			
			$results = $wpdb->get_results($sql);
			
			if($results)
			{
				
				return true;
			}
			else
			{
				return false;
			}
	}
	
	
	function get_key($key)
	{
		
		global $wpdb;
        	$sql = "SELECT * FROM `" . $wpdb->prefix . "amazingcart_paymentgateway_meta` WHERE gateway_key='".$key."'";
		
			$results = $wpdb->get_results($sql);
			
			
				foreach ($results as $result) {
					$re = $result;
				}
				return $re;
			
	}
}

?>
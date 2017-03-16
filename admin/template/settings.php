<?php

 $tabs = array( 'General' => 'General', 'PaymentGateway' => 'Payment Gateway','pushnotification'=>'Push Notification Cert');
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
 
		if($_GET['tab'] == $tab )
		{
        echo "<a class='nav-tab nav-tab-active' href='?page=AmazingCartSettings&tab=$tab'>$name</a>";
		}
		else
		{
			if($_GET['tab'] == "" && $tab == "General")
			{
				echo "<a class='nav-tab nav-tab-active' href='?page=AmazingCartSettings&tab=$tab'>$name</a>";
			}
			else
			{
			  echo "<a class='nav-tab' href='?page=AmazingCartSettings&tab=$tab'>$name</a>";
			}
		}
    }
    echo '</h2>';
	
	
	if($_GET['tab'] == "General")
	{
		require AMAZING_CART_PLUGIN_PATH."admin/template/setting_template/general.php";
		
	}
	else if($_GET['tab'] == "PaymentGateway")
	{
		
		require AMAZING_CART_PLUGIN_PATH."admin/template/setting_template/payment-gateway-settings.php";
	}
	else if($_GET['tab'] == "pushnotification")
	{
		
		require AMAZING_CART_PLUGIN_PATH."admin/template/setting_template/push-notification-settings.php";
	}
	else 
	{
		require AMAZING_CART_PLUGIN_PATH."admin/template/setting_template/general.php";
	}
?>
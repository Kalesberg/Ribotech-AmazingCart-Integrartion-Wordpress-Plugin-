<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
<script src="<?php echo plugins_url( 'AmazingCart/js/noty/jquery.noty.js')."" ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/layouts/top.js')."" ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/layouts/topLeft.js')."" ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/layouts/topRight.js')."" ?>"></script>
<!-- You can add more layouts if you want -->

<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/themes/default.js')."" ?>"></script>
<?php


$payment_gateway_meta = new AmazingCart_paymentgateway_meta();


?>

<div>
	<div>These are available payment gateway that you enabled from WooCommerces Settings.</div>
<div>1. You can choose if you dont want to display in mobile</div>
<div>2. Some payment gateway required user to use safari. So instead of using in app webview, you can redirect it to safari.</div>

</div>

<div style="margin-top:20px; width:98%;" id="amazingcart_payment_gateway_list">

<?php
$payment_gateway_meta->payment_gateway_list();
?>

</div>

<script>
	
	function safariItExe(kesy){
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=payment_gateway_meta_useInApp_to_safari",{key:kesy},function(result){
	   
	 $( "#amazingcart_payment_gateway_list" ).load( "<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=payment_gateway_list_ajax", function() {
  processing.close();
});
		
		});
		
	}
	
	function useInWebItExe(kesy){
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=payment_gateway_meta_safari_to_useInAppWeb",{key:kesy},function(result){
	   
	 $( "#amazingcart_payment_gateway_list" ).load( "<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=payment_gateway_list_ajax", function() {
  processing.close();
});
		
		});
		
	}
	
	
	
	function hideItExe(kesy){
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=payment_gateway_meta_hide_it_to_display_it",{key:kesy},function(result){
	   
	 $( "#amazingcart_payment_gateway_list" ).load( "<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=payment_gateway_list_ajax", function() {
  processing.close();
});
		
		});
		
	}
	
	function displayItExe(kesy){
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=payment_gateway_meta_display_it_to_hide_it",{key:kesy},function(result){
	   
	   
	   $( "#amazingcart_payment_gateway_list" ).load( "<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=payment_gateway_list_ajax", function() {
  processing.close();
});
	
		
		});
		
	}
	
</script>
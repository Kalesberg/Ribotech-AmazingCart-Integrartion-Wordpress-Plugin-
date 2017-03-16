<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
<script src="http://mjsarfatti.com/sandbox/nestedSortable/jquery-ui-1.8.16.custom.min.js"></script>
<script src="<?php echo plugins_url( 'AmazingCart/js/jquery.mjs.nestedSortable.js')."" ?>"></script>
<script src="<?php echo plugins_url( 'AmazingCart/js/noty/jquery.noty.js')."" ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/layouts/top.js')."" ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/layouts/topLeft.js')."" ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/layouts/topRight.js')."" ?>"></script>
<!-- You can add more layouts if you want -->

<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/themes/default.js')."" ?>"></script>

<div style="padding:5px;">	

	<div style="font-size:14px; margin-top:5px;"><b><u>Instagram Settings</u></b></div>
    <div style="margin-top:10px;">
		<div style="float:left; width:200px; margin-top:5px;">Instagram Client id :</div>
		<div style="float:left;"><input type="text" name="instagramClientID" id="instagramClientID" value="<?php echo get_option( 'instagramClientID' ); ?>"></div>
        <div style="clear:both;"></div>
        
        <div style="float:left; width:200px; margin-top:5px;">Instagram Client Secret :</div>
		<div style="float:left;"><input type="text" name="instagramClientSecret" id="instagramClientSecret" value="<?php echo get_option( 'instagramClientSecret' ); ?>"></div>
        <div style="clear:both;"></div>
        
        <div style="float:left; width:200px; margin-top:5px;">Instagram Website Url :</div>
		<div style="float:left;"><input type="text" name="instagramWebsiteUrl" id="instagramWebsiteUrl" value="<?php echo get_option( 'instagramWebsiteUrl' ); ?>"> <i>Tips : Your website url</i></div>
        <div style="clear:both;"></div>
        
         <div style="float:left; width:200px; margin-top:5px;">Instagram Redirect Uri :</div>
		<div style="float:left;"><input type="text" name="instagramRedirectUri" id="instagramRedirectUri" value="<?php echo get_option( 'instagramRedirectUri' ); ?>"> <i>Tips : Your website url</i></div>
        <div style="clear:both;"></div>
        
        <div style="margin-top:10px;">Note : To get these info, Please go to <a href="http://instagram.com/developer/">http://instagram.com/developer/</a> to get your own token number. It only take you about 5 minutes only.</div>
        <div style="margin-top:20px;"><button  id="saveInstagramSettingBtn" class="button-primary" onClick="saveBtn()">Save</button></div>
	</div>
    
    <div style="margin-top:20px;">
    <div style="font-size:14px; margin-top:5px;"><b><u>Push Notification Settings</u></b></div>
    <div style="margin-top:10px;">
    	<div>
    	<div style="float:left; width:400px;">When Save Order, notify user the changed via Push Notification </div>
        
         <?php if(get_option( 'amazingcart_save_order_push' ) == 1){ ?>
         <div style="float:left;"><input type="checkbox" name="amazingCart_save_order_push" id="amazingCart_save_order_push" value="1" checked="checked"></div>
        <?php }else{ ?>
         <div style="float:left;"><input type="checkbox" name="amazingCart_save_order_push" id="amazingCart_save_order_push" value="1"></div>
        <?php } ?>
        <div style="clear:both;"></div>
    	</div>
        
        <div style="margin-top:10px;">
    	<div style="float:left; width:400px;">Note for customer : Notify user via Push Notification</div>
        <?php if(get_option( 'amazingcart_customer_note_push' ) == 1){ ?>
        <div style="float:left;"><input type="checkbox" name="amazingCart_save_note_for_customer_push" id="amazingCart_save_note_for_customer_push" value="1" checked="checked"></div>
        <?php }else{ ?>
        <div style="float:left;"><input type="checkbox" name="amazingCart_save_note_for_customer_push" id="amazingCart_save_note_for_customer_push" value="1" ></div>
        <?php } ?>
        <div style="clear:both;"></div>
    	</div>
        
        
        <div style="margin-top:10px;">
    	<div style="float:left; width:400px;">Comment Follow Up : Notify user via Push Notification</div>
         <?php if(get_option( 'amazingcart_comment_follow_up_push' ) == 1){ ?>
         <div style="float:left;"><input type="checkbox" name="amazingCart_comment_follow_push" id="amazingCart_comment_follow_push" value="1" checked="checked"></div>
        <?php }else{ ?>
         <div style="float:left;"><input type="checkbox" name="amazingCart_comment_follow_push" id="amazingCart_comment_follow_push" value="1" ></div>
        <?php } ?>
        <div style="clear:both;"></div>
    	</div>
        
        <div style="margin-top:20px;"><button id="savePushNoti"  class="button-primary" onClick="savePushBtn()">Save</button></div>
    </div>
    </div>
    
 </div>
 
 

 <script>
 
 	function savePushBtn()
	{
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		$('#savePushNoti').attr('disabled', 'disabled');
		
	
	
	
   if ($('#amazingCart_save_order_push').is(':checked')) {
    var1 = 1;
	} else {
   var1 = 0;
	} 
	
	if ($('#amazingCart_save_note_for_customer_push').is(':checked')) {
    var2 = 1;
	} else {
   var2 = 0;
	} 
	
	if ($('#amazingCart_comment_follow_push').is(':checked')) {
    var3 = 1;
	} else {
   var3 = 0;
	}
	
	$.post("<?php echo bloginfo('url'); ?>/?amazingCart=adminAjaxSave&type=setting_general_push",{
        amazingCart_save_order_pushs:var1,
        amazingCart_save_note_for_customer_pushs:var2,
        amazingCart_comment_follow_pushs:var3},function(result){
	   
	 
	   
	   $('#savePushNoti').attr('disabled', false);
	   	 processing.close();
		
		});
		
	}
 
 
 function saveBtn()
	{
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		$('#saveInstagramSettingBtn').attr('disabled', 'disabled');
		
	
	var instagramClientIDs = $('#instagramClientID').val();
	var instagramClientSecrets = $('#instagramClientSecret').val();
	var instagramWebsiteUrls = $('#instagramWebsiteUrl').val();
	var instagramRedirectUris = $('#instagramRedirectUri').val();
	

	
	$.post("<?php echo bloginfo('url'); ?>/?amazingCart=adminAjaxSave&type=setting_general",{instagramClientID:instagramClientIDs,instagramClientSecret:instagramClientSecrets,instagramWebsiteUrl:instagramWebsiteUrls,instagramRedirectUri:instagramRedirectUris},function(result){
	   
	   $('#saveInstagramSettingBtn').attr('disabled', false);
	   	 processing.close();
		
		});
		
	}
 
 </script>
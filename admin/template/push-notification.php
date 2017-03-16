<?php 
$previous = new pushnotification;
 ?>
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 
 <script src="http://malsup.github.com/jquery.form.js"></script> 
<div style=" padding-right:10px;">
<?php if(get_option("amazingcart_pushNotificationWorkingIndicator") == 1){ ?>

<?php }else if(get_option("amazingcart_pushNotificationWorkingIndicator") == 2){ ?>
<!--<div style="padding:10px; background-color:#FC0;">Your push notification test was failed. Please test you APNS before you can use this page.</div>-->
<?php }else{ ?>
<!--<div style="padding:10px; background-color:#FC0;">You haven't setup your push notification. Please setup your APNS and run a test.</div>-->
<?php } ?>

<script>

function onchangeRedirect(){
	
	var val = $('#redirectTo').val();
	
	if(val == "none")
	{
		$('#postIDRedirect').hide();
		$('#pageIDRedirect').hide();
		$('#linkIDRedirect').hide();
		$('#productIDRedirect').hide();
	}
	else if(val == "post")
	{
		$('#postIDRedirect').show();
		$('#pageIDRedirect').hide();
		$('#linkIDRedirect').hide();
		$('#productIDRedirect').hide();
	}
	else if(val == "page")
	{
		$('#postIDRedirect').hide();
		$('#pageIDRedirect').show();
		$('#linkIDRedirect').hide();
		$('#productIDRedirect').hide();
	}
	else if(val == "link")
	{
		$('#postIDRedirect').hide();
		$('#pageIDRedirect').hide();
		$('#linkIDRedirect').show();
		$('#productIDRedirect').hide();
	}
	else if(val == "product")
	{
		$('#postIDRedirect').hide();
		$('#pageIDRedirect').hide();
		$('#linkIDRedirect').hide();
		$('#productIDRedirect').show();
	}
}
</script>

<form action="<?php echo get_bloginfo( 'url' )."/?amazingcart=pushnotificationajax"; ?>" method="post" name="krukpushnotification" id="krukpushnotification">
	<div><h1>Push Notification</h1></div>
    <div><hr /></div>
    <div>Total Subscriber : <b><?php echo $previous->getTotalSubscriber(); ?> users</b></div>
    
	<div><h3>Your Message</h3></div>
    <div>Not more than 200 characters</div>
    <div><textarea name="pushmessage" id="pushmessage " onkeyup="countChar(this)" style="width:100%; height:40px;"></textarea></div>
   	<div>
    <div style="float:left; width:34%;"><input type="submit" value="Push Now" name="testExternalServer" id="testExternalServer" class="button-primary"/> <span id="charNum">200 characters left
</span></div>
	<div style="float:left; margin-top:4px; width:65%;">
    <div align="right">
    <span><select id="redirectTo" name="redirectTo" onchange="onchangeRedirect()">
    <option value="none">Redirect To None</option>
    <option value="post">Redirect To Post</option>
    <option value="page">Redirect To Page</option>
    <option value="product">Redirect To Product</option>
    <option value="link">Redirect To Link</option>
    
    </select>
    </span>
    <span id="postIDRedirect" style="display:none;">
    Post Id <input type="text" name="postIDRedirect" value="" /> e.g 64
    </span>
    <span id="pageIDRedirect" style="display:none;">
    Page Id <input type="text" name="pageIDRedirect" value="" /> e.g 24
    </span>
     <span id="linkIDRedirect" style="display:none;">
    Link : <input type="text" name="linkIDRedirect" value="" /> e.g http://www.google.com
    </span>
    <span id="productIDRedirect" style="display:none;">
    Product Id : <input type="text" name="productIDRedirect" value="" /> e.g 24
    </span>
    </div>
    </div>
    <div style="clear:both;"></div>
    </div>
    <div style="margin-top:10px; margin-bottom:10px; padding:20px; background-color:#CCC; display:none;" id="progressing">
    <div><b>Please don't close this window.</b></div>
    <div><div style="float:left; margin-right:10px;"><img src="<?php echo PLUGIN_DIR . 'images/ajax-loader.gif'; ?>" /></div> <div style="float:left; ">Progress : <span id="totalSum">0</span> / <?php echo $previous->getTotalSubscriber(); ?></div><div style="clear:both;"></div></div>
    
    </div>
    
    <div><h3>Previous Push</h3></div>
    <div style="padding-right:10px;" id="previouspost">
    	<?php echo $previous->getAllPreviousPushed(1,5); ?>
    </div>
    <div id="loadingPagination" style="display:none;"><img src="<?php echo PLUGIN_DIR . 'images/ajax-loader.gif'; ?>" /></div>
</form>
</div>

<script>

$(document).ready(function() { 

    // bind form using ajaxForm 
    $('#krukpushnotification').ajaxForm({ 
        // dataType identifies the expected content type of the server response 
        dataType:  'json', 
 		beforeSubmit:  showRequest,
        // success identifies the function to invoke when the server response 
        // has been received 
        success:   processJson 
    }); 
});
function showRequest(formData, jqForm, options) {
	if($('#pushmessage').val() == "")
	{
		alert('Your message is empty');	
		return false;
	}
	else
	{
	$("#pushmessage").prop("disabled",true);
	$("#testExternalServer").prop("disabled",true);
	$('#totalSum').html('0');
	$('#progressing').slideDown('fast');
	return true;
	}
	
}

function processJson(data) { 
	$('#previouspost').load('<?php echo get_bloginfo( 'url' )."/?amazingcart=previous_pushed"; ?>&page=1', function() {
		pushNotification();
	});    	
}


function pagination(page)
{
	$('#loadingPagination').show();
	 $('#previouspost').load('<?php echo get_bloginfo( 'url' )."/?amazingcart=previous_pushed"; ?>&page='+page+'', function() {
	
 	$('#loadingPagination').hide();
	});
}

function pushNotification(){
		var total_subs = <?php echo $previous->getTotalSubscriber(); ?>
		
		$.ajax({
		'cache': true,
        'async': false,
        'global': true,
        'url': '<?php echo bloginfo('url'); ?>/?amazingcart=ajax_push',
        'dataType': "json",
        'success': function (data) {
			
            if(data.total_user_left > 0)
			{
				sums = total_subs - data.total_user_left;
				
				
				
				$('#totalSum').html(sums);
				
				pushNotification();
			}
			else
			{
				
				sums = total_subs - data.total_user_left;
				$('#totalSum').html(sums);
				
				 $("#pushmessage").prop("disabled",false);
				$("#testExternalServer").prop("disabled",false);
				
 				$('#pushmessage').val('');
				$('#progressing').slideUp('fast');
			}
        }
   	 });
	 
	 
	
	}
	
	function countChar(val){
                                var len = val.value.length;
                                if (len >= 200) {
                                    val.value = val.value.substring(0, 200);
                                }else {
                                    $('#charNum').text(200 - len+' characters left');
                                }
        }
	
</script>
<?php

$push = new pushnotification;

if(get_option("amazingcart_ApnsEnv") == 1)
{
	$env="checked";	
	$env2="";
	
	$nameEnv = "sandbox";
	$pemEnv = get_option("amazingcart_pem");
}
else
{
	$env="";
	$env2="checked";
	
	$nameEnv = "production";
	$pemEnv = get_option("amazingcart_pem_production");
}



?>
<div style="padding-right:10px;"><form action="" method="post">

    <div>
    	<div><h2>Push Notification settings</h2></div>
         <div><h3>Step 1 : Choose your enviroment</h3></div>
         <div>If your Amazing Cart is stil not in the AppStore, choose development enviroment. If your Amazing Cart is in the AppStore, choose production</div>
        <div style="margin-top:10px;">
        <div style="float:left; width:150px;">
        <label>
          <input type="radio" name="chooseEnviroment"  value="1" <?php echo $env; ?> id="env_0">
          Development</label>
    </div>
    <div style="float:left; width:150px;">
        <label>
         <input type="radio" name="chooseEnviroment"  value="2" <?php echo $env2; ?> id="env_1">
          Production</label>
    </div>
    <div style="clear:both;"></div>
        </div>
        <div ><h3>Step 2 : Upload your .pem in server using FTP and paste the link here</h3></div>
        <div style="margin-bottom:20px;">e.g http://www.example.com/wp-content/uploads/[your .pem file name].pem</div>
    	<div>Push Notification cert (.pem) (Development): http://<input type="text" name="chatPemKruk" id="chatPemKruk" value="<?php echo get_option("amazingcart_pem"); ?>" size="30"></div>
        <div>Push Notification cert (.pem) (Production):  http://<input type="text" name="chatPemKrukPro" id="chatPemKrukPro" value="<?php echo get_option("amazingcart_pem_production"); ?>" size="30"></div>
        
        <div><h3>Step 3 : Your push notification password or phrase :</h3></div>
    <div><input type="text" name="pemPassword" id="pemPassword" value="<?php echo get_option("amazingcart_pemPassword"); ?>" size="30"> e.g abcd</div>
    <div>Your push notification password was created in .pem file you just generate</div>
        
        
    </div>
    
    
    
    <div><h3>Step 4 : Choose your server</h3></div>
<div style="margin-top:-10px;">Most of the hosting will disable Apns port 2195 or 2196. This will result the apns will not going to work. You can choose 3 options here :</div>


<?php
if(get_option("amazingcart_ChooseHost") == 1)
{
	$currentServer="checked";	
	
	$currentServerText="";	
	$externalServerText="display:none;";	
	$displayKrukServerText="display:none;";
	$idontText="display:none;";
	
}
else if(get_option("amazingcart_ChooseHost") == 2)
{
	
	$externalServer="checked";
	
	$currentServerText="display:none;";	
	$externalServerText="";	
	$displayKrukServerText="display:none;";
	$idontText="display:none;";
}
else
{
	$idont="checked";
	
	$currentServerText="display:none;";	
	$externalServerText="display:none;";	
	$displayKrukServerText="display:none;";
	$idontText="";
}
?>


<div style="margin-top:10px;">
    
    <div style="float:left; width:150px;">
        <label>
         <input type="radio" name="ChooseHost" onClick="displayExternalServer()" value="2" <?php echo $externalServer; ?> id="ChooseHost_1">
          Use External Server</label>
    </div>
    <div style="float:left; width:150px;">
        <label>
          <input type="radio" name="ChooseHost" onClick="displayIDontNeed()" value="4" <?php echo $idont; ?> id="ChooseHost_3">
          its ok, I don't need APNS</label>
    </div>
    <div style="clear:both;"></div>
</div>

<div id="thisServer" style="<?php echo $currentServerText;  ?> padding:10px; border:#999; border-style:solid; border-width:3px; margin-top:20px;">
You can try out using current server. If you success, no need to choose other option. <br><br>*You must install scrollblog into your device and allow push notification.
<div style="margin-top:20px; margin-bottom:20px;"><input type="submit" value="Test Now" name="testCurrentServer" class="button-primary"/>  </div>
</div>


<div style="<?php echo $externalServerText;  ?> padding:10px; border:#999; border-style:solid; border-width:3px; margin-top:20px;" id="externalServer">
If your current server was failed. You can use your external server. Please upload krukapns.php provided into your other hosting or server. Get the link. e.g http://www.yourdomain.com/krukapns.php and paste it into text form below.
	<div style="margin-top:20px;"> http://<input type="text" id="kruk_external_server" name="kruk_external_server" size="30" value="<?php echo get_option("amazingcart_externalServer"); ?>"> e.g http://www.yourdomain.com/krukapns.php</div>
    <br><br>*You must install Amazing Cart into your device and allow push notification
    <div style="margin-top:20px; margin-bottom:20px;"><input type="submit" value="Test Now" name="testExternalServer" class="button-primary"/> </div>
    <?php if($_GET['status'] == "Success"){ ?>
    <?php update_option("amazingcart_pushNotificationWorkingIndicator",1);   ?>
    	<div style="color:green;"><b>Message Delivered</b></div>
    <?php }else if($_GET['status'] == "Failed"){ ?>
    <?php update_option("amazingcart_pushNotificationWorkingIndicator",2);   ?>
    	<div style="color:red; font-weight:bold;">Failed</div>
        <div ><?php echo $_GET['reason']; ?></div>
	<?php } ?>
</div>



<div style="<?php echo $idontText;  ?>padding:10px; border:#999; border-style:solid; border-width:3px; margin-top:20px;" id="notChoose">
Don't worry.You always can setup later.
</div>
<div style="margin-top:20px; margin-bottom:20px;"><input type="submit" value="Save All" name="saveSetting2" class="button-primary"/>  </div>



<?php 

if (isset($_POST["testExternalServer"])) {  


$decoded = $push->pushToAllTestExternalServer(get_option("amazingcart_pemPassword"),$nameEnv,$pemEnv);
echo '<meta HTTP-EQUIV="REFRESH" content="0; url=admin.php?page=AmazingCartSettings&tab=pushnotification&status='.$decoded->status.'&reason='.$decoded->reason.'">';


}

if (isset($_POST["testCurrentServer"])) {  


$decoded = $push->pushToAllTestCurrentServer(get_option("amazingcart_pemPassword"),$nameEnv,$pemEnv);
echo '<meta HTTP-EQUIV="REFRESH" content="0; url=admin.php?page=AmazingCartSettings&tab=pushnotification&status='.$decoded->status.'&reason='.$decoded->reason.'">';


}


if (isset($_POST["saveSetting2"])) {  

	update_option("amazingcart_ChooseHost",$_POST['ChooseHost']);
	update_option("amazingcart_pem",$_POST['chatPemKruk']);
;
	update_option("amazingcart_ApnsEnv",$_POST['chooseEnviroment']);
;
	update_option("amazingcart_pem_production",$_POST['chatPemKrukPro']);
	update_option("amazingcart_externalServer",$_POST['kruk_external_server']);
	update_option("amazingcart_pemPassword",$_POST['pemPassword']);
	echo '<meta http-equiv="refresh" content="0; url=admin.php?page=AmazingCartSettings&tab=pushnotification">';
}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>
function displayUseThisServer(){
	
	$("#thisServer").show();
	$("#externalServer").hide();
	
	$("#notChoose").hide();
}

function displayExternalServer(){
	
	$("#thisServer").hide();
	$("#externalServer").show();

	$("#notChoose").hide();
}





function displayIDontNeed(){
	
	$("#thisServer").hide();
	$("#externalServer").hide();
	
	$("#notChoose").show();
	
}

</script>
   </form> 
</div>
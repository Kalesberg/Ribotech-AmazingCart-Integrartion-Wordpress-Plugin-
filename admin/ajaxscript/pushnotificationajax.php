<?php  
	header('Content-type: text/html; charset=utf-8');
	header('HTTP/1.1 200 OK');
	session_start();
	if($_POST['testExternalServer'])
{
global $wpdb;
$_SESSION['pushMsg'] = stripslashes($_POST['pushmessage']);


$_SESSION['pushType'] = $_POST['redirectTo'];

if($_POST['redirectTo'] == "none")
{
$_SESSION['pushID'] = 0;

$qry = "INSERT INTO ".$wpdb->prefix."amazingcart_pushnotification (pushmsg, unixtime,type,pID) values('".stripslashes(mysql_real_escape_string($_POST['pushmessage']))."','".date("U")."','none','0')";
 
}
else if($_POST['redirectTo'] == "post")
{
	
	$_SESSION['pushID'] = $_POST['postIDRedirect'];

$qry = "INSERT INTO ".$wpdb->prefix."amazingcart_pushnotification (pushmsg, unixtime,type,pID) values('".stripslashes(mysql_real_escape_string($_POST['pushmessage']))."','".date("U")."','". $_POST['redirectTo']."','".$_POST['postIDRedirect']."')";
}
else if($_POST['redirectTo'] == "page")
{
	
	$_SESSION['pushID'] = $_POST['pageIDRedirect'];

$qry = "INSERT INTO ".$wpdb->prefix."amazingcart_pushnotification (pushmsg, unixtime,type,pID) values('".stripslashes(mysql_real_escape_string($_POST['pushmessage']))."','".date("U")."','". $_POST['redirectTo']."','".$_POST['pageIDRedirect']."')";
}
else if($_POST['redirectTo'] == "link")
{
	$_SESSION['pushID'] = $_POST['linkIDRedirect'];

$qry = "INSERT INTO ".$wpdb->prefix."amazingcart_pushnotification (pushmsg, unixtime,type,pID) values('".stripslashes(mysql_real_escape_string($_POST['pushmessage']))."','".date("U")."','". $_POST['redirectTo']."','".$_POST['linkIDRedirect']."')";
}
else if($_POST['redirectTo'] == "product")
{
	$_SESSION['pushID'] = $_POST['productIDRedirect'];

$qry = "INSERT INTO ".$wpdb->prefix."amazingcart_pushnotification (pushmsg, unixtime,type,pID) values('".stripslashes(mysql_real_escape_string($_POST['pushmessage']))."','".date("U")."','". $_POST['redirectTo']."','".$_POST['productIDRedirect']."')";
}
  
 $wpdb->query($qry);



$sql = "UPDATE ".$wpdb->prefix."amazingcart_user SET indicator='0'";
$wpdb->query($sql);

echo '{ "message": "next" }';  
}
?>
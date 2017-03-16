<?php
	session_start();

	header("Cache-control: private"); //IE 6 Fix
	ini_set('session.gc_maxlifetime', 60);
		
	$admin_idx = $_SESSION['aidx'];
	$admin_username = $_SESSION['cuname'];
	$admin_passwd = $_SESSION['cpasswd'];

	if(!isset($admin_username))
	{
		$current_page = basename($_SERVER['PHP_SELF']);
		
		if( $current_page!="index.php" && $current_page!="confirm.php")
		{
			echo '<script type="text/javascript">';
			echo 'document.location.href="index.php";';
			echo '</script>';
			exit();
		}
	}
?>
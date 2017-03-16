<?php
class AmazingCartStatus{
	
	
	
	function checkInstagramClient()
	{
		if(get_option( 'instagramClientID' ) == "" || get_option( 'instagramClientSecret' ) == "" || get_option( 'instagramWebsiteUrl' ) == "" || get_option( 'instagramRedirectUri' ) == "")
		{
			
			return false;
		}
		else
		{
			return true;
		}
		
	}
	
	
	
}


?>
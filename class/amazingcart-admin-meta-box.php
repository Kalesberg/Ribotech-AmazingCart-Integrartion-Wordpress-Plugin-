<?php
class amazingcart_admin_meta_box{
		
	var $optionArray;
	var $optionOnPostDetailArray;
		var $optionOnBrowseArray;
	 public function __construct() {
			
			$this->option_array_value();
			$this->option_onbrowse_array_value();
			$this->option_array_post_value();
			
			add_action('admin_menu', array($this,'pageMetaBox'));
			add_action('admin_menu', array($this,'postMetaBox'));
			
			add_action('save_post', array($this,'savePostMeta'));
	}
	
	function pageMetaBox()
	{
    add_meta_box('AmazingCart', // Unique ID
        'Sweet Page Template', // Title
        array($this,"metaBoxUI"), // Callback function
        'page', // Admin page (or post type)
        'side', // Context
        'high' // Priority
        );
		
	}
	
	function metaBoxUI($post){
		global $AmazingCartStatus;
			echo '<div>Page Template</div>';
		echo '<select name="amazingPageTemplate" id="amazingPageTemplate" onchange="openInput()">';
		
		foreach($this->optionArray as $option)
		{
			if($option['value'] == get_post_meta( $post->ID, "amazing_pagetemplate", true ))
			{
		   echo '<option value="'.$option['value'].'" selected="selected">'.$option['label'].'</option>';
			}
			else
			{
			echo '<option value="'.$option['value'].'">'.$option['label'].'</option>';	
			}
		}
		
		echo '</select>';
		
		
		?>
        
        <div id="amazingOfficeLocationDiv" style="display:none; margin-top:10px;">
        <div>Store Name</div>
        <div><input id="amazingStoreName" name="amazingStoreName" value="<?php echo get_post_meta( $post->ID, "amazingStoreName", true ); ?>"  /></div>
        <div style="margin-top:10px;">Store Address</div>
        <div><input id="amazingStoreAddress" name="amazingStoreAddress" value="<?php echo get_post_meta( $post->ID, "amazingStoreAddress", true ); ?>"  /></div>
        
        
        <div style="margin-top:10px;">Store Longtitude</div>
        <div><input id="amazingLat" name="amazingLat" value="<?php echo get_post_meta( $post->ID, "amazingLat", true ); ?>"  /></div>
        <div style="margin-top:10px;">Store Longtitude</div>
        <div  ><input id="amazingLong" name="amazingLong" value="<?php echo get_post_meta( $post->ID, "amazingLong", true ); ?>"  /></div>
         <div style="margin-top:10px;">Store Email</div>
        <div  ><input id="amazingLocEmail" name="amazingLocEmail" value="<?php echo get_post_meta( $post->ID, "amazingLocEmail", true ); ?>"  /></div>
        <div style="margin-top:10px;">Store Phone Number</div>
        <div  ><input id="amazingLocPhone" name="amazingLocPhone" value="<?php echo get_post_meta( $post->ID, "amazingLocPhone", true ); ?>"  /></div>
        <div style="margin-top:10px;"><b>Sweet Tips :</b> When you enable this template and when a user tap in the menu. Map page will apear with email and phone number.Suitable if you have multiple store. Your content on your left side will no use at all.</div>
        </div>
        
       
        <div id="amazingEmailDiv" style="display:none; margin-top:10px;">
        <div>Add your Email address</div>
        <div><input id="amazingEmail" name="amazingEmail" value="<?php echo get_post_meta( $post->ID, "amazingEmail", true ); ?>"  /></div>
        <div style="margin-top:10px;"><b>Sweet Tips :</b> When you enable this template and when a user tap in the menu. Email Form will appear with your email provided. Your content on your left side will no use at all.</div>
        </div>
     	
        <div id="amazingCallUsDiv" style="display:none; margin-top:10px;">
        <div>Add your contact number</div>
        <div><input id="amazingCallUs" name="amazingCallUs" value="<?php echo get_post_meta( $post->ID, "amazingCallUs", true ); ?>"  /></div>
        <div style="margin-top:10px;"><b>Sweet Tips :</b> When you enable this template and when a user tap in the menu. It will call your number in one click. Your content on your left side will no use at all.</div>
        </div>
        
        <div id="amazingRSSFeedDiv" style="display:none; margin-top:10px;">
        <div>Add your RSS URL with http:// include</div>
        <div><input id="amazingRSSFeed" name="amazingRSSFeed" value="<?php echo get_post_meta( $post->ID, "amazingRSSFeedUrl", true ); ?>"  /></div>
        <div style="margin-top:10px;"><b>Sweet Tips :</b> When you enable this template and when a user tap in the menu. It will show the content of your RSS Feed. Your content on your left side will no use at all.</div>
        </div>
        
        
        <div id="imgGalleryDiv" style="display:none; margin-top:10px;">
       
       
       
        <div style="margin-top:10px;"><b>Sweet Tips :</b> When you enable this template and when a user tap in the menu. Image gallery wil appear and show in thumbnail and full screen. Upload your image using using WP Simple Gallery below. Your content and featured image will no use at all.</div>
        </div>
        
        <div id="instagramDiv" style="display:none; margin-top:10px;">
       
              <?php if($AmazingCartStatus->checkInstagramClient() == false){ ?>
       <div style="margin-top:10px;"><b>Sweet Error :</b> You havent configure your instagram Client. Please go to AmazingCart->Settings->General</div>
       
       <?php }else{ ?>
       <div>Please provide your #HashTag</div>
       <div>#<input id="amazingInstagramHashtag" name="amazingInstagramHashtag" value="<?php echo get_post_meta( $post->ID, "amazingInstagramHashtag", true ); ?>"  /></div>

        <div style="margin-top:10px;"><b>Sweet Tips :</b> Run your contest or what ever purpose you want. Ask your customer to #tag thier photo. It will appear in your App.</div>
        
        <?php } ?>
        </div>
        
        
        <?php
		
		
		echo '<div id="amazingEnableShareDiv"><hr style="margin-top:10px;"/><div style="margin-top:10px;">Enable/Disable Page Share</div>';
		echo '<div ><select name="amazingEnableShare">';
		
		if(get_post_meta( $post->ID, "amazing_enableshare", true ) == "yes")
		{
		  	 echo '<option value="no" >NO</option>';
		     echo '<option value="yes" selected="selected" >YES</option>';
		}
		else
		{
			 echo '<option value="no" selected="selected" >NO</option>';
		     echo '<option value="yes"  >YES</option>';
		}
		
		echo '</select></div></div>';
		
		?>
        <script>
			openInput();
			function openInput()
			{
				var e = document.getElementById("amazingPageTemplate");
				var strUser = e.options[e.selectedIndex].value;
				if(strUser == "CallUs")
				{
					document.getElementById('amazingEnableShareDiv').style.display = "none";
					document.getElementById('instagramDiv').style.display = "none";
					document.getElementById('imgGalleryDiv').style.display = "none";
					document.getElementById('amazingOfficeLocationDiv').style.display = "none";
				document.getElementById('amazingCallUsDiv').style.display = "block";
				document.getElementById('amazingEmailDiv').style.display = "none";
				document.getElementById('amazingRSSFeedDiv').style.display = "none";
				}
				else if(strUser == "EmailUs")
				{
					document.getElementById('amazingEnableShareDiv').style.display = "none";
					document.getElementById('instagramDiv').style.display = "none";
					document.getElementById('imgGalleryDiv').style.display = "none";
					document.getElementById('amazingOfficeLocationDiv').style.display = "none";
				document.getElementById('amazingCallUsDiv').style.display = "none";
				document.getElementById('amazingEmailDiv').style.display = "block";
				document.getElementById('amazingRSSFeedDiv').style.display = "none";
					
				}
				else if(strUser == "RSSFeed")
				{
					document.getElementById('amazingEnableShareDiv').style.display = "none";
					document.getElementById('instagramDiv').style.display = "none";
					document.getElementById('imgGalleryDiv').style.display = "none";
					document.getElementById('amazingOfficeLocationDiv').style.display = "none";
					document.getElementById('amazingCallUsDiv').style.display = "none";
				document.getElementById('amazingEmailDiv').style.display = "none";
				document.getElementById('amazingRSSFeedDiv').style.display = "block";
				}
				else if(strUser == "OfficeOrStoreLocation")
				{
					document.getElementById('amazingEnableShareDiv').style.display = "none";
					document.getElementById('instagramDiv').style.display = "none";
					document.getElementById('amazingOfficeLocationDiv').style.display = "block";
					document.getElementById('amazingCallUsDiv').style.display = "none";
				document.getElementById('amazingEmailDiv').style.display = "none";
				document.getElementById('amazingRSSFeedDiv').style.display = "none";
				}
				else if(strUser == "imggallery")
				{
					document.getElementById('amazingEnableShareDiv').style.display = "none";
					document.getElementById('instagramDiv').style.display = "none";
					document.getElementById('imgGalleryDiv').style.display = "block";
					document.getElementById('amazingOfficeLocationDiv').style.display = "none";
					document.getElementById('amazingCallUsDiv').style.display = "none";
				document.getElementById('amazingEmailDiv').style.display = "none";
				document.getElementById('amazingRSSFeedDiv').style.display = "none";
				}
				else if(strUser == "instagramHash")
				{
					document.getElementById('amazingEnableShareDiv').style.display = "none";
					document.getElementById('instagramDiv').style.display = "block";
					document.getElementById('imgGalleryDiv').style.display = "none";
					document.getElementById('amazingOfficeLocationDiv').style.display = "none";
					document.getElementById('amazingCallUsDiv').style.display = "none";
				document.getElementById('amazingEmailDiv').style.display = "none";
				document.getElementById('amazingRSSFeedDiv').style.display = "none";
				}
				else 
				{document.getElementById('amazingEnableShareDiv').style.display = "block";
					document.getElementById('instagramDiv').style.display = "none";
					document.getElementById('imgGalleryDiv').style.display = "none";
					document.getElementById('amazingOfficeLocationDiv').style.display = "none";
					document.getElementById('amazingCallUsDiv').style.display = "none";
					document.getElementById('amazingEmailDiv').style.display = "none";
					document.getElementById('amazingRSSFeedDiv').style.display = "none";
				}
			}
		
		</script>
        
        <?php
		
	}
	
	function savePostMeta($post_id)
	{
		if($_POST['amazingPageTemplate'])
		{
		$dracoPageTemplate = $_POST['amazingPageTemplate'];
		update_post_meta($post_id, 'amazing_pagetemplate', $dracoPageTemplate);
				
				if($_POST['amazingPageTemplate'] == "EmailUs")
				{
					$amazingEmail = $_POST['amazingEmail'];
					update_post_meta($post_id, 'amazingEmail', $amazingEmail);
				}
				else if($_POST['amazingPageTemplate'] == "CallUs")
				{
					$amazingCallUs = $_POST['amazingCallUs'];
					update_post_meta($post_id, 'amazingCallUs', $amazingCallUs);
				}
				else if($_POST['amazingPageTemplate'] == "RSSFeed")
				{
					$amazingRSSFeed = $_POST['amazingRSSFeed'];
					update_post_meta($post_id, 'amazingRSSFeedUrl', $amazingRSSFeed);
				}
				else if($_POST['amazingPageTemplate'] == "OfficeOrStoreLocation")
				{
					$amazingStoreName = $_POST['amazingStoreName'];
					update_post_meta($post_id, 'amazingStoreName', $amazingStoreName);
					
					$amazingStoreAddress = $_POST['amazingStoreAddress'];
					update_post_meta($post_id, 'amazingStoreAddress', $amazingStoreAddress);
					
					$amazingLat = $_POST['amazingLat'];
					update_post_meta($post_id, 'amazingLat', $amazingLat);
					
					$amazingLong = $_POST['amazingLong'];
					update_post_meta($post_id, 'amazingLong', $amazingLong);
					
					$amazingLocEmail = $_POST['amazingLocEmail'];
					update_post_meta($post_id, 'amazingLocEmail', $amazingLocEmail);
					
					$amazingLocPhone = $_POST['amazingLocPhone'];
					update_post_meta($post_id, 'amazingLocPhone', $amazingLocPhone);
				}
				else if($_POST['amazingPageTemplate'] == "instagramHash")
				{
					
					update_post_meta($post_id, 'amazingInstagramHashtag', $_POST['amazingInstagramHashtag']);
				}
			
		}
		
		if($_POST['onBrowseTemplate'])
		{
		$dracoPageTemplate = $_POST['onBrowseTemplate'];
		update_post_meta($post_id, 'amazing_onBrowseTemplate', $dracoPageTemplate);
		}
		
		if($_POST['amazingEnableShare'])
		{
		$amazingEnableShare = $_POST['amazingEnableShare'];
		update_post_meta($post_id, 'amazing_enableshare', $amazingEnableShare);
		}
	}
	
	

	function option_array_value()
	{
		$array = array(
					array("label"=>"Parallax page","value"=>"ParallaxPage"),
					array("label"=>"Plain page without featured image","value"=>"PlainPageWithoutFeaturedImage"),
					array("label"=>"Office/Store Location","value"=>"OfficeOrStoreLocation"),
					array("label"=>"Call Us","value"=>"CallUs"),
					array("label"=>"Email Us","value"=>"EmailUs"),
					array("label"=>"RSS Feed","value"=>"RSSFeed"),
					array("label"=>"Gallery","value"=>"imggallery"),
					array("label"=>"Instagram HashTag","value"=>"instagramHash"),
					);
					
		$this->optionArray = $array;
		
	}
	
	function option_array_post_value()
	{
		$array = array(
					array("label"=>"Parallax page","value"=>"ParallaxPage"),
					array("label"=>"Plain page without featured image","value"=>"PlainPageWithoutFeaturedImage")
					);
					
		$this->optionOnPostDetailArray = $array;
		
	}
	
	
	function postMetaBox()
	{
    add_meta_box('AmazingCart', // Unique ID
        'Sweet Post Template', // Title
        array($this,"postMetaBoxUI"), // Callback function
        'post', // Admin page (or post type)
        'side', // Context
        'high' // Priority
        );
		
	}
	
	
	function postMetaBoxUI($post){
		
		echo '<div>On Browse Post Template</div>';
		echo '<div><select name="onBrowseTemplate">';
		
		foreach($this->optionOnBrowseArray as $option)
		{
			if($option['value'] == get_post_meta( $post->ID, "amazing_onBrowseTemplate", true ))
			{
		   echo '<option value="'.$option['value'].'" selected="selected">'.$option['label'].'</option>';
			}
			else
			{
			echo '<option value="'.$option['value'].'">'.$option['label'].'</option>';	
			}
		}
		
		echo '</select></div>';
		
		echo '<div style="margin-top:10px;">On Full Content Post Template</div>';
		echo '<div><select name="amazingPageTemplate">';
		
		foreach($this->optionOnPostDetailArray as $option)
		{
			if($option['value'] == get_post_meta( $post->ID, "amazing_pagetemplate", true ))
			{
		   echo '<option value="'.$option['value'].'" selected="selected">'.$option['label'].'</option>';
			}
			else
			{
			echo '<option value="'.$option['value'].'">'.$option['label'].'</option>';	
			}
		}
		
		echo '</select></div>';
		
	}
	
	
	function option_onbrowse_array_value()
	{
		$array = array(
					array("label"=>"Template 1 (title top)","value"=>"onBrowseTemplateTitleImgDesc"),
					array("label"=>"Template 2 (title bottom)","value"=>"onBrowseTemplateImgTitleDate")
					
					);
					
		$this->optionOnBrowseArray = $array;
		
	}
	
}
	
?>
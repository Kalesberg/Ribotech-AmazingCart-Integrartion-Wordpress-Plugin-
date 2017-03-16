<?php
class amazingcart_wp_json_api{
		
		public function __construct() {
			
		
			
			add_action('template_redirect', array($this,'redirectMethod'), 1);
			
			
	}
	
	

	
	
	
	public function redirectMethod() {
        
			global $wpdb;
			
			error_reporting(0);
        if($_GET['amazingcart']=="wp-post-api")
		{
			if($_GET['type'] == "get-single-post")
			{
			
			echo json_encode($this->get_single_post($_GET['postID']));
			
			}
			else if($_GET['type'] == "get-post-by-category-id")
			{
			echo json_encode($this->getPostsByCategoryID($_GET['catID'],$_GET['currentPage'],$_GET['post_per_page']));	
			}
			else if($_GET['type'] == "search-post")
			{
			echo json_encode($this->search_post($_GET['keyword'],$_GET['currentPage'],$_GET['post_per_page']));	
			}
			die();
			
		}
		
		
	}
	
	
	
	public function get_single_post($postID)
 	 {
		 
		 $product = get_post($postID); 
		 
		 return $this->jsonPostSetup($product);
	 }
		
		
		public function search_post($keyword,$current_page=1,$post_per_page = 10)
		{
			$array = array();
		
		    $args = array( 
					'post_type' => 'post', 
					's' => $keyword,
					'posts_per_page' =>$post_per_page,
					'paged'=>$current_page,
					);
				
		$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) : $loop->the_post();


	      	array_push($array,$this->jsonPostSetup($loop->post));
		endwhile;
		
		
		//Count All Post
		$argss = array( 'post_type' => 'post', 's' => $keyword,'posts_per_page' =>-1);
		$loops = new WP_Query( $argss );
		
		$count = 0;
		while ( $loops->have_posts() ) : $loops->the_post();

			$count++;
		    

		endwhile;
		$totalPage = ceil($count/$post_per_page);
			
			return array(
					"categoryName"=>$keyword,
					"current_page"=>(int)$current_page,
					"total_page"=>(int)$totalPage,
					"post_per_page"=>(int)$post_per_page,
					"total_post"=>(int)$count,
					"posts"=>$array
					);
			
		}
		
		
		private function jsonPostSetup($data)
		{
			
			
			if(wp_get_attachment_url(get_post_thumbnail_id($data->ID, 'full')) == false)
			{
				$featuredImage = "0";	
			
			}
			else
			{
				$featuredImage = wp_get_attachment_url(get_post_thumbnail_id($data->ID, 'full'));	
			}
			
			$user_info = get_userdata($data->post_author);
			
			
			$extraimages = get_post_meta($data->ID, 'wpsimplegallery_gallery', true);
			$newextraimagesarray = array();
			foreach($extraimages as $value)
			{
				array_push($newextraimagesarray,wp_get_attachment_url($value));
			}
			
			if($data->post_excerpt == "")
			{
				$newexcerpt = substr(htmlspecialchars(strip_tags($data->post_content)), 0, 100)."...";
				
			}
			else
			{
				$newexcerpt = htmlspecialchars(strip_tags($data->post_excerpt));
			}
			
			
			$array = array(
						"ID"=>$data->ID,
						"title"=>htmlspecialchars(strip_tags($data->post_title)),	
						"post_date"=>$data->post_date,
						"post_date_ago"=>$this->time_elapsed_string(strtotime($data->post_date)),
						"full_content"=>htmlspecialchars(do_shortcode($data->post_content)),
						"excerpt"=>$this->stripBBCode($newexcerpt),
						"page_template_type"=>get_post_meta($data->ID, "amazing_pagetemplate", true ),
						"page_template_meta"=>array
											  (
											  "if_EmailUs"=>array
											  				(
															"amazingEmail"=>get_post_meta($data->ID, "amazingEmail", true )
											  				),
											   "if_CallUs"=>array
											  				(
															"amazingCallUs"=>get_post_meta($data->ID, "amazingCallUs", true )
											  				),
												"if_amazingRSSFeed"=>array
											  				(
															"amazingRSSFeed"=>get_post_meta($data->ID, "amazingRSSFeedUrl", true )
											  				),
												"if_OfficeOrStoreLocation"=>array
											  				(
															"amazingStoreName"=>get_post_meta($data->ID, "amazingStoreName", true ),
															"amazingStoreAddress"=>get_post_meta($data->ID, "amazingStoreAddress", true ),
															"amazingLat"=>get_post_meta($data->ID, "amazingLat", true ),
															"amazingLong"=>get_post_meta($data->ID, "amazingLong", true ),
															"amazingLocEmail"=>get_post_meta($data->ID, "amazingLocEmail", true ),
															"amazingLocPhone"=>get_post_meta($data->ID, "amazingLocPhone", true )
											  				),
												"if_instagramHash"=>array
											  				(
															"hash_tag"=>get_post_meta($data->ID, "amazingInstagramHashtag", true )
															
											  				)
															
											  ),
						"post_onBrowseTemplate_type"=>get_post_meta($data->ID, "amazing_onBrowseTemplate", true ),
						"share_enable"=>get_post_meta($data->ID, "amazing_enableshare", true ),
						"comment_status"=>$data->comment_status,
						"permalink"=>get_permalink($data->ID),
						"comment_count"=>$data->comment_count,
						"author"=>array(
										"author_id"=>$data->post_author,
										"avatar"=>$this->wp_user_avatar_extension($data->post_author),
										"author_name"=>$user_info->display_name
										),
						"images"=>array(
									"featured_image"=>$featuredImage,
									"other_images"=>$newextraimagesarray
									 )
			
			);
			
			return $array;
			
		}
		
		
		
		public function getPostsByCategoryID($categoryID,$current_page=1,$post_per_page = 10){
		
		$array = array();
		
		$term = get_term( $categoryID, "category" );
	
		
		$args = array( 'post_type' => 'post', 'cat' => $categoryID,'posts_per_page' =>$post_per_page,'paged'=>$current_page);
		
$loop = new WP_Query( $args );

while ( $loop->have_posts() ) : $loop->the_post();


	      array_push($array,$this->jsonPostSetup($loop->post));

endwhile;
		
		
		
		
		
		
		//Count All Post
		$argss = array( 'post_type' => 'post', 'cat' => $categoryID,'posts_per_page' =>-1);
		$loops = new WP_Query( $argss );
		
		$count = 0;
		while ( $loops->have_posts() ) : $loops->the_post();

	$count++;
	    

endwhile;
		$totalPage = ceil($count/$post_per_page);
		
		return array(
					"categoryID"=>(int)$categoryID,
					"categoryName"=>$term->name,
					"categorySlug"=>$term->slug,
					"current_page"=>(int)$current_page,
					"total_page"=>(int)$totalPage,
					"post_per_page"=>(int)$post_per_page,
					"total_post"=>(int)$count,
					"posts"=>$array
					);
	}
		
		
		private function stripBBCode($text_to_search) {
			 $pattern = '|[[\/\!]*?[^\[\]]*?]|si';
 			$replace = '';
			 return preg_replace($pattern, $replace, $text_to_search);
			}
		
		
		 private function wp_user_avatar_extension($userID)
  		{
	 
			
				
				$user_avatar_id = get_user_meta( $userID, "wp_user_avatar", true );
				$get_user = get_user_by("id",$userID );
				
					$s	= wp_get_attachment_url($user_avatar_id);
					if($s == false)
					{
						$avatar	= $this->get_avatar_url(get_avatar( $userID , 100));
					}
					else
					{
						$avatar	= wp_get_attachment_url($user_avatar_id);
					}
					
				
			
			
			return $avatar;
  	}
		
		function get_avatar_url($get_avatar){
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}
		
		
		private function time_elapsed_string($ptime) {
    $etime = time() - $ptime;
    
    if ($etime < 1) {
        return '0 seconds';
    }
    
    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );
    
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's ago' : ' ago');
        }
    }
	
	}
   
	}
	
?>
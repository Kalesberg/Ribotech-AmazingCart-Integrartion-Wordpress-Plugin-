<?php
if( !class_exists( 'amazingcart_qrcodegenerator' ) ) {

	class amazingcart_qrcodegenerator{

		public function __construct() {

			/* Register the metaboxes */
			add_action( 'add_meta_boxes', array( $this, 'RegisterMetabox' ) );
			add_action('template_redirect', array($this,'amazingcart_include_template'), 1);

		}
		
		
		

	
	
	 public function amazingcart_include_template() {
        
		
        if($_GET['amazingcart']=="qrcode-api")
		{
			
			echo json_encode($this->compile($_GET['link']));
			die();
		}
		
		
		
	 }
		
		private function compile($link)
		{
			
			$postID = url_to_postid($link);
			if($postID > 0)
			{
				//Post Or Page	
				
				$postData = get_post($postID);
				
				return array("link"=>$link,"postID"=>$postID,"post_type"=>$postData->post_type);
				
			}
			else
			{
				//check for custom post
				$otherpostID = $this->bwp_url_to_postid($link);
				if($otherpostID > 0)
				{
					//is product or coupon
					
					$postData = get_post($otherpostID);
					
					if($postData->post_type == "product")
					{
						return array("link"=>$link,"postID"=>$otherpostID,"post_type"=>$postData->post_type);	
					}
					else if($postData->post_type == "page")
					{
						return array("link"=>$link,"postID"=>$otherpostID,"post_type"=>$postData->post_type);	
					}
					else if($postData->post_type == "post")
					{
						return array("link"=>$link,"postID"=>$otherpostID,"post_type"=>$postData->post_type);	
					}
					else
					{
							if (filter_var($link, FILTER_VALIDATE_URL) === FALSE) {
							return array("link"=>$link,"postID"=>$otherpostID,"post_type"=>"string");
    					    
							}
							else
							{
								return array("link"=>$link,"postID"=>$otherpostID,"post_type"=>"link");
							}
						
							
					}
				}
				else
				{
					//Unsupport Link
					if (filter_var($link, FILTER_VALIDATE_URL) === FALSE) {
							
    					  
							return array("link"=>$link,"postID"=>$otherpostID,"post_type"=>"string");
							}
							else
							{
								  return array("link"=>$link,"postID"=>$otherpostID,"post_type"=>"link");
							}
				}
			}
		}
		
		
		function bwp_url_to_postid($url)
		{
    global $wp_rewrite;
 
    $url = apply_filters('url_to_postid', $url);
 
    // First, check to see if there is a 'p=N' or 'page_id=N' to match against
    if ( preg_match('#[?&](p|page_id|attachment_id)=(\d+)#', $url, $values) )   {
        $id = absint($values[2]);
        if ( $id )
            return $id;
    }
 
    // Check to see if we are using rewrite rules
    $rewrite = $wp_rewrite->wp_rewrite_rules();
 
    // Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options
    if ( empty($rewrite) )
        return 0;
 
    // Get rid of the #anchor
    $url_split = explode('#', $url);
    $url = $url_split[0];
 
    // Get rid of URL ?query=string
    $url_split = explode('?', $url);
    $url = $url_split[0];
 
    // Add 'www.' if it is absent and should be there
    if ( false !== strpos(home_url(), '://www.') && false === strpos($url, '://www.') )
        $url = str_replace('://', '://www.', $url);
 
    // Strip 'www.' if it is present and shouldn't be
    if ( false === strpos(home_url(), '://www.') )
        $url = str_replace('://www.', '://', $url);
 
    // Strip 'index.php/' if we're not using path info permalinks
    if ( !$wp_rewrite->using_index_permalinks() )
        $url = str_replace('index.php/', '', $url);
 
    if ( false !== strpos($url, home_url()) ) {
        // Chop off http://domain.com
        $url = str_replace(home_url(), '', $url);
    } else {
        // Chop off /path/to/blog
        $home_path = parse_url(home_url());
        $home_path = isset( $home_path['path'] ) ? $home_path['path'] : '' ;
        $url = str_replace($home_path, '', $url);
    }
 
    // Trim leading and lagging slashes
    $url = trim($url, '/');
 
    $request = $url;
    // Look for matches.
    $request_match = $request;
    foreach ( (array)$rewrite as $match => $query) {
        // If the requesting file is the anchor of the match, prepend it
        // to the path info.
        if ( !empty($url) && ($url != $request) && (strpos($match, $url) === 0) )
            $request_match = $url . '/' . $request;
 
        if ( preg_match("!^$match!", $request_match, $matches) ) {
            // Got a match.
            // Trim the query of everything up to the '?'.
            $query = preg_replace("!^.+\?!", '', $query);
 
            // Substitute the substring matches into the query.
            $query = addslashes(WP_MatchesMapRegex::apply($query, $matches));
 
            // Filter out non-public query vars
            global $wp;
            parse_str($query, $query_vars);
            $query = array();
            foreach ( (array) $query_vars as $key => $value ) {
                if ( in_array($key, $wp->public_query_vars) )
                    $query[$key] = $value;
            }
 
        // Taken from class-wp.php
        foreach ( $GLOBALS['wp_post_types'] as $post_type => $t )
            if ( $t->query_var )
                $post_type_query_vars[$t->query_var] = $post_type;
 
        foreach ( $wp->public_query_vars as $wpvar ) {
            if ( isset( $wp->extra_query_vars[$wpvar] ) )
                $query[$wpvar] = $wp->extra_query_vars[$wpvar];
            elseif ( isset( $_POST[$wpvar] ) )
                $query[$wpvar] = $_POST[$wpvar];
            elseif ( isset( $_GET[$wpvar] ) )
                $query[$wpvar] = $_GET[$wpvar];
            elseif ( isset( $query_vars[$wpvar] ) )
                $query[$wpvar] = $query_vars[$wpvar];
 
            if ( !empty( $query[$wpvar] ) ) {
                if ( ! is_array( $query[$wpvar] ) ) {
                    $query[$wpvar] = (string) $query[$wpvar];
                } else {
                    foreach ( $query[$wpvar] as $vkey => $v ) {
                        if ( !is_object( $v ) ) {
                            $query[$wpvar][$vkey] = (string) $v;
                        }
                    }
                }
 
                if ( isset($post_type_query_vars[$wpvar] ) ) {
                    $query['post_type'] = $post_type_query_vars[$wpvar];
                    $query['name'] = $query[$wpvar];
                }
            }
        }
 
            // Do the query
            $query = new WP_Query($query);
            if ( !empty($query->posts) && $query->is_singular )
                return $query->post->ID;
            else
                return 0;
        }
    }
    return 0;
}
		
		
		
		public function getPostTypes() {

			/* Define the post types */
			$post_types = array(
				'page',
				'post',
				'product',
			);

			return apply_filters( 'wpaqr_post_types', $post_types );
		}

		public function RegisterMetabox() {

			$post_types = $this->getPostTypes();

			foreach( $post_types as $pt ) {
        		add_meta_box( 'DracoCartQRCodeGenerator_qrcode', __( 'QR Code', 'DracoCartQRCodeGenerator' ), array( $this, 'GenerateQrCode'), $pt, 'side', 'default' );
    		}
		}

		public function GenerateQrCode() {

			global $post;

			?><img src="https://chart.googleapis.com/chart?cht=qr&amp;chs=200x200&amp;chl=<?php echo get_permalink( $post->ID ); ?>" width="200" height="200" /><?php
		}

		
	}
}
?>
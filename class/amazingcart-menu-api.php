<?php
class amazing_cart_menu{
		
		var $termMenuName;
	var $termMenuSlug;
	
	public function __construct() {
			
		
			
			add_action('template_redirect', array($this,'template_redirect'), 1);
			
			$this->navMenuInstall("AmazingCartPushLeftMenu","amazingpushleftmenu"); // push on left menu

	}
	
	

	
	
	
	public function template_redirect() {
        
			global $wpdb;
			error_reporting(0);
        if($_GET['amazingcart']=="wp-menu-json-api")
		{
			if($_GET['type'] == "menu")
			{
			$this->termMenuSlug = $_GET['slugname'];
			echo json_encode(
				array(
					"menu"=>$this->getWpMenu($wpdb->prefix, $parentID=0)
					)
			);
			
			}
			die();
			
		}
		
		
	}
	
	
	
    public function getWpMenu($table_prefix, $parentID=0)
    {
        global $wpdb;
        
        
        
        
        $sql = "SELECT * 
FROM  `" . $table_prefix . "term_relationships` 
LEFT JOIN  `" . $table_prefix . "posts` ON " . $table_prefix . "term_relationships.object_id = " . $table_prefix . "posts.ID
WHERE " . $table_prefix . "term_relationships.term_taxonomy_id =  '" . $this->getFlipBlogIpadMenuIdTaxomy($table_prefix) . "'
ORDER BY " . $table_prefix . "posts.menu_order ASC";
        
        $arry    = array();
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
            if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_menu_item_parent") == $parentID) {
                if ($this->getSinglePost($table_prefix, "post_title", $result->object_id) !== "") {
                    $name = $this->getSinglePost($table_prefix, "post_title", $result->object_id);
                } else {
                    if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object") == "category") {
                        $name = $this->getSingleCategory($table_prefix, "name", $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object_id"));
                    } else if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object") == "page") {
                        $name = $this->getSinglePost($table_prefix, "post_title", $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object_id"));
                    }
					else if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object") == "product") {
                        $name = $this->getSinglePost($table_prefix, "post_title", $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object_id"));
                    }
                    
                }
                
                if ($this->getSinglePost($table_prefix, "post_excerpt", $result->object_id) == "") {
                    $sub = 0;
                } else {
                    $sub = $this->getSinglePost($table_prefix, "post_excerpt", $result->object_id);
                }
                
				
                $objectID = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object_id");
                
                if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_url") == "") {
                    $link = '0';
                } else {
                    $link = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_url");
                }
                
				
				 if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_thumbnail") == "") {
                    $thumb = 0;
                } else {
                    $thumb = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_thumbnail");
                }
				
				
				
				
				if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "") {
                    
					$type = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object");
                
				
				} else if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "0") {
                    $type = "custom";
                }
				
				
				
			
				
                $order = $this->getSinglePost($table_prefix, "menu_order", $result->object_id);
                
                $childCheck = $this->childCheck($table_prefix, $result->object_id);
                
                
                array_push($arry, array(
                    "name" => $name,
                    "subtitle" => $sub,
                    "type" => $type,
                    "objectID" => $objectID,
                    "parentID" => $parentID,
                    "postID" => $result->object_id,
                    "link" => $link,
                    "order" => $order,
                    "childcheck" => $childCheck,
                    "child" => $this->getWpMenu($wpdb->prefix, $result->object_id)
                ));
                
                
            } else {
            }
            
        }
        
        
       
        
      // echo json_encode(array("menu"=>$arry));
	   return $arry;
			
    }
    
    function validateFeed( $sFeedURL )
	{

	
	 if(simplexml_load_string(file_get_contents($sFeedURL))){

        return true;
    }else { return false; }
	
	
	}

    
    
    public function childCheck($table_prefix, $parentID)
    {
        global $wpdb;
        
        $sql = "SELECT * 
FROM  `" . $table_prefix . "term_relationships` 
LEFT JOIN  `" . $table_prefix . "posts` ON " . $table_prefix . "term_relationships.object_id = " . $table_prefix . "posts.ID
WHERE " . $table_prefix . "term_relationships.term_taxonomy_id =  '" . $this->getFlipBlogIpadMenuIdTaxomy($table_prefix) . "'
ORDER BY " . $table_prefix . "posts.menu_order ASC";
        
        
        
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
            if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_menu_item_parent") == $parentID) {
                $count++;
            }
            
        }
        
        
        
        
        
        
        
        return $count;
    }
    
    
    
    public function child($table_prefix, $parentID)
    {
        global $wpdb;
        
        $sql = "SELECT * 
FROM  `" . $table_prefix . "term_relationships` 
LEFT JOIN  `" . $table_prefix . "posts` ON " . $table_prefix . "term_relationships.object_id = " . $table_prefix . "posts.ID
WHERE " . $table_prefix . "term_relationships.term_taxonomy_id =  '" . $this->getFlipBlogIpadMenuIdTaxomy($table_prefix) . "'
ORDER BY " . $table_prefix . "posts.menu_order ASC";
        
        
        $arry    = array();
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
            if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_menu_item_parent") == $parentID) {
                if ($this->getSinglePost($table_prefix, "post_title", $result->object_id) !== "") {
                    $name = $this->getSinglePost($table_prefix, "post_title", $result->object_id);
                } else {
                    if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object") == "category") {
                        $name = $this->getSingleCategory($table_prefix, "name", $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object_id"));
                    } else if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object") == "page") {
                        $name = $this->getSinglePost($table_prefix, "post_title", $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object_id"));
                    }
                    
                }
                
                if ($this->getSinglePost($table_prefix, "post_excerpt", $result->object_id) == "") {
                    $sub = 0;
                } else {
                    $sub = $this->getSinglePost($table_prefix, "post_excerpt", $result->object_id);
                }
                
               	 if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_thumbnail") == "") {
                    $thumb = 0;
                } else {
                    $thumb = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_thumbnail");
                }
				
				
				
				
				if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "") {
                    
					if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object") == "category")
					{
						if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_catType") == "category")
						{
							$type = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object");
						}
						else
						{
							$type = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_catType");
						}
					}
					else
					{
					$type = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object");
					}
					
                } else if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "0") {
                    $type = "custom";
                }else if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "1") {
                    $type = "RSS";
                }else if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "2") {
                    $type = "TwitterPage";
                }else if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "3") {
                    $type = "TwitterHashTag";
                }else if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "4") {
                    $type = "PostMap";
                }else if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "5") {
                    $type = "ContactUs";
                }else if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "5") {
                    $type = "OneClickCall";
                }else if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_ptype") == "6") {
                    $type = "OneClickMail";
                }
                
                $objectID = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_object_id");
                
                if ($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_url") == "") {
                    $link = '0';
                } else {
                    $link = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_url");
                }
				
				if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_twitterpage")=="")
				{
					$twiterPage = "";
				}
				else
				{
					list($temp, $screen_name) = split("@", $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_twitterpage"));
					$twiterPage = $screen_name;
				}
				
				
				if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_oneclickcall")=="")
				{
					$oneclickcall = "";
				}
				else
				{
			
					$oneclickcall = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_oneclickcall");
				}
				
				
				if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_oneclickmail")=="")
				{
					$oneclickmail = "";
				}
				else
				{
			
					$oneclickmail = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_oneclickmail");
				}
				
				if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_companyname")=="")
				{
					$companyname = "";
				}
				else
				{
			
					$companyname = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_companyname");
				}
				
				if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_companyaddress")=="")
				{
					$companyaddress = "";
				}
				else
				{
			
					$companyaddress = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_companyaddress");
				}
				
				if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_companylatlong")=="")
				{
					$companylatlong = "";
				}
				else
				{
			
					
					
					list($companylat, $companylong) = split(",", $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_companylatlong"));
					
				}
				
				if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_companyphonenumber")=="")
				{
					$companyphonenumber = "";
				}
				else
				{
			
					$companyphonenumber = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_companyphonenumber");
				}
				
				
				if($this->getPostMeta($table_prefix, $result->object_id, "_menu_item_companyemailaddress")=="")
				{
					$companyemailaddress = "";
				}
				else
				{
			
					$companyemailaddress = $this->getPostMeta($table_prefix, $result->object_id, "_menu_item_companyemailaddress");
				}
                
                $order = $this->getSinglePost($table_prefix, "menu_order", $result->object_id);
                
                $childCheck = $this->childCheck($table_prefix, $result->object_id);
                
                array_push($arry, array(
                    "name" => $name,
					"thumb" => $thumb,
                    "subtitle" => $sub,
                    "type" => $type,
                    "objectID" => $objectID,
                    "parentID" => $parentID,
                    "postID" => $result->object_id,
                    "link" => $link,
                    "order" => $order,
                    "childcheck" => $childCheck,
                    "child" => $this->child($table_prefix, $result->object_id),
					"twitter_page"=>$twiterPage,
					"oneclickcallnumber"=>$oneclickcall,
					"oneclickmail"=>$oneclickmail,
					"contact_us"=>array("name"=>$companyname,"address"=>$companyaddress,"lat"=>$companylat,"long"=>$companylong,"phone_number"=>$companyphonenumber,"email_address"=>$companyemailaddress)
                ));
                
            }
            
        }
        
        
        
        
        
        
        
        return $arry;
    }
    
    
    private function getSingleCategory($table_prefix, $attribute, $term_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM `" . $table_prefix . "terms` WHERE term_id='" . $term_id . "'";
        
        
        
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
            $attr = $result->$attribute;
        }
        
        
        return $attr;
    }
    
    
    public function getFlipBlogIpadMenuIdTaxomy($table_prefix)
    {
        global $wpdb;
        $sql = "SELECT * FROM `" . $table_prefix . "term_taxonomy` WHERE term_id='" . $this->getFlipBlogIpadMenuId($table_prefix) . "'";
        
        
        
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
            $term_taxonomy_id = $result->term_taxonomy_id;
        }
        
        
        return $term_taxonomy_id;
    }
    
    public function getFlipBlogIpadMenuId($table_prefix)
    {
        global $wpdb;
        $sql = "SELECT * FROM `" . $table_prefix . "terms` WHERE slug='".$this->termMenuSlug."'";
        
        
        
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
            $termID = $result->term_id;
        }
        
        
        return $termID;
    }
    
    private function getSinglePost($table_prefix, $attribute, $post_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM `" . $table_prefix . "posts` WHERE ID='" . $post_id . "'";
        
        
        
        
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
            $getmeta = $result->$attribute;
        }
        
        
        return $getmeta;
    }
    
    
    
    
    
    private function getPostMeta($table_prefix, $post_id, $meta_key)
    {
        global $wpdb;
        
        
        $sql = "SELECT * FROM `" . $table_prefix . "postmeta` WHERE post_id='" . $post_id . "' AND meta_key='" . $meta_key . "'";
        
        
        
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
            $getmeta = $result->meta_value;
        }
        
        return $getmeta;
        
    }
    
	public function getOptionName($table_prefix,$optionName)
		{
			global $wpdb;
			$sql = "SELECT * FROM `".$table_prefix."options` WHERE option_name='".$optionName."'";
			 $results = $wpdb->get_results($sql);
			
			 foreach ($results as $result) {
            	$option_value = $result->option_value;
       		 }
        
       		 return $option_value;
		}
	
	public function getParentCatToExcludeList($table_prefix,$parent)
		{
			global $wpdb;
			$sql = "SELECT * FROM `".$table_prefix."term_taxonomy` WHERE taxonomy='category' AND parent='".$parent."'";
			$results = $wpdb->get_results($sql);
			
			$count = 0;
			
			
			
			$ars = array();
	
	$excludeCategories = $this->getOptionName($table_prefix,"excludeCategoriesScrollBlog"); 
	$tags = explode(',',$excludeCategories);
	
	
	foreach($tags as $key) {

    array_push($ars, $key);

	}
	
	
	
			foreach ($results as $result) {
            	if (in_array($result->term_id, $ars)) {
   						 ?>
                         <div style="margin-bottom:10px; background-color:#666; padding:10px;">
                         <div style="color:#FFF;"><?php echo $this->getSingleCategory($table_prefix,"name",$result->term_id); ?> <a href='../../draco/class/?page=ScrollBlogDashboard&amp;id=<?php echo $result->term_id; ?>' style="color:red;" >( Remove )</a></div>
                         </div>
                         <?php
						
					}
       		 }
	
			
		}
	
	
	public function getParentCatToExcludeCount($table_prefix,$parent)
		{
			global $wpdb; 
			$sql = "SELECT * FROM `".$table_prefix."term_taxonomy` WHERE taxonomy='category' AND parent='".$parent."'";
			$results = $wpdb->get_results($sql);
			
			$count = 0;
			
			
			
			$ars = array();
	
			$excludeCategories = $this->getOptionName($table_prefix,"excludeCategoriesScrollBlog"); 
			$tags = explode(',',$excludeCategories);
	
	
			foreach($tags as $key) {

    		array_push($ars, $key);

			}
	
	
			 foreach ($results as $result) {
            	if (!in_array($result->term_id, $ars)) {
   						
						 $count++;
						
					}
        		}
	
			
			 
			 return $count;
		}
	
	
	
	
	public function getParentCatToExcludeListCount($table_prefix,$parent)
		{
			global $wpdb; 
			$sql = "SELECT * FROM `".$table_prefix."term_taxonomy` WHERE taxonomy='category' AND parent='".$parent."'";
			$results = $wpdb->get_results($sql);
			
			$count = 0;
			
			
			
			$ars = array();
	
			$excludeCategories = $this->getOptionName($table_prefix,"excludeCategoriesScrollBlog"); 
			$tags = explode(',',$excludeCategories);
	
	
			foreach($tags as $key) {

    		array_push($ars, $key);

			}
	
	
			 foreach ($results as $result) {
            	if (in_array($result->term_id, $ars)) {
   						
						 $count++;
						
					}
        		}
	
			
			 
			 return $count;
		}
	
	
	
	public function getParentCatToExclude($table_prefix,$parent)
		{
			global $wpdb; 
			$sql = "SELECT * FROM `".$table_prefix."term_taxonomy` WHERE taxonomy='category' AND parent='".$parent."'";
			$results = $wpdb->get_results($sql);
			
			$count = 0;
			
			
			$ars = array();
	
	$excludeCategories = $this->getOptionName($table_prefix,"excludeCategoriesScrollBlog"); 
	$tags = explode(',',$excludeCategories);
	
	
	foreach($tags as $key) {

    array_push($ars, $key);

	}
	
	
	foreach ($results as $result) {
            	if (!in_array($result->term_id, $ars)) {
   						 ?>
                         <option value="<?php echo $result->term_id; ?>"><?php echo $this->getSingleCategory($table_prefix,"name",$result->term_id); ?></option>
                         <?php
					}
        		}
			
			
		}
		
		
		
		/*
		=======================
		ACTIVATION/INSTALLATION
		=======================
		*/
		
		
		private function navMenuInstall($name,$slug)
		{
    	global $wpdb;
   	 
			if ($this->checkMenuIfAvailabel($slug) == false)
			{
				$data['name'] = $name;
				
				$data['slug'] = $slug;
				
				$data['term_group'] = "0";
				
				
				$qry = "INSERT INTO " . $wpdb->prefix . "terms (name, slug, term_group) values('" . $data['name'] . "','" . $data['slug'] . "','" . $data['term_group'] . "')";
				
				$wpdb->query($qry);
				
				$this->addTermMenu($name);
				
			}
    
	}

   
	 
	 public function checkMenuIfAvailabel($term)
	{
		global $wpdb;
		
		$qry = "SELECT * FROM `" . $wpdb->prefix . "terms` WHERE slug='".$term."'";
		$results = $wpdb->get_results($qry);
		$count = 0;
		
		foreach ($results as $result)
		{
			$count++;
		}
		
		if ($count > 0)
		{
			return true;
			
		}
		
		else
		{
			return false;
			
		}
		
		
	}
	
	private function addTermMenu($name)
		{
			global $wpdb;
			
			
			
			$data['term_id'] = $this->getMenuID($name);
			
			$data['taxonomy'] = "nav_menu";
			
			
			
			
			
			$qry = "INSERT INTO " . $wpdb->prefix . "term_taxonomy (term_id, taxonomy) values('" . $data['term_id'] . "','" . $data['taxonomy'] . "')";
			
			$wpdb->query($qry);
			
			
			
		}
	
	
	 private function getMenuID($name)
	 {
		global $wpdb;
		$qry = "SELECT * FROM `" .$wpdb->prefix . "terms` WHERE name='".$name."'";
		$results = $wpdb->get_results($qry);
		
		foreach ($results as $result)
		{
			$ids = $result->term_id;
			
		}
    
    
    
   		 return $ids;
    
	}
		
   
	}
	
?>
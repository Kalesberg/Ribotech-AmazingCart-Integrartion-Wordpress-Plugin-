<?php
class AmazingCart_category_appearance
{
		
		
	public function init()
	{
		
		add_action('template_redirect', array($this,'template_redirect'), 1);
	}
	
		
	 public function template_redirect() {
        
		error_reporting(0);
        if($_GET['amazingcart']=="adminAjaxSave")
		{
			
			if($_GET['type'] == "category_appearance_add_category")
			{
				
				$this->addNewCategory($_POST['categoryID']);
				die();
			}
			else if($_GET['type'] == "category_appearance_save_order")
			{
				
				$this->saveOrder($_POST['serialize']);
				die();
			}
			else if($_GET['type'] == "category_appearance_delete_item")
			{
				
				$this->deleteItem($_POST['id']);
				die();
			}
			else if($_GET['type'] == "category_appearance_choose_option")
			{
				
				update_option( 'amazingcart_categories_option', $_POST['option']);
				die();
			}
			else if($_GET['type'] == "category_appearance_choose_thumb")
			{
				
				update_option( 'amazingcart_show_category_thumb', $_POST['showThumb']);
				die();
			}
		
		
		
		}
		else if($_GET['amazingcart']=="getAjax")
		{
			if($_GET['type'] == "get_all_custom_categories")
			{
				$this->get_all_custom_categories();
				die();
			}
		}
		else if($_GET['amazingcart']=="custom-category-appearance-api")
		{
			echo json_encode($this->get_categories_api_array());
			die();
		}
		
		
	 }
	 
	 function get_categories_api_array()
	 {
		  global $wpdb;
        $sql = "SELECT * FROM `" . $wpdb->prefix . "amazingcart_home_appearance_product_categories` ORDER BY ordering ASC";
        
        $se = array();
        
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
			$category = get_term($result->category_id,'product_cat');
			
			 $thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true ); 
    
    		$image = wp_get_attachment_url( $thumbnail_id );
			
			if($image == false)
			{
				$imgurl = "0";	
			}
			else
			{
				$imgurl = $image;
			}
			
			array_push($se,array
							(
							"term_id"=>$category->term_id,
							"thumb"=>$imgurl,
							"name"=>$category->name,
							"slug"=>$category->slug,
							"category_parent"=>$category->parent,
							"post_count"=>$category->count,
							"children"=>$this->get_product_categories_array($category->term_id)
							));
			
		}
		
		return $se;
		 
	 }
	 
	 public function get_product_categories_array($parent = 0)
 	 {
	  
	  
	  
	  global $wp_query;
    // get the query object
    $cat = $wp_query->get_queried_object();
    // get the thumbnail id user the term_id
   
	
	
	 	 $taxonomies = array( 
    					'product_cat'
						);
	  
		$args = array(
					  'orderby' => 'name',
					  'parent'         => $parent
					  );
  		$se = array();
		$categories = get_terms( $taxonomies, $args );
		foreach ( $categories as $category ) {
			
			 $thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true ); 
    
    $image = wp_get_attachment_url( $thumbnail_id );
			
			if($image == false)
			{
				$imgurl = "0";	
			}
			else
			{
				$imgurl = $image;
			}
			
			array_push($se,array
							(
							"term_id"=>$category->term_id,
							"thumb"=>$imgurl,
							"name"=>$category->name,
							"slug"=>$category->slug,
							"category_parent"=>$category->parent,
							"post_count"=>$category->count,
							"children"=>$this->get_product_categories_array($category->term_id)
							));
		}  
		
		return $se;
  	}
	 
	 function addNewCategory($categoryID)
		{
			 global $wpdb;
			 $qry = "INSERT INTO `" . $wpdb->prefix . "amazingcart_home_appearance_product_categories` (category_id)
VALUES ('".$categoryID."')";
 					 $wpdb->query($qry);
		}
	 
	 function deleteItem($id)
		{
			 global $wpdb;
			 $qry = "DELETE FROM `" . $wpdb->prefix . "amazingcart_home_appearance_product_categories`
WHERE no='".$id."';";
 					 $wpdb->query($qry);
		}
	 
	 function saveOrder($serialize)
		{
			
			 global $wpdb;
			 
			 $data = explode("&", $serialize);

			$parent = 0;
        	$count = 0;
 				foreach ($data as $k => $v) {
                
               
			   $data = explode("=", $v);
			   
			   preg_match_all('#\[(.+)\]#iUs', $data[0], $matches);
			   
			   
			   
			
			 
			 foreach($matches[1] as $value)
			 {
				$va = 	$value; 
			 }
			 
			 
					if($v == "null")
					{
						$v = 0;
					}
			
				
					 $qry = "UPDATE `" . $wpdb->prefix . "amazingcart_home_appearance_product_categories`
SET ordering='".$k."'
WHERE no='".$va."';";
 					 $wpdb->query($qry);
					 
					 $count++;
              
               		 
        		}
				
				echo  $serialize;
		}
	 
	 
	public function get_all_custom_categories()
	{
		 global $wpdb;
        $sql = "SELECT * FROM `" . $wpdb->prefix . "amazingcart_home_appearance_product_categories` ORDER BY ordering ASC";
        
        
        
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
			$cat = get_term($result->category_id,'product_cat');
			?>
             <li id="category_no_<?php echo $result->no; ?>">
             	<div>
					<div style="float:left; margin-right:10px;"><b><?php echo $cat->name; ?></b></div>
                	<div style="float:left; font-size:9px; "><a href="javascript:deleteItem('<?php echo $result->no; ?>')" style="color:#FFF;">Delete</a></div>
                	<div style=" clear:both;"></div>
                </div>
             </li>
			<?php
		}
		
	}
		
		
		public function get_product_categories()
 	 {
	  
	  
	  
	  global $wp_query;
    // get the query object
    $cat = $wp_query->get_queried_object();
    // get the thumbnail id user the term_id
   
	
	
	 	 $taxonomies = array( 
    					'product_cat'
						);
	  
		$args = array(
					  'orderby' => 'name'
					  );
  		$se = array();
		 $count = $counter;
		$categories = get_terms( $taxonomies, $args );
		foreach ( $categories as $category ) {
			
			 $thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true ); 
    
    $image = wp_get_attachment_url( $thumbnail_id );
			
			if($image == false)
			{
				$imgurl = "0";	
			}
			else
			{
				$imgurl = $image;
			}
			
			
							
							?>
                            <option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
                          
                            <?php
							
						
		}  
		
		
  	}
	
	
	
	 
	 
}
	
	
	
	
?>
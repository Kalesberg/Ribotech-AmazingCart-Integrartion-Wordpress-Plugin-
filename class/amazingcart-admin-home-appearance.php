<?php
class AmazingCart_homeappearances
{
		
		
	
	public function init()
	{
		
		add_action('template_redirect', array($this,'template_redirect'), 1);
	}
		
		 public function template_redirect() {
        
		error_reporting(0);
        if($_GET['amazingcart']=="adminAjaxSave")
		{
			
			if($_GET['type'] == "home_appearance_menu_save_all")
			{
				
				$this->saveAllAdmin($_POST['dataser']);
				die();
				
			}
			else if($_GET['type'] == "home_appearance_menu_save_product_all")
			{
				
				$this->saveProductAdmin($_POST['dataser']);
				die();
				
			}
			else if($_GET['type'] == "home_appearance_menu_save_title")
			{
				
				$this->saveTitle($_POST['id'],$_POST['newTitle']);
				die();
				
			}
			else if($_GET['type'] == "home_appearance_menu_add_new")
			{
				
				$this->addNew($_POST['newTitle']);
				die();
				
			}
			else if($_GET['type'] == "home_appearance_menu_delete")
			{
				
				$this->deleteItem($_POST['id']);
				die();
				
			}
			else if($_GET['type'] == "home_appearance_menu_product_delete")
			{
				
				$this->deleteProduct($_POST['id']);
				die();
				
			}
			else if($_GET['type'] == "home_appearance_menu_update_type")
			{
				
				$this->updateType($_POST['id'],$_POST['type']);
				die();
			}
			else if($_GET['type'] == "home_appearance_menu_update_link")
			{
				
				$this->saveLink($_POST['id'],$_POST['linkValue']);
				die();
			}
			else if($_GET['type'] == "home_appearance_menu_add_product")
			{
				
				$this->addNewProductIntoItem($_POST['itemID'],$_POST['productID']);
				die();
			}
			else if($_GET['type'] == "home_appearance_save_option")
			{
				
				update_option( 'amazingcart_home_appearance_choose', $_POST['optionValue'] );
				die();
			}
			else if($_GET['type'] == "home_appearance_save_html_link")
			{
				
				update_option( 'amazingcart_home_appearance_use_html_link', $_POST['htmllink'] );
				die();
			}
			
			
		}
		else if($_GET['amazingcart']=="getAjax")
		{
			
			if($_GET['type'] == "home_appearance_menu")
			{
				
				$this->getAllInAdmin();
				die();
				
			}
			else if($_GET['type'] == "home_appearance_menu_product")
			{
				
				$this->getProductByItemID($_GET['itemID']);
				die();
				
			}
			
			
			
		}
		else if($_GET['amazingcart']=="home-appearance-api")
		{
			echo json_encode($this->getAllItemsArray());
				die();
			
		}
		
		
	 }
		
		
		
		function getAllItemsArray()
		{
			global $wpdb;
        	$sql = "SELECT * FROM `" . $wpdb->prefix . "amazingcart_home_appearance` ORDER BY ordering ASC";
			 $count = 0;
        	$array = array();
			$results = $wpdb->get_results($sql);
        	foreach ($results as $result) {
				
				if($result->type == "usepage")
				{
					$post = get_page($result->link); 
					
					array_push($array,array(
									"title"=>$result->title,
									"type"=>$result->type,
									"order"=>$result->ordering,
									"pageID"=>$result->link,
									"content"=>htmlspecialchars(do_shortcode($post->post_content)),
									"data"=>$this->getAllProductItemsArray($result->no)
									));
					
				}
				else
				{
				
				array_push($array,array(
									"title"=>$result->title,
									"type"=>$result->type,
									"order"=>$result->ordering,
									"link"=>$result->link,
									"data"=>$this->getAllProductItemsArray($result->no)
									));
									
				}
			
			}
			
			if(get_option('amazingcart_home_appearance_choose') == "usewppage")
			{
				$post = get_page(get_option('amazingcart_home_appearance_use_html_link'));
			return array(
						"home_menu_type"=>get_option('amazingcart_home_appearance_choose'),
						"html_link"=>get_option('amazingcart_home_appearance_use_html_link'),
						"content"=>htmlspecialchars(do_shortcode($post->post_content)),
						"items"=>$array
						);
			}
			else
			{
				return array(
						"home_menu_type"=>get_option('amazingcart_home_appearance_choose'),
						"html_link"=>get_option('amazingcart_home_appearance_use_html_link'),
						"items"=>$array
						);
			}
		}
		
		
		
		function getAllProductItemsArray($id)
		{
			
			 global $wpdb;
			$sql = "SELECT * FROM `" . $wpdb->prefix . "amazingcart_home_appearance_product` WHERE id='".$id."' ORDER BY ordering ASC";
            $results = $wpdb->get_results($sql);
			$array = array();
				
				foreach ($results as $result) {
				
				$post = get_post($result->product_id);
				
				$amazingcart_woo_json_api = new amazingcart_woo_json_api;
				
				array_push($array,$amazingcart_woo_json_api->jsonSetup($post));
			
				}
				
				return $array;
			
		}
		
		function getAllInAdmin()
		{
			
			 global $wpdb;
        $sql = "SELECT * FROM `" . $wpdb->prefix . "amazingcart_home_appearance` ORDER BY ordering ASC";
        
        
        
        $results = $wpdb->get_results($sql);
        
        $count = 0;
        
        foreach ($results as $result) {
           ?>
           
            <li id="sortable_li_<?php echo $result->no; ?>">
            <div>
         	<div style="float:left; width:20%; margin-top:6px;" >
            <div id="sortable_title_<?php echo $result->no; ?>"  style="cursor:text;">
            	<strong style="font-size:14px;" id="sortable_title_strong_<?php echo $result->no; ?>" onClick="titleChange(<?php echo $result->no; ?>)"><?php echo $result->title; ?></strong>  
                <a href="javascript:deleteItem(<?php echo $result->no; ?>)" style="color:#FFF; font-size:9px; font-style:italic;">Delete</a></div>
            <div id="sortable_edit_<?php echo $result->no; ?>" style="display:none;"><input name="sortable_edit_title_<?php echo $result->no; ?>" id="sortable_edit_title_<?php echo $result->no; ?>" value="<?php echo $result->title; ?>" /> <a href="javascript:titleChangeDone(<?php echo $result->no; ?>)" style="color:#FFF;">Done</a></div>
            </div>
            <div style="float:left; width:79%; text-align:right;">
            	<select id="sortable_select_<?php echo $result->no; ?>" onChange="onChangeSelect('<?php echo $result->no; ?>')">
    				
                    <?php foreach($this->getAllSortableType() as $value){ 
					if($value['value'] == $result->type)
					{
					?>
                    <option value="<?php echo $value['value']; ?>" selected><?php echo $value['label']; ?></option>
                    <?php 
					}
					else
					{
					?>
                    <option value="<?php echo $value['value']; ?>"><?php echo $value['label']; ?></option>
                    <?php	
					}
					} ?>
            		
    			</select>
                <?php if($result->type == "customitems"){ ?>
                <button class="button-secondary" id="add_item_<?php echo $result->no; ?>" onClick="addProductView('<?php echo $result->no; ?>')" >Add Item</button>
                <?php }else{ ?>
                <button class="button-secondary" id="add_item_<?php echo $result->no; ?>" style="display:none;" onClick="addProductView('<?php echo $result->no; ?>')">Add Item</button>
                <?php } ?>
                
                <?php if($result->type == "usehtml" ){ ?>
                
                <input name="sortable_html_link_<?php echo $result->no; ?>" placeholder="http://" id="sortable_html_link_<?php echo $result->no; ?>"value="<?php echo $result->link; ?>" />
                <button class="button-secondary" id="save_link_item_<?php echo $result->no; ?>" onClick="saveLinkItem(<?php echo $result->no; ?>)"  >Save Link</button>
                
                
                
                
                <?php }else{ ?>
                
                <input name="sortable_html_link_<?php echo $result->no; ?>"  placeholder="http://" id="sortable_html_link_<?php echo $result->no; ?>" style=" display:none;" value="<?php echo $result->link; ?>" />
                <button class="button-secondary" id="save_link_item_<?php echo $result->no; ?>" onClick="saveLinkItem(<?php echo $result->no; ?>)" style="display:none;" >Save Link</button>
                <?php } ?>
                
                
                
                
                <?php if($result->type == "usepage" ){ ?>
                
               
                
                <select id="select_page_<?php echo $result->no; ?>" onchange="savePageItem(<?php echo $result->no; ?>)">
                <?php 
				 $pages = get_pages(); 
  				foreach ( $pages as $page ) {
					
					if($page->ID == $result->link)
					{
					?>
                    
                    <option value="<?php echo $page->ID; ?>" selected="selected"><?php echo $page->post_title; ?></option>
                    <?php
					}
					else
					{
						?>
                        <option value="<?php echo $page->ID; ?>" ><?php echo $page->post_title; ?></option>
                        <?php
					}
  					
  				}
				?>
                	
                </select>
                
                
                <?php }else{ ?>
                
               
                <select style="display:none;" id="select_page_<?php echo $result->no; ?>" onchange="savePageItem(<?php echo $result->no; ?>)">
                <?php 
				 $pages = get_pages(); 
  				foreach ( $pages as $page ) {
					
					if($page->ID == $result->link)
					{
					?>
                    
                    <option value="<?php echo $page->ID; ?>" selected="selected"><?php echo $page->post_title; ?></option>
                    <?php
					
					}
					else
					{
						
						?>
                    
                    <option value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
                    <?php
					}
  					
  				}
				?>
                	
                </select>
                <?php } ?>
                
                
             </div>
             <div style="clear:both;"></div>
             
             </div>
        </li>
           
           <?php
        }
			
		}
		
		
		function getProductByItemID($id)
		{
			 global $wpdb;
			$sql = "SELECT * FROM `" . $wpdb->prefix . "amazingcart_home_appearance_product` WHERE id='".$id."' ORDER BY ordering ASC";
            $results = $wpdb->get_results($sql);
			
			foreach($results as $result)
			{
				
				?>
                
                <li id="sortable_li_<?php echo $result->no; ?>">
            		<div>
                    	<div style="float:left; width:70%;">
                    		<div style="float:left; margin-right:10px;"><img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($result->product_id, 'thumbnail')); ?>" width="35" height="35" /></div>
            				<div style="float:left; margin-top:8px;"><b><?php echo get_the_title($result->product_id); ?></b></div>
                        	<div style="clear:both;"></div>
                         </div>
                         <div style="float:left; width:29%; margin-top:8px; text-align:right;">
                         <a href="javascript:deleteProduct('<?php echo $result->no; ?>')" style="color:#FFF;">Delete</a>
                         </div>
                         <div style="clear:both;"></div>
            		</div>
       			 </li>
                <?php
			}
			
			
		}
		
		
		
		function getAllProducts()
		{
			
			$args = array( 
				'post_type' => 'product',
				'posts_per_page' =>-1,
				'meta_query' => array(
									array(
									'key' => '_visibility',
									'value' => array('visible','catalog')
									)
									));
		
			$loop = new WP_Query( $args );

			while ( $loop->have_posts() ) : $loop->the_post();

				?>
                <option value="<?php echo $loop->post->ID; ?>"><?php echo $loop->post->post_title; ?></option>
                <?php
		 
			endwhile;	
			
		}
		
		
		function getItemById($id)
		{
			 global $wpdb;
			$sql = "SELECT * FROM `" . $wpdb->prefix . "amazingcart_home_appearance` WHERE no='".$id."'";
            $results = $wpdb->get_results($sql);
			
			foreach($results as $result)
			{
				$return = $result;
			}
			
			return $return;
		}
		
		function getAllSortableType()
		{
		$sortable = array(
						array("label"=>"Featured Items","value"=>"featureditems"),
						array("label"=>"New Items","value"=>"newitems"),
						array("label"=>"Random Items","value"=>"randomitems"),
						array("label"=>"Custom Items","value"=>"customitems"),
						array("label"=>"Use HTML","value"=>"usehtml"),
						array("label"=>"Use Page","value"=>"usepage")
						);
						
		return $sortable;
		
		}
		
		function addNew($title)
		{
			 global $wpdb;
			 $qry = "INSERT INTO `" . $wpdb->prefix . "amazingcart_home_appearance` (title,type,ordering)
VALUES ('".$title."','featureditems', '0')";
 					 $wpdb->query($qry);
		}
		
		
		function addNewProductIntoItem($itemID,$productID)
		{
			 global $wpdb;
			 $qry = "INSERT INTO `" . $wpdb->prefix . "amazingcart_home_appearance_product` (id,product_id,ordering)
VALUES ('".$itemID."','".$productID."', '0')";
 					 $wpdb->query($qry);
		}
		
		
		function saveTitle($id,$title)
		{
			 global $wpdb;
			 $qry = "UPDATE `" . $wpdb->prefix . "amazingcart_home_appearance`
SET title='".$title."'
WHERE no='".$id."';";
 					 $wpdb->query($qry);
		}
		
		
		function saveLink($id,$link)
		{
			 global $wpdb;
			 $qry = "UPDATE `" . $wpdb->prefix . "amazingcart_home_appearance`
SET link='".$link."'
WHERE no='".$id."';";
 					 $wpdb->query($qry);
		}
		
		function updateType($id,$type)
		{
			 global $wpdb;
			 $qry = "UPDATE `" . $wpdb->prefix . "amazingcart_home_appearance`
SET type='".$type."'
WHERE no='".$id."';";
 					 $wpdb->query($qry);
		}
		
		
		function deleteItem($id)
		{
			 global $wpdb;
			 $qry = "DELETE FROM `" . $wpdb->prefix . "amazingcart_home_appearance`
WHERE no='".$id."';";
 					 $wpdb->query($qry);
		}
		
		function deleteProduct($id)
		{
			 global $wpdb;
			 $qry = "DELETE FROM `" . $wpdb->prefix . "amazingcart_home_appearance_product`
WHERE no='".$id."';";
 					 $wpdb->query($qry);
		}
		
		
		function saveAllAdmin($serialize)
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
			
				
					 $qry = "UPDATE `" . $wpdb->prefix . "amazingcart_home_appearance`
SET ordering='".$k."'
WHERE no='".$va."';";
 					 $wpdb->query($qry);
					 
					 $count++;
              
               		 
        		}
				
				echo  $serialize;
		}
		
		
		function saveProductAdmin($serialize)
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
			
				
					 $qry = "UPDATE `" . $wpdb->prefix . "amazingcart_home_appearance_product`
SET ordering='".$k."'
WHERE no='".$va."';";
 					 $wpdb->query($qry);
					 
					 $count++;
              
               		 
        		}
				
				echo  $serialize;
		}
		

}
	
	
	
	
?>
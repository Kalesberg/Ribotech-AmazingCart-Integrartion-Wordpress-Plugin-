<?php
$appearances = new AmazingCart_homeappearances();
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script src="<?php echo plugins_url( 'AmazingCart/js/jquery.mjs.nestedSortable.js')."" ?>"></script>
<script src="<?php echo plugins_url( 'AmazingCart/js/noty/jquery.noty.js')."" ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/layouts/top.js')."" ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/layouts/topLeft.js')."" ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/layouts/topRight.js')."" ?>"></script>
<!-- You can add more layouts if you want -->

<script type="text/javascript" src="<?php echo plugins_url( 'AmazingCart/js/noty/themes/default.js')."" ?>"></script>


<style>

#sortableDiv ol{
	
	list-style-type: none;
	margin-left:0px;
}

#sortableDiv ol li{
	padding:10px;
	color:#FFF;
	background-color:#f22000;
	width:97%;
	cursor:pointer;
	
	
}

</style>

<div style="padding:10px;">
<?php if($_GET['childpage'] == "addProduct"){?>
<div style="width:59%; margin-bottom:20px; text-align:left; display:none;">
    Style : 
    	<select id="chooseOpton" onChange="onChangeMain()">
        <?php if(get_option('amazingcart_home_appearance_choose') == "usenative"){ ?>
    		<option value="usenative" selected>Use Native</option>
            <option value="usehtml">Use Html</option>
            <option value="usewppage">Use Page</option>
            <?php }else if(get_option('amazingcart_home_appearance_choose') == "usehtml"){ ?>
            <option value="usenative" >Use Native</option>
            <option value="usehtml" selected>Use Html</option>
            <option value="usewppage">Use Page</option>
          
            <?php }else { ?>
            <option value="usenative" >Use Native</option>
            <option value="usehtml" >Use Html</option>
            <option value="usewppage" selected>Use Page</option>
            <?php } ?>
    	</select>
         <button class="button-primary" onClick="saveOption()" >Choose</button>
        </div>
<?php }else{ ?>
	<div style="width:59%; margin-bottom:20px; text-align:left; ">
    Style : 
    	<select id="chooseOpton" onChange="onChangeMain()">
        <?php if(get_option('amazingcart_home_appearance_choose') == "usenative"){ ?>
    		<option value="usenative" selected>Use Native</option>
            <option value="usehtml">Use Html</option>
            <option value="usewppage">Use Page</option>
          <?php }else if(get_option('amazingcart_home_appearance_choose') == "usehtml"){ ?>
            <option value="usenative" >Use Native</option>
            <option value="usehtml" selected>Use Html</option>
            <option value="usewppage">Use Page</option>
          
            <?php }else { ?>
            <option value="usenative" >Use Native</option>
            <option value="usehtml" >Use Html</option>
            <option value="usewppage" selected>Use Page</option>
            <?php } ?>
    	</select>
         <button class="button-primary" onClick="saveOption()" >Choose</button>
        </div>
 <?php } ?>   
    <div id="usenative" style="display:none;">
    
    <?php if($_GET['childpage'] == "addProduct"){ ?>
    <div><a href="?page=AmazingMainPageAppearance">< BACK</a> <b><?php echo $appearances->getItemById($_GET['id'])->title; ?> / Add Product</b></div>
    <div style="margin-top:20px;">
    	<select id="addProduct">
        	<option value="0">Please Choose your product</option>
    		<?php $appearances->getAllProducts(); ?>
    	</select>
        <button class="button-primary" onClick="addNewProduct()">Add product into <?php echo $appearances->getItemById($_GET['id'])->title; ?></button>
    </div>
 		<div id="sortableDiv">
    	<ol class="sortable">
   		  <?php 
		  	$appearances->getProductByItemID($_GET['id']);
		  ?>
    	 
		</ol>
    
    	<div><button class="button-primary" onClick="saveProductAll()" >Save Order</button></div>
    
    </div>
    
    
    <?php }else{ ?>
    
    <div>Title : <input type="text" name="new_title" id="new_title" > <button class="button-primary" onClick="addNewMenu()" >Add New Menu</button></div>
	<div id="sortableDiv">
    	<ol class="sortable">
   		  <?php 
		  	$appearances->getAllInAdmin();
		  ?>
    	 
		</ol>
    
    	<div><button class="button-primary" onClick="saveAll()" >Save Order</button></div>
    
    </div>
	<?php } ?>
    
    </div>
    
     <div id="usehtml" style="display:none;">
     
     	<div>You can use HTML and design your front page. Its support Bootstrap and Jquery.</div>
        
        <div style="margin-top:10px;">Your HTML URL : e.g www.yourdomain.com/yourhtml.html</div>
        <div><input type="text" name="htmlPage" id="htmlPage" size="45" placeholder="http://" value="<?php echo get_option('amazingcart_home_appearance_use_html_link'); ?>" /> <button class="button-primary" onClick="saveHtmlLink()" >Save Link</button></div>
      
      <div style="margin-top:10px;">Example : Please upload HTML file provided and get the link and paste it here.</div>
     	
     </div>
     
     
       <div id="usewppage" style="display:none;">
     
     	<div>You can create your home tab by choosing your wordpress page.</div>
        
        <div style="margin-top:10px;">
        	
             <select id="select_page_main" >
                <?php 
				 $pages = get_pages(); 
  				foreach ( $pages as $page ) {
					
					if($page->ID ==  get_option('amazingcart_home_appearance_use_html_link'))
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
                
                <button class="button-primary" onClick="saveWpPageMain()" >Save</button>
            
        </div>
     	
     </div>
    
</div>

<script>

$(document).ready(function(){

        $('.sortable').nestedSortable({
            handle: 'div',
            items: 'li',
			maxLevels: 1,
            toleranceElement: '> div'
        });


			onChangeMain();

    });
	
	
	function saveWpPageMain()
	{
		var htmlli = $("#select_page_main").val();
		
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_save_html_link",{htmllink:htmlli},function(result){
	   
	   
		 processing.close();
		
		});
		
	}
	
	function saveHtmlLink()
	{
		var htmlli = $("#htmlPage").val();
		
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_save_html_link",{htmllink:htmlli},function(result){
	   
	   
		 processing.close();
		
		});
		
	}
	
	
	function onChangeMain()
	{
		
		var option = $("#chooseOpton").val(); 
			
			if(option == "usenative")
			{
				
				$('#usenative').show();
				$('#usehtml').hide();
				$('#usewppage').hide();
			}
			else if(option == "usehtml")
			{
				$('#usenative').hide();
				$('#usehtml').show();
				$('#usewppage').hide();
			}
			else if(option == "usewppage")
			{
				$('#usenative').hide();
				$('#usehtml').hide();
				$('#usewppage').show();
				
			}
	}
	
	function saveOption(){
		
		var option = $("#chooseOpton").val(); 
		
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_save_option",{optionValue:option},function(result){
	   
	   
		 processing.close();
		
		});
		
		
	}
	
	
	function addNewProduct()
	{
		
		var produID = $("#addProduct").val(); 
		
		<?php if(!$_GET['id']){ ?>
		var iID = 0;
		<?php }else{ ?>
		var iID = <?php echo $_GET['id']; ?>;
		<?php } ?>
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_menu_add_product",{itemID:iID,productID:produID},function(result){
	   
	   
		
		$('.sortable').load('<?php echo bloginfo('url'); ?>/?amazingcart=getAjax&type=home_appearance_menu_product&itemID=<?php echo $_GET['id']; ?>', function() {
 		 
		  processing.close();
		
		 
		});
		
		
		});
	}
	
	
	function addNewMenu()
	{
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
			title = $("#new_title").val();
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_menu_add_new",{newTitle:title},function(result){
	   
	   
		
		$('.sortable').load('<?php echo bloginfo('url'); ?>/?amazingcart=getAjax&type=home_appearance_menu', function() {
 		 
		  processing.close();
		  $("#new_title").val('');
		 
		});
		
		
		});
			
	}
	
	
	
	function titleChange(no)
	{
		
		$("#sortable_title_"+no+"").hide();
		$("#sortable_edit_"+no+"").show();
	}
	
	function titleChangeDone(no)
	{
		
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		title = $("#sortable_edit_title_"+no+"").val();
		$("#sortable_title_strong_"+no+"").html(""+title+"");
		$("#sortable_title_"+no+"").show();
		$("#sortable_edit_"+no+"").hide();
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_menu_save_title",{id:no,newTitle:title},function(result){
	   
	    processing.close();
		
		});
		
	}
	
	function savePageItem(no)
	{
		
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		linkV = $("#select_page_"+no+"").val();
		
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_menu_update_link",{id:no,linkValue:linkV},function(result){
	   
	    processing.close();
		
		});
		
	}
	
	
	function saveLinkItem(no)
	{
		
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		linkV = $("#sortable_html_link_"+no+"").val();
		
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_menu_update_link",{id:no,linkValue:linkV},function(result){
	   
	    processing.close();
		
		});
		
	}
	
	function addProductView(no)
	{
		window.location = "?page=AmazingMainPageAppearance&childpage=addProduct&id="+no+"";
	}
	
	function onChangeSelect(no)
	{
		var value = $('#sortable_select_'+no+'').val();
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		
		if(value == "customitems")
		{
			$('#add_item_'+no+'').show();
		}
		else
		{
			$('#add_item_'+no+'').hide();
		}
		
		if(value == "usehtml")
		{
			$('#save_link_item_'+no+'').show();
			$('#sortable_html_link_'+no+'').show();
		}
		else
		{
			$('#save_link_item_'+no+'').hide();
			$('#sortable_html_link_'+no+'').hide();
		}
		
		
		if(value == "usepage")
		{
			$('#select_page_'+no+'').show();
		
		}
		else
		{
			
			$('#select_page_'+no+'').hide();
		}
		
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_menu_update_type",{id:no,type:value},function(result){
	   
	    processing.close();
		
		});
	}
	
	
	function deleteItem(no)
	{
	var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
	 
	 $.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_menu_delete",{id:no},function(result){
	    $("#sortable_li_" + no).remove();	
	    processing.close();
		
		});
	 
	 
	}
	
	function deleteProduct(no)
	{
	var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
	 
	 $.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_menu_product_delete",{id:no},function(result){
	    $("#sortable_li_" + no).remove();	
	    processing.close();
		
		});
	 
	 
	}
	
	function saveAll()
	{
		 var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		var aserrr = $('ol.sortable').nestedSortable('serialize');
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_menu_save_all",{dataser:aserrr},function(result){
	   
	 processing.close();
	 
	 
	 
		
		});
		
	}
	
	
	function saveProductAll()
	{
		 var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		var aserrr = $('ol.sortable').nestedSortable('serialize');
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=home_appearance_menu_save_product_all",{dataser:aserrr},function(result){
	   
	 processing.close();
	 
	 
	 
		
		});
		
	}
	
	
	
	
	
</script>
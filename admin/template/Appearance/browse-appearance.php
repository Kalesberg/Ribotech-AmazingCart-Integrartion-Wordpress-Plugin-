<?php 
$category = new AmazingCart_category_appearance();
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

<div style="width:59%; margin-bottom:20px; text-align:left; ">
    
    <div>Show Category Thumbnail : 
    
    <select id="showThumbnail" onChange="onChangeOption()" >
    <?php if(get_option('amazingcart_show_category_thumb') == "hide"){ ?>
        <option value="show">Show</option>
        <option value="hide" selected>Hide</option>
        <?php }else{ ?>
        <option value="show" selected>Show</option>
        <option value="hide" >Hide</option>
        <?php } ?>
    	</select>
         <button class="button-primary" onClick="saveShowThumb()" >Save</button>
    
    </div>
    <div style="margin-top:20px;">
    Options : 
    	<select id="chooseOpton" onChange="onChangeOption()" >
        <?php if(get_option('amazingcart_categories_option') == "listall"){ ?>
        <option value="listall" selected>List all categories</option>
         <option value="customcategories">Custom Categories</option>
         <?php }else{ ?>
          <option value="listall" >List all categories</option>
         <option value="customcategories" selected>Custom Categories</option>
         <?php } ?>
    	</select>
         <button class="button-primary" onClick="changeOption()" >Save</button>
         
     </div>
 </div>
 
 
 <div id="useCustomCategories">
 
 	 <div>
     
     <select id="catID">
     <?php $category->get_product_categories(); ?>
     </select>
     
      <button class="button-primary" onClick="addCategory()" >Add Category</button>
     </div>
     
     
     
     <div id="sortableDiv">
    	<ol class="sortable">
   		  <?php $category->get_all_custom_categories(); ?>
    	 
		</ol>
    
    	<div><button class="button-primary" onClick="saveOrder()" >Save Order</button></div>
    
    </div>
     
 
 </div>
 
 <script>
 $(document).ready(function(){

        $('.sortable').nestedSortable({
            handle: 'div',
            items: 'li',
			maxLevels: 0,
            toleranceElement: '> div'
        });

			onChangeOption();
    });
	
	function onChangeOption()
	{
		var option = $('#chooseOpton').val();
		if(option == "listall")
		{
			
			$('#useCustomCategories').hide();
		}
		else
		{
			$('#useCustomCategories').show();
		}
		
	}
	
	
	
	function saveShowThumb()
	{
		var opt = $('#showThumbnail').val();
		
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=category_appearance_choose_thumb",{showThumb:opt},function(result){
	   
	 processing.close();
	
	 
	 
		
		});
	}
	
	function changeOption()
	{
		var opt = $('#chooseOpton').val();
		
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=category_appearance_choose_option",{option:opt},function(result){
	   
	 processing.close();
	
	 
	 
		
		});
	}
	
	
	function deleteItem(ids)
	{
		
		 var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=category_appearance_delete_item",{id:ids},function(result){
	   
	 processing.close();
	 $('#category_no_'+ids+'').remove();
	 
	 
		
		});
	}
	
	function addCategory()
	{
		var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		
		var catID = $('#catID').val();
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=category_appearance_add_category",{categoryID:catID},function(result){
	   
	   $('.sortable').load('<?php echo bloginfo('url'); ?>/?amazingcart=getAjax&type=get_all_custom_categories', function() {
   		processing.close();
		});
		
		
		});
		
	}
	
	function saveOrder()
	{
		
		 var processing = noty({
			layout: 'topRight',
			type: 'information',
			text: 'Processing...'
			});
		var aserrr = $('ol.sortable').nestedSortable('serialize');
		
		$.post("<?php echo bloginfo('url'); ?>/?amazingcart=adminAjaxSave&type=category_appearance_save_order",{serialize:aserrr},function(result){
	   
	 processing.close();
	 
	 
	 
		
		});
	}
 </script>
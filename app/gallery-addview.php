<?php
    include_once "config.inc.php";
    include_once "includes/class_database.php";
    //include_once "includes/session.php";
    
    $config = new JConfig();
    $db = new JDatabase($config->dbhost, $config->dbname, $config->dbuser, $config->dbpassword);
	
	$action = $_POST["action"];
    $idx = $_POST["idx"];
	$pluginurl = $_POST['plugin'];
	$real_path = $link_url = $description = '';
	
    if( $action=="edit" )
    {
		$galleries = $db->get_result("SELECT * FROM tbl_gallery WHERE id=".$idx);

		$real_path = $galleries[0]["real_path"];
		$description = $galleries[0]["description"];
		$link_url = $galleries[0]["link_url"];
	}
?>
<div id="pagecontainer">
    <fieldset>
		<form method="post" action="" name="gallery_frm" id="gallery_frm" enctype="multipart/form-data">
			<div class="paper">
				<div class="paper-title">
					Gallery
					<hr>
				</div>
				<table class="paper-table">
				<?php if($real_path != ''): ?>
					<tr>
						<td class="paper-td" width="150px">Photo:</td>
						<td width="750px">
							<img src="<?php echo $pluginurl.$real_path;?>" width=300 border="0" />
						</td>
					</tr>
				<?php endif; ?>
					<tr>
						<td class="paper-td" width="150px">&nbsp;</td>
						<td width="750px">
							<input name="imagefile" id="imagefile" type="file">
						</td>
					</tr>
					<tr>
						<td class="paper-td" width="150px">Description:</td>
						<td width="750px">
							<textarea name="description" id="description" class="contents" rows="6" style="width:640px;"><?php echo $description;?></textarea>
						</td>
					</tr>
					<tr>
						<td class="paper-td" width="150px">Link:</td>
						<td width="750px">
							<input name="link_url" id="link_url" style="width:640px;" class="textfield input required" value="<?php echo $link_url;?>" type="text">
						</td>
					</tr>
				</table>
				<div class="paper-row" style="height:30px;"></div>
				<div class="paper-row">
					<input type="hidden" name="action" id="action" value="<?php echo $action;?>">
					<input type="hidden" name="idx" id="idx" value="<?php echo $idx;?>">

					<input type="submit" value=" Save " style="margin-top:10px;margin-left:300px;">
					<input id="cancelbtn" name="cancelbtn" type="button" value=" Cancel " style="margin-top:10px;margin-left:100px;">
				</div>
				<div class="paper-row" style="height:100px;"></div>
			</div>
		</form>
	</fieldset>
	<fieldset class="tblFooters"></fieldset>
</div>

<script type="text/javascript">
	jQuery(document).ready( function($) {		                        
		jQuery.validator.messages.required = "";
        
        jQuery("#gallery_frm").validate({
            submitHandler : function(form) {
            
				jQuery(form).ajaxSubmit({
                    target: "",
                    beforeSend : function(msg) {
                        alert('hey');
                    },
                    success : function(msg) {
                    alert('done'+ msg);
                        jQuery("#maincontainer").html(msg);
                    }
                });
            },
            invalidHandler : function(form, validator) {
                
            }
        });
    });

	jQuery( "#cancelbtn" ).click(function() {
		jQuery.ajax({
            type: "POST",
            url: "index-gallery-manager.php",
            data: "",
            beforeSend:function(){
                
            },
            success:function(msg){
                jQuery("#maincontainer").html(msg);
            }
        });
	})

</script>
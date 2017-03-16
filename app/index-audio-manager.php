<?php
    include_once "config.inc.php";
    include_once "includes/class_database.php";
    include_once "includes/functions.lib.php";
	// include_once "includes/session.php";
    
    $config = new JConfig();
    $db = new JDatabase($config->dbhost, $config->dbname, $config->dbuser, $config->dbpassword);
	
	if( isset($_REQUEST["action"]) &&  $_REQUEST["action"]=="save" )
	{
		if(isset($_FILES['audiofile']) && $_FILES['audiofile']['tmp_name']!='') 
		{
			$audios = $db->get_result("SELECT * FROM tbl_push_audio");
		
			if( count($audios) > 0 )
			{
				$real_path = $audios[0]["real_path"];
				$folder = dirname(__FILE__);
				unlink($folder.$real_path);

				$db->query("truncate table tbl_push_audio");
			}

			$file_tmp = $_FILES['audiofile']['tmp_name'];
			$file_size = $_FILES['audiofile']['size'];
			$filename = basename($_FILES['audiofile']['name']);
			$file_ext = substr($filename, strrpos($filename, '.') + 1);

			$save_name = date("Ymd").rand(1001, 9999).".".$file_ext;
			
			$file_location = dirname(__FILE__)."/resources/audios/".$save_name;

			if (!move_uploaded_file($file_tmp, $file_location)) 
			{
				die("Couldnt move uploaded file".$file_location);
			}
			
			$audio["real_name"] = $filename;
			$audio["real_path"] = "/resources/audios/".$save_name;

			$db->insert_query($audio, "tbl_push_audio");

		}
	}
?>
<link rel="stylesheet" href="<?php echo PLUGIN_DIR . 'app/css/system.css'; ?>" />
<div id="pagecontainer">
    <fieldset>
        <legend>
            Push Audio Manager
        </legend>
		<?php
			$audios = $db->get_result("SELECT * FROM tbl_push_audio");

			if( count($audios) > 0 )
			{
				echo '<div class="genaral-row-div">';
					echo '<div class="general-label">';
						echo 'Current Audio:';
					echo '</div>';
					echo '<div class="general-label">';
						echo $audios[0]["real_name"];
					echo '</div>';
				echo '</div>';
			}
		?>
		<form method="post" action="" name="audio_frm" id="audio_frm" enctype="multipart/form-data">
			<div class="genaral-row-div">
				<div class="general-label">New Audio:</div>
				<input name="audiofile" id="audiofile" type="file" style="padding-top:5px" class="input required">
			</div>
			<div class="genaral-row-div">
				<input type="hidden" name="action" id="action" value="save">
				<input type="submit" value=" Save " style="margin-top:10px;margin-left:200px;">
			</div>
		</form>
	</fieldset>
    <fieldset class="tblFooters"></fieldset>
</div>

<script type="text/javascript">
	$(document).ready( function() {
		
		jQuery.validator.messages.required = "";
        
        jQuery("#audio_frm").validate({
            submitHandler : function(form) {
                jQuery(form).ajaxSubmit({
                    target: "",
                    beforeSend : function(msg) {
                        
                    },
                    success : function(msg) {
                        $("#maincontainer").html(msg);
                    }
                });
            },
            invalidHandler : function(form, validator) {
                
            }
        });
    });	
</script>
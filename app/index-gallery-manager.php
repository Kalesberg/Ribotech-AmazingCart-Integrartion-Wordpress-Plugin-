<?php
    include_once "config.inc.php";
    include_once "includes/class_database.php";
    include_once "includes/functions.lib.php";
	//include_once "includes/session.php";
    
    $config = new JConfig();
    $db = new JDatabase($config->dbhost, $config->dbname, $config->dbuser, $config->dbpassword);
	
	if( isset($_REQUEST["action"]) &&  $_REQUEST["action"]=="add" )
	{

		$gallery["description"] = $_REQUEST["description"];
		$gallery["link_url"] = $_REQUEST["link_url"];
		$gallery["update_datetime"] = date("Y-m-d H:i:s");
		
		if(isset($_FILES['imagefile']) && $_FILES['imagefile']['tmp_name']!='') 
		{
			// echo "alert('hey2');";
			$file_tmp = $_FILES['imagefile']['tmp_name'];
			$file_size = $_FILES['imagefile']['size'];
			$filename = basename($_FILES['imagefile']['name']);
			$file_ext = substr($filename, strrpos($filename, '.') + 1);
		
			$save_name = date("Ymd").rand(1001, 9999).".".$file_ext;
			
			$file_location = dirname(__FILE__)."/resources/photos/".$save_name;
			$thumb_file_location = dirname(__FILE__)."/resources/thumbs/".$save_name;

			// echo "alert('$filetmp');";
			// echo "alert('$file_location');";
			if (!move_uploaded_file($file_tmp, $file_location)) 
			{
				die("Couldnt move uploaded file".$file_location);
			}
			else
			{
				list($imagewidth, $imageheight, $imageType) = getimagesize($file_location);
				
				$show_width = "80";
				$scale = $show_width/$imagewidth;

				$thumbnailed = resizeThumbnailImage($thumb_file_location, $file_location, $imagewidth, $imageheight, 0, 0, $scale);


				$gallery["real_path"] = "/resources/photos/".$save_name;
				$gallery["thumb_path"] = "/resources/thumbs/".$save_name;
			}
		}

		$db->insert_query($gallery, "tbl_gallery");
	}

	if( isset($_REQUEST["action"]) &&  $_REQUEST["action"]=="edit" )
	{
		$idx = $_REQUEST["idx"];

		$gallery["description"] = addslashes($_REQUEST["description"]);
		$gallery["link_url"] = $_REQUEST["link_url"];
		$gallery["update_datetime"] = date("Y-m-d H:i:s");

		if(isset($_FILES['imagefile']) && $_FILES['imagefile']['tmp_name']!='') 
		{
			$file_tmp = $_FILES['imagefile']['tmp_name'];
			$file_size = $_FILES['imagefile']['size'];
			$filename = basename($_FILES['imagefile']['name']);
			$file_ext = substr($filename, strrpos($filename, '.') + 1);
		
			$save_name = date("Ymd").rand(1001, 9999).".".$file_ext;
			
			$file_location = dirname(__FILE__)."/resources/photos/".$save_name;
			$thumb_file_location = dirname(__FILE__)."/resources/thumbs/".$save_name;

			if (!move_uploaded_file($file_tmp, $file_location)) 
			{
				die("Couldnt move uploaded file".$file_location);
			}
			else
			{
				list($imagewidth, $imageheight, $imageType) = getimagesize($file_location);
				
				$show_width = "80";
				$scale = $show_width/$imagewidth;

				$thumbnailed = resizeThumbnailImage($thumb_file_location, $file_location, $imagewidth, $imageheight, 0, 0, $scale);


				$gallery["real_path"] = "/resources/photos/".$save_name;
				$gallery["thumb_path"] = "/resources/thumbs/".$save_name;
			}
		}

		$db->update_query($gallery, "tbl_gallery", $idx, "id");
	}

	if( isset($_REQUEST["action"]) &&  $_REQUEST["action"]=="delete" )
	{
		// echo "alert ('hey');";
		$idx = $_POST["idx"];

		$gallery = $db->get_result("SELECT * FROM tbl_gallery WHERE id=".$idx);
		$real_path = $gallery[0]["real_path"];
		$thumb_path = $gallery[0]["thumb_path"];
		
		$folder = dirname(__FILE__);
		unlink($folder.$real_path);
		unlink($folder.$thumb_path);
	
		$db->query("DELETE FROM tbl_gallery WHERE id=".$idx);
		exit;
	}
?>
<link rel="stylesheet" href="<?php echo PLUGIN_DIR . 'app/css/system.css'; ?>" />
<div id="maincontainer">
    <fieldset>
        <legend>
            Gallery Management
        </legend>
		<div class="genaral-row-div">
            <input id="addbtn" name="addbtn" type="button" value=" Adding " style="margin-top:10px;margin-left:1000px;" onclick="showAddView('add', 0)"></input>
        </div>
		<div class="genaral-row-div">
			<table>
				<thead>
					<th width="40px">No</th>
					<th width="80px">Photos</th>
					<th width="320px">Description</th>
					<th width="320px">Link</th>
					<th width="150px">Update Date</th>
					<th width="160px">Action</th>
				</thead>
				<tbody>
				<?php
					$galleries = $db->get_result("SELECT * FROM tbl_gallery");
						
					for($icount=0; $icount < count($galleries); $icount++)
                    {
						$thumb_path = PLUGIN_DIR.'app'.$galleries[$icount]["thumb_path"];

                        $row_num = $icount+1;
                        
                        $odd_even = ($row_num%2)?'odd':'even';
                        
                        echo '<tr class="'.$odd_even.'">';
							echo '<td align="center">'.$row_num.'</td>';
							echo '<td align="center">';
								echo '<img src="'.$thumb_path.'" width="80px" height="50px">';
							echo '</td>';
							echo '<td align="center">';
								echo $galleries[$icount]["description"];
							echo '</td>';
							echo '<td align="center">';
								echo $galleries[$icount]["link_url"];
							echo '</td>';
							echo '<td align="center">'.$galleries[$icount]["update_datetime"].'</td>';
							echo '<td align="center">';
								echo '<span class="nowrap">';
                                    echo '<a href="javascript:showAddView(\'edit\', '.$galleries[$icount]["id"].')">';
										echo '<img class="icon" width="16" height="16" alt="Edit" title="Edit" src="'.PLUGIN_DIR.'app'.'/themes/b_edit.png"></img>Edit';
                                    echo '</a>';
                                echo '</span>';
                                echo '<span class="nowrap">';
                                    echo '<a href="javascript:deleteRow('.$galleries[$icount]["id"].')">';
                                        echo '<img class="icon" width="16" height="16" alt="Delete" title="Delete" src="'.PLUGIN_DIR.'app'.'/themes/b_drop.png"></img>Delete';
                                    echo '</a>';
                                echo '</span>';
                            echo '</td>';
						echo '</tr>';
					}
					
					if( count($galleries) == 0 )
					{
						echo '<tr>';
							echo '<td colspan="6">No Data</td>';
						echo '</tr>';
					}
				?>
				</tbody>
				<tfoot>
					<th colspan="6"></th>
				</tfoot>
			</table>
		</div>
	</fieldset>
    <fieldset class="tblFooters"></fieldset>
</div>
	
<script type="text/javascript">
	
	function showAddView(mode, idx) {
        
        var postData = "action="+mode+"&idx="+idx+"&plugin="+'<?php echo PLUGIN_DIR.'app'; ?>';
        
	<?php $url = plugin_dir_url( __FILE__ )."gallery-addview.php"; ?>

        jQuery.ajax({
            type: "POST",
            url: "<?php echo $url ?>",
            data: postData,
            beforeSend:function(){
                
            },
            success:function(msg){
                jQuery("#maincontainer").html(msg);
            }
        });
    }

	function deleteRow(idx)
	{
		if( confirm("Are you sure you want to delete selected data?") )
		{
			var postData = "action=delete&idx="+idx;
			
			jQuery.ajax({
				type: "POST",
				url: "",
				data: postData,
				beforeSend:function(){
					
				},
				success:function(msg){
					location.reload();
				}
			});
		}
	}

</script>
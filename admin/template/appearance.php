<?php

$tabs = array( 'homeAppearance' => 'Home Appearance', 'browseAppearance' => 'Product Categories Appearance');
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
 
		if($_GET['tab'] == $tab )
		{
        echo "<a class='nav-tab nav-tab-active' href='?page=AmazingMainPageAppearance&tab=$tab'>$name</a>";
		}
		else
		{
			if($_GET['tab'] == "" && $tab == "homeAppearance")
			{
				echo "<a class='nav-tab nav-tab-active' href='?page=AmazingMainPageAppearance&tab=$tab'>$name</a>";
			}
			else
			{
			  echo "<a class='nav-tab' href='?page=AmazingMainPageAppearance&tab=$tab'>$name</a>";
			}
		}
    }
    echo '</h2>';
	
	
	if($_GET['tab'] == "homeAppearance")
	{
		require AMAZING_CART_PLUGIN_PATH."admin/template/Appearance/home-appearance-settings.php";
		
	}
	else if($_GET['tab'] == "browseAppearance")
	{
		require AMAZING_CART_PLUGIN_PATH."admin/template/Appearance/browse-appearance.php";
		
	}
	else 
	{
		require AMAZING_CART_PLUGIN_PATH."admin/template/Appearance/home-appearance-settings.php";
	}

?>
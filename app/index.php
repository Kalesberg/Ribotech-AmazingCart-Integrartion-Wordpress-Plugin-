<?php
    include_once "config.inc.php";
    include_once "includes/session.php";
    
    $config = new JConfig();
    
    //date_default_timezone_set($config->default_timezone_value);
    
    if(!isset($admin_username))
    {
        header("Location: login.php");
    }
?>
<html>
    <head>
        <title><?php echo $config->sitename;?></title>
        <meta name="Author" content="">
        <meta name="Keywords" content="">
        <meta name="Description" content="">
        
        <link href="css/controls.css" rel="stylesheet" media="screen" type="text/css" />        
        <link href="css/system.css" rel="stylesheet" media="screen" type="text/css" />
        <link href="css/topmenu.css" rel="stylesheet" media="screen" type="text/css" />
        <link href="css/jquery-ui-1.8.16.custom.css" rel="stylesheet" media="screen" type="text/css" />
    
        <script type="text/javascript" src="js/jquery-1.6.2.js"></script>
        <script type="text/javascript" src="js/jquery.form-1.3.2.js"></script>
        <script type="text/javascript" src="js/jquery.validate-1.5.5.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
    </head>
    <body>
        <div id="serverinfo"><b><?php echo $config->sitename;?></b>&nbsp;&nbsp;:&nbsp;&nbsp;Hi,&nbsp;&nbsp;<?php echo $admin_username;?></div>
        <div id="topmenucontainer">
            <ul id="topmenu">
                <li>
                    <a class="tab" href="javascript:pageView('gallery-tab')" id="gallery-tab">
                        <img class="icon" width="16" height="16" alt="" src="themes/photos.png"></img>                        
                        Gallery
                    </a>
                </li>
				 <li>
                    <a class="tab" href="javascript:pageView('audio-tab')" id="audio-tab">
                        <img class="icon" width="16" height="16" alt="" src="themes/floor-plans.png"></img>
						PushAudio
                    </a>
                </li>
				<li>
                    <a class="tab" href="javascript:pageView('notify-tab')" id="notify-tab">
                        <img class="icon" width="16" height="16" alt="" src="themes/events.png"></img>
						Notification
                    </a>
                </li>
                <li class="submenu shown">
                    <a class="tab" href="javascript:pageView('stats-tab')" id="stats-tab">  
                        <img class="icon" width="16" height="16" alt="" src="themes/apartment_flat.png"></img>
                        Statistics
                    </a>
                </li>
                  <li class="submenu shown">
                    <a class="tab" href="javascript:pageView('shoutcast-tab')" id="shoutcast-tab">  
                        <img class="icon" width="16" height="16" alt="" src="themes/b_engine.png"></img>
                        Shoutcast
                    </a>
                </li>
                
                <li class="submenu shown">
                    <a class="tab" href="javascript:pageView('docu-tab')" id="docu-tab">  
                        <img class="icon" width="16" height="16" alt="" src="themes/events.png"></img>
                        Documentation
                    </a>
                </li>
                
                    </a>
                </li>
                
                <li class="submenu shown">
                    <a class="tab" href="javascript:empty()" id="settings-tab">   
                        <img class="icon" width="16" height="16" alt="" src="themes/b_tblops.png"></img>
                        Settings
                    </a>
                    <ul>
                        <li>
                            <a class="tab" href="javascript:pageView('settings-tab', 'changepwd')">
                                <img class="icon" width="16" height="16" alt="" src="themes/change_password.png"></img>
                                Change Password
                            </a>
                        </li>
						<li>
                            <a class="tab" href="logout.php">
                                <img class="icon" width="16" height="16" alt="" src="themes/s_loggoff.png"></img>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div id="maincontainer" style="width: 1200px;margin: auto;">
            
        </div>
    </body>
    <script type="text/javascript">
        
        function pageView(tabname, subsettings)
        {
            var postData = "";
            
            removeCssClass();
            $("#"+tabname).addClass("tabactive");
            
            var viewPageName = "";
        
            if (tabname == "gallery-tab")
            {
                viewPageName = "index-gallery-manager.php";
            }
			if (tabname == "audio-tab")
            {
                viewPageName = "index-audio-manager.php";
            }
			if (tabname == "notify-tab")
			{
				viewPageName = "index-notification.php";
			}
			if (tabname == "stats-tab")
			{
				viewPageName = "index-statistics.php";
			}
			if (tabname == "shoutcast-tab")
			{
				viewPageName = "index-shoutcast.php";
			}
			
			if (tabname == "appleDev-tab")
			{
				viewPageName = "index-appleDev.php";
			}
			
			if (tabname == "docu-tab")
			{
				viewPageName = "index-docu.php";
			}
			
            else if (tabname == "settings-tab")
            {
                if (subsettings=="changepwd") {
                    viewPageName = "setting-change-password.php";
                }
                else if (subsettings=="changename")
                {
                    viewPageName = "setting-change-username.php";    
                }
            }
            
            $.ajax({
                type: "POST",
                url: viewPageName,
                data: postData,
                beforeSend:function(){
                    $("#maincontainer").html('<div id="waiting_img" class="waiting_img" style="width:850px;height:300px;text-align:center;margin-top:40px;"><img src="images/loading_big.gif" width="128" height="128" border="0"><div style="font-size:28px;margin-top:10px;font-family: Arial, Helvetica, sans-serif;font-weight: bold;color:white;">Loading....</div></div>');
                },
                success:function(msg){
                    $("#maincontainer").html(msg);
                }
            });
        }
        
        function removeCssClass()
        {
            $("#settings-tab").removeClass("tabactive");   
			$("#gallery-tab").removeClass("tabactive");
			$("#audio-tab").removeClass("tabactive");
			$("#notify-tab").removeClass("tabactive");
        }

		function empty(){}
        
    </script>
</html>


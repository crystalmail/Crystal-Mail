<?php
include("login.php");
// Database file, i.e. file with real data
$data_file = USERS_LIST_FILE;

// Database definition file. You have to describe database format in this file.
// See flatfile.inc.php header for sample.
$structure_file = 'users.def';

// Fields delimiter
$delimiter = ',';

// Number of lines to skip
$skip_lines = 1;

include("../config/admin.inc.php");
include("../config/version.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="robots" content="index,follow" />
    <title>Crystal Mail Admin Panel :: Check For Updates</title>
	
    <link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
	<link rel="Stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.7.1.custom.css"  />	
	<!--[if IE 7]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen, projection" /><![endif]-->
	<!--[if IE 6]><link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen, projection" /><![endif]-->
	<link rel="stylesheet" type="text/css" href="markitup/skins/markitup/style.css" />
	<link rel="stylesheet" type="text/css" href="markitup/sets/default/style.css" />
	<link rel="stylesheet" type="text/css" href="css/superfish.css" media="screen">
	<!--[if IE]>
		<style type="text/css">
		  .clearfix {
		    zoom: 1;     /* triggers hasLayout */
		    display: block;     /* resets display for IE/Win */
		    }  /* Only IE can see inside the conditional comment
		    and read this CSS rule. Don't ever use a normal HTML
		    comment inside the CC or it will close prematurely. */
		</style>
	<![endif]-->
	<!-- JavaScript -->
    <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
	<script type="text/javascript" src="js/hoverIntent.js"></script>
	<script type="text/javascript" src="js/superfish.js"></script>
	<script type="text/javascript">
		// initialise plugins
		jQuery(function(){
			jQuery('ul.sf-menu').superfish();
		});

	</script>
	<script type="text/javascript" src="js/excanvas.pack.js"></script>
	<script type="text/javascript" src="js/jquery.flot.pack.js"></script>
    <script type="text/javascript" src="markitup/jquery.markitup.pack.js"></script>
	<script type="text/javascript" src="markitup/sets/default/set.js"></script>
  	<script type="text/javascript" src="js/custom.js"></script>
  	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
  	<script type="text/javascript">
  	                                      
    $(document).ready(function() {
    $('#fade').fadeIn("slow");
});              
</script><script type="text/javascript">
function getXMLHttp()
{
  var xmlHttp

  try
  {
    //Firefox, Opera 8.0+, Safari
    xmlHttp = new XMLHttpRequest();
  }
  catch(e)
  {
    //Internet Explorer
    try
    {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e)
    {
      try
      {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e)
      {
        alert("Your browser does not support AJAX!")
        return false;
      }
    }
  }
  return xmlHttp;
}
function Update()
{
   $('#message').fadeOut('slow');
    setTimeout(function() { $('#updating').fadeIn(); }, 1000);
  var xmlHttp = getXMLHttp();
  
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      HandleResponse(xmlHttp.responseText);
    }
  }

  xmlHttp.open("GET", "update.php", true); 
  xmlHttp.send(null);
  setTimeout(function() { 
    $('#updating').fadeOut('slow');
    setTimeout(function() { $('#done').fadeIn(); }, 1500); 
    }, 4000);
}
</script>

	 <!--[if IE]><script language="javascript" type="text/javascript" src="excanvas.pack.js"></script><![endif]-->
</head>
<body>
<div class="container" id="container">
    <div  id="header">
    	<div id="profile_info">
			<img src="img/avatar.jpg" id="avatar" alt="avatar" />
			<br>
			<p>Welcome <strong>Admin</strong>. <a href="?logout=1">Log out?</a></p>
			<p>System Version:&nbsp; <?php echo $version; ?></p>
		</div>
		<div id="logo"><h1><a href="./index.php">Crystal Webmail Admin Panel</a></h1></div>
		
    </div><!-- end header -->
	    <div id="content" >
	    <?php
	    include ('nav.php')
	    ?>
</div>
		<div id="content_main" class="clearfix">
			<div id="main_panel_container" class="left">
			<div id="subpage">
			<div align="left">
				<h2 class="ico_mug">Check For Updates</h2>
				</div>
				<div class="clearfix">
				<div class="content_sub_page" style="height:125px;">
			
			
<div id="fade" style="display:none;">
<center><img src="img/ajax-loader.gif"></center>
<center><div style="font-family:arial; font-size:19px; color: #333;">Checking For Updates</div>
</div>
<?php

// Download Info File
if (!copy('http://update.crystalwebmail.com/info', 'info.php')) {
}

//Include Info File
include ('info.php');

//Include Version File
include ('../config/version.php');


//Check if Installed Version and Info Version
if ($version == $infoversion){

echo "
<script type='text/javascript'>                                         
    $(document).ready(function() {
    $('#fade').fadeOut('slow');
    setTimeout(function() { $('#message').fadeIn(); }, 1500);
});
 </script>  
<div id='message' style='display:none;'>
<center><h1 style='font-family:arial; font-size:30px; color: #333;'>Up to date</h1>
<p style='font-family:arial; font-size:15px; color: #333;'>Your Version of Crystal Mail is the newest possable version. No further action is needed.</p> 
</div>

";
}
else {

echo "<script type='text/javascript'>                                         
    $(document).ready(function() {
    $('#fade').fadeOut('slow');
    setTimeout(function() { $('#message').fadeIn(); }, 1500);
});
 </script>  
<div id='message' style='display:none;'>
<center><h1 style='font-family:arial; font-size:30px; color: #333;'><strong>Update Available</strong></h1>
<p style='font-family:arial; font-size:15px; color: #333;'>Crystal Mail is not the newest version possable! Please press the <strong>Update</strong> button below to take advantage of this new update.</p> 
<input type='button' onclick='Update();' value='Update'/>
</div>
<div id='updating' style='display:none;'>
<center><img src='ajax-loader.gif'></center>
<center><div style='font-family:arial; font-size:19px; color: #333;'>Updating</div>
</div>
<div id='done' style='display:none;'>
<center><h1 style='font-family:arial; font-size:30px; color: #333;'>Update Complete!</h1>
<p style='font-family:arial; font-size:15px; color: #333;'>Crystal Mail has finished updating. Click the button below to check if there are any more updates for your system.</p> 
<input type='button' onclick='window.location.reload()' value='Check Again'/>
</center>
</div>
</div>";
}

unlink ('info.php');
include ('footer.php');
?>

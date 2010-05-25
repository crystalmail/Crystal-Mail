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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Crystal Mail Admin - User Management</title>
	<meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="robots" content="index,follow" />
	
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

	 <!--[if IE]><script language="javascript" type="text/javascript" src="excanvas.pack.js"></script><![endif]-->
	 <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	//if submit button is clicked
	$('#submit').click(function () {		
		
		//Get the data from all the fields
		var name = $('input[name=name]');
		var email = $('input[name=email]');
		var website = $('input[name=website]');
		var comment = $('textarea[name=comment]');

		//Simple validation to make sure user entered something
		//If error found, add hightlight class to the text field
		if (name.val()=='') {
			name.addClass('hightlight');
			return false;
		} else name.removeClass('hightlight');
		
		if (email.val()=='') {
			email.addClass('hightlight');
			return false;
		} else email.removeClass('hightlight');
		
		if (comment.val()=='') {
			comment.addClass('hightlight');
			return false;
		} else comment.removeClass('hightlight');
		
		//organize the data properly
		var data = 'name=' + name.val() + '&email=' + email.val() + '&website=' + 
		website.val() + '&comment='  + encodeURIComponent(comment.val());
		
		//disabled all the text fields
		$('.text').attr('disabled','true');
		
		//show the loading sign
		$('.loading').show();
		
		//start the ajax
		$.ajax({
			//this is the php file that processes the data and send mail
			url: "message_process.php",	
			
			//GET method is used
			type: "GET",

			//pass the data			
			data: data,		
			
			//Do not cache the page
			cache: false,
			
			//success
			success: function (html) {				
				//if process.php returned 1/true (send mail success)
				if (html==1) {					
					//hide the form
					$('.form').fadeOut('slow');					
					
					//show the success message
					$('.done').fadeIn('slow');
					
				//if process.php returned 0/false (send mail failed)
				} else alert('Sorry, unexpected error. Please try again later.');				
			}		
		});
		
		//cancel the submit button default behaviours
		return false;
	});	
});	
</script>
<style>
body{text-align:center;}
.clear {
	clear:both
}
.block {
	width:400px;
	margin:0 auto;
	text-align:left;
}
.element * {
	padding:5px; 
	margin:2px; 
	font-family:arial;
	font-size:12px;
}
.element label {
	float:left; 
	width:75px;
	font-weight:700
}
.element input.text {
	float:left; 
	width:270px;
	padding-left:20px;
}
.element .textarea {
	height:120px; 
	width:270px;
	padding-left:20px;
}
.element .hightlight {
	border:2px solid #9F1319;
	background:url(img/iconCaution.gif) no-repeat 2px
}
.element #submit {
	float:right;
	margin-right:10px;
}
.loading {
	float:right; 
	background:url(img/ajax-loader.gif) no-repeat 1px; 
	height:28px; 
	width:28px; 
	display:none;
}
.done {
	background:url(img/iconIdea.gif) no-repeat 2px; 
	padding-left:20px;
	font-family:arial;
	font-size:12px; 
	width:70%; 
	margin:20px auto; 
	display:none
}
</style>
<body>

<div class="container" id="container">
    <div  id="header">
    	<div id="profile_info">
			<img src="img/avatar.jpg" id="avatar" alt="avatar" />
			<p>Welcome <strong>Admin</strong>. <a href="?logout=1">Log out?</a></p>
			<p>System Version:<a href="http://crystalwebmail.com/?versionfeatures=<?php echo systemversion; ?>"><?php echo systemversion; ?></a></p>
			<p class="last_login">Last login: 21:03 12.05.2010</p>
		</div>
		<div id="logo"><h1><a href="/">AdmintTheme</a></h1></div>
		
    </div><!-- end header -->
	    <div id="content" >
	    <div id="top_menu" class="clearfix">
	    	<ul class="sf-menu"> <!-- DROPDOWN MENU -->
			<li class="current">
				<a href="#a">Settings</a><!-- First level MENU -->
				<ul>
					<li>
						<a href="#aa">Database options</a>
					</li>
					<li class="current">
						<a href="#ab">Blog settings</a> <!-- Second level MENU -->
						<ul>
							<li class="current"><a href="#">Settings</a></li>
							<li><a href="#aba">menu item</a></li>
							<li><a href="#abb">menu item</a></li>
							<li><a href="#abc">menu item</a></li>
							<li><a href="#abd">menu item</a></li>
						</ul>
					</li>
					<li>
						<a href="#">Menu Options</a> <!-- Third level MENU -->
						<ul>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
						</ul>
					</li>
					<li>
						<a href="#">Editor</a>
						<ul>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
						</ul>
					</li>
				</ul>
			</li>
			<li>
				<a href="admin_user_management.php">Admin User Management</a>
			</li>
			
		</ul>
			<a href="#" id="visit" class="right">Visit site</a>
	    </div>
		<div id="content_main" class="clearfix">
			<div id="main_panel_container" class="left">
			<div id="subpage">
			<div align="left">
				<h2 class="ico_mug">Login Message</h2>
				</div>
				<div class="clearfix">
				<div class="content_sub_page">
				



<div class="block">
<div class="done">
<b><strong>Success!</strong></b> Your Message Has Been Posted 
</div>
	<div class="form">
	<form method="post" action="message_process.php">
	<div class="element">
		<label>Comment</label>
		<textarea name="comment" class="text textarea" /></textarea>
	</div>
	<div class="element">
		
		<input type="submit" id="submit"/>
		<div class="loading"></div>
	</div>
	</form>
	</div>
</div>

<div class="clear"></div>
<?php
include ('footer.php');
?>
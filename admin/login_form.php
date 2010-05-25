<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Crystal Mail Admin - Login</title>
	<meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="robots" content="index,follow" />
	<!--[if IE]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen, projection" /><![endif]-->
    <link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
	<link rel="Stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.7.1.custom.css"  />	
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
		<script type="text/javascript" src="js/custom.js"></script>
	</head>
	 <!--[if IE]><script language="javascript" type="text/javascript" src="excanvas.pack.js"></script><![endif]-->
</head>
<body>
<div  id="login_container">
    <div  id="header">
   
		<div id="logo"><h1><a href="/">AdmintTheme</a></h1></div>
		
    </div><!-- end header -->
	   
	    <div id="login" class="section">
	    	
	    	<form name="loginform" id="loginform" method="post">
			
			
			<font color="red"><?php echo $error_msg; ?></font><br />
<?php if (USE_USERNAME) echo '<label><strong>Username</strong></label><input type="text" name="access_login" id="user_login"  size="22" class="input"/>'; ?>
			<br />
			<label><strong>Password</strong></label><input type="password" name="access_password" id="user_pass"  size="22" class="input"/>
			
			<br />
		<br>
			<input id="save" class="loginbutton" type="submit" class="submit" value="Log In" />
			
			</form>
			
			<a href="reminder.php" id="passwordrecoverylink">Forgot your username or password?</a>
	    </div>
	
	    
		    


</div><!-- end container -->

</body>
</html>

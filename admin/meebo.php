<?php
include ('header.php');

include ('../config/main.inc.php');
?>
 
	
	
	<!--[if lt IE 7]>
	<style> 
	div.apple_overlay {
		background-image:url(http://static.flowplayer.org/tools/img/overlay/overlay_IE6.gif);
		color:#fff;
	}
	
	/* default close button positioned on upper right corner */
	div.apple_overlay div.close {
		background-image:url(http://static.flowplayer.org/tools/img/overlay/overlay_close_IE6.gif);
	
	}	
	</style>
	<![endif]--> 
<h2 class="ico_mug">Meebo Bar Configuration</h2>
				<div class="clearfix">
				<div class="content_sub_page">
				<form action="meebo_save.php" method="post">
<strong>Meebo Bar ID:</strong><input type="text" value="<?php echo$rcmail_config['meebo_code']?>" name="code";> <font size="1" color="#333" face="Verdana">(<a href="#" id="show-menu">What?</a>)</font><br>
<br>
<input type="submit" />
</form>
<div id="what"style="display: none;">
<p>
<a href="#" rel="#photo1"><h2>What is Meebo?</h2></a>
<div id="what-is-meebo">
The Meebo Bar allows users to connect to multiple IM networks including, Facebook, Google Talk, Twitter, Myspace Chat, AIM, Yahoo (Requires a meebo account), and ICQ (Requires a meebo account). Click <a href="http://bar.meebo.com" target="_blank">Here</a> for a demo!</p>
</div>

<h2>How Do I get a Meebo Bar?</h2>
<div id="get">
<strong>Step 1:</strong>Sign Up for a Meebo Bar account. To get started click <a href="https://bar.meebo.com/setup/1/" target="_blank">Here</a>.<br><br>
<strong>Step 2:</strong>After you have Entered your information and click Continue you will be asked for your <strong>Site Name</strong> and <strong>Site URL</strong> (It is very important that you enter this informaion correctly, If your Crystal Mail installation is on a sub-domain you will need to enter the sub-domain as well).<br><br>
<strong>Step 3:</strong>No you will be directed to a page that tells you how to add the meebo bar to your site. Crystal Mail is pre-configured for Meebo support all you will need to do is add your <strong>Meebo Network ID</strong> into the field above, please note <strong>this is not your username</strong>. To find your <strong>Meebo Network ID</strong> look at the url it should say "https://dashboard.meebo.com/<strong>Your Meebo Network ID</strong>/integrate/?fb=true. Copy and Paste your Meebo Network ID into the field above.<br><br>
<strong>Step 4:</strong>Enjoy!
</div>

<h2>Troubleshooting</h2>
<div id="troubleshooting">
<strong>I am using a non-offical skin with Crystal Mail and the meebo bar isn't showing up.</strong><br><br>
<form action="meebo_mod.php" method="post">
<input type="submit" value="Click Here to fix"/>
</form>
<div>
</div>
<script>
    $("#show-menu").click(function () {
    $("#what").show("slow");
    });
    
    </script>
<?php
include ('footer.php');
?>
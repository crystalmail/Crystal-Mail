<?php
include ('header.php');
if ($rcmail['login_message'] == "") {
echo "I got nothing!";
}
else{
echo "rcmail['login_message']='".$rcmail['login_message']."';";
}
?>
				<h2 class="ico_mug">Login Message</h2>
				<div class="clearfix">
				<div class="content_sub_page">
				
<center><div><p ><strong>Login Message</strong><br><textarea id="message" cols="40" rows="8"></textarea></center>
<?php
include ('footer.php');
?>
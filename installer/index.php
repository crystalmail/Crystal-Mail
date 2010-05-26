<link rel="stylesheet" href="style.css" type="text/css" media="all" />
<body>
<div id="logo"><img src="logo.png"/></div>
<?php
if (file_exists('../config/main.inc.php')) {
include ('../config/main.inc.php');
if ($rcmail_config['enable_installer'] == True)
{

error_reporting(0);
if (file_exists($_GET["step"].".php")) {

$step = $_GET["step"].".php";
include ($step);

} else if ($_GET["action"] == "install")
{
include ('install.php');
}
else 
{
include ('welcome.php');
}
}
else
{
echo '<div id="rounded"><h1><center>System Already Installed!</h1>
<br>
<center><p>You cannot use the installer because Crystal Mail is already installed. You can enable the installer in the admin panel by toggleing <strong>Enable_installer</strong> in the admin panel under <strong>Quick Config</strong></p></center></div>
<br>
</div>';
}
}
else {
error_reporting(0);
if (file_exists($_GET["step"].".php")) {

$step = $_GET["step"].".php";
include ($step);

} else if ($_GET["action"] == "install")
{
include ('install.php');
}
else 
{
include ('welcome.php');
}
}
?>
</div>
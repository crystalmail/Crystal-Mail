<?php

ini_set('error_reporting', E_ALL&~E_NOTICE);
ini_set('display_errors', 1);

define('INSTALL_PATH', realpath(dirname(__FILE__) . '/../').'/');
define('RCMAIL_CONFIG_DIR', INSTALL_PATH . 'config');

$include_path  = INSTALL_PATH . 'program/lib' . PATH_SEPARATOR;
$include_path .= INSTALL_PATH . 'program' . PATH_SEPARATOR;
$include_path .= INSTALL_PATH . 'program/include' . PATH_SEPARATOR;
$include_path .= ini_get('include_path');

set_include_path($include_path);

require_once 'utils.php';
require_once 'main.inc';

session_start();

$RCI = crystal_install::get_instance();
$RCI->load_config();

if (isset($_GET['_getfile']) && in_array($_GET['_getfile'], array('main', 'db'))) {
  $filename = $_GET['_getfile'] . '.inc.php.dist';
  if (!empty($_SESSION[$filename])) {
    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    echo $_SESSION[$filename];
    exit;
  }
  else {
    header('HTTP/1.0 404 Not found');
    die("The requested configuration was not found. Please run the installer from the beginning.");
  }
}

if ($RCI->configured && ($RCI->getprop('enable_installer') || $_SESSION['allowinstaller']) &&
    isset($_GET['_mergeconfig']) && in_array($_GET['_mergeconfig'], array('main', 'db'))) {
  $filename = $_GET['_mergeconfig'] . '.inc.php';

  header('Content-type: text/plain');
  header('Content-Disposition: attachment; filename="'.$filename.'"');
  
  $RCI->merge_config();
  echo $RCI->create_config($_GET['_mergeconfig'], true);
  exit;
}

// go to 'check env' step if we have a local configuration
if ($RCI->configured && empty($_REQUEST['_step'])) {
  header("Location: ./?_step=1");
  exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Crystal Webmail Installer</title>
<meta name="Robots" content="noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="client.js"></script>
<script type="text/javascript" src="../program/js/jquery-1.4.min.js"></script>
</head>

<body>

  <div id="logo"><img src="images/logo.png"/></div>
<center>
	<ol id="progress">
	<?php
  
	  foreach (array('Check Environment', 'Create Configuration', 'Generate Configuration', 'Test Configuration') as $i => $item) {
   	  	$j = $i + 1;
  	  	$link = ($RCI->step >= $j || $RCI->configured) ? '<a href="./index.php?_step='.$j.'">' . Q($item) . '</a>' : Q($item);
    	printf('<li class="step%d%s">%s</li>', $j+1, $RCI->step > $j ? ' passed' : ($RCI->step == $j ? ' current' : ''), $link);
  	  }
	?>
	</ol>
</center>


<?php

  // exit if installation is complete
  if ($RCI->configured && !$RCI->getprop('enable_installer') && !$_SESSION['allowinstaller']) {
    // header("HTTP/1.0 404 Not Found");
    echo '<h2 class="error">The installer is disabled!</h2>';
    echo '<p>To enable it again, set <tt>$rcmail_config[\'enable_installer\'] = true;</tt> in RCMAIL_CONFIG_DIR/main.inc.php</p>';
    echo '</div></body></html>';
    exit;
  }
  
?>

<?php
$include_steps = array('./welcome.php', './check.php', './config.php', './gen.php', './test.php');

if ($include_steps[$RCI->step]) {
  include $include_steps[$RCI->step];
}
else {
  header("HTTP/1.0 404 Not Found");
  echo '<h2 class="error">Invalid step</h2>';
}

?>

<div id="footer">
  Installer by the Crystal Webmail Dev Team. Copyright &copy; 2010 - Published under the GNU Public License;&nbsp;
  Icons by <a href="http://famfamfam.com">famfamfam</a>
</div>
</body>
</html>

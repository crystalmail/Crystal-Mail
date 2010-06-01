<form action="index.php?_step=3" method="post">
<?php
// check env for a local configuration
if (!$RCI->configured ) {
    echo '<h2><p class="center">Your configuration files have been written sucessfully!</p></h2>';

	$content = $_SESSION['main.inc.php'];
	$new_main = '../config/main.inc.php';
	$handle_main = fopen($new_main, 'x+') or die("Can't open file");
	fwrite ($handle_main,$content);
	fclose($handle_main);
	
	$handle_db = fopen('../config/db.inc.php', 'x+') or die("Can't open file");
	fwrite ($handle_db,$_SESSION['db.inc.php']);
	fclose($handle_db);
}
?>
<div id="rounded" class="center">
	<h3>Generating config files</h3>
	<div id="loading"><img src="images/loading.gif" id="loading.gif" name="loading.gif" alt="loading.gif" /></div>

<?php

	//Delay, then load next page
    echo '<div id="complete" style="display:none"><h3>COMPLETE!</h3></div>';
    echo "<script>setTimeout(function() { $('#loading').fadeOut(); }, 2000);</script>";
    echo "<script>setTimeout(function() { $('#complete').fadeIn(); }, 2500);</script>";
	if (!isset($_GET['reload'])) {
		echo '<meta http-equiv=Refresh content="4;url=index.php?_step=4">';
	}
?>

</div>

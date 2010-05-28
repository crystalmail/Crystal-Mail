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
include ('../config/main.inc.php');
// Some variables to work with
$file_location = '../config/main.inc.php';
$search_for_term = 'rcmail_config[\'meebo_code\'] = \''.$rcmail_config['meebo_code'].'\';';
$replace_with_term = 'rcmail_config[\'meebo_code\'] = \''.$_POST["code"].'\';';

// The function that does the replacing
function search_replace($search, $replace, $subject)
{
	$file_in_string = file_get_contents($subject);
	if(!file_put_contents($subject, str_replace($search, $replace, $file_in_string)))
	{
		return FALSE;
	}
	else
	{
		return TRUE;
	}
}

// Execute the function
search_replace($search_for_term, $replace_with_term, $file_location);
echo "<style>
body {
font-size:12px;font-family: Arial, Helvetica, sans-serif;color:#000;background:url(img/bg.jpg) repeat-x #4780ae;line-height:16px;
}
</style>
<body>
<meta http-equiv=\"REFRESH\" content=\"0;url=meebo.php\">
</body>";
?>
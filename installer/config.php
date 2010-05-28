<title>Crystal Mail Installer :: Step 2 :: Configuration</title>
<form action="index.php" method="post">
<input type="hidden" name="_step" value="2" />
<?php
// also load the default config to fill in the fields
$RCI->load_defaults();

// register these boolean fields
$RCI->bool_config_props = array(
	'ip_check' => 1,
    'enable_caching' => 1,
	'enable_spellcheck' => 1,
	'auto_create_user' => 1,
	'smtp_log' => 1,
	'prefer_html' => 1,
	'preview_pane' => 1,
	'htmleditor' => 1,
	'debug_level' => 1,
	'smtp_user_u' => 1,
);

// allow the current user to get to the next step
$_SESSION['allowinstaller'] = true;

if (!empty($_POST['submit'])) {
	$textbox = new html_textarea(array('rows' => 16, 'cols' => 60, 'class' => "configfile"));

	echo '<div><em>main.inc.php (<a href="index.php?_getfile=main">download</a>)</em></div>';
	echo $textbox->show(($_SESSION['main.inc.php'] = $RCI->create_config('main')));

	echo '<div style="margin-top:1em"><em>db.inc.php (<a href="index.php?_getfile=db">download</a>)</em></div>';
	echo $textbox->show($_SESSION['db.inc.php'] = $RCI->create_config('db'));

	echo '<p class="hint">Of course there are more options to configure.
	Have a look at the config files or visit <a href="http://trac.roundcube.net/wiki/Howto_Config">Howto_Config</a> to find out.</p>';

	echo '<p><input type="button" onclick="location.href=\'./index.php?_step=3\'" value="CONTINUE" /></p>';

	// echo '<style type="text/css"> .configblock { display:none } </style>';
	echo "\n<hr style='margin-bottom:1.6em' />\n";
}

// Function to create a new random token
// e.g. createToken('UG8D-', 3, 4)
// Might produce: UG8D-6T8Y-FCK7-09PL
function createToken($tokenprefix, $sections, $sectionlength) {
	// Declare salt and prefix
	$token .= $tokenprefix;
	$salt = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!@$%^&*';

	// Prepare randomizer
	srand((double) microtime() * 1000000);

	// Create the token
	for($i = 0; $i < $sections; $i++) {
	for($n = 0; $n < $sectionlength; $n++) {
		$token.=substr($salt, rand() % strlen($salt), 1);
	}

	if($i < ($sections - 1)){ $token .= '-'; } 
	}

	// Return the token
	return $token;
}
?>
<form action="index.php" method="post">
<input type="hidden" name="_step" value="2" />
<div id="rounded">
	<div id="button">
		<input type="submit" value="Verify">
	</div>
	<br />
	<div id="impatient">
		<p>FOR THE IMPATIENT: It is possible to just enter the "Connection & Server" information and run Crystal Webmail without any other configuration.</p>
		<p>It is recommended to be familiar with each of the options though.</p>
	</div>
	<br /><br />

	
<form name="form" method="post" action="?action=install">


<fieldset>
<legend>Connection & Server Configuration</legend>
<p class="propname">NOTE: Additional configuration options are available through the main.inc.php in the Crystal Mail config directory. This template is only used to provide an initial configuration to get Crystal mail installed and running.</p><br />

<dl class="configblock">
	<dt class="propname">Database Configuration</dt>
	<div class="hint">Description: Enter the mail host to be used for login. If left blank, a text box will be provided during login so the user can enter their host.</div>
	<dd>
		<?php
			require_once 'MDB2.php';

			$supported_dbs = array('MySQL' => 'mysql', 'MySQLi' => 'mysqli',
			    'PgSQL' => 'pgsql', 'SQLite' => 'sqlite');

			$select_dbtype = new html_select(array('name' => '_dbtype', 'id' => "cfgdbtype"));
			foreach ($supported_dbs AS $database => $ext) {
	    		if (extension_loaded($ext)) {
			        $select_dbtype->add($database, $ext);
			    }
			}

			$input_dbhost = new html_inputfield(array('name' => '_dbhost', 'size' => 20, 'id' => "cfgdbhost"));
			$input_dbname = new html_inputfield(array('name' => '_dbname', 'size' => 20, 'id' => "cfgdbname"));
			$input_dbuser = new html_inputfield(array('name' => '_dbuser', 'size' => 20, 'id' => "cfgdbuser"));
			$input_dbpass = new html_passwordfield(array('name' => '_dbpass', 'size' => 20, 'id' => "cfgdbpass"));

			$dsnw = MDB2::parseDSN($RCI->getprop('db_dsnw'));

			echo $select_dbtype->show($RCI->is_post ? $_POST['_dbtype'] : $dsnw['phptype']);
			echo '<label for="cfgdbtype">Database type Note: Only databases that were detected will be displayed.</label><br />';
			echo $input_dbhost->show($RCI->is_post ? $_POST['_dbhost'] : $dsnw['hostspec']);
			echo '<label for="cfgdbhost">Database server (omit for sqlite)</label><br />';
			echo $input_dbname->show($RCI->is_post ? $_POST['_dbname'] : $dsnw['database']);
			echo '<label for="cfgdbname">Database name (use absolute path and filename for sqlite)</label><br />';
			echo $input_dbuser->show($RCI->is_post ? $_POST['_dbuser'] : $dsnw['username']);
			echo '<label for="cfgdbuser">Database user name (needs write permissions)(omit for sqlite)</label><br />';
			echo $input_dbpass->show($RCI->is_post ? $_POST['_dbpass'] : $dsnw['password']);
			echo '<label for="cfgdbpass">Database password (omit for sqlite)</label><br />';
		?>
	</dd>
	<dt class="propname">Mail Host</dt>
	<div class="hint">Description: Enter the mail host to be used for login. If left blank, a text box will be provided during login so the user can enter their host.</div>
	<div class="hint">If the server utilizes encryption, add SSL or TLS to the beginning of the host. i.e. ssl://mail.excample.com</div>
	<div class="hint">Note: Multiple hosts can be added through the admin panel and a dropdown box will be created upon login to choose from the host list.</div>
	<dd>
		<div id="defaulthostlist">
		<?php
			$text_imaphost = new html_inputfield(array('name' => '_default_host[]', 'size' => 30));
			$default_hosts = $RCI->get_hostlist();

			if (empty($default_hosts))
	  			$default_hosts = array('');

	  			$i = 0;
	  			foreach ($default_hosts as $host) {
		    		echo '<div id="defaulthostentry'.$i.'">' . $text_imaphost->show($host);
			  	if ($i++ > 0)
				    echo '<a href="#" onclick="removehostfield(this.parentNode);return false" class="removelink" title="Remove this entry">remove</a>';
			    	echo '</div>';
	  			}
		?>
		</div>
		<div><a href="javascript:addhostfield()" class="addlink" title="Add another field">add</a></div>
	</dd>
	<dt class="propname">Mail Host Port</dt>
	<div class="hint">Description: Enter the mail host port number.</div>
	<dd>
		<?php
			$text_imapport = new html_inputfield(array('name' => '_default_port', 'size' => 6, 'id' => "cfgimapport"));
			echo $text_imapport->show($RCI->getprop('default_port'));
		?>
		Default IMAP port is 143
	</dd>
	<dt class="propname">IMAP Auth Type</dt>
	<div class="hint">Description: If your IMAP server requires authentication, select here..</div>
	<dd>
		<select name="imap_auth_type" id="imap_auth_type">
			<option value="check" selected="selected">Auto-detect</option>
			<option value="auth">auth (CRAM-MD5)</option>
			<option value="plain">plain (PLAIN)</option>
		</select>
	</dd>
	<dt class="propname">SMTP Host</dt>
	<div class="hint">Description: Enter the SMTP host to be used for mailing. If left blank, the PHP mail() function will be used.</div>
	<div class="hint">Note: Use %h variable as a replacement for the user's IMAP hostname.</div>
	<dd>
		<?php
			$text_smtphost = new html_inputfield(array('name' => '_smtp_server', 'size' => 30, 'id' => "cfgsmtphost"));
			echo $text_smtphost->show($RCI->getprop('smtp_server'));
		?>
	</dd>
	<dt class="propname">SMTP Host Port</dt>
	<div class="hint">Description: Enter the SMTP host port number.</div>
	<div class="hint">Note: Default port(s) are 25; 465 for SSL; 587 for submission.</div>
	<dd>
		<?php
			$text_smtpport = new html_inputfield(array('name' => '_smtp_port', 'size' => 6, 'id' => "cfgsmtpport"));
			echo $text_smtpport->show($RCI->getprop('smtp_port'));
		?>
	</dd>
	<dt class="propname">SMTP User and Password</dt>
	<div class="hint">Description: If your SMTP server requires authentication, enter it here.</div>
	<dd>
		<?php
			$check_smtpuser = new html_checkbox(array('name' => '_smtp_user_u', 'id' => "cfgsmtpuseru"));
			echo $check_smtpuser->show($RCI->getprop('smtp_user') == '%u' || $_POST['_smtp_user_u'] ? 1 : 0, array('value' => 1));
			echo "Use the current IMAP username and password for SMTP authentication.<br /><br />";

			echo "Username:\n"; 
				$text_smtpuser = new html_inputfield(array('name' => '_smtp_user', 'size' => 20, 'id' => "cfgsmtpuser"));
				echo $text_smtpuser->show($RCI->getprop('smtp_user'));
				echo "<br /><br />";
			echo "Password:\n"; 
				$text_smtppass = new html_passwordfield(array('name' => '_smtp_pass', 'size' => 20, 'id' => "cfgsmtppass"));
				echo $text_smtppass->show($RCI->getprop('smtp_pass'));
		?>
	</dd>
	<dt class="propname">SMTP Auth Type</dt>
	<div class="hint">Description: If your SMTP uses authentication, select it here.</div>
	<dd>
		<?php
	
	   		$select_smtpauth = new html_select(array('name' => '_smtp_auth_type', 'id' => "cfgsmtpauth"));
	   		$select_smtpauth->add(array('(auto)', 'PLAIN', 'DIGEST-MD5', 'CRAM-MD5', 'LOGIN'), array('0', 'PLAIN', 'DIGEST-MD5', 'CRAM-MD5', 'LOGIN'));
	   		echo $select_smtpauth->show(intval($RCI->getprop('smtp_auth_type')));
	   
		?>
	</dd>
	<dt class="propname">SMTP HELO Host</dt>
	<div class="hint">Description: If your SMTP server requires a host response for HELO or EHLO, enter it here.</div>
	<div class="hint">Note: If you leave this blank, the either the variable for server_name or localhost will be passed.</div>
	<dd>
		<input name="smtp_helo_host" size="20" id="smtp_helo_host" value="" type="text" /> 
	</dd>
	<dt class="propname">Mail Domain</dt>
	<div class="hint">Description: This will be used to form email addresses of new users.</div>
	<dd>
		<input name="mail_domain" size="20" id="mail_domain" value="example.com" type="text" /> 
	</dd>
	<dt class="propname">Username Domain</dt>
	<div class="hint">Description: If your server requires username+domain to authenticate, enter the domain here.</div>
	<dd>
		<?php
			$text_userdomain = new html_inputfield(array('name' => '_username_domain', 'size' => 30, 'id' => "cfguserdomain"));
			echo $text_userdomain->show($RCI->getprop('username_domain'));
		?>
	</dd>
	<dt class="propname">Virtual Users</dt>
	<div class="hint">Description: Path to virtual user table to resolve user names and email addresses.</div>
	<dd>
		<input name="virtuser_file" size="40" id="virtuser_file" value="" type="text" /> 
	</dd>
	<dt class="propname">Virtual Users Query</dt>
	<div class="hint">Description: Query to resolve virtual users user names and email addresses.</div>
	<div class="hint">Note: Use %u to replace the current username for login.</div>
	<div class="hint">Note 2: The query expects the first column to be email address and the second column to be an optional identity name.</div>
	<dd>
		<input name="virtuser_query" size="40" id="virtuser_query" value="" type="text" /> 
	</dd>
		
	<!-- These entries are not part of the installer but are required for the main.inc.php -->
	<input name="imap_root" size="10" id="imap_root" value="" type="hidden" />
	<input name="imap_delimiter" size="10" id="imap_delimiter" value="" type="hidden" />
</fieldset>


<div class="spacer"></div>

<fieldset>
<legend>Administrative & Security Configuration</legend>
<dl class="configblock">
	<dt class="propname">Enable Admin Panel</dt>
	<div class="hint">Description: This will enable the Administrative Panel.</div>
	<div class="hint">Note: For some people this might pose a security risk.</div>
	<dd>
		<select name="enable_admin" id="enable_admin">
			<option value="true" selected="selected">Yes, enable the admin panel.</option>
			<option value="false">No, do not enable the admin panel.</option>
		</select>
	</dd>
	<dt class="propname">Admin User and Password</dt>
	<div class="hint">Description: This will be the username and password used to login to the Administrative Panel.</div>
	<dd>
		Username: <input name="admin_user" size="20" id="admin_user" value="admin" type="text" /><br /><br />
		Password: <input name="admin_password" size="20" id="admin_password" value="admin" type="password" /> Default: admin
	</dd>
	<dt class="propname">Client IP Check</dt>
	<div class="hint">Description: This will check the client IP in the session authorization.</div>
	<dd>
		<?php
			$check_ipcheck = new html_checkbox(array('name' => '_ip_check', 'id' => "cfgipcheck"));
			echo $check_ipcheck->show(intval($RCI->getprop('ip_check')), array('value' => 1));
		?>Check for Client IP Authorization
	</dd>
	<dt class="propname">Double Authorization</dt>
	<div class="hint">Description: This will use an additional changing cookie to authorize users.</div>
	<div class="hint">NOTE: There have been problems reported with this option when enabled.</div>
	<dd>
		<select name="double_auth" id="double_auth">
			<option value="false" selected="selected">No, do not enable double authorization.</option>
			<option value="true">Yes, enable double authorization.</option>
		</select>
	</dd>
	<dt class="propname">Outgoing Email Message</dt>
	<div class="hint">Description: This will is a message that will be added to the bottom of all outgoing messages.</div>
	<div class="hint">NOTE: The message will be stored in 'footer.txt' in the config directory.</div>
	<dd>
		<textarea name="message_footer" id="message_footer" value="" cols="65" rows="10"></textarea>
	
	</dd>
	<dt class="propname">Add IP and Hostname to Header?</dt>
	<div class="hint">Description: This will add the users IP and hostname to the header of outgoing messages.</div>
	<div class="hint">Note: For some people this might pose a security risk.</div>
	<dd>
		<select name="http_received_header" id="http_received_header">
			<option value="false" selected="selected">No, do not add additional information to the header.</option>
			<option value="true">Yes, add additional information to the header.</option>
		</select>
	</dd>
	<dt class="propname">Encrypt IP and Hostname in Header?</dt>
	<div class="hint">Description: This will encrypt the users IP and hostname in the header of outgoing messages.</div>
	<dd>
		<select name="http_received_header_encrypt" id="http_received_header_encrypt">
			<option value="false" selected="selected">No, do not encrypt additional information in the header.</option>
			<option value="true">Yes, encrypt additional information in the header.</option>
		</select>
	</dd>
	<dt class="propname">Load Host Specific Configuration?</dt>
	<div class="hint">Description: This will load host specific configuration.</div>
	<div class="hint">Note: See <a href="http://www.crystalwebmail.com/documentation" target="_blank">http://www.crystalwebmail.com/documentation</a> for more details..</div>
	<dd>
		<select name="include_host_config" id="include_host_config">
			<option value="false" selected="selected">No, do not include host configuration.</option>
			<option value="true">Yes, include host configuration.</option>
		</select>
	</dd>
	<dt class="propname">Enable DNS checking for E-Mail Address Validation?</dt>
	<div class="hint">Description: This will perform a DNS lookup to validate the senders e-mail address..</div>
	<dd>
		<select name="email_dns_check" id="email_dns_check">
			<option value="false" selected="selected">No, do not enable DNS checks.</option>
			<option value="true">Yes, enable DNS checks.</option>
		</select>
	</dd>
	<dt class="propname">User Over-Rides</dt>
	<div class="hint">Description: When the options below are checked, users will be unable to change these options.</div>
	<dd>
		<table>
			<tr>
				<td><input name="skin_override" id="skin_override" value="skin" type="checkbox" /> Change Theme<br /></td>
				<td><input name="pagesize_override" id="pagesize_override" value="pagesize" type="checkbox" /> Emails per page shown<br /></td>
				<td><input name="timezone_override" id="timezone_override" value="timezone" type="checkbox" /> Timezone<br /></td>
			</tr>
				<td><input name="prefer_html_override" id="prefer_html_override" value="prefer_html" type="checkbox" /> Show HTML Messages<br /></td>
				<td><input name="show_images_override" id="show_images_override" value="show_images" type="checkbox" /> Display Inline Images<br /></td>
				<td><input name="htmleditor_override" id="htmleditor_override" value="htmleditor" type="checkbox" /> Composing Messages in HTML Format<br /></td>
			<tr>
				<td><input name="draft_autosave_override" id="draft_autosave_override" value="draft_autosave" type="checkbox" /> Draft Autosave Time<br /></td>
				<td><input name="preview_pane_override" id="preview_pane_override" value="preview_pane" type="checkbox" /> Preview Pane Display<br /></td>
				<td><input name="logout_purge_override" id="logout_purge_override" value="logout_purge" type="checkbox" /> Trash Purge Upon Logout<br /></td>
			</tr>	
			<tr>
				<td><input name="inline_images_override" id="inline_images_override" value="inline_images" type="checkbox" /> Display Images Inline<br /></td>
				<td><input name="logout_expunge_override" id="logout_expunge_override" value="logout_expunge" type="checkbox" /> Compact Inbox Upon Logout<br /></td>
				<td><input name="check_all_folders_override" id="check_all_folders_override" value="check_all_folders" type="checkbox" /> Check All Folders for New Messages<br /></td>
			</tr>	
			<tr>
				<td><input name="prettydate_override" id="prettydate_override" value="prettydate" type="checkbox" /> Date Display<br /></td>
				<td><input name="dst_active_override" id="dst_active_override" value="dst_active" type="checkbox" /> Daylight Savings Time<br /></td>
			</tr>
		</table>	
	</dd>
		
	<!-- These entries are not part of the config but are required for the main.inc.php -->
	<input name="des_key" size="30" id="des_key" value="<?php echo(createToken('', 1, 24)); ?>" type="hidden" />
	<input name="generic_message_footer" size="30" id="generic_message_footer" value="config/footer.txt" type="hidden" />
	<input name="mail_header_delimiter" size="30" id="mail_header_delimiter" value="null" type="hidden" />
</fieldset>

<div class="spacer"></div>

<fieldset>
<legend>Debugging & Logging Configuration</legend>
<dl class="configblock">
	<dt class="propname">Debug Level</dt>
	<div class="hint">Description: What level of logging should be performed.</div>
	<div class="hint">Note: Anything other than one could expose sensitive information which be be a security risk.</div>
	<dd>
		<?php
			$value = $RCI->getprop('debug_level');
			$check_debug = new html_checkbox(array('name' => '_debug_level[]'));
			echo $check_debug->show(($value & 1) ? 1 : 0 , array('value' => 1, 'id' => 'cfgdebug1'));
			echo '<label for="cfgdebug1">Log errors</label><br />';

			echo $check_debug->show(($value & 4) ? 4 : 0, array('value' => 4, 'id' => 'cfgdebug4'));
			echo '<label for="cfgdebug4">Print errors (to the browser)</label><br />';

			echo $check_debug->show(($value & 8) ? 8 : 0, array('value' => 8, 'id' => 'cfgdebug8'));
			echo '<label for="cfgdebug8">Verbose display (enables debug console)</label><br />';
		?>
	</dd>
	<dt class="propname">Log Driver</dt>
	<div class="hint">Description: What driver should be used for logging.</div>
	<dd>
		<?php
			$select_log_driver = new html_select(array('name' => '_log_driver', 'id' => "cfglogdriver"));
			$select_log_driver->add(array('file', 'syslog'), array('file', 'syslog'));
			echo $select_log_driver->show($RCI->getprop('log_driver', 'file'));
		?>
	</dd>
	<dt class="propname">Log Date Format</dt>
	<div class="hint">Description: What format should the time be in for the log files.</div>
	<div class="hint">Note: Check out <a href="http://php.net/manual/en/function.date.php" target="_blank">http://php.net/manual/en/function.date.php</a> for the various date formats.</div>
	<dd>
		<input name="admin_user" size="20" id="admin_user" value="d-M-Y H:i:s O" type="text" /> Example: [25-May-2010 17:34:14 -0400] 
	</dd>
	<dt class="propname">Syslog ID</dt>
	<div class="hint">Description: This is the name that will be used to identify log entries from Crystal Webmail.</div>
	<dd>
		<?php
			$input_syslogid = new html_inputfield(array('name' => '_syslog_id', 'size' => 30, 'id' => "cfgsyslogid"));
			echo $input_syslogid->show($RCI->getprop('syslog_id', 'crystal'));
		?>
	</dd>
	<dt class="propname">Syslog Facility</dt>
	<div class="hint">Description: when using the syslog driver, what facility should ebe used?</div>
	<div class="hint">Note: Check out <a href="http://php.net/manual/en/function.openlog.php" target="_blank">http://php.net/manual/en/function.openlog.php</a> for the possible values.</div>
	<dd>
		<?php
			$input_syslogfacility = new html_select(array('name' => '_syslog_facility', 'id' => "cfgsyslogfacility"));
			$input_syslogfacility->add('User-Level Messages', LOG_USER);
			$input_syslogfacility->add('Mail Subsystem', LOG_MAIL);
			$input_syslogfacility->add('local level 0', LOG_LOCAL0);
			$input_syslogfacility->add('local level 1', LOG_LOCAL1);
			$input_syslogfacility->add('local level 2', LOG_LOCAL2);
			$input_syslogfacility->add('local level 3', LOG_LOCAL3);
			$input_syslogfacility->add('local level 4', LOG_LOCAL4);
			$input_syslogfacility->add('local level 5', LOG_LOCAL5);
			$input_syslogfacility->add('local level 6', LOG_LOCAL6);
			$input_syslogfacility->add('local level 7', LOG_LOCAL7);
			echo $input_syslogfacility->show($RCI->getprop('syslog_facility'), LOG_USER);
		?>
	</dd>
	<dt class="propname">Log Folder</dt>
	<div class="hint">Description: This is the folder where log files will be stored when using the 'file' log driver.</div>
	<div class="hint">Note: This folder must have write access for the web server user. i.e. apache/www</div>
	<dd>
		<?php
			$input_logdir = new html_inputfield(array('name' => '_log_dir', 'size' => 30, 'id' => "cfglogdir"));
			echo $input_logdir->show($RCI->getprop('log_dir'));
		?>
	</dd>
	<dt class="propname">Temporary Folder</dt>
	<div class="hint">Description: This is the folder where temporary files will be stored like attachments</div>
	<div class="hint">Note: This folder must have write access for the web server user. i.e. apache/www</div>
	<dd>
		<?php
			$input_tempdir = new html_inputfield(array('name' => '_temp_dir', 'size' => 30, 'id' => "cfgtempdir"));
			echo $input_tempdir->show($RCI->getprop('temp_dir'));
		?>
	</dd>
	<dt class="propname">Log Successful Logins</dt>
	<div class="hint">Description: This will log all successful logins by users.</div>
	<dd>
		<select name="log_options" id="log_options">
			<option value="false" selected="selected">No, do not log all successful logins.</option>
			<option value="true">Yes, log all successful logins.</option>
		</select>
	</dd>
	<dt class="propname">Services Debugging</dt>
	<div class="hint">Description: When the options below are checked, all input for each service is redirected to the logs.</div>
	<div class="hint">Note: WARNING! If you enable IMAP debug, passwords are logged in clear text!</div>
	<div class="hint">Note 2: SMTP logging will log every message sent including contents of the message. This will require a significant amout of disk space over time.</div>
	<dd>
		<?php
			echo "<table><tr>";
			
			$check_smtplog = new html_checkbox(array('name' => '_smtp_log', 'id' => "cfgsmtplog"));
			echo "<td>".$check_smtplog->show(intval($RCI->getprop('smtp_log')), array('value' => 1));
			echo "SMTP Log</td>\n";

			$check_smtpdebug = new html_checkbox(array('name' => '_smtp_debug', 'id' => "cfgsmtpdebug"));
			echo "<td>".$check_smtpdebug->show(intval($RCI->getprop('smtp_debug')), array('value' => 1));
			echo "SMTP Debug</td>\n";

			$check_sqldebug = new html_checkbox(array('name' => '_sql_debug', 'id' => "cfgsqldebug"));
			echo "<td>".$check_sqldebug->show(intval($RCI->getprop('sql_debug')), array('value' => 1));
			echo "SQL Debug</td></tr>\n";

			$check_imapdebug = new html_checkbox(array('name' => '_imap_debug', 'id' => "cfgimapdebug"));
			echo "<tr><td>".$check_imapdebug->show(intval($RCI->getprop('imap_debug')), array('value' => 1));
			echo "IMAP Debug</td>\n";

			$check_ldapdebug = new html_checkbox(array('name' => '_ldap_debug', 'id' => "cfgldapdebug"));
			echo "<td>".$check_ldapdebug->show(intval($RCI->getprop('ldap_debug')), array('value' => 1));
			echo "LDAP Debug</td>\n";
			
			echo "</table></tr>";
		?>
	</dd>
</fieldset>

<div class="spacer"></div>

<fieldset>
<legend>General Configuration</legend>
<dl class="configblock">
	<dt class="propname">Plugins</dt>
	<div class="hint">Description: When the options below are checked, the associated plugins are enabled.</div>
	<div class="hint">Note: The default plugins selected have already been configured. If you enable additional ones, you may need to configure them.</div><br />
	<div class="hint">The following Plugins were found on your system:</div>
	<dd>

		<?php
		/* EXAMPLE!!!!!!!!!!!!!!!!!
			$input_skin = new html_select(array('name' => '_skin', 'id' => "cfgskin"));
			$skins=scandir("../skins");
			foreach ($skins as $skin_name){
				if (preg_match("/[^.\^.svn\^.DS_Store]/", $skin_name)) {
					$input_skin->add($skin_name, $skin_name);
				}
			}		
			echo $input_skin->show($RCI->getprop('skin'), skin);
			


			// This is for multiple selects
			$check_smtplog = new html_checkbox(array('name' => '_smtp_log', 'id' => "cfgsmtplog"));
			echo "<td>".$check_smtplog->show(intval($RCI->getprop('smtp_log')), array('value' => 1));
			echo "SMTP Log</td>\n";
		*/
		?>

		<?php
		    $plugins=scandir("../plugins"); 
			foreach ($plugins as $plugin_name){ 
				$checked = ""; 
				if (preg_match("/[^.\^.svn\^.DS_Store]/", $plugin_name)) { 
					if (in_array($plugin_name, $rcmail_config['plugins'])) 
						$checked = "checked=\"checked\"";
						$check_plugin = $check_plugin.$plugin_name;
						$check_plugin = new html_checkbox(array('name' => '_plugin_'.$plugin_name, 'id' => "cfgplugin".$plugin_name));

				} 
			}         
		?>
		<?php
		/* ORIGINAL CODE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		    $plugins=scandir("../plugins"); 
			foreach ($plugins as $plugin_name){ 
				$checked = ""; 
				if (preg_match("/[^.\^.svn\^.DS_Store]/", $plugin_name)) { 
					if (in_array($plugin_name, $rcmail_config['plugins'])) 
						$checked = "checked=\"checked\""; 
						$plugin_list .= "<input name=\"$plugin_name\" id=\"$plugin_name\"$checked value=\"$plugin_name\" type=\"checkbox\" \> $plugin_name<br />"; 
				} 
			}         
        	echo "$plugin_list";
		*/?>
	</dd>
	<dt class="propname">Automatically Create Users</dt>
	<div class="hint">Description: This will automatically create a new user once the IMAP login has suceeded.</div>
	<div class="hint">Note: It is recommended to leave this enabled otherwise only users that have logged into Crystal before will be able to login.</div>
	<dd>
		<?php
			$check_autocreate = new html_checkbox(array('name' => '_auto_create_user', 'id' => "cfgautocreate"));
			echo $check_autocreate->show(intval($RCI->getprop('auto_create_user')), array('value' => 1));
		?>Automatically Create Users (Recommended)
	</dd>
	<dt class="propname">Page Titles</dt>
	<div class="hint">Description: This is the title you want to appear in the browser window title bar.</div>
	<dd>
		<?php
			$input_prodname = new html_inputfield(array('name' => '_product_name', 'size' => 30, 'id' => "cfgprodname"));
			echo $input_prodname->show($RCI->getprop('product_name'));
		?>
	</dd>
	<dt class="propname">Folder Names: Drafts</dt>
	<div class="hint">Description: This is the name of the folder used to store draft messages.</div>
	<div class="hint">Note: If left blank, draft messages will not be stored.</div>
	<dd>
		<?php
			$text_draftsmbox = new html_inputfield(array('name' => '_drafts_mbox', 'size' => 20, 'id' => "cfgdraftsmbox"));
			echo $text_draftsmbox->show($RCI->getprop('drafts_mbox'));
		?>
	</dd>
	<dt class="propname">Folder Names: Junk</dt>
	<div class="hint">Description: This is the name of the folder used to store junk messages.</div>
	<div class="hint">Note: If left blank, junk messages will not be stored.</div>
	<dd>
		<?php
			$text_junkmbox = new html_inputfield(array('name' => '_junk_mbox', 'size' => 20, 'id' => "cfgjunkmbox"));
			echo $text_junkmbox->show($RCI->getprop('junk_mbox'));
		?>
	</dd>
	<dt class="propname">Folder Names: Archive</dt>
	<div class="hint">Description: This is the name of the folder used to store archive messages.</div>
	<div class="hint">Note: If left blank, archive messages will not be stored.</div>
	<dd>
		<?php
			$text_archivembox = new html_inputfield(array('name' => '_archive_mbox', 'size' => 20, 'id' => "cfgarchivembox"));
			echo $text_archivembox->show($RCI->getprop('archive_mbox'));
		?>
	</dd>
	<dt class="propname">Folder Names: Sent</dt>
	<div class="hint">Description: This is the name of the folder used to store sent messages.</div>
	<div class="hint">Note: If left blank, sent messages will not be stored.</div>
	<dd>
		<?php
			$text_sentmbox = new html_inputfield(array('name' => '_sent_mbox', 'size' => 20, 'id' => "cfgsentmbox"));
			echo $text_sentmbox->show($RCI->getprop('sent_mbox'));
		?>
	</dd>
	<dt class="propname">Folder Names: Trash</dt>
	<div class="hint">Description: This is the name of the folder used to store trash messages.</div>
	<div class="hint">Note: If left blank, trash messages will not be stored.</div>
	<dd>
		<?php
			$text_trashmbox = new html_inputfield(array('name' => '_trash_mbox', 'size' => 20, 'id' => "cfgtrashmbox"));
			echo $text_trashmbox->show($RCI->getprop('trash_mbox'));
		?>
	</dd>
	<dt class="propname">Automaticvally Create Default Folders</dt>
	<div class="hint">Description: This will automatically create the default IMAP folders after login.</div>
	<dd>
		<select name="create_default_folders" id="create_default_folders">
			<option value="true" selected="selected">Yes, automatically create IMAP folders upon login.</option>
			<option value="false">No, do not automatically create IMAP folders upon login.</option>
		</select>
	</dd>
	<dt class="propname">Protect Default IMAP Folders</dt>
	<div class="hint">Description: When this option is enabled, it will prevent users from renaming, deleting, or subscription changes to default IMAP folders.</div>
	<dd>
		<select name="protect_default_folders" id="protect_default_folders">
			<option value="true" selected="selected">Yes, protect the default IMAP folders.</option>
			<option value="false">No, do not protect the default IMAP folders.</option>
		</select>
	</dd>
	<dt class="propname">Delivery Notification</dt>
	<div class="hint">Description: Choose the default behavior when a delivery notification (read receipt) is requested.</div>
	<dd>
		<?php
			$select_mdnreq = new html_select(array('name' => '_mdn_requests', 'id' => "cfgmdnreq"));
			$select_mdnreq->add(array('Ask The User', 'Send Automatically', 'Ignore'), array(0, 1, 2));
			echo $select_mdnreq->show(intval($RCI->getprop('mdn_requests')));
		?>
	</dd>
	<dt class="propname">Identities</dt>
	<div class="hint">Description: This will determine to what extent a users identity may be modified.</div>
	<dd>
		<?php
			$input_ilevel = new html_select(array('name' => '_identities_level', 'id' => "cfgidentitieslevel"));
			$input_ilevel->add('One identity with possibility to edit all params but not email address.', 3);
			$input_ilevel->add('One identity with possibility to edit all params.', 2);
			$input_ilevel->add('Many identities with possibility to edit all params but not email address.', 1);
			$input_ilevel->add('Many identities with possibility to edit all params.', 0);
			echo $input_ilevel->show($RCI->getprop('identities_level'), 0);
		?>
	</dd>
	
	<!-- These entries are not part of the config but are required for the main.inc.php -->
	<input name="check_all_folders" size="5" id="check_all_folders" value="true" type="hidden" />
</fieldset>



<div class="spacer"></div>

<fieldset>
<legend>Miscellaneous Configuration</legend>
<dl class="configblock">
	<dt class="propname">Caching</dt>
	<div class="hint">Description: When this option is enabled, messages will be cached in the local database.</div>
	<div class="hint">Note: This is recommended to improve speed if your IMAP server resides on a different host.</div>
	<dd>
		<?php
			$check_caching = new html_checkbox(array('name' => '_enable_caching', 'id' => "cfgcache"));
			echo $check_caching->show(intval($RCI->getprop('enable_caching')), array('value' => 1));
		?>
		Enable Message Caching
	</dd>
	<dt class="propname">Message Cache Lifetime</dt>
	<div class="hint">Description: When message caching is enabled, this will determine the length of time that the cach is valid for.</div>
	<dd>
		<input name="message_cache_lifetime" size="5" id="message_cache_lifetime" value="10" type="text" />
		<select name="message_cache_lifetime_increment" id="message_cache_lifetime_increment">
			<option value="s">Seconds (s)</option>
			<option value="m">Minutes (m)</option>
			<option value="h">Hours (h)</option>
			<option value="d" selected="selected">Days (d)</option>
			<option value="w">Weeks (w)</option>
		</select>
	</dd>
	<dt class="propname">Force HTTPS</dt>
	<div class="hint">Description: When this option is enabled, Crystal will force the connection over HTTPS.</div>
	<dd>
		<select name="force_https" id="force_https">
			<option value="false" selected="selected">No, allow un-secure connections.</option>
			<option value="true">Yes, only allow secure connections.</option>
		</select>
	</dd>
	<dt class="propname">Sendmail Delay</dt>
	<div class="hint">Description: This options sets the number of seconds a user must wait between sending emails.</div>
	<dd>
		<input name="sendmail_delay" size="10" id="sendmail_delay" value="5" type="text" />
	</dd>
	<dt class="propname">Session Lifetime</dt>
	<div class="hint">Description: This options sets the number of seconds a users session is active.</div>
	<div class="hint">Note: The value must be greater than the 'Keep Alive'.</div>
	<dd>
		<input name="session_lifetime" size="10" id="session_lifetime" value="15" type="text" />
	</dd>
	<dt class="propname">Keep Alive Minimum</dt>
	<div class="hint">Description: This options sets the minimum number of seconds a users keep alive is active.</div>
	<div class="hint">Note: The value must be lesser than the 'Session Lifetime'.</div>
	<dd>
		<input name="min_keep_alive" size="10" id="min_keep_alive" value="5" type="text" />
	</dd>
	<dt class="propname">Keep Alive</dt>
	<div class="hint">Description: This options sets the number of seconds a users keep alive is active.</div>
	<div class="hint">Note: The value must be greater than or equal to the 'Keep Alive Minimum'.</div>
	<dd>
		<input name="keep_alive" size="10" id="keep_alive" value="10" type="text" />
	</dd>
	<dt class="propname">User Agent</dt>
	<div class="hint">Description: This options sets the user agent that is added to the message headers.</div>
	<dd>
		<input name="useragent" size="30" id="useragent" value="Crystal Webmail" type="text" />
	</dd>
	<dt class="propname">Zero Quota</dt>
	<div class="hint">Description: This setting allows for using zero (0) as a no limit indicator.</div>
	<div class="hint">Description: If your system uses 0 to signify 'no limit', then set this option to 'true'.</div>
	<dd>
		<select name="quota_zero_as_unlimited" id="quota_zero_as_unlimited">
			<option value="false" selected="selected">No, my system does not use zero(0) to signify 'no limit'.</option>
			<option value="true">Yes, my system does use zero(0) to signify 'no limit'.</option>
		</select>
	</dd>
	<dt class="propname">Default Character Set</dt>
	<div class="hint">Description: This options sets the default character set to use as a fallback for message decoding.</div>
	<dd>
		<input name="default_charset" size="20" id="default_charset" value="ISO-8859-1" type="text" />
	</dd>
	<dt class="propname">Spell Checker Enabled?</dt>
	<div class="hint">Description: This setting allows you to use the built in spell checker from GoogieSpell.</div>
	<div class="hint">Note: Since Googie spell utilizes https for transmission, your PHP installation requires OpenSSL support for this to work.</div>
	<div class="hint">Note 2: The OpenSSL dependency should have been identified on the previous page.</div>
	<dd>
		<?php
			$check_spell = new html_checkbox(array('name' => '_enable_spellcheck', 'id' => "cfgspellcheck"));
			echo $check_spell->show(intval($RCI->getprop('enable_spellcheck')), array('value' => 1));
		?>
		Enable Spell Checker
	</dd>
	<dt class="propname">Spell Checker Choice</dt>
	<div class="hint">Description: This setting allows you to choose which spell checker to use.</div>
	<div class="hint">Note: If you use Nox Spell, choose googie.</div>
	<div class="hint">Note 2: If you use PSpell, ensure the PSpell extensions are installed for PHP.</div>
	<dd>
		<?php
			$select_spell = new html_select(array('name' => '_spellcheck_engine', 'id' => "cfgspellcheckengine"));
			if (extension_loaded('pspell'))
	  			$select_spell->add('Googie', 'googie');
	  			$select_spell->add('pspell', 'pspell');
	  		echo $select_spell->show($RCI->is_post ? $_POST['_spellcheck_engine'] : 'pspell');
	  	?>
	</dd>
	<dt class="propname">Spell Check URI</dt>
	<div class="hint">Description: If you have a locally installed Nox Spell Server, specify the URI to call it here.</div>
	<div class="hint">Note: If you use Googie, leave this blank.</div>
	<dd>
		<input name="spellcheck_uri" size="50" id="spellcheck_uri" value="" type="text" />
	</dd>
	<dt class="propname">Session Domain</dt>
	<div class="hint">Description: This is the domain that is added to the session ID.</div>
	<dd>
		<input name="session_domain" size="50" id="session_domain" value="" type="text" /> Example: example.com
	</dd>
	<dt class="propname">Delete Always</dt>
	<div class="hint">Description: This will allow for messages to always be marked as deleted even if moving to the "Trash" fails.</div>
	<div class="hint">Note: Some setups require this if users do not have a trash folder or if they are over their quota.</div>
	<dd>
		<select name="delete_always" id="delete_always">
			<option value="false" selected="selected">No, do not mark messages as deleted.</option>
			<option value="true">Yes, always mark messages as deleted.</option>
		</select>
	</dd>
	<dt class="propname">Draft Autosave</dt>
	<div class="hint">Description: This is the length of time in seconds before a draft message is save to the 'Drafts' folder.</div>
	<dd>
		<?php
			$select_autosave = new html_select(array('name' => '_draft_autosave', 'id' => 'cfgautosave'));
			$select_autosave->add('never', 0);
			foreach (array(1, 3, 5, 10, 15, 20) as $i => $min)
	  			$select_autosave->add("$min min", $min*60);
				echo $select_autosave->show(intval($RCI->getprop('draft_autosave')));
	  	?>
	</dd>
	<dt class="propname">Clear Trash On Logout</dt>
	<div class="hint">Description: If set, this option will clear a users trash folder when they logout.</div>
	<div class="hint">Note: This setting can be overridden by the user.</div>
	<dd>
		<select name="logout_purge" id="logout_purge">
			<option value="false" selected="selected">No, do not empty the trash on logout.</option>
			<option value="true">Yes, empty the trash on logout.</option>
		</select>
	</dd>
	<dt class="propname">Compact Inbox On Logout</dt>
	<div class="hint">Description: If set, this option will compcat the users inbox folder when they logout.</div>
	<div class="hint">Note: This setting can be overridden by the user.</div>
	<dd>
		<select name="logout_expunge" id="logout_expunge">
			<option value="false" selected="selected">No, do not archive the inbox on logout.</option>
			<option value="true">Yes, archive the inbox on logout.</option>
		</select>
	</dd>
	<dt class="propname">Flag For Deletion</dt>
	<div class="hint">Description: If set, this option will immediately remove flagged messages for deletion.</div>
	<dd>
		<select name="flag_for_deletion" id="flag_for_deletion">
			<option value="false" selected="selected">No, do not flag for deletion.</option>
			<option value="true">Yes, flag for deletion.</option>
		</select>
	</dd>
	<dt class="propname">Mime Magic Database</dt>
	<div class="hint">Description: This is the location of the mime magic database.</div>
	<dd>
		<input name="mime_magic" size="30" id="mime_magic" value="/usr/share/misc/magic" type="text" /> 
	</dd>
	
	<!-- These entries are not part of the config but are required for the main.inc.php -->
	<input name="skin_include_php" size="5" id="skin_include_php" value="true" type="hidden" />
	<input name="spellcheck_languages" size="50" id="spellcheck_languages" value="" type="hidden" />
</fieldset>





<div class="spacer"></div>


<fieldset>
<legend>Appearance Configuration</legend>
<dl class="configblock">
	<dt class="propname">Theme</dt>
	<div class="hint">Description: Choose the theme Crystal should use.</div>
	<dd>
		<?php
			$input_skin = new html_select(array('name' => '_skin', 'id' => "cfgskin"));
			$skins=scandir("../skins");
			foreach ($skins as $skin_name){
				if (preg_match("/[^.\^.svn\^.DS_Store]/", $skin_name)) {
					$input_skin->add($skin_name, $skin_name);
				}
			}		
			echo $input_skin->show($RCI->getprop('skin'), skin);
		?>
	</dd>
	<dt class="propname">Email Columns</dt>
	<div class="hint">Description: This is a list of the columns that should be displayed when viewing the inbox.</div>
	<dd>
		<table>
			<tr>
				<td><input name="cols_var1" id="cols_var1" value="subject" type="checkbox" checked="yes" /> Subject<br /></td>
				<td><input name="cols_var2" id="cols_var2" value="from" type="checkbox" checked="yes" /> From<br /></td>
				<td><input name="cols_var3" id="cols_var3" value="to" type="checkbox" /> To<br /></td>
				<td><input name="cols_var4" id="cols_var4" value="cc" type="checkbox" /> CC<br /></td>
				<td><input name="cols_var5" id="cols_var5" value="attachment" type="checkbox" checked="yes" /> Attachment<br /></td>
			</tr>
			<tr>
				<td><input name="cols_var6" id="cols_var6" value="date" type="checkbox" checked="yes" /> Date<br /></td>
				<td><input name="cols_var7" id="cols_var7" value="size" type="checkbox" checked="yes" /> Size<br /></td>
				<td><input name="cols_var8" id="cols_var8" value="flag" type="checkbox" checked="yes" /> Flag<br /></td>
				<td><input name="cols_var9" id="cols_var9" value="replyto" type="checkbox" /> Reply-To<br /></td>
			</tr>
		</table>
	</dd>
	<dt class="propname">Date: Short</dt>
	<div class="hint">Description: This is the display format for the date in a short format.</div>
	<dd>
		<input name="date_short" size="10" id="date_short" value="D H:i" type="text" /> 
	</dd>
	<dt class="propname">Date: Long</dt>
	<div class="hint">Description: This is the display format for the date in a long format.</div>
	<dd>
		<input name="date_long" size="10" id="date_long" value="d.m.Y H:i" type="text" /> 
	</dd>
	<dt class="propname">Date: Today</dt>
	<div class="hint">Description: This is the display format for the date format today.</div>
	<dd>
		<input name="date_today" size="10" id="date_today" value="H:i" type="text" /> 
	</dd>
	<dt class="propname">Date: Date Only</dt>
	<div class="hint">Description: This is the display format for the date only and does not include time.</div>
	<dd>
		<input name="date_only" size="10" id="date_only" value="d F Y" type="text" /> 
	</dd>
	<dt class="propname">Default IMAP Folders</dt>
	<div class="hint">Description: This is a list of the default IMAP folders that will be displayed when viewing the inbox.</div>
	<dd>
		<table>
			<tr>
				<td><input name="default_imap_folders_var1" id="default_imap_folders_var1" value="INBOX" type="checkbox" checked="yes" /> Inbox<br /></td>
				<td><input name="default_imap_folders_var2" id="default_imap_folders_var2" value="Drafts" type="checkbox" checked="yes" /> Drafts<br /></td>
				<td><input name="default_imap_folders_var3" id="default_imap_folders_var3" value="Sent" type="checkbox" checked="yes" /> Sent<br /></td>
			</tr>
			<tr>
				<td><input name="default_imap_folders_var4" id="default_imap_folders_var4" value="Junk" type="checkbox" checked="yes" /> Junk<br /></td>
				<td><input name="default_imap_folders_var5" id="default_imap_folders_var5" value="Archive" type="checkbox" checked="yes" /> Archive<br /></td>
				<td><input name="default_imap_folders_var6" id="default_imap_folders_var6" value="Trash" type="checkbox" checked="yes" /> Trash<br /></td>
			</tr>
		</table>
	</dd>
	<dt class="propname">Display Next Message After Deletion</dt>
	<div class="hint">Description: If set, this option will display the next message after deleting a message.</div>
	<dd>
		<select name="display_next" id="display_next">
			<option value="false" selected="selected">No, do not display the next message after deletion.</option>
			<option value="true">Yes, display the next message after deletion.</option>
		</select>
	</dd>
	<dt class="propname">Sort Messages By?</dt>
	<div class="hint">Description: This option chooses whether messages should be sorted by index number or date.</div>
	<dd>
		<select name="index_sort" id="index_sort">
			<option value="true" selected="selected">Yes, sort messages by index.</option>
			<option value="false">No, sort messages by date.</option>
		</select>
	</dd>
	<dt class="propname">Display Images Inline</dt>
	<div class="hint">Description: This option chooses whether images should be displayed inline or below the message body.</div>
	<dd>
		<select name="inline_images" id="inline_images">
			<option value="true" selected="selected">Yes, display the images inline.</option>
			<option value="false">No, display the images after the message body.</option>
		</select>
	</dd>
	<dt class="propname">Display Remote Images</dt>
	<div class="hint">Description: This option chooses whether iremote images should be displayed.</div>
	<dd>
		<select name="show_images" id="show_images">
			<option value="0" selected="selected">Ask if remote images should be displayed.</option>
			<option value="1">Ask if remote images should be displayed if sender is not in address book.</option>
			<option value="2">Always display remote images.</option>
		</select>
	</dd>
	<dt class="propname">Attachment Encoding</dt>
	<div class="hint">Description: This option chooses the encoding format used for attachments.</div>
	<dd>
		<?php
			$select_param_folding = new html_select(array('name' => '_mime_param_folding', 'id' => "cfgmimeparamfolding"));
			$select_param_folding->add('Full RFC 2231 (Crystal, Thunderbird)', '0');
			$select_param_folding->add('RFC 2047/2231 (MS Outlook, OE)', '1');
			$select_param_folding->add('Full RFC 2047 (deprecated)', '2');
			echo $select_param_folding->show(intval($RCI->getprop('mime_param_folding')));
		?>
	</dd>
	<dt class="propname">Show Deleted Messages</dt>
	<div class="hint">Description: This option chooses whether deleted messages are still shown in the users inbox.</div>
	<dd>
		<select name="skip_deleted" id="skip_deleted">
			<option value="true" selected="selected">Do not show deleted messages.</option>
			<option value="false">Show deleted messages.</option>
		</select>
	</dd>
	<dt class="propname">Mark Messages As Read When Deleted</dt>
	<div class="hint">Description: This option chooses whether read messages are considered read upon deletion.</div>
	<dd>
		<select name="read_when_deleted" id="read_when_deleted">
			<option value="false" selected="selected">No, do not mark messages as read when they are deleted.</option>
			<option value="true">Yes, mark messages as read when they are deleted.</option>
		</select>
	</dd>
	<dt class="propname">Preview Pane</dt>
	<div class="hint">Description: This option chooses whether a preview pane is displayed for viewing messages.</div>
	<dd>
		<?php
			$check_prevpane = new html_checkbox(array('name' => '_preview_pane', 'id' => "cfgprevpane", 'value' => 1));
			echo $check_prevpane->show(intval($RCI->getprop('preview_pane')));
		?>Enable Preview Pane
	</dd>
	<dt class="propname">Focus Window When New Message Arrives</dt>
	<div class="hint">Description: This bring the browser window/tab to your attention when a new message arrives.</div>
	<dd>
		<select name="focus_on_new_message" id="focus_on_new_message">
			<option value="true" selected="selected">Yes, focus window.</option>
			<option value="false">No, do not focus window.</option>
		</select>
	</dd>
	<dt class="propname">Default Sort Column</dt>
	<div class="hint">Description: Choose the default column that messages should be sorted by.</div>
	<dd>
		<select name="message_sort_col" id="message_sort_col">
			<option value="date" selected="selected">date</option>
			<option value="subject">subject</option>
			<option value="from">from</option>
			<option value="to">to</option>
			<option value="cc">cc</option>
			<option value="attachment">attachment</option>
			<option value="size">size</option>
			<option value="flag">flag</option>
		</select>
	</dd>
	<dt class="propname">Default Sort Order</dt>
	<div class="hint">Description: This is the efault sort order for messages.</div>
	<dd>
		<select name="message_sort_order" id="message_sort_order">
			<option value="DESC" selected="selected">Descending</option>
			<option value="ASC">Ascending</option>
		</select>
	</dd>
	<dt class="propname">Default Number of Messages Displayed</dt>
	<div class="hint">Description: This number determines the number of messages displayed per page when viewing message folders.</div>
	<div class="hint">Note: This setting can be overridden by the user.</div>
	<dd>
		<?php
			$input_pagesize = new html_inputfield(array('name' => '_pagesize', 'size' => 6, 'id' => "cfgpagesize"));
			echo $input_pagesize->show($RCI->getprop('pagesize'));
		?>
	</dd>
	<dt class="propname">Max Number of Messages Displayed</dt>
	<div class="hint">Description: This number determines the maximum number of messages displayed per page when viewing message folders.</div>
	<dd>
		<input name="max_pagesize" size="5" id="max_pagesize" value="200" type="text" />
	</dd>
	<dt class="propname">HTML Messages</dt>
	<div class="hint">Description: This will display HTML messages by default.</div>
	<div class="hint">Note: This setting can be overridden by the user.</div>
	<dd>
		<?php
			$check_htmlview = new html_checkbox(array('name' => '_prefer_html', 'id' => "cfghtmlview", 'value' => 1));
			echo $check_htmlview->show(intval($RCI->getprop('prefer_html')));
		?>Allow HTML Messages
	</dd>
	<dt class="propname">HTML Editor</dt>
	<div class="hint">Description: This will allow messages to be edited using the HTML editor.</div>
	<div class="hint">Note: This setting can be overridden by the user.</div>
	<dd>
		<?php
			$check_htmlcomp = new html_checkbox(array('name' => '_htmleditor', 'id' => "cfghtmlcompose", 'value' => 1));
			echo $check_htmlcomp->show(intval($RCI->getprop('htmleditor')));
		?>Use the HTML Editor
	</dd>
	<!-- These entries are not part of the config but are required for the main.inc.php -->
	<input name="language" size="5" id="language" value="" type="hidden" />
	<input name="timezone" size="5" id="timezone" value="auto" type="hidden" />
	<input name="dst_active" size="5" id="dst_active" value="auto" type="hidden" />
</fieldset>




<div class="spacer"></div>

<fieldset>
<legend>Address Book Configuration</legend>
<dl class="configblock">
	<dt class="propname">What type of Address Books to Use</dt>
	<div class="hint">Description: You can choose two types of address books currently; SQL and/or LDAP.</div>
	<dd>
		<select name="address_book_type" id="address_book_type">
			<option value="sql" selected="selected">SQL Only</option>
			<option value="ldap">LDAP Only</option>
			<option value="sql,ldap">SQL & LDAP</option>
		</select>
	</dd>
	<dt class="propname">Name of LDAP Address Books</dt>
	<div class="hint">Description: What should this address book be called?</div>
	<dd>
		<input name="ldap_name" size="30" id="ldap_name" value="" type="text" /> Example: My Contacts<br />
	</dd>
	<dt class="propname">LDAP Server</dt>
	<div class="hint">Description: List the hostname(s) for the LDAP server(s).</div>
	<dd>
		<input name="ldap_hosts" size="50" id="ldap_hosts" value="" type="text" />Example: ldap1.example.com,ldap2.example.com
	</dd>
	<dt class="propname">LDAP Port</dt>
	<div class="hint">Description: LDAP port to connect on.</div>
	<dd>
		<input name="ldap_port" size="6" id="ldap_port" value="" type="text" />Example: 389
	</dd>
	<dt class="propname">LDAP Version</dt>
	<div class="hint">Description: What version of LDAP should be used?</div>
	<dd>
		<select name="ldap_version" id="ldap_version">
			<option value="2" selected="selected">LDAP Version 2</option>
			<option value="3">LDAP Version 3.</option>
		</select>
	</dd>
	<dt class="propname">LDAP StartTLS</dt>
	<div class="hint">Description: Enable this is your server requires StartTLS authentication.</div>
	<dd>
		<select name="ldap_tls" id="ldap_tls">
			<option value="false" selected="selected">No, do not use StartTLS.</option>
			<option value="true">Yes, use StartTLS.</option>
		</select>
	</dd>
	<dt class="propname">LDAP Search Scope</dt>
	<div class="hint">Description: Determine what level searches should go to for the LDAP server.</div>
	<dd>
		<select name="ldap_scope" id="ldap_scope">
			<option value="sub" selected="selected">SUB</option>
			<option value="base">BASE</option>
			<option value="list">LIST</option>
		</select>
	</dd>
	<dt class="propname">LDAP Search Type</dt>
	<div class="hint">Description: Does the LDAP server allow for wildcard (fuzzy) searches?</div>
	<dd>
		<select name="ldap_fuzzy" id="ldap_fuzzy">
			<option value="true" selected="selected">Yes, the LDAP server supports wildcards.</option>
			<option value="false">No, the LDAP server does not support wildcards.</option>
		</select>
	</dd>
	<dt class="propname">Bind As User</dt>
	<div class="hint">Note: This will use the users username and password as well as the base DN to authenticate.</div>
	<dd>
		<select name="ldap_userbind" id="ldap_userbind">
			<option value="false" selected="selected">No, do not bind as user.</option>
			<option value="true">Yes, bind as user.</option>
		</select><br /><br />
	</dd>
	<dt class="propname">Base DN</dt>
	<div class="hint">Description: Base DN of LDAP server.</div>
	<dd>
		<input name="ldap_base" size="50" id="ldap_base" value="" type="text" />Example: ou=people,dc=example,dc=com
	</dd>
	<dt class="propname">Bind DN</dt>
	<div class="hint">Description: Bind DN of LDAP server.</div>
	<dd>
		<input name="ldap_bind" size="50" id="ldap_bind" value="" type="text" />Example: cn=search,dc=example,dc=com
	</dd>
	<dt class="propname">Bind Password</dt>
	<div class="hint">Description: Bind Password of LDAP server.</div>
	<dd>
		<input name="ldap_password" size="50" id="ldap_password" value="" type="password" />
	</dd>
	<dt class="propname">Address Book Writeable?</dt>
	<div class="hint">Description: Do users have write access to the address book?</div>
	<dd>
		<select name=ldap_writeable" id="ldap_writeable">
				<option value="false" selected="selected">No, LDAP is not writeable.</option>
				<option value="true">Yes, LDAP is writeable.</option>
		</select><br /><br />
	</dd>
	<dt class="propname">LDAP Object Classes</dt>
	<div class="hint">Description: Provide a comma seperated list of LDAP object classes to be used when creating a new address book entry.</div>
	<dd>
		<input name="ldap_objectclass" size="50" id="ldap_objectclass" value="" type="text" /> Example: top, inetOrgPerson
	</dd>
	<dt class="propname">LDAP Required Fields</dt>
	<div class="hint">Description: Provide a comma seperated list of LDAP required fields from the object classes above when creating a new address book entry.</div>
	<div class="hint">Note: This can also include additional fields not required by the object classes.</div>
	<dd>
		<input name="ldap_required" size="50" id="ldap_required" value="" type="text" /> Example: cn, sn, mail
	</dd>
	<dt class="propname">LDAP Search Fields</dt>
	<div class="hint">Description: Provide a comma seperated list of LDAP search fields for searching the address book.</div>
	<dd>
		<input name="ldap_search" size="50" id="ldap_search" value="" type="text" /> Example: cn, mail
	</dd>
	<dt class="propname">LDAP RDN</dt>
	<div class="hint">Description: This is the RDN that is used for new entries. This field must be one of the searchable fields.</div>
	<div class="hint">Note: The base DN is appended to the RDN to insert into the address book.</div>
	<dd>
		<input name="ldap_rdn" size="50" id="ldap_rdn" value="" type="text" /> Example: mail
	</dd>
	<dt class="propname">LDAP New Entry: First Name</dt>
	<div class="hint">Description: This is the attribute used to store the First Name of address book entries.</div>
	<dd>
		<input name="ldap_fname" size="50" id="ldap_fname" value="" type="text" /> Example: gn
	</dd>	
	<dt class="propname">LDAP New Entry: Last Name</dt>
	<div class="hint">Description: This is the attribute used to store the Last Name of address book entries.</div>
	<dd>
		<input name="ldap_lname" size="50" id="ldap_lname" value="" type="text" /> Example: sn
	</dd>	
	<dt class="propname">LDAP New Entry: Full Name</dt>
	<div class="hint">Description: This is the attribute used to store the Full Name of address book entries.</div>
	<dd>
		<input name="ldap_fullname" size="50" id="ldap_fullname" value="" type="text" /> Example: cn
	</dd>	
	<dt class="propname">LDAP New Entry: Email Address</dt>
	<div class="hint">Description: This is the attribute used to store the Email Address of address book entries.</div>
	<dd>
		<input name="ldap_email" size="50" id="ldap_email" value="" type="text" /> Example: mail
	</dd>	
	<dt class="propname">LDAP Entry Sort</dt>
	<div class="hint">Description: This is the attribute used sort address book entries.</div>
	<dd>
		<input name="ldap_sort" size="50" id="ldap_sort" value="" type="text" /> Example: cn
	</dd>	
	<dt class="propname">LDAP Filter</dt>
	<div class="hint">Description: This is the attribute used to filter out address book entries from display.</div>
	<dd>
		<input name="ldap_filter" size="50" id="ldap_filter" value="" type="text" /> Example: accountStatus=active
	</dd>	
	<!-- These entries are not part of the config but are required for the main.inc.php -->
	<input name="autocomplete_addressbooks" size="5" id="autocomplete_addressbooks" value="sql, ldap" type="hidden" />
</fieldset>
</dl>
<?php

echo '<p><div id="button"><input type="submit" name="submit" value="' . ($RCI->configured ? 'UPDATE' : 'CREATE') . ' CONFIG" ' . ($RCI->failures ? 'disabled' : '') . ' /></div></p>';

?>
</form>

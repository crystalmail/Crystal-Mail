<title>Crystal Mail Installer :: Step 2 :: Generate Config</title>
<?php
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
<center>
<ol id="progress">
	<li class="step2">Check Environment</li><li class="step3"><inprogress>Generate Configuration</inprogress></li><li class="step4">Check Installation</li>
</ol>
</center>
<div id="rounded">
	<div id="button">
		<input type="submit" value="Install">
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
		<select name="db_type" id="db_type">
			<option value="mysql" selected="selected">MySQL</option>
			<option value="mysqli">MySQLi</option>
			<option value="pgsql">PgSQL</option>
			<option value="sqlite">SQLite</option>
			<option value="mssql">MSSQL</option>
			<option value="sqlsrv">SQLSrv</option>
		</select><br /><br />
		DB Username: <input name="db_user" size="20" id="db_user" value="" type="text" /><br /><br />
		DB Password: <input name="db_pass" size="20" id="db_pass" value="" type="password" /><br /><br />
		If your using SQLite, enter the full path to the DB file here:<br />
		<input name="db_file" size="50" id="db_file" value="" type="text" /> Example: sqlite:////full/path/to/sqlite.db?mode=0646
	</dd>
	<dt class="propname">Mail Host</dt>
	<div class="hint">Description: Enter the mail host to be used for login. If left blank, a text box will be provided during login so the user can enter their host.</div>
	<div class="hint">If the server utilizes encryption, select SSL or TLS from the drop down.</div>
	<div class="hint">Note: Multiple hosts can be added through the admin panel and a dropdown box will be created upon login to choose from the host list.</div>
	<dd>
		Encryption Type: <select name="host_encryption" id="host_encryption">
			<option value="" selected="selected">NONE</option>
			<option value="ssl">SSL</option>
			<option value="tls">TLS</option>
		</select>
		<input name="default_host" size="30" id="default_host" value="" type="text" /> Example: mail.example.com
	</dd>
	<dt class="propname">Mail Host Port</dt>
	<div class="hint">Description: Enter the mail host port number.</div>
	<dd>
		<input name="default_port" size="6" id="default_port" value="143" type="text" /> Default IMAP port is 143
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
		<input name="smtp_server" size="30" id="smtp_server" value="" type="text" /> Example: mail.example.com
	</dd>
	<dt class="propname">SMTP Host Port</dt>
	<div class="hint">Description: Enter the SMTP host port number.</div>
	<dd>
		<input name="smtp_port" size="6" id="smtp_port" value="25" type="text" /> Default SMTP port is 25
	</dd>
	<dt class="propname">SMTP User and Password</dt>
	<div class="hint">Description: If your SMTP server requires authentication, enter it here.</div>
	<dd>
		Username: <input name="smtp_user" size="20" id="smtp_user" value="" type="text" /><br /><br />
		Password: <input name="smtp_pass" size="20" id="smtp_pass" value="" type="password" />
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
		<input name="username_domain" size="20" id="username_domain" value="" type="text" /> 
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
	<input name="smtp_auth_type" size="10" id="smtp_auth_type" value="" type="hidden" />
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
		<select name="ip_check" id="ip_check">
			<option value="false" selected="selected">No, do not enable client IP check.</option>
			<option value="true">Yes, enable client IP check.</option>
		</select>
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
		<select name="debug_level" id="debug_level">
			<option value="1" selected="selected">Log Level 1: Log to normal logs. This is the default.</option>
			<option value="4">Log Level 4: Display the errors in the browser.</option>
			<option value="8">Log Level 8: This will produce tracing errors (verbose logging).</option>
		</select>
	</dd>
	<dt class="propname">Log Driver</dt>
	<div class="hint">Description: What driver should be used for logging.</div>
	<dd>
		<select name="log_driver" id="log_driver">
			<option value="file" selected="selected">Log to the local Crystal logs directory chosen below.</option>
			<option value="syslog">Utilize Syslog.</option>
		</select>
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
		<input name="syslog_id" size="10" id="syslog_id" value="crystal" type="text" />  
	</dd>
	<dt class="propname">Syslog Facility</dt>
	<div class="hint">Description: when using the syslog driver, what facility should ebe used?</div>
	<div class="hint">Note: Check out <a href="http://php.net/manual/en/function.openlog.php" target="_blank">http://php.net/manual/en/function.openlog.php</a> for the possible values.</div>
	<dd>
		<input name="syslog_facility" size="10" id="syslog_facility" value="LOG_USER" type="text" /> 
	</dd>
	<dt class="propname">Log Folder</dt>
	<div class="hint">Description: This is the folder where log files will be stored when using the 'file' log driver.</div>
	<div class="hint">Note: This folder must have write access for the web server user. i.e. apache/www</div>
	<dd>
		<input name="log_dir" size="30" id="log_dir" value="logs" type="text" />  
	</dd>
	<dt class="propname">Temporary Folder</dt>
	<div class="hint">Description: This is the folder where temporary files will be stored like attachments</div>
	<div class="hint">Note: This folder must have write access for the web server user. i.e. apache/www</div>
	<dd>
		<input name="temp_dir" size="30" id="temp_dir" value="temp" type="text" />  
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
		<table>
			<tr>
				<td><input name="smtp_log" id="smtp_log" value="smtp_log" type="checkbox" /> SMTP Log<br /></td>
				<td><input name="smtp_debug" id="smtp_debug" value="smtp_debug" type="checkbox" /> SMTP Debug<br /></td>
				<td><input name="sql_debug" id="sql_debug" value="sql_debug" type="checkbox" /> SQL Debug<br /></td>
			</tr>	
			<tr>
				<td><input name="imap_debug" id="imap_debug" value="imap_debug" type="checkbox" /> IMAP Debug<br /></td>
				<td><input name="ldap_debug" id="ldap_debug" value="ldap_debug" type="checkbox" /> LDAP Debug<br /></td>
			</tr>
		</table>	
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
		?>
	</dd>
	<dt class="propname">Automatically Create Users</dt>
	<div class="hint">Description: This will automatically create a new user once the IMAP login has suceeded.</div>
	<div class="hint">Note: It is recommended to leave this enabled otherwise only users that have logged into Crystal before will be able to login.</div>
	<dd>
		<select name="auto_create_user" id="auto_create_user">
			<option value="true" selected="selected">Yes, automatically create new users upon successful logins.</option>
			<option value="false">No, do not automatically create new users upon successful logins.</option>
		</select>
	</dd>
	<dt class="propname">Page Titles</dt>
	<div class="hint">Description: This is the title you want to appear in the browser window title bar.</div>
	<dd>
		<input name="product_name" size="30" id="product_name" value="Crystal Webmail" type="text" />  
	</dd>
	<dt class="propname">Folder Names: Drafts</dt>
	<div class="hint">Description: This is the name of the folder used to store draft messages.</div>
	<div class="hint">Note: If left blank, draft messages will not be stored.</div>
	<dd>
		<input name="drafts_mbox" size="20" id="drafts_mbox" value="Drafts" type="text" />  
	</dd>
	<dt class="propname">Folder Names: Junk</dt>
	<div class="hint">Description: This is the name of the folder used to store junk messages.</div>
	<div class="hint">Note: If left blank, junk messages will not be stored.</div>
	<dd>
		<input name="junk_mbox" size="20" id="junk_mbox" value="Junk" type="text" />  
	</dd>
	<dt class="propname">Folder Names: Archive</dt>
	<div class="hint">Description: This is the name of the folder used to store archive messages.</div>
	<div class="hint">Note: If left blank, archive messages will not be stored.</div>
	<dd>
		<input name="archive_mbox" size="20" id="archive_mbox" value="Archive" type="text" />  
	</dd>
	<dt class="propname">Folder Names: Sent</dt>
	<div class="hint">Description: This is the name of the folder used to store sent messages.</div>
	<div class="hint">Note: If left blank, sent messages will not be stored.</div>
	<dd>
		<input name="sent_mbox" size="20" id="sent_mbox" value="Sent" type="text" />  
	</dd>
	<dt class="propname">Folder Names: Trash</dt>
	<div class="hint">Description: This is the name of the folder used to store trash messages.</div>
	<div class="hint">Note: If left blank, trash messages will not be stored.</div>
	<dd>
		<input name="trash_mbox" size="20" id="trash_mbox" value="Trash" type="text" />  
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
		<select name="mdn_requests" id="mdn_requests">
			<option value="0" selected="selected">Prompt the user for a response.</option>
			<option value="1">Send automatically.</option>
			<option value="2">Ignore. Never send or ask.</option>
		</select>
	</dd>
	<dt class="propname">Identities</dt>
	<div class="hint">Description: This will determine to what extent a users identity may be modified.</div>
	<dd>
		<select name="identities_level" id="identities_level">
			<option value="0">Many identities with the possibility to edit all parameters.</option>
			<option value="1">Many identities with the possibility to edit all parameters except email address.</option>
			<option value="2">One identity with the possibility to edit all parameters.</option>
			<option value="3" selected="selected">One identity with the possibility to edit all parameters except email address.</option>
		</select>
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
		<select name="enable_caching" id="enable_caching">
			<option value="false" selected="selected">No, disable message caching.</option>
			<option value="true">Yes, enable message caching.</option>
		</select>
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
		<select name="enable_spellcheck" id="enable_spellcheck">
			<option value="true" selected="selected">Yes, enable the spell checker program.</option>
			<option value="false">No, do not enable the spell checker program.</option>
		</select>
	</dd>
	<dt class="propname">Spell Checker Choice</dt>
	<div class="hint">Description: This setting allows you to choose which spell checker to use.</div>
	<div class="hint">Note: If you use Nox Spell, choose googie.</div>
	<div class="hint">Note 2: If you use PSpell, ensure the PSpell extensions are installed for PHP.</div>
	<dd>
		<select name="spellcheck_engine" id="spellcheck_engine">
			<option value="googie" selected="selected">Googie</option>
			<option value="pspell">PSpell</option>
		</select>
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
		<input name="draft_autosave" size="10" id="draft_autosave" value="300" type="text" /> Default is 300 seconds (5 minutes)
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
		$skins=scandir("../skins");
		foreach ($skins as $skin_name){
			if (preg_match("/[^.\^.svn\^.DS_Store]/", $skin_name)) {
				$skin_list .= "<option value=\"$skin_name\">$skin_name";
			}
		}		
		echo "<select name=\"skin\" id=\"skin\">";
		echo "$skin_list";
		echo "</select>";
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
		<select name="mime_param_folding" id="mime_param_folding">
			<option value="0" selected="selected">Full RFC 2231 Compatibility</option>
			<option value="1">RFC 2047 for 'name' and RFC 2231 for 'filename'. (Thunderbird Default)</option>
			<option value="2">Full RFC 2047 Compatibility</option>
		</select>
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
		<select name="preview_pane" id="preview_pane">
			<option value="true" selected="selected">Yes, display preview pane.</option>
			<option value="false">No, do not display preview pane.</option>
		</select>
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
		<input name="pagesize" size="5" id="pagesize" value="50" type="text" />
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
		<select name="prefer_html" id="prefer_html">
			<option value="true" selected="selected">Yes, display HTML messages by default.</option>
			<option value="false">No, do not display HTML messages by default.</option>
		</select>
	</dd>
	<dt class="propname">HTML Editor</dt>
	<div class="hint">Description: This will allow messages to be edited using the HTML editor.</div>
	<div class="hint">Note: This setting can be overridden by the user.</div>
	<dd>
		<select name="html_editor" id="html_editor">
			<option value="true" selected="selected">Yes, allow HTML editing of messages by default.</option>
			<option value="false">No, do not allow HTML editing of messages by default.</option>
		</select>
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
	<div id="button">
		<input type="submit" value="Install">
	</div>
	<br>
</form>

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
<ol id="progress">
	<li class="step2">Check Environment</li><li class="step3"><inprogress>Generate Configuration</inprogress></li><li class="step4">Check Installation</li>
</ol>

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
<dl class="configblock">
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
		$dir = "../plugins";
		$plugins=scandir("../plugins");
		foreach ($plugins as $plugin_name){
			if (preg_match("/[^.\^.svn]/", $plugin_name)) {
				foreach ($rcmail_config['plugins'] as $default_plugin){ 
					if ($plugin_name == $default_plugin) {
						$checked="checked=\"yes\" ";
					} else {	
						$checked="";
					}	
				}
				$plugin_list .= "<input name=\"$plugin_name\" id=\"$plugin_name\"$checked value=\"$plugin_name\" type=\"checkbox\" \> $plugin_name<br />";
			}
		}		
		echo "$plugin_list";
		?>
	</dd>
	<dt class="propname">Automaticvally Create Users</dt>
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







</dl>

</fieldset>
	<div id="button">
		<input type="submit" value="Install">
	</div>
	<br>
</form>

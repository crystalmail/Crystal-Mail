<title>Crystal Mail Installer :: Step 2 :: Generate Config</title>
<?php
// Function to create a new random token
// e.g. createToken('UG8D-', 3, 4)
// Might produce: UG8D-6T8Y-FCK7-09PL
function createToken($tokenprefix, $sections, $sectionlength)
{
	// Declare salt and prefix
	$token .= $tokenprefix;
	$salt = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!@$%^&*';

	// Prepare randomizer
	srand((double) microtime() * 1000000);

	// Create the token
	for($i = 0; $i < $sections; $i++)
	{
		for($n = 0; $n < $sectionlength; $n++)
		{
			$token.=substr($salt, rand() % strlen($salt), 1);
		}

		if($i < ($sections - 1)){ $token .= '-'; }
	}

	// Return the token
	return $token;
}
?>
<center>
<style>
fieldset {
  margin-bottom: 1.5em;
  border: 1px solid #aaa;
  background-color: #fffffe;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
  
}

fieldset p.hint {
  margin-top: 0.5em;
}

legend {
  font-size: 1.1em;
  font-weight: bold;
}

textarea.configfile {
  background-color: #f9f9f9;
  font-family: monospace;
  font-size: 9pt;
  width: 100%;
  height: 30em;
}

.propname {
  font-size: 9pt;
  margin-top: 1em;
  margin-bottom: 0.6em;
}

dd div {
  margin-top: 0.3em;
}

dd label {
  padding-left: 0.5em;
}

th {
  text-align: left;
}

ul li {
  margin: 0.3em 0 0.4em -1em;
}

ul li ul li {
  margin-bottom: 0.2em;
}

h3 {
  font-size: 1.1em;
  margin-top: 1.5em;
  margin-bottom: 0.6em;
}

h4 {
  margin-bottom: 0.2em;
}

a.blocktoggle {
  color: #666;
  text-decoration: none;
}

a.addlink {
  color: #999;
  font-size: 0.9em;
  padding: 1px 0 1px 20px;
  background: url('images/add.png') top left no-repeat;
  text-decoration: none;
}

a.removelink {
  color: #999;
  font-size: 0.9em;
  padding: 1px 0 1px 24px;
  background: url('images/delete.png') 4px 0 no-repeat;
  text-decoration: none;
}

.hint {
  color: #666;
  font-size: 0.85em;
background-color: #FFFFE0;
border-style:solid;
border-width:1px;
border-color: #EEEEE0;
padding: 3px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
}

.success {
  color: #006400;
  font-weight: bold !important;
}

.fail {
  color: #ff0000 !important;
  font-weight: bold !important;
}

.na {
  color: #f60;
  font-weight: bold;
}

.indent {
  padding-left: 0.8em;
}

.notice {
  padding: 1em;
  background-color: #f7fdcb;
  border: 2px solid #c2d071;
}

.suggestion {
  padding: 0.6em;
  background-color: #ebebeb;
  border: 1px solid #999;
}

p.warning,
div.warning {
  padding: 1em;
  background-color: #ef9398;
  border: 2px solid #dc5757;
}

h3.warning {
  color: #c00;
  background: url('images/error.png') top left no-repeat;
  padding-left: 24px;
}

</style>
<ol id="progress">
<li class="step2">Check environment</li><li class="step3"><inprogress>Generate config</inprogress></li><li class="step4">Check install</li></ol>
</center>
<div id="rounded">
<form name="form" method="post" action="?action=install">
<fieldset>
<legend>General configuration</legend>
<dl class="configblock">

<dt class="propname">Installation Name</dt>
<dd>
<input name="productname" size="30" id="cfgprodname" value="Crystal Webmail" type="text" /><div class="hint">The name of your service (used to compose page titles)</div>
</dd>

<dt class="propname">Temp Dir</dt>
<dd>
<input name="tempdir" size="30" id="cfgtempdir" value="temp/" type="text" /><div class="hint">Use this folder to store temp files (must be writeable for webserver)</div>
</dd>


<dt class="propname">IP Check</dt>

<dd>
<input name="ipcheck" id="cfgipcheck" value="1" type="checkbox" /><label for="cfgipcheck">Check client IP in session authorization</label><br />

<p class="hint">This increases security but can cause sudden logouts when someone uses a proxy with changeing IPs.</p>
</dd>

<dt class="propname">Des Key</dt>
<dd>
<input name="deskey" size="30" id="cfgdeskey" value="<?php echo(createToken('', 1, 24)); ?>" type="text" /><div>This key is used to encrypt the users imap password before storing in the session record</div>
<p class="hint">It's a random generated string to ensure that every installation has it's own key.
If you enter it manually please provide a string of exactly 24 chars.</p>
</dd>

<dt class="propname">Enable Caching</dt>
<dd>
<input name="enablecaching" id="cfgcache" value="1" type="checkbox" /><label for="cfgcache">Cache messages in local database</label><br />
</dd>

<dt class="propname">Enable Spellcheck</dt>
<dd>
<input name="enablespellcheck" id="cfgspellcheck" value="1" checked="checked" type="checkbox" /><label for="cfgspellcheck">Make use of the spell checker</label><br />
</dd>

<dt class="propname">Spellcheck Engine</dt>
<dd>

<select name="spellcheckengine" id="cfgspellcheckengine">
<option value="googie">Googie</option>
</select>
<label for="cfgspellcheckengine">Which spell checker to use</label><br />

<p class="hint">GoogieSpell implies that the message content will be sent to Google in order to check the spelling.</p>
</dd>

<dt class="propname">Identities Level</dt>
<dd>
<select name="identitieslevel" id="cfgidentitieslevel">
<option value="0" selected="selected">many identities with possibility to edit all params</option>

<option value="1">many identities with possibility to edit all params but not email address</option>
<option value="2">one identity with possibility to edit all params</option>
<option value="3">one identity with possibility to edit all params but not email address</option>
</select>
<div>Level of identities access</div>
<p class="hint">Defines what users can do with their identities.</p>
</dd>

</dl>
</fieldset>

<fieldset>

<legend>Logging & Debugging</legend>
<dl class="loggingblock">

<dt class="propname">Debug Level</dt>
<dd>
<input name="debuglevel[]" value="1" id="cfgdebug1" checked="checked" type="checkbox" /><label for="cfgdebug1">Log errors</label><br /><input name="debuglevel[]" value="4" id="cfgdebug4" type="checkbox" /><label for="cfgdebug4">Print errors (to the browser)</label><br /><input name="debuglevel[]" value="8" id="cfgdebug8" type="checkbox" /><label for="cfgdebug8">Verbose display (enables debug console)</label><br /></dd>

<dt class="propname">Log Driver</dt>
<dd>
<select name="logdriver" id="cfglogdriver">

<option value="file" selected="selected">file</option>
<option value="syslog">syslog</option>
</select>
<div>How to do logging? 'file' - write to files in the log directory, 'syslog' - use the syslog facility.</div>
</dd>

<dt class="propname">Log Dir</dt>
<dd>
<input name="logdir" size="30" id="cfglogdir" value="logs/" type="text" /><div>Use this folder to store log files (must be writeable for webserver). Note that this only applies if you are using the 'file' logdriver.</div>
</dd>

<dt class="propname">Syslog Id</dt>

<dd>
<input name="syslogid" size="30" id="cfgsyslogid" value="crystalmail" type="text" /><div>What ID to use when logging with syslog. Note that this only applies if you are using the 'syslog' logdriver.</div>
</dd>

<dt class="propname">Syslog Facility</dt>
<dd>
<select name="syslogfacility" id="cfgsyslogfacility">
<option value="8" selected="selected">user-level messages</option>
<option value="16">mail subsystem</option>
<option value="128">local level 0</option>
<option value="136">local level 1</option>

<option value="144">local level 2</option>
<option value="152">local level 3</option>
<option value="160">local level 4</option>
<option value="168">local level 5</option>
<option value="176">local level 6</option>
<option value="184">local level 7</option>
</select>
<div>What ID to use when logging with syslog.  Note that this only applies if you are using the 'syslog' logdriver.</div>
</dd>



</dl>
</fieldset>


<fieldset>
<legend>Database setup</legend>
<dl class="configblock" id="cgfblockdb">
<dt class="propname">Database Dsnw</dt>
<dd>
<p>Database settings for read/write operations:</p>
<select name="dbtype" id="cfgdbtype">

<option value="mysql" selected="selected">MySQL</option>
<option value="mysqli">MySQLi</option>
<option value="pgsql">PgSQL</option>
<option value="sqlite">SQLite</option>
</select>
<label for="cfgdbtype">Database type</label><br /><input name="dbhost" size="20" id="cfgdbhost" value="localhost" type="text" /><label for="cfgdbhost">Database server (omit for sqlite)</label><br /><input name="dbname" size="20" id="cfgdbname" value="crystalmail" type="text" /><label for="cfgdbname">Database name (use absolute path and filename for sqlite)</label><br /><input name="dbuser" size="20" id="cfgdbuser" value="crystal" type="text" /><label for="cfgdbuser">Database user name (needs write permissions)(omit for sqlite)</label><br /><input name="dbpass" size="20" id="cfgdbpass" value="pass" type="password" /><label for="cfgdbpass">Database password (omit for sqlite)</label><br /></dd>
</dl>
</fieldset>


<fieldset>
<legend>IMAP Settings</legend>
<dl class="configblock" id="cgfblockimap">
<dt class="propname">Default Host</dt>
<dd>
<div>The IMAP host chosen to perform the log-in</div>
<div id="defaulthostlist">
<div id="defaulthostentry0"><input name="defaulthost[]" size="30" value="" type="text" /></div></div>

<p class="hint">Leave blank to show a textbox at login. To use SSL/IMAPS connection, type ssl://hostname</p>

</dd>

<dt class="propname">Default Port</dt>
<dd>
<input name="defaultport" size="6" id="cfgimapport" value="143" type="text" /><div>TCP port used for IMAP connections</div>
</dd>

<dt class="propname">Username Domain</dt>
<dd>
<input name="usernamedomain" size="30" id="cfguserdomain" value="" type="text" /><div>Automatically add this domain to user names for login</div>

<p class="hint">Only for IMAP servers that require full e-mail addresses for login</p>

</dd>

<dt class="propname">Auto Create User</dt>
<dd>
<input name="autocreateuser" id="cfgautocreate" value="1" checked="checked" type="checkbox" /><label for="cfgautocreate">Automatically create a new RoundCube user when log-in the first time</label><br />

<p class="hint">A user is authenticated by the IMAP server but it requires a local record to store settings
and contacts. With this option enabled a new user record will automatically be created once the IMAP login succeeds.</p>

<p class="hint">If this option is disabled, the login only succeeds if there's a matching user-record in the local RoundCube database
what means that you have to create those records manually or disable this option after the first login.</p>
</dd>

<dt class="propname">Sent mbox</dt>

<dd>
<input name="sentmbox" size="20" id="cfgsentmbox" value="Sent" type="text" /><div>Store sent messages in this folder</div>

<p class="hint">Leave blank if sent messages should not be stored</p>
</dd>

<dt class="propname">Trash mbox</dt>
<dd>
<input name="trashmbox" size="20" id="cfgtrashmbox" value="Trash" type="text" /><div>Move messages to this folder when deleting them</div>

<p class="hint">Leave blank if they should be deleted directly</p>
</dd>

<dt class="propname">Drafts mbox</dt>
<dd>
<input name="draftsmbox" size="20" id="cfgdraftsmbox" value="Drafts" type="text" /><div>Store draft messages in this folder</div>

<p class="hint">Leave blank if they should not be stored</p>
</dd>

<dt class="propname">Junk mbox</dt>
<dd>
<input name="junkmbox" size="20" id="cfgjunkmbox" value="Junk" type="text" /><div>Store spam messages in this folder</div>
</dd>

</dl>
</fieldset>


<fieldset>
<legend>SMTP Settings</legend>
<dl class="configblock" id="cgfblocksmtp">
<dt class="propname">Smtp Server</dt>
<dd>
<input name="smtpserver" size="30" id="cfgsmtphost" value="" type="text" /><div>Use this host for sending mails</div>

<p class="hint">To use SSL connection, set ssl://smtp.host.com. If left blank, the PHP mail() function is used</p>
</dd>

<dt class="propname">Smtp Port</dt>
<dd>
<input name="smtpport" size="6" id="cfgsmtpport" value="25" type="text" /><div>SMTP port (default is 25; 465 for SSL; 587 for submission)</div>
</dd>

<dt class="propname">Smtp User And Pass</dt>
<dd>
<input name="smtpuser" size="20" id="cfgsmtpuser" value="" type="text" /><input name="smtppass" size="20" id="cfgsmtppass" value="" type="password" /><div>SMTP username and password (if required)</div>
<p>
<input name="smtpuseru" id="cfgsmtpuseru" value="1" type="checkbox" /><label for="cfgsmtpuseru">Use the current IMAP username and password for SMTP authentication</label>
</p>

</dd>
<!--
<dt class="propname">smtpauthtype</dt>
<dd>
<div>Method to authenticate at the SMTP server. Choose (auto) if you don't know what this is</div>
</dd>
-->
<dt class="propname">Smtp Log</dt>
<dd>
<input name="smtplog" id="cfgsmtplog" value="1" checked="checked" type="checkbox" /><label for="cfgsmtplog">Log sent messages in <tt>{logdir}/sendmail</tt> or to syslog.</label><br />
</dd>

</dl>
</fieldset>


<fieldset>

<legend>Display settings &amp; user prefs</legend>
<dl class="configblock" id="cgfblockdisplay">

<dt class="propname">Language <span class="userconf">*</span></dt>
<dd>
<input name="language" size="6" id="cfglocale" value="" type="text" /><div>The default locale setting. This also defines the language of the login screen.<br/>Leave it empty to auto-detect the user agent language.</div>
<p class="hint">Enter a <a href="http://www.faqs.org/rfcs/rfc1766">RFC1766</a> formatted language name. Examples: enUS, deDE, deCH, frFR, ptBR</p>

</dd>

<dt class="propname">Skin <span class="userconf">*</span></dt>
<dd>
<input name="skin" size="30" id="cfgskin" value="default" type="text" /><div>Name of interface skin (folder in /skins)</div>
</dd>

<dt class="propname">Pagesize <span class="userconf">*</span></dt>
<dd>
<input name="pagesize" size="6" id="cfgpagesize" value="40" type="text" /><div>Show up to X items in list view.</div>
</dd>

<dt class="propname">Prefer Html <span class="userconf">*</span></dt>
<dd>
<input name="preferhtml" id="cfghtmlview" value="1" checked="checked" type="checkbox" /><label for="cfghtmlview">Prefer displaying HTML messages</label><br />
</dd>

<dt class="propname">Preview Pane <span class="userconf">*</span></dt>
<dd>
<input name="previewpane" id="cfgprevpane" value="1" type="checkbox" /><label for="cfgprevpane">If preview pane is enabled</label><br />
</dd>

<dt class="propname">Html Editor (WYSIWYG) <span class="userconf">*</span></dt>
<dd>
<input name="htmleditor" id="cfghtmlcompose" value="1" type="checkbox" /><label for="cfghtmlcompose">Compose HTML formatted messages</label><br />
</dd>

<dt class="propname">Draft Autosave <span class="userconf">*</span></dt>
<dd>
<label for="cfgautosave">Save compose message every</label>
<select name="draftautosave" id="cfgautosave">
<option value="0">never</option>

<option value="60">1 min</option>
<option value="180">3 min</option>
<option value="300" selected="selected">5 min</option>
<option value="600">10 min</option>
</select>
</dd>

<dt class="propname">Mdn Requests <span class="userconf">*</span></dt>
<dd>
<select name="mdnrequests" id="cfgmdnreq">
<option value="0" selected="selected">ask the user</option>

<option value="1">send automatically</option>
<option value="2">ignore</option>
</select>
<div>Behavior if a received message requests a message delivery notification (read receipt)</div>
</dd>

<dt class="propname">Mime Param Folding <span class="userconf">*</span></dt>
<dd>
<select name="mimeparamfolding" id="cfgmimeparamfolding">
<option value="0">Full RFC 2231 (Crystal Mail, Thunderbird)</option>
<option value="1">RFC 2047/2231 (MS Outlook, OE)</option>

<option value="2">Full RFC 2047 (deprecated)</option>
</select>
<div>How to encode attachment long/non-ascii names</div>
</dd>

</dl>

<p class="hint"><span class="userconf">*</span>&nbsp; These settings are defaults for the user preferences</p>
</fieldset>
<div id="button">
<input type="submit" value="Install">
</div>
<br>
</form>
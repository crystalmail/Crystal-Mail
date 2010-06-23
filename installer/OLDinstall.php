<title>Crystal Mail Installer :: Installing</title>
<center>
<ol id="progress">
        <li>Check Environment</li><li>Configuration</li><li class="bold">Verify Configuration</li>
</ol>
<h3>Below is the configuration files that have been generated. Click 'Install' to complete the installation process.</h3>


<div id="rounded">
<h1>main.inc.php</h1>
<textarea rows="20" cols="150">
<?php echo "<?php" ?>

$cmail_config = array();

// system error reporting: 1 = log; 2 = report (not implemented yet), 4 = show, 8 = trace
$cmail_config['debug_level'] = 1;

// log driver:  'syslog' or 'file'.
$cmail_config['log_driver'] = '<?php echo $_POST["logdriver"] ?>';

// date format for log entries
// (read http://php.net/manual/en/function.date.php for all format characters)  
$cmail_config['log_date_format'] = 'd-M-Y H:i:s O';

// Syslog ident string to use, if using the 'syslog' log driver.
$cmail_config['syslog_id'] = '<?php echo $_POST["syslogid"] ?>';

// Syslog facility to use, if using the 'syslog' log driver.
// For possible values see installer or http://php.net/manual/en/function.openlog.php
$cmail_config['syslog_facility'] = '<?php echo $_POST["syslogfacility"] ?>';

// use this folder to store log files (must be writeable for apache user)
// This is used by the 'file' log driver.
$cmail_config['log_dir'] = '<?php echo $_POST["logdir"] ?>';

// use this folder to store temp files (must be writeable for apache user)
$cmail_config['temp_dir'] = '<?php echo $_POST["tempdir"] ?>';

// List of active plugins (in plugins/ directory)
$cmail_config['plugins'] = array(remember_me, calendar, contextmenu);

// enable caching of messages and mailbox data in the local database.
// this is recommended if the IMAP server does not run on the same machine
$cmail_config['enable_caching'] = '<?php if (isset ($_POST['enablecaching'])) { echo "true"; } else { echo "false" ; } ?>';

// lifetime of message cache
// possible units: s, m, h, d, w
$cmail_config['message_cache_lifetime'] = '10d';

// enforce connections over https
// with this option enabled, all non-secure connections will be redirected.
// set the port for the ssl connection as value of this option if it differs from the default 443
$cmail_config['force_https'] = FALSE;

// automatically create a new crystal user when log-in the first time.
// a new user will be created once the IMAP login succeeds.
// set to false if only registered users can use this service
$cmail_config['auto_create_user'] = TRUE;

// the mail host chosen to perform the log-in
// leave blank to show a textbox at login, give a list of hosts
// to display a pulldown menu or set one host as string.
// To use SSL/TLS connection, enter hostname with prefix ssl:// or tls://
$cmail_config['default_host'] = 'mail.hoppedolancomputers.com';

// TCP port used for IMAP connections
$cmail_config['default_port'] = 143;

// IMAP auth type. Can be "auth" (CRAM-MD5), "plain" (PLAIN) or "check" to auto detect.
// Optional, defaults to "check"
$cmail_config['imap_auth_type'] = null;

// If you know your imap's root directory and its folder delimiter,
// you can specify them here. Otherwise they will be determined automatically.
$cmail_config['imap_root'] = null;
$cmail_config['imap_delimiter'] = null;

// Automatically add this domain to user names for login
// Only for IMAP servers that require full e-mail addresses for login
// Specify an array with 'host' => 'domain' values to support multiple hosts
$cmail_config['username_domain'] = 'hoppedolancomputers.com';

// This domain will be used to form e-mail addresses of new users
// Specify an array with 'host' => 'domain' values to support multiple hosts
$cmail_config['mail_domain'] = '';

// Path to a virtuser table file to resolve user names and e-mail addresses
$cmail_config['virtuser_file'] = '';

// Query to resolve user names and e-mail addresses from the database
// %u will be replaced with the current username for login.
// The query should select the user's e-mail address as first column
// and optional identity name as second column
$cmail_config['virtuser_query'] = '';

// use this host for sending mails.
// to use SSL connection, set ssl://smtp.host.com
// if left blank, the PHP mail() function is used
// Use %h variable as replacement for user's IMAP hostname
$cmail_config['smtp_server'] = 'mail.hoppedolancomputers.com';

// SMTP port (default is 25; 465 for SSL)
$cmail_config['smtp_port'] = 25;

// SMTP username (if required) if you use %u as the username crystal
// will use the current username for login
$cmail_config['smtp_user'] = '%u';

// SMTP password (if required) if you use %p as the password crystal
// will use the current user's password for login
$cmail_config['smtp_pass'] = '%p';

// SMTP AUTH type (DIGEST-MD5, CRAM-MD5, LOGIN, PLAIN or empty to use
// best server supported one)
$cmail_config['smtp_auth_type'] = '';

// SMTP HELO host 
// Hostname to give to the remote server for SMTP 'HELO' or 'EHLO' messages 
// Leave this blank and you will get the server variable 'server_name' or 
// localhost if that isn't defined. 
$cmail_config['smtp_helo_host'] = '';

// Log sent messages
$cmail_config['smtp_log'] = TRUE;

// Log SQL queries to <log_dir>/sql or to syslog
$cmail_config['sql_debug'] = false;

// Log IMAP conversation to <log_dir>/imap or to syslog
$cmail_config['imap_debug'] = false;

// Log LDAP conversation to <log_dir>/ldap or to syslog
$cmail_config['ldap_debug'] = false;

// Log SMTP conversation to <log_dir>/smtp or to syslog
$cmail_config['smtp_debug'] = false;

// How many seconds must pass between emails sent by a user
$cmail_config['sendmail_delay'] = 0;

// These cols are shown in the message list. Available cols are:
// subject, from, to, cc, replyto, date, size, flag, attachment
$cmail_config['list_cols'] = array('subject', 'from', 'date', 'size', 'flag', 'attachment');

// Includes should be interpreted as PHP files
$cmail_config['skin_include_php'] = TRUE;

// Session lifetime in minutes
// must be greater than 'keep_alive'/60
$cmail_config['session_lifetime'] = 10;

// check client IP in session athorization
$cmail_config['ip_check'] = '<?php if (isset ($_POST['ipcheck'])) { echo "true"; } else { echo "false" ; } ?>';

// Use an additional frequently changing cookie to athenticate user sessions.
// There have been problems reported with this feature.
$cmail_config['double_auth'] = false;

// this key is used to encrypt the users imap password which is stored
// in the session record (and the client cookie if remember password is enabled).
// please provide a string of exactly 24 chars.
$cmail_config['des_key'] = '<?php echo $_POST["deskey"] ?>';

// the default locale setting (leave empty for auto-detection)
// RFC1766 formatted language name like en_US, de_DE, de_CH, fr_FR, pt_BR
$cmail_config['language'] = null;

// use this format for short date display (date or strftime format)
$cmail_config['date_short'] = 'D H:i';

// use this format for detailed date/time formatting (date or strftime format)
$cmail_config['date_long'] = 'd.m.Y H:i';

// use this format for today's date display (date or strftime format)
$cmail_config['date_today'] = 'H:i';

// add this user-agent to message headers when sending
$cmail_config['useragent'] = 'Crystal Webmail/'.cmail_VERSION;

// use this name to compose page titles
$cmail_config['product_name'] = '<?php echo $_POST["productname"] ?>';

// store draft message is this mailbox
// leave blank if draft messages should not be stored
$cmail_config['drafts_mbox'] = 'Drafts';

// store spam messages in this mailbox
$cmail_config['junk_mbox'] = 'Junk';

// store archived messages in this mailbox
$cmail_config['archive_mbox']= 'Archive';

// store sent message is this mailbox
// leave blank if sent messages should not be stored
$cmail_config['sent_mbox'] = 'Sent';

// move messages to this folder when deleting them
// leave blank if they should be deleted directly
$cmail_config['trash_mbox'] = 'Trash';

// display these folders separately in the mailbox list.
// these folders will also be displayed with localized names
$cmail_config['default_imap_folders'] = array('INBOX', 'Drafts', 'Sent', 'Junk', Archive, 'Trash');

// automatically create the above listed default folders on login
$cmail_config['create_default_folders'] = TRUE;

// protect the default folders from renames, deletes, and subscription changes
$cmail_config['protect_default_folders'] = TRUE;

// if in your system 0 quota means no limit set this option to TRUE 
$cmail_config['quota_zero_as_unlimited'] = FALSE;

// Behavior if a received message requests a message delivery notification (read receipt)
// 0 = ask the user, 1 = send automatically, 2 = ignore (never send or ask)
$cmail_config['mdn_requests'] = 0;

// Use this charset as fallback for message decoding
$cmail_config['default_charset'] = 'ISO-8859-1';

// Make use of the built-in spell checker. It is based on GoogieSpell.
// Since Google only accepts connections over https your PHP installatation
// requires to be compiled with Open SSL support
$cmail_config['enable_spellcheck'] = '<?php if (isset ($_POST['enablespellcheck'])) { echo "true"; } else { echo "false" ; } ?>';

// Set the spell checking engine. 'googie' is the default. 'pspell' is also available,
// but requires the Pspell extensions. When using Nox Spell Server, also set 'googie' here.
$cmail_config['spellcheck_engine'] = '<?php echo $_POST["spellcheckengine"] ?>';

// For a locally installed Nox Spell Server, please specify the URI to call it.
// Get Nox Spell Server from http://orangoo.com/labs/?page_id=72
// Leave empty to use the Google spell checking service, what means
// that the message content will be sent to Google in order to check spelling
$cmail_config['spellcheck_uri'] = '';

// These languages can be selected for spell checking.
// Configure as a PHP style hash array: array('en'=>'English', 'de'=>'Deutsch');
// Leave empty for default set of available language.
$cmail_config['spellcheck_languages'] = NULL;

// path to a text file which will be added to each sent message
// paths are relative to the crystal root folder
$cmail_config['generic_message_footer'] = '';

// add a received header to outgoing mails containing the creators IP and hostname
$cmail_config['http_received_header'] = false;

// Whether or not to encrypt the IP address and the host name
// these could, in some circles, be considered as sensitive information;
// however, for the administrator, these could be invaluable help
// when tracking down issues.
$cmail_config['http_received_header_encrypt'] = false;

// this string is used as a delimiter for message headers when sending
// leave empty for auto-detection
$cmail_config['mail_header_delimiter'] = NULL;

// session domain: .example.org
$cmail_config['session_domain'] = '';

// This indicates which type of address book to use. Possible choises:
// 'sql' (default) and 'ldap'.
// If set to 'ldap' then it will look at using the first writable LDAP
// address book as the primary address book and it will not display the
// SQL address book in the 'Address Book' view.
$cmail_config['address_book_type'] = 'sql';

// In order to enable public ldap search, configure an array like the Verisign
// example further below. if you would like to test, simply uncomment the example.
$cmail_config['ldap_public'] = array();

//
// If you are going to use LDAP for individual address books, you will need to 
// set 'user_specific' to true and use the variables to generate the appropriate DNs to access it.
//
// The recommended directory structure for LDAP is to store all the address book entries
// under the users main entry, e.g.:
//
//  o=root
//   ou=people
//    uid=user@domain
//  mail=contact@contactdomain
//
// So the base_dn would be uid=%fu,ou=people,o=root
// The bind_dn would be the same as based_dn or some super user login.
/* 
 * example config for Verisign directory
 *
$cmail_config['ldap_public']['Verisign'] = array(
  'name'          => 'Verisign.com',
  'hosts'         => array('directory.verisign.com'),
  'port'          => 389,
  'use_tls'      => false,
  'user_specific' => false,   // If true the base_dn, bind_dn and bind_pass default to the user's IMAP login.
  // %fu - The full username provided, assumes the username is an email
  //       address, uses the username_domain value if not an email address.
  // %u  - The username prior to the '@'.
  // %d  - The domain name after the '@'.
  'base_dn'       => '',
  'bind_dn'       => '',
  'bind_pass'     => '',
  'writable'      => false,   // Indicates if we can write to the LDAP directory or not.
  // If writable is true then these fields need to be populated:
  // LDAP_Object_Classes, required_fields, LDAP_rdn
  'LDAP_Object_Classes' => array("top", "inetOrgPerson"), // To create a new contact these are the object classes to specify (or any other classes you wish to use).
  'required_fields'     => array("cn", "sn", "mail"),     // The required fields needed to build a new contact as required by the object classes (can include additional fields not required by the object classes).
  'LDAP_rdn'      => 'mail', // The RDN field that is used for new entries, this field needs to be one of the search_fields, the base of base_dn is appended to the RDN to insert into the LDAP directory.
  'ldap_version'  => 3,       // using LDAPv3
  'search_fields' => array('mail', 'cn'),  // fields to search in
  'name_field'    => 'cn',    // this field represents the contact's name
  'email_field'   => 'mail',  // this field represents the contact's e-mail
  'surname_field' => 'sn',    // this field represents the contact's last name
  'firstname_field' => 'gn',  // this field represents the contact's first name
  'sort'          => 'cn',    // The field to sort the listing by.
  'scope'         => 'sub',   // search mode: sub|base|list
  'filter'        => '',      // used for basic listing (if not empty) and will be &'d with search queries. example: status=act
  'fuzzy_search'  => true);   // server allows wildcard search
*/

// An ordered array of the ids of the addressbooks that should be searched
// when populating address autocomplete fields server-side. ex: array('sql','Verisign');
$cmail_config['autocomplete_addressbooks'] = array('sql');

// don't allow these settings to be overriden by the user
$cmail_config['dont_override'] = array();

// Set identities access level:
// 0 - many identities with possibility to edit all params
// 1 - many identities with possibility to edit all params but not email address
// 2 - one identity with possibility to edit all params
// 3 - one identity with possibility to edit all params but not email address
$cmail_config['identities_level'] = <?php echo $_POST["identitieslevel"] ?>';

// try to load host-specific configuration
// see http://trac.crystal.net/wiki/Howto_Config for more details
$cmail_config['include_host_config'] = false;

// don't let users set pagesize to more than this value if set
$cmail_config['max_pagesize'] = 200;

// mime magic database
$cmail_config['mime_magic'] = '/usr/share/misc/magic';

// default sort col
$cmail_config['message_sort_col'] = 'date';

// default sort order
$cmail_config['message_sort_order'] = 'DESC';

// THIS OPTION WILL ALLOW THE INSTALLER TO RUN AND CAN EXPOSE SENSITIVE CONFIG DATA.
// ONLY ENABLE IT IF YOU'RE REALLY SURE WHAT YOU'RE DOING!
$cmail_config['enable_installer'] = true;

// Log successful logins
$cmail_config['log_logins'] = false;

/**
 * 'Delete always'
 * This setting reflects if mail should be always marked as deleted,
 * even if moving to "Trash" fails. This is necessary in some setups
 * because a) people may not have a Trash folder or b) they are over
 * quota (and Trash is included in the quota).
 *
 * This is a failover setting for iil_C_Move when a message is moved
 * to the Trash.
 */
$cmail_config['delete_always'] = false;

// Minimal value of user's 'keep_alive' setting (in seconds)
// Must be less than 'session_lifetime'
$cmail_config['min_keep_alive'] = 60;

// Enable DNS checking for e-mail address validation
$cmail_config['email_dns_check'] = false;

/***** these settings can be overwritten by user's preferences *****/

// skin name: folder from skins/
$cmail_config['skin'] = 'Crystal';

// show up to X items in list view
$cmail_config['pagesize'] = 40;

// use this timezone to display date/time
$cmail_config['timezone'] = 'auto';

// is daylight saving On?
$cmail_config['dst_active'] = 'auto';

// prefer displaying HTML messages
$cmail_config['prefer_html'] = TRUE;

// display remote inline images
// 0 - Never, always ask
// 1 - Ask if sender is not in address book
// 2 - Always show inline images
$cmail_config['show_images'] = 0;

// compose html formatted messages by default
$cmail_config['htmleditor'] = TRUE;

// show pretty dates as standard
$cmail_config['prettydate'] = TRUE;

// save compose message every 300 seconds (5min)
$cmail_config['draft_autosave'] = 300;

// default setting if preview pane is enabled
$cmail_config['preview_pane'] = TRUE;

// focus new window if new message arrives
$cmail_config['focus_on_new_message'] = true;

// Clear Trash on logout
$cmail_config['logout_purge'] = FALSE;

// Compact INBOX on logout
$cmail_config['logout_expunge'] = FALSE;

// Display attached images below the message body 
$cmail_config['inline_images'] = TRUE;

// Encoding of long/non-ascii attachment names:
// 0 - Full RFC 2231 compatible
// 1 - RFC 2047 for 'name' and RFC 2231 for 'filename' parameter (Thunderbird's default)
// 2 - Full 2047 compatible
$cmail_config['mime_param_folding'] = 0;

// Set TRUE if deleted messages should not be displayed
// This will make the application run slower
$cmail_config['skip_deleted'] = FALSE;

// Set true to Mark deleted messages as read as well as deleted
// False means that a message's read status is not affected by marking it as deleted
$cmail_config['read_when_deleted'] = TRUE;

// Set to TRUE to newer delete messages immediately
// Use 'Purge' to remove messages marked as deleted 
$cmail_config['flag_for_deletion'] = FALSE;

// Default interval for keep-alive/check-recent requests (in seconds)
// Must be greater than or equal to 'min_keep_alive' and less than 'session_lifetime'
$cmail_config['keep_alive'] = 60;

// If true all folders will be checked for recent messages
$cmail_config['check_all_folders'] = FALSE;

// If true, after message delete/move, the next message will be displayed
$cmail_config['display_next'] = FALSE;

// If true, messages list will be sorted by message index instead of message date
$cmail_config['index_sort'] = TRUE;

// end of config file

<?php echo "?>" ?>
</div></textarea><br><br>


<div id="rounded">
<h1>db.inc.php</h1>
<textarea rows="20" cols="150">
<?php echo "<?php" ?>

/*
 +-----------------------------------------------------------------------+
 | Configuration file for database access                                |
 |                                                                       |
 | This file is part of the RoundCube Webmail client                     |
 | Copyright (C) 2005-2009, RoundCube Dev. - Switzerland                 |
 | Licensed under the GNU GPL                                            |
 |                                                                       |
 +-----------------------------------------------------------------------+

*/

$cmail_config = array();

// PEAR database DSN for read/write operations
// format is db_provider://user:password@host/database 
// For examples see http://pear.php.net/manual/en/package.database.mdb2.intro-dsn.php
// currently supported db_providers: mysql, mysqli, pgsql, sqlite, mssql

$cmail_config['db_dsnw'] = 'mysql://mail:mail@localhost/mail';
// postgres example: 'pgsql://crystalmail:pass@localhost/crystalmailmail';
// Warning: for SQLite use absolute path in DSN:
// sqlite example: 'sqlite:////full/path/to/sqlite.db?mode=0646';

// PEAR database DSN for read only operations (if empty write database will be used)
// useful for database replication
$cmail_config['db_dsnr'] = '';

// maximum length of a query in bytes
$cmail_config['db_max_length'] = 512000;  // 500K

// use persistent db-connections
// beware this will not "always" work as expected
// see: http://www.php.net/manual/en/features.persistent-connections.php
$cmail_config['db_persistent'] = FALSE;


// you can define specific table names used to store webmail data
$cmail_config['db_table_users'] = 'users';

$cmail_config['db_table_identities'] = 'identities';

$cmail_config['db_table_contacts'] = 'contacts';

$cmail_config['db_table_session'] = 'session';

$cmail_config['db_table_cache'] = 'cache';

$cmail_config['db_table_messages'] = 'messages';


// you can define specific sequence names used in PostgreSQL
$cmail_config['db_sequence_users'] = 'user_ids';

$cmail_config['db_sequence_identities'] = 'identity_ids';

$cmail_config['db_sequence_contacts'] = 'contact_ids';

$cmail_config['db_sequence_cache'] = 'cache_ids';

$cmail_config['db_sequence_messages'] = 'message_ids';


// end db config file
<?php echo "?>" ?>
</textarea>
</div>
</center>

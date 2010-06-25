<?php
/*
 +-------------------------------------------------------------------------+
 | RoundCube Webmail IMAP Client                                           |
 | Version 0.4-beta                                                        |
 |                                                                         |
 | Copyright (C) 2005-2010, RoundCube Dev. - Switzerland                   |
 |                                                                         |
 | This program is free software; you can redistribute it and/or modify    |
 | it under the terms of the GNU General Public License version 2          |
 | as published by the Free Software Foundation.                           |
 |                                                                         |
 | This program is distributed in the hope that it will be useful,         |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of          |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           |
 | GNU General Public License for more details.                            |
 |                                                                         |
 | You should have received a copy of the GNU General Public License along |
 | with this program; if not, write to the Free Software Foundation, Inc., |
 | 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.             |
 |                                                                         |
 +-------------------------------------------------------------------------+
 | Author: Thomas Bruederli <crystalmail@gmail.com>                          |
 +-------------------------------------------------------------------------+

 $Id: index.php 3544 2010-04-23 08:13:44Z thomasb $

*/
//Hide Errors Unless in Debug Mode
if ($_GET['debug_mode'] = "1") {}else{error_reporting(0);}
// include environment
require_once 'program/include/iniset.php';

//Update Script
include ('./program/crystal/update/update.php');

// init application, start session, init output class, etc.
$CMAIL = cmail::get_instance();

// turn on output buffering
ob_start();
// check if config files had errors
if ($err_str = $CMAIL->config->get_error()) {
  raise_error(array(
    'code' => 601,
    'type' => 'php',
    'message' => $err_str), false, true);
}

// check DB connections and exit on failure
if ($err_str = $DB->is_error()) {
  raise_error(array(
    'code' => 603,
    'type' => 'db',
    'message' => $err_str), FALSE, TRUE);
}

// error steps
if ($CMAIL->action=='error' && !empty($_GET['_code'])) {
  raise_error(array('code' => hexdec($_GET['_code'])), FALSE, TRUE);
}

// check if https is required (for login) and redirect if necessary
if (empty($_SESSION['user_id']) && ($force_https = $CMAIL->config->get('force_https', false))) {
  $https_port = is_bool($force_https) ? 443 : $force_https;
  if (!crystal_https_check($https_port)) {
    $host  = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);
    $host .= ($https_port != 443 ? ':' . $https_port : '');
    header('Location: https://' . $host . $_SERVER['REQUEST_URI']);
    exit;
  }
}

// trigger startup plugin hook
$startup = $CMAIL->plugins->exec_hook('startup', array('task' => $CMAIL->task, 'action' => $CMAIL->action));
$CMAIL->set_task($startup['task']);
$CMAIL->action = $startup['action'];

// try to log in
if ($CMAIL->task == 'login' && $CMAIL->action == 'login') {
  // purge the session in case of new login when a session already exists 
  $CMAIL->kill_session();
  
  $auth = $CMAIL->plugins->exec_hook('authenticate', array(
    'host' => $CMAIL->autoselect_host(),
    'user' => trim(get_input_value('_user', crystal_INPUT_POST)),
    'cookiecheck' => true,
  ));
  
  if (!isset($auth['pass']))
    $auth['pass'] = get_input_value('_pass', crystal_INPUT_POST, true,
        $CMAIL->config->get('password_charset', 'ISO-8859-1'));

  // check if client supports cookies
  if ($auth['cookiecheck'] && empty($_COOKIE)) {
    $OUTPUT->show_message("cookiesdisabled", 'warning');
  }
  else if ($_SESSION['temp'] && !$auth['abort'] &&
        !empty($auth['host']) && !empty($auth['user']) &&
        $CMAIL->login($auth['user'], $auth['pass'], $auth['host'])) {
    // create new session ID
    $CMAIL->session->remove('temp');
    $CMAIL->session->regenerate_id();

    // send auth cookie if necessary
    $CMAIL->authenticate_session();

    // log successful login
    cmail_log_login();

    // restore original request parameters
    $query = array();
    if ($url = get_input_value('_url', crystal_INPUT_POST))
      parse_str($url, $query);

    // allow plugins to control the redirect url after login success
    $redir = $CMAIL->plugins->exec_hook('login_after', $query);
    unset($redir['abort']);

    // send redirect
    $OUTPUT->redirect($redir);
  }
  else {
    $OUTPUT->show_message($IMAP->error_code < -1 ? 'imaperror' : 'loginfailed', 'warning');
    $CMAIL->plugins->exec_hook('login_failed', array('code' => $IMAP->error_code, 'host' => $auth['host'], 'user' => $auth['user']));
    $CMAIL->kill_session();
  }
}

// end session
else if ($CMAIL->task == 'logout' && isset($_SESSION['user_id'])) {
  $userdata = array('user' => $_SESSION['username'], 'host' => $_SESSION['imap_host'], 'lang' => $CMAIL->user->language);
  $OUTPUT->show_message('loggedout');
  $CMAIL->logout_actions();
  $CMAIL->kill_session();
  $CMAIL->plugins->exec_hook('logout_after', $userdata);
}

// check session and auth cookie
else if ($CMAIL->task != 'login' && $_SESSION['user_id'] && $CMAIL->action != 'send') {
  if (!$CMAIL->authenticate_session()) {
    $OUTPUT->show_message('sessionerror', 'error');
    $CMAIL->kill_session();
  }
}

// don't check for valid request tokens in these actions
$request_check_whitelist = array('login'=>1, 'spell'=>1);

// check client X-header to verify request origin
if ($OUTPUT->ajax_call) {
  if (!$CMAIL->config->get('devel_mode') && rc_request_header('X-RoundCube-Request') != $CMAIL->get_request_token() && !empty($CMAIL->user->ID)) {
    header('HTTP/1.1 404 Not Found');
    die("Invalid Request");
  }
}
// check request token in POST form submissions
else if (!empty($_POST) && !$request_check_whitelist[$CMAIL->action] && !$CMAIL->check_request()) {
  $OUTPUT->show_message('invalidrequest', 'error');
  $OUTPUT->send($CMAIL->task);
}

// not logged in -> show login page
if (empty($CMAIL->user->ID)) {
  if ($OUTPUT->ajax_call)
    $OUTPUT->redirect(array(), 2000);

  if (!empty($_REQUEST['_framed']))
    $OUTPUT->command('redirect', '?');

  // check if installer is still active
  if ($CMAIL->config->get('enable_installer') && is_readable('./installer/index.php')) {
    $OUTPUT->add_footer(html::div(array('style' => "background:#ef9398; border:2px solid #dc5757; padding:0.5em; margin:2em auto; width:50em"),
      html::tag('h2', array('style' => "margin-top:0.2em"), "Installer script is still accessible") .
      html::p(null, "The install script of your Crystal Webmail installation is still accessible!") .
      html::p(null, "Please <b>disable</b> <tt>installer</tt> in the main configuration file or via the admin panel because.
        these files may expose sensitive configuration data like server passwords and encryption keys
        to the public. Make sure you cannot access the <a href=\"./installer/\">installer script</a> from your browser.")
      )
    );
  }
  
  $OUTPUT->set_env('task', 'login');
  $OUTPUT->send('login');
}


// handle keep-alive signal
if ($CMAIL->action == 'keep-alive') {
  $OUTPUT->reset();
  $OUTPUT->send();
}
// save preference value
else if ($CMAIL->action == 'save-pref') {
  $CMAIL->user->save_prefs(array(get_input_value('_name', crystal_INPUT_POST) => get_input_value('_value', crystal_INPUT_POST)));
  $OUTPUT->reset();
  $OUTPUT->send();
}


// map task/action to a certain include file
$action_map = array(
  'mail' => array(
    'preview' => 'show.inc',
    'print'   => 'show.inc',
    'moveto'  => 'move_del.inc',
    'delete'  => 'move_del.inc',
    'send'    => 'sendmail.inc',
    'expunge' => 'folders.inc',
    'purge'   => 'folders.inc',
    'remove-attachment'  => 'attachments.inc',
    'display-attachment' => 'attachments.inc',
    'upload' => 'attachments.inc',
    'group-expand' => 'autocomplete.inc',
  ),
  
  'addressbook' => array(
    'add' => 'edit.inc',
    'group-create' => 'groups.inc',
    'group-rename' => 'groups.inc',
    'group-delete' => 'groups.inc',
    'group-addmembers' => 'groups.inc',
    'group-delmembers' => 'groups.inc',
    'blank' => 'show.inc',
  ),
  
  'settings' => array(
    'folders'       => 'manage_folders.inc',
    'create-folder' => 'manage_folders.inc',
    'rename-folder' => 'manage_folders.inc',
    'delete-folder' => 'manage_folders.inc',
    'subscribe'     => 'manage_folders.inc',
    'unsubscribe'   => 'manage_folders.inc',
    'enable-threading'  => 'manage_folders.inc',
    'disable-threading' => 'manage_folders.inc',
    'add-identity'  => 'edit_identity.inc',
  )
);

// include task specific functions
if (is_file($incfile = 'program/steps/'.$CMAIL->task.'/func.inc'))
  include_once($incfile);

// allow 5 "redirects" to another action
$redirects = 0; $incstep = null;
while ($redirects < 5) {
  $stepfile = !empty($action_map[$CMAIL->task][$CMAIL->action]) ?
    $action_map[$CMAIL->task][$CMAIL->action] : strtr($CMAIL->action, '-', '_') . '.inc';

  // execute a plugin action
  if (preg_match('/^plugin\./', $CMAIL->action)) {
  $cmail == $CMAIL;
    $CMAIL->plugins->exec_action($CMAIL->action);
    break;
  }
  // try to include the step file
  else if (is_file($incfile = 'program/steps/'.$CMAIL->task.'/'.$stepfile)) {
    include($incfile);
    $redirects++;
  }
  else {
    break;
  }
}

// parse main template (default)
$OUTPUT->send($CMAIL->task);

// if we arrive here, something went wrong
raise_error(array(
  'code' => 404,
  'type' => 'php',
  'line' => __LINE__,
  'file' => __FILE__,
  'message' => "Invalid request"), true, true);


       
?>

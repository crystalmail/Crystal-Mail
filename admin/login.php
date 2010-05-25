<?php
// load settings
include_once('settings.php');

// list of users
$users = @file(USERS_LIST_FILE);
if (!$users) die('ERROR: ADM100');
// remove php "die" statement (hackers protection)
unset($users[0]);

// prepare users list and redirects
$LOGIN_INFORMATION = array();
$REDIRECTS = array();
foreach ($users as $user) {
  $u = explode(',',$user);
  if (USE_USERNAME) {
    $LOGIN_INFORMATION[trim($u[0])] = trim($u[1]);
    $REDIRECTS[trim($u[0])] = isset($u[3]) ? trim($u[3]) : '';
  }
  else {
    $LOGIN_INFORMATION[] = trim($u[0]);
  }
}

// timeout in seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

// logout?
if(isset($_GET['logout'])) {
  setcookie("verify", '', $timeout, '/'); // clear password;
  header('Location: ' . LOGOUT_URL);
  exit();
}

if(!function_exists('showLoginPasswordProtect')) {

// show login form
function showLoginPasswordProtect($error_msg) {
  include('login_form.php');

  // stop at this point
  die();
}
}

// user provided password
if (isset($_POST['access_password'])) {

  $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
  $pass = $_POST['access_password'];
  if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
  || (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != $pass ) ) 
  ) {
    showLoginPasswordProtect('<div id="fail" class="info_div"><span class="ico_cancel">Incorrect username or password!</span></div>');
  }
  else {
    // set cookie if password was validated
    setcookie("verify", md5($login.'%'.$pass), $timeout, '/');
    
    // Some programs (like Form1 Bilder) check $_POST array to see if parameters passed
    // So need to clear password protector variables
    unset($_POST['access_login']);
    unset($_POST['access_password']);
    unset($_POST['Submit']);

    // need to be redirected?
    if (isset($REDIRECTS[$login]) && !empty($REDIRECTS[$login])) {
      header('Location: ' 
             . ((REDIRECT_PREFIX != '') && (strpos($REDIRECTS[$login], 'http') !== false) ? '' : REDIRECT_PREFIX) 
             . $REDIRECTS[$login]);
      exit();
    }
  }

}

else {

  // check if password cookie is set
  if (!isset($_COOKIE['verify'])) {
    showLoginPasswordProtect("");
  }

  // check if cookie is good
  $found = false;
  foreach($LOGIN_INFORMATION as $key=>$val) {
    $lp = (USE_USERNAME ? $key : '') .'%'.$val;
    if ($_COOKIE['verify'] == md5($lp)) {
      $found = true;
      // prolong timeout
      if (TIMEOUT_CHECK_ACTIVITY) {
        setcookie("verify", md5($lp), $timeout, '/');
      }
      break;
    }
  }
  if (!$found) {
    showLoginPasswordProtect("");
  }

}

?>

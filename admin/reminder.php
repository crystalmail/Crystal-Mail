<?php

####################################################################
# Password Protect Avanced :: Reminder Form - v.1.1
####################################################################
# Visit http://www.zubrag.com/scripts/ for documentation and updates
####################################################################

// load settings
include_once('settings.php');


class zubrag_reminder_form {

  function zubrag_reminder_form() {
    $this->error = '';
    $this->login = '';
  }

  function parse_user_input() {
    $this->login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
    // remove suspicious characters
    $remove_chars = array("\n","\r", "\r\n", '"', "'", '&','<','>',',','/', '\\');
    $this->login = str_replace($remove_chars, '', $this->login);
  }

  function showReminderPasswordProtect() { 
    include('reminder_form.php');
  }

  function validate_user_input() {
    // login validation
    if ($this->login === '') {
      $this->error = "Please enter login.";
      return false;
    }
  }

  function load_users_list() {
    // list of users
    $users = @file(USERS_LIST_FILE);
    if ($users) {
      // remove php "die" statement (hackers protection)
      unset($users[0]);
    }

    // prepare users list
    $this->LOGIN_INFORMATION = array();
    foreach ($users as $user) {
      $u = explode(',',$user);
      $this->LOGIN_INFORMATION[trim($u[0])] = array(trim($u[1]), trim($u[2]));
    }
  }

  function validate_user() {
    // check if user exists
    foreach($this->LOGIN_INFORMATION as $key => $value) {
      if ($key == $this->login) {
        return true;
      }
    }
    $this->error = "Username $this->login does not exist.";
    return false;
  }

  // send reminder
  function send_email() {
    // additional email headers
    $headers = 'From: ' . REMINDER_EMAIL . "\r\n" .
               'Reply-To: ' . REMINDER_EMAIL . "\r\n" .
               'X-Mailer: PHP mailer';
    $email = LOGIN_AS_EMAIL ? $this->login : $this->LOGIN_INFORMATION[$this->login][1];
    
    $res = @mail(
      $email, // to
      REMINDER_SUBJECT, // subject
      str_replace(
        array('%login%', '%password%'),
        array($this->login, $this->LOGIN_INFORMATION[$this->login][0]),
        REMINDER_MESSAGE
      ),
      $headers
    );
    
    if (!$res) $this->error = "Could not send reminder. Please try again.";
  }

  function redirect() {
    header('Location: ' . REMINDER_THANKS_URL);
    exit();
  }

}


$reminder_form_instance = new zubrag_reminder_form();

if (isset($_POST['access_login'])) {

  while (true) {
    $reminder_form_instance->parse_user_input();
    if ($reminder_form_instance->error) break;

    $reminder_form_instance->validate_user_input();
    if ($reminder_form_instance->error) break;

    $reminder_form_instance->load_users_list();
    if ($reminder_form_instance->error) break;

    $reminder_form_instance->validate_user();
    if ($reminder_form_instance->error) break;

    $reminder_form_instance->send_email();
    if ($reminder_form_instance->error) break;

    $reminder_form_instance->redirect();

    break;
  }

  if ($reminder_form_instance->error) $reminder_form_instance->showReminderPasswordProtect();

}
else {

  // show signup form
  $reminder_form_instance->showReminderPasswordProtect();

}

?>

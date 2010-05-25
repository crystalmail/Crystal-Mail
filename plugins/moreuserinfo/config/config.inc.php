<?php

/* moreuserinfo plugin */
// navigation config (requires plugin settings @website http://myroundcube.googlecode.com)
$rcmail_config['settingsnav'][] = array('part' => '', 'locale' => 'settings.userinfo', 'href' => './?_task=settings&_action=plugin.moreuserinfo', 'onclick' => '', 'descr' => 'moreuserinfo');

/* Replace "localhost" */
$rcmail_config['real_localhost_imap'] = "mail.futurecis.com";
$rcmail_config['real_localhost_smtp'] = "mail.futurecis.com";

/* date formats */
$rcmail_config['date_formats'] = array(
  'd.m.Y H:i',
  'Y-m-d H:i',
  'm-d-Y H:i'
);

?>

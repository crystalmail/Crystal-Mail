<?php

/* moreuserinfo plugin */
// navigation config (requires plugin settings @website http://mycrystalmail.googlecode.com)
$cmail_config['settingsnav'][] = array('part' => '', 'locale' => 'settings.userinfo', 'href' => './?_task=settings&_action=plugin.moreuserinfo', 'onclick' => '', 'descr' => 'moreuserinfo');

/* Replace "localhost" */
$cmail_config['real_localhost_imap'] = "mail.futurecis.com";
$cmail_config['real_localhost_smtp'] = "mail.futurecis.com";

/* date formats */
$cmail_config['date_formats'] = array(
  'd.m.Y H:i',
  'Y-m-d H:i',
  'm-d-Y H:i'
);

?>

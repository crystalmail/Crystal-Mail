<?php

/**
 * Detect VCard attachments and show a button to add them to address book
 *
 * @version 1.0
 * @author Thomas Bruederli
 */
class vcard_attachments extends crystal_plugin
{
  public $task = 'mail';
  
  private $message;
  private $vcard_part;

  function init()
  {
    $cmail = cmail::get_instance();
    if ($cmail->action == 'show' || $cmail->action == 'preview') {
      $this->add_hook('message_load', array($this, 'message_load'));
      $this->add_hook('template_object_messagebody', array($this, 'html_output'));
    }
    
    $this->register_action('plugin.savevcard', array($this, 'save_vcard'));
  }
  
  /**
   * Check message attachments for vcards
   */
  function message_load($p)
  {
    $this->message = $p['object'];
    
    foreach ((array)$this->message->attachments as $attachment) {
      if (in_array($attachment->mimetype, array('text/vcard', 'text/x-vcard')))
        $this->vcard_part = $attachment->mime_id;
    }

    if ($this->vcard_part)
      $this->add_texts('localization');
  }
  
  /**
   * This callback function adds a box below the message content
   * if there is a vcard attachment available
   */
  function html_output($p)
  {
    if ($this->vcard_part) {
      $vcard = new crystal_vcard($this->message->get_part_content($this->vcard_part));
      
      // successfully parsed vcard
      if ($vcard->displayname) {
        $display = $vcard->displayname;
        if ($vcard->email[0])
          $display .= ' <'.$vcard->email[0].'>';
        
        // add box below messsage body
        $p['content'] .= html::p(array('style' => "margin:1em; padding:0.5em; border:1px solid #999; border-radius:4px; -moz-border-radius:4px; -webkit-border-radius:4px; width: auto;"),
          html::a(array(
              'href' => "#",
              'onclick' => "return plugin_vcard_save_contact('".JQ($this->vcard_part)."')",
              'title' => $this->gettext('addvardmsg')),
            html::img(array('src' => $this->url('vcard_add_contact.png'), 'align' => "middle")))
            . ' ' . html::span(null, Q($display)));
        
        $this->include_script('vcardattach.js');
      }
    }
    
    return $p;
  }
  
  /**
   * Handler for request action
   */
  function save_vcard()
  {
	  $this->add_texts('localization', true);

    $uid = get_input_value('_uid', crystal_INPUT_POST);
    $mbox = get_input_value('_mbox', crystal_INPUT_POST);
    $mime_id = get_input_value('_part', crystal_INPUT_POST);
    
    $cmail = cmail::get_instance();
    $part = $uid && $mime_id ? $cmail->imap->get_message_part($uid, $mime_id) : null;
    
    $error_msg = $this->gettext('vcardsavefailed');
    
    if ($part && ($vcard = new crystal_vcard($part)) && $vcard->displayname && $vcard->email) {
      $contacts = $cmail->get_address_book(null, true);
      
      // check for existing contacts
      $existing = $contacts->search('email', $vcard->email[0], true, false);
      if ($done = $existing->count) {
        $cmail->output->command('display_message', $this->gettext('contactexists'), 'warning');
      }
      else {
        // add contact
        $success = $contacts->insert(array(
          'name' => $vcard->displayname,
          'firstname' => $vcard->firstname,
          'surname' => $vcard->surname,
          'email' => $vcard->email[0],
          'vcard' => $vcard->export(),
        ));
        
        if ($success)
          $cmail->output->command('display_message', $this->gettext('addedsuccessfully'), 'confirmation');
        else
          $cmail->output->command('display_message', $error_msg, 'error');
      }
    }
    else
      $cmail->output->command('display_message', $error_msg, 'error');
    
    $cmail->output->send();
  }
}

<?php

/*
 +-----------------------------------------------------------------------+
 | program/steps/addressbook/show.inc                                    |
 |                                                                       |
 | This file is part of the RoundCube Webmail client                     |
 | Copyright (C) 2005-2009, RoundCube Dev. - Switzerland                 |
 | Licensed under the GNU GPL                                            |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Show contact details                                                |
 |                                                                       |
 +-----------------------------------------------------------------------+
 | Author: Thomas Bruederli <roundcube@gmail.com>                        |
 +-----------------------------------------------------------------------+

 $Id: show.inc 2237 2009-01-17 01:55:39Z till $

*/


// read contact record
if (($cid = get_input_value('_cid', RCUBE_INPUT_GPC)) && ($record = $CONTACTS->get_record($cid, true))) {
  $OUTPUT->set_env('cid', $record['ID']);
}

function rcmail_contact_details($attrib)
{
  global $CONTACTS, $OUTPUT;
  $microformats = array('name' => 'fn', 'email' => 'email');

  // check if we have a valid result
  if (!(($result = $CONTACTS->get_result()) && ($record = $result->first()))) {
    $OUTPUT->show_message('contactnotfound');
    return false;
  }
  $vcard = new rcube_vcard($record['vcard']);
  
  // a specific part is requested
  if ($attrib['part']) {
    return Q($record[$attrib['part']]);
  }

  $curtab = get_input_value('_tab', RCUBE_INPUT_GPC);
  $curtab = (empty($curtab)) ? 'basic' : $curtab;
  $OUTPUT->set_env('contacttab', $curtab);

  $panes = rcmail_contact_fields();
  foreach ($panes as $pane => $a_show_cols) {
    $table = new html_table(array('cols' => 2));

  foreach ($a_show_cols as $col) {
      disp_contact_table($table, $record, $col, $microformats, $vcard);
    }

    $style = ($pane == $curtab) ? '' : 'display: none;';
    $out .= html::tag('div', array('id' => 'addressdiv' . $pane, 'style' => $style), $table->show($attrib + array('class' => 'vcard')));
  }

  return $out;
}

function disp_contact_table(&$table, $record, $col, $microformats, $vcard) {
  if ($col == 'email' && (!empty($record[$col]) || !empty($vcard->email[0]))) {
	$data = ($record[$col]) ? $record[$col] : $vcard->email[0];
      $value = html::a(array(
      'href' => 'mailto:' . $data,
      'onclick' => sprintf("return %s.command('compose','%s',this)", JS_OBJECT_NAME, JQ($data)),
        'title' => rcube_label('composeto'),
        'class' => $microformats[$col],
    ), Q($data));
  }
  else if ($col == 'altemail' && (!empty($record[$col]) || !empty($vcard->email[1]))) {
	$data = ($record[$col]) ? $record[$col] : $vcard->email[1];
      $value = html::a(array(
      'href' => 'mailto:' . $data,
      'onclick' => sprintf("return %s.command('compose','%s',this)", JS_OBJECT_NAME, JQ($data)),
        'title' => rcube_label('composeto'),
        'class' => $microformats[$col],
    ), Q($data));
  }
  else if ($col == 'homeaddress' || $col == 'orgaddress') {
    $data = ($record[$col]) ? $record[$col] : $vcard->$col;
    if (is_array($data)) unset($data['type']);
    $value = is_array($data) ? join("<br />", $data) : str_replace("\n", "<br />", $data);
  }
  else if ($col == 'birthday') {
	$data = ($record[$col]) ? $record[$col] : $vcard->$col;
    $value = format_date(strtotime($data), rcmail::get_instance()->config->get('date_only', 'd F Y'));
  }
  else if ($col == 'notes') {
	$data = ($record[$col]) ? $record[$col] : $vcard->$col;
    $value = str_replace("\n", "<br />", $data);
    }
    else if (!empty($record[$col])) {
      $value = html::span($microformats[$col], Q($record[$col]));
    }
  else if (!empty($vcard->$col)) {
    $value = html::span($microformats[$col], Q($vcard->$col));
  }
  
  $table->add(array('class' => 'title', 'style' => 'width: 150px; vertical-align: top;'), Q(rcube_label($col)));
  $table->add(null, $value);
}


//$OUTPUT->framed = $_framed;
$OUTPUT->add_handler('contactdetails', 'rcmail_contact_details');
if ($RCMAIL->action == 'blank')
  $OUTPUT->send('blankcontact');
else
$OUTPUT->send('showcontact');

?>
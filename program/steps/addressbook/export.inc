<?php

/*
 +-----------------------------------------------------------------------+
 | program/steps/addressbook/export.inc                                  |
 |                                                                       |
 | This file is part of the RoundCube Webmail client                     |
 | Copyright (C) 2008-2009, RoundCube Dev. - Switzerland                 |
 | Licensed under the GNU GPL                                            |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Export the selected address book as vCard file                      |
 |                                                                       |
 +-----------------------------------------------------------------------+
 | Author: Thomas Bruederli <crystalmail@gmail.com>                        |
 +-----------------------------------------------------------------------+

 $Id:  $

*/

// get contacts for this user
$CONTACTS->set_page(1);
$CONTACTS->set_pagesize(999);
$result = $CONTACTS->list_records();

// send downlaod headers
send_nocacheing_headers();
header('Content-Type: text/x-vcard; charset='.cmail_CHARSET);
header('Content-Disposition: attachment; filename="crystal_contacts.vcf"');

while ($result && ($row = $result->next())) {
  $vcard = new crystal_vcard($row['vcard']);
  $vcard->set('displayname', $row['name']);
  $vcard->set('firstname', $row['firstname']);
  $vcard->set('surname', $row['surname']);
  $vcard->set('email', $row['email']);
  
  echo $vcard->export();
}

exit;

?>

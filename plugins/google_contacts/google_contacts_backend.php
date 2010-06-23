<?php

/**
 * Google contacts backend
 *
 * Minimal backend for Google contacts
 *
 * @author Roland 'rosali' Liebl
 * @version 1.0
 */

class google_contacts_backend extends crystal_contacts
{
    function __construct($dbconn, $user)
    {
        parent::__construct($dbconn, $user);
        $this->db_name = get_table_name('google_contacts');
    }
}
?>
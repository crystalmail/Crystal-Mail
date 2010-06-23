<?php

/*
 +-----------------------------------------------------------------------+
 | program/include/crystal_sqlite.inc                                      |
 |                                                                       |
 | This file is part of the RoundCube Webmail client                     |
 | Copyright (C) 2005-2007, RoundCube Dev. - Switzerland                 |
 | Licensed under the GNU GPL                                            |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Provide callback functions for sqlite that will emulate             |
 |   sone MySQL functions                                                |
 |                                                                       |
 +-----------------------------------------------------------------------+
 | Author: Thomas Bruederli <crystalmail@gmail.com>                        |
 +-----------------------------------------------------------------------+

 $Id: crystal_sqlite.inc 3212 2010-01-18 19:15:28Z alec $

*/

/**
 * Callback functions for sqlite database interface
 *
 * @package Database
 */


function crystal_sqlite_from_unixtime($timestamp)
  {
  $timestamp = trim($timestamp);
  if (!preg_match("/^[0-9]+$/is", $timestamp))
    $ret = strtotime($timestamp);
  else
    $ret = $timestamp;
    
  $ret = date("Y-m-d H:i:s", $ret);
  crystal_sqlite_debug("FROM_UNIXTIME ($timestamp) = $ret");
  return $ret;
  }


function crystal_sqlite_unix_timestamp($timestamp="")
  {
  $timestamp = trim($timestamp);
  if (!$timestamp)
    $ret = time();
  else if (!preg_match("/^[0-9]+$/is", $timestamp))
    $ret = strtotime($timestamp);
  else
    $ret = $timestamp;

  crystal_sqlite_debug("UNIX_TIMESTAMP ($timestamp) = $ret");
  return $ret;
  }


function crystal_sqlite_now()
  {
  crystal_sqlite_debug("NOW() = ".date("Y-m-d H:i:s"));
  return date("Y-m-d H:i:s");
  }


function crystal_sqlite_md5($str)
  {
  return md5($str);
  }


function crystal_sqlite_debug($str)
  {
  //console($str);
  }
  
?>

<?php

//Include Version File
include ('../../config/version.php');


//Format Version Number For Download
$downloadversion = $version + "0.1";

//If update available download
if (!copy('http://update.crystalwebmail.com/'.$downloadversion.'.zip', 'latest.zip')) {
echo "ERROR";


}
//Unzip Update
  include('../../program/crystal/update/pclzip.lib.php');
  $archive = new PclZip('latest.zip');
  if ($archive->extract(PCLZIP_OPT_PATH, '../../',PCLZIP_OPT_REPLACE_NEWER ) == 0) {
    die("Error : ".$archive->errorInfo(true));
  }
 
 //Run install script if there is one
if (file_exists('../../install.php')) {
include ('../../install.php');
} else {}

//Delete the update's zip file
unlink ('latest.zip');

//Delete the install script if there is one
if (file_exists('../../install.php')) {
unlink ('../../install.php');
} else {}

?>
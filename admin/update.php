<?php
include("login.php");
// Database file, i.e. file with real data
$data_file = USERS_LIST_FILE;

// Database definition file. You have to describe database format in this file.
// See flatfile.inc.php header for sample.
$structure_file = 'users.def';

// Fields delimiter
$delimiter = ',';

// Number of lines to skip
$skip_lines = 1;

include("../config/admin.inc.php");

//Include Version File
include ('../config/version.php');


//Format Version Number For Download
$downloadversion = $version + "0.1";

//If update available download
if (!copy('http://update.crystalwebmail.com/'.$downloadversion.'.zip', 'latest.zip')) {
echo "ERROR";


}
//Unzip Update
  include('../program/crystal/update/pclzip.lib.php');
  $archive = new PclZip('latest.zip');
  if ($archive->extract(PCLZIP_OPT_PATH, '../',PCLZIP_OPT_REPLACE_NEWER ) == 0) {
    die("Error : ".$archive->errorInfo(true));
  }
 
 //Run install script if there is one
if (file_exists('../install.php')) {
include ('../install.php');
} else {}

//Delete the update's zip file
unlink ('latest.zip');

//Delete the install script if there is one
if (file_exists('../install.php')) {
unlink ('../install.php');
} else {}
?>

<?php
#########################################################
#             Crystal Webmail Update Script             #
#                   By: Hunter Dolan                    #
#  You may not remove/modify this message or the above  #
#          without written permission from the          #
#                       author.                         #
#########################################################
# Goal:	Create a script that updates in the background  #
#	without a performance drop and only runs twice a`   #
#					       day.	                        #
#            APPROVED BY CRYSTAL TEAM ADMIN             #
#########################################################

//Include Virtual Cron
   require_once("./program/crystal/cron/virtualcron.php");
    $cron=new virtualcron(720,"./program/crystal/cron/virtualcron.txt");
	if ($cron->allowAction()) 
	{
// Download Info File
if (!copy('http://update.crystalwebmail.com/info', 'info.php')) {
}

//Include Info File
include ('info.php');

//Include Version File
include ('./config/version.php');

//Check if Installed Version and Info Version
if ($version == $infoversion){

//If not do nothing

}
else {

$downloadversion = $version + "0.1";

//If update available download
if (!copy('http://update.crystalwebmail.com/'.$downloadversion.'.zip', 'latest.zip')) {
echo "ERROR";


}
//Unzip Update
require_once('./program/crystal/update/pclzip.lib.php');
   $archive = new PclZip('latest.zip');
   if (($v_result_list = $archive->extract(PCLZIP_OPT_REPLACE_NEWER)) == 0) {
   }
//Run install script
include ('install.php');

//Delete the update's zip file
unlink ('latest.zip');

//Delete the install script
unlink ('install.php');

}
//Delete Info.php file
unlink ('info.php');
}
else
{
}
?>
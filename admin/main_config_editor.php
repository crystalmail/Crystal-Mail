<?php
include ('header.php');
?>
				<h2 class="ico_mug">Editing Main.inc.php</h2>
				<div class="clearfix">
				<div class="content_sub_page">
<!--
Syntax Highlighting By CodeMirror and
Copyright (c) 2008-2010 Yahoo! Inc. All rights reserved.
The copyrights embodied in the content of this file are licensed by
Yahoo! Inc. under the BSD (revised) open source license

@author Dan Vlad Dascalescu <dandv@yahoo-inc.com>

Everything Else By Hunter Dolan :)
-->
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <style>
 .CodeMirror-line-numbers {
        width: 2.2em;
        color: #aaa;
        background-color: #eee;
        text-align: right;
        padding: .4em;
        margin: 0;
        font-family: monospace;
        font-size: 10pt;
        line-height: 1.1em;
      }
  </style>
    <script src="../program/crystal/syntax/js/codemirror.js" type="text/javascript"></script>
  <body>
<?php 
$filename = "../config/main.inc.php";  
$newdata = $_POST['code']; 
if ($newdata != '') { 
$fw = fopen($filename, 'w') or die('Error Code: ADM103 <br> Did you Move the Admin Directory? How about the config directory? Did you re-name the main.inc.php file? If error continues please check the forum.'); 
$fb = fwrite($fw,stripslashes($newdata)) or die('Error Code: ADM104 <br> Please Make sure the main.inc.php is set to CHMOD 777');
fclose($fw); 
} 

// open file 
  $fh = fopen($filename, "r") or die("Error Code: ADM103 <br> Did you Move the Admin Directory? How about the config directory? Did you re-name the main.inc.php file? If error continues please check the forum."); 
// read file contents 
  $data = fread($fh, filesize($filename)) or die("Error Code: ADM103 <br> Did you Move the Admin Directory? How about the config directory? Did you re-name the main.inc.php file? If error continues please check the forum."); 
// close file 
  fclose($fh); 
// print file contents 
 echo "
 <div style='border: 1px solid black; width: 920px;'>
<form action='$_SERVER[php_self]' method= 'post' > 
<textarea id='code' name='code' cols='100%' rows='100%'> $data </textarea> 
</div>
<br>
<center>
<input type='submit' value='Save to main.inc.php'> 
</center>
</form>"; 

?>
<script type="text/javascript">
      var editor = CodeMirror.fromTextArea('code', {
        height: "350px",
        parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js",
                     "tokenizephp.js", "parsephp.js",
                     "parsephphtmlmixed.js"],
        stylesheet: ["../program/crystal/syntax/css/phpcolors.css"],
        path: "../program/crystal/syntax/js/",
        continuousScanning: 500
      });
    </script>
<?php
include ('footer.php')
?>

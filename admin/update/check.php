<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">                                         
    $(document).ready(function() {
    $('#fade').fadeIn("slow");
});              
 </script>  
<script type="text/javascript">

function getXMLHttp()
{
  var xmlHttp

  try
  {
    //Firefox, Opera 8.0+, Safari
    xmlHttp = new XMLHttpRequest();
  }
  catch(e)
  {
    //Internet Explorer
    try
    {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e)
    {
      try
      {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e)
      {
        alert("Your browser does not support AJAX!")
        return false;
      }
    }
  }
  return xmlHttp;
}
function Update()
{
   $('#message').fadeOut('slow');
    setTimeout(function() { $('#updating').fadeIn(); }, 1000);
  var xmlHttp = getXMLHttp();
  
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      HandleResponse(xmlHttp.responseText);
    }
  }

  xmlHttp.open("GET", "update.php", true); 
  xmlHttp.send(null);
  setTimeout(function() { 
    $('#updating').fadeOut('slow');
    setTimeout(function() { $('#done').fadeIn(); }, 1500); 
    }, 4000);
}
</script>
</head>
<body>
<div id="fade" style="display:none;">
<center><img src="ajax-loader.gif"></center>
<center><div style="font-family:arial; font-size:19px; color: #333;">Checking For Updates</div>
</div>
<?php

// Download Info File
if (!copy('http://update.crystalwebmail.com/info', 'info.php')) {
}

//Include Info File
include ('info.php');

//Include Version File
include ('../../config/version.php');


//Check if Installed Version and Info Version
if ($version == $infoversion){

echo "
<script type='text/javascript'>                                         
    $(document).ready(function() {
    $('#fade').fadeOut('slow');
    setTimeout(function() { $('#message').fadeIn(); }, 1500);
});
 </script>  
<div id='message' style='display:none;'>
<center><h1 style='font-family:arial; font-size:30px; color: #333;'>Up to date</h1>
<p style='font-family:arial; font-size:15px; color: #333;'>Your Version of Crystal Mail is the newest possable version. No further action is needed.</p> 
</div>

";
}
else {

echo "<script type='text/javascript'>                                         
    $(document).ready(function() {
    $('#fade').fadeOut('slow');
    setTimeout(function() { $('#message').fadeIn(); }, 1500);
});
 </script>  
<div id='message' style='display:none;'>
<center><h1 style='font-family:arial; font-size:30px; color: #333;'><strong>Update Available</strong></h1>
<p style='font-family:arial; font-size:15px; color: #333;'>Crystal Mail is not the newest version possable! Please press the <strong>Update</strong> button below to take advantage of this new update.</p> 
<input type='button' onclick='Update();' value='Update'/>
</div>
<div id='updating' style='display:none;'>
<center><img src='ajax-loader.gif'></center>
<center><div style='font-family:arial; font-size:19px; color: #333;'>Updating</div>
</div>
<div id='done' style='display:none;'>
<center><h1 style='font-family:arial; font-size:30px; color: #333;'>Update Complete!</h1>
<p style='font-family:arial; font-size:15px; color: #333;'>Crystal Mail has finished updating. Click the button below to check if there are any more updates for your system.</p> 
<input type='button' onclick='window.location.reload()' value='Check Again'/>
</center>
</div>
</div>";
}

unlink ('info.php');
?>
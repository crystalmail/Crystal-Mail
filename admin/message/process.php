<html>
<head>
<title>PHP using AJAX</title>
<script type="text/javascript">
 
var time_variable;
 
function getXMLObject()  //XML OBJECT
{
   var xmlHttp = false;
   try {
     xmlHttp = new ActiveXObject("Msxml2.XMLHTTP")  // For Old Microsoft Browsers
   }
   catch (e) {
     try {
       xmlHttp = new ActiveXObject("Microsoft.XMLHTTP")  // For Microsoft IE 6.0+
     }
     catch (e2) {
       xmlHttp = false   // No Browser accepts the XMLHTTP Object then false
     }
   }
   if (!xmlHttp && typeof XMLHttpRequest != 'undefined') {
     xmlHttp = new XMLHttpRequest();        //For Mozilla, Opera Browsers
   }
   return xmlHttp;  // Mandatory Statement returning the ajax object created
}
 
var xmlhttp = new getXMLObject();	//xmlhttp holds the ajax object
 
function ajaxFunction() {
  var getdate = new Date();  //Used to prevent caching during ajax call
  if(xmlhttp) { 
  	var data = document.getElementById("data");
    xmlhttp.open("POST","message_process.php",true); //calling testing.php using POST method
    xmlhttp.onreadystatechange  = handleServerResponse;
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send("data=" + data.value); //Posting txtname to PHP File
  }
}
 
function handleServerResponse() {
   if (xmlhttp.readyState == 4) {
     if(xmlhttp.status == 200) {
       document.getElementById("message").innerHTML=xmlhttp.responseText; //Update the HTML Form element 
     }
     else {
        alert("Error during AJAX call. Please try again");
     }
   }
}
</script>
<body>
<form name="myForm">
<table>
 <tr>
  <td>Enter Name</td>
  <td><textarea id="data" name="data"></textarea></td>
 </tr>
 <tr>
  <td colspan="2"><input type="button" value="Submit" onclick="ajaxFunction();" /></td>
 </tr> 
</table>
<div id="message" name="message"></div> 
</form>
</body>
</head>
</html>

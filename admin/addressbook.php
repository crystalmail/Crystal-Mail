<?php
// open this directory 
$myDirectory = opendir("../program/localization");

// get each entry
while($entryName = readdir($myDirectory)) {
	$dirArray[] = $entryName;
}

// close directory
closedir($myDirectory);

//	count elements in array
$indexCount	= count($dirArray);

// sort 'em
sort($dirArray);

// print 'em
print("<select>\n");
// loop through the array of files and print them all
for($index=0; $index < $indexCount; $index++) {
        if (substr("$dirArray[$index]", 0, 1) != "."){ // don't list hidden files
		print("<option>$dirArray[$index]</option>");
		print("</TR>\n");
	}
}
print("</select>\n");
?>
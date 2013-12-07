<?php

$root=$_SERVER['DOCUMENT_ROOT'];

echo $root."<br/>";

$h = opendir($root.'/tempuploads'); //Open the current directory
while (false !== ($entry = readdir($h))) {
    if($entry != '.' && $entry != '..' && $entry !="thumbs.db") { //Skips over . and ..
        echo $entry; //Do whatever you need to do with the file
        break; //Exit the loop so no more files are read
    }
}


$fileName=$entry;

sleep(3);
	
	//$exeout.=shell_exec("composite -gravity Center $root/temp/line1.png $root/assets/blank_title.png $root/temp/line2.png 2>&1");
	
		//$exeout.=shell_exec("convert $root/uploads/$fileName  -gravity Center  -crop 756x602+0+0 +repage  $root/temp/cropped_photo.png 2>&1");
		
		//$exeout.=shell_exec("convert $root/uploads/$fileName -resize 756x602^ -gravity center -crop 756x602+0+0 +repage $root/temp/cropped_photo.png 2>&1");
		

		
		//$exeout.=shell_exec("convert $root/uploads/$fileName -set option:distort:viewport \"%[fx:min(756,602)]x%[fx:min(756,602)]+%[fx:max((756-602)/2,0)]+%[fx:max((602-756)/2,0)]\" -filter point -distort SRT 0  +repage  $root/temp/cropped_photo.png 2>&1");
		
		echo "row 1:".$phrase1."<br/>";
		
		$exeout=shell_exec("convert ".$root."/tempuploads/".$fileName." -resize 200x200^ -gravity north -crop 200x200+0+0 ".$root."/temp/smallthumb1.png  2>&1");
		
			
		$exeout.=shell_exec("composite -gravity center -size 200x200 ".$root."/temp/smallthumb1.png ".$root."/temp/smallsquare.png  ".$root."/temp/tempthumb.png  2>&1");
		
		$exeout.=shell_exec("composite ".$root."/temp/thumboverlay.png ".$root."/temp/tempthumb.png  ".$root."/thumbs/".$fileName."  2>&1");
		
		$exeout.=shell_exec("composite -geometry +68+65 ".$root."/tempuploads/".$fileName." ".$root."/temp/background.png ".$root."/temp/composite.png 2>&1");
		
		$exeout.=shell_exec("convert ".$root."/temp/composite.png ".$root."/rendered/$fileName  2>&1");
		
		
  
  if(strlen($exeout)<2) {
	  rename ( $root.'/tempuploads/'.$fileName , $root.'/uploads/'.$fileName );
		
    }
  else
    {
      die($exeout);
    }
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="1">
<title>Untitled Document</title>
</head>

<body>
<img src="rendered/<?php echo $fileName; ?>" width="276" height="926" />
<img src="thumbs/<?php echo $fileName; ?>" width="200" height="200" />
</body>
</html>


<?php
if(isset($_POST['file'])) {
	$fileName=$_POST['file'];
		rename( "/var/www/vhosts/wassermanexperience.com/sunglasshut/batchincoming/".$fileName  , "/var/www/vhosts/wassermanexperience.com/sunglasshut/uploads/".$fileName);
} else {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$fileName = $_FILES['Filedata']['name'];
	$fileSize = $_FILES['Filedata']['size'];
	move_uploaded_file($tempFile, "uploads/" . $fileName);
}

sleep(3);

$root=$_SERVER['DOCUMENT_ROOT'];

	
	//$exeout.=shell_exec("composite -gravity Center $root/temp/line1.png $root/assets/blank_title.png $root/temp/line2.png 2>&1");
	
		//$exeout.=shell_exec("convert $root/uploads/$fileName  -gravity Center  -crop 756x602+0+0 +repage  $root/temp/cropped_photo.png 2>&1");
		
		//$exeout.=shell_exec("convert $root/uploads/$fileName -resize 756x602^ -gravity center -crop 756x602+0+0 +repage $root/temp/cropped_photo.png 2>&1");
		

		
		//$exeout.=shell_exec("convert $root/uploads/$fileName -set option:distort:viewport \"%[fx:min(756,602)]x%[fx:min(756,602)]+%[fx:max((756-602)/2,0)]+%[fx:max((602-756)/2,0)]\" -filter point -distort SRT 0  +repage  $root/temp/cropped_photo.png 2>&1");
		
		$exeout="thumbnail row 1".shell_exec("convert $root/uploads/$fileName -resize 200x200^ -gravity north -crop 200x200+0+0 $root/temp/smallthumb1.png  2>&1");
		
			
		$exeout.="thumbnail row 2".shell_exec("composite -gravity center -size 200x200 $root/temp/smallthumb1.png $root/temp/smallsquare.png  $root/temp/tempthumb.png  2>&1");
		
		$exeout.="thumbnail row 3".shell_exec("composite $root/temp/thumboverlay.png $root/temp/tempthumb.png  $root/thumbs/$fileName  2>&1");
		
		$exeout.="final pic part 1".shell_exec("composite -geometry +68+65 $root/uploads/$fileName $root/temp/background.png $root/temp/composite.png 2>&1");
		
		$exeout.="final pic part 2".shell_exec("convert $root/temp/composite.png $root/rendered/$fileName  2>&1");
		
		
  
  if(strlen($exeout)<2) {
		die("result=OK&location=http://ultrabook-lounge.com/rendered/".$fileName);
    }
  else
    {
      die("result=error&message=". $exeout);
    }
?>

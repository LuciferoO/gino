<?php
chdir(dirname(__FILE__));
error_reporting(E_ALL);

//
global $rootDir;
$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
$dirContent=array();
echo $_SERVER['DOCUMENT_ROOT'];

	$root=$_SERVER['DOCUMENT_ROOT'];
	echo "server root:".$root;

//print "difference is:".count($diff)."\n";

//if(count($diff)>0) {
	$videoList=rglob($_SERVER['DOCUMENT_ROOT']."/batchincoming",'/\.mp4$/i');
	
	if(count($videoList)>0) {
		$thumb = preg_replace('"\.mp4$"', '.jpg', $videoList[0]);
		$ogv = preg_replace('"\.mp4$"', '.ogv', $videoList[0]);
		$result0=shell_exec('/usr/local/bin/ffmpeg -i '.$_SERVER['DOCUMENT_ROOT'].'/batchincoming/'.$videoList[0].' -y -vf thumbnail=25 -y -vframes 1 '.$_SERVER['DOCUMENT_ROOT'].'/temp/nakedvideoframe.png 2>&1');
		
			$exeout="thumbnail row 1".shell_exec("convert $root/temp/nakedvideoframe.png -resize 200x200^ -gravity center -crop 200x200+0+0 $root/temp/squarevideoframe.png  2>&1");
			
				$exeout.="thumbnail row 2".shell_exec("convert $root/temp/squarevideoframe.png $root/assets/overlay_small_thumb_video.png -composite -quality 90%  $root/squarethumbs/$thumb  2>&1");
				
				echo "result of thumb generation:".$exeout."<br/>";
		$convertstring='/usr/local/bin/ffmpeg -t 30 -i '.$_SERVER['DOCUMENT_ROOT'].'/batchincoming/'.$videoList[0].'  -y -i '.$_SERVER['DOCUMENT_ROOT'].'/assets/overlay.png -y -filter_complex "[0]scale=640:428,overlay" -qscale 0 -b:v 1200k '.$_SERVER['DOCUMENT_ROOT'].'/temp/'.$videoList[0].' 2>&1';
		$result=shell_exec($convertstring);
		echo "resuly of ffmpeg:".$result."<br/>";
		echo "convert query mp4:".$convertstring."<br/>";
		
		$convertstring='/usr/local/bin/ffmpeg -t 30 -i '.$_SERVER['DOCUMENT_ROOT'].'/batchincoming/'.$videoList[0].'  -y -i '.$_SERVER['DOCUMENT_ROOT'].'/assets/overlay.png -y -filter_complex "[0]scale=640:428,overlay" -vcodec libtheora -acodec libvorbis -b:v 2M '.$_SERVER['DOCUMENT_ROOT'].'/video/'.$ogv.' 2>&1';
		$result=shell_exec($convertstring);
		echo "convert query ogv:".$convertstring."<br/>";
		echo "resuly of ffmpeg:".$result;
		
		$result2=shell_exec('/usr/local/bin/ffmpeg -i '.$_SERVER['DOCUMENT_ROOT'].'/temp/'.$videoList[0].' -y -vf thumbnail=25 -y -vframes 1 '.$_SERVER['DOCUMENT_ROOT'].'/temp/tempvideothumb.png 2>&1');
		echo "resuly of ffmpeg:".$result;
		echo "resuly of reame:".$result2;
		
			$thumbstring="convert $root/temp/tempvideothumb.png $root/assets/overlay_play.png -composite -quality 90%  $root/video/$thumb  2>&1";
			$result3="thumbnail row 2".shell_exec($thumbstring);
			
			//rename($_SERVER['DOCUMENT_ROOT']."/temp/".$videoList[0],$_SERVER['DOCUMENT_ROOT']."/video/".$videoList[0]);
			
			//@unlink("$root/temp/tempvideothumb.png");
			//@unlink("$root/temp/nakedvideoframe.png");
			//@unlink("$root/temp/squarevideoframe.png");
			//@unlink("$root/temp/".$videoList[0]);
			
			echo "thumb string:".$thumbstring."<br/>";
			echo "resuly of reame:".$result3;
		//rename($_SERVER['DOCUMENT_ROOT']."/batchincoming/".$videoList[0],$_SERVER['DOCUMENT_ROOT']."/batchprocessed/".$videoList[0]);
	} else {
		die("nothing to do...");
	}

function rglob($sDir, $regEx, $nFlags = NULL)
  {
	  chdir(dirname(__FILE__));
	  
	$result=array();
  if ($handle = opendir($sDir)) {
  while (false !== ($file = readdir($handle))) {
	  //echo "$file\n";
	  preg_match($regEx, $file, $matches);
	  if ($file != '.' && $file != '..' && count($matches) > 0) {
		  $result[]=$file;
		  //print("<pre>$regEx $sDir $file \n=");
	  }
	  
	  }
	}
	//print "array:".is_array($result)."\n";
	return $result;
  
} 
?>
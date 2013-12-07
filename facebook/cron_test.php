<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="5">
<title>Cron job</title>
</head>

<body>
<?php 

echo $_SERVER['DOCUMENT_ROOT'];


require_once('/var/www/vhosts/wassermanexperience.com/sunglasshut/Connections/localhost.php'); ?>
<?php
//
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}



//
global $rootDir;

$rootDir="/var/www/vhosts/wassermanexperience.com/sunglasshut/batchprocessed";
$filename=$rootDir."status.txt";
$dirContent=array();
echo "read dir<br/>";
if ($handle = opendir($rootDir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            //echo "entry: $entry -".$rootDir+"/".$entry."<br/>";
			echo "cazo";
				$file = fopen("/var/www/vhosts/wassermanexperience.com/sunglasshut/batchprocessed/".$entry,"r");
				$content=fread($file,filesize("/var/www/vhosts/wassermanexperience.com/sunglasshut/batchprocessed/".$entry));
				fclose($file);
				$xml = simplexml_load_string($content);
	
	print_r($xml->urn ." extraterms:".$xml->extraterms."<br/>");
	if($xml->extraterms==1) {
		$gino="y";
	} else {
		$gino="N";
	}
$insertSQL = sprintf("update users set extra_optin=%s,offline=1 where urn=%s",
			 GetSQLValueString($gino, "text"),
             GetSQLValueString($xml->urn, "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die("result=ERROR&message=".urlencode("error updating batch timestamp".mysql_error()));
$affected = mysql_affected_rows();
if($affected > 0) {
   echo "row was updated/inserted<br/>";
}
else {
   echo "No rows was ....<br/>"; }
        }
    }
    closedir($handle);
}
echo "after read dir<br/>";
die();
//print "difference is:".count($diff)."\n";

//if(count($diff)>0) {
	$xmlList=rglob($rootDir."batchincoming",'/\.xml$/i');
	print "making image list\n";
	$imgList=rglob($rootDir."batchincoming",'/\.jpg$/i');
	$deleteList=rglob($rootDir."batchincoming",'/\.xmlp$/i');
	for($i=0;$i<count($xmlList);$i++) {
		//$basename=stristr(
		echo "now parsing ".$xmlList[$i]."<br/>";
		$currentFile=parseXML($xmlList[$i]);
		echo "now searching for $currentFile<br/>";

		$result=array_search(strtolower($currentFile),array_lower($imgList));

		if($currentFile==0) {
			echo "data has no photo<br/>\n";
			parseResult($xmlList[$i],$currentFile);
			break;
		} else if($result===false) {
			echo "photo not found<br/>\n";
			//processfile($xmlList[$i],"","",$basename);
		} else {
			echo "photo FOUND<br/>\n";
			parseResult($xmlList[$i],$currentFile);
			break;
			//processfile($xmlList[$i],$imgList[$result],$deleteList[$i],$basename);
		}

			
		
		
	}
	print "end of proc\n";
	//print_r($xmlList);
	//print_r($imgList);
//}


/**
* Recursive version of glob
*
* @return array containing all pattern-matched files.
*
* @param string $sDir      Directory to start with.
* @param string $sPattern  Pattern to glob for.
* @param int $nFlags       Flags sent to glob.
*/
function rglob($sDir, $regEx, $nFlags = NULL)
  {
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
function array_lower($orig) {
	$dest=array();
	for ($i=0;$i<count($orig);$i++) {
		$dest[]=strtolower($orig[$i]);
	}
	return $dest;
}
function parseXML($xmlfile) {
	$fileXML="/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/batchincoming/".$xmlfile;
	$file = fopen($fileXML,"r");
	$content=fread($file,filesize($fileXML));
	fclose($file);
	
	preg_match('#<hasphoto>(.*)</hasphoto>#Us', $content, $hasphoto);
	
	preg_match('#<destFileName>(.*)</destFileName>#Us', $content, $destFileName);

	die("result of hasphoto:".$hasphoto[1]);

	if($hasphoto[1]=="0") {
		return 0;
	} else {
		return $destFileName[1];
	}
	
}
function parseResult($xmlfile,$pic) {
	echo "parse result<br/>";
	global $database_localhost,$username_localhost,$password_localhost,$localhost;
	$fileXML="/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/batchincoming/".$xmlfile;
	$file = fopen($fileXML,"r");
	$content=fread($file,filesize($fileXML));
	fclose($file);
	
	$xml = simplexml_load_string($content);
	echo "date:".date("d-m-Y",(int)$xml->added);
	print_r($xml);
	$token=trim($xml->token);
	echo "token length:".strlen($token)."<br/>";
	if($xml->facebook==1) {
		$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"http://sunglasshut.wassermanexperience.com/setuser.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
				"urn=".$xml->urn."&token=".$xml->token);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$server_output = curl_exec ($ch);
	} else {
		$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"http://sunglasshut.wassermanexperience.com/register.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
				"urn=".$xml->urn."&firstname=".$xml->firstname."&lastname=".$xml->lastname."&email=".$xml->email."&mobile=".$xml->mobile);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$server_output = curl_exec ($ch);
	}
	
	echo "server output registration $server_output <br/>";
	
	if($xml->hasphoto==1) {
	
	if(rename( "/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/batchincoming/".$pic  , "/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/uploads/".$pic)) {
	
	echo "execute cron with photo,<br/>";
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"http://sunglasshut.wassermanexperience.com/publishphoto.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
				"urn=".$xml->urn."&file=".$pic."&prize=".$xml->grabPrize."&score=".$xml->ticketsWon);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$server_output = curl_exec ($ch);
	
	echo "server output publish photo $server_output <br/>";
	
	echo "end of cron,<br/>";
	
	curl_close ($ch);
	} else {
		$server_output="file not found!";
	}
	
	$insertSQL = sprintf("update users set server_result=%s where urn=%s",
                       GetSQLValueString($server_output, "text"),
					   GetSQLValueString($xml->urn, "text")

					   );

  		mysql_select_db($database_localhost, $localhost);
  		$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
	} else {
			$ch = curl_init();
		
			curl_setopt($ch, CURLOPT_URL,"http://sunglasshut.wassermanexperience.com/publishscore.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
						"urn=".$xml->urn."&facebook=".$xml->facebook."&prize=".$xml->grabPrize."&score=".$xml->ticketsWon);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$server_output = curl_exec ($ch);
			
			echo "result of publish score: $server_output<br/>";

	}
	
	  if($xml->VFCustomer=="1") {
		  echo "vodafone customer <br/>";
  			$insertSQL = sprintf("INSERT IGNORE INTO userbigprize (urn, userbigprize.`value`,creationdate) VALUES (%s, %s, NOW())",
             GetSQLValueString($xml->urn, "text"),
             GetSQLValueString($xml->finalPrize, "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die("result=ERROR&message=".urlencode(mysql_error()));
  }
		
		echo "server result:".$server_output;
		
		rename( "/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/batchincoming/".$xmlfile  , "/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/uploads/".$xmlfile);

	
	
}
function processfile($xml,$img,$delete,$urn) {
	global $database_localhost,$username_localhost,$password_localhost,$localhost;
	@unlink(( "/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/batchincoming/".$delete));
	
	mysql_select_db($database_localhost, $localhost);
	$query_Recordset1 = sprintf("SELECT * FROM users WHERE urn = %s", GetSQLValueString($urn, "text"));
	$Recordset1 = mysql_query($query_Recordset1, $localhost) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	if($totalRows_Recordset1>0) {
		echo "file exists!<br/>";
		@unlink("/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/batchincoming/".$xml);
		@unlink("/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/batchincoming/".$img);
		return false;
	}
	
	print "processing: $xml - $img rootDir: $rootdir \n";
	$processresult=true;
	insertRecord($xml,$img);
	if(!rename( "/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/batchincoming/".$xml  , "/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/batchprocessed/".$xml)) {
		$processresult=false;
	}

	if($processresult) {
		echo "SUCCESS \n";
	} else {
		echo "FAIL \n";
	}
	
}
function insertRecord($xmlFile,$imgfile) {
	print "inserting record: $xmlFile \n";
	$fileXML="/var/www/vhosts/ignitesocial.co.uk/httpdocs/summertimeremote/batchincoming/".$xmlFile;
	$file = fopen($fileXML,"r");
	$content=fread($file,filesize($fileXML));
	fclose($file);
	
	preg_match('#<firstname>(.*)</firstname>#Us', $content, $firstname);
	preg_match('#<lastname>(.*)</lastname>#Us', $content, $lastname);
	preg_match('#<email>(.*)</email>#Us', $content, $email);
	preg_match('#<urn>(.*)</urn>#Us', $content, $urn);
	preg_match('#<usertime>(.*)</usertime>#Us', $content, $usertime);
	preg_match('#<game1>(.*)</game1>#Us', $content, $game1);
	preg_match('#<game2>(.*)</game2>#Us', $content, $game2);
	preg_match('#<game3>(.*)</game3d>#Us', $content, $game3);
	preg_match('#<game4>(.*)</game4>#Us', $content, $game4);
	preg_match('#<game5>(.*)</game5>#Us', $content, $game5);
	
	echo "postcard: $imgfile <br/>";
	
	if(strlen(trim($imgfile))>2 && file_exists("batchincoming/".$imgfile)) {
		echo $urn[1]."postcard exists<br/>";
		rename( "batchincoming/".$imgfile  , "postcards/".$imgfile);
		$haspostcard=true;
	} else {
		echo $urn[1]."no postcard present<br/>";
		$haspostcard=false;
	}
	
   	echo("processing this one game 5:".$game5[5]);
	
	global $database_localhost,$username_localhost,$password_localhost,$localhost;
  $insertSQL = sprintf("INSERT INTO users (firstname, lastname, email, urn, usertime, game1, game2, game3,game4,game5) VALUES (%s, %s, %s, %s,timestamp(%s), %s, %s, %s,%s,%s)",
                       GetSQLValueString($firstname[1], "text"),
                       GetSQLValueString($lastname[1], "text"),
                       GetSQLValueString($email[1], "text"),
                       GetSQLValueString($urn[1], "text"),
                       GetSQLValueString($usertime[1], "text"),
                       GetSQLValueString($game1[1], "text"),
                       GetSQLValueString($game2[1], "text"),
					   GetSQLValueString($game3[1], "text"),
					   GetSQLValueString($game4[1], "text"),
					   GetSQLValueString($game5[1], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
  
  $userid=mysql_insert_id();
  

  
  if(strtotime($usertime[1])>strtotime("2012-05-01 01:00:00")) {
	 echo "sending email<br/>";

  

  	require_once('phpmailer/class.phpmailer.php');
	
	$mail             = new PHPMailer(); // defaults to using php "mail()"
	
	//$mail->IsSendmail(); // telling the class to use SendMail transport
	
	$mail->IsSMTP();
	$mail->Host = "localhost";
	
	$mail->SMTPDebug  = 2;
	
	$body             = file_get_contents('Emailer_Layered_FIN.htm');
	$body             = eregi_replace("[\]",'',$body);
	
	if($game1[1]=="") {
		$game1[1]=0;
	}
	if($game2[1]=="") {
		$game2[1]=0;
	}
	if($game3[1]=="") {
		$game3[1]=0;
	}
	if($game4[1]=="") {
		$game4[1]=0;
	}
	if($game5[1]=="") {
		$game5[1]=0;
	}
	
	$body=str_replace("#score1#",$game1[1],$body);
	$body=str_replace("#score2#",$game2[1],$body);
	$body=str_replace("#score3#",$game3[1],$body);
	$body=str_replace("#score4#",$game4[1],$body);
	$body=str_replace("#score5#",$game5[1],$body);
	$total=round(($game1[1]+$game2[1]+$game3[1]+$game4[1]+$game5[1])/5);
	
	$body=str_replace("#total#",$total,$body);
	
	
	
	$mail->AddReplyTo("Noreply@carlsberg.co.uk","Carlsberg Photo Booth");
	
	$mail->SetFrom('Noreply@carlsberg.co.uk', 'Carlsberg Photo Booth');
	
	
	$mail->AddAddress($email[1],$firstname[1]." ".$lastname[1]);
	
	$mail->Subject    = "Your Carlsberg Scorecard";
	
	$mail->AltBody    = "To see your picture, please use a HTML compatible viewer!"; // optional, comment out and test
	
	
	if($haspostcard) {
		  $body=str_replace("#image#",'<tr><td colspan="4"><img src="postcards/'.$imgfile.'" width="650" height="487" style="display:block;" border="0" id="Emailer_650x562px_r3_c2" alt="" /></td></tr>',$body);
		  $mail->AddAttachment("/var/www/vhosts/ignitionsecure.co.uk/httpdocs/car1008/postcards/".$imgfile); // attachment
	} else {
		$body=str_replace("#image#","",$body);
	}
	$mail->MsgHTML($body);
	if(!$mail->Send()) {
	  $result= $mail->ErrorInfo;
	} else {
	  $result= "OK";
	}


	  } else {
		  echo "not sending email<br/>";
	  }
  
}
?></body>
</html>
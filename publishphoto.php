<?php require_once('Connections/localhost.php'); ?>
<?php

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

$colname_user = "-1";
if (isset($_POST['urn'])) {
  $colname_user = $_POST['urn'];
} else {
	die("direct access not allowed");
}
mysql_select_db($database_localhost, $localhost);
$query_user = sprintf("SELECT * FROM userphoto WHERE urn = %s", GetSQLValueString($colname_user, "text"));
$user = mysql_query($query_user, $localhost) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

if($totalRows_user>0) {
	die("result=ERROR&message=".urlencode("photo already published!"));
}

$colname_user = "-1";
if (isset($_POST['urn'])) {
  $colname_user = $_POST['urn'];
}
mysql_select_db($database_localhost, $localhost);
$query_user = sprintf("SELECT * FROM users WHERE urn = %s", GetSQLValueString($colname_user, "text"));
$user = mysql_query($query_user, $localhost) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

if(strlen(trim($row_user['token']))>2) {
	$facebook_optin=1;
} else {
	$facebook_optin=0;
}


mysql_select_db($database_localhost, $localhost);
$query_update = sprintf("insert into userphoto (urn, creationdate,filename,facebook) values (%s,NOW(),%s,%s)", GetSQLValueString($_POST['urn'], "text"), GetSQLValueString($_POST['file'], "text"),GetSQLValueString($facebook_optin, "text"));
$update = mysql_query($query_update, $localhost) or die("result=ERROR&message=".urlencode("insert in db ".$query_update." ".mysql_error()));

$userphotoid = mysql_insert_id();

// increase the post number, not used at the moment
//mysql_select_db($database_localhost, $localhost);
//$query_user = sprintf("update users set posts=posts+1 WHERE id = %s", GetSQLValueString($_POST['id'], "text"));
//$user = mysql_query($query_user, $localhost) or die("result=ERROR&message=".urlencode(mysql_error()));

if($facebook_optin==1) {
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require 'php-sdk/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '157192541140769',
  'secret' => 'f85958425660412d8df765436d1cd74f',
));

// Get User ID
$facebook->setAccessToken($row_user['token']);


try {
	$user = $facebook->api('/me', 'get');
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".urlencode($e->getMessage());
				die();
}




$facebook->setFileUploadSupport(true);
$args = array('message' => "Which Sunglass Hut look do you prefer? #styleitout"); //,'place'=>'50.712560964795'
//$args = array('message' => 'Nézd meg, ahogy a Vodafone McLaren Mercedes F1 versenyautójában ülök a Vodafone Kezdj El Valami Újat Hétvégén!');
$args['image'] = '@' . realpath($_SERVER['DOCUMENT_ROOT']."/rendered/".basename($_POST['file']));
/*$args['tags']= array(array(
                              'tag_uid'=> $user['id'],
                              'x'      => 0,
                              'y'      => 0));*/

try {
	$data = $facebook->api('/me/photos', 'post', $args); 
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".urlencode($e->getMessage());
				die();
}

$query_update = sprintf("update userphoto set photoid=%s where urn='%s'", GetSQLValueString($data['id'], "text"), $_POST['urn']);
$update = mysql_query($query_update, $localhost) or die("result=ERROR&message=".urlencode("insert in db ".$query_update." ".mysql_error()));



        $post_url = "https://graph.facebook.com/".$data['id']."/tags/"
        . $user['id']."?access_token=".$row_user['token']."&x=" . 68 ."&y=65"
         ."&method=POST";
        $response = file_get_contents($post_url);
		echo("result=SUCCESS&id=".$data['id']);
} else {
	require_once('phpmailer/class.phpmailer.php');
	
	$mail             = new PHPMailer(); // defaults to using php "mail()"
	
	$mail->SMTPDebug = false;
	$mail->do_debug = 0;

	$mail->IsSMTP();
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "smtp.sendgrid.net"; // sets the SMTP server
	$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
	$mail->Username   = "ignite"; // SMTP account username
	$mail->Password   = "k0st0golov";        // SMTP account password

	$mail->AddReplyTo("noreply@sunglasshut.com","Sunglass Hut");
	
	$mail->SetFrom("noreply@sunglasshut.com","Sunglass Hut");
	$mail->CharSet="UTF-8";
	
	$mail->AddAddress($row_user['email'],$row_user['firstname']." ".$row_user['lastname']);
	
	$mail->Subject    = "Style It Out with Sunglass Hut";
	
	$mail->AltBody    = "Please use an html compatible viewer!\n\n"; // optional, comment out and test
	
	$body=file_get_contents($_SERVER['DOCUMENT_ROOT']."/emailer/mailtemplate.html");
	
	$body=str_replace("#name#",$row_user['firstname'],$body);
	$body=str_replace("#filename#",$_POST['file'],$body);
	//$body=str_replace("#filename#",$_POST['file']."&urn=".$row_user['urn'],$body);
	$body=str_replace("#urn#",$row_user['urn'],$body);
	
	$mail->MsgHTML($body);
	
	$mail->AddCustomHeader(sprintf( 'X-SMTPAPI: %s', '{"category": "SunglassHut"}' ) );
	
	//$basefile=urldecode(basename($_POST['file']));
	//$mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT']."/rendered/".$basefile,"logo_2u",$basefile); // attachment
	
	if(!$mail->Send()) {
	  $result= $mail->ErrorInfo;
	  echo("result=ERROR&message=".urlencode("Error while sending email:".$result));
	} else {
	  echo("result=SUCCESS");
	}



}

function shortenURL($url) {
	$result=file_get_contents("http://off.st/api.php?&format=simple&action=shorturl&url=".$url);
}

$page = ob_get_contents();
ob_end_flush();
mysql_select_db($database_localhost, $localhost);
$query_Recordset1 = "insert into photolog (urn,output) values('".$_POST['urn']."','".mysql_real_escape_string($page)."')";
$Recordset1 = mysql_query($query_Recordset1, $localhost) or die("result=ERROR&message=".$query_Recordset1);
die();

//199581572813




mysql_free_result($user);
?>

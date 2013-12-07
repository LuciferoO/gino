<?php require_once('Connections/localhost.php'); ?>
<?php
require_once('FirePHPCore/fb.php');
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
fb("ciao mamma");

require 'aes.php';     // AES PHP implementation
require 'aesctr.php';  // AES Counter Mode implementation 
$colname_user = "-1";
if (isset($_POST['urn'])) {
	 $colname_user =$_POST['urn'];
}
mysql_select_db($database_localhost, $localhost);
$query_user = sprintf("SELECT * FROM users WHERE urn = %s", GetSQLValueString($colname_user, "text"));
$user = mysql_query($query_user, $localhost) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

if($totalRows_user>0) {
	die("result=ERROR&message=score already published");
}

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
 mysql_select_db($database_localhost, $localhost);
				$query_user = sprintf("update users set prize=%s,tickets=%s WHERE urn = %s", 
				GetSQLValueString($_POST['prize'], "text"),
				GetSQLValueString($_POST['score'], "text"),
				GetSQLValueString($_POST['urn'], "text"));
				$user = mysql_query($query_user, $localhost) or die("result=ERROR&message=".urlencode(mysql_error().":".$query_user));

switch ($_POST['prize']) {
	case "0":
	$description="I just played the Vodafone Freebee Rewardz Challenge at Capital FM Summertime Ball. ";
	break;
	case "5":
	$description="I just won at the Vodafone Freebee Rewardz Challenge at Capital FM Summertime Ball.";
	break;
	case "10":
	$description="I just won at the Vodafone Freebee Rewardz Challenge at Capital FM Summertime Ball. ";
	break;
	default:
	$description="I just played the Vodafone Freebee Rewardz Challenge at Capital FM Summertime Ball.";
	break;
}
 
if($_POST['facebook']=="true" || $_POST['facebook']=="1") {
	fb("publishg on facebook");
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
		echo "result=ERROR&message=".$e->getMessage();
					die();
	}
	
	try {
					$publishStream = $facebook->api("/me/feed", 'post', array(
						'message' => $description,
						'link'    => 'https://rewardz.vodafone.co.uk',
						'picture' => "http://sunglasshut.wassermanexperience.com/summertimelogo.png",
						'name'    => "Vodafone Freebee Rewardz at Capital STB",
						'description'=> "To find out more https://rewardz.vodafone.co.uk",

						)
					);
				} catch (FacebookApiException $e) {
					echo "result=ERROR&message=".urlencode($e->getMessage());
					die();
				}		
	$dunp=print_r($publishStream,true);
	fb($dunp,"DUmp FB Result:");
	$postid=explode("_",$publishStream['id']);
	mysql_select_db($database_localhost, $localhost);
				$query_user = "insert into userposts (userposts.urn,userposts.postid,userposts.creationdate) values ('".mysql_real_escape_string($_POST['urn'])."','".mysql_real_escape_string($postid[1])."',NOW());";
				$user = mysql_query($query_user, $localhost) or die("result=ERROR&message=".urlencode(mysql_error().":".$query_user));
} else {
	fb("NOT PUBLISHING on facebook");
}

die("result=SUCCESS&id=".$publishStream['id']);

?>

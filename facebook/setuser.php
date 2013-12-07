<?php require_once('../Connections/localhost.php'); ?>
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
require 'aes.php';     // AES PHP implementation
require 'aesctr.php';  // AES Counter Mode implementation 

//$token=AesCtr::decrypt(mysql_real_escape_string($_POST['token']), 'ginotronico', 256);
//$urn=AesCtr::decrypt(mysql_real_escape_string($_POST['urn']), 'ginotronico', 256);

$token=mysql_real_escape_string($_POST['token']);

require $_SERVER['DOCUMENT_ROOT'].'/php-sdk/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '157192541140769',
  'secret' => 'f85958425660412d8df765436d1cd74f',
));

// Get User ID from GET
try {
	$facebook->setAccessToken($_POST['token']);
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
				die();
            }

try {
	$data = $facebook->api('/me', 'get');
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
				die();
}


	$params = array(
	    'method' => 'fql.query',
	    'query' => "SELECT friend_count FROM user WHERE uid = me()",
	);

	try {
		$result = $facebook->api($params);
		} catch (FacebookApiException $e) {
			echo "result=ERROR&message=".urlencode($e->getMessage());
			die();
}


$insertSQL = sprintf("INSERT IGNORE INTO stats (urn, friend_count,social_id,post_type) VALUES (%s, %s,%s,'FACEBOOK')",
                       GetSQLValueString($_POST['urn'], "text"),
                       GetSQLValueString($result[0]['friend_count'], "text"),
					   GetSQLValueString($data['id'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die("result=ERROR&message=".urlencode(mysql_error()));



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//if ((isset($_POST["urn"]))) {
  $insertSQL = sprintf("INSERT INTO users (urn, token, firstname, lastname, email, screen_name, user_id, gender,location,hometown,current_location,extraterms,usertype) VALUES (%s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,'facebook')",
                       GetSQLValueString($_POST['urn'], "text"),
                       GetSQLValueString($_POST['token'], "text"),
                       GetSQLValueString($data['first_name'], "text"),
                       GetSQLValueString($data['last_name'], "text"),
                       GetSQLValueString($data['email'], "text"),
                       GetSQLValueString($data['username'], "text"),
                       GetSQLValueString($data['id'], "text"),
                       GetSQLValueString($data['gender'], "text"),
					   GetSQLValueString($data['location']['name'], "text"),
					   GetSQLValueString($data['hometown']['name'], "text"),
					   GetSQLValueString($_POST['current_location'], "text"),
					   GetSQLValueString($_POST['extraterms'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die("result=ERROR&message=".urlencode("Error inserting FB user:".mysql_error()));
//}
/*
try {
		$facebook->api('/me/checkins', 'POST', array(
		'access_token' => $facebook->getAccessToken(),
		'place' => '383333131704343',
		'message' =>'I just checked-in blah blah blah setuser'. GetSQLValueString($data['email'], "text")."",
		'picture' => 'http://ultrabook-lounge.com/ultrabook_logo.png',
		'coordinates' => json_encode(array(
		   'latitude'  => '50.712560964795',
		   'longitude' => '-1.2870725803089',
		   'tags' => $data['id'])
		 )
		
		)
		);
		} catch (FacebookApiException $e) {
					echo "result=ERROR&message=CHECKIN:".$e->getMessage();
				}	
				
			
	try {
					$publishStream = $facebook->api("/me/feed", 'post', array(
						'message' => "I just entered the draw to win an Ultrabook™!",
						'link'    => 'http://www.ultrabook-lounge.com',
						'picture' => 'http://ultrabook-lounge.com/ultrabook_logo.png',
						'name'    => "Intel Ultrabook™",
						'description'=> "Ultra Responsive, Ultra Sleek, Ultrabook™ inspired by Intel®. Click on the Intel logo to learn more or buy now at PC World or Amazon"
						)
					);
				} catch (FacebookApiException $e) {
					echo "result=ERROR&message=".$e->getMessage();
				}	*/

				
die("result=OK&first_name=".urlencode($data['first_name'])."&last_name=".urlencode($data['last_name'])."&email=".urlencode($data['email'])."&id=".$data['id']."&location=".urlencode($data['location']['name'])."&hometown=".urlencode($data['hometown']['name']));
?>
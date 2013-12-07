<?php
require_once('../Connections/php.mysql.class.php');
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
if(!isset($_POST['urn'])) {
	die("result=ERROR&message=".urlencode("URN not set!"));
}

$db= new MySQL(DB,DBUSER,DBPASS);
	
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_POST['token'], $_POST['token_secret']);

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');

$res=$db->Insert(array(
				"urn"=>$_POST['urn'],
				"post_type"=>"twitter",
				"social_id"=>$content->id_str,
				"friend_count"=>$content->friends_count,
				"followers_count"=>$content->followers_count),"stats",true);
				
if(!$res) {
	die("result=ERROR&message=".urlencode("Error creating stat log:".$db->lastError));
} 

$res=$db->Insert(array(
				"urn"=>$_POST['urn'],
				"usertype"=>"twitter",
				"profile_pic"=>$content->profile_image_url,
				"user_id"=>$content->id,
				"current_location"=>$_POST['current_location'],
				"screen_name"=>$content->screen_name,
				"location"=>$content->location,
				"firstname"=>$content->name,
				
				"lastname"=>"",
				"token"=>$_POST['token'],
				"token_secret"=>$_POST['token_secret']
							),"users",true);

if(!$res) {
	die("result=ERROR&message=".urlencode("Error inserting twitter user:".$db->lastError));
} else {
	die("result=OK");
}
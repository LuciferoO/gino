<?php
/**
 * @file
 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
if (!isset($_GET['urn'])) {
   die("user_id not set!");
}
require_once('../Connections/php.mysql.class.php');
$db= new MySQL(DB,DBUSER,DBPASS);

$resulto=$db->Select("users",array("urn"=>$_GET['urn']));

var_dump($resulto);
echo "token:".$resulto['token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $resulto['token'], $resulto['token_secret']);

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');

/* Some example calls */
//$connection->get('users/show', array('screen_name' => 'abraham'));
$resulto=$connection->get('statuses/show', array('id' => '366701672925364224'));
//$connection->post('statuses/destroy', array('id' => 5437877770));
//$connection->post('friendships/create', array('id' => 9436992));
//$connection->post('friendships/destroy', array('id' => 9436992));

/* Include HTML to display on the page */
echo "resulto:<br/>";

if(isset($resulto->errors)) {
	echo "ci sono errori<br/>";
	echo "messaggio:".$resulto->errors[0]->message;
} else {
	echo "stringa:".$resulto->id_str;
	}

print_r($resulto);
include('html.inc');

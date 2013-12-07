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

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  header('Location: ./clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;

/* Remove no longer needed request tokens */

//var_dump($access_token);



/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
  /* The user has been verified and the access tokens can be saved for future use */
  $_SESSION['status'] = 'verified';
  $content = $connection->get('account/verify_credentials');
  //var_dump($content);
  echo "credentials here:<br/>";
  echo "id:".$content->id."<br/>";
   echo "screen_name:".$content->screen_name."<br/>";
   echo "name:".$content->name."<br/>";
   echo "location:".$content->location."<br/>";
   echo "profile_image_url:".$content->profile_image_url."<br/>";
	$name=explode(" ",$content->name);
	//$db= new MySQL(DB,DBUSER,DBPASS);
	
	//$res=$db->Select("users",array("user_id"=>$content->id));
	
	
	/*$res=$db->Insert(array(
				"urn"=>$_SESSION['urn'],
				"usertype"=>"twitter",
				"profile_pic"=>$content->profile_image_url,
				"user_id"=>$content->id,
				"screen_name"=>$content->screen_name,
				"location"=>$content->location,
				"firstname"=>$name[0],
				"lastname"=>$name[1],
				"token"=>$access_token['oauth_token'],
				"token_secret"=>$access_token['oauth_token_secret']
							),"users");*/
	
	//echo "mysql error:".$db->lastError;
   
   //	$_SESSION['user_id']=$content->id;
   
  
  //header('Location: ./index.php');
} else {
  /* Save HTTP status for error dialog on connnect page.*/
  header('Location: ./clearsessions.php');
} ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twitter Oauth Landing Page</title>
</head>

<body>
<script type="text/javascript">
document.location=JSON.stringify({result:"twittersuccess",user_id:"<?php echo $content->id; ?>",token:escape("<?php echo $access_token['oauth_token']; ?>"),token_secret:escape("<?php echo $access_token['oauth_token_secret']; ?>")});
</script>
</body>
</html><?php 
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']); ?>

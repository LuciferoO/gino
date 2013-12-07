<?php require_once('../Connections/php.mysql.class.php'); ?>
<?php


$db= new MySQL(DB,DBUSER,DBPASS);

$user=$db->Select("users",array("urn"=>$_GET['urn']));

if($db->records==0) {
	die("direct access not allowed");
}

$photo=$db->Select("userphoto",array("urn"=>$_GET['urn']));

switch($_GET['share_type']) {
	case "TWITTER":
	$incrementfield="twitter_page_hit";
	break;
	case "FACEBOOK":
	$incrementfield="facebook_page_hit";
	break;
	default:
	$incrementfield="email_page_hit";
	break;
}


$result=$db->ExecuteSQL("update stats set ".$incrementfield."=".$incrementfield."+1 where urn='".$db->SecureData($_GET['urn'])."'");

if(!$result) {
	die("error updating stats".$db->lastQuery."-".$db->lastError);
}



header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

?><!DOCTYPE html>
<html lang="en" xml:lang="en">
<meta property="fb:app_id" content="157192541140769">
<meta property="fb:admins" content="595373701">
  <meta property="og:url" content="http://www.barclaysyouarefootball.uk.com/jwplayer/player.php?urn=">
  <meta property="og:title" content="Spectacular Man Made Sun Brightens up London 2 Hours Early">
  <meta property="og:description" content="This is Gino Tronico Video">
  <meta property="og:type" content="video">
  <meta property="og:image" content="https://wmg.box.com/shared/static/ko0b8mzinwl36zbhc5vs.png">
  <meta property="og:video" content="http://www.ignitesocial.co.uk/youarefotball/jwplayer/ginotronico.mp4">
  <meta property="og:video:type" content="video/mp4">
  <meta property="og:video:width" content="640">
  <meta property="og:video:height" content="360">
  <meta property="og:site_name" content="#YouAreFootBall">
    
<meta name="twitter:card" content="player">
<meta name="twitter:site" content="">
<meta name="twitter:creator" content="">
<meta name="twitter:title" content="Title of this thing">
<meta name="twitter:description" content="">
<meta name="twitter:image:src" content="https://wmg.box.com/shared/static/ko0b8mzinwl36zbhc5vs.png">
<meta name="twitter:player" content="https://www.ignitesocial.co.uk/youarefotball/jwplayer/player.php">
<meta name="twitter:player:stream" content="http://www.ignitesocial.co.uk/youarefotball/jwplayer/ginotronico.mp4">
<meta name="twitter:player:stream:content_type" content="video/mp4">
<meta name="twitter:player:height" content="640">
<meta name="twitter:player:width" content="480">
<meta name="twitter:domain" content="ignitesocial.co.uk">
<meta name="twitter:app:name:iphone" content="">
<meta name="twitter:app:name:ipad" content="">
<meta name="twitter:app:name:googleplay" content="">
<meta name="twitter:app:url:iphone" content="">
<meta name="twitter:app:url:ipad" content="">
<meta name="twitter:app:url:googleplay" content="">
<meta name="twitter:app:id:iphone" content="">
<meta name="twitter:app:id:ipad" content="">
<meta name="twitter:app:id:googleplay" content="">
    

<style type="text/css">
#apDiv1 {
	position: absolute;
	width: 200px;
	height: 115px;
	z-index: 1;
	background-color:0xffffff;
}
</style>
<head>
      <script src="jwplayer.js"></script>
  		<script>jwplayer.key="i2Zxr/339GzwRyQ6XS0LMB2xDkTSEWjtDJOX/Q=="</script>
  		<style type="text/css">
        html,body { height:100%; width:100%; padding:0; margin:0; }
    		#player { height:100%; width:100%; padding:0; margin:0; }
  		</style>
  <title>#YouAreFootball</title>
    </head>
<body>
    <div id="apDiv1"><a href="https://www.facebook.com/sharer/sharer.php?u=http://www.ignitesocial.co.uk/youarefotball/jwplayer/player.php" target="_blank">
  Share on Facebook
</a><br/>
<a href="https://twitter.com/intent/tweet?url=http://www.ignitesocial.co.uk/youarefotball/jwplayer/player.php" class="twitter-share-button" data-lang="en">Tweet this video</a></div>
    

      <div id='player'></div>
<script type='text/javascript'>
        jwplayer('player').setup({
          file: "http://www.ignitesocial.co.uk/youarefotball/jwplayer/ginotronico.mp4",
          image: "https://wmg.box.com/shared/static/ko0b8mzinwl36zbhc5vs.png",
          width: "100%",
          height: "100%"
        });
      </script>      
</body>
</html>
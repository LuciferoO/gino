<?php require_once('Connections/php.mysql.class.php'); ?>
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

$useragent=strtolower($_SERVER['HTTP_USER_AGENT']);
if(strpos($useragent,"twitter")===false && !isset($_GET['no_index'])) {
	$result=$db->ExecuteSQL("update stats set ".$incrementfield."=".$incrementfield."+1 where urn='".$db->SecureData($_GET['urn'])."'");
	
	if(!$result) {
		die("error updating stats".$db->lastQuery."-".$db->lastError);
	}
}
$thumbFile=preg_replace('"\.mp4$"', '.jpg', $photo['filename']);

header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
<meta property="fb:app_id" content="157192541140769">
<meta property="fb:admins" content="595373701">
<meta property="og:url" content="https://skytoystory.wassermanexperience.com<?php echo $_SERVER['REQUEST_URI']; ?>">
<meta property="og:title" content="My video from the match on Saturday">
<meta property="og:description" content="We love cheering from the stands, come rain or shine! #YouAreFootball">
<meta property="og:type" content="video">
<meta property="og:image" content="https://skytoystory.wassermanexperience.com/squarethumbs/<?php echo $thumbFile; ?>">
<meta property="og:video" content="https://skytoystory.wassermanexperience.com/video/<?php echo $photo['filename']; ?>">
<meta property="og:video:type" content="video/mp4">
<meta property="og:video:width" content="640">
<meta property="og:video:height" content="428">
<meta property="og:site_name" content="#YouAreFootBall">
<meta name="twitter:card" content="player">
<meta name="twitter:site" content="">
<meta name="twitter:creator" content="">
<meta name="twitter:title" content="Title of this thing">
<meta name="twitter:description" content="">
<meta name="twitter:image:src" content="http://www.barclaysyouarefootball.uk.com/video/<?php echo $thumbFile; ?>">
<meta name="twitter:player" content="https://skytoystory.wassermanexperience.com<?php echo $_SERVER['REQUEST_URI']; ?>">
<meta name="twitter:player:stream" content="http://www.barclaysyouarefootball.uk.com/video/<?php echo $photo['filename']; ?>">
<meta name="twitter:player:stream:content_type" content="video/mp4">
<meta name="twitter:player:height" content="640">
<meta name="twitter:player:width" content="428">
<meta name="twitter:domain" content="barclaysyouarefootball.uk.com">
<style type="text/css">
#apDiv1 {
	position: absolute;
	width: 200px;
	height: 115px;
	z-index: 1;
	background-color: 0xffffff;
}
</style>
<head>
<script src="/jwplayer/jwplayer.js"></script>
<script>jwplayer.key="yBKJ30aVmiAa72IWeXkaGA8JIfRLjzLWhGu/Og=="</script>
<style type="text/css">
html, body {
	height: 100%;
	width: 100%;
	padding: 0;
	margin: 0;
}
#player {
	height: 100%;
	width: 100%;
	padding: 0;
	margin: 0;
}
</style>
<title>#YouAreFootball - video</title>
</head>
<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-43693286-1', 'wassermanexperience.com');
  ga('send', 'pageview');

</script>
<div style="width:824px;margin-left:auto;margin-right:auto;background-color:#FFF;">
  <table width="100%" border="0" cellspacing="0" cellpadding="8">
    <tr>
      <td align="center"><a href="#"></a>
        <div style="width:100%;text-align:left;"><img src="/assets/barclayslogo.png" width="207" height="75" alt="Barclays" /></div>
        <table cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" valign="middle" ><img src="/assets/vdivider_video.png" width="7" height="440" /></td>
            <td align="center" valign="middle" ><div id='player'></div>
<script type='text/javascript'>
        jwplayer('player').setup({
          file: "https://skytoystory.wassermanexperience.com/video/<?php echo $photo['filename']; ?>",
          image: "https://skytoystory.wassermanexperience.com/video/<?php echo $thumbFile; ?>",
          width: "640",
          height: "428",
		  ga: {
        idstring: "title",
        trackingobject: "pageTracker"
    	}
        });
      </script></td>
            <td align="center" valign="middle" ><img src="/assets/vdivider_video.png" width="7" height="440" /></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td align="center"><div class="buttons"><a href="/download.php?<?php echo isset($_GET['no_index'])?"no_index=1&":"";  ?>urn=<?php echo $user['urn']; ?>"><img src="/assets/downloadvideo.png" style="border:none;" width="223" height="52" alt="Download Video" /></a><a href="/sharevideo.php?urn=<?php echo $user['urn']; ?>&share_type=TWITTER"><img style="border:none;" src="/assets/shareontwitter.png" width="226" height="52" alt="Share on Twitter" /></a><a href="/sharevideo.php?urn=<?php echo $user['urn']; ?>&share_type=FACEBOOK"> <img src="/assets/shareonfb.png" style="border:none;" width="227" height="52" alt="Share on Facebook" /></a></div></td>
    </tr>
    <tr>
      <td align="center"><a href="https://www.facebook.com/BarclaysFootball" target="_blank"><img style="border:none;" src="/assets/connectwithus.png" width="160" height="44" alt="Connect With Us" /></a></td>
    </tr>
  </table>
</div>
</body>
</html>
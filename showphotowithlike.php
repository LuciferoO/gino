<?php require_once('./Connections/php.mysql.class.php'); ?>
<?php


$db= new MySQL(DB,DBUSER,DBPASS);

$user=$db->Select("users",array("urn"=>$_GET['urn']));

if(!$user) {
	die("error quering user:".$db->lastError);
}

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


header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

?>
<!doctype html>
<html>

<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# youarefootbal: http://ogp.me/ns/fb/youarefootbal#">
<meta charset="utf-8">
<meta property="og:title" content="My photo from the match on Saturday" />
<meta property="fb:admins" content="595373701" />
<meta property="fb:app_id" content="157192541140769" />
<meta property="og:image" content="https://skytoystory.wassermanexperience.com/squarethumbs/<?php echo $photo['filename']; ?>" /> 
<meta property="og:image:type" content="image/jpeg" />
<meta property="og:image:width" content="620" />
<meta property="og:image:height" content="541" />
<meta property="og:description" content="We love cheering from the stands, come rain or shine! #YouAreFootball" />
<meta property="og:url" content="https://skytoystory.wassermanexperience.com<?php echo $_SERVER['REQUEST_URI']; ?>">
<meta property="og:type"  content="youarefootbal:photo" /> 
<meta property="og:determiner" content="a" />
<title>#YouAreFootball</title>
<style type="text/css">
body {
    background-image:url(assets/background.gif);
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
img, img a {
	border:0px;
}
.centralimage {
	/*max-width:80%;*/
}

.buttons img {
	margin-left:auto;
	margin-right:auto;
}
</style>
</head>

<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=157192541140769";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-43693286-1', 'wassermanexperience.com');
  ga('send', 'pageview');

</script>
<div style="width:824px;margin-left:auto;margin-right:auto;background-color:#FFF;"><table width="100%" border="0" cellspacing="0" cellpadding="8">
  <tr>
    <td align="center"><a href="#"></a>
      <div style="width:100%;text-align:left;"><img src="/assets/barclayslogo.png" width="207" height="75" alt="Barclays" />
        
      </div>
      <table cellpadding="0" cellspacing="0">
        <tr>
          <td align="center" valign="middle" ><img src="/assets/vdivider.png" width="7" height="399" /></td>
          <td align="center" valign="middle" ><a href="#"><img class="centralimage" style="border:none;" src="https://skytoystory.wassermanexperience.com/photo/<?php echo $photo['filename']; ?>" alt="" width="500" /></a></td>
          <td align="center" valign="middle" ><img src="/assets/vdivider.png" width="7" height="399" /></td>
          </tr>
      </table></td>
    </tr>
  <tr>
    <td align="center"><div style="clear:both;text-align:center;"><div class="fb-like" data-href="http://www.barclaysyouarefootball.uk.com/showphoto.php?no_index=1&amp;urn=OV0C7BAGVNQ5TC7E" data-width="450" data-show-faces="true" data-send="true"></div></div><div class="buttons"><a href="/download.php?<?php echo isset($_GET['no_index'])?"no_index=1&":"";  ?>urn=<?php echo $user['urn']; ?>"><img src="/assets/downloadphoto.png" style="border:none;" width="223" height="52" alt="Download Photo" /></a><a href="/sharephoto.php?urn=<?php echo $user['urn']; ?>&share_type=TWITTER"><img style="border:none;" src="/assets/shareontwitter.png" width="226" height="52" alt="Share on Twitter" /></a><a href="/sharephoto.php?urn=<?php echo $user['urn']; ?>&share_type=FACEBOOK">
      <img src="/assets/shareonfb.png" style="border:none;" width="227" height="52" alt="Share on Facebook" /></a></div></td>
    </tr>
  <tr>
    <td align="center"><a href="https://www.facebook.com/BarclaysFootball" target="_blank"><img style="border:none;" src="/assets/connectwithus.png" width="160" height="44" alt="Connect With Us" /></a></td>
    </tr>
</table>
</div>
</body>
</html>
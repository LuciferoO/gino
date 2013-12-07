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
<meta charset="utf-8">
<meta property="og:title" content="My Christmas photo from Sky" />
<meta property="fb:admins" content="595373701" />
<meta property="fb:app_id" content="157192541140769" />
<meta property="og:image" content="https://skytoystory.wassermanexperience.com/photo/<?php echo $photo['filename']; ?>" /> 
<meta property="og:image:type" content="image/jpeg" />
<meta property="og:image:width" content="620" />
<meta property="og:description" content="Wishing you a Christmas full of magical moments from Sky" />
<meta property="og:url" content="https://skytoystory.wassermanexperience.com<?php echo $_SERVER['REQUEST_URI']; ?>">
<meta property="og:determiner" content="a" />
 <meta property="og:type"   content="skydisney:photo" /> 
<title>My Christmas photo from Sky</title>
<style type="text/css">
body {
	background-image: url(/emailer/background.jpg);
	background-repeat: repeat;
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
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-43693286-1', 'wassermanexperience.com');
  ga('send', 'pageview');

</script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=157192541140769";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div style="width:824px;margin-left:auto;margin-right:auto;"><table width="100%" border="0" cellspacing="0" cellpadding="8">
  <tr>
    <td align="center"><p style="te"><a href="#"></a>
      <img src="/emailer/sky_header_web.png" width="800" height="138" alt="Barclays" /></p>
      <p>&nbsp; </p>
      <table cellpadding="0" cellspacing="0">
        <tr>

          <td align="center" valign="middle" ><a href="#"><img src="https://skytoystory.wassermanexperience.com/photo/<?php echo $photo['filename']; ?>" alt="" width="800" height="600" class="centralimage" style="border:none;" /></a></td>

          </tr>
    </table></td>
    </tr>
  <tr>
    <td align="center"><div class="fb-like" data-href="https://skytoystory.wassermanexperience.com<?php echo $_SERVER['REQUEST_URI']; ?>" data-width="450" data-show-faces="true" data-send="true"></div></td>
  </tr>
  <tr>
    <td align="center"><div class="buttons"><a href="/download.php?<?php echo isset($_GET['no_index'])?"no_index=1&":"";  ?>urn=<?php echo $user['urn']; ?>"><img src="/emailer/downloadphoto.gif" style="border:none;" width="208" height="47" alt="Download Photo" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/sharephoto.php?urn=<?php echo $user['urn']; ?>&share_type=FACEBOOK">
      <img src="/emailer/shareonfacebook.gif" style="border:none;" width="208" height="47" alt="Share on Twitter" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/sharephoto.php?urn=<?php echo $user['urn']; ?>&share_type=TWITTER"><img src="/emailer/shareontwitter.gif" style="border:none;" width="208" height="47" alt="Share on Twitter" /></a></div></td>
    </tr>
  <tr>
    <td align="center"><a href="https://www.facebook.com/sky" target="_blank"><img style="border:none;" src="/emailer/fbconnect.gif" width="143" height="30" alt="Connect With Us" /></a></td>
    </tr>
</table>
</div>
</body>
</html>
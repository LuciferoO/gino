<?php require_once('./Connections/php.mysql.class.php'); ?>
<?php
$db= new MySQL(DB,DBUSER,DBPASS);

$user=$db->Select("users",array("urn"=>$_GET['urn']));

if($db->records==0) {
	die("direct access not allowed");
}

$photo=$db->Select("userphoto",array("urn"=>$_GET['urn']));

switch($_GET['share_type']) {
	case "TWITTER":
		$incrementfield="twitter_share";
		$url="http://www.twitter.com/share?text=".urlencode("My Christmas photo from Sky ")."&url=".$photo['shorturl'];
	break;
	case "FACEBOOK":
		$incrementfield="facebook_share";
		$url="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fskytoystory.wassermanexperience.com%2Ffbphoto%2F".$user['urn'];
	break;
	default:
		die("direct access not allowed");
	break;
}


$result=$db->ExecuteSQL("update stats set ".$incrementfield."=".$incrementfield."+1 where urn='".$db->SecureData($_GET['urn'])."'");

if(!$result) {
	die("error updating stats".$db->lastQuery."-".$db->lastError);
}



header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.


?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Refresh" content="3; url=<?php echo $url; ?>" />
<title>Sky Toy Story - Share</title>
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

</body>
</html>
<?php require_once('Connections/localhost.php'); ?>
<?php
if (!isset($_GET['urn'])) {
	die("direct access not allowed"); 
}
	
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

$colname_userinfo = "-1";
if (isset($_GET['urn'])) {
  $colname_userinfo = $_GET['urn'];
}
mysql_select_db($database_localhost, $localhost);
$query_userinfo = sprintf("SELECT * FROM users WHERE urn = %s", GetSQLValueString($colname_userinfo, "text"));
$userinfo = mysql_query($query_userinfo, $localhost) or die(mysql_error());
$row_userinfo = mysql_fetch_assoc($userinfo);
$totalRows_userinfo = mysql_num_rows($userinfo);

$insertSQL = sprintf("insert ignore into stats (stats.urn,stats.email_hash,stats.post_type) values (%s,MD5(%s),'email')",
                       GetSQLValueString($_GET['urn'], "text"),
					   GetSQLValueString($row_userinfo['email'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die("result=ERROR&message=".urlencode("error:".mysql_error()."query:".$insertSQL ));

	
  $updateSQL = sprintf("update stats set stats.email_share=stats.email_share+1 where stats.urn=%s",
                       GetSQLValueString($_GET['urn'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die("result=ERROR&message=".$updateSQL.(mysql_error()));


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="3; url=https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fsunglasshut.wassermanexperience.com%2Fshowphoto.php%3Furn%3D<?php echo $_GET['urn']; ?>" />
<title>Sunglass Hut</title>
<style type="text/css">
body,td,th {
	color: #FFF;
}
body {
	background-color: #000;
}
</style>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41838708-1', 'wassermanexperience.com');
  ga('send', 'pageview');

</script>
</body>
</html>
<?php
mysql_free_result($userinfo);
?>

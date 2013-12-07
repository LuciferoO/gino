<?php require_once($_SERVER['DOCUMENT_ROOT'].'/summertimeremote/Connections/localhost.php'); ?>
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

mysql_select_db($database_localhost, $localhost);
$query_getphoto = "SELECT *,DATEDIFF(NOW(),last_scraped) FROM userposts where isnull(last_scraped) or DATEDIFF(NOW(),last_scraped)>1 limit 1";
$getphoto = mysql_query($query_getphoto, $localhost) or die(mysql_error());
$row_getphoto = mysql_fetch_assoc($getphoto);
$totalRows_getphoto = mysql_num_rows($getphoto);

$colname_getuser = "-1";
if (isset($row_getphoto['urn'])) {
  $colname_getuser = $row_getphoto['urn'];
}
mysql_select_db($database_localhost, $localhost);
$query_getuser = sprintf("SELECT * FROM users WHERE urn = %s", GetSQLValueString($colname_getuser, "text"));
$getuser = mysql_query($query_getuser, $localhost) or die(mysql_error());
$row_getuser = mysql_fetch_assoc($getuser);
$totalRows_getuser = mysql_num_rows($getuser);

echo "now scraping:".$row_getuser['fb_id']." token:".$row_getuser['token'];

if($totalRows_getuser>0) {
	
	mysql_select_db($database_localhost, $localhost);
	$query_setuser = sprintf("update userposts set last_scraped=NOW() WHERE urn = %s", 
	GetSQLValueString($row_getphoto['urn'], "text"));
	$setuser = mysql_query($query_setuser, $localhost) or die(mysql_error());

require $_SERVER['DOCUMENT_ROOT'].'/summertimeremote/php-sdk/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '157192541140769',
  'secret' => '2d6f1e67564407cbb5eaa746f9ca2b5a',
));

// Get User ID
$facebook->setAccessToken($row_getuser['token']);

try {
	$data = $facebook->api('/'.$row_getphoto['postid']."?limit=1000", 'get'); 
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
	die();
}

print_r($data);
print "total comments:".count($data['comments']['data']);
print "total comments:".count($data['likes']['data']);

$comments=count($data['comments']['data']);
$likes=count($data['likes']['data']);

	$query_setuser = sprintf("update stats set comment_count_post=%s,like_count_post=%s WHERE urn = %s", 
	GetSQLValueString($comments, "text"),
	GetSQLValueString($likes, "text"),
	GetSQLValueString($row_getphoto['urn'], "text"));
	$setuser = mysql_query($query_setuser, $localhost) or die(mysql_error());




}

mysql_free_result($getphoto);

mysql_free_result($getuser);
?>

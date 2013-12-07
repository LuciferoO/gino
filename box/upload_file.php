<?php require_once('../Connections/localhost.php'); ?>
<?php
error_reporting(E_ALL);

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
$query_boxitems = "SELECT * FROM boxdata";
$boxitems = mysql_query($query_boxitems, $localhost) or die(mysql_error());
$row_boxitems = mysql_fetch_assoc($boxitems);
$totalRows_boxitems = mysql_num_rows($boxitems);
if($totalRows_boxitems>0) {
$url = 'http://api.box.com/2.0/files/content';

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");

   //this is my method to construct the Authorisation header
   $header_details = array('Authorization: Bearer '.$row_boxitems['access_token']);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $header_details);

   $post_vars = array();
   $post_vars['filename'] = "@/var/www/vhosts/wassermanexperience.com/skytoystory/twitter/thumb.png";
   $post_vars['folder_id'] = 0;

   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vars);
   curl_setopt($ch, CURLOPT_URL, $url);

   $data = curl_exec($ch);
   curl_close($ch);
   echo $data;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($boxitems);
?>

<?php require_once('Connections/localhost.php'); ?>
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
$query_getlikes = "SELECT * FROM likecount";
$getlikes = mysql_query($query_getlikes, $localhost) or die(mysql_error());
$row_getlikes = mysql_fetch_assoc($getlikes);
$totalRows_getlikes = mysql_num_rows($getlikes);

if($totalRows_getlikes>0) {

mysql_select_db($database_localhost, $localhost);
$user = mysql_query("update likecount likes set likecount=likecount+1", $localhost) or die(mysql_error());
} else {
	mysql_select_db($database_localhost, $localhost);
$user = mysql_query("insert INTO likecount (likecount.likecount) VALUES (1)", $localhost) or die(mysql_error());
	
}

mysql_free_result($getlikes);
?>
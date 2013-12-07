<?php require_once('../Connections/localhost.php'); ?>
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
$query_boxitems = sprintf("SELECT * FROM boxdata WHERE ipaddress = %s", GetSQLValueString($_SERVER['REMOTE_ADDR'], "text"));
$boxitems = mysql_query($query_boxitems, $localhost) or die(mysql_error());
$row_boxitems = mysql_fetch_assoc($boxitems);
$totalRows_boxitems = mysql_num_rows($boxitems);
if($totalRows_boxitems>0) {
$header = array('Authorization: Bearer '.$row_boxitems['access_token']);

$curl = curl_init();

curl_setopt( $curl, CURLOPT_URL, 'http://api.box.com/2.0/folders/1012423483');
curl_setopt( $curl, CURLOPT_HTTPHEADER, $header);
curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt( $curl, CURLOPT_HTTP_VERSION, "CURL_HTTP_VERSION_1_1");
curl_setopt( $curl, CURLOPT_VERBOSE, true );
//curl_setopt( $curl, CURLINFO_HEADER, true);
curl_setopt( $curl, CURLINFO_HEADER_OUT, true);
//curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 2);

$res = curl_exec( $curl );

if ($res === false)
{
    print_r('Curl error: ' . curl_error($curl));
}

var_dump( curl_getinfo($curl) );
curl_close($curl);

var_dump($res);
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

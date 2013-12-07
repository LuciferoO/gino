<?php 
require_once('Connections/localhost.php'); ?>
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

if (!isset($_POST['urn'])) {
	die("result=ERROR&message=".urlencode("direct acess not allowed"));
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST['urn']))&& strlen($_POST['urn'])>10) {
$insertSQL = sprintf("insert ignore into stats (stats.urn,stats.email_hash,stats.post_type) values (%s,MD5(%s),'email')",
                       GetSQLValueString($_POST['urn'], "text"),
					   GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die("result=ERROR&message=".urlencode("error:".mysql_error()."query:".$insertSQL ));
	
	
  $insertSQL = sprintf("INSERT INTO users (urn, firstname, lastname, email,mobile,current_location,extraterms,customertype,address,postcode,heardofus) VALUES (%s, %s, %s, %s, %s,%s,%s,%s,%s,%s, %s)",
                       GetSQLValueString($_POST['urn'], "text"),
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
					   GetSQLValueString($_POST['customertype'], "text"),
					   GetSQLValueString($_POST['current_location'], "text"),
					   GetSQLValueString($_POST['extraterms'], "text"),
					   GetSQLValueString($_POST['customertype'], "text"),
					   GetSQLValueString($_POST['address'], "text"),
					   GetSQLValueString($_POST['postcode'], "text"),
					   GetSQLValueString($_POST['heardofus'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die("result=ERROR&message=".urlencode(mysql_error()));
  die("result=OK");
} else {
	 die("result=ERROR&message=".urlencode("No urn set!"));
}
?>
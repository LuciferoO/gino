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


?>
<?php


 
define('VERIFY_TOKEN', 'YOURVERIFYTOKEN');
$method = $_SERVER['REQUEST_METHOD'];
 
if ($method == 'GET' && $_GET['hub_mode'] == 'subscribe' && $_GET['hub_verify_token'] == VERIFY_TOKEN) {
    echo $_GET['hub_challenge'];
} else if ($method == 'POST') {
    $updates = json_decode(file_get_contents("php://input"), true);
 
    // Here you can do whatever you want with the JSON object that you receive from FaceBook.
    // Before you decide what to do with the notification object you might aswell just check if
    // you are actually getting one. You can do this by choosing to output the object to a textfile.
    // It can be done by simply adding the following line:
    // file_put_contents('/filepath/updates.txt',$updates, FILE_APPEND);
 
    error_log('updates = ' . print_r($obj, true));
}
 
?>
  

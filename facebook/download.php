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



/*mysql_select_db($database_localhost, $localhost);
$query_Recordset1 = "update downloadcount set downloadcount.count=downloadcount.count+1";
$Recordset1 = mysql_query($query_Recordset1, $localhost) or die(mysql_error());*/
$updateSQL = sprintf("update stats set stats.download_from_email=stats.download_from_email+1 where stats.urn=%s",
                       GetSQLValueString($_GET['urn'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die("result=ERROR&message=".$updateSQL.(mysql_error()));


$file =  "rendered/".preg_replace('#[\r\n]#', '', $_GET['filename']);
//echo "path: $file";
//echo "size:".filesize($file);
//readfile($file);
//die();

if (strpos($file, '../') !== false ||
    strpos($file, "..\\") !== false ||
    strpos($file, '/..') !== false ||
    strpos($file, '\..') !== false ||
	strpos($file, '.php') !== false ||
	strpos($file, '://') !== false)
{
    die("death to the hacker");
}



if(ini_get('zlib.output_compression'))
  ini_set('zlib.output_compression', 'Off');
  
header("Pragma: public"); // required
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false); // required for certain browsers 
 header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;



mysql_free_result($Recordset1);
?>

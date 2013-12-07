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

$input=urldecode(@file_get_contents('php://input'));
$result=substr($input,1);

$xml=simplexml_load_string($result);
$result.=print_r($xml,true);

 $insertSQL = sprintf("INSERT INTO box_ids (dump,item_name,item_id,item_parent_folder_id,item_creation) VALUES (%s,%s,%s,%s,NOW()) on duplicate key update item_creation=NOW()",
 GetSQLValueString($result, "text"),
 GetSQLValueString($xml->item_name, "text"),
 GetSQLValueString($xml->item_id, "text"),
 GetSQLValueString($xml->item_parent_folder_id, "text")
                       );

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

	mysql_select_db($database_localhost, $localhost);
	$query_boxitems = "SELECT * FROM boxdata";
	$boxitems = mysql_query($query_boxitems, $localhost) or die(mysql_error());
	$row_boxitems = mysql_fetch_assoc($boxitems);
	$totalRows_boxitems = mysql_num_rows($boxitems);
	if($totalRows_boxitems>0) {
	$result=shell_exec('curl http://api.box.com/2.0/files/'.$xml->item_id.' -H "Authorization: Bearer '.$row_boxitems['access_token'].'" -d \'{"shared_link": {"access": "open"}}\' -X PUT  2>&1');
		
	$parsed_result=substr($result,strpos($result,"{"));
	$gino=json_decode($parsed_result);
	echo "download link:".$gino->shared_link->download_url;
	
	 $updateSQL = sprintf("update box_ids set download_link=%s where item_id=%s",
 GetSQLValueString($gino->shared_link->download_url, "text"),
 GetSQLValueString($xml->item_id, "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
	

	}
?>
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
$query_boxitems = "SELECT * FROM boxdata";
$boxitems = mysql_query($query_boxitems, $localhost) or die(mysql_error());
$row_boxitems = mysql_fetch_assoc($boxitems);
$totalRows_boxitems = mysql_num_rows($boxitems);
if($totalRows_boxitems>0) {

function flatten_GP_array(array $var,$prefix = false){
        $return = array();
        foreach($var as $idx => $value){
                if(is_scalar($value)){
                        if($prefix){
                                $return[$prefix.'['.$idx.']'] = $value;
                        } else {
                                $return[$idx] = $value;
                        }
                } else {
                        $return = array_merge($return,flatten_GP_array($value,$prefix ? $prefix.'['.$idx.']' : $idx));
                }
        }
        return $return;
}

var_dump(flatten_GP_array(array("shared_link"=>array("access"=>"open"))));


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.box.com/2.0/files/9490624949");
curl_setopt($ch, CURLOPT_HEADER, false); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, flatten_GP_array(array("shared_link"=>array("access"=>"open"))));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$row_boxitems['access_token']));
$result = curl_exec($ch);
echo "error:".curl_error($ch);
curl_close($ch);

print_r($result);


$parsed_result=substr($result,strpos($result,"{"));
$gino=json_decode($parsed_result);
echo "download link:".$gino->shared_link->download_url;
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

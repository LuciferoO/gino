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

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_GET['id'])) && ($_GET['id'] != "") && (isset($_GET['delete']))) {
	
	mysql_select_db($database_localhost, $localhost);
	$query_limit_getphotos = sprintf("SELECT * FROM userphoto where id=%s", $_GET['id']);
	$getphotos = mysql_query($query_limit_getphotos, $localhost) or die(mysql_error());
	$row_getphotos = mysql_fetch_assoc($getphotos);
	
  $deleteSQL = sprintf("DELETE FROM userphoto WHERE id=%s",
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($deleteSQL, $localhost) or die(mysql_error());
  @unlink("uploads/".$row_getphotos['filename']);
}

$maxRows_getphotos = 30;
$pageNum_getphotos = 0;
if (isset($_GET['pageNum_getphotos'])) {
  $pageNum_getphotos = $_GET['pageNum_getphotos'];
}
$startRow_getphotos = $pageNum_getphotos * $maxRows_getphotos;

mysql_select_db($database_localhost, $localhost);
$query_getphotos = "SELECT * FROM userphoto ORDER BY creationdate DESC";
$query_limit_getphotos = sprintf("%s LIMIT %d, %d", $query_getphotos, $startRow_getphotos, $maxRows_getphotos);
$getphotos = mysql_query($query_limit_getphotos, $localhost) or die(mysql_error());
$row_getphotos = mysql_fetch_assoc($getphotos);

if (isset($_GET['totalRows_getphotos'])) {
  $totalRows_getphotos = $_GET['totalRows_getphotos'];
} else {
  $all_getphotos = mysql_query($query_getphotos);
  $totalRows_getphotos = mysql_num_rows($all_getphotos);
}
$totalPages_getphotos = ceil($totalRows_getphotos/$maxRows_getphotos)-1;

$queryString_getphotos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_getphotos") == false && 
        stristr($param, "totalRows_getphotos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_getphotos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_getphotos = sprintf("&totalRows_getphotos=%d%s", $totalRows_getphotos, $queryString_getphotos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p>&nbsp; </p>
<table border="0">
  <tr>
    <td><?php if ($pageNum_getphotos > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_getphotos=%d%s", $currentPage, 0, $queryString_getphotos); ?>">First</a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_getphotos > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_getphotos=%d%s", $currentPage, max(0, $pageNum_getphotos - 1), $queryString_getphotos); ?>">Previous</a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_getphotos < $totalPages_getphotos) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_getphotos=%d%s", $currentPage, min($totalPages_getphotos, $pageNum_getphotos + 1), $queryString_getphotos); ?>">Next</a>
      <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_getphotos < $totalPages_getphotos) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_getphotos=%d%s", $currentPage, $totalPages_getphotos, $queryString_getphotos); ?>">Last</a>
      <?php } // Show if not last page ?></td>
  </tr>
</table>
</p>
<table border="1">
  <tr>
    <td>id</td>
    <td>urn</td>
    <td>creationdate</td>
    <td>value</td>
    <td>filename</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_getphotos['id']; ?></td>
      <td><?php echo $row_getphotos['urn']; ?></td>
      <td><?php echo $row_getphotos['creationdate']; ?></td>
      <td><?php echo $row_getphotos['value']; ?></td>
      <td><img name="" src="uploads/<?php echo $row_getphotos['filename']; ?>" width="300" height="200" alt="" /></td>
      <td><a href="browsepics.php?delete=1&amp;id=<?php echo $row_getphotos['id']; ?>">delete</a></td>
    </tr>
    <?php } while ($row_getphotos = mysql_fetch_assoc($getphotos)); ?>
</table>
<p>&nbsp;
<table border="0">
  <tr>
    <td><?php if ($pageNum_getphotos > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_getphotos=%d%s", $currentPage, 0, $queryString_getphotos); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_getphotos > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_getphotos=%d%s", $currentPage, max(0, $pageNum_getphotos - 1), $queryString_getphotos); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_getphotos < $totalPages_getphotos) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_getphotos=%d%s", $currentPage, min($totalPages_getphotos, $pageNum_getphotos + 1), $queryString_getphotos); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_getphotos < $totalPages_getphotos) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_getphotos=%d%s", $currentPage, $totalPages_getphotos, $queryString_getphotos); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
</p>
</body>
</html>
<?php
mysql_free_result($getphotos);
?>

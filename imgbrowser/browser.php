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

if(!isset($_SESSION)) {
	session_start();
}



$colname_GetStuff = "Arsenal Stadium";
if (isset($_POST['current_location'])) {
	$_SESSION['current_location']=$_POST['current_location'];
  	$colname_GetStuff = $_POST['current_location'];
} else if (isset($_SESSION['current_location'])) {
	$colname_GetStuff=$_SESSION['current_location'];
}
mysql_select_db($database_localhost, $localhost);
$query_GetStuff = sprintf("select users.urn,`users`.`id` AS `id`,`users`.`user_id` AS `Social Media User Id`,`users`.`screen_name` AS `Screen Name`,`users`.`firstname` AS `firstname`,`users`.`lastname` AS `lastname`,`users`.`email` AS `email`,`users`.`mobile` AS `mobile`,`users`.`current_location` AS `current_location`,`users`.`added` AS `added`,`userphoto`.`userid` AS `userid`,`userphoto`.`social_id` AS `social_id`,`userphoto`.`creationdate` AS `creationdate`,`userphoto`.`post_type` AS `post_type`,`userphoto`.`mediatype` AS `mediatype`,`userphoto`.`filename` AS `filename` from (`users` join `userphoto` on((`users`.`urn` = `userphoto`.`urn`))) where (date(users.added)>=date('2013-11-15')) and exists(select * from stats where stats.urn=users.urn and stats.facebook_share>0)", GetSQLValueString($colname_GetStuff, "text"));
$GetStuff = mysql_query($query_GetStuff, $localhost) or die(mysql_error());
$totalRows_GetStuff=mysql_num_rows($GetStuff);
$row_GetStuff = mysql_fetch_assoc($GetStuff);


	function changeExt($fileName) {
		return preg_replace('"\.mp4$"', '.jpg', $fileName);
	}
	
	function makeLink($urn,$mediatype,$file="") {
		if ($mediatype=="jpgfile") {
			return "showphoto.php?no_index=1&urn=".$urn;
		} else if ($mediatype=="videofile")  {
			return "showvideo.php?no_index=1&urn=".$urn;
		}
	}
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Browse media</title>
<style type="text/css">
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
}
a:link {
	color: #03ADEF;
}
a:visited {
	color: #03ADEF;
}
a:hover {
	color: #03ADEF;
}
a:active {
	color: #03ADEF;
}
</style>
</head>

<body>
<p>&lt;- <a href="index.php">Back to Index&nbsp;</a>
<p><?php echo $totalRows_GetStuff ?>
  </p>
<table border="1">
  <tr>
    <td>Post Type</td>
    <td>&nbsp;</td>
    <td>Media<br>
      type</td>
    <td>Picture</td>
    <td>firstname</td>
    <td>Real Picture</td>
    <td>lastname</td>
    <td>email</td>
    <td>mobile</td>
    <td>Social Media
      <br>
    Screen Name</td>
    <td>Social Media<br>
      User ID</td>
    <td>Event Location</td>
    <td>Timestamp</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_GetStuff['post_type']; ?></td>
      <td><a href="emailer.php?urn=<?php echo $row_GetStuff['urn']; ?>">send email</a></td>
      <td><?php echo $row_GetStuff['mediatype']; ?></td>
      <td><a href="../<?php echo makeLink($row_GetStuff['urn'],$row_GetStuff['mediatype']); ?>" target="_blank"><img src="../squarethumbs/<?php echo changeExt($row_GetStuff['filename']); ?>"  alt=""></a></td>
      <td><?php echo $row_GetStuff['firstname']; ?></td>
      <td><img src="../photo/<?php echo changeExt($row_GetStuff['filename']); ?>"  alt="" width="200"></td>
      <td><?php echo $row_GetStuff['lastname']; ?></td>
      <td><?php echo $row_GetStuff['email']; ?></td>
      <td><?php echo $row_GetStuff['mobile']; ?></td>
      <td><?php echo $row_GetStuff['Screen Name']; ?></td>
      <td><?php echo $row_GetStuff['Social Media User Id']; ?></td>
      <td><?php echo $row_GetStuff['current_location']; ?></td>
      <td><?php echo $row_GetStuff['added']; ?></td>
    </tr>
    <?php } while ($row_GetStuff = mysql_fetch_assoc($GetStuff)); ?>
</table>
<p><?php echo $totalRows_GetStuff ?>
</body>
</html>
<?php
mysql_free_result($GetStuff);
?>

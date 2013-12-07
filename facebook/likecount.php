<?php
require 'php-sdk/src/facebook.php';
			
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '157192541140769',
  'secret' => 'f85958425660412d8df765436d1cd74f',
));
function getLikes ($usertoken, $photoid)
{
	 $facebook = $GLOBALS['facebook'];
	 
	 $facebook->setAccessToken( $usertoken );
	
	 if ($photoid!=NULL)
	 {
	
	try {
		$data = $facebook->api('/'.$photoid.'/likes', 'get'); 
		//echo $photoid."<br/>";
		//echo $usertoken."<br/>";
		
		//print_r( $data );
		
		return count($data['data']) ;

	} catch (FacebookApiException $e) {}
	 } else
	 {
	return -1;
	 }
	 return 0;
}



?><?php require_once('Connections/localhost.php'); ?>
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
$query_countusers = "SELECT
userphoto.id,
userphoto.userid,
userphoto.urn,
userphoto.creationdate,
userphoto.`value`,
userphoto.facebook,
userphoto.filename,
userphoto.photoid,
users.id,
users.urn,
users.token,
users.firstname,
users.lastname,
users.email,
users.mobile,
users.profileurl_large,
users.profileurl,
users.fbusername,
users.added,
users.photo,
users.fb_id,
users.gender,
users.team,
users.customer,
users.optInTerms,
users.posts,
users.extendedterms,
users.optInMarketing,
users.winner
FROM
users
INNER JOIN userphoto ON users.urn = userphoto.urn
WHERE
not isnull(photoid)";
$countusers = mysql_query($query_countusers, $localhost) or die(mysql_error());
$row_countusers = mysql_fetch_assoc($countusers);
$totalRows_countusers = mysql_num_rows($countusers);

$getusers = mysql_query($query_countusers, $localhost) or die(mysql_error());
$row_getusers = mysql_fetch_assoc($getusers);
$totalRows_getusers = mysql_num_rows($getusers);

echo $query_countusers;



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Like count</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>

<h1>Generating like count, please wait... </h1>
<h2>Total photos to count:<strong><?php echo $totalRows_getusers; ?></strong></h2>
<table border="1">
  <tr>
    <td>id</td>
    <td>urn</td>
    <td>token</td>
    <td>firstname</td>
    <td>lastname</td>
    <td>email</td>
    <td>mobile</td>
    <td>profileurl_large</td>
    <td>profileurl</td>
    <td>fbusername</td>
    <td>added</td>
    <td>photo</td>
    <td>fb_id</td>
    <td>photoid</td>
    <td>team</td>
    <td>customer</td>
    <td>optInTerms</td>
    <td>posts</td>
    <td>extendedterms</td>
    <td>optInMarketing</td>
    <td>winner</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_getusers['id']; ?></td>
      <td><?php echo $row_getusers['urn']; ?></td>
      <td><?php echo $row_getusers['token']; ?></td>
      <td><?php echo $row_getusers['firstname']; ?></td>
      <td><?php echo $row_getusers['lastname']; ?></td>
      <td><?php echo $row_getusers['email']; ?></td>
      <td><?php echo $row_getusers['mobile']; ?></td>
      <td><?php echo $row_getusers['profileurl_large']; ?></td>
      <td><?php echo $row_getusers['profileurl']; ?></td>
      <td><?php echo $row_getusers['fbusername']; ?></td>
      <td><?php echo $row_getusers['added']; ?></td>
      <td><?php echo $row_getusers['photo']; ?></td>
      <td><?php echo $row_getusers['fb_id']; ?></td>
      <td><?php echo $row_getusers['photoid']; ?></td>
      <td><?php echo $row_getusers['team']; ?></td>
      <td><?php echo $row_getusers['customer']; ?></td>
      <td><?php echo $row_getusers['optInTerms']; ?></td>
      <td><?php echo $row_getusers['posts']; ?></td>
      <td><?php echo $row_getusers['extendedterms']; ?></td>
      <td><?php echo $row_getusers['optInMarketing']; ?></td>
      <td><?php echo getLikes ($row_getusers['token'], $row_getusers['photoid']);
mysql_select_db($database_localhost, $localhost); ?></td>
    </tr>

    <?php } while ($row_getusers = mysql_fetch_assoc($getusers)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($getusers);
?>

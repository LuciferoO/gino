<?php require_once('Connections/carPhoto.php'); ?>
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

  $insertSQL = sprintf("INSERT INTO users (`first`, `last`, filename, email) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['first'], "text"),
                       GetSQLValueString($_POST['last'], "text"),
                       GetSQLValueString($_POST['filename'].".jpg", "text"),
                       GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_carPhoto, $carPhoto);
  $Result1 = mysql_query($insertSQL, $carPhoto) or die(mysql_error());
  
  $id=mysql_insert_id();
  
  echo "id: $id";

	require_once('phpmailer/class.phpmailer.php');
	
	$mail             = new PHPMailer(); // defaults to using php "mail()"
	
	$mail->IsSendmail(); // telling the class to use SendMail transport
	
	$body             = file_get_contents('contents.html');
	$body             = eregi_replace("[\]",'',$body);
	
	$body=str_replace("#name#",$_POST['first'],$body);
	
	$body=str_replace("#image#",$_POST['filename'].".jpg",$body);
	
	$mail->AddReplyTo("Noreply@carlsberg.co.uk","Carlsberg Photo Booth");
	
	$mail->SetFrom('Noreply@carlsberg.co.uk', 'Carlsberg Photo Booth');
	
	
	$mail->AddAddress($_POST['email'],$_POST['first']." ".$_POST['last']);
	//$mail->AddAddress("andrew.fraser@ignite-london.com", "Andrew Fraser");
	
	$mail->Subject    = "Your Carlsberg Photo Booth Picture";
	
	$mail->AltBody    = "To see your picture, please use a HTML compatible viewer!"; // optional, comment out and test
	
	$mail->MsgHTML($body);
	
	$mail->AddAttachment("uploads/".$_POST['filename'].".jpg"); // attachment
	
	if(!$mail->Send()) {
	  $result= $mail->ErrorInfo;
	} else {
	  $result= "OK";
	}

	
	
	
  $insertSQL = sprintf("update users set emailstatus=%s where id=%s",
                       GetSQLValueString($result, "text"),
					   GetSQLValueString($id, "text"));

  mysql_select_db($database_carPhoto, $carPhoto);	
	$Result1 = mysql_query($insertSQL, $carPhoto) or die($insertSQL." ".mysql_error());


echo "result from server<br/>";
print_r($_POST);

?>
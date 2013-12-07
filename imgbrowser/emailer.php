<?php require_once('../Connections/localhost.php'); ?>

<?php

chdir(dirname(__FILE__));
error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/php.mysql.class.php');

emailUser($_GET['urn']);

function emailUser($urn) {
	chdir(dirname(__FILE__));
	
	
	
	$db= new MySQL(DB,DBUSER,DBPASS);
	$result=$db->Select("users",array("urn"=>$urn));
	
	if(!$result) {
		die("mysql error:".$db->lastError);
	}
	echo "rows found:".$db->records."<br/>";
	
		print_r($result);
	
	$photo=$db->Select("userphoto",array("urn"=>$urn));
	
	if(!$result) {
		die("mysql error:".$db->lastError);
	}
	
	echo "photo rows found:".$db->records."<br/>";
	
	print_r($photo);
	

	


	

		$label="photo";
		$link="http://skytoystory.wassermanexperience.com/showphoto.php?urn=";
		$rewriteurl="http://skytoystory.wassermanexperience.com/showphoto/";
		$rewritefb="http://skytoystory.wassermanexperience.com/fbphoto/";
		$rewritetwit="http://skytoystory.wassermanexperience.com/twitterphoto/";
	

	$shortenurl=shortenURL($rewritetwit.$urn);

	require_once($_SERVER['DOCUMENT_ROOT'].'/phpmailer/class.phpmailer.php');
	
	$mail             = new PHPMailer(); // defaults to using php "mail()"
	
	$mail->SMTPDebug = false;
	$mail->do_debug = 0;

	$mail->IsSMTP();
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "ssl://smtp.sendgrid.net"; // sets the SMTP server
	$mail->Port       = 465;   
	$mail->Username   = "wasserman"; // SMTP account username
	$mail->Password   = "k0st0golov";        // SMTP account password

	$mail->AddReplyTo("noreply@sky.com","Sky");
	
	$mail->SetFrom("noreply@sky.com","Sky");
	$mail->CharSet="UTF-8";
	
	$mail->AddAddress("tlovegrove@wmgllc.com","Tom Lovegrove");
	$mail->AddBCC("lisophorm@gmail.com","Alfonso Florio");
	$mail->AddBCC("bratcliffe@wmgllc.com","Becca Ratcliffe");
	
	$mail->Subject    = "Your Toy Story photo from Sky";
	//$mail->AddBCC("lisophorm@gmail.com","Alfonso");
	$mail->AltBody    = "Please use an html compatible viewer!\n\n"; // optional, comment out and test
	
	$body=file_get_contents($_SERVER['DOCUMENT_ROOT']."/emailer/mailtemplate.html");
	
	$body=str_replace("#name#",$result['firstname'],$body);
	$body=str_replace("#mediatype#",$label,$body);
	$body=str_replace("#link#",$rewriteurl.$urn,$body);
	//$body=str_replace("#filename#",$_POST['file']."&urn=".$row_user['urn'],$body);
	$body=str_replace("#urn#",$urn,$body);
	$body=str_replace("#filename#",$photo['filename'],$body);
	
	$mail->MsgHTML($body);
	
	$mail->AddCustomHeader(sprintf( 'X-SMTPAPI: %s', '{"unique_args": {"urn":"'.$xml->urn.'"},"category": "SkyToyStory"}' ) );

	//$basefile=urldecode(basename($_POST['file']));
	//$mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT']."/rendered/".$basefile,"logo_2u",$basefile); // attachment
	
	if(!$mail->Send()) {
	  $emailresult= $mail->ErrorInfo;
	  //echo("result=ERROR&message=".urlencode("Error while sending email:".$result));
	} else {
	 $emailresult="SUCCESS";
	}
	
	echo "email result:".$emailresult."<br/>";
	
	$db->CloseConnection();
	
}

function shortenURL($url) {
	$result=file_get_contents("http://is.gd/create.php?format=simple&url=".$url);
	return $result;
}


?>
<h1 style="color: #F00">Hit the back button, please</h1>

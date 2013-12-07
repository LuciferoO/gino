<html>
<head>
<title>PHPMailer - SMTP basic test with authentication</title>
</head>
<body>

<?php

//error_reporting(E_ALL);
error_reporting(E_STRICT);

date_default_timezone_set('America/Toronto');

require_once('../class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$body             = file_get_contents('contents.html');
$body             = eregi_replace("[\]",'',$body);

	$mail->SMTPDebug = true;
	$mail->do_debug = 1;

	$mail->IsSMTP();
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "ssl://smtp.sendgrid.net"; // sets the SMTP server
	$mail->Port       = 465;   
	$mail->Username   = "wasserman"; // SMTP account username
	$mail->Password   = "k0st0golov";        // SMTP account password
	
	$mail->AddCustomHeader(sprintf( 'X-SMTPAPI: %s', '{"category": "YouAreFootball"}' ) );

$mail->SetFrom('info@ignite-london.com', 'First Last');

$mail->AddReplyTo("info@ignite-london.com","First Last");

$mail->Subject    = "PHPMailer Test Subject via smtp, basic with authentication";

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

$address = "lisophorm@gmail.com";
$mail->AddAddress($address, "Alfonso Florio");

$mail->AddAttachment("images/phpmailer.gif");      // attachment
$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>

</body>
</html>

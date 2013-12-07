<?php require 'php-sdk/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '157192541140769',
  'secret' => 'f85958425660412d8df765436d1cd74f',
  'cookie' => true
));

$fb_key = 'fbs_'.sfConfig::get('app_facebook_application_id');
  set_cookie($fb_key, '', '', '', '/', '');
  $facebook->setSession(NULL);

?>

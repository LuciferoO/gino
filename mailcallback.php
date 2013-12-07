<?php
$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
echo "server root:".$_SERVER['DOCUMENT_ROOT'];

chdir(dirname(__FILE__));
error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/php.mysql.class.php');

$post=print_r($_POST,true);
$get=print_r($_GET,true);

$final="POST:".$post." GET:".$get;

if($_POST["category"]=="YouAreFootball") {

$db= new MySQL(DB,DBUSER,DBPASS);
$risultato=$db->Insert(array("urn"=>$_POST["urn"],"event"=>$_POST["event"],"url"=>$_POST["url"],"UserAgent"=>$_POST["UserAgent"],"event"=>$_POST["event"],"email"=>$_POST["email"]),"mail_logs");

$result=$db->Update("userphoto",array("last_email_event"=>$_POST["event"]),array("urn"=>$_POST["urn"]));

} else if($_POST["category"]=="SkyToyStory") {

$db= new MySQL("skydisney",DBUSER,DBPASS);
$risultato=$db->Insert(array("urn"=>$_POST["urn"],"event"=>$_POST["event"],"url"=>$_POST["url"],"UserAgent"=>$_POST["UserAgent"],"event"=>$_POST["event"],"email"=>$_POST["email"]),"mail_logs");

$result=$db->Update("userphoto",array("last_email_event"=>$_POST["event"]),array("urn"=>$_POST["urn"]));

} else if(strtolower($_POST["category"])=="justtextgiving") {

$db= new MySQL("admin_justtextgiving","tomvfn","b00msma88!");
$risultato=$db->Insert(array("urn"=>$_POST["urn"],"event"=>$_POST["event"],"url"=>$_POST["url"],"UserAgent"=>$_POST["UserAgent"],"event"=>$_POST["event"],"email"=>$_POST["email"]),"mail_logs");

$result=$db->Update("userphoto",array("last_email_event"=>$_POST["event"]),array("urn"=>$_POST["urn"]));

} else if($_POST["category"]=="Intranet") {
	
$db= new MySQL("admin_intranet","intranet","k0st0golov");
$risultato=$db->Insert(array("event"=>$_POST["event"],"url"=>$_POST["url"],"UserAgent"=>$_POST["UserAgent"],"event"=>$_POST["event"],"email"=>$_POST["email"]),"mail_logs");

}


?>


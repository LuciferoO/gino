<?php 
require_once('../Connections/localhost.php');
require_once('../Connections/php.mysql.class.php'); ?>
<?php



echo "ciAO";
$db= new MySQL(DB,DBUSER,DBPASS);
$result=$db->Select("boxdata",'','',1);
if($result) {
	echo "query ok";
} else {
	die("error:".$db->lastError);
}
print_r($result);
echo "total records".$db->records;
echo "token:".$result['refresh_token'];


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.box.com/oauth2/token");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=refresh_token&refresh_token='.$result['refresh_token'].'&client_id='.CLIENT_ID.'&client_secret='.CLIENT_SECRET);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);


$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if($httpCode!=200) {
		log_error("refresh token","http code:".$httpCode,curl_error($ch));
} else if( !$result ) 
    { 
        log_error("refresh token","",curl_error($ch));
    } 
curl_close($ch);

print_r($result);

if( !$result ) {
	die("end of procedure");
}



$parsed_result=substr($result,strpos($result,"{"));
$data=json_decode($parsed_result);
print_r($data);
if($data->error) {
	log_error("refresh token",$data->error,print_r($data,true));
	die("madonna puttana");
} 

$updateResult=$db->Update("boxdata",array("access_token"=>$data->access_token,
									"expires_in"=>$data->expires_in,
									"token_type"=>$data->token_type,
									"refresh_token"=>$data->refresh_token));
	
if($updateResult) {
	echo "token refreshed";
} else {
	echo "no good";
	var_dump($updateResult);
	echo "error:".$db->lastError;
}

$db->CloseConnection();

?>

<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_localhost = "localhost";
$database_localhost = "skydisney";
$username_localhost = "barclays";
$password_localhost = "k0st0golov";
$localhost = mysql_pconnect($hostname_localhost, $username_localhost, $password_localhost) or trigger_error(mysql_error(),E_USER_ERROR); 
?><?php
error_reporting(E_ALL);
define("CONSUMERKEY","MPo6c0OvaaItBQY0gdvHA");
define("CONSUMERSECRET","BT1W7A5Qn8MzZjK6hvHDKwk54Ak3gtzPvtJZZGsLiZU");

define("CLIENT_ID","zxmmdddymldpm6v7gkb5lklpplr9hks6");
define("CLIENT_SECRET","dO82JTRIo6J9mhABmddHw9AubvpoQE5W");


define("HOST","localhost");
define("DBUSER","barclays");
define("DBPASS","k0st0golov");
define("DB","skydisney");

function curla($fields=0,$url = 'http://www.box.com/api/oauth2/token',$bearer=NULL) {
	

	
	//url-ify the data for the POST
	if($fields!=0) {
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
	}
	
	echo "fields:".$fields_string."<br/>";
	
	//open connection
	$ch = curl_init();
	
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLINFO_HEADER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, "CURL_HTTP_VERSION_1_1");
	if($fields!=0) {
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	}
	if($bearer != NULL) {
		echo "curla de qua curla de la<br/>";
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer '.$bearer
    ));
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	
	//execute post
	$result = curl_exec($ch);
	
	//close connection
	curl_close($ch);
	
	return $result;
}
function http_build_query_for_curl( $arrays, &$new = array(), $prefix = null ) {

    if ( is_object( $arrays ) ) {
        $arrays = get_object_vars( $arrays );
    }

    foreach ( $arrays AS $key => $value ) {
        $k = isset( $prefix ) ? $prefix . '[' . $key . ']' : $key;
        if ( is_array( $value ) OR is_object( $value )  ) {
            http_build_query_for_curl( $value, $new, $k );
        } else {
            $new[$k] = $value;
        }
    }
}


?>

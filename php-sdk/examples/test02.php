<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require '../src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '157192541140769',
  'secret' => '2d6f1e67564407cbb5eaa746f9ca2b5a',
));

// Get User ID
$facebook->setAccessToken('AAACvCmO4HUMBAAkETmmxwiiWeiZAqWUkZAhJZB9SWLsbdPHAueZBFadfoZCO0pUUsPZA7S9b7O6mZA7uWdCiLMSGmjMILZCcy5jGCZBP1CNtSrAZDZD');

try {
	$data = $facebook->api('/me', 'get'); 
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
				die();
}


/*
$facebook->api('/me/checkins', 'POST', array(
'access_token' => $facebook->getAccessToken(),
'place' => '347938985254006',
'message' =>'I went to the Office!',
'picture' => 'http://ignitesocial.co.uk/vf_budapest/google.jpg',
'coordinates' => json_encode(array(
   'latitude'  => '51.5160652',
   'longitude' => '-0.1428639',
   'tags' => "100003724834749")
 )

)
)
*/
try {
		$facebook->api('/me/checkins', 'POST', array(
		'access_token' => $facebook->getAccessToken(),
		'place' => '376361179078795',
		'message' =>'Trying to win an Ultrabook ',
		'picture' => 'http://www.intel.co.uk/content/dam/intel/dm/image/logo.png',
		'coordinates' => json_encode(array(
		   'latitude'  => '51.536234370481',
		   'longitude' => '-0.035434579476747',
		   'tags' => $data['id'])
		 )
		
		)
		);
		} catch (FacebookApiException $e) {
					echo "result=ERROR&message=CHECKIN:".$e->getMessage();
				}	


	try {
					$publishStream = $facebook->api("/me/feed", 'post', array(
						'message' => 'This is the message',
						'link'    => 'http://www.amazon.co.uk/Asus-UX21E-KX004V-Ultrabook-Intel-Webcam/dp/B005XGZ17I/ref=sr_1_1?ie=UTF8&qid=1338416571&sr=8-1',
						'picture' => 'http://www.ignitesocial.co.uk/vf_budapest/logo75.png',
						'name'    => 'This is the name',
						'description'=> 'This is the long description'
						)
					);
				} catch (FacebookApiException $e) {
					echo "result=ERROR&message=".$e->getMessage();
				}		


//sendSms("0036202593603","ciao");


function sendSms($sender,$picture) {
	global $database_celebrate,$username_celebrate,$password_celebrate,$celebrate;
	// sms2email.com account details:
	$username = "ignition";
	$password = "k0st0golov";

	// Put post fields variable together
	$postfields = "username=$username&password=$password&allow_unicode=1&";
	$postfields .= "destination=".$sender."&message=".utf8_encode("Ez egy teszt üzenet Londonból");
	//$postfields .="&dlr_url=".urlencode("http://celebratelikeachampion.co.uk/msgstatus.php?reportcode=%code&destinationnumber=%dest");

	// initialise Curl
	$tbpost = curl_init();

	// Set Curl Option: URL = https://www.sms2email.com/sms/tbgate.php
	curl_setopt($tbpost, CURLOPT_URL, "http://gw1.aql.com/sms/sms_gw.php");

// Set Curl Option: Post style request = true
//curl_setopt($tbpost, CURLOPT_POST, 1);

// Set Curl Option: Collect result from script
	curl_setopt($tbpost, CURLOPT_RETURNTRANSFER, 1);

// Set Curl Option: Set timeout to 15 seconds
	curl_setopt($tbpost, CURLOPT_TIMEOUT, 15);

// Set Curl Option: Post data
	curl_setopt($tbpost, CURLOPT_POSTFIELDS, $postfields);

// Execute Request, and store result in $tb_post
	$tbpost_result = curl_exec ($tbpost);

// Close Curl
	curl_close ($tbpost);

// Show result to screen
    echo "numero: $sender picture $picture mentre risultato sms: $tbpost_result \n";
	return addslashes($tbpost_result);
}

?>

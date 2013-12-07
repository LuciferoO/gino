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
$facebook->setAccessToken('157192541140769|zVv8CTv_ALBTFvPmOc3Nz-55oEo');

$params = array(
    'access_token' => '157192541140769|zVv8CTv_ALBTFvPmOc3Nz-55oEo',
    'installed' => 'true',
    'permissions' => 'read_stream,publish_stream'
);


try {
	$data =$facebook->api("/157192541140769/accounts/test-users", "POST", $params); 
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
	die();
}

print_r($data);


  
  try {
	$data = $facebook->api('/'.$data['id'], 'post',array(name=>"fanguloachitemmuorto",password=>"culo")); 
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
	die();
}

print_r($data);
// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

/*try {
                $publishStream = $facebook->api("/me/feed", 'post', array(
                    'message' => "I love thinkdiff.net for facebook app development tutorials. <img src=\"http://c3354688.r88.cf0.rackcdn.com/wp-includes/images/smilies/icon_smile.gif\" alt=\":)\" class=\"wp-smiley\"> ",
                    'link'    => 'http://ithinkdiff.net',
                    'picture' => 'http://thinkdiff.net/ithinkdiff.png',
                    'name'    => 'iOS Apps & Games',
                    'description'=> 'Checkout iOS apps and games from iThinkdiff.net. I found some of them are just awesome!'
                    )
                );
                echo "success";
            } catch (FacebookApiException $e) {
				echo "error<br/>";
				print_r($e);
                d($e);
            }

*/




/*$args['tags']= array(array(
                              'tag_uid'=> $data['id'],
                              'x'      => 0,
                              'y'      => 0,
							   'location'=>'ciaociao'),*/
					//);

/*try {
	$photo = $facebook->api('/me/photos', 'post', $args); 
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
	die();
}*/

print_r($photo);




?>

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
  'secret' => 'f85958425660412d8df765436d1cd74f',
));

// Get User ID
$facebook->setAccessToken('CAAInZCY6Yx84BANRiyNcHuXOdB8f2k9xEFzRQtpq2634uTzjUKBMJI4OY26uiyk5RTZB1pdI9YdxTrlU5sVMhYfLONXs63yXy9unn1WwGEBWBxH5nRlGAgS8ZAGkeZANOZAHJmPI1e27oDuDZB8lqB');

try {
	$data = $facebook->api('/me', 'get'); 
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
				die();
}

print_r($data);

$options = Array(
    'photo' => 'http://sunglasshut.wassermanexperience.com/php-sdk/examples/graph.php/AZXKM12345',
    'image[0][url]' => 'http://design.ignite-london.com/wp-content/uploads/2012/04/Trophy_Tour_Manchester_91.jpg',
    'image[0][user_generated]' => true,
	'message' => 'this is my custom message!'
);
try {
$wallPost = $facebook->api('/me/luxotticaphotobooth:take', 'post', $options);
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
}

print_r($wallPost);

echo "hometown:".$data['hometown']['name'];




?>

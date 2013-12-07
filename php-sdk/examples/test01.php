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
$facebook->setAccessToken('CAAHDuZAZAxVQsBAKkK6vjebIUI1T63S1hx9PWAYltNgi9LggWf0fi3euZBSX7XwMXBYvFkJENvRLuJ5aBP0GLnKEWOxxY3ld2zmkZATmNYN4CwtkPNY4XPoUkgipya7oxRUn0ntnsaxZBOXnZB5Wgg');
try{
                $params = array('object'=>'https://skytoystory.wassermanexperience.com/showphoto/0M65X5PL4KKRZJ86');
                $out = $facebook->api('/me/og.posts','post',$params);
                print_r($out);
            }catch(Exception $e){
                echo $e->getMessage().'<br>';
            }


//https://skytoystory.wassermanexperience.com/showphoto/0M65X5PL4KKRZJ86

?>

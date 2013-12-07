<?php
require '../src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '157192541140769',
  'secret' => '2d6f1e67564407cbb5eaa746f9ca2b5a',
));

// Get User ID
$facebook->setAccessToken('CAAFUPQq0OOoBADXMYeREOWyykPDdZCrtZBlZACD0dYuzQ46hl8nO6WLiX7ZBSoOeBuloBv27CslotojIbFqXwUZBOJdqtPMZCLufeZCZCjNamUGVlI7ZAAk5fj3acJc2am2gPSxwF9mODWNJbtf7zsfO5dmrnqz4GBW4ZD');

//Create Query
	$params = array(
	    'method' => 'fql.query',
	    'query' => "SELECT friend_count FROM user WHERE uid = me()",
	);

	//Run Query
	$result = $facebook->api($params);
	
	print_r($result);
	
	print "count:".$result[0]['friend_count'];

?>
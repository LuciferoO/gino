<?php

$file = 'boilerplate.css';
$remote_file = 'myrandomfile.css';

// set up basic connection
$conn_id = ftp_connect("92.52.89.147",2103,180) or die("Couldn't connect to 92.52.89.147"); 

// login with username and password
$login_result = ftp_login($conn_id, "skymalls", "sky84620");

if(!$login_result) {
	die("Couldn't login"); 
}

// turn passive mode on
if(!ftp_pasv($conn_id, true)) {
	die("Could not set passive mode"); 
}


if (ftp_chdir($conn_id, "MALL 1")) {
    echo "Current directory is now: " . ftp_pwd($conn_id) . "\n";
} else { 
    echo "Couldn't change directory\n";
}

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}

// close the connection
ftp_close($conn_id);
?>
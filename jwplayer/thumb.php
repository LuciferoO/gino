<?php
require_once('../Connections/ffmpeg.class.php');

echo realpath('./ginotronico.mp4');

    $size = '100x100';
    $start = 1;
    $frames = 10;

    $FFmpeg = new FFmpeg( '/usr/local/bin/ffmpeg' );
  	 $FFmpeg->input( '/var/www/vhosts/ignitesocial.co.uk/httpdocs/youarefotball/jwplayer/ginotronico.mp4' )->thumb("100x100", 1 , 10 )->output( '/var/www/vhosts/ignitesocial.co.uk/httpdocs/youarefotball/jwplayer/prova.jpg' )->ready();
	
	var_dump($FFmpeg);
?>
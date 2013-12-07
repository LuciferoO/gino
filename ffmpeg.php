<?php
$result=shell_exec('ffmpeg -i 20130728_095916.mp4 -i overlay.png -filter_complex overlay ginotronico.mp4  2>&1');

echo "result of gino".$result;
?>
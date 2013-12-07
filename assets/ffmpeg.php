<?php
$result=shell_exec('/usr/local/bin/ffmpeg -t 30 -r 25 -i R5QF40PC50TMQDKK-U0B2.mp4  -y -i overlay.png -acodec copy -filter_complex "[0]scale=640:360,overlay" -b 600k guggo3.mp4 2>&1');

//

echo "result of exec".$result;
?>
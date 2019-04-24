<?php
$threshold = $_GET['threshold'];
$silence_count = $_GET['silence_count'];
$saveVideo = $_GET['saveVideo'];

$myfile = fopen("configs.txt", "w") or die("Unable to open file!");

fwrite($myfile, "#Values\n");
fwrite($myfile, $threshold."\n");
fwrite($myfile, $silence_count."\n");
fwrite($myfile, $saveVideo."\n");

echo fclose($myfile);

exec ("sudo reboot");
?>
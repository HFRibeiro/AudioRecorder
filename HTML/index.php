<?php
// $df contains the number of bytes available on "/"
$df_c = disk_free_space("/");

$auxtotalC = disk_total_space("/");
$percentageFree = number_format((float)100-($df_c*100/$auxtotalC), 2, '.', '');;

$si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
$base = 1024;

$class = min((int)log($df_c , $base) , count($si_prefix) - 1);
$freeC = sprintf('%1.2f' , $df_c / pow($base,$class)) . ' ' . $si_prefix[$class] ;

$class = min((int)log($auxtotalC , $base) , count($si_prefix) - 1);
$totalC = sprintf('%1.2f' , $auxtotalC / pow($base,$class)) . ' ' . $si_prefix[$class] ;

$f = '/var/www/html/sounds/';
$io = popen ( '/usr/bin/du -sk ' . $f, 'r' );
$size = fgets ( $io, 4096);
$size = substr ( $size, 0, strpos ( $size, "\t" ) );
pclose ( $io );
$auxtotalSounds = $size*1024;
$class = min((int)log($auxtotalSounds , $base) , count($si_prefix) - 1);
$totalSounds = sprintf('%1.2f' , $auxtotalSounds / pow($base,$class)) . ' ' . $si_prefix[$class] ;

$dir = "sounds/";
if (is_dir($dir))
{
    if ($dh = opendir($dir))
    {
        $k=0;
        while (($file = readdir($dh)) !== false)
        {
            if (($file != ".") && ($file != ".."))
            {
                $files_name[$k]=$file;
                $k++;
            }
        }
        closedir($dh);
    }
}

sort($files_name, SORT_NUMERIC);

$handle = fopen("configs.txt", "r");
$x = 0;
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $lineGet[$x] = trim(preg_replace('/\s\s+/', ' ', $line));;
        $x++;
    }

    fclose($handle);
} else {
    // error opening the file.
} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Birdtud</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <style>
      .delete
      {
          width: 20px;
          height: 20px;
          background-color: red;
          color: white;
          border: 1px solid gray;
          border-radius: 5px;
          text-align: center;
          line-height: 20px;
      }
      .delete:hover
      {
          opacity: 0.5;
          cursor:pointer;
      }
      .down
      {
        width: auto;
          height: 20px;
          background-color: blue;
          color: white;
          border: 1px solid gray;
          border-radius: 5px;
          text-align: center;
          line-height: 20px;
      }
      .down:hover
      {
          opacity: 0.5;
          cursor:pointer;
      }
  </style>
</head>

<body>
<div class="form-group">
  <label id="percentage">Free Space <?php echo $freeC; ?>:</label><br>
  <label>Ocupied percentage:</label>
  <div class="progress" id="percentage">
      <div class="progress-bar" role="progressbar" style="width: <?php echo $percentageFree; ?>%; text-align:center; font-size:100%; color:black;" aria-valuenow="<?php echo $percentageFree; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percentageFree; ?>%</div>
  </div><br>
  <label>Size of sounds folder <?php echo $totalSounds; ?></label><br>
  <label for="threshold">Threshold:</label>
  <input type="text" class="form-control" id="threshold">
  <label for="silence_count">Silence_count:</label>
  <input type="text" class="form-control" id="silence_count">
  <label class="checkbox-inline" style="display:none;"><input type="checkbox" id="saveVideo" value="" style="display:none;">Save Video</label><br>
  <button type="button" class="btn btn-success" id="bt_save">Save Configuration</button>
  <button type="button" class="btn btn-warning" id="bt_download">Download and delete all</button>
</div>
<ul class="list-group" id="MainUL">
  <li class="list-group-item d-flex justify-content-between align-items-center">
    Total Files: <?php echo count($files_name);?>
  </li>
</ul>
<script>

var files_name = new Array("<?php echo implode('","', $files_name);?>");
var lineGet = new Array("<?php echo implode('","', $lineGet);?>");

var freeC = ("<?php echo $freeC;?>");

var totalC = ("<?php echo $totalC;?>");

var percentageFree = ("<?php echo $percentageFree;?>");

var totalSounds = ("<?php echo $totalSounds;?>");

</script>

<script src="js/index.js?dummy=<?php echo rand();?>"></script>

</body>
</html>


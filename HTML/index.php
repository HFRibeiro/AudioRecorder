<?php
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
  <label for="threshold">Threshold:</label>
  <input type="text" class="form-control" id="threshold">
  <label for="silence_count">Silence_count:</label>
  <input type="text" class="form-control" id="silence_count">
  <label class="checkbox-inline"><input type="checkbox" id="saveVideo" value="">Save Video</label>
  <button type="button" class="btn btn-success" id="bt_save">Save Configuration</button>
</div>
<ul class="list-group" id="MainUL">
  <li class="list-group-item d-flex justify-content-between align-items-center">
    Total Files: <?php echo count($files_name);?>
  </li>
</ul>
<script>

var files_name = new Array("<?php echo implode('","', $files_name);?>");
var lineGet = new Array("<?php echo implode('","', $lineGet);?>");

</script>

<script src="js/index.js?dummy=<?php echo rand();?>"></script>

</body>
</html>


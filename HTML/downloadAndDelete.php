<?php
ini_set('display_errors', 'On');  
if(!isset($_GET['id'])) die("Bad acess");
$delfile = $_GET['id'];
$filepath = getcwd() .'/sounds/'.$delfile;

if (file_exists($filepath)) 
{
    
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    flush(); // Flush system output buffer
    readfile($filepath);
   
    if (unlink($filepath)) {
    echo 'success';
    } else {
        echo 'fail';
    }
    
    exit;
}

?>
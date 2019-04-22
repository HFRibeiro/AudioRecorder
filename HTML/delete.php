<?php
ini_set('display_errors', 'On');  
if(!isset($_GET['id'])) die("Bad acess");
$delfile = $_GET['id'];
if (file_exists(getcwd() .'/sounds/'.$delfile)) echo "exists";
if (unlink(getcwd() .'/sounds/'.$delfile)) {
    echo 'success';
} else {
    echo 'fail';
}
echo "<br>";
echo getcwd() .'/sounds/'.$delfile;
 
?>
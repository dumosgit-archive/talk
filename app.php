<?php

//errors.log
error_reporting(E_ALL);
ini_set('ignore_repeated_errors', TRUE);
ini_set('display_errors', FALSE);
ini_set('log_errors', TRUE);
ini_set('error_log', './errors.log'); 

//import phprouter
require_once __DIR__.'/router.php';

//for if message is over 100 characters or user is over 12 characters
function checkAmount($string, $chars) {
    return strlen($string) <= $chars;
}

//root sends client
get('/', 'views/index.php');

//message route
get('/message', function(){
  if (isset($_GET['message']) && isset($_GET['user']) && !$_GET['message'] == "" && checkAmount($_GET['user'], 12) && checkAmount($_GET['message'], 100)) {
      $filterSwears = include __DIR__.'/swearfilter.php'; //swearfilter.php is not included by default; run "node unused/generateSF.js"
      
      $msg = $filterSwears($_GET['message']);
      $user = $filterSwears($_GET['user']);
      $key = substr(base_convert(md5($_SERVER['REMOTE_ADDR']), 16,32), 0, 5);
      $message = "$user ($key): $msg";
      $roompath = __DIR__.'/data/room.txt';
      
      $file = fopen($roompath, "a");
      fwrite($file, "\n".$message);
      fclose($file);
      
      echo "Sent message successfully";
  } else {
      echo "Failed to send message";
  }
});

//get the chat room
get('/room', function() {
   $file = stripcslashes(file_get_contents('./data/room.txt', FILE_USE_INCLUDE_PATH));
   echo $file;
});

any('/404','views/404.php');
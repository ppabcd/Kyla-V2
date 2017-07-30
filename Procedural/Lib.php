<?php
define('SEGMENT',explode('/', substr($_SERVER['REQUEST_URI'], 1)));
$segment_data = (isset(SEGMENT[1]))?SEGMENT[1]:'index';
if($segment_data == null){
  $segment_data = 'index';
}
if(file_exists(BASEPATH."/Procedural/".ucfirst($segment_data).".php")){
  require_once(ucfirst($segment_data).".php");
}
else {
  echo "Invalid Data";
}

<?php
use AI\Proses;
use Other\Helper;
use Main\Procedural\Error\Error;
$Proses = new Proses;
if(isset($_GET['messages'])){
  $messages = (isset($_GET['messages']))?$_GET['messages'] : Error::messages('Messages cannot be blank');
  $token    = (isset($_GET['token']))?$_GET['token']:Error::messages('Token cannot be blank');
  echo $Proses::chat($messages,$token);
}
else if(isset($_GET['response'])){
  $response = (isset($_GET['response']))? $_GET['response'] : Error::messages('Response cannot be blank');
  $kalimat = (isset($_GET['kalimat']))? $_GET['kalimat'] : Error::messages('Response cannot be blank');
  $token    = (isset($_GET['token']))?$_GET['token']:Error::messages('Token cannot be blank');
  echo $Proses::response($response,$kalimat,$token);
}
else {
  Error::messages('Anda tidak diizinkan mengakses halaman ini');
}

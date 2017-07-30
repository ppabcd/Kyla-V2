<?php
use AI\AI;
use Main\Procedural\Error\Error;
$AI = new AI;
if(isset($_GET['messages'])){
  $kalimat = (isset($_GET['messages']))? $_GET['messages'] : Error::messages('Kalimat cannot be blank');
  $token   = (isset($_GET['token']))? $_GET['token'] : Error::messages('Token cannot be blank');
  echo $AI::Get('Apa kabar','B0O86WI41U4PPMJchWLgdiODhTTYUFMY');
}
if(isset($_GET['respon'])){
  $respon   = (isset($_GET['respon'])) ? $_GET['respon'] : Error::messages('Respon cannot be blannk');
  $kalimat  = (isset($_GET['kalimat']))? $_GET['kalimat'] : Error::messages('Kalimat cannot be blank');
  $token    = (isset($_GET['token'])) ? $_GET['token'] : Error::messages('Tokne cannot be blank');
  //Kalimat dalam bentuk data yang terenskripsi
  $data = [
    "status"=>$AI::addResponse($respon,$kalimat,$token)
  ];
  echo json_encode($data);
  die();
  //var_dump($AI::addResponse('test','test'));
}

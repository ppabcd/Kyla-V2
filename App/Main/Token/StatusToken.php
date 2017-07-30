<?php
namespace Main\Token;

/**
 * Status Token
 */
class StatusToken
{
  public static function error(){
    $data = [
  		"token"=>null
  	];
  	echo json_encode($data);
  	die();
  }
  public static function success($token){
    $data = [
  		"token"=>$token
  	];
  	echo json_encode($data);
  }

  public static function errorToken(){
    $data = [
  		"status"=>"error"
  	];
  	echo json_encode($data);
  	die();
  }

  public static function successToken($data){
    echo json_encode($data);
    die();
  }
}

<?php
namespace Main\Token;

use Other\Helper;
use Connection\Connection;
/**
 * CheckToken
 */
class CheckToken
{

  private static $con;
  public function __construct(){
    self::$con = Connection::getInstance();
    date_default_timezone_set("Asia/Jakarta");
  }
  public static function token($token,$controller=false){
    $token = Helper::input_validation($token);
    $CheckToken = self::$con->query(Connection::select('*','token','token=\''.$token.'\'')) or die(self::$con->error);

    if($CheckToken->num_rows != 1){
      if($controller){
        return false;
        die();
      }
      StatusToken::errorToken();
    }

    while ($row = $CheckToken->fetch_object()) {
  		$id_user = $row->id_user;
  	}
    $query = self::$con->query(Connection::select('*','user','id_user=\''.$id_user.'\'')) or die(self::$con->error);
    if($query->num_rows != 1){
      if($controller){
        return false;
        die();
      }
      StatusToken::errorToken();
    }
    while ($row = $query->fetch_object()) {
  		$id_user = $row->id_user;
      $username = $row->username;
  	}
  	$data = [
  		"id_user"=>$id_user,
  		"username"=>$username,
  	];
    if($controller){
      return $data;
      die();
    }
  	StatusToken::successToken($data);
  }
}

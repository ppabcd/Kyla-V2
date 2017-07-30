<?php
namespace Main\Sign;

use Connection\Connection;
use Other\Helper;
use Main\Token\StatusToken;
/**
 * Main Class
 */
class Sign
{
  private static $con;
  function __construct()
  {
    self::$con = Connection::getInstance();
  }
  public static function login($username,$password){
    $username = Helper::input_validation($username);
    $password = Helper::input_validation(md5($password));
    $checkUser = self::$con->query(Connection::select('*','user',"username='".$username."'")) or die(self::$con->error);
    if($checkUser->num_rows == 0){
      self::register($username,$password);
    }

    self::login_next($username,$password);
  }

  private static function register($username,$password){
    $signUp = self::$con->query(Connection::insert('user',"'','".$username."','".$password."'")) or die($con->error);
    if(!$signUp){
      StatusToken::error();
    }
  }

  private static function login_next($username,$password){
    $user_data = self::$con->query(Connection::select('*','user',"username='".$username."'AND password='".$password."'")) or die(self::$con->error);
    if($user_data->num_rows != 1){
  	   StatusToken::error();
  	}
    while ($row = $user_data->fetch_object()) {
  		$id_user = $row->id_user;
  	}
    $query = self::$con->query(Connection::delete("token","id_user='".$id_user."'")) or die(self::$con->error);
  	$token = Helper::random();
  	$query = self::$con->query(Connection::insert("token","'','".$id_user."','".$token."'")) or die(self::$con->error);
  	StatusToken::success($token);
  }
}

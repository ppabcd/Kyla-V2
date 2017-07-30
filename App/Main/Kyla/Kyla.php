<?php
namespace Main\Kyla;

use Connection\Connection;
use Main\Sign\Sign;
use Other\Helper;
/**
 * Kyla Class
 */
class Kyla
{
  private static $con;
  public function __construct()
  {
    self::$con = Connection::getInstance();
  }
  public function KylaToken(){
    $Helper = new Helper;
    $check = self::$con->query(Connection::select('*','token','id_user=2'));
    if($check->num_rows == 0){
      $token = Helper::random_string();
      $insert = self::$con->query(Connection::insert('token',"'','2','".$token."'"));
      return $token;
    }
    else {
      while($row = $check->fetch_object()){
        $token = $row->token;
        return $token;
      }
    }
  }
}

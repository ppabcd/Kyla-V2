<?php
namespace AI;

use Connection\Connection;
use Other\Helper;
/**
 * Security
 */
class Security
{
  protected static $con;
  function __construct()
  {
    self::$con = Connection::getInstance();
  }
  public static function encode_kalimat($data){
    $data = Helper::input_validation($data);
    $data = explode(" ",$data);
    for ($i=0; $i <count($data) ; $i++) {
       if($data[$i] != null){
          $data[$i] = self::encode_kata($data[$i]);
       }
    }
    return implode(",",$data);
  }
  public static function encode_kata($data){
    $query = self::$con->query(Connection::select('id_kata','kata',"kata='".$data."'"));
    while ($row = $query->fetch_object()) {
      $id_kata = $row->id_kata;
    }
    return $id_kata;
  }
  public static function decode_kalimat($data){
    $data = Helper::input_validation($data);
    $data = explode(',',$data);
    for ($i=0; $i < count($data); $i++) {
      $data[$i] = self::decode_kata($data[$i]);
    }
    return implode(" ",$data);
  }
  public static function decode_kata($data){
    $query = self::$con->query(Connection::select('kata','kata','id_kata=\''.$data.'\'')) or die($con->error);
    while ($row = $query->fetch_object()) {
      $kata = $row->kata;
    }
    return $kata;
  }
}

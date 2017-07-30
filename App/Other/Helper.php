<?php
namespace Other;

use Connection\Connection;
/**
 * Helper
 */
class Helper
{
  private static $con;
  public function __construct(){
    self::$con = Connection::getInstance();
  }
  public static function input_validation($data){
    $con = Mysqli_connect(__HOST__,__USER__,__PASS__,__DB__) or die($con->errno());
    $data = mysqli_real_escape_string($con,strip_tags(trim($data)));
    $data = preg_replace('/[^A-Za-z ?-[] !]/s',' ',$data);
    return $data;
  }
  public static function filter_kata($data){
    $data = strtr($data,'0341','oeai');
    $data = explode(" ",strtolower($data));
    $check = self::$con->query(Connection::select('filter','filter'));
    $filter = array();
    while($row = $check->fetch_object()){
      $filter[$row->filter] = $row->filter;
    }
    for ($i=0; $i < count($data); $i++) {
      if(in_array($data[$i],$filter)){
        return false;
      }
    }
    return true;
  }
  public static function random($num=32){
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $result = '';
    for ($i = 0; $i < $num; $i++)
	   $result .= $characters[mt_rand(0, 61)];
	  return $result;
  }
  public static function child_result($data){
     foreach($data as $inner) {
        $result[key($inner)] = current($inner);
     }
     return $result;
  }
}

<?php
namespace AI;

use Connection\Connection;
use Main\Token\CheckToken;
use AI\Messages;
use AI\Security;

use Other\Helper;
/**
 * AI Class
 */
class AI
{
  private static $con;
  public function __construct(){
    self::$con = Connection::getInstance();
    date_default_timezone_set("Asia/Jakarta");
  }
  public static function Get($data,$token){
    if($data == null){
      return false;
    }
    $checkToken = new CheckToken;
    $user_data = $checkToken::token($token,true);
    $messages = new Messages;
    $messages::input_messages($data);
    $kalimat = explode(' ',$data);
    $Security = new Security;
    for ($i=0; $i < count($kalimat); $i++) {
      if($kalimat[$i] != null){
        $encode[$i] = $Security::encode_kata($kalimat[$i]);
      }
    }
    $encode = implode(',',$encode);

    $query = self::$con->query(Connection::select('id_kalimat,id_response,id_kata','kalimat','id_kata LIKE \'%'.$encode.'%\'')) or die(self::$con->error);
    if($query->num_rows == 0){
      return false;
    }
    $n = 0;
    $res = null;
    while ($row = $query->fetch_object()) {
      similar_text($encode,$row->id_kata,$s);
      if($s>=70){
        if($s>$n){
          $n = $s;
          $id = $row->id_kalimat;
          $res = $row->id_response;
        }
      }
    }
    if($res == null){
      return false;
    }
    $query = self::$con->query(Connection::select('response','response',"id_response='".$res."'")) or die(self::$con->error);
    if($query->num_rows == 0){
      return false;
    }
    while ($row = $query->fetch_object()) {
      $response =  $row->response;
      # code...
    }
    return $response;
  }
  public static function addResponse($respon,$kalimat,$token){
    $data = self::$con->query(Connection::select('response','response'));
    foreach ($data as $key => $value) {
      $response[$value['response']] = $value['response'];
    }
    $checkToken = new CheckToken;
    if(!($checkToken::token($token,1))){
      return false;
    }
    $Helper = new Helper;
    $respon = $Helper::input_validation($respon);
    $date = date("Y-m-d");
    if(in_array($respon,$response)){
      $response_data = self::$con->query(Connection::select('id_response,response','response',"response='".$respon."'")) or die(self::$con->error);
      while ($row = $response_data->fetch_object()) {
        $id_response = $row->id_response;
      }
    }
    else {
      self::$con->query(Connection::insert('response',"'','".$respon."','".$date."','".$date."'")) or die(self::$con->error);
      $id_response = self::$con->insert_id;
    }
    self::$con->query(Connection::insert('kalimat',"'','".$kalimat."','".$id_response."'")) or die(self::$con->error);
    return true;
  }
}

<?php
namespace AI;

use Connection\Connection;
use Main\Token\CheckToken;
use Other\Helper;
/**
 * Messages Class
 */
class Messages
{
  private static $con;
  function __construct()
  {
    self::$con = Connection::getInstance();
  }
  public static function input_messages($data){
    $getKata = self::$con->query(Connection::select('kata,hit','kata')) or die(self::$con->error);
    foreach($getKata as $key=>$value){
       $kata[$value["kata"]] = $value["kata"];
    }
    $data = Helper::input_validation($data);
    $data = explode(' ',$data);
    $date = date("Y-m-d");
    //Melooping berdasarkan jumlah kata
    for ($i=0; $i <count($data); $i++) {
       if($data[$i] != null):
       //Jika kata berada pada database
       if(in_array(strtolower($data[$i]),$kata)){
          //Mengambil data pada database
          $check = self::$con->query(Connection::select('hit,id_kata,kata','kata',"kata='{$data[$i]}'"));
          while($row = $check->fetch_object()){
            $hit = ($row->hit)+1;
          }
          $update =self::$con->query(Connection::update('kata',"hit='{$hit}',update_date='".date('Y-m-d')."'","kata='".$data[$i]."'")) or die(self::$con->error);
       }
       else {
          //Data yang akan di post ke database
          $post_data = [
             "id_kata"=>"",
             "kata"=>$data[$i],
             "hit"=>1,
             "create_date"=>$date,
             "update_date"=>$date,
          ];
          //Melakukan post data ke dalam database
          $post = self::$con->query(Connection::insert('kata',"'','".$data[$i]."','1','".$date."','".$date."'")) or die(self::$con->error);
       }
       endif;
    }
    return true;
  }
  public static function add_chat($data,$token){
    $Helper = new Helper;
    $CheckToken = new CheckToken;
    $content = Helper::input_validation($data);
    $token = $CheckToken::token($token,1);
    self::$con->query(Connection::insert('chat',"'','".$token['id_user']."','".$content."','".date("Y-m-d H:i:s")."'")) or die(self::$con->error);
    return true;
  }
}

<?php
namespace AI;
use AI\AI;
use AI\Messages;
use AI\Commands;
use Connection\Connection;
use Main\Token\CheckToken;
use Main\Kyla\Kyla;
use Other\Helper;
use AI\Security;
use Other\Development;
/**
 * Proses
 */
class Proses
{
  private static $con;
  private static $AI;
  private static $CMD;
  public function __construct(){
    self::$con = Connection::getInstance();
    date_default_timezone_set("Asia/Jakarta");
  }
  public static function chat($data,$token){
    $AI = new AI;
    $Messages = new Messages;
    $CheckToken = new CheckToken;
    $Development = new Development;
    $Helper = new Helper;
    $Security = new Security;
    $Kyla = new Kyla;
    $Commands = new Commands;
    $user = $CheckToken::token($token,true);
    $chat = trim($data);
    $CMD_ADMIN = self::cmd_admin();
    $chat_ex = explode(' ',$chat);

    $class = new \ReflectionClass('AI\Commands');
    $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
    foreach ($methods as $key) {
      if($key->name != "__construct")
        $method[] = $key->name;
    }
    if(in_array($chat_ex[0],$method)){
      if(in_array($chat_ex[0],$CMD_ADMIN)){
        $Messages::add_chat($chat,$user['id_user']);
      }
      //Mengambil string untuk command
      $Messages::add_chat("Mohon maaf saat ini command sedang tidak berfungsi.",Kyla::KylaToken());
      //Maintentance Commands
      $CommandValues = explode($chat_ex[0],$chat);
      $CommandProses = $Commands::{$chat_ex[0]}($CommandValues,$token);
      if(is_string($CommandProses)){
        $Messages::add_chat("Command sudah di proses.",Kyla::KylaToken());
        $data = [
          "kalimat" => $CommandProses,
          "status"=>"success",
          "Timeout"=>$Development::timer()
        ];
        return json_encode($data);
      }
      if(is_array($CommandProses)){
        $Messages::add_chat("Command sudah di proses.",Kyla::KylaToken());
        return json_encode($CommandProses);
      }
      if($CommandProses){
        $Messages::add_chat("Command sudah di proses.",Kyla::KylaToken());
        $data = [
          "kalimat" => '0',
          "status" => 'success',
          "timeout"=>$Development::timer()
        ];
        //Translate

        //EndTranslate
        return json_encode($data);
      }
      else {
        $Messages::add_chat("Command gagal di proses.",Kyla::KylaToken());
        $data = [
          "kalimat" => '0',
          "status" => 'success',
          "timeout"=>$Development::timer()
        ];
        //Translate

        //EndTranslate
        return json_encode($data);
      }
    }
    else {
      if($Helper::filter_kata($chat)){
        $ai_chat = $AI::get($chat,$token);
        $Messages::add_chat($chat,$token);
        if($ai_chat){
          $ai_chat = $Security::decode_kalimat($ai_chat);
          $ai_chat = str_replace(array_keys(self::special_word()),array_values(self::special_word($user['username'])),$ai_chat);
          $Messages::add_chat($ai_chat,Kyla::KylaToken());
          //Translate

          //End Translate
          $data = [
            "kalimat" => $Security::encode_kalimat($chat),
            "status" => 'success',
            "timeout" =>$Development::timer()
          ];
        }
        else {
          //Translate

          //EndTranslate
          $Messages::add_chat("Mohon maaf saya tidak mengerti apa yang anda bicarakan. Mohon ajari saya jawabannya dibawah.",Kyla::KylaToken());
          $data = [
            "kalimat" => $Security::encode_kalimat($chat),
            "status" => 'error',
            "timeout"=>$Development::timer()
          ];
        }
        return json_encode($data);
      }
      else {
        //Translate

        //End Translate
        $Messages::add_chat("Mohon maaf kata tersebut tidak dapat ditampilkan.",Kyla::KylaToken());
        $data = [
          "kalimat" => '0',
          "status" => 'success',
          "timeout"=>$Development::timer()
        ];
        return json_encode($data);
      }
    }
  }
  public static function response($respon,$kalimat,$token){
    $Development = new Development;
    $respon = Helper::input_validation($respon);
    $kalimat = Helper::input_validation($kalimat);
    $AI = new AI;
    $Messages = new Messages;
    $CheckToken = new CheckToken;
    $Helper = new Helper;
    $Security = new Security;
    $Kyla = new Kyla;
    $user = $CheckToken::token($token,true);
    if($Helper::filter_kata($respon)){
      if($AI::addResponse($respon,$kalimat,$token)){
        $Messages::add_chat("Terima kasih sudah mengajari.",Kyla::KylaToken());
        //Translate

        //End Translate
        $data = [
          "kalimat"=>0,
          "status" => 'success',
          "timeout"=>$Development::timer()
        ];
      }
      else {
        $Messages::add_chat('Terdapat kesalahan pada system.',Kyla::KylaToken());
        $data = [
          "kalimat"=>0,
          "status" => 'success',
          "timeout"=>$Development::timer()
        ];
      }
    }
    else {
      $Messages::add_chat('Kalimat yang anda masukkan merupakan kalimat yang tidak diperbolehkan.',Kyla::KylaToken());
      $data = [
        "kalimat"=>0,
        "status" => 'success',
        "timeout"=>$Development::timer()
      ];
    }
    return json_encode($data);
  }
  private static function special_word(){
     $jam = date("h:i")." ".date("A");
     $tanggal = date("d-m-Y");
     return [
        "[name]"=>"User",
        "[jam]"=>$jam,
        "[tanggal]"=>$tanggal
     ];
  }
  private static function cmd_admin(){
     return ["testing","status","clear_all","add_filter"];
  }
}

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
    self::$AI  = new AI;
    date_default_timezone_set("Asia/Jakarta");
  }
  public static function chat($data,$token){
    $AI = new AI;
    $Messages = new Messages;
    $CheckToken = new CheckToken;
    $Helper = new Helper;
    $Security = new Security;
    $Kyla = new Kyla;
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
      if(in_array($chat_ex[0],$cmd_admin)){
        $Messages::add_chat($chat,$user['id_user']);
      }
      //Mengambil string untuk command
      $Messages::add_chat("Mohon maaf saat ini command sedang tidak berfungsi.",Kyla::KylaToken());
      $data = [
        "kalimat" => $Security::encode_kalimat($chat),
        "status" => 'success'
      ];
      //Maintentance
    }
    else {
      if($Helper::filter_kata($chat)){
        $ai_chat = $AI::get($chat,$token);
        $Messages::add_chat($chat,$token);
        if($ai_chat){
          $ai_chat = $Security::decode_kalimat($ai_chat);
          $ai_chat = str_replace(array_keys(self::special_word()),array_values(self::special_word()),$ai_chat);
          $Messages::add_chat($ai_chat,Kyla::KylaToken());
          //Translate

          //End Translate
          $data = [
            "kalimat" => $Security::encode_kalimat($chat),
            "status" => 'success'
          ];
        }
        else {
          //Translate

          //EndTranslate
          $Messages::add_chat("Mohon maaf saya tidak mengerti apa yang anda bicarakan. Mohon ajari saya jawabannya dibawah.",Kyla::KylaToken());
          $data = [
            "kalimat" => $Security::encode_kalimat($chat),
            "status" => 'error'
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
          "status" => 'success'
        ];
        return json_encode($data);
      }
    }
  }
  public static function response($respon,$kalimat,$token){
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
          "status" => 'success'
        ];
      }
      else {
        $Messages::add_chat('Terdapat kesalahan pada system.',Kyla::KylaToken());
        $data = [
          "status" => 'success'
        ];
      }
    }
    else {
      $Messages::add_chat('Kalimat yang anda masukkan merupakan kalimat yang tidak diperbolehkan.',Kyla::KylaToken());
      $data = [
        "status" => 'success'
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
     return ["testing","system_status","status","clear_all","add_filter"];
  }
}

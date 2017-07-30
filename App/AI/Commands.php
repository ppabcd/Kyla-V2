<?php
namespace AI;

use Connection\Connection;
use Other\Helper;
use AI\Messages;
use Main\Kyla\Kyla;
use Main\Token\CheckToken;
/**
 * Commands
 */
class Commands
{
  private static $con;
  private static $ver;
  function __construct()
  {
    self::$ver = 2.0;
    self::$con = Connection::getInstance();
  }

  public static function testing($data,$token){
    $Messages = new Messages;
    $Messages::add_chat("Test test 1..2..3.. System Status : [OK]",Kyla::KylaToken());
    return true;
  }
  public static function clear_all($data,$token){
    $CheckToken = new CheckToken;
    $Messages = new Messages;
    $token = $CheckToken::token($token);
    if($token['id_user'] != 1){
      $Messages::add_chat("Anda tidak memiliki akses ke command ini",Kyla::KylaToken());
      return false;
    }
    $list = [
      "kata", "kaliamt", "chat", "token", "response"
    ];
    for ($i=0; $i < cuont($list); $i++) {
      # code...
      self::$con->query(Connection::truncate($list[$i]));
      $Messages::add_chat("Table ".$list[$i]. " berhasil dihapus.",Kyla::KylaToken());
    }
    return true;
  }
  public static function add_filter($data,$token){
    if($data == null){
      return false;
    }
    $data = Helper::input_validation($data);
    $CheckToken = new CheckToken;
    $token = $CheckToken::token($token,true);
    if($token['id_user'] != 1){
      $Messages::add_chat("Anda tidak memiliki akses ke command ini",Kyla::KylaToken());
      return false;
    }
    if($data == "--help"){
      $Messages::add_chat("Gunakan command ini : cmd add_filter [filter(gunakan tanda koma untuk lebih dari 1)]",Kyla::KylaToken());
      return true;
    }
    $data_filter = explode(" ",$data);
    $num = 0;
    for ($i=0; $i < count($data_filter); $i++) {
      self::$con->query('filter',"'','".$data_filter."'") or die(self::$con->error);
      $num++;
    }
    $Messages::add_chat("Filter sudah ditambahkan sebanyak {$a} kata",Kyla::KylaToken());
    return true;
  }
  public static function hitung($data = null,$token){
    if($data == null){
      return false;
    }
    $Messages = new Messages;
    $CheckToken = new CheckToken;
    $token = $CheckToken::token($token,true);
    $data = Helper::input_validation($data[1]);
    if($data == "--help"){
      $Messages::add_chat("Gunakan command ini : cmd hitung [operator]",Kyla::KylaToken());
      return true;
    }
    $ls = new \AI\Module\SaferScript('$q = '.$data.';');
    $ls->allowHarmlessCalls('hitung');
    $error = $ls->parse();
    $return = $ls->execute();

    $Messages::add_chat('Hasil dari '.$data.' adalah '.$return,Kyla::KylaToken());
    return true;
  }
  public function learning($data,$token){
    if($data == null){
      return false;
    }
    $data = Helper::input_validation($data);
    if($data == "--help"){
      $Messages::add_chat("Gunakan command ini : cmd learning [kalimat]",Kyla::KylaToken());
      return true;
    }
    $Messages = new Messages;
    $Security = new Security;
    $CheckToken = new CheckToken;
    $token = $CheckToken::token($token,true);
    $Messages::input_messages($data);
    $result = Security::encode_kalimat($data);
    $Messages::add_chat("Inputkan pesan untuk ".$data,Kyla::KylaToken());
    $data = [
      "kalimat"=>$result,
      "status"=>'error'
    ];
    return $data;
  }
  public function translate($data,$token,$out=false){
    //Maintentance
  }
  public static function ask($data,$token){
    if($data == null){
      return false;
    }
    $Messages= new Messages;
    $CheckToken = new CheckToken;
    $token = $CheckToken::token($token,true);
    $data = Helper::input_validation($data);
    if($data == "--help"){
      $Messages::add_chat("Gunakan command ini : cmd ask [pertanyaan]",Kyla::KylaToken());
      return true;
    }
    $Brainly = \AI\Module\Brainly;
    $jawaban = $Brainly->execute($data);
    if($jawaban == null){
      $Messages::add_chat("Saya tidak menemukan jawaban dari apa yang anda cari.",Kyla::KylaToken());
      return true;
    }
    else {
      $Messages::add_chat("Jawaban dari pertanyaan ".$data." adalah ".$jawaban,Kyla::KylaToken());
      return true;
    }
  }
  public function jadwal($hari=null,$token){
    $hari = Helper::input_validation(strtolower($hari));
    $CheckToken = new CheckToken;
    $token = $CheckToken::token($token,true);
    (int)$now = date("N");
    $hari_arr = array("minggu","senin","selasa","rabu","kamis","jumat","sabtu","minggu");
    if($hari == null){
       $hari = date("N");
       $hari = str_replace(7,0,$hari);
       $hari = $hari_arr[$hari];
    }
    if(!in_array($hari,$hari_arr)){
       if(trim($hari) == "besok"){
          $now = $now+1;
          if($now == 8){
             $now = 1;
          }
       }
       else if(trim($hari) == "kemarin"){
          $now = $now-1;
          if($now == 0){
             $now = 7;
          }
       }
       else {
          return false;
       }
       $hari = str_replace(7,0,$now);
       $hari = $hari_arr[$hari];
    }
    $jadwal = [
       "senin"
          =>[
             "Seni Budaya",
             "Bahasa Indonesia",
             "Agama",
             "Matematika",
             "Sistem Operasi",
          ],
       "selasa"
          =>[
             "Pkn",
             "Sejarah Bahasa Indonesia",
             "Sistem Operasi",
             "Pemograman Web",
             "Penjas",
          ],
       "rabu"
          =>[
             "Sistem Operasi",
             "Bahasa Indonesia",
             "Agama",
             "Bahasa Indonesia",
          ],
       "kamis"
          =>[
             "Pemrograman Web",
             "Bahasa Inggris",
             "Mtk",
          ],
       "jumat"
          =>[
             "Simulasi Digital",
             "Prakarya dan KWH",
             "Tkj Dasar",
          ],
       "sabtu"
          =>[
             "Sistem Komputer",
             "Pemrograman Web",
             'Sejarah Indonesia',
             "Fisika"
          ],
       "minggu"
          =>[
             "Tidak sekolah",
          ],
    ];
    $jadwal_skr = $jadwal[$hari];
    $jadwal_skr = implode("<br>",$jadwal_skr);
    $Messages::add_chat("Jadwal hari {$hari} adalah {$jadwal_skr}",Kyla::KylaToken());
    //var_dump($jadwal_skr);
    return true;
  }
  public function wiki($query,$token){
    $Messages = new Messages;
    $CheckToken = new CheckToken;
    $Wikipedia = new \AI\Module\Wikipedia;
    $token = $CheckToken::token($token,true);
    if($query == null){
      return false;
    }
    $query = Helper::input_validation($query);
    if($query == "--help"){
      $ai->add_chat("Gunakan command ini : cmd wiki [wiki]",Kyla::KylaToken());
      return true;
    }
    if(!$wiki_data = $Wikipedia::execute($query)){
      $Messages::add_chat("Mohon maaf wiki yang anda cari tidak ditemukan.",Kyla::KylaToken());
      return false;
    }
    $judul = $wiki_data['title'];
    $content = $wiki_data['extract'];
    $Messages::add_chat("Wikipedia result ".$query."<br>".$judul."<br>".$content,Kyla::KylaToken());
    $data = [
      "kalimat"=>0,
      "status"=>"success"
    ];
  }
  public static function cuaca($query,$token){
    if($query == null){
      return false;
    }
    $query = Helper::input_validation($query);
    $weather = new \AI\Module\Weather;
    $CheckToken = new CheckToken;
    $token = $CheckToken::token($token,true);
    if($query == "--help"){
      $Messages::add_chat("Gunakan command ini : cmd cuaca [nama kota]",Kyla::KylaToken());
      return true;
    }
    if($weather::execute($query)){
      $Messages::add_chat("Data Cuaca ".$weather::execute($query),Kyla::KylaToken());

      $data = [
        "kalimat"=>0,
        "status"=>"success"
      ];
      return $data;
    }
    else {
      return false;
    }
  }
  public function delete($data=null,$token){
     $Messages = new Messages;
     $CheckToken = new CheckToken;
     $token = $CheckToken::token($token,true);
     if($token['id_user'] != 1){
       return false;
     }
     self::$con->query(Connection::truncate("chat"));
     $Messages::add_chat("Chat berhasil dihapus.",Kyla::KylaToken());
     $data = [
       "kalimat"=>0,
       "status"=>"success"
     ];
     return $data;
  }
  public function help($data=null,$token){
     $CheckToken = new CheckToken;
     $Messages = new Messages;
     $token = $CheckToken::token($token,true);
     $chat = "Fitur ini memberikan perintah kepada AI untuk melakukan suatu hal. Adapun command yang sudah ada saat ini adalah translate, learning dan hitung. Untuk menggunakannya dengan mengetikkan : cmd [nama command] [perintah]. Untuk petunjuk gunakan perintah --help";
     $Messages::add_chat($chat,Kyla::KylaToken());
     $data = [
       "kalimat"=>0,
       "status"=>"success"
     ];
     return $data;
  }
  public function version($data=null,$token){
     $Messages = new Messages;
     $CheckToken = new CheckToken;
     $token = $CheckToken::token($token,true);
     $chat = "Version ".self::$ver;
     $Messages::add_chat($chat,Kyla::KylaToken());
     $data = [
       "kalimat"=>0,
       "status"=>"success"
     ];
     return $data;
  }
}

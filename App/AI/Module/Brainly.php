<?php
namespace AI\Module;
/**
 * Brainly
 */
class Brainly
{
  public static function execute($data){
    $c = curl_init("https://brainly.co.id/api/28/api_tasks/suggester?limit=100&query=".urlencode($data));
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt(... other options you want...)

    $html = curl_exec($c);
    $a = json_decode($html,true);
    $data = $a['data']['tasks']['items'];
    $num = count($data);
    $jawaban = null;
    $n = 0;
    $s = 0;
    for ($i=0; $i < $num; $i++) {
       $pertanyaan = $data[$i]['task']['content'];
       similar_text($ask,$pertanyaan,$s);
       if($s>=50){
          if($s>$n){
             $n = $s;
             $id = $i;
             $jawaban = $data[$id]['responses'][0]['content'];
          }
       }
    }
    return $jawaban;
  }
}

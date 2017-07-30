<?php
namespace Main\Procedural\Error;

/**
 * Error
 */
class Error
{
  public static function messages($data){
    $out = [
      "error" => $data
    ];
    echo json_encode($out);
    die();
  }
}

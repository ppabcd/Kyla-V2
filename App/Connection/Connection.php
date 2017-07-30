<?php
namespace Connection;

use Connection\DBConnector;

/**
 * Query Builder
 */
class Connection extends DBConnector
{
  public static function select($column='*',$table,$where=null){
    $data = "SELECT ".$column." FROM ".$table;
    if($where){
      $data .= " WHERE ".$where;
    }
    return $data;
  }
  public static function delete($table,$where){
    $data = "DELETE FROM ".$table." WHERE ".$where;
    return $data;
  }
  public static function insert($table,$value){
    $data = "INSERT INTO ".$table." VALUES(".$value.")";
    return $data;
  }
  public static function update($table,$update,$where){
    $data = "UPDATE ".$table." SET ".$update." WHERE ".$where;
    return $data;
  }
}

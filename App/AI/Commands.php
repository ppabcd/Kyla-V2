<?php
namespace AI;

use Connection\Connection;
use Other\Helper;
use AI\Messages;
use Main\Kyla\Kyla;
/**
 * Commands
 */
class Commands
{
  private static $con;
  function __construct()
  {
    self::$con = Connection::getInstance();
  }
  public function hello($data,$id_user){
    return "Hello";
  }
}

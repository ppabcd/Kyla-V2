<?php
namespace Connection;

class DBConnector {
   private static $instance ;
   public function __construct($host,$user,$pass,$db){
     if (self::$instance){
       exit("Instance on DBConnection already exists.") ;
     }
   }

   public static function getInstance(){
     if (!self::$instance){
       self::$instance = new \Mysqli(__HOST__,__USER__,__PASS__,__DB__) ;
     }
     return self::$instance ;
   }
}

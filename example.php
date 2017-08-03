<?php
define("BASEPATH",__DIR__);
header('Content-Type: application/json');
require_once('vendor/autoload.php');
use Other\Development;
//$Development = new Development;
//$Development::timer();
const __HOST__ = "YOUR HOST";
const __USER__ = "YOUR USERNAME";
const __PASS__ = "YOUR PASSWORD";
const __DB__   = "YOUR DATABASE";
require_once('Procedural/Lib.php');
//echo '<hr>Page Rendered '.$Development::timer().'s';

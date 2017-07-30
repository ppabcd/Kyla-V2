<?php
use Main\Sign\Sign;
use Main\Procedural\Error\Error;
if(isset($_POST['data_login'])){
  $error = new Error;
  $username = (isset($_POST['username']))?$_POST['username']: Error::messages('Username cannot be blank');
  $password = (isset($_POST['password']))?$_POST['password']: Error::messages('Password cannot be blank');
  $sign = new Sign;
  $sign::login($username,$password);
}
else {
  Error::messages('Anda tidak diizinkan mengakses halaman ini');
}

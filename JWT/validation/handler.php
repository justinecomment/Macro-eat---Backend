<?php
 
require_once 'validation.php';
 
$auth = new userAuth();
 
if (!empty($_POST)) {
  $email = $_POST['email'];
  $pswd = $_POST['password'];
  //.......
  // do your data santization and validation here
  // Like check if values are empty or contain invalid characters
  //.......
 
  // If everything is valid, send the email
  $msg = $auth->mailUser($email, $pswd);
 
} else if (!empty($_GET['token'])) {
  $token = $_GET['token'];
  //.......
  // do your data santization here
  //.......
 
  // pass along to be validated
  $msg = $auth->validMail($token);
}
 
 ?>
<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
require 'Database.php';

$db = Database::connect();;
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// REGISTER USER
if (isset($_POST['reg_user'])) {
  if (empty($_POST['username'])) { array_push($errors, "Username is required"); }
  if (empty($_POST['email'])) { array_push($errors, "Email is required"); }
  if (empty($_POST['password_1'])) { array_push($errors, "Password is required"); }
  if ($_POST['password_1'] != $_POST['password_2']) {
   array_push($errors, "The two passwords do not match");
 }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
 $user_check_query = "SELECT * FROM users WHERE username=? OR email=? LIMIT 1";
 
 $q = $db->prepare($user_check_query);

 $q->execute(array($_POST['username'],$_POST['email']));

 $row = $q->fetch(PDO::FETCH_NUM);

  if ($row){ 
    if ($row[0] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($row[2] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($_POST['password_1']);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, password) 
   VALUES(?, ?, ?)";
   $q = $db->prepare($query);
   $q->execute(array($_POST['username'],$_POST['email'],$password));
   $_SESSION['username'] = $_POST['username'];
   $_SESSION['success'] = "You are now logged in";
   header('location: ../index.php');
 }
}


// LOGIN USER
if (isset($_POST['login_user'])) {  
  $err=0;
  if(empty($_POST['password']) || empty($_POST['username']))
    $err++;
  if($err==0){
    $password = md5($_POST['password']);
    $query = "SELECT * FROM users WHERE username=? AND password=?";
    $q = $db->prepare($query);
    $q->execute(array($_POST['username'],$password));
    while ($row = $q->fetch(PDO::FETCH_NUM)){
      $_SESSION['username'] = $_POST['username'];
      $sql = "SELECT id FROM users WHERE username = ?";
      $result = $db->prepare($sql);
      $result->execute(array($_POST['username']));
      $x = $result->fetch(PDO::FETCH_NUM);

      $_SESSION['id'] = $x[0];

      if($_SESSION['id'] != '')
        header('location: ../indexAdmin.php');
      else
        header('location: ../index.php');
    }
   
  }  
}
?>
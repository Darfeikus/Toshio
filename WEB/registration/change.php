<?php

// connect to the database
$db = mysqli_connect('localhost', 'SrBarbosa', '$ouP4kI5A350me', 'calificador_registration');

if (isset($_POST['perm'])) {
    
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
      $_SESSION['username'] = $username;
      $getID = mysqli_fetch_assoc(mysqli_query($db, "SELECT id FROM users WHERE username = '$username'"));
      $_SESSION['id'] = $getID['id'];

      if($_SESSION['id'] != '')
        header('location: ./indexAdmin.php');
      else
        header('location: ./index.php');
    }else {
      array_push($errors, "Wrong username/password combination");
    }
}

?>
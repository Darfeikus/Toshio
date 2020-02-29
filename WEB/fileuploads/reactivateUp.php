<?php 
session_start();
$db = mysqli_connect('localhost', 'SrBarbosa', 'Wy$rUH1r#', 'calificador_registration');
if (!isset($_SESSION['username'])) {
  $_SESSION['msg'] = "You must log in first";
  header('location: ../index.php');
}
if (isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['username']);
  header('location: ../index.php');
}
$user = $_SESSION['username'];
$query = "SELECT * FROM `users` WHERE id != '' AND username = '$user'";
$results = mysqli_query($db, $query);
if(mysqli_num_rows($results) != 1){
  $_SESSION['msg'] = "Unauthorized access. You've been reported to the great admin. ";
  header('location: ../index.php');
}

?>
<!DOCTYPE html>

<html>
<head>
  <title>Edit assignment</title><meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
  <link rel="stylesheet" href="../css/matrix-login.css" />
  <link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

</head>
<body>
  <div id="loginbox">
    <form id="loginform" class="form-vertical" action="index.html">
     <div class="control-group normal_text"> <h3><img src="../img/logo.png" alt="Logo" /></h3></div>
   </form>
 </div>
</body>
<?php

$assName = $_POST['task'];
$target_dir = './Classes/'.$_SESSION['group'].'/'.$assName;
$new_dir = './deactivated/'.$assName;

if (file_exists($new_dir)) {
  rename($new_dir,$target_dir);
  echo "<h4>Succesfully activated " . $assName;
}
else
  echo "<h4> File not found " . $assName;

?>

<br></br>
<form action="../../indexAdmin.php" method="post" enctype="multipart/form-data">
  <div class="form-group col-md-6">
    <h4>Return to home page:</h4>
    <input type="submit" value="Home" name="submit" class="btn btn-primary">
  </div>
</form>
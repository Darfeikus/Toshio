<?php
   function delete_files($target) {
      if(is_dir($target)){
          $files = glob( $target . '*', GLOB_MARK );
          foreach( $files as $file ){
              delete_files( $file );      
          }
          if (file_exists($target)) {
            rmdir( $target );
          }
      } elseif(is_file($target)) {
          unlink( $target );  
      }
    }
?>
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
          <title>Delete Assigment</title><meta charset="UTF-8" />
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
  $option = $_POST['opt'];
  $target_dir = './Classes/'.$_SESSION['group'].'/'.$assName;
  $new_dir = './deactivated/'.$assName;
  
  if (file_exists($target_dir)) {
    if ($option == '0') {
      rename($target_dir,$new_dir);
      echo "<h4>Succesfully deactivated " . $assName;
    }
    else{
      delete_files($target_dir);
      require 'Database.php';
      try{
        if (!empty($_POST)) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM `Assigments` WHERE name = ?";
            $result = $pdo->prepare($sql);
            $result->execute(array($assName));
            Database::disconnect();
            echo "<h4>Succesfully removed from database " . $assName;
        } else {
            echo "<h4>There was a problem deleting the Data Base of " . $assName;
            die();
        }
      }
      catch(PDOException $e)
        {
        }
    }
  }
  else
    echo "<h4> File not found " . $assName;

?>

<br></br>
<form action="../indexAdmin.php" method="post" enctype="multipart/form-data">
      <div class="form-group col-md-6">
        <h4>Return to home page:</h4>
        <input type="submit" value="Home" name="submit" class="btn btn-primary">
      </div>
    </form>
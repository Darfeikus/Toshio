<?php 
    session_start();
    $db = mysqli_connect('localhost', 'SrBarbosa', '$ouP4kI5A350me', 'calificador_registration');
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: ./index.php');
    }
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header("location: ./index.php");
    }
    $user = $_SESSION['username'];
    $query = "SELECT * FROM `users` WHERE id = '2' AND username = '$user'";
    $results = mysqli_query($db, $query);
    if(mysqli_num_rows($results) != 1){
        $_SESSION['msg'] = "Unauthorized access. You've been reported to the great admin. ";
        header("location: ./index.php"); 
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Permissions</title><meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="css/matrix-login.css" />
        <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

    </head>
    <?php  if (isset($_SESSION['username'])) : ?>
      <p>User:  <strong><?php echo $_SESSION['username']; ?></strong></p>
      <p> <a href="./index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>

    <?php 
      if (!empty($_SESSION['msg'])) {
          echo '<p class="msg"> '.$_SESSION['msg'].'</p>';
          unset($_SESSION['msg']);
      }
    ?>

    <body>
        <div id="loginbox">
            <form method="post" class="form-vertical" action="selectPerm.php">
                <div class="control-group normal_text"> <h3><img src="img/logo.png" alt="Logo" /></h3></div>

                <h4>Username</h4>
                <input type="text" name="matr"><br>
                
                <div class="quick-actions_homepage">
                
                <h4>Choose the new permissions</h4>
                        <select name="perm" >  
                            <option value="">No permissions</option>
                            <option value="1">Normal permissions</option>
                            <option value="2">Admin (once given cannot be taken)</option>
                    </select>
                <br></br>
                
                </div>
                
                <input type="submit" value="Submit"></input>

            </form>
        </div>
    </body> 

    <?php 
        if (isset($_POST['submit'])){
            $db = mysqli_connect('localhost', 'root', '', 'registration');
            $user = $_SESSION['username'];
            $query = "SELECT * FROM `users` WHERE id != '' AND username = '$user'";
            $results = mysqli_query($db, $query);
            if(mysqli_num_rows($results) != 1){
                header("location: ./index.php"); 
            }
            else
                header("location: ./indexAdmin.php"); 
        }
    ?>
    <form action="./indexAdmin.php" method="post" enctype="multipart/form-data">
            <div class="form-group col-md-6">
                <h4>Return to home page:</h4>
                <input type="submit" value="Home" name="submit" class="btn btn-primary">
            </div>
        </form>
</html>

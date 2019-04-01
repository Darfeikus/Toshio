<?php 
    session_start();
    $db = mysqli_connect('localhost', 'tonitolinux', 'p4S5,A.C89mbh2', 'calificador_registration');
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: ../index.php');
    }
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header("location: ../index.php");
    }
    $user = $_SESSION['username'];
    $query = "SELECT * FROM `users` WHERE id = '2' AND username = '$user'";
    $results = mysqli_query($db, $query);
    if(mysqli_num_rows($results) != 1){
        $_SESSION['msg'] = "Unauthorized access. You've been reported to the great admin. ";
        header("location: ../index.php"); 
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
        <link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

    </head>

    <?php  if (isset($_SESSION['username'])) : ?>
      <p>User:  <strong><?php echo $_SESSION['username']; ?></strong></p>
      <p> <a href="/index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
    
    <body>
        <div id="loginbox">
            <form method="post" class="form-vertical" action="selectPerm.php">
                <div class="control-group normal_text"> <h3><img src="../img/logo.png" alt="Logo" /></h3></div>
            </form>
        </div>
    </body> 

    <?php

        $search = $_POST['matr'];
        $query = "SELECT * FROM `users` WHERE username = '$search'";
        $results = mysqli_query($db, $query);
        if(mysqli_num_rows($results) != 1){
            $_SESSION['msg'] = "Username not found";
			header("location: ./permissions.php"); 
        }
        else{
        	$newPermission = $_POST['perm'];
        	$_SESSION['msg'] = "";
        	$getID = mysqli_fetch_assoc(mysqli_query($db, "SELECT id FROM users WHERE username = '$search'"));
	        $id = $getID['id'];
        	if ($id!=2) {
		        $sql = "UPDATE users SET id = '$newPermission'  WHERE username = '$search'";
			    
			    if (mysqli_query($db, $sql)) {
					$_SESSION['msg'] = "Permissions updated successfully";
				} else {
					$_SESSION['msg'] = "Error updating permissions: " . mysqli_error($conn);
				}
				
	        	$getID = mysqli_fetch_assoc(mysqli_query($db, "SELECT id FROM users WHERE username = '$search'"));
	        	$id = $getID['id'];
	        	if ($id == '')
	        		$id = 'No permissions';
	        	else if ($id == '1')
	        		$id = 'Normal permissions';
	        	else
	        		$id = 'Admin';
	        	echo '<h4 class="msg"> Username = '.$search.'</h4>';
	          	echo '<h4 class="msg"> Permissions = '.$id.'</h4>';
          	}
          	else{
          		$_SESSION['msg'] = "Admin permissions cannot be taken\nContact the administrator";
          		header("location: ./permissions.php"); 
          	}
        }
    ?>
    
    <?php 
        if (isset($_POST['submit'])){
            $db = mysqli_connect('localhost', 'root', '', 'registration');
            $user = $_SESSION['username'];
            $query = "SELECT * FROM `users` WHERE id != '' AND username = '$user'";
            $results = mysqli_query($db, $query);
            if(mysqli_num_rows($results) != 1){
                header("location: ../index.php"); 
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

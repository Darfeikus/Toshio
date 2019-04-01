<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../index.php');
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('location: ../index.php');
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
        <title>Sumbission</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="../css/bootstrap.min.css" />
		<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="../css/matrix-login.css" />
        <link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

    </head>
    <?php  if (isset($_SESSION['username'])) : ?>
      <p>Welcome <strong><?php echo $_SESSION['username'];?></strong></p>
      <p> <a href="/index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
    <body>
        <div id="loginbox">
            <?php 
                if (isset($_POST['task'])){
                    $_SESSION['task'] = $_POST['task'];
                    echo './Classes/'.$_SESSION['group'].'/'.$_POST['task'].'/lang';
                    if (file_exists('./Classes/'.$_SESSION['group'].'/'.$_POST['task'].'/pretry.php')) {
                        $file = fopen('./Classes/'.$_SESSION['group'].'/'.$_POST['task'].'/lang',"r");
                        $lang = fgets($file);
                        fclose($file);
                        header('Location: ./Classes/'.$_SESSION['group'].'/'.$_POST['task'].'/pretry.php');
                    }
                    else
                        echo "It seems you can't go there :p";
                }
            ?>
            <form method="post" class="form-vertical" action="">
				 <div class="control-group normal_text"> <h3><img src="../img/logo.png" alt="Logo" /></h3></div>
                 <h4>Select your assignment</h4>

                    <?php 
                        require 'Database.php';
                        $pdo = Database::connect();
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $sql = "SELECT * FROM `Assigments` WHERE id_group = ?";
                        $result = $pdo->prepare($sql);
                        $result->execute(array($_SESSION['group']));
                    ?>

                    <select name="task">
                        <?php while ($row = $result->fetch(PDO::FETCH_NUM)) { if(file_exists('./Classes/'.$_SESSION['group'].'/'.$row[2].'/Alumnos/'.$_SESSION['username'])){?>
                          <option value="<?php echo $row[2];?>"><?php echo $row[2]; ?>  </option>
                          <?php } ?>
                        <?php } ?>
                    </select>
                <br><br>
                <input type="submit" value="Go" name="submit" class="btn btn-primary"/>
            </form>
        </div>
    </body>
    
    <form action="../index.php" method="post" enctype="multipart/form-data">
        <div class="form-group col-md-6">
            <h4>Return to home page:</h4>
                <input type="submit" value="Home" name="submit" class="btn btn-primary">
        </div>

    </form>
</html>

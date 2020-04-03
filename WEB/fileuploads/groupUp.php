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
if (mysqli_num_rows($results) != 1) {
  $_SESSION['msg'] = "Unauthorized access. You've been reported to the great admin. ";
  header('location: ../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Home Page</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
  <link rel="stylesheet" href="../css/fullcalendar.css" />
  <link rel="stylesheet" href="../css/matrix-style.css" />
  <link rel="stylesheet" href="../css/matrix-media.css" />
  <link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" href="jquery.gritter.css" />
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>

<body>

  <!--Header-part-->
  <div id="header">
  <h1><a href="dashboard.html">Submission</a></h1>
  </div>
  <div class="content">
    <!-- notification message -->

    <?php if (isset($_SESSION['success'])) : ?>
      <div class="error success">
        <h3>
          <?php
          echo $_SESSION['success'];
          unset($_SESSION['success']);
          ?>
        </h3>
      </div>
    <?php endif ?>

    <!-- logged in user information -->
    <?php if (isset($_SESSION['username'])) : ?>
      <p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
      <p> <a href="../index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
  </div>

  <!--main-container-part-->
  <div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
      <div id="breadcrumb"> <a href="../index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>Group Creation</a></div>
    </div>
    <!--End-breadcrumbs-->
    <?php
    if (!empty($_SESSION['msg'])) {
      echo '<p class="msg"> ' . $_SESSION['msg'] . '</p>';
      unset($_SESSION['msg']);
    }
    ?>
    <div class="container-fluid">
      <?php

      $target_dir = './Groups/';
      $target_dirAss = './Classes/';

      if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
        chmod($target_dir, 0777);
      }

      if (!move_uploaded_file($_FILES["students"]["tmp_name"], $target_dir . 'class.csv')) {
        header('location: ./createGroup.php');
      }

      chmod($target_dir . 'class.csv', 0777);

      $id = '';
      $name = '';

      $i = 0;
      $group;
      $clase;

      if (($handle = fopen($target_dir . '/class.csv', "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $num = count($data);
          for ($c = 0; $c < $num; $c++) {
            if ($i == 4 && $c == 1)
              $group = $data[$c];
            if ($i == 3 && $c == 1)
              $clase = str_replace(" ", "_", $data[$c]);
          }
          $i++;
        }
        $id = $clase . '_' . $group;
        $target_dir2 = $target_dirAss . $id;
        if (!file_exists($target_dir2)) {
          mkdir($target_dir2, 0777, true);
          chmod($target_dir2, 0755);
        }
        fclose($handle);
      }

      copy($target_dir . '/class.csv', $target_dir2 . '/class.csv');
      chmod($target_dir2 . '/class.csv', 0755);

      require 'Database.php';
      try {
        if (!empty($_POST)) {
          $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = "INSERT INTO `groups`(`id_group`, `name`,`matricula`) VALUES (?,?,?)";
          $q = $pdo->prepare($sql);
          $q->execute(array($id, $clase, $_SESSION['username']));
          Database::disconnect();
          echo "<h4>Group created with success</h4>";
        } else {
          header("Location: ../indexAdmin.php");
          die();
        }
      } catch (PDOException $e) {
      }

      ?>


      <form action="../indexAdmin.php" method="post" enctype="multipart/form-data">
        <div class="form-group col-md-6">
          <h4>Return to home page:</h4>
          <input type="submit" value="Home" name="submit" class="btn btn-primary">
        </div>
      </form>
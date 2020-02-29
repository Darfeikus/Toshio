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
      <div id="breadcrumb"> <a href="../index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>Edit Assignment</a></div>
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
      $assName = $_POST['task'];
      $idAss = $_SESSION['group'] . '_' . $_POST['task'];
      $target_dir = './Classes/' . $_SESSION['group'] . '/' . $assName;
      $target_dir2 = $target_dir . '/TestCases';
      $target_dir4 = $target_dir . '/Alumnos';
      if (file_exists($target_dir)) {

        system('echo ' . $_POST['lang'] . ' > ' . $target_dir . '/lang');
        system('echo ' . $_POST['tcNum'] . ' > ' . $target_dir . '/tcNum');
        system('echo ' . $_POST['tcNum'] . ' > ' . $target_dir . '/trNum');
        system('echo ' . $_POST['task'] . ' > ' . $target_dir . '/name');

        chmod($target_dir . '/lang', 0777);
        chmod($target_dir . '/tcNum', 0777);
        chmod($target_dir . '/trNum', 0777);
        chmod($target_dir . '/name', 0777);

        $myfile = fopen($target_dir . '/lang', "r");
        if (fgets($myfile) == "C\n") {
          $target_dir3 = $target_dir . '/TestCasesC';
          mkdir($target_dir3, 0777);
          copy('./TestCasesC/CompareTextFilesC.java', $target_dir3 . '/CompareTextFilesC.java');
          chmod($target_dir3 . '/CompareTextFilesC.java', 0777);
          chmod($target_dir3, 0777);
          copy('resultsC.php', $target_dir . '/results.php');
          chmod($target_dir . '/results.php', 0777);
          copy('pretry.php', $target_dir . '/pretry.php');
          chmod($target_dir . '/pretry.php', 0777);
        } else {
          $target_dir3 = $target_dir . '/TestCasesJava';
          mkdir($target_dir3, 0777);
          copy('./TestCasesJava/CompareTextFilesJava.java', $target_dir3 . '/CompareTextFilesJava.java');
          chmod($target_dir3 . '/CompareTextFilesJava.java', 0777);
          chmod($target_dir3, 0777);
          copy('resultsJava.php', $target_dir . '/results.php');
          chmod($target_dir . '/results.php', 0777);
          copy('pretryJava.php', $target_dir . '/pretry.php');
          chmod($target_dir . '/pretry.php', 0777);
        }

      }
      $file = $target_dir . '/tc.zip';
      unlink($file);
      move_uploaded_file($_FILES["testCases"]["tmp_name"], $target_dir . '/tc.zip');
      chmod($target_dir . '/tc.zip', 0777);

      $zip = new ZipArchive;
      $res = $zip->open($target_dir . '/tc.zip');
      if ($res === TRUE) {
        $zip->extractTo($target_dir2 . '/');
        $zip->close();
        echo 'Assignment edited with success';
      } else {
        echo 'extraction error';
      }

      $row = 0;
      if (($handle = fopen('./Classes/' . $_SESSION['group'] . '/class.csv', "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $num = count($data);
          if ($row > 6)
            for ($c = 0; $c < $num; $c++) {
              if ($c == 1)
                if (!file_exists($target_dir4 . '/' . $data[$c])) {
                  mkdir($target_dir4 . '/' . $data[$c]);
                  chmod($target_dir4 . '/' . $data[$c], 0777);
                  system('echo 0 > ' . $target_dir4 . '/' . $data[$c] . '/tries');
                  chmod($target_dir4 . '/' . $data[$c] . '/tries', 0777);
                }
            }
          $row++;
        }
        fclose($handle);
      }

      ?>


      <form action="../indexAdmin.php" method="post" enctype="multipart/form-data">
        <div class="form-group col-md-6">
          <h4>Return to home page:</h4>
          <input type="submit" value="Home" name="submit" class="btn btn-primary">
        </div>
      </form>
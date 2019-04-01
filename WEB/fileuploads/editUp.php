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
  <title>Edit Assigment</title><meta charset="UTF-8" />
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
$idAss = $_SESSION['group'].'_'.$_POST['task'];
$target_dir = './Classes/'.$_SESSION['group'].'/'.$assName;
$target_dir2 = $target_dir.'/TestCases';
$target_dir4 = $target_dir.'/Alumnos';
if (file_exists($target_dir)) {

  system('echo '.$_POST['lang'] .' > '.$target_dir.'/lang');
  system('echo '.$_POST['tcNum'] .' > '.$target_dir.'/tcNum');
  system('echo '.$_POST['tcNum'] .' > '.$target_dir.'/trNum');
  system('echo '.$_POST['task'] .' > '.$target_dir.'/name');
  
  chmod($target_dir.'/lang', 0777);
  chmod($target_dir.'/tcNum', 0777);
  chmod($target_dir.'/trNum', 0777);
  chmod($target_dir.'/name', 0777);
}
$file = $target_dir.'/tc.zip';
unlink($file);
move_uploaded_file($_FILES["testCases"]["tmp_name"], $target_dir.'/tc.zip');
chmod($target_dir.'/tc.zip', 0777);

$zip = new ZipArchive;
$res = $zip->open($target_dir.'/tc.zip');
if ($res === TRUE) {
  $zip->extractTo($target_dir2.'/');
  $zip->close();
  echo 'Assignement edited with succes';
} else {
  echo 'extraction error';
}

  $row = 0;
  if (($handle = fopen('./Classes/'.$_SESSION['group'].'/class.csv', "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $num = count($data);
          if($row>6)
          for ($c=0; $c < $num; $c++) {
            if($c==1)
            if (!file_exists($target_dir4.'/'.$data[$c])) {
              mkdir($target_dir4.'/'.$data[$c]);
              chmod($target_dir4.'/'.$data[$c], 0777);
              system('echo 0 > '.$target_dir4.'/'.$data[$c].'/tries');
              chmod($target_dir4.'/'.$data[$c].'/tries', 0777);
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
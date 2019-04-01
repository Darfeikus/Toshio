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
  <title>Assigment Creations</title><meta charset="UTF-8" />
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
$assName = $_POST['assName'];
$idAss = $_POST['group'].'_'.$_POST['assName'];
$group = $_POST['group'];
$target_dir = 'Classes/'.$group.'/'.$assName;
$target_dir2 = $target_dir.'/TestCases';
$target_dir4 = $target_dir.'/Alumnos';
if (!file_exists($target_dir) && $assName != "") {
  mkdir($target_dir,0777,true);
  mkdir($target_dir2,0777,true);
  mkdir($target_dir4,0777,true);
  chmod($target_dir, 0777);
  chmod($target_dir2, 0777);
  chmod($target_dir4, 0777);
  system('echo '.$_POST['lang'] .' > '.$target_dir.'/lang');
  system('echo '.$_POST['tcNum'] .' > '.$target_dir.'/tcNum');
  system('echo '.$_POST['trNum'] .' > '.$target_dir.'/trNum');
  system('echo '.$_POST['assName'] .' > '.$target_dir.'/name');
  chmod($target_dir.'/lang', 0777);
  chmod($target_dir.'/tcNum', 0777);
  chmod($target_dir.'/trNum', 0777);
  chmod($target_dir.'/name', 0777);
  $myfile = fopen($target_dir.'/lang', "r");
  if (fgets($myfile)=="C\n") {
    $target_dir3 = $target_dir.'/TestCasesC';
    mkdir($target_dir3,0777);
    copy('./TestCasesC/CompareTextFilesC.java', $target_dir3.'/CompareTextFilesC.java');
    chmod($target_dir3.'/CompareTextFilesC.java', 0777);
    chmod($target_dir3, 0777);
    copy('resultsC.php', $target_dir.'/results.php');
    chmod($target_dir.'/results.php', 0777);
    copy('pretry.php', $target_dir.'/pretry.php');
    chmod($target_dir.'/pretry.php', 0777);
  }
  else{
    $target_dir3 = $target_dir.'/TestCasesJava';
    mkdir($target_dir3,0777);
    copy('./TestCasesJava/CompareTextFilesJava.java', $target_dir3.'/CompareTextFilesJava.java');
    chmod($target_dir3.'/CompareTextFilesJava.java', 0777);
    chmod($target_dir3, 0777);
    copy('resultsJava.php', $target_dir.'/results.php');
    chmod($target_dir.'/results.php', 0777);
    copy('pretryJava.php', $target_dir.'/pretry.php');
    chmod($target_dir.'/pretry.php', 0777); 
  }
}
move_uploaded_file($_FILES["testCases"]["tmp_name"], $target_dir.'/tc.zip');
chmod($target_dir.'/tc.zip', 0777);
$row = 0;
if (($handle = fopen('./Classes/'.$group.'/class.csv', "r")) !== FALSE) {
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
            copy($target_dir.'/tcNum',$target_dir4.'/'.$data[$c].'/tcNum');
            $zip = new ZipArchive;
            $res = $zip->open($target_dir.'/tc.zip');
            if ($res === TRUE) {
              mkdir($target_dir4.'/'.$data[$c].'/TestCases');
              $zip->extractTo($target_dir4.'/'.$data[$c].'/TestCases');
              $zip->close();
            } else {
              echo 'extraction error';
            }
          }
        }
        $row++;
      }
      fclose($handle);
    }
    
    $zip = new ZipArchive;
    $res = $zip->open($target_dir.'/tc.zip');
    if ($res === TRUE) {
      $zip->extractTo($target_dir2.'/');
      $zip->close();
      echo 'Assignement created with success';
    } else {
      echo 'extraction error';
    }
    require 'Database.php';
    try{
      if (!empty($_POST)) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `Assigments`(`id_ass`, `id_group`,`name`) VALUES (?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($idAss,$group,$assName));
        Database::disconnect();
      } else {
        header("Location: ../indexAdmin.php");
        die();
      }
    }
    catch(PDOException $e)
    {
    }
    ?>


    <form action="../indexAdmin.php" method="post" enctype="multipart/form-data">
      <div class="form-group col-md-6">
        <h4>Return to home page:</h4>
        <input type="submit" value="Home" name="submit" class="btn btn-primary">
      </div>
    </form>
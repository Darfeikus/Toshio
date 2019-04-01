<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../../../../index.php');
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('location: ../../../../index.php');
  }
?>
<html>
	<head>
	    <title>Results</title><meta charset="UTF-8" />
	       <meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<link rel="stylesheet" href="../../../../css/bootstrap.min.css" />
			<link rel="stylesheet" href="../../../../css/bootstrap-responsive.min.css" />
	        <link rel="stylesheet" href="../../../../css/matrix-login.css" />
	        <link href="../../../../font-awesome/css/font-awesome.css" rel="stylesheet" />
			<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

	    </head>
	    <body>
	        <div id="loginbox">
	            <form id="loginform" class="form-vertical" action="index.html">
					 <div class="control-group normal_text"> <h3><img src="../../../../img/logo.png" alt="Logo" /></h3></div>
	            </form>
	        </div>
	    </body>
	<body>
		<?php  if (isset($_SESSION['username'])) : ?>
	      <p> <a href="/index.php?logout='1'" style="color: red;">logout</a> </p>
	    <?php endif ?>
		<h1>Java Results</h1>

			<pre> Results:
				<?php
					//Get studentId
					$matricula = $_SESSION['username'];
					$myfile = fopen('name', "r");
					$original = fgets($myfile);
					fclose($myfile);
					$assName = str_replace("\n",'',$original);
					//Create studentId folder
					$target_dir = './Alumnos/'.$matricula.'/';

					//Check for tries

					$file = fopen('trNum', 'r');
					$trAllowed = fgets($file);
					$file = fopen('./Alumnos/'.$matricula.'/tries', 'r');
					$triesN = fgets($file);

					if (intval($triesN) >= intval($trAllowed))
						echo "Maximum number of tries used.";
					else if (file_exists($target_dir) && $matricula!= "") {

						//Get submission from student

						$target_file = $target_dir . 'Main.java';

						if (file_exists($target_dir.'Main.class'))
							unlink( $target_dir.'Main.class');

						$uploadOk = 1;
						$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

						//Upload

						if ($uploadOk == 0) {
							echo "Sorry, your file was not uploaded.";
						}else {
							if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
							} else {
									echo "Sorry, there was an error uploading your file.";
							}
						}

						// Compile Main.java

						if(!system('docker run --rm -v $PWD:/app -w /app demo/oracle-java:8 javac ./Alumnos/'.$matricula.'/Main.java'))

						$myfile = fopen('tcNum', "r");
						$tcNum = fgets($myfile);
						fclose($myfile);

						// Run TestCase
						for ($i=1; $i <= $tcNum; $i++) { 
							$cont = system('docker run -i --rm -v $PWD:/app -w /app demo/oracle-java:8 java -cp ./Alumnos/'.$matricula.' Main < ./TestCases/in'.$i.' > ./Alumnos/'.$matricula.'/out'.$i);
						}
						
						// Compare Output
						
						system('echo '.$matricula.' > ./Alumnos/'.$matricula.'/current');
						system('docker run --rm -v $PWD:/app -w /app demo/oracle-java:8 javac -d ./Alumnos/'.$matricula.' ./TestCasesJava/CompareTextFilesJava.java');

						chdir('./Alumnos/'.$matricula.'/');
						
						$comm = 'docker run -i --rm -v $PWD:/app -w /app demo/oracle-java:8 java CompareTextFilesJava';

						$content = system($comm);
						$calificacion = file_get_contents('ResultsOutJava');
						$try = file_get_contents('tries');

						echo "Result = ".$calificacion;

						chdir('../../');

						error_reporting(E_ALL ^ E_NOTICE);
						require '../../../Database.php';
						$matriculaErr = $calificacionErr = "";
						$timezone = date_default_timezone_set('America/Mexico_City');;
						$date = date('m/d/Y h:i:s a', time());

						$valido = true;
						if (!empty($_POST)) {
							if($valido == true){
								$pdo = Database::connect();
								$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$sql = "INSERT INTO `Submissions`(`id_ass`, `matricula`, `calificacion`, `intento`, `time`, `language`) VALUES (?,?,?,?,?,?)";
								$q = $pdo->prepare($sql);
								$q->execute(array($_SESSION['group'].'-'.$_SESSION['task'],$matricula,$calificacion,$try,$date,'Java'));
								Database::disconnect();
							}
						}
						} else {
								header("Location: ../../../groupIndex.php");
								die();
						}
				?>
			</pre>
	</body>
	<form action="../../../../index.php" method="post" enctype="multipart/form-data">
			<div class="form-group col-md-6">
				<h4>Return to home page:</h4>
				<input type="submit" value="Home" name="submit" class="btn btn-primary">
			</div>

	</form>
</html>

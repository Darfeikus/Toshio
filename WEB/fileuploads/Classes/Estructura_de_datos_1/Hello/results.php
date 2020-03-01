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
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Submission in Java</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="../../../../css/bootstrap.min.css" />
	<link rel="stylesheet" href="../../../../css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" href="../../../../css/fullcalendar.css" />
	<link rel="stylesheet" href="../../../../css/matrix-style.css" />
	<link rel="stylesheet" href="../../../../css/matrix-media.css" />
	<link href="../../../../font-awesome/css/font-awesome.css" rel="stylesheet" />
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
			<p> <a href="../../../../index.php?logout='1'" style="color: red;">logout</a> </p>
		<?php endif ?>
	</div>

	<!--main-container-part-->
	<div id="content">
		<!--breadcrumbs-->
		<div id="content-header">
			<div id="breadcrumb"> <a href="../../../../index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>Results</a></div>
		</div>
		<!--End-breadcrumbs-->
		<?php
		if (!empty($_SESSION['msg'])) {
			echo '<p class="msg"> ' . $_SESSION['msg'] . '</p>';
			unset($_SESSION['msg']);
		}
		?>
		<div class="container-fluid">
			<h1>C Results</h1>

			<pre> Results:
				<?php
				//Get studentId
				$matricula = $_SESSION['username'];
				$myfile = fopen('name', "r");
				$original = fgets($myfile);
				fclose($myfile);
				$assName = str_replace("\n", '', $original);
				//Create studentId folder
				$target_dir = './Alumnos/' . $matricula . '/';

				//Check for tries

				$file = fopen('trNum', 'r');
				$trAllowed = fgets($file);
				$file = fopen('./Alumnos/' . $matricula . '/tries', 'r');
				$triesN = fgets($file);

				if (intval($triesN) >= intval($trAllowed))
					echo "Maximum number of tries used.";
				else if (file_exists($target_dir) && $matricula != "") {

					//Get submission from student

					$target_file = $target_dir . 'main.c';

					if (file_exists($target_dir . 'main'))
						unlink($target_dir . 'main');

					$uploadOk = 1;
					$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

					//Upload

					if ($uploadOk == 0) {
						echo "Sorry, your file was not uploaded.";
					} else {
						if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
						} else {
							echo "Sorry, there was an error uploading your file.";
						}
					}

					// Compile main.c

					if (!system('docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp gcc:8.2 gcc -o ' . $target_dir . 'main ' . $target_file))

						$myfile = fopen('tcNum', "r");
					$tcNum = fgets($myfile);
					fclose($myfile);

					// Run TestCase
					for ($i = 1; $i <= $tcNum; $i++) {
						$cont = system($target_dir . 'main < ./TestCases/in' . $i . ' > ./Alumnos/' . $matricula . '/out' . $i);
					}

					// Compare Output

					system('echo ' . $matricula . ' > ./Alumnos/' . $matricula . '/current');
					system('docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine javac -d ./Alumnos/' . $matricula . ' ./TestCasesC/CompareTextFilesC.java');

					chdir('./Alumnos/' . $matricula . '/');

					$comm = 'docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine java CompareTextFilesC';

					$content = system($comm);
					$calificacion = file_get_contents('ResultsOutC');
					$calif = explode("\n", $calificacion);
					$try = file_get_contents('tries');

					echo "Result = " . $calificacion;

					chdir('../../');

					error_reporting(E_ALL ^ E_NOTICE);
					require '../../../Database.php';
					$matriculaErr = $calificacionErr = "";
					$timezone = date_default_timezone_set('America/Mexico_City');;
					$date = date('m/d/Y h:i:s a', time());

					$valido = true;
					if (!empty($_POST)) {
						if ($valido == true) {
							$pdo = Database::connect();
							$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$sql = "INSERT INTO `Submissions`(`id_ass`, `matricula`, `calificacion`, `intento`, `time`, `language`) VALUES (?,?,?,?,?,?)";
							$q = $pdo->prepare($sql);
							$q->execute(array($_SESSION['group'] . '_' . $_SESSION['task'], $matricula, $calif[0], $try, $date, 'C'));
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
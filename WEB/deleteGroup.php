<?php 
session_start();
$db = mysqli_connect('localhost', 'SrBarbosa', 'Wy$rUH1r#', 'calificador_registration');
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
$query = "SELECT * FROM `users` WHERE id != '' AND username = '$user'";
$results = mysqli_query($db, $query);
if(mysqli_num_rows($results) != 1){
	$_SESSION['msg'] = "Unauthorized access. You've been reported to the great admin. ";
	header("location: ./index.php");	
}
?>
<!DOCTYPE html>

<html>
<head>
	<title>Delete Assigments</title><meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" href="css/matrix-login.css" />
	<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
	<div id="loginbox">
		<form id="loginform" class="form-vertical" action="index.html">
			<div class="control-group normal_text"> <h3><img src="img/logo.png" alt="Logo" /></h3></div>
		</form>
	</div>
</body>
<body>
	<div class="container-fluid">
		<h1>Delete Assignment</h1>
		<br><br>

		<?php 
		if (isset($_POST['task'])){
			$_SESSION['group'] = $_POST['task'];
			if (file_exists('./fileuploads/Classes/'.$_POST['task'])) 
				header('Location: ./fileuploads/deleteUpG.php');
			else
				echo "It seems you can't go there :p";
		}
		?>

		<form action="" method="post" enctype="multipart/form-data">


			<h4>Select your group</h4>

			<?php 
			require './fileuploads/Database.php';
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "SELECT * FROM `Groups` WHERE matricula = ?";
			$result = $pdo->prepare($sql);
			$result->execute(array($_SESSION['username']));

			?>

			<select name="task">
				<?php while ($row = $result->fetch(PDO::FETCH_NUM)) { ?>
					<option value="<?php echo $row[0] ?>"><?php echo $row[1]; ?>  </option>
				<?php } ?>
			</select>

			<br><br>

			<h4>
				Deleting a group will also remove it from the data base and all the assigments in this
				Group, it's advisable to download a copy of the db or download the data from the assigments
				first.
			</h4>
			You can download the data from the assigments in Check Assigment Results

			<br></br>
			<br><br>
			<input type="submit" value="Confirm" name="submit" class="btn btn-primary">

		</form>

		<form action="./indexAdmin.php" method="post" enctype="multipart/form-data">
			<div class="form-group col-md-6">
				<h4>Return to home page:</h4>
				<input type="submit" value="Home" name="submit" class="btn btn-primary">
			</div>
		</form>

	</body>
	</html>

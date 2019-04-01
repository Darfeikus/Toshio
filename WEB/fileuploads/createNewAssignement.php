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
	<title>Create new Assigment</title><meta charset="UTF-8" />
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
<body>
	<div class="container-fluid">
		<h1>New Assignment</h1>
		<br><br>

		<form action="./assUp.php" method="post" enctype="multipart/form-data">
			<div class="form-group col-md-6">
				<h4>Select the group for the assigment:</h4>
                    
                    <?php 
                        require 'Database.php';
                        $pdo = Database::connect();
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $sql = "SELECT * FROM `Groups` WHERE matricula = ?";
                        $result = $pdo->prepare($sql);
                        $result->execute(array($_SESSION['username']));

                    ?>

                    <select name="group">
                        <?php while ($row = $result->fetch(PDO::FETCH_NUM)) { ?>
                          <option value="<?php echo $row[0] ?>"><?php echo $row[1]; ?>  </option>
                        <?php } ?>
                    </select>

                <br><br>
			</div>

			<div class="form-group col-md-6">
				<h4>Name of the Assignement(No special characters):</h4>
				<input class="form-control" type="text" name="assName">
				<br><br>
			</div>
			
			<div class="form-group col-md-6">
				<h4>Number of Test Cases:</h4>
				<select name="tcNum">
					<?php for ($i = 1; $i <= 100; $i++) : ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php endfor; ?>
				</select>
				<br><br>
			</div>

			<div class="form-group col-md-6">
				<h4>Number of tries allowed:</h4>
				<select name="trNum">
					<?php for ($i = 1; $i <= 10; $i++) : ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php endfor; ?>
				</select>
				<br><br>
			</div>

			<div class="form-group col-md-6">
				<h4>Select Language:</h4>
				<select name="lang">
					<option value="C">C</option>
					<option value="Java">Java</option>
				</select>
				<br><br>
			</div>

			<h4>Please upload a zip file with the test cases</h4>
			<input type="file" name="testCases" id="testCases">
			<br><br>

			<input type="submit" value="Upload" name="submit" class="btn btn-primary">

		</form>
		<form action="../indexAdmin.php" method="post" enctype="multipart/form-data">
			<div class="form-group col-md-6">
				<h4>Return to home page:</h4>
				<input type="submit" value="Home" name="submit" class="btn btn-primary">
			</div>
		</form>
	</div>
</body>
</html>

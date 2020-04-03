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
	<title>Calificaciones</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="js/bootstrap.min.js"></script>
	<meta charset="utf-8">
</head>
<body>
	<?php 
	require '../../../Database.php';
	$pdo = Database::connect();

	?>
	<header>
		<h1 style="text-align: center">Lista de Calificaciones</h1>
	</header>
	<div class="wrapper">
		<div>
			<div class="container">
				<h2 style="text-align: center">Alumnos</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>
								Matricula
							</th>
							<th>
								Calificacion
							</th>
							<th>
								Intento
							</th>
							<th>
								Time
							</th>
							<th>
							</th>
							<th>
							</th>
							<th>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$file = fopen('name','r');
						$assName = fgets($file);
						fclose($file);
						$original = str_replace("\n",'',$assName);
						$sql = "SELECT * FROM `submissions` WHERE id_ass = ?";
						$result = $pdo->prepare($sql);
						$result->execute(array($_SESSION['id_ass']));
						while ($row = $result->fetch(PDO::FETCH_NUM)) {
							echo '<tr>';							   	
							echo '<td>'. $row[1] 	. '</td>';
							echo '<td>'. $row[2] 	. '</td>';
							echo '<td>'. $row[3] 	. '</td>';
							echo '<td>'. $row[4] 	. '</td>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
				<form action="../../../../indexAdmin.php" method="post" enctype="multipart/form-data">
					<div class="form-group col-md-6">
						<h4>Return to home page:</h4>
						<input type="submit" value="Home" name="submit" class="btn btn-primary">
					</div>
				</form>

			</div>
		</div>
	</div>
</body>
</html>;

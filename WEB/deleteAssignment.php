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
<html lang="en">

<head>
    <title>Delete Assignment</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="../css/fullcalendar.css" />
    <link rel="stylesheet" href="../css/matrix-style.css" />
    <link rel="stylesheet" href="../css/matrix-media.css" />
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
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
            <div id="breadcrumb"> <a href="index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>Delete Assignment</a></div>
        </div>
        <!--End-breadcrumbs-->
        <?php
        if (!empty($_SESSION['msg'])) {
            echo '<p class="msg"> ' . $_SESSION['msg'] . '</p>';
            unset($_SESSION['msg']);
        }
        ?>
        <div class="container-fluid">
		<h1>Delete Assignment</h1>
		<br><br>

		<form action="./fileuploads/deleteUp.php" method="post" enctype="multipart/form-data">


			<h4>Select your assignment</h4>

			<?php 
			require './fileuploads/Database.php';
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "SELECT * FROM `Assignments` WHERE id_group = ?";
			$result = $pdo->prepare($sql);
			$result->execute(array($_SESSION['group']));

			?>

			<select name="task">
				<?php while ($row = $result->fetch(PDO::FETCH_NUM)) { if(file_exists('./fileuploads/Classes/'.$_SESSION['group'].'/'.$row[2])){?>
					<option value="<?php echo $row[2];?>"><?php echo $row[2]; ?>  </option>
				<?php } ?>
			<?php } ?>
		</select>

		<br><br>

		Deleting an assignment will also remove it from the data base.
		You can download the data from the assignment in Check assignment Results

		<br></br>
		<select name="opt">
			<option value="0">Deactivate</option>
			<option value="1">Delete</option>
		</select>
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

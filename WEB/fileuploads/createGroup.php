<?php
session_start();

if (!isset($_SESSION['username'])) {
	$_SESSION['msg'] = "You must log in first";
	header('location: ../index.php');
}
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['username']);
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
			<div id="breadcrumb"> <a href="../index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>New Group</a></div>
		</div>
		<!--End-breadcrumbs-->
		<?php
		if (!empty($_SESSION['msg'])) {
			echo '<p class="msg"> ' . $_SESSION['msg'] . '</p>';
			unset($_SESSION['msg']);
		}
		?>
		<div class="container-fluid">
			<form action="./groupUp.php" method="post" enctype="multipart/form-data">

				<h4>Upload csv with the class ids</h4>
				<input type="file" name="students" id="students">
				<br><br>

				<input type="submit" value="Upload" name="submit" class="btn btn-primary">

			</form>
			<form action="../indexAdmin.php" method="post" enctype="multipart/form-data">
				<div class="form-group col-md-6">
					<h4>Return to home page:</h4>
					<input type="submit" value="Home" name="submit" class="btn btn-primary">
				</div>

			</form>
</body>

</html>
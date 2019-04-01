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

<html>
	<head>
	        <title>Submission in C</title><meta charset="UTF-8" />
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
		<div id="loginbox">
		<h1>C Sumbission</h1>

		<form action="./results.php" method="post" enctype="multipart/form-data">
			<div class="form-group col-md-6">
				<h4>Matricula:</h4>
				<?php  if (isset($_SESSION['username'])) : ?>
			      <p> <strong><?php echo $_SESSION['username']; ?></strong></p>
			    <?php endif ?>
				<br><br>
			</div>
			<input type="file" name="fileToUpload" id="fileToUpload">
	 		<input type="submit" value="Upload" name="submit" class="btn btn-primary">

		</form>
		<form action="../../../../index.php" method="post" enctype="multipart/form-data">
			<div class="form-group col-md-6">
				<h4>Return to home page:</h4>
				<input type="submit" value="Home" name="submit" class="btn btn-primary">
			</div>
		</form>
	</body>
</html>

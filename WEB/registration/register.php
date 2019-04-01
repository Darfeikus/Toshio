<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
   <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="../css/matrix-login.css" />
        <link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
  <body>
      <div id="loginbox">
       <div class="control-group normal_text"> <h3><img src="../img/logo.png" alt="Logo" /></h3></div>
      </div>
  </body>
</head>
<body>
  <div id="loginbox">
  <div class="header">
  	<h2>Register</h2>
  </div>
	
  <form method="post" action="register.php">  	
    <?php include('./errors.php'); ?>
  	<div class="input-group">
  	  <label>Username</label>
  	  <input type="text" name="username" value="<?php echo $username; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Email</label>
  	  <input type="email" name="email" value="<?php echo $email; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>
  	<p>
  		Already have a username? <a href="login.php">Sign in</a>
  	</p>
  </form>
</body>
</html>
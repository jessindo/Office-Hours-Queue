<?php
require 'config/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Class</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link href="styles.css" rel="stylesheet" type="text/css"></link>
	<link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-light bg-light">
  <a class="navbar-brand" href="welcome.php">Office Hours Queue</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      	<li class="nav-item">
        	<a class="nav-link" href="welcome.php">Home<span class="sr-only">(current)</span></a>
      	</li>
    <?php if( !isset($_SESSION['logged_in'])) : ?>
    	<li class="nav-item">
  			<a class="nav-link" href="login.php">Login</a>
    	</li>
   	 	<li class="nav-item">
    		<a class="nav-link" href="register.php">Register</a>
    	</li>
	<?php endif; ?>
		<li class="nav-item">
  			<a class="nav-link" href="lobby.php">Lobby</a>
    	</li>
    <?php if( isset($_SESSION['logged_in'])) : ?>
    	<li class="nav-item">
  			<a class="nav-link" href="logout.php">Logout</a>
    	</li>
	<?php endif; ?>
    </ul>
  </div>
</nav>

<div class="container">
	<div class="row">
		<h1 class="col-12 mt-4 mb-4">Add a Class</h1>
	</div> <!-- .row -->
</div> <!-- .container -->

<div class="container">

	<form action="add_confirmation.php" method="POST">

		<div class="form-group row">
			<label for="class-code-id" class="col-sm-3 col-form-label text-sm-right">Class Code: <span class="text-danger">*</span></label>
			<div class="col-sm-9">
				<input type="text" class="form-control" id="class-code-id" name="class_code">
			</div>
		</div> <!-- .form-group -->

		<div class="form-group row">
			<label for="class-name-id" class="col-sm-3 col-form-label text-sm-right">Class Name: <span class="text-danger">*</span></label>
			<div class="col-sm-9">
				<input type="text" class="form-control" id="class-name-id" name="class_name">
			</div>
		</div> <!-- .form-group -->

		<div class="form-group row">
			<div class="ml-auto col-sm-9">
				<span class="text-danger font-italic">* Required</span>
			</div>
		</div> <!-- .form-group -->

		<div class="form-group row">
			<div class="col-sm-3"></div>
			<div class="col-sm-9 mt-2">
				<button type="submit" class="btn btn-success">Submit</button>
				<button type="reset" class="btn btn-light">Reset</button>
			</div>
		</div> <!-- .form-group -->

	</form>

</div> <!-- .container -->
</body>
</html>
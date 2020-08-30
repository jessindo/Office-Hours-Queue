<?php
require 'config/config.php';

$isInserted = "";

// Check that all required fields have been passed to this page
if ( !isset($_POST['class_code']) || empty($_POST['class_code']) 
|| !isset($_POST['class_name']) || empty($_POST['class_name']) ) {
	$error = "Please fill out all required fields.";
} else {

	// Connect to the DB
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if($mysqli->connect_errno) {
      echo $mysqli->connect_error;
      exit();
    }

    // Check if this user already exists in the database
    $sql_added = "SELECT * FROM Classes WHERE classCode = '" . $_POST['class_code'] . "';";
    // echo $sql_registered;
    // echo "<hr>";

    $results_added = $mysqli->query($sql_added);
    if(!$results_added) {
      echo $mysqli->error;
      exit();
    }

    // If there is one match or more, that means a user with this username or email already exists, so display an error.
    if( $results_added->num_rows > 0 ) {
		$error = "The class has already been added. Please add another class.";
		$_SESSION["classAdded"] = false;
    }
    else {
	    // Prepared SQL statement to INSERT new record into the DB.
		$sql_prepared = "INSERT INTO Classes(classCode, className) VALUES(?, ?);";

		$statement = $mysqli->prepare($sql_prepared);
		// First parameter is data types, the rest are variables that will fill in the ? placeholders
		$statement->bind_param("ss", $_POST['class_code'], $_POST['class_name']);
		$executed = $statement->execute();
		// execute() will return false if there's an error
		if(!$executed) {
			echo $mysqli->error;
		}
		// affected_rows returns how many records were affected (updated/deleted/inserted)
		if( $statement->affected_rows == 1 ) {
			$isInserted = true;
		}
		$statement->close();
		$mysqli->close();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Confirmation</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
			<h1 class="col-12 mt-4">Add a Class</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">
		<div class="row mt-4">
			<div class="col-12">
				<?php if(isset($error) && !empty($error)) : ?>
					<div class="text-danger">
						<?php echo $error; ?>
					</div>
				<?php endif; ?>

				<?php if($isInserted) : ?>
					<div class="text-success">
						<span class="font-italic"><?php echo $_POST["class_code"]; ?></span>
						 was successfully added.
					</div>
				<?php endif; ?>

			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="add_class.php" role="button" class="btn btn-primary">Back to Add Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container -->
</body>
</html>
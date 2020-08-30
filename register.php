<?php
require 'config/config.php';

// If user is logged in, don't let them see this page. Kick them out.
// Otherwise, continue with the validation/authentication stuff
if( isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true){
  // Redirect the user to the home page
  header('Location: welcome.php');
}
else{
  // Second line of defense -- check again that the required fields are not empty
  if ( !isset($_POST['email']) || empty($_POST['email'])
    || !isset($_POST['name']) || empty($_POST['name'])
    || !isset($_POST['role']) || empty($_POST['role'])
    || !isset($_POST['password']) || empty($_POST['password']) ) {
    $error = "Please fill out all required fields.";
    $SESSION["registered"] = false;
  }
  else{
    // Connect to the database and add this new user into the users table
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if($mysqli->connect_errno) {
      echo $mysqli->connect_error;
      exit();
    }

    // Sanitize user input
    // echo ($_POST['role']);
    $name = $mysqli->real_escape_string($_POST['name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $role = $mysqli->real_escape_string($_POST['role']);
    // Hash password
    $password = hash("sha256", $password);

    // Check if this user already exists in the database
    $sql_registered = "SELECT * FROM Users WHERE email = '" . $email . "';";
    // echo $sql_registered;
    // echo "<hr>";

    $results_registered = $mysqli->query($sql_registered);
    if(!$results_registered) {
      echo $mysqli->error;
      exit();
    }

    // If there is one match or more, that means a user with this username or email already exists, so display an error.
    if( $results_registered->num_rows > 0 ) {
      $error = "Email already belongs to a registered user. Please choose another one.";
      $_SESSION["registered"] = false;
    }
    else {
      // Otherwise, insert this user into the users table.
      $sql = "INSERT INTO Users(name, email, password, role) 
        VALUES('" . $name .  "','" . $email .  "','" . $password . "','" . $role . "');";
      // echo $sql;
      // echo "<hr>";
      $results = $mysqli->query($sql);
      if (!$results) {
        echo $mysqli->error;
        $_SESSION["registered"] = false;
      } 
      else {
        $_SESSION["registeredFalse"] = true;
      }
    }
  $mysqli->close();

  }

}


?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<link href="styles.css" rel="stylesheet" type="text/css"></link>

  <link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway&display=swap" rel="stylesheet">

  <script src="https://kit.fontawesome.com/07e87b32f1.js" crossorigin="anonymous"></script>

  <title>Register</title>

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
          <a class="nav-link" href="welcome.php">Home</a>
        </li>
    <?php if( !isset($_SESSION['logged_in'])) : ?>
      <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="register.php">Register <span class="sr-only">(current)</span></a>
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

<div class="col-sm-6 col-sm-offset-3" style="padding-top:80px;">

    <h2><span class="fas fa-sign-in-alt"></span> Register</h2>

    <!-- REGISTER FORM -->
    <form action="register.php" method="POST">
      <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" id="name-id" name="name">
            <small id="name-error" class="invalid-feedback">Name is required.</small>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" id="email-id" name="email">
            <small id="email-error" class="invalid-feedback">Email is required.</small>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" id="password-id" name="password">
            <small id="password-error" class="invalid-feedback">Password is required.</small>
        </div>
        <div class="form-group">
            <label style="padding-right:10px">Instructor/Student: </label>
            <label style="padding-right:10px" class="radio-inline"><input type="radio" name="role" value="instructor"> Instructor</label>
            <input type="hidden" id="role-id">
            <label class="radio-inline"><input type="radio" name="role" value="student"> Student</label>
<!--             <small id="role-error" class="invalid-feedback">Role is required.</small>
 -->        </div>
        <button type="submit" class="btn btn-warning btn-lg">Sign up</button>
    </form>

    <div class="row mt-4">
      <div class="col-12">
        <?php if (isset($_SESSION["registered"])) : ?>
          <div class="text-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION["registeredFalse"])) : ?>
          <div class="text-success"><?php echo $_POST['name']; ?> was successfully registered. You may login now.</div>
        <?php endif; ?>
    </div> <!-- .col -->
  </div> <!-- .row -->

    <hr>

    <p>Already have an account? <a href="login.php">Login</a></p>
    <p>Or go <a href="welcome.php">home</a>.</p>

</div>


</div>
<script>
document.querySelector('form').onsubmit = function(){
  if ( document.querySelector('#name-id').value.trim().length == 0 ) {
    document.querySelector('#name-id').classList.add('is-invalid');
  } else {
    document.querySelector('#name-id').classList.remove('is-invalid');
  }
  if ( document.querySelector('#email-id').value.trim().length == 0 ) {
    document.querySelector('#email-id').classList.add('is-invalid');
  } else {
    document.querySelector('#email-id').classList.remove('is-invalid');
  }
  if ( document.querySelector('#password-id').value.trim().length == 0 ) {
    document.querySelector('#password-id').classList.add('is-invalid');
  } else {
    document.querySelector('#password-id').classList.remove('is-invalid');
  }
  var checked = document.querySelector("input[type=radio]:checked");
  if (!checked) {
    alert("You need to select whether you are a student or an instructor.");
    document.querySelector('#role-id').classList.add('is-invalid');
  } else {
    document.querySelector('#role-id').classList.remove('is-invalid');
  }

  // return false prevents the form from being submitted
  // If length is greater than zero, then it means validation has failed. Invert the response and can use that to prevent form from being submitted.
  return ( !document.querySelectorAll('.is-invalid').length > 0 );
}
</script>
</body>
</html>

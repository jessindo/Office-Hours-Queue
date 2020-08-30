<?php
require 'config/config.php';

// If user is logged in, redirect user to home page. Don't allow them to see the login page.
if( isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
  header('Location: welcome.php');
}
else {
  // If user attempted to log in (aka submitted the form)
  if( isset($_POST['email']) && isset($_POST['password']) ){
      
    if( empty($_POST['email']) || empty($_POST['password']) ) {
      $error = "Please enter an email and password ";
    }
    // Authenticate the user.
    else {
      $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      if($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
      }
      $emailInput = $_POST["email"];
      $passwordInput = $_POST["password"];
      // Hash user input of password to compare this string to the password stored in the users table
      $passwordInput = hash("sha256", $passwordInput);
      // Look for a match - username/password combinmation
      $sql = "SELECT * FROM Users
        WHERE email = '" . $emailInput . "' AND password = '" . $passwordInput . "';";
      $results = $mysqli->query($sql);
      if(!$results) {
        echo $mysqli->error;
        exit();
      }

      // If there is a match, we will get at least one result back
      if( $results->num_rows > 0) {
        // Log them in!
        $_SESSION['logged_in'] = true;
        $row = $results->fetch_assoc();
        $_SESSION['email'] = $row["email"];
        $_SESSION['name'] = $row["name"];
        $_SESSION['role'] = $row["role"];
        $_SESSION['userID'] = $row["userID"];

        header('Location: welcome.php');
      }
      else {
        $error = "Invalid email or password";
      }
      
    }
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
  <title>Login</title>

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
      <li class="nav-item active">
          <a class="nav-link" href="login.php">Login <span class="sr-only">(current)</span></a>
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

<div class="col-sm-6 col-sm-offset-3" style="padding-top:80px;">

    <h2><span class="fas fa-sign-in-alt"></span> Login</h2>

    <!-- LOGIN FORM -->
    <form action="login.php" method="POST">

        <div class="row mb-3">
          <div class="font-italic text-danger col-sm ml-sm-auto">
            <!-- Show errors here. -->
            <?php
              if ( isset($error) && !empty($error) ) {
                echo $error;
              }
            ?>
          </div>
        </div> <!-- .row -->

        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" id="email-id">
            <small id="email-error" class="invalid-feedback">Email is required.</small>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password" id="password-id">
            <small id="password-error" class="invalid-feedback">Password is required.</small>
        </div>

        <button type="submit" class="btn btn-warning btn-lg">Login</button>
    </form>

    <hr>

    <p>Need an account? <a href="register.php">Register</a></p>
    <p>Or go <a href="welcome.php">home</a>.</p>

</div>

</div>
<script>
document.querySelector('form').onsubmit = function(){
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

  // return false prevents the form from being submitted
  // If length is greater than zero, then it means validation has failed. Invert the response and can use that to prevent form from being submitted.
  return ( !document.querySelectorAll('.is-invalid').length > 0 );
}
</script>
</body>
</html>

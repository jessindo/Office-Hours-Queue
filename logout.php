<?php
require 'config/config.php';
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<script>
		if(!window.location.hash) {
	       window.location = window.location + '#loaded';
	       window.location.reload();
	   	}
	</script>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Logout</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway&display=swap" rel="stylesheet">
	<link href="styles.css" rel="stylesheet" type="text/css"></link>

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
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Logout</h1>
			<div class="col-12">You are now logged out.</div>
			<div class="col-12 mt-3">You can go to the <a href="welcome.php">home page</a> or <a href="login.php">log in</a> again.</div>

		</div> <!-- .row -->
	</div> <!-- .container -->

</body>
</html>
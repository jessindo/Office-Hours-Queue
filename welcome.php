<?php
require 'config/config.php';

// if(isset($_GET['loggedOut'])){
// 	session_destroy();
// 	// reloads page
// 	echo '<script type="text/javascript">',
//      'if(!window.location.hash) {',
//         'window.location = window.location + "#loaded";',
//         'window.location.reload();}',
//      '</script>';
// }
// var_dump($_SESSION['role']);

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway&display=swap" rel="stylesheet">

	<link href="styles.css" rel="stylesheet" type="text/css"></link>

	<title>Office Hours Queue</title>

</head>
<style>
	body{
		background-image: linear-gradient(to top, #a8edea 0%, #fed6e3 100%);
	}
</style>
<body>
	<!-- will include this in server as header -->
	<nav class="navbar navbar-expand-md navbar-light bg-light">
	  <a class="navbar-brand" href="welcome.php">Office Hours Queue</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarNavDropdown">
	    <ul class="navbar-nav">
	      	<li class="nav-item active">
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
	    <div class="header">
	    	<img src="officehours.png" style="margin-bottom: 20px">
<!-- 			<h1 class="text-center">Office Hours Queue</h1>  -->
			<?php if( !isset($_SESSION['logged_in']) ) : ?>
	    		<a class="lead" href="lobby.php">Proceed as guest...</a>
	    	<?php else : ?>
	    		<a class="lead" href="lobby.php">Proceed to the lobby...</a>
	    	<?php endif; ?>
		</div>
</body>
<footer>
	© 2019 Jessica Inc.
</footer>

</html>
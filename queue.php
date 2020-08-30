<?php
require 'config/config.php';

// var_dump($_GET);
if( !isset( $_GET["classID"] ) || empty($_GET["classID"]) ) {
	// A track id is not given, show error message. Don't do anything else.
	$error = "Invalid Class ID";
}
else {
	// lol don't put here bc it loads before jquery does and the thing gets confused
	// if( isset($_SESSION['logged_in'])){
	// 	if( $_SESSION['role'] == 'instructor'){
	// 		echo '<script type="text/javascript">',
	// 	    	'$("#queue").on("click", "td", function(event) {',
	// 	        '$(this).toggleClass("clicked");',
	// 	        '})',
	// 	     	'</script>';
	// 	}
	// }

	// Connect to the DB
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
	  echo $mysqli->connect_error;
	  exit();
	}
	// var_dump($_SESSION['userID']);

	// Set character set
	$mysqli->set_charset('utf8');
	
	if(isset($_GET["addToQueue"]) ){
		if($_GET["addToQueue"] == "true"){
			// Prepared SQL statement to INSERT new record into the DB.
			$sql_prepared = "INSERT INTO Queue(userID, classID) VALUES(?, ?);";

			$statement = $mysqli->prepare($sql_prepared);
			// First parameter is data types, the rest are variables that will fill in the ? placeholders
			$statement->bind_param("ii", $_SESSION['userID'], $_GET['classID']);
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
		}
	} else if( isset($_GET["deleteName"]) ) {
		if($_GET["deleteName"] == "true"){
			$sql = "DELETE FROM Queue WHERE queueID = " . $_GET["queueID"] . ";";
			$results = $mysqli->query($sql);
			if(!$results) {
				echo $mysqli->error;
				exit();
			}
			// if ($mysqli->affected_rows == 1) {
			// 	$isDeleted = true;
			// }
		}

	}



	// Write the SQL statement
	$sql = "SELECT q.queueID, q.userID, u.name 
	FROM Queue q
	LEFT JOIN Users u
		ON q.userID = u.userID
	LEFT JOIN Classes c
		ON c.classID = q.classID
	WHERE q.classID = " . $_GET["classID"] . ";";

	// echo "<hr>" . $sql . "<hr>";
	// Run the query on the DB
	$results = $mysqli->query($sql);
	if( !$results ) {
		echo $mysqli->error;
		exit();
	}

	if($results->num_rows == 0){
	  $_SESSION['queueEmpty'] = true;
	  // var_dump($_SESSION['noClasses']);
	} else {
		$_SESSION['queueEmpty'] = false;
	}
	// $row = $results->fetch_assoc();
	// var_dump($row);
	// Close the connection
	// $mysqli->close();
	// var_dump($_SESSION['queueEmpty']);

}

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<link href="styles.css" rel="stylesheet" type="text/css"></link>

	<link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway&display=swap" rel="stylesheet">

	<script src="https://kit.fontawesome.com/07e87b32f1.js" crossorigin="anonymous"></script>

	<title><?php echo $_GET["classCode"]; ?> Queue</title>

</head>
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


<div class="container">

	<div class="row" style="margin-top: 20px">
	    <div class="col-7" style="margin-left: 15px">
	        <h1 style="text-decoration: underline;"><?php echo $_GET["classCode"]; ?> Queue</h1>
	    </div>
	    <?php if( isset($_SESSION['logged_in'])) : ?>
	      <?php if( $_SESSION['role'] == 'student') : ?>
	        <div class="col-4">
	            <button onclick="window.location.href = 'queue.php?classID=<?php echo $_GET['classID'];?>&amp;classCode=<?php echo $_GET['classCode'];?>&amp;addToQueue=true'" type="button" class="btn btn-primary">Add name to queue</button>
	        </div>
	      <?php endif; ?>
	    <?php endif; ?>
		</div>

<div class="data-table">
    <table id="queue" border="1" cellpadding="7" cellspacing="7">
        <thead>
             <tr>
                <!-- <th>Recipe ID</th> -->
                <th>Name</th>
                <!-- <th>Name</th>   -->                      
                <th width="176px">Action</th>
            </tr>
        </thead>

        <?php if( isset($_SESSION['queueEmpty'])) : ?>
          <?php if( $_SESSION['queueEmpty'] == true) : ?>
          <tr>
            <td colspan="7">Queue is empty.</td>
          </tr>
          <?php endif; ?>
        <?php endif; ?>

        <?php while($row = $results->fetch_assoc() ) : ?>
        	<tr>
        		<td><?php echo $row["name"]; ?></td>
    			<?php if(isset($_SESSION["userID"])) : ?>
	        		<?php if( $row["userID"] == $_SESSION["userID"] ) : ?>
	        			<td>
	        				<a class="a-inside delete" href="queue.php?classID=<?php echo $_GET['classID'];?>&amp;classCode=<?php echo $_GET['classCode'];?>&amp;queueID=<?php echo $row['queueID'];?>&amp;deleteName=true">Delete</a>
	        			</td>
	        		<?php endif; ?>
	        	<?php endif; ?>
	        	<?php if(isset($_SESSION["role"])) : ?>
	        		<?php if( $_SESSION["role"] == "instructor" ) : ?>
	        			<td>
	        				<i id="<?php echo $row['queueID'];?>" class="fas fa-check"></i>
	        			</td>
	        		<?php endif; ?>
	        	<?php endif; ?>	        		
        	</tr>
        <?php endwhile; ?>


        <!-- <tr>
          <td>ITP 303</td>
          <td>Full-Stack Web Development</td>   
          <td>
              <a class="a-inside view" href="">View</a>
          </td>
        </tr>

        <tr>
          <td>EE 109L</td>
          <td>Introduction to Embedded Systems</td>   
          <td>
              <a class="a-inside view" href="">View</a>
          </td>
        </tr> -->

    </table>
</div>
<script>
	// $("#queue").on("click", "td", function(event) {
	// 	// event.preventDefault();
	// 	//$(this) refers to the clicked li, not all the elements
	// 	$(this).toggleClass("clicked");
	// })

	$("#queue").on("click", "i", function(event) {
		var $queueID = $(this).attr('id');
		// event.preventDefault();
		// event.stopPropagation();
		$(this).parent().parent().fadeOut(500, function() {
			// This code gets run when fadeOut animation is done
			$(this).remove();
			window.location.replace("queue.php?classID=<?php echo $_GET['classID'];?>&classCode=<?php echo $_GET['classCode'];?>&queueID="+ $queueID + "&deleteName=true");
		});
	})
</script>
	   <?php if( isset($_SESSION['logged_in'])){
		if( $_SESSION['role'] == 'instructor'){
			echo '<script type="text/javascript">',
		    	'$("#queue").on("click", "td", function(event) {',
		        '$(this).toggleClass("clicked");',
		        '})',
		     	'</script>';
		}
	} ?>
</body>
<footer>
	© 2019 Jessica Inc.
</footer>

</html>
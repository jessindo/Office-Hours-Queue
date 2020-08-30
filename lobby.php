<?php
require 'config/config.php';

// Connect to the DB
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno) {
  echo $mysqli->connect_error;
  exit();
}

// Set character set
$mysqli->set_charset('utf8');

if( isset($_GET["deleteClass"]) ) {
  if($_GET["deleteClass"] == "true"){
    $sql = "DELETE FROM Classes WHERE classID = " . $_GET["classID"] . ";";
    $results = $mysqli->query($sql);
    if(!$results) {
      echo $mysqli->error;
      exit();
    }
    // if ($mysqli->affected_rows == 1) {
    //  $isDeleted = true;
    // }
  }

}

$sql = "SELECT * FROM Classes ";

$sql = $sql . " ORDER BY classCode;";
// echo $sql;

// send off the query
$results = $mysqli->query($sql);

if($results->num_rows === 0){
  $_SESSION['noClasses'] = true;
  // var_dump($_SESSION['noClasses']);
} else {
  $_SESSION['classesExist'] = true;
  $_SESSION['noClasses'] = false;
}
// var_dump($_SESSION['noClasses']);


?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <link href="https://fonts.googleapis.com/css?family=Montserrat:600|Raleway&display=swap" rel="stylesheet">

  <script src="https://kit.fontawesome.com/07e87b32f1.js" crossorigin="anonymous"></script>
  <link href="styles.css" rel="stylesheet" type="text/css"></link>
  <title>Office Hours Lobby</title>

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
      <li class="nav-item">
        <a class="nav-link" href="register.php">Register</a>
      </li>
    <?php endif; ?>
      <li class="nav-item active">
          <a class="nav-link" href="lobby.php">Lobby <span class="sr-only">(current)</span></a>
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
        <h1 style="text-decoration: underline;">Classes</h1>
    </div>
    <?php if( isset($_SESSION['logged_in'])) : ?>
      <?php if( $_SESSION['role'] == 'instructor') : ?>
        <div class="col-4">
            <button onclick="window.location.href = 'add_class.php';" type="button" class="btn btn-primary">Add Class</button>
        </div>
      <?php endif; ?>
    <?php endif; ?>
</div>

<div class="col-12" style="  font-family: 'Raleway', sans-serif;">

  Showing <?php echo $results->num_rows; ?> result(s).

</div> <!-- .col -->

<div class="data-table">
    <table border="1" cellpadding="7" cellspacing="7">
        <thead>
             <tr>
                <!-- <th>Recipe ID</th> -->
                <th>Class Code</th>
                <th>Name</th>                        
                <th width="250px">Action</th>
            </tr>
        </thead>

        <?php if( isset($_SESSION['noClasses'])) : ?>
          <?php if( $_SESSION['noClasses'] == true) : ?>
          <tr>
            <td colspan="7">No existing classes</td>
          </tr>
          <?php endif; ?>
        <?php endif; ?>

        <?php if( isset($_SESSION['classesExist']) ) : ?>
          <?php while($row = $results->fetch_assoc() ) : ?>
            <tr>
              <td> <?php echo $row["classCode"] ?> </td>
              <td> <?php echo $row["className"] ?> </td>
              <td>
                <a class="a-inside view" href="queue.php?classID=<?php echo $row['classID']; ?>&amp;classCode=<?php echo $row["classCode"] ?>">View</a>
                <?php if(isset( $_SESSION["role"])) : ?>
                  <?php if( $_SESSION["role"] == "instructor" ) : ?>
                    <a class="a-inside edit" href="edit_class.php?classID=<?php echo $row['classID']; ?>">Edit</a>
                    <a class="a-inside delete" href="lobby.php?classID=<?php echo $row['classID']; ?>&amp;deleteClass=true">Delete</a>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php endif; ?>

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
</body>
</html>

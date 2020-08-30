<?php
require 'config/config.php';

$isEdited = false;

if( !isset($_GET["classID"]) || empty($_GET["classID"]) ){
  echo "Invalid Class ID";
  exit();
}

// Second line of defense -- check again that the required fields are not empty
if(isset($_GET["editSuccess"]) ){
  if($_GET["editSuccess"] == "true"){
    if ( !isset($_POST['class_code']) || empty($_POST['class_code'])
    || !isset($_POST['class_name']) || empty($_POST['class_name']) ) {
    $error = "Please fill out all required fields.";
    $_SESSION["edited"] = false;
    } else {
      $success = "Class has been edited successfully.";
      $_SESSION["edited"] = true;
    }
  }
}

// Connect to the DB
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno) {
  echo $mysqli->connect_error;
  exit();
}

if( isset($_GET["editSuccess"]) && $_SESSION["edited"] == true){
  $sql_prepared = "UPDATE Classes SET classCode = ?, className = ? WHERE classID = ?;";
  $statement = $mysqli->prepare($sql_prepared);
  // First parameter is data types, the rest are variables that will fill in the ? placeholders
  $statement->bind_param("ssi", $_POST['class_code'], $_POST['class_name'], $_GET["classID"]);
  $executed = $statement->execute();
  // execute() will return false if there's an error
  if(!$executed) {
    echo $mysqli->error;
  }
  // affected_rows returns how many records were affected (updated/deleted/inserted)
  if( $statement->affected_rows == 1 ) {
    $isEdited = true;
  }
  $statement->close();
}


$sql_classes = "SELECT * FROM Classes WHERE classID = " . $_GET["classID"] . ";";
$results_classes = $mysqli->query($sql_classes);
if( !$results_classes ){
  echo $mysqli->error;
  exit();
}

$row_classes = $results_classes->fetch_assoc();
// var_dump($row_classes);


$mysqli->close();

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

  <title>Edit Class</title>

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
      <h1 class="col-12 mt-4">Edit Class</h1>
    </div>

  <?php if ( isset($error) && !empty($error) ) : ?>
    <div class="text-danger font-italic">
      <?php echo $error; ?>
    </div>
  <?php endif; ?>
  <?php if ( isset($isEdited) && !empty($isEdited) ) : ?>
    <div class="text-success">
      <?php echo $success; ?>
    </div>
  <?php endif; ?>

  <form action="edit_class.php?classID=<?php echo $_GET['classID']; ?>&amp;editSuccess=true" method="POST">

    <div class="form-group row">
      <label for="class-code-id" class="col-sm-3 col-form-label text-sm-right">Class Code: </label>
      <div class="col-sm-9">
        <input type="text" class="form-control" id="class-code-id" name="class_code" value="<?php echo $row_classes['classCode']; ?>">
      </div>
    </div> <!-- .form-group -->

    <div class="form-group row">
      <label for="class-name-id" class="col-sm-3 col-form-label text-sm-right">Class Name: </label>
      <div class="col-sm-9">
        <input type="text" class="form-control" id="class-name-id" name="class_name" value="<?php echo $row_classes['className']; ?>">
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



</body>
<footer>
  © 2019 Jessica Inc.
</footer>

</html>
<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if ($_SESSION["permission"] === 3) {
    header("location: access_denied.php");
    exit;
}

// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM Questions WHERE question_id = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);

            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    $stmt->close();
    
    // Close connection
    $mysqli->close();
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Answers</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark static-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img class="logo" src="images/logo.png" alt="logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Sign Out of Your Account</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="pb-2 mt-5 mb-4 border-bottom">
                        <h1>View Answers</h1>
                    </div>
                    <ol class="list-group list-group-flush" type="A">
                        <li class="list-group-item"><?php echo $row["answer_one"]; ?></li>
                        <li class="list-group-item"><?php echo $row["answer_two"]; ?></li>
                        <li class="list-group-item"><?php echo $row["answer_three"]; ?></li>
                        <?php
                        if ($row["answer_four"] !== NULL) {
                            echo "<li class='list-group-item'>" . $row["answer_four"] . "</li>";
                        }  
                        if ($row["answer_five"] !== NULL) {
                            echo "<li class='list-group-item'>" . $row["answer_five"] . "</li>";
                        }
                        ?>
                    </ol>
                    <div class='pb-2 mt-4 mb-5 clearfix'>
                        <a href="javascript:history.back()" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if ($_SESSION["permission"] !== 1) {
    header("location: access_denied.php");
    exit;
}

// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $answer_one = $answer_two = $answer_three = $answer_four = $answer_five = "";
$name_err = $answer_err = $answer_err_two = $answer_err_three = "";
 
// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a question.";
    } else {
        $name = $input_name;
    }

    // Validate Answer One
    $input_answer_one = trim($_POST["answer-one"]);
    if(empty($input_answer_one)){
        $answer_err = "Please enter an answer.";     
    } else{
        $answer_one = $input_answer_one;
    }
    
    // Validate Answer Two
    $input_answer_two = trim($_POST["answer-two"]);
    if(empty($input_answer_two)){
        $answer_err_two = "Please enter an answer.";     
    } else{
        $answer_two = $input_answer_two;
    }

    // Validate Answer Three
    $input_answer_three = trim($_POST["answer-three"]);
    if(empty($input_answer_three)){
        $answer_err_three = "Please enter an answer.";     
    } else{
        $answer_three = $input_answer_three;
    }

    // NULL if not filled in (not required)
    $input_answer_four = trim($_POST["answer-four"]);
    if(empty($input_answer_four)){
        $answer_four = NULL;
    } else{
        $answer_four = $input_answer_four;
    }

    $input_answer_five = trim($_POST["answer-five"]);
    if(empty($input_answer_five)){
        $answer_five = NULL;
    } else{
        $answer_five = $input_answer_five;
    }
    
    // Check input errors before inserting in database
    if (empty($name_err) && empty($answer_err) && empty($answer_err_two) && empty($answer_err_three)) {
        // Prepare an update statement
        $sql = "UPDATE Questions SET question=?, answer_one=?, answer_two=?, answer_three=?, answer_four=?, answer_five=? WHERE question_id=?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssi", $param_name, $param_answer_one, $param_answer_two, $param_answer_three, $param_answer_four, $param_answer_five, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_answer_one = $answer_one;
            $param_answer_two = $answer_two;
            $param_answer_three = $answer_three;
            $param_answer_four = $answer_four;
            $param_answer_five = $answer_five;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records updated successfully. Redirect to landing page
                header("location: view_questions.php?id=".$_SESSION["current_id"]);
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id = trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM Questions WHERE question_id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["question"];
                    $answer_one = $row["answer_one"];
                    $answer_two = $row["answer_two"];
                    $answer_three = $row["answer_three"];
                    $answer_four = $row["answer_four"];
                    $answer_five = $row["answer_five"];

                } else {
                    // URL doesn't contain valid id. Redirect to error page
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Question</title>
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
                    <div class="pb-2 mt-5 mb-4 border-bottom clearfix">
                        <h2>Update Question</h2>
                    </div>
                    <p>Please edit the input values and submit to update the question.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Question</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <div class="invalid-feedback"><?php echo $name_err; ?></div>
                        </div>
                        <div class="form-group">
                            <label>Answer One</label>
                            <input type="text" name="answer-one" class="form-control <?php echo (!empty($answer_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $answer_one; ?>">
                            <div class="invalid-feedback"><?php echo $answer_err; ?></div>
                        </div>
                        <div class="form-group">
                            <label>Answer Two</label>
                            <input type="text" name="answer-two" class="form-control <?php echo (!empty($answer_err_two)) ? 'is-invalid' : ''; ?>" value="<?php echo $answer_two; ?>">
                            <div class="invalid-feedback"><?php echo $answer_err_two; ?></div>
                        </div>
                        <div class="form-group">
                            <label>Answer Three</label>
                            <input type="text" name="answer-three" class="form-control <?php echo (!empty($answer_err_three)) ? 'is-invalid' : ''; ?>" value="<?php echo $answer_three; ?>">
                            <div class="invalid-feedback"><?php echo $answer_err_three; ?></div>
                        </div>
                        <div class="form-group">
                            <label>Answer Four</label>
                            <input type="text" name="answer-four" class="form-control" value="<?php echo $answer_four; ?>">
                        </div>
                        <div class="form-group">
                            <label>Answer Five</label>
                            <input type="text" name="answer-five" class="form-control" value="<?php echo $answer_five; ?>">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <?php echo "<a href='view_questions.php?id=" . $_SESSION['current_id'] . "' class='btn btn-secondary'>Cancel</a>" ?>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION["current_id"];

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
            // Prepare an insert statement
            $sql = "INSERT INTO Questions (question, quiz_id, answer_one, answer_two, answer_three, answer_four, answer_five) VALUES (?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("sisssss", $param_name, $param_id, $param_answer_one, $param_answer_two, $param_answer_three, $param_answer_four, $param_answer_five);
                
                // Set parameters
                $param_name = $name;
                $param_id = $id;
                $param_answer_one = $answer_one;
                $param_answer_two = $answer_two;
                $param_answer_three = $answer_three;
                $param_answer_four = $answer_four;
                $param_answer_five = $answer_five;
                
                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Records created successfully. Redirect to landing page
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Question</title>
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
                        <h2>Create Question</h2>
                    </div>
                    <p>Please enter the question and the answers. Once you have done this submit to save to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <?php echo "<a href='view_questions.php?id=" . $_SESSION['current_id'] . "' class='btn btn-secondary'>Cancel</a>" ?>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
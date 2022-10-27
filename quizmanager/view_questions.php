<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Questions</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
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
    <div class="wrapper main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    // Check existence of id parameter before processing further
                    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
                        // Include config file
                        require_once "config.php";
                        
                        // Store quiz_id in Session
                        $_SESSION["current_id"] = trim($_GET["id"]);

                        // Prepare a select statement
                        $sql = "SELECT * FROM Questions WHERE quiz_id = ?";

                        if ($stmt = $mysqli->prepare($sql)) {
                            // Bind variables to the prepared statement as parameters
                            $stmt->bind_param("i", $param_id);
                            
                            // Set parameters
                            $param_id = trim($_GET["id"]);
                            
                            // Attempt to execute the prepared statement
                            if ($stmt->execute()) {
                                $result = $stmt->get_result();
                                echo "<div class='pb-2 mt-5 mb-4 border-bottom clearfix'>";
                                echo "<h2 class='float-left'>Questions</h2>";
                                echo "<a href='create_question.php?id=" . $param_id . "' class='btn btn-success float-right'>Add New Question</a>";
                                echo "</div>";

                                if ($result->num_rows > 0) {
                                    echo "<table class='table table-bordered table-striped'>";
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>Question</th>";
                                    echo "<th>Action</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    $counter=1; //define counter for numbering table rows
                                    while ($row = $result->fetch_array()) {
                                        echo "<tr>";
                                        echo "<td>" . $counter++  . "</td>"; 
                                        echo "<td>" . $row['question'] . "</td>";
                                        echo "<td>";
                                        if ($_SESSION["permission"] !== 3) {
                                            if ($_SESSION["permission"] === 1) {
                                              echo "<a href='update_question.php?id=" . $row['question_id'] . "' title='Update Question' data-toggle='tooltip'><i class='fas fa-edit'></i></a>";
                                              echo "<a href='delete_question.php?id=" . $row['question_id'] . "' title='Delete Question' data-toggle='tooltip'><i class='fas fa-trash-alt'></i></a>";
                                            }
                                            echo "<a href='view_answers.php?id=" . $row['question_id'] . "' title='View Answers' data-toggle='tooltip' ><i class='fas fa-check-double'></i></a>";
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                    echo "</table>";

                                } else {
                                    echo "<p class='lead'><em>No records were found.</em></p>";;
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
                    <div class='pb-2 mt-4 mb-5 clearfix'>
                        <a href='index.php' class='btn btn-primary float-right'>Back</a>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</body>
</html>
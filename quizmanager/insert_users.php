<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$mysqli = new mysqli("localhost", "root", "", "quizmanager");
 
// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

//Define the user credentials
$edit_username = 'Edit';
$edit_password = 'letmeinedit';
$edit_permission = 1; //1 value = edit permissions
$view_username = 'View';
$view_password = 'letmeinview';
$view_permission = 2; //2 value = view permissions
$restricted_username = 'Restricted';
$restricted_password = 'letmeinrestricted';
$restricted_permission = 3; //3 value = restricted permissions

//Hash the passwords
$edit_password_hashed = password_hash($edit_password, PASSWORD_DEFAULT);
$view_password_hashed = password_hash($view_password, PASSWORD_DEFAULT);
$restricted_password_hashed = password_hash($restricted_password, PASSWORD_DEFAULT);
 
// Attempt insert query execution
$sql = "INSERT INTO Users (username, password, permission) VALUES
            ('$edit_username', '$edit_password_hashed', '$edit_permission'),
            ('$view_username', '$view_password_hashed', '$view_permission'),
            ('$restricted_username', '$restricted_password_hashed', '$restricted_permission')";
if($mysqli->query($sql) === true){
    echo "User Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
}
 
// Close connection
$mysqli->close();
?>
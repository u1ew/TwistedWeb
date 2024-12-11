<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to main page
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true){
    header("Location: ../index.php");
    exit;
}
 
// Include config file
require_once "connect.php";
 
// Define variables and initialize with empty values
$email = $password = "";
$emailErr = $passwordErr = $loginErr = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if email is empty
    if(empty(trim($_POST["loginEmail"]))){
        $emailErr = "Please enter email.";
    } else{
        $email = trim($_POST["loginEmail"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["loginPassword"]))){
        $passwordErr = "Please enter your password.";
    } else{
        $password = trim($_POST["loginPassword"]);
    }
    
    // Validate credentials
    if(empty($emailErr) && empty($passwordErr)){
        // Prepare a select statement
        $sql = "SELECT users.userID, users.email, users.firstname, users.lastname, users.password, users.isAdmin FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $paramEmail);
            
            // Set parameters
            $paramEmail = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $firstname, $lastname, $hashed_password, $admin);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            
                            // Store data in session variables
                            $_SESSION["loggedIn"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;    
                            $_SESSION["name"] = $firstname.$lastname;
                            $_SESSION["admin"] = $admin;           
                            
                            // Redirect user to main page
                            header("Location: ../index.php");
                            exit;
                        } else{
                            // Password is not valid, display a generic error message
                            $loginErr = "Invalid email or password.";
                        }
                    }
                } else{
                    // email doesn't exist, display a generic error message
                    $loginErr = "Invalid email or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>
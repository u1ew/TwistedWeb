<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to main page
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "scripts/connect.php";
 
// Define variables and initialize with empty values
$email = $password = "";
$emailErr = $passwordErr = $loginErr = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $emailErr = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $passwordErr = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($emailErr) && empty($passwordErr)){
        // Prepare a select statement
        $sql = "SELECT users.userID, users.email, users.firstname, users.lastname users.password users.isAdmin FROM users WHERE email = ?";
        
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
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedIn"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;    
                            $_SESSION["name"] = $firstname.$lastname;
                            $_SESSION["admin"] = $admin;           
                            
                            // Redirect user to main page
                            header("location: index.php");
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

    <div class="modal modal-sheet position-static d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="fw-bold mb-0 fs-2">Log in</h1>
                </div>

                <div class="modal-body p-5 pt-0">
                    <form class="">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control rounded-3" id="email" name="email" placeholder="name@example.com">
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Log in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
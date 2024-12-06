<?php
require_once "../TwistedWeb/scripts/connect.php";
 
// Define variables and initialize with empty values
$email = $firstname = $lastname = $password = $confirm_password = "";
$emailErr = $firstnameErr = $lastnameErr = $passwordErr = $confirmPasswordErr = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["email"]))){
        $username_err = "Please enter an email.";
    } elseif(!preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/i', trim($_POST["email"]))){
        $username_err = "Email is not in the correct format. e.g johndoe@example.com";
    } else{
        // Prepare a select statement
        $sql = "SELECT users.email FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $emailErr = "This email is already in use.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    if(empty((trim($_POST["firstname"])))){
        $firstnameErr = "Please enter a first name";
    } elseif(strlen(trim($_POST["firstname"])) > 23) {
        $firstnameErr = "Please input less than 23 characters";
    } else {
        $firstname = trim($_POST["firstname"]);
    }

    if(empty((trim($_POST["lastname"])))){
        $lastnameErr = "Please enter a first name";
    } elseif(strlen(trim($_POST["lastname"])) > 23) {
        $lastnameErr = "Please input less than 23 characters";
    } else {
        $lastname = trim($_POST["lastname"]);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $passwordErr = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6 && strlen(trim($_POST["password"])) > 49){
        $passwordErr = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirmPasswordErr = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($passwordErr) && ($password != $confirm_password)){
            $confirmPasswordErr = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_email, $param_password);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: loginPage.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <header class="p-3 mb-3 border-bottom">
        <div class="container" bis_skin_checked="1">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start" bis_skin_checked="1">
                <a href="index.php" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                    <img class="bi me-2" height="55px" role="img" aria-label="The Car Garage Company" src="/img/logo.PNG">
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="index.php" class="nav-link px-2 link-body-emphasis">Home</a></li>
                    <li><a href="#" class="nav-link px-2 link-body-emphasis">Book service</a></li>
                    <li><a href="#" class="nav-link px-2 link-body-emphasis">Inquire</a></li>
                    <li><a href="#" class="nav-link px-2 link-body-emphasis">Previous bookings</a></li>
                    <li><a href="#" class="nav-link px-2 link-body-emphasis">FAQs</a></li>
                    <li><a href="#" class="nav-link px-2 link-body-emphasis">About</a></li>
                </ul>

                <?php if (isset($_SESSION['loggedIn']) && $_SESSION['isAdmin'] === 0) { ?>
                    <div class="dropdown text-end" bis_skin_checked="1">
                        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="/img/profile.png" alt="mdo" width="32" height="32" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu text-small">
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Sign out</a></li>
                        </ul>
                    </div>
                <?php } elseif (isset($_SESSION['loggedIn']) && $_SESSION['isAdmin'] === 1) { ?>
                    <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                        <li>
                            <a href="#" class="nav-link text-secondary">
                                <svg class="bi d-block mx-auto mb-1" width="24" height="24">
                                    <use xlink:href="#home"></use>
                                </svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white">
                                <svg class="bi d-block mx-auto mb-1" width="24" height="24">
                                    <use xlink:href="#speedometer2"></use>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white">
                                <svg class="bi d-block mx-auto mb-1" width="24" height="24">
                                    <use xlink:href="#table"></use>
                                </svg>
                                Orders
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white">
                                <svg class="bi d-block mx-auto mb-1" width="24" height="24">
                                    <use xlink:href="#grid"></use>
                                </svg>
                                Products
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white">
                                <svg class="bi d-block mx-auto mb-1" width="24" height="24">
                                    <use xlink:href="#people-circle"></use>
                                </svg>
                                Customers
                            </a>
                        </li>
                    </ul>
                <?php } else { ?>
                    <div class='text-end' bis_skin_checked='1'>
                        <a style="text-decoration: none;" href="loginPage.php">
                            <button type="button" class="btn btn-outline-dark me-2">Login</button>
                        </a>
                        <button disabled type="button" class="btn btn-warning">Sign-up</button>
                    </div>
                <?php } ?>

            </div>
        </div>
    </header>

    <div class="modal modal-sheet position-static d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="fw-bold mb-0 fs-2">Sign up for free</h1>
                </div>

                <div class="modal-body p-5 pt-0">
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control rounded-3 <?php echo (!empty($emailErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" id="email" placeholder="name@example.com">
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3 <?php echo (!empty($firstnameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>" id="firstname" placeholder="John">
                            <label for="firstname">First Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3 <?php echo (!empty($lastnameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>" id="lastname" placeholder="Doe">
                            <label for="lastname">Last Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3 <?php echo (!empty($passwordErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" id="password" placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3" id="confirm_password" placeholder="Confirm Password">
                            <label for="confirm_password">Confirm Password</label>
                        </div>
                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Sign up</button>
                        <small class="text-body-secondary">By clicking Sign up, you agree to the terms of use.</small>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
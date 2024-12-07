<?php
require_once "../TwistedWeb/scripts/connect.php";

// Check if the user is already logged in, if yes then redirect him to main page
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true){
    header("location: index.php");
    exit;
}

// Define variables and initialize with empty values
$email = $firstname = $lastname = $password = $confirm_password = "";
$emailErr = $firstnameErr = $lastnameErr = $passwordErr = $confirmPasswordErr = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["email"]))) {
        $username_err = "Please enter an email.";
    } elseif (!preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/i', trim($_POST["email"]))) {
        $username_err = "Email is not in the correct format. e.g johndoe@example.com";
    } else {
        // Prepare a select statement
        $sql = "SELECT users.email FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $paramEmail);

            // Set parameters
            $paramEmail = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $emailErr = "This email is already in use.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    if (empty((trim($_POST["firstname"])))) {
        $firstnameErr = "Please enter a first name";
    } elseif (strlen(trim($_POST["firstname"])) > 23) {
        $firstnameErr = "Please keep firstname less than 50 characters";
    } else {
        $firstname = trim($_POST["firstname"]);
    }

    if (empty((trim($_POST["lastname"])))) {
        $lastnameErr = "Please enter a first name";
    } elseif (strlen(trim($_POST["lastname"])) > 23) {
        $lastnameErr = "Keep lastname less than 50 characters";
    } else {

        $lastname = trim($_POST["lastname"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $passwordErr = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6 && strlen(trim($_POST["password"])) > 49) {
        $passwordErr = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirmPasswordErr = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($passwordErr) && ($password != $confirm_password)) {
            $confirmPasswordErr = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $paramFirstname, $paramLastname, $paramEmail, $paramPassword);

            // Set parameters
            $paramFirstname = $firstname;
            $paramLastname = $lastname;
            $paramEmail = $email;
            $paramPassword = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: loginPage.php");
            } else {
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
    <title>Sign-up Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

    <div class="modal modal-sheet position-static d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="fw-bold mb-0 fs-2">Sign up for free</h1>
                </div>

                <div class="modal-body p-5 pt-0">

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control rounded-3 <?php echo (!empty($emailErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" id="email" name="email" placeholder="name@example.com">
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3 <?php echo (!empty($firstnameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>" id="firstname" name="firstname" placeholder="John">
                            <label for="firstname">First Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3 <?php echo (!empty($lastnameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>" id="lastname" name="lastname" placeholder="Doe">
                            <label for="lastname">Last Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3 <?php echo (!empty($passwordErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" id="password" name="password" placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3 <?php echo (!empty($confirmPasswordErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
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
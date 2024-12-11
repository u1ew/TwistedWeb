<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "connect.php";

// Check if the user is already logged in, if yes then redirect him to main page
if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
    header("Location: ../index.php");
    exit;
}

// Define variables and initialize with empty values
$email = $firstname = $lastname = $password = $confirm_password = "";
$emailErr = $firstnameErr = $lastnameErr = $passwordErr = $confirmPasswordErr = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["signupEmail"]))) {
        $username_err = "Please enter an email.";
    } elseif (!preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/i', trim($_POST["signupEmail"]))) {
        $username_err = "Email is not in the correct format. e.g johndoe@example.com";
    } else {
        // Prepare a select statement
        $sql = "SELECT users.email FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $paramEmail);

            // Set parameters
            $paramEmail = trim($_POST["signupEmail"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $emailErr = "This email is already in use.";
                } else {
                    $email = trim($_POST["signupEmail"]);
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
    if (empty(trim($_POST["signupPassword"]))) {
        $passwordErr = "Please enter a password.";
    } elseif (strlen(trim($_POST["signupPassword"])) < 6 && strlen(trim($_POST["signupPassword"])) > 49) {
        $passwordErr = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["signupPassword"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirmPassword"]))) {
        $confirmPasswordErr = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirmPassword"]);
        if (empty($passwordErr) && ($password != $confirm_password)) {
            $confirmPasswordErr = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (firstname, lastname, email, password, isAdmin) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssi", $paramFirstname, $paramLastname, $paramEmail, $paramPassword, $paramAdmin);

            // Set parameters
            $paramFirstname = $firstname;
            $paramLastname = $lastname;
            $paramEmail = $email;
            $paramPassword = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $paramAdmin = 0;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../index.php"); // Redirect to login page
                exit;
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

<?php
function loggedInCheck()
{
    session_start();
    // Check if the user is already logged in, if yes then redirect him to main page
    if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
        header("Location: ../index.php");
        exit;
    }
}
?>
<?php
//Index
//Archie Mason

//Include the connection file so we have access to the database
include '../BackEnd/shared.php';

//Start a session to allow us to keep track of user data
session_start();

//Check if the user is already logged in.
//If they are, they should be sent back to the home screen.
if(isset($_COOKIE["UserLoggedIn"])){
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css?version=6">
    <title>Fishy's and Chippy's | Staff Login</title>
</head>
<body>
    <div class="loginFormContainer">
        <div class="loginImage"><img src="Images/fish.png" alt="logo" width="200" height="92"></div>
        <h2 class="loginSubTitle">Staff Login</h2>
        <?php
            //Check for errors in the URL and display a message
            if(isset($_GET["error"])){
                if($_GET["error"] == "empty"){
                    msg("StaffID field is empty!", false);
                } elseif($_GET["error"] == "notFound"){
                    msg("StaffID was not recognised!", false);
                } elseif($_GET["error"] == "unauthorised"){
                    msg("You don't have access to this page!", false);
                }
            } elseif(isset($_GET["logout"])){
                if($_GET["logout"] == "success"){
                    msg("Successfully logged out.", true);
                }
            }
        ?>
        <form class="loginForm" action="../BackEnd/handlelogin.php" method="post" name="StaffLoginForm">
            <input class="loginField" type="password" placeholder="Staff ID" name="StaffID">
            <button class="button" type="submit" name="Login-Submit">Submit</button>
        </form>
    </div>
</body>
</html>
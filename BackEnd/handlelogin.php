<?php
//Handle Login
//Archie Mason

//Include the connection file so we have access to the database
include 'shared.php';
//Check that the Login-Submit button was pressed
if(isset($_POST["Login-Submit"])){
    //Checks that the user entered the page correctly
    //Next, validate the user's login entry
    $UserEntry = $_POST["StaffID"]; 
    if(empty($UserEntry)){
        //If the login is empty, send the user back
        header("Location: ../FrontEnd/index.php?error=empty");
        exit();
    } else {
        //It's not empty, so check that the staffID exists
        $result = query("SELECT * FROM Staff WHERE StaffID = '$UserEntry'", $conn, false, false);
        $rows = $result["rows"];
        $row = $result["data"];
        if($rows == 0){
            //There's no match for this StaffID, so send them back to the login
            header("Location: ../FrontEnd/index.php?error=notFound");
            exit();
        } else {
            //There's a match, so start a session, store variables and send user to home page
            session_start();
            setcookie("UserLoggedIn", "true", time() + 3600, "/");
            $_SESSION["StaffID"] = $UserEntry;
            $_SESSION["Name"] = $row["Forename"];
            $_SESSION["Surname"] = $row["Surname"];
            $_SESSION["Permission"] = $row["EmployeeType"];
            header("Location: ../FrontEnd/home.php");
            exit();
        }
    }
} else {
    //The user did not enter this page correctly, so send them back to login
    header("Location: ../FrontEnd/index.php?error=unauthorised");
    exit();
}


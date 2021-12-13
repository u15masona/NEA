<?php
//Addlocation.php
//This file handles the inputs from the managers section on the Locations page,
//and adds a new location  into the locations table

//Archie Mason

//Start the session to gain access to session variables
session_start();

//Include the shared file
include 'shared.php';

//Index the users staffID from session data
$StaffID = $_SESSION["StaffID"];

if(isset($_POST["add-location"])){
    
    //Fetch the data provided
    $md = $_POST["md"]; //Manager ID
    $al1 = $_POST["al1"]; //address line 1
    $al2 = $_POST["al2"]; //address line 2 (can be empty as )
    $tn = $_POST["tn"]; //town
    $pc = $_POST["pc"]; //postcode

    //Validate - check if any variables are empty
    if(empty($al1) || empty($tn) || empty($pc) || empty($md)){
        header("Location: ../FrontEnd/locations.php?msg=Fill in all fields!&error");
        exit();
    } else {
        //Insert the new location

        $sql = query("INSERT INTO Locations(ManagerID, AddressLine1, AddressLine2, Town, Postcode) VALUES ('$md', '$al1', '$al2', '$tn', '$pc')", $conn, false, true);

        header("Location: ../FrontEnd/locations.php?msg=Successfully added new location!");
        exit();
    }
} else {
    header("Location: ../FrontEnd/index.php");
    exit();
}

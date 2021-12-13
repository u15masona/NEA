<?php
//Delete a shift from the rota
//Archie Mason

//Start the session to gain access to session variables
session_start();

//Include the shared file
include 'shared.php';

//Check Permissions
if($_SESSION["Permission"] == "Manager" || $_SESSION["Permission"] == "Owner"){
    //User has permission
    //Check that all parameters are passed
    if(isset($_GET["id"]) and isset($_GET["date"])){
        //User has permissions, and an ID was passed. 
        //Check the existance of a rota with the ID and Date provided
        $id = $_GET["id"];
        $date = $_GET["date"];

        $sql = query("SELECT * FROM Rota WHERE StaffID = '$id' AND ShiftDate = '$date'", $conn, false, false);
        $rows = $sql["rows"];

        if($rows == 0){
            //There is no shift with this ID or Date
            header("Location: ../FrontEnd/rota.php?msg=This shift doesn't exist?&error");
            exit();
        } else {
            //Shift is valid, delete it

            $sql = query("DELETE FROM Rota WHERE StaffID = '$id' AND ShiftDate = '$date'", $conn, false, true);
            //Deleted successfully
            header("Location: ../FrontEnd/rota.php?msg=Shift successfully deleted!");
            exit();
        }
    } else {
        header("Location: ../FrontEnd/rota.php?msg=No ID fields. Try again?&error");
        exit();
    }
} else {
    //No permission, send them back to the main screen
    header("Location: ../FrontEnd/home.php?msg=You are not allowed to visit that page!&error");
    exit();
}


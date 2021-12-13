<?php
//Addshift.php
//This file handles the inputs from the managers section on the Rota page,
//and adds a shift into the Rota table

//Archie Mason

//Start the session to gain access to session variables
session_start();

//Include the shared file
include 'shared.php';

//Index the users staffID from session data
$StaffID = $_SESSION["StaffID"];

if(isset($_POST["add-shift"])){
    
    //Fetch the data provided
    $StaffToAdd = $_POST["StaffID"];
    $Date = $_POST["date"];
    $Start = $_POST["start"];
    $End = $_POST["end"];

    //Validate - check if any variables are empty
    if(empty($StaffToAdd) || empty($Date) || empty($Start) || empty($End)){
        header("Location: ../FrontEnd/rota.php?msg=Fill in all fields!&error");
        exit();
    } else {
        //Check the dates are valid
        $sql = query("SELECT * FROM Staff WHERE StaffID = '$StaffToAdd'", $conn, false, false);
        $rows = $sql["rows"];
        $data = $sql["data"];

        if($rows < 1){
            header("Location: ../FrontEnd/rota.php?msg=This staff member does not exist!&error");
            exit();
        } else {
            $DateCheck = dateValidate($Date);
            $TimeCheck1 = timeValidate($Start);
            $TimeCheck2 = timeValidate($End);
    
            if($DateCheck == true) {
                if($TimeCheck1 == true){
                    if($TimeCheck2 == true) {

                        //All data is valid, so proceed with inserting shift into database
                        //First check to make sure this staff member doesn't already have a shift on that day
                        $sql = query("SELECT * FROM Rota WHERE StaffID = '$StaffToAdd' AND ShiftDate = '$Date'", $conn, false, false);
                        $rows = $sql["rows"];

                        if($rows > 0){
                            //This staff member has a shift on this day already, as there are more than 0 rows returned
                            header("Location: ../FrontEnd/rota.php?msg=" . $data["Forename"] . " already has a shift on $Date!&error");
                            exit();
                        } else {
                            //Everything is all good, time to insert into the database
                            $BaseLocation = $data["BaseLocationID"];
                            $sql = query("INSERT INTO Rota (StaffID, ShiftDate, LocationID, StartTime, EndTime, ClockedIn, ClockedOut, Earning) VALUES ('$StaffToAdd', '$Date', '$BaseLocation', '$Start', '$End', '-', '-', '0')", $conn, false, true);
                            header("Location: ../FrontEnd/rota.php?msg=Shift successfully added!");
                            exit();
                        }
                    } else {
                        header("Location: ../FrontEnd/rota.php?msg=End Time is invalid!&error");
                    }
                } else {
                    header("Location: ../FrontEnd/rota.php?msg=Start Time is invalid!&error");
                    exit();
                }
            } else {
                header("Location: ../FrontEnd/rota.php?msg=Date is invalid!&error");
                exit();
            }
        }
    }
} else {
    header("Location: ../FrontEnd/index.php");
    exit();
}

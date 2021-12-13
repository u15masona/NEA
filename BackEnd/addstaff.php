<?php
//Addstaff.php
//This file handles the inputs from the managers section on the Staff page,
//and adds a new staff member into the Staff table

//Archie Mason

//Start the session to gain access to session variables
session_start();

//Include the shared file
include 'shared.php';

//Index the users staffID from session data
$StaffID = $_SESSION["StaffID"];

if(isset($_POST["add-staff"])){
    
    //Fetch the data provided
    $fn = $_POST["fn"]; //Forename
    $sn = $_POST["sn"]; //Surname
    $dob = $_POST["dob"]; //Date of birth
    $al1 = $_POST["al1"]; //address line 1
    $al2 = $_POST["al2"]; //address line 2 (can be empty as )
    $tn = $_POST["tn"]; //town
    $pc = $_POST["pc"]; //postcode
    $pn = $_POST["pn"]; //phone number
    $bl = intval($_POST["bl"]); //base location id
    $et = $_POST["et"]; //employee type

    //Validate - check if any variables are empty
    if(empty($fn) || empty($sn) || empty($dob) || empty($al1) || empty($tn) || empty($pc) || empty($pn) || empty($bl) || empty($et)){
        header("Location: ../FrontEnd/staff.php?msg=Fill in all fields!&error");
        exit();
    } else {
        //Validate the DOB entry
        $DateCheck = dateValidate($dob);

        if($DateCheck == true){
            //Date is valid, so continue inserting new staff.
            
            //Check that the base location is a real location
            $sql = query("SELECT * FROM Locations WHERE LocationID = '$bl'", $conn, false, false);
            $rows = $sql["rows"];

            if($rows > 0){
                //This location exists
                //Check the employee type
                $sql = query("SELECT * FROM EmployeeType WHERE EmployeeType = '$et'", $conn, false, false);
                $rows = $sql["rows"];
                if($rows > 0){
                    //This is a valid employee type
                    //Insert the new employee

                    $sql = query("INSERT INTO Staff(Forename, Surname, DoB, AddressLine1, AddressLine2, Town, Postcode, PhoneNumber, BaseLocationID, Wage, EmployeeType) VALUES ('$fn', '$sn', '$dob', '$al1', '$al2', '$tn', '$pc', '$pn', '$bl', '0.00', '$et')", $conn, false, true);

                    //Fetch StaffID from the newly inserted field to calculate wage
                    $StaffID = $sql["StaffID"];
                    echo($StaffID);
                    exit();

                } else {
                    //This is not a valid employee type
                    header("Location: ../FrontEnd/staff.php?msg=".$et." is not a valid employee type!&error");
                    exit(); 
                }
            } else {
                //This location does not exist
                header("Location: ../FrontEnd/staff.php?msg=The BaseLocationID is invalid!&error");
                exit();
            }
        } else {
            //Date is not valid
            header("Location: ../FrontEnd/staff.php?msg=The birthdate is not a valid date!&error");
            exit();
        }
    }
} else {
    header("Location: ../FrontEnd/index.php");
    exit();
}

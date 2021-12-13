<?php
//handleshift.php
//Start/End the users shift when the button is pressed
//Archie Mason

//Start the session to gain access to session variables
session_start();

//Include the shared file
include 'shared.php';

//Index the users staffID from session data
$StaffID = $_SESSION["StaffID"];

//Create some variables that will be used throught the time of this script being ran
$Date = date("d/m/Y"); //The Date
$TimeUTC = Date("H:i"); //The time in UTC time
$Hour = intval(str_split($TimeUTC,2)[0]) - 1; //The hour in english time
$Minutes = Date("i"); //The minutes of the hour
$Time = $Hour . ":" . $Minutes; //The full time in english time

//Check which button was pressed
if(isset($_POST["start-shift"])) {
    $sql = query("SELECT * FROM Rota Where StaffID = '$StaffID' AND ShiftDate = '$Date'", $conn, false, false) or die(mysqli_error($conn));
    $Data = $sql["data"];

    if($Data["Earning"] != 0){
        header("Location: ../FrontEnd/home.php?msg=You've already worked today!");
        exit();   
    }

    //Check it's the right time to start
    $StartTime = $Data["StartTime"];
    $StartHour = intval(str_split($StartTime,2)[0]);
    if($Hour != $StartHour){
        //It's not time to start the shfit yet, so don't
        header("Location: ../FrontEnd/home.php?msg=Can't start yet! Try again at ". $StartTime ."!&error");
        exit();
    } else {
        //The employee can start the shift
           
        //first check to make sure it's not already been started
        if($Data["ClockedIn"] != "-"){
            header("Location: ../FrontEnd/home.php?msg=Shift has already been started?&error");
            exit();
        } else {
            //The shift isn't started, so update the Started field to the current time
            $sql = query("UPDATE Rota SET ClockedIn = '$Time' WHERE StaffID = '$StaffID' AND ShiftDate = '$Date'", $conn, false, true);
            //Send user back to main screen
            header("Location: ../FrontEnd/home.php?msg=You have started your shift!");
            exit();           
        }
    }
} elseif(isset($_POST["end-shift"])) {

    //Make necessary queries to the rota
    query("UPDATE Rota SET ClockedOut = '$Time' WHERE StaffID = '$StaffID' AND ShiftDate = '$Date'", $conn, false, true);
    $RotaInformation = query("SELECT * FROM Rota Where StaffID = '$StaffID' AND ShiftDate = '$Date'", $conn, false, false) or die(mysqli_error($conn));
    $RotaData = $RotaInformation["data"];

    //Fetch user information
    $UserSQL = query("SELECT * FROM Staff WHERE StaffID = '$StaffID'", $conn, false, false);
    $UserData = $UserSQL["data"];
    $age = getAge($UserData["DoB"]);
    $wage = getWage($StaffID, $age, $conn);

    //Calculate the earning for this shift
    $duration = getTimePassed($RotaData["ClockedIn"], $Time)["hours"];
    $earning = $duration * $wage;

    //Set the data on the rota
    query("UPDATE Rota SET Earning = '$earning' WHERE StaffID = '$StaffID' AND ShiftDate = '$Date'", $conn, false, true);

    header("Location: ../FrontEnd/home.php?msg=You have ended your shift!");
    exit();

} else {
    header("Location: ../FrontEnd/home.php?msg=Couldn't process shift&error=1");
    exit();
}
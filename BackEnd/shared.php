<?php
//Shared.php
//Provide useful global functions/variables to all files in the system
//Archie Mason

//MySQL Configuration
$Servername = "localhost";
$Username = "System";
$Password = "KK1hJx2-.w5vE2XY";
$Database = "Fishy";

//Create the connection variable for the MySQL database
$conn = mysqli_connect($Servername, $Username, $Password, $Database);

//Check to see if there was an error whilst connecting
if(mysqli_connect_error()){
    die("Can't connect! Error: " . mysqli_connect_error());
}

//------------------------\\
//Functions

//msg - to easily provide a message on front end files
function msg($message, $showAsBlue){
    if($showAsBlue == true) {
        echo('<p class="loginInfo">' . $message . '</p>');
    } elseif($showAsBlue == false || $showAsBlue == null) {
        echo('<p class="loginAlert">' . $message . '</p>');
    } else {
        echo('<p class="loginAlert">' . $message . '</p>');
    }
}

//query - run a mysqli query, and then return the result as an array
function query($SQL, $conn, $noAssoc, $ddl){
    //First, query the SQL passed into the function
    $result = mysqli_query($conn, $SQL);

    if($ddl == true) {
        //A DDL query is being ran, so we handle it differently
        if($result == false || $result == null) {
            //Exit the program as an error has occured, and display the error
            die("Query Error! " . mysqli_error($conn));
        } else {
            //Return true as the query ran successfully
            return true;
        }
    } else {
        //A DDL query is not being ran
        $data = array();
        $rows = 0;

        //If an associative array needs to be returned, this executes
        if($noAssoc == false){
            $data = mysqli_fetch_assoc($result);
            $rows = mysqli_num_rows($result);
        } elseif($noAssoc == true) {
            //No associative array needs to be returned, so $data remains false
            $data = false;
            $rows = mysqli_num_rows($result);
        }

        //Return the SQL data necessary
        return array(
            "rows"   => $rows,
            "data"   => $data,
            "mysqli" => $result,
            "query"  => $SQL,
        );
    }
}

//Calculate the date of birth from a date
function getAge($DOB){
    $Difference = date_diff(date_create_from_format('d/m/Y', $DOB), date_create_from_format('d/m/Y', date("d/m/Y"))); //Calculate the difference
    return $Difference->format("%y");  //Return the difference formattted in years
}

//Calculate the ammount of time passed between the time provided and now
function getTimePassed($Start, $End){
    $StartArray = array_map('intval', explode(':', $Start)); //Split the Start time string into it's HH and MM components
    $StartTime = mktime($StartArray[0] + 1, $StartArray[1], 0, date("m"), date("d"), date("Y")); //Turn these components into a PHP time object

    $EndArray = array_map('intval', explode(':', $End)); //Same again, but with the end time
    $EndTime = mktime($EndArray[0] + 1, $EndArray[1], 0, date("m"), date("d"), date("Y")); //Creating the end time into a PHP time object

    $inSeconds = $EndTime - $StartTime; //Calculate the number of seconds between the two times
    $inMinutes = ($inSeconds / 60); //Divide the seconds by 60 to get the number of minutes
    $inHours = ($inMinutes / 60); //Divide the number of minutes by 60 to get the number of hours
    
    return array(
        "mins" => $inMinutes, 
        "hours" => $inHours,
        "seconds" => $inSeconds,
    ); //Return an array providing all 3 results
}

//Calculate a wage based on a users information
function getWage($StaffID, $Age, $conn){
    //Get information
    $Wage = 0; //Decalre variable
    $StaffData = mysqli_query($conn, "SELECT * FROM Staff WHERE StaffID = '$StaffID'") or die(mysqli_error($conn)); //Fetch information about the staff member provided from the staff table
    $UserData = mysqli_fetch_assoc($StaffData); //Fetch this data as an array
    $EmployeeSQL = mysqli_query($conn, "SELECT * FROM EmployeeType WHERE EmployeeType = '" . $UserData["EmployeeType"] . "'"); //

    while($row = mysqli_fetch_assoc($EmployeeSQL)) {
        if($row["Age"] == $Age) {
            $Wage = $row["Wage"];
        }
    }

    if($Wage == 0){
        $EmployeeSQL = mysqli_query($conn, "SELECT * FROM EmployeeType WHERE EmployeeType = '" . $UserData["EmployeeType"] . "' AND Age = '21'");
        if(mysqli_num_rows($EmployeeSQL) > 0){
            $data = mysqli_fetch_assoc($EmployeeSQL);
            $Wage = $data["Wage"];
        } else {
            $Wage = 8.61;
        }
    }

    return $Wage;
}

//Check if the string provided is a valid date
function dateValidate($Date){
    $Array = array_map('intval', explode('/', $Date));
    if($Array == null) {
        return false;
    } else {
        if ($Array[0] > 0 and $Array[0] < 32){
            if($Array[1] > 0 and $Array[1] < 13){
                if($Array[2] > 1920 and $Array[2] < 3000) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

//Check if the string provided is a valid time
function timeValidate($Time){
    $Array = array_map('intval', explode(':', $Time));
    if($Array == null){
        return false;
    } else {
        if($Array[0] >= 0 and $Array[0] < 24){
            if($Array[1] >= 0 and $Array[1] < 60){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
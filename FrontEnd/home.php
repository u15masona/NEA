<?php
//Main Staff Homescreen
//Archie Mason

//Start session, and check user has access
session_start();
//If they don't, they should be sent back to the login screen.
if(!isset($_COOKIE["UserLoggedIn"])){
    header("Location: index.php?error=unauthorised");
}

//Include the connection file
include '../BackEnd/shared.php';

//The user is logged in, so we can continue displaying information to the user.
//Next, we need to run PHP code to get the information that will be displayed on the dashboard

//Check if the user is working today
$UserIsWorking = false;
$UserHasWorked = false;
$Title = $_SESSION["Name"] . ", you're not working today.";
$StaffID = $_SESSION["StaffID"];
$Date = date("d/m/Y");
$result = query("SELECT * FROM Rota Where StaffID = '$StaffID' AND ShiftDate = '$Date'", $conn, true, false);
$result2 = query("SELECT * FROM Rota Where StaffID = '$StaffID' AND ShiftDate = '$Date'", $conn, false, false)["data"];

$user = query("SELECT * FROM Staff WHERE StaffID = '$StaffID'", $conn, false, false)["data"];
$rows = $result["rows"];

$age = getAge($user["DoB"]);
$wage = getWage($StaffID, $age, $conn);


if($rows > 0){
    $UserIsWorking = true;
    $Title = $_SESSION["Name"] . ", you're working today.";
}

if(intval($result2["Earning"]) != 0.00){
    $UserHasWorked = true;
    $UserIsOnShift = false;
    $UserIsWorking = false;
    $Title = $_SESSION["Name"] . ", you've completed your shift for today!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css?version=12">
    <title>Fishy's and Chippy's | Staff Homepage</title>
</head>
<body>
    <!--Navigation Bar><!-->
    <?php include 'Shared/navbar.php'; ?>
    
    <!--Main Content><!-->
    <?php 
        if(isset($_GET["msg"])){
            $showAsBlue = true;
            if(isset($_GET["error"])){
                $showAsBlue = false;
            }
            msg($_GET["msg"], $showAsBlue);
        }
    ?>
    <h1 class="homeTitle"><?php echo($Title);?></h1>

    <?php
        //Next, we want to create a table that shows the user what shift they are working, but ONLY if they are working
        if($UserIsWorking == true || $UserHasWorked == true){
            //Only create the table if they are working
            echo('<div class="workingTableHolder">');
            echo('
            <table class="dashboardWorkingTable">
                <thead>
                    <tr>
                        <th class="workingTableHeader">Date</th>
                        <th class="workingTableHeader">Start</th>
                        <th class="workingTableHeader">End</th>
                        <th class="workingTableHeader">Started</th>
                        <th class="workingTableHeader">Ended</th>
                        <th class="workingTableHeader">Earning</th>
                    </tr>
                </thead>
                <tbody>
            ');
            //Next, fetch the data and display it in the table
            while($row = mysqli_fetch_assoc($result["mysqli"])){
                echo("<tr>");
                    echo('<td class="workingTableText">');
                    echo($row["ShiftDate"]);
                    echo("</td>");
                    echo('<td class="workingTableText">');
                    echo($row["StartTime"]);
                    echo("</td>");
                    echo('<td class="workingTableText">');
                    echo($row["EndTime"]);
                    echo("</td>");
                    echo('<td class="workingTableText">');
                    echo($row["ClockedIn"]);
                    echo("</td>");
                    echo('<td class="workingTableText">');
                    echo($row["ClockedOut"]);
                    echo("</td>");
                    echo('<td class="workingTableText">');
                    echo($row["Earning"]);
                    echo("</td>");
                echo("</tr>");

                if($row["ClockedIn"] != "-" and $row["ClockedOut"] == "-" and $row["Earning"] == 0){
                    $UserIsOnShift = true;
                } 
            }
            //Close the table tags
            echo("
                    </tbody>
                </table>
            </div>
            ");

            //Finally, we want to create a button that allows the user to start/stop the shift
            if($UserHasWorked == false) {
                if($UserIsOnShift == false){
                    echo('<form action="../BackEnd/handleshift.php" method="post"><button class="button" type="submit" name="start-shift">Start Shift</button></form>');
                } else {
                    echo('<form action="../BackEnd/handleshift.php" method="post"><button class="button" type="submit" name="end-shift">End Shift</button></form>');
                }
            }
        }
    ?>

    <hr width="100%" value="black">
    <h1 class="homeTitle">About You</h1>
    <ul class="homeAboutListHolder">
        <li class="homeAboutList"><p class="info"><strong>Name:</strong> <?php echo($_SESSION["Name"] . " " . $_SESSION["Surname"])?></p></li>
        <li class="homeAboutList"><p class="info"><strong>Role:</strong> <?php echo($_SESSION["Permission"])?></p></li>
        <li class="homeAboutList"><p class="info"><strong>Rate:</strong> <?php echo("£".$wage . "/hour")?></p></li>
    </ul>
</body>
</html>
<?php
//Rota Screen
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

//Fetch the rota 
//Fetch the date either from the filter parameter in the URL, or just use today's
$date = "";
if(isset($_GET["date_filter"])){
    $date = $_GET["date_filter"];
    if(dateValidate($date) == false){
        $date = date("d/m/Y");
    }
} else {
    $date = date("d/m/Y");
}

$result = query("SELECT * FROM Rota WHERE ShiftDate = '$date' ORDER BY StartTime ASC", $conn, true, false)["mysqli"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css?version=12">
    <title>Fishy's and Chippy's | Staff Rota</title>
</head>
<body>
    <!--Navigation Bar><!-->
    <?php include 'Shared/navbar.php'; ?>


    <!--Main Content><!-->
    <?php 
        //Check for a message
        if(isset($_GET["msg"])){
            $showAsBlue = true;
            if(isset($_GET["error"])){
                $showAsBlue = false;
            }
            msg($_GET["msg"], $showAsBlue);
        }
    ?>
    <h1 class="homeTitle">Staff Rota - <?php echo $date?></h1>
    <!--Filter form><!-->

    <?php
        //Next, display the rota
        //Only create the table if they are working
        echo('<div class="workingTableHolder">');
        if($_SESSION["Permission"] == "Manager" || $_SESSION["Permission"] == "Owner"){
            echo('
            <table class="dashboardWorkingTable">
                <thead>
                    <tr>
                        <th class="workingTableHeader">Forename</th>
                        <th class="workingTableHeader">Surname</th>
                        <th class="workingTableHeader">Date</th>
                        <th class="workingTableHeader">Start</th>
                        <th class="workingTableHeader">End</th>
                        <th class="workingTableHeader">Role</th>
                        <th class="workingTableHeader">Action</th>
                    </tr>
                </thead>
                <tbody>
            ');
        } else {
            echo('
            <table class="dashboardWorkingTable">
                <thead>
                    <tr>
                        <th class="workingTableHeader">Forename</th>
                        <th class="workingTableHeader">Surname</th>
                        <th class="workingTableHeader">Date</th>
                        <th class="workingTableHeader">Start</th>
                        <th class="workingTableHeader">End</th>
                        <th class="workingTableHeader">Role</th>
                    </tr>
                </thead>
                <tbody>
            ');
        }
        //Next, fetch the data and display it in the table
        while($row = mysqli_fetch_assoc($result)){
            $StaffID = $row["StaffID"];
            $Data = query("SELECT StaffID, Forename, Surname, EmployeeType FROM Staff WHERE StaffID = '$StaffID'",$conn, false, false)["data"];
            $Forename = $Data["Forename"];
            $Surname = $Data["Surname"];
            $EmployeeType = $Data["EmployeeType"];
            $Date = $row["ShiftDate"];
            echo("<tr>");
                echo('<td class="workingTableText">');
                echo($Forename);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($Surname);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($Date);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["StartTime"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["EndTime"]);
                echo('</td>');
                echo('<td class="workingTableText">');
                echo($EmployeeType);
                echo('</td>');
                if($Data["StaffID"] == $_SESSION["StaffID"]){
                    echo("</strong>");
                }
                if($_SESSION["Permission"] == "Manager" || $_SESSION["Permission"] == "Owner"){
                    echo('<td class="workingTableText"><a href="../BackEnd/deleteshift.php?id='.$StaffID.'&date='.$Date.'">Delete</a></td>');
                }
                echo("</td>");
            echo("</tr>");
        }
        echo("
                </tbody>
            </table>
        ");
        echo('</div>');
    ?>

    <hr width="100%" value="black">
    <h1 class="homeTitle">Date Filter</h1>
    <ul class="homeAboutListHolder">
        <form action="../BackEnd/filtershifts.php" method="post">
            <li class="homeAboutList"><input class="loginField" type="text" placeholder="Show shifts on date..." name="date"></li>
            <li class="homeAboutList"><button class="button" type="submit" name="add-shift">Filter Shifts</button></li>
        </form>
    </ul>


    <?php 
        if($_SESSION["Permission"] == "Manager" || $_SESSION["Permission"] == "Owner"){
            echo('
            <hr width="100%" value="black">
            <h1 class="homeTitle">Add Shift</h1>
            <ul class="homeAboutListHolder">
                <form action="../BackEnd/addshift.php" method="post">
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Staff ID" name="StaffID"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Date" name="date"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Start" name="start"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="End" name="end"></li>
                    <li class="homeAboutList"><button class="button" type="submit" name="add-shift">Add Shift</button></li>
                </form>
            </ul>
            ');
        }
    ?>
    <hr width="100%" value="black">
    <h1 class="homeTitle">About You</h1>
    <ul class="homeAboutListHolder">
        <li class="homeAboutList"><p class="info">Name: <?php echo($_SESSION["Name"] . " " . $_SESSION["Surname"])?></p></li>
        <li class="homeAboutList"><p class="info">Role: <?php echo($_SESSION["Permission"])?></p></li>
        <li class="homeAboutList"><p class="info">Rate: <?php echo($_SESSION["Wage"])?></p></li>
    </ul>

        
</body>
</html>
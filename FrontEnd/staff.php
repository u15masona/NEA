<?php
//Staff Screen
//Archie Mason

//Start session, and check user has access
session_start();
//If they don't, they should be sent back to the login screen.
if(!isset($_COOKIE["UserLoggedIn"])){
    header("Location: index.php?error=unauthorised");
}

//Only managers should be able to access this screen
$granted = false;

if($_SESSION["Permission"] == "Manager" || $_SESSION["Permission"] == "Owner"){
    $granted = true;
}

if($granted == false){
    header("Location: home.php?msg=You do not have permissions&error");
    exit();
}



//Include the connection file
include '../BackEnd/shared.php';

//The user is logged in, so we can continue displaying information to the user.

//Fetch the staff table 
$result = query("SELECT * FROM Staff ORDER BY StaffID ASC", $conn, true, false)["mysqli"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css?version=12">
    <title>Fishy's and Chippy's | Staff Information</title>
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
    <h1 class="homeTitle">Staff Information</h1>

    <div class="workingTableHolder">
        <table class="dashboardWorkingTable">
            <thead>
                <tr>
                    <th class="workingTableHeader">Forename</th>
                    <th class="workingTableHeader">Surname</th>
                    <th class="workingTableHeader">Date of Birth</th>
                    <th class="workingTableHeader">Address</th>
                    <th class="workingTableHeader">Telephone</th>
                    <th class="workingTableHeader">Base Location</th>
                    <th class="workingTableHeader">Role</th>
                </tr>
            </thead>
        <tbody>

    <?php
        while($row = mysqli_fetch_assoc($result)){
            echo("<tr>");
                echo('<td class="workingTableText">');
                echo($row["Forename"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["Surname"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["DoB"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["AddressLine1"] . " " . $row["AddressLine2"] . " " . $row["Town"] . " " . $row["Postcode"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["PhoneNumber"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["BaseLocationID"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["EmployeeType"]);
                echo("</td>");
            echo("</tr>");
        }
        echo("
                </tbody>
            </table>
        ");
        echo('</div>');
    ?>

    <?php 
        if($_SESSION["Permission"] == "Manager" || $_SESSION["Permission"] == "Owner"){
            echo('
            <hr width="100%" value="black">
            <h1 class="homeTitle">Add Staff</h1>
            <ul class="homeAboutListHolder">
                <form action="../BackEnd/addstaff.php" method="post">
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Forename" name="fn"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Surname" name="sn"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Birthdate" name="dob"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Address Line 1" name="al1"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Address Line 2" name="al2"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Town" name="tn"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Postcode" name="pc"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Phone Number" name="pn"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Base Location ID" name="bl"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Employee Type" name="et"></li>

                    <li class="homeAboutList"><button class="button" type="submit" name="add-staff">Add Staff</button></li>
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
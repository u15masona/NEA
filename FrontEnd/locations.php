<?php
//Locations Screen
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

//Fetch the locations table 
$result = query("SELECT * FROM Locations ORDER BY LocationID ASC", $conn, true, false)["mysqli"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css?version=12">
    <title>Fishy's and Chippy's Locations</title>
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
    <h1 class="homeTitle">Fishy's and Chippy's Locations</h1>

    <div class="workingTableHolder">
        <table class="dashboardWorkingTable">
            <thead>
                <tr>
                    <th class="workingTableHeader">LocationID</th>
                    <th class="workingTableHeader">General Manager</th>
                    <th class="workingTableHeader">Address Line 1</th>
                    <th class="workingTableHeader">Address Line 2</th>
                    <th class="workingTableHeader">Town</th>
                    <th class="workingTableHeader">Postcode</th>
                </tr>
            </thead>
        <tbody>

    <?php
        while($row = mysqli_fetch_assoc($result)){
            $managerID = $row['ManagerID'];
            $data = query("SELECT Forename, Surname FROM Staff WHERE StaffID = '$managerID'",$conn,false,false)['data'];
            echo("<tr>");
                echo('<td class="workingTableText">');
                echo($row["LocationID"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($data["Forename"] . " " . $data["Surname"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["AddressLine1"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["AddressLine2"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["Town"]);
                echo("</td>");
                echo('<td class="workingTableText">');
                echo($row["Postcode"]);
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
            <h1 class="homeTitle">Add Location</h1>
            <ul class="homeAboutListHolder">
                <form action="../BackEnd/addlocation.php" method="post">
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Address Line 1" name="al1"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Address Line 2" name="al2"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Town" name="tn"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Postcode" name="pc"></li>
                    <li class="homeAboutList"><input class="loginField" type="text" placeholder="Manager ID" name="md"></li>

                    <li class="homeAboutList"><button class="button" type="submit" name="add-location">Add Location</button></li>
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
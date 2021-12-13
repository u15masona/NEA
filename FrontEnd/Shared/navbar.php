<?php
//navbar.php
//This file contains the HTML for the navigation bar
?>

<!--Navigation Bar-->
<ul class="homeMenu">
    <li class="homeMenuItem"><img src="Images/fish.png" alt="logo" width="100" height="46"></li>
    <li class="homeMenuEnd"><a><strong><?php echo($_SESSION["Name"] . " " . $_SESSION["Surname"]);?></strong></a></li>
    <li class="homeMenuItem"><a href="home.php">Home</a></li>
    <li class="homeMenuItem"><a href="rota.php">Rota</a></li>
    <?php
        if($_SESSION["Permission"] == "Manager" || $_SESSION["Permission"] == "Owner"){
            echo('<li class="homeMenuItem"><a href="staff.php">Staff</a></li>');
            echo('<li class="homeMenuItem"><a href="locations.php">Locations</a></li>');
        }
    ?>
    <li class="homeMenuEnd"><a href="../BackEnd/handlelogout.php">Logout</a></li>
</ul>
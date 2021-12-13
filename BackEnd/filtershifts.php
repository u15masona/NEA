<?php
//Filtershifts.php
//Archie Mason

//Allows for shifts to be filtered. Very simple file, simply redirects user to the rota page with the date in the URL

if(isset($_POST["add-shift"])){
    $date = $_POST["date"];

    header("Location: ../FrontEnd/rota.php?date_filter=" . $date);
    exit();
} else {
    header("Location: ../FrontEnd/rota.php");
    exit();
}
<?php
//Handle Logout
//Archie Mason

//First, destroy all session variables using session_unset()
session_unset();
$_SESSION = array();
//Destroy the UserLoggedIn cookie, by re-setting the cookie to a time in the past
//This time is set to one hour ago, as 3600 seconds is 1 hour
setcookie("UserLoggedIn", "true", time() - 3600, "/");
//Next, destroy the session
session_destroy();

//Finally, send user back to login page
header("Location: ../FrontEnd/index.php?logout=success");
exit();

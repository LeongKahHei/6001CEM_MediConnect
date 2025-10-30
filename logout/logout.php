<?php
session_start();        
session_unset();// Remove all session variables
session_destroy();      

// Redirect to login page
header("Location: ../registration/login.php");
exit();
?>

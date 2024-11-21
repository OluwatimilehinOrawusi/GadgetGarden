<?php
session_start();  // Start the session

// Destroy all session data
session_unset();  // Unsets all session variables
session_destroy();  // Destroys the session

// Redirect the user to the login page
header("Location: ../index.php");
exit();
?>

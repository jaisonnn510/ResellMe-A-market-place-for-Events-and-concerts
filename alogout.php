<?php
session_start();
session_unset(); // Clear all session data
session_destroy(); // Destroy the session
header("Location: admin_login.php"); // Redirect to login page
exit();
?>

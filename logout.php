<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Output debug information
echo "Logged out. Redirecting to login page...";
header("Location: login.php");
exit();
?>

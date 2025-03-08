<!-- logout.php -->
<?php
session_start();
session_destroy();
header("Location: home.php"); // Redirect to home page after logout
exit();
?>

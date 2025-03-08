<?php
$host = 'localhost'; // Your database host (usually localhost)
$username = 'root';  // Your database username
$password = '';      // Your database password
$dbname = 'todo_app'; // The name of your database

// Create a connection to MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

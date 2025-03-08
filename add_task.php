<?php
include 'db.php'; // Database connection
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in.";
    exit();
}

// Check if the form is submitted via AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = mysqli_real_escape_string($conn, $_POST['task']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $user_id = $_SESSION['user_id'];

    // Insert the task into the database
    $query = "INSERT INTO tasks (task, deadline, category, priority, user_id) 
              VALUES ('$task', '$deadline', '$category', '$priority', '$user_id')";

    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

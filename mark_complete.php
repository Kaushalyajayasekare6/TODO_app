<?php
include 'db.php'; // Database connection
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the task ID is passed
if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    // Check if the task exists and belongs to the logged-in user
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM tasks WHERE id = $task_id AND user_id = $user_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Task exists, mark it as completed
        $update_query = "UPDATE tasks SET status = 'Completed', completed_at = NOW() WHERE id = $task_id";
        if (mysqli_query($conn, $update_query)) {
            header("Location: index.php?message=Task marked as completed");
        } else {
            echo "Error updating task: " . mysqli_error($conn);
        }
    } else {
        echo "Task not found or you don't have permission to mark it as done.";
    }
} else {
    echo "No task ID specified.";
}
?>

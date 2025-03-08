<?php
session_start();  // Start the session to store session variables
include 'db.php'; // Database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Prevent SQL Injection
    $password = $_POST['password'];

    // Query to fetch the user
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    
    // Check if a user exists with the given username
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Correct credentials, start the session
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");  // Redirect to the dashboard
            exit();  // Always call exit after header redirect
        } else {
            $error_message = "Invalid credentials!";
        }
    } else {
        $error_message = "Invalid credentials!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="signup-link">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
            <?php 
            if(isset($error_message)) { 
                echo "<p style='color: red;'>$error_message</p>"; 
            } 
            ?>
        </div>
    </div>
</body>
</html>

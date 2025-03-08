<?php
session_start();
include 'db.php'; // Ensure you have a database connection



// Check if the registration form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture user inputs
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Simple validation to check if passwords match
    if ($password === $confirm_password) {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query to insert user data into the database
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Check if the query is successful
        if (mysqli_query($conn, $query)) {
            // Set session for logged-in user
            $_SESSION['username'] = $username;
            // Redirect to dashboard
            header("Location: home.php");
            exit();
        } else {
            // Database error
            $error = "Error: Could not register. Please try again.";
        }
    } else {
        $error = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <h2>Register</h2>
            <form action="register.php" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
                <button type="submit" class="btn">Register</button>
                <div class="signup-link">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

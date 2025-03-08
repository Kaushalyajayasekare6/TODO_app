<?php
include 'db.php'; // Database connection
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch tasks for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM tasks WHERE user_id = $user_id ORDER BY deadline ASC"; // Order tasks by deadline
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle task addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = mysqli_real_escape_string($conn, $_POST['task']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    
    $insertQuery = "INSERT INTO tasks (user_id, task, deadline, category, priority) 
                    VALUES ('$user_id', '$task', '$deadline', '$category', '$priority')";
    
    if (mysqli_query($conn, $insertQuery)) {
        // Task added successfully, reload the page
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Dark Mode/Light Mode Variables */
        :root {
            --background-color-light: #f4f7fc;
            --background-color-dark: #121212;
            --primary-color: #6200ee;
            --secondary-color: #3700b3;
            --task-background: #ffffff;
            --task-background-dark: #333333;
            --task-border-color: #e0e0e0;
            --button-color: #ffffff;
            --button-hover-color: #3700b3;
            --button-border-radius: 10px;
            --text-color-light: #333;
            --text-color-dark: #e0e0e0;
        }

        body {
            background-color: var(--background-color-light);
            color: var(--text-color-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark Mode */
        [data-theme="dark"] {
            --background-color-light: #121212;
            --text-color-light: #e0e0e0;
            --task-background: #333333;
            --task-background-dark: #1a1a1a;
        }

        .dashboard {
            max-width: 1200px;
            margin: 50px auto;
            background-color: var(--task-background);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        h1 {
            font-size: 2.2em;
            color: var(--text-color-light);
            text-align: center;
            margin-bottom: 20px;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #f44336;
            color: var(--button-color);
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        /* Dark Mode Button */
        .theme-toggle-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: var(--primary-color);
            color: var(--button-color);
            padding: 12px;
            border-radius: 50%;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        .theme-toggle-btn:hover {
            background-color: var(--secondary-color);
        }

        /* Input Fields and Button Group */
        .task-input-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .task-input-group input, 
        .task-input-group select,
        .task-input-group button {
            padding: 12px;
            font-size: 0.95rem;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            width: 180px;
            transition: all 0.3s ease;
        }

        .task-input-group input:focus,
        .task-input-group select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .task-input-group button {
            background-color: var(--primary-color);
            color: var(--button-color);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .task-input-group button:hover {
            background-color: var(--secondary-color);
        }

        /* Task List Styling */
        .task-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }

        .task-item {
            background-color: var(--task-background);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background-color 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .task-item:hover {
            transform: translateY(-5px);
            background-color: var(--task-background-dark);
        }

        .task-item.completed {
            background-color: #4caf50;
            color: white;
            text-decoration: line-through;
        }

        .task-item .priority {
            font-weight: bold;
            font-size: 0.9rem;
        }

        .task-item .priority-high {
            color: red;
        }

        .task-item .priority-medium {
            color: orange;
        }

        .task-item .priority-low {
            color: green;
        }

        .task-item button {
            background-color: #6200ee;
            color: white;
            padding: 8px 15px;
            border-radius: 10px;
            margin-top: 10px;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .task-item button:hover {
            background-color: #3700b3;
        }

        .task-item .btn-success {
            background-color: #4caf50;
        }

        .task-item .btn-success:hover {
            background-color: #45a049;
        }

        .task-item .btn-danger {
            background-color: #f44336;
        }

        .task-item .btn-danger:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

    <div class="dashboard">
        <a href="logout.php" class="logout-btn">Logout</a>

        <!-- Dark mode toggle button -->
        <button class="theme-toggle-btn">
            <i class="fas fa-moon"></i>
        </button>

        <h1>Welcome to Your Todo List</h1>
        
        <div class="task-input-group">
            <form action="index.php" method="POST"> <!-- Change to index.php -->
                <input type="text" name="task" placeholder="Enter your task" required>
                <input type="date" name="deadline" required>
                <select name="category" required>
                    <option value="Work">Work</option>
                    <option value="Personal">Personal</option>
                    <option value="Others">Others</option>
                </select>
                <select name="priority" required>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
                <button type="submit">Add Task</button>
            </form>
        </div>

        <h2>Your Tasks:</h2>

        <ul class="task-list">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($todo = mysqli_fetch_assoc($result)) {
                    $completedClass = ($todo['status'] == 'completed') ? 'completed' : '';
                    echo "<li class='task-item $completedClass' id='task-" . $todo['id'] . "'>";
                    echo "<div style='flex-grow: 1;'>";
                    echo "<h3>" . $todo['task'] . "</h3>";
                    echo "<p>Category: " . $todo['category'] . "</p>";
                    echo "<p>Deadline: " . $todo['deadline'] . "</p>";
                    echo "<p class='priority " . strtolower($todo['priority']) . "'>Priority: " . $todo['priority'] . "</p>";
                    echo "</div>";
                    echo "<div style='display: flex; align-items: center; gap: 5px;'>";
                    echo "<a href='delete_task.php?id=" . $todo['id'] . "'><button class='btn-danger'><i class='fas fa-trash'></i></button></a>";
                    if ($todo['status'] != 'completed') {
                        echo "<button class='btn-success mark-complete' data-id='" . $todo['id'] . "'><i class='fas fa-check'></i> Done</button>";
                    }
                    echo "</div>";
                    echo "</li>";
                }
            } else {
                echo "<p>You have no tasks yet!</p>";
            }
            ?>
        </ul>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButton = document.querySelector('.theme-toggle-btn');
            const currentTheme = localStorage.getItem('theme') || 'light';

            // Set initial theme
            document.documentElement.setAttribute('data-theme', currentTheme);

            // Toggle dark mode/light mode
            toggleButton.addEventListener('click', () => {
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                localStorage.setItem('theme', newTheme);
                document.documentElement.setAttribute('data-theme', newTheme);

                // Toggle the icon based on the current theme
                toggleButton.innerHTML = newTheme === 'dark' 
                    ? '<i class="fas fa-sun"></i>' 
                    : '<i class="fas fa-moon"></i>';
            });

            // Mark task as complete
            document.querySelectorAll(".mark-complete").forEach(button => {
                button.addEventListener("click", function() {
                    let taskId = this.getAttribute("data-id");
                    let taskItem = document.getElementById("task-" + taskId);

                    fetch("mark_complete.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + taskId
                    })
                    .then(response => response.text())
                    .then(() => {
                        taskItem.classList.add("completed");
                    });
                });
            });
        });
    </script>

</body>
</html>

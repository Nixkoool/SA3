<?php
session_start();

// Initialize tasks array in session if not already set
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Feedback message initialization
$feedback = "";

// Handle form submissions (create, update, delete)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $task = trim($_POST['task']);
        if (!empty($task)) {
            $_SESSION['tasks'][] = htmlspecialchars($task, ENT_QUOTES, 'UTF-8');
            $feedback = "Task added successfully!";
        } else {
            $feedback = "Task cannot be empty!";
        }
    } elseif ($action == 'delete') {
        $index = filter_var($_POST['index'], FILTER_VALIDATE_INT);
        if ($index !== false && isset($_SESSION['tasks'][$index])) {
            array_splice($_SESSION['tasks'], $index, 1);
            $feedback = "Task deleted successfully!";
        } else {
            $feedback = "Task not found or invalid index!";
        }
    } elseif ($action == 'update') {
        $index = filter_var($_POST['index'], FILTER_VALIDATE_INT);
        $task = trim($_POST['task']);
        if ($index !== false && isset($_SESSION['tasks'][$index]) && !empty($task)) {
            $_SESSION['tasks'][$index] = htmlspecialchars($task, ENT_QUOTES, 'UTF-8');
            $feedback = "Task updated successfully!";
        } else {
            $feedback = "Invalid update request!";
        }
    }

    // Store feedback message in session
    $_SESSION['feedback'] = $feedback;

    // Redirect back to index.php
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mario To-do App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <div class="logo">
            <img src="images/mario_logo.png" alt="Mario Logo">
        </div>
        <ul>
            <li><a href="#overview">Overview</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="home.html">Home</a></li>
        </ul>
    </nav>

    <header>
        <h1>Mario To-do App</h1>
        <p>Level up your task management!</p>
    </header>

    <main>
        <section id="overview" class="container">
            <h2>About the App</h2>
            <p>Welcome to the Mario To-do App! Manage your tasks with the excitement of a Mario adventure.</p>
        </section>

        <section id="features" class="container">
            <h2>Features</h2>
            <div class="feature-container">
                <div class="feature">
                    <img src="images/feature1.png" alt="Create and manage tasks easily">
                    <p>Create and manage your tasks easily with an intuitive interface.</p>
                </div>
                <div class="feature">
                    <img src="images/feature2.png" alt="Bright Mario-themed design">
                    <p>Enjoy a bright Mario-themed design that makes task management fun and engaging.</p>
                </div>
            </div>
        </section>

        <section id="call-to-action" class="container">
            <button onclick="navigateToHome()" aria-label="Start using the app">Start Using the App</button>
        </section>

        <section id="tasks" class="container">
            <?php if (isset($_SESSION['feedback'])): ?>
                <p class="feedback"><?php echo htmlspecialchars($_SESSION['feedback'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php unset($_SESSION['feedback']); ?>
            <?php endif; ?>

            <form action="index.php" method="POST">
                <input type="text" name="task" placeholder="New task..." required>
                <input type="hidden" name="action" value="create">
                <button type="submit">Add Task</button>
            </form>

            <ul>
                <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
                    <li>
                        <form action="index.php" method="POST" style="display:inline;">
                            <input type="text" name="task" value="<?php echo htmlspecialchars($task, ENT_QUOTES, 'UTF-8'); ?>" required>
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <button type="submit">Update</button>
                        </form>
                        <form action="index.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <script>
        function navigateToHome() {
            window.location.href = 'home.html';
        }
    </script>
</body>
</html>

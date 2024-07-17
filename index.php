<?php
session_start(); // Start or resume session

// Initialize tasks array in session if not already set
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Handle form submissions (create, update, delete)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'create') {
        $task = $_POST['task'];
        if (!empty($task)) {
            $_SESSION['tasks'][] = $task;
        }
    } elseif ($_POST['action'] == 'delete') {
        $index = $_POST['index'];
        // Check if index exists before deleting
        if (isset($_SESSION['tasks'][$index])) {
            unset($_SESSION['tasks'][$index]);
            // Reset array keys to maintain sequential numbering
            $_SESSION['tasks'] = array_values($_SESSION['tasks']);
        }
    } elseif ($_POST['action'] == 'update') {
        $index = $_POST['index'];
        $task = $_POST['task'];
        // Check if index exists before updating
        if (isset($_SESSION['tasks'][$index]) && !empty($task)) {
            $_SESSION['tasks'][$index] = $task;
        }
    }
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
            <li><a href="index.html#overview">Overview</a></li>
            <li><a href="index.html#features">Features</a></li>
            <li><a href="home.html">Home</a></li>
        </ul>
    </nav>

    <center>
        <h1>Mario To-do App</h1>
        <p>Level up your task management!</p>
    </center>        

    <main class="container">
        <form action="index.php" method="POST">
            <input type="text" name="task" placeholder="New task...">
            <input type="hidden" name="action" value="create">
            <button type="submit">Add Task</button>
        </form>

        <ul>
            <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
                <li>
                    <form action="index.php" method="POST" style="display:inline;">
                        <input type="text" name="task" value="<?php echo htmlspecialchars($task); ?>">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button type="submit">Update</button>
                    </form>
                    <form action="index.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <a href="display_task.php?index=<?php echo $index; ?>">Details</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>

</body>
</html>

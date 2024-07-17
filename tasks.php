<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    if ($action == 'create') {
        $task = $_POST['task'];
        if (!empty($task)) {
            $_SESSION['tasks'][] = $task;
        }
    } elseif ($action == 'delete') {
        $index = $_POST['index'];
        if (isset($_SESSION['tasks'][$index])) {
            array_splice($_SESSION['tasks'], $index, 1);
        }
    } elseif ($action == 'update') {
        $index = $_POST['index'];
        $task = $_POST['task'];
        if (!empty($task)) {
            $_SESSION['tasks'][$index] = $task;
        }
    }
}

// Redirect back to index.php
header('Location: index.php');
exit();
?>

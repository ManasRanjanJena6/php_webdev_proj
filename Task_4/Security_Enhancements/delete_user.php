<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}

include 'db.php';

// Get the user ID from the URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Prevent the admin from deleting themselves
if ($user_id === $_SESSION['user_id']) {
    header("Location: manage_users.php?error=You+cannot+delete+your+own+account");
    exit();
}

// Delete the user
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: manage_users.php?msg=User+deleted+successfully");
    exit();
} else {
    header("Location: manage_users.php?error=Failed+to+delete+user");
    exit();
}
?>
<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 1.5rem;
            color: #343a40;
        }
        .btn {
            border-radius: 20px;
        }
    </style>
</head>
<body class="container mt-5">
    <h1 class="text-center text-primary">Admin Dashboard</h1>
    <p class="text-center">Welcome, Admin! Here you can manage the application and its users.</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-4">
                <h5 class="card-title text-center">Manage Users</h5>
                <p class="text-center">View, edit, or delete user accounts.</p>
                <div class="text-center">
                    <a href="manage_users.php" class="btn btn-secondary">Manage Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <h5 class="card-title text-center">View Posts</h5>
                <p class="text-center">View all posts created by users.</p>
                <div class="text-center">
                    <a href="view_posts.php" class="btn btn-primary">View Posts</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <h5 class="card-title text-center">Site Settings</h5>
                <p class="text-center">Configure application settings.</p>
                <div class="text-center">
                    <a href="site_settings.php" class="btn btn-warning">Settings</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
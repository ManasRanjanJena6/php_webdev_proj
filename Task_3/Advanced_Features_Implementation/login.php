<?php include "db.php"; session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4facfe, rgb(128, 155, 156));
        }
        .card {
            border-radius: 1rem;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4 text-primary">Login</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Enter your username">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter your password">
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-success" type="submit">Login</button>
                <a href="register.php" class="btn btn-outline-primary text-center">Don't have an account? Register</a>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $hashed_password);
                $stmt->fetch();
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger mt-3 text-center'>Invalid password.</div>";
                }
            } else {
                echo "<div class='alert alert-danger mt-3 text-center'>No user found.</div>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
    <div class="footer text-center mt-4">
        <p>&copy; 2025 Blog Application. All rights reserved.</p>
    </div>
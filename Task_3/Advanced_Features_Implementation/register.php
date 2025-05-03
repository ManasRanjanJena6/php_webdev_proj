<!-- register.php -->
<?php include "db.php"; session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4facfe,rgb(128, 155, 156));
        }
        .card {
            border-radius: 1rem;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4 text-primary">Register</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Enter new username">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter new password">
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-success" type="submit">Sign Up</button>
                <a href="login.php" class="btn btn-outline-primary text-center">Already have an account? Login</a>
            </div>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

            $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $check->bind_param("s", $username);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                echo "<div class='alert alert-warning mt-3 text-center'>Username already taken. Please choose another.</div>";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $password);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success mt-3 text-center'>Registration successful!</div>";
                } else {
                    echo "<div class='alert alert-danger mt-3 text-center'>Error: Could not register.</div>";
                }
            }
        }
        ?>
    </div>
</div>
    <div class="footer text-center mt-4">
        <p>&copy; 2025 Blog Application. All rights reserved.</p>
</body>
</html>

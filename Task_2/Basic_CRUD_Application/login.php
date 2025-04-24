<?php session_start(); include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light">

<!-- Centered login form with a card-style background -->
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4">Login</h2>
        
        <!-- Login Form -->
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-success" type="submit">Login</button>
                <a href="register.php" class="btn btn-link text-center">Register</a>
            </div>
        </form>

        <!-- Error message for failed login -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
            $stmt->bind_param("s", $_POST["username"]);
            $stmt->execute();
            $stmt->bind_result($id, $hashed);
            if ($stmt->fetch() && password_verify($_POST["password"], $hashed)) {
                $_SESSION["user_id"] = $id;
                header("Location: index.php");
                exit(); // Important to stop further execution
            } else {
                echo "<div class='alert alert-danger mt-3'>Invalid credentials!</div>";
            }
        }
        ?>
    </div>
</div>

</body>
</html>

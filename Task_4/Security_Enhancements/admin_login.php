<!-- filepath: c:\xampp\htdocs\ApexPlanet_Internship_May_25_Proj\Task_4\Security_Enhancements\admin_login.php -->
<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Check if the admin exists in the database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ? AND role = 'admin'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Store admin details in session
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';
            $_SESSION['last_activity'] = time(); // Track session activity

            // Redirect to admin dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No admin found with that username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 1s ease-in-out;
        }
        .card h2 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #1e3c72;
            text-align: center;
            margin-bottom: 1rem;
        }
        .form-control {
            border-radius: 20px;
            padding: 8px 12px;
            font-size: 0.9rem;
        }
        .btn-success {
            background: #28a745;
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 0.9rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-success:hover {
            background: #218838;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
        }
        .alert {
            border-radius: 20px;
            animation: fadeIn 0.8s ease-in-out;
        }
        .home-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.8);
            color: #1e3c72;
            border: none;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .home-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.5);
        }
        .footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 8px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 0.8rem;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Home Button -->
    <a href="index.php" class="btn btn-secondary home-btn">Home</a>

    <div class="card">
        <h2>Admin Login</h2>
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
            </div>
        </form>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2025 Blog Application. All rights reserved.
    </div>
</body>
</html>
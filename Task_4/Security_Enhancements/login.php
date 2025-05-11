<!-- filepath: c:\xampp\htdocs\ApexPlanet_Internship_May_25_Proj\Task_4\Security_Enhancements\login.php -->
<?php
include "db.php";
session_start();

// Initialize variables for error messages
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Store user details in session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Redirect based on role
            if ($role === 'admin') {
                $error_message = "Access denied. Please contact the administrator.";
            } elseif ($role === 'editor') {
                header("Location: editor_dashboard.php");
                exit();
            } elseif ($role === 'user') {
                header("Location: view_posts.php");
                exit();
            }
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with that username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298); /* Modern blue gradient */
            color: white;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .home-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.8);
            color: #1e3c72;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 1rem;
            text-decoration: none; /* Remove underline */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .home-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.5);
        }
        .container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 2s ease-in-out;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            animation: slideUp 1.5s ease-in-out;
        }
        .card h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #1e3c72;
            animation: zoomIn 1.5s ease-in-out;
        }
        .btn {
            border-radius: 30px;
            font-size: 1.1rem;
            padding: 10px 20px;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }
        .btn-success {
            background: #28a745; /* Green for login */
            border: none;
        }
        .btn-success:hover {
            background: #1e7e34; /* Darker green on hover */
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.5);
        }
        .btn-outline-primary {
            color: #007bff;
            border: 2px solid #007bff;
        }
        .btn-outline-primary:hover {
            background: #007bff;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.5);
        }
        .alert {
            animation: fadeIn 1s ease-in-out;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #ccc;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        @keyframes zoomIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Home Button -->
    <a href="index.php" class="home-btn">Home</a>

    <div class="container">
        <div class="card">
            <h2 class="text-center mb-4">Login</h2>
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

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger mt-3 text-center"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="footer text-center" style="position: fixed; bottom: 0; width: 100%; background: rgba(0, 0, 0, 0.8); color: white; padding: 10px; font-size: 0.9rem;">
    <p>&copy; 2025 Blog Application. All rights reserved.</p>
    </div>
</body>
</html>
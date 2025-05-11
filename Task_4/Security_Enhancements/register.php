<!-- filepath: c:\xampp\htdocs\ApexPlanet_Internship_May_25_Proj\Task_4\Security_Enhancements\register.php -->
<?php include "db.php"; session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.9rem;
            text-decoration: none; /* Remove underline */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .home-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.5);
        }
        .container {
            height: calc(100vh - 60px); /* Adjust height to leave space for footer */
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 1.5s ease-in-out;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 450px;
            width: 100%;
            animation: slideUp 1.2s ease-in-out;
        }
        .card h2 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #1e3c72;
            animation: zoomIn 1.2s ease-in-out;
        }
        .btn {
            border-radius: 25px;
            font-size: 1rem;
            padding: 8px 16px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-success {
            background: #28a745; /* Green for register */
            border: none;
        }
        .btn-success:hover {
            background: #1e7e34; /* Darker green on hover */
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }
        .btn-outline-primary {
            color: #007bff;
            border: 2px solid #007bff;
        }
        .btn-outline-primary:hover {
            background: #007bff;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        }
        .alert {
            animation: fadeIn 1s ease-in-out;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            font-size: 0.85rem;
            text-align: center;
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
                transform: scale(0.9);
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
            <h2 class="text-center mb-4">Register</h2>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required placeholder="Enter new username">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter new password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="user">User</option>
                        <option value="editor">Editor</option>
                    </select>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-success" type="submit">Sign Up</button>
                    <a href="login.php" class="btn btn-outline-primary text-center">Already have an account? Login</a>
                </div>
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = trim($_POST["username"]);
                $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
                $role = trim($_POST["role"]);

                // Check if the username already exists
                $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                $checkStmt->bind_param("s", $username);
                $checkStmt->execute();
                $checkStmt->store_result();

                if ($checkStmt->num_rows > 0) {
                    echo "<div class='alert alert-danger mt-3 text-center'>Error: Username already exists. Please choose a different username.</div>";
                } else {
                    // Insert the new user into the database
                    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $username, $password, $role);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success mt-3 text-center'>Registration successful! Redirecting to login...</div>";
                        // Redirect to login page after 3 seconds
                        echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 3000);</script>";
                    } else {
                        echo "<div class='alert alert-danger mt-3 text-center'>Error: Could not register. Please try again later.</div>";
                    }
                }
            }
            ?>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2025 Blog Application. All rights reserved.</p>
    </div>
</body>
</html>
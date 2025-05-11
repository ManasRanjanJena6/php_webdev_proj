<!-- filepath: c:\xampp\htdocs\ApexPlanet_Internship_May_25_Proj\Task_4\Security_Enhancements\index.php -->
<?php
include 'db.php';

// Fetch site settings from the database
$stmt = $conn->prepare("SELECT site_name, site_description FROM settings WHERE id = 1");
$stmt->execute();
$stmt->bind_result($site_name, $site_description);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_name); ?></title>
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
        .welcome-container {
            height: calc(100vh - 50px); /* Adjust height to leave space for footer */
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
            animation: fadeIn 2s ease-in-out;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            animation: slideUp 1.5s ease-in-out;
        }
        .card h1 {
            font-size: 2.8rem;
            font-weight: bold;
            color: #1e3c72; /* Dark blue for the title */
            animation: zoomIn 1.5s ease-in-out;
        }
        .card p {
            font-size: 1.2rem;
            color: #495057; /* Neutral gray for description */
            margin-bottom: 2rem;
        }
        .btn {
            border-radius: 30px;
            font-size: 1.1rem;
            padding: 10px 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-primary {
            background: #007bff; /* Bright blue */
            border: none;
        }
        .btn-primary:hover {
            background: #0056b3; /* Darker blue on hover */
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.5);
        }
        .btn-success {
            background: #28a745; /* Green for register */
            border: none;
        }
        .btn-success:hover {
            background: #1e7e34; /* Darker green on hover */
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.5);
        }
        .btn-warning {
            background: #ffc107; /* Yellow for admin login */
            border: none;
            color: #212529;
        }
        .btn-warning:hover {
            background: #e0a800; /* Darker yellow on hover */
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.5);
        }
        .footer {
            height: 50px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.9rem;
            position: fixed;
            bottom: 0;
            width: 100%;
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
    <div class="welcome-container">
        <div class="card">
            <!-- Dynamically display the site name -->
            <h1 class="mb-4"><?php echo htmlspecialchars($site_name); ?></h1>
            <!-- Dynamically display the site description -->
            <p class="mb-4"><?php echo htmlspecialchars($site_description); ?></p>
            <div class="d-grid gap-3">
                <a href="login.php" class="btn btn-primary btn-lg">Login</a>
                <a href="register.php" class="btn btn-success btn-lg">Register</a>
                <a href="admin_login.php" class="btn btn-warning btn-lg">Admin Login</a>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <div class="footer">
        &copy; 2025 Blog Application. All rights reserved.
    </div>
</body>
</html>
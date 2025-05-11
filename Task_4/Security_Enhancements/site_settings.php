<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}

include 'db.php';

// Initialize variables for messages
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = trim($_POST['site_name']);
    $site_description = trim($_POST['site_description']);

    // Validate input
    if (empty($site_name) || empty($site_description)) {
        $error_message = "Both fields are required.";
    } else {
        // Update site settings in the database
        $stmt = $conn->prepare("UPDATE settings SET site_name = ?, site_description = ? WHERE id = 1");
        $stmt->bind_param("ss", $site_name, $site_description);

        if ($stmt->execute()) {
            $success_message = "Settings updated successfully!";
        } else {
            $error_message = "Failed to update settings. Please try again.";
        }
        $stmt->close();
    }
}

// Fetch current settings
$stmt = $conn->prepare("SELECT site_name, site_description FROM settings WHERE id = 1");
$stmt->execute();
$stmt->bind_result($site_name, $site_description);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Site Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn {
            border-radius: 20px;
        }
    </style>
</head>
<body class="container mt-5">
    <h1 class="text-center text-primary">Site Settings</h1>
    <p class="text-center">Update the application settings below.</p>

    <div class="card p-4 mt-4">
        <!-- Display success or error messages -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success text-center"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Settings Form -->
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Site Name</label>
                <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($site_name); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Site Description</label>
                <textarea name="site_description" class="form-control" rows="4" required><?php echo htmlspecialchars($site_description); ?></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Save Settings</button>
                <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>
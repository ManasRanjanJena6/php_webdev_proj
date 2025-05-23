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

// Default values
$msg = null;
$type = "danger";
$user = null;
$show_success = false;

// Check if the session user_id is set (must be set at login)
if (!isset($_SESSION['user_id'])) {
    $msg = "Session error: User ID not found. Please log in again.";
} elseif ($user_id === $_SESSION['user_id']) {
    $msg = "You cannot delete your own account.";
} elseif ($user_id > 0) {
    // Fetch user info for confirmation
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $show_success = true;
        } else {
            $msg = "Failed to delete user.";
        }
    }
} else {
    $msg = "User not found.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(33,147,176,0.13);
            border: none;
            background: #fff;
            max-width: 400px;
            width: 100%;
            padding: 2rem 2rem 1.5rem 2rem;
        }
        .main-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #d9534f;
            margin-bottom: 1.2rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #f8d7da;
            display: flex;
            align-items: center;
            gap: 0.5em;
            justify-content: center;
        }
        .btn-danger {
            border-radius: 30px;
            padding: 8px 28px;
            font-size: 1.1rem;
        }
        .btn-secondary {
            border-radius: 30px;
            padding: 8px 24px;
            font-size: 1.1rem;
        }
        .alert {
            border-radius: 1rem;
        }
        @media (max-width: 600px) {
            .card {
                padding: 1.2rem 0.5rem;
                max-width: 98vw;
            }
            .main-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="card shadow text-center">
        <div class="main-title mb-3">
            <i class="bi bi-person-x-fill"></i> Delete User
        </div>
        <?php if ($show_success): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> User deleted successfully!
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = 'manage_users.php';
                }, 3000);
            </script>
        <?php elseif ($msg): ?>
            <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
            <a href="manage_users.php" class="btn btn-secondary mt-2"><i class="bi bi-arrow-left"></i> Back</a>
        <?php elseif ($user && isset($user['username'])): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Are you sure you want to delete user <strong><?= htmlspecialchars($user['username']) ?></strong>?
            </div>
            <form method="post">
                <button type="submit" name="confirm_delete" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Yes, Delete
                </button>
                <a href="manage_users.php" class="btn btn-secondary ms-2">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">User not found.</div>
            <a href="manage_users.php" class="btn btn-secondary mt-2"><i class="bi bi-arrow-left"></i> Back</a>
        <?php endif; ?>
    </div>
</body>
</html>
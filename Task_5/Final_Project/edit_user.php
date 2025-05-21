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

// Fetch the user's current details
$stmt = $conn->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<div class='alert alert-danger text-center'>User not found.</div>");
}

$user = $result->fetch_assoc();

// Handle form submission
$show_success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $role = $_POST['role'];

    if (!empty($username) && in_array($role, ['admin', 'editor', 'user'])) {
        $update_stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $username, $role, $user_id);

        if ($update_stmt->execute()) {
            $show_success = true;
            // Show message for 3 seconds, then redirect (handled in HTML below)
        } else {
            echo "<div class='alert alert-danger text-center'>Failed to update user.</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid input.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            min-width: 100vw;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-wrapper {
            min-height: 100vh;
            min-width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(33,147,176,0.13);
            border: none;
            background: #fff;
            width: 100%;
            max-width: 500px;
            padding: 2rem 2rem 1.5rem 2rem;
        }
        .main-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2193b0;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #b3c6e0;
            display: flex;
            align-items: center;
            gap: 0.5em;
            justify-content: center;
        }
        .form-label {
            font-weight: 600;
            color: #1e3c72;
        }
        .form-control, select.form-control {
            border-radius: 1rem;
            font-size: 1.08rem;
            background: #f7fbff;
        }
        .form-control:focus, select.form-control:focus {
            border-color: #2193b0;
            box-shadow: 0 0 0 0.2rem rgba(33,147,176,0.08);
        }
        .btn-success {
            border-radius: 30px;
            padding: 8px 28px;
            font-size: 1.1rem;
            background: linear-gradient(90deg, #28a745 60%, #6dd5ed 100%);
            border: none;
        }
        .btn-success:hover {
            background: linear-gradient(90deg, #2193b0 60%, #28a745 100%);
            color: #fff;
            transform: scale(1.05);
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
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="main-wrapper">
    <div class="card shadow">
        <div class="main-title mb-4">
            <i class="bi bi-person-lines-fill"></i> Edit User
        </div>
        <?php if ($show_success): ?>
            <div class="alert alert-success text-center" id="successMsg">
                <i class="bi bi-check-circle-fill"></i> User updated successfully!
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = 'manage_users.php';
                }, 3000);
            </script>
        <?php else: ?>
        <form method="post" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="editor" <?php echo $user['role'] === 'editor' ? 'selected' : ''; ?>>Editor</option>
                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Update User</button>
                <a href="manage_users.php" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
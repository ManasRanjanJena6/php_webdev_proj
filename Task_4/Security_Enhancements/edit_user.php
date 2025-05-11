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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $role = $_POST['role'];

    if (!empty($username) && in_array($role, ['admin', 'editor', 'user'])) {
        $update_stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $username, $role, $user_id);

        if ($update_stmt->execute()) {
            header("Location: manage_users.php?msg=User+updated+successfully");
            exit();
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
</head>
<body class="container mt-5">
    <h1 class="text-center text-primary">Edit User</h1>
    <form method="post" class="mt-4">
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
            <button type="submit" class="btn btn-success">Update User</button>
            <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</body>
</html>
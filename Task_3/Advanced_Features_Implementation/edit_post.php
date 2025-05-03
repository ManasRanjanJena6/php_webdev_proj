<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Access+denied");
    exit;
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

// Check if post exists and belongs to user
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php?error=You+are+not+authorized+to+edit+this+post");
    exit;
}

$post = $result->fetch_assoc();

// Handle form submission
$update_success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $update_stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
    $update_stmt->bind_param("ssii", $title, $content, $post_id, $user_id);

    if ($update_stmt->execute()) {
        $update_success = true;
    } else {
        echo "<div class='alert alert-danger text-center'>Update failed.</div>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .container {
            max-width: 700px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card shadow p-4">
        <h2 class="mb-4 text-primary">Edit Post</h2>

        <?php if ($update_success): ?>
            <div class="alert alert-success text-center">
                Post updated successfully!
                <br>
                <a href="index.php" class="btn btn-primary mt-3">← Back to Dashboard</a>
            </div>
            <script>
                setTimeout(() => window.location.href = 'index.php', 3000);
            </script>
        <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control" rows="6" required><?= htmlspecialchars($post['content']); ?></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

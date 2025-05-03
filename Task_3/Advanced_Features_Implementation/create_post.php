<?php
// create_post.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("<div class='alert alert-danger text-center mt-5'>Access denied. Please <a href='login.php'>login</a>.</div>");
}

$user_id = $_SESSION['user_id'];

// Handle form submission
$create_success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Insert new post into the database
    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $content);

    if ($stmt->execute()) {
        $create_success = true;
    } else {
        echo "<div class='alert alert-danger text-center'>Post creation failed.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
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
        <h2 class="mb-4 text-primary">Create New Post</h2>

        <?php if ($create_success): ?>
            <div class="alert alert-success text-center">
                Post created successfully!
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
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control" rows="6" required></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Create Post</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

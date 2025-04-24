<?php
include "db.php";
include "auth.php";

$id = $_GET["id"] ?? 0;
$title = $content = "";

// Fetch the post details
$stmt = $conn->prepare("SELECT title, content FROM posts WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $_SESSION["user_id"]);
$stmt->execute();
$stmt->store_result(); // Fixes "commands out of sync"
if ($stmt->num_rows > 0) {
    $stmt->bind_result($title, $content);
    $stmt->fetch();
} else {
    echo "<div class='alert alert-danger text-center'>Post not found or access denied.</div>";
    exit;
}
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newTitle = $_POST["title"];
    $newContent = $_POST["content"];

    $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssii", $newTitle, $newContent, $id, $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-lg mx-auto" style="max-width: 720px;">
            <div class="card-body">
                <h3 class="card-title mb-4 text-center text-primary">Edit Your Post</h3>
                <form method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input name="title" id="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control" rows="6" required><?= htmlspecialchars($content) ?></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Update Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

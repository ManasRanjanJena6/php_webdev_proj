<?php include "db.php"; include "auth.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="card shadow-lg p-4 w-100" style="max-width: 600px;">
    <h3 class="text-center text-primary mb-4">Create New Post</h3>
    
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="5" required></textarea>
        </div>
        <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-secondary">Back</a>
            <button class="btn btn-success" type="submit">Post</button>
        </div>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $_POST["title"], $_POST["content"], $_SESSION["user_id"]);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>

</body>
</html>

<?php include "db.php"; include "auth.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">

</head>
<body class="container mt-5">
<h2 class="mb-4">Blog Posts</h2>

<a href="create_post.php" class="btn btn-primary mb-3">Add New Post</a>
<a href="logout.php" class="btn btn-danger mb-3 float-end">Logout</a>

<?php
$result = $conn->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>";
    echo "<p class='card-text'>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
    echo "<p class='card-text'><small class='text-muted'>By " . $row['username'] . " on " . $row['created_at'] . "</small></p>";
    echo "<a href='edit_post.php?id={$row['id']}' class='btn btn-sm btn-outline-primary'>Edit</a> ";
    echo "<a href='delete_post.php?id={$row['id']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Delete this post?\")'>Delete</a>";
    echo "</div></div>";
}
?>
</body>
</html>

<!-- filepath: c:\xampp\htdocs\ApexPlanet_Internship_May_25_Proj\Task_4\Security_Enhancements\editor_dashboard.php -->
<?php
include 'db.php'; // Include database connection

// Initialize search variable
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch posts based on the search term
$query = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username 
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          WHERE title LIKE ? OR content LIKE ? 
          ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$searchTerm = '%' . $search . '%';
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Pagination variables
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$postsPerPage = 5;
$totalPosts = $result->num_rows;
$totalPages = ceil($totalPosts / $postsPerPage);

// Adjust query for pagination
$offset = ($page - 1) * $postsPerPage;
$query .= " LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssii", $searchTerm, $searchTerm, $offset, $postsPerPage);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(209, 228, 226);
            font-family: 'Arial', sans-serif;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            color: #343a40;
        }

        .card-text {
            font-size: 1rem;
            color: #495057;
        }

        .card-body {
            padding: 20px;
        }

        .pagination {
            margin-top: 30px;
        }

        .page-link {
            color: #007bff;
        }

        .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .input-group input {
            border-radius: 20px 0 0 20px;
        }

        .input-group button {
            border-radius: 0 20px 20px 0;
        }

        .alert {
            border-radius: 10px;
        }

        .alert-success {
            background-color: #e0ffe0;
            color: #2d7a2d;
            border: 1px solid #2d7a2d;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .view-posts-btn {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .create-post-btn {
            display: block;
            margin: 20px auto;
            background: #28a745;
            color: white;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 1rem;
            text-decoration: none;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .create-post-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.5);
        }
    </style>
</head>
<body class="container mt-5">
    <!-- View Posts Button -->
    <a href="view_posts.php" class="btn btn-secondary view-posts-btn">View Posts</a>

    <!-- Logout Button -->
    <a href="logout.php" class="btn btn-danger logout-btn">Logout</a>

    <h2 class="mb-4 text-center">Editor Dashboard</h2>

    <!-- Display Success or Error Message -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success text-center">
            <?= htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Create Post Button -->
    <a href="create_post.php" class="create-post-btn">Create New Post</a>

    <!-- Search Form -->
    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>" aria-label="Search posts">
            <button class="btn btn-outline-secondary" type="submit" aria-label="Search">Search</button>
        </div>
    </form>

    <!-- Posts List -->
    <?php
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card mb-3'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>";
        echo "<p class='card-text'>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
        echo "<p class='card-text'><small class='text-muted'>By " . ($row['username'] ?? 'Unknown') . " on " . $row['created_at'] . "</small></p>";
        echo "<a href='edit_post.php?id={$row['id']}' class='btn btn-sm btn-outline-primary'>Edit</a> ";
        echo "<a href='delete_post.php?id={$row['id']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Delete this post?\")'>Delete</a>";
        echo "</div></div>";
    }
    ?>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=1" aria-label="First">
                        <span aria-hidden="true">&laquo;&laquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $totalPages ?>" aria-label="Last">
                        <span aria-hidden="true">&raquo;&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>
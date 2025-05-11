<?php
include "auth.php"; // Ensure the user is authenticated
include "db.php"; // Include database connection

// Allow only admins and editors to access this page
checkRole(['admin', 'editor']);

// Handle search and pagination
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Posts per page
$offset = ($page - 1) * $limit;

$whereClause = "";
$params = [];
$types = "";

if ($search !== '') {
    $whereClause = "WHERE posts.title LIKE ? OR posts.content LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types = "ss";
}

// Count total posts for pagination
$countSql = "SELECT COUNT(*) FROM posts JOIN users ON posts.user_id = users.id " . $whereClause;
$stmt = $conn->prepare($countSql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$stmt->bind_result($totalPosts);
$stmt->fetch();
$stmt->close();

$totalPages = ceil($totalPosts / $limit);

// Fetch posts with limit and offset
$sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id " . 
       ($whereClause ? $whereClause . " " : "") . "ORDER BY created_at DESC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

function refValues($arr) {
    $refs = [];
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

if (!empty($params)) {
    $types .= "ii";
    $params[] = $limit;
    $params[] = $offset;
    $bindParams = array_merge([$types], $params);
    call_user_func_array([$stmt, 'bind_param'], refValues($bindParams));
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Posts</title>
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
            background-color: #f8d7da;
            color: #721c24;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .dashboard-btn {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body class="container mt-5">
    <!-- Back to Dashboard Button for Admin and Editor -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin_dashboard.php" class="btn btn-secondary dashboard-btn">Back to Admin Dashboard</a>
    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'editor'): ?>
        <a href="editor_dashboard.php" class="btn btn-secondary dashboard-btn">Back to Editor Dashboard</a>
    <?php endif; ?>

    <!-- Logout Button -->
    <a href="logout.php" class="btn btn-danger logout-btn">Logout</a>

    <h2 class="mb-4 text-center">Blog Posts</h2>

    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>" aria-label="Search posts">
            <button class="btn btn-outline-secondary" type="submit" aria-label="Search">Search</button>
        </div>
    </form>

    <?php
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card mb-3'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>";
        echo "<p class='card-text'>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
        echo "<p class='card-text'><small class='text-muted'>By " . $row['username'] . " on " . $row['created_at'] . "</small></p>";
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
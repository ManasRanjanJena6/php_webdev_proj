<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}

include 'db.php';

// Fetch all users from the database
$stmt = $conn->prepare("SELECT id, username, role FROM users ORDER BY role, username");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .main-title {
            font-size: 2.2rem;
            font-weight: bold;
            color: #2193b0;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #b3c6e0;
        }
        .subtitle {
            color: #495057;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        .table {
            background: #fff;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(33,147,176,0.10);
        }
        th {
            background: #2193b0;
            color: #fff;
            font-weight: 600;
            text-align: center;
        }
        td {
            vertical-align: middle !important;
            text-align: center;
        }
        .btn-warning, .btn-danger {
            border-radius: 20px;
            font-size: 0.95rem;
            padding: 5px 16px;
        }
        .btn-warning {
            background: linear-gradient(90deg, #ffc107 60%, #ffe082 100%);
            color: #212529;
            border: none;
        }
        .btn-warning:hover {
            background: linear-gradient(90deg, #e0a800 60%, #ffc107 100%);
            color: #212529;
        }
        .btn-danger {
            background: linear-gradient(90deg, #dc3545 60%, #f8d7da 100%);
            color: #fff;
            border: none;
        }
        .btn-danger:hover {
            background: linear-gradient(90deg, #b52a37 60%, #dc3545 100%);
            color: #fff;
        }
        .btn-secondary {
            border-radius: 20px;
            padding: 8px 24px;
            font-size: 1.1rem;
            margin-top: 20px;
        }
        .table-responsive {
            border-radius: 1rem;
            overflow: hidden;
        }
        @media (max-width: 600px) {
            .main-title {
                font-size: 1.2rem;
            }
            .subtitle {
                font-size: 0.95rem;
            }
            .btn-secondary {
                font-size: 0.95rem;
                padding: 6px 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="text-center">
            <div class="main-title"><i class="bi bi-people"></i> Manage Users</div>
            <div class="subtitle">Here you can view, edit, or delete users.</div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mt-4 align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td>
                                <span class="badge bg-<?php
                                    if ($row['role'] === 'admin') echo 'primary';
                                    elseif ($row['role'] === 'editor') echo 'success';
                                    else echo 'secondary';
                                ?>">
                                    <?php echo htmlspecialchars(ucfirst($row['role'])); ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this user?');">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <a href="admin_dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
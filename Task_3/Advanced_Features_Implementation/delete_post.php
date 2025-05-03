<?php
include "db.php";
include "auth.php";

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $postId = (int) $_GET["id"];
    $userId = $_SESSION["user_id"];

    // Check if the post exists and belongs to this user
    $check = $conn->prepare("SELECT id FROM posts WHERE id = ?");
    $check->bind_param("i", $postId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // Post exists, now check if user owns it
        $check_owner = $conn->prepare("SELECT id FROM posts WHERE id = ? AND user_id = ?");
        $check_owner->bind_param("ii", $postId, $userId);
        $check_owner->execute();
        $check_owner->store_result();

        if ($check_owner->num_rows > 0) {
            // User owns the post, proceed with deletion
            $delete = $conn->prepare("DELETE FROM posts WHERE id = ?");
            $delete->bind_param("i", $postId);
            if ($delete->execute()) {
                header("Location: index.php?msg=Post+deleted+successfully");
                exit;
            } else {
                header("Location: index.php?error=Failed+to+delete+post");
                exit;
            }
        } else {
            // Post exists but doesn't belong to user
            header("Location: index.php?error=You+are+not+allowed+to+delete+this+post");
            exit;
        }
    } else {
        header("Location: index.php?error=Post+not+found");
        exit;
    }
} else {
    header("Location: index.php?error=Invalid+post+ID");
    exit;
}
?>

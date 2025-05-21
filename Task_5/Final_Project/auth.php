<!-- filepath: c:\xampp\htdocs\ApexPlanet_Internship_May_25_Proj\Task_4\Security_Enhancements\auth.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if it's not already active
}

// Define session timeout duration (30 minutes)
define('SESSION_TIMEOUT', 1800); // 1800 seconds = 30 minutes

// Session timeout logic
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=Session+expired");
    exit();
}
$_SESSION['last_activity'] = time(); // Update last activity time

// Authentication check
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["admin_id"])) {
    // Store the requested page to redirect after login
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php?error=Please+login+to+access+this+page");
    exit();
}

// Role-based access control
function checkRole(array $allowedRoles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
        error_log("Unauthorized access attempt by user_id: " . ($_SESSION['user_id'] ?? 'Unknown') . " with role: " . ($_SESSION['role'] ?? 'None'));
        header("Location: unauthorized.php?error=Access+denied");
        exit();
    }
}

// Redirect to the requested page after login
function redirectToPreviousPage() {
    if (isset($_SESSION['redirect_to'])) {
        $redirectTo = $_SESSION['redirect_to'];
        unset($_SESSION['redirect_to']); // Clear the redirect session variable
        header("Location: $redirectTo");
        exit();
    }
}

// Function to check if the session is active
function isSessionActive() {
    return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']);
}

// Function to log out the user
function logout() {
    session_unset();
    session_destroy();
    header("Location: login.php?msg=You+have+been+logged+out");
    exit();
}
?>
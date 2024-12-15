<?php
session_start();

// Check if "last_activity" is set in $_SESSION
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
    // Last request was more than 10 minutes ago (600 seconds)
    // This will unset $_SESSION variable
    session_unset(); 

    // This will destroy the session data
    session_destroy(); 

    // Redirect the user to login page
    header("Location: index.php");
    exit();
} 

// Check if user_id is set in $_SESSION
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to login page
    header("Location: index.php");
    exit();
} else {
    // Update last activity time stamp
    $_SESSION['last_activity'] = time();
}
?>

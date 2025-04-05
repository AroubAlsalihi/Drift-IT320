<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user clicked "Log Out"
if (isset($_GET['logout'])) {
    session_destroy();

echo "<script>alert('Logged out successfully'); window.location.href='home.php';</script>";// Redirect to home after logout

    exit();
}
?>
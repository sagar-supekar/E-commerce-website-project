<?php
// Start the session
session_start();

// Unset all session variables
session_unset();  

// Destroy the session
session_destroy();

// Delete the session cookie (PHPSESSID)
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/'); // Set cookie expiration time to past to delete it
}

// Optionally, delete any other cookies you set for the user (e.g., 'login_id' or custom cookies)
if (isset($_COOKIE['login_id'])) {
    setcookie('login_id', '', time() - 3600, '/');  // Expire the 'login_id' cookie
}

// You can check if session variables are still set for debugging (remove later)
if (isset($_SESSION['login_id'])) {
    echo "Session variable is still set.";
} else {
    echo "Session variable is destroyed.";
}

// Redirect the user to the login page or home page
header('Location: login.php');  // Redirect to login page after logout
exit();
?>

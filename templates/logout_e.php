<?php
// Start the session
session_start();

// Destroy all session variables
session_unset();
session_destroy();

// Clear the 'login_id' cookie
if (isset($_COOKIE['login_id'])) {
    setcookie('login_id', '', time() - 3600, '/'); // Expire the cookie
    unset($_COOKIE['login_id']); // Unset the variable in this request
}

//Debug messages (only for testing; remove in production)
if (!isset($_SESSION['login_id'])) {
    echo "Session variable 'login_id' is destroyed.<br>";
} else {
    echo "Session variable 'login_id' still exists: " . $_SESSION['login_id'] . "<br>";
}

if (!isset($_COOKIE['login_id'])) {
    echo "Cookie 'login_id' is cleared.<br>";
} else {
    echo "Cookie 'login_id' still exists: " . $_COOKIE['login_id'] . "<br>";
}
header("Location: welcome.php");
exit;
?>


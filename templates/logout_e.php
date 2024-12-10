<?php
session_start();
session_unset();
session_destroy();

if (isset($_COOKIE['login_id'])) {
    setcookie('login_id', '', time() - 3600); // Expire the cookie
    unset($_COOKIE['login_id']); 
}

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


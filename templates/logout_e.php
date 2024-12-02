<?php
// Start the session
session_start();

session_unset();  
session_destroy();  
echo "logout the page";
// Delete the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/'); // Set cookie expiration time to past to delete it
}

// Optionally, delete any other cookies you set for the user
// Example: if you have a 'login_id' cookie
if (isset($_COOKIE['login_id'])) {
    setcookie('login_id', '', time() - 3600, '/');  // Expire the cookie
}

// Redirect the user or show a logout message
//header('Location: welcome.php');  // Redirect to login page or another page
exit();
?>
